<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Anggota Kelas: ') . $class->name }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Anggota Kelas: {{ $class->name }}</h1>
                        <p class="mt-1 text-gray-600 text-sm">Guru: {{ $class->teacher->name ?? 'N/A' }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.classes') }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-sm">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                    <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Absen</th>
                                    <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($students as $index => $student)
                                    <tr>
                                        <td class="px-3 sm:px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-3 sm:px-6 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                            <span class="truncate block max-w-[80px] sm:max-w-none">{{ $student->email }}</span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $student->attendances->last()?->created_at?->format('d M Y H:i') ?? 'Tidak ada' }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('print.preview.id.card', $student->id) }}" class="text-green-600 hover:text-green-900" target="_blank">Lihat Kartu</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-3 sm:px-6 py-4 text-center text-gray-500">Tidak ada siswa dalam kelas ini</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 sm:mt-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Ringkasan Kelas</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                            <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                                <p class="text-xs sm:text-sm text-gray-600">Jumlah Siswa</p>
                                <p class="text-lg sm:text-2xl font-bold text-blue-600">{{ count($students) }}</p>
                            </div>
                            <div class="bg-green-50 p-3 sm:p-4 rounded-lg">
                                <p class="text-xs sm:text-sm text-gray-600">Hadir Hari Ini</p>
                                <p class="text-lg sm:text-2xl font-bold text-green-600">
                                    {{ $class->attendances->where('date', today())->where('status', 'Hadir')->count() }}
                                </p>
                            </div>
                            <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg">
                                <p class="text-xs sm:text-sm text-gray-600">Terlambat Hari Ini</p>
                                <p class="text-lg sm:text-2xl font-bold text-yellow-600">
                                    {{ $class->attendances->where('date', today())->where('status', 'Terlambat')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>