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
    'citizen/payments/success',
    'citizen/payments/fail',
    'citizen/payments/cancel',
    // Public AmarPay endpoints (gateway may POST without CSRF token/session)
    'payment/amarpay/success',
    'payment/amarpay/fail',
    'payment/amarpay/cancel',
    'payment/amarpay/callback',
    ];
}
