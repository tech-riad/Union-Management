@extends('layouts.super-admin')

@section('title', 'নতুন অ্যাডমিন তৈরি করুন')

@section('content')
<div class="animate-fade-in">
    <!-- শিরোনাম অংশ -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-user-plus text-blue-600 mr-2"></i>
                    নতুন অ্যাডমিন তৈরি করুন
                </h2>
                <p class="text-gray-600 mt-2">সিস্টেমে একটি নতুন অ্যাডমিনিস্ট্রেটর যোগ করুন</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.users.admins.index') }}" 
                   class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-gray-600 to-gray-800 hover:from-gray-700 hover:to-gray-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    অ্যাডমিনদের তালিকায় ফিরে যান
                </a>
            </div>
        </div>
        
        <!-- ব্রেডক্রাম্ব -->
        <div class="mt-4 flex items-center text-sm text-gray-500">
            <a href="{{ route('super_admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-home mr-1"></i> ড্যাশবোর্ড
            </a>
            <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
            <a href="{{ route('super_admin.users.admins.index') }}" class="text-blue-600 hover:text-blue-800">
                অ্যাডমিন ব্যবস্থাপনা
            </a>
            <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
            <span class="text-gray-600">নতুন অ্যাডমিন তৈরি করুন</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- প্রধান ফর্ম -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- ফর্ম শিরোনাম -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fas fa-user-shield mr-2"></i>
                        অ্যাডমিন তথ্য
                    </h3>
                    <p class="text-blue-100 text-sm mt-1">একটি নতুন অ্যাডমিন অ্যাকাউন্ট তৈরি করতে নিচের বিবরণ পূরণ করুন</p>
                </div>
                
                <!-- ফর্ম কন্টেন্ট -->
                <form action="{{ route('super_admin.users.admins.store') }}" method="POST" id="adminForm">
                    @csrf
                    
                    <div class="p-6 space-y-6">
                        @if($errors->any())
                        <div class="rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-600 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">দয়া করে নিম্নলিখিত ত্রুটিগুলি সংশোধন করুন:</h3>
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

                        <!-- মৌলিক তথ্য অংশ -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                    <i class="fas fa-id-card text-blue-500 mr-2"></i>
                                    মৌলিক তথ্য
                                </h4>
                                
                                <!-- নাম ফিল্ড -->
                                <div class="mb-6">
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
                                               class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('name') border-red-500 @enderror"
                                               placeholder="অ্যাডমিনের পূর্ণ নাম লিখুন"
                                               required>
                                    </div>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- মোবাইল ফিল্ড -->
                                <div class="mb-6">
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
                                               class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('mobile') border-red-500 @enderror"
                                               placeholder="01XXXXXXXXX"
                                               required
                                               maxlength="11"
                                               pattern="[0-9]{11}">
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        ১১-অঙ্কের বাংলাদেশী মোবাইল নম্বর লিখুন
                                    </div>
                                    @error('mobile')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- ইমেইল ফিল্ড -->
                                <div class="mb-6">
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
                                               class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('email') border-red-500 @enderror"
                                               placeholder="admin@example.com">
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        ঐচ্ছিক তবে নোটিফিকেশনের জন্য সুপারিশকৃত
                                    </div>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- পাসওয়ার্ড অংশ -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                    <i class="fas fa-lock text-blue-500 mr-2"></i>
                                    নিরাপত্তা সেটিংস
                                </h4>
                                
                                <!-- পাসওয়ার্ড ফিল্ড -->
                                <div class="mb-6">
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
                                               class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror"
                                               placeholder="পাসওয়ার্ড লিখুন"
                                               required
                                               minlength="8">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <button type="button" onclick="togglePassword('password')" class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            সর্বনিম্ন ৮টি অক্ষর
                                        </div>
                                        <div id="passwordStrength" class="text-xs"></div>
                                    </div>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- পাসওয়ার্ড নিশ্চিতকরণ ফিল্ড -->
                                <div class="mb-6">
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
                                               class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                               placeholder="পাসওয়ার্ড আবার লিখুন"
                                               required>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <button type="button" onclick="togglePassword('password_confirmation')" class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        নিশ্চিতকরণের জন্য পাসওয়ার্ড পুনরায় লিখুন
                                    </div>
                                </div>
                                
                                <!-- পাসওয়ার্ড মিল ইন্ডিকেটর -->
                                <div id="passwordMatch" class="hidden mb-6 p-3 rounded-lg bg-gray-50 border border-gray-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <span class="text-sm text-gray-700">পাসওয়ার্ড মিলেছে</span>
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
                                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    অ্যাডমিন অ্যাকাউন্ট তৈরি করুন
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- সাইড তথ্য -->
        <div class="lg:col-span-1">
            <!-- অনুমতি কার্ড -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-lg mb-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-shield-alt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">অ্যাডমিন অনুমতি</h3>
                        <p class="text-blue-100 text-sm">সিস্টেম এক্সেস লেভেল</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-300 mr-3"></i>
                        <span>অ্যাপ্লিকেশন ব্যবস্থাপনা</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-300 mr-3"></i>
                        <span>সার্টিফিকেট ইস্যু</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-300 mr-3"></i>
                        <span>পেমেন্ট প্রক্রিয়াকরণ</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-300 mr-3"></i>
                        <span>রিপোর্ট ও বিশ্লেষণ</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-300 mr-3"></i>
                        <span>ব্যবহারকারী ব্যবস্থাপনা (দেখুন)</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-300 mr-3"></i>
                        <span>সিস্টেম সেটিংস</span>
                    </div>
                </div>
            </div>
            
            <!-- গুরুত্বপূর্ণ নোট -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-exclamation-circle text-amber-500 mr-2"></i>
                    গুরুত্বপূর্ণ নোট
                </h3>
                
                <div class="space-y-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-800">মোবাইল নম্বর</h4>
                            <p class="text-sm text-gray-600 mt-1">একটি বৈধ ১১-অঙ্কের বাংলাদেশী মোবাইল নম্বর হতে হবে যা ০১ দিয়ে শুরু হয়।</p>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-lock text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-800">পাসওয়ার্ড নিরাপত্তা</h4>
                            <p class="text-sm text-gray-600 mt-1">পাসওয়ার্ড অবশ্যই কমপক্ষে ৮টি অক্ষরের হতে হবে। একটি শক্তিশালী, অনন্য পাসওয়ার্ড ব্যবহার বিবেচনা করুন।</p>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-shield text-purple-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-800">অ্যাডমিন বিশেষাধিকার</h4>
                            <p class="text-sm text-gray-600 mt-1">অ্যাডমিন সংবেদনশীল সিস্টেম ফাংশনে অ্যাক্সেস পাবে। এই ভূমিকা সাবধানে নির্ধারণ করুন।</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- দ্রুত কাজ -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl p-6 text-white shadow-lg mt-6">
                <h3 class="text-lg font-bold mb-4">দ্রুত কাজ</h3>
                <div class="space-y-3">
                    <a href="{{ route('super_admin.users.admins.index') }}" 
                       class="flex items-center justify-between p-3 bg-gray-700/50 rounded-xl hover:bg-gray-700 transition-all duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-list mr-3"></i>
                            <span>সকল অ্যাডমিন দেখুন</span>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#" 
                       class="flex items-center justify-between p-3 bg-gray-700/50 rounded-xl hover:bg-gray-700 transition-all duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-cog mr-3"></i>
                            <span>অ্যাডমিন সেটিংস</span>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#" 
                       class="flex items-center justify-between p-3 bg-gray-700/50 rounded-xl hover:bg-gray-700 transition-all duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-history mr-3"></i>
                            <span>কার্যকলাপ লগ</span>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- সাফল্য মোডাল -->
