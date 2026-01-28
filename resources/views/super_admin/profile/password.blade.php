@extends('layouts.super-admin')

@section('title', 'Change Password')

@section('content')
<div class="animate-fade-in">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-key text-blue-600 mr-2"></i>
                    পাসওয়ার্ড পরিবর্তন করুন
                </h2>
                <p class="text-gray-600 mt-2">আপনার অ্যাকাউন্টের পাসওয়ার্ড আপডেট করুন</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.users.admins.edit', auth()->user()) }}" 
                   class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-gray-600 to-gray-800 hover:from-gray-700 hover:to-gray-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    প্রোফাইলে ফিরে যান
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <h3 class="text-lg font-bold text-white">
                    <i class="fas fa-lock mr-2"></i>
                    নিরাপত্তা সেটিংস
                </h3>
                <p class="text-blue-100 text-sm mt-1">আপনার পাসওয়ার্ড সুরক্ষিতভাবে আপডেট করুন</p>
            </div>
            
            <!-- Form Content -->
            <form action="{{ route('super_admin.profile.update-password') }}" method="POST" id="changePasswordForm">
                @csrf
                
                <div class="p-6 space-y-6">
                    @if(session('success'))
                    <div class="rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-600 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">সফল!</h3>
                                <div class="mt-1 text-sm text-green-700">
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-600 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">নিম্নলিখিত ত্রুটি ঠিক করুন:</h3>
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

                    <!-- Security Information -->
                    <div class="rounded-xl bg-gradient-to-r from-blue-50 to-blue-100 p-4 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-blue-500 mt-0.5 mr-3"></i>
                            <div class="text-sm text-gray-700">
                                <p>একটি শক্তিশালী পাসওয়ার্ড ব্যবহার করুন যা অন্তত 8 অক্ষরের এবং সংখ্যা, বড় হাতের ও ছোট হাতের অক্ষর এবং বিশেষ চিহ্নের সমন্বয়ে গঠিত।</p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            বর্তমান পাসওয়ার্ড <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="আপনার বর্তমান পাসওয়ার্ড লিখুন"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" onclick="togglePassword('current_password')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            নতুন পাসওয়ার্ড <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="নতুন পাসওয়ার্ড লিখুন"
                                   required
                                   minlength="8"
                                   oninput="checkPasswordStrength()">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" onclick="togglePassword('password')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div id="passwordStrength" class="h-2 bg-gray-200 rounded-full overflow-hidden hidden">
                                <div id="passwordStrengthBar" class="h-full transition-all duration-300"></div>
                            </div>
                            <div id="passwordStrengthText" class="text-xs mt-1 hidden"></div>
                        </div>
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            নতুন পাসওয়ার্ড নিশ্চিত করুন <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="নতুন পাসওয়ার্ড আবার লিখুন"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" onclick="togglePassword('password_confirmation')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4">
                        <h4 class="text-sm font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-list-check text-blue-500 mr-2"></i>
                            পাসওয়ার্ডের প্রয়োজনীয়তা
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div class="flex items-center">
                                <i id="lengthCheck" class="fas fa-times text-red-500 mr-2 text-xs"></i>
                                <span class="text-sm text-gray-600">কমপক্ষে ৮ অক্ষর</span>
                            </div>
                            <div class="flex items-center">
                                <i id="uppercaseCheck" class="fas fa-times text-red-500 mr-2 text-xs"></i>
                                <span class="text-sm text-gray-600">একটি বড় হাতের অক্ষর</span>
                            </div>
                            <div class="flex items-center">
                                <i id="lowercaseCheck" class="fas fa-times text-red-500 mr-2 text-xs"></i>
                                <span class="text-sm text-gray-600">একটি ছোট হাতের অক্ষর</span>
                            </div>
                            <div class="flex items-center">
                                <i id="numberCheck" class="fas fa-times text-red-500 mr-2 text-xs"></i>
                                <span class="text-sm text-gray-600">একটি সংখ্যা</span>
                            </div>
                            <div class="md:col-span-2 flex items-center">
                                <i id="specialCheck" class="fas fa-times text-red-500 mr-2 text-xs"></i>
                                <span class="text-sm text-gray-600">একটি বিশেষ চিহ্ন (!@#$%^&*)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>
                            আপনার পাসওয়ার্ড এনক্রিপ্ট করা হয়
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('super_admin.users.admins.edit', auth()->user()) }}"
                               class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>
                                বাতিল করুন
                            </a>
                            <button type="submit" 
                                    id="submitBtn"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i>
                                পাসওয়ার্ড আপডেট করুন
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle Password Visibility
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

