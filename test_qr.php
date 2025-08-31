<?php

require __DIR__ . '/vendor/autoload.php';

// Initialize Laravel app for access to services
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "Testing QR Service...\n";

try {
    $qr = App\Services\QrCodeService::generateBase64Png('TEST123');
    echo "Success: " . (strlen($qr) > 100 ? 'YES' : 'NO') . "\n";
    echo "QR length: " . strlen($qr) . "\n";
    echo "Starts with data:image/png: " . (strpos($qr, 'data:image/png') === 0 ? 'YES' : 'NO') . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";
