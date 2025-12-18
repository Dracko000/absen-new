<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Classes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Kelas Saya</h3>
                            <p class="text-sm text-gray-500 mt-1">Ini adalah kelas-kelas yang ditugaskan kepada Anda oleh Superadmin</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                            <div class="relative w-full sm:w-64">
                                <input type="text" id="searchInput" placeholder="Cari kelas..."
                                    class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="hidden md:table-cell px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                                        <th scope="col" class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                                        <th scope="col" class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                        <th scope="col" class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($classes as $class)
                                        <tr>
                                            <td class="px-3 sm:px-4 py-3 sm:py-4 whitespace-nowrap">
                                                <div class="text-xs sm:text-sm font-medium text-gray-900 max-w-[100px] sm:max-w-[150px] truncate">{{ $class->name }}</div>
                                            </td>
                                            <td class="hidden md:table-cell px-3 sm:px-4 py-3 sm:py-4">
                                                <div class="text-xs sm:text-sm text-gray-500 max-w-[120px] sm:max-w-[180px] truncate">{{ $class->description ?? 'No description' }}</div>
                                            </td>
                                            <td class="px-3 sm:px-4 py-3 sm:py-4">
                                                <div class="text-xs sm:text-sm text-gray-500">{{ $class->entry_time ? \Carbon\Carbon::parse($class->entry_time)->format('H:i') : '-' }}</div>
                                            </td>
                                            <td class="px-3 sm:px-4 py-3 sm:py-4">
                                                <div class="text-xs sm:text-sm text-gray-500">{{ $class->exit_time ? \Carbon\Carbon::parse($class->exit_time)->format('H:i') : '-' }}</div>
                                            </td>
                                            <td class="px-3 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                                {{ $class->created_at->format('d M Y') }}
                                                <div class="sm:hidden text-gray-400 text-xs">{{ $class->created_at->format('H:i') }}</div>
                                                <div class="hidden sm:block">{{ $class->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-3 sm:px-4 py-3 sm:py-4 text-xs sm:text-sm font-medium">
                                                <div class="flex flex-col sm:flex-row sm:flex-wrap gap-1">
                                                    <a href="{{ route('admin.class.attendance', $class->id) }}" class="text-indigo-600 hover:text-indigo-900 mb-1 sm:mb-0 sm:mr-2">Lihat</a>
                                                    <a href="{{ route('admin.class.take.attendance', $class->id) }}" class="text-green-600 hover:text-green-900 mb-1 sm:mb-0 sm:mr-2">Absen</a>
                                                    <a href="{{ route('admin.class.export', $class->id) }}" class="text-blue-600 hover:text-blue-900 mb-1 sm:mb-0 sm:mr-2">Excel</a>
                                                    @if($class->schedules->count() > 0)
                                                    <a href="{{ route('admin.schedules') }}" class="text-purple-600 hover:text-purple-900">Jadwal</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-3 sm:px-4 py-4 text-center text-xs sm:text-sm text-gray-500">Tidak ada kelas ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('tbody tr');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    // Loop through all cells to check for matches
                    let foundMatch = false;

                    for (let cell of row.cells) {
                        // Get text content of cell and all its child elements
                        let cellText = cell.textContent.toLowerCase();

                        // Also include text from inner elements (like divs, spans)
                        const innerElements = cell.querySelectorAll('*');
                        for (let element of innerElements) {
                            cellText += ' ' + element.textContent.toLowerCase();
                        }

                        if (cellText.includes(searchTerm)) {
                            foundMatch = true;
                            break;
                        }
                    }

                    if (foundMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>