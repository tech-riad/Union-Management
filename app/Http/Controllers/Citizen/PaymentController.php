<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\BkashTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Karim007\LaravelBkash\Facade\BkashPayment;

class PaymentController extends Controller
{
    public function showPaymentPage(Invoice $invoice)
    {
        if (auth()->id() !== $invoice->user_id) {
            abort(403);
        }
        // dd($invoice);

        return view('citizen.payment', compact('invoice'));
    }

    // public function initiatePayment(Invoice $invoice)
    // {
    //     if (auth()->id() !== $invoice->user_id) {
    //         abort(403);
    //     }

    //     try {
    //         // Prepare request payload
    //         $request_data = [
    //             'amount'  => $invoice->amount,
    //             'intent'  => 'sale',
    //             'invoice' => $invoice->invoice_no,
    //         ];

    //         // 🔹 Initiate bKash Payment
    //         $response = BkashPayment::cPayment(json_encode($request_data));

    //         if (!isset($response['bkashURL'])) {
    //             Log::error('bKash cPayment failed', $response);
    //             return back()->with('error', 'bKash পেমেন্ট শুরু করা যায়নি');
    //         }

    //         // Save transaction
    //         BkashTransaction::create([
    //             'invoice_id'      => $invoice->id,
    //             'payment_id'      => $response['paymentID'] ?? null,
    //             'amount'          => $invoice->amount,
    //             'currency'        => 'BDT',
    //             'status'          => 'pending',
    //             'request_payload' => json_encode($request_data),
    //         ]);

    //         // Redirect to bKash checkout
    //         return redirect()->away($response['bkashURL']);

    //     } catch (\Exception $e) {
    //         Log::error('bKash initiate error', ['msg' => $e->getMessage()]);
    //         return back()->with('error', $e->getMessage());
    //     }
    // }


    public function initiatePayment(Request $request, Invoice $invoice)
    {
        // dd($request->all());
        // Optional: validate input if needed
        // $request->validate(['mobile' => 'required|regex:/^01[3-9]\d{8}$/']);

        try {
            // 1️⃣ Call bKash API to initiate payment
            // For demo, let's simulate a redirect URL:
            $redirectUrl = 'https://bKash.com/payment?invoice=' . $invoice->id;

            return response()->json([
                'success' => true,
                'redirect_url' => $redirectUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function callback(Request $request)
    {
        try {
            $paymentID = $request->paymentID;

            if (!$paymentID) {
                return redirect()->route('citizen.dashboard')
                    ->with('error', 'Invalid payment response');
            }

            // 🔹 Execute Payment
            $response = BkashPayment::executePayment($paymentID);

            if (($response['statusCode'] ?? '') !== '0000') {
                return redirect()->route('citizen.dashboard')
                    ->with('error', $response['statusMessage'] ?? 'Payment failed');
            }

            $transaction = BkashTransaction::where('payment_id', $paymentID)->firstOrFail();
            $invoice = $transaction->invoice;

            $transaction->update([
                'status'           => 'success',
                'response_payload' => json_encode($response),
                'payment_time'     => now(),
            ]);

            $invoice->update([
                'payment_status' => 'paid',
                'payment_method' => 'bkash',
                'paid_at'        => now(),
            ]);

            return redirect()
                ->route('citizen.invoices.show', $invoice)
                ->with('success', 'bKash পেমেন্ট সফল হয়েছে');

        } catch (\Exception $e) {
            Log::error('bKash callback error', ['msg' => $e->getMessage()]);
            return redirect()->route('citizen.dashboard')
                ->with('error', $e->getMessage());
        }
    }
}
