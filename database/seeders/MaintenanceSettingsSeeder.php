<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MaintenanceSettings;

class MaintenanceSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default maintenance settings if none exist
        if (!MaintenanceSettings::exists()) {
            MaintenanceSettings::create([
                'is_enabled' => false,
                'title' => 'Site Under Maintenance',
                'message' => 'We are currently performing maintenance on our website. We will be back online shortly!',
                'estimated_completion' => null,
                'allowed_ips' => null,
                'updated_by' => null,
            ]);
        }
    }
}
