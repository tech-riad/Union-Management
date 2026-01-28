@extends('layouts.super-admin')

@section('title', 'সিস্টেম আপডেট')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-sync-alt mr-2 text-blue-600"></i>
                    সিস্টেম আপডেট
                </h1>
                <p class="text-gray-600">
                    সিস্টেমের সর্বশেষ সংস্করণ চেক করুন এবং আপডেট করুন
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="checkForUpdates()" class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-800 text-white rounded-lg hover:from-green-700 hover:to-green-900 transition-all duration-200 shadow-md hover:shadow-lg flex items-center">
                    <i class="fas fa-search mr-2" id="checkIcon"></i>
                    <span id="checkText">আপডেট চেক করুন</span>
                </button>
                <a href="{{ route('super_admin.dashboard') }}" class="px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-800 text-white rounded-lg hover:from-gray-700 hover:to-gray-900 transition-all duration-200 shadow-md hover:shadow-lg flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    ড্যাশবোর্ডে ফিরে যান
                </a>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Current System Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        বর্তমান সিস্টেম তথ্য
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- System Details -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">সিস্টেম ভার্সন</label>
                                <div class="flex items-center">
                                    <div class="px-3 py-2 bg-blue-100 text-blue-800 rounded-lg font-bold">
                                        <i class="fas fa-code-branch mr-2"></i>
                                        v1.0.0
                                    </div>
                                    <span class="ml-3 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        রিলিজ
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">ইন্সটলেশন তারিখ</label>
                                <div class="px-3 py-2 bg-gray-100 text-gray-800 rounded-lg">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    ১৫ ডিসেম্বর, ২০২৩
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">সিস্টেম স্ট্যাটাস</label>
                                <div class="px-3 py-2 bg-green-100 text-green-800 rounded-lg font-medium">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    স্টেবল ও কার্যকরী
                                </div>
                            </div>
                        </div>
                        
                        <!-- Technical Details -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">PHP ভার্সন</label>
                                <div class="px-3 py-2 bg-purple-100 text-purple-800 rounded-lg">
                                    <i class="fab fa-php mr-2"></i>
                                    {{ phpversion() }}
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Laravel ভার্সন</label>
                                <div class="px-3 py-2 bg-indigo-100 text-indigo-800 rounded-lg">
                                    <i class="fab fa-laravel mr-2"></i>
                                    {{ app()->version() }}
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">ডাটাবেস</label>
                                <div class="px-3 py-2 bg-teal-100 text-teal-800 rounded-lg">
                                    <i class="fas fa-database mr-2"></i>
                                    MySQL 8.0
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Health -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">সিস্টেম স্বাস্থ্য</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600 mb-1">95%</div>
                                <p class="text-sm text-gray-600">পারফরম্যান্স</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600 mb-1">99.8%</div>
                                <p class="text-sm text-gray-600">আপটাইম</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600 mb-1">0.02%</div>
                                <p class="text-sm text-gray-600">এরর রেট</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-cloud-download-alt mr-2 text-green-600"></i>
                    আপডেট স্ট্যাটাস
                </h2>
            </div>
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">আপনার সিস্টেম আপ-টু-ডেট</h3>
                    <p class="text-gray-600 text-sm">সর্বশেষ ভার্সন ইন্সটল করা আছে</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">সর্বশেষ চেক</span>
                        <span class="font-medium" id="lastCheckTime">আজ ১১:৩০ AM</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">পরবর্তী অটো চেক</span>
                        <span class="font-medium">২৪ ঘণ্টা পরে</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">আপডেট চ্যানেল</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">স্টেবল</span>
                    </div>
                </div>
                
                <div class="mt-6">
                    <div class="flex items-center text-sm text-gray-600 mb-2">
                        <i class="fas fa-cog mr-2"></i>
                        <span>অটো আপডেট সেটিংস</span>
                    </div>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600" checked disabled>
                            <span class="ml-2 text-sm text-gray-700">নিরাপত্তা আপডেট</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">নতুন ফিচার</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600" checked>
                            <span class="ml-2 text-sm text-gray-700">বাগ ফিক্স</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update History -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-history mr-2 text-purple-600"></i>
                আপডেট হিস্টোরি
            </h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ভার্সন</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">প্রকার</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">তারিখ</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">বিবরণ</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900">v1.0.0</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-star mr-1"></i>
                                    রিলিজ
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ১৫ ডিসেম্বর, ২০২৩
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                ইনিশিয়াল রিলিজ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    ইন্সটলড
                                </span>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900">v0.9.5</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-bug mr-1"></i>
                                    বাগ ফিক্স
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ১০ ডিসেম্বর, ২০২৩
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                বাগ ফিক্সেস এবং অপ্টিমাইজেশন
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    রিপ্লেসড
                                </span>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900">v0.9.0</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-flask mr-1"></i>
                                    বিটা
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ১ ডিসেম্বর, ২০২৩
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                বিটা ভার্সন রিলিজ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    রিপ্লেসড
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Manual Update Section -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-yellow-100">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-tools mr-2 text-yellow-600"></i>
                ম্যানুয়াল আপডেট
            </h2>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">আপডেট ফাইল আপলোড</h3>
                <p class="text-gray-600 mb-4">আপনি যদি ম্যানুয়ালি আপডেট করতে চান, তাহলে নতুন ভার্সনের ফাইল আপলোড করুন।</p>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-600 mb-2">আপডেট ফাইল ড্রপ করুন অথবা ব্রাউজ করুন</p>
                    <p class="text-sm text-gray-500 mb-4">.zip ফাইল সাপোর্টেড</p>
                    <button class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        <i class="fas fa-folder-open mr-2"></i>
                        ফাইল নির্বাচন করুন
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Backup Settings -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-save mr-2 text-blue-600"></i>
                        ব্যাকআপ অপশনস
                    </h4>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600" checked>
                            <span class="ml-2 text-sm text-gray-700">আপডেটের আগে অটো ব্যাকআপ নিন</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600" checked>
                            <span class="ml-2 text-sm text-gray-700">ডাটাবেস ব্যাকআপ নিন</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">ফাইলস ব্যাকআপ নিন</span>
                        </label>
                    </div>
                </div>
                
                <!-- Update Options -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-cogs mr-2 text-purple-600"></i>
                        আপডেট অপশনস
                    </h4>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" name="update_type" class="form-radio h-4 w-4 text-blue-600" checked>
                            <span class="ml-2 text-sm text-gray-700">ইনক্রিমেন্টাল আপডেট</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="update_type" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">ফুল আপডেট</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600" checked>
                            <span class="ml-2 text-sm text-gray-700">আপডেটের পর মেইনটেনেন্স মোড চালু করুন</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="simulateUpdate()" class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-800 text-white rounded-lg hover:from-green-700 hover:to-green-900 transition-all duration-200 shadow-md flex items-center">
                    <i class="fas fa-play mr-2" id="updateIcon"></i>
                    <span id="updateText">আপডেট শুরু করুন</span>
                </button>
                <button onclick="rollbackUpdate()" class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-800 text-white rounded-lg hover:from-red-700 hover:to-red-900 transition-all duration-200 shadow-md flex items-center">
                    <i class="fas fa-undo mr-2"></i>
                    রোলব্যাক
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Progress Modal -->
<div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-sync-alt animate-spin mr-2 text-blue-600"></i>
                আপডেট প্রগ্রেস
            </h3>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>প্রগ্রেস</span>
                    <span id="progressPercent">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progressBar" class="bg-green-500 h-2 rounded-full" style="width: 0%"></div>
                </div>
            </div>
            
            <div id="updateSteps" class="space-y-3 mb-6">
                <div class="flex items-center">
                    <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                        <i class="fas fa-check text-blue-600 text-xs"></i>
                    </div>
                    <span class="text-sm text-gray-700">ব্যাকআপ তৈরি হচ্ছে...</span>
                </div>
                <div class="flex items-center opacity-50">
                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                        <span class="text-gray-500 text-xs">2</span>
                    </div>
                    <span class="text-sm text-gray-700">ফাইল ডাউনলোড করা হচ্ছে...</span>
                </div>
                <div class="flex items-center opacity-50">
                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                        <span class="text-gray-500 text-xs">3</span>
                    </div>
                    <span class="text-sm text-gray-700">ফাইল এক্সট্র্যাক্ট করা হচ্ছে...</span>
                </div>
                <div class="flex items-center opacity-50">
                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                        <span class="text-gray-500 text-xs">4</span>
                    </div>
                    <span class="text-sm text-gray-700">ফাইল রিপ্লেস করা হচ্ছে...</span>
                </div>
                <div class="flex items-center opacity-50">
                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                        <span class="text-gray-500 text-xs">5</span>
                    </div>
                    <span class="text-sm text-gray-700">ডাটাবেস আপডেট করা হচ্ছে...</span>
                </div>
                <div class="flex items-center opacity-50">
                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                        <span class="text-gray-500 text-xs">6</span>
                    </div>
                    <span class="text-sm text-gray-700">ক্লিনআপ...</span>
                </div>
            </div>
            
            <div id="updateLogs" class="bg-gray-50 rounded-lg p-3 max-h-32 overflow-y-auto text-xs">
                <div class="text-gray-600">লগস এখানে দেখানো হবে...</div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button onclick="closeUpdateModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                বাতিল করুন
            </button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                আপডেট সফল!
            </h3>
        </div>
        <div class="p-6 text-center">
            <div class="w-20 h-20 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-green-600 text-3xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-800 mb-2">আপডেট সফলভাবে সম্পন্ন হয়েছে</h4>
            <p class="text-gray-600 mb-4">সিস্টেম v1.0.0 থেকে v1.1.0 এ আপডেট করা হয়েছে।</p>
            <div class="bg-gray-50 rounded-lg p-4 text-left mb-4">
                <h5 class="font-medium text-gray-800 mb-2">নতুন ফিচারসমূহ:</h5>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        উন্নত ড্যাশবোর্ড
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        নতুন রিপোর্টিং সিস্টেম
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        পারফরম্যান্স অপ্টিমাইজেশন
                    </li>
                </ul>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button onclick="closeSuccessModal()" class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-800 text-white rounded-lg hover:from-green-700 hover:to-green-900 transition-all duration-200">
                ঠিক আছে
            </button>
        </div>
    </div>
</div>

<style>
.premium-card {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.premium-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    animation: shimmer 2s infinite;
}

.premium-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.update-step-active {
    opacity: 1 !important;
}

.update-step-completed .w-6 {
    background-color: #10b981 !important;
}

.update-step-completed i {
    color: white !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize
    updateLastCheckTime();
});

// Update last check time
function updateLastCheckTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
    });
    const dateString = now.toLocaleDateString('bn-BD', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
    
    document.getElementById('lastCheckTime').textContent = `আজ ${timeString}`;
}

// Check for updates
function checkForUpdates() {
    const checkIcon = document.getElementById('checkIcon');
    const checkText = document.getElementById('checkText');
    const button = checkIcon.closest('button');
    
    // Save original state
    const originalIcon = checkIcon.className;
    const originalText = checkText.textContent;
    
    // Show loading state
    checkIcon.className = 'fas fa-spinner fa-spin mr-2';
    checkText.textContent = 'চেক করা হচ্ছে...';
    button.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Show success message
        showNotification('আপনার সিস্টেম আপ-টু-ডেট। নতুন আপডেট পাওয়া যায়নি।', 'success');
        
        // Update last check time
        updateLastCheckTime();
        
        // Restore button state
        setTimeout(() => {
            checkIcon.className = originalIcon;
            checkText.textContent = originalText;
            button.disabled = false;
        }, 1000);
    }, 2000);
}

