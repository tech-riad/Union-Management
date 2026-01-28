@extends('layouts.app')

@section('title', 'পেমেন্ট ব্যর্থ')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-red-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Error Icon -->
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100 mb-8">
                <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            
            <!-- Error Message -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                পেমেন্ট সম্পন্ন হয়নি
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                দুঃখিত, আপনার পেমেন্ট প্রক্রিয়াকরণে সমস্যা হয়েছে।
            </p>
            
            <!-- Error Details -->
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-8">
                {{ session('error') }}
            </div>
            @endif
            
            <!-- Possible Reasons -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">সম্ভাব্য কারণসমূহ</h3>
                <div class="space-y-4 text-left">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-medium text-gray-800">bKash অ্যাকাউন্টে পর্যাপ্ত ব্যালেন্স নেই</h4>
                            <p class="text-gray-600 mt-1">আপনার bKash অ্যাকাউন্টে প্রয়োজনীয় পরিমাণ টাকা নেই।</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-medium text-gray-800">নেটওয়ার্ক সমস্যা</h4>
                            <p class="text-gray-600 mt-1">ইন্টারনেট সংযোগ বা bKash সার্ভারে সমস্যা হতে পারে।</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-medium text-gray-800">পেমেন্ট বাতিল করা হয়েছে</h4>
                            <p class="text-gray-600 mt-1">আপনি পেমেন্ট প্রক্রিয়া বাতিল করেছেন।</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="space-y-4">
                @if(isset($invoice))
                <a href="{{ route('citizen.payments.show', $invoice) }}" 
                   class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                    পুনরায় চেষ্টা করুন
                </a>
                @endif
                
                <div class="space-x-4">
                    <a href="{{ route('citizen.invoices.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        চালান তালিকা
                    </a>
                    
                    <a href="{{ route('citizen.dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        ড্যাশবোর্ড
                    </a>
                </div>
            </div>
            
            <!-- Support Information -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-gray-600 mb-4">সমস্যার সমাধানের জন্য আমাদের সাথে যোগাযোগ করুন:</p>
                <div class="space-y-2">
                    <p class="text-gray-700">
                        <span class="font-medium">হেল্পলাইন:</span> ০১৭১২-৩৪৫৬৭৮
                    </p>
                    <p class="text-gray-700">
                        <span class="font-medium">ইমেইল:</span> support@unionparishad.gov.bd
                    </p>
                    <p class="text-gray-700">
                        <span class="font-medium">অফিস সময়:</span> সকাল ৯টা - বিকাল ৫টা
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection