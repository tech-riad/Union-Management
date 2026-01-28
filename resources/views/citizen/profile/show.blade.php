@extends('layouts.app')

@section('title', 'নাগরিক প্রোফাইল')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-6 md:mb-0">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">
                        <span class="text-blue-600">আমার</span> প্রোফাইল
                    </h1>
                    <p class="text-gray-600 text-lg">
                        আপনার ব্যক্তিগত তথ্য ও প্রোফাইল বিবরণ
                    </p>
                </div>
                
                <!-- Edit Button -->
                <div>
                    <a href="{{ route('citizen.profile.edit') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-300 shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        প্রোফাইল সম্পাদনা
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
            <!-- Profile Header with Photo -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 md:p-8 text-white">
                <div class="flex flex-col md:flex-row items-center">
                    <!-- Profile Photo -->
                    <div class="mb-6 md:mb-0 md:mr-8">
                        @if($profile && $profile->profile_photo)
                            <div class="relative">
                                <img src="{{ asset('storage/'.$profile->profile_photo) }}" 
                                     alt="Profile Photo"
                                     class="w-36 h-36 rounded-full object-cover border-4 border-white shadow-lg">
                                <div class="absolute bottom-2 right-2 bg-blue-100 text-blue-600 p-1 rounded-full">
                                    <i class="fas fa-camera text-sm"></i>
                                </div>
                            </div>
                        @else
                            <div class="relative">
                                <div class="w-36 h-36 rounded-full bg-gradient-to-br from-blue-100 to-blue-300 flex items-center justify-center border-4 border-white shadow-lg">
                                    <i class="fas fa-user text-4xl text-blue-600"></i>
                                </div>
                                <div class="absolute bottom-2 right-2 bg-blue-100 text-blue-600 p-1 rounded-full">
                                    <i class="fas fa-plus text-sm"></i>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Basic Info -->
                    <div class="text-center md:text-left">
                        <h2 class="text-2xl md:text-3xl font-bold mb-2">{{ $profile->name_bn ?? 'নাম পাওয়া যায়নি' }}</h2>
                        <p class="text-blue-100 mb-3">{{ $profile->name_en ?? '' }}</p>
                        
                        <div class="flex flex-wrap justify-center md:justify-start gap-3 mb-4">
                            <div class="bg-blue-400 bg-opacity-30 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-id-card mr-1"></i>
                                NID: {{ $profile->nid_number ?? 'নাই' }}
                            </div>
                            <div class="bg-blue-400 bg-opacity-30 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-user-tag mr-1"></i>
                                {{ ucfirst($profile->gender ?? 'নাই') }}
                            </div>
                            <div class="bg-blue-400 bg-opacity-30 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-briefcase mr-1"></i>
                                {{ $profile->occupation ?? 'পেশা নাই' }}
                            </div>
                            <div class="bg-blue-400 bg-opacity-30 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-mobile-alt mr-1"></i>
                                {{ $profile->mobile ?? 'মোবাইল নাই' }}
                            </div>
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="flex flex-wrap gap-4 text-sm">
                            @if($profile && $profile->mobile)
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-2 text-blue-200"></i>
                                <span>{{ $profile->mobile }}</span>
                            </div>
                            @endif
                            
                            @if($profile && $profile->email)
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-2 text-blue-200"></i>
                                <span>{{ $profile->email }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="p-6 md:p-8">
                <!-- Contact Information Section -->
                <div class="mb-10">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-teal-100 rounded-lg mr-3">
                            <i class="fas fa-address-book text-teal-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">যোগাযোগ তথ্য</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mobile Number -->
                        <div class="bg-gradient-to-r from-teal-50 to-teal-100 rounded-xl p-5 border border-teal-200">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-teal-100 rounded-lg mr-3">
                                    <i class="fas fa-mobile-alt text-teal-600 text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">মোবাইল নম্বর</h4>
                                    <p class="text-sm text-gray-500">প্রাথমিক যোগাযোগের নম্বর</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-white p-2 rounded-lg mr-3">
                                    <i class="fas fa-phone text-teal-500"></i>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-gray-800">{{ $profile->mobile ?? 'নাই' }}</p>
                                    @if($profile && $profile->mobile)
                                    <div class="flex space-x-2 mt-2">
                                        <a href="tel:{{ $profile->mobile }}" 
                                           class="text-xs bg-teal-500 text-white px-2 py-1 rounded hover:bg-teal-600 transition">
                                            <i class="fas fa-phone-alt mr-1"></i> কল করুন
                                        </a>
                                        <a href="https://wa.me/88{{ str_replace('+', '', $profile->mobile) }}" 
                                           target="_blank"
                                           class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 transition">
                                            <i class="fab fa-whatsapp mr-1"></i> WhatsApp
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Email Address -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                    <i class="fas fa-envelope text-blue-600 text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">ইমেইল ঠিকানা</h4>
                                    <p class="text-sm text-gray-500">ইলেকট্রনিক যোগাযোগ</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-white p-2 rounded-lg mr-3">
                                    <i class="fas fa-at text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-gray-800">{{ $profile->email ?? 'নাই' }}</p>
                                    @if($profile && $profile->email)
                                    <div class="mt-2">
                                        <a href="mailto:{{ $profile->email }}" 
                                           class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition">
                                            <i class="fas fa-paper-plane mr-1"></i> ইমেইল করুন
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="mb-10">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-blue-100 rounded-lg mr-3">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">ব্যক্তিগত তথ্য</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Column 1 -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition duration-300">
                                <div class="text-sm text-gray-500 mb-1">
                                    <i class="fas fa-signature mr-2"></i> নাম (বাংলা)
                                </div>
                                <div class="text-lg font-semibold text-gray-800">{{ $profile->name_bn ?? '-' }}</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition duration-300">
                                <div class="text-sm text-gray-500 mb-1">
                                    <i class="fas fa-user-friends mr-2"></i> পিতার নাম
                                </div>
                                <div class="text-lg font-semibold text-gray-800">{{ $profile->father_name_bn ?? '-' }}</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition duration-300">
                                <div class="text-sm text-gray-500 mb-1">
                                    <i class="fas fa-female mr-2"></i> মাতার নাম
                                </div>
                                <div class="text-lg font-semibold text-gray-800">{{ $profile->mother_name_bn ?? '-' }}</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition duration-300">
                                <div class="text-sm text-gray-500 mb-1">
                                    <i class="fas fa-birthday-cake mr-2"></i> জন্ম তারিখ
                                </div>
                                <div class="text-lg font-semibold text-gray-800">
                                    @if($profile && $profile->dob)
                                        {{ \Carbon\Carbon::parse($profile->dob)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Column 2 -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition duration-300">
                                <div class="text-sm text-gray-500 mb-1">
                                    <i class="fas fa-signature mr-2"></i> Name (English)
                                </div>
                                <div class="text-lg font-semibold text-gray-800">{{ $profile->name_en ?? '-' }}</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition duration-300">
                                <div class="text-sm text-gray-500 mb-1">
                                    <i class="fas fa-user-friends mr-2"></i> Father's Name
                                </div>
                                <div class="text-lg font-semibold text-gray-800">{{ $profile->father_name_en ?? '-' }}</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition duration-300">
                                <div class="text-sm text-gray-500 mb-1">
                                    <i class="fas fa-female mr-2"></i> Mother's Name
                                </div>
                                <div class="text-lg font-semibold text-gray-800">{{ $profile->mother_name_en ?? '-' }}</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition duration-300">
                                <div class="text-sm text-gray-500 mb-1">
                                    <i class="fas fa-heart mr-2"></i> বৈবাহিক অবস্থা
                                </div>
                                <div class="text-lg font-semibold text-gray-800">{{ ucfirst($profile->marital_status ?? '-') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="mb-10">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-green-100 rounded-lg mr-3">
                            <i class="fas fa-home text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">ঠিকানা তথ্য</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-green-50 rounded-xl p-5 border border-green-100">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-green-100 rounded-lg mr-3">
                                    <i class="fas fa-map-marker-alt text-green-600"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800">বর্তমান ঠিকানা</h4>
                            </div>
                            <p class="text-gray-700">{{ $profile->present_address ?? 'নাই' }}</p>
                        </div>
                        
                        <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                    <i class="fas fa-map-pin text-blue-600"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800">স্থায়ী ঠিকানা</h4>
                            </div>
                            <p class="text-gray-700">{{ $profile->permanent_address ?? 'নাই' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="text-sm text-gray-500 mb-1">
                                <i class="fas fa-village mr-2"></i> গ্রাম
                            </div>
                            <div class="font-medium text-gray-800">{{ $profile->village ?? '-' }}</div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="text-sm text-gray-500 mb-1">
                                <i class="fas fa-building mr-2"></i> ওয়ার্ড
                            </div>
                            <div class="font-medium text-gray-800">{{ $profile->ward ?? '-' }}</div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="text-sm text-gray-500 mb-1">
                                <i class="fas fa-map-marked-alt mr-2"></i> জন্মস্থান
                            </div>
                            <div class="font-medium text-gray-800">{{ $profile->birth_place ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-10">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-purple-100 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">অন্যান্য তথ্য</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition duration-300">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                    <i class="fas fa-mosque text-blue-600"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800">ধর্ম</h4>
                            </div>
                            <p class="text-gray-700">{{ $profile->religion ?? 'নাই' }}</p>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition duration-300">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-green-100 rounded-lg mr-3">
                                    <i class="fas fa-graduation-cap text-green-600"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800">শিক্ষাগত যোগ্যতা</h4>
                            </div>
                            <p class="text-gray-700">{{ $profile->education ?? 'নাই' }}</p>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition duration-300">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                                    <i class="fas fa-briefcase text-yellow-600"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800">পেশা</h4>
                            </div>
                            <p class="text-gray-700">{{ $profile->occupation ?? 'নাই' }}</p>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition duration-300">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-red-100 rounded-lg mr-3">
                                    <i class="fas fa-ruler-vertical text-red-600"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800">উচ্চতা</h4>
                            </div>
                            <p class="text-gray-700">{{ $profile->height ?? 'নাই' }}</p>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition duration-300">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                    <i class="fas fa-star text-purple-600"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800">জন্মচিহ্ন</h4>
                            </div>
                            <p class="text-gray-700">{{ $profile->birth_mark ?? 'নাই' }}</p>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition duration-300">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                                    <i class="fas fa-tags text-indigo-600"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800">কোটা</h4>
                            </div>
                            <p class="text-gray-700">{{ $profile->quota ?? 'নাই' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact (Optional) -->
                @if($profile && ($profile->emergency_contact || $profile->emergency_mobile))
                <div class="mb-10">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-red-100 rounded-lg mr-3">
                            <i class="fas fa-phone-square-alt text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">জরুরী যোগাযোগ</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($profile->emergency_contact)
                        <div class="bg-red-50 rounded-xl p-5 border border-red-200">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-red-100 rounded-lg mr-3">
                                    <i class="fas fa-user-friends text-red-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">জরুরী যোগাযোগ ব্যক্তি</h4>
                                    <p class="text-sm text-gray-500">জরুরী অবস্থায় যোগাযোগের ব্যক্তি</p>
                                </div>
                            </div>
                            <p class="text-lg font-medium text-gray-800">{{ $profile->emergency_contact }}</p>
                        </div>
                        @endif
                        
                        @if($profile->emergency_mobile)
                        <div class="bg-orange-50 rounded-xl p-5 border border-orange-200">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-orange-100 rounded-lg mr-3">
                                    <i class="fas fa-phone-volume text-orange-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">জরুরী মোবাইল নম্বর</h4>
                                    <p class="text-sm text-gray-500">জরুরী অবস্থায় যোগাযোগের নম্বর</p>
                                </div>
                            </div>
                            <p class="text-lg font-bold text-gray-800">{{ $profile->emergency_mobile }}</p>
                            <div class="mt-3">
                                <a href="tel:{{ $profile->emergency_mobile }}" 
                                   class="inline-flex items-center px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                                    <i class="fas fa-phone-alt mr-1"></i> জরুরী কল করুন
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('citizen.profile.edit') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-300 shadow-md flex-1">
                            <i class="fas fa-edit mr-2"></i>
                            প্রোফাইল সম্পাদনা করুন
                        </a>
                        
                        <a href="{{ route('citizen.dashboard') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 transition duration-300 flex-1">
                            <i class="fas fa-arrow-left mr-2"></i>
                            ড্যাশবোর্ডে ফিরে যান
                        </a>
                    </div>
                    
                    <!-- Quick Contact Actions -->
                    <div class="mt-6 flex flex-wrap gap-3 justify-center">
                        @if($profile && $profile->mobile)
                        <a href="tel:{{ $profile->mobile }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                            <i class="fas fa-phone mr-2"></i> কল করুন
                        </a>
                        @endif
                        
                        @if($profile && $profile->email)
                        <a href="mailto:{{ $profile->email }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                            <i class="fas fa-envelope mr-2"></i> ইমেইল করুন
                        </a>
                        @endif
                        
                        @if($profile && $profile->mobile)
                        <a href="https://wa.me/88{{ str_replace('+', '', $profile->mobile) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Stats Card -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg mr-4">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">0</div>
                        <div class="text-sm text-gray-600">মোট আবেদন</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">0</div>
                        <div class="text-sm text-gray-600">অনুমোদিত আবেদন</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg mr-4">
                        <i class="fas fa-receipt text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">0</div>
                        <div class="text-sm text-gray-600">চালান সমূহ</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Card -->
        <div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
            <div class="flex items-start">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-2">প্রোফাইল তথ্য সম্পর্কে</h4>
                    <p class="text-gray-600 text-sm">
                        আপনার প্রোফাইলের সকল তথ্য সঠিক এবং হালনাগাদ আছে কিনা নিশ্চিত করুন। 
                        কোন তথ্য ভুল থাকলে "প্রোফাইল সম্পাদনা" বাটনে ক্লিক করে সংশোধন করুন।
                        প্রোফাইল তথ্য আপডেট করতে সর্বশেষ NID এবং অন্যান্য প্রয়োজনীয় কাগজপত্র প্রস্তুত রাখুন।
                    </p>
                    <div class="mt-4 text-sm">
                        <p class="text-gray-700 font-medium">যোগাযোগ তথ্য:</p>
                        <ul class="list-disc pl-5 text-gray-600 mt-2 space-y-1">
                            <li>মোবাইল নম্বর পেমেন্ট এবং জরুরী যোগাযোগের জন্য ব্যবহার করা হয়</li>
                            <li>ইমেইল ঠিকানা অফিসিয়াল নোটিশেশনের জন্য ব্যবহৃত হয়</li>
                            <li>জরুরী যোগাযোগের তথ্য থাকলে তা এমারজেন্সিতে ব্যবহার করুন</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Styles for Profile Page */
    .profile-card {
        transition: all 0.3s ease;
    }
    
    .profile-card:hover {
        transform: translateY(-2px);
    }
    
    /* Mobile number animation */
    .mobile-number {
        position: relative;
        overflow: hidden;
    }
    
    .mobile-number::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: 0.5s;
    }
    
    .mobile-number:hover::after {
        left: 100%;
    }
    
    /* Contact button styles */
    .contact-btn {
        transition: all 0.3s ease;
    }
    
    .contact-btn:hover {
        transform: scale(1.05);
    }
</style>

<script>
    // Animation for profile cards
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to profile cards
        const cards = document.querySelectorAll('.bg-gray-50, .bg-white');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('fade-in');
        });
        
        // Profile photo hover effect
        const profilePhoto = document.querySelector('img[alt="Profile Photo"]');
        if (profilePhoto) {
            profilePhoto.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            profilePhoto.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        }
        
        // Mobile number copy functionality
        const mobileElement = document.querySelector('.mobile-number');
        if (mobileElement) {
            mobileElement.addEventListener('click', function() {
                const mobileNumber = this.textContent.trim();
                if (mobileNumber !== 'নাই') {
                    navigator.clipboard.writeText(mobileNumber).then(() => {
                        // Show copied message
                        const originalText = this.textContent;
                        this.textContent = 'কপি হয়েছে!';
                        this.style.color = '#10b981';
                        
                        setTimeout(() => {
                            this.textContent = originalText;
                            this.style.color = '';
                        }, 2000);
                    });
                }
            });
        }
        
        // Contact buttons animation
        const contactButtons = document.querySelectorAll('.contact-btn');
        contactButtons.forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.boxShadow = '';
            });
        });
    });
</script>
@endsection