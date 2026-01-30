<?php

return [
    'sandbox' => env('AAMARPAY_SANDBOX', true),

    'sandbox_url' => env('AAMARPAY_SANDBOX_URL', 'https://sandbox.aamarpay.com/jsonpost.php'),
    'verify_sandbox_url' => env('AAMARPAY_VERIFY_SANDBOX_URL', 'http://sandbox.aamarpay.com/api/v1/trxcheck/request.php'),

    'live_url' => env('AAMARPAY_LIVE_URL', 'https://secure.aamarpay.com/jsonpost.php'),
    'verify_live_url' => env('AAMARPAY_VERIFY_LIVE_URL', 'http://secure.aamarpay.com/api/v1/trxcheck/request.php'),

    'store_id' => env('AAMARPAY_STORE_ID', 'aamarpaytest'),
    'signature_key' => env('AAMARPAY_SIGNATURE_KEY', 'dbb74894e82415a2f7ff0ec3a97e4183'),

    'currency' => env('AAMARPAY_CURRENCY', 'BDT'),
];
