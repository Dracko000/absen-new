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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 0.1in;
            box-shadow: 0 0.05in 0.1in rgba(0,0,0,0.3);
            color: white;
            position: relative;
            overflow: hidden;
            page-break-inside: avoid;
        }
        
        .card-header {
            padding: 0.15in 0.2in;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .card-body {
            padding: 0.15in 0.2in 0.1in;
        }
        
        .user-info {
            margin-top: 0.05in;
        }
        
        .user-name {
            font-size: 0.15in;
            font-weight: bold;
            margin-bottom: 0.02in;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        .user-role {
            font-size: 0.12in;
            margin-bottom: 0.03in;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        .user-id {
            font-size: 0.09in;
            opacity: 0.8;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        .user-email {
            font-size: 0.08in;
            margin-top: 0.02in;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        .card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 0.08in 0.2in;
            background: rgba(0,0,0,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .school-name {
            font-size: 0.08in;
            font-weight: bold;
        }
        
        .qr-section {
            margin-left: auto;
        }
        
        .qr-code {
            width: 0.6in;
            height: 0.6in;
            background: white;
            padding: 0.02in;
        }
        
        .logo {
            position: absolute;
            top: 0.08in;
            right: 0.15in;
            width: 0.4in;
            height: 0.4in;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.15in;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <div class="id-card">
            <!-- Logo placeholder -->
            <div class="logo">SC</div>
            
            <div class="card-header">
                <h3 style="margin: 0; font-size: 0.13in;">KARTU IDENTITAS</h3>
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
                    <div class="qr-code">
                        {!! QrCode::size(44)->generate($user->getQrCodeAttribute()) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>