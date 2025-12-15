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

    public function manageUsers()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('superadmin.users', compact('users', 'roles'));
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('User');

        return redirect()->route('superadmin.users')->with('success', 'Student created successfully.');
    }

    public function manageClasses()
    {
        $classes = ClassModel::with('teacher')->get();
        $teachers = User::role('Admin')->get();
        return view('superadmin.classes', compact('classes', 'teachers'));
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
}
