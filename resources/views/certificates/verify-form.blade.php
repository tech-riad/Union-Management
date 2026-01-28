@extends('layouts.public')
@section('title', 'সনদ যাচাই করুন')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50 to-gray-100 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    সনদ যাচাই করুন
                </h1>
                <p class="text-gray-600">
                    আপনার সনদের যথার্থতা যাচাই করতে সনদ নম্বর দিন
                </p>
            </div>
            
            <!-- Verification Form -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
                <div class="p-8">
                    <form action="{{ route('certificate.verify.process') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="certificate_number" class="block text-sm font-medium text-gray-700 mb-2">
                                সনদ নম্বর
                            </label>
                            <input type="text" 
                                   id="certificate_number" 
                                   name="certificate_number"
                                   value="{{ old('certificate_number') }}"
                                   placeholder="যেমন: CERT-2025-00125"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   required>
                            @error('certificate_number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input id="terms" 
                                       name="terms" 
                                       type="checkbox"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       required>
                                <label for="terms" class="ml-2 block text-sm text-gray-700">
                                    আমি সম্মত যে, এই যাচাই পদ্ধতি শুধুমাত্র সনদের যথার্থতা নিরূপণের জন্য
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" 
                                class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            <i class="fas fa-search mr-2"></i> সনদ যাচাই করুন
                        </button>
                    </form>
                    
                    <!-- Help Section -->
                    <div class="mt-8 pt-6 border-t">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                            কিভাবে সনদ নম্বর পাবেন?
                        </h3>
                        <ul class="text-gray-600 space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>সনদের উপরের ডান কোণায় QR কোড স্ক্যান করুন</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>সনদের নিচে বাম পাশে সনদ নম্বর দেখতে পাবেন</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>সনদ নম্বর সাধারণত এই ফরম্যাটে থাকে: CERT-YYYY-XXXXX</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- QR Code Demo -->
                    <div class="mt-8 text-center">
                        <div class="inline-block p-4 bg-gray-100 rounded-lg">
                            <div class="text-sm text-gray-600 mb-2">সনদ QR কোড উদাহরণ:</div>
                            <div class="bg-white p-3 rounded inline-block">
                                {!! QrCode::size(150)->generate('CERT-2025-00125') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Security Notice -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-shield-alt mr-1"></i>
                    এই সিস্টেমটি সরকারি ডিজিটাল সনদ যাচাই প্ল্যাটফর্মের অংশ। সকল তথ্য নিরাপদে সংরক্ষিত।
                </p>
            </div>
        </div>
    </div>
</div>
@endsection