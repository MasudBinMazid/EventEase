<?php

/**
 * SSLCommerz Configuration Test Script
 * 
 * This script tests if your SSLCommerz payment gateway is properly configured.
 * Run this via: php test_sslcommerz_config.php
 */

require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== SSLCommerz Payment Gateway Configuration Test ===\n\n";

// Check if required environment variables are set
$requiredVars = [
    'SSLCOMMERZ_STORE_ID',
    'SSLCOMMERZ_STORE_PASSWORD', 
    'SSLCOMMERZ_API_URL',
    'SSLCOMMERZ_VALIDATION_URL',
    'SSLCOMMERZ_ENVIRONMENT'
];

$configStatus = true;

foreach ($requiredVars as $var) {
    $value = $_ENV[$var] ?? null;
    
    if (empty($value) || $value === 'your_sslcommerz_store_id' || $value === 'your_sslcommerz_store_password') {
        echo "❌ {$var}: Not configured or using placeholder value\n";
        $configStatus = false;
    } else {
        echo "✅ {$var}: Configured\n";
    }
}

echo "\n";

// Check callback URLs
$callbackUrls = [
    'SSLCOMMERZ_SUCCESS_URL',
    'SSLCOMMERZ_FAIL_URL',
    'SSLCOMMERZ_CANCEL_URL',
    'SSLCOMMERZ_IPN_URL'
];

echo "=== Callback URLs ===\n";
foreach ($callbackUrls as $url) {
    $value = $_ENV[$url] ?? null;
    if (!empty($value)) {
        echo "✅ {$url}: {$value}\n";
    } else {
        echo "⚠️ {$url}: Not configured (will use default)\n";
    }
}

echo "\n";

// Check if required files exist
$requiredFiles = [
    'app/Services/SSLCommerzService.php',
    'app/Http/Controllers/SSLCommerzController.php',
    'config/sslcommerz.php',
    'resources/views/payments/success.blade.php'
];

echo "=== Required Files ===\n";
foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ {$file}: Exists\n";
    } else {
        echo "❌ {$file}: Missing\n";
        $configStatus = false;
    }
}

echo "\n";

// Check routes
$routeFiles = ['routes/web.php'];
echo "=== Route Configuration ===\n";
foreach ($routeFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'SSLCommerzController') !== false && strpos($content, 'sslcommerz') !== false) {
            echo "✅ SSLCommerz Routes: Configured in {$file}\n";
        } else {
            echo "❌ SSLCommerz Routes: Not found in {$file}\n";
            $configStatus = false;
        }
    }
}

echo "\n";

// Check database migration
echo "=== Database Setup ===\n";
$migrationFiles = glob('database/migrations/*add_sslcommerz_fields_to_tickets_table*');
if (!empty($migrationFiles)) {
    echo "✅ SSLCommerz Migration: Found\n";
} else {
    echo "❌ SSLCommerz Migration: Not found\n";
    $configStatus = false;
}

// Check if Guzzle HTTP is available (for API calls)
if (class_exists('GuzzleHttp\Client') || class_exists('Illuminate\Support\Facades\Http')) {
    echo "✅ HTTP Client: Available\n";
} else {
    echo "❌ HTTP Client: Not available\n";
    $configStatus = false;
}

echo "\n";
echo "=== Overall Status ===\n";

if ($configStatus && !empty($_ENV['SSLCOMMERZ_STORE_ID']) && $_ENV['SSLCOMMERZ_STORE_ID'] !== 'your_sslcommerz_store_id') {
    echo "✅ SSLCommerz is properly configured and ready to use!\n";
    echo "\nNext steps:\n";
    echo "1. Run: php artisan serve\n";
    echo "2. Visit your application and create an event\n";
    echo "3. Try purchasing a ticket with 'Pay Now' option\n";
    echo "4. Test the complete payment flow\n";
} else {
    echo "❌ SSLCommerz configuration is incomplete.\n";
    echo "\nTo fix:\n";
    echo "1. Get SSLCommerz sandbox/live credentials\n";
    echo "2. Update your .env file with real credentials:\n";
    echo "   - SSLCOMMERZ_STORE_ID=your_store_id\n";
    echo "   - SSLCOMMERZ_STORE_PASSWORD=your_store_password\n";
    echo "3. Run: php artisan config:clear\n";
    echo "4. Run: php artisan migrate (if not done)\n";
    echo "5. Test again\n";
}

echo "\n=== Configuration Details ===\n";
echo "Store ID: " . ($_ENV['SSLCOMMERZ_STORE_ID'] ?? 'Not set') . "\n";
echo "Store Password: " . (empty($_ENV['SSLCOMMERZ_STORE_PASSWORD']) ? 'Not set' : '[Hidden - ' . strlen($_ENV['SSLCOMMERZ_STORE_PASSWORD']) . ' characters]') . "\n";
echo "Environment: " . ($_ENV['SSLCOMMERZ_ENVIRONMENT'] ?? 'sandbox') . "\n";
echo "API URL: " . ($_ENV['SSLCOMMERZ_API_URL'] ?? 'Not set') . "\n";
echo "Success URL: " . ($_ENV['SSLCOMMERZ_SUCCESS_URL'] ?? 'Not set') . "\n";

echo "\n=== Testing Information ===\n";
echo "For sandbox testing, you can use these test cards:\n";
echo "- Visa: 4111111111111111\n";
echo "- MasterCard: 5555555555554444\n";
echo "- Any CVV: 123\n";
echo "- Any future expiry date\n";

echo "\n";
