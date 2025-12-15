<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;

class QrCodeController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $qrCode = QrCode::size(200)->generate(route('user.qr.show', ['id' => $user->id]));

        return Response::make($qrCode, 200)->header('Content-Type', 'image/png');
    }

    public function generateForUser($userId)
    {
        $user = User::findOrFail($userId);
        $qrCodeUrl = route('user.qr.show', ['id' => $user->id]);
        $qrCode = QrCode::size(200)->generate($qrCodeUrl);

        return Response::make($qrCode, 200)->header('Content-Type', 'image/png');
    }
}
