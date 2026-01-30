<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use Karim007\LaravelBkash\Facades\Bkash;
use App\Http\Controllers\Controller;

class BkashController extends Controller
{
    public function pay(Request $request)
    {
        $invoice = uniqid('INV-');
        $amount  = 500; // dynamic amount

        $payment = Bkash::createPayment([
            'amount' => $amount,
            'invoice' => $invoice,
        ]);

        if (isset($payment['bkashURL'])) {
            return redirect()->away($payment['bkashURL']);
        }

        return back()->with('error', 'bKash Payment Failed');
    }

    public function callback(Request $request)
    {
        if ($request->status !== 'success') {
            return redirect('/payment-failed');
        }

        $execute = Bkash::executePayment($request->paymentID);

        if (isset($execute['statusCode']) && $execute['statusCode'] == '0000') {

            // ✅ SAVE PAYMENT INFO
            // Payment::create([...]);

            return redirect('/payment-success');
        }

        return redirect('/payment-failed');
    }
}
