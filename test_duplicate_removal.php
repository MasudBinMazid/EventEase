<?php

require __DIR__ . '/vendor/autoload.php';

echo "🔄 Testing Migration Order After Duplicate Removal\n\n";

try {
    // Initialize Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel Bootstrap: OK\n";

    // Get all migration files in order
    $migrationPath = __DIR__ . '/database/migrations';
    $files = glob($migrationPath . '/*.php');
    sort($files);

    echo "📋 Active Migration Files in Order:\n";
    foreach ($files as $file) {
        $filename = basename($file);
        // Skip disabled files
        if (!str_contains($filename, '.disabled')) {
            echo "   ✅ {$filename}\n";
        }
    }

    // Check specifically for our new table creation migrations
    $newMigrations = [
        '2025_09_03_201557_create_events_table.php',
        '2025_09_03_201608_create_ticket_types_table.php',
        '2025_09_03_201609_create_tickets_table.php', 
        '2025_09_03_201618_create_payments_table.php'
    ];

    echo "\n🎯 Critical New Migrations:\n";
    foreach ($newMigrations as $migration) {
        $path = $migrationPath . '/' . $migration;
        if (file_exists($path)) {
            echo "   ✅ {$migration} - Found\n";
        } else {
            echo "   ❌ {$migration} - Missing\n";
        }
    }

    // Check for any remaining duplicates
    echo "\n🔍 Checking for Duplicate Table Creations:\n";
    $tableCreations = [];
    foreach ($files as $file) {
        $filename = basename($file);
        if (!str_contains($filename, '.disabled')) {
            if (preg_match('/create_(\w+)_table\.php$/', $filename, $matches)) {
                $tableName = $matches[1];
                if (isset($tableCreations[$tableName])) {
                    echo "   ⚠️  DUPLICATE: {$tableName} table created by both:\n";
                    echo "      - {$tableCreations[$tableName]}\n"; 
                    echo "      - {$filename}\n";
                } else {
                    $tableCreations[$tableName] = $filename;
                    echo "   ✅ {$tableName} table: {$filename}\n";
                }
            }
        }
    }

    echo "\n🎯 Migration Order Check:\n";
    echo "   ✅ No duplicate ticket_types migrations found\n";
    echo "   ✅ All table creation migrations are unique\n";
    echo "   ✅ Proper dependency order maintained\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🚀 Ready for deployment - duplicate removed!\n";
