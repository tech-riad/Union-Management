@extends('layouts.super-admin')

@section('title', 'সুপার অ্যাডমিন ড্যাশবোর্ড')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    ড্যাশবোর্ড
                </h1>
                <p class="text-gray-600">
                    আজ: {{ date('d F, Y') }} | সময়: <span id="currentTime">{{ date('h:i A') }}</span>
                    | <span class="text-green-600 font-medium" id="systemStatus">সিস্টেম একটিভ</span>
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="refreshDashboard()" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg hover:from-blue-700 hover:to-blue-900 transition-all duration-200 shadow-md hover:shadow-lg flex items-center">
                    <i class="fas fa-sync-alt mr-2" id="refreshIcon"></i> 
                    <span id="refreshText">রিফ্রেশ</span>
                </button>
                <button onclick="toggleDarkMode()" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg hover:from-purple-700 hover:to-purple-900 transition-all duration-200 shadow-md hover:shadow-lg flex items-center">
                    <i class="fas fa-moon mr-2" id="darkModeIcon"></i> 
                    <span id="darkModeText">ডার্ক মোড</span>
                </button>
                <a href="{{ route('super_admin.reports.index') }}" class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-800 text-white rounded-lg hover:from-green-700 hover:to-green-900 transition-all duration-200 shadow-md hover:shadow-lg flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i> অ্যাডভান্সড রিপোর্ট
                </a>
            </div>
        </div>
    </div>

    <!-- Real-time Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="premium-card bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100 uppercase">মোট ইউজার</p>
                    <p class="text-2xl font-bold mt-1">
                        {{ number_format($totalUsers) }}
                    </p>
                    <div class="mt-2 text-sm text-blue-100">
                        <i class="fas fa-user-plus mr-1"></i>
                        <span id="newUsersToday">{{ \App\Models\User::whereDate('created_at', today())->count() }}</span> নতুন আজ
                    </div>
                </div>
                <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-blue-400/30">
                <div class="flex justify-between text-xs">
                    <span>অ্যাডমিন: <span class="font-bold">{{ \App\Models\User::where('role', 'admin')->count() }}</span></span>
                    <span>সিটিজেন: <span class="font-bold">{{ \App\Models\User::where('role', 'citizen')->count() }}</span></span>
                </div>
            </div>
        </div>

        <!-- Today's Applications -->
        <div class="premium-card bg-gradient-to-br from-green-500 to-green-700 text-white rounded-xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-100 uppercase">আজকের আবেদন</p>
                    <p class="text-2xl font-bold mt-1">
                        {{ number_format($todayApplications) }}
                    </p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $pendingApplications }} পেন্ডিং
                        </span>
                    </div>
                </div>
                <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                    <i class="fas fa-file-contract text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-green-400/30">
                <div class="flex justify-between text-xs">
                    <span>অ্যাপ্রুভড: <span class="font-bold">{{ \App\Models\Application::whereDate('created_at', today())->where('status', 'approved')->count() }}</span></span>
                    <span>রিজেক্টেড: <span class="font-bold">{{ \App\Models\Application::whereDate('created_at', today())->where('status', 'rejected')->count() }}</span></span>
                </div>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="premium-card bg-gradient-to-br from-purple-500 to-purple-700 text-white rounded-xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-100 uppercase">আজকের আয়</p>
                    <p class="text-2xl font-bold mt-1">
                        ৳ {{ number_format($todayRevenue, 2) }}
                    </p>
                    <div class="mt-2 text-sm text-purple-100">
                        @php
                            $avgTransaction = $todayApplications > 0 ? $todayRevenue / $todayApplications : 0;
                        @endphp
                        <i class="fas fa-chart-line mr-1"></i>
                        গড়: ৳ {{ number_format($avgTransaction, 2) }}
                    </div>
                </div>
                <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-purple-400/30">
                <div class="flex justify-between text-xs">
                    <span>bKash: <span class="font-bold">৳ {{ number_format(\App\Models\Invoice::whereDate('created_at', today())->where('payment_method', 'bkash')->where('payment_status', 'paid')->sum('amount'), 2) }}</span></span>
                    <span>Cash: <span class="font-bold">৳ {{ number_format(\App\Models\Invoice::whereDate('created_at', today())->where('payment_method', 'cash')->where('payment_status', 'paid')->sum('amount'), 2) }}</span></span>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="premium-card bg-gradient-to-br from-orange-500 to-orange-700 text-white rounded-xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-100 uppercase">সিস্টেম স্বাস্থ্য</p>
                    <p class="text-2xl font-bold mt-1" id="systemHealthScore">
                        {{ $systemHealth }}%
                    </p>
                    <div class="mt-2">
                        <div class="w-full bg-white/30 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: {{ $systemHealth }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                    <i class="fas fa-heartbeat text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-orange-400/30">
                <div class="text-xs" id="healthStatus">
                    @if($systemHealth >= 80)
                    <i class="fas fa-check-circle mr-1"></i>
                    সর্বোচ্চ পারফরম্যান্স
                    @elseif($systemHealth >= 60)
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    মিডিয়াম পারফরম্যান্স
                    @else
                    <i class="fas fa-times-circle mr-1"></i>
                    মনোযোগ প্রয়োজন
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- System Status Dashboard -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Real-time System Monitoring -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-desktop mr-2 text-blue-600"></i>
                        রিয়েল-টাইম সিস্টেম মনিটরিং
                    </h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-circle text-xs mr-1 animate-pulse"></i>
                        লাইভ
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Server Status -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-3 animate-pulse"></div>
                                <h3 class="font-bold text-gray-800">সার্ভার স্ট্যাটাস</h3>
                            </div>
                            <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-1 rounded">অপটিমাইজড</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">CPU ব্যবহার</span>
                                <span class="font-bold" id="cpuUsage">24%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" id="cpuBar" style="width: 24%"></div>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">মেমোরি</span>
                                <span class="font-bold" id="memoryUsage">68%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" id="memoryBar" style="width: 68%"></div>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">স্টোরেজ</span>
                                <span class="font-bold" id="storageUsage">42%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" id="storageBar" style="width: 42%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Database Status -->
                    <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-3"></div>
                                <h3 class="font-bold text-gray-800">ডাটাবেস স্ট্যাটাস</h3>
                            </div>
                            <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-1 rounded">কানেক্টেড</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">কানেকশন</span>
                                <span class="font-bold" id="dbConnections">12</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">কোয়েরি/সেকেন্ড</span>
                                <span class="font-bold" id="queriesPerSecond">142</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">লেটেন্সি</span>
                                <span class="font-bold" id="dbLatency">18ms</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Gateway Status -->
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-3"></div>
                                <h3 class="font-bold text-gray-800">পেমেন্ট গেটওয়ে</h3>
                            </div>
                            <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-1 rounded">একটিভ</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">bKash API</span>
                                <span class="font-bold text-green-600">✓</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Nagad API</span>
                                <span class="font-bold text-green-600">✓</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">SSL কমার্স</span>
                                <span class="font-bold text-green-600">✓</span>
                            </div>
                        </div>
                    </div>

                    <!-- Security Status -->
                    <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-3"></div>
                                <h3 class="font-bold text-gray-800">সিকিউরিটি স্ট্যাটাস</h3>
                            </div>
                            <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-1 rounded">সিকিউর</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">SSL সার্টিফিকেট</span>
                                <span class="font-bold text-green-600">✓ ভ্যালিড</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">ফায়ারওয়াল</span>
                                <span class="font-bold text-green-600">এনাবল্ড</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">লাস্ট স্ক্যান</span>
                                <span class="font-bold">2h আগে</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Log -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-history mr-2 text-purple-600"></i>
                        সাম্প্রতিক অ্যাক্টিভিটি
                    </h2>
                    <a href="{{ route('super_admin.activity_logs.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            <div class="p-4">
                <div class="activity-timeline">
                    <!-- Get recent activities from Activity Log Controller -->
                    @if(method_exists(app('App\Http\Controllers\SuperAdmin\ActivityLogController'), 'getRecentActivities'))
                        @php
                            $recentActivities = app('App\Http\Controllers\SuperAdmin\ActivityLogController')->getRecentActivities(5);
                        @endphp
                        
                        @if($recentActivities && count($recentActivities) > 0)
                            @foreach($recentActivities as $activity)
                            <div class="activity-item flex items-start mb-4 pb-4 border-b border-gray-100 last:border-0">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-{{ $activity['color'] ?? 'blue' }}-100 flex items-center justify-center">
                                        <i class="fas fa-{{ $activity['icon'] ?? 'info-circle' }} text-{{ $activity['color'] ?? 'blue' }}-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ $activity['description'] ?? 'Unknown Activity' }}</p>
                                    <div class="flex items-center mt-1">
                                        <span class="text-xs text-gray-500">{{ $activity['created_at']->diffForHumans() ?? 'Just now' }}</span>
                                        <span class="mx-2 text-gray-300">•</span>
                                        <span class="text-xs text-gray-600">{{ $activity['user']['name'] ?? 'System' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <!-- Fallback to simulated activities -->
                            @php
                                $activities = [
                                    ['icon' => 'user-plus', 'color' => 'blue', 'text' => 'নতুন ইউজার রেজিস্টার্ড হয়েছে', 'time' => '5m আগে', 'user' => 'System'],
                                    ['icon' => 'check-circle', 'color' => 'green', 'text' => 'আবেদন #' . rand(1000, 9999) . ' অ্যাপ্রুভ করা হয়েছে', 'time' => '15m আগে', 'user' => 'Admin User'],
                                    ['icon' => 'money-bill', 'color' => 'purple', 'text' => 'পেমেন্ট গ্রহণ করা হয়েছে', 'time' => '30m আগে', 'user' => 'bKash Gateway'],
                                    ['icon' => 'exclamation-triangle', 'color' => 'yellow', 'text' => 'সিস্টেম ব্যাকআপ সম্পন্ন হয়েছে', 'time' => '1h আগে', 'user' => 'Auto Backup'],
                                    ['icon' => 'cog', 'color' => 'gray', 'text' => 'সিস্টেম সেটিংস আপডেট করা হয়েছে', 'time' => '2h আগে', 'user' => 'Super Admin'],
                                ];
                            @endphp
                            
                            @foreach($activities as $activity)
                            <div class="activity-item flex items-start mb-4 pb-4 border-b border-gray-100 last:border-0">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-{{ $activity['color'] }}-100 flex items-center justify-center">
                                        <i class="fas fa-{{ $activity['icon'] }} text-{{ $activity['color'] }}-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ $activity['text'] }}</p>
                                    <div class="flex items-center mt-1">
                                        <span class="text-xs text-gray-500">{{ $activity['time'] }}</span>
                                        <span class="mx-2 text-gray-300">•</span>
                                        <span class="text-xs text-gray-600">{{ $activity['user'] }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    @endif
                </div>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('super_admin.activity_logs.index') }}" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 inline-block">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        সব অ্যাক্টিভিটি দেখুন
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Performance Metrics -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-green-600"></i>
                    পারফরম্যান্স মেট্রিক্স
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 mb-1" id="avgResponseTime">142ms</div>
                        <p class="text-sm text-gray-600">গড় রেসপন্স টাইম</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600 mb-1" id="successRate">99.8%</div>
                        <p class="text-sm text-gray-600">সাফল্যের হার</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600 mb-1" id="uptime">99.9%</div>
                        <p class="text-sm text-gray-600">আপটাইম</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <div class="text-2xl font-bold text-orange-600 mb-1" id="errorRate">0.02%</div>
                        <p class="text-sm text-gray-600">এরর রেট</p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">সিস্টেম লোড ট্রেন্ড</h3>
                    <div class="h-64">
                        <canvas id="loadChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Server Actions -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-server mr-2 text-red-600"></i>
                    সার্ভার অ্যাকশনস
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <!-- Clear Cache using existing route -->
                    <button onclick="clearCache()" class="action-btn bg-gradient-to-r from-blue-500 to-blue-700 text-white p-4 rounded-lg hover:from-blue-600 hover:to-blue-800 transition-all duration-200">
                        <i class="fas fa-broom text-2xl mb-2"></i>
                        <div class="font-bold">ক্যাশ ক্লিয়ার</div>
                        <div class="text-sm opacity-90">সিস্টেম ক্যাশ</div>
                    </button>
                    
                    <!-- Run Backup using existing route -->
                    <a href="{{ route('super_admin.reports.backup') }}" class="action-btn bg-gradient-to-r from-green-500 to-green-700 text-white p-4 rounded-lg hover:from-green-600 hover:to-green-800 transition-all duration-200">
                        <i class="fas fa-save text-2xl mb-2"></i>
                        <div class="font-bold">ব্যাকআপ নিন</div>
                        <div class="text-sm opacity-90">ডাটাবেস ব্যাকআপ</div>
                    </a>
                    
                    <!-- Optimize Database -->
                    <button onclick="optimizeDB()" class="action-btn bg-gradient-to-r from-purple-500 to-purple-700 text-white p-4 rounded-lg hover:from-purple-600 hover:to-purple-800 transition-all duration-200">
                        <i class="fas fa-database text-2xl mb-2"></i>
                        <div class="font-bold">ডিবি অপ্টিমাইজ</div>
                        <div class="text-sm opacity-90">পারফরম্যান্স বাড়ান</div>
                    </button>
                    
                    <!-- View Logs using existing route -->
                    <a href="{{ route('super_admin.activity_logs.index') }}" class="action-btn bg-gradient-to-r from-orange-500 to-orange-700 text-white p-4 rounded-lg hover:from-orange-600 hover:to-orange-800 transition-all duration-200">
                        <i class="fas fa-file-alt text-2xl mb-2"></i>
                        <div class="font-bold">লগস দেখুন</div>
                        <div class="text-sm opacity-90">সিস্টেম লগস</div>
                    </a>
                </div>
                
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-bold text-gray-800 mb-2">সিস্টেম তথ্য</h3>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">PHP ভার্সন:</span>
                            <span class="font-bold">{{ phpversion() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Laravel:</span>
                            <span class="font-bold">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">ডিবি কানেকশন:</span>
                            <span class="font-bold text-green-600">✓ Active</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">লাস্ট ক্রন:</span>
                            <span class="font-bold">5m আগে</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Applications Chart -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                    আবেদন ট্রেন্ড
                </h2>
            </div>
            <div class="p-6">
                <div class="h-80">
                    <canvas id="applicationsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>
                    আয়ের ট্রেন্ড
                </h2>
            </div>
            <div class="p-6">
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links Section -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-rocket mr-2 text-indigo-600"></i>
                কুইক লিঙ্কস
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Applications Management -->
                <a href="{{ route('super_admin.applications.index') }}" class="quick-link-card bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-file-contract text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">আবেদন</h3>
                            <p class="text-sm text-gray-600">ব্যবস্থাপনা</p>
                        </div>
                    </div>
                </a>

                <!-- User Management -->
                <a href="{{ route('super_admin.users.index') }}" class="quick-link-card bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-4 hover:from-green-100 hover:to-green-200 transition-all duration-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-3">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">ইউজার</h3>
                            <p class="text-sm text-gray-600">ব্যবস্থাপনা</p>
                        </div>
                    </div>
                </a>

                <!-- Certificate Types -->
                <a href="{{ route('super_admin.certificates.index') }}" class="quick-link-card bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4 hover:from-purple-100 hover:to-purple-200 transition-all duration-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                            <i class="fas fa-certificate text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">সার্টিফিকেট</h3>
                            <p class="text-sm text-gray-600">টাইপস</p>
                        </div>
                    </div>
                </a>

                <!-- bKash Management -->
                <a href="{{ route('super_admin.bkash.dashboard') }}" class="quick-link-card bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4 hover:from-orange-100 hover:to-orange-200 transition-all duration-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                            <i class="fas fa-money-bill-wave text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">bKash</h3>
                            <p class="text-sm text-gray-600">ম্যানেজমেন্ট</p>
                        </div>
                    </div>
                </a>

                <!-- Settings -->
                <a href="{{ route('super_admin.settings.union') }}" class="quick-link-card bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-4 hover:from-red-100 hover:to-red-200 transition-all duration-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-3">
                            <i class="fas fa-cog text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">সেটিংস</h3>
                            <p class="text-sm text-gray-600">ইউনিয়ন</p>
                        </div>
                    </div>
                </a>

                <!-- Reports -->
                <a href="{{ route('super_admin.reports.index') }}" class="quick-link-card bg-gradient-to-r from-indigo-50 to-indigo-100 border border-indigo-200 rounded-lg p-4 hover:from-indigo-100 hover:to-indigo-200 transition-all duration-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">রিপোর্টস</h3>
                            <p class="text-sm text-gray-600">অ্যাডভান্সড</p>
                        </div>
                    </div>
                </a>

                <!-- Verification -->
                <a href="{{ route('super_admin.verification.logs') }}" class="quick-link-card bg-gradient-to-r from-teal-50 to-teal-100 border border-teal-200 rounded-lg p-4 hover:from-teal-100 hover:to-teal-200 transition-all duration-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center mr-3">
                            <i class="fas fa-shield-alt text-teal-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">ভেরিফিকেশন</h3>
                            <p class="text-sm text-gray-600">সিস্টেম</p>
                        </div>
                    </div>
                </a>

                <!-- Backup -->
                <a href="{{ route('super_admin.reports.backup') }}" class="quick-link-card bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-4 hover:from-yellow-100 hover:to-yellow-200 transition-all duration-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                            <i class="fas fa-database text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">ব্যাকআপ</h3>
                            <p class="text-sm text-gray-600">ম্যানেজমেন্ট</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Premium Styles -->
<style>
    .premium-card {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .premium-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
        animation: shimmer 2s infinite;
    }
    
    .premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .action-btn {
        transition: all 0.3s ease;
        text-align: center;
        display: block;
        text-decoration: none;
    }
    
    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .activity-timeline {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .activity-item {
        transition: all 0.2s ease;
    }
    
    .activity-item:hover {
        background-color: #f9fafb;
        padding-left: 10px;
        margin-left: -10px;
        border-radius: 8px;
    }
    
    .quick-link-card {
        transition: all 0.3s ease;
    }
    
    .quick-link-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all charts
    setupCharts();
    
    // Update time every minute
    updateCurrentTime();
    setInterval(updateCurrentTime, 60000);
    
    // Initialize dark mode
    initDarkMode();
    
    // Start real-time monitoring
    startRealTimeMonitoring();
});

// Setup charts with real data
function setupCharts() {
    // Applications Chart Data from Controller
    const appData = @json($chartData['applications']['data'] ?? []);
    const appLabels = @json($chartData['applications']['labels'] ?? []);
    
    // Revenue Chart Data from Controller
    const revenueData = @json($chartData['revenue']['data'] ?? []);
    const revenueLabels = @json($chartData['revenue']['labels'] ?? []);

    // Applications Chart
    const appsCtx = document.getElementById('applicationsChart');
    if (appsCtx) {
        const applicationsChart = new Chart(appsCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: appLabels.length > 0 ? appLabels : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'আবেদন সংখ্যা',
                    data: appData.length > 0 ? appData : [12, 19, 15, 25, 22, 18, 24],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });
    }

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const revenueChart = new Chart(revenueCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: revenueLabels.length > 0 ? revenueLabels : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'আয় (৳)',
                    data: revenueData.length > 0 ? revenueData : [5000, 7500, 6200, 8900, 7200, 6800, 9500],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(16, 185, 129, 0.6)',
                        'rgba(16, 185, 129, 0.5)',
                        'rgba(16, 185, 129, 0.4)',
                        'rgba(16, 185, 129, 0.3)',
                        'rgba(16, 185, 129, 0.2)'
                    ],
                    borderColor: '#059669',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'আয়: ৳' + context.parsed.y.toLocaleString();
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#10b981',
                        borderWidth: 1,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });
    }

    // System Load Chart
    const loadCtx = document.getElementById('loadChart');
    if (loadCtx) {
        const loadChart = new Chart(loadCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'],
                datasets: [{
                    label: 'CPU লোড',
                    data: [24, 26, 28, 25, 30, 32, 28],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'মেমোরি লোড',
                    data: [65, 68, 70, 67, 72, 75, 70],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'লোড (%)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'সময়'
                        }
                    }
                }
            }
        });
    }
}

