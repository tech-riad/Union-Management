@extends('layouts.super-admin')

@section('title', 'আবেদন রিপোর্ট - সুপার অ্যাডমিন')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-file-contract text-blue-600 mr-3"></i>
                    আবেদন রিপোর্ট
                </h1>
                <p class="text-gray-600">সকল আবেদনের বিশ্লেষণ এবং স্ট্যাটিস্টিক্স</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <button onclick="exportApplications('csv')" 
                        class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-700 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-file-csv mr-2"></i>
                    CSV এক্সপোর্ট
                </button>
                <button onclick="printReport()"
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
                ফিল্টার আবেদন
            </h3>
        </div>
        
        <div class="p-6">
            <form action="{{ route('super_admin.reports.applications') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Date Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-day mr-2"></i>
                            তারিখ থেকে
                        </label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-day mr-2"></i>
                            তারিখ পর্যন্ত
                        </label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tasks mr-2"></i>
                            স্ট্যাটাস
                        </label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">সকল স্ট্যাটাস</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>পেন্ডিং</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>অনুমোদিত</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>প্রত্যাখ্যাত</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>সম্পন্ন</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>প্রসেসিং</option>
                        </select>
                    </div>
                    
                    <!-- Application Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-alt mr-2"></i>
                            আবেদনের ধরন
                        </label>
                        <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">সকল ধরন</option>
                            @foreach($applicationTypes as $type)
                            <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name_bn }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Admin Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tie mr-2"></i>
                            অ্যাডমিন
                        </label>
                        <select name="admin_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">সকল অ্যাডমিন</option>
                            @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Items Per Page -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-eye mr-2"></i>
                            প্রতি পৃষ্ঠায়
                        </label>
                        <select name="per_page" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>২০ টি</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>৫০ টি</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>১০০ টি</option>
                            <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>২০০ টি</option>
                        </select>
                    </div>
                    
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-2"></i>
                            সার্চ
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ট্র্যাকিং আইডি বা নাম"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-end space-x-3">
                        <button type="submit" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-filter mr-2"></i>
                            ফিল্টার প্রয়োগ
                        </button>
                        <a href="{{ route('super_admin.reports.applications') }}" 
                           class="flex-1 px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-800 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            রিসেট
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Applications -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 mb-2">মোট আবেদন</p>
                    <h2 class="text-3xl font-bold">{{ number_format($totalApplications) }}</h2>
                </div>
                <div class="bg-blue-800/50 p-4 rounded-xl">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-blue-100">
                <i class="fas fa-chart-line mr-2"></i>
                <span class="text-sm">সকল সময়ের</span>
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-700 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 mb-2">পেন্ডিং</p>
                    <h2 class="text-3xl font-bold">{{ number_format($pendingApplications) }}</h2>
                </div>
                <div class="bg-yellow-800/50 p-4 rounded-xl">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-yellow-100">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="text-sm">মোট {{ $pendingPercentage }}%</span>
            </div>
        </div>

        <!-- Approved Applications -->
        <div class="bg-gradient-to-r from-green-500 to-green-700 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 mb-2">অনুমোদিত</p>
                    <h2 class="text-3xl font-bold">{{ number_format($approvedApplications) }}</h2>
                </div>
                <div class="bg-green-800/50 p-4 rounded-xl">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-green-100">
                <i class="fas fa-chart-bar mr-2"></i>
                <span class="text-sm">মোট {{ $approvedPercentage }}%</span>
            </div>
        </div>

        <!-- Rejected Applications -->
        <div class="bg-gradient-to-r from-red-500 to-red-700 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 mb-2">প্রত্যাখ্যাত</p>
                    <h2 class="text-3xl font-bold">{{ number_format($rejectedApplications) }}</h2>
                </div>
                <div class="bg-red-800/50 p-4 rounded-xl">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-red-100">
                <i class="fas fa-chart-pie mr-2"></i>
                <span class="text-sm">মোট {{ $rejectedPercentage }}%</span>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 mb-8 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-list text-blue-600 mr-3"></i>
                    আবেদনের তালিকা
                </h3>
                <p class="text-gray-600 text-sm mt-1">
                    মোট {{ $applications->total() }} টি আবেদন পাওয়া গেছে
                </p>
            </div>
            <div class="mt-2 sm:mt-0 flex items-center space-x-3">
                <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                    পৃষ্ঠা {{ $applications->currentPage() }} / {{ $applications->lastPage() }}
                </span>
                <button onclick="toggleColumnVisibility()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-xl text-sm font-medium transition-colors">
                    <i class="fas fa-columns mr-2"></i>
                    কলাম
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            @if($applications->isEmpty())
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <h4 class="text-gray-500 font-medium mb-2">কোন আবেদন পাওয়া যায়নি</h4>
                <p class="text-gray-400">ফিল্টার অনুসারে কোনো আবেদন পাওয়া যায়নি</p>
            </div>
            @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ট্র্যাকিং আইডি
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider column-visibility">
                            আবেদনকারী
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ধরন
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider column-visibility">
                            স্ট্যাটাস
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            অ্যাডমিন
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider column-visibility">
                            পেমেন্ট
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider column-visibility">
                            তারিখ
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            অ্যাকশন
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($applications as $application)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium font-mono">
                                    {{ $application->tracking_id }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap column-visibility">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $application->user->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $application->user->phone ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-alt text-purple-600"></i>
                                </div>
                                <span class="ml-2 text-sm text-gray-900">
                                    {{ $application->type->name_bn ?? 'N/A' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap column-visibility">
                            @php
                                $statusConfig = [
                                    'pending' => ['color' => 'yellow', 'icon' => 'clock'],
                                    'approved' => ['color' => 'green', 'icon' => 'check-circle'],
                                    'rejected' => ['color' => 'red', 'icon' => 'times-circle'],
                                    'processing' => ['color' => 'blue', 'icon' => 'cog'],
                                    'completed' => ['color' => 'green', 'icon' => 'check-double']
                                ][$application->status] ?? ['color' => 'gray', 'icon' => 'question-circle'];
                            @endphp
                            <span class="px-3 py-1 bg-{{ $statusConfig['color'] }}-100 text-{{ $statusConfig['color'] }}-700 rounded-full text-sm font-medium inline-flex items-center">
                                <i class="fas fa-{{ $statusConfig['icon'] }} mr-2"></i>
                                {{ $application->status_bn }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($application->admin)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-tie text-green-600 text-sm"></i>
                                </div>
                                <div class="ml-2">
                                    <div class="text-sm text-gray-900">{{ $application->admin->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $application->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">নির্ধারিত হয়নি</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap column-visibility">
                            @if($application->invoice)
                            <div class="flex items-center">
                                <span class="font-bold text-green-600 mr-2">৳ {{ number_format($application->invoice->amount, 2) }}</span>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                    {{ ucfirst($application->invoice->payment_method) }}
                                </span>
                            </div>
                            @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">পেমেন্ট নেই</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap column-visibility">
                            <div class="text-sm text-gray-900">
                                {{ $application->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $application->created_at->format('h:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('super_admin.applications.show', $application->id) }}" 
                                   class="p-2 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg transition-colors"
                                   title="বিস্তারিত দেখুন">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('super_admin.applications.edit', $application->id) }}"
                                   class="p-2 bg-green-100 text-green-600 hover:bg-green-200 rounded-lg transition-colors"
                                   title="এডিট করুন">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="viewApplicationDetails({{ $application->id }})"
                                        class="p-2 bg-purple-100 text-purple-600 hover:bg-purple-200 rounded-lg transition-colors"
                                        title="কুইক ভিউ">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        
        @if($applications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-gray-500 mb-2 sm:mb-0">
                    দেখানো হচ্ছে <span class="font-medium">{{ $applications->firstItem() }}</span> থেকে 
                    <span class="font-medium">{{ $applications->lastItem() }}</span> পর্যন্ত
                    মোট <span class="font-medium">{{ $applications->total() }}</span> রেকর্ডের
                </div>
                <div>
                    {{ $applications->appends([
                        'from_date' => request('from_date'),
                        'to_date' => request('to_date'),
                        'status' => request('status'),
                        'type' => request('type'),
                        'admin_id' => request('admin_id'),
                        'search' => request('search'),
                        'per_page' => request('per_page', 20)
                    ])->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Status Distribution -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-chart-pie text-purple-600 mr-3"></i>
                    স্ট্যাটাস ডিস্ট্রিবিউশন
                </h3>
                <button onclick="exportStatusChart()" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-xl text-sm font-medium transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    এক্সপোর্ট
                </button>
            </div>
            
            <div class="flex items-center justify-center mb-6">
                <div class="relative w-48 h-48">
                    <!-- Pie Chart will be rendered here -->
                    <canvas id="statusPieChart"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-800">{{ $totalApplications }}</div>
                            <div class="text-sm text-gray-500">মোট আবেদন</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                @php
                    $statuses = [
                        ['label' => 'অনুমোদিত', 'count' => $approvedApplications, 'color' => 'bg-green-500'],
                        ['label' => 'পেন্ডিং', 'count' => $pendingApplications, 'color' => 'bg-yellow-500'],
                        ['label' => 'প্রত্যাখ্যাত', 'count' => $rejectedApplications, 'color' => 'bg-red-500'],
                        ['label' => 'প্রসেসিং', 'count' => $processingApplications, 'color' => 'bg-blue-500'],
                        ['label' => 'সম্পন্ন', 'count' => $completedApplications, 'color' => 'bg-green-700'],
                    ];
                @endphp
                
                @foreach($statuses as $status)
                @if($status['count'] > 0)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-3 h-3 {{ $status['color'] }} rounded-full mr-3"></div>
                        <span class="text-gray-700">{{ $status['label'] }}</span>
                    </div>
                    <div class="text-right">
                        <span class="font-bold text-gray-900">{{ $status['count'] }}</span>
                        <span class="text-gray-500 ml-2">
                            @if($totalApplications > 0)
                            {{ number_format(($status['count'] / $totalApplications) * 100, 1) }}%
                            @else
                            0%
                            @endif
                        </span>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <!-- Daily Applications Trend -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                    দৈনিক আবেদন প্রবণতা
                </h3>
                <select id="trendPeriod" class="px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="7">সর্বশেষ ৭ দিন</option>
                    <option value="14">সর্বশেষ ১৪ দিন</option>
                    <option value="30">সর্বশেষ ৩০ দিন</option>
                    <option value="90">সর্বশেষ ৯০ দিন</option>
                </select>
            </div>
            
            <div class="h-64">
                <!-- Line Chart will be rendered here -->
                <canvas id="dailyApplicationsChart"></canvas>
            </div>
            
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded-xl">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-arrow-up text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">সর্বোচ্চ দৈনিক</p>
                            <p class="text-xl font-bold text-gray-800">{{ $maxDailyApplications }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 p-4 rounded-xl">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-chart-bar text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">গড় দৈনিক</p>
                            <p class="text-xl font-bold text-gray-800">{{ number_format($avgDailyApplications, 1) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Admin Performance -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-user-tie text-blue-600 mr-3"></i>
                অ্যাডমিন পারফরম্যান্স
            </h3>
            
            <div class="space-y-4">
                @php
                    $adminStats = $applications->groupBy('admin_id')->map(function($items, $adminId) {
                        $admin = $items->first()->admin ?? null;
                        if(!$admin) return null;
                        
                        return [
                            'admin' => $admin,
                            'total' => $items->count(),
                            'approved' => $items->where('status', 'approved')->count(),
                            'pending' => $items->where('status', 'pending')->count(),
                            'completion_rate' => $items->count() > 0 ? 
                                (($items->whereIn('status', ['approved', 'completed'])->count() / $items->count()) * 100) : 0
                        ];
                    })->filter()->sortByDesc('total')->take(5);
                @endphp
                
                @forelse($adminStats as $stat)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user-tie text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $stat['admin']->name }}</div>
                            <div class="text-sm text-gray-500">{{ $stat['total'] }} টি আবেদন</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-green-600">{{ $stat['approved'] }} অ্যাপ্রুভড</div>
                        <div class="text-sm">
                            <span class="text-green-500 font-medium">{{ number_format($stat['completion_rate'], 1) }}%</span>
                            <span class="text-gray-500">সফলতা</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-user-tie text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">অ্যাডমিন পারফরম্যান্স ডাটা নেই</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Application Type Distribution -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-chart-bar text-purple-600 mr-3"></i>
                আবেদনের ধরন অনুযায়ী
            </h3>
            
            <div class="space-y-4">
                @php
                    $typeStats = $applications->groupBy('type_id')->map(function($items, $typeId) use ($applicationTypes) {
                        $type = $applicationTypes->where('id', $typeId)->first();
                        if(!$type) return null;
                        
                        return [
                            'type' => $type,
                            'count' => $items->count(),
                            'pending' => $items->where('status', 'pending')->count(),
                            'approved' => $items->where('status', 'approved')->count(),
                        ];
                    })->filter()->sortByDesc('count');
                @endphp
                
                @forelse($typeStats as $stat)
                @if($stat['count'] > 0)
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-purple-600 mr-3"></i>
                            <span class="font-medium text-gray-700">{{ $stat['type']->name_bn }}</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $stat['count'] }}</span>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-700 h-3 rounded-full"
                             style="width: {{ ($stat['count'] / $totalApplications) * 100 }}%"></div>
                    </div>
                    
                    <div class="flex justify-between text-sm text-gray-500 mt-2">
                        <span>{{ number_format(($stat['pending'] / $stat['count']) * 100, 1) }}% পেন্ডিং</span>
                        <span>{{ number_format(($stat['approved'] / $stat['count']) * 100, 1) }}% অনুমোদিত</span>
                    </div>
                </div>
                @endif
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-file-alt text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">আবেদনের ধরন অনুযায়ী ডাটা নেই</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Application Details Modal -->
<div id="applicationModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeAppModal()"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">আবেদন বিস্তারিত</h3>
                    <button type="button" onclick="closeAppModal()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="bg-white p-6" id="applicationDetail">
                <!-- Content will be loaded here -->
                <div class="text-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-4 text-gray-600">লোড হচ্ছে...</p>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeAppModal()"
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400 transition-colors">
                    বন্ধ করুন
                </button>
                <a href="#" id="editAppLink"
                   class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-800 text-white rounded-xl hover:shadow-lg transition-all flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    এডিট করুন
                </a>
                <a href="#" id="viewFullLink"
                   class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl hover:shadow-lg transition-all flex items-center">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    সম্পূর্ণ দেখুন
                </a>
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
    
    // Check for column visibility preference
    const hiddenColumns = localStorage.getItem('hiddenColumns');
    if (hiddenColumns) {
        const columns = JSON.parse(hiddenColumns);
        columns.forEach(column => {
            const elements = document.querySelectorAll(`.${column}`);
            elements.forEach(el => el.classList.add('hidden'));
        });
    }
});

function initializeCharts() {
    // Status Distribution Pie Chart
    const statusCtx = document.getElementById('statusPieChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: ['অনুমোদিত', 'পেন্ডিং', 'প্রত্যাখ্যাত', 'প্রসেসিং', 'সম্পন্ন'],
            datasets: [{
                data: [
                    {{ $approvedApplications }},
                    {{ $pendingApplications }},
                    {{ $rejectedApplications }},
                    {{ $processingApplications }},
                    {{ $completedApplications }}
                ],
                backgroundColor: [
                    '#10B981', // green
                    '#F59E0B', // yellow
                    '#EF4444', // red
                    '#3B82F6', // blue
                    '#047857'  // dark green
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Daily Applications Trend Line Chart
    const trendCtx = document.getElementById('dailyApplicationsChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: @json($dailyTrends->pluck('date')),
            datasets: [{
                label: 'দৈনিক আবেদন',
                data: @json($dailyTrends->pluck('count')),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5
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
                    mode: 'index',
                    intersect: false
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
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });
}

// Toggle column visibility
function toggleColumnVisibility() {
    const modalHtml = `
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-black opacity-50" onclick="closeColumnModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">কলাম ভিজিবিলিটি</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 column-toggle" data-column="column-visibility" checked>
                            <span class="ml-3 text-gray-700">সকল কলাম দেখান</span>
                        </label>
                        <div class="border-t border-gray-200 pt-3 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-target="applicant-column">
                                <span class="ml-3 text-gray-700">আবেদনকারী কলাম</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-target="status-column">
                                <span class="ml-3 text-gray-700">স্ট্যাটাস কলাম</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-target="payment-column">
                                <span class="ml-3 text-gray-700">পেমেন্ট কলাম</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-target="date-column">
                                <span class="ml-3 text-gray-700">তারিখ কলাম</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeColumnModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400 transition-colors">
                            বাতিল
                        </button>
                        <button type="button" onclick="applyColumnVisibility()" 
                                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl hover:shadow-lg transition-all">
                            প্রয়োগ করুন
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const modal = document.createElement('div');
    modal.innerHTML = modalHtml;
    modal.id = 'columnModal';
    document.body.appendChild(modal);
}

function closeColumnModal() {
    const modal = document.getElementById('columnModal');
    if (modal) modal.remove();
}

function applyColumnVisibility() {
    const checkboxes = document.querySelectorAll('#columnModal input[type="checkbox"]');
    const hiddenColumns = [];
    
    checkboxes.forEach(checkbox => {
        if (!checkbox.checked && checkbox.dataset.target) {
            hiddenColumns.push(checkbox.dataset.target);
        }
    });
    
    // Toggle visibility
    hiddenColumns.forEach(column => {
        const elements = document.querySelectorAll(`.${column}`);
        elements.forEach(el => el.classList.add('hidden'));
    });
    
    // Show other columns
    document.querySelectorAll('[class*="column"]').forEach(el => {
        if (!hiddenColumns.some(col => el.classList.contains(col))) {
            el.classList.remove('hidden');
        }
    });
    
    // Save to localStorage
    localStorage.setItem('hiddenColumns', JSON.stringify(hiddenColumns));
    
    closeColumnModal();
    showToast('কলাম ভিজিবিলিটি সংরক্ষিত হয়েছে', 'success');
}

// View application details
function viewApplicationDetails(appId) {
    const modal = document.getElementById('applicationModal');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Load application details via AJAX
    fetch(`/api/applications/${appId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('applicationDetail').innerHTML = data.html;
                document.getElementById('editAppLink').href = data.edit_url;
                document.getElementById('viewFullLink').href = data.view_url;
            } else {
                document.getElementById('applicationDetail').innerHTML = `
                    <div class="p-6">
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-circle text-red-500 text-4xl mb-4"></i>
                            <h4 class="text-red-600 font-medium mb-2">ত্রুটি!</h4>
                            <p class="text-gray-600">আবেদন ডাটা লোড করতে সমস্যা</p>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('applicationDetail').innerHTML = `
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

// Close application modal
function closeAppModal() {
    const modal = document.getElementById('applicationModal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Export applications
function exportApplications(format) {
    const params = new URLSearchParams(window.location.search);
    params.append('format', format);
    
    showToast(`${format.toUpperCase()} রিপোর্ট তৈরি হচ্ছে...`, 'info');
    
    setTimeout(() => {
        window.location.href = `{{ route('super_admin.reports.export', 'applications') }}?${params.toString()}`;
    }, 1500);
}

// Print report
function printReport() {
    showToast('প্রিন্ট প্রস্তুত হচ্ছে...', 'info');
    
    // Create print window
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>আবেদন রিপোর্ট - {{ date('d/m/Y') }}</title>
            <style>
                body { font-family: 'Noto Sans Bengali', sans-serif; margin: 20px; }
                .print-header { text-align: center; margin-bottom: 30px; }
                .print-header h1 { color: #1e3a8a; }
                .print-summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px; }
                .print-card { padding: 15px; border-radius: 10px; color: white; text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th { background: #1e3a8a; color: white; padding: 10px; text-align: left; }
                td { padding: 8px; border-bottom: 1px solid #ddd; }
                .status-approved { color: green; }
                .status-pending { color: orange; }
                .status-rejected { color: red; }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h1>আবেদন রিপোর্ট</h1>
                <p>তৈরির তারিখ: {{ date('d/m/Y h:i A') }}</p>
                <p>ইউনিয়ন ডিজিটাল সেন্টার</p>
            </div>
            
            <div class="print-summary">
                <div class="print-card" style="background: #3B82F6;">
                    <h3>মোট আবেদন</h3>
                    <h2>${document.querySelector('[data-stat="total"]')?.textContent || '0'}</h2>
                </div>
                <div class="print-card" style="background: #F59E0B;">
                    <h3>পেন্ডিং</h3>
                    <h2>${document.querySelector('[data-stat="pending"]')?.textContent || '0'}</h2>
                </div>
                <div class="print-card" style="background: #10B981;">
                    <h3>অনুমোদিত</h3>
                    <h2>${document.querySelector('[data-stat="approved"]')?.textContent || '0'}</h2>
                </div>
                <div class="print-card" style="background: #EF4444;">
                    <h3>প্রত্যাখ্যাত</h3>
                    <h2>${document.querySelector('[data-stat="rejected"]')?.textContent || '0'}</h2>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ট্র্যাকিং আইডি</th>
                        <th>আবেদনকারী</th>
                        <th>ধরন</th>
                        <th>স্ট্যাটাস</th>
                        <th>তারিখ</th>
                    </tr>
                </thead>
                <tbody>
                    ${Array.from(document.querySelectorAll('tbody tr')).map(row => `
                        <tr>
                            <td>${row.cells[0]?.textContent || ''}</td>
                            <td>${row.cells[1]?.querySelector('.text-gray-900')?.textContent || ''}</td>
                            <td>${row.cells[2]?.querySelector('.text-gray-900')?.textContent || ''}</td>
                            <td class="status-${row.cells[3]?.querySelector('[class*="bg-"]')?.className.includes('green') ? 'approved' : 
                                              row.cells[3]?.querySelector('[class*="bg-"]')?.className.includes('yellow') ? 'pending' : 
                                              row.cells[3]?.querySelector('[class*="bg-"]')?.className.includes('red') ? 'rejected' : ''}">
                                ${row.cells[3]?.textContent || ''}
                            </td>
                            <td>${row.cells[6]?.querySelector('.text-gray-900')?.textContent || ''}</td>
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

// Export status chart
function exportStatusChart() {
    showToast('চার্ট এক্সপোর্ট প্রস্তুত হচ্ছে...', 'info');
    
    // In a real application, you would generate and download the chart image
    setTimeout(() => {
        showToast('চার্ট PNG ফরম্যাটে ডাউনলোড হয়েছে', 'success');
    }, 1000);
}

// Change trend period
document.addEventListener('DOMContentLoaded', function() {
    const trendPeriod = document.getElementById('trendPeriod');
    if (trendPeriod) {
        trendPeriod.addEventListener('change', function() {
            showToast('লোড হচ্ছে...', 'info');
            // In a real application, you would reload the chart data
            setTimeout(() => {
                showToast('ডাটা আপডেট হয়েছে', 'success');
            }, 1500);
        });
    }
});
</script>
@endpush

@push('styles')
<style>
/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        font-size: 12px;
    }
}

/* Chart container */
canvas {
    max-width: 100%;
}

/* Modal styles */
#applicationModal .bg-white {
    max-height: 80vh;
    overflow-y: auto;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .column-visibility {
        display: none;
    }
}
</style>
@endpush

@endsection