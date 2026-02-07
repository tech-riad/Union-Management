@extends('layouts.super-admin')

@section('title', 'ইউনিয়ন সেটিংস - সুপার অ্যাডমিন')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-university text-blue-600 mr-3"></i>
                    ইউনিয়ন সেটিংস
                </h1>
                <p class="text-gray-600">ইউনিয়নের সমস্ত তথ্য, লোগো, সীল ও স্বাক্ষর পরিচালনা করুন</p>
            </div>
            <div>
                <button onclick="resetAllSettings()" class="px-4 py-2 border border-red-500 text-red-600 rounded-lg hover:bg-red-50 transition-colors duration-200 flex items-center">
                    <i class="fas fa-trash-restore mr-2"></i> ডিফল্টে রিসেট
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-6 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-600 p-4 animate-fade-in shadow-md">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl text-green-600 mr-3"></i>
                <div class="flex-1">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-600 p-4 animate-fade-in shadow-md">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-2xl text-red-600 mr-3"></i>
                <div class="flex-1">
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-600 p-4 animate-fade-in shadow-md">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-2xl text-red-600 mr-3"></i>
                <div class="flex-1">
                    <h6 class="font-bold mb-1 text-red-800">নিম্নলিখিত ত্রুটি ঠিক করুন:</h6>
                    <ul class="mt-2 space-y-1">
                        @foreach($errors->all() as $error)
                        <li class="flex items-start text-red-700">
                            <i class="fas fa-circle text-xs mt-1 mr-2"></i>
                            {{ $error }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        <!-- Settings Form -->
        <form action="{{ route('super_admin.settings.union.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Basic Info & Media -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information Card -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-info-circle mr-3"></i>
                                ইউনিয়ন তথ্য
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ইউনিয়ন নাম (বাংলা) *
                                    </label>
                                    <input type="text"
                                           name="union_name_bangla"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('union_name_bangla') border-red-500 @enderror"
                                           value="{{ old('union_name_bangla', $settings->union_name_bangla) }}"
                                           placeholder="ইউনিয়ন পরিষদ, উত্তর গাবতলী"
                                           required>
                                    @error('union_name_bangla')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ইউনিয়ন নাম (ইংরেজি) *
                                    </label>
                                    <input type="text"
                                           name="union_name"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('union_name') border-red-500 @enderror"
                                           value="{{ old('union_name', $settings->union_name) }}"
                                           placeholder="Union Parishad, North Gabtali"
                                           required>
                                    @error('union_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        যোগাযোগ নম্বর *
                                    </label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="text"
                                               name="contact_number"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('contact_number') border-red-500 @enderror"
                                               value="{{ old('contact_number', $settings->contact_number) }}"
                                               placeholder="১৬৩৪৫"
                                               required>
                                    </div>
                                    @error('contact_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        জরুরী নম্বর
                                    </label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-ambulance"></i>
                                        </span>
                                        <input type="text"
                                               name="emergency_number"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('emergency_number') border-red-500 @enderror"
                                               value="{{ old('emergency_number', $settings->emergency_number) }}"
                                               placeholder="৯৯৯">
                                    </div>
                                    @error('emergency_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ইমেইল *
                                    </label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email"
                                               name="email"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                               value="{{ old('email', $settings->email) }}"
                                               placeholder="info@union.gov.bd"
                                               required>
                                    </div>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ঠিকানা (বাংলা) *
                                    </label>
                                    <textarea name="address_bangla"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address_bangla') border-red-500 @enderror"
                                              rows="3"
                                              placeholder="উত্তর গাবতলী, গাবতলী উপজেলা, বগুড়া"
                                              required>{{ old('address_bangla', $settings->address_bangla) }}</textarea>
                                    @error('address_bangla')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ঠিকানা (ইংরেজি) *
                                    </label>
                                    <textarea name="address"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                                              rows="3"
                                              placeholder="North Gabtali, Gabtali Upazila, Bogura"
                                              required>{{ old('address', $settings->address) }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chairman & Secretary Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Chairman Card -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-800 text-white">
                                <h2 class="text-xl font-bold flex items-center">
                                    <i class="fas fa-user-tie mr-3"></i>
                                    চেয়ারম্যান তথ্য
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        চেয়ারম্যানের নাম *
                                    </label>
                                    <input type="text"
                                           name="chairman_name"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('chairman_name') border-red-500 @enderror"
                                           value="{{ old('chairman_name', $settings->chairman_name) }}"
                                           placeholder="মোঃ রফিকুল ইসলাম"
                                           required>
                                    @error('chairman_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        চেয়ারম্যানের ফোন *
                                    </label>
                                    <input type="text"
                                           name="chairman_phone"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('chairman_phone') border-red-500 @enderror"
                                           value="{{ old('chairman_phone', $settings->chairman_phone) }}"
                                           placeholder="০১৭XXXXXXXX"
                                           required>
                                    @error('chairman_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Chairman Signature -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        চেয়ারম্যান স্বাক্ষর
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-blue-500 transition-colors duration-200">
                                        @if($settings->chairman_signature)
                                            <div class="mb-4">
                                                <img src="{{ Storage::url($settings->chairman_signature) }}"
                                                     alt="Chairman Signature"
                                                     class="mx-auto max-h-32 border rounded-lg">
                                            </div>
                                            <button type="button"
                                                    onclick="deleteImage('chairman_signature')"
                                                    class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 text-sm">
                                                <i class="fas fa-trash mr-1"></i> মুছুন
                                            </button>
                                        @else
                                            <div class="py-6">
                                                <i class="fas fa-signature text-4xl text-gray-400 mb-3"></i>
                                                <p class="text-gray-500 text-sm">স্বাক্ষর আপলোড করা হয়নি</p>
                                            </div>
                                        @endif
                                        <input type="file"
                                               name="chairman_signature"
                                               class="mt-4 w-full px-3 py-2 border border-gray-300 rounded-lg"
                                               accept="image/*">
                                        <p class="text-xs text-gray-500 mt-2">PNG/JPG (সুপারিশ: 200x100px)</p>
                                    </div>
                                </div>

                                <!-- Chairman Seal -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        চেয়ারম্যান সীল
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-blue-500 transition-colors duration-200">
                                        @if($settings->chairman_seal)
                                            <div class="mb-4">
                                                <img src="{{ Storage::url($settings->chairman_seal) }}"
                                                     alt="Chairman Seal"
                                                     class="mx-auto w-32 h-32 border rounded-full object-cover">
                                            </div>
                                            <button type="button"
                                                    onclick="deleteImage('chairman_seal')"
                                                    class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 text-sm">
                                                <i class="fas fa-trash mr-1"></i> মুছুন
                                            </button>
                                        @else
                                            <div class="py-6">
                                                <i class="fas fa-stamp text-4xl text-gray-400 mb-3"></i>
                                                <p class="text-gray-500 text-sm">সীল আপলোড করা হয়নি</p>
                                            </div>
                                        @endif
                                        <input type="file"
                                               name="chairman_seal"
                                               class="mt-4 w-full px-3 py-2 border border-gray-300 rounded-lg"
                                               accept="image/*">
                                        <p class="text-xs text-gray-500 mt-2">PNG/JPG (সুপারিশ: 150x150px)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Secretary Card -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                                <h2 class="text-xl font-bold flex items-center">
                                    <i class="fas fa-user-shield mr-3"></i>
                                    সচিব তথ্য
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        সচিবের নাম *
                                    </label>
                                    <input type="text"
                                           name="secretary_name"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('secretary_name') border-red-500 @enderror"
                                           value="{{ old('secretary_name', $settings->secretary_name) }}"
                                           placeholder="মোঃ আব্দুল করিম"
                                           required>
                                    @error('secretary_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        সচিবের ফোন *
                                    </label>
                                    <input type="text"
                                           name="secretary_phone"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('secretary_phone') border-red-500 @enderror"
                                           value="{{ old('secretary_phone', $settings->secretary_phone) }}"
                                           placeholder="০১৭XXXXXXXX"
                                           required>
                                    @error('secretary_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Secretary Signature -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        সচিব স্বাক্ষর
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-blue-500 transition-colors duration-200">
                                        @if($settings->secretary_signature_url)
                                            <div class="mb-4">
                                                <img src="{{ $settings->secretary_signature_url }}"
                                                     alt="Secretary Signature"
                                                     class="mx-auto max-h-32 border rounded-lg">
                                            </div>
                                            <button type="button"
                                                    onclick="deleteImage('secretary_signature')"
                                                    class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 text-sm">
                                                <i class="fas fa-trash mr-1"></i> মুছুন
                                            </button>
                                        @else
                                            <div class="py-6">
                                                <i class="fas fa-signature text-4xl text-gray-400 mb-3"></i>
                                                <p class="text-gray-500 text-sm">স্বাক্ষর আপলোড করা হয়নি</p>
                                            </div>
                                        @endif
                                        <input type="file"
                                               name="secretary_signature"
                                               class="mt-4 w-full px-3 py-2 border border-gray-300 rounded-lg"
                                               accept="image/*">
                                        <p class="text-xs text-gray-500 mt-2">PNG/JPG (সুপারিশ: 200x100px)</p>
                                    </div>
                                </div>

                                <!-- Office Hours -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        অফিস সময় *
                                    </label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">শুরুর সময়</label>
                                            <input type="time"
                                                   name="office_start_time"
                                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('office_start_time') border-red-500 @enderror"
                                                   value="{{ old('office_start_time', $settings->office_start_time?->format('H:i') ?? '09:00') }}"
                                                   required>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">শেষের সময়</label>
                                            <input type="time"
                                                   name="office_end_time"
                                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('office_end_time') border-red-500 @enderror"
                                                   value="{{ old('office_end_time', $settings->office_end_time?->format('H:i') ?? '17:00') }}"
                                                   required>
                                        </div>
                                    </div>
                                    @error('office_start_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @error('office_end_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        কাজের দিন *
                                    </label>
                                    <input type="text"
                                           name="working_days"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('working_days') border-red-500 @enderror"
                                           value="{{ old('working_days', $settings->working_days ?? 'রবিবার - বৃহস্পতিবার') }}"
                                           placeholder="রবিবার - বৃহস্পতিবার"
                                           required>
                                    @error('working_days')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Media & Appearance -->
                <div class="space-y-6">
                    <!-- Logo Upload Card -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-yellow-600 to-yellow-800 text-white">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-image mr-3"></i>
                                লোগো ও ফ্যাভিকন
                            </h2>
                        </div>
                        <div class="p-6">
                            <!-- Logo -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    ইউনিয়ন লোগো
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-blue-500 transition-colors duration-200">
                                    @if($settings->logo_url)
                                        <div class="mb-4">
                                            <img src="{{ $settings->logo_url }}"
                                                 alt="Union Logo"
                                                 class="mx-auto max-h-32 border rounded-lg">
                                        </div>
                                        <button type="button"
                                                onclick="deleteImage('logo')"
                                                class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 text-sm">
                                            <i class="fas fa-trash mr-1"></i> মুছুন
                                        </button>
                                    @else
                                        <div class="py-6">
                                            <i class="fas fa-landmark text-4xl text-gray-400 mb-3"></i>
                                            <p class="text-gray-500 text-sm">লোগো আপলোড করা হয়নি</p>
                                        </div>
                                    @endif
                                    <input type="file"
                                           name="logo"
                                           class="mt-4 w-full px-3 py-2 border border-gray-300 rounded-lg"
                                           accept="image/*">
                                    <p class="text-xs text-gray-500 mt-2">PNG/JPG/SVG (সুপারিশ: 300x300px)</p>
                                </div>
                            </div>

                            <!-- Favicon -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    ফ্যাভিকন (ব্রাউজার আইকন)
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-blue-500 transition-colors duration-200">
                                    @if($settings->favicon_url)
                                        <div class="mb-4">
                                            <img src="{{ $settings->favicon_url }}"
                                                 alt="Favicon"
                                                 class="mx-auto w-16 h-16 border rounded-lg">
                                        </div>
                                        <button type="button"
                                                onclick="deleteImage('favicon')"
                                                class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 text-sm">
                                            <i class="fas fa-trash mr-1"></i> মুছুন
                                        </button>
                                    @else
                                        <div class="py-6">
                                            <i class="fas fa-flag text-4xl text-gray-400 mb-3"></i>
                                            <p class="text-gray-500 text-sm">ফ্যাভিকন আপলোড করা হয়নি</p>
                                        </div>
                                    @endif
                                    <input type="file"
                                           name="favicon"
                                           class="mt-4 w-full px-3 py-2 border border-gray-300 rounded-lg"
                                           accept="image/*">
                                    <p class="text-xs text-gray-500 mt-2">PNG/ICO (সুপারিশ: 64x64px)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Appearance Settings -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-800 text-white">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-palette mr-3"></i>
                                ডিজাইন ও রং
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        প্রাইমারি কালার
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <input type="color"
                                               name="primary_color"
                                               class="w-16 h-10 border border-gray-300 rounded cursor-pointer"
                                               value="{{ old('primary_color', $settings->primary_color ?? '#3b82f6') }}">
                                        <input type="text"
                                               name="primary_color_text"
                                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                                               value="{{ old('primary_color', $settings->primary_color ?? '#3b82f6') }}"
                                               placeholder="#3b82f6">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        সেকেন্ডারি কালার
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <input type="color"
                                               name="secondary_color"
                                               class="w-16 h-10 border border-gray-300 rounded cursor-pointer"
                                               value="{{ old('secondary_color', $settings->secondary_color ?? '#10b981') }}">
                                        <input type="text"
                                               name="secondary_color_text"
                                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                                               value="{{ old('secondary_color', $settings->secondary_color ?? '#10b981') }}"
                                               placeholder="#10b981">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            কারেন্সি *
                                        </label>
                                        <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="BDT" {{ old('currency', $settings->currency) == 'BDT' ? 'selected' : '' }}>৳ - BDT</option>
                                            <option value="USD" {{ old('currency', $settings->currency) == 'USD' ? 'selected' : '' }}>$ - USD</option>
                                            <option value="EUR" {{ old('currency', $settings->currency) == 'EUR' ? 'selected' : '' }}>€ - EUR</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            সময় অঞ্চল *
                                        </label>
                                        <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="Asia/Dhaka" {{ old('timezone', $settings->timezone) == 'Asia/Dhaka' ? 'selected' : '' }}>এশিয়া/ঢাকা (BDT)</option>
                                            <option value="UTC" {{ old('timezone', $settings->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            তারিখ ফরম্যাট *
                                        </label>
                                        <select name="date_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="d F, Y" {{ old('date_format', $settings->date_format) == 'd F, Y' ? 'selected' : '' }}>২৫ ডিসেম্বর, ২০২৪</option>
                                            <option value="d/m/Y" {{ old('date_format', $settings->date_format) == 'd/m/Y' ? 'selected' : '' }}>২৫/১২/২০২৪</option>
                                            <option value="Y-m-d" {{ old('date_format', $settings->date_format) == 'Y-m-d' ? 'selected' : '' }}>২০২৪-১২-২৫</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            সময় ফরম্যাট *
                                        </label>
                                        <select name="time_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="h:i A" {{ old('time_format', $settings->time_format) == 'h:i A' ? 'selected' : '' }}>০২:৩০ PM</option>
                                            <option value="H:i" {{ old('time_format', $settings->time_format) == 'H:i' ? 'selected' : '' }}>১৪:৩০</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-share-alt mr-3"></i>
                                সোশ্যাল মিডিয়া
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ওয়েবসাইট
                                    </label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-globe"></i>
                                        </span>
                                        <input type="url"
                                               name="website"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               value="{{ old('website', $settings->website) }}"
                                               placeholder="https://union.gov.bd">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ফেসবুক
                                    </label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fab fa-facebook-f"></i>
                                        </span>
                                        <input type="url"
                                               name="facebook"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               value="{{ old('facebook', $settings->facebook) }}"
                                               placeholder="https://facebook.com/union">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        টুইটার
                                    </label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fab fa-twitter"></i>
                                        </span>
                                        <input type="url"
                                               name="twitter"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               value="{{ old('twitter', $settings->twitter) }}"
                                               placeholder="https://twitter.com/union">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ইউটিউব
                                    </label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fab fa-youtube"></i>
                                        </span>
                                        <input type="url"
                                               name="youtube"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               value="{{ old('youtube', $settings->youtube) }}"
                                               placeholder="https://youtube.com/union">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance Mode -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-red-800 text-white">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-tools mr-3"></i>
                                সিস্টেম সেটিংস
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start">
                                <input class="mt-1 mr-3 w-5 h-5 rounded focus:ring-blue-500 text-blue-600"
                                       type="checkbox"
                                       name="maintenance_mode"
                                       id="maintenance_mode"
                                       value="1"
                                       {{ old('maintenance_mode', $settings->maintenance_mode) ? 'checked' : '' }}>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="maintenance_mode">
                                        মেইনটেনেন্স মোড
                                    </label>
                                    <p class="text-sm text-gray-600 mt-1">
                                        চালু করলে শুধু অ্যাডমিন সিস্টেম ব্যবহার করতে পারবেন
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="sticky bottom-0 bg-white/95 backdrop-blur-sm p-4 rounded-xl shadow-lg">
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg hover:from-blue-700 hover:to-blue-900 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center text-lg font-medium">
                            <i class="fas fa-save mr-3"></i> সেটিংস সংরক্ষণ করুন
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div id="resetModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-rose-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-gray-800">সতর্কতা</h3>
                    <p class="text-gray-600">আপনি কি নিশ্চিত যে আপনি <strong>সকল সেটিংস ডিফল্টে রিসেট</strong> করতে চান?</p>
                </div>
            </div>
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 mt-0.5 mr-2"></i>
                    <p class="text-sm text-yellow-800">এই কাজটি বিপরীত করা যাবে না। সকল কাস্টম সেটিংস, লোগো, সীল ও স্বাক্ষর মুছে যাবে।</p>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeResetModal()" class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                    বাতিল
                </button>
                <button type="button" onclick="confirmReset()" class="px-5 py-2 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg hover:opacity-90 transition duration-150">
                    <i class="fas fa-trash-restore mr-2"></i> রিসেট করুন
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Color picker synchronization
        document.querySelectorAll('input[type="color"]').forEach(colorPicker => {
            const textInput = colorPicker.parentNode.querySelector('input[type="text"]');

            colorPicker.addEventListener('input', function() {
                textInput.value = this.value;
            });

            textInput.addEventListener('input', function() {
                if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                    colorPicker.value = this.value;
                }
            });
        });
    });

    // Image deletion
    function deleteImage(type) {
        if (!confirm('আপনি কি এই ছবিটি ডিলিট করতে চান?')) {
            return;
        }

        fetch('{{ route("super_admin.settings.union.delete-image") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('ছবি ডিলিট করতে সমস্যা হয়েছে: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('নেটওয়ার্ক সমস্যা হয়েছে।');
        });
    }

    // Reset modal functions
    function resetAllSettings() {
        const modal = document.getElementById('resetModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('div > div').classList.remove('scale-95', 'opacity-0');
            modal.querySelector('div > div').classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeResetModal() {
        const modal = document.getElementById('resetModal');
        modal.querySelector('div > div').classList.remove('scale-100', 'opacity-100');
        modal.querySelector('div > div').classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function confirmReset() {
        window.location.href = '{{ route("super_admin.settings.union.reset") }}';
    }

    // Close modal when clicking outside
    document.getElementById('resetModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeResetModal();
        }
    });

    // Form validation
    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        const primaryColor = document.querySelector('input[name="primary_color"]').value;
        const secondaryColor = document.querySelector('input[name="secondary_color"]').value;

        const hexRegex = /^#[0-9A-F]{6}$/i;

        if (!hexRegex.test(primaryColor)) {
            alert('প্রাইমারি কালার সঠিক হেক্স ফরম্যাটে দিন (যেমন: #3b82f6)');
            e.preventDefault();
            return false;
        }

        if (!hexRegex.test(secondaryColor)) {
            alert('সেকেন্ডারি কালার সঠিক হেক্স ফরম্যাটে দিন (যেমন: #10b981)');
            e.preventDefault();
            return false;
        }

        return true;
    });

    // Show toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-lg z-50 transition-all duration-300 transform translate-x-full`;

        const typeClasses = {
            success: 'bg-gradient-to-r from-green-500 to-emerald-600 text-white',
            error: 'bg-gradient-to-r from-red-500 to-rose-600 text-white',
            warning: 'bg-gradient-to-r from-yellow-500 to-orange-600 text-white',
            info: 'bg-gradient-to-r from-blue-500 to-cyan-600 text-white'
        };

        toast.classList.add(typeClasses[type]);

        const icon = type === 'success' ? 'check-circle' :
                    type === 'error' ? 'exclamation-circle' :
                    type === 'warning' ? 'exclamation-triangle' : 'info-circle';

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
