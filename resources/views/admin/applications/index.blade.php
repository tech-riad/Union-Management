@extends('layouts.admin')

@section('title', 'Applications Management - Admin')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-600"></i>
                    আবেদন ব্যবস্থাপনা (অ্যাডমিন)
                </h1>
                <p class="text-gray-600 mt-1">সব সার্টিফিকেট আবেদন দেখুন এবং ব্যবস্থাপনা করুন</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Filter Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <i class="fas fa-filter text-gray-500"></i>
                        <span class="font-medium text-gray-700">স্ট্যাটাস ফিল্টার</span>
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                        <div class="py-2">
                            <a href="#" data-status="all" class="filter-item block px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-150">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-layer-group text-gray-400"></i>
                                    <span>সব স্ট্যাটাস</span>
                                </div>
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="#" data-status="pending" class="filter-item block px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-150">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-yellow-500"></i>
                                    <span>পেন্ডিং</span>
                                </div>
                            </a>
                            <a href="#" data-status="approved" class="filter-item block px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-150">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span>অনুমোদিত</span>
                                </div>
                            </a>
                            <a href="#" data-status="rejected" class="filter-item block px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-150">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-times-circle text-red-500"></i>
                                    <span>বাতিল</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Refresh Button -->
                <button id="refreshTable" class="flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    <i class="fas fa-sync-alt"></i>
                    রিফ্রেশ
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Applications -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">মোট আবেদন</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] ?? $applications->count() }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl">
                    <i class="fas fa-file-alt text-xl text-blue-600"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-500">
                <i class="far fa-calendar-alt mr-1"></i>
                {{ now()->format('d M, Y') }}
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="bg-white rounded-xl border border-yellow-100 p-5 shadow-sm hover:shadow-md transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">পেন্ডিং রিভিউ</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['pending'] ?? $applications->where('status', 'pending')->count() }}</p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-xl">
                    <i class="fas fa-clock text-xl text-yellow-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                    অপেক্ষারত
                </span>
            </div>
        </div>

        <!-- Approved Applications -->
        <div class="bg-white rounded-xl border border-green-100 p-5 shadow-sm hover:shadow-md transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">অনুমোদিত</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['approved'] ?? $applications->where('status', 'approved')->count() }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-xl">
                    <i class="fas fa-check-circle text-xl text-green-600"></i>
                </div>
            </div>
            @php
                $totalRevenue = $applications->where('status', 'approved')->sum('fee');
            @endphp
            @if($totalRevenue > 0)
            <div class="mt-4">
                <div class="text-sm font-medium text-green-600">
                    <i class="fas fa-money-bill-wave mr-1"></i>
                    ৳{{ number_format($totalRevenue, 2) }}
                </div>
                <p class="text-xs text-gray-500 mt-1">মোট আয়</p>
            </div>
            @endif
        </div>

        <!-- Rejected Applications -->
        <div class="bg-white rounded-xl border border-red-100 p-5 shadow-sm hover:shadow-md transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">বাতিল</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['rejected'] ?? $applications->where('status', 'rejected')->count() }}</p>
                </div>
                <div class="p-3 bg-red-50 rounded-xl">
                    <i class="fas fa-times-circle text-xl text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-list mr-2 text-blue-500"></i>
                        সমস্ত আবেদন
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        মোট {{ $applications->total() }} টি আবেদন (পৃষ্ঠা {{ $applications->currentPage() }} এর {{ $applications->lastPage() }})
                    </p>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i class="far fa-clock"></i>
                    <span>{{ now()->format('h:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mx-6 mt-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg p-1.5 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mx-6 mt-4 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg p-1.5 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        <!-- Search and Filter -->
        <div class="px-6 py-4 border-b bg-gray-50">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <form action="{{ route('admin.applications.index') }}" method="GET" class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" 
                                   name="search" 
                                   placeholder="নাম, ইমেইল, আইডি দিয়ে সার্চ করুন..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                   value="{{ request('search') }}">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <select name="status" 
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            <option value="">সব স্ট্যাটাস</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>পেন্ডিং</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>অনুমোদিত</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>বাতিল</option>
                        </select>
                        <select name="payment_status" 
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            <option value="">পেমেন্ট স্ট্যাটাস</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>পেইড</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>অপেইড</option>
                        </select>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium text-sm">
                            সার্চ
                        </button>
                        @if(request()->has('search') || request()->has('status') || request()->has('payment_status'))
                            <a href="{{ route('admin.applications.index') }}" 
                               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium text-sm">
                                ক্লিয়ার
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">আবেদনকারী</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">সার্টিফিকেট</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">তারিখ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">পেমেন্ট</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">স্ট্যাটাস</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ফি</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications as $application)
                    @php
                        $user = $application->user ?? null;
                        $certificateType = $application->certificateType ?? null;
                        $invoice = $application->invoice ?? null;
                        
                        $applicantName = 'Unknown';
                        if ($application->form_data && is_array($application->form_data)) {
                            if (isset($application->form_data['name_bangla'])) {
                                $applicantName = $application->form_data['name_bangla'];
                            } elseif (isset($application->form_data['name_english'])) {
                                $applicantName = $application->form_data['name_english'];
                            }
                        }
                        if ($applicantName === 'Unknown' && $user) {
                            $applicantName = $user->name;
                        }
                        
                        // Check if can be approved
                        $canApprove = false;
                        if ($application->status === 'pending') {
                            if ($application->payment_status === 'paid' && 
                                (!$invoice || $invoice->status === 'paid')) {
                                $canApprove = true;
                            }
                        }
                    @endphp
                    
                    <tr class="hover:bg-gray-50 transition duration-150 application-row" data-status="{{ $application->status }}">
                        <!-- Application ID -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">#{{ $application->id }}</span>
                                <span class="text-xs text-gray-500 mt-1">{{ $application->application_no ?? 'N/A' }}</span>
                            </div>
                        </td>
                        
                        <!-- Applicant Information -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($applicantName, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $applicantName }}
                                    </div>
                                    @if($user)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $user->email ?? 'No email' }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <!-- Certificate Information -->
                        <td class="px-6 py-4">
                            @if($certificateType)
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">{{ $certificateType->name }}</span>
                                <span class="text-xs text-gray-500 mt-1">{{ $certificateType->template ?? 'No template' }}</span>
                            </div>
                            @else
                            <div class="flex items-center text-red-600">
                                <i class="fas fa-exclamation-triangle mr-2 text-sm"></i>
                                <span class="text-sm">সার্টিফিকেট নেই</span>
                            </div>
                            @endif
                        </td>
                        
                        <!-- Applied Date -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-900">{{ $application->created_at->format('d/m/Y') }}</span>
                                <span class="text-xs text-gray-500 mt-1">{{ $application->created_at->format('h:i A') }}</span>
                            </div>
                        </td>
                        
                        <!-- Payment Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($application->payment_status === 'paid')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800">
                                <i class="fas fa-check-circle mr-1.5"></i>
                                পেইড
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-red-100 to-pink-100 text-red-800">
                                <i class="fas fa-times-circle mr-1.5"></i>
                                অপেইড
                            </span>
                            @endif
                            @if($invoice)
                            <div class="text-xs text-gray-500 mt-1">
                                Invoice: {{ $invoice->status ?? 'N/A' }}
                            </div>
                            @endif
                        </td>
                        
                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($application->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800">
                                <i class="fas fa-check-circle mr-1.5"></i>
                                অনুমোদিত
                            </span>
                            @elseif($application->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-red-100 to-pink-100 text-red-800">
                                <i class="fas fa-times-circle mr-1.5"></i>
                                বাতিল
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800">
                                <i class="fas fa-clock mr-1.5"></i>
                                পেন্ডিং
                            </span>
                            @endif
                        </td>
                        
                        <!-- Fee -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium {{ $application->fee > 0 ? 'text-green-600' : 'text-gray-600' }}">
                                    ৳{{ number_format($application->fee, 2) }}
                                </span>
                                <span class="text-xs text-gray-500 mt-1">
                                    {{ $application->payment_status === 'paid' ? 'পেইড' : 'অপেইড' }}
                                </span>
                            </div>
                        </td>
                        
                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-2">
                                <!-- View Button -->
                                <a href="{{ route('admin.applications.show', $application->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-sm font-medium transition duration-200">
                                    <i class="fas fa-eye mr-1.5 text-sm"></i>
                                    বিস্তারিত দেখুন
                                </a>
                                
                                <!-- Status Actions -->
                                @if($application->status === 'pending')
                                    <div class="flex space-x-2">
                                        @if($canApprove)
                                            <form action="{{ route('admin.applications.approve', $application->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('এই আবেদনটি অনুমোদন করতে চান?')"
                                                        class="inline-flex items-center px-3 py-1.5 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg text-sm font-medium transition duration-200">
                                                    <i class="fas fa-check mr-1.5 text-sm"></i>
                                                    অনুমোদন
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-500 rounded-lg text-sm font-medium cursor-not-allowed"
                                                    title="পেমেন্ট সম্পূর্ণ না হলে অনুমোদন করা যাবে না">
                                                <i class="fas fa-check mr-1.5 text-sm"></i>
                                                অনুমোদন
                                            </button>
                                        @endif
                                        
                                        <button type="button" 
                                                onclick="showRejectModal({{ $application->id }})"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm font-medium transition duration-200">
                                            <i class="fas fa-times mr-1.5 text-sm"></i>
                                            বাতিল
                                        </button>
                                    </div>
                                @endif
                                
                                <!-- PDF Button for Approved -->
                                @if($application->status === 'approved')
                                <a href="{{ route('certificates.pdf', $application->id) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-sm font-medium transition duration-200">
                                    <i class="fas fa-file-pdf mr-1.5 text-sm"></i>
                                    সার্টিফিকেট ডাউনলোড
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">কোন আবেদন পাওয়া যায়নি</h3>
                                <p class="text-gray-500 mb-4">এই মুহূর্তে কোন সার্টিফিকেট আবেদন নেই।</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        @if($applications->count() > 0)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Summary -->
                <div class="flex items-center space-x-6">
                    <div class="text-sm text-gray-600">
                        দেখানো হচ্ছে {{ $applications->firstItem() ?? 0 }} থেকে {{ $applications->lastItem() ?? 0 }} এর {{ $applications->total() }} টি আবেদন
                    </div>
                    <!-- Status Summary -->
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">পেন্ডিং: {{ $stats['pending'] ?? $applications->where('status', 'pending')->count() }}</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">অনুমোদিত: {{ $stats['approved'] ?? $applications->where('status', 'approved')->count() }}</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">বাতিল: {{ $stats['rejected'] ?? $applications->where('status', 'rejected')->count() }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                @if($applications->hasPages())
                <div>
                    {{ $applications->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Additional Styles -->
<style>
    /* Custom Scrollbar */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    
    /* Smooth Animations */
    .group:hover .group-hover\:opacity-100 {
        opacity: 1;
    }
    
    .group:hover .group-hover\:visible {
        visibility: visible;
    }
    
    /* Status Badge Animation */
    .application-row:hover {
        transform: translateX(4px);
        transition: transform 0.2s ease;
    }
    
    /* Filter Dropdown Animation */
    .group .absolute {
        transform: translateY(10px);
        transition: all 0.2s ease;
    }
    
    .group:hover .absolute {
        transform: translateY(0);
    }
    
    /* Button Hover Effects */
    button:hover, a:hover {
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    /* Responsive Design */
    @media (max-width: 640px) {
        .text-2xl {
            font-size: 1.5rem;
        }
        
        .px-6 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        
        .flex-col {
            flex-direction: column;
        }
        
        .space-x-2 > :not([hidden]) ~ :not([hidden]) {
            margin-left: 0.25rem;
            margin-right: 0.25rem;
        }
    }
    
    /* Gradient Backgrounds */
    .bg-gradient-to-r {
        background-size: 200% auto;
        transition: background-position 0.5s ease;
    }
    
    .bg-gradient-to-r:hover {
        background-position: right center;
    }
    
    /* Table Row Hover Effect */
    .application-row {
        position: relative;
        overflow: hidden;
    }
    
    .application-row::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: transparent;
        transition: background 0.3s ease;
    }
    
    .application-row[data-status="pending"]::before {
        background: linear-gradient(to bottom, #f59e0b, #d97706);
    }
    
    .application-row[data-status="approved"]::before {
        background: linear-gradient(to bottom, #10b981, #047857);
    }
    
    .application-row[data-status="rejected"]::before {
        background: linear-gradient(to bottom, #ef4444, #dc2626);
    }
</style>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter Applications
        const filterItems = document.querySelectorAll('.filter-item');
        const applicationRows = document.querySelectorAll('.application-row');
        
        filterItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const status = this.dataset.status;
                
                // Remove active class from all items
                filterItems.forEach(i => i.classList.remove('bg-blue-50', 'text-blue-600'));
                
                // Add active class to clicked item
                this.classList.add('bg-blue-50', 'text-blue-600');
                
                // Filter rows
                applicationRows.forEach(row => {
                    if (status === 'all' || row.dataset.status === status) {
                        row.style.display = '';
                        row.classList.remove('hidden');
                    } else {
                        row.style.display = 'none';
                        row.classList.add('hidden');
                    }
                });
            });
        });
        
        // Refresh Button
        const refreshBtn = document.getElementById('refreshTable');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const originalText = this.innerHTML;
                
                // Add spin animation
                icon.classList.add('animate-spin');
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner animate-spin"></i> লোড হচ্ছে...';
                
                // Simulate loading
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });
        }
        
        // Auto-hide alerts
        const alerts = document.querySelectorAll('[class*="bg-gradient-to-r"]');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(20px)';
                alert.style.transition = 'all 0.3s ease';
                
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 300);
            }, 5000);
            
            // Close button functionality
            const closeBtn = alert.querySelector('button');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(20px)';
                    
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                });
            }
        });
    });
    
    // Reject Modal with SweetAlert2
    function showRejectModal(applicationId) {
        Swal.fire({
            title: 'বাতিলের কারণ লিখুন',
            input: 'textarea',
            inputPlaceholder: 'বাতিলের কারণ লিখুন...',
            inputAttributes: {
                'aria-label': 'বাতিলের কারণ'
            },
            showCancelButton: true,
            confirmButtonText: 'বাতিল করুন',
            cancelButtonText: 'বাতিল করুননা',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            customClass: {
                title: 'text-lg font-bold text-gray-800',
                input: 'text-right font-bangla',
                confirmButton: 'font-medium',
                cancelButton: 'font-medium'
            },
            buttonsStyling: true,
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('বাতিলের কারণ দিন');
                }
                return reason;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // Submit reject form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/applications/${applicationId}/reject`;
                
                // CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
                
                // Rejection reason
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'rejection_reason';
                reasonInput.value = result.value;
                form.appendChild(reasonInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<!-- SweetAlert2 for better modals -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom Animation CSS -->
<style>
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    /* Custom SweetAlert Styles */
    .swal2-popup {
        font-family: 'Noto Sans Bengali', sans-serif !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1) !important;
    }
    
    .swal2-title {
        color: #1f2937 !important;
        font-weight: 600 !important;
        font-size: 1.25rem !important;
    }
    
    .swal2-textarea {
        font-family: 'Noto Sans Bengali', sans-serif !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 8px !important;
        padding: 12px !important;
        resize: vertical !important;
    }
    
    .swal2-textarea:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    
    .swal2-confirm {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        border: none !important;
        padding: 10px 24px !important;
        font-weight: 500 !important;
    }
    
    .swal2-cancel {
        background: #f3f4f6 !important;
        color: #6b7280 !important;
        border: 1px solid #e5e7eb !important;
        padding: 10px 24px !important;
        font-weight: 500 !important;
    }
    
    /* Responsive adjustments for SweetAlert */
    @media (max-width: 640px) {
        .swal2-popup {
            width: 90% !important;
            margin: 0 auto !important;
        }
        
        .swal2-title {
            font-size: 1.1rem !important;
        }
    }
</style>
@endsection