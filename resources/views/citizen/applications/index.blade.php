@extends('layouts.app')

@section('title', 'আমার আবেদনসমূহ')

@section('content')

@php
    // ================= SAFE DEFAULTS =================
    $paidCount = 0;
    $unpaidCount = 0;
    $pendingCount = 0;
    $approvedCount = 0;
    $processingCount = 0;
    $applicationsCount = 0;
    $certificatesCount = 0;

    if(isset($applications) && $applications->count() > 0){
        $applicationsCount = $applications->count();
        $paidCount = $applications->where('payment_status', 'paid')->count();
        $unpaidCount = $applications->where('payment_status', 'unpaid')->count();
        $pendingCount = $applications->where('payment_status', 'pending')->count();
        $approvedCount = $applications->where('status', 'approved')->count();
        $processingCount = $applications->where('status', 'processing')->count();
    }

    if(isset($certificates)) {
        $certificatesCount = $certificates->count();
    }
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Premium Header -->
        <div class="relative mb-10">
            <div class="absolute -top-4 -left-4 w-24 h-24 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-full blur-xl"></div>
            <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-gradient-to-bl from-emerald-500/10 to-teal-500/10 rounded-full blur-xl"></div>
            
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur-lg opacity-60"></div>
                                <div class="relative p-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    আমার আবেদনসমূহ
                                </h1>
                                <p class="text-gray-600 mt-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    আপনার সকল সার্টিফিকেট আবেদনের অবস্থা, পেমেন্ট ও বিস্তারিত
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($certificatesCount > 0)
                    <div class="group relative">
                        <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                        @if($certificates->first())
                        <!-- আপনার রাউট অনুযায়ী: certificates/{certificate}/apply -->
                        <a href="{{ url('citizen/certificates/' . $certificates->first()->id . '/apply') }}" 
                           class="relative flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                            <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="text-lg">নতুন আবেদন করুন</span>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
                
                <!-- Stats Summary -->
                @if($applicationsCount > 0)
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-8">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-5 shadow-lg border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">মোট আবেদন</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $applicationsCount }}</p>
                            </div>
                            <div class="p-3 bg-blue-50 rounded-xl">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-5 shadow-lg border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">অনুমোদিত</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $approvedCount }}</p>
                            </div>
                            <div class="p-3 bg-emerald-50 rounded-xl">
                                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-5 shadow-lg border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">প্রক্রিয়াধীন</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $processingCount }}</p>
                            </div>
                            <div class="p-3 bg-amber-50 rounded-xl">
                                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-5 shadow-lg border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">পেমেন্ট সম্পন্ন</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $paidCount }}</p>
                            </div>
                            <div class="p-3 bg-green-50 rounded-xl">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-5 shadow-lg border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">পেমেন্ট বাকি</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $unpaidCount + $pendingCount }}</p>
                            </div>
                            <div class="p-3 bg-rose-50 rounded-xl">
                                <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Applications Table -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
            @if($applicationsCount == 0)
            <!-- Empty State -->
            <div class="text-center py-20 px-4">
                <div class="relative w-32 h-32 mx-auto mb-8">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full blur-xl"></div>
                    <div class="relative p-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-full">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">কোন আবেদন খুঁজে পাওয়া যায়নি</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    আপনি এখনো কোন সার্টিফিকেটের জন্য আবেদন করেননি। আপনার প্রয়োজনীয় সার্টিফিকেটের জন্য প্রথম আবেদন করুন।
                </p>
                @if($certificatesCount > 0 && $certificates->first())
                <div class="group relative inline-block">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                    <a href="{{ url('citizen/certificates/' . $certificates->first()->id . '/apply') }}" 
                       class="relative flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        প্রথম আবেদন করুন
                    </a>
                </div>
                @else
                <div class="group relative inline-block">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                    <a href="{{ route('citizen.certificates.index') }}" 
                       class="relative flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        প্রথম আবেদন করুন
                    </a>
                </div>
                @endif
            </div>
            @else
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-blue-50/50 border-b border-gray-200">
                            <th class="py-6 px-8 text-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-100 to-blue-50 flex items-center justify-center">
                                        <span class="text-sm font-semibold text-blue-600">#</span>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 uppercase tracking-wider">ক্রমিক</span>
                                </div>
                            </th>
                            <th class="py-6 px-8 text-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-indigo-100 to-indigo-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 uppercase tracking-wider">সার্টিফিকেট</span>
                                </div>
                            </th>
                            <th class="py-6 px-8 text-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-emerald-100 to-emerald-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 uppercase tracking-wider">অবস্থা</span>
                                </div>
                            </th>
                            <th class="py-6 px-8 text-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-green-100 to-green-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 uppercase tracking-wider">পেমেন্ট</span>
                                </div>
                            </th>
                            <th class="py-6 px-8 text-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-amber-100 to-amber-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 uppercase tracking-wider">তারিখ</span>
                                </div>
                            </th>
                            <th class="py-6 px-8 text-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-purple-100 to-purple-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 uppercase tracking-wider">কর্ম</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($applications as $app)
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-300 group">
                            <td class="py-6 px-8">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center group-hover:from-blue-100 group-hover:to-blue-50 transition-all duration-300">
                                        <span class="text-lg font-bold text-gray-700 group-hover:text-blue-600 transition-colors duration-300">{{ $loop->iteration }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl blur opacity-20 group-hover:opacity-30 transition duration-300"></div>
                                        <div class="relative p-3 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-xl">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-base font-semibold text-gray-900 group-hover:text-indigo-700 transition-colors duration-300">
                                            {{ optional($app->certificateType)->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                            আবেদন নং: #{{ str_pad($app->id, 6, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                @php
                                    $statusConfig = [
                                        'pending' => [
                                            'color' => 'from-amber-500 to-orange-500',
                                            'bg' => 'bg-gradient-to-r from-amber-50 to-orange-50',
                                            'text' => 'text-amber-700',
                                            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                                        ],
                                        'approved' => [
                                            'color' => 'from-emerald-500 to-teal-600',
                                            'bg' => 'bg-gradient-to-r from-emerald-50 to-teal-50',
                                            'text' => 'text-emerald-700',
                                            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                        ],
                                        'rejected' => [
                                            'color' => 'from-rose-500 to-pink-600',
                                            'bg' => 'bg-gradient-to-r from-rose-50 to-pink-50',
                                            'text' => 'text-rose-700',
                                            'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
                                        ],
                                        'processing' => [
                                            'color' => 'from-blue-500 to-indigo-600',
                                            'bg' => 'bg-gradient-to-r from-blue-50 to-indigo-50',
                                            'text' => 'text-blue-700',
                                            'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
                                        ],
                                        'completed' => [
                                            'color' => 'from-purple-500 to-violet-600',
                                            'bg' => 'bg-gradient-to-r from-purple-50 to-violet-50',
                                            'text' => 'text-purple-700',
                                            'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
                                        ]
                                    ];
                                    
                                    $appStatus = $app->status ?? 'pending';
                                    $config = $statusConfig[$appStatus] ?? $statusConfig['pending'];
                                    $statusText = [
                                        'pending' => 'মুলতুবি',
                                        'approved' => 'অনুমোদিত',
                                        'rejected' => 'প্রত্যাখ্যাত',
                                        'processing' => 'প্রক্রিয়াধীন',
                                        'completed' => 'সম্পন্ন'
                                    ][$appStatus] ?? ucfirst($appStatus);
                                @endphp
                                
                                <div class="relative group/status">
                                    <div class="absolute -inset-1 {{ $config['color'] }} rounded-xl blur opacity-20 group-hover/status:opacity-30 transition duration-300"></div>
                                    <div class="relative px-4 py-2.5 rounded-xl {{ $config['bg'] }} border border-transparent group-hover/status:border-white/20">
                                        <div class="flex items-center gap-2.5">
                                            <div class="p-1.5 {{ $config['bg'] }} rounded-lg">
                                                <svg class="w-4 h-4 {{ $config['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-semibold {{ $config['text'] }}">{{ $statusText }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                @php
                                    // Database এর আসল payment_status অনুযায়ী কনফিগারেশন
                                    $paymentConfig = [
                                        'paid' => [
                                            'color' => 'from-green-500 to-emerald-600',
                                            'bg' => 'bg-gradient-to-r from-green-50 to-emerald-50',
                                            'text' => 'text-green-700',
                                            'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                                            'text_display' => 'পেমেন্ট সম্পন্ন'
                                        ],
                                        'unpaid' => [
                                            'color' => 'from-amber-500 to-orange-500',
                                            'bg' => 'bg-gradient-to-r from-amber-50 to-orange-50',
                                            'text' => 'text-amber-700',
                                            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                            'text_display' => 'পেমেন্ট বাকি'
                                        ],
                                        'pending' => [
                                            'color' => 'from-blue-500 to-indigo-600',
                                            'bg' => 'bg-gradient-to-r from-blue-50 to-indigo-50',
                                            'text' => 'text-blue-700',
                                            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                            'text_display' => 'পেমেন্ট প্রক্রিয়াধীন'
                                        ]
                                    ];
                                    
                                    $paymentStatus = $app->payment_status ?? 'unpaid';
                                    $pConfig = $paymentConfig[$paymentStatus] ?? $paymentConfig['unpaid'];
                                @endphp
                                
                                <div class="space-y-2">
                                    <div class="relative group/payment" data-payment-status="{{ $paymentStatus }}">
                                        <div class="absolute -inset-1 {{ $pConfig['color'] }} rounded-xl blur opacity-20 group-hover/payment:opacity-30 transition duration-300"></div>
                                        <div class="relative px-4 py-2.5 rounded-xl {{ $pConfig['bg'] }} border border-transparent group-hover/payment:border-white/20">
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="p-1.5 {{ $pConfig['bg'] }} rounded-lg">
                                                        <svg class="w-4 h-4 {{ $pConfig['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $pConfig['icon'] }}"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm font-semibold {{ $pConfig['text'] }}">
                                                        {{ $pConfig['text_display'] }}
                                                    </span>
                                                </div>
                                                @if($paymentStatus === 'paid')
                                                <div class="text-xs {{ $pConfig['text'] }} font-medium">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Paid
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Invoice Link -->
                                    @if($app->invoice)
                                    <div class="text-center">
                                        <a href="{{ route('citizen.invoices.show', $app->invoice) }}" 
                                           class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Invoice দেখুন
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="space-y-1">
                                    <div class="text-base font-semibold text-gray-900">
                                        {{ $app->created_at->format('d M, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $app->created_at->format('h:i A') }}
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-2">
                                    <!-- Application Details Link -->
                                    <a href="{{ route('citizen.applications.show', $app) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200 border border-blue-100"
                                       title="বিস্তারিত দেখুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        বিস্তারিত
                                    </a>
                                    
                                    <!-- Payment Button -->
                                    @if($app->invoice && $paymentStatus !== 'paid')
                                    <form action="{{ route('citizen.invoices.pay', $app->invoice) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg hover:from-emerald-600 hover:to-teal-700 transition-all duration-200 shadow hover:shadow-md"
                                                title="পেমেন্ট করুন">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            পেমেন্ট
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Payment Summary -->
            <div class="px-8 py-6 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-blue-50/30">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <p class="text-gray-600">মোট {{ $applicationsCount }}টি আবেদন দেখানো হচ্ছে</p>
                        @if(($unpaidCount + $pendingCount) > 0)
                        <div class="mt-2">
                            <div class="text-amber-600 text-sm flex items-center justify-center md:justify-start gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $unpaidCount + $pendingCount }}টি আবেদনের পেমেন্ট বাকি</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Quick Navigation -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('citizen.dashboard') }}" 
               class="group p-5 bg-white rounded-2xl shadow-lg hover:shadow-xl border border-gray-100 hover:border-blue-200 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl blur opacity-0 group-hover:opacity-20 transition duration-300"></div>
                        <div class="relative p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl group-hover:from-blue-100 group-hover:to-indigo-100 transition-all duration-300">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-300">
                            ড্যাশবোর্ডে ফিরে যান
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            প্রধান পাতায় ফিরে যান
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            
            @if($certificatesCount > 0 && $certificates->first())
            <a href="{{ url('citizen/certificates/' . $certificates->first()->id . '/apply') }}" 
               class="group p-5 bg-white rounded-2xl shadow-lg hover:shadow-xl border border-gray-100 hover:border-emerald-200 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl blur opacity-0 group-hover:opacity-20 transition duration-300"></div>
                        <div class="relative p-3 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl group-hover:from-emerald-100 group-hover:to-teal-100 transition-all duration-300">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors duration-300">
                            নতুন আবেদন করুন
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            আরেকটি সার্টিফিকেটের জন্য আবেদন করুন
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 transform group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @else
            <a href="{{ route('citizen.certificates.index') }}" 
               class="group p-5 bg-white rounded-2xl shadow-lg hover:shadow-xl border border-gray-100 hover:border-emerald-200 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl blur opacity-0 group-hover:opacity-20 transition duration-300"></div>
                        <div class="relative p-3 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl group-hover:from-emerald-100 group-hover:to-teal-100 transition-all duration-300">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors duration-300">
                            সার্টিফিকেট দেখুন
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            উপলব্ধ সার্টিফিকেটসমূহ
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 transform group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @endif
            
            @if(($unpaidCount + $pendingCount) > 0)
            <a href="{{ route('citizen.invoices.index') }}" 
               class="group p-5 bg-white rounded-2xl shadow-lg hover:shadow-xl border border-gray-100 hover:border-amber-200 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl blur opacity-0 group-hover:opacity-20 transition duration-300"></div>
                        <div class="relative p-3 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl group-hover:from-amber-100 group-hover:to-orange-100 transition-all duration-300">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 group-hover:text-amber-600 transition-colors duration-300">
                            বাকি পেমেন্ট
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            {{ $unpaidCount + $pendingCount }}টি পেমেন্ট বাকি আছে
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-amber-500 transform group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @else
            <a href="{{ url('/citizen/profile') }}" 
               class="group p-5 bg-white rounded-2xl shadow-lg hover:shadow-xl border border-gray-100 hover:border-purple-200 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-violet-500 rounded-xl blur opacity-0 group-hover:opacity-20 transition duration-300"></div>
                        <div class="relative p-3 bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl group-hover:from-purple-100 group-hover:to-violet-100 transition-all duration-300">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors duration-300">
                            প্রোফাইল দেখুন
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            আপনার ব্যক্তিগত তথ্য আপডেট করুন
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-500 transform group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes pulse-paid {
        0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.5); }
        50% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
    }
    
    tr {
        animation: fadeInUp 0.4s ease-out forwards;
        animation-delay: calc(var(--i) * 0.05s);
        opacity: 0;
    }
    
    .payment-paid {
        animation: pulse-paid 2s infinite;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation delay to table rows
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            row.style.setProperty('--i', index);
            
            // Add paid animation for paid payments
            const paymentStatus = row.querySelector('[data-payment-status]');
            if (paymentStatus && paymentStatus.dataset.paymentStatus === 'paid') {
                const paymentBadge = row.querySelector('.group/payment');
                if (paymentBadge) {
                    paymentBadge.classList.add('payment-paid');
                }
            }
        });
        
        // Show success message if exists
        @if(session('success'))
        setTimeout(() => {
            const alert = document.createElement('div');
            alert.className = 'fixed top-4 right-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-3 rounded-xl shadow-xl z-50 animate-bounce';
            alert.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <div class="font-semibold">সফল!</div>
                        <div class="text-sm opacity-90">{{ session('success') }}</div>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(alert);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alert.parentElement) {
                    alert.remove();
                }
            }, 5000);
        }, 1000);
        @endif
    });
</script>
@endpush
@endsection