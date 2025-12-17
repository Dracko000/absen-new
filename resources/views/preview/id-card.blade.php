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
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            color: #333;
            position: relative;
            overflow: hidden;
        }
        
        .content-container {
            padding: 15px 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .header-container {
            text-align: center;
        }
        
        .main-content {
            display: flex;
            flex-grow: 1;
        }
        
        .user-info {
            margin-top: 30px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .user-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        .user-role {
            font-size: 14px;
            margin-bottom: 8px;
            color: #333;
        }
        
        .user-id {
            font-size: 12px;
            color: #333;
        }
        
        .user-email {
            font-size: 12px;
            margin-top: 5px;
            color: #333;
        }
        
        .qr-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-left: 10px;
        }
        
        
        .qr-code {
            width: 60px;
            height: 60px;
            background: white;
        }
        
        .qr-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        
        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            text-align: left;
            width: 100%;
        }
        
        .school-logo {
            position: absolute;
            top: 10px;
            right: 15px;
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
            <a href="{{ url()->previous() }}" class="btn btn-back" style="margin-left: 10px;">
                ← Kembali
            </a>
        </div>
        
        <div class="card-container">
            <div class="id-card" id="card-to-download">
                <!-- School logo -->
                <div class="school-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah">
                </div>
                
                <div class="content-container">
                    <div class="header-container">
                        <h3 style="margin: 0 0 5px 0; font-size: 16px; color: #333; text-align: center;">KARTU IDENTITAS</h3>
                        <div class="school-name" style="font-size: 16px; margin-bottom: 10px; text-align: center;">SDN CIKAMPEK SELATAN 1</div>
                    </div>

                    <div class="main-content">
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

                        <div class="qr-section">
                            <div class="qr-wrapper">
                                <div class="qr-code">
                                    {!! QrCode::size(44)->generate($user->getQrCodeAttribute()) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script>
            function downloadAsJpg() {
                html2canvas(document.querySelector('#card-to-download'), {
                    backgroundColor: 'white',
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