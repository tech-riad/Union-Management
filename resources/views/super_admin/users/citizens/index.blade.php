@extends('layouts.super-admin')

@section('title', 'নাগরিক ব্যবস্থাপনা')

@section('content')
<div class="animate-fade-in">
    <!-- শিরোনাম অংশ -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-users text-green-600 mr-2"></i>
                    নাগরিক ব্যবস্থাপনা
                </h2>
                <p class="text-gray-600 mt-2">সমস্ত নাগরিক এবং তাদের তথ্য ব্যবস্থাপনা করুন</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.users.citizens.create') }}" 
                   class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-green-600 to-green-800 hover:from-green-700 hover:to-green-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    নতুন নাগরিক যোগ করুন
                </a>
                <button type="button" 
                        onclick="exportCitizens()"
                        class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-download mr-2"></i>
                    এক্সেল এক্সপোর্ট করুন
                </button>
            </div>
        </div>
    </div>

    <!-- পরিসংখ্যান কার্ড -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">মোট নাগরিক</p>
                    <h3 class="text-3xl font-bold">{{ $citizens->total() }}</h3>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">সক্রিয় নাগরিক</p>
                    <h3 class="text-3xl font-bold">{{ $citizens->where('status', 'active')->count() }}</h3>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-amber-500 to-amber-700 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium mb-1">এই মাসের নতুন</p>
                    <h3 class="text-3xl font-bold">{{ $citizens->where('created_at', '>=', now()->subDays(30))->count() }}</h3>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">যাচাইকৃত অ্যাকাউন্ট</p>
                    <h3 class="text-3xl font-bold">{{ $citizens->where('email_verified_at', '!=', null)->count() }}</h3>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- অনুসন্ধান এবং ফিল্টার অংশ -->
    <div class="bg-white rounded-2xl shadow-lg mb-6 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-2">
                <div class="relative">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="নাম, NID, মোবাইল বা ইমেইল দিয়ে নাগরিক খুঁজুন..." 
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>
            <div>
                <select id="statusFilter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 bg-white">
                    <option value="">সব অবস্থা</option>
                    <option value="active">সক্রিয়</option>
                    <option value="inactive">নিষ্ক্রিয়</option>
                </select>
            </div>
            <div>
                <button onclick="clearFilters()"
                        class="w-full px-4 py-3 bg-gradient-to-r from-gray-500 to-gray-700 hover:from-gray-600 hover:to-gray-800 text-white font-medium rounded-xl shadow hover:shadow-lg transition-all duration-200">
                    <i class="fas fa-redo mr-2"></i>
                    ফিল্টার পরিষ্কার করুন
                </button>
            </div>
        </div>
    </div>

    <!-- নাগরিকদের টেবিল -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="citizensTable">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            নাগরিক তথ্য
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            যোগাযোগের বিবরণ
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            NID ও ঠিকানা
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            অবস্থা
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            কার্যক্রম
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($citizens as $citizen)
                    <tr class="hover:bg-green-50/50 transition-colors duration-150" data-status="{{ $citizen->status }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-green-500 to-green-700 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                        {{ strtoupper(substr($citizen->name, 0, 2)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $citizen->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-user-friends text-xs mr-1"></i>
                                        {{ $citizen->father_name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-2">
                                @if($citizen->mobile)
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-phone text-green-500 mr-2 w-4"></i>
                                    {{ $citizen->mobile }}
                                </div>
                                @endif
                                @if($citizen->email)
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-envelope text-green-500 mr-2 w-4"></i>
                                    {{ $citizen->email }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-2">
                                <div class="flex items-start text-sm text-gray-700">
                                    <i class="fas fa-id-card text-amber-500 mr-2 w-4 mt-0.5"></i>
                                    <span class="font-mono">{{ $citizen->nid }}</span>
                                </div>
                                <div class="text-xs text-gray-500 max-w-xs truncate">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ Str::limit($citizen->address, 60) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $citizen->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $citizen->status == 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                </span>
                                <button onclick="changeCitizenStatus({{ $citizen->id }}, '{{ $citizen->name }}', '{{ $citizen->status }}')"
                                        class="p-1 text-gray-400 hover:text-green-600 transition-colors duration-200"
                                        title="অবস্থা পরিবর্তন করুন">
                                    <i class="fas fa-sync-alt text-sm"></i>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('super_admin.users.citizens.edit', $citizen->id) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-lg shadow-sm hover:shadow transition-all duration-200"
                                   title="সম্পাদনা">
                                    <i class="fas fa-edit text-xs mr-1"></i>
                                    সম্পাদনা
                                </a>
                                <a href="{{ route('super_admin.users.show', $citizen->id) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg shadow-sm hover:shadow transition-all duration-200"
                                   title="দেখুন">
                                    <i class="fas fa-eye text-xs mr-1"></i>
                                    দেখুন
                                </a>
                                <button onclick="confirmCitizenDelete({{ $citizen->id }}, '{{ $citizen->name }}')"
                                        class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg shadow-sm hover:shadow transition-all duration-200"
                                        title="মুছুন">
                                    <i class="fas fa-trash text-xs mr-1"></i>
                                    মুছুন
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-users text-4xl mb-3"></i>
                                <h3 class="text-lg font-medium text-gray-600 mb-2">কোন নাগরিক পাওয়া যায়নি</h3>
                                <p class="text-gray-500">সিস্টেমে আপনার প্রথম নাগরিক যোগ করে শুরু করুন।</p>
                            </div>
                            <a href="{{ route('super_admin.users.citizens.create') }}" 
                               class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-green-600 to-green-800 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                                <i class="fas fa-plus mr-2"></i>
                                নতুন নাগরিক যোগ করুন
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- পেজিনেশন -->
        @if($citizens->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                    দেখানো হচ্ছে <span class="font-semibold">{{ $citizens->firstItem() }}</span> থেকে 
                    <span class="font-semibold">{{ $citizens->lastItem() }}</span> পর্যন্ত, মোট 
                    <span class="font-semibold">{{ $citizens->total() }}</span> ফলাফল
                </div>
                <div class="flex space-x-1">
                    {{ $citizens->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- মুছে ফেলার নিশ্চিতকরণ মোডাল -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-gradient-to-r from-red-100 to-red-200 rounded-full mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">মুছে ফেলার নিশ্চিতকরণ</h3>
            <p class="text-gray-600 text-center mb-6">আপনি কি নিশ্চিত যে আপনি নাগরিক মুছতে চান: <span id="deleteCitizenName" class="font-semibold text-gray-900"></span>?</p>
            <p class="text-sm text-red-600 text-center mb-6">
                <i class="fas fa-exclamation-circle mr-1"></i>
                এটি অ্যাপ্লিকেশন সহ সমস্ত নাগরিক ডেটা স্থায়ীভাবে সরিয়ে দেবে।
            </p>
            <div class="flex space-x-3">
                <button type="button" onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-gray-500 to-gray-700 hover:from-gray-600 hover:to-gray-800 text-white font-medium rounded-xl shadow hover:shadow-lg transition-all duration-200">
                    বাতিল
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-3 bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white font-medium rounded-xl shadow hover:shadow-lg transition-all duration-200">
                        মুছুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- অবস্থা পরিবর্তন মোডাল -->
<div id="statusModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="statusModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-gradient-to-r from-blue-100 to-blue-200 rounded-full mb-4">
                <i class="fas fa-sync-alt text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">অবস্থা পরিবর্তন</h3>
            <p class="text-gray-600 text-center mb-6">নাগরিকের অবস্থা পরিবর্তন করুন: <span id="statusCitizenName" class="font-semibold text-gray-900"></span></p>
            
            <div class="space-y-4 mb-6">
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" id="activeBtn"
                            class="p-4 border-2 rounded-xl text-center transition-all duration-200"
                            onclick="selectCitizenStatus('active')">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mb-2">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <span class="font-medium text-gray-700">সক্রিয়</span>
                            <span class="text-sm text-gray-500">লগইন করতে পারবে</span>
                        </div>
                    </button>
                    <button type="button" id="inactiveBtn"
                            class="p-4 border-2 rounded-xl text-center transition-all duration-200"
                            onclick="selectCitizenStatus('inactive')">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mb-2">
                                <i class="fas fa-times text-red-600"></i>
                            </div>
                            <span class="font-medium text-gray-700">নিষ্ক্রিয়</span>
                            <span class="text-sm text-gray-500">লগইন করতে পারবে না</span>
                        </div>
                    </button>
                </div>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                নিষ্ক্রিয় নাগরিকরা লগইন করতে বা সার্টিফিকেটের জন্য আবেদন করতে পারবে না।
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeStatusModal()" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-gray-500 to-gray-700 hover:from-gray-600 hover:to-gray-800 text-white font-medium rounded-xl shadow hover:shadow-lg transition-all duration-200">
                    বাতিল
                </button>
                <button type="button" onclick="updateCitizenStatus()" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-medium rounded-xl shadow hover:shadow-lg transition-all duration-200">
                    অবস্থা আপডেট করুন
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentCitizenId = null;
let selectedCitizenStatus = null;
let currentCitizenName = null;

// অনুসন্ধান কার্যকারিতা
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    const searchText = this.value.toLowerCase();
    const rows = document.querySelectorAll('#citizensTable tbody tr');
    
    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(searchText) ? '' : 'none';
    });
});

// অবস্থা ফিল্টার
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('#citizensTable tbody tr');
    
    rows.forEach(row => {
        if (status === '') {
            row.style.display = '';
        } else {
            const rowStatus = row.getAttribute('data-status');
            row.style.display = rowStatus === status ? '' : 'none';
        }
    });
});