// Check Password Strength
function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('passwordStrengthText');
    const bar = document.getElementById('passwordStrengthBar');
    
    if (!strengthBar || !strengthText || !bar) return;
    
    strengthBar.classList.remove('hidden');
    strengthText.classList.remove('hidden');
    
    // Check requirements
    const hasLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasSpecial = /[!@#$%^&*]/.test(password);
    
    // Update check icons
    document.getElementById('lengthCheck').className = hasLength ? 'fas fa-check text-green-500 mr-2 text-xs' : 'fas fa-times text-red-500 mr-2 text-xs';
    document.getElementById('uppercaseCheck').className = hasUppercase ? 'fas fa-check text-green-500 mr-2 text-xs' : 'fas fa-times text-red-500 mr-2 text-xs';
    document.getElementById('lowercaseCheck').className = hasLowercase ? 'fas fa-check text-green-500 mr-2 text-xs' : 'fas fa-times text-red-500 mr-2 text-xs';
    document.getElementById('numberCheck').className = hasNumber ? 'fas fa-check text-green-500 mr-2 text-xs' : 'fas fa-times text-red-500 mr-2 text-xs';
    document.getElementById('specialCheck').className = hasSpecial ? 'fas fa-check text-green-500 mr-2 text-xs' : 'fas fa-times text-red-500 mr-2 text-xs';
    
    // Calculate strength
    let strength = 0;
    let message = '';
    let color = '';
    let width = '0%';
    
    if (hasLength) strength += 20;
    if (hasUppercase) strength += 20;
    if (hasLowercase) strength += 20;
    if (hasNumber) strength += 20;
    if (hasSpecial) strength += 20;
    
    if (strength < 40) {
        message = 'দুর্বল';
        color = 'bg-red-500';
        width = '20%';
    } else if (strength < 60) {
        message = 'মাঝারি';
        color = 'bg-orange-500';
        width = '40%';
    } else if (strength < 80) {
        message = 'ভাল';
        color = 'bg-yellow-500';
        width = '60%';
    } else if (strength < 100) {
        message = 'শক্তিশালী';
        color = 'bg-green-500';
        width = '80%';
    } else {
        message = 'খুবই শক্তিশালী';
        color = 'bg-emerald-600';
        width = '100%';
    }
    
    // Update UI
    bar.className = `h-full transition-all duration-300 ${color}`;
    bar.style.width = width;
    strengthText.textContent = `পাসওয়ার্ড শক্তি: ${message}`;
    strengthText.className = `text-xs mt-1 ${color.replace('bg-', 'text-')}`;
}

// Form Validation
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    const currentPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submitBtn');
    
    // Check if new password and confirmation match
    if (newPassword.value !== confirmPassword.value) {
        e.preventDefault();
        showToast('error', 'নতুন পাসওয়ার্ড এবং নিশ্চিতকরণ পাসওয়ার্ড একই নয়');
        confirmPassword.focus();
        return;
    }
    
    // Check if new password is different from current
    if (currentPassword.value === newPassword.value) {
        e.preventDefault();
        showToast('error', 'নতুন পাসওয়ার্ড বর্তমান পাসওয়ার্ড থেকে আলাদা হতে হবে');
        newPassword.focus();
        return;
    }
    
    // Check password strength
    const hasLength = newPassword.value.length >= 8;
    const hasUppercase = /[A-Z]/.test(newPassword.value);
    const hasLowercase = /[a-z]/.test(newPassword.value);
    const hasNumber = /\d/.test(newPassword.value);
    const hasSpecial = /[!@#$%^&*]/.test(newPassword.value);
    
    if (!(hasLength && hasUppercase && hasLowercase && hasNumber)) {
        e.preventDefault();
        showToast('error', 'পাসওয়ার্ড নিয়ম মেনে তৈরি করুন');
        return;
    }
    
    // Disable submit button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> আপডেট হচ্ছে...';
});

// Toast function
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-lg z-50 transition-all duration-300 transform translate-x-full`;
    
    const typeClasses = {
        success: 'bg-gradient-to-r from-green-500 to-emerald-600 text-white',
        error: 'bg-gradient-to-r from-red-500 to-rose-600 text-white',
        warning: 'bg-gradient-to-r from-yellow-500 to-orange-600 text-white'
    };
    
    toast.classList.add(typeClasses[type]);
    
    const icon = type === 'success' ? 'check-circle' :
                type === 'error' ? 'exclamation-circle' : 'exclamation-triangle';
    
    toast.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas fa-${icon} text-xl"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

// Initialize password strength check on page load
document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('password');
    if (passwordField) {
        passwordField.addEventListener('input', checkPasswordStrength);
        checkPasswordStrength(); // Initial check
    }
});
</script>
@endsection