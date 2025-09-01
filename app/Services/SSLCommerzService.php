<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SSLCommerzService
{
    private $storeId;
    private $storePassword;
    private $apiUrl;
    private $validationUrl;
    private $environment;

    public function __construct()
    {
        $this->storeId = config('sslcommerz.store_id', env('SSLCOMMERZ_STORE_ID'));
        $this->storePassword = config('sslcommerz.store_password', env('SSLCOMMERZ_STORE_PASSWORD'));
        $this->apiUrl = config('sslcommerz.api_url', env('SSLCOMMERZ_API_URL'));
        $this->validationUrl = config('sslcommerz.validation_url', env('SSLCOMMERZ_VALIDATION_URL'));
        $this->environment = config('sslcommerz.environment', env('SSLCOMMERZ_ENVIRONMENT', 'sandbox'));
    }

    /**
     * Initialize payment session with SSLCommerz
     */
    public function initiatePayment($paymentData)
    {
        try {
            $postData = [
                // Store Information
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                
                // Transaction Information
                'total_amount' => $paymentData['amount'],
                'currency' => 'BDT',
                'tran_id' => $paymentData['transaction_id'],
                
                // Product Information
                'product_name' => $paymentData['product_name'],
                'product_category' => 'Event Tickets',
                'product_profile' => 'general',
                
                // Customer Information
                'cus_name' => $paymentData['customer_name'],
                'cus_email' => $paymentData['customer_email'],
                'cus_add1' => $paymentData['customer_address'] ?? 'N/A',
                'cus_add2' => 'N/A',
                'cus_city' => $paymentData['customer_city'] ?? 'Dhaka',
                'cus_state' => 'Dhaka',
                'cus_postcode' => '1000',
                'cus_country' => 'Bangladesh',
                'cus_phone' => $paymentData['customer_phone'] ?? 'N/A',
                'cus_fax' => 'N/A',
                
                // Shipment Information
                'ship_name' => $paymentData['customer_name'],
                'ship_add1' => $paymentData['customer_address'] ?? 'N/A',
                'ship_add2' => 'N/A',
                'ship_city' => $paymentData['customer_city'] ?? 'Dhaka',
                'ship_state' => 'Dhaka',
                'ship_postcode' => '1000',
                'ship_country' => 'Bangladesh',
                
                // URLs
                'success_url' => $paymentData['success_url'],
                'fail_url' => $paymentData['fail_url'],
                'cancel_url' => $paymentData['cancel_url'],
                'ipn_url' => $paymentData['ipn_url'],
                
                // Optional Parameters
                'shipping_method' => 'NO',
                'num_of_item' => $paymentData['quantity'] ?? 1,
                'product_amount' => $paymentData['amount'],
                'vat' => 0,
                'discount_amount' => 0,
                'convenience_fee' => 0,
                
                // EMI Information (Optional)
                'emi_option' => 0,
            ];

            $response = Http::withOptions(['verify' => false])
                ->asForm()
                ->post($this->apiUrl, $postData);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if ($responseData['status'] === 'SUCCESS') {
                    return [
                        'success' => true,
                        'data' => $responseData,
                        'payment_url' => $responseData['GatewayPageURL']
                    ];
                } else {
                    Log::error('SSLCommerz Payment Initiation Failed', [
                        'response' => $responseData,
                        'request_data' => $postData
                    ]);
                    
                    return [
                        'success' => false,
                        'message' => $responseData['failedreason'] ?? 'Payment initialization failed',
                        'data' => $responseData
                    ];
                }
            } else {
                Log::error('SSLCommerz API Request Failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to connect to payment gateway',
                    'data' => null
                ];
            }
        } catch (\Exception $e) {
            Log::error('SSLCommerz Payment Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Payment gateway error: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Validate payment transaction
     */
    public function validatePayment($tranId, $amount, $currency = 'BDT')
    {
        try {
            $validationData = [
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'val_id' => $tranId
            ];

            $response = Http::withOptions(['verify' => false])
                ->asForm()
                ->post($this->validationUrl, $validationData);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check if validation is successful
                if ($responseData['status'] === 'VALID' || $responseData['status'] === 'VALIDATED') {
                    // Additional validation checks
                    $isValidAmount = floatval($responseData['amount']) === floatval($amount);
                    $isValidCurrency = $responseData['currency'] === $currency;
                    $isValidStore = $responseData['store_id'] === $this->storeId;
                    
                    if ($isValidAmount && $isValidCurrency && $isValidStore) {
                        return [
                            'valid' => true,
                            'data' => $responseData
                        ];
                    } else {
                        Log::warning('SSLCommerz Payment Validation Mismatch', [
                            'expected_amount' => $amount,
                            'actual_amount' => $responseData['amount'],
                            'expected_currency' => $currency,
                            'actual_currency' => $responseData['currency'],
                            'response' => $responseData
                        ]);
                        
                        return [
                            'valid' => false,
                            'message' => 'Payment validation failed - data mismatch',
                            'data' => $responseData
                        ];
                    }
                } else {
                    return [
                        'valid' => false,
                        'message' => 'Payment validation failed',
                        'data' => $responseData
                    ];
                }
            } else {
                Log::error('SSLCommerz Validation Request Failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return [
                    'valid' => false,
                    'message' => 'Failed to validate payment',
                    'data' => null
                ];
            }
        } catch (\Exception $e) {
            Log::error('SSLCommerz Validation Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'valid' => false,
                'message' => 'Payment validation error: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Check if SSLCommerz is properly configured
     */
    public function isConfigured()
    {
        return !empty($this->storeId) && 
               !empty($this->storePassword) && 
               !empty($this->apiUrl) &&
               $this->storeId !== 'your_sslcommerz_store_id';
    }

    /**
     * Get environment info
     */
    public function getEnvironment()
    {
        return $this->environment;
    }
}
