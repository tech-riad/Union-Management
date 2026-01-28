@extends('layouts.public')

@section('title', 'সনদ যাচাই - অকার্যকর')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-red-50 to-gray-100 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            
            <!-- Error Header -->
            <div class="text-center mb-8">
                <div class="inline-block p-4 bg-red-100 rounded-full mb-4">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    সনদ যাচাই ব্যর্থ
                </h1>
                <p class="text-gray-600">
                    প্রদত্ত সনদ নম্বরটি আমাদের ডাটাবেসে পাওয়া যায়নি
                </p>
            </div>

            <!-- Error Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-red-200">
                <!-- Error Banner -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 text-white py-4 px-6">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-2xl mr-3"></i>
                        <div>
                            <h2 class="text-xl font-bold">অকার্যকর সনদ নম্বর</h2>
                            <p class="text-red-100 text-sm">
                                এই সনদটি আমাদের সিস্টেমে নিবন্ধিত নয়
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Error Content -->
                <div class="p-8">
                    <div class="text-center mb-8">
                        <div class="inline-block p-6 bg-red-50 rounded-full mb-4">
                            <i class="fas fa-search text-5xl text-red-500"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            সনদ পাওয়া যায়নি
                        </h3>
                        <p class="text-gray-600 mb-6">
                            সনদ নম্বর: 
                            <code class="bg-gray-100 px-3 py-1 rounded font-mono text-lg font-bold text-red-600">
                                {{ $certificateNumber ?? 'N/A' }}
                            </code>
                        </p>
                    </div>
                    
                    <!-- Error Details -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                        <h4 class="font-semibold text-yellow-800 mb-3 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            সম্ভাব্য কারণসমূহ:
                        </h4>
                        <ul class="text-yellow-700 space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-500 mt-1 mr-2 text-sm"></i>
                                <span>সনদ নম্বরটি ভুলভাবে প্রবেশ করানো হয়েছে</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-500 mt-1 mr-2 text-sm"></i>
                                <span>সনদটি এখনও অনুমোদিত হয়নি</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-500 mt-1 mr-2 text-sm"></i>
                                <span>সনদটি বাতিল বা প্রত্যাহার করা হয়েছে</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-500 mt-1 mr-2 text-sm"></i>
                                <span>সনদটি অন্য ইউনিয়ন পরিষদ থেকে জারি করা হয়েছে</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- What to Do -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-lightbulb mr-2"></i>
                            করণীয়:
                        </h4>
                        <ul class="text-blue-700 space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>সনদ নম্বরটি পুনরায় চেক করুন</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>সনদের QR কোড স্ক্যান করে আবার চেষ্টা করুন</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>প্রদানকারী কর্তৃপক্ষের সাথে যোগাযোগ করুন</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Error Message (if any) -->
                    @if(isset($error) && $error)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-bug text-red-500 mr-3"></i>
                            <div>
                                <h5 class="font-semibold text-red-800">ত্রুটি বার্তা:</h5>
                                <p class="text-red-700 mt-1">{{ $error }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <a href="{{ route('certificate.verify.form') }}" 
                           class="block w-full text-center py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-md">
                            <i class="fas fa-search mr-2"></i>
                            নতুন সনদ যাচাই করুন
                        </a>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ url('/') }}" 
                               class="block text-center py-3 px-4 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-medium rounded-lg hover:from-gray-700 hover:to-gray-800 transition shadow-md">
                                <i class="fas fa-home mr-2"></i>
                                হোম পেজ
                            </a>
                            
                            <button onclick="window.print()" 
                                    class="w-full py-3 px-4 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg hover:from-green-700 hover:to-green-800 transition shadow-md">
                                <i class="fas fa-print mr-2"></i>
                                এই পৃষ্ঠা প্রিন্ট করুন
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Verification Tips -->
            <div class="mt-8 bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    সনদ যাচাই সহায়িকা
                </h4>
                <div class="text-sm text-gray-600 space-y-2">
                    <p>✅ সনদ নম্বর সাধারণত এই ফরম্যাটে থাকে: <code class="bg-gray-100 px-2 py-1 rounded">CERT-YYYY-XXXXX</code></p>
                    <p>✅ সনদের উপরের ডান কোণায় QR কোড স্ক্যান করুন</p>
                    <p>✅ সনদের নিচে বাম পাশে সনদ নম্বর দেখতে পাবেন</p>
                    <p>✅ সনদ নম্বর সঠিকভাবে কপি করে এখানে পেস্ট করুন</p>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="mt-8 text-center">
                <p class="text-gray-600 mb-2">
                    আরও সাহায্যের প্রয়োজন?
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="tel:+880XXXXXXXXXX" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        <i class="fas fa-phone mr-2"></i>
                        কল করুন
                    </a>
                    <a href="mailto:info@union.gov.bd" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        <i class="fas fa-envelope mr-2"></i>
                        ইমেইল করুন
                    </a>
                    <a href="{{ url('/contact') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        <i class="fas fa-headset mr-2"></i>
                        কাস্টমার সাপোর্ট
                    </a>
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
        
        .shadow-xl, .shadow-md {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        .rounded-2xl {
            border-radius: 0 !important;
        }
        
        .bg-white {
            background: white !important;
            border: 1px solid #ddd !important;
            margin-bottom: 15px !important;
        }
        
        .bg-red-50, .bg-yellow-50, .bg-blue-50 {
            background: #f8f9fa !important;
        }
        
        .text-red-600, .text-red-500 {
            color: #dc3545 !important;
        }
        
        .text-yellow-700, .text-yellow-800 {
            color: #856404 !important;
        }
        
        .text-blue-600, .text-blue-700, .text-blue-800 {
            color: #0056b3 !important;
        }
        
        .bg-gradient-to-r {
            background: #f8f9fa !important;
        }
        
        button, .no-print-button {
            display: none !important;
        }
        
        a {
            color: black !important;
            text-decoration: none !important;
        }
        
        code {
            background: #f8f9fa !important;
            border: 1px solid #ddd !important;
        }
    }
</style>

@push('scripts')
<script>
// Add print button dynamically
document.addEventListener('DOMContentLoaded', function() {
    const printBtn = document.createElement('button');
    printBtn.innerHTML = '<i class="fas fa-print mr-2"></i> প্রিন্ট';
    printBtn.className = 'no-print fixed bottom-6 right-6 z-50 bg-green-600 text-white p-3 rounded-full shadow-lg hover:bg-green-700 transition';
    printBtn.onclick = function() {
        window.print();
    };
    document.body.appendChild(printBtn);
});
</script>
@endpush
@endsection