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

        return view('student.dashboard', compact('attendances'));
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
}
