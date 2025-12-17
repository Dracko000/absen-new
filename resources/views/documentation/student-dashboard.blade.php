@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="text-xl font-semibold leading-tight text-gray-800">Student Dashboard Guide</h1>
</div>
@endsection

@section('slot')
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">Student Dashboard Guide</h2>

                    @auth
                    @if(auth()->user()->hasRole('User') || auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Superadmin'))
                    <p class="mb-4">
                        The Student Dashboard provides students with access to their attendance records,
                        QR code for check-in, and other relevant information. This guide will help
                        students navigate and use all features available to their role.
                    </p>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">Dashboard Overview</h3>
                    <p class="mb-3">
                        The dashboard displays today's attendance records for the logged-in student.
                        Information includes:
                    </p>
                    <ul class="list-disc pl-5 mb-4">
                        <li>Classes attended today</li>
                        <li>Time of attendance</li>
                        <li>Status (Present, Late, etc.)</li>
                    </ul>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">Navigation Menu</h3>
                    <p class="mb-3">
                        From the navigation menu, students have access to the following sections:
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
                                    <td class="py-2 px-4 border-b border-r text-sm">My Attendance</td>
                                    <td class="py-2 px-4 border-b text-sm">View complete attendance history</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 border-b border-r text-sm">QR Code</td>
                                    <td class="py-2 px-4 border-b text-sm">Access personal QR code for attendance</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">Attending Classes</h3>
                    <p class="mb-3">
                        To attend a class using QR code:
                    </p>
                    <ol class="list-decimal pl-5 mb-3">
                        <li>Go to your "QR Code" section</li>
                        <li>Show your unique QR code to the teacher</li>
                        <li>The teacher will scan your QR code to mark your attendance</li>
                        <li>You'll receive confirmation once attendance is recorded</li>
                    </ol>
                    <p class="mb-4">
                        Alternatively, your teacher may manually mark your attendance without scanning.
                    </p>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">Viewing Attendance History</h3>
                    <p class="mb-3">
                        In the "My Attendance" section, you can:
                    </p>
                    <ul class="list-disc pl-5 mb-4">
                        <li>See all past attendance records</li>
                        <li>Filter by date range</li>
                        <li>Check attendance status for each class</li>
                        <li>View times when attendance was marked</li>
                    </ul>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">QR Code Security</h3>
                    <p class="mb-4">
                        Your QR code is unique and linked only to your account. Keep it secure and
                        do not share with others. If you suspect unauthorized use, contact
                        administration to get a new QR code.
                    </p>
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
                                    You are currently logged in with a different role. This documentation is specifically for Student users.
                                    The content may not be relevant to your permissions.
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="mb-4">
                        This section provides information about the Student Dashboard features.
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
                                    This documentation section is specifically for Student users.
                                    Please log in with your credentials to view the full content.
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="mb-4">
                        The Student Dashboard provides students with access to their attendance records,
                        QR code for check-in, and other relevant information. This guide will help
                        students navigate and use all features available to their role.
                    </p>
                    <p>
                        To access the full documentation and features, please log in with your Student account.
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