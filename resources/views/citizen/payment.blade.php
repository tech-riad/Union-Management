@extends('layouts.app')

@section('title', 'পেমেন্ট করুন - ' . $invoice->invoice_no)

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- পেমেন্ট হেডার -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">
                <span class="text-green-600">পেমেন্ট</span> করুন
            </h1>
            <p class="text-gray-600 text-lg">
                আপনার চালানের পেমেন্ট সম্পন্ন করুন
            </p>
        </div>

        <!-- ইনভয়েস কার্ড -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ইনভয়েস তথ্য -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">চালান তথ্য</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">চালান নং:</span>
                            <span class="font-semibold">{{ $invoice->invoice_no }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">সেবার ধরন:</span>
                            <span class="font-semibold">{{ optional($invoice->application->certificateType)->name ?? 'সনদ সেবা' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">তারিখ:</span>
                            <span class="font-semibold">{{ $invoice->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">স্ট্যাটাস:</span>
                            <span class="font-semibold {{ $invoice->payment_status === 'paid' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $invoice->payment_status === 'paid' ? 'পরিশোধিত' : 'বকেয়া' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- পেমেন্ট তথ্য -->
                <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-5">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">পেমেন্ট বিবরণ</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">মূল ফি:</span>
                            <span class="font-semibold">৳ {{ number_format($invoice->amount - ($invoice->vat_amount + $invoice->service_charge), 2) }}</span>
                        </div>
                        @if($invoice->vat_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">ভ্যাট (১৫%):</span>
                            <span class="font-semibold">৳ {{ number_format($invoice->vat_amount, 2) }}</span>
                        </div>
                        @endif
                        @if($invoice->service_charge > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">সার্ভিস চার্জ:</span>
                            <span class="font-semibold">৳ {{ number_format($invoice->service_charge, 2) }}</span>
                        </div>
                        @endif
                        <div class="pt-2 border-t border-gray-300">
                            <div class="flex justify-between text-lg font-bold">
                                <span class="text-gray-800">মোট:</span>
                                <span class="text-green-600">৳ {{ number_format($invoice->amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- পেমেন্ট গেটওয়ে সিলেকশন -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">পেমেন্ট মাধ্যম নির্বাচন করুন</h3>

            <div id="paymentForm">
                <!-- Gateway Selection -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        পেমেন্ট মাধ্যম *
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- bKash Option -->
                        <div class="relative">
                            <input type="radio"
                                   name="gateway"
                                   id="gateway_bkash"
                                   value="bkash"
                                   class="hidden peer"
                                   data-form-id="bkash_form"
                                   checked>
                            <label for="gateway_bkash"
                                   class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-green-500 peer-checked:border-green-500 peer-checked:bg-green-50 transition duration-300">
                                <div class="flex-shrink-0 w-12 h-12 mr-4">
                                    <div class="w-12 h-12 bg-green-100 rounded flex items-center justify-center">
                                        <span class="text-green-600 font-bold text-lg">bK</span>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">bKash</h4>
                                    <p class="text-sm text-gray-600">bKash অ্যাকাউন্ট থেকে পেমেন্ট করুন</p>
                                </div>
                                <svg class="w-6 h-6 text-green-500 ml-auto hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Gateway Specific Forms -->

                <!-- bKash Form -->
                <div>
                    <form id="bkashPaymentForm">
                        @csrf
                        <div class="mb-6">
                            <button type="button" id="bkashSubmitBtn" class="btn-danger w-full py-3 rounded-lg font-bold">
                                bKash পেমেন্ট করুন - ৳ {{ number_format($invoice->amount, 2) }}
                            </button>

                            <p class="text-sm text-center text-gray-500 mt-3">
                                bKash পেমেন্ট পেজে রিডাইরেক্ট হবে
                            </p>
                        </div>
                    </form>
                </div>

            </div>



            <!-- General Payment Instructions -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-3">সাধারণ নির্দেশনা</h4>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>পেমেন্ট সম্পন্ন হওয়ার পর স্বয়ংক্রিয়ভাবে চালানের স্ট্যাটাস আপডেট হবে</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>কোন সমস্যা হলে সাহায্যের জন্য কল করুন: ০১৭১২-৩৪৫৬৭৮</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>অফিস সময়: সকাল ৯টা - বিকাল ৫টা (শুক্রবার ও সরকারি ছুটির দিন বন্ধ)</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-8">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-800 mb-1">নিরাপত্তা নোটিশ</h4>
                    <p class="text-blue-700 text-sm">
                        • আপনার পেমেন্ট তথ্য নিরাপদে প্রক্রিয়াকরণ করা হয়<br>
                        • bKash সরাসরি আপনার পেমেন্ট পরিচালনা করে<br>
                        • কোনও পেমেন্ট তথ্য আমরা সংরক্ষণ করি না<br>
                        • SSL সিকিউরিটি দ্বারা সুরক্ষিত
                    </p>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('citizen.invoices.index') }}"
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                ইনভয়েস তালিকায় ফিরে যান
            </a>
        </div>
    </div>
</div>

@push('scripts')

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const btn = document.getElementById('bkashSubmitBtn');

                    btn.addEventListener('click', function () {
                        const token = document.querySelector('input[name="_token"]').value;

                        // Disable button to prevent multiple clicks
                        btn.disabled = true;
                        btn.innerText = 'Processing...';

                        fetch("{{ route('citizen.payments.initiate', $invoice) }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": token,
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                // If you want, you can send mobile number or other data here
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            btn.disabled = false;
                            btn.innerText = `bKash পেমেন্ট করুন - ৳ {{ number_format($invoice->amount, 2) }}`;

                            if(data.success && data.redirect_url){
                                // Redirect to bKash payment page
                                window.location.href = data.redirect_url;
                            } else if(data.error){
                                alert(data.error);
                            } else {
                                alert('Something went wrong. Please try again.');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            btn.disabled = false;
                            btn.innerText = `bKash পেমেন্ট করুন - ৳ {{ number_format($invoice->amount, 2) }}`;
                            alert('Server error! Please try again.');
                        });
                    });
                });
            </script>

@endpush

@endsection
