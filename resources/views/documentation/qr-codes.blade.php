@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="text-xl font-semibold leading-tight text-gray-800">QR Code Usage Guide</h1>
</div>
@endsection

@section('slot')
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">QR Code Usage Guide</h2>

                    @auth
                    <p class="mb-4">
                        QR codes provide a convenient and efficient way to track attendance in the system.
                        Each student receives a unique QR code that identifies them in the attendance system.
                        This guide explains how to use QR codes for attendance tracking.
                    </p>

                    @if(auth()->user()->hasRole('User'))
                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">For Students</h3>
                    <p class="mb-3">
                        To access your personal QR code:
                    </p>
                    <ol class="list-decimal pl-5 mb-3">
                        <li>Log in to your student account</li>
                        <li>Navigate to the "QR Code" section in the menu</li>
                        <li>Your unique QR code will be displayed</li>
                        <li>Show this QR code to your teacher when requested</li>
                    </ol>
                    <p class="mb-4">
                        The QR code contains a unique identifier that links directly to your student account.
                    </p>
                    @endif

                    @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Superadmin'))
                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">For Teachers</h3>
                    <p class="mb-3">
                        To use QR codes for taking attendance:
                    </p>
                    <ol class="list-decimal pl-5 mb-3">
                        <li>Navigate to "Take Attendance" for the required class</li>
                        <li>Have students present their QR codes</li>
                        <li>Scan the QR code using the provided scanner interface</li>
                        <li>The system will automatically record their attendance</li>
                    </ol>
                    <p class="mb-4">
                        Alternatively, teachers can also use the manual attendance entry if needed.
                    </p>
                    @endif

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">Benefits of Using QR Codes</h3>
                    <ul class="list-disc pl-5 mb-4">
                        <li><strong>Efficiency:</strong> Quick and easy attendance recording</li>
                        <li><strong>Accuracy:</strong> Eliminates manual data entry errors</li>
                        <li><strong>Speed:</strong> Faster than traditional sign-in sheets</li>
                        <li><strong>Security:</strong> Each QR code is unique to a student account</li>
                        <li><strong>Tracking:</strong> Detailed logs of attendance timestamps</li>
                    </ul>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">QR Code Security</h3>
                    <p class="mb-3">
                        Important security considerations:
                    </p>
                    <ul class="list-disc pl-5 mb-4">
                        <li>Each QR code is associated with a single student account</li>
                        <li>Do not share your QR code with others</li>
                        <li>Contact administration if you suspect misuse of your QR code</li>
                        <li>QR codes can be regenerated if security is compromised</li>
                        <li>System logs track all QR code scans for accountability</li>
                    </ul>

                    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-700">Troubleshooting</h3>
                    <div class="border-l-4 border-blue-500 pl-4 bg-blue-50 p-4 mb-4">
                        <p><strong>QR Code won't scan:</strong> Ensure good lighting and clean camera lens.
                        Hold the device steady and at the right distance.</p>
                    </div>
                    <div class="border-l-4 border-blue-500 pl-4 bg-blue-50 p-4 mb-4">
                        <p><strong>Wrong student identified:</strong> Verify that the scanned QR code belongs
                        to the correct student. Contact technical support if the issue persists.</p>
                    </div>
                    <div class="border-l-4 border-blue-500 pl-4 bg-blue-50 p-4">
                        <p><strong>QR Code not recognized:</strong> Refresh the QR code page or log out and log
                        back in to regenerate your QR code.</p>
                    </div>
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
                                    This documentation section provides information about QR code usage in the attendance system.
                                    Please log in with your credentials to view the full content tailored to your role.
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="mb-4">
                        QR codes provide a convenient and efficient way to track attendance in the system.
                        Each student receives a unique QR code that identifies them in the attendance system.
                        This guide explains how to use QR codes for attendance tracking.
                    </p>
                    <p>
                        To access the full documentation and features, please log in to your account.
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