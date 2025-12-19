<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function exportDaily(Request $request, $date = null)
    {
        $date = $date ?? today()->toDateString();

        $attendances = Attendance::where('date', $date)
            ->with(['user', 'classModel'])
            ->get();

        return Excel::download(
            new AttendanceExport($attendances, 'daily'),
            "attendance_daily_{$date}.xlsx"
        );
    }

    public function exportWeekly(Request $request, $year, $week)
    {
        $attendances = Attendance::whereYear('date', $year)
            ->whereWeek('date', $week)
            ->with(['user', 'classModel'])
            ->get();

        return Excel::download(
            new AttendanceExport($attendances, 'weekly'),
            "attendance_weekly_{$year}_{$week}.xlsx"
        );
    }

    public function exportMonthly(Request $request, $year, $month)
    {
        $attendances = Attendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with(['user', 'classModel'])
            ->get();

        $monthName = date('F', mktime(0, 0, 0, $month, 10)); // Get month name

        return Excel::download(
            new AttendanceExport($attendances, 'monthly'),
            "attendance_monthly_{$year}_{$monthName}.xlsx"
        );
    }

    public function exportByClass($classId, $date = null)
    {
        $date = $date ?? today()->toDateString();

        $attendances = Attendance::where('class_model_id', $classId)
            ->where('date', $date)
            ->with(['user', 'classModel'])
            ->get();

        return Excel::download(
            new AttendanceExport($attendances, 'class'),
            "attendance_class_{$classId}_{$date}.xlsx"
        );
    }

    public function exportByUser($userId, $dateFrom = null, $dateTo = null)
    {
        $query = Attendance::where('user_id', $userId)->with(['user', 'classModel']);

        if ($dateFrom && $dateTo) {
            $query->whereBetween('date', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        }

        $attendances = $query->get();

        $fileName = $dateFrom && $dateTo
            ? "attendance_user_{$userId}_{$dateFrom}_to_{$dateTo}.xlsx"
            : ($dateFrom ? "attendance_user_{$userId}_from_{$dateFrom}.xlsx" : "attendance_user_{$userId}.xlsx");

        return Excel::download(
            new AttendanceExport($attendances, 'user'),
            $fileName
        );
    }

    // CSV export methods
    public function exportDailyCSV(Request $request, $date = null)
    {
        $date = $date ?? today()->toDateString();

        $attendances = Attendance::where('date', $date)
            ->with(['user', 'classModel'])
            ->get();

        return Excel::download(
            new AttendanceExport($attendances, 'daily'),
            "attendance_daily_{$date}.csv"
        );
    }

    public function exportWeeklyCSV(Request $request, $year, $week)
    {
        $attendances = Attendance::whereYear('date', $year)
            ->whereWeek('date', $week)
            ->with(['user', 'classModel'])
            ->get();

        return Excel::download(
            new AttendanceExport($attendances, 'weekly'),
            "attendance_weekly_{$year}_{$week}.csv"
        );
    }

    public function exportMonthlyCSV(Request $request, $year, $month)
    {
        $attendances = Attendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with(['user', 'classModel'])
            ->get();

        $monthName = date('F', mktime(0, 0, 0, $month, 10)); // Get month name

        return Excel::download(
            new AttendanceExport($attendances, 'monthly'),
            "attendance_monthly_{$year}_{$monthName}.csv"
        );
    }

    public function exportByClassCSV($classId, $date = null)
    {
        $date = $date ?? today()->toDateString();

        $attendances = Attendance::where('class_model_id', $classId)
            ->where('date', $date)
            ->with(['user', 'classModel'])
            ->get();

        return Excel::download(
            new AttendanceExport($attendances, 'class'),
            "attendance_class_{$classId}_{$date}.csv"
        );
    }

    public function exportDailyTeachers(Request $request, $date = null)
    {
        $date = $date ?? today()->toDateString();

        $attendances = Attendance::where('date', $date)
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'Admin')
            ->select('attendances.*')
            ->with(['user', 'classModel'])
            ->get();

        return Excel::download(
            new AttendanceExport($attendances, 'daily'),
            "attendance_daily_teachers_{$date}.xlsx"
        );
    }

    public function exportDailyTeachersCSV(Request $request, $date = null)
    {
        $date = $date ?? today()->toDateString();

        $attendances = Attendance::where('date', $date)
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'Admin')
            ->select('attendances.*')
            ->with(['user', 'classModel'])
            ->get();

        return Excel::download(
            new AttendanceExport($attendances, 'daily'),
            "attendance_daily_teachers_{$date}.csv"
        );
    }
}
