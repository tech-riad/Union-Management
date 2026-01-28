@extends('layouts.super-admin')

@section('title', 'অ্যাডমিন ব্যবস্থাপনা')

@section('content')
<div class="animate-fade-in">
    <!-- শিরোনাম অংশ -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-user-shield text-blue-600 mr-2"></i>
                    অ্যাডমিন ব্যবস্থাপনা
                </h2>
                <p class="text-gray-600 mt-2">সিস্টেম অ্যাডমিন এবং তাদের অনুমতি ব্যবস্থাপনা করুন</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.users.admins.create') }}" 
                   class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    নতুন অ্যাডমিন যোগ করুন
                </a>
                <button type="button" 
                        onclick="window.location.href='{{ route('super_admin.users.index') }}'"
                        class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-gray-600 to-gray-800 hover:from-gray-700 hover:to-gray-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-users mr-2"></i>
                    সকল ব্যবহারকারী
                </button>
            </div>
        </div>
    </div>

    <!-- পরিসংখ্যান কার্ড -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">মোট অ্যাডমিন</p>
                    <h3 class="text-3xl font-bold">{{ $admins->total() }}</h3>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-blue-400/30">
                <div class="flex justify-between text-sm">
                    <span class="text-blue-100">সক্রিয়: {{ $admins->where('status', 'active')->count() }}</span>
                    <span class="text-blue-100">নিষ্ক্রিয়: {{ $admins->where('status', 'inactive')->count() }}</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">সক্রিয় অ্যাডমিন</p>
                    <h3 class="text-3xl font-bold">{{ $admins->where('status', 'active')->count() }}</h3>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-green-400/30">
                <p class="text-green-100 text-sm">বর্তমানে সক্রিয় সিস্টেম অ্যাডমিন</p>
            </div>
        </div>

        <div class="bg-gradient-to-r from-amber-500 to-amber-700 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium mb-1">সাম্প্রতিক কার্যক্রম</p>
                    <h3 class="text-3xl font-bold">{{ $admins->where('created_at', '>=', now()->subDays(7))->count() }}</h3>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-amber-400/30">
                <p class="text-amber-100 text-sm">গত ৭ দিনে নতুন অ্যাডমিন যোগ হয়েছে</p>
            </div>
        </div>
    </div>

    <!-- অনুসন্ধান এবং ফিল্টার অংশ -->
    <div class="bg-white rounded-2xl shadow-lg mb-6 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2">
                <div class="relative">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="নাম, ইমেইল বা মোবাইল দিয়ে অ্যাডমিন খুঁজুন..." 
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>
            <div>
                <select id="statusFilter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white">
                    <option value="">সব অবস্থা</option>
                    <option value="active">সক্রিয়</option>
                    <option value="inactive">নিষ্ক্রিয়</option>
                </select>
            </div>
        </div>
    </div>

    <!-- অ্যাডমিন টেবিল -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="adminsTable">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            অ্যাডমিন তথ্য
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            যোগাযোগ
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            অবস্থা
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            তৈরির তারিখ
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            কার্যক্রম
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($admins as $admin)
                    <tr class="hover:bg-blue-50/50 transition-colors duration-150" data-status="{{ $admin->status }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $admin->name }}</div>
                                    <div class="text-sm text-gray-500">আইডি: #{{ $admin->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-2">
                                @if($admin->mobile)
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-phone text-blue-500 mr-2 w-4"></i>
                                    {{ $admin->mobile }}
                                </div>
                                @endif
                                @if($admin->email)
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-envelope text-blue-500 mr-2 w-4"></i>
                                    {{ $admin->email }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $admin->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $admin->status == 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                </span>
                                <button onclick="changeStatus({{ $admin->id }}, '{{ $admin->name }}', '{{ $admin->status }}')"
                                        class="p-1 text-gray-400 hover:text-blue-600 transition-colors duration-200"
                                        title="অবস্থা পরিবর্তন করুন">
                                    <i class="fas fa-sync-alt text-sm"></i>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $admin->created_at->format('d M, Y') }}
                            <div class="text-xs text-gray-500">{{ $admin->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('super_admin.users.admins.edit', $admin->id) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-lg shadow-sm hover:shadow transition-all duration-200"
                                   title="সম্পাদনা">
                                    <i class="fas fa-edit text-xs mr-1"></i>
                                    সম্পাদনা
                                </a>
                                <a href="{{ route('super_admin.users.show', $admin->id) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg shadow-sm hover:shadow transition-all duration-200"
                                   title="দেখুন">
                                    <i class="fas fa-eye text-xs mr-1"></i>
                                    দেখুন
                                </a>
                                <button onclick="confirmDelete({{ $admin->id }}, '{{ $admin->name }}')"
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
                                <i class="fas fa-user-shield text-4xl mb-3"></i>
                                <h3 class="text-lg font-medium text-gray-600 mb-2">কোন অ্যাডমিন পাওয়া যায়নি</h3>
                                <p class="text-gray-500">আপনার প্রথম সিস্টেম অ্যাডমিন যোগ করে শুরু করুন।</p>
                            </div>
                            <a href="{{ route('super_admin.users.admins.create') }}" 
                               class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                                <i class="fas fa-plus mr-2"></i>
                                নতুন অ্যাডমিন যোগ করুন
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- পেজিনেশন -->
        @if($admins->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                    দেখানো হচ্ছে <span class="font-semibold">{{ $admins->firstItem() }}</span> থেকে 
                    <span class="font-semibold">{{ $admins->lastItem() }}</span> পর্যন্ত, মোট 
                    <span class="font-semibold">{{ $admins->total() }}</span> ফলাফল
                </div>
                <div class="flex space-x-1">
                    {{ $admins->links('pagination::tailwind') }}
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
            <p class="text-gray-600 text-center mb-6">আপনি কি নিশ্চিত যে আপনি অ্যাডমিন মুছতে চান: <span id="deleteAdminName" class="font-semibold text-gray-900"></span>?</p>
            <p class="text-sm text-red-600 text-center mb-6">
                <i class="fas fa-exclamation-circle mr-1"></i>
                এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।
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
            <p class="text-gray-600 text-center mb-6">অ্যাডমিনের অবস্থা পরিবর্তন করুন: <span id="statusAdminName" class="font-semibold text-gray-900"></span></p>
            
            <div class="space-y-4 mb-6">
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" id="activeBtn"
                            class="p-4 border-2 rounded-xl text-center transition-all duration-200"
                            onclick="selectStatus('active')">
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
                            onclick="selectStatus('inactive')">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mb-2">
                                <i class="fas fa-times text-red-600"></i>
                            </div>
                            <span class="font-medium text-gray-700">নিষ্ক্রিয়</span>
                            <span class="text-sm text-gray-500">লগইন করতে পারবে না</span>
                        </div>
                    </button>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeStatusModal()" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-gray-500 to-gray-700 hover:from-gray-600 hover:to-gray-800 text-white font-medium rounded-xl shadow hover:shadow-lg transition-all duration-200">
                    বাতিল
                </button>
                <button type="button" onclick="updateStatus()" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-medium rounded-xl shadow hover:shadow-lg transition-all duration-200">
                    অবস্থা আপডেট
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentAdminId = null;
let selectedStatus = null;
let currentAdminName = null;

// অনুসন্ধান কার্যকারিতা
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    const searchText = this.value.toLowerCase();
    const rows = document.querySelectorAll('#adminsTable tbody tr');
    
    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(searchText) ? '' : 'none';
    });
});

// অবস্থা ফিল্টার
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('#adminsTable tbody tr');
    
    rows.forEach(row => {
        if (status === '') {
            row.style.display = '';
        } else {
            const rowStatus = row.getAttribute('data-status');
            row.style.display = rowStatus === status ? '' : 'none';
        }
    });
});

// মুছে ফেলার মোডাল ফাংশন
function confirmDelete(id, name) {
    currentAdminId = id;
    currentAdminName = name;
    
    document.getElementById('deleteAdminName').textContent = name;
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
        currentAdminId = null;
        currentAdminName = null;
    }, 300);
}

// অবস্থা মোডাল ফাংশন
function changeStatus(id, name, currentStatus) {
    currentAdminId = id;
    currentAdminName = name;
    selectedStatus = currentStatus;
    
    document.getElementById('statusAdminName').textContent = name;
    selectStatus(currentStatus);
    
    const modal = document.getElementById('statusModal');
    const modalContent = document.getElementById('statusModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function selectStatus(status) {
    selectedStatus = status;
    
    const activeBtn = document.getElementById('activeBtn');
    const inactiveBtn = document.getElementById('inactiveBtn');
    
    // শৈলী রিসেট করুন
    activeBtn.classList.remove('border-blue-500', 'bg-blue-50');
    inactiveBtn.classList.remove('border-blue-500', 'bg-blue-50');
    activeBtn.classList.add('border-gray-200');
    inactiveBtn.classList.add('border-gray-200');
    
    // নির্বাচিত শৈলী প্রয়োগ করুন
    if (status === 'active') {
        activeBtn.classList.remove('border-gray-200');
        activeBtn.classList.add('border-blue-500', 'bg-blue-50');
    } else {
        inactiveBtn.classList.remove('border-gray-200');
        inactiveBtn.classList.add('border-blue-500', 'bg-blue-50');
    }
}

function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    const modalContent = document.getElementById('statusModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        currentAdminId = null;
        currentAdminName = null;
        selectedStatus = null;
    }, 300);
}