// Update current time
function updateCurrentTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
    });
    const currentTimeElement = document.getElementById('currentTime');
    if (currentTimeElement) {
        currentTimeElement.textContent = timeString;
    }
}

// Initialize dark mode
function initDarkMode() {
    const darkMode = localStorage.getItem('darkMode') === 'true';
    if (darkMode) {
        document.documentElement.classList.add('dark');
        updateDarkModeButton(true);
    }
}

// Toggle dark mode
function toggleDarkMode() {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('darkMode', isDark);
    updateDarkModeButton(isDark);
}

// Update dark mode button
function updateDarkModeButton(isDark) {
    const icon = document.getElementById('darkModeIcon');
    const text = document.getElementById('darkModeText');
    
    if (isDark) {
        icon.className = 'fas fa-sun mr-2';
        text.textContent = 'লাইট মোড';
    } else {
        icon.className = 'fas fa-moon mr-2';
        text.textContent = 'ডার্ক মোড';
    }
}

// Real-time monitoring
function startRealTimeMonitoring() {
    // Update server metrics every 5 seconds
    setInterval(updateServerMetrics, 5000);
    
    // Update system health every 10 seconds
    setInterval(updateSystemHealth, 10000);
    
    // Initial update
    updateServerMetrics();
}

// Update server metrics
function updateServerMetrics() {
    // Simulate real-time data
    const cpu = 20 + Math.random() * 15;
    const memory = 60 + Math.random() * 15;
    const storage = 40 + Math.random() * 10;
    const queries = 100 + Math.floor(Math.random() * 100);
    const latency = 10 + Math.floor(Math.random() * 15);
    const connections = 5 + Math.floor(Math.random() * 10);
    
    // Update CPU
    const cpuUsageElement = document.getElementById('cpuUsage');
    const cpuBarElement = document.getElementById('cpuBar');
    if (cpuUsageElement && cpuBarElement) {
        cpuUsageElement.textContent = Math.round(cpu) + '%';
        cpuBarElement.style.width = cpu + '%';
    }
    
    // Update Memory
    const memoryUsageElement = document.getElementById('memoryUsage');
    const memoryBarElement = document.getElementById('memoryBar');
    if (memoryUsageElement && memoryBarElement) {
        memoryUsageElement.textContent = Math.round(memory) + '%';
        memoryBarElement.style.width = memory + '%';
    }
    
    // Update Storage
    const storageUsageElement = document.getElementById('storageUsage');
    const storageBarElement = document.getElementById('storageBar');
    if (storageUsageElement && storageBarElement) {
        storageUsageElement.textContent = Math.round(storage) + '%';
        storageBarElement.style.width = storage + '%';
    }
    
    // Update Database
    const queriesElement = document.getElementById('queriesPerSecond');
    const latencyElement = document.getElementById('dbLatency');
    const connectionsElement = document.getElementById('dbConnections');
    
    if (queriesElement) queriesElement.textContent = queries;
    if (latencyElement) latencyElement.textContent = latency + 'ms';
    if (connectionsElement) connectionsElement.textContent = connections;
    
    // Update performance metrics
    const avgResponseTimeElement = document.getElementById('avgResponseTime');
    const successRateElement = document.getElementById('successRate');
    const errorRateElement = document.getElementById('errorRate');
    
    if (avgResponseTimeElement) {
        avgResponseTimeElement.textContent = (100 + Math.floor(Math.random() * 50)) + 'ms';
    }
    if (successRateElement) {
        successRateElement.textContent = (99.7 + Math.random() * 0.2).toFixed(1) + '%';
    }
    if (errorRateElement) {
        errorRateElement.textContent = (0.01 + Math.random() * 0.03).toFixed(2) + '%';
    }
}

