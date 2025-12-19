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

        // Check if user has NIS (for students) or NIP/NUPTK (for teachers)
        if (empty($user->nis) && empty($user->nip_nuptk)) {
            return response()->json([
                'success' => false,
                'message' => 'No identification number found for this user'
            ], 400);
        }

        // For teachers, use nip_nuptk if available, otherwise fallback to nis for students
        $identifier = !empty($user->nip_nuptk) ? $user->nip_nuptk : $user->nis;
        $qrCode = QrCode::size(200)->generate($identifier);

        return Response::make($qrCode, 200)->header('Content-Type', 'image/png');
    }

    public function generateForUser($userId)
    {
        $user = User::findOrFail($userId);

        // Check if user has NIS (for students) or NIP/NUPTK (for teachers)
        if (empty($user->nis) && empty($user->nip_nuptk)) {
            return response()->json([
                'success' => false,
                'message' => 'No identification number found for this user'
            ], 400);
        }

        // For teachers, use nip_nuptk if available, otherwise fallback to nis for students
        $identifier = !empty($user->nip_nuptk) ? $user->nip_nuptk : $user->nis;
        $qrCode = QrCode::size(200)->generate($identifier);

        return Response::make($qrCode, 200)->header('Content-Type', 'image/png');
    }

    /**
     * Generate QR code for user with NIS/NIP
     */
    public function generateForUserWithNis($userId)
    {
        $user = User::findOrFail($userId);

        // Check if user has NIS (for students) or NIP/NUPTK (for teachers)
        if (empty($user->nis) && empty($user->nip_nuptk)) {
            return response()->json([
                'success' => false,
                'message' => 'No identification number found for this user'
            ], 400);
        }

        // For teachers, use nip_nuptk if available, otherwise fallback to nis for students
        $identifier = !empty($user->nip_nuptk) ? $user->nip_nuptk : $user->nis;
        $qrCode = QrCode::size(200)->generate($identifier);

        return Response::make($qrCode, 200)->header('Content-Type', 'image/png');
    }

    /**
     * Show QR code for user with NIS/NIP
     */
    public function showWithNis($userId)
    {
        $user = User::findOrFail($userId);

        // Check if user has NIS (for students) or NIP/NUPTK (for teachers)
        if (empty($user->nis) && empty($user->nip_nuptk)) {
            return response()->json([
                'success' => false,
                'message' => 'No identification number found for this user'
            ], 400);
        }

        // For teachers, use nip_nuptk if available, otherwise fallback to nis for students
        $identifier = !empty($user->nip_nuptk) ? $user->nip_nuptk : $user->nis;
        $qrCode = QrCode::size(200)->generate($identifier);

        return Response::make($qrCode, 200)->header('Content-Type', 'image/png');
    }
}
