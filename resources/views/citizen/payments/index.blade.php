@extends('layouts.app')

@section('title', 'পেমেন্ট করুন - ' . $invoice->invoice_no)

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4">

        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                <span class="text-green-600">পেমেন্ট</span> করুন
            </h1>
            <p class="text-gray-600">আপনার চালানের পেমেন্ট সম্পন্ন করুন</p>
        </div>

        {{-- Invoice Info --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold mb-3">চালান তথ্য</h3>
                    <p>চালান নং: <strong>{{ $invoice->invoice_no }}</strong></p>
                    <p>তারিখ: {{ $invoice->created_at->format('d/m/Y') }}</p>
                    <p>
                        স্ট্যাটাস:
                        <span class="{{ $invoice->payment_status == 'paid' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $invoice->payment_status == 'paid' ? 'পরিশোধিত' : 'বকেয়া' }}
                        </span>
                    </p>
                </div>

                <div class="bg-green-50 p-4 rounded">
                    <h3 class="font-semibold mb-3">পেমেন্ট সারাংশ</h3>
                    <p class="flex justify-between">
                        <span>মোট পরিমাণ</span>
                        <strong class="text-green-600">৳ {{ number_format($invoice->amount, 2) }}</strong>
                    </p>
                </div>
            </div>
        </div>

        {{-- Payment Gateway --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="font-semibold mb-4">পেমেন্ট মাধ্যম নির্বাচন করুন</h3>

            {{-- bKash --}}
            <div class="border rounded-lg p-4 mb-4 bg-green-50">
                <h4 class="font-semibold text-green-700 mb-2">bKash</h4>
                <p class="text-sm text-gray-600 mb-4">
                    bKash অফিসিয়াল পেমেন্ট পেজে রিডাইরেক্ট হবে
                </p>

                <form action="{{ route('citizen.bkash.create.invoice', $invoice) }}" method="POST">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $invoice->amount }}">

                    <button type="submit"
                            class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg">
                        bKash পেমেন্ট করুন – ৳ {{ number_format($invoice->amount, 2) }}
                    </button>
                </form>
            </div>

            {{-- AmarPay (placeholder) --}}
            <div class="border rounded-lg p-4 mb-4 bg-purple-50">
                <h4 class="font-semibold text-purple-700">AmarPay</h4>
                <p class="text-sm text-gray-600">কার্ড / ব্যাংক / মোবাইল ব্যাংকিং</p>
                <p class="text-xs text-gray-500 mt-2">শীঘ্রই সংযুক্ত হবে</p>
            </div>

            {{-- Manual Payment --}}
            <div class="border rounded-lg p-4 bg-yellow-50">
                <h4 class="font-semibold text-yellow-700 mb-2">অফলাইন পেমেন্ট</h4>
                <p class="text-sm text-gray-600 mb-4">
                    অফিসে সরাসরি এসে পেমেন্ট করুন
                </p>

                <form action="{{ route('citizen.payments.confirm.manual', $invoice) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="text-sm">পেমেন্ট তারিখ</label>
                        <input type="date" name="payment_date" required
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-bold rounded-lg">
                        অফলাইন পেমেন্ট নিশ্চিত করুন
                    </button>
                </form>
            </div>
        </div>

        {{-- Security Notice --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h4 class="font-semibold text-blue-700">নিরাপত্তা তথ্য</h4>
            <p class="text-sm text-blue-600 mt-1">
                bKash সরাসরি আপনার পেমেন্ট পরিচালনা করে। আমরা কোনো সংবেদনশীল তথ্য সংরক্ষণ করি না।
            </p>
        </div>

        {{-- Back --}}
        <div class="text-center">
            <a href="{{ route('citizen.invoices.index') }}"
               class="inline-block px-6 py-2 border rounded hover:bg-gray-100">
                ← ইনভয়েস তালিকায় ফিরে যান
            </a>
        </div>

    </div>
</div>
@endsection
