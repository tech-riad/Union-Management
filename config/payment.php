<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    */
    
    'default_gateway' => env('PAYMENT_DEFAULT_GATEWAY', 'bkash'),
    
    'gateways' => [
        'bkash' => [
            'name' => 'bKash',
            'logo' => 'images/payment/bkash.png',
            'description' => 'বাংলাদেশের সবচেয়ে জনপ্রিয় মোবাইল ফিন্যান্সিয়াল সার্ভিস',
            'active' => env('BKASH_ACTIVE', true),
            'sandbox' => env('BKASH_SANDBOX', true),
            
            // Sandbox credentials (For Testing)
            'sandbox_credentials' => [
                'app_key' => '5tunt4masn6pv2hnvte1sb5n3j',
                'app_secret' => '1vggbqd4hqk9g96o9rrrp2jftvek578v7d2bnerim12a87dbrrka',
                'username' => 'sandboxTestUser',
                'password' => 'hWD@8vtzw0',
            ],
            
            // Live credentials (For Production)
            'live_credentials' => [
                'app_key' => env('BKASH_APP_KEY'),
                'app_secret' => env('BKASH_APP_SECRET'),
                'username' => env('BKASH_USERNAME'),
                'password' => env('BKASH_PASSWORD'),
            ],
            
            // API URLs
            'sandbox_url' => 'https://tokenized.sandbox.bka.sh/v1.2.0-beta',
            'live_url' => 'https://tokenized.pay.bka.sh/v1.2.0-beta',
            
            // Callback URLs
            'callback_url' => env('BKASH_CALLBACK_URL', 'http://localhost:8000/payment/bkash/callback'),
            'success_url' => env('BKASH_SUCCESS_URL', 'http://localhost:8000/payment/success'),
            'fail_url' => env('BKASH_FAIL_URL', 'http://localhost:8000/payment/failed'),
            
            // Merchant Account
            'merchant_account_number' => env('BKASH_MERCHANT_ACCOUNT', '01770618575'),
        ],
        
        'amarpay' => [
            'name' => 'AmarPay',
            'logo' => 'images/payment/amarpay.png',
            'description' => 'বাংলাদেশের সেরা পেমেন্ট গেটওয়ে সার্ভিস',
            'active' => env('AMARPAY_ACTIVE', true),
            'sandbox' => env('AMARPAY_SANDBOX', true),
            
            // Sandbox credentials (For Testing)
            'sandbox_credentials' => [
                'store_id' => 'aamarpaytest',
                'signature_key' => 'dbb74894e82415a2f7ff0ec3a97e4183',
                'api_key' => '28c78c8c6d9b45b5b4757b6d4c28c78c',
            ],
            
            // Live credentials (For Production)
            'live_credentials' => [
                'store_id' => env('AMARPAY_STORE_ID'),
                'signature_key' => env('AMARPAY_SIGNATURE_KEY'),
                'api_key' => env('AMARPAY_API_KEY'),
            ],
            
            // API URLs
            'sandbox_url' => 'https://sandbox.aamarpay.com',
            'live_url' => 'https://secure.aamarpay.com',
            
            // Callback URLs
            'callback_url' => env('AMARPAY_CALLBACK_URL', 'http://localhost:8000/payment/amarpay/callback'),
            'success_url' => env('AMARPAY_SUCCESS_URL', 'http://localhost:8000/payment/success'),
            'fail_url' => env('AMARPAY_FAIL_URL', 'http://localhost:8000/payment/failed'),
            
            // Payment Methods
            'payment_methods' => [
                'cards' => ['visa', 'mastercard', 'amex', 'dbbl_nexus'],
                'mobile_banking' => ['bkash', 'nagad', 'rocket', 'upay', 'tap'],
                'internet_banking' => ['dbbl', 'ibbl', 'city', 'prime', 'bankasia'],
            ],
            
            // Settings
            'currency' => 'BDT',
            'transaction_charge' => 1.5, // 1.5% transaction charge
            'minimum_amount' => 10,
            'maximum_amount' => 100000,
        ],
    ],
    
    // Currency Settings
    'currency' => [
        'code' => 'BDT',
        'symbol' => '৳',
        'name' => 'বাংলাদেশী টাকা',
        'decimal' => 2,
    ],
    
    // Payment Settings
    'settings' => [
        'success_redirect' => '/citizen/invoices',
        'failure_redirect' => '/citizen/invoices',
        'timeout' => 300, // 5 minutes
        'retry_limit' => 3,
        'enable_logging' => true,
        'auto_verify' => true,
    ],
    
    // Transaction Status Codes
    'status_codes' => [
        'initiated' => 'INITIATED',
        'pending' => 'PENDING',
        'processing' => 'PROCESSING',
        'completed' => 'COMPLETED',
        'failed' => 'FAILED',
        'cancelled' => 'CANCELLED',
        'refunded' => 'REFUNDED',
    ],
    
    // Webhook Settings
    'webhooks' => [
        'enabled' => env('PAYMENT_WEBHOOK_ENABLED', false),
        'secret' => env('PAYMENT_WEBHOOK_SECRET'),
        'url' => env('APP_URL') . '/api/webhook/payment',
    ],
];