// ফিল্টার পরিষ্কার করুন
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    
    const rows = document.querySelectorAll('#citizensTable tbody tr');
    rows.forEach(row => row.style.display = '');
}

// মুছে ফেলার মোডাল ফাংশন
function confirmCitizenDelete(id, name) {
    currentCitizenId = id;
    currentCitizenName = name;
    
    document.getElementById('deleteCitizenName').textContent = name;
    document.getElementById('deleteForm').action = `/super-admin/users/${id}`;
    
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        currentCitizenId = null;
        currentCitizenName = null;
    }, 300);
}

// অবস্থা মোডাল ফাংশন
function changeCitizenStatus(id, name, currentStatus) {
    currentCitizenId = id;
    currentCitizenName = name;
    selectedCitizenStatus = currentStatus;
    
    document.getElementById('statusCitizenName').textContent = name;
    selectCitizenStatus(currentStatus);
    
    const modal = document.getElementById('statusModal');
    const modalContent = document.getElementById('statusModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function selectCitizenStatus(status) {
    selectedCitizenStatus = status;
    
    const activeBtn = document.getElementById('activeBtn');
    const inactiveBtn = document.getElementById('inactiveBtn');
    
    // শৈলী রিসেট করুন
    activeBtn.classList.remove('border-green-500', 'bg-green-50');
    inactiveBtn.classList.remove('border-green-500', 'bg-green-50');
    activeBtn.classList.add('border-gray-200');
    inactiveBtn.classList.add('border-gray-200');
    
    // নির্বাচিত শৈলী প্রয়োগ করুন
    if (status === 'active') {
        activeBtn.classList.remove('border-gray-200');
        activeBtn.classList.add('border-green-500', 'bg-green-50');
    } else {
        inactiveBtn.classList.remove('border-gray-200');
        inactiveBtn.classList.add('border-green-500', 'bg-green-50');
    }
}

function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    const modalContent = document.getElementById('statusModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        currentCitizenId = null;
        currentCitizenName = null;
        selectedCitizenStatus = null;
    }, 300);
}

async function updateCitizenStatus() {
    if (!currentCitizenId || !selectedCitizenStatus) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    try {
        const response = await fetch(`/super-admin/users/${currentCitizenId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: selectedCitizenStatus })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // UI আপডেট করুন
            const row = document.querySelector(`tr[data-status]`);
            if (row) {
                const statusBadge = row.querySelector('span[class*="bg-"]');
                const statusButton = row.querySelector('button[onclick*="changeCitizenStatus"]');
                
                // অবস্থা ব্যাজ আপডেট করুন
                statusBadge.className = `px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
                    selectedCitizenStatus === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                }`;
                statusBadge.textContent = selectedCitizenStatus === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়';
                
                // সারি ডাটা অ্যাট্রিবিউট আপডেট করুন
                row.setAttribute('data-status', selectedCitizenStatus);
                
                // বাটন onclick আপডেট করুন
                statusButton.setAttribute('onclick', `changeCitizenStatus(${currentCitizenId}, '${currentCitizenName}', '${selectedCitizenStatus}')`);
            }
            
            showToast('নাগরিক অবস্থা সফলভাবে আপডেট হয়েছে!', 'success');
            closeStatusModal();
        } else {
            showToast(data.message || 'অবস্থা আপডেট করতে সমস্যা হয়েছে', 'error');
        }
    } catch (error) {
        showToast('নেটওয়ার্ক ত্রুটি। দয়া করে আবার চেষ্টা করুন।', 'error');
    }
}

// এক্সপোর্ট ফাংশন
function exportCitizens() {
    showToast('নাগরিক ডেটা এক্সপোর্ট করা হচ্ছে...', 'info');
    // আপনার এক্সপোর্ট লজিক এখানে যোগ করুন
    window.location.href = '{{ route('super_admin.users.export') }}?type=citizens';
}

// Escape কীতে মোডাল বন্ধ করুন
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeDeleteModal();
        closeStatusModal();
    }
});

// বাইরে ক্লিক করলে মোডাল বন্ধ করুন
document.getElementById('deleteModal').addEventListener('click', (e) => {
    if (e.target.id === 'deleteModal') {
        closeDeleteModal();
    }
});

document.getElementById('statusModal').addEventListener('click', (e) => {
    if (e.target.id === 'statusModal') {
        closeStatusModal();
    }
});
</script>
@endsection