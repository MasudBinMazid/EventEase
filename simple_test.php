<?php
echo "=== Payment Gateway Configuration Test ===\n\n";

// Load .env file directly
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $env[trim($key)] = trim($value, '"');
        }
    }
    
    echo "Environment Variables from .env:\n";
    $sslKeys = ['SSLCOMMERZ_STORE_ID', 'SSLCOMMERZ_STORE_PASSWORD', 'SSLCOMMERZ_ENVIRONMENT', 'SSLCOMMERZ_API_URL'];
    foreach ($sslKeys as $key) {
        echo "$key: " . (isset($env[$key]) ? $env[$key] : 'NOT SET') . "\n";
    }
}

echo "\n=== Test Complete ===\n";
