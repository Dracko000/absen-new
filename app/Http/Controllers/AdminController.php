<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminController extends Controller
{
    public function index()
    {
        $classes = ClassModel::where('teacher_id', auth()->id())->get();
        $schedules = Schedule::whereIn('class_model_id', $classes->pluck('id'))->get();
        $todayAttendance = Attendance::where('date', today())
            ->whereIn('class_model_id', $classes->pluck('id'))
            ->count();

        return view('admin.dashboard', compact('classes', 'schedules', 'todayAttendance'));
    }

    public function manageClasses()
    {
        $classes = ClassModel::where('teacher_id', auth()->id())->with(['schedules'])->get();
        return view('admin.classes', compact('classes'));
    }

    public function createClass(Request $request)
    {
        // Admin tidak diizinkan untuk membuat kelas
        abort(403, 'You are not authorized to create classes. Only Superadmin can create classes.');
    }

    public function manageSchedules()
    {
        $classes = ClassModel::where('teacher_id', auth()->id())->get();
        $schedules = Schedule::with('classModel')->whereHas('classModel', function($q) {
            $q->where('teacher_id', auth()->id());
        })->get();

        return view('admin.schedules', compact('classes', 'schedules'));
    }

    public function createSchedule(Request $request)
    {
        $request->validate([
            'class_model_id' => 'required|exists:class_models,id',
            'subject' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'day_of_week' => 'required|integer|min:1|max:7',
        ]);

        Schedule::create([
            'class_model_id' => $request->class_model_id,
            'subject' => $request->subject,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'day_of_week' => $request->day_of_week,
        ]);

        return redirect()->route('admin.schedules')->with('success', 'Schedule created successfully.');
    }

    public function takeAttendance($classId)
    {
        $class = ClassModel::findOrFail($classId);
        $students = User::role('User')->get(); // All students
        $admins = User::role('Admin')->get(); // All admins

        // Combine students and admins, but make sure they can be distinguished in the view
        return view('admin.take-attendance', compact('class', 'students', 'admins'));
    }

    public function classAttendance($classId)
    {
        $class = ClassModel::where('teacher_id', auth()->id())->with('schedules')->findOrFail($classId);
        $attendances = Attendance::where('class_model_id', $classId)
            ->where('date', today())
            ->with(['user', 'classModel'])
            ->get();

        $date = today()->toDateString();
        return view('admin.class-attendance', compact('class', 'attendances', 'date'));
    }

    public function classMembers($classId)
    {
        $class = ClassModel::with('students')->findOrFail($classId);
        $students = $class->students;
        return view('admin.class-members', compact('class', 'students'));
    }

    public function exportClass($classId)
    {
        $class = ClassModel::where('teacher_id', auth()->id())->findOrFail($classId);
        $attendances = Attendance::where('class_model_id', $classId)
            ->with(['user', 'classModel'])
            ->orderBy('date', 'desc')
            ->get();

        $export = new \App\Exports\ClassAttendanceExport(
            $attendances,
            $class->name
        );

        return Excel::download(
            $export,
            "absensi_kelas_{$class->name}_" . now()->format('Y-m-d') . ".xlsx"
        );
    }

    public function classAttendanceByDate($classId, $date = null)
    {
        $date = $date ?? today()->toDateString();
        $class = ClassModel::where('teacher_id', auth()->id())->with('schedules')->findOrFail($classId);
        $attendances = Attendance::where('class_model_id', $classId)
            ->where('date', $date)
            ->with(['user', 'classModel'])
            ->get();

        return view('admin.class-attendance', compact('class', 'attendances', 'date'));
    }

    public function manageLeaveRequests()
    {
        $leaveRequests = \App\Models\LeaveRequest::with('user', 'approvedBy')
            ->whereHas('user', function($q) {
                $q->whereHas('attendances', function($query) {
                    $query->whereIn('class_model_id',
                        \App\Models\ClassModel::where('teacher_id', auth()->id())->pluck('id')->toArray()
                    );
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.leave-requests', compact('leaveRequests'));
    }

    public function showLeaveRequest($id)
    {
        $leaveRequest = \App\Models\LeaveRequest::with('user', 'approvedBy')->findOrFail($id);

        // Check if the admin has access to this leave request (student should be in one of admin's classes)
        $isAdminOfClass = $leaveRequest->user->attendances()
            ->whereIn('class_model_id',
                \App\Models\ClassModel::where('teacher_id', auth()->id())->pluck('id')->toArray()
            )
            ->exists();

        if (!$isAdminOfClass) {
            abort(403);
        }

        return view('admin.show-leave-request', compact('leaveRequest'));
    }

    public function approveLeaveRequest(Request $request, $id)
    {
        $leaveRequest = \App\Models\LeaveRequest::findOrFail($id);

        // Check if admin has access to this leave request
        $isAdminOfClass = $leaveRequest->user->attendances()
            ->whereIn('class_model_id',
                \App\Models\ClassModel::where('teacher_id', auth()->id())->pluck('id')->toArray()
            )
            ->exists();

        if (!$isAdminOfClass) {
            abort(403);
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->notes,
        ]);

        // Update attendance records for the leave period
        $currentDate = \Carbon\Carbon::parse($leaveRequest->start_date);
        $endDate = \Carbon\Carbon::parse($leaveRequest->end_date);

        // Get all classes the student is enrolled in for the leave period
        $classes = \App\Models\Attendance::where('user_id', $leaveRequest->user_id)
            ->whereBetween('date', [$leaveRequest->start_date, $leaveRequest->end_date])
            ->pluck('class_model_id')
            ->unique();

        // Create or update attendance records for each day in the leave period
        while ($currentDate->lte($endDate)) {
            foreach ($classes as $classId) {
                // Check if attendance already exists for this date and class
                $attendance = \App\Models\Attendance::firstOrCreate([
                    'user_id' => $leaveRequest->user_id,
                    'date' => $currentDate->toDateString(),
                    'class_model_id' => $classId,
                ], [
                    'status' => 'Tidak Hadir',  // Default status
                    'note' => 'Izin disetujui dari ' . $leaveRequest->start_date . ' sampai ' . $leaveRequest->end_date,
                ]);

                // Update the attendance status to reflect approved leave
                $attendance->update([
                    'status' => 'Izin', // Mark as approved leave
                    'note' => ($attendance->note ? $attendance->note . ' | ' : '') . 'Izin disetujui (ID: ' . $leaveRequest->id . ')',
                ]);
            }
            $currentDate->addDay();
        }

        return redirect()->route('admin.leave.requests')->with('success', 'Permohonan izin berhasil disetujui.');
    }

    public function rejectLeaveRequest(Request $request, $id)
    {
        $leaveRequest = \App\Models\LeaveRequest::findOrFail($id);

        // Check if admin has access to this leave request
        $isAdminOfClass = $leaveRequest->user->attendances()
            ->whereIn('class_model_id',
                \App\Models\ClassModel::where('teacher_id', auth()->id())->pluck('id')->toArray()
            )
            ->exists();

        if (!$isAdminOfClass) {
            abort(403);
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->notes,
        ]);

        // Update attendance records for the leave period
        $currentDate = \Carbon\Carbon::parse($leaveRequest->start_date);
        $endDate = \Carbon\Carbon::parse($leaveRequest->end_date);

        // Get all classes the student is enrolled in for the leave period
        $classes = \App\Models\Attendance::where('user_id', $leaveRequest->user_id)
            ->whereBetween('date', [$leaveRequest->start_date, $leaveRequest->end_date])
            ->pluck('class_model_id')
            ->unique();

        // Create or update attendance records for each day in the leave period
        while ($currentDate->lte($endDate)) {
            foreach ($classes as $classId) {
                // Check if attendance already exists for this date and class
                $attendance = \App\Models\Attendance::firstOrCreate([
                    'user_id' => $leaveRequest->user_id,
                    'date' => $currentDate->toDateString(),
                    'class_model_id' => $classId,
                ], [
                    'status' => 'Tidak Hadir',  // Default status
                    'note' => 'Izin ditolak dari ' . $leaveRequest->start_date . ' sampai ' . $leaveRequest->end_date,
                ]);

                // Update the attendance status for rejected leave
                $attendance->update([
                    'status' => 'Tidak Hadir', // Mark as absent for rejected leave
                    'note' => ($attendance->note ? $attendance->note . ' | ' : '') . 'Izin ditolak (ID: ' . $leaveRequest->id . ')',
                ]);
            }
            $currentDate->addDay();
        }

        return redirect()->route('admin.leave.requests')->with('success', 'Permohonan izin berhasil ditolak.');
    }

    public function showQrCode()
    {
        $user = auth()->user();

        // Check if user has NIS or NIP/NUPTK
        if (empty($user->nis) && empty($user->nip_nuptk)) {
            // Show the view with an error message instead of redirecting
            return view('admin.qr-code', [
                'user' => $user,
                'qrCode' => null,
                'error' => 'Identification number not found. Please contact admin to set your NIS or NIP/NUPTK.'
            ]);
        }

        // For teachers, use nip_nuptk if available, otherwise fallback to nis
        $identifier = !empty($user->nip_nuptk) ? $user->nip_nuptk : $user->nis;
        $qrCode = base64_encode(QrCode::format('png')
            ->size(200)
            ->generate($identifier));

        return view('admin.qr-code', compact('qrCode', 'user'));
    }
}
