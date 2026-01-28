<?php
namespace App\Http\Controllers;

use App\Services\BkashPaymentService;
use App\Models\Invoice;
use App\Models\BkashTransaction;
use Illuminate\Http\Request;

class BkashController extends Controller
{
    protected $bkashService;

    public function __construct(BkashPaymentService $bkashService)
    {
        $this->bkashService = $bkashService;
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        
        // Create bKash transaction record
        $bkashTransaction = BkashTransaction::create([
            'invoice_id' => $invoice->id,
            'amount' => $invoice->total_amount,
            'status' => 'pending',
        ]);

        // Call bKash API
        $response = $this->bkashService->createPayment(
            $invoice->id,
            $invoice->total_amount,
            $invoice->invoice_number
        );

        if (isset($response['paymentID'])) {
            $bkashTransaction->update([
                'payment_id' => $response['paymentID'],
                'request_payload' => $response,
            ]);

            return response()->json([
                'success' => true,
                'paymentID' => $response['paymentID'],
                'bkashURL' => $response['bkashURL'] ?? null,
                'redirect' => $response['paymentID'] ? true : false,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment creation failed',
        ], 400);
    }

    public function callback(Request $request)
    {
        $paymentID = $request->paymentID;
        $status = $request->status;
        
        // Find transaction
        $transaction = BkashTransaction::where('payment_id', $paymentID)->first();
        
        if ($transaction && $status == 'success') {
            // Execute payment
            $executeResponse = $this->bkashService->executePayment($paymentID);
            
            if ($executeResponse['transactionStatus'] == 'Completed') {
                $transaction->update([
                    'status' => 'completed',
                    'trx_id' => $executeResponse['trxID'],
                    'response_payload' => $executeResponse,
                    'payment_time' => now(),
                ]);
                
                // Update invoice status
                $transaction->invoice->update([
                    'payment_status' => 'paid',
                    'payment_method' => 'bkash',
                ]);
                
                return redirect()->route('invoice.show', $transaction->invoice_id)
                    ->with('success', 'Payment completed successfully!');
            }
        }
        
        return redirect()->route('invoice.show', $transaction->invoice_id)
            ->with('error', 'Payment failed or cancelled.');
    }
}