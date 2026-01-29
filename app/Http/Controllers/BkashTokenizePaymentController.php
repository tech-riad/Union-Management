<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;

class BkashTokenizePaymentController extends Controller
{
    public function index()
    {
        return view('bkash.payment');
    }

    /**
     * STEP 1: Create Payment
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'invoice_no' => 'required'
        ]);

        $uniqueInvoice = 'INV-'.$request->invoice_no.'-'.uniqid();

        $bkashRequest = [
            'intent' => 'sale',
            'mode' => '0011',
            'payerReference' => $uniqueInvoice,
            'currency' => 'BDT',
            'amount' => number_format($request->amount, 2, '.', ''),
            'merchantInvoiceNumber' => $uniqueInvoice,
            'callbackURL' => route('bkash.callback'),
        ];

        $response = BkashPaymentTokenize::cPayment(json_encode($bkashRequest));

        Log::info('bKash Create Payment', $response);

        if (isset($response['bkashURL'])) {

            session([
                'bkash_payment_id' => $response['paymentID'],
                'invoice_no' => $request->invoice_no,
            ]);

            return response()->json([
                'success' => true,
                'bkashURL' => $response['bkashURL'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response['statusMessage'] ?? 'Payment creation failed'
        ], 422);
    }

    /**
     * STEP 2: Callback (Money deducted here)
     */
    public function callBack(Request $request)
    {
        Log::info('bKash Callback', $request->all());

        if (!$request->paymentID) {
            return BkashPaymentTokenize::failure('Payment ID missing');
        }

        if ($request->status === 'success') {

            $execute = BkashPaymentTokenize::executePayment($request->paymentID)
                ?? BkashPaymentTokenize::queryPayment($request->paymentID);

            Log::info('bKash Execute', $execute);

            if (
                isset($execute['statusCode'], $execute['transactionStatus']) &&
                $execute['statusCode'] === '0000' &&
                $execute['transactionStatus'] === 'Completed'
            ) {
                // ✅ টাকা এখানে সত্যিই কাটা হয়
                return BkashPaymentTokenize::success(
                    'Payment Successful',
                    $execute['trxID']
                );
            }

            return BkashPaymentTokenize::failure(
                $execute['statusMessage'] ?? 'Execution failed'
            );
        }

        if ($request->status === 'cancel') {
            return BkashPaymentTokenize::cancel('Payment cancelled');
        }

        return BkashPaymentTokenize::failure('Payment failed');
    }
}
