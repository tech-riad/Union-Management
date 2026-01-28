@extends('layouts.app')

@section('title', 'ইনভয়েস ডিটেইল - ' . $invoice->invoice_no)

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap');
    
    .bangla-font {
        font-family: 'Hind Siliguri', 'SolaimanLipi', 'Nikosh', sans-serif;
    }
    
    .invoice-border {
        border-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-image-slice: 1;
    }
    
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .shadow-soft {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    
    .shadow-hard {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }
    
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    .glow {
        box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
    }
    
    .border-glow {
        border: 2px solid transparent;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, #667eea 0%, #764ba2 100%) border-box;
    }
    
    .animated-bg {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }
    
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    
    .badge-glow {
        box-shadow: 0 0 15px currentColor;
    }
    
    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        animation: blink 2s infinite;
    }
    
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 bangla-font">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                        <div class="p-3 rounded-full gradient-bg text-white">
                            <i class="fas fa-file-invoice text-xl"></i>
                        </div>
                        <span class="gradient-text">ইনভয়েস ডিটেইলস</span>
                    </h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        <span>আপনার ইনভয়েসের সম্পূর্ণ বিবরণ এখানে দেখানো হচ্ছে</span>
                    </p>
                </div>
                
                <!-- Invoice Number Badge -->
                <div class="bg-white rounded-2xl shadow-hard px-6 py-4 border-glow">
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-1">ইনভয়েস নম্বর</p>
                        <p class="text-2xl font-bold gradient-text">#{{ $invoice->invoice_no }}</p>
                        <div class="mt-2 flex items-center justify-center gap-2">
                            <span class="status-dot {{ $invoice->payment_status == 'paid' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                            <span class="text-sm {{ $invoice->payment_status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $invoice->payment_status == 'paid' ? 'পরিশোধিত' : 'অপেক্ষমান' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl shadow-hard overflow-hidden mb-8 hover-lift">
            <!-- Card Header -->
            <div class="gradient-bg px-6 md:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-white">
                        <h2 class="text-2xl font-bold mb-2">ইনভয়েস সারসংক্ষেপ</h2>
                        <p class="opacity-90">
                            <i class="far fa-calendar-alt mr-2"></i>
                            তারিখ: {{ $invoice->created_at->format('d F, Y - h:i A') }}
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2">
                        <p class="text-white text-sm">মোট পরিমাণ</p>
                        <p class="text-2xl font-bold text-white">
                            ৳ {{ number_format($invoice->amount + ($invoice->vat_amount ?? 0) + ($invoice->service_charge ?? 0), 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 md:p-8">
                <!-- Personal & Application Info Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Personal Information Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 shadow-soft hover-lift border border-blue-100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">ব্যক্তিগত তথ্য</h3>
                                <p class="text-gray-600 text-sm">গ্রাহকের বিবরণ</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-user-tag text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">নাম</p>
                                    <p class="font-semibold text-gray-800">{{ $invoice->user->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-envelope text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">ইমেইল</p>
                                    <p class="font-semibold text-gray-800">{{ $invoice->user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-phone text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">ফোন নম্বর</p>
                                    <p class="font-semibold text-gray-800">{{ $invoice->user->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Information Card -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 shadow-soft hover-lift border border-purple-100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-purple-500 flex items-center justify-center">
                                <i class="fas fa-file-certificate text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">আবেদন তথ্য</h3>
                                <p class="text-gray-600 text-sm">সার্টিফিকেট বিবরণ</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-hashtag text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">আবেদন নম্বর</p>
                                    <p class="font-semibold text-gray-800">{{ $invoice->application->application_no ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center">
                                    <i class="fas fa-certificate text-pink-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">সার্টিফিকেট প্রকার</p>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-800">{{ $invoice->application->certificateType->name ?? 'N/A' }}</span>
                                        <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs font-medium">
                                            <i class="fas fa-tag mr-1"></i>
                                            সার্টিফিকেট
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">আবেদন তারিখ</p>
                                    <p class="font-semibold text-gray-800">
                                        @if($invoice->application)
                                            {{ $invoice->application->created_at->format('d F, Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Details Table -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-green-500 to-emerald-500">
                                <i class="fas fa-receipt text-white"></i>
                            </div>
                            বিলিং বিবরণ
                        </h3>
                        <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            পরিশোধযোগ্য অর্থ
                        </span>
                    </div>
                    
                    <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-soft">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="py-4 px-6 text-left text-gray-700 font-bold border-b">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-list-ol text-blue-500"></i>
                                            ক্রমিক
                                        </div>
                                    </th>
                                    <th class="py-4 px-6 text-left text-gray-700 font-bold border-b">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-clipboard-list text-blue-500"></i>
                                            সেবার বিবরণ
                                        </div>
                                    </th>
                                    <th class="py-4 px-6 text-left text-gray-700 font-bold border-b">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-money-bill text-blue-500"></i>
                                            পরিমাণ (৳)
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="py-5 px-6">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-500 text-white font-bold">
                                            ১
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $invoice->application->certificateType->name ?? 'সার্টিফিকেট' }} ফি</p>
                                            <p class="text-sm text-gray-600 mt-1">সার্টিফিকেট আবেদন প্রক্রিয়াকরণ ফি</p>
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <div class="text-right">
                                            <p class="text-xl font-bold text-green-600">৳ {{ number_format($invoice->amount, 2) }}</p>
                                        </div>
                                    </td>
                                </tr>
                                
                                @if($invoice->vat_amount > 0)
                                <tr class="hover:bg-green-50 transition-colors">
                                    <td class="py-5 px-6">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white font-bold">
                                            ২
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <div>
                                            <p class="font-semibold text-gray-800">মূল্য সংযোজন কর (ভ্যাট)</p>
                                            <p class="text-sm text-gray-600 mt-1">সরকারি ভ্যাট {{ $invoice->vat_rate ?? 15 }}%</p>
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <div class="text-right">
                                            <p class="text-xl font-bold text-green-600">৳ {{ number_format($invoice->vat_amount, 2) }}</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                
                                @if($invoice->service_charge > 0)
                                <tr class="hover:bg-purple-50 transition-colors">
                                    <td class="py-5 px-6">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-500 text-white font-bold">
                                            ৩
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <div>
                                            <p class="font-semibold text-gray-800">সেবা চার্জ</p>
                                            <p class="text-sm text-gray-600 mt-1">অতিরিক্ত সেবা চার্জ</p>
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <div class="text-right">
                                            <p class="text-xl font-bold text-green-600">৳ {{ number_format($invoice->service_charge, 2) }}</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                
                                <!-- Total Row -->
                                <tr class="bg-gradient-to-r from-emerald-50 to-green-50">
                                    <td colspan="2" class="py-6 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center">
                                                <i class="fas fa-calculator text-white"></i>
                                            </div>
                                            <div>
                                                <p class="text-lg font-bold text-gray-800">মোট পরিশোধযোগ্য</p>
                                                <p class="text-sm text-gray-600">সকল খরচ সহ মোট পরিমাণ</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-6 px-6">
                                        <div class="text-right">
                                            <p class="text-3xl font-bold text-emerald-700 animate-pulse">
                                                ৳ {{ number_format($invoice->amount + ($invoice->vat_amount ?? 0) + ($invoice->service_charge ?? 0), 2) }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Status Card -->
                <div class="mb-8">
                    <div class="{{ $invoice->payment_status == 'paid' ? 'bg-gradient-to-r from-green-50 to-emerald-50' : 'bg-gradient-to-r from-yellow-50 to-orange-50' }} rounded-2xl p-6 border {{ $invoice->payment_status == 'paid' ? 'border-green-200' : 'border-yellow-200' }}">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                            <div class="flex items-center gap-4">
                                <div class="{{ $invoice->payment_status == 'paid' ? 'bg-green-500 glow' : 'bg-yellow-500 pulse' }} w-16 h-16 rounded-full flex items-center justify-center">
                                    @if($invoice->payment_status == 'paid')
                                        <i class="fas fa-check-circle text-white text-2xl"></i>
                                    @else
                                        <i class="fas fa-clock text-white text-2xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-800 mb-2">
                                        @if($invoice->payment_status == 'paid')
                                            ✅ পরিশোধ সম্পন্ন
                                        @else
                                            ⚠️ পরিশোধ বাকি
                                        @endif
                                    </h4>
                                    <p class="text-gray-600">
                                        @if($invoice->payment_status == 'paid')
                                            <i class="fas fa-check text-green-500 mr-2"></i>
                                            এই ইনভয়েসটি ইতিমধ্যে পরিশোধ করা হয়েছে
                                            @if($invoice->updated_at)
                                                <br>
                                                <span class="text-sm text-gray-500">
                                                    <i class="far fa-calendar-alt mr-1"></i>
                                                    পরিশোধের তারিখ: {{ $invoice->updated_at->format('d F, Y h:i A') }}
                                                </span>
                                            @endif
                                        @else
                                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                                            এই ইনভয়েসটি এখনও পরিশোধ করা হয়নি। দয়া করে নিচের বাটনে ক্লিক করে পেমেন্ট সম্পন্ন করুন
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div>
                                <span class="px-6 py-3 rounded-full {{ $invoice->payment_status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} font-bold text-lg">
                                    @if($invoice->payment_status == 'paid')
                                        <i class="fas fa-check-circle mr-2"></i>
                                        পরিশোধিত
                                    @else
                                        <i class="fas fa-clock mr-2"></i>
                                        অপরিশোধিত
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 border border-gray-200">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-arrow-left text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">ইনভয়েস লিস্টে ফিরে যান</p>
                                <a href="{{ route('citizen.invoices.index') }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-2 mt-1">
                                    <i class="fas fa-chevron-right"></i>
                                    সকল ইনভয়েস দেখুন
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-3">
                            <!-- View PDF Button -->
                            <a href="{{ route('citizen.invoices.view', $invoice) }}" 
                               target="_blank"
                               class="group relative overflow-hidden px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <span class="relative z-10 flex items-center gap-2">
                                    <i class="fas fa-eye"></i>
                                    PDF দেখুন
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                            </a>
                            
                            <!-- Download PDF Button -->
                            <a href="{{ route('citizen.invoices.pdf', $invoice) }}" 
                               class="group relative overflow-hidden px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <span class="relative z-10 flex items-center gap-2">
                                    <i class="fas fa-download"></i>
                                    PDF ডাউনলোড
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-teal-600 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                            </a>
                            
                            <!-- Pay Button -->
                            @if($invoice->payment_status !== 'paid')
                                <form action="{{ route('citizen.invoices.pay', $invoice) }}" 
                                      method="POST" 
                                      class="inline-block">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('আপনি কি নিশ্চিত যে এই ইনভয়েসটি পরিশোধ করতে চান?')"
                                            class="group relative overflow-hidden px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                        <span class="relative z-10 flex items-center gap-2">
                                            <i class="fas fa-credit-card"></i>
                                            পরিশোধ করুন
                                        </span>
                                        <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-red-600 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 md:px-8 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center text-gray-300">
                    <div class="flex items-center gap-3 mb-4 md:mb-0">
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm">এই ইনভয়েসটি {{ config('app.name') }} সিস্টেম দ্বারা সুরক্ষিত</p>
                        </div>
                    </div>
                    
                    <div class="text-sm">
                        <p class="flex items-center gap-2">
                            <i class="far fa-clock"></i>
                            শেষ আপডেট: {{ $invoice->updated_at->format('d F, Y h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Support Card -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 shadow-soft hover-lift border border-blue-200">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-headset text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">সহায়তা প্রয়োজন?</h4>
                        <p class="text-gray-600 text-sm mb-3">আমাদের সাপোর্ট টিম আপনার সহায়তার জন্য প্রস্তুত</p>
                        <div class="flex items-center gap-2 text-blue-600">
                            <i class="fas fa-phone"></i>
                            <span class="font-semibold">১৬৫৪৫</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Card -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 shadow-soft hover-lift border border-green-200">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-500 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-question-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">সচরাচর জিজ্ঞাসা</h4>
                        <p class="text-gray-600 text-sm mb-3">ইনভয়েস সম্পর্কিত সাধারণ প্রশ্নের উত্তর</p>
                        <a href="#" class="text-green-600 text-sm font-semibold flex items-center gap-2">
                            দেখুন FAQ
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Print Card -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 shadow-soft hover-lift border border-purple-200">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-500 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-print text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">প্রিন্ট করুন</h4>
                        <p class="text-gray-600 text-sm mb-3">আপনার ইনভয়েসের হার্ড কপি সংগ্রহ করুন</p>
                        <button onclick="window.print()" class="text-purple-600 text-sm font-semibold flex items-center gap-2">
                            <i class="fas fa-print"></i>
                            প্রিন্ট করুন
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Animation for status dot
    document.addEventListener('DOMContentLoaded', function() {
        // Add ripple effect to buttons
        const buttons = document.querySelectorAll('a, button');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (this.tagName === 'BUTTON' && this.type === 'submit') {
                    // Already handled by form submission
                    return;
                }
                
                // Create ripple effect
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.7);
                    transform: scale(0);
                    animation: ripple-animation 0.6s linear;
                    width: ${size}px;
                    height: ${size}px;
                    top: ${y}px;
                    left: ${x}px;
                    pointer-events: none;
                `;
                
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });
        
        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .relative {
                position: relative;
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);
        
        // Confirmation for payment
        const payForm = document.querySelector('form');
        if (payForm) {
            payForm.addEventListener('submit', function(e) {
                if (!confirm('আপনি কি নিশ্চিত যে এই ইনভয়েসটি পরিশোধ করতে চান?\n\nইনভয়েস নং: #{{ $invoice->invoice_no }}\nপরিমাণ: ৳ {{ number_format($invoice->amount + ($invoice->vat_amount ?? 0) + ($invoice->service_charge ?? 0), 2) }}')) {
                    e.preventDefault();
                    return false;
                }
            });
        }
        
        // Auto refresh after payment success
        @if(session('success'))
            setTimeout(() => {
                location.reload();
            }, 3000);
        @endif
        
        // Add hover effects to cards
        const cards = document.querySelectorAll('.hover-lift');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Count animation for total amount
        const totalAmount = {{ $invoice->amount + ($invoice->vat_amount ?? 0) + ($invoice->service_charge ?? 0) }};
        const totalElement = document.querySelector('.animate-pulse');
        if (totalElement) {
            let current = 0;
            const increment = totalAmount / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= totalAmount) {
                    current = totalAmount;
                    clearInterval(timer);
                }
                totalElement.textContent = '৳ ' + current.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }, 30);
        }
    });
</script>
@endpush
@endsection