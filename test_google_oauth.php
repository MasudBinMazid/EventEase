<?php

/**
 * Google OAuth Configuration Test Script
 * 
 * This script tests if your Google OAuth is properly configured.
 * Run this via: php test_google_oauth.php
 */

require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== Google OAuth Configuration Test ===\n\n";

// Check if required environment variables are set
$requiredVars = ['GOOGLE_CLIENT_ID', 'GOOGLE_CLIENT_SECRET', 'GOOGLE_REDIRECT_URI'];
$configStatus = true;

foreach ($requiredVars as $var) {
    $value = $_ENV[$var] ?? null;
    
    if (empty($value) || $value === 'your_google_client_id_here' || $value === 'your_google_client_secret_here') {
        echo "❌ {$var}: Not configured or using placeholder value\n";
        $configStatus = false;
    } else {
        echo "✅ {$var}: Configured\n";
    }
}

echo "\n";

// Check if Laravel Socialite is installed
if (class_exists('Laravel\Socialite\Facades\Socialite')) {
    echo "✅ Laravel Socialite: Installed\n";
} else {
    echo "❌ Laravel Socialite: Not installed\n";
    $configStatus = false;
}

// Check if routes are accessible (basic check)
$routeFiles = ['routes/web.php'];
foreach ($routeFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'auth/google') !== false && strpos($content, 'SocialController') !== false) {
            echo "✅ Google OAuth Routes: Configured in {$file}\n";
        } else {
            echo "❌ Google OAuth Routes: Not found in {$file}\n";
            $configStatus = false;
        }
    }
}

// Check if SocialController exists
$controllerPath = 'app/Http/Controllers/Auth/SocialController.php';
if (file_exists($controllerPath)) {
    echo "✅ SocialController: Exists\n";
} else {
    echo "❌ SocialController: Missing\n";
    $configStatus = false;
}

// Check database configuration
$dbConnection = $_ENV['DB_CONNECTION'] ?? null;
if ($dbConnection) {
    echo "✅ Database Connection: {$dbConnection}\n";
} else {
    echo "❌ Database Connection: Not configured\n";
    $configStatus = false;
}

echo "\n";
echo "=== Overall Status ===\n";

if ($configStatus && !empty($_ENV['GOOGLE_CLIENT_ID']) && $_ENV['GOOGLE_CLIENT_ID'] !== 'your_google_client_id_here') {
    echo "✅ Google OAuth is properly configured and ready to use!\n";
    echo "\nNext steps:\n";
    echo "1. Run: php artisan serve\n";
    echo "2. Visit your application\n";
    echo "3. Test the Google login functionality\n";
} else {
    echo "❌ Google OAuth configuration is incomplete.\n";
    echo "\nTo fix:\n";
    echo "1. Get Google OAuth credentials from Google Cloud Console\n";
    echo "2. Update your .env file with real credentials\n";
    echo "3. Run: php artisan config:clear\n";
    echo "4. Test again\n";
}

echo "\n=== Configuration Details ===\n";
echo "Google Client ID: " . ($_ENV['GOOGLE_CLIENT_ID'] ?? 'Not set') . "\n";
echo "Google Client Secret: " . (empty($_ENV['GOOGLE_CLIENT_SECRET']) ? 'Not set' : '[Hidden - ' . strlen($_ENV['GOOGLE_CLIENT_SECRET']) . ' characters]') . "\n";
echo "Redirect URI: " . ($_ENV['GOOGLE_REDIRECT_URI'] ?? 'Not set') . "\n";
echo "App URL: " . ($_ENV['APP_URL'] ?? 'Not set') . "\n";

echo "\n";
