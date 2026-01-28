@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="animate-fade-in">
    <!-- Profile Header Card -->
    <div class="bg-gradient-to-r from-primary-600 to-emerald-500 rounded-2xl p-6 text-white shadow-hard mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <!-- Profile Info -->
            <div class="flex items-center space-x-4 mb-4 md:mb-0">
                <div class="relative profile-img-container">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" 
                             alt="Profile" 
                             class="w-24 h-24 rounded-2xl border-4 border-white/30 object-cover shadow-lg">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-white/20 to-transparent border-4 border-white/30 flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-shield text-3xl text-white/80"></i>
                        </div>
                    @endif
                    
                    <!-- Status Indicator -->
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-emerald-400 border-4 border-white flex items-center justify-center">
                        <i class="fas fa-check text-xs text-white"></i>
                    </div>
                </div>
                
                <div>
                    <h1 class="text-2xl font-bold">{{ auth()->user()->name }}</h1>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-user-tag mr-1"></i>
                            {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                        </span>
                        @if(auth()->user()->designation)
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-briefcase mr-1"></i>
                            {{ auth()->user()->designation }}
                        </span>
                        @endif
                    </div>
                    <p class="text-white/80 text-sm mt-2">
                        <i class="fas fa-envelope mr-2"></i>{{ auth()->user()->email }}
                    </p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('admin.profile.edit') }}" 
                   class="bg-white text-primary-700 hover:bg-gray-100 px-4 py-3 rounded-xl font-medium shadow-soft transition duration-300 hover-lift flex items-center justify-center">
                    <i class="fas fa-edit mr-2"></i> Edit Profile
                </a>
                <a href="{{ route('admin.password.change') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-3 rounded-xl font-medium shadow-soft transition duration-300 hover-lift flex items-center justify-center">
                    <i class="fas fa-key mr-2"></i> Change Password
                </a>
            </div>
        </div>
    </div>
    
    <!-- Profile Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Personal Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information Card -->
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="border-b border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-emerald-400 flex items-center justify-center mr-3">
                            <i class="fas fa-user-circle text-white"></i>
                        </div>
                        Personal Information
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 hover-lift transition duration-300">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center flex-shrink-0 mr-4">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Full Name</p>
                                    <p class="font-medium text-gray-800 text-lg">{{ auth()->user()->name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 hover-lift transition duration-300">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-400 flex items-center justify-center flex-shrink-0 mr-4">
                                    <i class="fas fa-envelope text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Email Address</p>
                                    <p class="font-medium text-gray-800 text-lg">{{ auth()->user()->email }}</p>
                                    @if(auth()->user()->email_verified_at)
                                    <span class="text-xs text-emerald-600 mt-1 flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i> Verified
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mobile -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 hover-lift transition duration-300">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-400 flex items-center justify-center flex-shrink-0 mr-4">
                                    <i class="fas fa-phone text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Mobile Number</p>
                                    <p class="font-medium text-gray-800 text-lg">
                                        {{ auth()->user()->mobile ? auth()->user()->mobile : 'Not provided' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Designation -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 hover-lift transition duration-300">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-400 flex items-center justify-center flex-shrink-0 mr-4">
                                    <i class="fas fa-briefcase text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Designation</p>
                                    <p class="font-medium text-gray-800 text-lg">
                                        {{ auth()->user()->designation ? auth()->user()->designation : 'Administrator' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address (if exists) -->
                    @if(auth()->user()->address)
                    <div class="mt-6">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 hover-lift transition duration-300">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-rose-500 to-pink-400 flex items-center justify-center flex-shrink-0 mr-4">
                                    <i class="fas fa-map-marker-alt text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 mb-1">Address</p>
                                    <p class="font-medium text-gray-800">{{ auth()->user()->address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Bio (if exists) -->
                    @if(auth()->user()->bio)
                    <div class="mt-6">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 hover-lift transition duration-300">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-400 flex items-center justify-center flex-shrink-0 mr-4">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 mb-1">Bio / About</p>
                                    <p class="text-gray-800 leading-relaxed">{{ auth()->user()->bio }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Account Activity Timeline -->
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="border-b border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-400 flex items-center justify-center mr-3">
                            <i class="fas fa-history text-white"></i>
                        </div>
                        Account Activity
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Account Created -->
                        <div class="flex items-start border-l-4 border-primary-500 pl-4 py-2">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mr-4">
                                <i class="fas fa-user-plus text-emerald-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800">Account Created</h4>
                                <p class="text-sm text-gray-600">
                                    {{ auth()->user()->created_at->format('F d, Y \a\t h:i A') }}
                                </p>
                            </div>
                            <span class="text-sm text-gray-500 whitespace-nowrap">
                                {{ auth()->user()->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <!-- Last Update -->
                        <div class="flex items-start border-l-4 border-blue-500 pl-4 py-2">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mr-4">
                                <i class="fas fa-sync-alt text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800">Last Profile Update</h4>
                                <p class="text-sm text-gray-600">
                                    {{ auth()->user()->updated_at->format('F d, Y \a\t h:i A') }}
                                </p>
                            </div>
                            <span class="text-sm text-gray-500 whitespace-nowrap">
                                {{ auth()->user()->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Stats & Actions -->
        <div class="space-y-6">
            <!-- Account Status Card -->
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="border-b border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-400 flex items-center justify-center mr-3">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        Account Status
                    </h2>
                </div>
                
                <div class="p-6">
                    <!-- Status Items -->
                    <div class="space-y-4">
                        <!-- Account Status -->
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-emerald-600"></i>
                                </div>
                                <span class="text-gray-700 font-medium">Account Status</span>
                            </div>
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
                                Active
                            </span>
                        </div>
                        
                        <!-- Role -->
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-user-shield text-blue-600"></i>
                                </div>
                                <span class="text-gray-700 font-medium">Role</span>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                            </span>
                        </div>
                        
                        <!-- User ID -->
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-id-card text-purple-600"></i>
                                </div>
                                <span class="text-gray-700 font-medium">User ID</span>
                            </div>
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">
                                #{{ auth()->user()->id }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Member Since -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-2">Member Since</div>
                            <div class="text-2xl font-bold text-primary-600">
                                {{ auth()->user()->created_at->format('d') }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ auth()->user()->created_at->format('F Y') }}
                            </div>
                            <div class="text-xs text-gray-500 mt-2">
                                {{ auth()->user()->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions Card -->
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="border-b border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-400 flex items-center justify-center mr-3">
                            <i class="fas fa-bolt text-white"></i>
                        </div>
                        Quick Actions
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-3">
                        <!-- Edit Profile -->
                        <a href="{{ route('admin.profile.edit') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 rounded-xl transition duration-300 group hover-lift">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-emerald-400 flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">Edit Profile</p>
                                <p class="text-xs text-gray-500">Update personal information</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-primary-600 transition duration-300"></i>
                        </a>
                        
                        <!-- Change Password -->
                        <a href="{{ route('admin.password.change') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 rounded-xl transition duration-300 group hover-lift">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-pink-400 flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-key text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">Change Password</p>
                                <p class="text-xs text-gray-500">Update your password</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-rose-600 transition duration-300"></i>
                        </a>
                        
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 rounded-xl transition duration-300 group hover-lift">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-tachometer-alt text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">Dashboard</p>
                                <p class="text-xs text-gray-500">Back to main dashboard</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600 transition duration-300"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Profile Completion -->
            <div class="bg-gradient-to-r from-primary-500 to-emerald-400 rounded-2xl p-6 text-white shadow-hard">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Profile Completion</h3>
                </div>
                
                @php
                    $completion = 60; // Basic info (name, email, role)
                    if(auth()->user()->mobile) $completion += 10;
                    if(auth()->user()->designation) $completion += 10;
                    if(auth()->user()->address) $completion += 10;
                    if(auth()->user()->bio) $completion += 10;
                    if(auth()->user()->profile_photo) $completion += 10;
                @endphp
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span>{{ $completion }}% Complete</span>
                        @if($completion < 100)
                        <span>{{ 100 - $completion }}% to go</span>
                        @else
                        <span>Complete!</span>
                        @endif
                    </div>
                    <div class="h-2 bg-white/30 rounded-full overflow-hidden">
                        <div class="h-full bg-white rounded-full transition-all duration-500" style="width: {{ $completion }}%"></div>
                    </div>
                </div>
                
                <!-- Completion Checklist -->
                <div class="space-y-2 text-sm">
                    <div class="flex items-center">
                        @if(auth()->user()->mobile)
                        <i class="fas fa-check-circle text-emerald-300 mr-2"></i>
                        @else
                        <i class="far fa-circle text-white/50 mr-2"></i>
                        @endif
                        <span class="{{ !auth()->user()->mobile ? 'text-white/70' : '' }}">Add mobile number</span>
                    </div>
                    
                    <div class="flex items-center">
                        @if(auth()->user()->designation)
                        <i class="fas fa-check-circle text-emerald-300 mr-2"></i>
                        @else
                        <i class="far fa-circle text-white/50 mr-2"></i>
                        @endif
                        <span class="{{ !auth()->user()->designation ? 'text-white/70' : '' }}">Update designation</span>
                    </div>
                    
                    <div class="flex items-center">
                        @if(auth()->user()->profile_photo)
                        <i class="fas fa-check-circle text-emerald-300 mr-2"></i>
                        @else
                        <i class="far fa-circle text-white/50 mr-2"></i>
                        @endif
                        <span class="{{ !auth()->user()->profile_photo ? 'text-white/70' : '' }}">Add profile photo</span>
                    </div>
                </div>
                
                @if($completion < 100)
                <a href="{{ route('admin.profile.edit') }}" 
                   class="mt-4 block text-center bg-white text-primary-600 hover:bg-gray-100 font-medium py-3 rounded-xl transition duration-300 hover-lift shadow-soft">
                    Complete Your Profile
                </a>
                @else
                <div class="mt-4 text-center">
                    <div class="w-10 h-10 rounded-full bg-emerald-300/20 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-trophy text-emerald-300"></i>
                    </div>
                    <p class="text-sm">Profile is 100% complete!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-refresh profile image when changed
let lastProfileImage = "{{ auth()->user()->profile_photo }}";

function checkProfileImageUpdate() {
    fetch('{{ route("admin.profile.index") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parse the HTML to get updated profile image
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const profileImageElement = doc.querySelector('.profile-img-container img');
        const currentImage = profileImageElement ? profileImageElement.src : null;
        
        // Check if image has changed
        if (currentImage && currentImage !== lastProfileImage) {
            lastProfileImage = currentImage;
            
            // Update the image on the page
            const profileImg = document.querySelector('.profile-img-container img');
            if (profileImg) {
                profileImg.src = currentImage;
                
                // Show success message
                showToast('success', 'Profile image updated successfully!');
            }
        }
    })
    .catch(error => console.error('Error checking image update:', error));
}

// Check for updates every 5 seconds when on profile page
if (window.location.pathname.includes('/admin/profile')) {
    setInterval(checkProfileImageUpdate, 5000);
}

// Toast notification function
function showToast(type, message) {
    // Reuse existing toast system if available
    if (typeof window.showToast === 'function') {
        window.showToast(message, type);
        return;
    }
    
    // Fallback toast
    const toast = document.createElement('div');
    const colors = {
        success: 'from-emerald-500 to-green-500',
        error: 'from-rose-500 to-red-500',
        warning: 'from-amber-500 to-orange-500'
    };
    
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-hard z-50 bg-gradient-to-r ${colors[type]} text-white transform translate-x-full opacity-0 transition-all duration-300`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
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
</script>
@endpush
@endsection