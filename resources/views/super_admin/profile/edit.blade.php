@extends('layouts.super-admin')

@section('title', 'Edit Profile')

@section('content')
<div class="animate-fade-in">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-user-edit text-blue-600 mr-2"></i>
                    প্রোফাইল এডিট করুন
                </h2>
                <p class="text-gray-600 mt-2">আপনার ব্যক্তিগত তথ্য আপডেট করুন</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fas fa-user-edit mr-2"></i>
                        ব্যক্তিগত তথ্য
                    </h3>
                    <p class="text-blue-100 text-sm mt-1">আপনার তথ্য আপডেট করুন</p>
                </div>
                
                <!-- Form Content -->
                <form action="{{ route('super_admin.users.admins.update', auth()->user()) }}" method="POST" id="editProfileForm" enctype="multipart/form-data">
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

                        <!-- Profile Picture -->
                        <div class="space-y-4">
                            <h4 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                <i class="fas fa-image text-blue-500 mr-2"></i>
                                প্রোফাইল ছবি
                            </h4>
                            
                            <div class="flex flex-col items-center space-y-4">
                                <!-- Current Profile Picture -->
                                <div class="relative">
                                    @if(auth()->user()->profile_photo)
                                        <img id="profileImagePreview" 
                                             src="{{ asset('storage/' . auth()->user()->profile_photo) }}" 
                                             alt="Profile" 
                                             class="w-32 h-32 rounded-full border-4 border-blue-200 object-cover shadow-lg">
                                    @else
                                        <div id="profileImagePreview" class="w-32 h-32 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 border-4 border-blue-200 flex items-center justify-center shadow-lg">
                                            <i class="fas fa-user-shield text-white text-4xl"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Change Picture Button -->
                                    <label for="profile_photo" 
                                           class="absolute bottom-0 right-0 w-10 h-10 bg-gradient-to-r from-blue-600 to-blue-800 rounded-full flex items-center justify-center text-white cursor-pointer shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" 
                                           id="profile_photo" 
                                           name="profile_photo" 
                                           class="hidden" 
                                           accept="image/*"
                                           onchange="previewImage(event)">
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">ছবি আপলোড করতে ক্লিক করুন (JPG, PNG, Max 2MB)</p>
                                    <button type="button" 
                                            onclick="removeProfileImage()" 
                                            class="mt-2 text-sm text-red-600 hover:text-red-800 flex items-center justify-center space-x-1">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>ছবি মুছুন</span>
                                    </button>
                                    <input type="hidden" name="remove_profile_photo" id="removeProfilePhoto" value="0">
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="space-y-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                <i class="fas fa-user text-blue-500 mr-2"></i>
                                ব্যক্তিগত তথ্য
                            </h4>
                            
                            <!-- Name Field -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        পুরো নাম <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', auth()->user()->name) }}"
                                               class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                               placeholder="আপনার পুরো নাম লিখুন"
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
                                               value="{{ old('mobile', auth()->user()->mobile) }}"
                                               class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                               placeholder="01XXXXXXXXX"
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    ইমেইল ঠিকানা <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', auth()->user()->email) }}"
                                           class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                           placeholder="email@example.com"
                                           required>
                                </div>
                            </div>
                            
                            <!-- Designation Field -->
                            <div>
                                <label for="designation" class="block text-sm font-medium text-gray-700 mb-2">
                                    পদবী
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-briefcase text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           id="designation" 
                                           name="designation" 
                                           value="{{ old('designation', auth()->user()->designation) }}"
                                           class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                           placeholder="আপনার পদবী লিখুন">
                                </div>
                            </div>
                            
                            <!-- Bio/About Field -->
                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                                    সম্পর্কে / বায়ো
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none">
                                        <i class="fas fa-file-alt text-gray-400"></i>
                                    </div>
                                    <textarea id="bio" 
                                              name="bio" 
                                              rows="4"
                                              class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                              placeholder="আপনার সম্পর্কে কিছু লিখুন...">{{ old('bio', auth()->user()->bio) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="space-y-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                                ঠিকানা তথ্য
                            </h4>
                            
                            <!-- Address Field -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    ঠিকানা
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none">
                                        <i class="fas fa-home text-gray-400"></i>
                                    </div>
                                    <textarea id="address" 
                                              name="address" 
                                              rows="3"
                                              class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                              placeholder="আপনার সম্পূর্ণ ঠিকানা লিখুন...">{{ old('address', auth()->user()->address) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-shield-alt mr-1"></i>
                                আপনার সব তথ্য নিরাপদে সংরক্ষিত হবে
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('super_admin.users.admins.edit', auth()->user()) }}"
                                   class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200">
                                    <i class="fas fa-times mr-2"></i>
                                    বাতিল করুন
                                </a>
                                <button type="submit" 
                                        id="updateBtn"
                                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                                    <i class="fas fa-save mr-2"></i>
                                    আপডেট করুন
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Side Information -->
        <div class="lg:col-span-1">
            <!-- Profile Picture Preview -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-eye text-blue-500 mr-2"></i>
                    প্রিভিউ
                </h3>
                
                <div class="text-center">
                    <div class="w-32 h-32 rounded-full border-4 border-blue-200 mx-auto mb-4 overflow-hidden">
                        <img id="profilePreview" 
                             src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><rect width="128" height="128" fill="%233b82f6"/><text x="50%" y="50%" font-family="Arial" font-size="48" fill="white" text-anchor="middle" dy=".3em">SA</text></svg>' }}" 
                             alt="Profile Preview" 
                             class="w-full h-full object-cover">
                    </div>
                    <p class="text-sm text-gray-600">আপনার প্রোফাইল ছবির প্রিভিউ</p>
                </div>
            </div>
            
            <!-- Important Notes -->
            <div class="bg-gradient-to-r from-amber-50 to-amber-100 rounded-2xl p-6 border border-amber-200 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
                    গুরুত্বপূর্ণ তথ্য
                </h3>
                
                <div class="space-y-3">
                    <div class="flex items-start text-sm text-gray-700">
                        <i class="fas fa-info-circle text-amber-500 mt-0.5 mr-2"></i>
                        <span>ছবির সাইজ সর্বোচ্চ 2MB হওয়া উচিত</span>
                    </div>
                    <div class="flex items-start text-sm text-gray-700">
                        <i class="fas fa-info-circle text-amber-500 mt-0.5 mr-2"></i>
                        <span>সব বাধ্যতামূলক ফিল্ড (*) পূরণ করতে হবে</span>
                    </div>
                    <div class="flex items-start text-sm text-gray-700">
                        <i class="fas fa-info-circle text-amber-500 mt-0.5 mr-2"></i>
                        <span>ইমেইল একবার পরিবর্তন করলে ভেরিফিকেশন প্রয়োজন</span>
                    </div>
                </div>
            </div>
            
            <!-- Profile Completion -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                    প্রোফাইল কমপ্লিশন
                </h3>
                
                @php
                    $completion = 60;
                    if(auth()->user()->mobile) $completion += 10;
                    if(auth()->user()->designation) $completion += 10;
                    if(auth()->user()->address) $completion += 10;
                    if(auth()->user()->profile_photo) $completion += 10;
                @endphp
                
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-700 font-medium">{{ $completion }}% সম্পূর্ণ</span>
                        <span class="text-blue-600">{{ 100 - $completion }}% বাকি</span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-500" 
                             style="width: {{ $completion }}%"></div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex items-center text-sm {{ auth()->user()->mobile ? 'text-green-600' : 'text-gray-500' }}">
                        <i class="fas {{ auth()->user()->mobile ? 'fa-check-circle' : 'fa-circle' }} mr-2"></i>
                        <span>মোবাইল নম্বর</span>
                    </div>
                    <div class="flex items-center text-sm {{ auth()->user()->designation ? 'text-green-600' : 'text-gray-500' }}">
                        <i class="fas {{ auth()->user()->designation ? 'fa-check-circle' : 'fa-circle' }} mr-2"></i>
                        <span>পদবী</span>
                    </div>
                    <div class="flex items-center text-sm {{ auth()->user()->profile_photo ? 'text-green-600' : 'text-gray-500' }}">
                        <i class="fas {{ auth()->user()->profile_photo ? 'fa-check-circle' : 'fa-circle' }} mr-2"></i>
                        <span>প্রোফাইল ছবি</span>
                    </div>
                    <div class="flex items-center text-sm {{ auth()->user()->address ? 'text-green-600' : 'text-gray-500' }}">
                        <i class="fas {{ auth()->user()->address ? 'fa-check-circle' : 'fa-circle' }} mr-2"></i>
                        <span>ঠিকানা</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image Preview Function
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('profileImagePreview');
    const profilePreview = document.getElementById('profilePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Check if preview is an image element or a div
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Replace div with image
                const newImg = document.createElement('img');
                newImg.id = 'profileImagePreview';
                newImg.src = e.target.result;
                newImg.alt = 'Profile';
                newImg.className = 'w-32 h-32 rounded-full border-4 border-blue-200 object-cover shadow-lg';
                preview.parentNode.replaceChild(newImg, preview);
            }
            
            // Update profile preview
            profilePreview.src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
        
        // Reset remove photo flag
        document.getElementById('removeProfilePhoto').value = '0';
    }
}

