@extends('layouts.super-admin')

@section('title', 'ব্যাকআপ ব্যবস্থাপনা')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-database mr-2 text-blue-600"></i>
                    ডাটাবেস ব্যাকআপ
                </h2>
                <p class="text-gray-600 mt-1">
                    আপনার ডাটাবেসের নিরাপদ রাখুন এবং যেকোনো সময় রিস্টোর করতে পারেন
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                <button type="button" onclick="showBackupModal()" 
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    নতুন ব্যাকআপ
                </button>
                
                <a href="{{ route('super_admin.settings.backup') }}" 
                   class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-cog mr-2"></i>
                    ব্যাকআপ সেটিংস
                </a>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Backups Card -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-700 font-medium">মোট ব্যাকআপ</p>
                    <p class="text-3xl font-bold text-blue-800 mt-2">{{ $backups ? count($backups) : 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-database text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-700">
                <i class="fas fa-info-circle mr-1"></i>
                <span>সকল ব্যাকআপ ফাইল</span>
            </div>
        </div>
        
        <!-- Total Size Card -->
        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-700 font-medium">মোট সাইজ</p>
                    <p class="text-3xl font-bold text-green-800 mt-2">
                        @if($backups && count($backups) > 0)
                            {{ number_format(array_sum(array_column($backups, 'size_bytes')) / 1048576, 2) }} MB
                        @else
                            0 MB
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hdd text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-700">
                <i class="fas fa-server mr-1"></i>
                <span>সকল ব্যাকআপের সাইজ</span>
            </div>
        </div>
        
        <!-- Database Size Card -->
        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-700 font-medium">ডাটাবেস সাইজ</p>
                    <p class="text-3xl font-bold text-purple-800 mt-2">{{ $dbInfo['size'] ?? 0 }} MB</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-database text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-purple-700">
                <i class="fas fa-database mr-1"></i>
                <span>{{ $dbInfo['name'] ?? 'ডাটাবেস' }}</span>
            </div>
        </div>
        
        <!-- Last Backup Card -->
        <div class="bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-amber-700 font-medium">সর্বশেষ ব্যাকআপ</p>
                    <p class="text-2xl font-bold text-amber-800 mt-2">
                        @if($backups && count($backups) > 0)
                            {{ \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $backups[0]['modified'])->diffForHumans() }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-amber-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-amber-700">
                <i class="fas fa-calendar-check mr-1"></i>
                <span>
                    @if($backups && count($backups) > 0)
                        {{ $backups[0]['modified'] }}
                    @else
                        কোনো ব্যাকআপ নেই
                    @endif
                </span>
            </div>
        </div>
    </div>
    
    <!-- Storage Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Storage Usage -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class="fas fa-hdd mr-2 text-blue-600"></i>
                    স্টোরেজ ব্যবহার
                </h3>
            </div>
            <div class="p-6">
                @if($storageInfo)
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">ব্যবহৃত স্টোরেজ</span>
                            <span class="text-sm font-bold text-gray-900">{{ $storageInfo['used_percentage'] ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full" 
                                 style="width: {{ min($storageInfo['used_percentage'], 100) }}%"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-4 text-center">
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-3">
                                <p class="text-xs text-blue-700">মোট</p>
                                <p class="font-bold text-blue-800">{{ $storageInfo['total'] ?? 0 }} GB</p>
                            </div>
                            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3">
                                <p class="text-xs text-green-700">ব্যবহৃত</p>
                                <p class="font-bold text-green-800">{{ $storageInfo['used'] ?? 0 }} GB</p>
                            </div>
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3">
                                <p class="text-xs text-gray-700">ফ্রি</p>
                                <p class="font-bold text-gray-800">{{ $storageInfo['free'] ?? 0 }} GB</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-3xl text-yellow-500 mb-3"></i>
                    <p class="text-gray-600">স্টোরেজ তথ্য পাওয়া যায়নি</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Backup Configuration -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class="fas fa-cogs mr-2 text-blue-600"></i>
                    ব্যাকআপ কনফিগারেশন
                </h3>
            </div>
            <div class="p-6">
                @if($backupConfig)
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-robot text-xl text-blue-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-800">অটো ব্যাকআপ</p>
                                <p class="text-sm text-gray-600">স্বয়ংক্রিয় ব্যাকআপ সিস্টেম</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $backupConfig['auto_backup'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $backupConfig['auto_backup'] ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt text-xl text-green-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-800">ব্যাকআপ সময়সূচী</p>
                                <p class="text-sm text-gray-600">ব্যাকআপের ফ্রিকোয়েন্সি</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">
                            {{ ucfirst($backupConfig['backup_schedule'] ?? 'daily') }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-history text-xl text-purple-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-800">রিটেনশন সময়</p>
                                <p class="text-sm text-gray-600">ব্যাকআপ রাখার সময়সীমা</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-bold">
                            {{ $backupConfig['retention_days'] ?? 30 }} দিন
                        </span>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-3xl text-yellow-500 mb-3"></i>
                    <p class="text-gray-600">কনফিগারেশন তথ্য পাওয়া যায়নি</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Backup Files Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center">
                <i class="fas fa-folder mr-2 text-blue-600"></i>
                ব্যাকআপ ফাইলসমূহ
            </h3>
            <div class="flex items-center space-x-2">
                <button onclick="uploadBackup()" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg text-sm transition">
                    <i class="fas fa-upload mr-2"></i> আপলোড
                </button>
                <input type="text" id="searchBackup" placeholder="ব্যাকআপ খুঁজুন..." 
                       class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       onkeyup="filterBackups()">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full" id="backupTable">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">
                            <label class="flex items-center">
                                <input type="checkbox" id="selectAll" class="mr-2 rounded border-gray-300" onchange="toggleSelectAll()">
                                ফাইল নাম
                            </label>
                        </th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">সাইজ</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">সর্বশেষ পরিবর্তন</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">টাইপ</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">কার্যকলাপ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="backupTableBody">
                    @if($backups && count($backups) > 0)
                        @foreach($backups as $index => $backup)
                        <tr class="hover:bg-gray-50 transition" data-filename="{{ $backup['filename'] }}">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="selected_backups[]" value="{{ $backup['filename'] }}" 
                                           class="mr-3 rounded border-gray-300 backup-checkbox" onchange="updateSelectedCount()">
                                    <i class="fas fa-file-archive text-xl text-blue-500 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-gray-800 backup-filename">{{ $backup['filename'] }}</p>
                                        <p class="text-xs text-gray-500">
                                            পাথ: {{ str_replace(storage_path('app/backups/'), '', $backup['path']) }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-weight-hanging mr-1"></i>
                                    {{ $backup['size'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <i class="far fa-clock text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-700">{{ $backup['modified'] }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $type = str_contains(strtolower($backup['filename']), 'full') ? 'full' : 
                                            (str_contains(strtolower($backup['filename']), 'database') ? 'database' : 'partial');
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $type == 'full' ? 'bg-green-100 text-green-800' : 
                                       ($type == 'database' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    @if($type == 'full')
                                        <i class="fas fa-database mr-1"></i> পূর্ণ ব্যাকআপ
                                    @elseif($type == 'database')
                                        <i class="fas fa-table mr-1"></i> ডাটাবেস
                                    @else
                                        <i class="fas fa-file mr-1"></i> আংশিক
                                    @endif
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-2">
                                    <button onclick="downloadBackup('{{ $backup['filename'] }}')" 
                                            class="px-3 py-1.5 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 text-blue-700 rounded-lg text-sm transition flex items-center"
                                            title="ডাউনলোড">
                                        <i class="fas fa-download text-sm mr-1"></i>
                                        ডাউনলোড
                                    </button>
                                    
                                    <button onclick="showRestoreModal('{{ $backup['filename'] }}')" 
                                            class="px-3 py-1.5 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 text-green-700 rounded-lg text-sm transition flex items-center"
                                            title="রিস্টোর">
                                        <i class="fas fa-redo text-sm mr-1"></i>
                                        রিস্টোর
                                    </button>
                                    
                                    <button onclick="deleteBackup('{{ $backup['filename'] }}')" 
                                            class="px-3 py-1.5 bg-gradient-to-r from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 text-red-700 rounded-lg text-sm transition flex items-center"
                                            title="ডিলিট">
                                        <i class="fas fa-trash text-sm mr-1"></i>
                                        ডিলিট
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-600 mb-2">কোনো ব্যাকআপ ফাইল পাওয়া যায়নি</p>
                                    <button onclick="showBackupModal()" 
                                            class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition">
                                        প্রথম ব্যাকআপ তৈরি করুন
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($backups && count($backups) > 0)
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <p class="text-sm text-gray-600">
                    মোট {{ count($backups) }} টি ব্যাকআপ ফাইল
                </p>
                <div id="selectedCount" class="hidden">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                        <span id="selectedNumber">0</span> টি নির্বাচিত
                    </span>
                    <button onclick="downloadSelected()" class="ml-2 px-3 py-1 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg text-sm">
                        <i class="fas fa-download mr-1"></i> নির্বাচিত ডাউনলোড
                    </button>
                    <button onclick="deleteSelected()" class="ml-2 px-3 py-1 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg text-sm">
                        <i class="fas fa-trash mr-1"></i> নির্বাচিত ডিলিট
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Backup Modal -->
<div id="backupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 text-lg flex items-center">
                    <i class="fas fa-plus-circle mr-2 text-blue-600"></i>
                    নতুন ব্যাকআপ তৈরি করুন
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Modal Form -->
            <form id="backupForm" method="POST" action="{{ route('super_admin.reports.create-backup') }}">
                @csrf
                <div class="p-6 space-y-4">
                    <!-- Backup Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ব্যাকআপ টাইপ</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="type" value="full" class="mr-2 text-blue-600" checked>
                                <div>
                                    <p class="font-medium text-gray-800">পূর্ণ ব্যাকআপ</p>
                                    <p class="text-xs text-gray-600">সমস্ত ডাটা ব্যাকআপ</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="type" value="database" class="mr-2 text-blue-600">
                                <div>
                                    <p class="font-medium text-gray-800">ডাটাবেস</p>
                                    <p class="text-xs text-gray-600">শুধু ডাটাবেস ব্যাকআপ</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            বর্ণনা (ঐচ্ছিক)
                        </label>
                        <textarea name="description" id="description" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="ব্যাকআপের সংক্ষিপ্ত বর্ণনা..."></textarea>
                    </div>
                    
                    <!-- Estimated Size -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <p class="text-sm font-medium text-blue-800">অনুমানকৃত সাইজ</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-lg font-bold text-blue-900">{{ $dbInfo['size'] ?? 0 }} MB</p>
                                <p class="text-xs text-blue-700">ডাটাবেস সাইজ</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-blue-900">{{ $dbInfo['size'] ? $dbInfo['size'] + 10 : 10 }} MB</p>
                                <p class="text-xs text-blue-700">মোট অনুমান</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        বাতিল করুন
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition shadow-md hover:shadow-lg">
                        <i class="fas fa-database mr-2"></i>
                        ব্যাকআপ তৈরি করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Restore Modal -->
<div id="restoreModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 text-lg flex items-center">
                    <i class="fas fa-redo mr-2 text-green-600"></i>
                    ব্যাকআপ রিস্টোর করুন
                </h3>
                <button onclick="closeRestoreModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-3"></i>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">সতর্কতা!</h4>
                    <p class="text-gray-600 mb-4">
                        আপনি <span id="restoreFileName" class="font-bold"></span> ফাইলটি থেকে ডাটা রিস্টোর করতে চলেছেন। এটি বর্তমান ডাটাবেসের সকল তথ্য প্রতিস্থাপন করবে।
                    </p>
                    <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-skull-crossbones text-red-600 mr-3"></i>
                            <div class="text-left">
                                <p class="font-bold text-red-800 text-sm">এই অপারেশন পূর্বাবস্থায় ফেরানো যাবে না</p>
                                <p class="text-red-700 text-xs">দয়া করে নিশ্চিত করুন যে আপনি একটি সম্পূর্ণ ব্যাকআপ তৈরি করেছেন</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Confirmation -->
                <div class="mb-6">
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg bg-gray-50">
                        <input type="checkbox" id="confirmRestore" class="mr-2 text-red-600">
                        <span class="text-sm text-gray-700">আমি বুঝতে পেরেছি যে এই অপারেশন পূর্বাবস্থায় ফেরানো যাবে না এবং আমি আমার বর্তমান ডাটার একটি সম্পন্ন ব্যাকআপ নিয়েছি</span>
                    </label>
                </div>
                
                <!-- Database Name -->
                <div class="mb-6">
                    <label for="restoreDatabase" class="block text-sm font-medium text-gray-700 mb-2">
                        ডাটাবেস নাম
                    </label>
                    <input type="text" id="restoreDatabase" 
                           value="{{ $dbInfo['name'] ?? '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                           readonly>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeRestoreModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    বাতিল করুন
                </button>
                <button type="button" onclick="startRestore()" id="restoreButton"
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition shadow-md hover:shadow-lg opacity-50 cursor-not-allowed"
                        disabled>
                    <i class="fas fa-redo mr-2"></i>
                    রিস্টোর শুরু করুন
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Backup Modal -->
<div id="uploadBackupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 text-lg flex items-center">
                    <i class="fas fa-upload mr-2 text-blue-600"></i>
                    ব্যাকআপ আপলোড করুন
                </h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors" 
                             id="dropArea">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 mb-2">ব্যাকআপ ফাইল এখানে ড্রপ করুন অথবা</p>
                            <label class="cursor-pointer">
                                <input type="file" name="backup_file" id="backupFile" class="hidden" accept=".sql,.zip,.tar,.gz" onchange="handleFileSelect(this)">
                                <span class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition">
                                    ফাইল নির্বাচন করুন
                                </span>
                            </label>
                            <p class="text-xs text-gray-500 mt-4">সমর্থিত ফরম্যাট: .sql, .zip, .tar, .gz</p>
                        </div>
                        
                        <div id="fileInfo" class="hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800" id="fileName"></p>
                                        <p class="text-sm text-gray-600" id="fileSize"></p>
                                    </div>
                                    <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeUploadModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    বাতিল করুন
                </button>
                <button type="button" onclick="uploadBackupFile()" id="uploadButton"
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition shadow-md hover:shadow-lg opacity-50 cursor-not-allowed"
                        disabled>
                    <i class="fas fa-upload mr-2"></i>
                    আপলোড করুন
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast Template -->
<div id="successToast" class="hidden fixed top-4 right-4 px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-lg z-50">
    <div class="flex items-center space-x-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span id="toastMessage"></span>
    </div>
</div>

<!-- Error Toast Template -->
<div id="errorToast" class="hidden fixed top-4 right-4 px-6 py-4 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl shadow-lg z-50">
    <div class="flex items-center space-x-3">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <span id="errorMessage"></span>
    </div>
</div>

<!-- Info Toast Template -->
<div id="infoToast" class="hidden fixed top-4 right-4 px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg z-50">
    <div class="flex items-center space-x-3">
        <i class="fas fa-info-circle text-xl"></i>
        <span id="infoMessage"></span>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Global variables
    let currentRestoreFile = null;
    let selectedFile = null;

    // Modal functions
    function showBackupModal() {
        document.getElementById('backupModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        document.getElementById('backupModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('backupForm').reset();
    }
    
    function showRestoreModal(filename) {
        document.getElementById('restoreFileName').textContent = filename;
        document.getElementById('restoreModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        currentRestoreFile = filename;
    }
    
    function closeRestoreModal() {
        document.getElementById('restoreModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('confirmRestore').checked = false;
        document.getElementById('restoreButton').disabled = true;
        document.getElementById('restoreButton').classList.add('opacity-50', 'cursor-not-allowed');
        currentRestoreFile = null;
    }
    
    // Upload modal functions
    function uploadBackup() {
        document.getElementById('uploadBackupModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeUploadModal() {
        document.getElementById('uploadBackupModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        clearFile();
    }
    
    // File handling
    function handleFileSelect(input) {
        const file = input.files[0];
        if (file) {
            selectedFile = file;
            showFileInfo(file);
            document.getElementById('uploadButton').disabled = false;
            document.getElementById('uploadButton').classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
    
    function showFileInfo(file) {
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatBytes(file.size);
        document.getElementById('fileInfo').classList.remove('hidden');
    }
    
    function clearFile() {
        document.getElementById('backupFile').value = '';
        document.getElementById('fileInfo').classList.add('hidden');
        selectedFile = null;
        document.getElementById('uploadButton').disabled = true;
        document.getElementById('uploadButton').classList.add('opacity-50', 'cursor-not-allowed');
    }
    
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
    
    // Upload backup file
    function uploadBackupFile() {
        if (!selectedFile) return;
        
        const formData = new FormData();
        formData.append('backup_file', selectedFile);
        formData.append('_token', '{{ csrf_token() }}');
        
        const uploadButton = document.getElementById('uploadButton');
        const originalText = uploadButton.innerHTML;
        
        // Show loading
        uploadButton.disabled = true;
        uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> আপলোড হচ্ছে...';
        showToast('ফাইল আপলোড করা হচ্ছে...', 'info');
        
        fetch('{{ route("super_admin.reports.upload-backup") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('ব্যাকআপ ফাইল সফলভাবে আপলোড হয়েছে');
                closeUploadModal();
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showErrorToast(data.message || 'আপলোড ব্যর্থ হয়েছে');
                uploadButton.disabled = false;
                uploadButton.innerHTML = originalText;
            }
        })
        .catch(error => {
            showErrorToast('নেটওয়ার্ক ত্রুটি: ' + error.message);
            uploadButton.disabled = false;
            uploadButton.innerHTML = originalText;
        });
    }
    
    // Confirm restore checkbox
    document.getElementById('confirmRestore')?.addEventListener('change', function() {
        const restoreButton = document.getElementById('restoreButton');
        if (this.checked) {
            restoreButton.disabled = false;
            restoreButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            restoreButton.disabled = true;
            restoreButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    });
    
    // Backup form submission - FIXED
    document.getElementById('backupForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        // Show loading
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> তৈরি হচ্ছে...';
        showToast('ব্যাকআপ তৈরি করা হচ্ছে...', 'info');
        
        // Use fetch instead of form submission
        const formData = new FormData(form);
        
        fetch('{{ route("super_admin.reports.create-backup") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('ব্যাকআপ সফলভাবে তৈরি হয়েছে!');
                closeModal();
                // Reload page after 2 seconds
                setTimeout(() => window.location.reload(), 2000);
            } else {
                showErrorToast(data.message || 'ব্যাকআপ তৈরি ব্যর্থ হয়েছে');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        })
        .catch(error => {
            showErrorToast('নেটওয়ার্ক ত্রুটি: ' + error.message);
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        });
    });
    
    // Download backup - FIXED SIMPLE VERSION
    function downloadBackup(filename) {
        showToast('ডাউনলোড শুরু হচ্ছে...', 'info');
        
        // Direct download using a simple link
        const downloadUrl = '{{ route("super_admin.reports.download-backup", ["filename" => "__FILENAME__"]) }}'.replace('__FILENAME__', filename);
        
        // Create temporary link and click
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = filename;
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    // Download multiple selected backups
    function downloadSelected() {
        const selected = getSelectedBackups();
        if (selected.length === 0) {
            showErrorToast('দয়া করে অন্তত একটি ব্যাকআপ নির্বাচন করুন');
            return;
        }
        
        if (selected.length === 1) {
            downloadBackup(selected[0]);
            return;
        }
        
        showToast(`${selected.length} টি ব্যাকআপ প্রক্রিয়াধীন...`, 'info');
        
        // For multiple files
        fetch('{{ route("super_admin.reports.download-multiple") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ files: selected })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.download_url) {
                // Open download URL in new tab
                window.open(data.download_url, '_blank');
                showToast('ZIP ফাইল ডাউনলোড শুরু হয়েছে');
            } else {
                showErrorToast(data.message || 'ডাউনলোড ব্যর্থ');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorToast('ডাউনলোড ব্যর্থ: ' + error.message);
        });
    }
    
    // Delete backup - FIXED
    function deleteBackup(filename) {
        if (confirm(`আপনি কি "${filename}" ব্যাকআপ ফাইলটি ডিলিট করতে চান?\n\n⚠️ এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।`)) {
            showToast('ডিলিট করা হচ্ছে...', 'info');
            
            fetch('{{ route("super_admin.reports.delete-backup", ["filename" => "__FILENAME__"]) }}'.replace('__FILENAME__', filename), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('ব্যাকআপ ফাইল সফলভাবে ডিলিট হয়েছে');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showErrorToast(data.message || 'ডিলিট ব্যর্থ হয়েছে');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorToast('নেটওয়ার্ক ত্রুটি: ' + error.message);
            });
        }
    }
    
    // Delete selected backups
    function deleteSelected() {
        const selected = getSelectedBackups();
        if (selected.length === 0) {
            showErrorToast('দয়া করে অন্তত একটি ব্যাকআপ নির্বাচন করুন');
            return;
        }
        
        if (confirm(`আপনি কি ${selected.length} টি ব্যাকআপ ফাইল ডিলিট করতে চান?\n\n⚠️ এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।`)) {
            showToast(`${selected.length} টি ব্যাকআপ ডিলিট করা হচ্ছে...`, 'info');
            
            fetch('{{ route("super_admin.reports.delete-multiple") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ files: selected })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(`${selected.length} টি ব্যাকআপ সফলভাবে ডিলিট হয়েছে`);
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showErrorToast(data.message || 'ডিলিট ব্যর্থ');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorToast('নেটওয়ার্ক ত্রুটি: ' + error.message);
            });
        }
    }
    
    // Start restore process
    function startRestore() {
        if (!currentRestoreFile) return;
        
        if (confirm(`আপনি কি নিশ্চিত যে আপনি "${currentRestoreFile}" ফাইল থেকে ডাটা রিস্টোর করতে চান?\n\n⚠️ এই অপারেশন পূর্বাবস্থায় ফেরানো যাবে না।`)) {
            showToast('রিস্টোর শুরু হয়েছে...', 'info');
            
            fetch('{{ route("super_admin.reports.restore-backup", ["filename" => "__FILENAME__"]) }}'.replace('__FILENAME__', currentRestoreFile), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('ডাটা সফলভাবে রিস্টোর হয়েছে!');
                    closeRestoreModal();
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    showErrorToast(data.message || 'রিস্টোর ব্যর্থ');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorToast('নেটওয়ার্ক ত্রুটি: ' + error.message);
            });
        }
    }
    
    // Checkbox selection handling
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.backup-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        updateSelectedCount();
    }
    
    // Update selected count
    function updateSelectedCount() {
        const selected = getSelectedBackups();
        const selectedCount = document.getElementById('selectedCount');
        const selectedNumber = document.getElementById('selectedNumber');
        
        if (selected.length > 0) {
            selectedNumber.textContent = selected.length;
            selectedCount.classList.remove('hidden');
        } else {
            selectedCount.classList.add('hidden');
        }
        
        // Update select all checkbox
        const checkboxes = document.querySelectorAll('.backup-checkbox');
        const allChecked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb.checked);
        document.getElementById('selectAll').checked = allChecked;
    }
    
    // Get selected backups
    function getSelectedBackups() {
        const checkboxes = document.querySelectorAll('.backup-checkbox:checked');
        return Array.from(checkboxes).map(checkbox => checkbox.value);
    }
    
    // Filter backups
    function filterBackups() {
        const searchTerm = document.getElementById('searchBackup').value.toLowerCase();
        const rows = document.querySelectorAll('#backupTableBody tr');
        
        rows.forEach(row => {
            const filename = row.querySelector('.backup-filename').textContent.toLowerCase();
            if (filename.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Toast functions
    function showToast(message, type = 'success') {
        let toast;
        
        // Remove existing toasts
        document.querySelectorAll('[id$="Toast"]:not([id="successToast"]):not([id="errorToast"]):not([id="infoToast"])').forEach(t => t.remove());
        
        if (type === 'success') {
            toast = document.getElementById('successToast').cloneNode(true);
            toast.id = 'toast-' + Date.now();
            toast.querySelector('#toastMessage').textContent = message;
        } else if (type === 'error') {
            toast = document.getElementById('errorToast').cloneNode(true);
            toast.id = 'toast-' + Date.now();
            toast.querySelector('#errorMessage').textContent = message;
        } else {
            toast = document.getElementById('infoToast').cloneNode(true);
            toast.id = 'toast-' + Date.now();
            toast.querySelector('#infoMessage').textContent = message;
        }
        
        toast.classList.remove('hidden');
        document.body.appendChild(toast);
        
        // Position toast
        toast.style.position = 'fixed';
        toast.style.top = '1rem';
        toast.style.right = '1rem';
        toast.style.zIndex = '9999';
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }, 3000);
    }
    
    function showErrorToast(message) {
        showToast(message, 'error');
    }
    
    // Drag and drop for upload
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('dropArea');
        if (dropArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                dropArea.classList.add('border-blue-400', 'bg-blue-50');
            }
            
            function unhighlight() {
                dropArea.classList.remove('border-blue-400', 'bg-blue-50');
            }
            
            dropArea.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    const file = files[0];
                    
                    // Check file type
                    const validTypes = ['.sql', '.zip', '.tar', '.gz'];
                    const fileExt = '.' + file.name.split('.').pop().toLowerCase();
                    
                    if (!validTypes.includes(fileExt)) {
                        showErrorToast('অসমর্থিত ফাইল ফরম্যাট। শুধুমাত্র .sql, .zip, .tar, .gz ফাইল সাপোর্টেড।');
                        return;
                    }
                    
                    selectedFile = file;
                    showFileInfo(file);
                    document.getElementById('uploadButton').disabled = false;
                    document.getElementById('uploadButton').classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        }
        
        // Initialize checkboxes
        const checkboxes = document.querySelectorAll('.backup-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
    });
</script>
@endsection