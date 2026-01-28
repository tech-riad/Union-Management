@extends('layouts.super-admin')

@section('title', 'নতুন নাগরিক তৈরি করুন')

@section('content')
<div class="animate-fade-in">
    <!-- শিরোনাম অংশ -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-user-plus text-green-600 mr-2"></i>
                    নতুন নাগরিক তৈরি করুন
                </h2>
                <p class="text-gray-600 mt-2">সিস্টেমে একটি নতুন নাগরিক যোগ করুন</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.users.citizens.index') }}" 
                   class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-gray-600 to-gray-800 hover:from-gray-700 hover:to-gray-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    নাগরিকদের তালিকায় ফিরে যান
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- প্রধান ফর্ম -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- ফর্ম শিরোনাম -->
                <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fas fa-user-plus mr-2"></i>
                        নাগরিক তথ্য
                    </h3>
                    <p class="text-green-100 text-sm mt-1">একটি নতুন নাগরিক অ্যাকাউন্ট তৈরি করতে নিচের বিবরণ পূরণ করুন</p>
                </div>
                
                <!-- ফর্ম কন্টেন্ট -->
                <form action="{{ route('super_admin.users.citizens.store') }}" method="POST" id="citizenForm">
                    @csrf
                    
                    <div class="p-6 space-y-6">
                        @if($errors->any())
                        <div class="rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-600 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">নিম্নলিখিত ত্রুটিগুলি সংশোধন করুন:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- ব্যক্তিগত তথ্য -->
                        <div class="space-y-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                <i class="fas fa-user text-green-500 mr-2"></i>
                                ব্যক্তিগত তথ্য
                            </h4>
                            
                            <!-- নাম ফিল্ড -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        পূর্ণ নাম <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}"
                                               class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300"
                                               placeholder="পূর্ণ নাম লিখুন"
                                               required>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">
                                        মোবাইল নম্বর <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone text-gray-400"></i>
                                        </div>
                                        <input type="tel" 
                                               id="mobile" 
                                               name="mobile" 
                                               value="{{ old('mobile') }}"
                                               class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300"
                                               placeholder="01XXXXXXXXX"
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ইমেইল ফিল্ড -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    ইমেইল ঠিকানা
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300"
                                           placeholder="citizen@example.com">
                                </div>
                            </div>
                        </div>

                        <!-- নিরাপত্তা সেটিংস -->
                        <div class="space-y-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                <i class="fas fa-lock text-green-500 mr-2"></i>
                                নিরাপত্তা সেটিংস
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        পাসওয়ার্ড <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-key text-gray-400"></i>
                                        </div>
                                        <input type="password" 
                                               id="password" 
                                               name="password" 
                                               class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300"
                                               placeholder="পাসওয়ার্ড লিখুন"
                                               required
                                               minlength="8">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <button type="button" onclick="togglePassword('password')" class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        পাসওয়ার্ড নিশ্চিত করুন <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-key text-gray-400"></i>
                                        </div>
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300"
                                               placeholder="পাসওয়ার্ড আবার লিখুন"
                                               required>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <button type="button" onclick="togglePassword('password_confirmation')" class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ফর্ম ফুটার -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-shield-alt mr-1"></i>
                                সকল তথ্য এনক্রিপশনের মাধ্যমে সুরক্ষিত
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="reset" 
                                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200">
                                    <i class="fas fa-redo mr-2"></i>
                                    ফর্ম রিসেট করুন
                                </button>
                                <button type="submit" 
                                        id="submitBtn"
                                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-800 hover:from-green-700 hover:to-green-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    নাগরিক অ্যাকাউন্ট তৈরি করুন
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- সাইড তথ্য -->
        <div class="lg:col-span-1">
            <!-- গুরুত্বপূর্ণ তথ্য -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-green-500 mr-2"></i>
                    গুরুত্বপূর্ণ তথ্য
                </h3>
                
                <div class="space-y-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-id-card text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-800">NID যাচাইকরণ</h4>
                            <p class="text-sm text-gray-600 mt-1">জাতীয় পরিচয়পত্র অবশ্যই অনন্য এবং যাচাইকৃত হতে হবে।</p>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-800">মোবাইল নম্বর</h4>
                            <p class="text-sm text-gray-600 mt-1">একটি বৈধ ১১-অঙ্কের বাংলাদেশী নম্বর হতে হবে।</p>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-home text-amber-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-800">ঠিকানার বিবরণ</h4>
                            <p class="text-sm text-gray-600 mt-1">সার্টিফিকেট বিতরণের জন্য সম্পূর্ণ ঠিকানা প্রদান করুন।</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- নাগরিক বৈশিষ্ট্য -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-star text-amber-500 mr-2"></i>
                    নাগরিক বৈশিষ্ট্য
                </h3>
                
                <div class="space-y-3">
                    <div class="flex items-center text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>অনলাইন সার্টিফিকেট আবেদন</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>আবেদন অবস্থা ট্র্যাকিং</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>অনলাইন পেমেন্ট সুবিধা</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>সার্টিফিকেট ডাউনলোড</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>প্রোফাইল ব্যবস্থাপনা</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling.querySelector('button');
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection