@extends('layouts.admin')

@section('title', 'Edit Profile')

@section('content')
<div class="animate-fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Profile</h1>
            <p class="text-gray-600">Update your personal information</p>
        </div>
        <a href="{{ route('admin.profile.index') }}" 
           class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-xl font-medium shadow-soft transition duration-300 hover-lift flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Profile
        </a>
    </div>

    <!-- Edit Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Profile Image -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="border-b border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-emerald-400 flex items-center justify-center mr-3">
                            <i class="fas fa-camera text-white"></i>
                        </div>
                        Profile Photo
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="text-center">
                        <!-- Current Profile Image -->
                        <div class="relative mx-auto w-48 h-48 mb-4">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" 
                                     alt="Current Profile" 
                                     class="w-full h-full rounded-2xl object-cover border-4 border-gray-200 shadow-lg"
                                     id="profileImagePreview">
                            @else
                                <div class="w-full h-full rounded-2xl bg-gradient-to-br from-gray-200 to-gray-300 border-4 border-gray-200 flex items-center justify-center shadow-lg"
                                     id="profileImagePreview">
                                    <i class="fas fa-user text-gray-400 text-5xl"></i>
                                </div>
                            @endif
                            
                            <!-- Upload Overlay -->
                            <div class="absolute inset-0 bg-black/50 rounded-2xl opacity-0 hover:opacity-100 transition duration-300 flex items-center justify-center cursor-pointer"
                                 onclick="document.getElementById('profile_photo').click()">
                                <div class="text-center p-4">
                                    <i class="fas fa-camera text-white text-2xl mb-2"></i>
                                    <p class="text-white font-medium">Change Photo</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upload Controls -->
                        <div class="space-y-3">
                            <!-- File Input (Hidden) -->
                            <input type="file" 
                                   id="profile_photo" 
                                   name="profile_photo" 
                                   class="hidden"
                                   accept="image/*"
                                   onchange="previewImage(event)">
                            
                            <!-- Upload Button -->
                            <button type="button"
                                    onclick="document.getElementById('profile_photo').click()"
                                    class="w-full bg-gradient-to-r from-primary-500 to-emerald-400 hover:from-primary-600 hover:to-emerald-500 text-white px-4 py-3 rounded-xl font-medium shadow-soft transition duration-300 hover-lift">
                                <i class="fas fa-upload mr-2"></i> Upload New Photo
                            </button>
                            
                            <!-- Remove Button (if has image) -->
                            @if(auth()->user()->profile_photo)
                            <button type="button"
                                    onclick="removeProfileImage()"
                                    class="w-full bg-gradient-to-r from-rose-500 to-pink-400 hover:from-rose-600 hover:to-pink-500 text-white px-4 py-3 rounded-xl font-medium shadow-soft transition duration-300 hover-lift">
                                <i class="fas fa-trash mr-2"></i> Remove Photo
                            </button>
                            <input type="hidden" name="remove_image" id="removeImageInput" value="0">
                            @endif
                            
                            <!-- Image Requirements -->
                            <div class="text-xs text-gray-500 mt-4">
                                <p class="font-medium mb-1">Photo Requirements:</p>
                                <ul class="space-y-1">
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-emerald-500 mr-2 text-xs"></i>
                                        Max file size: 2MB
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-emerald-500 mr-2 text-xs"></i>
                                        Formats: JPG, PNG, GIF
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-emerald-500 mr-2 text-xs"></i>
                                        Square images work best
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="mt-6 bg-white rounded-2xl shadow-soft p-6">
                <div class="space-y-3">
                    <button type="submit" 
                            form="profileForm"
                            class="w-full bg-gradient-to-r from-primary-500 to-emerald-400 hover:from-primary-600 hover:to-emerald-500 text-white px-4 py-3 rounded-xl font-medium shadow-soft transition duration-300 hover-lift">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                    
                    <a href="{{ route('admin.profile.index') }}" 
                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-xl font-medium shadow-soft transition duration-300 hover-lift flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Profile Form -->
        <div class="lg:col-span-2">
            <form id="profileForm" 
                  method="POST" 
                  action="{{ route('admin.profile.update') }}" 
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Personal Information Card -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="border-b border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center mr-3">
                                <i class="fas fa-user-edit text-white"></i>
                            </div>
                            Personal Information
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-6">
                            <!-- Name & Email -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Full Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Name <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', auth()->user()->name) }}"
                                               required
                                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-300"
                                               placeholder="Enter your full name">
                                    </div>
                                    @error('name')
                                        <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', auth()->user()->email) }}"
                                               required
                                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-300"
                                               placeholder="Enter your email">
                                    </div>
                                    @error('email')
                                        <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Mobile & Designation -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Mobile Number -->
                                <div>
                                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mobile Number
                                    </label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                            <i class="fas fa-phone text-gray-400"></i>
                                        </div>
                                        <input type="text" 
                                               id="mobile" 
                                               name="mobile" 
                                               value="{{ old('mobile', auth()->user()->mobile) }}"
                                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-300"
                                               placeholder="+8801XXXXXXXXX">
                                    </div>
                                    @error('mobile')
                                        <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Designation -->
                                <div>
                                    <label for="designation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Designation
                                    </label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                            <i class="fas fa-briefcase text-gray-400"></i>
                                        </div>
                                        <input type="text" 
                                               id="designation" 
                                               name="designation" 
                                               value="{{ old('designation', auth()->user()->designation) }}"
                                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-300"
                                               placeholder="Your position/designation">
                                    </div>
                                    @error('designation')
                                        <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Address -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Address
                                </label>
                                <div class="relative">
                                    <div class="absolute left-3 top-3">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <textarea id="address" 
                                              name="address" 
                                              rows="3"
                                              class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-300 resize-none"
                                              placeholder="Enter your address">{{ old('address', auth()->user()->address) }}</textarea>
                                </div>
                                @error('address')
                                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Bio -->
                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                                    Bio / About
                                </label>
                                <div class="relative">
                                    <div class="absolute left-3 top-3">
                                        <i class="fas fa-file-alt text-gray-400"></i>
                                    </div>
                                    <textarea id="bio" 
                                              name="bio" 
                                              rows="4"
                                              class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-300 resize-none"
                                              placeholder="Tell us about yourself">{{ old('bio', auth()->user()->bio) }}</textarea>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Brief description about yourself (max 1000 characters)</p>
                                @error('bio')
                                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Read-Only Information -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="border-b border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-400 flex items-center justify-center mr-3">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            Account Information (Read Only)
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Role -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4">
                                <p class="text-sm text-gray-500 mb-1">Account Role</p>
                                <p class="font-medium text-gray-800">
                                    {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                                </p>
                            </div>
                            
                            <!-- User ID -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4">
                                <p class="text-sm text-gray-500 mb-1">User ID</p>
                                <p class="font-medium text-gray-800">#{{ auth()->user()->id }}</p>
                            </div>
                            
                            <!-- Created At -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4">
                                <p class="text-sm text-gray-500 mb-1">Member Since</p>
                                <p class="font-medium text-gray-800">
                                    {{ auth()->user()->created_at->format('d M, Y') }}
                                </p>
                            </div>
                            
                            <!-- Last Updated -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4">
                                <p class="text-sm text-gray-500 mb-1">Last Updated</p>
                                <p class="font-medium text-gray-800">
                                    {{ auth()->user()->updated_at->format('d M, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentProfileImage = "{{ auth()->user()->profile_photo }}";

// Image preview function
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        // Show preview
        updateImagePreview(e.target.result);
        
        // Upload image via AJAX
        uploadProfileImage(file);
    };
    reader.readAsDataURL(file);
}

// Update image preview
function updateImagePreview(imageSrc) {
    const previewContainer = document.querySelector('.relative.mx-auto.w-48.h-48');
    const existingImage = previewContainer.querySelector('img, div');
    
    if (existingImage) {
        existingImage.remove();
    }
    
    const img = document.createElement('img');
    img.id = 'profileImagePreview';
    img.src = imageSrc;
    img.className = 'w-full h-full rounded-2xl object-cover border-4 border-gray-200 shadow-lg';
    img.alt = 'Profile Preview';
    
    // Add upload overlay
    const overlay = document.createElement('div');
    overlay.className = 'absolute inset-0 bg-black/50 rounded-2xl opacity-0 hover:opacity-100 transition duration-300 flex items-center justify-center cursor-pointer';
    overlay.onclick = () => document.getElementById('profile_photo').click();
    overlay.innerHTML = `
        <div class="text-center p-4">
            <i class="fas fa-camera text-white text-2xl mb-2"></i>
            <p class="text-white font-medium">Change Photo</p>
        </div>
    `;
    
    previewContainer.appendChild(img);
    previewContainer.appendChild(overlay);
    
    // Show remove button
    showRemoveButton();
}

// Show remove button
function showRemoveButton() {
    let removeBtn = document.querySelector('[onclick="removeProfileImage()"]');
    if (!removeBtn) {
        const uploadBtn = document.querySelector('[onclick*="profile_photo"]');
        const newRemoveBtn = uploadBtn.cloneNode(true);
        newRemoveBtn.innerHTML = '<i class="fas fa-trash mr-2"></i> Remove Photo';
        newRemoveBtn.onclick = removeProfileImage;
        newRemoveBtn.className = 'w-full bg-gradient-to-r from-rose-500 to-pink-400 hover:from-rose-600 hover:to-pink-500 text-white px-4 py-3 rounded-xl font-medium shadow-soft transition duration-300 hover-lift';
        uploadBtn.parentNode.appendChild(newRemoveBtn);
    }
}

// Hide remove button
function hideRemoveButton() {
    const removeBtn = document.querySelector('[onclick="removeProfileImage()"]');
    if (removeBtn) {
        removeBtn.remove();
    }
}

// AJAX image upload
function uploadProfileImage(file) {
    const formData = new FormData();
    formData.append('profile_photo', file);
    formData.append('_token', '{{ csrf_token() }}');
    
    showLoading();
    
    fetch('{{ route("admin.profile.upload-image") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('success', data.message);
            currentProfileImage = data.image_url;
        } else {
            showToast('error', data.message);
            // Restore previous image
            restorePreviousImage();
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Upload failed. Please try again.');
        console.error('Error:', error);
        restorePreviousImage();
    });
}

// Remove profile image
function removeProfileImage() {
    if (!confirm('Are you sure you want to remove your profile photo?')) {
        return;
    }
    
    showLoading();
    
    fetch('{{ route("admin.profile.remove-image") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('success', data.message);
            currentProfileImage = null;
            showDefaultImage();
            hideRemoveButton();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Failed to remove image.');
        console.error('Error:', error);
    });
}

