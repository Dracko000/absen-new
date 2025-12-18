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
                        <!-- Toggle between Check-in and Check-out -->
                        <div class="flex space-x-4 mb-4">
                            <button id="checkInBtn" onclick="switchMode('check-in')" class="mode-btn bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg active-mode-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                Check-in (Masuk)
                            </button>
                            <button id="checkOutBtn" onclick="switchMode('check-out')" class="mode-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                Check-out (Pulang)
                            </button>
                        </div>

                        <div class="flex space-x-4">
                            <button onclick="showScanner()" class="bg-green-600 hover:bg-green-800 text-white font-bold py-3 px-6 rounded-lg mr-4">
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
                    </div>

                    <!-- QR Scanner Section -->
                    <div id="scanner" class="hidden mb-6 p-6 bg-gray-50 rounded-xl">
                        <h4 class="text-lg font-medium mb-4 text-gray-900">Pemindai QR Code</h4>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <div id="camera-container" class="hidden border-2 border-dashed border-gray-300 rounded-lg p-2 text-center bg-white">
                                    <div class="relative">
                                        <video id="camera-video" autoplay playsinline class="w-full max-h-96 mx-auto" style="max-width: 100%;"></video>
                                        <canvas id="qr-canvas" class="hidden"></canvas>
                                    </div>
                                    <button onclick="stopCameraScanner()" class="mt-3 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg w-full sm:w-auto">
                                        Hentikan Kamera
                                    </button>
                                </div>

                                <div id="no-camera-access" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center bg-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto sm:h-16 sm:w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                    <p class="text-gray-600 mt-2 sm:mt-4 text-sm sm:text-base">Arahkan kamera ke kode QR siswa untuk memindai</p>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-1 sm:mt-2">Pastikan izin kamera telah diberikan</p>
                                    <button onclick="startCameraScanner()" class="mt-2 sm:mt-3 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg w-full sm:w-auto">
                                        Aktifkan Kamera
                                    </button>
                                </div>

                                <div class="mt-3 sm:mt-4">
                                    <label for="qrInput" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Atau masukkan kode QR secara manual:</label>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="text" id="qrInput" placeholder="Tempel kode QR di sini..." class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2 px-3">
                                        <button onclick="scanQrCode()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
                                            Pindai
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="bg-white p-3 sm:p-4 rounded-lg border">
                                    <h5 class="font-medium text-gray-900 mb-2 sm:mb-3 text-sm sm:text-base">Hasil Pemindaian</h5>
                                    <div id="scanResult" class="text-gray-600 min-h-[80px] sm:min-h-[100px] text-sm">
                                        <p>Hasil pemindaian akan muncul di sini...</p>
                                    </div>
                                </div>

                                <div class="mt-3 sm:mt-4 bg-blue-50 p-3 sm:p-4 rounded-lg">
                                    <h5 class="font-medium text-gray-900 mb-2 text-sm sm:text-base">Petunjuk Pemindaian</h5>
                                    <ul class="list-disc pl-4 text-xs sm:text-sm text-gray-600 space-y-1">
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

    <!-- Load jsQR library for QR code detection -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

    <script>
        let videoStream = null;

        function showScanner() {
            document.getElementById('scanner').classList.remove('hidden');
            document.getElementById('manualEntry').classList.add('hidden');
        }

        function showManual() {
            document.getElementById('manualEntry').classList.remove('hidden');
            document.getElementById('scanner').classList.add('hidden');
        }

        function startCameraScanner() {
            // Hide no camera access message
            document.getElementById('no-camera-access').style.display = 'none';

            // Show camera container
            document.getElementById('camera-container').classList.remove('hidden');

            const video = document.getElementById('camera-video');
            const canvas = document.getElementById('qr-canvas');

            if (!videoStream) {
                // Try to access the back camera on mobile devices
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { exact: "environment" } }
                })
                .then(function(stream) {
                    videoStream = stream;
                    video.srcObject = stream;
                    video.play();

                    // Start QR detection once video is playing
                    video.addEventListener('play', function() {
                        detectQRCode();
                    });
                })
                .catch(function(error) {
                    console.error("Camera error: ", error);
                    // If back camera fails, try any available camera
                    navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function(stream) {
                        videoStream = stream;
                        video.srcObject = stream;
                        video.play();

                        video.addEventListener('play', function() {
                            detectQRCode();
                        });
                    })
                    .catch(function(error) {
                        console.error("Camera error (fallback): ", error);
                        alert('Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan.');
                        document.getElementById('camera-container').classList.add('hidden');
                        document.getElementById('no-camera-access').style.display = 'block';
                    });
                });
            }
        }

        function stopCameraScanner() {
            if (videoStream) {
                const tracks = videoStream.getTracks();
                tracks.forEach(track => track.stop());
                videoStream = null;
            }

            document.getElementById('camera-container').classList.add('hidden');
            document.getElementById('no-camera-access').style.display = 'block';
        }

        function detectQRCode() {
            const video = document.getElementById('camera-video');
            const canvas = document.getElementById('qr-canvas');
            const ctx = canvas.getContext('2d');

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                // Set canvas dimensions to match video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // Draw current video frame to canvas
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Get image data from canvas
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

                // Try to decode QR code using jsQR
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                if (code) {
                    // QR code detected!
                    document.getElementById('qrInput').value = code.data;
                    scanQrCode(); // Process the scanned code with current mode
                    // Keep camera active for continuous scanning - removed stopCameraScanner() call
                }
            }

            // Continue scanning
            requestAnimationFrame(detectQRCode);
        }

        function scanQrCode() {
            const qrCode = document.getElementById('qrInput').value;
            if (!qrCode) {
                return; // Don't show alert for continuous scanning
            }

            // Get CSRF token with error handling
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                playErrorSound();
                alert('CSRF token not found. Please refresh the page.');
                return;
            }

            // Temporarily disable the input field during processing
            document.getElementById('qrInput').disabled = true;

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
                    playSuccessSound(); // Play success sound
                    // Clear input field for next scan
                    document.getElementById('qrInput').value = '';
                } else if(data.duplicate) {
                    // Handle duplicate scan specifically
                    resultDiv.innerHTML = `
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Scan Duplikat! </strong>
                            <span class="block sm:inline">${data.message}</span>
                        </div>
                    `;
                    // Show browser notification for duplicate scan
                    showDuplicateNotification(data.message);
                    playErrorSound(); // Play error sound for duplicate
                    // Clear input field to allow next scan
                    document.getElementById('qrInput').value = '';
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Gagal! </strong>
                            <span class="block sm:inline">${data.message}</span>
                        </div>
                    `;
                    playErrorSound(); // Play error sound
                    // Clear input field to allow retry if there's an error
                    document.getElementById('qrInput').value = '';
                }

                // Re-enable input field
                document.getElementById('qrInput').disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('scanResult').innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Gagal! </strong>
                        <span class="block sm:inline">Terjadi kesalahan saat memindai kode QR: ${error.message}</span>
                    </div>
                `;
                playErrorSound(); // Play error sound
                // Clear input field to allow retry if there's an error
                document.getElementById('qrInput').value = '';
                // Re-enable input field
                document.getElementById('qrInput').disabled = false;
            });
        }

        // Audio notification functions
        function playSuccessSound() {
            // Create audio context for success sound (beep)
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.type = 'sine';
                oscillator.frequency.value = 800; // Higher pitch for success
                gainNode.gain.value = 0.3;

                oscillator.start();
                setTimeout(() => {
                    oscillator.stop();
                }, 200); // Short beep for success
            } catch (e) {
                // Fallback if Web Audio API is not supported
                console.log("Web Audio API not supported, using system alert");
            }
        }

        function playErrorSound() {
            // Create audio context for error sound (beep)
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.type = 'sine';
                oscillator.frequency.value = 400; // Lower pitch for error
                gainNode.gain.value = 0.3;

                oscillator.start();
                setTimeout(() => {
                    oscillator.stop();
                }, 400); // Longer beep for error
            } catch (e) {
                // Fallback if Web Audio API is not supported
                console.log("Web Audio API not supported, using system alert");
            }
        }

        // Function to show duplicate scan notification
        function showDuplicateNotification(message) {
            // Create a more prominent alert for duplicate scans
            alert('Peringatan: ' + message);

            // Additionally, we could use the Notification API if available
            if ("Notification" in window) {
                // Request permission if not already granted
                if (Notification.permission === "granted") {
                    new Notification("Scan Duplikat!", {
                        body: message,
                        icon: null // You could add an icon if desired
                    });
                } else if (Notification.permission !== "denied") {
                    Notification.requestPermission().then(function(permission) {
                        if (permission === "granted") {
                            new Notification("Scan Duplikat!", {
                                body: message,
                                icon: null
                            });
                        }
                    });
                }
            }
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
                    if(data.duplicate) {
                        // Show duplicate notification for manual entry
                        alert('Peringatan Duplikat: ' + data.message);
                        showDuplicateNotification(data.message);
                    } else if(data.message) {
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

        // Mode functionality
        function switchMode(mode) {
            // Update active button styles
            document.querySelectorAll('.mode-btn').forEach(btn => {
                btn.classList.remove('active-mode-btn');
                btn.classList.remove('bg-green-500');
                btn.classList.remove('bg-blue-500');
                btn.classList.add('bg-green-500'); // Default style
            });

            if (mode === 'check-in') {
                document.getElementById('checkInBtn').classList.add('active-mode-btn');
                document.getElementById('checkInBtn').classList.remove('bg-green-500');
                document.getElementById('checkInBtn').classList.add('bg-green-700');
                document.getElementById('checkOutBtn').classList.remove('bg-blue-700');
                document.getElementById('checkOutBtn').classList.add('bg-blue-500');
            } else if (mode === 'check-out') {
                document.getElementById('checkOutBtn').classList.add('active-mode-btn');
                document.getElementById('checkOutBtn').classList.remove('bg-blue-500');
                document.getElementById('checkOutBtn').classList.add('bg-blue-700');
                document.getElementById('checkInBtn').classList.remove('bg-green-700');
                document.getElementById('checkInBtn').classList.add('bg-green-500');
            }

            // Update the global mode variable
            window.currentMode = mode;
        }

        // Override the original scanQrCode function to handle both modes
        function scanQrCode() {
            const qrCode = document.getElementById('qrInput').value;
            if (!qrCode) {
                return; // Don't show alert for continuous scanning
            }

            // Get CSRF token with error handling
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                playErrorSound();
                alert('CSRF token not found. Please refresh the page.');
                return;
            }

            // Temporarily disable the input field during processing
            document.getElementById('qrInput').disabled = true;

            // Determine the endpoint based on current mode
            const endpoint = window.currentMode === 'check-out' ? '/attendance/checkout' : '/attendance/scan';
            const modeLabel = window.currentMode === 'check-out' ? 'Check-out' : 'Check-in';

            fetch(endpoint, {
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
                            <strong class="font-bold">${modeLabel} Berhasil! </strong>
                            <span class="block sm:inline">${data.message}</span>
                        </div>
                    `;
                    playSuccessSound(); // Play success sound
                    // Clear input field for next scan
                    document.getElementById('qrInput').value = '';
                } else if(data.duplicate) {
                    // Handle duplicate scan specifically (for both check-in and check-out)
                    resultDiv.innerHTML = `
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">${modeLabel} Duplikat! </strong>
                            <span class="block sm:inline">${data.message}</span>
                        </div>
                    `;
                    // Show browser notification for duplicate
                    showDuplicateNotification(data.message);
                    playErrorSound(); // Play error sound for duplicate
                    // Clear input field to allow next scan
                    document.getElementById('qrInput').value = '';
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Gagal! </strong>
                            <span class="block sm:inline">${data.message}</span>
                        </div>
                    `;
                    playErrorSound(); // Play error sound
                    // Clear input field to allow retry if there's an error
                    document.getElementById('qrInput').value = '';
                }

                // Re-enable input field
                document.getElementById('qrInput').disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('scanResult').innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Gagal! </strong>
                        <span class="block sm:inline">Terjadi kesalahan saat ${modeLabel.toLowerCase()} kode QR: ${error.message}</span>
                    </div>
                `;
                playErrorSound(); // Play error sound
                // Clear input field to allow retry if there's an error
                document.getElementById('qrInput').value = '';
                // Re-enable input field
                document.getElementById('qrInput').disabled = false;
            });
        }

        // Initialize mode to check-in
        window.currentMode = 'check-in';

        // Add CSS for the active mode button
        const style = document.createElement('style');
        style.textContent = `
            .active-mode-btn {
                transform: scale(1.05);
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>