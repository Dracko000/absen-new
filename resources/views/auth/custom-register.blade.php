<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="flex justify-center">
                <x-application-logo class="h-16 w-auto text-indigo-600" />
            </div>

            <h2 class="mt-6 text-center text-2xl font-extrabold text-gray-900">
                Daftar {{ $role }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Buat akun {{ strtolower($role) }} baru
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ $action }}" class="mt-6">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Nama')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- NIP/NUPTK for Admins -->
                @if($role === 'Admin')
                <div class="mb-4">
                    <x-input-label for="nip_nuptk" :value="__('NIP/NUPTK')" />
                    <x-text-input id="nip_nuptk" class="block mt-1 w-full" type="text" name="nip_nuptk" :value="old('nip_nuptk')" required autocomplete="off" />
                    <x-input-error :messages="$errors->get('nip_nuptk')" class="mt-2" />
                </div>
                @endif

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                                  type="password"
                                  name="password"
                                  required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                  type="password"
                                  name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Sudah punya akun?') }}
                    </a>

                    <x-primary-button>
                        {{ __('Daftar sebagai ' . $role) }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>