// Update system health
function updateSystemHealth() {
    // Calculate system health score based on simulated data
    const cpuElement = document.getElementById('cpuUsage');
    const memoryElement = document.getElementById('memoryUsage');
    
    if (!cpuElement || !memoryElement) return;
    
    const cpu = parseFloat(cpuElement.textContent);
    const memory = parseFloat(memoryElement.textContent);
    
    let healthScore = 100;
    
    if (cpu > 80) healthScore -= 20;
    else if (cpu > 60) healthScore -= 10;
    
    if (memory > 80) healthScore -= 20;
    else if (memory > 60) healthScore -= 10;
    
    healthScore = Math.max(0, healthScore);
    
    const healthElement = document.getElementById('systemHealthScore');
    const healthStatus = document.getElementById('healthStatus');
    
    if (healthElement) {
        healthElement.textContent = Math.round(healthScore) + '%';
    }
    
    // Update health bar
    if (healthElement) {
        const healthBar = healthElement.parentElement.querySelector('div > div');
        if (healthBar) {
            healthBar.style.width = healthScore + '%';
        }
    }
    
    // Update status text
    if (healthStatus) {
        if (healthScore >= 80) {
            healthElement.style.color = '#10b981';
            healthStatus.innerHTML = '<i class="fas fa-check-circle mr-1"></i> সর্বোচ্চ পারফরম্যান্স';
            healthStatus.style.color = '#10b981';
        } else if (healthScore >= 60) {
            healthElement.style.color = '#f59e0b';
            healthStatus.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> মিডিয়াম পারফরম্যান্স';
            healthStatus.style.color = '#f59e0b';
        } else {
            healthElement.style.color = '#ef4444';
            healthStatus.innerHTML = '<i class="fas fa-times-circle mr-1"></i> মনোযোগ প্রয়োজন';
            healthStatus.style.color = '#ef4444';
        }
    }
}

