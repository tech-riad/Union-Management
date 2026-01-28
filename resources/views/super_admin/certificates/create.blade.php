@extends('layouts.super-admin')

@section('title', 'সার্টিফিকেট তৈরি করুন - সুপার অ্যাডমিন')

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
        <span class="text-sm font-medium text-gray-500">নতুন তৈরি করুন</span>
    </li>
@endsection

@section('page_title', 'নতুন সার্টিফিকেট টাইপ তৈরি করুন')
@section('page_description', 'সিস্টেমে একটি নতুন সার্টিফিকেট টেমপ্লেট যোগ করুন')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('super_admin.certificates.store') }}" method="POST">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- ফর্ম হেডার -->
            <div class="px-6 py-4 border-b bg-gradient-to-r from-blue-50 to-blue-100">
                <h2 class="text-lg font-bold text-gray-800">সার্টিফিকেট বিবরণ</h2>
                <p class="text-gray-600 text-sm">নতুন সার্টিফিকেট টাইপের জন্য মৌলিক তথ্য পূরণ করুন</p>
            </div>

            <!-- ফর্ম বডি -->
            <div class="p-6 space-y-6">
                <!-- ত্রুটি বার্তা -->
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
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

                <!-- সার্টিফিকেট নাম -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        সার্টিফিকেটের নাম <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="যেমন: চরিত্র সনদ">
                    @error('name')
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
                            <option value="{{ $template }}" {{ old('template') == $template ? 'selected' : '' }}>
                                {{ $template }}
                            </option>
                        @endforeach
                    </select>
                    @error('template')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ফি -->
                <div>
                    <label for="fee" class="block text-sm font-medium text-gray-700 mb-2">
                        ফি (টাকা) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="fee" 
                           id="fee"
                           value="{{ old('fee') }}"
                           required
                           min="0"
                           step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fee') border-red-500 @enderror"
                           placeholder="যেমন: ২০০.০০">
                    @error('fee')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- মেয়াদ -->
                <div>
                    <label for="validity" class="block text-sm font-medium text-gray-700 mb-2">
                        মেয়াদ <span class="text-red-500">*</span>
                    </label>
                    <select name="validity" 
                            id="validity"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('validity') border-red-500 @enderror">
                        <option value="none" {{ old('validity') == 'none' ? 'selected' : '' }}>সীমাহীন</option>
                        <option value="yearly" {{ old('validity') == 'yearly' ? 'selected' : '' }}>বার্ষিক নবায়ন</option>
                    </select>
                    @error('validity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- মেয়াদ দিন (যদি বার্ষিক হয়) -->
                <div id="validityDaysSection" style="display: none;">
                    <label for="validity_days" class="block text-sm font-medium text-gray-700 mb-2">
                        মেয়াদকাল (দিন) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="validity_days" 
                           id="validity_days"
                           value="{{ old('validity_days') }}"
                           min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('validity_days') border-red-500 @enderror"
                           placeholder="যেমন: ৩৬৫ দিন">
                    <p class="mt-1 text-sm text-gray-500">এই সার্টিফিকেটটি কতদিন বৈধ থাকবে (যেমন: বার্ষিকের জন্য ৩৬৫)</p>
                    @error('validity_days')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- সিরিয়াল প্রিফিক্স -->
                <div>
                    <label for="serial_prefix" class="block text-sm font-medium text-gray-700 mb-2">
                        সার্টিফিকেট সিরিয়াল প্রিফিক্স
                    </label>
                    <input type="text" 
                           name="serial_prefix" 
                           id="serial_prefix"
                           value="{{ old('serial_prefix') }}"
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
                    <label for="signatures" class="block text-sm font-medium text-gray-700 mb-2">
                        প্রয়োজনীয় স্বাক্ষর
                    </label>
                    <div class="space-y-2" id="signaturesContainer">
                        @php
                            $signatures = old('signatures', ['চেয়ারম্যান', 'সচিব']);
                        @endphp
                        
                        @if(is_array($signatures) && count($signatures) > 0)
                            @foreach($signatures as $index => $signature)
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
                                <span id="selectedCount">0</span> টি ফিল্ড নির্বাচিত
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
                                // পুরানো ইনপুট চেক করুন
                                $isSelected = false;
                                if (old('form_fields') && is_array(old('form_fields'))) {
                                    $isSelected = in_array($fieldKey, old('form_fields'));
                                }
                            @endphp
                            <div class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition duration-150 {{ $isSelected ? 'bg-blue-50 border border-blue-200' : '' }}">
                                <input type="checkbox" 
                                       name="form_fields[]" 
                                       value="{{ $fieldKey }}" 
                                       id="field_{{ $fieldKey }}"
                                       class="field-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
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
                                @if($isSelected)
                                    <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                        <i class="fas fa-check mr-1"></i> নির্বাচিত
                                    </span>
                                @endif
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
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('super_admin.certificates.index') }}" 
                       class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                        <i class="fas fa-times mr-2"></i> বাতিল করুন
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 font-medium flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i> সার্টিফিকেট তৈরি করুন
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('additional_content')
<!-- দ্রুত টিপস -->
<div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
        দ্রুত টিপস
    </h3>
    <ul class="space-y-2 text-sm text-gray-700">
        <li class="flex items-start">
            <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
            <span>সার্টিফিকেট টাইপের ভিত্তিতে প্রাসঙ্গিক ফর্ম ফিল্ড নির্বাচন করুন</span>
        </li>
        <li class="flex items-start">
            <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
            <span>বার্ষিক মেয়াদ শেষ হওয়া সার্টিফিকেটের জন্য "বার্ষিক নবায়ন" নির্বাচন করুন</span>
        </li>
        <li class="flex items-start">
            <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
            <span>সরকারি রেট অনুযায়ী উপযুক্ত ফি নির্ধারণ করুন</span>
        </li>
        <li class="flex items-start">
            <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
            <span>সার্টিফিকেট যাচাইকরণের জন্য প্রয়োজনীয় স্বাক্ষর যোগ করুন</span>
        </li>
        <li class="flex items-start">
            <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
            <span>সার্টিফিকেট ট্র্যাকিংয়ের জন্য অর্থপূর্ণ সিরিয়াল প্রিফিক্স সেট করুন</span>
        </li>
    </ul>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ফর্ম ফিল্ড চেকবক্স হ্যান্ডেল করুন
        const checkboxes = document.querySelectorAll('.field-checkbox');
        
        function updateSelectedCount() {
            const selected = document.querySelectorAll('.field-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = selected;
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const parentDiv = this.closest('.flex');
                if (this.checked) {
                    parentDiv.classList.add('border', 'border-blue-300', 'bg-blue-50');
                } else {
                    parentDiv.classList.remove('border', 'border-blue-300', 'bg-blue-50');
                }
                updateSelectedCount();
            });
            
            // প্রাথমিক অবস্থা
            if (checkbox.checked) {
                checkbox.closest('.flex').classList.add('border', 'border-blue-300', 'bg-blue-50');
            }
        });

        // প্রাথমিক সংখ্যা
        updateSelectedCount();

        // মেয়াদ নির্বাচন হ্যান্ডেল করুন
        const validitySelect = document.getElementById('validity');
        const validityDaysSection = document.getElementById('validityDaysSection');
        
        if (validitySelect) {
            // প্রাথমিক মান চেক করুন
            if (validitySelect.value === 'yearly' || '{{ old("validity") }}' === 'yearly') {
                validityDaysSection.style.display = 'block';
                document.getElementById('validity_days').required = true;
            }
            
            validitySelect.addEventListener('change', function() {
                if (this.value === 'yearly') {
                    validityDaysSection.style.display = 'block';
                    document.getElementById('validity_days').required = true;
                } else {
                    validityDaysSection.style.display = 'none';
                    document.getElementById('validity_days').required = false;
                }
            });
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

        // টেমপ্লেটের ভিত্তিতে ডিফল্ট ফিল্ড স্বয়ংক্রিয়ভাবে নির্বাচন করুন
        const templateSelect = document.getElementById('template');
        if (templateSelect) {
            templateSelect.addEventListener('change', function() {
                const template = this.value;
                
                // টেমপ্লেট থেকে ডিফল্ট ফিল্ড ম্যাপিং
                const templateDefaults = {
                    'নাগরিকত্ব সনদ': ['name_bangla', 'father_name_bangla', 'mother_name_bangla', 
                                     'permanent_address_bangla', 'nid_number', 'dob'],
                    'ট্রেড লাইসেন্স': ['name_bangla', 'business_name_bangla', 'business_address_bangla', 
                                      'nid_number'],
                    'ওয়ারিশান সনদ': ['name_bangla', 'father_name_bangla', 'mother_name_bangla',
                                    'permanent_address_bangla'],
                    'ভূমিহীন সনদ': ['name_bangla', 'father_name_bangla', 'permanent_address_bangla',
                                   'nid_number'],
                    'পারিবারিক সনদ': ['name_bangla', 'father_name_bangla', 'mother_name_bangla',
                                     'wife_name_bangla', 'permanent_address_bangla'],
                    'অবিবাহিত সনদ': ['name_bangla', 'father_name_bangla', 'mother_name_bangla',
                                    'permanent_address_bangla', 'dob'],
                    'পুনর্বিবাহ না হওয়া সনদ': ['name_bangla', 'father_name_bangla', 'permanent_address_bangla'],
                    'একই নামের প্রত্যয়ন': ['name_bangla', 'father_name_bangla', 'permanent_address_bangla',
                                         'nid_number'],
                    'প্রতিবন্ধী সনদপত্র': ['name_bangla', 'father_name_bangla', 'permanent_address_bangla',
                                          'nid_number', 'dob'],
                    'অর্থনৈতিক অসচ্ছলতার সনদপত্র': ['name_bangla', 'father_name_bangla', 'permanent_address_bangla',
                                                   'nid_number'],
                    'বিবাহিত সনদ': ['name_bangla', 'father_name_bangla', 'wife_name_bangla',
                                   'permanent_address_bangla'],
                    'দ্বিতীয় বিবাহের অনুমতি পত্র': ['name_bangla', 'father_name_bangla', 'permanent_address_bangla'],
                    'নতুন ভোটার প্রত্যয়ন': ['name_bangla', 'father_name_bangla', 'mother_name_bangla',
                                           'permanent_address_bangla', 'dob'],
                    'জাতীয়তা সনদ': ['name_bangla', 'father_name_bangla', 'mother_name_bangla',
                                   'permanent_address_bangla', 'nid_number', 'dob'],
                    'এতিম সনদ': ['name_bangla', 'permanent_address_bangla', 'dob'],
                    'মাসিক আয়ের সনদ': ['name_bangla', 'father_name_bangla', 'permanent_address_bangla',
                                     'nid_number']
                };

                // সমস্ত চেকবক্স রিসেট করুন
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    const parentDiv = checkbox.closest('.flex');
                    parentDiv.classList.remove('border', 'border-blue-300', 'bg-blue-50');
                });

                // টেমপ্লেটের জন্য ডিফল্ট ফিল্ড নির্বাচন করুন
                if (templateDefaults[template]) {
                    templateDefaults[template].forEach(fieldKey => {
                        const checkbox = document.getElementById(`field_${fieldKey}`);
                        if (checkbox) {
                            checkbox.checked = true;
                            const parentDiv = checkbox.closest('.flex');
                            parentDiv.classList.add('border', 'border-blue-300', 'bg-blue-50');
                        }
                    });
                }

                updateSelectedCount();
            });
        }
    });
</script>
@endpush