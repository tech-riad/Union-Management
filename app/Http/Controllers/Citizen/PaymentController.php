<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\BkashTransaction;
use App\Models\AamarpayTransaction;
use App\Models\Application;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Karim007\LaravelBkash\Facade\BkashPayment;

class PaymentController extends Controller
{
    public function showPaymentPage(Invoice $invoice)
    {
        if (auth()->id() !== $invoice->user_id) {
            abort(403);
        }

        return view('citizen.payment', compact('invoice'));
    }

    public function createAmarPayPayment(Request $request, Invoice $invoice)
    {
        if (auth()->id() !== $invoice->user_id) {
            abort(403);
        }


        $amount = $invoice->amount;
        $currency = config('aamarpay.currency', 'BDT');

        $tran_id = 'inv'.$invoice->id.'-'.Str::random(8);

        $store_id = config('aamarpay.store_id');
        $signature_key = config('aamarpay.signature_key');
        $endpoint =  config('aamarpay.live_url');

        $rawPhone = '01750114128';
        $phone = preg_replace('/\D+/', '', (string) $rawPhone);

        if (empty($phone) || strlen($phone) < 10) {
            Log::warning('AmarPay: invalid or missing customer phone', ['invoice_id' => $invoice->id, 'raw' => $rawPhone]);
            return redirect()->back()->with('error', 'Valid phone number is required to pay with AmarPay. Please update your profile and try again.');
        }

        $payload = [
            'store_id' => $store_id,
            'tran_id' => $tran_id,
            'success_url' => route('citizen.payments.amarpay.uni.manage.success'),
            'fail_url' => route('citizen.payments.amarpay.uni.manage.fail'),
            'cancel_url' => route('citizen.payments.amarpay.uni.manage.cancel'),
            'amount' => (string) $amount,
            'currency' => $currency,
            'signature_key' => $signature_key,
            'desc' => "Invoice #{$invoice->id} payment",
            'cus_name' => optional($invoice->user)->name ?? 'Customer',
            'cus_email' => optional($invoice->user)->email ?? null,
            'cus_phone' => $phone,
            'type' => 'json'
        ];

        // dd($payload);

        try {
            $response = Http::timeout(30)->post($endpoint, $payload);
        } catch (\Exception $e) {
            Log::error('AmarPay: request failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Could not initiate AmarPay payment. Please try again later.');
        }

        $data = $response->json();
        // dd($data);

        // store initiation record
        $transaction = AamarpayTransaction::create([
            'invoice_id' => $invoice->id,
            'tran_id' => $tran_id,
            'amount' => $amount,
            'currency' => $currency,
            'status' => $data['status'] ?? 'initiated',
            'request_payload' => json_encode($payload),
            'response_payload' => json_encode($data),
        ]);
        // dd($transaction);

        if (!empty($data['payment_url'])) {
            return redirect()->away($data['payment_url']);
        }

        return redirect()->back()->with('error', 'Failed to create AmarPay payment.');
    }

    public function success(Request $request)
    {
        // dd('here');

        $request_id = $request->input('mer_txnid') ?? $request->input('request_id') ?? $request->query('request_id') ?? null;

        if (! $request_id) {
            Log::warning('AmarPay success called without transaction id', ['request' => $request->all(), 'query' => $request->query()]);
            return redirect()->route('citizen.invoices.index')->with('error', 'Invalid AmarPay response (missing transaction id).');
        }

        $store_id = config('aamarpay.store_id');
        $signature_key = config('aamarpay.signature_key');
        $verifyUrl = config('aamarpay.verify_live_url');

        try {
            $response = Http::timeout(30)->get($verifyUrl, [
                'request_id' => $request_id,
                'store_id' => $store_id,
                'signature_key' => $signature_key,
                'type' => 'json'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('citizen.invoices.index')->with('error', 'Could not verify payment.');
        }

        $data = $response->json();

        $transaction = AamarpayTransaction::where('tran_id', $request_id)
            ->orWhere('tran_id', $request->input('tran_id'))
            ->latest()
            ->first();


        if ($transaction) {


            // Check AmarPay response for success
            if (
                (isset($data['pay_status']) && $data['pay_status'] === 'Successful') &&
                (isset($data['status_code']) && $data['status_code'] === '2') &&
                (isset($data['status_title']) && $data['status_title'] === 'Successful Transaction')
            ) {

                $transaction->update([
                    'status' => 'success',
                    'response_payload' => json_encode($data)
                ]);
                // dd($data);
                $invoice = $transaction->invoice;
                // dd( $invoice);

                Invoice::where('id', $invoice->id)->update([
                    'payment_status' => 'paid',
                    'payment_method' => $data['payment_processor'],
                    'status' => 'approved',
                    'transaction_id' => $data['bank_trxid'],
                    'payment_gateway' => $data['payment_type'],
                    'paid_at' => now()
                ]);
                Application::where('invoice_id', $invoice->id)->update([
                    'payment_status' => 'paid',
                    'paid_at' => now()
                ]);

                Log::warning('AmarPay: transaction success', ['invoice' => $invoice]);
                if ($invoice && ! $invoice->isPaid()) {
                    $invoice->markAsPaid('aamarpay', $transaction->tran_id, $data);
                }
            } else {
                $transaction->update([
                    'status' => 'failed',
                    'response_payload' => json_encode($data)
                ]);
            }
        }

        return redirect()->route('citizen.invoices.index')->with('success', 'Payment processed.');
    }

    public function aMarPayCallback(Request $request)
    {
        // Log::info('AmarPay callback received', ['request' => $request->all()]);

        $request_id = $request->input('request_id') ?? $request->input('mer_txnid') ?? null;

        if ($request_id) {
            $transaction = AamarpayTransaction::where('tran_id', $request_id)->first();
            if ($transaction) {
                $transaction->update([
                    'status' => $request->input('status') ?? 'callback_received',
                    'response_payload' => json_encode($request->all())
                ]);
                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false], 400);
    }

    public function fail(Request $request){
        // Log::info('AmarPay payment failed', ['request' => $request->all()]);
        return redirect()->route('citizen.invoices.index')->with('error', 'Payment failed or was declined.');
    }

    public function cancel(){
        return redirect()->route('citizen.invoices.index')->with('warning', 'Payment was cancelled.');
    }

}
