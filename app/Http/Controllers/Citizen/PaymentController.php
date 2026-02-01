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

        $amount   = $invoice->amount;
        $currency = config('aamarpay.currency');
        $tran_id  = 'inv'.$invoice->id.'-'.Str::random(8);

        $store_id      = config('aamarpay.store_id');
        $signature_key = config('aamarpay.signature_key');
        $endpoint      = config('aamarpay.live_url');

        $rawPhone = '01750114128';
        $phone    = preg_replace('/\D+/', '', (string) $rawPhone);

        if (empty($phone) || strlen($phone) < 10) {
            return back()->with('error', 'Valid phone number required for AmarPay.');
        }

        $payload = [
            'store_id'       => $store_id,
            'tran_id'        => $tran_id,
            'success_url'    => route('citizen.payments.amarpay.uni.manage.success'),
            'fail_url'       => route('citizen.payments.amarpay.uni.manage.fail'),
            'cancel_url'     => route('citizen.payments.amarpay.uni.manage.cancel'),
            'amount'         => (string) $amount,
            'currency'       => $currency,
            'signature_key'  => $signature_key,
            'desc'           => "Invoice #{$invoice->id} Payment",
            'cus_name'       => optional($invoice->user)->name ?? 'Customer',
            'cus_email'      => optional($invoice->user)->email,
            'cus_phone'      => $phone,
            'type'           => 'json',
        ];

        try {
            $response = Http::timeout(30)->post($endpoint, $payload);
        } catch (\Exception $e) {
            Log::error('AmarPay Live Init Failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Payment initiation failed.');
        }

        $data = $response->json();

        AamarpayTransaction::create([
            'invoice_id'       => $invoice->id,
            'tran_id'          => $tran_id,
            'amount'           => $amount,
            'currency'         => $currency,
            'status'           => $data['status'] ?? 'initiated',
            'request_payload'  => json_encode($payload),
            'response_payload' => json_encode($data),
        ]);

        if (!empty($data['payment_url'])) {
            return redirect()->away($data['payment_url']);
        }

        return back()->with('error', 'Unable to redirect to AmarPay.');
    }

    public function success(Request $request)
    {
        $request_id = $request->input('mer_txnid')
            ?? $request->input('request_id');

        if (!$request_id) {
            return redirect()
                ->route('citizen.invoices.index')
                ->with('error', 'Invalid payment response.');
        }

        $verifyUrl = config('aamarpay.verify_live_url');

        try {
            $response = Http::timeout(30)->get($verifyUrl, [
                'request_id'    => $request_id,
                'store_id'      => config('aamarpay.store_id'),
                'signature_key' => config('aamarpay.signature_key'),
                'type'          => 'json',
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('citizen.invoices.index')
                ->with('error', 'Payment verification failed.');
        }

        $data = $response->json();

        $transaction = AamarpayTransaction::where('tran_id', $request_id)->first();

        if (!$transaction) {
            return redirect()->route('citizen.invoices.index');
        }

        if (
            ($data['pay_status'] ?? '') === 'Successful' &&
            ($data['status_code'] ?? '') == '2'
        ) {
            $transaction->update([
                'status' => 'success',
                'response_payload' => json_encode($data),
            ]);

            $invoice = $transaction->invoice;

            $invoice->update([
                'payment_status'  => 'paid',
                'status'          => 'approved',
                'payment_method'  => 'aamarpay',
                'transaction_id'  => $data['bank_trxid'] ?? null,
                'payment_gateway' => $data['payment_type'] ?? null,
                'paid_at'         => now(),
            ]);

            Application::where('invoice_id', $invoice->id)->update([
                'payment_status' => 'paid',
                'status'         => 'approved',
                'paid_at'        => now(),
            ]);

            return redirect()
                ->route('citizen.invoices.index')
                ->with('success', 'Payment successful.');
        }

        $transaction->update([
            'status' => 'failed',
            'response_payload' => json_encode($data),
        ]);

        return redirect()
            ->route('citizen.invoices.index')
            ->with('error', 'Payment failed.');
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
