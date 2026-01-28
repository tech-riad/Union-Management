@extends('layouts.super-admin')

@section('title', 'নাগরিক সম্পাদনা করুন')

@section('content')
<div class="animate-fade-in">
    <!-- শিরোনাম অংশ -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-user-edit text-green-600 mr-2"></i>
                    নাগরিক সম্পাদনা করুন
                </h2>
                <p class="text-gray-600 mt-2">নাগরিক তথ্য আপডেট করুন</p>
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
                        <i class="fas fa-user-edit mr-2"></i>
                        নাগরিক তথ্য সম্পাদনা করুন
                    </h3>
                    <p class="text-green-100 text-sm mt-1">নিচের বিবরণগুলি আপডেট করুন</p>
                </div>
                
                <!-- ফর্ম কন্টেন্ট -->
                <form action="{{ route('super_admin.users.citizens.update', $user) }}" method="POST" id="editCitizenForm">
                    @csrf
                    @method('PUT')
                    
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
                                               value="{{ old('name', $user->name) }}"
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
                                               value="{{ old('mobile', $user->mobile) }}"
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
                                           value="{{ old('email', $user->email) }}"
                                           class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300"
                                           placeholder="citizen@example.com">
                                </div>
                            </div>
                        </div>

                        <!-- নিরাপত্তা সেটিংস -->
                        <div class="space-y-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                <i class="fas fa-lock text-green-500 mr-2"></i>
                                পাসওয়ার্ড সেটিংস
                            </h4>
                            
                            <div class="rounded-xl bg-gradient-to-r from-blue-50 to-blue-100 p-4 mb-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-gray-700">
                                        <p>পাসওয়ার্ড পরিবর্তন করতে না চাইলে পাসওয়ার্ড ফিল্ডগুলি খালি রাখুন।</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        নতুন পাসওয়ার্ড
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-key text-gray-400"></i>
                                        </div>
                                        <input type="password" 
                                               id="password" 
                                               name="password" 
                                               class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300"
                                               placeholder="নতুন পাসওয়ার্ড লিখুন"
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
                                        নতুন পাসওয়ার্ড নিশ্চিত করুন
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-key text-gray-400"></i>
                                        </div>
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300"
                                               placeholder="নতুন পাসওয়ার্ড আবার লিখুন">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <button type="button" onclick="togglePassword('password_confirmation')" class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- অবস্থা সেটিংস -->
                        <div class="space-y-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                <i class="fas fa-toggle-on text-green-500 mr-2"></i>
                                অ্যাকাউন্ট অবস্থা
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        অ্যাকাউন্ট অবস্থা <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user-check text-gray-400"></i>
                                        </div>
                                        <select id="status" 
                                                name="status" 
                                                class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 appearance-none">
                                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
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
                                <a href="{{ route('super_admin.users.citizens.index') }}"
                                   class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200">
                                    <i class="fas fa-times mr-2"></i>
                                    বাতিল করুন
                                </a>
                                <button type="submit" 
                                        id="updateBtn"
                                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-800 hover:from-green-700 hover:to-green-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                                    <i class="fas fa-save mr-2"></i>
                                    নাগরিক আপডেট করুন
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- সাইড তথ্য -->
        <div class="lg:col-span-1">
            <!-- নাগরিক তথ্য কার্ড -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-user-circle text-blue-500 mr-2"></i>
                    নাগরিক বিবরণ
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-600">ব্যবহারকারী আইডি</h4>
                        <p class="text-sm text-gray-800 font-mono bg-white px-3 py-1 rounded-lg mt-1">{{ $user->id }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-600">বর্তমান অবস্থা</h4>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1 
                            {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-circle text-xs mr-1"></i>
                            {{ $user->status == 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                        </span>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-600">অ্যাকাউন্ট তৈরি হয়েছে</h4>
                        <p class="text-sm text-gray-800 mt-1">
                            {{ $user->created_at->format('d M Y, h:i A') }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-600">সর্বশেষ আপডেট</h4>
                        <p class="text-sm text-gray-800 mt-1">
                            {{ $user->updated_at->format('d M Y, h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- গুরুত্বপূর্ণ নোট -->
            <div class="bg-gradient-to-r from-amber-50 to-amber-100 rounded-2xl p-6 border border-amber-200 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
                    গুরুত্বপূর্ণ নোট
                </h3>
                
                <div class="space-y-3">
                    <div class="flex items-start text-sm text-gray-700">
                        <i class="fas fa-info-circle text-amber-500 mt-0.5 mr-2"></i>
                        <span>পাসওয়ার্ড পরিবর্তন ঐচ্ছিক। বর্তমান পাসওয়ার্ড রাখতে খালি রাখুন।</span>
                    </div>
                    <div class="flex items-start text-sm text-gray-700">
                        <i class="fas fa-info-circle text-amber-500 mt-0.5 mr-2"></i>
                        <span>অবস্থা "নিষ্ক্রিয়" পরিবর্তন করলে নাগরিক লগইন করতে পারবে না।</span>
                    </div>
                    <div class="flex items-start text-sm text-gray-700">
                        <i class="fas fa-info-circle text-amber-500 mt-0.5 mr-2"></i>
                        <span>সমস্ত পরিবর্তন নিরাপত্তার জন্য লগ করা হয়।</span>
                    </div>
                </div>
            </div>
            
            <!-- দ্রুত কাজ -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-bolt text-purple-500 mr-2"></i>
                    দ্রুত কাজ
                </h3>
                
                <div class="space-y-3">
                    <a href="{{ route('super_admin.users.show', $user) }}"
                       class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-eye text-blue-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-800">সম্পূর্ণ প্রোফাইল দেখুন</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                    </a>
                    
                    <button type="button"
                            onclick="document.getElementById('editCitizenForm').reset()"
                            class="w-full flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-redo text-amber-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-800">ফর্ম রিসেট করুন</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                    </button>
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

// পাসওয়ার্ড নিশ্চিতকরণ ভ্যালিডেশন
document.getElementById('password').addEventListener('input', validatePassword);
document.getElementById('password_confirmation').addEventListener('input', validatePassword);

function validatePassword() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const updateBtn = document.getElementById('updateBtn');
    
    if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity('পাসওয়ার্ড মিলছে না');
        updateBtn.disabled = true;
        updateBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        confirmPassword.setCustomValidity('');
        updateBtn.disabled = false;
        updateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}
</script>
@endsection