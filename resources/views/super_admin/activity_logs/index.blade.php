@extends('layouts.super-admin')

@section('title', 'অ্যাক্টিভিটি লগ - সুপার অ্যাডমিন')

@section('content')
<div class="container-fluid">
    <div class="mb-8 animate-fade-in">
        <!-- Header with Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 mb-1">মোট লগ</p>
                        <h3 class="text-2xl font-bold text-blue-800">{{ $stats['total_logs'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-database text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center text-xs text-blue-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>আজকের লগ: {{ $stats['today_logs'] }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-50 to-emerald-100 border border-green-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 mb-1">অ্যাক্টিভ ইউজার</p>
                        <h3 class="text-2xl font-bold text-green-800">{{ $stats['top_users']->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center text-xs text-green-600">
                        <i class="fas fa-history mr-1"></i>
                        <span>সকল ইউজার কার্যক্রম</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600 mb-1">মডিউল</p>
                        <h3 class="text-2xl font-bold text-purple-800">{{ $stats['module_counts']->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-cubes text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center text-xs text-purple-600">
                        <i class="fas fa-layer-group mr-1"></i>
                        <span>বিভিন্ন মডিউল</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-600 mb-1">কার্যক্রম</p>
                        <h3 class="text-2xl font-bold text-amber-800">{{ $actions->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-amber-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-tasks text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center text-xs text-amber-600">
                        <i class="fas fa-bolt mr-1"></i>
                        <span>বিভিন্ন ধরনের কার্যক্রম</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Card -->
        <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl shadow-lg mb-8">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-3"></i>
                    লগ ফিল্টার
                </h3>
                <p class="text-sm text-gray-500 mt-1">কার্যক্রম অনুসন্ধান ও ফিল্টার করুন</p>
            </div>
            
            <div class="p-6">
                <form method="GET" action="{{ route('super_admin.activity_logs.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search mr-2 text-blue-600"></i>অনুসন্ধান
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"
                                   placeholder="ব্যবহারকারী, বর্ণনা, মডিউল...">
                        </div>
                        
                        <!-- Role Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-tag mr-2 text-blue-600"></i>রোল
                            </label>
                            <select name="user_role" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                                <option value="">সকল রোল</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ request('user_role') == $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Module Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-cube mr-2 text-blue-600"></i>মডিউল
                            </label>
                            <select name="module" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                                <option value="">সকল মডিউল</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                        {{ $module }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Action Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-bolt mr-2 text-blue-600"></i>কার্যক্রম
                            </label>
                            <select name="action" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                                <option value="">সকল কার্যক্রম</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ $action }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>তারিখ থেকে
                            </label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                        </div>
                        
                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>তারিখ পর্যন্ত
                            </label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-end space-x-3">
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center">
                                <i class="fas fa-search mr-2"></i>ফিল্টার করুন
                            </button>
                            <a href="{{ route('super_admin.activity_logs.index') }}" 
                               class="px-4 py-3 bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-700 rounded-xl font-medium transition-all duration-300 shadow-sm hover:shadow-md flex items-center justify-center">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Export and Clear Buttons -->
                    <div class="flex justify-between pt-4 border-t border-gray-100">
                        <div class="flex space-x-3">
                            <a href="{{ route('super_admin.activity_logs.export', request()->query()) }}" 
                               class="bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-md hover:shadow-lg flex items-center">
                                <i class="fas fa-file-export mr-2"></i>CSV এক্সপোর্ট
                            </a>
                            <button type="button" onclick="confirmClearOldLogs()"
                                    class="bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-md hover:shadow-lg flex items-center">
                                <i class="fas fa-trash-alt mr-2"></i>পুরোনো লগ ডিলিট
                            </button>
                        </div>
                        
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ $logs->total() }} টি রেকর্ড পাওয়া গেছে
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Activity Logs Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 animate-fade-in">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-history text-blue-600 mr-3"></i>
                কার্যক্রমের লগ
                <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">
                    {{ $logs->total() }} লগ
                </span>
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>তারিখ ও সময়
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>ব্যবহারকারী
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user-tag mr-2"></i>রোল
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-bolt mr-2"></i>কার্যক্রম
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cube mr-2"></i>মডিউল
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-file-alt mr-2"></i>বর্ণনা
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-globe mr-2"></i>IP ঠিকানা
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-eye mr-2"></i>বিস্তারিত
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition-all duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $log->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $log->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $log->created_at->format('h:i:s A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-r 
                                    @if($log->user_role == 'super_admin') from-red-500 to-rose-600
                                    @elseif($log->user_role == 'admin') from-blue-500 to-blue-600
                                    @elseif($log->user_role == 'secretary') from-green-500 to-emerald-600
                                    @elseif($log->user_role == 'citizen') from-purple-500 to-purple-600
                                    @else from-gray-500 to-gray-600 @endif
                                    flex items-center justify-center text-white font-bold shadow-sm">
                                    {{ substr($log->user_name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $log->user_name }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if($log->user && $log->user->email)
                                            {{ $log->user->email }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($log->user_role == 'super_admin') bg-red-100 text-red-800
                                @elseif($log->user_role == 'admin') bg-blue-100 text-blue-800
                                @elseif($log->user_role == 'secretary') bg-green-100 text-green-800
                                @elseif($log->user_role == 'citizen') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($log->user_role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($log->action == 'CREATE') bg-emerald-100 text-emerald-800
                                @elseif($log->action == 'UPDATE') bg-blue-100 text-blue-800
                                @elseif($log->action == 'DELETE') bg-red-100 text-red-800
                                @elseif($log->action == 'LOGIN') bg-green-100 text-green-800
                                @elseif($log->action == 'LOGOUT') bg-gray-100 text-gray-800
                                @elseif($log->action == 'APPROVE') bg-green-100 text-green-800
                                @elseif($log->action == 'REJECT') bg-red-100 text-red-800
                                @elseif($log->action == 'PAYMENT') bg-yellow-100 text-yellow-800
                                @else bg-indigo-100 text-indigo-800 @endif">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $log->module }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 truncate max-w-xs" title="{{ $log->description }}">
                                {{ $log->description }}
                            </div>
                            @if($log->url)
                                <div class="text-xs text-gray-500 truncate max-w-xs">
                                    {{ $log->method }}: {{ Str::limit($log->url, 50) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">{{ $log->ip_address }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('super_admin.activity_logs.show', $log) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200 flex items-center">
                                <i class="fas fa-eye mr-1"></i> বিস্তারিত
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p class="text-lg font-medium text-gray-500">কোনো লগ পাওয়া যায়নি</p>
                                <p class="text-sm text-gray-400 mt-1">কোনো কার্যক্রম রেকর্ড করা হয়নি</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    {{ $logs->firstItem() }} থেকে {{ $logs->lastItem() }} পর্যন্ত দেখানো হচ্ছে
                    <span class="font-medium">(মোট {{ $logs->total() }} টি)</span>
                </div>
                <div class="flex space-x-2">
                    {{ $logs->withQueryString()->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Sidebar Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <!-- Top Active Users -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl shadow-lg">
                <div class="p-5 border-b border-gray-100">
                    <h4 class="font-bold text-gray-800 flex items-center">
                        <i class="fas fa-user-chart text-blue-600 mr-3"></i>সর্বাধিক সক্রিয় ব্যবহারকারী
                    </h4>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        @foreach($stats['top_users'] as $user)
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-white hover:from-blue-50 hover:to-blue-100 rounded-xl transition-all duration-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold mr-3">
                                    {{ substr($user->user_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $user->user_name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->user_role ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 text-sm font-medium rounded-full">
                                {{ $user->total }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Module Distribution -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl shadow-lg">
                <div class="p-5 border-b border-gray-100">
                    <h4 class="font-bold text-gray-800 flex items-center">
                        <i class="fas fa-chart-pie text-green-600 mr-3"></i>মডিউল বণ্টন
                    </h4>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        @foreach($stats['module_counts'] as $module)
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-white hover:from-green-50 hover:to-green-100 rounded-xl transition-all duration-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center text-white mr-3">
                                    <i class="fas fa-cube"></i>
                                </div>
                                <div class="font-medium text-gray-800">{{ $module->module }}</div>
                            </div>
                            <span class="px-3 py-1 bg-gradient-to-r from-green-100 to-emerald-200 text-emerald-800 text-sm font-medium rounded-full">
                                {{ $module->total }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl shadow-lg">
                <div class="p-5 border-b border-gray-100">
                    <h4 class="font-bold text-gray-800 flex items-center">
                        <i class="fas fa-clock text-amber-600 mr-3"></i>সাম্প্রতিক কার্যক্রম
                    </h4>
                </div>
                <div class="p-4 max-h-80 overflow-y-auto">
                    <div class="space-y-4">
                        @foreach($stats['recent_activities'] as $activity)
                        <div class="border-l-4 
                            @if($activity->action == 'LOGIN') border-green-500
                            @elseif($activity->action == 'CREATE') border-blue-500
                            @elseif($activity->action == 'APPROVE') border-emerald-500
                            @elseif($activity->action == 'REJECT') border-red-500
                            @else border-gray-400 @endif
                            pl-4 py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center mb-1">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-r 
                                            @if($activity->user_role == 'admin') from-blue-500 to-blue-600
                                            @elseif($activity->user_role == 'citizen') from-purple-500 to-purple-600
                                            @else from-gray-500 to-gray-600 @endif
                                            flex items-center justify-center text-white text-xs font-bold mr-2">
                                            {{ substr($activity->user_name, 0, 1) }}
                                        </div>
                                        <div class="font-medium text-gray-800">{{ $activity->user_name }}</div>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $activity->description }}</p>
                                </div>
                                <div class="text-xs text-gray-500 whitespace-nowrap ml-2">
                                    {{ $activity->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="mt-2 flex items-center text-xs">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded mr-2">{{ $activity->module }}</span>
                                <span class="px-2 py-1 
                                    @if($activity->action == 'LOGIN') bg-green-100 text-green-700
                                    @elseif($activity->action == 'CREATE') bg-blue-100 text-blue-700
                                    @elseif($activity->action == 'APPROVE') bg-emerald-100 text-emerald-700
                                    @elseif($activity->action == 'REJECT') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif
                                    rounded">
                                    {{ $activity->action }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal for Clearing Old Logs -->
<div id="clearOldLogsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 animate-slide-down">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-rose-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">পুরোনো লগ ডিলিট</h3>
                    <p class="text-sm text-gray-600">অনুগ্রহ করে নিশ্চিত করুন</p>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-red-50 to-rose-100 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-center text-red-700 mb-2">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="font-medium">সতর্কতা</span>
                </div>
                <p class="text-sm text-red-600">
                    আপনি কি নিশ্চিত যে ৩০ দিনের পুরোনো সকল লগ ডিলিট করতে চান? 
                    এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।
                </p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideModal()"
                        class="px-5 py-2.5 bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-700 rounded-xl font-medium transition-all duration-300">
                    বাতিল
                </button>
                <form action="{{ route('super_admin.activity_logs.clear') }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800 text-white rounded-xl font-medium transition-all duration-300 shadow-md hover:shadow-lg">
                        হ্যাঁ, ডিলিট করুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmClearOldLogs() {
    const modal = document.getElementById('clearOldLogsModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('opacity-100');
    }, 10);
}

function hideModal() {
    const modal = document.getElementById('clearOldLogsModal');
    modal.classList.add('hidden');
    modal.classList.remove('opacity-100');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('clearOldLogsModal');
    if (event.target === modal) {
        hideModal();
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('clearOldLogsModal');
        if (!modal.classList.contains('hidden')) {
            hideModal();
        }
    }
});

// Auto-refresh every 60 seconds (optional)
// setTimeout(() => {
//     window.location.reload();
// }, 60000);
</script>
@endpush

@endsection