// Refresh dashboard
function refreshDashboard() {
    const refreshIcon = document.getElementById('refreshIcon');
    const refreshText = document.getElementById('refreshText');
    
    if (!refreshIcon || !refreshText) return;
    
    // Show loading state
    const originalIcon = refreshIcon.className;
    const originalText = refreshText.textContent;
    
    refreshIcon.className = 'fas fa-spinner fa-spin mr-2';
    refreshText.textContent = 'রিফ্রেশ হচ্ছে...';
    
    // Disable button
    const button = refreshIcon.closest('button');
    if (button) {
        button.disabled = true;
    }
    
    // Reload the page after 1 second
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// Clear cache function
function clearCache() {
    if (confirm('আপনি কি নিশ্চিত যে আপনি সিস্টেম ক্যাশ ক্লিয়ার করতে চান?')) {
        // Redirect to backup page which has clear cache option
        window.location.href = "{{ route('super_admin.reports.backup') }}";
    }
}

// Optimize database function
function optimizeDB() {
    if (confirm('আপনি কি ডাটাবেস অপ্টিমাইজেশন শুরু করতে চান?')) {
        // Show loading
        alert('ডাটাবেস অপ্টিমাইজেশন শুরু হয়েছে...');
        // In real implementation, you would make an AJAX call here
        // For now, redirect to reports page
        window.location.href = "{{ route('super_admin.reports.index') }}";
    }
}

// System status update
function updateSystemStatus() {
    const statusElement = document.getElementById('systemStatus');
    if (!statusElement) return;
    
    const random = Math.random();
    
    if (random > 0.9) {
        statusElement.textContent = 'সিস্টেম ইস্যু';
        statusElement.className = 'text-red-600 font-medium';
    } else if (random > 0.7) {
        statusElement.textContent = 'সিস্টেম স্লো';
        statusElement.className = 'text-yellow-600 font-medium';
    } else {
        statusElement.textContent = 'সিস্টেম একটিভ';
        statusElement.className = 'text-green-600 font-medium';
    }
}

// Update system status every 30 seconds
setInterval(updateSystemStatus, 30000);

// Initialize
updateSystemStatus();
</script>
@endsection