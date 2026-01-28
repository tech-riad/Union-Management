@extends('layouts.super-admin')

@section('title', 'ব্যবহারকারীর বিস্তারিত তথ্য')

@section('content')
<div class="animate-fade-in">
    <!-- শিরোনাম অংশ -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-user-circle text-green-600 mr-2"></i>
                    ব্যবহারকারীর বিস্তারিত তথ্য
                </h2>
                <p class="text-gray-600 mt-2">সম্পূর্ণ ব্যবহারকারী তথ্য দেখুন</p>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $backRoute = match($user->role) {
                        'admin' => 'super_admin.users.admins.index',
                        'secretary' => 'super_admin.users.secretaries.index',
                        'citizen' => 'super_admin.users.citizens.index',
                        default => 'super_admin.users.index'
                    };
                    
                    $editRoute = match($user->role) {
                        'admin' => 'super_admin.users.admins.edit',
                        'secretary' => 'super_admin.users.secretaries.edit',
                        'citizen' => 'super_admin.users.citizens.edit',
                        default => 'super_admin.users.edit'
                    };
                @endphp
                
                <a href="{{ route($backRoute) }}" 
                   class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-gray-600 to-gray-800 hover:from-gray-700 hover:to-gray-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ ucfirst($user->role) }}দের তালিকায় ফিরে যান
                </a>
                <a href="{{ route($editRoute, $user) }}"
                   class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-green-600 to-green-800 hover:from-green-700 hover:to-green-900 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-edit mr-2"></i>
                    ব্যবহারকারী সম্পাদনা করুন
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- প্রধান তথ্য -->
        <div class="lg:col-span-2">
            <!-- ব্যক্তিগত তথ্য কার্ড -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fas fa-user mr-2"></i>
                        ব্যক্তিগত তথ্য
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">পূর্ণ নাম</label>
                            <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">মোবাইল নম্বর</label>
                            <p class="text-gray-900 font-medium">{{ $user->mobile }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">ইমেইল ঠিকানা</label>
                            <p class="text-gray-900 font-medium">{{ $user->email ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">ভূমিকা</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($user->role === 'admin') bg-purple-100 text-purple-800
                                @elseif($user->role === 'secretary') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800
                                @endif">
                                <i class="fas fa-user-tag mr-1 text-xs"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- নাগরিক নির্দিষ্ট তথ্য (শুধুমাত্র নাগরিকদের জন্য) -->
            @if($user->role === 'citizen')
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fas fa-id-card mr-2"></i>
                        নাগরিক তথ্য
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($user->nid)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">NID নম্বর</label>
                            <p class="text-gray-900 font-medium">{{ $user->nid }}</p>
                        </div>
                        @endif
                        
                        @if($user->father_name)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">পিতার নাম</label>
                            <p class="text-gray-900 font-medium">{{ $user->father_name }}</p>
                        </div>
                        @endif
                        
                        @if($user->mother_name)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">মাতার নাম</label>
                            <p class="text-gray-900 font-medium">{{ $user->mother_name }}</p>
                        </div>
                        @endif
                        
                        @if($user->address)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">ঠিকানা</label>
                            <p class="text-gray-900 font-medium">{{ $user->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            <!-- অ্যাকাউন্ট তথ্য -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-amber-600 to-amber-800 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fas fa-cog mr-2"></i>
                        অ্যাকাউন্ট তথ্য
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">ব্যবহারকারী আইডি</label>
                            <p class="text-gray-900 font-mono">{{ $user->id }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">অ্যাকাউন্ট অবস্থা</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-circle text-xs mr-1"></i>
                                {{ $user->status == 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">ইমেইল যাচাইকরণ</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas {{ $user->email_verified_at ? 'fa-check-circle' : 'fa-times-circle' }} text-xs mr-1"></i>
                                {{ $user->email_verified_at ? 'যাচাইকৃত' : 'যাচাই করা হয়নি' }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">শেষ লগইন</label>
                            <p class="text-gray-900 font-medium">
                                {{ $user->last_login_at ? $user->last_login_at->format('d M Y, h:i A') : 'কখনও লগইন করেনি' }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- টাইমলাইন -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">
                            <i class="fas fa-history mr-2"></i>
                            অ্যাকাউন্ট টাইমলাইন
                        </h4>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-plus text-green-600 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">অ্যাকাউন্ট তৈরি হয়েছে</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $user->created_at->format('d M Y, h:i A') }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-edit text-blue-600 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">সর্বশেষ আপডেট</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $user->updated_at->format('d M Y, h:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- সাইড তথ্য -->
        <div class="lg:col-span-1">
            <!-- ব্যবহারকারী প্রোফাইল কার্ড -->
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200 mb-6">
                <div class="text-center">
                    <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center shadow-lg mx-auto mb-4">
                        <i class="fas fa-user text-purple-600 text-6xl"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-gray-600 mt-1">{{ ucfirst($user->role) }}</p>
                    
                    <div class="mt-4">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                            {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-circle text-xs mr-2"></i>
                            {{ $user->status == 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }} অ্যাকাউন্ট
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- দ্রুত কাজ -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-bolt text-amber-500 mr-2"></i>
                    দ্রুত কাজ
                </h3>
                
                <div class="space-y-3">
                    <a href="{{ route($editRoute, $user) }}"
                       class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-edit text-green-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-800">প্রোফাইল সম্পাদনা করুন</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                    </a>
                    
                    <!-- অবস্থা টগল -->
                    <form action="{{ route('super_admin.users.update-status', $user) }}" method="POST" id="statusForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" id="statusInput" value="{{ $user->status == 'active' ? 'inactive' : 'active' }}">
                        <button type="button" 
                                onclick="toggleStatus()"
                                class="w-full flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 group">
                            <div class="flex items-center">
                                <div class="w-10 h-10 {{ $user->status == 'active' ? 'bg-red-100' : 'bg-green-100' }} rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas {{ $user->status == 'active' ? 'fa-user-slash text-red-600' : 'fa-user-check text-green-600' }}"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800">
                                    {{ $user->status == 'active' ? 'অ্যাকাউন্ট নিষ্ক্রিয় করুন' : 'অ্যাকাউন্ট সক্রিয় করুন' }}
                                </span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                        </button>
                    </form>
                    
                    <!-- মুছে ফেলার বাটন (নিশ্চিতকরণ সহ) -->
                    @if($user->role !== 'super_admin')
                    <button type="button"
                            onclick="confirmDelete()"
                            class="w-full flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-trash text-red-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-800">অ্যাকাউন্ট মুছুন</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                    </button>
                    
                    <form action="{{ route('super_admin.users.destroy', $user) }}" method="POST" id="deleteForm" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif
                </div>
            </div>
            
            <!-- গুরুত্বপূর্ণ তথ্য -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    গুরুত্বপূর্ণ তথ্য
                </h3>
                
                <div class="space-y-3">
                    <div class="flex items-start text-sm text-gray-700">
                        <i class="fas fa-shield-alt text-blue-500 mt-0.5 mr-2"></i>
                        <span>ব্যবহারকারীর তথ্য এনক্রিপ্ট করা এবং নিরাপদে সংরক্ষিত থাকে।</span>
                    </div>
                    
                    @if($user->role === 'citizen')
                    <div class="flex items-start text-sm text-gray-700">
                        <i class="fas fa-certificate text-blue-500 mt-0.5 mr-2"></i>
                        <span>নাগরিকরা অনলাইনে সার্টিফিকেটের জন্য আবেদন করতে পারে।</span>
                    </div>
                    @endif
                    
                    <div class="flex items-start text-sm text-gray-700">
                        <i class="fas fa-history text-blue-500 mt-0.5 mr-2"></i>
                        <span>সমস্ত অ্যাকাউন্ট কার্যকলাপ লগ করা হয়।</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// অবস্থা টগল ফাংশন
function toggleStatus() {
    const currentStatus = "{{ $user->status }}";
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const actionText = currentStatus === 'active' ? 'নিষ্ক্রিয়' : 'সক্রিয়';
    
    Swal.fire({
        title: 'অবস্থা পরিবর্তনের নিশ্চিতকরণ',
        text: `আপনি কি নিশ্চিত যে আপনি এই অ্যাকাউন্টটি ${actionText} করতে চান?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `হ্যাঁ, ${actionText} করুন!`,
        cancelButtonText: 'বাতিল'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('statusInput').value = newStatus;
            document.getElementById('statusForm').submit();
        }
    });
}

// মুছে ফেলার নিশ্চিতকরণ
function confirmDelete() {
    Swal.fire({
        title: 'অ্যাকাউন্ট মুছবেন?',
        text: "এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না। সমস্ত ব্যবহারকারী ডেটা স্থায়ীভাবে মুছে যাবে।",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'হ্যাঁ, মুছে ফেলুন!',
        cancelButtonText: 'বাতিল'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}

// SweetAlert যোগ করুন যদি না উপস্থিত থাকে
if (typeof Swal === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    script.onload = function() {
        console.log('SweetAlert লোড হয়েছে');
    };
    document.head.appendChild(script);
}
</script>
@endsection