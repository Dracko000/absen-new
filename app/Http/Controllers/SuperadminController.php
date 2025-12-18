<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\Attendance;
use App\Exports\ClassMembersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SuperadminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalTeachers = User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->count();
        $totalStudents = User::whereHas('roles', function($q) {
            $q->where('name', 'User');
        })->count();
        $totalClasses = ClassModel::count();
        $todayAttendance = Attendance::whereDate('created_at', today())->count();

        // Get all attendance data for the dashboard statistics
        $attendances = Attendance::with(['user', 'classModel'])->get();

        // Get recent attendance for the dashboard table
        $recentAttendances = Attendance::with(['user', 'classModel'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact('totalUsers', 'totalTeachers', 'totalStudents', 'totalClasses', 'todayAttendance', 'attendances', 'recentAttendances'));
    }

    public function manageUsers(Request $request)
    {
        $query = User::with('roles');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Class filter functionality
        if ($request->filled('class_id')) {
            $classId = $request->input('class_id');
            $query->whereHas('attendances', function($q) use ($classId) {
                $q->where('class_model_id', $classId);
            });
        }

        $users = $query->get();

        // Get class information for each student by their latest attendance
        foreach ($users as $user) {
            if ($user->hasRole('User')) { // Only for students
                $latestAttendance = \App\Models\Attendance::where('user_id', $user->id)
                    ->latest('date')
                    ->first();
                $user->setAttribute('class', $latestAttendance ? $latestAttendance->classModel : null);
            }
        }

        $roles = Role::all();
        $classes = \App\Models\ClassModel::all(); // Get all classes for student assignment
        return view('superadmin.users', compact('users', 'roles', 'classes'));
    }

    public function createTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('Admin');

        return redirect()->route('superadmin.users')->with('success', 'Teacher created successfully.');
    }

    public function createStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:users,nis',
            'class_id' => 'nullable|exists:class_models,id', // Optional class assignment
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->nis . '@student.example.com', // Generate email from NIS
            'nis' => $request->nis,
            'password' => Hash::make($request->nis), // Use NIS as password
        ]);

        $user->assignRole('User');

        // If a class is selected, create an initial attendance record to associate the student with the class
        if ($request->filled('class_id')) {
            \App\Models\Attendance::create([
                'user_id' => $user->id,
                'class_model_id' => $request->class_id,
                'date' => now()->toDateString(),
                'time_in' => now()->toTimeString(),
                'status' => 'Tidak Hadir', // Default status when first assigned
                'note' => 'Siswa didaftarkan ke kelas',
            ]);
        }

        return redirect()->route('superadmin.users')->with('success', 'Student created successfully.');
    }

    public function showEditStudentClassForm($userId)
    {
        $user = User::findOrFail($userId);
        $classes = \App\Models\ClassModel::all();

        // Get the current class of the student (if any) from their latest attendance record
        $currentAttendance = \App\Models\Attendance::where('user_id', $userId)
            ->latest('date')
            ->first();

        $currentClassId = $currentAttendance ? $currentAttendance->class_model_id : null;

        return view('superadmin.edit-student-class', compact('user', 'classes', 'currentClassId'));
    }

    public function updateStudentClass(Request $request, $userId)
    {
        $request->validate([
            'class_id' => 'required|exists:class_models,id',
        ]);

        $user = User::findOrFail($userId);

        // Create or update attendance record to associate the student with the new class
        // Remove any existing attendance records for other classes on today's date
        \App\Models\Attendance::where('user_id', $user->id)
            ->where('date', today())
            ->delete();

        // Create new attendance record for the new class
        \App\Models\Attendance::create([
            'user_id' => $user->id,
            'class_model_id' => $request->class_id,
            'date' => today(),
            'time_in' => now()->toTimeString(),
            'status' => 'Tidak Hadir', // Default status when first assigned
            'note' => 'Kelas diperbarui oleh admin',
        ]);

        return redirect()->route('superadmin.users')->with('success', 'Kelas siswa berhasil diperbarui.');
    }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $roles = \Spatie\Permission\Models\Role::all();
        $classes = \App\Models\ClassModel::all();

        return view('superadmin.edit-user', compact('user', 'roles', 'classes'));
    }

    public function updateUser(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'role' => 'required|exists:roles,name',
        ];

        // Add password validation only if password is being changed
        if ($request->filled('password')) {
            $validationRules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($validationRules);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Sync roles
        $user->syncRoles([$request->role]);

        return redirect()->route('superadmin.users')->with('success', 'User updated successfully.');
    }

    public function resetUserPasswordToNis(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Check if the user has an NIS value
        if (empty($user->nis)) {
            return redirect()->back()->with('error', 'User does not have an NIS value to reset to.');
        }

        // Reset password to NIS value
        $user->update([
            'password' => Hash::make($user->nis),
        ]);

        return redirect()->route('superadmin.users')->with('success', 'Password has been reset to NIS successfully.');
    }

    public function manageLeaveRequests()
    {
        $leaveRequests = \App\Models\LeaveRequest::with('user', 'approvedBy')->orderBy('created_at', 'desc')->get();
        return view('superadmin.leave-requests', compact('leaveRequests'));
    }

    public function showLeaveRequest($id)
    {
        $leaveRequest = \App\Models\LeaveRequest::with('user', 'approvedBy')->findOrFail($id);
        return view('superadmin.show-leave-request', compact('leaveRequest'));
    }

    public function approveLeaveRequest(Request $request, $id)
    {
        $leaveRequest = \App\Models\LeaveRequest::findOrFail($id);

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

        return redirect()->route('superadmin.leave.requests')->with('success', 'Permohonan izin berhasil disetujui.');
    }

    public function rejectLeaveRequest(Request $request, $id)
    {
        $leaveRequest = \App\Models\LeaveRequest::findOrFail($id);

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

        return redirect()->route('superadmin.leave.requests')->with('success', 'Permohonan izin berhasil ditolak.');
    }

    public function manageClasses()
    {
        $classes = ClassModel::with('teacher')->get();
        $teachers = User::role('Admin')->get();
        return view('superadmin.classes', compact('classes', 'teachers'));
    }

    public function createClass(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:class_models,name',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
            'entry_time' => 'required|date_format:H:i',
            'exit_time' => 'required|date_format:H:i|after:entry_time',
        ]);

        $class = ClassModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $request->teacher_id,
            'entry_time' => $request->entry_time,
            'exit_time' => $request->exit_time,
        ]);

        return redirect()->route('superadmin.classes')->with('success', 'Class created successfully.');
    }

    public function attendanceReport()
    {
        $attendances = Attendance::with(['user', 'classModel'])->latest()->paginate(10);
        return view('superadmin.attendance-report', compact('attendances'));
    }

    public function classMembers($classId)
    {
        $class = ClassModel::with('students')->findOrFail($classId);
        $students = $class->students;
        return view('superadmin.class-members', compact('class', 'students'));
    }

    public function exportClassMembers($classId)
    {
        $class = ClassModel::with('students')->findOrFail($classId);
        $students = $class->students;

        return Excel::download(
            new ClassMembersExport($students, $class->name),
            "anggota_kelas_{$class->name}.xlsx"
        );
    }

    public function importStudents(Request $request, $classId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $class = ClassModel::findOrFail($classId);

        try {
            // Import students with the specific class ID
            Excel::import(new \App\Imports\StudentsImport($classId), $request->file('file'));

            return redirect()->back()->with('success', 'Students imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing students: ' . $e->getMessage());
        }
    }

    public function showImportStudentsForm($classId)
    {
        $class = ClassModel::findOrFail($classId);
        return view('superadmin.import-students', compact('class'));
    }

    public function showEditClassForm($classId)
    {
        $class = ClassModel::with('teacher')->findOrFail($classId);
        $teachers = User::role('Admin')->get();
        return view('superadmin.edit-class', compact('class', 'teachers'));
    }

    public function updateClass(Request $request, $classId)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:class_models,name,' . $classId,
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
            'entry_time' => 'required|date_format:H:i',
            'exit_time' => 'required|date_format:H:i|after:entry_time',
        ]);

        $class = ClassModel::findOrFail($classId);
        $class->update([
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $request->teacher_id,
            'entry_time' => $request->entry_time,
            'exit_time' => $request->exit_time,
        ]);

        return redirect()->route('superadmin.classes')->with('success', 'Class updated successfully.');
    }

    public function deleteClass($classId)
    {
        $class = ClassModel::with(['attendances', 'schedules'])->findOrFail($classId);

        // Delete related attendances and schedules before deleting the class
        $class->attendances()->delete();
        $class->schedules()->delete();

        $class->delete();

        return redirect()->route('superadmin.classes')->with('success', 'Class deleted successfully.');
    }

    public function takeAttendance()
    {
        $classes = ClassModel::all(); // Get all classes for the dropdown
        $users = User::all(); // Get all users (students and admins)
        return view('superadmin.take-attendance', compact('classes', 'users'));
    }

    public function showAdminQrCode($userId)
    {
        $user = User::findOrFail($userId);

        // Verify that the user is an admin
        if (!$user->hasRole('Admin')) {
            abort(404);
        }

        $qrCode = base64_encode(QrCode::format('png')
            ->size(200)
            ->generate(route('user.qr.show', ['id' => $user->id])));

        return view('superadmin.admin-qr-code', compact('qrCode', 'user'));
    }
}
