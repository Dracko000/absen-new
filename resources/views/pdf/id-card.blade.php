<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Identitas - {{ $user->name }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .card-container {
            width: 100%;
            max-width: 3.375in; /* Standard ID card width: 85.6mm */
            height: 2.125in; /* Standard ID card height: 54mm */
            margin: 0 auto;
        }
        
        .id-card {
            width: 100%;
            height: 100%;
            background: white;
            border-radius: 0.1in;
            box-shadow: 0 0.05in 0.1in rgba(0,0,0,0.3);
            color: #333;
            position: relative;
            overflow: hidden;
            page-break-inside: avoid;
        }
        
        .content-container {
            padding: 0.15in 0.2in;
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
            margin-top: 0.1in;
            flex-grow: 1;
        }

        .user-name {
            font-size: 0.15in;
            font-weight: bold;
            margin-bottom: 0.02in;
            color: #333;
        }

        .user-role {
            font-size: 0.12in;
            margin-bottom: 0.03in;
            color: #333;
        }

        .user-id {
            font-size: 0.09in;
            color: #333;
        }

        .user-email {
            font-size: 0.08in;
            margin-top: 0.02in;
            color: #333;
        }

        .qr-section {
            text-align: center;
            margin-left: 0.1in;
        }

        .qr-label {
            font-size: 0.06in;
            color: #333;
            margin-bottom: 0.01in;
        }

        .qr-code {
            width: 0.7in;
            height: 0.7in;
            background: white;
        }

        .qr-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .school-name {
            font-size: 0.13in;
            font-weight: bold;
            color: #333;
            text-align: center;
            width: 100%;
        }

        .footer-container {
            padding-top: 0.1in;
            border-top: 0.5pt solid #ddd;
            margin-top: 0.1in;
            text-align: center;
        }
        
        .school-logo {
            position: absolute;
            top: 0.08in;
            right: 0.15in;
            width: 0.4in;
            height: 0.4in;
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
                    <h3 style="margin: 0 0 0.05in 0; font-size: 0.13in; color: #333;">KARTU IDENTITAS</h3>
                    <div class="school-name" style="font-size: 0.13in; margin-bottom: 0.07in; text-align: left;">SDN CIKAMPEK SELATAN 1</div>
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