<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Absensi Kelas ') . $class->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Absensi Kelas: {{ $class->name }}</h1>
                <p class="mt-1 text-gray-600">Tanggal: {{ now()->format('d M Y') }}</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <button onclick="showScanner()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            Pindai QR Code
                        </button>
                        <button onclick="showManual()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Input Manual
                        </button>
                    </div>

                    <!-- QR Scanner Section -->
                    <div id="scanner" class="hidden mb-6 p-6 bg-gray-50 rounded-xl">
                        <h4 class="text-lg font-medium mb-4 text-gray-900">Pemindai QR Code</h4>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                    <p class="text-gray-600 mt-4">Arahkan kamera ke kode QR siswa untuk memindai</p>
                                    <p class="text-sm text-gray-500 mt-2">Fungsi kamera akan aktif di lingkungan produksi</p>
                                    <button onclick="startCameraScanner()" class="mt-3 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                                        Aktifkan Kamera
                                    </button>
                                </div>

                                <div class="mt-4">
                                    <label for="qrInput" class="block text-sm font-medium text-gray-700 mb-2">Atau masukkan kode QR secara manual:</label>
                                    <div class="flex">
                                        <input type="text" id="qrInput" placeholder="Tempel kode QR di sini..." class="flex-1 border-gray-300 rounded-l-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <button onclick="scanQrCode()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-r-lg">
                                            Pindai
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="bg-white p-4 rounded-lg border">
                                    <h5 class="font-medium text-gray-900 mb-3">Hasil Pemindaian</h5>
                                    <div id="scanResult" class="text-gray-600 min-h-[100px]">
                                        <p>Hasil pemindaian akan muncul di sini...</p>
                                    </div>
                                </div>

                                <div class="mt-4 bg-blue-50 p-4 rounded-lg">
                                    <h5 class="font-medium text-gray-900 mb-2">Petunjuk Pemindaian</h5>
                                    <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                        <li>Pastikan pencahayaan cukup</li>
                                        <li>Jaga jarak 10-20cm dari kode QR</li>
                                        <li>Hasil absensi akan muncul secara otomatis</li>
                                        <li>Jika gagal, coba pindai ulang</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Manual Entry Section -->
                    <div id="manualEntry" class="hidden mb-6">
                        <h4 class="text-lg font-medium mb-4 text-gray-900">Input Manual Absensi</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($students as $student)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $student->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <select class="status-select border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                        data-student-id="{{ $student->id }}">
                                                    <option value="Hadir">Hadir</option>
                                                    <option value="Terlambat">Terlambat</option>
                                                    <option value="Tidak Hadir">Tidak Hadir</option>
                                                </select>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text" class="note-input border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                       data-student-id="{{ $student->id }}" placeholder="Catatan...">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <button class="record-btn bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-3 rounded text-sm"
                                                        data-student-id="{{ $student->id }}">
                                                    Simpan
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada siswa ditemukan untuk kelas ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">
                            Simpan Semua Absensi
                        </button>
                        <a href="{{ route('admin.classes') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg">
                            Kembali ke Kelas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showScanner() {
            document.getElementById('scanner').classList.remove('hidden');
            document.getElementById('manualEntry').classList.add('hidden');
        }

        function showManual() {
            document.getElementById('manualEntry').classList.remove('hidden');
            document.getElementById('scanner').classList.add('hidden');
        }

        function startCameraScanner() {
            alert('Fitur scanner kamera akan diaktifkan di lingkungan produksi. Silakan masukkan kode QR secara manual untuk saat ini.');
            // Ini adalah tempat untuk menambahkan implementasi scanner kamera
            // Misalnya menggunakan library seperti QuaggaJS atau Html5-QRCode
        }

        function scanQrCode() {
            const qrCode = document.getElementById('qrInput').value;
            if (!qrCode) {
                alert('Silakan masukkan atau pindai kode QR terlebih dahulu');
                return;
            }

            // Get CSRF token with error handling
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                alert('CSRF token not found. Please refresh the page.');
                return;
            }

            fetch('/attendance/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    qr_data: qrCode,
                    class_model_id: {{ $class->id }}
                })
            })
            .then(response => {
                // Check if response is ok before parsing JSON
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const resultDiv = document.getElementById('scanResult');
                if(data.success) {
                    resultDiv.innerHTML = `
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Scan Berhasil! </strong>
                            <span class="block sm:inline">${data.message}</span>
                        </div>
                    `;
                    // Clear input field
                    document.getElementById('qrInput').value = '';
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Gagal! </strong>
                            <span class="block sm:inline">${data.message}</span>
                        </div>
                    `;
                }

                // Show notification
                alert(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('scanResult').innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Gagal! </strong>
                        <span class="block sm:inline">Terjadi kesalahan saat memindai kode QR: ${error.message}</span>
                    </div>
                `;
                alert('Terjadi kesalahan saat memindai kode QR: ' + error.message);
            });
        }

        // Handle recording attendance manually
        document.querySelectorAll('.record-btn').forEach(button => {
            button.addEventListener('click', function() {
                const studentId = this.getAttribute('data-student-id');
                const statusSelect = document.querySelector(`.status-select[data-student-id="${studentId}"]`);
                const noteInput = document.querySelector(`.note-input[data-student-id="${studentId}"]`);

                const status = statusSelect.value;
                const note = noteInput ? noteInput.value : '';

                fetch('/attendance/manual', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: studentId,
                        class_model_id: {{ $class->id }},
                        status: status,
                        note: note
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.message) {
                        alert(data.message);
                    } else {
                        alert('Error: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mencatat absensi');
                });
            });
        });

        // Initialize with scanner view
        showScanner();
    </script>
</x-app-layout>