<div id="successModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="successModalContent">
        <div class="p-6 text-center">
            <div class="flex items-center justify-center w-16 h-16 mx-auto bg-gradient-to-r from-green-100 to-green-200 rounded-full mb-4">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">অ্যাডমিন সফলভাবে তৈরি হয়েছে!</h3>
            <p class="text-gray-600 mb-6" id="successMessage"></p>
            <div class="flex space-x-3">
                <button type="button" onclick="closeSuccessModal()" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-gray-500 to-gray-700 hover:from-gray-600 hover:to-gray-800 text-white font-medium rounded-xl shadow hover:shadow-lg transition-all duration-200">
                    বন্ধ করুন
                </button>
                <a href="{{ route('super_admin.users.admins.create') }}" 
                   class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-medium rounded-xl shadow hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                    আরেকটি যোগ করুন
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('adminForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const mobileInput = document.getElementById('mobile');
    const submitBtn = document.getElementById('submitBtn');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordMatch = document.getElementById('passwordMatch');
    
    // পাসওয়ার্ড দৃশ্যমানতা টগল করুন
    window.togglePassword = function(fieldId) {
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
    };
    
    // মোবাইল নম্বর ভ্যালিডেশন
    mobileInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        this.value = value;
        validateMobile();
    });
    
    function validateMobile() {
        const mobile = mobileInput.value;
        const isValid = mobile.length === 11 && mobile.startsWith('01');
        
        if (mobile.length > 0) {
            if (isValid) {
                mobileInput.classList.remove('border-red-500');
                mobileInput.classList.add('border-green-500');
            } else {
                mobileInput.classList.remove('border-green-500');
                mobileInput.classList.add('border-red-500');
            }
        } else {
            mobileInput.classList.remove('border-red-500', 'border-green-500');
        }
        
        return isValid;
    }
    
    // পাসওয়ার্ড শক্তি পরীক্ষক
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 'দুর্বল';
        let color = 'text-red-500';
        
        if (password.length >= 12) {
            strength = 'শক্তিশালী';
            color = 'text-green-500';
        } else if (password.length >= 8) {
            strength = 'মধ্যম';
            color = 'text-yellow-500';
        }
        
        passwordStrength.innerHTML = `<span class="${color}">${strength}</span>`;
        checkPasswordMatch();
        validateForm();
    });
    
    // পাসওয়ার্ড মিল পরীক্ষক
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (password && confirmPassword) {
            if (password === confirmPassword) {
                confirmPasswordInput.classList.remove('border-red-500');
                confirmPasswordInput.classList.add('border-green-500');
                passwordMatch.classList.remove('hidden');
                passwordMatch.classList.add('flex');
            } else {
                confirmPasswordInput.classList.remove('border-green-500');
                confirmPasswordInput.classList.add('border-red-500');
                passwordMatch.classList.remove('flex');
                passwordMatch.classList.add('hidden');
            }
        } else {
            confirmPasswordInput.classList.remove('border-red-500', 'border-green-500');
            passwordMatch.classList.remove('flex');
            passwordMatch.classList.add('hidden');
        }
    }
    
    // ফর্ম ভ্যালিডেশন
    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const mobile = mobileInput.value;
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        const isMobileValid = validateMobile();
        const isPasswordValid = password.length >= 8;
        const isPasswordMatch = password === confirmPassword;
        const isNameValid = name.length >= 3;
        
        const isValid = isNameValid && isMobileValid && isPasswordValid && isPasswordMatch;
        
        submitBtn.disabled = !isValid;
        
        return isValid;
    }
    
    // রিয়েল-টাইম ফর্ম ভ্যালিডেশন
    document.querySelectorAll('#adminForm input').forEach(input => {
        input.addEventListener('input', validateForm);
    });
    
    // ফর্ম জমা দেওয়া
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            showToast('দয়া করে সমস্ত প্রয়োজনীয় ক্ষেত্র সঠিকভাবে পূরণ করুন', 'error');
            return false;
        }
        
        // লোডিং অবস্থা দেখান
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> তৈরি করা হচ্ছে...';
    });
    
    // সাফল্য মোডাল ফাংশন
    window.showSuccessModal = function(message) {
        document.getElementById('successMessage').textContent = message;
        const modal = document.getElementById('successModal');
        const modalContent = document.getElementById('successModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    };
    
    window.closeSuccessModal = function() {
        const modal = document.getElementById('successModal');
        const modalContent = document.getElementById('successModalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            window.location.href = '{{ route('super_admin.users.admins.index') }}';
        }, 300);
    };
    
    // Escape কীতে মোডাল বন্ধ করুন
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSuccessModal();
        }
    });
    
    // সার্ভার থেকে সাফল্য বার্তা পরীক্ষা করুন (ফর্ম জমা দেওয়ার পর)
    @if(session('success'))
        showSuccessModal('{{ session('success') }}');
    @endif
    
    // ফর্ম ভ্যালিডেশন শুরু করুন
    validateForm();
});

</script>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        showSuccessModal('{{ session('success') }}');
    }, 500);
});
</script>
@endif
@endsection

@section('styles')
<style>
/* কাস্টম ফর্ম স্টাইলিং */
input:focus, select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* পাসওয়ার্ড শক্তি নির্দেশক */
#passwordStrength {
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* ট্রানজিশন ইফেক্ট */
#successModalContent {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ফর্ম ভ্যালিডেশন অবস্থা */
.border-green-500 {
    border-color: #10b981 !important;
}

.border-red-500 {
    border-color: #ef4444 !important;
}

/* বাটন হোভার ইফেক্ট */
button[type="submit"]:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
}

/* মোবাইল নম্বর ভ্যালিডেশন */
input:valid {
    border-color: #10b981;
}

/* লোডিং স্পিনার */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection