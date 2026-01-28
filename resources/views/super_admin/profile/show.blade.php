@extends('layouts.super-admin')

@section('title', 'My Profile')

@section('content')
<div class="animate-fade-in">
    <!-- Profile Header Card -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-6 text-white shadow-hard mb-6">
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
                            সুপার অ্যাডমিন
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
                <!-- পরিবর্তন ১: এই লাইনটি সংশোধন করুন -->
                <a href="{{ route('super_admin.users.profile.edit') }}" 
                   class="bg-white text-blue-700 hover:bg-gray-100 px-4 py-3 rounded-xl font-medium shadow-soft transition duration-300 hover-lift flex items-center justify-center">
                    <i class="fas fa-edit mr-2"></i> প্রোফাইল এডিট করুন
                </a>
                <a href="{{ route('super_admin.profile.password') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-3 rounded-xl font-medium shadow-soft transition duration-300 hover-lift flex items-center justify-center">
                    <i class="fas fa-key mr-2"></i> পাসওয়ার্ড পরিবর্তন করুন
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
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mr-3">
                            <i class="fas fa-user-circle text-white"></i>
                        </div>
                        ব্যক্তিগত তথ্য
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
                                    <p class="text-sm text-gray-500 mb-1">পুরো নাম</p>
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
                                    <p class="text-sm text-gray-500 mb-1">ইমেইল ঠিকানা</p>
                                    <p class="font-medium text-gray-800 text-lg">{{ auth()->user()->email }}</p>
                                    @if(auth()->user()->email_verified_at)
                                    <span class="text-xs text-emerald-600 mt-1 flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i> ভেরিফাইড
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
                                    <p class="text-sm text-gray-500 mb-1">মোবাইল নম্বর</p>
                                    <p class="font-medium text-gray-800 text-lg">
                                        {{ auth()->user()->mobile ? auth()->user()->mobile : 'প্রদান করা হয়নি' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User ID -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 hover-lift transition duration-300">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-400 flex items-center justify-center flex-shrink-0 mr-4">
                                    <i class="fas fa-id-card text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">ইউজার আইডি</p>
                                    <p class="font-medium text-gray-800 text-lg">
                                        #{{ auth()->user()->id }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(auth()->user()->address)
                    <div class="mt-6">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 hover-lift transition duration-300">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-rose-500 to-pink-400 flex items-center justify-center flex-shrink-0 mr-4">
                                    <i class="fas fa-map-marker-alt text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 mb-1">ঠিকানা</p>
                                    <p class="font-medium text-gray-800">{{ auth()->user()->address }}</p>
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
                        অ্যাকাউন্ট এক্টিভিটি
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Account Created -->
                        <div class="flex items-start border-l-4 border-blue-500 pl-4 py-2">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mr-4">
                                <i class="fas fa-user-plus text-emerald-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800">অ্যাকাউন্ট তৈরি করা হয়েছে</h4>
                                <p class="text-sm text-gray-600">
                                    {{ auth()->user()->created_at->format('d F, Y \a\t h:i A') }}
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
                                <h4 class="font-medium text-gray-800">সর্বশেষ আপডেট</h4>
                                <p class="text-sm text-gray-600">
                                    {{ auth()->user()->updated_at->format('d F, Y \a\t h:i A') }}
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
                        অ্যাকাউন্ট স্ট্যাটাস
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
                                <span class="text-gray-700 font-medium">অ্যাকাউন্ট স্ট্যাটাস</span>
                            </div>
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
                                সক্রিয়
                            </span>
                        </div>
                        
                        <!-- Role -->
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-user-shield text-blue-600"></i>
                                </div>
                                <span class="text-gray-700 font-medium">ভূমিকা</span>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                সুপার অ্যাডমিন
                            </span>
                        </div>
                    </div>
                    
                    <!-- Member Since -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-2">সদস্য হওয়ার তারিখ</div>
                            <div class="text-2xl font-bold text-blue-600">
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
                        দ্রুত অপশন
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-3">
                        <!-- Edit Profile -->
                        <!-- পরিবর্তন ২: এই লাইনটি সংশোধন করুন -->
                        <a href="{{ route('super_admin.users.profile.edit') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 rounded-xl transition duration-300 group hover-lift">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">প্রোফাইল এডিট করুন</p>
                                <p class="text-xs text-gray-500">ব্যক্তিগত তথ্য আপডেট করুন</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600 transition duration-300"></i>
                        </a>
                        
                        <!-- Change Password -->
                        <a href="{{ route('super_admin.profile.password') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 rounded-xl transition duration-300 group hover-lift">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-pink-400 flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-key text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">পাসওয়ার্ড পরিবর্তন করুন</p>
                                <p class="text-xs text-gray-500">আপনার পাসওয়ার্ড আপডেট করুন</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-rose-600 transition duration-300"></i>
                        </a>
                        
                        <!-- Dashboard -->
                        <a href="{{ route('super_admin.dashboard') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 rounded-xl transition duration-300 group hover-lift">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-green-400 flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-tachometer-alt text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">ড্যাশবোর্ডে ফিরে যান</p>
                                <p class="text-xs text-gray-500">মূল ড্যাশবোর্ডে যান</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-emerald-600 transition duration-300"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection