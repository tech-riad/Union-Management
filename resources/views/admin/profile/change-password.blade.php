@extends('layouts.admin')

@section('title', 'Change Password')

@section('content')
<div class="animate-fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Change Password</h1>
            <p class="text-gray-600">Update your account password</p>
        </div>
        <a href="{{ route('admin.profile.index') }}" 
           class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-xl font-medium shadow-soft transition duration-300 hover-lift flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Profile
        </a>
    </div>

    <!-- Change Password Form -->
    <div class="max-w-2xl mx-auto">
        <!-- Security Info -->
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 rounded-r-xl p-6 mb-6 shadow-soft">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center mr-4">
                    <i class="fas fa-shield-alt text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Password Security Guidelines</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-emerald-500 mr-2"></i>
                            Minimum 8 characters long
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-emerald-500 mr-2"></i>
                            Include uppercase and lowercase letters
                        </li>
                        <li class="fas fa-check-circle text-emerald-500 mr-2"></i>
                            Include at least one number
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-emerald-500 mr-2"></i>
                            Include at least one special character (!@#$%^&*)
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Password Form Card -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="border-b border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-800 flex items-center">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-400 flex items-center justify-center mr-3">
                        <i class="fas fa-key text-white"></i>
                    </div>
                    Update Password
                </h2>
            </div>
            
            <form method="POST" action="{{ route('admin.password.update') }}" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Current Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-300"
                                   placeholder="Enter your current password">
                            <button type="button" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    onclick="togglePasswordVisibility('current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="new_password" 
                                   name="new_password" 
                                   required
                                   class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-300"
                                   placeholder="Enter new password">
                            <button type="button" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    onclick="togglePasswordVisibility('new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Password Strength Meter -->
                        <div class="mt-3">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Password Strength</span>
                                <span id="passwordStrengthText">Very Weak</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div id="passwordStrengthBar" class="h-full bg-rose-500 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confirm New Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm New Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   required
                                   class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-300"
                                   placeholder="Confirm new password">
                            <button type="button" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    onclick="togglePasswordVisibility('new_password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('new_password_confirmation')
                            <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Password Match Indicator -->
                        <div class="mt-2">
                            <p id="passwordMatch" class="text-sm flex items-center hidden">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Passwords match</span>
                            </p>
                            <p id="passwordMismatch" class="text-sm flex items-center hidden">
                                <i class="fas fa-times-circle mr-2"></i>
                                <span>Passwords do not match</span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4">
                        <h4 class="font-medium text-gray-700 mb-3">Password Requirements</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div class="flex items-center">
                                <i id="reqLength" class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                                <span class="text-sm text-gray-600">At least 8 characters</span>
                            </div>
                            <div class="flex items-center">
                                <i id="reqUppercase" class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                                <span class="text-sm text-gray-600">One uppercase letter</span>
                            </div>
                            <div class="flex items-center">
                                <i id="reqLowercase" class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                                <span class="text-sm text-gray-600">One lowercase letter</span>
                            </div>
                            <div class="flex items-center">
                                <i id="reqNumber" class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                                <span class="text-sm text-gray-600">One number</span>
                            </div>
                            <div class="flex items-center">
                                <i id="reqSpecial" class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                                <span class="text-sm text-gray-600">One special character</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="submit" 
                                id="submitButton"
                                class="flex-1 bg-gradient-to-r from-primary-500 to-emerald-400 hover:from-primary-600 hover:to-emerald-500 text-white px-6 py-3 rounded-xl font-medium shadow-soft transition duration-300 hover-lift disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            <i class="fas fa-key mr-2"></i> Change Password
                        </button>
                        
                        <a href="{{ route('admin.profile.index') }}" 
                           class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-3 rounded-xl font-medium shadow-soft transition duration-300 hover-lift flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle password visibility
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password strength checker
const passwordInput = document.getElementById('new_password');
const confirmInput = document.getElementById('new_password_confirmation');
const strengthBar = document.getElementById('passwordStrengthBar');
const strengthText = document.getElementById('passwordStrengthText');
const submitButton = document.getElementById('submitButton');

// Requirement indicators
const reqLength = document.getElementById('reqLength');
const reqUppercase = document.getElementById('reqUppercase');
const reqLowercase = document.getElementById('reqLowercase');
const reqNumber = document.getElementById('reqNumber');
const reqSpecial = document.getElementById('reqSpecial');

// Match indicators
const passwordMatch = document.getElementById('passwordMatch');
const passwordMismatch = document.getElementById('passwordMismatch');

function checkPasswordStrength(password) {
    let strength = 0;
    let requirements = {
        length: false,
        uppercase: false,
        lowercase: false,
        number: false,
        special: false
    };
    
    // Length check
    if (password.length >= 8) {
        strength += 20;
        requirements.length = true;
        reqLength.className = 'fas fa-check-circle text-emerald-500 mr-2 text-xs';
    } else {
        reqLength.className = 'fas fa-times-circle text-rose-500 mr-2 text-xs';
    }
    
    // Uppercase check
    if (/[A-Z]/.test(password)) {
        strength += 20;
        requirements.uppercase = true;
        reqUppercase.className = 'fas fa-check-circle text-emerald-500 mr-2 text-xs';
    } else {
        reqUppercase.className = 'fas fa-times-circle text-rose-500 mr-2 text-xs';
    }
    
    // Lowercase check
    if (/[a-z]/.test(password)) {
        strength += 20;
        requirements.lowercase = true;
        reqLowercase.className = 'fas fa-check-circle text-emerald-500 mr-2 text-xs';
    } else {
        reqLowercase.className = 'fas fa-times-circle text-rose-500 mr-2 text-xs';
    }
    
    // Number check
    if (/[0-9]/.test(password)) {
        strength += 20;
        requirements.number = true;
        reqNumber.className = 'fas fa-check-circle text-emerald-500 mr-2 text-xs';
    } else {
        reqNumber.className = 'fas fa-times-circle text-rose-500 mr-2 text-xs';
    }
    
    // Special character check
    if (/[^A-Za-z0-9]/.test(password)) {
        strength += 20;
        requirements.special = true;
        reqSpecial.className = 'fas fa-check-circle text-emerald-500 mr-2 text-xs';
    } else {
        reqSpecial.className = 'fas fa-times-circle text-rose-500 mr-2 text-xs';
    }
    
    // Update progress bar and text
    strengthBar.style.width = strength + '%';
    
    // Update colors and text based on strength
    if (strength <= 40) {
        strengthBar.className = 'h-full bg-rose-500 rounded-full transition-all duration-300';
        strengthText.textContent = 'Very Weak';
        strengthText.className = 'text-rose-500';
    } else if (strength <= 60) {
        strengthBar.className = 'h-full bg-amber-500 rounded-full transition-all duration-300';
        strengthText.textContent = 'Weak';
        strengthText.className = 'text-amber-500';
    } else if (strength <= 80) {
        strengthBar.className = 'h-full bg-blue-500 rounded-full transition-all duration-300';
        strengthText.textContent = 'Good';
        strengthText.className = 'text-blue-500';
    } else {
        strengthBar.className = 'h-full bg-emerald-500 rounded-full transition-all duration-300';
        strengthText.textContent = 'Strong';
        strengthText.className = 'text-emerald-500';
    }
    
    return requirements;
}

function checkPasswordMatch() {
    const password = passwordInput.value;
    const confirm = confirmInput.value;
    
    if (password && confirm) {
        if (password === confirm) {
            passwordMatch.classList.remove('hidden');
            passwordMatch.classList.add('flex');
            passwordMismatch.classList.add('hidden');
            passwordMismatch.classList.remove('flex');
            passwordMatch.className = 'text-sm flex items-center text-emerald-600';
            return true;
        } else {
            passwordMismatch.classList.remove('hidden');
            passwordMismatch.classList.add('flex');
            passwordMatch.classList.add('hidden');
            passwordMatch.classList.remove('flex');
            passwordMismatch.className = 'text-sm flex items-center text-rose-600';
            return false;
        }
    }
    return false;
}

function updateSubmitButton() {
    const password = passwordInput.value;
    const currentPassword = document.getElementById('current_password').value;
    const requirements = checkPasswordStrength(password);
    const passwordMatches = checkPasswordMatch();
    
    const allRequirementsMet = Object.values(requirements).every(req => req === true);
    
    if (currentPassword && password && passwordMatches && allRequirementsMet) {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
}

// Event listeners
passwordInput.addEventListener('input', updateSubmitButton);
confirmInput.addEventListener('input', updateSubmitButton);
document.getElementById('current_password').addEventListener('input', updateSubmitButton);

// Initialize
updateSubmitButton();
</script>
@endpush
@endsection