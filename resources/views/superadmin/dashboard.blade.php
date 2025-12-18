<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Superadmin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard Superadmin</h1>
                <p class="mt-1 text-gray-600 text-sm sm:text-base">Selamat datang, {{ Auth::user()->name }}! Berikut ringkasan sistem absensi sekolah.</p>
            </div>

            <!-- Stats Cards - Responsive Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-blue-400 bg-opacity-30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm font-medium">Total Pengguna</h3>
                            <p class="text-lg sm:text-2xl font-bold">{{ $totalUsers }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-green-400 bg-opacity-30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm font-medium">Guru</h3>
                            <p class="text-lg sm:text-2xl font-bold">{{ $totalTeachers }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-yellow-400 bg-opacity-30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm font-medium">Siswa</h3>
                            <p class="text-lg sm:text-2xl font-bold">{{ $totalStudents }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-purple-400 bg-opacity-30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm font-medium">Kelas</h3>
                            <p class="text-lg sm:text-2xl font-bold">{{ $totalClasses }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid - Responsive -->
            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                <!-- Attendance Stats -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Statistik Absensi Hari Ini</h3>
                        <span class="text-xs sm:text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-4 sm:mb-6">
                        <div class="text-center p-3 sm:p-4 bg-green-50 rounded-lg">
                            <p class="text-lg sm:text-2xl font-bold text-green-600">{{ $attendances->where('status', 'Hadir')->count() }}</p>
                            <p class="text-xs sm:text-sm text-gray-600">Hadir</p>
                        </div>
                        <div class="text-center p-3 sm:p-4 bg-yellow-50 rounded-lg">
                            <p class="text-lg sm:text-2xl font-bold text-yellow-600">{{ $attendances->where('status', 'Terlambat')->count() }}</p>
                            <p class="text-xs sm:text-sm text-gray-600">Terlambat</p>
                        </div>
                        <div class="text-center p-3 sm:p-4 bg-red-50 rounded-lg">
                            <p class="text-lg sm:text-2xl font-bold text-red-600">{{ $attendances->where('status', 'Tidak Hadir')->count() }}</p>
                            <p class="text-xs sm:text-sm text-gray-600">Tidak Hadir</p>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-6">
                        <h4 class="font-medium text-gray-900 text-sm sm:text-base mb-2 sm:mb-3">Akses Cepat</h4>
                        <div class="grid grid-cols-4 gap-2 sm:gap-3">
                            <a href="{{ route('print.preview.id.card', Auth::id()) }}" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 py-2 sm:py-3 px-3 sm:px-4 rounded-lg text-center transition duration-200" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="text-xs sm:text-sm">Lihat Kartu</span>
                            </a>
                            <a href="{{ route('superadmin.users') }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 py-2 sm:py-3 px-3 sm:px-4 rounded-lg text-center transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="text-xs sm:text-sm">Pengguna</span>
                            </a>
                            <a href="{{ route('superadmin.classes') }}" class="bg-green-50 hover:bg-green-100 text-green-700 py-2 sm:py-3 px-3 sm:px-4 rounded-lg text-center transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="text-xs sm:text-sm">Kelas</span>
                            </a>
                            <a href="{{ route('superadmin.attendance.report') }}" class="bg-purple-50 hover:bg-purple-100 text-purple-700 py-2 sm:py-3 px-3 sm:px-4 rounded-lg text-center transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span class="text-xs sm:text-sm">Laporan</span>
                            </a>
                            <a href="{{ route('superadmin.take.attendance') }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 py-2 sm:py-3 px-3 sm:px-4 rounded-lg text-center transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <span class="text-xs sm:text-sm">Absen</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity - Mobile First -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Aktivitas Terbaru</h3>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-2 sm:ml-3">
                                <p class="text-sm font-medium text-gray-900">Sistem Terinstal</p>
                                <p class="text-sm text-gray-500">Sistem absensi berhasil diinstal</p>
                                <p class="text-xs text-gray-400 mt-1">{{ now()->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-2 sm:ml-3">
                                <p class="text-sm font-medium text-gray-900">Akun Dibuat</p>
                                <p class="text-sm text-gray-500">3 akun telah dibuat hari ini</p>
                                <p class="text-xs text-gray-400 mt-1">{{ now()->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Attendance - Fully Responsive -->
            <div class="mt-6 bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Absensi Terbaru</h3>
                    <a href="{{ route('superadmin.attendance.report') }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat semua &rarr;</a>
                </div>

                @if($recentAttendances)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa/Guru</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentAttendances as $attendance)
                                <tr>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 truncate max-w-[100px] sm:max-w-none">{{ $attendance->user->name }}</div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 truncate max-w-[80px] sm:max-w-none">{{ $attendance->classModel->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attendance->date }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($attendance->status == 'Hadir') bg-green-100 text-green-800
                                            @elseif($attendance->status == 'Terlambat') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $attendance->status }}
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('superadmin.attendance.report') }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 sm:px-6 py-4 text-center text-gray-500">Tidak ada data absensi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-gray-500 text-center py-4">Tidak ada data absensi terbaru</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>