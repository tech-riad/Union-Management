@extends('layouts.admin')

@section('title', 'Revenue Report')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Revenue Report</h1>
                <p class="text-gray-600 mt-2">Track and analyze revenue from certificate payments</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Filter by Period</h2>
            <div class="flex space-x-2">
                <div class="text-sm text-gray-600">Current: <span class="font-bold capitalize">{{ $period }}</span></div>
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.reports.revenue', ['period' => 'today']) }}" 
               class="p-4 text-center rounded-lg border {{ $period == 'today' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-gray-200 hover:bg-gray-50' }}">
                <div class="text-2xl font-bold">Today</div>
                <div class="text-sm text-gray-600 mt-1">Daily revenue</div>
            </a>
            
            <a href="{{ route('admin.reports.revenue', ['period' => 'week']) }}" 
               class="p-4 text-center rounded-lg border {{ $period == 'week' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-gray-200 hover:bg-gray-50' }}">
                <div class="text-2xl font-bold">Week</div>
                <div class="text-sm text-gray-600 mt-1">Last 7 days</div>
            </a>
            
            <a href="{{ route('admin.reports.revenue', ['period' => 'month']) }}" 
               class="p-4 text-center rounded-lg border {{ $period == 'month' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-gray-200 hover:bg-gray-50' }}">
                <div class="text-2xl font-bold">Month</div>
                <div class="text-sm text-gray-600 mt-1">Last 30 days</div>
            </a>
            
            <a href="{{ route('admin.reports.revenue', ['period' => 'year']) }}" 
               class="p-4 text-center rounded-lg border {{ $period == 'year' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-gray-200 hover:bg-gray-50' }}">
                <div class="text-2xl font-bold">Year</div>
                <div class="text-sm text-gray-600 mt-1">Year to date</div>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium opacity-90">Total Revenue</div>
                    <div class="text-3xl font-bold mt-2">৳ {{ number_format($data['total'] ?? 0, 2) }}</div>
                    <div class="text-sm opacity-90 mt-1">{{ $data['count'] ?? 0 }} payments</div>
                </div>
                <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            @if(isset($data['previous']) && $data['previous'] > 0)
                @php
                    $change = (($data['total'] - $data['previous']) / $data['previous']) * 100;
                @endphp
                <div class="mt-4 text-sm">
                    @if($change > 0)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-200 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                            {{ round($change, 1) }}% increase
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-200 text-red-800">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                            {{ round(abs($change), 1) }}% decrease
                        </span>
                    @endif
                    <span class="ml-2 opacity-90">from previous period</span>
                </div>
            @endif
        </div>

        <!-- Average Transaction -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium opacity-90">Avg. Transaction</div>
                    <div class="text-3xl font-bold mt-2">
                        ৳ {{ $data['count'] > 0 ? number_format($data['total'] / $data['count'], 2) : '0.00' }}
                    </div>
                    <div class="text-sm opacity-90 mt-1">per payment</div>
                </div>
                <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>

        <!-- Period Info -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium opacity-90">Report Period</div>
                    <div class="text-xl font-bold mt-2 capitalize">{{ $period }}</div>
                    <div class="text-sm opacity-90 mt-1">
                        {{ $data['period'] ?? ($period == 'today' ? 'Today' : ($period == 'year' ? 'This Year' : 'This ' . ucfirst($period))) }}
                    </div>
                </div>
                <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Export Revenue Report</h2>
        <p class="text-gray-600 mb-6">Download the revenue report in your preferred format</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- CSV Export -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-6 border border-green-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-gray-800 mb-1">CSV Export</h3>
                        <p class="text-sm text-gray-600">Export as CSV file for Excel</p>
                    </div>
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <a href="{{ route('admin.reports.export', ['type' => 'revenue', 'format' => 'csv', 'period' => $period]) }}" 
                   class="w-full flex items-center justify-center space-x-2 p-3 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 transition duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Download CSV</span>
                </a>
            </div>
            
            <!-- PDF Export -->
            <div class="bg-gradient-to-r from-red-50 to-rose-50 rounded-lg p-6 border border-red-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-gray-800 mb-1">PDF Export</h3>
                        <p class="text-sm text-gray-600">Export as printable PDF document</p>
                    </div>
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <a href="{{ route('admin.reports.export', ['type' => 'revenue', 'format' => 'pdf', 'period' => $period]) }}" 
                   class="w-full flex items-center justify-center space-x-2 p-3 rounded-lg bg-gradient-to-r from-red-500 to-rose-600 text-white hover:from-red-600 hover:to-rose-700 transition duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Download PDF</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Revenue Chart Placeholder -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Revenue Trends</h2>
        <p class="text-gray-600 mb-6">Visual representation of revenue over time</p>
        
        <!-- Chart will be loaded here -->
        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <div class="text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="text-gray-500">Revenue chart would appear here with actual data</p>
                <p class="text-sm text-gray-400 mt-2">Chart integration requires additional setup</p>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Report Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 bg-blue-50 rounded-lg">
                <div class="text-sm font-medium text-blue-700 mb-1">Report Generated</div>
                <div class="font-bold text-gray-800">{{ now()->format('F j, Y') }}</div>
                <div class="text-sm text-gray-600 mt-1">{{ now()->format('h:i A') }}</div>
            </div>
            
            <div class="p-4 bg-green-50 rounded-lg">
                <div class="text-sm font-medium text-green-700 mb-1">Data Range</div>
                <div class="font-bold text-gray-800 capitalize">{{ $period }}</div>
                <div class="text-sm text-gray-600 mt-1">{{ $data['period'] ?? 'Current period' }}</div>
            </div>
            
            <div class="p-4 bg-purple-50 rounded-lg">
                <div class="text-sm font-medium text-purple-700 mb-1">Report Type</div>
                <div class="font-bold text-gray-800">Revenue Report</div>
                <div class="text-sm text-gray-600 mt-1">Financial analysis</div>
            </div>
        </div>
    </div>
</div>
@endsection