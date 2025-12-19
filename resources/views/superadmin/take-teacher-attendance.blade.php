<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Take Teacher Attendance') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Pengambilan Absensi Guru</h1>
                <p class="mt-1 text-gray-600">Scan QR code yang dimiliki oleh guru untuk merekam kehadiran mereka</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih Kelas</h3>
                        <div class="mb-4">
                            <label for="classSelect" class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select id="classSelect" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Pilih kelas...</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Scan QR Code</h3>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center" id="qrScanner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">Arahkan kamera ke QR Code guru</p>
                                <div class="mt-4">
                                    <input type="file" id="qrFileInput" accept="image/*" capture="environment" class="hidden" />
                                    <button onclick="document.getElementById('qrFileInput').click()"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Gunakan Kamera
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Guru</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($teachers as $teacher)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $teacher->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $teacher->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Belum Absen
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button onclick="markTeacherAttendance({{ $teacher->id }})" 
                                                        class="text-blue-600 hover:text-blue-900 mr-2">
                                                    Absen Manual
                                                </button>
                                                <a href="{{ route('superadmin.admin.qr.code', $teacher->id) }}" 
                                                   target="_blank"
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    Lihat QR
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada guru ditemukan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-800">Catatan:</h4>
                            <p class="mt-1 text-sm text-blue-700">Fitur scan QR untuk guru akan segera tersedia. Untuk sementara, gunakan tombol 'Absen Manual' untuk merekam kehadiran guru.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function markTeacherAttendance(teacherId) {
            const classId = document.getElementById('classSelect').value;

            if (!classId) {
                alert('Silakan pilih kelas terlebih dahulu');
                return;
            }

            if (confirm('Apakah Anda yakin ingin mencatat kehadiran guru ini?')) {
                // In a real implementation, you would make an API call here
                fetch('/attendance/manual', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: teacherId,
                        class_model_id: classId,
                        status: 'Hadir', // Default status
                        note: 'Absensi guru oleh superadmin'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert('Kehadiran guru berhasil direkam');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mencatat kehadiran');
                });
            }
        }

        // Handle QR file input
        document.getElementById('qrFileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                alert('File QR Code diterima. Dalam implementasi penuh, ini akan diproses untuk deteksi kode QR.');
                // In a full implementation, you would use a QR code scanner library here
                // For now, we'll just show an alert
            }
        });
    </script>
</x-app-layout>