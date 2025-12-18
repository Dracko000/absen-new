<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Dashboard routes based on user roles
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('Superadmin')) {
            return redirect()->route('superadmin.dashboard');
        } elseif (auth()->user()->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->hasRole('User')) {
            return redirect()->route('student.dashboard');
        }
        return redirect('/login');
    })->name('dashboard');

    // Superadmin routes
    Route::middleware(['role:Superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperadminController::class, 'index'])->name('dashboard');
        Route::get('/users', [SuperadminController::class, 'manageUsers'])->name('users');
        Route::post('/users/teacher', [SuperadminController::class, 'createTeacher'])->name('create.teacher');
        Route::post('/users/student', [SuperadminController::class, 'createStudent'])->name('create.student');
        Route::get('/classes', [SuperadminController::class, 'manageClasses'])->name('classes');
        Route::post('/classes', [SuperadminController::class, 'createClass'])->name('classes.store');
        Route::get('/attendance-report', [SuperadminController::class, 'attendanceReport'])->name('attendance.report');
        Route::get('/class/{classId}/members', [SuperadminController::class, 'classMembers'])->name('class.members');
        Route::get('/class/{classId}/export', [SuperadminController::class, 'exportClassMembers'])->name('class.export');
        Route::get('/class/{classId}/import-students', [SuperadminController::class, 'showImportStudentsForm'])->name('class.import.students.form');
        Route::post('/class/{classId}/import-students', [SuperadminController::class, 'importStudents'])->name('class.import.students');
    });

    // Admin routes
    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/classes', [AdminController::class, 'manageClasses'])->name('classes');
        Route::post('/classes', [AdminController::class, 'createClass'])->name('create.class');
        Route::get('/schedules', [AdminController::class, 'manageSchedules'])->name('schedules');
        Route::post('/schedules', [AdminController::class, 'createSchedule'])->name('create.schedule');
        Route::get('/class/{id}/take-attendance', [AdminController::class, 'takeAttendance'])->name('class.take.attendance');
        Route::get('/class/{id}/attendance', [AdminController::class, 'classAttendance'])->name('class.attendance');
        Route::get('/class/{id}/attendance/{date?}', [AdminController::class, 'classAttendanceByDate'])->name('class.attendance.by.date');
        Route::get('/class/{id}/members', [AdminController::class, 'classMembers'])->name('class.members');
        Route::get('/class/{classId}/export', [AdminController::class, 'exportClass'])->name('class.export');
    });

    // Student routes
    Route::middleware(['role:User'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
        Route::get('/attendance-history', [StudentController::class, 'attendanceHistory'])->name('attendance.history');
        Route::get('/qr-code', [StudentController::class, 'showQrCode'])->name('qr.code');
    });

    // User management routes
    Route::middleware(['auth', 'role:Superadmin'])->prefix('users')->group(function () {
        Route::delete('/{id}', function($id) {
            $user = \App\Models\User::findOrFail($id);
            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully.');
        })->name('users.destroy');
    });

    // Shared attendance routes
    Route::middleware(['auth'])->prefix('attendance')->name('attendance.')->group(function () {
        Route::post('/record', [AttendanceController::class, 'recordAttendance'])->name('record');
        Route::post('/scan', [AttendanceController::class, 'scanQrCode'])->name('scan');
        Route::post('/manual', [AttendanceController::class, 'markAttendanceManual'])->name('manual');
        Route::put('/{id}/status', [AttendanceController::class, 'updateAttendanceStatus'])->name('update.status');
        Route::delete('/{id}', [AttendanceController::class, 'deleteAttendance'])->name('delete');
        Route::get('/daily/{date?}', [AttendanceController::class, 'getDailyAttendance'])->name('daily');
        Route::get('/weekly/{year}/{week}', [AttendanceController::class, 'getWeeklyAttendance'])->name('weekly');
        Route::get('/monthly/{year}/{month}', [AttendanceController::class, 'getMonthlyAttendance'])->name('monthly');
        Route::get('/user/{userId}', [AttendanceController::class, 'getAttendanceByUser'])->name('by.user');
        Route::get('/class/{classId}/{date?}', [AttendanceController::class, 'getAttendanceByClass'])->name('by.class');
    });

    // Export routes
    Route::middleware(['auth'])->prefix('export')->name('export.')->group(function () {
        // XLSX exports
        Route::get('/daily/{date?}', [ExportController::class, 'exportDaily'])->name('daily');
        Route::get('/weekly/{year}/{week}', [ExportController::class, 'exportWeekly'])->name('weekly');
        Route::get('/monthly/{year}/{month}', [ExportController::class, 'exportMonthly'])->name('monthly');
        Route::get('/class/{classId}/{date?}', [ExportController::class, 'exportByClass'])->name('by.class');
        Route::get('/user/{userId}', [ExportController::class, 'exportByUser'])->name('by.user');

        // CSV exports
        Route::get('/daily-csv/{date?}', [ExportController::class, 'exportDailyCSV'])->name('daily.csv');
        Route::get('/weekly-csv/{year}/{week}', [ExportController::class, 'exportWeeklyCSV'])->name('weekly.csv');
        Route::get('/monthly-csv/{year}/{month}', [ExportController::class, 'exportMonthlyCSV'])->name('monthly.csv');
        Route::get('/class-csv/{classId}/{date?}', [ExportController::class, 'exportByClassCSV'])->name('by.class.csv');
    });

    // QR Code routes
    Route::get('/qr/show/{id}', [QrCodeController::class, 'show'])->name('user.qr.show');
    Route::get('/qr/generate/{userId}', [QrCodeController::class, 'generateForUser'])->name('user.qr.generate');

    // Print routes
    Route::middleware(['auth'])->prefix('print')->name('print.')->group(function () {
        Route::get('/id-card/{userId}', [PrintController::class, 'printIdCard'])->name('id.card');
        Route::post('/multiple-id-cards', [PrintController::class, 'printMultipleIdCards'])->name('multiple.id.cards');
        Route::get('/preview/id-card/{userId}', [PrintController::class, 'previewIdCard'])->name('preview.id.card');
    });
});


// Custom registration routes for Superadmin and Admin (hidden routes)
Route::get('/superadmin/register', [App\Http\Controllers\Auth\CustomRegistrationController::class, 'showSuperadminRegistrationForm'])->name('superadmin.register.form');
Route::post('/superadmin/register', [App\Http\Controllers\Auth\CustomRegistrationController::class, 'registerSuperadmin'])->name('superadmin.register.store');

Route::get('/admin/register', [App\Http\Controllers\Auth\CustomRegistrationController::class, 'showAdminRegistrationForm'])->name('admin.register.form');
Route::post('/admin/register', [App\Http\Controllers\Auth\CustomRegistrationController::class, 'registerAdmin'])->name('admin.register.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
