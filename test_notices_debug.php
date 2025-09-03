<?php

// Debug script to test notice functionality
require_once 'vendor/autoload.php';

// Create Laravel app instance
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use App\Models\Notice;
use App\Models\NoticeSettings;

echo "=== Notice Table Debug ===\n";

try {
    // Check if tables exist
    echo "Checking if notices table exists: " . (Schema::hasTable('notices') ? 'YES' : 'NO') . "\n";
    echo "Checking if notice_settings table exists: " . (Schema::hasTable('notice_settings') ? 'YES' : 'NO') . "\n";
    
    if (Schema::hasTable('notices')) {
        echo "\n=== Notices Table Columns ===\n";
        $columns = Schema::getColumnListing('notices');
        foreach ($columns as $column) {
            echo "- $column\n";
        }
        
        echo "\n=== Sample Notice Data ===\n";
        $notices = Notice::take(2)->get();
        if ($notices->count() > 0) {
            foreach ($notices as $notice) {
                echo "ID: {$notice->id}, Title: {$notice->title}\n";
                echo "Has priority field: " . (isset($notice->priority) ? 'YES' : 'NO') . "\n";
                echo "Has order field: " . (isset($notice->order) ? 'YES' : 'NO') . "\n";
            }
        } else {
            echo "No notices found in database\n";
        }
    }
    
    if (Schema::hasTable('notice_settings')) {
        echo "\n=== Notice Settings ===\n";
        $settings = NoticeSettings::getSettings();
        echo "Settings exist: " . ($settings ? 'YES' : 'NO') . "\n";
        if ($settings) {
            echo "Is enabled: " . ($settings->is_enabled ? 'YES' : 'NO') . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\nDebug complete.\n";
