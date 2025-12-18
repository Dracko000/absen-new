<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:ml-6 md:flex md:space-x-4">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Beranda
                    </x-nav-link>

                    @if(Auth::user()->hasRole('Superadmin'))
                        <x-nav-link :href="route('superadmin.users')" :active="request()->routeIs('superadmin.users') || request()->routeIs('superadmin.create.*')">
                            Pengguna
                        </x-nav-link>
                        <x-nav-link :href="route('superadmin.leave.requests')" :active="request()->routeIs('superadmin.leave.requests')">
                            Permintaan Izin
                        </x-nav-link>
                        <x-nav-link :href="route('superadmin.classes')" :active="request()->routeIs('superadmin.classes')">
                            Kelas
                        </x-nav-link>
                        <x-nav-link :href="route('superadmin.attendance.report')" :active="request()->routeIs('superadmin.attendance.report')">
                            Laporan
                        </x-nav-link>
                    @elseif(Auth::user()->hasRole('Admin'))
                        <x-nav-link :href="route('admin.classes')" :active="request()->routeIs('admin.classes')">
                            Kelas Saya
                        </x-nav-link>
                        <x-nav-link :href="route('admin.leave.requests')" :active="request()->routeIs('admin.leave.requests')">
                            Permintaan Izin
                        </x-nav-link>
                        <x-nav-link :href="route('admin.schedules')" :active="request()->routeIs('admin.schedules')">
                            Jadwal
                        </x-nav-link>
                        <x-nav-link :href="route('admin.classes')" :active="request()->routeIs('admin.classes')">
                            Absensi
                        </x-nav-link>
                    @elseif(Auth::user()->hasRole('User'))
                        <x-nav-link :href="route('student.attendance.history')" :active="request()->routeIs('student.attendance.history')">
                            Absensi Saya
                        </x-nav-link>
                        <x-nav-link :href="route('student.leave.requests')" :active="request()->routeIs('student.leave.requests')">
                            Permintaan Izin
                        </x-nav-link>
                        <x-nav-link :href="route('student.qr.code')" :active="request()->routeIs('student.qr.code')">
                            Kode QR
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden md:flex md:items-center md:ml-4">
                <div class="relative ml-3">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition duration-150 ease-in-out">
                                <div class="flex items-center space-x-2">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-indigo-600">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    </div>
                                    <span class="hidden md:block">{{ Str::limit(Auth::user()->name, 15) }}</span>
                                </div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm text-gray-700">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')">
                                Profil
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition duration-150 ease-in-out">
                    <span class="sr-only">Buka menu utama</span>
                    <svg class="h-6 w-6" :class="{'hidden': open, 'block': !open}" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" :class="{'block': open, 'hidden': !open}" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="md:hidden" x-cloak>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Beranda
            </x-responsive-nav-link>

            @if(Auth::user()->hasRole('Superadmin'))
                <x-responsive-nav-link :href="route('superadmin.users')" :active="request()->routeIs('superadmin.users') || request()->routeIs('superadmin.create.*')">
                    Pengguna
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('superadmin.leave.requests')" :active="request()->routeIs('superadmin.leave.requests')">
                    Permintaan Izin
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('superadmin.classes')" :active="request()->routeIs('superadmin.classes')">
                    Kelas
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('superadmin.attendance.report')" :active="request()->routeIs('superadmin.attendance.report')">
                    Laporan
                </x-responsive-nav-link>
            @elseif(Auth::user()->hasRole('Admin'))
                <x-responsive-nav-link :href="route('admin.classes')" :active="request()->routeIs('admin.classes')">
                    Kelas Saya
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.leave.requests')" :active="request()->routeIs('admin.leave.requests')">
                    Permintaan Izin
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.schedules')" :active="request()->routeIs('admin.schedules')">
                    Jadwal
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.classes')" :active="request()->routeIs('admin.classes')">
                    Absensi
                </x-responsive-nav-link>
            @elseif(Auth::user()->hasRole('User'))
                <x-responsive-nav-link :href="route('student.attendance.history')" :active="request()->routeIs('student.attendance.history')">
                    Absensi Saya
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.leave.requests')" :active="request()->routeIs('student.leave.requests')">
                    Permintaan Izin
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.qr.code')" :active="request()->routeIs('student.qr.code')">
                    Kode QR
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
