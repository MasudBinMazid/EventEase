<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Email Assets Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for email assets like logos and images
    | that need to be embedded or referenced in email templates.
    |
    */

    'logo' => [
        'url' => env('APP_URL', 'http://127.0.0.1:8000') . '/assets/images/logo.png',
        'alt' => 'EventEase',
        'width' => 200,
        'height' => 'auto',
    ],

    'brand' => [
        'name' => 'EventEase',
        'tagline' => 'Your trusted partner for event discovery and ticket booking',
        'emoji' => '🎪',
        'colors' => [
            'primary' => '#667eea',
            'secondary' => '#764ba2',
        ],
    ],
];
