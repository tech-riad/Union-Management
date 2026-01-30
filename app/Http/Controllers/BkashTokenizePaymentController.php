<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\BkashTransaction;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;

class BkashTokenizePaymentController extends Controller
{
    public function index()
    {
        return view('bkash.payment');
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1'
        ]);

        $invoice = Invoice::findOrFail($request->invoice_no);

        $uniqueInvoice = 'INV-'.$invoice->id.'-'.uniqid();

        $bkashRequest = [
            'intent' => 'sale',
            'mode' => '0011',
            'payerReference' => $uniqueInvoice,
            'currency' => 'BDT',
            'amount' => number_format($invoice->amount, 2, '.', ''),
            'merchantInvoiceNumber' => $uniqueInvoice,
            'callbackURL' => route('bkash.callback'),
        ];

        $response = BkashPaymentTokenize::cPayment(json_encode($bkashRequest));

        if (isset($response['paymentID'])) {
            BkashTransaction::create([
                'invoice_id' => $invoice->id,
                'payment_id' => $response['paymentID'],
                'amount' => $invoice->amount,
                'status' => 'initiated'
            ]);

            return response()->json([
                'success' => true,
                'bkashURL' => $response['bkashURL'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response['statusMessage'] ?? 'Payment failed'
        ], 422);
    }

    public function callBack(Request $request)
    {
        $paymentID = $request->paymentID;
        $status = $request->status;

        if (!$paymentID) {
            return BkashPaymentTokenize::failure('Payment ID missing');
        }

        $payment = BkashTransaction::where('payment_id', $paymentID)->first();

        if (!$payment) {
            return BkashPaymentTokenize::failure('Payment record not found');
        }

        if ($status === 'success') {

            $execute = BkashPaymentTokenize::executePayment($paymentID)
                ?? BkashPaymentTokenize::queryPayment($paymentID);

            if (
                isset($execute['statusCode'], $execute['transactionStatus']) &&
                $execute['statusCode'] === '0000' &&
                $execute['transactionStatus'] === 'Completed'
            ) {

                $payment->update([
                    'trx_id' => $execute['trxID'],
                    'status' => 'paid'
                ]);


                Invoice::where('id', $payment->invoice_id)->update([
                    'payment_status' => 'paid',
                    'payment_method' => 'Bkash',
                    'status' => 'approved',
                    'transaction_id' => $execute['trxID'],
                    'payment_gateway' => 'Bkash',
                    'paid_at' => now()
                ]);
                Application::where('invoice_id', $payment->invoice_id)->update([
                    'payment_status' => 'paid',
                    'paid_at' => now()
                ]);

                return BkashPaymentTokenize::success(
                    'Payment Successful',
                    $execute['trxID']
                );
            }

            return BkashPaymentTokenize::failure(
                $execute['statusMessage'] ?? 'Payment not completed'
            );
        }

        if ($status === 'cancel') {
            $payment->update(['status' => 'cancelled']);
            return BkashPaymentTokenize::cancel('Payment cancelled');
        }

        $payment->update(['status' => 'failed']);
        return BkashPaymentTokenize::failure('Payment failed');
    }

}
