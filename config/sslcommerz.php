<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SSLCommerz Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for SSLCommerz payment gateway integration
    |
    */

    // Store Credentials
    'store_id' => env('SSLCOMMERZ_STORE_ID'),
    'store_password' => env('SSLCOMMERZ_STORE_PASSWORD'),

    // API URLs
    'api_url' => env('SSLCOMMERZ_API_URL', 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'),
    'validation_url' => env('SSLCOMMERZ_VALIDATION_URL', 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'),

    // Environment (sandbox/live)
    'environment' => env('SSLCOMMERZ_ENVIRONMENT', 'sandbox'),

    // Callback URLs
    'success_url' => env('SSLCOMMERZ_SUCCESS_URL', env('APP_URL') . '/payment/success'),
    'fail_url' => env('SSLCOMMERZ_FAIL_URL', env('APP_URL') . '/payment/fail'),
    'cancel_url' => env('SSLCOMMERZ_CANCEL_URL', env('APP_URL') . '/payment/cancel'),
    'ipn_url' => env('SSLCOMMERZ_IPN_URL', env('APP_URL') . '/payment/ipn'),

    // Production URLs (when environment is 'live')
    'live' => [
        'api_url' => 'https://securepay.sslcommerz.com/gwprocess/v4/api.php',
        'validation_url' => 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php',
    ],

    // Currency
    'currency' => 'BDT',

    // Default customer info (for testing)
    'default_customer' => [
        'address' => 'Dhaka, Bangladesh',
        'city' => 'Dhaka',
        'state' => 'Dhaka',
        'postcode' => '1000',
        'country' => 'Bangladesh',
    ],
];
