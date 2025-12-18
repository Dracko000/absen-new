<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="flex justify-center">
                <x-application-logo class="h-16 w-auto text-indigo-600" />
            </div>

            <h2 class="mt-6 text-center text-2xl font-extrabold text-gray-900">
                Pendaftaran Tidak Tersedia
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Pendaftaran pelajar sekarang ditangani oleh admin.
            </p>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}"
                   class="inline-block px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
