@extends('layouts.app')

@section('title', 'bKash টেস্ট পেমেন্ট')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Test Payment Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-green-600 font-bold text-2xl">bK</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">bKash টেস্ট পেমেন্ট</h1>
                <p class="text-gray-600 mt-2">সিমুলেটেড পেমেন্ট পৃষ্ঠা</p>
            </div>
            
            <!-- Info Box -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-yellow-800 text-sm">
                            <strong>দ্রষ্টব্য:</strong> এটি একটি টেস্ট/সিমুলেশন পৃষ্ঠা। কোনো প্রকৃত পেমেন্ট প্রক্রিয়াকরণ হবে না।
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Payment Details -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8">
                <h3 class="font-semibold text-gray-700 mb-4 text-lg">পেমেন্ট বিবরণ</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">পেমেন্ট আইডি:</span>
                        <span class="font-mono text-sm">{{ $paymentID }}</span>
                    </div>
                    
                    @if(isset($transaction->amount) && $transaction->amount)
                    <div class="flex justify-between">
                        <span class="text-gray-600">পরিমাণ:</span>
                        <span class="font-semibold text-green-600">৳ {{ number_format($transaction->amount, 2) }}</span>
                    </div>
                    @else
                    <div class="flex justify-between">
                        <span class="text-gray-600">পরিমাণ:</span>
                        <span class="font-semibold text-green-600">৳ 100.00</span>
                    </div>
                    @endif
                    
                    @if(isset($transaction->invoice) && isset($transaction->invoice->invoice_no))
                    <div class="flex justify-between">
                        <span class="text-gray-600">চালান নং:</span>
                        <span class="font-semibold">{{ $transaction->invoice->invoice_no }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">স্ট্যাটাস:</span>
                        <span class="font-semibold text-yellow-600">টেস্ট মোড</span>
                    </div>
                </div>
            </div>
            
            <!-- Simulation Buttons -->
            <div class="mb-8">
                <h3 class="font-semibold text-gray-700 mb-4 text-lg">পেমেন্ট সিমুলেশন</h3>
                <p class="text-gray-600 text-sm mb-4">
                    নিচের বাটনে ক্লিক করে পেমেন্টের ফলাফল সিমুলেট করুন:
                </p>
                
                <form action="{{ url('/test-bkash-process/' . $paymentID) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <button type="submit" name="status" value="success" 
                                class="w-full py-4 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition duration-300 flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            পেমেন্ট সফল করুন
                        </button>
                        
                        <button type="submit" name="status" value="fail" 
                                class="w-full py-4 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition duration-300 flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            পেমেন্ট ব্যর্থ করুন
                        </button>
                        
                        <button type="submit" name="status" value="cancel" 
                                class="w-full py-4 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-700 transition duration-300 flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            পেমেন্ট বাতিল করুন
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8">
                <h4 class="font-semibold text-blue-800 mb-2">নির্দেশনা:</h4>
                <ul class="text-blue-700 text-sm space-y-1">
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span><strong>পেমেন্ট সফল করুন:</strong> পেমেন্ট সফল হবে এবং আপনি সফলতা পৃষ্ঠায় যাবেন</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span><strong>পেমেন্ট ব্যর্থ করুন:</strong> পেমেন্ট ব্যর্থ হবে এবং ব্যর্থতা পৃষ্ঠায় যাবেন</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span><strong>পেমেন্ট বাতিল করুন:</strong> পেমেন্ট বাতিল হবে এবং ইনভয়েস পৃষ্ঠায় ফিরে যাবেন</span>
                    </li>
                </ul>
            </div>
            
            <!-- Back Button -->
            <div class="text-center">
                @if(isset($transaction->invoice_id) && $transaction->invoice_id)
                <a href="{{ route('citizen.invoices.show', $transaction->invoice_id) }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    ইনভয়েসে ফিরে যান
                </a>
                @else
                <a href="{{ route('citizen.dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    ড্যাশবোর্ডে ফিরে যান
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for fail and cancel buttons
    document.querySelectorAll('button[type="submit"]').forEach(button => {
        if (button.value === 'fail' || button.value === 'cancel') {
            button.addEventListener('click', function(e) {
                const message = this.value === 'fail' 
                    ? 'আপনি কি নিশ্চিত পেমেন্ট ব্যর্থ হবে?' 
                    : 'আপনি কি নিশ্চিত পেমেন্ট বাতিল হবে?';
                
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        }
    });
});
</script>
@endpush
@endsection