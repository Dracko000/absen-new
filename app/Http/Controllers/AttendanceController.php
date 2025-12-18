<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\ClassModel;
use App\Http\Resources\AttendanceResource;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function recordAttendance(Request $request)
    {
        // Manual validation to provide better error handling
        $userId = $request->input('user_id');
        $classModelId = $request->input('class_model_id');
        $date = $request->input('date');
        $timeIn = $request->input('time_in');
        $status = $request->input('status');
        $note = $request->input('note');

        if (empty($userId) || !is_numeric($userId)) {
            return response()->json([
                'message' => 'User ID diperlukan dan harus berupa angka',
            ], 400);
        }

        if (empty($classModelId) || !is_numeric($classModelId)) {
            return response()->json([
                'message' => 'ID kelas diperlukan dan harus berupa angka',
            ], 400);
        }

        if (empty($date)) {
            return response()->json([
                'message' => 'Tanggal diperlukan',
            ], 400);
        }

        if (empty($timeIn)) {
            return response()->json([
                'message' => 'Waktu masuk diperlukan',
            ], 400);
        }

        if (empty($status) || !in_array($status, ['Hadir', 'Terlambat', 'Tidak Hadir'])) {
            return response()->json([
                'message' => 'Status harus salah satu dari: Hadir, Terlambat, Tidak Hadir',
            ], 400);
        }

        // Check if user exists
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'Pengguna tidak ditemukan',
            ], 400);
        }

        // Check if class exists
        $class = ClassModel::find($classModelId);
        if (!$class) {
            return response()->json([
                'message' => 'Kelas tidak ditemukan',
            ], 400);
        }

        // Check if the authenticated user is the teacher assigned to this class
        if ($class->teacher_id !== auth()->id()) {
            return response()->json([
                'message' => 'Anda tidak diizinkan mengambil absensi untuk kelas ini',
            ], 403);
        }

        // Check if the user is already attending a different class on the same day
        $existingAttendanceDifferentClass = Attendance::where('user_id', $userId)
            ->where('date', $date)
            ->where('class_model_id', '!=', $classModelId)
            ->first();

        if ($existingAttendanceDifferentClass) {
            return response()->json([
                'message' => 'Siswa tidak dapat menghadiri kelas lain pada hari yang sama',
            ], 400);
        }

        // Check if attendance already exists for this user and date for the same class
        $existingAttendance = Attendance::where('user_id', $userId)
            ->where('date', $date)
            ->where('class_model_id', $classModelId)
            ->first();

        if ($existingAttendance) {
            // Update existing attendance
            $existingAttendance->update([
                'status' => $status,
                'note' => $note,
                'time_in' => $timeIn, // Update time_in when manually recorded
            ]);

            return response()->json([
                'duplicate' => true,
                'message' => 'Absensi untuk ' . $existingAttendance->user->name . ' sudah direkam sebelumnya, data diperbarui',
                'data' => new AttendanceResource($existingAttendance)
            ], 200);
        }

        // Determine if student is late based on class entry time if status is 'Hadir'
        if ($status === 'Hadir') {
            $class = \App\Models\ClassModel::find($classModelId);
            $currentTime = \Carbon\Carbon::parse($timeIn);
            $entryTime = $class ? $class->entry_time : null;

            if ($entryTime) {
                $classEntryTime = \Carbon\Carbon::parse($entryTime);

                // Compare only the time parts
                if ($currentTime->gt($classEntryTime)) {
                    $status = 'Terlambat'; // Change to late if scanned after entry time
                    $note = ($note ? $note . ' ' : '') . '(Terlambat)';
                }
            }
        }

        $attendance = Attendance::create([
            'user_id' => $userId,
            'class_model_id' => $classModelId,
            'date' => $date,
            'time_in' => $timeIn,
            'status' => $status,
            'note' => $note ?: null,
        ]);

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'data' => new AttendanceResource($attendance)
        ], 200);
    }

    public function scanQrCode(Request $request)
    {
        // Manual validation to provide better error handling
        $qrData = $request->input('qr_data');
        $classModelId = $request->input('class_model_id');

        if (empty($qrData)) {
            return response()->json([
                'success' => false,
                'message' => 'QR data diperlukan',
                'data' => null
            ], 400);
        }

        if (empty($classModelId) || !is_numeric($classModelId)) {
            return response()->json([
                'success' => false,
                'message' => 'ID kelas diperlukan dan harus berupa angka',
                'data' => null
            ], 400);
        }

        // Check if class exists
        $class = ClassModel::find($classModelId);
        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan',
                'data' => null
            ], 400);
        }

        // Check if the authenticated user is the teacher assigned to this class
        if ($class->teacher_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak diizinkan mengambil absensi untuk kelas ini',
                'data' => null
            ], 403);
        }

        // Extract user ID from QR code URL
        $pattern = '/\/qr\/show\/(\d+)/';
        preg_match($pattern, $qrData, $matches);

        if (empty($matches[1])) {
            return response()->json([
                'success' => false,
                'message' => 'Kode QR tidak valid',
                'data' => null
            ], 400);
        }

        $userId = $matches[1];
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Check if the user is already attending a different class on the same day
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', today())
            ->where('class_model_id', '!=', $classModelId)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak dapat menghadiri kelas lain pada hari yang sama',
                'data' => null
            ], 400);
        }

        // Check if attendance already exists for this user and date for the same class
        $existingAttendanceForClass = Attendance::where('user_id', $user->id)
            ->where('date', today())
            ->where('class_model_id', $classModelId)
            ->first();

        if ($existingAttendanceForClass) {
            return response()->json([
                'success' => false, // Now returning false for duplicate scan
                'duplicate' => true, // Adding flag to specifically identify duplicate scans
                'message' => 'Absensi sudah direkam sebelumnya untuk ' . $user->name . ' pada kelas ini',
                'data' => new AttendanceResource($existingAttendanceForClass)
            ], 200); // Still return 200 as it's not an error, just a duplicate
        }

        // Determine if student is late based on class entry time
        $class = \App\Models\ClassModel::find($classModelId);
        $currentTime = now();
        $entryTime = $class ? $class->entry_time : null;

        $status = 'Hadir';
        $note = 'Dipindai melalui Kode QR';

        if ($entryTime) {
            $classEntryTime = \Carbon\Carbon::parse($entryTime);
            $currentDateTime = $currentTime->copy();
            $currentDateTime->setTime($currentDateTime->hour, $currentDateTime->minute, $currentDateTime->second);

            // Compare only the time parts
            if ($currentDateTime->gt($classEntryTime)) {
                $status = 'Terlambat'; // Mark as late
                $note .= ' (Terlambat)';
            }
        }

        // Create new attendance record
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'class_model_id' => $classModelId,
            'date' => today(),
            'time_in' => now()->toTimeString(),
            'status' => $status,
            'note' => $note,
        ]);

        $statusMessage = $status == 'Terlambat' ? 'Terlambat' : 'berhasil';
        return response()->json([
            'success' => true,
            'message' => "Scan {$statusMessage}! Absensi direkam untuk " . $user->name . " (Status: {$status})",
            'data' => new AttendanceResource($attendance)
        ], 200);
    }

    public function scanQrCodeForCheckout(Request $request)
    {
        // Manual validation to provide better error handling
        $qrData = $request->input('qr_data');
        $classModelId = $request->input('class_model_id');

        if (empty($qrData)) {
            return response()->json([
                'success' => false,
                'message' => 'QR data diperlukan',
                'data' => null
            ], 400);
        }

        if (empty($classModelId) || !is_numeric($classModelId)) {
            return response()->json([
                'success' => false,
                'message' => 'ID kelas diperlukan dan harus berupa angka',
                'data' => null
            ], 400);
        }

        // Check if class exists
        $class = ClassModel::find($classModelId);
        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan',
                'data' => null
            ], 400);
        }

        // Check if the authenticated user is the teacher assigned to this class
        if ($class->teacher_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak diizinkan mengambil absensi untuk kelas ini',
                'data' => null
            ], 403);
        }

        // Extract user ID from QR code URL
        $pattern = '/\/qr\/show\/(\d+)/';
        preg_match($pattern, $qrData, $matches);

        if (empty($matches[1])) {
            return response()->json([
                'success' => false,
                'message' => 'Kode QR tidak valid',
                'data' => null
            ], 400);
        }

        $userId = $matches[1];
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Check if attendance already exists for this user and date for the same class
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', today())
            ->where('class_model_id', $classModelId)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi masuk tidak ditemukan untuk ' . $user->name . ' pada kelas ini',
                'data' => null
            ], 400);
        }

        // Check if already checked out
        if ($attendance->time_out) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa sudah check-out sebelumnya pada kelas ini',
                'data' => new AttendanceResource($attendance)
            ], 200);
        }

        // Check if current time is before class exit time
        if ($class->exit_time) {
            $currentTime = now();
            $classExitTime = \Carbon\Carbon::parse($class->exit_time);

            // Compare only the time parts (hours and minutes)
            if ($currentTime->lt($classExitTime)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jam pulang belum tiba. Jam pulang kelas ini adalah pukul ' . $class->exit_time . '. Silakan tunggu hingga jam pulang.',
                    'data' => new AttendanceResource($attendance)
                ], 400);
            }
        }

        // Update the time_out field
        $attendance->update([
            'time_out' => now()->toTimeString(),
            'note' => $attendance->note . ' | Check-out: ' . now()->format('H:i'),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Check-out berhasil! " . $user->name . " telah check-out dari kelas ini",
            'data' => new AttendanceResource($attendance)
        ], 200);
    }

    public function getDailyAttendance($date = null)
    {
        $date = $date ?? today()->toDateString();
        $attendances = Attendance::where('date', $date)
            ->with(['user', 'classModel'])
            ->get();

        return response()->json([
            'date' => $date,
            'attendances' => AttendanceResource::collection($attendances)
        ], 200);
    }

    public function getWeeklyAttendance($year, $week)
    {
        $attendances = Attendance::whereYear('date', $year)
            ->whereWeek('date', $week)
            ->with(['user', 'classModel'])
            ->get();

        return response()->json([
            'attendances' => AttendanceResource::collection($attendances)
        ], 200);
    }

    public function getMonthlyAttendance($year, $month)
    {
        $attendances = Attendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with(['user', 'classModel'])
            ->get();

        return response()->json([
            'attendances' => AttendanceResource::collection($attendances)
        ], 200);
    }

    public function getAttendanceByUser($userId)
    {
        $attendances = Attendance::where('user_id', $userId)
            ->with(['user', 'classModel'])
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'attendances' => AttendanceResource::collection($attendances)
        ], 200);
    }

    public function getAttendanceByClass($classId, $date = null)
    {
        $date = $date ?? today()->toDateString();
        $attendances = Attendance::where('class_model_id', $classId)
            ->where('date', $date)
            ->with(['user', 'classModel'])
            ->get();

        return response()->json([
            'date' => $date,
            'attendances' => AttendanceResource::collection($attendances)
        ], 200);
    }

    public function markAttendanceManual(Request $request)
    {
        // Manual validation to provide better error handling
        $userId = $request->input('user_id');
        $classModelId = $request->input('class_model_id');
        $status = $request->input('status');
        $note = $request->input('note');

        if (empty($userId) || !is_numeric($userId)) {
            return response()->json([
                'message' => 'User ID diperlukan dan harus berupa angka',
            ], 400);
        }

        if (empty($classModelId) || !is_numeric($classModelId)) {
            return response()->json([
                'message' => 'ID kelas diperlukan dan harus berupa angka',
            ], 400);
        }

        if (empty($status) || !in_array($status, ['Hadir', 'Terlambat', 'Tidak Hadir'])) {
            return response()->json([
                'message' => 'Status harus salah satu dari: Hadir, Terlambat, Tidak Hadir',
            ], 400);
        }

        // Check if user exists
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'Pengguna tidak ditemukan',
            ], 400);
        }

        // Check if class exists
        $class = ClassModel::find($classModelId);
        if (!$class) {
            return response()->json([
                'message' => 'Kelas tidak ditemukan',
            ], 400);
        }

        // Check if the authenticated user is the teacher assigned to this class
        if ($class->teacher_id !== auth()->id()) {
            return response()->json([
                'message' => 'Anda tidak diizinkan mengambil absensi untuk kelas ini',
            ], 403);
        }

        // Check if the user is already attending a different class on the same day
        $existingAttendanceDifferentClass = Attendance::where('user_id', $userId)
            ->where('date', today())
            ->where('class_model_id', '!=', $classModelId)
            ->first();

        if ($existingAttendanceDifferentClass) {
            return response()->json([
                'message' => 'Siswa tidak dapat menghadiri kelas lain pada hari yang sama',
            ], 400);
        }

        // Check if attendance already exists for this user and date for the same class
        $existingAttendance = Attendance::where('user_id', $userId)
            ->where('date', today())
            ->where('class_model_id', $classModelId)
            ->first();

        if ($existingAttendance) {
            // Update existing attendance
            $existingAttendance->update([
                'status' => $status,
                'note' => $note,
                'time_in' => $existingAttendance->time_in ?? now()->toTimeString(), // Keep existing time_in if exists
            ]);

            return response()->json([
                'duplicate' => true,
                'message' => 'Absensi untuk ' . $existingAttendance->user->name . ' sudah direkam sebelumnya, data diperbarui',
                'data' => new AttendanceResource($existingAttendance)
            ], 200);
        }

        // Determine if student is late based on class entry time if status is 'Hadir'
        if ($status === 'Hadir') {
            $class = \App\Models\ClassModel::find($classModelId);
            $currentTime = now();
            $entryTime = $class ? $class->entry_time : null;

            if ($entryTime) {
                $classEntryTime = \Carbon\Carbon::parse($entryTime);
                $currentDateTime = $currentTime->copy();
                $currentDateTime->setTime($currentDateTime->hour, $currentDateTime->minute, $currentDateTime->second);

                // Compare only the time parts
                if ($currentDateTime->gt($classEntryTime)) {
                    $status = 'Terlambat'; // Change to late if scanned after entry time
                    $note = ($note ? $note . ' ' : '') . '(Terlambat)';
                }
            }
        }

        // Create new attendance record
        $attendance = Attendance::create([
            'user_id' => $userId,
            'class_model_id' => $classModelId,
            'date' => today(),
            'time_in' => now()->toTimeString(),
            'status' => $status,
            'note' => $note,
        ]);

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'data' => new AttendanceResource($attendance)
        ], 200);
    }

    public function updateAttendanceStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Terlambat,Tidak Hadir,Izin',
            'note' => 'nullable|string',
        ]);

        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'status' => $request->status,
            'note' => $request->note,
        ]);

        return response()->json([
            'message' => 'Attendance status updated successfully',
            'data' => new AttendanceResource($attendance)
        ], 200);
    }

    public function deleteAttendance($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'message' => 'Attendance record deleted successfully'
        ], 200);
    }
}
