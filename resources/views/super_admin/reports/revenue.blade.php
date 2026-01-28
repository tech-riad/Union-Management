@extends('layouts.super-admin')

@section('title', 'রাজস্ব রিপোর্ট - সুপার অ্যাডমিন')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-money-bill-wave text-green-600 mr-3"></i>
                    রাজস্ব রিপোর্ট
                </h1>
                <p class="text-gray-600">সিস্টেমের মোট আয় এবং লেনদেন বিশ্লেষণ</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <button onclick="exportReport('csv')" 
                        class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-700 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-file-csv mr-2"></i>
                    CSV এক্সপোর্ট
                </button>
                <button onclick="exportReport('pdf')" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    PDF রিপোর্ট
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 mb-8">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-filter text-blue-600 mr-3"></i>
                ফিল্টার
            </h3>
        </div>
        
        <div class="p-6">
            <form action="{{ route('super_admin.reports.revenue') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Time Period -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            সময়কাল
                        </label>
                        <select name="period" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                onchange="this.form.submit()">
                            <option value="day" {{ $period == 'day' ? 'selected' : '' }}>আজ</option>
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>সপ্তাহ</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>মাস</option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>বছর</option>
                        </select>
                    </div>
                    
                    <!-- Admin -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tie mr-2"></i>
                            অ্যাডমিন
                        </label>
                        <select name="admin_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                onchange="this.form.submit()">
                            <option value="">সকল অ্যাডমিন</option>
                            @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ $adminId == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Items Per Page -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-eye mr-2"></i>
                            প্রদর্শন
                        </label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                onchange="window.location.href='{{ route('super_admin.reports.revenue') }}?period={{ $period }}&admin_id={{ $adminId }}&per_page=' + this.value">
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>২০ টি</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>৫০ টি</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>১০০ টি</option>
                        </select>
                    </div>
                    
                    <!-- Reset Button -->
                    <div class="flex items-end">
                        <a href="{{ route('super_admin.reports.revenue') }}" 
                           class="w-full px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-800 text-white rounded-xl hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            রিসেট ফিল্টার
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 mb-2">মোট আয়</p>
                    <h2 class="text-3xl font-bold">৳ {{ number_format($totalRevenue, 2) }}</h2>
                </div>
                <div class="bg-blue-800/50 p-4 rounded-xl">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-blue-100">
                <i class="fas fa-clock mr-2"></i>
                {{ $periodText }}
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="bg-gradient-to-r from-green-500 to-green-700 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 mb-2">মোট লেনদেন</p>
                    <h2 class="text-3xl font-bold">{{ number_format($totalTransactions) }}</h2>
                </div>
                <div class="bg-green-800/50 p-4 rounded-xl">
                    <i class="fas fa-exchange-alt text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-green-100">
                <i class="fas fa-chart-line mr-2"></i>
                {{ $adminId ? 'নির্দিষ্ট অ্যাডমিন' : 'সকল অ্যাডমিন' }}
            </div>
        </div>

        <!-- Average Transaction -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-700 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 mb-2">গড় লেনদেন</p>
                    <h2 class="text-3xl font-bold">
                        @if($totalTransactions > 0)
                        ৳ {{ number_format($totalRevenue / $totalTransactions, 2) }}
                        @else
                        ৳ 0.00
                        @endif
                    </h2>
                </div>
                <div class="bg-purple-800/50 p-4 rounded-xl">
                    <i class="fas fa-calculator text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-purple-100">
                <i class="fas fa-percentage mr-2"></i>
                গড় গণনা
            </div>
        </div>

        <!-- Period Info -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-700 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 mb-2">সময়কাল</p>
                    <h2 class="text-xl font-bold">{{ $periodText }}</h2>
                    <p class="text-sm mt-2 text-orange-100">
                        @if($adminId)
                        অ্যাডমিন: {{ $admins->where('id', $adminId)->first()->name ?? 'N/A' }}
                        @else
                        সকল অ্যাডমিন
                        @endif
                    </p>
                </div>
                <div class="bg-orange-800/50 p-4 rounded-xl">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Table -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 mb-8 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-list text-blue-600 mr-3"></i>
                    লেনদেনের তালিকা
                </h3>
                <p class="text-gray-600 text-sm mt-1">
                    মোট {{ $invoices->total() }} টি লেনদেন
                </p>
            </div>
            <div class="mt-2 sm:mt-0">
                <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                    পৃষ্ঠা {{ $invoices->currentPage() }} / {{ $invoices->lastPage() }}
                </span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            @if($invoices->isEmpty())
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <h4 class="text-gray-500 font-medium mb-2">কোন লেনদেন পাওয়া যায়নি</h4>
                <p class="text-gray-400">এই সময়কালে কোনো পেমেন্ট রেকর্ড নেই</p>
            </div>
            @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            #
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ইনভয়েস নং
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ব্যবহারকারী
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            অ্যাডমিন
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            পরিমাণ
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            পেমেন্ট পদ্ধতি
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            তারিখ
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            অ্যাকশন
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($invoices as $index => $invoice)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $invoices->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                #{{ $invoice->invoice_number ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-circle text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $invoice->application->user->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $invoice->application->user->phone ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($invoice->application->admin)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-tie text-green-600 text-sm"></i>
                                </div>
                                <span class="ml-2 text-sm text-gray-900">{{ $invoice->application->admin->name }}</span>
                            </div>
                            @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold text-green-600">
                                ৳ {{ number_format($invoice->amount, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $method = strtolower($invoice->payment_method ?? 'cash');
                                $badgeClasses = [
                                    'bkash' => 'bg-pink-100 text-pink-700',
                                    'nagad' => 'bg-orange-100 text-orange-700',
                                    'rocket' => 'bg-purple-100 text-purple-700',
                                    'bank' => 'bg-yellow-100 text-yellow-700',
                                    'cash' => 'bg-gray-100 text-gray-700'
                                ][$method] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-3 py-1 {{ $badgeClasses }} rounded-full text-sm font-medium inline-flex items-center">
                                <i class="fas fa-{{ $method == 'bkash' ? 'mobile-alt' : ($method == 'bank' ? 'university' : 'money-bill-wave') }} mr-2"></i>
                                {{ ucfirst($method) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>
                                {{ $invoice->paid_at ? $invoice->paid_at->format('d/m/Y') : 'N/A' }}
                                <br>
                                <span class="text-gray-400">
                                    {{ $invoice->paid_at ? $invoice->paid_at->format('h:i A') : '' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="viewInvoice({{ $invoice->id }})" 
                                        class="p-2 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg transition-colors"
                                        title="বিস্তারিত দেখুন">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="printInvoice({{ $invoice->id }})"
                                        class="p-2 bg-green-100 text-green-600 hover:bg-green-200 rounded-lg transition-colors"
                                        title="প্রিন্ট">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        
        @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-gray-500 mb-2 sm:mb-0">
                    দেখানো হচ্ছে <span class="font-medium">{{ $invoices->firstItem() }}</span> থেকে 
                    <span class="font-medium">{{ $invoices->lastItem() }}</span> পর্যন্ত
                    মোট <span class="font-medium">{{ $invoices->total() }}</span> রেকর্ডের
                </div>
                <div>
                    {{ $invoices->appends([
                        'period' => $period,
                        'admin_id' => $adminId,
                        'per_page' => request('per_page', 20)
                    ])->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Payment Methods -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-chart-pie text-purple-600 mr-3"></i>
                পেমেন্ট পদ্ধতি অনুযায়ী
            </h3>
            
            @php
                $paymentMethods = $invoices->groupBy('payment_method');
                $totalAmount = $invoices->sum('amount');
            @endphp
            
            @if($paymentMethods->isNotEmpty())
            <div class="space-y-4">
                @foreach($paymentMethods as $method => $items)
                @php
                    $count = $items->count();
                    $total = $items->sum('amount');
                    $percentage = $totalAmount > 0 ? ($total / $totalAmount * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            @php
                                $iconClass = [
                                    'bkash' => 'fas fa-mobile-alt text-pink-600',
                                    'nagad' => 'fas fa-wallet text-orange-600',
                                    'rocket' => 'fas fa-rocket text-purple-600',
                                    'bank' => 'fas fa-university text-yellow-600',
                                    'cash' => 'fas fa-money-bill-wave text-gray-600'
                                ][strtolower($method)] ?? 'fas fa-money-bill-wave text-gray-600';
                            @endphp
                            <i class="{{ $iconClass }} mr-3"></i>
                            <span class="font-medium text-gray-700">{{ ucfirst($method) }}</span>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">৳ {{ number_format($total, 2) }}</div>
                            <div class="text-sm text-gray-500">{{ $count }} টি লেনদেন</div>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-700 h-3 rounded-full transition-all duration-500"
                             style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="text-right text-sm text-gray-500 mt-1">
                        {{ number_format($percentage, 1) }}%
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-chart-pie text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500">কোন ডাটা পাওয়া যায়নি</p>
            </div>
            @endif
        </div>

        <!-- Admin Performance -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-user-tie text-blue-600 mr-3"></i>
                অ্যাডমিন পারফরম্যান্স
            </h3>
            
            @php
                $adminPerformance = $invoices->groupBy(function($invoice) {
                    return $invoice->application->admin->id ?? 'unknown';
                })->sortByDesc(function($items) {
                    return $items->sum('amount');
                });
            @endphp
            
            @if($adminPerformance->isNotEmpty())
            <div class="space-y-6">
                @foreach($adminPerformance as $adminId => $items)
                @php
                    $admin = $items->first()->application->admin ?? null;
                    if(!$admin) continue;
                    
                    $count = $items->count();
                    $total = $items->sum('amount');
                    $average = $count > 0 ? $total / $count : 0;
                    $percentage = $totalRevenue > 0 ? ($total / $totalRevenue * 100) : 0;
                @endphp
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-tie text-blue-600"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-center">
                            <h4 class="font-medium text-gray-900">{{ $admin->name }}</h4>
                            <span class="font-bold text-green-600">৳ {{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500 mt-1">
                            <span>{{ $count }} টি লেনদেন</span>
                            <span>গড়: ৳ {{ number_format($average, 2) }}</span>
                        </div>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-700 h-2 rounded-full"
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-user-tie text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500">কোন ডাটা পাওয়া যায়নি</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Invoice Detail Modal -->
<div id="invoiceModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">ইনভয়েস বিস্তারিত</h3>
                    <button type="button" onclick="closeModal()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="bg-white p-6" id="invoiceDetail">
                <!-- Content will be loaded here -->
                <div class="text-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-4 text-gray-600">লোড হচ্ছে...</p>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeModal()"
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400 transition-colors">
                    বন্ধ করুন
                </button>
                <button type="button" onclick="printInvoiceDetail()"
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl hover:shadow-lg transition-all flex items-center">
                    <i class="fas fa-print mr-2"></i>
                    প্রিন্ট
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// View invoice details
function viewInvoice(invoiceId) {
    const modal = document.getElementById('invoiceModal');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Load invoice details via AJAX
    fetch(`/api/invoices/${invoiceId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('invoiceDetail').innerHTML = data.html;
            } else {
                document.getElementById('invoiceDetail').innerHTML = `
                    <div class="p-6">
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-circle text-red-500 text-4xl mb-4"></i>
                            <h4 class="text-red-600 font-medium mb-2">ত্রুটি!</h4>
                            <p class="text-gray-600">ইনভয়েস ডাটা লোড করতে সমস্যা</p>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('invoiceDetail').innerHTML = `
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

// Close modal
function closeModal() {
    const modal = document.getElementById('invoiceModal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Print invoice
function printInvoice(invoiceId) {
    showToast('প্রিন্ট অপশন প্রস্তুত হচ্ছে...', 'info');
    
    // Simulate print
    setTimeout(() => {
        showToast('ইনভয়েস প্রিন্ট প্রস্তুত', 'success');
    }, 1000);
}

// Print invoice detail
function printInvoiceDetail() {
    const printContent = document.getElementById('invoiceDetail').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

// Export report
function exportReport(format) {
    const params = new URLSearchParams(window.location.search);
    params.append('format', format);
    
    showToast(`${format.toUpperCase()} রিপোর্ট তৈরি হচ্ছে...`, 'info');
    
    // Simulate download
    setTimeout(() => {
        window.location.href = `{{ route('super_admin.reports.export', 'revenue') }}?${params.toString()}`;
    }, 1500);
}

// Auto-refresh page every 5 minutes (optional)
// setTimeout(function() {
//     showToast('ডাটা রিফ্রেশ হচ্ছে...', 'info');
//     window.location.reload();
// }, 300000);
</script>
@endpush

@push('styles')
<style>
/* Custom scrollbar for modal */
#invoiceModal .bg-white {
    max-height: 80vh;
    overflow-y: auto;
}

/* Custom scrollbar */
#invoiceModal .bg-white::-webkit-scrollbar {
    width: 6px;
}

#invoiceModal .bg-white::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#invoiceModal .bg-white::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

#invoiceModal .bg-white::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Print styles */
@media print {
    body * {
        visibility: hidden;
    }
    #printable, #printable * {
        visibility: visible;
    }
    #printable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
@endpush

@endsection