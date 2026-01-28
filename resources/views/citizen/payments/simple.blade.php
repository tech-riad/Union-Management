@extends('layouts.app')

@section('title', 'bKash পেমেন্ট')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">bKash পেমেন্ট</h2>
            
            <!-- Invoice Info -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">চালান নং:</span>
                    <span class="font-semibold">{{ $invoice->invoice_no }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">পরিমাণ:</span>
                    <span class="font-bold text-green-600">৳ {{ number_format($invoice->amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">স্ট্যাটাস:</span>
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                        বকেয়া
                    </span>
                </div>
            </div>
            
            <!-- Payment Form -->
            <form action="{{ route('citizen.payments.direct', $invoice) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        bKash মোবাইল নম্বর *
                    </label>
                    <input type="text" 
                           name="mobile" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="01XXXXXXXXX"
                           pattern="01[3-9]\d{8}"
                           maxlength="11"
                           required>
                    <p class="text-sm text-gray-500 mt-1">bKash একাউন্টের মোবাইল নম্বর দিন</p>
                </div>
                
                <div class="mb-6">
                    <button type="submit" 
                            class="w-full py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition duration-300">
                        <div class="flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            bKash-এ পেমেন্ট করুন - ৳ {{ number_format($invoice->amount, 2) }}
                        </div>
                    </button>
                </div>
                
                <!-- Instructions -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-800 mb-3">পেমেন্ট নির্দেশনা:</h4>
                    <ol class="list-decimal pl-5 text-sm text-gray-600 space-y-2">
                        <li>মোবাইল নম্বর দিন এবং "bKash-এ পেমেন্ট করুন" বাটনে ক্লিক করুন</li>
                        <li>bKash payment page-এ redirect হবে</li>
                        <li>bKash অ্যাপ/মোবাইলে Payment অপশন সিলেক্ট করুন</li>
                        <li>Merchant: "Online Payment" সিলেক্ট করুন</li>
                        <li>পেমেন্ট সম্পন্ন করুন</li>
                    </ol>
                </div>
            </form>
            
            <!-- Back Button -->
            <div class="mt-6 text-center">
                <a href="{{ route('citizen.invoices.index') }}" 
                   class="text-blue-600 hover:text-blue-800 font-medium">
                    ← ইনভয়েস তালিকায় ফিরে যান
                </a>
            </div>
        </div>
        
        <!-- Test Info (Development Only) -->
        @if(app()->environment('local'))
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h4 class="font-semibold text-yellow-800 mb-2">টেস্ট তথ্য:</h4>
            <p class="text-sm text-yellow-700">
                Sandbox টেস্টের জন্য নিচের তথ্য ব্যবহার করুন:<br>
                <strong>মোবাইল:</strong> 01770618575<br>
                <strong>PIN:</strong> 123456
            </p>
        </div>
        @endif
    </div>
</div>
@endsection