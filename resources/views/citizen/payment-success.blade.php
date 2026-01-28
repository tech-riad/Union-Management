@extends('layouts.app')

@section('title', 'পেমেন্ট সফল')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-green-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-8">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            
            <!-- Success Message -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                পেমেন্ট সফলভাবে সম্পন্ন হয়েছে!
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                আপনার পেমেন্ট সফলভাবে প্রক্রিয়াকরণ করা হয়েছে। আপনাকে ধন্যবাদ।
            </p>
            
            <!-- Success Details -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-8">
                {{ session('success') }}
            </div>
            @endif
            
            <!-- Next Steps -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">পরবর্তী ধাপসমূহ</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                <span class="text-blue-600 font-semibold">১</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-800">অনুমোদনের জন্য অপেক্ষা করুন</h4>
                            <p class="text-gray-600 mt-1">আপনার আবেদনটি স্বয়ংক্রিয়ভাবে অনুমোদনের জন্য প্রেরণ করা হয়েছে।</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                                <span class="text-green-600 font-semibold">২</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-800">সনদ ডাউনলোড করুন</h4>
                            <p class="text-gray-600 mt-1">অনুমোদন পাওয়ার পর আপনার সনদ ডাউনলোড করতে পারবেন।</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-purple-100">
                                <span class="text-purple-600 font-semibold">৩</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-800">সনদ যাচাই করুন</h4>
                            <p class="text-gray-600 mt-1">আপনার সনদের QR কোড স্ক্যান করে যাচাই করতে পারবেন।</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="space-y-4">
                <a href="{{ route('citizen.invoices.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    আমার চালান দেখুন
                </a>
                
                <div>
                    <a href="{{ route('citizen.dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        ড্যাশবোর্ডে ফিরে যান
                    </a>
                </div>
            </div>
            
            <!-- Support Information -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-gray-600 mb-4">কোন সমস্যা হলে আমাদের সাথে যোগাযোগ করুন:</p>
                <div class="space-y-2">
                    <p class="text-gray-700">
                        <span class="font-medium">হেল্পলাইন:</span> ০১৭১২-৩৪৫৬৭৮
                    </p>
                    <p class="text-gray-700">
                        <span class="font-medium">ইমেইল:</span> support@unionparishad.gov.bd
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection