@extends('layouts.super-admin')

@section('title', 'লগ বিস্তারিত - সুপার অ্যাডমিন')

@section('content')
<div class="container-fluid">
    <div class="mb-6 animate-fade-in">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                    লগ বিস্তারিত
                </h2>
                <p class="text-gray-600 mt-1">কার্যক্রমের সম্পূর্ণ বিবরণ</p>
            </div>
            <a href="{{ route('super_admin.activity_logs.index') }}" 
               class="bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-700 px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-sm hover:shadow-md flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> পিছনে
            </a>
        </div>
    </div>
    
    <!-- Log Details Card -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Basic Information -->
        <div class="space-y-6">
            <!-- Basic Info Card -->
            <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl shadow-lg">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                        মৌলিক তথ্য
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                            <div class="font-medium text-gray-700">লগ আইডি</div>
                            <div class="font-bold text-blue-800">#{{ $activityLog->id }}</div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <div class="font-medium text-gray-700">তারিখ ও সময়</div>
                            <div class="font-bold text-gray-800">{{ $activityLog->created_at->format('d/m/Y h:i:s A') }}</div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl">
                            <div class="font-medium text-gray-700">ব্যবহারকারী</div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-r 
                                    @if($activityLog->user_role == 'super_admin') from-red-500 to-rose-600
                                    @elseif($activityLog->user_role == 'admin') from-blue-500 to-blue-600
                                    @elseif($activityLog->user_role == 'secretary') from-green-500 to-emerald-600
                                    @elseif($activityLog->user_role == 'citizen') from-purple-500 to-purple-600
                                    @else from-gray-500 to-gray-600 @endif
                                    flex items-center justify-center text-white font-bold mr-2">
                                    {{ substr($activityLog->user_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-purple-800">{{ $activityLog->user_name }}</div>
                                    <div class="text-xs text-gray-600">{{ $activityLog->user_role }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl">
                            <div class="font-medium text-gray-700">কার্যক্রম</div>
                            <div>
                                <span class="px-3 py-1.5 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    @if($activityLog->action == 'CREATE') bg-emerald-100 text-emerald-800
                                    @elseif($activityLog->action == 'UPDATE') bg-blue-100 text-blue-800
                                    @elseif($activityLog->action == 'DELETE') bg-red-100 text-red-800
                                    @elseif($activityLog->action == 'LOGIN') bg-green-100 text-green-800
                                    @elseif($activityLog->action == 'LOGOUT') bg-gray-100 text-gray-800
                                    @elseif($activityLog->action == 'APPROVE') bg-green-100 text-green-800
                                    @elseif($activityLog->action == 'REJECT') bg-red-100 text-red-800
                                    @else bg-indigo-100 text-indigo-800 @endif">
                                    {{ $activityLog->action }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-amber-50 to-amber-100 rounded-xl">
                            <div class="font-medium text-gray-700">মডিউল</div>
                            <div>
                                <span class="px-3 py-1.5 inline-flex text-sm leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                    {{ $activityLog->module }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                            <div class="font-medium text-gray-700 mb-2">বর্ণনা</div>
                            <div class="font-bold text-blue-800">{{ $activityLog->description }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Technical Details Card -->
            <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl shadow-lg">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-terminal text-gray-600 mr-3"></i>
                        প্রযুক্তিগত তথ্য
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <div class="font-medium text-gray-700 mb-1">IP ঠিকানা</div>
                            <div class="font-mono text-sm bg-gray-200 text-gray-800 px-3 py-2 rounded-lg">
                                {{ $activityLog->ip_address }}
                            </div>
                        </div>
                        
                        <div class="p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <div class="font-medium text-gray-700 mb-1">ব্রাউজার তথ্য</div>
                            <div class="text-sm text-gray-600">
                                <code class="bg-gray-200 text-gray-800 px-2 py-1 rounded text-xs break-all">
                                    {{ $activityLog->user_agent }}
                                </code>
                            </div>
                        </div>
                        
                        <div class="p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <div class="font-medium text-gray-700 mb-1">URL</div>
                            <div class="text-sm text-gray-800 truncate">
                                {{ $activityLog->url }}
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <div class="font-medium text-gray-700">পদ্ধতি</div>
                            <div>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    @if($activityLog->method == 'GET') bg-blue-100 text-blue-800
                                    @elseif($activityLog->method == 'POST') bg-green-100 text-green-800
                                    @elseif($activityLog->method == 'PUT' || $activityLog->method == 'PATCH') bg-yellow-100 text-yellow-800
                                    @elseif($activityLog->method == 'DELETE') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $activityLog->method }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Data Changes -->
        <div class="space-y-6">
            @if($activityLog->old_data || $activityLog->new_data)
            <!-- Data Changes Card -->
            <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl shadow-lg">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-exchange-alt text-green-600 mr-3"></i>
                        ডেটা পরিবর্তন
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @if($activityLog->old_data)
                        <div>
                            <div class="flex items-center mb-3">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <h4 class="font-bold text-gray-700">পুরাতন ডেটা</h4>
                            </div>
                            <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-100 rounded-xl p-4">
                                <pre class="text-sm text-red-800 overflow-x-auto max-h-64"><code>{{ json_encode($activityLog->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                            </div>
                        </div>
                        @endif
                        
                        @if($activityLog->new_data)
                        <div>
                            <div class="flex items-center mb-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <h4 class="font-bold text-gray-700">নতুন ডেটা</h4>
                            </div>
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-xl p-4">
                                <pre class="text-sm text-green-800 overflow-x-auto max-h-64"><code>{{ json_encode($activityLog->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                            </div>
                        </div>
                        @endif
                        
                        @if(!$activityLog->old_data && !$activityLog->new_data)
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-database text-4xl"></i>
                            </div>
                            <p class="text-gray-500">কোনো ডেটা পরিবর্তন পাওয়া যায়নি</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            <!-- User Information Card -->
            <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl shadow-lg">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-user-circle text-purple-600 mr-3"></i>
                        ব্যবহারকারী তথ্য
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-r 
                            @if($activityLog->user_role == 'super_admin') from-red-500 to-rose-600
                            @elseif($activityLog->user_role == 'admin') from-blue-500 to-blue-600
                            @elseif($activityLog->user_role == 'secretary') from-green-500 to-emerald-600
                            @elseif($activityLog->user_role == 'citizen') from-purple-500 to-purple-600
                            @else from-gray-500 to-gray-600 @endif
                            flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                            {{ substr($activityLog->user_name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <h4 class="text-xl font-bold text-gray-800">{{ $activityLog->user_name }}</h4>
                            <div class="flex items-center mt-1">
                                <span class="px-3 py-1 bg-gradient-to-r 
                                    @if($activityLog->user_role == 'super_admin') from-red-100 to-rose-200 text-red-800
                                    @elseif($activityLog->user_role == 'admin') from-blue-100 to-blue-200 text-blue-800
                                    @elseif($activityLog->user_role == 'secretary') from-green-100 to-emerald-200 text-green-800
                                    @elseif($activityLog->user_role == 'citizen') from-purple-100 to-purple-200 text-purple-800
                                    @else from-gray-100 to-gray-200 text-gray-800 @endif
                                    rounded-full text-sm font-medium">
                                    {{ ucfirst($activityLog->user_role) }}
                                </span>
                                <span class="mx-2 text-gray-400">•</span>
                                <span class="text-sm text-gray-600">{{ $activityLog->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-600 mb-1">লগ আইডি</div>
                                <div class="font-bold text-gray-800">#{{ $activityLog->id }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 mb-1">তারিখ</div>
                                <div class="font-bold text-gray-800">{{ $activityLog->created_at->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 mb-1">সময়</div>
                                <div class="font-bold text-gray-800">{{ $activityLog->created_at->format('h:i:s A') }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 mb-1">পদ্ধতি</div>
                                <div class="font-bold text-gray-800">{{ $activityLog->method }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl shadow-lg">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-bolt text-amber-600 mr-3"></i>
                        দ্রুত কার্যক্রম
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('super_admin.activity_logs.index') }}" 
                           class="bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 border border-blue-200 rounded-xl p-4 text-center transition-all duration-300 group">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-list text-white"></i>
                            </div>
                            <div class="font-medium text-blue-800">লগ তালিকা</div>
                        </a>
                        
                        @if($activityLog->user && $activityLog->user->id)
                        <a href="#" 
                           class="bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 border border-purple-200 rounded-xl p-4 text-center transition-all duration-300 group">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="font-medium text-purple-800">ব্যবহারকারী</div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection