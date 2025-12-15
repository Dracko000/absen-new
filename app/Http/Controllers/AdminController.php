<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ClassModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => auth()->id(),
        ]);

        return redirect()->route('admin.classes')->with('success', 'Class created successfully.');
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
        return view('admin.take-attendance', compact('class', 'students'));
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
}
