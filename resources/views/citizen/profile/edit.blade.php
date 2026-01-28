@extends('layouts.app')

@section('title', 'প্রোফাইল সম্পাদনা - নাগরিক')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                প্রোফাইল সম্পাদনা করুন
            </h1>
            <p class="text-gray-600">আপনার ব্যক্তিগত তথ্য আপডেট করুন। সমস্ত তথ্য সঠিকভাবে পূরণ করুন।</p>
        </div>

        <!-- Progress Indicator -->
        <div class="mb-10">
            <div class="flex items-center justify-center mb-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center">
                        <span>১</span>
                    </div>
                    <div class="w-32 h-1 bg-blue-600"></div>
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center">
                        <span>২</span>
                    </div>
                    <div class="w-32 h-1 bg-gray-300"></div>
                    <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center">
                        <span>৩</span>
                    </div>
                </div>
            </div>
            <div class="text-center text-sm text-gray-600">
                <span class="text-blue-600 font-semibold">ব্যক্তিগত তথ্য</span> → যোগাযোগ তথ্য → সম্পূর্ণকরণ
            </div>
        </div>

        <form action="{{ route('citizen.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg overflow-hidden">
            @csrf
            @method('PUT')

            <!-- Personal Information Section -->
            <div class="border-b border-gray-200">
                <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        ব্যক্তিগত তথ্য
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">
                    <!-- Profile Photo Upload -->
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-6 mb-8">
                            <div class="relative">
                                @if(isset($profile) && $profile->profile_photo)
                                    <img src="{{ asset('storage/' . $profile->profile_photo) }}" 
                                         alt="Profile" 
                                         class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                                @else
                                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center border-4 border-white shadow-lg">
                                        <svg class="w-16 h-16 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute bottom-0 right-0 bg-blue-600 rounded-full p-2 shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    প্রোফাইল ছবি
                                </label>
                                <div class="flex items-center gap-4">
                                    <input type="file" 
                                           name="profile_photo" 
                                           id="profile_photo"
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <div class="text-xs text-gray-500">
                                        সর্বোচ্চ ২MB, JPG, PNG ফরম্যাট
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Name in Bengali -->
                    <div class="space-y-2">
                        <label for="name_bn" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> নাম (বাংলা)
                        </label>
                        <input type="text" 
                               id="name_bn" 
                               name="name_bn" 
                               value="{{ old('name_bn', isset($profile) ? $profile->name_bn : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="আপনার বাংলা নাম লিখুন">
                        @error('name_bn')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name in English -->
                    <div class="space-y-2">
                        <label for="name_en" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> Name (English)
                        </label>
                        <input type="text" 
                               id="name_en" 
                               name="name_en" 
                               value="{{ old('name_en', isset($profile) ? $profile->name_en : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="Enter your name in English">
                        @error('name_en')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Father's Name -->
                    <div class="space-y-2">
                        <label for="father_name_bn" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> পিতার নাম (বাংলা)
                        </label>
                        <input type="text" 
                               id="father_name_bn" 
                               name="father_name_bn" 
                               value="{{ old('father_name_bn', isset($profile) ? $profile->father_name_bn : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="পিতার বাংলা নাম লিখুন">
                        @error('father_name_bn')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="father_name_en" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> Father's Name (English)
                        </label>
                        <input type="text" 
                               id="father_name_en" 
                               name="father_name_en" 
                               value="{{ old('father_name_en', isset($profile) ? $profile->father_name_en : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="Enter father's name in English">
                        @error('father_name_en')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mother's Name -->
                    <div class="space-y-2">
                        <label for="mother_name_bn" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> মাতার নাম (বাংলা)
                        </label>
                        <input type="text" 
                               id="mother_name_bn" 
                               name="mother_name_bn" 
                               value="{{ old('mother_name_bn', isset($profile) ? $profile->mother_name_bn : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="মাতার বাংলা নাম লিখুন">
                        @error('mother_name_bn')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="mother_name_en" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> Mother's Name (English)
                        </label>
                        <input type="text" 
                               id="mother_name_en" 
                               name="mother_name_en" 
                               value="{{ old('mother_name_en', isset($profile) ? $profile->mother_name_en : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="Enter mother's name in English">
                        @error('mother_name_en')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="space-y-2">
                        <label for="dob" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> জন্ম তারিখ
                        </label>
                        <input type="date" 
                               id="dob" 
                               name="dob" 
                               value="{{ old('dob', isset($profile) ? $profile->dob : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               onchange="calculateAge()">
                        @error('dob')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Calculated Age -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            বয়স
                        </label>
                        <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg">
                            <span id="ageDisplay" class="text-gray-700">-- বছর</span>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="space-y-2">
                        <label for="gender" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> লিঙ্গ
                        </label>
                        <select id="gender" 
                                name="gender"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="">লিঙ্গ নির্বাচন করুন</option>
                            <option value="male" {{ old('gender', isset($profile) ? $profile->gender : '') == 'male' ? 'selected' : '' }}>পুরুষ</option>
                            <option value="female" {{ old('gender', isset($profile) ? $profile->gender : '') == 'female' ? 'selected' : '' }}>মহিলা</option>
                            <option value="other" {{ old('gender', isset($profile) ? $profile->gender : '') == 'other' ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Marital Status -->
                    <div class="space-y-2">
                        <label for="marital_status" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> বৈবাহিক অবস্থা
                        </label>
                        <select id="marital_status" 
                                name="marital_status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="">অবস্থা নির্বাচন করুন</option>
                            <option value="single" {{ old('marital_status', isset($profile) ? $profile->marital_status : '') == 'single' ? 'selected' : '' }}>অবিবাহিত</option>
                            <option value="married" {{ old('marital_status', isset($profile) ? $profile->marital_status : '') == 'married' ? 'selected' : '' }}>বিবাহিত</option>
                            <option value="divorced" {{ old('marital_status', isset($profile) ? $profile->marital_status : '') == 'divorced' ? 'selected' : '' }}>তালাকপ্রাপ্ত</option>
                            <option value="widowed" {{ old('marital_status', isset($profile) ? $profile->marital_status : '') == 'widowed' ? 'selected' : '' }}>বিধবা/বিপত্নীক</option>
                        </select>
                        @error('marital_status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Religion -->
                    <div class="space-y-2">
                        <label for="religion" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> ধর্ম
                        </label>
                        <select id="religion" 
                                name="religion"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="">ধর্ম নির্বাচন করুন</option>
                            <option value="islam" {{ old('religion', isset($profile) ? $profile->religion : '') == 'islam' ? 'selected' : '' }}>ইসলাম</option>
                            <option value="hinduism" {{ old('religion', isset($profile) ? $profile->religion : '') == 'hinduism' ? 'selected' : '' }}>হিন্দু</option>
                            <option value="christianity" {{ old('religion', isset($profile) ? $profile->religion : '') == 'christianity' ? 'selected' : '' }}>খ্রিস্টান</option>
                            <option value="buddhism" {{ old('religion', isset($profile) ? $profile->religion : '') == 'buddhism' ? 'selected' : '' }}>বৌদ্ধ</option>
                            <option value="other" {{ old('religion', isset($profile) ? $profile->religion : '') == 'other' ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                        @error('religion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NID Number (Conditional) -->
                    <div class="space-y-2" id="nidSection">
                        <label for="nid_number" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> জাতীয় পরিচয়পত্র নম্বর
                        </label>
                        <input type="text" 
                               id="nid_number" 
                               name="nid_number" 
                               value="{{ old('nid_number', isset($profile) ? $profile->nid_number : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="১০ বা ১৭ ডিজিটের NID নম্বর">
                        <p class="text-xs text-gray-500" id="nidHelp">১৮ বছরের বেশি বয়সীদের জন্য আবশ্যক</p>
                        @error('nid_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Height -->
                    <div class="space-y-2">
                        <label for="height" class="block text-sm font-medium text-gray-700">
                            উচ্চতা
                        </label>
                        <input type="text" 
                               id="height" 
                               name="height" 
                               value="{{ old('height', isset($profile) ? $profile->height : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="উদা: ৫' ৬''">
                        @error('height')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birth Mark -->
                    <div class="space-y-2">
                        <label for="birth_mark" class="block text-sm font-medium text-gray-700">
                            জন্ম চিহ্ন
                        </label>
                        <input type="text" 
                               id="birth_mark" 
                               name="birth_mark" 
                               value="{{ old('birth_mark', isset($profile) ? $profile->birth_mark : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="জন্ম চিহ্নের বিবরণ">
                        @error('birth_mark')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information Section -->
            <div class="border-b border-gray-200">
                <div class="px-8 py-6 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        ঠিকানা তথ্য
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">
                    <!-- Present Address -->
                    <div class="md:col-span-2">
                        <label for="present_address" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> বর্তমান ঠিকানা
                        </label>
                        <textarea id="present_address" 
                                  name="present_address" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                  placeholder="পূর্ণ বর্তমান ঠিকানা লিখুন">{{ old('present_address', isset($profile) ? $profile->present_address : '') }}</textarea>
                        @error('present_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Permanent Address -->
                    <div class="md:col-span-2">
                        <label for="permanent_address" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> স্থায়ী ঠিকানা
                        </label>
                        <textarea id="permanent_address" 
                                  name="permanent_address" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                  placeholder="পূর্ণ স্থায়ী ঠিকানা লিখুন">{{ old('permanent_address', isset($profile) ? $profile->permanent_address : '') }}</textarea>
                        @error('permanent_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Village -->
                    <div class="space-y-2">
                        <label for="village" class="block text-sm font-medium text-gray-700">
                            গ্রাম
                        </label>
                        <input type="text" 
                               id="village" 
                               name="village" 
                               value="{{ old('village', isset($profile) ? $profile->village : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="গ্রামের নাম">
                        @error('village')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ward -->
                    <div class="space-y-2">
                        <label for="ward" class="block text-sm font-medium text-gray-700">
                            ওয়ার্ড নম্বর
                        </label>
                        <input type="text" 
                               id="ward" 
                               name="ward" 
                               value="{{ old('ward', isset($profile) ? $profile->ward : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="ওয়ার্ড নম্বর">
                        @error('ward')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Other Information Section -->
            <div>
                <div class="px-8 py-6 bg-gradient-to-r from-purple-50 to-pink-50">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        অন্যান্য তথ্য
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">
                    <!-- Occupation -->
                    <div class="space-y-2">
                        <label for="occupation" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> পেশা
                        </label>
                        <select id="occupation" 
                                name="occupation"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="">পেশা নির্বাচন করুন</option>
                            <option value="student" {{ old('occupation', isset($profile) ? $profile->occupation : '') == 'student' ? 'selected' : '' }}>শিক্ষার্থী</option>
                            <option value="government_job" {{ old('occupation', isset($profile) ? $profile->occupation : '') == 'government_job' ? 'selected' : '' }}>সরকারি চাকরি</option>
                            <option value="private_job" {{ old('occupation', isset($profile) ? $profile->occupation : '') == 'private_job' ? 'selected' : '' }}>প্রাইভেট চাকরি</option>
                            <option value="business" {{ old('occupation', isset($profile) ? $profile->occupation : '') == 'business' ? 'selected' : '' }}>ব্যবসা</option>
                            <option value="housewife" {{ old('occupation', isset($profile) ? $profile->occupation : '') == 'housewife' ? 'selected' : '' }}>গৃহিণী</option>
                            <option value="farmer" {{ old('occupation', isset($profile) ? $profile->occupation : '') == 'farmer' ? 'selected' : '' }}>কৃষক</option>
                            <option value="unemployed" {{ old('occupation', isset($profile) ? $profile->occupation : '') == 'unemployed' ? 'selected' : '' }}>বেকার</option>
                            <option value="other" {{ old('occupation', isset($profile) ? $profile->occupation : '') == 'other' ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                        @error('occupation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Education -->
                    <div class="space-y-2">
                        <label for="education" class="block text-sm font-medium text-gray-700">
                            <span class="text-red-500">*</span> শিক্ষাগত যোগ্যতা
                        </label>
                        <select id="education" 
                                name="education"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="">শিক্ষাগত যোগ্যতা নির্বাচন করুন</option>
                            <option value="illiterate" {{ old('education', isset($profile) ? $profile->education : '') == 'illiterate' ? 'selected' : '' }}>অশিক্ষিত</option>
                            <option value="primary" {{ old('education', isset($profile) ? $profile->education : '') == 'primary' ? 'selected' : '' }}>প্রাথমিক</option>
                            <option value="secondary" {{ old('education', isset($profile) ? $profile->education : '') == 'secondary' ? 'selected' : '' }}>মাধ্যমিক</option>
                            <option value="higher_secondary" {{ old('education', isset($profile) ? $profile->education : '') == 'higher_secondary' ? 'selected' : '' }}>উচ্চ মাধ্যমিক</option>
                            <option value="graduate" {{ old('education', isset($profile) ? $profile->education : '') == 'graduate' ? 'selected' : '' }}>স্নাতক</option>
                            <option value="post_graduate" {{ old('education', isset($profile) ? $profile->education : '') == 'post_graduate' ? 'selected' : '' }}>স্নাতকোত্তর</option>
                        </select>
                        @error('education')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quota -->
                    <div class="md:col-span-2 space-y-2">
                        <label for="quota" class="block text-sm font-medium text-gray-700">
                            কোটা (যদি প্রযোজ্য হয়)
                        </label>
                        <select id="quota" 
                                name="quota"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="">কোন কোটা প্রযোজ্য নয়</option>
                            <option value="freedom_fighter" {{ old('quota', isset($profile) ? $profile->quota : '') == 'freedom_fighter' ? 'selected' : '' }}>মুক্তিযোদ্ধা</option>
                            <option value="tribal" {{ old('quota', isset($profile) ? $profile->quota : '') == 'tribal' ? 'selected' : '' }}>উপজাতি</option>
                            <option value="disabled" {{ old('quota', isset($profile) ? $profile->quota : '') == 'disabled' ? 'selected' : '' }}>প্রতিবন্ধী</option>
                            <option value="woman" {{ old('quota', isset($profile) ? $profile->quota : '') == 'woman' ? 'selected' : '' }}>মহিলা</option>
                        </select>
                        @error('quota')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="text-red-500">*</span> বাধ্যতামূলক ক্ষেত্রগুলি নির্দেশ করে
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('citizen.dashboard') }}"
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                            বাতিল করুন
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                প্রোফাইল আপডেট করুন
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Help Section -->
        <div class="mt-8 bg-blue-50 rounded-xl p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                সাহায্য ও নির্দেশনা
            </h3>
            <ul class="space-y-2 text-blue-700">
                <li class="flex items-start gap-2">
                    <span class="text-blue-500">•</span>
                    <span>সমস্ত তথ্য বাংলা এবং ইংরেজি উভয় ভাষায় সঠিকভাবে পূরণ করুন</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-500">•</span>
                    <span>১৮ বছর বা তার বেশি বয়সী ব্যক্তিদের জন্য NID নম্বর আবশ্যক</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-500">•</span>
                    <span>প্রোফাইল ছবির আকার সর্বোচ্চ ২MB হতে হবে</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-500">•</span>
                    <span>ঠিকানা সম্পর্কিত তথ্য পূর্ণাঙ্গ ও স্পষ্টভাবে লিখুন</span>
                </li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
function calculateAge() {
    const dobInput = document.getElementById('dob');
    const ageDisplay = document.getElementById('ageDisplay');
    const nidSection = document.getElementById('nidSection');
    const nidInput = document.getElementById('nid_number');
    const nidHelp = document.getElementById('nidHelp');
    
    if (dobInput.value) {
        const dob = new Date(dobInput.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        
        ageDisplay.textContent = `${age} বছর`;
        
        // Show/hide NID section based on age
        if (age >= 18) {
            nidSection.style.display = 'block';
            nidInput.required = true;
            nidHelp.textContent = '১৮ বছরের বেশি বয়সীদের জন্য আবশ্যক';
            nidHelp.className = 'text-xs text-red-500';
        } else {
            nidSection.style.display = 'block';
            nidInput.required = false;
            nidInput.value = '';
            nidHelp.textContent = '১৮ বছরের কম বয়সীদের জন্য প্রযোজ্য নয়';
            nidHelp.className = 'text-xs text-gray-500';
        }
    } else {
        ageDisplay.textContent = '-- বছর';
        nidSection.style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateAge();
    
    // Set initial age based on existing dob
    const dobInput = document.getElementById('dob');
    if (dobInput.value) {
        calculateAge();
    }
    
    // Preview profile photo
    const photoInput = document.getElementById('profile_photo');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const profileImg = document.querySelector('.profile-image');
                    if (profileImg) {
                        profileImg.src = e.target.result;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('দয়া করে সমস্ত বাধ্যতামূলক ক্ষেত্রগুলি পূরণ করুন।');
            }
        });
    }
});
</script>
@endpush
@endsection