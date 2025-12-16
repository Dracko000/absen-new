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
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
    <div class="card-container">
        <div class="id-card">
            <!-- Logo placeholder -->
            <div class="logo">SC</div>

            <div class="card-header">
                <h3 style="margin: 0; font-size: 16px; color: white; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">KARTU IDENTITAS</h3>
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
                    <div class="user-email" style="font-size: 12px; margin-top: 5px; color: white;">
                        {{ $user->email }}
                    </div>
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
</body>
</html>