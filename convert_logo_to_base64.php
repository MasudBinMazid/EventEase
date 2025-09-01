<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

echo "=== Converting EventEase Logo to Base64 for Email ===\n\n";

$logoPath = public_path('assets/images/logo.png');

if (file_exists($logoPath)) {
    $logoData = file_get_contents($logoPath);
    $base64Logo = base64_encode($logoData);
    $logoSrc = 'data:image/png;base64,' . $base64Logo;
    
    echo "✅ Logo found and converted to base64\n";
    echo "Logo size: " . number_format(strlen($base64Logo)) . " characters\n";
    echo "File size: " . number_format(filesize($logoPath)) . " bytes\n\n";
    
    // Write to a file for easy copying
    $configContent = '<?php
    
// EventEase Logo as Base64 for Email Templates
// This ensures the logo displays in all email clients
return [
    "logo_base64" => "' . $logoSrc . '"
];';
    
    file_put_contents('config/email-assets.php', $configContent);
    echo "✅ Config file created: config/email-assets.php\n";
    echo "You can now use config('email-assets.logo_base64') in your email templates\n";
    
} else {
    echo "❌ Logo file not found at: $logoPath\n";
    echo "Available files in assets/images:\n";
    $files = glob(public_path('assets/images/*'));
    foreach ($files as $file) {
        echo "- " . basename($file) . "\n";
    }
}

echo "\n=== Process completed ===\n";
