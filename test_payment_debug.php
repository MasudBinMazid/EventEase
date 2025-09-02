<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== Payment Gateway Debug Test ===\n\n";

// Test environment variables
echo "Environment Variables:\n";
echo "SSLCOMMERZ_STORE_ID: " . ($_ENV['SSLCOMMERZ_STORE_ID'] ?? 'NOT SET') . "\n";
echo "SSLCOMMERZ_STORE_PASSWORD: " . (isset($_ENV['SSLCOMMERZ_STORE_PASSWORD']) ? '[SET]' : 'NOT SET') . "\n";
echo "SSLCOMMERZ_ENVIRONMENT: " . ($_ENV['SSLCOMMERZ_ENVIRONMENT'] ?? 'NOT SET') . "\n";
echo "SSLCOMMERZ_API_URL: " . ($_ENV['SSLCOMMERZ_API_URL'] ?? 'NOT SET') . "\n";

// Test config
echo "\nConfig Values:\n";
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $storeId = config('sslcommerz.store_id');
    $storePassword = config('sslcommerz.store_password');
    $apiUrl = config('sslcommerz.api_url');
    
    echo "Config store_id: " . ($storeId ?? 'NOT SET') . "\n";
    echo "Config store_password: " . (isset($storePassword) ? '[SET]' : 'NOT SET') . "\n";
    echo "Config api_url: " . ($apiUrl ?? 'NOT SET') . "\n";
    
    // Test service instantiation
    echo "\nTesting SSLCommerz Service:\n";
    $service = new \App\Services\SSLCommerzService();
    echo "✅ SSLCommerz Service instantiated successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Debug Test Complete ===\n";
