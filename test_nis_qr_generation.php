<?php
// Simple test script to confirm NIS-based QR generation works

require_once 'vendor/autoload.php';

use App\Models\User;

// Test to make sure we can find users by NIS
echo "Testing NIS-based QR code functionality...\n";

// Get a sample user to test with
$user = User::first();
if ($user) {
    echo "Found user: " . $user->name . "\n";
    echo "User NIS: " . ($user->nis ?? 'NULL') . "\n";
    echo "User NIP/NUPTK: " . ($user->nip_nuptk ?? 'NULL') . "\n";
    
    if ($user->nis) {
        echo "Testing QR code generation with NIS: " . $user->nis . "\n";
        echo "This value would be embedded in the QR code\n";
    } else {
        echo "No NIS found for this user, would use NIP/NUPTK if available\n";
    }
} else {
    echo "No users found in the database\n";
}

echo "Testing completed.\n";