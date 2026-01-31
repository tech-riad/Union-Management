<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // AmarPay payment routes
        'citizen/payments/amarpay/uni-manage/create/*',
        'citizen/payments/amarpay/uni-manage/success',
        'citizen/payments/amarpay/uni-manage/fail',
        'citizen/payments/amarpay/uni-manage/cancel',
    ];
}
