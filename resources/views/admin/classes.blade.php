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
                            <h3 class="text-lg font-medium text-gray-900">My Classes</h3>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                            <div class="relative w-full sm:w-64">
                                <input type="text" id="searchInput" placeholder="Search classes..."
                                    class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <button onclick="document.getElementById('createClassModal').classList.remove('hidden')"
                                    class="w-full sm:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add New Class
                            </button>
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
                                                    <a href="{{ route('admin.class.attendance', $class->id) }}" class="text-indigo-600 hover:text-indigo-900 mb-1 sm:mb-0 sm:mr-2">View</a>
                                                    <a href="{{ route('admin.class.take.attendance', $class->id) }}" class="text-green-600 hover:text-green-900 mb-1 sm:mb-0 sm:mr-2">Take</a>
                                                    <a href="{{ route('admin.class.export', $class->id) }}" class="text-blue-600 hover:text-blue-900 mb-1 sm:mb-0 sm:mr-2">Excel</a>
                                                    @if($class->schedules->count() > 0)
                                                    <a href="{{ route('admin.schedules') }}" class="text-purple-600 hover:text-purple-900">Sched</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-3 sm:px-4 py-4 text-center text-xs sm:text-sm text-gray-500">No classes found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create Class Modal -->
    <div id="createClassModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="myModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Create New Class</h3>
                    <button onclick="document.getElementById('createClassModal').classList.add('hidden')" 
                            class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('admin.create.class') }}">
                    @csrf
                    <div class="mt-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Class Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="entry_time" class="block text-sm font-medium text-gray-700">Jam Masuk</label>
                            <input type="time" name="entry_time" id="entry_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="exit_time" class="block text-sm font-medium text-gray-700">Jam Pulang</label>
                            <input type="time" name="exit_time" id="exit_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Class
                        </button>
                    </div>
                </form>
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