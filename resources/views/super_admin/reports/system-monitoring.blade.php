@extends('layouts.super-admin')

@section('title', 'সিস্টেম মনিটরিং - সুপার অ্যাডমিন')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-desktop text-blue-600 mr-3"></i>
                    সিস্টেম মনিটরিং
                </h1>
                <p class="text-gray-600">সিস্টেম স্বাস্থ্য, পারফরম্যান্স এবং রিসোর্স মনিটরিং</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <button onclick="refreshSystemStatus()" 
                        class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-700 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i>
                    রিফ্রেশ
                </button>
                <button onclick="exportSystemReport()"
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-file-export mr-2"></i>
                    রিপোর্ট এক্সপোর্ট
                </button>
            </div>
        </div>
    </div>

    <!-- System Status Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Overall Status -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-heartbeat text-red-500 mr-3"></i>
                    সিস্টেম স্ট্যাটাস
                </h3>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                    <i class="fas fa-check-circle mr-1"></i>
                    Active
                </span>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-gray-700">সার্ভার স্ট্যাটাস</span>
                    </div>
                    <span class="font-bold text-green-600">Online</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-gray-700">ডাটাবেস কানেকশন</span>
                    </div>
                    <span class="font-bold text-green-600">{{ $dbInfo['connection_status'] }}</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-gray-700">ক্যাশে স্ট্যাটাস</span>
                    </div>
                    <span class="font-bold text-green-600">{{ $performance['cache_status'] }}</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-gray-700">কিউ স্ট্যাটাস</span>
                    </div>
                    <span class="font-bold text-green-600">{{ $performance['queue_status'] }}</span>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-tachometer-alt text-blue-500 mr-3"></i>
                পারফরম্যান্স মেট্রিক্স
            </h3>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700">রেসপন্স টাইম</span>
                        <span class="font-bold text-gray-900">{{ $performance['response_time'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700">সিস্টেম আপটাইম</span>
                        <span class="font-bold text-gray-900">{{ $performance['uptime'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 98%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700">মেমোরি ব্যবহার</span>
                        <span class="font-bold text-gray-900">{{ $serverInfo['memory_limit'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 65%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700">এক্সিকিউশন টাইম</span>
                        <span class="font-bold text-gray-900">{{ $serverInfo['max_execution_time'] }}s</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 40%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage Overview -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-hdd text-purple-500 mr-3"></i>
                    স্টোরেজ ব্যবহার
                </h3>
                <span class="text-sm text-gray-500">
                    {{ $storageInfo['used_percentage'] }}% ব্যবহৃত
                </span>
            </div>
            
            <div class="mb-6">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>ডিস্ক স্পেস</span>
                    <span>{{ $storageInfo['used'] }}GB / {{ $storageInfo['total'] }}GB</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-4 rounded-full" 
                         style="width: {{ $storageInfo['used_percentage'] }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-2">
                    <span>ফ্রি: {{ $storageInfo['free'] }}GB</span>
                    <span>ব্যবহৃত: {{ $storageInfo['used'] }}GB</span>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-database text-blue-500 mr-3"></i>
                        <span class="text-gray-700">ডাটাবেস সাইজ</span>
                    </div>
                    <span class="font-bold">{{ $dbInfo['total_size'] }} MB</span>
                </div>
                
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-file text-green-500 mr-3"></i>
                        <span class="text-gray-700">লগ ফাইলস</span>
                    </div>
                    <span class="font-bold">{{ $logFilesSize }} MB</span>
                </div>
                
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-upload text-yellow-500 mr-3"></i>
                        <span class="text-gray-700">ম্যাক্স আপলোড</span>
                    </div>
                    <span class="font-bold">{{ $serverInfo['upload_max_filesize'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed System Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Server Information -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-server text-gray-600 mr-3"></i>
                সার্ভার তথ্য
            </h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-xl">
                        <div class="text-sm text-gray-600 mb-1">PHP ভার্সন</div>
                        <div class="font-bold text-gray-800">{{ $serverInfo['php_version'] }}</div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-xl">
                        <div class="text-sm text-gray-600 mb-1">লারাভেল ভার্সন</div>
                        <div class="font-bold text-gray-800">{{ $serverInfo['laravel_version'] }}</div>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-xl">
                        <div class="text-sm text-gray-600 mb-1">এনভায়রনমেন্ট</div>
                        <div class="font-bold text-gray-800">{{ $serverInfo['environment'] }}</div>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-xl">
                        <div class="text-sm text-gray-600 mb-1">টাইমজোন</div>
                        <div class="font-bold text-gray-800">{{ $serverInfo['timezone'] }}</div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">সার্ভার সফটওয়্যার</span>
                        <span class="font-mono text-sm">{{ $serverInfo['server_software'] }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">ডিবাগ মোড</span>
                        <span class="font-bold {{ $serverInfo['debug_mode'] == 'On' ? 'text-red-600' : 'text-green-600' }}">
                            {{ $serverInfo['debug_mode'] }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">পোস্ট ম্যাক্স সাইজ</span>
                        <span class="font-bold">{{ $serverInfo['post_max_size'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Information -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-database text-blue-600 mr-3"></i>
                ডাটাবেস তথ্য
            </h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-xl">
                        <div class="text-sm text-gray-600 mb-1">ডাটাবেস নাম</div>
                        <div class="font-bold text-gray-800">{{ $dbInfo['database'] }}</div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-xl">
                        <div class="text-sm text-gray-600 mb-1">কানেকশন</div>
                        <div class="font-bold text-gray-800">{{ $dbInfo['connection'] }}</div>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-xl">
                        <div class="text-sm text-gray-600 mb-1">টেবিল সংখ্যা</div>
                        <div class="font-bold text-gray-800">{{ $dbInfo['tables_count'] }}</div>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-xl">
                        <div class="text-sm text-gray-600 mb-1">মোট সাইজ</div>
                        <div class="font-bold text-gray-800">{{ $dbInfo['total_size'] }} MB</div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">সর্বশেষ ব্যাকআপ</span>
                        <span class="font-bold {{ $dbInfo['last_backup'] == 'কখনোই নয়' ? 'text-red-600' : 'text-green-600' }}">
                            {{ $dbInfo['last_backup'] }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">কানেকশন স্ট্যাটাস</span>
                        <span class="font-bold {{ $dbInfo['connection_status'] == 'Connected' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $dbInfo['connection_status'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Statistics -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-chart-bar text-green-600 mr-3"></i>
            অ্যাপ্লিকেশন পরিসংখ্যান
        </h3>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl text-center">
                <div class="text-2xl font-bold text-blue-600 mb-1">{{ $appInfo['users_count'] }}</div>
                <div class="text-sm text-gray-600">ব্যবহারকারী</div>
            </div>
            
            <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl text-center">
                <div class="text-2xl font-bold text-green-600 mb-1">{{ $appInfo['admins_count'] }}</div>
                <div class="text-sm text-gray-600">অ্যাডমিন</div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl text-center">
                <div class="text-2xl font-bold text-purple-600 mb-1">{{ $appInfo['applications_count'] }}</div>
                <div class="text-sm text-gray-600">আবেদন</div>
            </div>
            
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-4 rounded-xl text-center">
                <div class="text-2xl font-bold text-yellow-600 mb-1">{{ $appInfo['invoices_count'] }}</div>
                <div class="text-sm text-gray-600">ইনভয়েস</div>
            </div>
            
            <div class="bg-gradient-to-r from-red-50 to-red-100 p-4 rounded-xl text-center">
                <div class="text-2xl font-bold text-red-600 mb-1">{{ $appInfo['certificate_types_count'] }}</div>
                <div class="text-sm text-gray-600">সার্টিফিকেট</div>
            </div>
            
            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-4 rounded-xl text-center">
                <div class="text-2xl font-bold text-indigo-600 mb-1">৳ {{ number_format($appInfo['today_revenue'], 2) }}</div>
                <div class="text-sm text-gray-600">আজকের আয়</div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="bg-gray-50 p-4 rounded-xl">
                <div class="flex items-center mb-3">
                    <i class="fas fa-user-plus text-green-600 mr-3"></i>
                    <span class="font-medium text-gray-700">আজকের ব্যবহারকারী</span>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $appInfo['today_users'] }}</div>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-xl">
                <div class="flex items-center mb-3">
                    <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                    <span class="font-medium text-gray-700">আজকের আবেদন</span>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $appInfo['today_applications'] }}</div>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-xl">
                <div class="flex items-center mb-3">
                    <i class="fas fa-chart-line text-purple-600 mr-3"></i>
                    <span class="font-medium text-gray-700">সক্রিয় সেশন</span>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $activeSessionsCount }}</div>
            </div>
        </div>
    </div>

    <!-- System Alerts -->
    @if(count($alerts) > 0)
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                সিস্টেম অ্যালার্টস
            </h3>
            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium">
                {{ count($alerts) }} অ্যালার্ট
            </span>
        </div>
        
        <div class="space-y-4">
            @foreach($alerts as $alert)
            <div class="flex items-start p-4 rounded-xl border {{ $alert['type'] == 'critical' ? 'border-red-200 bg-red-50' : 'border-yellow-200 bg-yellow-50' }}">
                <div class="mr-4 mt-1">
                    <i class="fas {{ $alert['icon'] }} text-2xl {{ $alert['type'] == 'critical' ? 'text-red-500' : 'text-yellow-500' }}"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-gray-800 mb-1">{{ $alert['title'] }}</h4>
                    <p class="text-gray-600 mb-2">{{ $alert['message'] }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-clock mr-1"></i>
                            {{ now()->format('h:i A') }}
                        </span>
                        <button class="text-sm font-medium {{ $alert['type'] == 'critical' ? 'text-red-600 hover:text-red-800' : 'text-yellow-600 hover:text-yellow-800' }}">
                            {{ $alert['action'] }}
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Activities -->
    @if($recentActivities->count() > 0)
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-history text-gray-600 mr-3"></i>
            সাম্প্রতিক কার্যকলাপ
        </h3>
        
        <div class="space-y-4">
            @foreach($recentActivities as $activity)
            <div class="flex items-start p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">
                <div class="mr-4 mt-1">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-gray-800">{{ $activityDescription[$loop->index] ?? 'কার্যকলাপ' }}</p>
                            <p class="text-sm text-gray-600 mt-1">
                                @if(isset($activity->causer) && $activity->causer)
                                    {{ $activity->causer->name }} দ্বারা
                                @elseif(isset($activity->user) && $activity->user)
                                    {{ $activity->user->name }} দ্বারা
                                @else
                                    System দ্বারা
                                @endif
                            </p>
                        </div>
                        <span class="text-sm text-gray-500 whitespace-nowrap">
                            {{ $activityTimes[$loop->index] ?? 'এখনই' }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-6 text-center">
            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                <span>সমস্ত কার্যকলাপ দেখুন</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
    @endif

    <!-- System Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <button onclick="clearCache()" 
                class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl hover:shadow-md transition-all flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-broom text-blue-600 text-2xl mb-2"></i>
                <div class="font-medium text-gray-800">ক্যাশে ক্লিয়ার</div>
            </div>
        </button>
        
        <button onclick="optimizeDatabase()"
                class="p-4 bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl hover:shadow-md transition-all flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-database text-green-600 text-2xl mb-2"></i>
                <div class="font-medium text-gray-800">ডাটাবেস অপটিমাইজ</div>
            </div>
        </button>
        
        <button onclick="checkUpdates()"
                class="p-4 bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-xl hover:shadow-md transition-all flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-sync text-purple-600 text-2xl mb-2"></i>
                <div class="font-medium text-gray-800">আপডেট চেক</div>
            </div>
        </button>
        
        <button onclick="runMaintenance()"
                class="p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl hover:shadow-md transition-all flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-tools text-yellow-600 text-2xl mb-2"></i>
                <div class="font-medium text-gray-800">মেইনটেনেন্স</div>
            </div>
        </button>
    </div>
</div>

<!-- Maintenance Modal -->
<div id="maintenanceModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeMaintenanceModal()"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">সিস্টেম মেইনটেনেন্স</h3>
                    <button type="button" onclick="closeMaintenanceModal()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="bg-white p-6">
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-blue-50 rounded-xl">
                        <input type="checkbox" id="clearCache" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="clearCache" class="ml-3 text-gray-700">
                            অ্যাপ্লিকেশন ক্যাশে ক্লিয়ার করুন
                        </label>
                    </div>
                    
                    <div class="flex items-center p-4 bg-green-50 rounded-xl">
                        <input type="checkbox" id="optimizeDb" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="optimizeDb" class="ml-3 text-gray-700">
                            ডাটাবেস টেবিল অপটিমাইজ করুন
                        </label>
                    </div>
                    
                    <div class="flex items-center p-4 bg-yellow-50 rounded-xl">
                        <input type="checkbox" id="clearLogs" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="clearLogs" class="ml-3 text-gray-700">
                            পুরানো লগ ফাইল ডিলিট করুন
                        </label>
                    </div>
                    
                    <div class="flex items-center p-4 bg-purple-50 rounded-xl">
                        <input type="checkbox" id="runBackup" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="runBackup" class="ml-3 text-gray-700">
                            ব্যাকআপ তৈরি করুন
                        </label>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">মেইনটেনেন্স নোট</label>
                    <textarea id="maintenanceNote" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="মেইনটেনেন্স সম্পর্কে নোট..."></textarea>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeMaintenanceModal()"
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400 transition-colors">
                    বাতিল
                </button>
                <button type="button" onclick="executeMaintenance()"
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl hover:shadow-lg transition-all flex items-center">
                    <i class="fas fa-play mr-2"></i>
                    মেইনটেনেন্স শুরু করুন
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Refresh system status
function refreshSystemStatus() {
    showToast('সিস্টেম স্ট্যাটাস রিফ্রেশ হচ্ছে...', 'info');
    
    // Simulate refresh
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// Export system report
function exportSystemReport() {
    showToast('সিস্টেম রিপোর্ট তৈরি হচ্ছে...', 'info');
    
    // In a real app, you would generate and download the report
    setTimeout(() => {
        showToast('সিস্টেম রিপোর্ট ডাউনলোড শুরু হয়েছে', 'success');
        
        // Create a simple report
        const reportContent = `
            সিস্টেম মনিটরিং রিপোর্ট
            তারিখ: ${new Date().toLocaleDateString()}
            সময়: ${new Date().toLocaleTimeString()}
            
            ১. সার্ভার তথ্য:
            - PHP ভার্সন: {{ $serverInfo['php_version'] }}
            - লারাভেল ভার্সন: {{ $serverInfo['laravel_version'] }}
            - সার্ভার: {{ $serverInfo['server_software'] }}
            
            ২. ডাটাবেস তথ্য:
            - ডাটাবেস: {{ $dbInfo['database'] }}
            - টেবিল সংখ্যা: {{ $dbInfo['tables_count'] }}
            - সাইজ: {{ $dbInfo['total_size'] }} MB
            
            ৩. স্টোরেজ:
            - মোট: {{ $storageInfo['total'] }} GB
            - ব্যবহৃত: {{ $storageInfo['used'] }} GB ({{ $storageInfo['used_percentage'] }}%)
            - ফ্রি: {{ $storageInfo['free'] }} GB
            
            ৪. অ্যাপ্লিকেশন পরিসংখ্যান:
            - ব্যবহারকারী: {{ $appInfo['users_count'] }}
            - অ্যাডমিন: {{ $appInfo['admins_count'] }}
            - আবেদন: {{ $appInfo['applications_count'] }}
            - ইনভয়েস: {{ $appInfo['invoices_count'] }}
            
            ৫. পারফরম্যান্স:
            - রেসপন্স টাইম: {{ $performance['response_time'] }}
            - আপটাইম: {{ $performance['uptime'] }}
            - কিউ স্ট্যাটাস: {{ $performance['queue_status'] }}
            - ক্যাশে স্ট্যাটাস: {{ $performance['cache_status'] }}
        `;
        
        // Create and download text file
        const blob = new Blob([reportContent], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `system-report-${new Date().toISOString().split('T')[0]}.txt`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }, 1500);
}

// Clear application cache
function clearCache() {
    if (!confirm('আপনি কি নিশ্চিত যে আপনি অ্যাপ্লিকেশন ক্যাশে ক্লিয়ার করতে চান?')) {
        return;
    }
    
    showToast('ক্যাশে ক্লিয়ার হচ্ছে...', 'info');
    
    fetch('/api/system/clear-cache', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('ক্যাশে সফলভাবে ক্লিয়ার হয়েছে', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showToast('ক্যাশে ক্লিয়ার ব্যর্থ: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showToast('নেটওয়ার্ক সমস্যা', 'error');
    });
}

// Optimize database
function optimizeDatabase() {
    if (!confirm('ডাটাবেস অপটিমাইজেশন প্রক্রিয়া শুরু করতে চান?')) {
        return;
    }
    
    showToast('ডাটাবেস অপটিমাইজ হচ্ছে...', 'info');
    
    fetch('/api/system/optimize-database', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('ডাটাবেস সফলভাবে অপটিমাইজ হয়েছে', 'success');
        } else {
            showToast('ডাটাবেস অপটিমাইজ ব্যর্থ: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showToast('নেটওয়ার্ক সমস্যা', 'error');
    });
}

// Check for updates
function checkUpdates() {
    showToast('আপডেট চেক করা হচ্ছে...', 'info');
    
    fetch('/api/system/check-updates', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.updates_available) {
                showToast(`${data.updates_count} টি আপডেট পাওয়া গেছে`, 'warning');
                
                // Show updates modal
                showUpdatesModal(data.updates);
            } else {
                showToast('সব আপডেট ইন্সটল করা আছে', 'success');
            }
        } else {
            showToast('আপডেট চেক ব্যর্থ: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showToast('নেটওয়ার্ক সমস্যা', 'error');
    });
}

// Show updates modal
function showUpdatesModal(updates) {
    const modalHtml = `
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-black opacity-50" onclick="closeUpdatesModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">উপলব্ধ আপডেটস</h3>
                    
                    <div class="space-y-3 mb-6">
                        ${updates.map(update => `
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <div>
                                    <div class="font-medium text-gray-800">${update.name}</div>
                                    <div class="text-sm text-gray-600">${update.description}</div>
                                </div>
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                    v${update.version}
                                </span>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeUpdatesModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400 transition-colors">
                            পরে করুন
                        </button>
                        <button type="button" onclick="installUpdates()" 
                                class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-800 text-white rounded-xl hover:shadow-lg transition-all">
                            আপডেট ইন্সটল করুন
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const modal = document.createElement('div');
    modal.innerHTML = modalHtml;
    modal.id = 'updatesModal';
    document.body.appendChild(modal);
}

// Close updates modal
function closeUpdatesModal() {
    const modal = document.getElementById('updatesModal');
    if (modal) modal.remove();
}

// Install updates
function installUpdates() {
    showToast('আপডেট ইন্সটল করা হচ্ছে...', 'info');
    closeUpdatesModal();
    
    // In a real app, you would install updates
    setTimeout(() => {
        showToast('আপডেট সফলভাবে ইন্সটল হয়েছে', 'success');
    }, 3000);
}

// Run maintenance
function runMaintenance() {
    const modal = document.getElementById('maintenanceModal');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

// Close maintenance modal
function closeMaintenanceModal() {
    const modal = document.getElementById('maintenanceModal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Execute maintenance tasks
function executeMaintenance() {
    const tasks = [];
    const note = document.getElementById('maintenanceNote').value;
    
    if (document.getElementById('clearCache').checked) tasks.push('ক্যাশে ক্লিয়ার');
    if (document.getElementById('optimizeDb').checked) tasks.push('ডাটাবেস অপটিমাইজ');
    if (document.getElementById('clearLogs').checked) tasks.push('লগ ক্লিয়ার');
    if (document.getElementById('runBackup').checked) tasks.push('ব্যাকআপ তৈরি');
    
    if (tasks.length === 0) {
        showToast('কোন টাস্ক সিলেক্ট করা হয়নি', 'warning');
        return;
    }
    
    if (!confirm(`${tasks.join(', ')} - এই টাস্কগুলি এক্সিকিউট করতে চান?`)) {
        return;
    }
    
    showToast('মেইনটেনেন্স শুরু হয়েছে...', 'info');
    closeMaintenanceModal();
    
    // Simulate maintenance
    setTimeout(() => {
        showToast('মেইনটেনেন্স টাস্কগুলি সফলভাবে সম্পন্ন হয়েছে', 'success');
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    }, 3000);
}

// Auto-refresh every 5 minutes
setInterval(() => {
    const shouldRefresh = confirm('সিস্টেম স্ট্যাটাস আপডেট করতে চান?');
    if (shouldRefresh) {
        refreshSystemStatus();
    }
}, 300000); // 5 minutes

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Add tooltips to system actions
    const actionButtons = document.querySelectorAll('[onclick*="clearCache"], [onclick*="optimizeDatabase"], [onclick*="checkUpdates"], [onclick*="runMaintenance"]');
    
    actionButtons.forEach(button => {
        button.setAttribute('title', button.querySelector('.font-medium').textContent);
        button.setAttribute('data-toggle', 'tooltip');
    });
    
    // Initialize tooltips
    if (typeof $ !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
    }
});
</script>
@endpush

@push('styles')
<style>
/* System status indicators */
.status-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 8px;
}

.status-online {
    background-color: #10B981;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

.status-warning {
    background-color: #F59E0B;
    box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
}

.status-critical {
    background-color: #EF4444;
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
}

/* Progress bar animations */
.progress-bar {
    transition: width 1s ease-in-out;
}

/* Modal styles */
#maintenanceModal .bg-white,
#updatesModal .bg-white {
    max-height: 80vh;
    overflow-y: auto;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .system-actions {
        display: none !important;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .system-overview {
        flex-direction: column;
    }
}
</style>
@endpush

@endsection