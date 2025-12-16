<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Kartu Identitas - {{ $user->name }}</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .container {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
        }
        
        .controls {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .btn {
            background: #4f46e5;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background: #4338ca;
        }
        
        .btn-back {
            background: #6b7280;
        }
        
        .btn-back:hover {
            background: #4b5563;
        }
        
        .card-container {
            width: 100%;
            max-width: 350px;
            height: 220px;
            margin: 0 auto;
        }
        
        .id-card {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .card-header {
            padding: 15px 20px;
            background: rgba(0,0,0,0.4);
            border-bottom: 1px solid rgba(255,255,255,0.4);
        }
        
        .card-body {
            padding: 15px 20px 10px;
        }
        
        .user-info {
            margin-top: 10px;
        }
        
        .user-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            color: white;
        }
        
        .user-role {
            font-size: 14px;
            margin-bottom: 8px;
            color: white;
        }
        
        .user-id {
            font-size: 12px;
            color: white;
        }
        
        .user-email {
            font-size: 12px;
            margin-top: 5px;
            color: white;
        }
        
        .card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 20px;
            background: rgba(0,0,0,0.5);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .school-name {
            font-size: 10px;
            font-weight: bold;
            color: white;
        }
        
        .qr-section {
            margin-left: auto;
            text-align: center;
        }
        
        .qr-label {
            font-size: 8px;
            color: white;
            margin-bottom: 2px;
        }
        
        .qr-code {
            width: 50px;
            height: 50px;
            background: white;
            padding: 3px;
        }
        
        .logo {
            position: absolute;
            top: 10px;
            right: 15px;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .school-logo {
            position: absolute;
            top: 10px;
            left: 15px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .school-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="controls">
            <button onclick="downloadAsJpg()" class="btn">
                ⬇️ Download JPG
            </button>
            <a href="javascript:history.back()" class="btn btn-back" style="margin-left: 10px;">
                ← Kembali
            </a>
        </div>

        <div class="card-container">
            <div class="id-card" id="card-to-download">
                <!-- School logo -->
                <div class="school-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah">
                </div>

                <div class="card-header">
                    <h3 style="margin: 0; font-size: 16px; color: white;">KARTU IDENTITAS</h3>
                </div>

                <div class="card-body">
                    <div class="user-info">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-role">
                            @if($user->hasRole('Superadmin'))
                                Super Administrator
                            @elseif($user->hasRole('Admin'))
                                Guru
                            @elseif($user->hasRole('User'))
                                Siswa
                            @else
                                Pengguna
                            @endif
                        </div>
                        <div class="user-id">ID: {{ $user->id }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="school-name">SDN CIKAMPEK SELATAN 1</div>
                    <div class="qr-section">
                        <div class="qr-label">SCAN UNTUK ABSEN</div>
                        <div class="qr-code">
                            {!! QrCode::size(44)->generate($user->getQrCodeAttribute()) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script>
            function downloadAsJpg() {
                html2canvas(document.querySelector('#card-to-download'), {
                    backgroundColor: '#667eea',
                    scale: 2 // Higher resolution
                }).then(canvas => {
                    // Create a temporary link to download the image
                    const link = document.createElement('a');
                    link.download = 'kartu-identitas-{{ $user->name }}.jpg';
                    link.href = canvas.toDataURL('image/jpeg', 0.8); // 80% quality
                    link.click();
                });
            }
        </script>
    </div>
</body>
</html>