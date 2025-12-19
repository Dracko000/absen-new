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
        // Generate QR code with NIS instead of user ID
        $qrCode = QrCode::size(200)->generate($user->nis);

        return Response::make($qrCode, 200)->header('Content-Type', 'image/png');
    }

    public function generateForUser($userId)
    {
        $user = User::findOrFail($userId);
        // Generate QR code with NIS instead of user ID
        $qrCode = QrCode::size(200)->generate($user->nis);

        return Response::make($qrCode, 200)->header('Content-Type', 'image/png');
    }

    /**
     * Generate QR code for user with NIS
     */
    public function generateForUserWithNis($userId)
    {
        $user = User::findOrFail($userId);

        // Check if user has NIS
        if (empty($user->nis)) {
            return response()->json([
                'success' => false,
                'message' => 'NIS not found for this user'
            ], 400);
        }

        $qrCode = QrCode::size(200)->generate($user->nis);

        return Response::make($qrCode, 200)->header('Content-Type', 'image/png');
    }

    /**
     * Show QR code for user with NIS
     */
    public function showWithNis($userId)
    {
        $user = User::findOrFail($userId);

        // Check if user has NIS
        if (empty($user->nis)) {
            return response()->json([
                'success' => false,
                'message' => 'NIS not found for this user'
            ], 400);
        }

        $qrCode = QrCode::size(200)->generate($user->nis);

        return Response::make($qrCode, 200)->header('Content-Type', 'image/png');
    }
}
