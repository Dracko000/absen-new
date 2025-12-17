@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="text-xl font-semibold leading-tight text-gray-800">Documentation</h1>
</div>
@endsection

@section('slot')
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Attendance Application Documentation</h2>

                    @auth
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">For Administrators</h3>
                            <ul class="list-disc pl-5 space-y-1 text-blue-700">
                                <li><a href="{{ route('documentation.page', 'admin-dashboard') }}" class="hover:underline">Admin Dashboard Guide</a></li>
                                <li><a href="{{ route('documentation.page', 'managing-classes') }}" class="hover:underline">Managing Classes</a></li>
                                <li><a href="{{ route('documentation.page', 'creating-schedules') }}" class="hover:underline">Creating Schedules</a></li>
                                <li><a href="{{ route('documentation.page', 'taking-attendance') }}" class="hover:underline">Taking Attendance</a></li>
                                <li><a href="{{ route('documentation.page', 'attendance-reports') }}" class="hover:underline">Attendance Reports</a></li>
                            </ul>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                            <h3 class="text-lg font-semibold text-green-800 mb-2">For Teachers</h3>
                            <ul class="list-disc pl-5 space-y-1 text-green-700">
                                <li><a href="{{ route('documentation.page', 'teacher-dashboard') }}" class="hover:underline">Teacher Dashboard</a></li>
                                <li><a href="{{ route('documentation.page', 'class-management') }}" class="hover:underline">Class Management</a></li>
                                <li><a href="{{ route('documentation.page', 'viewing-attendance') }}" class="hover:underline">Viewing Attendance</a></li>
                                <li><a href="{{ route('documentation.page', 'exporting-data') }}" class="hover:underline">Exporting Data</a></li>
                                <li><a href="{{ route('documentation.page', 'printing-options') }}" class="hover:underline">Printing Options</a></li>
                            </ul>
                        </div>

                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                            <h3 class="text-lg font-semibold text-purple-800 mb-2">For Students</h3>
                            <ul class="list-disc pl-5 space-y-1 text-purple-700">
                                <li><a href="{{ route('documentation.page', 'student-dashboard') }}" class="hover:underline">Student Dashboard</a></li>
                                <li><a href="{{ route('documentation.page', 'attendance-history') }}" class="hover:underline">Viewing Attendance History</a></li>
                                <li><a href="{{ route('documentation.page', 'qr-codes') }}" class="hover:underline">Using QR Codes</a></li>
                                <li><a href="{{ route('documentation.page', 'viewing-profile') }}" class="hover:underline">Managing Profile</a></li>
                            </ul>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-2">For Superadmins</h3>
                            <ul class="list-disc pl-5 space-y-1 text-yellow-700">
                                <li><a href="{{ route('documentation.page', 'superadmin-dashboard') }}" class="hover:underline">Superadmin Dashboard</a></li>
                                <li><a href="{{ route('documentation.page', 'user-management') }}" class="hover:underline">User Management</a></li>
                                <li><a href="{{ route('documentation.page', 'role-assignments') }}" class="hover:underline">Role Assignments</a></li>
                                <li><a href="{{ route('documentation.page', 'overall-reports') }}" class="hover:underline">Overall Reports</a></li>
                            </ul>
                        </div>
                    </div>
                    @else
                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 mb-8">
                        <h3 class="text-xl font-semibold text-blue-800 mb-3">Welcome to Attendance Application Documentation</h3>
                        <p class="mb-4">
                            This documentation provides guidance on using our attendance tracking system.
                            The system serves educational institutions with features for administrators,
                            teachers, and students to efficiently manage attendance.
                        </p>
                        <p class="mb-4">
                            To access role-specific documentation and use the application, please log in with your credentials.
                        </p>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Log In to Access Full Documentation
                        </a>
                    </div>
                    @endauth

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">Application Overview</h3>
                        <p class="mb-3">
                            This attendance application is designed to streamline attendance tracking in educational institutions.
                            The system supports three main user roles: Superadmin, Administrator (Teacher), and Student.
                        </p>

                        <h4 class="font-semibold text-gray-700 mt-4 mb-2">Features:</h4>
                        <ul class="list-disc pl-5 space-y-1 mb-4">
                            <li><strong>User Management:</strong> Create and manage users with different roles</li>
                            <li><strong>Class Management:</strong> Create classes and assign students</li>
                            <li><strong>Schedule Creation:</strong> Set class schedules and time slots</li>
                            <li><strong>QR Code Attendance:</strong> Take attendance using QR codes</li>
                            <li><strong>Manual Attendance:</strong> Mark attendance manually</li>
                            <li><strong>Reporting:</strong> Generate daily, weekly, and monthly reports</li>
                            <li><strong>Data Export:</strong> Export attendance data to Excel and CSV</li>
                            <li><strong>Student Profiles:</strong> Manage student information and QR codes</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-xl font-semibold mb-3 text-gray-700">Getting Started</h3>
                        @auth
                        <ol class="list-decimal pl-5 space-y-2">
                            <li><strong>Dashboard:</strong> Navigate to your role-specific dashboard</li>
                            <li><strong>Create Classes:</strong> (Admins) Create class groups and assign students</li>
                            <li><strong>Set Schedules:</strong> (Admins) Define class schedules</li>
                            <li><strong>Take Attendance:</strong> Use QR codes or manual entry to record attendance</li>
                            <li><strong>View Reports:</strong> Access attendance reports and export data</li>
                        </ol>
                        @else
                        <p class="mb-3">
                            After logging in, you'll be directed to your role-specific dashboard where you can access
                            features appropriate for your role in the system.
                        </p>
                        <p>
                            Contact your system administrator if you don't have login credentials.
                        </p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection