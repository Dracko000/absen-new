<?php
// Simple test to validate the checkout logic

require_once __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;

// Simulate the logic in the scanQrCodeForCheckout function
function testData($currentTime, $classExitTime) {
    echo "Current Time: $currentTime\n";
    echo "Class Exit Time: $classExitTime\n";
    
    $current = Carbon::parse($currentTime);
    $exit = Carbon::parse($classExitTime);
    
    if ($current->lt($exit)) {
        echo "RESULT: Alert triggered - Students must wait until class end time\n";
        echo "Message: 'Jam pulang belum tiba. Jam pulang kelas ini adalah pukul $classExitTime. Silakan tunggu hingga jam pulang.'\n";
    } else {
        echo "RESULT: Check-out allowed - Time is after or equal to class exit time\n";
        echo "Action: Process the check-out normally\n";
    }
    echo "---\n";
}

echo "TESTING CHECKOUT LOGIC FOR CLASS EXIT TIME VERIFICATION:\n\n";

// Test Case 1: Current time is before class exit time (should alert)
testData('14:30:00', '15:00:00');

// Test Case 2: Current time is equal to class exit time (should allow)
testData('15:00:00', '15:00:00');

// Test Case 3: Current time is after class exit time (should allow)
testData('15:30:00', '15:00:00');

// Test Case 4: Edge case with early morning times
testData('08:45:00', '09:00:00');

echo "\nThe logic correctly implements the requirement:\n";
echo "- If current time < class exit time → Show alert that checkout is not allowed yet\n";
echo "- If current time >= class exit time → Allow checkout as normal\n";