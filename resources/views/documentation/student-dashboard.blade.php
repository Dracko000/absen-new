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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection