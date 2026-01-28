@extends('layouts.super-admin')

@section('title', 'রিপোর্ট ড্যাশবোর্ড - সুপার অ্যাডমিন')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center">
            <i class="fas fa-chart-bar text-blue-600 mr-3"></i>
            রিপোর্ট ড্যাশবোর্ড
        </h1>
        <p class="text-gray-600">সিস্টেমের সকল রিপোর্ট এবং বিশ্লেষণ</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 mb-1">মোট আয়</p>
                    <h2 class="text-3xl font-bold" id="totalRevenue">৳ 0</h2>
                </div>
                <div class="bg-blue-800/50 p-4 rounded-xl">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-blue-100">
                <i class="fas fa-arrow-up mr-2"></i>
                <span>সময়কাল: আজ</span>
            </div>
        </div>

        <!-- Total Applications -->
        <div class="bg-gradient-to-r from-green-500 to-green-700 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 mb-1">মোট আবেদন</p>
                    <h2 class="text-3xl font-bold" id="totalApplications">0</h2>
                </div>
                <div class="bg-green-800/50 p-4 rounded-xl">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-green-100">
                <i class="fas fa-clock mr-2"></i>
                <span>সকল সময়</span>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-700 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 mb-1">মোট ব্যবহারকারী</p>
                    <h2 class="text-3xl font-bold" id="totalUsers">0</h2>
                </div>
                <div class="bg-purple-800/50 p-4 rounded-xl">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-purple-100">
                <i class="fas fa-user-check mr-2"></i>
                <span>নিবন্ধিত ব্যবহারকারী</span>
            </div>
        </div>

        <!-- Total Admins -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-700 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 mb-1">মোট অ্যাডমিন</p>
                    <h2 class="text-3xl font-bold" id="totalAdmins">0</h2>
                </div>
                <div class="bg-orange-800/50 p-4 rounded-xl">
                    <i class="fas fa-user-tie text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-orange-100">
                <i class="fas fa-shield-alt mr-2"></i>
                <span>সক্রিয় অ্যাডমিন</span>
            </div>
        </div>
    </div>

    <!-- Report Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Revenue Report -->
        <a href="{{ route('super_admin.reports.revenue') }}" 
           class="group bg-white rounded-2xl shadow-md hover:shadow-xl border border-gray-200 p-6 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-gradient-to-r from-blue-100 to-blue-50 p-4 rounded-xl">
                    <i class="fas fa-chart-line text-3xl text-blue-600"></i>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                    Live
                </span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors">
                রাজস্ব রিপোর্ট
            </h3>
            <p class="text-gray-600 mb-4">সিস্টেমের মোট আয় এবং লেনদেন বিশ্লেষণ</p>
            <div class="flex items-center text-blue-600 font-medium">
                <span>বিস্তারিত দেখুন</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>

        <!-- Applications Report -->
        <a href="{{ route('super_admin.reports.applications') }}" 
           class="group bg-white rounded-2xl shadow-md hover:shadow-xl border border-gray-200 p-6 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-gradient-to-r from-green-100 to-green-50 p-4 rounded-xl">
                    <i class="fas fa-file-contract text-3xl text-green-600"></i>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                    Daily
                </span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-green-600 transition-colors">
                আবেদন রিপোর্ট
            </h3>
            <p class="text-gray-600 mb-4">আবেদনের স্ট্যাটাস এবং প্রবণতা বিশ্লেষণ</p>
            <div class="flex items-center text-green-600 font-medium">
                <span>বিস্তারিত দেখুন</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>

        <!-- Admin Performance -->
        <a href="{{ route('super_admin.reports.admin-performance') }}" 
           class="group bg-white rounded-2xl shadow-md hover:shadow-xl border border-gray-200 p-6 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-gradient-to-r from-purple-100 to-purple-50 p-4 rounded-xl">
                    <i class="fas fa-user-tie text-3xl text-purple-600"></i>
                </div>
                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">
                    Weekly
                </span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-purple-600 transition-colors">
                অ্যাডমিন পারফরম্যান্স
            </h3>
            <p class="text-gray-600 mb-4">অ্যাডমিনদের কাজের পরিমাণ এবং দক্ষতা বিশ্লেষণ</p>
            <div class="flex items-center text-purple-600 font-medium">
                <span>বিস্তারিত দেখুন</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>

        <!-- System Monitoring -->
        <a href="{{ route('super_admin.reports.system-monitoring') }}" 
           class="group bg-white rounded-2xl shadow-md hover:shadow-xl border border-gray-200 p-6 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-gradient-to-r from-red-100 to-red-50 p-4 rounded-xl">
                    <i class="fas fa-desktop text-3xl text-red-600"></i>
                </div>
                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium">
                    Live
                </span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-red-600 transition-colors">
                সিস্টেম মনিটরিং
            </h3>
            <p class="text-gray-600 mb-4">সিস্টেম স্বাস্থ্য এবং পারফরম্যান্স মনিটরিং</p>
            <div class="flex items-center text-red-600 font-medium">
                <span>বিস্তারিত দেখুন</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>

        <!-- Backup Report -->
        <a href="{{ route('super_admin.reports.backup') }}" 
           class="group bg-white rounded-2xl shadow-md hover:shadow-xl border border-gray-200 p-6 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-gradient-to-r from-yellow-100 to-yellow-50 p-4 rounded-xl">
                    <i class="fas fa-database text-3xl text-yellow-600"></i>
                </div>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                    Auto
                </span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-yellow-600 transition-colors">
                ব্যাকআপ রিপোর্ট
            </h3>
            <p class="text-gray-600 mb-4">ব্যাকআপ স্ট্যাটাস এবং ডাটা ব্যবস্থাপনা</p>
            <div class="flex items-center text-yellow-600 font-medium">
                <span>বিস্তারিত দেখুন</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>

        <!-- Export Reports -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-white/10 p-4 rounded-xl">
                    <i class="fas fa-download text-3xl"></i>
                </div>
                <span class="px-3 py-1 bg-blue-500/50 text-white rounded-full text-sm font-medium">
                    Export
                </span>
            </div>
            <h3 class="text-xl font-bold mb-2">রিপোর্ট এক্সপোর্ট</h3>
            <p class="text-gray-300 mb-6">বিভিন্ন ফরমেটে রিপোর্ট ডাউনলোড করুন</p>
            
            <div class="space-y-3">
                <button onclick="exportReport('csv')" 
                        class="w-full bg-white/10 hover:bg-white/20 text-white px-4 py-3 rounded-xl flex items-center justify-between transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-file-csv text-green-400 mr-3"></i>
                        <span>CSV এক্সপোর্ট</span>
                    </div>
                    <i class="fas fa-arrow-down"></i>
                </button>
                
                <button onclick="exportReport('pdf')" 
                        class="w-full bg-white/10 hover:bg-white/20 text-white px-4 py-3 rounded-xl flex items-center justify-between transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-file-pdf text-red-400 mr-3"></i>
                        <span>PDF এক্সপোর্ট</span>
                    </div>
                    <i class="fas fa-arrow-down"></i>
                </button>
                
                <button onclick="exportReport('excel')" 
                        class="w-full bg-white/10 hover:bg-white/20 text-white px-4 py-3 rounded-xl flex items-center justify-between transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-file-excel text-green-500 mr-3"></i>
                        <span>Excel এক্সপোর্ট</span>
                    </div>
                    <i class="fas fa-arrow-down"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-8 bg-white rounded-2xl shadow-md border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-history text-blue-600 mr-3"></i>
                সাম্প্রতিক কার্যকলাপ
            </h3>
            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                <span>সব দেখুন</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        
        <div class="space-y-4">
            @for($i = 1; $i <= 3; $i++)
            <div class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">
                <div class="mr-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-blue-600"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">নতুন পেমেন্ট গ্রহণ</p>
                    <p class="text-sm text-gray-600">ইনভয়েস #INV00{{ $i }} | ৳ ১৫০০</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">১০ মিনিট আগে</p>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
});

function loadDashboardStats() {
    fetch('{{ route("super_admin.reports.dashboard-stats") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalRevenue').textContent = '৳' + data.data.total_revenue.toLocaleString();
                document.getElementById('totalApplications').textContent = data.data.total_applications.toLocaleString();
                document.getElementById('totalUsers').textContent = data.data.total_users.toLocaleString();
                document.getElementById('totalAdmins').textContent = data.data.total_admins.toLocaleString();
            }
        })
        .catch(error => {
            console.error('Error loading stats:', error);
        });
}

function exportReport(format) {
    showToast(`${format.toUpperCase()} রিপোর্ট ডাউনলোড শুরু হচ্ছে...`, 'info');
    
    // Simulate download
    setTimeout(() => {
        showToast(`${format.toUpperCase()} রিপোর্ট ডাউনলোড সম্পন্ন হয়েছে`, 'success');
    }, 1500);
}

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                          type === 'error' ? 'fa-exclamation-circle' : 
                          'fa-info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}
</script>
@endpush

<style>
.animate-slide-in {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>

@endsection