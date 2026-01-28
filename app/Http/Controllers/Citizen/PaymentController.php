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

}
