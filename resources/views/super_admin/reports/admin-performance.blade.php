@extends('layouts.super-admin')

@section('title', 'অ্যাডমিন পারফরম্যান্স রিপোর্ট - সুপার অ্যাডমিন')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-user-tie text-blue-600 mr-3"></i>
                    অ্যাডমিন পারফরম্যান্স রিপোর্ট
                </h1>
                <p class="text-gray-600">অ্যাডমিনদের কাজের দক্ষতা এবং কার্যকারিতার বিশ্লেষণ</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <button onclick="exportPerformance('csv')" 
                        class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-700 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-file-csv mr-2"></i>
                    CSV এক্সপোর্ট
                </button>
                <button onclick="printPerformanceReport()"
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-print mr-2"></i>
                    প্রিন্ট রিপোর্ট
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 mb-8">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-filter text-blue-600 mr-3"></i>
                পারফরম্যান্স ফিল্টার
            </h3>
        </div>
        
        <div class="p-6">
            <form action="{{ route('super_admin.reports.admin.performance') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Time Period -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            সময়কাল
                        </label>
                        <select name="period" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="day" {{ $period == 'day' ? 'selected' : '' }}>আজ</option>
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>সপ্তাহ</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>মাস</option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>বছর</option>
                            <option value="all" {{ $period == 'all' ? 'selected' : '' }}>সকল সময়</option>
                        </select>
                    </div>
                    
                    <!-- Sort By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sort mr-2"></i>
                            সাজান
                        </label>
                        <select name="sort_by" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="revenue" {{ $sortBy == 'revenue' ? 'selected' : '' }}>রাজস্ব অনুযায়ী</option>
                            <option value="applications" {{ $sortBy == 'applications' ? 'selected' : '' }}>আবেদন অনুযায়ী</option>
                            <option value="approval_rate" {{ $sortBy == 'approval_rate' ? 'selected' : '' }}>অনুমোদন হার অনুযায়ী</option>
                            <option value="efficiency" {{ $sortBy == 'efficiency' ? 'selected' : '' }}>দক্ষতা অনুযায়ী</option>
                        </select>
                    </div>
                    
                    <!-- Performance Threshold -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-chart-line mr-2"></i>
                            পারফরম্যান্স থ্রেশহোল্ড
                        </label>
                        <select id="performanceThreshold" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="all">সকল অ্যাডমিন</option>
                            <option value="high">উচ্চ পারফরম্যান্স</option>
                            <option value="medium">মাঝারি পারফরম্যান্স</option>
                            <option value="low">নিম্ন পারফরম্যান্স</option>
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-end space-x-3">
                        <button type="submit" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-filter mr-2"></i>
                            ফিল্টার প্রয়োগ
                        </button>
                        <a href="{{ route('super_admin.reports.admin.performance') }}" 
                           class="flex-1 px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-800 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            রিসেট
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Admins -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 mb-2">মোট অ্যাডমিন</p>
                    <h2 class="text-3xl font-bold">{{ $admins->count() }}</h2>
                </div>
                <div class="bg-blue-800/50 p-4 rounded-xl">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-blue-100">
                <i class="fas fa-clock mr-2"></i>
                {{ $periodText }}
            </div>
        </div>

        <!-- Total Applications -->
        <div class="bg-gradient-to-r from-green-500 to-green-700 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 mb-2">মোট আবেদন</p>
                    <h2 class="text-3xl font-bold">{{ $admins->sum('total_applications') }}</h2>
                </div>
                <div class="bg-green-800/50 p-4 rounded-xl">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-green-100">
                <i class="fas fa-chart-bar mr-2"></i>
                গড়: {{ round($admins->avg('total_applications'), 1) }}
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-700 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 mb-2">মোট রাজস্ব</p>
                    <h2 class="text-3xl font-bold">৳ {{ number_format($admins->sum('total_revenue'), 2) }}</h2>
                </div>
                <div class="bg-purple-800/50 p-4 rounded-xl">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-purple-100">
                <i class="fas fa-calculator mr-2"></i>
                গড়: ৳ {{ number_format($admins->avg('total_revenue'), 2) }}
            </div>
        </div>

        <!-- Average Approval Rate -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-700 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 mb-2">গড় অনুমোদন হার</p>
                    <h2 class="text-3xl font-bold">{{ round($admins->avg('approval_rate'), 1) }}%</h2>
                </div>
                <div class="bg-orange-800/50 p-4 rounded-xl">
                    <i class="fas fa-chart-pie text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-orange-100">
                <i class="fas fa-percentage mr-2"></i>
                {{ $periodText }}
            </div>
        </div>
    </div>

    <!-- Performance Rankings -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 mb-8 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-3"></i>
                    পারফরম্যান্স র‍্যাঙ্কিং
                </h3>
                <p class="text-gray-600 text-sm mt-1">
                    সাজানো হয়েছে: {{ $sortBy == 'revenue' ? 'রাজস্ব অনুযায়ী' : 
                                   ($sortBy == 'applications' ? 'আবেদন অনুযায়ী' : 
                                   ($sortBy == 'approval_rate' ? 'অনুমোদন হার অনুযায়ী' : 'দক্ষতা অনুযায়ী')) }}
                </p>
            </div>
            <div class="mt-2 sm:mt-0">
                <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                    সময়কাল: {{ $periodText }}
                </span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            র‍্যাঙ্ক
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            অ্যাডমিন
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            মোট আবেদন
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            অনুমোদন হার
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            মোট রাজস্ব
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            দক্ষতা স্কোর
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            অ্যাকশন
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($admins as $index => $admin)
                    @php
                        // Determine performance level
                        if ($admin->efficiency_score >= 80) {
                            $performanceLevel = 'high';
                            $performanceColor = 'green';
                        } elseif ($admin->efficiency_score >= 60) {
                            $performanceLevel = 'medium';
                            $performanceColor = 'yellow';
                        } else {
                            $performanceLevel = 'low';
                            $performanceColor = 'red';
                        }
                    @endphp
                    <tr class="performance-row hover:bg-gray-50 transition-colors" data-performance="{{ $performanceLevel }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                @if($index < 3)
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r 
                                    {{ $index == 0 ? 'from-yellow-500 to-yellow-700' : 
                                       ($index == 1 ? 'from-gray-400 to-gray-600' : 
                                       'from-orange-500 to-orange-700') }} 
                                    flex items-center justify-center text-white font-bold text-lg">
                                    {{ $index + 1 }}
                                </div>
                                @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                                    {{ $index + 1 }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-r from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($admin->name, 0, 2)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $admin->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $admin->email ?? 'N/A' }}</div>
                                    <div class="mt-1">
                                        <span class="px-2 py-1 bg-{{ $performanceColor }}-100 text-{{ $performanceColor }}-700 rounded-full text-xs">
                                            {{ $performanceLevel == 'high' ? 'উচ্চ পারফরম্যান্স' : 
                                               ($performanceLevel == 'medium' ? 'মাঝারি পারফরম্যান্স' : 'নিম্ন পারফরম্যান্স') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $admin->total_applications }}</div>
                                <div class="text-xs text-gray-500 flex justify-center space-x-2 mt-1">
                                    <span class="text-green-600">{{ $admin->approved_applications }} ✓</span>
                                    <span class="text-yellow-600">{{ $admin->pending_applications }} ⏱</span>
                                    <span class="text-red-600">{{ $admin->rejected_applications }} ✗</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">অনুমোদন</span>
                                    <span class="text-sm font-bold text-green-600">{{ $admin->approval_rate }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $admin->approval_rate }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500">
                                    প্রত্যাখ্যান: {{ $admin->rejection_rate }}%
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-2">
                                <div class="text-2xl font-bold text-green-600">
                                    ৳ {{ number_format($admin->total_revenue, 2) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    @if($admin->total_applications > 0)
                                    গড়: ৳ {{ number_format($admin->total_revenue / $admin->total_applications, 2) }}
                                    @else
                                    গড়: ৳ 0.00
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="text-lg font-bold text-gray-900 mr-2">{{ $admin->efficiency_score }}</span>
                                    <div class="flex-1">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" style="width: {{ $admin->efficiency_score }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500">
                                    প্রসেসিং সময়: {{ $admin->avg_processing_time }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="viewAdminDetails({{ $admin->id }})" 
                                        class="p-2 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg transition-colors"
                                        title="বিস্তারিত দেখুন">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('super_admin.applications.index', ['admin_id' => $admin->id]) }}"
                                   class="p-2 bg-green-100 text-green-600 hover:bg-green-200 rounded-lg transition-colors"
                                   title="আবেদন দেখুন">
                                    <i class="fas fa-list"></i>
                                </a>
                                <button onclick="sendPerformanceAlert({{ $admin->id }})"
                                        class="p-2 bg-yellow-100 text-yellow-600 hover:bg-yellow-200 rounded-lg transition-colors"
                                        title="নোটিফিকেশন পাঠান">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($admins->isEmpty())
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-user-tie text-gray-400 text-3xl"></i>
            </div>
            <h4 class="text-gray-500 font-medium mb-2">কোন অ্যাডমিন ডাটা পাওয়া যায়নি</h4>
            <p class="text-gray-400">এই সময়কালে কোনো অ্যাডমিন পারফরম্যান্স ডাটা নেই</p>
        </div>
        @endif
    </div>

    <!-- Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Performance Distribution -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-chart-bar text-blue-600 mr-3"></i>
                    পারফরম্যান্স ডিস্ট্রিবিউশন
                </h3>
                <button onclick="exportPerformanceChart()" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-xl text-sm font-medium transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    এক্সপোর্ট
                </button>
            </div>
            
            <div class="h-64 mb-6">
                <!-- Performance Distribution Chart -->
                <canvas id="performanceChart"></canvas>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                @php
                    $highCount = $admins->filter(function($admin) {
                        return $admin->efficiency_score >= 80;
                    })->count();
                    
                    $mediumCount = $admins->filter(function($admin) {
                        return $admin->efficiency_score >= 60 && $admin->efficiency_score < 80;
                    })->count();
                    
                    $lowCount = $admins->filter(function($admin) {
                        return $admin->efficiency_score < 60;
                    })->count();
                    
                    $totalAdmins = $admins->count();
                @endphp
                
                <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-trophy text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">উচ্চ পারফরম্যান্স</p>
                            <p class="text-xl font-bold text-gray-800">{{ $highCount }}</p>
                            <p class="text-xs text-gray-500">
                                @if($totalAdmins > 0)
                                {{ round(($highCount / $totalAdmins) * 100, 1) }}%
                                @else
                                0%
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-4 rounded-xl">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-chart-line text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">মাঝারি পারফরম্যান্স</p>
                            <p class="text-xl font-bold text-gray-800">{{ $mediumCount }}</p>
                            <p class="text-xs text-gray-500">
                                @if($totalAdmins > 0)
                                {{ round(($mediumCount / $totalAdmins) * 100, 1) }}%
                                @else
                                0%
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-red-50 to-red-100 p-4 rounded-xl">
                    <div class="flex items-center">
                        <div class="bg-red-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">নিম্ন পারফরম্যান্স</p>
                            <p class="text-xl font-bold text-gray-800">{{ $lowCount }}</p>
                            <p class="text-xs text-gray-500">
                                @if($totalAdmins > 0)
                                {{ round(($lowCount / $totalAdmins) * 100, 1) }}%
                                @else
                                0%
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-crown text-yellow-500 mr-3"></i>
                সেরা পারফর্মারগণ
            </h3>
            
            <div class="space-y-4">
                @php
                    $topPerformers = $admins->sortByDesc('efficiency_score')->take(3);
                @endphp
                
                @foreach($topPerformers as $index => $admin)
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($admin->name, 0, 2)) }}
                            </div>
                            @if($index == 0)
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-crown text-white text-xs"></i>
                            </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <div class="font-bold text-gray-900">{{ $admin->name }}</div>
                            <div class="text-sm text-gray-600">
                                দক্ষতা: <span class="font-bold text-green-600">{{ $admin->efficiency_score }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-gray-900">{{ $admin->total_applications }} আবেদন</div>
                        <div class="text-sm text-green-600 font-bold">৳ {{ number_format($admin->total_revenue, 2) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 mb-3">পরিসংখ্যান</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="text-sm text-gray-600">সর্বোচ্চ অনুমোদন হার</div>
                        <div class="text-lg font-bold text-green-600">
                            {{ $admins->max('approval_rate') ?? 0 }}%
                        </div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="text-sm text-gray-600">সর্বোচ্চ রাজস্ব</div>
                        <div class="text-lg font-bold text-purple-600">
                            ৳ {{ number_format($admins->max('total_revenue') ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="mt-8 bg-white rounded-2xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-chart-line text-purple-600 mr-3"></i>
            পারফরম্যান্স ট্রেন্ড
        </h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Approval Rate Trend -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="font-bold text-gray-800">অনুমোদন হার ট্রেন্ড</h4>
                        <p class="text-sm text-gray-600">গড়: {{ round($admins->avg('approval_rate'), 1) }}%</p>
                    </div>
                    <div class="text-2xl text-green-600">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    @foreach($admins->sortByDesc('approval_rate')->take(5) as $admin)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 truncate max-w-[120px]">{{ $admin->name }}</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $admin->approval_rate }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-800">{{ $admin->approval_rate }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Revenue Generation -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="font-bold text-gray-800">রাজস্ব জেনারেশন</h4>
                        <p class="text-sm text-gray-600">মোট: ৳ {{ number_format($admins->sum('total_revenue'), 2) }}</p>
                    </div>
                    <div class="text-2xl text-blue-600">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    @foreach($admins->sortByDesc('total_revenue')->take(5) as $admin)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 truncate max-w-[120px]">{{ $admin->name }}</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                @php
                                    $maxRevenue = $admins->max('total_revenue');
                                    $revenuePercentage = $maxRevenue > 0 ? ($admin->total_revenue / $maxRevenue) * 100 : 0;
                                @endphp
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $revenuePercentage }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-800">৳ {{ number_format($admin->total_revenue, 2) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Efficiency Scores -->
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="font-bold text-gray-800">দক্ষতা স্কোর</h4>
                        <p class="text-sm text-gray-600">গড়: {{ round($admins->avg('efficiency_score'), 1) }}</p>
                    </div>
                    <div class="text-2xl text-purple-600">
                        <i class="fas fa-bolt"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    @foreach($admins->sortByDesc('efficiency_score')->take(5) as $admin)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 truncate max-w-[120px]">{{ $admin->name }}</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" style="width: {{ $admin->efficiency_score }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-800">{{ $admin->efficiency_score }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Details Modal -->
<div id="adminModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeAdminModal()"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">অ্যাডমিন পারফরম্যান্স বিস্তারিত</h3>
                    <button type="button" onclick="closeAdminModal()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="bg-white p-6" id="adminDetail">
                <!-- Content will be loaded here -->
                <div class="text-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-4 text-gray-600">লোড হচ্ছে...</p>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeAdminModal()"
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400 transition-colors">
                    বন্ধ করুন
                </button>
                <button type="button" onclick="generatePerformanceReport()"
                        class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-800 text-white rounded-xl hover:shadow-lg transition-all flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    PDF রিপোর্ট
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    setupPerformanceFilters();
});

function initializeCharts() {
    // Performance Distribution Chart
    const ctx = document.getElementById('performanceChart').getContext('2d');
    
    @php
        $highCount = $admins->filter(function($admin) {
            return $admin->efficiency_score >= 80;
        })->count();
        
        $mediumCount = $admins->filter(function($admin) {
            return $admin->efficiency_score >= 60 && $admin->efficiency_score < 80;
        })->count();
        
        $lowCount = $admins->filter(function($admin) {
            return $admin->efficiency_score < 60;
        })->count();
    @endphp
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['উচ্চ পারফরম্যান্স', 'মাঝারি পারফরম্যান্স', 'নিম্ন পারফরম্যান্স'],
            datasets: [{
                label: 'অ্যাডমিন সংখ্যা',
                data: [{{ $highCount }}, {{ $mediumCount }}, {{ $lowCount }}],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',  // green
                    'rgba(245, 158, 11, 0.8)', // yellow
                    'rgba(239, 68, 68, 0.8)'   // red
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.raw} জন অ্যাডমিন`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Setup performance filters
function setupPerformanceFilters() {
    const thresholdSelect = document.getElementById('performanceThreshold');
    if (thresholdSelect) {
        thresholdSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            const rows = document.querySelectorAll('.performance-row');
            
            rows.forEach(row => {
                if (selectedValue === 'all') {
                    row.style.display = '';
                } else {
                    const performanceLevel = row.getAttribute('data-performance');
                    if (performanceLevel === selectedValue) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
            
            const visibleCount = document.querySelectorAll('.performance-row[style=""]').length + 
                               document.querySelectorAll('.performance-row:not([style])').length;
            
            showToast(`${visibleCount} জন অ্যাডমিন দেখানো হচ্ছে`, 'info');
        });
    }
}

// View admin details
function viewAdminDetails(adminId) {
    const modal = document.getElementById('adminModal');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Load admin details via AJAX
    fetch(`/api/admins/${adminId}/performance-details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('adminDetail').innerHTML = data.html;
            } else {
                document.getElementById('adminDetail').innerHTML = `
                    <div class="p-6">
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-circle text-red-500 text-4xl mb-4"></i>
                            <h4 class="text-red-600 font-medium mb-2">ত্রুটি!</h4>
                            <p class="text-gray-600">অ্যাডমিন ডাটা লোড করতে সমস্যা</p>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('adminDetail').innerHTML = `
                <div class="p-6">
                    <div class="text-center py-8">
                        <i class="fas fa-wifi text-red-500 text-4xl mb-4"></i>
                        <h4 class="text-red-600 font-medium mb-2">নেটওয়ার্ক ত্রুটি</h4>
                        <p class="text-gray-600">সার্ভার কানেকশন সমস্যা</p>
                    </div>
                </div>
            `;
        });
}

// Close admin modal
function closeAdminModal() {
    const modal = document.getElementById('adminModal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Export performance report
function exportPerformance(format = 'csv') {
    const params = new URLSearchParams(window.location.search);
    params.append('format', format);
    
    showToast(`${format.toUpperCase()} রিপোর্ট তৈরি হচ্ছে...`, 'info');
    
    setTimeout(() => {
        window.location.href = `{{ route('super_admin.reports.export', 'admin-performance') }}?${params.toString()}`;
    }, 1500);
}

// Print performance report
function printPerformanceReport() {
    showToast('প্রিন্ট প্রস্তুত হচ্ছে...', 'info');
    
    // Create print window
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>অ্যাডমিন পারফরম্যান্স রিপোর্ট - {{ date('d/m/Y') }}</title>
            <style>
                body { font-family: 'Noto Sans Bengali', sans-serif; margin: 20px; }
                .print-header { text-align: center; margin-bottom: 30px; }
                .print-header h1 { color: #1e3a8a; }
                .print-summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px; }
                .print-card { padding: 15px; border-radius: 10px; color: white; text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th { background: #1e3a8a; color: white; padding: 10px; text-align: left; }
                td { padding: 8px; border-bottom: 1px solid #ddd; }
                .rank-1 { background: #fff9c4; }
                .rank-2 { background: #f5f5f5; }
                .rank-3 { background: #ffe0b2; }
                .performance-high { color: green; font-weight: bold; }
                .performance-medium { color: orange; font-weight: bold; }
                .performance-low { color: red; font-weight: bold; }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h1>অ্যাডমিন পারফরম্যান্স রিপোর্ট</h1>
                <p>তৈরির তারিখ: {{ date('d/m/Y h:i A') }}</p>
                <p>সময়কাল: {{ $periodText }}</p>
            </div>
            
            <div class="print-summary">
                <div class="print-card" style="background: #3B82F6;">
                    <h3>মোট অ্যাডমিন</h3>
                    <h2>${document.querySelector('[data-stat="total-admins"]')?.textContent || '0'}</h2>
                </div>
                <div class="print-card" style="background: #10B981;">
                    <h3>মোট আবেদন</h3>
                    <h2>${document.querySelector('[data-stat="total-applications"]')?.textContent || '0'}</h2>
                </div>
                <div class="print-card" style="background: #8B5CF6;">
                    <h3>মোট রাজস্ব</h3>
                    <h2>${document.querySelector('[data-stat="total-revenue"]')?.textContent || '0'}</h2>
                </div>
                <div class="print-card" style="background: #F59E0B;">
                    <h3>গড় অনুমোদন হার</h3>
                    <h2>${document.querySelector('[data-stat="avg-approval"]')?.textContent || '0'}%</h2>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>র‍্যাঙ্ক</th>
                        <th>অ্যাডমিন</th>
                        <th>আবেদন</th>
                        <th>অনুমোদন হার</th>
                        <th>রাজস্ব</th>
                        <th>দক্ষতা</th>
                    </tr>
                </thead>
                <tbody>
                    ${Array.from(document.querySelectorAll('.performance-row')).slice(0, 10).map((row, index) => `
                        <tr class="rank-${index < 3 ? index + 1 : ''}">
                            <td>${index + 1}</td>
                            <td>${row.cells[1]?.querySelector('.text-gray-900')?.textContent || ''}</td>
                            <td>${row.cells[2]?.querySelector('.text-gray-900')?.textContent || ''}</td>
                            <td>${row.cells[3]?.querySelector('.text-green-600')?.textContent || ''}</td>
                            <td>${row.cells[4]?.querySelector('.text-green-600')?.textContent || ''}</td>
                            <td class="performance-${row.getAttribute('data-performance')}">
                                ${row.cells[5]?.querySelector('.text-gray-900')?.textContent || ''}
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            
            <div class="no-print" style="margin-top: 30px; text-align: center;">
                <button onclick="window.print()" style="padding: 10px 20px; background: #1e3a8a; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    প্রিন্ট করুন
                </button>
                <button onclick="window.close()" style="padding: 10px 20px; background: #6B7280; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                    বন্ধ করুন
                </button>
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
}

// Send performance alert to admin
function sendPerformanceAlert(adminId) {
    showToast('নোটিফিকেশন পাঠানো হচ্ছে...', 'info');
    
    fetch(`/api/admins/${adminId}/send-performance-alert`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('নোটিফিকেশন সফলভাবে পাঠানো হয়েছে', 'success');
        } else {
            showToast('নোটিফিকেশন পাঠানো ব্যর্থ', 'error');
        }
    })
    .catch(error => {
        showToast('নেটওয়ার্ক সমস্যা', 'error');
    });
}

// Generate PDF performance report
function generatePerformanceReport() {
    showToast('PDF রিপোর্ট তৈরি হচ্ছে...', 'info');
    
    // In a real application, you would call your PDF generation endpoint
    setTimeout(() => {
        showToast('PDF রিপোর্ট ডাউনলোড শুরু হয়েছে', 'success');
    }, 2000);
}

// Export performance chart
function exportPerformanceChart() {
    showToast('চার্ট এক্সপোর্ট প্রস্তুত হচ্ছে...', 'info');
    
    // In a real application, you would generate and download the chart image
    setTimeout(() => {
        showToast('চার্ট PNG ফরম্যাটে ডাউনলোড হয়েছে', 'success');
    }, 1000);
}
</script>
@endpush

@push('styles')
<style>
/* Performance level colors */
.performance-row[data-performance="high"] {
    border-left: 4px solid #10B981;
}

.performance-row[data-performance="medium"] {
    border-left: 4px solid #F59E0B;
}

.performance-row[data-performance="low"] {
    border-left: 4px solid #EF4444;
}

/* Hover effects */
.performance-row:hover {
    background-color: #f8fafc !important;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
}

/* Chart container */
canvas {
    max-width: 100%;
}

/* Modal styles */
#adminModal .bg-white {
    max-height: 80vh;
    overflow-y: auto;
}
</style>
@endpush

@endsection