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

        $users = $query->get();
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
            'teacher_id' => 'nullable|exists:users,id'
        ]);

        $class = ClassModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $request->teacher_id,
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
}
