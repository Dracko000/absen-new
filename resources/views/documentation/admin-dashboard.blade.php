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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection