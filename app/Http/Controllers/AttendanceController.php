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
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'class_model_id' => 'required|exists:class_models,id',
            'date' => 'required|date',
            'time_in' => 'required|date_format:H:i',
            'status' => 'required|in:Hadir,Terlambat,Tidak Hadir',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $attendance = Attendance::create([
            'user_id' => $request->user_id,
            'class_model_id' => $request->class_model_id,
            'date' => $request->date,
            'time_in' => $request->time_in,
            'status' => $request->status,
            'note' => $request->note ?: null,
        ]);

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'data' => new AttendanceResource($attendance)
        ], 200);
    }

    public function scanQrCode(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
            'class_model_id' => 'required|exists:class_models,id', // Class where attendance is being taken
        ]);

        // Extract user ID from QR code URL
        $pattern = '/\/qr\/show\/(\d+)/';
        preg_match($pattern, $request->qr_data, $matches);

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

        // Check if attendance already exists for this user and date
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', today())
            ->where('class_model_id', $request->class_model_id)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => true,
                'message' => 'Absensi sudah direkam sebelumnya untuk ' . $user->name,
                'data' => new AttendanceResource($existingAttendance)
            ], 200);
        }

        // Create new attendance record
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'class_model_id' => $request->class_model_id,
            'date' => today(),
            'time_in' => now()->toTimeString(),
            'status' => 'Hadir', // Default to present when scanned
            'note' => 'Dipindai melalui Kode QR',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Scan berhasil! Absensi direkam untuk ' . $user->name,
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
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'class_model_id' => 'required|exists:class_models,id',
            'status' => 'required|in:Hadir,Terlambat,Tidak Hadir',
            'note' => 'nullable|string',
        ]);

        // Check if attendance already exists for this user and date
        $existingAttendance = Attendance::where('user_id', $request->user_id)
            ->where('date', today())
            ->where('class_model_id', $request->class_model_id)
            ->first();

        if ($existingAttendance) {
            // Update existing attendance
            $existingAttendance->update([
                'status' => $request->status,
                'note' => $request->note,
                'time_in' => $existingAttendance->time_in ?? now()->toTimeString(), // Keep existing time_in if exists
            ]);

            return response()->json([
                'message' => 'Attendance updated successfully',
                'data' => new AttendanceResource($existingAttendance)
            ], 200);
        }

        // Create new attendance record
        $attendance = Attendance::create([
            'user_id' => $request->user_id,
            'class_model_id' => $request->class_model_id,
            'date' => today(),
            'time_in' => now()->toTimeString(),
            'status' => $request->status,
            'note' => $request->note,
        ]);

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'data' => new AttendanceResource($attendance)
        ], 200);
    }

    public function updateAttendanceStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Terlambat,Tidak Hadir',
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