// Show default placeholder image
function showDefaultImage() {
    const previewContainer = document.querySelector('.relative.mx-auto.w-48.h-48');
    const existingImage = previewContainer.querySelector('img, div');
    
    if (existingImage) {
        existingImage.remove();
    }
    
    const div = document.createElement('div');
    div.id = 'profileImagePreview';
    div.className = 'w-full h-full rounded-2xl bg-gradient-to-br from-gray-200 to-gray-300 border-4 border-gray-200 flex items-center justify-center shadow-lg';
    div.innerHTML = '<i class="fas fa-user text-gray-400 text-5xl"></i>';
    
    // Add upload overlay
    const overlay = document.createElement('div');
    overlay.className = 'absolute inset-0 bg-black/50 rounded-2xl opacity-0 hover:opacity-100 transition duration-300 flex items-center justify-center cursor-pointer';
    overlay.onclick = () => document.getElementById('profile_photo').click();
    overlay.innerHTML = `
        <div class="text-center p-4">
            <i class="fas fa-camera text-white text-2xl mb-2"></i>
            <p class="text-white font-medium">Upload Photo</p>
        </div>
    `;
    
    previewContainer.appendChild(div);
    previewContainer.appendChild(overlay);
}

// Restore previous image
function restorePreviousImage() {
    if (currentProfileImage) {
        updateImagePreview(currentProfileImage);
    } else {
        showDefaultImage();
    }
}

