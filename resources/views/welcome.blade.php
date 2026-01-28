@php
use App\Helpers\UnionHelper;
@endphp
@extends('layouts.public')

@section('title', 'ইউনিয়ন ডিজিটাল প্ল্যাটফর্ম - স্বাগতম')

@section('content')
<div class="py-8 md:py-12">
    <!-- Hero Section -->
    <div class="text-center mb-12 fade-in">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
            <span class="gradient-text">{{ UnionHelper::getName() }}-এ</span> স্বাগতম
        </h1>
        <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-8">
            ডিজিটাল বাংলাদেশের অংশ হিসেবে আমরা আপনাকে দ্রুত ও সহজে ইউনিয়ন সেবা প্রদান করছি
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('certificate.verify.form') }}" class="btn btn-primary">
                <i class="fas fa-search mr-2"></i>
                সার্টিফিকেট যাচাই করুন
            </a>
            <a href="{{ route('register') }}" class="btn btn-success">
                <i class="fas fa-user-plus mr-2"></i>
                রেজিস্টার করুন
            </a>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 fade-in">
        <div class="card p-6">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center mb-4">
                <i class="fas fa-file-certificate text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">অনলাইন সার্টিফিকেট</h3>
            <p class="text-gray-600">
                ঘরে বসেই সকল ধরনের সার্টিফিকেটের জন্য আবেদন করুন এবং ডাউনলোড করুন
            </p>
        </div>
        
        <div class="card p-6">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center mb-4">
                <i class="fas fa-search-check text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">সার্টিফিকেট যাচাই</h3>
            <p class="text-gray-600">
                যেকোনো সার্টিফিকেটের সত্যতা অনলাইনে যাচাই করুন এবং নিশ্চিত হন
            </p>
        </div>
        
        <div class="card p-6">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center mb-4">
                <i class="fas fa-credit-card text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">অনলাইন পেমেন্ট</h3>
            <p class="text-gray-600">
                নিরাপদ ও সহজ পদ্ধতিতে সকল ফি অনলাইনে পরিশোধ করুন
            </p>
        </div>
    </div>

    <!-- Verification Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8 mb-12 fade-in">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">সার্টিফিকেট যাচাই করুন</h2>
            <p class="text-gray-600">সার্টিফিকেট নম্বর বা QR কোড স্ক্যান করে যাচাই করুন</p>
        </div>
        
        <form action="{{ route('certificate.verify.process') }}" method="POST" class="max-w-md mx-auto">
            @csrf
            <div class="mb-4">
                <input type="text" 
                       name="certificate_number" 
                       placeholder="সার্টিফিকেট নম্বর লিখুন" 
                       class="form-input"
                       required>
            </div>
            <button type="submit" class="btn btn-primary w-full">
                <i class="fas fa-search mr-2"></i>
                যাচাই করুন
            </button>
        </form>
    </div>
</div>
@endsection