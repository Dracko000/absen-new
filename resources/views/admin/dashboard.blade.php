<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 sm:mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard Guru</h1>
                <p class="mt-1 text-gray-600 text-sm sm:text-base">Halo, {{ Auth::user()->name }}! Berikut ringkasan kelas dan absensi Anda.</p>
            </div>

            <!-- Stats Cards - Responsive Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-blue-400 bg-opacity-30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm font-medium">Kelas Saya</h3>
                            <p class="text-lg sm:text-2xl font-bold">{{ count($classes) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-green-400 bg-opacity-30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm font-medium">Jadwal Saya</h3>
                            <p class="text-lg sm:text-2xl font-bold">{{ count($schedules) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-purple-400 bg-opacity-30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm font-medium">Absensi Hari Ini</h3>
                            <p class="text-lg sm:text-2xl font-bold">{{ $todayAttendance }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid - Responsive -->
            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                <!-- Classes Overview -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Kelas Saya</h3>
                        <a href="{{ route('admin.classes') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat semua</a>
                    </div>
                    @if(count($classes) > 0)
                        <div class="space-y-4">
                            @foreach($classes as $class)
                                <div class="border rounded-lg p-3 sm:p-4 hover:shadow-md transition duration-200">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                                        <div class="mb-2 sm:mb-0">
                                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">{{ $class->name }}</h4>
                                            <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $class->description ?? 'Tidak ada deskripsi' }}</p>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                                            <a href="{{ route('admin.class.attendance', $class->id) }}" class="inline-flex items-center justify-center px-3 py-1 border border-transparent text-xs sm:text-sm font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200">
                                                Lihat Absensi
                                            </a>
                                            <a href="{{ route('admin.class.members', $class->id) }}" class="inline-flex items-center justify-center px-3 py-1 border border-transparent text-xs sm:text-sm font-medium rounded text-green-700 bg-green-100 hover:bg-green-200">
                                                Anggota Kelas
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 sm:py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada kelas</h3>
                            <p class="mt-1 text-gray-500">Anda belum memiliki kelas yang ditugaskan.</p>
                            <div class="mt-4 sm:mt-6">
                                <a href="{{ route('admin.classes') }}" class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                    Kelola Kelas Saya
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions - Mobile First -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Fitur untuk Guru</h3>
                    <div class="mb-4 p-3 sm:p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm font-medium text-blue-700">Anda bisa membuat kelas baru!</p>
                        <p class="text-sm text-blue-600">Klik 'Kelola Kelas' untuk membuat atau mengelola kelas yang Anda ajarkan</p>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('print.preview.id.card', Auth::id()) }}" class="flex items-center p-3 sm:p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition duration-200" target="_blank">
                            <div class="flex-shrink-0 w-8 sm:w-10 h-8 sm:h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <p class="text-sm font-medium text-gray-900">Lihat Kartu Saya</p>
                                <p class="text-sm text-gray-500">Pratinjau kartu identitas saya</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.classes') }}" class="flex items-center p-3 sm:p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200">
                            <div class="flex-shrink-0 w-8 sm:w-10 h-8 sm:h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <p class="text-sm font-medium text-gray-900">Kelola Kelas</p>
                                <p class="text-sm text-gray-500">Buat atau atur kelas Anda</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.schedules') }}" class="flex items-center p-3 sm:p-4 bg-green-50 hover:bg-green-100 rounded-lg transition duration-200">
                            <div class="flex-shrink-0 w-8 sm:w-10 h-8 sm:h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <p class="text-sm font-medium text-gray-900">Jadwal Kelas</p>
                                <p class="text-sm text-gray-500">Atur jadwal pelajaran</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.classes') }}" class="flex items-center p-3 sm:p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition duration-200">
                            <div class="flex-shrink-0 w-8 sm:w-10 h-8 sm:h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <p class="text-sm font-medium text-gray-900">Absensi Hari Ini</p>
                                <p class="text-sm text-gray-500">Lihat absensi siswa</p>
                            </div>
                        </a>
                    </div>

                    <div class="mt-6 sm:mt-8">
                        <h4 class="font-medium text-gray-900 text-sm sm:text-base mb-3">Statistik Hari Ini</h4>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="text-center p-2 sm:p-3 bg-green-50 rounded-lg">
                                <p class="text-base sm:text-lg font-bold text-green-600">{{ \App\Models\Attendance::where('date', today())->whereIn('class_model_id', $classes->pluck('id'))->where('status', 'Hadir')->count() }}</p>
                                <p class="text-xs sm:text-sm text-gray-600">Hadir</p>
                            </div>
                            <div class="text-center p-2 sm:p-3 bg-yellow-50 rounded-lg">
                                <p class="text-base sm:text-lg font-bold text-yellow-600">{{ \App\Models\Attendance::where('date', today())->whereIn('class_model_id', $classes->pluck('id'))->where('status', 'Terlambat')->count() }}</p>
                                <p class="text-xs sm:text-sm text-gray-600">T. Lambat</p>
                            </div>
                            <div class="text-center p-2 sm:p-3 bg-red-50 rounded-lg">
                                <p class="text-base sm:text-lg font-bold text-red-600">{{ \App\Models\Attendance::where('date', today())->whereIn('class_model_id', $classes->pluck('id'))->where('status', 'Tidak Hadir')->count() }}</p>
                                <p class="text-xs sm:text-sm text-gray-600">T. Hadir</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Attendance - Fully Responsive -->
            <div class="mt-6 bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Absensi Terbaru</h3>
                    <a href="{{ route('admin.classes') }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat semua &rarr;</a>
                </div>

                @php
                    // Get latest attendances across all classes taught by this admin
                    $latestAttendances = \App\Models\Attendance::whereIn('class_model_id', $classes->pluck('id'))
                        ->with(['user', 'classModel'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                @if($latestAttendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                                <th scope="col" class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th scope="col" class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($latestAttendances as $attendance)
                            <tr>
                                <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm font-medium text-gray-900 truncate max-w-[80px] sm:max-w-[100px] md:max-w-none">{{ $attendance->user->name }}</div>
                                </td>
                                <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm text-gray-500 truncate max-w-[60px] sm:max-w-[80px] md:max-w-none">{{ $attendance->classModel->name }}</div>
                                </td>
                                <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                    {{ $attendance->date }}
                                </td>
                                <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 whitespace-nowrap">
                                    <span class="px-1 sm:px-2 inline-flex text-[0.6rem] sm:text-xs leading-5 font-semibold rounded-full
                                        @if($attendance->status == 'Hadir') bg-green-100 text-green-800
                                        @elseif($attendance->status == 'Terlambat') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                                <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                    {{ $attendance->time_in }}
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-2 sm:px-3 md:px-4 py-2 sm:py-4 text-center text-xs sm:text-sm text-gray-500">Tidak ada data absensi</td>
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