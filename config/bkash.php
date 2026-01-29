<?php

return [
    'app_key' => env('BKASH_LIVE_APP_KEY'),
    'app_secret' => env('BKASH_LIVE_APP_SECRET'),
    'username' => env('BKASH_LIVE_USERNAME'),
    'password' => env('BKASH_LIVE_PASSWORD'),
    'base_url' => 'https://tokenized.pay.bka.sh', // LIVE URL
    'callbackURL' => env('BKASH_CALLBACK_URL'),
];
