@extends('layouts.super-admin')

@section('title', 'সার্টিফিকেট সম্পাদনা করুন - সুপার অ্যাডমিন')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400"></i>
    </li>
    <li class="inline-flex items-center">
        <a href="{{ route('super_admin.certificates.index') }}" class="text-sm font-medium text-gray-500 hover:text-blue-600">সার্টিফিকেট টাইপ</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400"></i>
    </li>
    <li class="inline-flex items-center">
        <span class="text-sm font-medium text-gray-500">সম্পাদনা: {{ $certificate->name }}</span>
    </li>
@endsection

@section('page_title', 'সার্টিফিকেট টাইপ সম্পাদনা করুন')
@section('page_description', 'সার্টিফিকেট টেমপ্লেট বিবরণ আপডেট করুন')

@section('header_action')
    <div class="flex items-center space-x-3">
        <a href="{{ route('super_admin.certificates.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
            <i class="fas fa-arrow-left mr-2"></i> তালিকায় ফিরে যান
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">নিম্নলিখিত ত্রুটিগুলি সংশোধন করুন:</p>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    
    <form action="{{ route('super_admin.certificates.update', $certificate->id) }}" method="POST" id="certificateForm">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- ফর্ম হেডার -->
            <div class="px-6 py-4 border-b bg-gradient-to-r from-yellow-50 to-orange-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">সার্টিফিকেট টাইপ সম্পাদনা করুন</h2>
                        <p class="text-gray-600 text-sm">এই সার্টিফিকেট টেমপ্লেটের বিবরণ আপডেট করুন</p>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                            <i class="fas fa-certificate mr-1"></i> আইডি: #{{ $certificate->id }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- ফর্ম বডি -->
            <div class="p-6 space-y-6">
                <!-- সার্টিফিকেট নাম -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        সার্টিফিকেটের নাম <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name', $certificate->name) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="যেমন: চরিত্র সনদ">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- বর্ণনা -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        বর্ণনা
                    </label>
                    <textarea name="description" 
                              id="description"
                              rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="এই সার্টিফিকেট সম্পর্কে সংক্ষিপ্ত বর্ণনা">{{ old('description', $certificate->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- টেমপ্লেট নির্বাচন -->
                <div>
                    <label for="template" class="block text-sm font-medium text-gray-700 mb-2">
                        টেমপ্লেট টাইপ <span class="text-red-500">*</span>
                    </label>
                    <select name="template" 
                            id="template"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('template') border-red-500 @enderror">
                        <option value="">একটি টেমপ্লেট নির্বাচন করুন</option>
                        @foreach($templates as $template)
                            <option value="{{ $template }}" {{ old('template', $certificate->template) == $template ? 'selected' : '' }}>
                                {{ $template }}
                            </option>
                        @endforeach
                    </select>
                    @error('template')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ফি -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="fee" class="block text-sm font-medium text-gray-700 mb-2">
                            ফি (টাকা) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="fee" 
                               id="fee"
                               value="{{ old('fee', $certificate->fee) }}"
                               required
                               min="0"
                               step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fee') border-red-500 @enderror"
                               placeholder="যেমন: ২০০.০০">
                        @error('fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- অবস্থা -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            অবস্থা <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status', $certificate->status) == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                            <option value="inactive" {{ old('status', $certificate->status) == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- মেয়াদ -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="validity" class="block text-sm font-medium text-gray-700 mb-2">
                            মেয়াদ <span class="text-red-500">*</span>
                        </label>
                        <select name="validity" 
                                id="validity"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('validity') border-red-500 @enderror">
                            <option value="none" {{ old('validity', $certificate->validity) == 'none' ? 'selected' : '' }}>সীমাহীন</option>
                            <option value="yearly" {{ old('validity', $certificate->validity) == 'yearly' ? 'selected' : '' }}>বার্ষিক নবায়ন</option>
                        </select>
                        @error('validity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- মেয়াদ দিন (যদি বার্ষিক হয়) -->
                    <div id="validityDaysSection" class="{{ ($certificate->validity == 'yearly' || old('validity') == 'yearly') ? '' : 'hidden' }}">
                        <label for="validity_days" class="block text-sm font-medium text-gray-700 mb-2">
                            মেয়াদকাল (দিন) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="validity_days" 
                               id="validity_days"
                               value="{{ old('validity_days', $certificate->validity_days) }}"
                               min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('validity_days') border-red-500 @enderror"
                               placeholder="যেমন: ৩৬৫ দিন">
                        @error('validity_days')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- সিরিয়াল প্রিফিক্স -->
                <div>
                    <label for="serial_prefix" class="block text-sm font-medium text-gray-700 mb-2">
                        সার্টিফিকেট সিরিয়াল প্রিফিক্স
                    </label>
                    <input type="text" 
                           name="serial_prefix" 
                           id="serial_prefix"
                           value="{{ old('serial_prefix', $certificate->serial_prefix) }}"
                           maxlength="10"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('serial_prefix') border-red-500 @enderror"
                           placeholder="যেমন: CITIZEN (সর্বোচ্চ ১০টি অক্ষর)">
                    <p class="mt-1 text-sm text-gray-500">সার্টিফিকেট সিরিয়াল নম্বরের জন্য প্রিফিক্স (যেমন: CITIZEN-202412-00001)</p>
                    @error('serial_prefix')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- স্বাক্ষর -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        প্রয়োজনীয় স্বাক্ষর
                    </label>
                    <div class="space-y-2" id="signaturesContainer">
                        @php
                            // কন্ট্রোলার থেকে পাস করা স্বাক্ষর ব্যবহার করুন - FIXED
                            $signatureList = $signatures ?? ['চেয়ারম্যান', 'সচিব'];
                            if (old('signatures')) {
                                $signatureList = old('signatures');
                            }
                        @endphp
                        
                        @if(is_array($signatureList) && count($signatureList) > 0)
                            @foreach($signatureList as $index => $signature)
                            <div class="flex items-center signature-item">
                                <input type="text" 
                                       name="signatures[]" 
                                       value="{{ $signature }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="যেমন: চেয়ারম্যান">
                                <button type="button" class="ml-2 text-red-500 hover:text-red-700 remove-signature">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        @else
                            <div class="flex items-center signature-item">
                                <input type="text" 
                                       name="signatures[]" 
                                       value="চেয়ারম্যান"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="যেমন: চেয়ারম্যান">
                                <button type="button" class="ml-2 text-red-500 hover:text-red-700 remove-signature">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="flex items-center signature-item mt-2">
                                <input type="text" 
                                       name="signatures[]" 
                                       value="সচিব"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="যেমন: সচিব">
                                <button type="button" class="ml-2 text-red-500 hover:text-red-700 remove-signature">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="addSignature" class="mt-2 text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-plus mr-1"></i> আরেকটি স্বাক্ষর যোগ করুন
                    </button>
                    <p class="mt-1 text-sm text-gray-500">এই সার্টিফিকেটে স্বাক্ষর করার প্রয়োজনীয় কর্মকর্তাদের পদবি যোগ করুন</p>
                </div>

                <!-- ফর্ম ফিল্ড নির্বাচন -->
                <div class="pt-4 border-t">
                    <div class="mb-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">প্রয়োজনীয় ফর্ম ফিল্ড</h3>
                                <p class="text-sm text-gray-600 mt-1">এই সার্টিফিকেটের জন্য আবেদনকারীদের পূরণ করতে হবে এমন ফিল্ড নির্বাচন করুন:</p>
                            </div>
                            <div class="text-sm text-gray-500">
                                <span id="selectedCount">{{ count($selectedFields ?? []) }}</span> টি ফিল্ড নির্বাচিত
                            </div>
                        </div>
                    </div>
                    
                    <!-- ক্যাটাগরি অনুযায়ী ফিল্ড গ্রুপ করুন -->
                    @php
                        // ক্যাটাগরি অনুযায়ী উপলব্ধ ফিল্ড গ্রুপ করুন
                        $groupedFields = [];
                        foreach($availableFields as $fieldKey => $fieldConfig) {
                            $group = $fieldConfig['group'] ?? 'অন্যান্য';
                            if (!isset($groupedFields[$group])) {
                                $groupedFields[$group] = [];
                            }
                            $groupedFields[$group][$fieldKey] = $fieldConfig;
                        }
                    @endphp
                    
                    @foreach($groupedFields as $groupName => $fields)
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-800 mb-3 border-b pb-2">{{ $groupName }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($fields as $fieldKey => $fieldConfig)
                            @php
                                // নিরাপদে চেক করুন ফিল্ড নির্বাচিত কিনা
                                $isSelected = false;
                                if (is_array($selectedFields)) {
                                    $isSelected = in_array($fieldKey, $selectedFields);
                                }
                                // পুরানো ইনপুটও চেক করুন
                                if (old('form_fields') && is_array(old('form_fields'))) {
                                    $isSelected = in_array($fieldKey, old('form_fields'));
                                }
                            @endphp
                            <div class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition duration-150 field-checkbox {{ $isSelected ? 'bg-blue-50 border border-blue-200' : '' }}">
                                <input type="checkbox" 
                                       name="form_fields[]" 
                                       value="{{ $fieldKey }}" 
                                       id="field_{{ $fieldKey }}"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 field-checkbox-input"
                                       {{ $isSelected ? 'checked' : '' }}>
                                <label for="field_{{ $fieldKey }}" class="ml-3 text-sm text-gray-700 cursor-pointer flex-1">
                                    {{ $fieldConfig['label'] }}
                                    <span class="text-xs text-gray-500 ml-1">
                                        ({{ $fieldConfig['type'] }})
                                    </span>
                                    @if($fieldConfig['required'])
                                        <span class="text-xs text-red-500 ml-1">*</span>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                    
                    @error('form_fields')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ফর্ম অ্যাকশন -->
                <div class="flex justify-between pt-6 border-t">
                    <div>
                        <button type="button" 
                                onclick="confirmDelete()"
                                class="px-4 py-2.5 bg-red-50 text-red-600 border border-red-300 rounded-lg hover:bg-red-100 transition duration-200 font-medium flex items-center">
                            <i class="fas fa-trash-alt mr-2"></i> সার্টিফিকেট মুছুন
                        </button>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('super_admin.certificates.index') }}" 
                           class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                            <i class="fas fa-times mr-2"></i> বাতিল করুন
                        </a>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 font-medium flex items-center">
                            <i class="fas fa-save mr-2"></i> সার্টিফিকেট আপডেট করুন
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- মুছে ফেলার নিশ্চিতকরণ মোডাল -->
<div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
            <div class="bg-red-100 p-2 rounded-full mr-3">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">সার্টিফিকেট টাইপ মুছুন</h3>
        </div>
        <p class="text-gray-600 mb-6">আপনি কি নিশ্চিত যে আপনি "{{ $certificate->name }}" মুছতে চান? এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</p>
        <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                বাতিল
            </button>
            <form action="{{ route('super_admin.certificates.destroy', $certificate->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                    মুছুন
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('additional_content')
<!-- বর্তমান সার্টিফিকেট বিবরণ -->
<div class="mt-8 bg-gray-50 border border-gray-200 rounded-xl p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
        বর্তমান সার্টিফিকেট বিবরণ
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded-lg border">
            <h4 class="text-sm font-medium text-gray-500 mb-2">মৌলিক তথ্য</h4>
            <ul class="space-y-2">
                <li class="flex justify-between">
                    <span class="text-gray-600">সার্টিফিকেট আইডি:</span>
                    <span class="font-medium">#{{ $certificate->id }}</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">বর্তমান নাম:</span>
                    <span class="font-medium">{{ $certificate->name }}</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">বর্তমান ফি:</span>
                    <span class="font-medium text-green-600">৳{{ number_format($certificate->fee, 2) }}</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">বর্তমান মেয়াদ:</span>
                    <span class="font-medium">
                        @if($certificate->validity == 'yearly')
                            <span class="text-green-600">বার্ষিক নবায়ন</span>
                            @if($certificate->validity_days)
                                <span class="text-gray-500 text-sm">({{ $certificate->validity_days }} দিন)</span>
                            @endif
                        @else
                            <span class="text-gray-600">সীমাহীন</span>
                        @endif
                    </span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">সিরিয়াল প্রিফিক্স:</span>
                    <span class="font-medium">{{ $certificate->serial_prefix ?? 'সেট করা নেই' }}</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">অবস্থা:</span>
                    <span class="font-medium">
                        @if($certificate->status == 'active')
                            <span class="text-green-600">সক্রিয়</span>
                        @else
                            <span class="text-red-600">নিষ্ক্রিয়</span>
                        @endif
                    </span>
                </li>
            </ul>
        </div>
        <div class="bg-white p-4 rounded-lg border">
            <h4 class="text-sm font-medium text-gray-500 mb-2">বর্তমান বিবরণ</h4>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-600 text-sm mb-1">বর্তমান স্বাক্ষর:</p>
                    <div class="flex flex-wrap gap-2">
                        @php
                            // FIXED: স্বাক্ষরের জন্য নিরাপদ ডিকোড
                            $currentSignatures = [];
                            if (!empty($certificate->signatures)) {
                                if (is_array($certificate->signatures)) {
                                    $currentSignatures = $certificate->signatures;
                                } elseif (is_string($certificate->signatures)) {
                                    $currentSignatures = json_decode($certificate->signatures, true) ?? [];
                                }
                            }
                        @endphp
                        @if(is_array($currentSignatures) && count($currentSignatures) > 0)
                            @foreach($currentSignatures as $signature)
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">
                                    {{ $signature }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-gray-500 text-sm">কোনো স্বাক্ষর সেট করা নেই</span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-gray-600 text-sm mb-1">নির্বাচিত ফিল্ড:</p>
                    <div class="flex flex-wrap gap-2">
                        @php
                            // FIXED: ফর্ম ফিল্ডের জন্য নিরাপদ ডিকোড
                            $currentFormFields = [];
                            if (!empty($certificate->form_fields)) {
                                if (is_array($certificate->form_fields)) {
                                    $currentFormFields = $certificate->form_fields;
                                } elseif (is_string($certificate->form_fields)) {
                                    $currentFormFields = json_decode($certificate->form_fields, true) ?? [];
                                }
                            }
                        @endphp
                        @if(is_array($currentFormFields) && count($currentFormFields) > 0)
                            @foreach($currentFormFields as $field)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    {{ $field['label'] ?? $field['name'] ?? '' }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-gray-500 text-sm">কোনো ফিল্ড নির্বাচিত নেই</span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-gray-600 text-sm mb-1">তৈরির তারিখ:</p>
                    <div class="flex items-center">
                        <i class="fas fa-calendar text-gray-400 mr-2"></i>
                        <span class="text-gray-700">{{ $certificate->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>
                <div>
                    <p class="text-gray-600 text-sm mb-1">সর্বশেষ আপডেট:</p>
                    <div class="flex items-center">
                        <i class="fas fa-history text-gray-400 mr-2"></i>
                        <span class="text-gray-700">{{ $certificate->updated_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ফর্ম ফিল্ড চেকবক্স হ্যান্ডেল করুন
        const checkboxes = document.querySelectorAll('.field-checkbox-input');
        const selectedCount = document.getElementById('selectedCount');
        
        function updateSelectedCount() {
            const checkedBoxes = document.querySelectorAll('.field-checkbox-input:checked');
            selectedCount.textContent = checkedBoxes.length;
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const parentDiv = this.closest('.field-checkbox');
                if (this.checked) {
                    parentDiv.classList.add('border', 'border-blue-300', 'bg-blue-50');
                } else {
                    parentDiv.classList.remove('border', 'border-blue-300', 'bg-blue-50');
                }
                updateSelectedCount();
            });
            
            // প্রাথমিক অবস্থা
            if (checkbox.checked) {
                checkbox.closest('.field-checkbox').classList.add('border', 'border-blue-300', 'bg-blue-50');
            }
        });
        
        // প্রাথমিক সংখ্যা আপডেট
        updateSelectedCount();

        // মেয়াদ নির্বাচন হ্যান্ডেল করুন
        const validitySelect = document.getElementById('validity');
        const validityDaysSection = document.getElementById('validityDaysSection');
        
        if (validitySelect) {
            // প্রাথমিক অবস্থা
            toggleValidityDays(validitySelect.value);
            
            validitySelect.addEventListener('change', function() {
                toggleValidityDays(this.value);
            });
        }

        function toggleValidityDays(validity) {
            if (validity === 'yearly') {
                validityDaysSection.classList.remove('hidden');
                document.getElementById('validity_days').setAttribute('required', 'required');
            } else {
                validityDaysSection.classList.add('hidden');
                document.getElementById('validity_days').removeAttribute('required');
            }
        }

        // স্বাক্ষর ব্যবস্থাপনা হ্যান্ডেল করুন
        const signaturesContainer = document.getElementById('signaturesContainer');
        const addSignatureBtn = document.getElementById('addSignature');

        // স্বাক্ষর সরান
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-signature')) {
                const signatureItem = e.target.closest('.signature-item');
                if (signatureItem && signaturesContainer.children.length > 1) {
                    signatureItem.remove();
                }
            }
        });

        // নতুন স্বাক্ষর যোগ করুন
        if (addSignatureBtn) {
            addSignatureBtn.addEventListener('click', function() {
                const newSignature = document.createElement('div');
                newSignature.className = 'flex items-center signature-item mt-2';
                newSignature.innerHTML = `
                    <input type="text" 
                           name="signatures[]" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="যেমন: কর্মকর্তার নাম">
                    <button type="button" class="ml-2 text-red-500 hover:text-red-700 remove-signature">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                signaturesContainer.appendChild(newSignature);
            });
        }

        // ফর্ম ভ্যালিডেশন
        const form = document.getElementById('certificateForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // চেক করুন অন্তত একটি ফর্ম ফিল্ড নির্বাচিত কিনা
                const formFields = document.querySelectorAll('.field-checkbox-input:checked');
                if (formFields.length === 0) {
                    e.preventDefault();
                    alert('দয়া করে অন্তত একটি ফর্ম ফিল্ড নির্বাচন করুন।');
                    return false;
                }
                
                // চেক করুন মেয়াদ দিন প্রয়োজনীয় কিনা
                if (validitySelect && validitySelect.value === 'yearly') {
                    const validityDays = document.getElementById('validity_days');
                    if (!validityDays.value) {
                        e.preventDefault();
                        alert('দয়া করে দিনে মেয়াদকাল লিখুন।');
                        validityDays.focus();
                        return false;
                    }
                }
                
                return true;
            });
        }
    });

    // মুছে ফেলার মোডাল ফাংশন
    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush