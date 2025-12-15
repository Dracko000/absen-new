<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-gray-600 text-sm sm:text-base">Selamat datang, {{ Auth::user()->name }}!</p>
            </div>

            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 sm:p-8 text-white mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold">Sistem Absensi SDN Cikampek Selatan 1</h2>
                        <p class="mt-2 text-indigo-100 max-w-2xl">Aplikasi absensi berbasis QR Code untuk memudahkan pencatatan kehadiran siswa dan guru.</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <p class="text-sm text-indigo-100">Tanggal</p>
                            <p class="text-lg font-bold">{{ now()->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role-specific Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Informasi Pengguna</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Nama</p>
                                <p class="text-base sm:text-lg font-bold text-blue-600">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="text-base sm:text-lg font-bold text-green-600 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Role</p>
                                <p class="text-base sm:text-lg font-bold text-yellow-600">
                                    @foreach(Auth::user()->roles as $role)
                                        {{ $role->name }}
                                    @endforeach
                                </p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Terdaftar</p>
                                <p class="text-base sm:text-lg font-bold text-purple-600">{{ Auth::user()->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Akses Cepat</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @if(Auth::user()->hasRole('Superadmin'))
                                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Dashboard Superadmin</p>
                                        <p class="text-sm text-gray-500">Kelola semua fitur</p>
                                    </div>
                                </a>
                                <a href="{{ route('superadmin.users') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Kelola Pengguna</p>
                                        <p class="text-sm text-gray-500">Tambah/hapus pengguna</p>
                                    </div>
                                </a>
                                <a href="{{ route('superadmin.classes') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Kelola Kelas</p>
                                        <p class="text-sm text-gray-500">Lihat semua kelas</p>
                                    </div>
                                </a>
                            @elseif(Auth::user()->hasRole('Admin'))
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Dashboard Guru</p>
                                        <p class="text-sm text-gray-500">Informasi kelas saya</p>
                                    </div>
                                </a>
                                <a href="{{ route('admin.classes') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Kelas Saya</p>
                                        <p class="text-sm text-gray-500">Lihat kelas saya</p>
                                    </div>
                                </a>
                                <a href="{{ route('admin.schedules') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Jadwal Saya</p>
                                        <p class="text-sm text-gray-500">Lihat jadwal saya</p>
                                    </div>
                                </a>
                            @else
                                <a href="{{ route('student.dashboard') }}" class="flex items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Dashboard Siswa</p>
                                        <p class="text-sm text-gray-500">Informasi absensi saya</p>
                                    </div>
                                </a>
                                <a href="{{ route('student.attendance.history') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Riwayat Absensi</p>
                                        <p class="text-sm text-gray-500">Lihat absensi saya</p>
                                    </div>
                                </a>
                                <a href="{{ route('student.qr.code') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">QR Code Saya</p>
                                        <p class="text-sm text-gray-500">Tampilkan QR untuk absensi</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