// Remove Profile Image
function removeProfileImage() {
    const preview = document.getElementById('profileImagePreview');
    const profilePreview = document.getElementById('profilePreview');
    const fileInput = document.getElementById('profile_photo');
    const removeFlag = document.getElementById('removeProfilePhoto');
    
    // Reset file input
    if (fileInput) {
        fileInput.value = '';
    }
    
    // Set remove flag
    removeFlag.value = '1';
    
    // Show default profile image
    const defaultImage = document.createElement('div');
    defaultImage.id = 'profileImagePreview';
    defaultImage.className = 'w-32 h-32 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 border-4 border-blue-200 flex items-center justify-center shadow-lg';
    defaultImage.innerHTML = '<i class="fas fa-user-shield text-white text-4xl"></i>';
    
    if (preview) {
        preview.parentNode.replaceChild(defaultImage, preview);
    }
    
    // Update profile preview with default
    profilePreview.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><rect width="128" height="128" fill="%233b82f6"/><text x="50%" y="50%" font-family="Arial" font-size="48" fill="white" text-anchor="middle" dy=".3em">SA</text></svg>';
    
    // Show success message
    showToast('success', 'প্রোফাইল ছবি সরানো হয়েছে। সংরক্ষণ করুন।');
}

// Form Validation
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    const mobile = document.getElementById('mobile').value;
    const email = document.getElementById('email').value;
    
    // Mobile validation (Bangladeshi number)
    const mobileRegex = /^01[3-9]\d{8}$/;
    if (mobile && !mobileRegex.test(mobile)) {
        e.preventDefault();
        showToast('error', 'সঠিক মোবাইল নম্বর লিখুন (01XXXXXXXXX)');
        document.getElementById('mobile').focus();
        return;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        showToast('error', 'সঠিক ইমেইল ঠিকানা লিখুন');
        document.getElementById('email').focus();
        return;
    }
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
</script>
@endsection