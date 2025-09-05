<?php

namespace Database\Seeders;

use App\Models\FeatureBanner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FeatureBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create storage/app/public/banners directory if it doesn't exist
        $bannersPath = storage_path('app/public/banners');
        if (!File::exists($bannersPath)) {
            File::makeDirectory($bannersPath, 0755, true);
        }

        // Copy existing banner images to new location for demo purposes
        $publicPath = public_path('assets/images');
        if (File::exists($publicPath . '/banner1.png')) {
            File::copy($publicPath . '/banner1.png', $bannersPath . '/banner1.png');
        }
        if (File::exists($publicPath . '/banner2.png')) {
            File::copy($publicPath . '/banner2.png', $bannersPath . '/banner2.png');
        }

        // Create sample banners
        $banners = [
            [
                'title' => 'Welcome to EventEase',
                'image' => 'banners/banner1.png',
                'link' => 'https://eventease.laravel.cloud/',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'title' => 'Discover Amazing Events',
                'image' => 'banners/banner2.png',
                'link' => 'https://eventease.laravel.cloud/',
                'is_active' => true,
                'sort_order' => 2
            ]
        ];

        foreach ($banners as $banner) {
            // Only create if the image file exists
            if (File::exists(storage_path('app/public/' . $banner['image']))) {
                FeatureBanner::create($banner);
            }
        }

        $this->command->info('Feature banners seeded successfully!');
    }
}