// Loading indicator
function showLoading() {
    let loading = document.getElementById('loadingIndicator');
    if (!loading) {
        loading = document.createElement('div');
        loading.id = 'loadingIndicator';
        loading.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
        loading.innerHTML = `
            <div class="bg-white p-6 rounded-2xl shadow-hard">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 border-4 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-gray-800">Uploading image...</p>
                </div>
            </div>
        `;
        document.body.appendChild(loading);
    }
}

function hideLoading() {
    const loading = document.getElementById('loadingIndicator');
    if (loading) {
        loading.remove();
    }
}

// Toast notification
function showToast(type, message) {
    const toast = document.createElement('div');
    const colors = {
        success: 'from-emerald-500 to-green-500',
        error: 'from-rose-500 to-red-500',
        warning: 'from-amber-500 to-orange-500',
        info: 'from-blue-500 to-cyan-500'
    };
    
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-hard z-50 bg-gradient-to-r ${colors[type]} text-white transform translate-x-full opacity-0 transition-all duration-300`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);
    
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (toast.parentNode) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Character counter for bio
const bioTextarea = document.getElementById('bio');
if (bioTextarea) {
    const counter = document.createElement('div');
    counter.className = 'text-right text-xs text-gray-500 mt-1';
    counter.id = 'bioCounter';
    bioTextarea.parentNode.appendChild(counter);
    
    function updateCounter() {
        const length = bioTextarea.value.length;
        counter.textContent = `${length}/1000 characters`;
        
        if (length > 1000) {
            counter.classList.add('text-rose-500');
            counter.classList.remove('text-gray-500');
        } else {
            counter.classList.remove('text-rose-500');
            counter.classList.add('text-gray-500');
        }
    }
    
    bioTextarea.addEventListener('input', updateCounter);
    updateCounter();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Set initial image state
    if (!currentProfileImage || currentProfileImage === '') {
        showDefaultImage();
    }
    
    // Clear file input on page refresh
    document.getElementById('profile_photo').value = '';
});
</script>
@endpush
@endsection