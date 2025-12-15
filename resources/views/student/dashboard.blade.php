<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 sm:mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard Siswa</h1>
                <p class="mt-1 text-gray-600 text-sm sm:text-base">Halo, {{ Auth::user()->name }}! Berikut informasi absensi Anda hari ini.</p>
            </div>

            <!-- Stats Cards - Responsive Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-blue-400 bg-opacity-30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm font-medium">Total Absensi</h3>
                            <p class="text-lg sm:text-2xl font-bold">{{ Auth::user()->attendances->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-green-400 bg-opacity-30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm font-medium">Hadir</h3>
                            <p class="text-lg sm:text-2xl font-bold">{{ Auth::user()->attendances->where('status', 'Hadir')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-yellow-400 bg-opacity-30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm font-medium">Terlambat</h3>
                            <p class="text-lg sm:text-2xl font-bold">{{ Auth::user()->attendances->where('status', 'Terlambat')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-red-400 bg-opacity-30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm font-medium">Tidak Hadir</h3>
                            <p class="text-lg sm:text-2xl font-bold">{{ Auth::user()->attendances->where('status', 'Tidak Hadir')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid - Responsive -->
            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                <!-- Today's Attendance -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Absensi Hari Ini</h3>
                        <span class="text-xs sm:text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
                    </div>
                    @if(count($attendances) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Masuk</th>
                                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($attendances as $attendance)
                                        <tr>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 truncate max-w-[100px] sm:max-w-none">{{ $attendance->classModel->name ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $attendance->time_in }}</div>
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($attendance->status == 'Hadir') bg-green-100 text-green-800
                                                    @elseif($attendance->status == 'Terlambat') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $attendance->status }}
                                                </span>
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $attendance->note ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6 sm:py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada absensi hari ini</h3>
                            <p class="mt-1 text-gray-500 text-sm">Absensi untuk hari ini belum direkam.</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions - Mobile First -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('student.qr.code') }}" class="flex items-center p-3 sm:p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition duration-200">
                            <div class="flex-shrink-0 w-8 sm:w-10 h-8 sm:h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <p class="text-sm font-medium text-gray-900">QR Code Saya</p>
                                <p class="text-sm text-gray-500">Tampilkan QR Code untuk absensi</p>
                            </div>
                        </a>

                        <a href="{{ route('student.attendance.history') }}" class="flex items-center p-3 sm:p-4 bg-green-50 hover:bg-green-100 rounded-lg transition duration-200">
                            <div class="flex-shrink-0 w-8 sm:w-10 h-8 sm:h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <p class="text-sm font-medium text-gray-900">Riwayat Absensi</p>
                                <p class="text-sm text-gray-500">Lihat semua absensi Anda</p>
                            </div>
                        </a>
                    </div>

                    <div class="mt-6 sm:mt-8 p-3 sm:p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 text-sm sm:text-base mb-3">Statistik Minggu Ini</h4>
                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between text-xs sm:text-sm mb-1">
                                    <span>Presensi</span>
                                    <span>{{ number_format(($attendances->count() / (now()->dayOfWeek ?: 7)) * 100, 0) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ number_format(($attendances->count() / (now()->dayOfWeek ?: 7)) * 100, 0) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Attendance - Fully Responsive -->
            <div class="mt-6 bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Absensi Terbaru</h3>
                    <a href="{{ route('student.attendance.history') }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat semua &rarr;</a>
                </div>

                @if($recentAttendances = Auth::user()->attendances->sortByDesc('date')->take(5))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentAttendances as $attendance)
                                <tr>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attendance->date }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 truncate max-w-[80px] sm:max-w-none">{{ $attendance->classModel->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($attendance->status == 'Hadir') bg-green-100 text-green-800
                                            @elseif($attendance->status == 'Terlambat') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $attendance->status }}
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attendance->time_in }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 sm:px-6 py-4 text-center text-gray-500">Tidak ada data absensi</td>
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