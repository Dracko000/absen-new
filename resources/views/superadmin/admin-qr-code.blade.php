<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin QR Code') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center">
                        <h3 class="text-xl font-semibold mb-6">QR Code for Admin: {{ $user->name }}</h3>
                        <div class="bg-white p-6 rounded-lg shadow-lg inline-block">
                            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="mx-auto">
                        </div>
                        <p class="mt-4 text-gray-600">Scan this QR code to record attendance for {{ $user->name }}</p>
                        <p class="mt-2 text-sm text-gray-500">Admin unique ID: {{ $user->id }}</p>
                        <p class="mt-2 text-sm text-gray-500">Email: {{ $user->email }}</p>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-medium mb-4">How to use:</h4>
                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <li>Use this QR code to scan attendance for this admin user</li>
                            <li>Go to "Take Attendance" page and scan this code</li>
                            <li>Select the appropriate class for this admin's attendance</li>
                            <li>Attendance will be recorded for this admin user</li>
                        </ol>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('superadmin.users') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>