// Simulate update process
function simulateUpdate() {
    // Show update modal
    document.getElementById('updateModal').classList.remove('hidden');
    
    // Reset progress
    document.getElementById('progressBar').style.width = '0%';
    document.getElementById('progressPercent').textContent = '0%';
    
    // Reset steps
    const steps = document.querySelectorAll('#updateSteps > div');
    steps.forEach((step, index) => {
        step.classList.remove('update-step-active', 'update-step-completed');
        if (index > 0) step.classList.add('opacity-50');
    });
    
    // Start update simulation
    startUpdateSimulation();
}

// Start update simulation
function startUpdateSimulation() {
    const steps = [
        { name: 'ব্যাকআপ তৈরি হচ্ছে...', duration: 1500 },
        { name: 'ফাইল ডাউনলোড করা হচ্ছে...', duration: 2000 },
        { name: 'ফাইল এক্সট্র্যাক্ট করা হচ্ছে...', duration: 1500 },
        { name: 'ফাইল রিপ্লেস করা হচ্ছে...', duration: 2000 },
        { name: 'ডাটাবেস আপডেট করা হচ্ছে...', duration: 2500 },
        { name: 'ক্লিনআপ...', duration: 1000 }
    ];
    
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const updateLogs = document.getElementById('updateLogs');
    const stepElements = document.querySelectorAll('#updateSteps > div');
    
    let currentStep = 0;
    let totalDuration = steps.reduce((sum, step) => sum + step.duration, 0);
    let elapsedTime = 0;
    
    updateLogs.innerHTML = '<div class="text-gray-600">আপডেট প্রক্রিয়া শুরু হয়েছে...</div>';
    
    function updateProgress() {
        elapsedTime += 100;
        const progress = Math.min((elapsedTime / totalDuration) * 100, 100);
        
        progressBar.style.width = progress + '%';
        progressPercent.textContent = Math.round(progress) + '%';
        
        if (currentStep < steps.length) {
            const step = steps[currentStep];
            if (elapsedTime >= steps.slice(0, currentStep + 1).reduce((sum, s) => sum + s.duration, 0)) {
                // Mark current step as completed
                stepElements[currentStep].classList.add('update-step-completed');
                stepElements[currentStep].classList.add('update-step-active');
                
                // Add log entry
                const logEntry = document.createElement('div');
                logEntry.className = 'text-green-600 mb-1';
                logEntry.innerHTML = `<i class="fas fa-check mr-1"></i> ${step.name} সম্পন্ন`;
                updateLogs.prepend(logEntry);
                
                // Activate next step
                currentStep++;
                if (currentStep < steps.length) {
                    stepElements[currentStep].classList.add('update-step-active');
                    stepElements[currentStep].classList.remove('opacity-50');
                }
            }
        }
        
        if (progress < 100) {
            setTimeout(updateProgress, 100);
        } else {
            // Update complete
            setTimeout(() => {
                closeUpdateModal();
                setTimeout(() => {
                    document.getElementById('successModal').classList.remove('hidden');
                }, 300);
            }, 1000);
        }
    }
    
    // Start first step
    stepElements[0].classList.add('update-step-active');
    stepElements[0].classList.remove('opacity-50');
    
    // Start progress updates
    updateProgress();
}

// Close update modal
function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
}

// Close success modal
function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
    showNotification('সিস্টেম সফলভাবে আপডেট করা হয়েছে!', 'success');
}

// Rollback update
function rollbackUpdate() {
    if (confirm('আপনি কি নিশ্চিত যে আপনি শেষ আপডেট রোলব্যাক করতে চান?')) {
        showNotification('রোলব্যাক প্রক্রিয়া শুরু হয়েছে...', 'warning');
        // In real implementation, this would trigger rollback
        setTimeout(() => {
            showNotification('সিস্টেম সফলভাবে পূর্ববর্তী ভার্সনে ফিরিয়ে আনা হয়েছে।', 'success');
        }, 2000);
    }
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notification
    const existingNotification = document.getElementById('system-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    const notification = document.createElement('div');
    notification.id = 'system-notification';
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${
                type === 'success' ? 'check-circle' :
                type === 'error' ? 'exclamation-circle' :
                type === 'warning' ? 'exclamation-triangle' :
                'info-circle'
            } mr-3 text-xl"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endsection