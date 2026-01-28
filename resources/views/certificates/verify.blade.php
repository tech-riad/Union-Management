@extends('layouts.public')

@section('title', 'সনদ যাচাই - ' . ($application->certificate_number ?? ''))

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50 to-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            
            <!-- Verification Status Banner -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg mb-8 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div class="flex items-center mb-4 md:mb-0">
                            <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                <i class="fas fa-shield-check text-3xl"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold">সনদ যাচাইকরণ রিপোর্ট</h1>
                                <p class="text-green-100 mt-1">
                                    এই সনদটি সরকারি রেজিস্ট্রিতে নিবন্ধিত ও বৈধ
                                </p>
                            </div>
                        </div>
                        <div class="text-center md:text-right">
                            <div class="text-sm font-medium text-green-100">যাচাই তারিখ</div>
                            <div class="text-xl font-bold">{{ $verificationDate ?? now()->format('d F, Y h:i A') }}</div>
                            <div class="text-xs mt-1 text-green-100">
                                ID: {{ $verificationId ?? 'VER-' . now()->format('YmdHis') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Certificate Details -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Certificate Information Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b">
                            <h2 class="text-xl font-bold text-gray-800">
                                <i class="fas fa-file-certificate text-blue-600 mr-2"></i>
                                সনদ তথ্য
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">সনদ নম্বর</label>
                                    <div class="text-lg font-mono font-bold text-blue-600 bg-blue-50 p-3 rounded-lg">
                                        {{ $application->certificate_number }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">সনদের ধরণ</label>
                                    <div class="text-lg font-semibold text-gray-900 bg-gray-50 p-3 rounded-lg">
                                        {{ $application->certificateType->name ?? 'নাগরিকত্ব সনদ' }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ইস্যুর তারিখ</label>
                                    <div class="text-gray-900 bg-gray-50 p-3 rounded-lg">
                                        {{ \Carbon\Carbon::parse($application->approved_at)->format('d F, Y') }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">মেয়াদ উত্তীর্ণ</label>
                                    <div class="text-gray-900 bg-gray-50 p-3 rounded-lg">
                                        {{ \Carbon\Carbon::parse($application->approved_at)->addYear()->format('d F, Y') }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Badges -->
                            <div class="mt-6 flex flex-wrap gap-3">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-2"></i> বৈধ সনদ
                                </span>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-user-check mr-2"></i> অনুমোদিত
                                </span>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-qrcode mr-2"></i> QR কোড সক্রিয়
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Applicant Information Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b">
                            <h2 class="text-xl font-bold text-gray-800">
                                <i class="fas fa-user text-green-600 mr-2"></i>
                                আবেদনকারীর তথ্য
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">পূর্ণ নাম (বাংলা)</label>
                                    <div class="text-lg font-semibold text-gray-900 bg-gray-50 p-3 rounded-lg">
                                        {{ $formData['name_bangla'] ?? $application->user->name ?? 'নাম পাওয়া যায়নি' }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">পূর্ণ নাম (ইংরেজি)</label>
                                    <div class="text-lg font-semibold text-gray-900 bg-gray-50 p-3 rounded-lg">
                                        {{ $formData['name_english'] ?? $application->user->name ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">পিতার নাম</label>
                                    <div class="text-gray-900 bg-gray-50 p-3 rounded-lg">
                                        {{ $formData['father_name_bangla'] ?? $formData['father_name'] ?? 'নাম পাওয়া যায়নি' }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">মাতার নাম</label>
                                    <div class="text-gray-900 bg-gray-50 p-3 rounded-lg">
                                        {{ $formData['mother_name_bangla'] ?? $formData['mother_name'] ?? 'নাম পাওয়া যায়নি' }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">জাতীয় পরিচয়পত্র নম্বর</label>
                                    <div class="text-gray-900 bg-gray-50 p-3 rounded-lg font-mono">
                                        {{ $formData['nid_number'] ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">জন্ম তারিখ</label>
                                    <div class="text-gray-900 bg-gray-50 p-3 rounded-lg">
                                        {{ $formData['date_of_birth'] ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Address Information -->
                            @if(isset($formData['permanent_address_bangla']) || isset($formData['present_address_bangla']))
                            <div class="mt-6 pt-6 border-t">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                    <i class="fas fa-home text-blue-500 mr-2"></i>
                                    ঠিকানা
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if(isset($formData['permanent_address_bangla']))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">স্থায়ী ঠিকানা</label>
                                        <div class="text-gray-900 bg-gray-50 p-3 rounded-lg">
                                            {{ $formData['permanent_address_bangla'] }}
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if(isset($formData['present_address_bangla']))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">বর্তমান ঠিকানা</label>
                                        <div class="text-gray-900 bg-gray-50 p-3 rounded-lg">
                                            {{ $formData['present_address_bangla'] }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Issuing Authority Information -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-violet-50 px-6 py-4 border-b">
                            <h2 class="text-xl font-bold text-gray-800">
                                <i class="fas fa-landmark text-purple-600 mr-2"></i>
                                প্রদানকারী কর্তৃপক্ষ
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="bg-purple-50 p-4 rounded-xl">
                                        <i class="fas fa-university text-3xl text-purple-600 mb-3"></i>
                                        <h3 class="font-bold text-lg text-gray-900">{{ $unionName ?? 'ইউনিয়ন পরিষদ' }}</h3>
                                        <p class="text-gray-600 text-sm mt-1">সনদ প্রদানকারী অফিস</p>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="bg-blue-50 p-4 rounded-xl">
                                        <i class="fas fa-user-tie text-3xl text-blue-600 mb-3"></i>
                                        <h3 class="font-bold text-lg text-gray-900">মোঃ রফিকুল ইসলাম</h3>
                                        <p class="text-gray-600 text-sm mt-1">চেয়ারম্যান</p>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="bg-green-50 p-4 rounded-xl">
                                        <i class="fas fa-user-shield text-3xl text-green-600 mb-3"></i>
                                        <h3 class="font-bold text-lg text-gray-900">মোঃ আব্দুল করিম</h3>
                                        <p class="text-gray-600 text-sm mt-1">সচিব</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 text-center">
                                <div class="inline-flex items-center text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <span>উত্তর গাবতলী, গাবতলী, বগুড়া</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Verification Actions & QR -->
                <div class="space-y-6">
                    
                    <!-- Verification Security Stamp -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-red-50 to-orange-50 px-6 py-4 border-b">
                            <h2 class="text-xl font-bold text-gray-800">
                                <i class="fas fa-shield-alt text-red-600 mr-2"></i>
                                নিরাপত্তা সীল
                            </h2>
                        </div>
                        <div class="p-6 text-center">
                            <div class="relative inline-block">
                                <div class="w-48 h-48 mx-auto bg-gradient-to-br from-red-50 to-orange-100 rounded-full flex items-center justify-center border-4 border-red-200">
                                    <div class="text-center">
                                        <i class="fas fa-certificate text-5xl text-red-500 mb-2"></i>
                                        <div class="font-bold text-red-700">যাচাইকৃত</div>
                                        <div class="text-sm text-red-600 mt-1">ডিজিটাল সীল</div>
                                    </div>
                                </div>
                                <div class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full w-10 h-10 flex items-center justify-center">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            
                            <div class="mt-6 space-y-3">
                                <div class="flex items-center justify-center text-green-600">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span class="font-semibold">ডিজিটালি স্বাক্ষরিত</span>
                                </div>
                                <div class="flex items-center justify-center text-blue-600">
                                    <i class="fas fa-database mr-2"></i>
                                    <span class="font-semibold">ব্লকচেইনে সংরক্ষিত</span>
                                </div>
                                <div class="flex items-center justify-center text-purple-600">
                                    <i class="fas fa-qrcode mr-2"></i>
                                    <span class="font-semibold">QR কোড সক্রিয়</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code for Verification -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 px-6 py-4 border-b">
                            <h2 class="text-xl font-bold text-gray-800">
                                <i class="fas fa-qrcode text-blue-600 mr-2"></i>
                                যাচাইকরণ QR কোড
                            </h2>
                        </div>
                        <div class="p-6 text-center">
                            <div class="bg-white p-4 rounded-lg inline-block border border-gray-300">
                                @php
                                    $verificationUrl = route('certificate.verify', ['cid' => $application->certificate_number]);
                                @endphp
                                {!! QrCode::size(200)->generate($verificationUrl) !!}
                            </div>
                            <div class="mt-4 text-sm text-gray-600">
                                এই QR কোড স্ক্যান করে পুনরায় যাচাই করুন
                            </div>
                            <div class="mt-2 text-xs text-gray-500 font-mono break-all">
                                {{ str_replace(['http://', 'https://'], '', $verificationUrl) }}
                            </div>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                            <h2 class="text-xl font-bold text-gray-800">
                                <i class="fas fa-cogs text-gray-600 mr-2"></i>
                                কার্যক্রম
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Download PDF -->
                            <a href="{{ route('certificate.pdf', $application->id) }}" 
                               target="_blank"
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-md">
                                <i class="fas fa-file-pdf mr-2"></i>
                                সনদ PDF ডাউনলোড করুন
                            </a>
                            
                            <!-- Print -->
                            <button onclick="window.print()" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-medium rounded-lg hover:from-gray-700 hover:to-gray-800 transition shadow-md">
                                <i class="fas fa-print mr-2"></i>
                                এই পৃষ্ঠা প্রিন্ট করুন
                            </button>
                            
                            <!-- Verify Another -->
                            <a href="{{ route('certificate.verify.form') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg hover:from-green-700 hover:to-green-800 transition shadow-md">
                                <i class="fas fa-search mr-2"></i>
                                অন্য সনদ যাচাই করুন
                            </a>
                            
                            <!-- Share -->
                            <button onclick="shareCertificate()" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition shadow-md">
                                <i class="fas fa-share-alt mr-2"></i>
                                শেয়ার করুন
                            </button>
                        </div>
                    </div>

                    <!-- Verification Details -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b">
                            <h2 class="text-xl font-bold text-gray-800">
                                <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                                যাচাই বিবরণ
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">যাচাই সনাক্তকারী</label>
                                    <div class="font-mono text-gray-900 bg-gray-50 p-2 rounded">
                                        {{ $verificationId ?? 'VER-' . now()->format('YmdHis') }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">যাচাই সময়</label>
                                    <div class="text-gray-900 bg-gray-50 p-2 rounded">
                                        {{ $verificationDate ?? now()->format('d F, Y h:i A') }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">আইপি ঠিকানা</label>
                                    <div class="font-mono text-gray-900 bg-gray-50 p-2 rounded">
                                        {{ request()->ip() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Warning -->
            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-1 mr-3"></i>
                    <div>
                        <h3 class="font-bold text-yellow-800 text-lg mb-2">সতর্কতা</h3>
                        <ul class="text-yellow-700 space-y-1">
                            <li class="flex items-start">
                                <i class="fas fa-circle text-yellow-500 text-xs mt-1 mr-2"></i>
                                <span>জাল সনদ তৈরি ও ব্যবহার দণ্ডনীয় অপরাধ</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle text-yellow-500 text-xs mt-1 mr-2"></i>
                                <span>যেকোনো সন্দেহের ক্ষেত্রে সরাসরি ইউনিয়ন পরিষদে যোগাযোগ করুন</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle text-yellow-500 text-xs mt-1 mr-2"></i>
                                <span>এই যাচাই রিপোর্ট শুধুমাত্র প্রদর্শনের জন্য, আইনি দলিল নয়</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Footer Links -->
            <div class="mt-8 text-center">
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 transition">
                        <i class="fas fa-home mr-1"></i> হোম পেজ
                    </a>
                    <a href="{{ route('certificate.verify.form') }}" class="text-blue-600 hover:text-blue-800 transition">
                        <i class="fas fa-search mr-1"></i> অন্য সনদ যাচাই
                    </a>
                    <a href="javascript:void(0)" onclick="window.print()" class="text-blue-600 hover:text-blue-800 transition">
                        <i class="fas fa-print mr-1"></i> প্রিন্ট
                    </a>
                    <a href="{{ route('certificate.pdf', $application->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition">
                        <i class="fas fa-download mr-1"></i> PDF ডাউনলোড
                    </a>
                </div>
                
                <div class="mt-6 text-sm text-gray-500">
                    <p>© {{ date('Y') }} {{ $unionName ?? 'ইউনিয়ন পরিষদ' }}। সকল অধিকার সংরক্ষিত।</p>
                    <p class="mt-1">এই সিস্টেম ডিজিটাল বাংলাদেশ এর অংশ হিসেবে চালু করা হয়েছে</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .bg-gradient-to-b {
            background: white !important;
        }
        
        .shadow-lg, .shadow-md {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        .rounded-2xl {
            border-radius: 0 !important;
        }
        
        .grid {
            display: block !important;
        }
        
        .space-y-6 > * + * {
            margin-top: 20px !important;
        }
        
        .mt-8 {
            margin-top: 20px !important;
        }
        
        .py-8 {
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }
        
        .text-center {
            text-align: center !important;
        }
        
        .flex {
            display: block !important;
        }
        
        .bg-gradient-to-r {
            background: #f8f9fa !important;
        }
        
        .border-b {
            border-bottom: 2px solid #000 !important;
        }
        
        .bg-white {
            background: white !important;
            border: 1px solid #ddd !important;
            margin-bottom: 15px !important;
            page-break-inside: avoid !important;
        }
        
        .bg-gray-50, .bg-blue-50, .bg-green-50, .bg-purple-50, .bg-red-50, .bg-yellow-50 {
            background: #f8f9fa !important;
        }
        
        .inline-block {
            display: inline-block !important;
        }
        
        .hidden {
            display: none !important;
        }
        
        a {
            color: black !important;
            text-decoration: none !important;
        }
        
        button {
            display: none !important;
        }
        
        .verification-stamp {
            border: 3px solid #000 !important;
            background: white !important;
            color: black !important;
        }
    }
</style>

@push('scripts')
<script>
function shareCertificate() {
    const certificateNumber = "{{ $application->certificate_number }}";
    const verificationUrl = "{{ route('certificate.verify', ['cid' => $application->certificate_number]) }}";
    const message = `সনদ নম্বর ${certificateNumber} যাচাই করুন: ${verificationUrl}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'সনদ যাচাই রিপোর্ট',
            text: message,
            url: verificationUrl
        })
        .then(() => console.log('Shared successfully'))
        .catch((error) => console.log('Error sharing:', error));
    } else {
        // Fallback: Copy to clipboard
        navigator.clipboard.writeText(message).then(() => {
            alert('লিংক ক্লিপবোর্ডে কপি করা হয়েছে!');
        });
    }
}

function printCertificate() {
    window.print();
}

// Add print button to page for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Add print button dynamically
    const printBtn = document.createElement('button');
    printBtn.innerHTML = '<i class="fas fa-print mr-2"></i> প্রিন্ট';
    printBtn.className = 'no-print fixed bottom-6 right-6 z-50 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition';
    printBtn.onclick = printCertificate;
    document.body.appendChild(printBtn);
    
    // Add share button
    const shareBtn = document.createElement('button');
    shareBtn.innerHTML = '<i class="fas fa-share-alt mr-2"></i> শেয়ার';
    shareBtn.className = 'no-print fixed bottom-6 right-24 z-50 bg-green-600 text-white p-3 rounded-full shadow-lg hover:bg-green-700 transition';
    shareBtn.onclick = shareCertificate;
    document.body.appendChild(shareBtn);
});
</script>
@endpush
@endsection