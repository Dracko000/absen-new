<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My QR Code') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center">
                        <h3 class="text-xl font-semibold mb-6">Your QR Code for Attendance</h3>
                        <div class="bg-white p-6 rounded-lg shadow-lg inline-block">
                            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="mx-auto">
                        </div>
                        <p class="mt-4 text-gray-600">Show this QR code to superadmin for attendance scanning</p>
                        <p class="mt-2 text-sm text-gray-500">Your unique ID: {{ $user->id }}</p>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-medium mb-4">How to use:</h4>
                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <li>Show this QR code to superadmin when requested</li>
                            <li>Superadmin will scan this code using their device</li>
                            <li>Your attendance will be automatically recorded</li>
                            <li>You can check your attendance history in your dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>