async function updateStatus() {
    if (!currentAdminId || !selectedStatus) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    try {
        const response = await fetch(`/super-admin/users/${currentAdminId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: selectedStatus })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // UI আপডেট করুন
            const row = document.querySelector(`tr[data-status]`);
            if (row) {
                const statusBadge = row.querySelector('span[class*="bg-"]');
                const statusButton = row.querySelector('button[onclick*="changeStatus"]');
                
                // অবস্থা ব্যাজ আপডেট করুন
                statusBadge.className = `px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
                    selectedStatus === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                }`;
                statusBadge.textContent = selectedStatus === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়';
                
                // সারি ডাটা অ্যাট্রিবিউট আপডেট করুন
                row.setAttribute('data-status', selectedStatus);
                
                // বাটন onclick আপডেট করুন
                statusButton.setAttribute('onclick', `changeStatus(${currentAdminId}, '${currentAdminName}', '${selectedStatus}')`);
            }
            
            showToast('অবস্থা সফলভাবে আপডেট হয়েছে!', 'success');
            closeStatusModal();
        } else {
            showToast(data.message || 'অবস্থা আপডেট করতে সমস্যা হয়েছে', 'error');
        }
    } catch (error) {
        showToast('নেটওয়ার্ক ত্রুটি। দয়া করে আবার চেষ্টা করুন।', 'error');
    }
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

@section('styles')
<style>
/* কাস্টম টেবিল শৈলী */
#adminsTable thead th {
    border-bottom: 2px solid #e5e7eb;
}

#adminsTable tbody tr:last-child {
    border-bottom: none;
}

/* মসৃণ ট্রানজিশন */
#deleteModalContent, #statusModalContent {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* নির্বাচন শৈলী */
#activeBtn.border-blue-500, #inactiveBtn.border-blue-500 {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* প্রতিক্রিয়াশীল সমন্বয় */
@media (max-width: 768px) {
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
        overflow-x: auto;
    }
    
    #adminsTable {
        min-width: 768px;
    }
}
</style>
@endsection