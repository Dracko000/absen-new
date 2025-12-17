@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="text-xl font-semibold leading-tight text-gray-800">Admin Dashboard Guide</h1>
</div>
@endsection

@section('slot')
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">Admin Dashboard Guide</h2>

                    @auth
                    @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Superadmin'))
                    <p class="mb-4">
                        The Admin Dashboard serves as the central hub for administrators to manage their classes,
                        students, and attendance activities. This guide will help you navigate and utilize all
                        features available to the Admin role.
                    </p>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">Dashboard Overview</h3>
                    <p class="mb-3">
                        The dashboard provides a quick overview of your classes, schedules, and today's attendance count.
                        Key metrics include:
                    </p>
                    <ul class="list-disc pl-5 mb-4">
                        <li>Total number of classes you manage</li>
                        <li>Number of scheduled classes</li>
                        <li>Attendance count for the current day</li>
                    </ul>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">Navigation Menu</h3>
                    <p class="mb-3">
                        From the navigation menu, admins have access to the following sections:
                    </p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-2 px-4 border-b border-r text-left text-sm font-semibold text-gray-700">Section</th>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">Functionality</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="py-2 px-4 border-b border-r text-sm">My Classes</td>
                                    <td class="py-2 px-4 border-b text-sm">Create, view and manage your class groups</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 border-b border-r text-sm">Schedules</td>
                                    <td class="py-2 px-4 border-b text-sm">Manage class schedules and time slots</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 border-b border-r text-sm">Attendance</td>
                                    <td class="py-2 px-4 border-b text-sm">Record attendance for your classes</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">Managing Classes</h3>
                    <p class="mb-3">
                        To create a new class:
                    </p>
                    <ol class="list-decimal pl-5 mb-3">
                        <li>Navigate to the "My Classes" section</li>
                        <li>Click the "Create Class" button</li>
                        <li>Enter the class name and description</li>
                        <li>Click "Save" to create the class</li>
                    </ol>
                    <p class="mb-4">
                        Once created, you can view class details and add students to each class.
                    </p>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">Recording Attendance</h3>
                    <p class="mb-3">
                        There are multiple ways to record attendance:
                    </p>
                    <ul class="list-disc pl-5 mb-4">
                        <li><strong>QR Code Scanning:</strong> Students present their QR code which you scan to mark attendance</li>
                        <li><strong>Manual Entry:</strong> Mark students as present, absent, late, etc. manually</li>
                        <li><strong>Class Attendance:</strong> Record attendance for an entire class at once</li>
                    </ul>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">Additional Features</h3>
                    <ul class="list-disc pl-5 mb-4">
                        <li>View daily, weekly, and monthly class attendance</li>
                        <li>Export attendance data to Excel or CSV</li>
                        <li>Print ID cards for students</li>
                        <li>Manage schedules for each class</li>
                    </ul>
                    @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    You are currently logged in with a different role. This documentation is specifically for Admin users.
                                    The content may not be relevant to your permissions.
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="mb-4">
                        This section provides information about the Admin Dashboard features.
                        As you're logged in with a different role, some of these features may not be available to you.
                    </p>
                    @endif
                    @else
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    This documentation section is specifically for Admin users.
                                    Please log in with your credentials to view the full content.
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="mb-4">
                        The Admin Dashboard serves as the central hub for administrators to manage their classes,
                        students, and attendance activities. This guide will help you navigate and utilize all
                        features available to the Admin role.
                    </p>
                    <p>
                        To access the full documentation and features, please log in with your Admin account.
                    </p>
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-4">
                        Log In
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection