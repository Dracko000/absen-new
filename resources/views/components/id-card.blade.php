<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Identitas - {{ $user->name }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        
        .card-container {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .id-card {
            width: 100%;
            max-width: 350px;
            height: 220px;
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

        .main-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-grow: 1;
        }

        .user-info {
            margin-top: 30px;
            flex-grow: 1;
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
            text-align: center;
            margin-left: 10px;
        }

        .qr-label {
            font-size: 8px;
            color: #333;
            margin-bottom: 2px;
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
            text-align: center;
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
    <div class="card-container">
        <div class="id-card">
            <!-- School logo -->
            <div class="school-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" style="max-width:100%; max-height:100%;">
            </div>
            
            <div class="content-container">
                <div class="header-container">
                    <h3 style="margin: 0 0 5px 0; font-size: 16px; color: #333;">KARTU IDENTITAS</h3>
                    <div class="school-name" style="font-size: 16px; margin-bottom: 10px; text-align: left;">SDN CIKAMPEK SELATAN 1</div>
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
                        <div class="qr-label">SCAN UNTUK ABSEN</div>
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
</body>
</html>