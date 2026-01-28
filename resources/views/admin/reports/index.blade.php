@extends('layouts.admin')

@section('title', 'Reports Dashboard')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Reports Dashboard</h1>
        <p class="text-gray-600 mt-2">Generate and analyze various reports</p>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue Report Card -->
        <a href="{{ route('admin.reports.revenue') }}" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-200 hover:border-blue-500">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Revenue</div>
                        <div class="text-2xl font-bold text-gray-800 mt-2">৳ {{ number_format(\App\Models\Invoice::where('payment_status', 'paid')->sum('amount'), 2) }}</div>
                        <div class="text-sm text-gray-600 mt-1">{{ \App\Models\Invoice::where('payment_status', 'paid')->count() }} Total Payments</div>
                    </div>
                    <div class="text-blue-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- Applications Report Card -->
        <a href="{{ route('admin.reports.applications') }}" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-200 hover:border-green-500">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Applications</div>
                        <div class="text-2xl font-bold text-gray-800 mt-2">{{ \App\Models\Application::count() }}</div>
                        <div class="text-sm text-gray-600 mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">
                                {{ \App\Models\Application::where('status', 'pending')->count() }} Pending
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ \App\Models\Application::where('status', 'approved')->count() }} Approved
                            </span>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- Users Report Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Users</div>
                        <div class="text-2xl font-bold text-gray-800 mt-2">{{ \App\Models\User::count() }}</div>
                        <div class="text-sm text-gray-600 mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                {{ \App\Models\User::where('role', 'citizen')->count() }} Citizen
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ \App\Models\User::whereIn('role', ['admin', 'super_admin'])->count() }} Staff
                            </span>
                        </div>
                    </div>
                    <div class="text-purple-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 5.197v-1a6 6 0 00-9-5.197M9 14.354a4 4 0 100-5.292"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificates Report Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Certificates</div>
                        <div class="text-2xl font-bold text-gray-800 mt-2">{{ \App\Models\CertificateType::count() }}</div>
                        <div class="text-sm text-gray-600 mt-1">Certificate Types</div>
                    </div>
                    <div class="text-red-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Revenue Reports -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Revenue Reports</h2>
                <p class="text-gray-600 mb-6">Generate detailed revenue reports with payment history and analysis.</p>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.reports.revenue', ['period' => 'today']) }}" 
                       class="flex items-center justify-between p-4 bg-gray-50 hover:bg-blue-50 rounded-lg transition duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800 group-hover:text-blue-600">Today's Revenue</div>
                                <div class="text-sm text-gray-500">Daily income report</div>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('admin.reports.revenue', ['period' => 'week']) }}" 
                       class="flex items-center justify-between p-4 bg-gray-50 hover:bg-blue-50 rounded-lg transition duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800 group-hover:text-green-600">Weekly Report</div>
                                <div class="text-sm text-gray-500">Last 7 days analysis</div>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('admin.reports.revenue', ['period' => 'month']) }}" 
                       class="flex items-center justify-between p-4 bg-gray-50 hover:bg-blue-50 rounded-lg transition duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800 group-hover:text-purple-600">Monthly Report</div>
                                <div class="text-sm text-gray-500">Last 30 days summary</div>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Application Reports -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Application Reports</h2>
                <p class="text-gray-600 mb-6">Track and analyze certificate applications with detailed statistics.</p>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.reports.applications', ['period' => 'today']) }}" 
                       class="flex items-center justify-between p-4 bg-gray-50 hover:bg-green-50 rounded-lg transition duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800 group-hover:text-yellow-600">Today's Applications</div>
                                <div class="text-sm text-gray-500">New applications today</div>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('admin.reports.applications', ['period' => 'week']) }}" 
                       class="flex items-center justify-between p-4 bg-gray-50 hover:bg-green-50 rounded-lg transition duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800 group-hover:text-blue-600">Weekly Applications</div>
                                <div class="text-sm text-gray-500">Applications in last week</div>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('admin.reports.applications', ['period' => 'month']) }}" 
                       class="flex items-center justify-between p-4 bg-gray-50 hover:bg-green-50 rounded-lg transition duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800 group-hover:text-red-600">Monthly Summary</div>
                                <div class="text-sm text-gray-500">Monthly application trends</div>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Export Options</h2>
            <p class="text-gray-600 mb-6">Export reports in different formats for further analysis.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Revenue Export -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-bold text-gray-800 mb-2">Revenue Export</h3>
                    <p class="text-sm text-gray-600 mb-4">Export revenue data in CSV or PDF format</p>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.reports.export', ['type' => 'revenue', 'format' => 'csv', 'period' => 'month']) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            CSV Export
                        </a>
                        <a href="{{ route('admin.reports.export', ['type' => 'revenue', 'format' => 'pdf', 'period' => 'month']) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg hover:from-red-600 hover:to-rose-700 transition duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                            </svg>
                            PDF Export
                        </a>
                    </div>
                </div>
                
                <!-- Applications Export -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-bold text-gray-800 mb-2">Applications Export</h3>
                    <p class="text-sm text-gray-600 mb-4">Export application data in CSV or PDF format</p>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.reports.export', ['type' => 'applications', 'format' => 'csv', 'period' => 'month']) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            CSV Export
                        </a>
                        <a href="{{ route('admin.reports.export', ['type' => 'applications', 'format' => 'pdf', 'period' => 'month']) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg hover:from-red-600 hover:to-rose-700 transition duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                            </svg>
                            PDF Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection