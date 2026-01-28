@extends('layouts.super-admin')

@section('title', 'সার্টিফিকেট তালিকা - সুপার অ্যাডমিন')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400"></i>
    </li>
    <li class="inline-flex items-center">
        <span class="text-sm font-medium text-gray-500">সার্টিফিকেট টাইপ</span>
    </li>
@endsection

@section('page_title', 'সার্টিফিকেট টাইপ ব্যবস্থাপনা')
@section('page_description', 'সমস্ত সার্টিফিকেট টেমপ্লেট ব্যবস্থাপনা করুন')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- হেডার -->
    <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-blue-50 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-gray-800">সার্টিফিকেট টাইপ</h2>
            <p class="text-gray-600 text-sm mt-1">মোট: {{ $certificates->count() }} টি সার্টিফিকেট</p>
        </div>
        <a href="{{ route('super_admin.certificates.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center shadow">
            <i class="fas fa-plus-circle mr-2"></i> নতুন সার্টিফিকেট তৈরি করুন
        </a>
    </div>

    <!-- সফল বার্তা -->
    @if(session('success'))
        <div class="mx-6 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded">
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

    <!-- টেবিল -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">আইডি</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">নাম</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ফি (টাকা)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">টেমপ্লেট</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">মেয়াদ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">কার্যক্রম</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($certificates as $certificate)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-medium">#{{ $certificate->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $certificate->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                @php
                                    // FIX: সেফলি ফর্ম ফিল্ড কাউন্ট করুন
                                    $formFieldsCount = 0;
                                    if (!empty($certificate->form_fields)) {
                                        if (is_array($certificate->form_fields)) {
                                            $formFieldsCount = count($certificate->form_fields);
                                        } elseif (is_string($certificate->form_fields)) {
                                            $decoded = json_decode($certificate->form_fields, true);
                                            if (is_array($decoded)) {
                                                $formFieldsCount = count($decoded);
                                            }
                                        }
                                    }
                                @endphp
                                ফিল্ড: {{ $formFieldsCount }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-green-600">৳{{ number_format($certificate->fee, 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-900">{{ $certificate->template }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($certificate->validity == 'yearly')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-calendar-check mr-1"></i> বার্ষিক
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-infinity mr-1"></i> সীমাহীন
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('super_admin.certificates.edit', $certificate->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition duration-200 flex items-center">
                                    <i class="fas fa-edit mr-1"></i> সম্পাদনা
                                </a>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('super_admin.certificates.destroy', $certificate->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('আপনি কি নিশ্চিত যে আপনি এই সার্টিফিকেট টাইপটি মুছতে চান?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition duration-200 flex items-center">
                                        <i class="fas fa-trash-alt mr-1"></i> মুছুন
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="mb-4">
                                <i class="fas fa-certificate text-gray-300 text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">কোন সার্টিফিকেট টাইপ পাওয়া যায়নি</h3>
                            <p class="text-gray-500 mb-4">আপনার প্রথম সার্টিফিকেট টাইপ তৈরি করে শুরু করুন।</p>
                            <a href="{{ route('super_admin.certificates.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i> প্রথম সার্টিফিকেট তৈরি করুন
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- পরিসংখ্যান সহ ফুটার -->
    @if($certificates->count() > 0)
    <div class="px-6 py-4 border-t bg-gray-50">
        <div class="flex justify-between items-center text-sm text-gray-600">
            <div>
                {{ $certificates->count() }} টি সার্টিফিকেট দেখানো হচ্ছে
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                    <span>বার্ষিক: {{ $certificates->where('validity', 'yearly')->count() }}</span>
                </div>
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-gray-500 rounded-full mr-2"></div>
                    <span>সীমাহীন: {{ $certificates->where('validity', 'none')->count() }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection