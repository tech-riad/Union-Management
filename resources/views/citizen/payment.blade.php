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

                        <!-- Manual Payment Option -->
                        <div class="relative">
                            <input type="radio" 
                                   name="gateway" 
                                   id="gateway_manual" 
                                   value="manual"
                                   class="hidden peer"
                                   data-form-id="manual_form">
                            <label for="gateway_manual" 
                                   class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-green-500 peer-checked:border-green-500 peer-checked:bg-green-50 transition duration-300">
                                <div class="flex-shrink-0 w-12 h-12 mr-4">
                                    <div class="w-12 h-12 bg-yellow-100 rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">অফলাইন পেমেন্ট</h4>
                                    <p class="text-sm text-gray-600">সরাসরি অফিসে পেমেন্ট করুন</p>
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
                <div id="bkash_form" class="gateway-form">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-6">
                        <h4 class="font-semibold text-green-800 mb-3">bKash পেমেন্ট নির্দেশনা</h4>
                        <ul class="space-y-2 text-green-700 text-sm">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>পেমেন্ট বাটনে ক্লিক করলে bKash পেমেন্ট পেজে রিডাইরেক্ট হবেন</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>bKash মোবাইল মেনু থেকে Payment &gt; Merchant &gt; Online Payment সিলেক্ট করুন</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>পেমেন্ট সম্পন্ন হলে স্বয়ংক্রিয়ভাবে সফলতা পেজে রিডাইরেক্ট হবে</span>
                            </li>
                        </ul>
                    </div>

                    <form id="bkashPaymentForm" action="{{ route('citizen.payments.initiate', $invoice) }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="bkash_mobile" class="block text-gray-700 text-sm font-medium mb-2">
                                bKash মোবাইল নম্বর *
                            </label>
                            <input type="text" 
                                   id="bkash_mobile" 
                                   name="mobile"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="01XXXXXXXXX"
                                   pattern="01[3-9]\d{8}"
                                   maxlength="11"
                                   value="01712345678">
                            <p class="text-sm text-gray-500 mt-1">bKash অ্যাকাউন্টের মোবাইল নম্বর দিন</p>
                        </div>

                        <!-- Submit Button for bKash -->
                        <div class="mb-6">
                            <button type="submit" 
                                    id="bkashSubmitBtn"
                                    class="w-full py-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition duration-300">
                                <div class="flex items-center justify-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    bKash পেমেন্ট করুন - ৳ {{ number_format($invoice->amount, 2) }}
                                </div>
                            </button>
                            <p class="text-sm text-center text-gray-500 mt-3">
                                bKash পেমেন্ট পেজে রিডাইরেক্ট হবে
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Manual Payment Form -->
                <div id="manual_form" class="gateway-form hidden">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6">
                        <h4 class="font-semibold text-yellow-800 mb-3">অফলাইন পেমেন্ট নির্দেশনা</h4>
                        <ul class="space-y-2 text-yellow-700 text-sm">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>ইউনিয়ন পরিষদ অফিসে সরাসরি এসে পেমেন্ট করুন</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>পেমেন্ট করার পর সেক্রেটারির কাছে রিসিট দেখান</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>অনুমোদনের জন্য ২৪-৪৮ ঘণ্টা সময় লাগতে পারে</span>
                            </li>
                        </ul>
                    </div>

                    <form id="manualPaymentForm" action="{{ route('citizen.payments.confirm.manual', $invoice) }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="transaction_id" class="block text-gray-700 text-sm font-medium mb-2">
                                ট্রানজেকশন আইডি (ঐচ্ছিক)
                            </label>
                            <input type="text" 
                                   id="transaction_id" 
                                   name="transaction_id"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="ব্যাংক/বিকাশ ট্রানজেকশন আইডি">
                        </div>

                        <div class="mb-6">
                            <label for="payment_date" class="block text-gray-700 text-sm font-medium mb-2">
                                পেমেন্ট তারিখ *
                            </label>
                            <input type="date" 
                                   id="payment_date" 
                                   name="payment_date"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   max="{{ date('Y-m-d') }}"
                                   value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="mb-6">
                            <label for="payment_note" class="block text-gray-700 text-sm font-medium mb-2">
                                মন্তব্য (ঐচ্ছিক)
                            </label>
                            <textarea id="payment_note" 
                                      name="payment_note"
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="পেমেন্ট সম্পর্কিত অতিরিক্ত তথ্য..."></textarea>
                        </div>

                        <!-- Submit Button for Manual Payment -->
                        <div class="mb-6">
                            <button type="submit" 
                                    class="w-full py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-bold rounded-lg hover:from-yellow-600 hover:to-yellow-700 focus:outline-none focus:ring-4 focus:ring-yellow-300 transition duration-300">
                                <div class="flex items-center justify-center">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                    </svg>
                                    অফলাইন পেমেন্ট নিশ্চিত করুন
                                </div>
                            </button>
                            <p class="text-sm text-center text-gray-500 mt-3">
                                পেমেন্ট নিশ্চিত করার পর অ্যাডমিন পর্যালোচনা করবেন
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="hidden text-center py-4">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                    <p class="text-gray-600 mt-2">পেমেন্ট প্রক্রিয়াকরণ হচ্ছে...</p>
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span id="errorText"></span>
                    </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    const errorMessage = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
    
    // Show gateway specific forms based on selection
    document.querySelectorAll('input[name="gateway"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Hide all forms
            document.querySelectorAll('.gateway-form').forEach(form => {
                form.classList.add('hidden');
            });
            
            // Show selected form
            const formId = this.dataset.formId;
            if (formId) {
                document.getElementById(formId).classList.remove('hidden');
            }
        });
    });

    // Initially show bKash form
    document.getElementById('bkash_form').classList.remove('hidden');

    // bKash Form Submission
    const bkashForm = document.getElementById('bkashPaymentForm');
    if (bkashForm) {
        bkashForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const mobileInput = document.getElementById('bkash_mobile');
            const mobile = mobileInput.value;
            
            // Validate mobile number
            if (!/^01[3-9]\d{8}$/.test(mobile)) {
                showError('দয়া করে একটি সঠিক bKash মোবাইল নম্বর দিন (01XXXXXXXXX)');
                mobileInput.focus();
                return;
            }
            
            // Show loading
            const submitBtn = document.getElementById('bkashSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <div class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    প্রক্রিয়াকরণ হচ্ছে...
                </div>
            `;
            errorMessage.classList.add('hidden');
            
            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Redirect to bKash payment page
                    if (data.bkashURL) {
                        window.location.href = data.bkashURL;
                    } else if (data.paymentID) {
                        // If we get paymentID, we need to redirect to bKash
                        window.location.href = '/bkash/test-payment/' + data.paymentID;
                    } else {
                        showError('bKash পেমেন্ট লিঙ্ক পাওয়া যায়নি');
                        resetForm(submitBtn);
                    }
                } else {
                    showError(data.message || 'bKash পেমেন্ট শুরু করতে সমস্যা হয়েছে');
                    resetForm(submitBtn);
                }
            } catch (error) {
                console.error('bKash payment error:', error);
                showError('নেটওয়ার্ক ত্রুটি। অনুগ্রহ করে আবার চেষ্টা করুন');
                resetForm(submitBtn);
            }
        });
    }

    // Manual Payment Form Submission
    const manualForm = document.getElementById('manualPaymentForm');
    if (manualForm) {
        manualForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const paymentDate = document.getElementById('payment_date').value;
            
            // Validate payment date
            if (!paymentDate) {
                showError('দয়া করে পেমেন্ট তারিখ নির্বাচন করুন');
                return;
            }
            
            const today = new Date().toISOString().split('T')[0];
            if (paymentDate > today) {
                showError('পেমেন্ট তারিখ আজকের তারিখের পরে হতে পারবে না');
                return;
            }
            
            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <div class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    নিশ্চিত করা হচ্ছে...
                </div>
            `;
            errorMessage.classList.add('hidden');
            
            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Redirect to success page
                    window.location.href = '{{ route('citizen.payments.success') }}?invoice=' + data.invoice_id;
                } else {
                    showError(data.message || 'অফলাইন পেমেন্ট নিশ্চিত করতে সমস্যা হয়েছে');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `
                        <div class="flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                            </svg>
                            অফলাইন পেমেন্ট নিশ্চিত করুন
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Manual payment error:', error);
                showError('নেটওয়ার্ক ত্রুটি। অনুগ্রহ করে আবার চেষ্টা করুন');
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <div class="flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                        </svg>
                        অফলাইন পেমেন্ট নিশ্চিত করুন
                    </div>
                `;
            }
        });
    }

    // Helper Functions
    function showError(message) {
        errorText.textContent = message;
        errorMessage.classList.remove('hidden');
        errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function resetForm(submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `
            <div class="flex items-center justify-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                bKash পেমেন্ট করুন - ৳ {{ number_format($invoice->amount, 2) }}
            </div>
        `;
    }
});
</script>
@endpush

@endsection