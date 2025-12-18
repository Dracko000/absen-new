<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Users') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Pengguna</h1>
                <p class="mt-2 text-gray-600">Daftar semua pengguna dalam sistem berdasarkan peran</p>
            </div>

            <!-- Success and Error Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex justify-end mb-6">
                <div class="flex space-x-2">
                    <button onclick="document.getElementById('addTeacherModal').classList.remove('hidden')"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Tambah Guru
                    </button>
                    <button onclick="document.getElementById('addStudentModal').classList.remove('hidden')"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Tambah Siswa
                    </button>
                    <button onclick="document.getElementById('importUserModal').classList.remove('hidden')"
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        Import Pengguna
                    </button>
                </div>
            </div>

            <!-- Tabs for filtering by role -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <a href="#" onclick="showTab('all')" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 py-4 px-1 text-sm font-medium tab active" data-tab="all">
                            Semua ({{ $users->count() }})
                        </a>
                        <a href="#" onclick="showTab('superadmin')" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 py-4 px-1 text-sm font-medium tab" data-tab="superadmin">
                            Superadmin ({{ $users->filter(fn($u) => $u->hasRole('Superadmin'))->count() }})
                        </a>
                        <a href="#" onclick="showTab('admin')" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 py-4 px-1 text-sm font-medium tab" data-tab="admin">
                            Guru ({{ $users->filter(fn($u) => $u->hasRole('Admin'))->count() }})
                        </a>
                        <a href="#" onclick="showTab('student')" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 py-4 px-1 text-sm font-medium tab" data-tab="student">
                            Siswa ({{ $users->filter(fn($u) => $u->hasRole('User'))->count() }})
                        </a>
                    </nav>
                </div>
            </div>

            <div class="mb-6 grid grid-cols-1 md:grid-cols-[1fr_auto_auto] gap-4">
                <form method="GET" action="{{ route('superadmin.users') }}" class="relative">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" id="table-search-users" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari nama atau email...">
                        @if(request('class_id'))
                            <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                        @endif
                    </div>
                    <button type="submit" class="absolute right-2.5 bottom-2.5 bg-blue-500 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-4 py-2 whitespace-nowrap">Cari</button>
                </form>

                <form method="GET" action="{{ route('superadmin.users') }}">
                    <select name="class_id" onchange="this.form.submit()" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                </form>

                @if(request('search') || request('class_id'))
                    <a href="{{ route('superadmin.users') }}" class="flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-medium rounded-lg text-sm whitespace-nowrap self-start">
                        Reset
                    </a>
                @endif
            </div>

            <!-- Users Table by Role -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="tab-all" class="tab-content active">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Semua Pengguna</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ substr($user->name, 0, 15) }}{{ strlen($user->name) > 15 ? '...' : '' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->nis ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->hasRole('User') && $user->class ? $user->class->name : '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('superadmin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900" data-user-id="{{ $user->id }}">Edit</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                @if($user->hasRole('User'))
                                                <a href="{{ route('superadmin.users.edit.class', $user->id) }}" class="text-purple-600 hover:text-purple-900">Edit Kelas</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                @endif
                                                <a href="{{ route('print.preview.id.card', $user->id) }}" class="text-green-600 hover:text-green-900" target="_blank">Lihat</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                <a href="#" class="text-red-600 hover:text-red-900 delete-user" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">Hapus</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="tab-superadmin" class="tab-content" style="display: none;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengguna Superadmin</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($users->filter(fn($u) => $u->hasRole('Superadmin')) as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ substr($user->name, 0, 15) }}{{ strlen($user->name) > 15 ? '...' : '' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->nis ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->hasRole('User') && $user->class ? $user->class->name : '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('superadmin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900" data-user-id="{{ $user->id }}">Edit</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                @if($user->hasRole('User'))
                                                <a href="{{ route('superadmin.users.edit.class', $user->id) }}" class="text-purple-600 hover:text-purple-900">Edit Kelas</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                @endif
                                                <a href="{{ route('print.preview.id.card', $user->id) }}" class="text-green-600 hover:text-green-900" target="_blank">Lihat</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                <a href="#" class="text-red-600 hover:text-red-900 delete-user" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">Hapus</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada pengguna Superadmin.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="tab-admin" class="tab-content" style="display: none;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengguna Guru</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($users->filter(fn($u) => $u->hasRole('Admin')) as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ substr($user->name, 0, 15) }}{{ strlen($user->name) > 15 ? '...' : '' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->nis ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->hasRole('User') && $user->class ? $user->class->name : '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('superadmin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900" data-user-id="{{ $user->id }}">Edit</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                @if($user->hasRole('User'))
                                                <a href="{{ route('superadmin.users.edit.class', $user->id) }}" class="text-purple-600 hover:text-purple-900">Edit Kelas</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                @endif
                                                <a href="{{ route('print.preview.id.card', $user->id) }}" class="text-green-600 hover:text-green-900" target="_blank">Lihat</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                <a href="{{ route('superadmin.admin.qr.code', $user->id) }}" class="text-blue-600 hover:text-blue-900" target="_blank">QR Code</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                <a href="#" class="text-red-600 hover:text-red-900 delete-user" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">Hapus</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada pengguna guru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="tab-student" class="tab-content" style="display: none;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengguna Siswa</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($users->filter(fn($u) => $u->hasRole('User')) as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ substr($user->name, 0, 15) }}{{ strlen($user->name) > 15 ? '...' : '' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->nis ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->class ? $user->class->name : '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('superadmin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900" data-user-id="{{ $user->id }}">Edit</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                @if($user->hasRole('User'))
                                                <a href="{{ route('superadmin.users.edit.class', $user->id) }}" class="text-purple-600 hover:text-purple-900">Edit Kelas</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                @endif
                                                <a href="{{ route('print.preview.id.card', $user->id) }}" class="text-green-600 hover:text-green-900" target="_blank">Lihat</a>
                                                <span class="mx-2 text-gray-300">|</span>
                                                <a href="#" class="text-red-600 hover:text-red-900 delete-user" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">Hapus</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada pengguna siswa.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Teacher Modal -->
    <div id="addTeacherModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Tambah Guru Baru</h3>
                    <button onclick="document.getElementById('addTeacherModal').classList.add('hidden')"
                            class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('superadmin.create.teacher') }}">
                    @csrf
                    <div class="mt-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <div class="mt-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <div class="mt-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Guru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Tambah Siswa Baru</h3>
                    <button onclick="document.getElementById('addStudentModal').classList.add('hidden')"
                            class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('superadmin.create.student') }}">
                    @csrf
                    <div class="mt-4">
                        <label for="s_name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" id="s_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <div class="mt-4">
                        <label for="s_nis" class="block text-sm font-medium text-gray-700">NIS (Nomor Induk Siswa)</label>
                        <input type="text" name="nis" id="s_nis" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <div class="mt-4">
                        <label for="class_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                        <select name="class_id" id="class_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Kelas (Opsional)</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import User Modal -->
    <div id="importUserModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Import Pengguna</h3>
                    <button onclick="document.getElementById('importUserModal').classList.add('hidden')"
                            class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('superadmin.users.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mt-4">
                        <label for="user_type" class="block text-sm font-medium text-gray-700">Jenis Pengguna</label>
                        <select name="user_type" id="user_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="">Pilih jenis pengguna</option>
                            <option value="User">Siswa</option>
                            <option value="Admin">Guru</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <label for="file" class="block text-sm font-medium text-gray-700">File Excel</label>
                        <input type="file" name="file" id="file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" accept=".xlsx,.xls,.csv" required>
                        <p class="mt-1 text-xs text-gray-500">Format file yang didukung: XLSX, XLS, atau CSV (Maksimal 2MB)</p>
                    </div>

                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Download template untuk:</p>
                        <div class="flex space-x-2">
                            <a href="{{ route('superadmin.users.import.template', 'user') }}" class="text-sm text-blue-600 hover:text-blue-800 underline">Siswa</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('superadmin.users.import.template', 'admin') }}" class="text-sm text-blue-600 hover:text-blue-800 underline">Guru</a>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            <strong>Format Siswa:</strong> name, nis, class_id (ID kelas, opsional)<br>
                            <strong>Format Guru:</strong> name, email, nip_nuptk
                        </p>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Import Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
                tab.classList.remove('border-blue-500');
                tab.classList.remove('text-blue-600');
            });

            // Show selected tab content
            document.getElementById('tab-' + tabName).style.display = 'block';

            // Add active class to clicked tab
            event.target.classList.add('active');
            event.target.classList.add('border-blue-500');
            event.target.classList.add('text-blue-600');
        }

        // Set the "Semua" tab as active by default
        document.addEventListener('DOMContentLoaded', function() {
            const allTab = document.querySelector('[data-tab="all"]');
            allTab.classList.add('active');
            allTab.classList.add('border-blue-500');
            allTab.classList.add('text-blue-600');
        });

        // Handle delete functionality
        document.querySelectorAll('.delete-user').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');

                if(confirm(`Apakah Anda yakin ingin menghapus pengguna "${userName}"? Tindakan ini tidak dapat dibatalkan.`)) {
                    // Create a form to submit the delete request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('users.destroy', '') }}/${userId}`;
                    form.style.display = 'none';

                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    // Add method spoofing for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
</x-app-layout>