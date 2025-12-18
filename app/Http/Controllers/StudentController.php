<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentController extends Controller
{
    public function index()
    {
        $attendances = Attendance::where('user_id', auth()->id())
            ->where('date', today())
            ->with('classModel')
            ->get();

        $recentAttendances = Attendance::where('user_id', auth()->id())
            ->with('classModel')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('attendances', 'recentAttendances'));
    }

    public function attendanceHistory()
    {
        $attendances = Attendance::where('user_id', auth()->id())
            ->with('classModel')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('student.attendance-history', compact('attendances'));
    }

    public function showQrCode()
    {
        $user = auth()->user();
        $qrCode = base64_encode(QrCode::format('png')
            ->size(200)
            ->generate(route('user.qr.show', ['id' => $user->id])));

        return view('student.qr-code', compact('qrCode', 'user'));
    }

    public function showLeaveRequestForm()
    {
        return view('student.leave-request-form');
    }

    public function submitLeaveRequest(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'attachment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // Store the attachment
        $attachmentPath = $request->file('attachment')->store('leave-requests', 'public');

        \App\Models\LeaveRequest::create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'attachment' => $attachmentPath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending', // Default to pending
        ]);

        return redirect()->route('student.leave.requests')->with('success', 'Permohonan izin berhasil diajukan.');
    }

    public function showLeaveRequests()
    {
        $leaveRequests = \App\Models\LeaveRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.leave-requests', compact('leaveRequests'));
    }
}
