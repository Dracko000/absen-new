<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Pengajuan Izin') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Ajukan Permohonan Izin</h3>

                    <form method="POST" action="{{ route('student.leave.request.submit') }}" enctype="multipart/form-data" id="leaveRequestForm">
                        @csrf

                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Izin</label>
                            <textarea name="reason" id="reason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required value="{{ old('start_date') }}">
                                @error('start_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required value="{{ old('end_date') }}">
                                @error('end_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lampiran (Foto Bukti)</label>

                            <!-- Image selection options -->
                            <div class="mb-4">
                                <div class="flex space-x-4">
                                    <button type="button" id="uploadOption" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-gray-700 font-medium option-btn active" onclick="selectUploadOption()">
                                        Upload Foto
                                    </button>
                                    <button type="button" id="cameraOption" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-gray-700 font-medium option-btn" onclick="selectCameraOption()">
                                        Ambil Foto
                                    </button>
                                </div>
                            </div>

                            <!-- Upload section -->
                            <div id="uploadSection" class="upload-section">
                                <input type="file" name="attachment" id="attachment" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" accept="image/*">
                                <p class="mt-2 text-sm text-gray-500">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB.</p>
                            </div>

                            <!-- Camera section -->
                            <div id="cameraSection" class="camera-section hidden">
                                <!-- Hidden input to store captured image data -->
                                <input type="hidden" name="captured_image" id="capturedImage" value="">

                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center bg-white">
                                    <div id="camera-container" class="hidden">
                                        <div class="relative">
                                            <video id="camera-video" autoplay playsinline class="w-full max-h-64 mx-auto" style="max-width: 100%;"></video>
                                            <canvas id="camera-canvas" class="hidden"></canvas>
                                        </div>
                                        <button type="button" onclick="captureImage()" class="mt-3 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                                            Ambil Foto
                                        </button>
                                        <button type="button" onclick="stopCamera()" class="mt-3 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg ml-2">
                                            Matikan Kamera
                                        </button>
                                    </div>

                                    <div id="no-camera-access" class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <p class="text-gray-600 mt-2">Ambil foto dengan kamera</p>
                                        <p class="text-xs text-gray-500 mt-1">Pastikan izin kamera telah diberikan</p>
                                        <button type="button" onclick="startCamera()" class="mt-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                                            Aktifkan Kamera
                                        </button>
                                    </div>
                                </div>

                                <!-- Preview for captured image -->
                                <div id="imagePreviewContainer" class="hidden mt-4">
                                    <img id="imagePreview" src="" alt="Preview" class="w-full max-h-64 mx-auto object-contain border rounded">
                                    <div class="mt-2 flex justify-center space-x-2">
                                        <button type="button" onclick="retakeImage()" class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded">
                                            Ulangi
                                        </button>
                                        <button type="button" onclick="removeImage()" class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Display error if neither option is selected -->
                            <div id="attachment-error" class="mt-2 text-sm text-red-600 hidden">Silakan pilih atau ambil foto terlebih dahulu.</div>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('student.leave.requests') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-white hover:bg-indigo-700">
                                Kirim Permohonan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let videoStream = null;

        // Initialize option buttons
        document.addEventListener('DOMContentLoaded', function() {
            selectUploadOption(); // Default to upload option
        });

        function selectUploadOption() {
            // Update button states
            document.getElementById('uploadOption').classList.add('active', 'bg-indigo-600', 'text-white');
            document.getElementById('uploadOption').classList.remove('bg-gray-200', 'hover:bg-gray-300');

            document.getElementById('cameraOption').classList.remove('active', 'bg-indigo-600', 'text-white');
            document.getElementById('cameraOption').classList.add('bg-gray-200', 'hover:bg-gray-300');

            // Show upload section, hide camera section
            document.getElementById('uploadSection').classList.remove('hidden');
            document.getElementById('cameraSection').classList.add('hidden');

            // Clear any previously captured image
            document.getElementById('capturedImage').value = '';
            document.getElementById('imagePreviewContainer').classList.add('hidden');
        }

        function selectCameraOption() {
            // Update button states
            document.getElementById('cameraOption').classList.add('active', 'bg-indigo-600', 'text-white');
            document.getElementById('cameraOption').classList.remove('bg-gray-200', 'hover:bg-gray-300');

            document.getElementById('uploadOption').classList.remove('active', 'bg-indigo-600', 'text-white');
            document.getElementById('uploadOption').classList.add('bg-gray-200', 'hover:bg-gray-300');

            // Show camera section, hide upload section
            document.getElementById('cameraSection').classList.remove('hidden');
            document.getElementById('uploadSection').classList.add('hidden');

            // Reset camera state
            if (videoStream) {
                stopCamera();
            }
        }

        function startCamera() {
            // Hide no camera access message
            document.getElementById('no-camera-access').style.display = 'none';

            // Show camera container
            document.getElementById('camera-container').classList.remove('hidden');

            const video = document.getElementById('camera-video');

            // Try to access the back camera on mobile devices
            navigator.mediaDevices.getUserMedia({
                video: { facingMode: { exact: "environment" } }
            })
            .then(function(stream) {
                videoStream = stream;
                video.srcObject = stream;
                video.play();
            })
            .catch(function(error) {
                console.error("Camera error: ", error);
                // If back camera fails, try any available camera
                navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    videoStream = stream;
                    video.srcObject = stream;
                    video.play();
                })
                .catch(function(error) {
                    console.error("Camera error (fallback): ", error);
                    alert('Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan.');
                    document.getElementById('camera-container').classList.add('hidden');
                    document.getElementById('no-camera-access').style.display = 'block';
                });
            });
        }

        function stopCamera() {
            if (videoStream) {
                const tracks = videoStream.getTracks();
                tracks.forEach(track => track.stop());
                videoStream = null;
            }

            document.getElementById('camera-container').classList.add('hidden');
            document.getElementById('no-camera-access').style.display = 'block';
        }

        function captureImage() {
            const video = document.getElementById('camera-video');
            const canvas = document.getElementById('camera-canvas');
            const context = canvas.getContext('2d');

            // Set canvas dimensions to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Draw current video frame to canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert to data URL and store in hidden input
            let imageDataUrl = canvas.toDataURL('image/jpeg', 0.8); // 80% quality

            // Check if the image is too large (over 2MB equivalent in base64)
            const byteString = atob(imageDataUrl.split(',')[1]);
            const byteLength = byteString.length;
            const mbSize = byteLength / 1024 / 1024;

            // If image is too large, reduce quality
            if (mbSize > 2) {
                // Try with 60% quality
                imageDataUrl = canvas.toDataURL('image/jpeg', 0.6);
            }

            // Recheck size after quality reduction
            const byteStringFinal = atob(imageDataUrl.split(',')[1]);
            const byteLengthFinal = byteStringFinal.length;
            const mbSizeFinal = byteLengthFinal / 1024 / 1024;

            // If still too large, try 40% quality
            if (mbSizeFinal > 2) {
                imageDataUrl = canvas.toDataURL('image/jpeg', 0.4);
            }

            // Final check - if still too large, warn user
            const finalByteString = atob(imageDataUrl.split(',')[1]);
            const finalByteLength = finalByteString.length;
            const finalMbSize = finalByteLength / 1024 / 1024;

            if (finalMbSize > 2) {
                alert('Ukuran gambar masih terlalu besar meskipun kualitas telah dikurangi. Silakan ambil foto dengan pencahayaan yang lebih baik atau dari jarak yang lebih jauh.');
                return;
            }

            document.getElementById('capturedImage').value = imageDataUrl;

            // Show preview
            const preview = document.getElementById('imagePreview');
            preview.src = imageDataUrl;
            document.getElementById('imagePreviewContainer').classList.remove('hidden');

            // Stop camera after capturing
            stopCamera();
        }

        function retakeImage() {
            document.getElementById('imagePreviewContainer').classList.add('hidden');
            document.getElementById('capturedImage').value = '';
            startCamera();
        }

        function removeImage() {
            document.getElementById('imagePreviewContainer').classList.add('hidden');
            document.getElementById('capturedImage').value = '';
        }

        // Form validation
        document.getElementById('leaveRequestForm').addEventListener('submit', function(e) {
            const uploadSectionVisible = !document.getElementById('uploadSection').classList.contains('hidden');
            const attachmentFile = document.getElementById('attachment').files[0];
            const capturedImage = document.getElementById('capturedImage').value;

            // Check if upload option is selected but no file chosen
            if (uploadSectionVisible && !attachmentFile) {
                e.preventDefault();
                document.getElementById('attachment-error').classList.remove('hidden');
                return false;
            }

            // Check if camera option is selected but no image captured
            if (!uploadSectionVisible && !capturedImage) {
                e.preventDefault();
                document.getElementById('attachment-error').classList.remove('hidden');
                return false;
            }

            // If everything is fine, hide the error message
            document.getElementById('attachment-error').classList.add('hidden');
        });

        // Remove error when user selects an option
        document.getElementById('attachment').addEventListener('change', function() {
            document.getElementById('attachment-error').classList.add('hidden');
        });

        document.getElementById('capturedImage').addEventListener('change', function() {
            document.getElementById('attachment-error').classList.add('hidden');
        });
    </script>

    <style>
        .option-btn.active {
            background-color: #4f46e5;
            color: white;
        }
    </style>
</x-app-layout>