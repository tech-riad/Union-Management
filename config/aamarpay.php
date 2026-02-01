<?php

return [
    'store_id' => env('AAMARPAY_STORE_ID'),
    'signature_key' => env('AAMARPAY_SIGNATURE_KEY'),
    'live_url' => env('AAMARPAY_LIVE_URL'),
    'verify_live_url' => env('AAMARPAY_VERIFY_LIVE_URL'),
    'currency' => env('AAMARPAY_CURRENCY', 'BDT'),
];
