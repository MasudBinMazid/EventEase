<?php

/**
 * Quick Fix Script for Notice System Issues
 * Run this via: php fix_notice_issues.php
 */

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "EventEase Notice System Quick Fix\n";
echo "=================================\n\n";

try {
    // Check if tables exist and create if missing
    if (!\Illuminate\Support\Facades\Schema::hasTable('notices')) {
        echo "Creating notices table...\n";
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_09_01_173536_create_notices_table.php',
            '--force' => true
        ]);
        echo "✓ Notices table created\n";
    }
    
    if (!\Illuminate\Support\Facades\Schema::hasTable('notice_settings')) {
        echo "Creating notice_settings table...\n";
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_09_01_173647_create_notice_settings_table.php',
            '--force' => true
        ]);
        echo "✓ Notice settings table created\n";
    }
    
    // Run styling migration if needed
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('notices');
    if (!in_array('bg_color', $columns)) {
        echo "Adding styling fields to notices table...\n";
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_09_01_190418_add_styling_fields_to_notices_table.php',
            '--force' => true
        ]);
        echo "✓ Styling fields added\n";
    }
    
    // Clear all caches
    echo "Clearing caches...\n";
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "✓ Caches cleared\n";
    
    // Test the controller
    echo "Testing Notice Controller...\n";
    $controller = new \App\Http\Controllers\Admin\NoticeController();
    $response = $controller->index();
    echo "✓ Notice Controller is working\n";
    
    // Create default settings if they don't exist
    $settings = \App\Models\NoticeSettings::getSettings();
    echo "✓ Notice settings initialized\n";
    
    echo "\n=== SUCCESS ===\n";
    echo "Notice system should be working now!\n";
    echo "You can now access the notice page from your admin panel.\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    // Provide specific solutions based on error type
    if (strpos($e->getMessage(), 'Base table or view already exists') !== false) {
        echo "\nSOLUTION: Tables already exist. Try running: php artisan migrate:status\n";
    } elseif (strpos($e->getMessage(), 'Column not found') !== false) {
        echo "\nSOLUTION: Database schema mismatch. Check the debug script output.\n";
    } elseif (strpos($e->getMessage(), 'Class not found') !== false) {
        echo "\nSOLUTION: Run: composer install && composer dump-autoload\n";
    }
}

echo "\nDone.\n";
