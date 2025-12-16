<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PrintController extends Controller
{
    /**
     * Print a single user's ID card
     */
    public function printIdCard($userId)
    {
        $user = User::findOrFail($userId);

        // Authorize the request - only admins/superadmins can print any user's card
        // Regular users can only print their own card
        if (!Auth::user()->hasRole(['Superadmin', 'Admin'])) {
            if ($user->id !== Auth::id()) {
                abort(403, 'Unauthorized to print this card');
            }
        }

        $data = [
            'user' => $user
        ];

        $pdf = Pdf::loadView('pdf.id-card', $data);
        return $pdf->stream('kartu-identitas-' . $user->name . '.pdf');
    }

    /**
     * Print multiple ID cards (batch printing)
     */
    public function printMultipleIdCards(Request $request)
    {
        $userIds = $request->input('user_ids', []);

        if (empty($userIds)) {
            abort(400, 'Tidak ada pengguna yang dipilih untuk dicetak');
        }

        // Authorize the request
        if (!Auth::user()->hasRole(['Superadmin', 'Admin'])) {
            // Regular users can only print their own card
            $userIds = array_intersect($userIds, [Auth::id()]);
            if (empty($userIds)) {
                abort(403, 'Unauthorized to print these cards');
            }
        }

        $users = User::whereIn('id', $userIds)->get();

        $data = [
            'users' => $users
        ];

        $pdf = Pdf::loadView('pdf.multiple-id-cards', $data);
        return $pdf->stream('kartu-identitas-batch.pdf');
    }

    /**
     * Show preview of ID card
     */
    public function previewIdCard($userId)
    {
        $user = User::findOrFail($userId);

        // Authorize the request
        if (!Auth::user()->hasRole(['Superadmin', 'Admin'])) {
            if ($user->id !== Auth::id()) {
                abort(403, 'Unauthorized to view this card');
            }
        }

        return view('preview.id-card', compact('user'));
    }
}
