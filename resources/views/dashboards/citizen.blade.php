@extends('layouts.app')

@section('title', 'ড্যাশবোর্ড - ইউনিয়ন ডিজিটাল প্ল্যাটফর্ম')

@push('styles')
<style>
    /* Dashboard Premium Styles */
    :root {
        --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --gradient-4: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        --gradient-5: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        --gradient-dark: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }
    
    /* Floating Particles Background */
    .particles-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        opacity: 0.1;
    }
    
    .particle {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        animation: floatParticle 20s infinite linear;
    }
    
    @keyframes floatParticle {
        0% { transform: translateY(0) rotate(0deg); opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
    }
    
    /* Dashboard Cards */
    .dashboard-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 20px 20px 0 0;
    }
    
    .dashboard-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .dashboard-card.gradient-1::before { background: var(--gradient-1); }
    .dashboard-card.gradient-2::before { background: var(--gradient-2); }
    .dashboard-card.gradient-3::before { background: var(--gradient-3); }
    .dashboard-card.gradient-4::before { background: var(--gradient-4); }
    .dashboard-card.gradient-5::before { background: var(--gradient-5); }
    
    /* Stats Counter Animation */
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
    }
    
    /* Progress Circle */
    .progress-circle {
        width: 120px;
        height: 120px;
        position: relative;
    }
    
    .progress-circle svg {
        width: 120px;
        height: 120px;
        transform: rotate(-90deg);
    }
    
    .progress-circle circle {
        fill: none;
        stroke-width: 8;
        stroke-linecap: round;
        transform: translate(10px, 10px);
    }
    
    .progress-bg {
        stroke: #e2e8f0;
    }
    
    .progress-value {
        stroke: url(#gradient);
        animation: progressAnimation 2s ease-out forwards;
    }
    
    @keyframes progressAnimation {
        0% { stroke-dashoffset: 314; }
        100% { stroke-dashoffset: var(--progress-value); }
    }
    
    /* Activity Timeline */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: 3px solid white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    
    /* Quick Actions Grid */
    .quick-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        border-radius: 15px;
        background: white;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .quick-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .quick-action:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .quick-action:hover::before {
        transform: scaleX(1);
    }
    
    /* Profile Completion Meter */
    .profile-meter {
        height: 10px;
        background: #e2e8f0;
        border-radius: 5px;
        overflow: hidden;
        position: relative;
    }
    
    .profile-meter::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: var(--profile-progress);
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 5px;
        animation: progressFill 1.5s ease-out;
    }
    
    @keyframes progressFill {
        0% { width: 0; }
        100% { width: var(--profile-progress); }
    }
    
    /* Stats Grid Animation */
    .stats-grid {
        display: grid;
        gap: 1.5rem;
    }
    
    .stats-item {
        padding: 1.5rem;
        border-radius: 15px;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .stats-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }
    
    /* Notification Bell */
    .notification-bell {
        position: relative;
        cursor: pointer;
    }
    
    .notification-bell .bell-icon {
        animation: bellRing 5s infinite;
    }
    
    @keyframes bellRing {
        0%, 100% { transform: rotate(0); }
        5%, 15% { transform: rotate(15deg); }
        10%, 20% { transform: rotate(-15deg); }
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stat-number {
            font-size: 2rem;
        }
        
        .progress-circle {
            width: 80px;
            height: 80px;
        }
        
        .progress-circle svg {
            width: 80px;
            height: 80px;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
    <!-- Floating Particles Background -->
    <div class="particles-bg" id="particles"></div>
    
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Welcome Banner -->
        <div class="welcome-banner mb-8">
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-6 md:mb-0">
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">স্বাগতম, {{ auth()->user()->name ?? 'নাগরিক' }}! 👋</h1>
                        <p class="text-blue-100 opacity-90">
                            {{ now()->format('l, d F Y') }} • {{ now()->format('h:i A') }}
                        </p>
                        <div class="flex items-center gap-4 mt-4">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-green-400 animate-pulse"></div>
                                <span class="text-sm">সিস্টেম সক্রিয়</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-shield-alt"></i>
                                <span class="text-sm">সুরক্ষিত</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <i class="fas fa-user text-white text-2xl"></i>
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center border-2 border-white">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div>
                            <div class="font-semibold">নাগরিক একাউন্ট</div>
                            <div class="text-sm text-blue-100">ID: {{ auth()->user()->id }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        @php
            $totalApplications = auth()->user()->applications()->count();
            $pendingApplications = auth()->user()->applications()->where('status', 'pending')->count();
            $approvedApplications = auth()->user()->applications()->where('status', 'approved')->count();
            $recentApplications = auth()->user()->applications()->latest()->take(5)->get();
            
            $profile = auth()->user()->profile;
            $profileComplete = $profile && $profile->is_complete;
            $profileProgress = $profileComplete ? 100 : 50;
        @endphp
        
        <div class="stats-grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Applications -->
            <div class="stats-item">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="stat-number" id="totalApps">{{ $totalApplications }}</div>
                        <div class="text-gray-600 text-sm mt-1">মোট আবেদন</div>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-blue-100 to-purple-100 flex items-center justify-center">
                        <i class="fas fa-file-alt text-2xl text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>এই মাসে</span>
                        <span class="font-medium text-green-600">+12%</span>
                    </div>
                </div>
            </div>
            
            <!-- Pending Applications -->
            <div class="stats-item">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="stat-number" id="pendingApps">{{ $pendingApplications }}</div>
                        <div class="text-gray-600 text-sm mt-1">বিচারাধীন</div>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-yellow-100 to-orange-100 flex items-center justify-center">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-sm text-gray-500">প্রক্রিয়াধীন আবেদনসমূহ</div>
                </div>
            </div>
            
            <!-- Approved Applications -->
            <div class="stats-item">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="stat-number" id="approvedApps">{{ $approvedApplications }}</div>
                        <div class="text-gray-600 text-sm mt-1">অনুমোদিত</div>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-green-100 to-teal-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-sm text-gray-500">সফলভাবে প্রক্রিয়াকৃত</div>
                </div>
            </div>
            
            <!-- Profile Status -->
            <div class="stats-item">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="stat-number">
                            {{ $profileProgress }}%
                        </div>
                        <div class="text-gray-600 text-sm mt-1">প্রোফাইল স্ট্যাটাস</div>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center">
                        <i class="fas fa-user-check text-2xl text-purple-600"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="profile-meter" style="--profile-progress: {{ $profileProgress }}%"></div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Profile Warning -->
                @unless($profileComplete)
                <div class="dashboard-card gradient-1">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">প্রোফাইল সম্পূর্ণ করুন</h3>
                        <div class="px-4 py-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm rounded-full">
                            প্রয়োজনীয়
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">
                        সার্টিফিকেট আবেদনের জন্য আপনার প্রোফাইল ১০০% সম্পূর্ণ করতে হবে। অনুগ্রহ করে প্রয়োজনীয় তথ্য যুক্ত করুন।
                    </p>
                    <a href="{{ route('citizen.profile.edit') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-edit"></i>
                        <span>প্রোফাইল সম্পূর্ণ করুন</span>
                    </a>
                </div>
                @endunless

                <!-- Quick Actions -->
                <div class="dashboard-card gradient-2">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">দ্রুত অ্যাকশনসমূহ ⚡</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('citizen.certificates.index') }}" class="quick-action">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center mb-3">
                                <i class="fas fa-plus text-white"></i>
                            </div>
                            <span class="font-medium text-gray-900">নতুন আবেদন</span>
                            <span class="text-xs text-gray-500 mt-1">এখনই শুরু করুন</span>
                        </a>
                        
                        <a href="{{ route('citizen.applications.index') }}" class="quick-action">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-green-500 to-teal-500 flex items-center justify-center mb-3">
                                <i class="fas fa-list text-white"></i>
                            </div>
                            <span class="font-medium text-gray-900">আবেদন তালিকা</span>
                            <span class="text-xs text-gray-500 mt-1">সমস্ত দেখুন</span>
                        </a>
                        
                        <a href="{{ route('citizen.invoices.index') }}" class="quick-action">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-orange-500 to-red-500 flex items-center justify-center mb-3">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <span class="font-medium text-gray-900">পেমেন্ট</span>
                            <span class="text-xs text-gray-500 mt-1">ইনভয়েস</span>
                        </a>
                        
                        <a href="tel:16345" class="quick-action">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 flex items-center justify-center mb-3">
                                <i class="fas fa-headset text-white"></i>
                            </div>
                            <span class="font-medium text-gray-900">সাহায্য</span>
                            <span class="text-xs text-gray-500 mt-1">কল করুন</span>
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="dashboard-card gradient-3">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">সাম্প্রতিক কার্যক্রম 📈</h3>
                        <a href="{{ route('citizen.applications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            সব দেখুন <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="timeline">
                        @forelse($recentApplications as $application)
                        <div class="timeline-item">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-medium text-gray-900">{{ $application->certificateType->name ?? 'সার্টিফিকেট' }}</span>
                                    <span class="text-sm text-gray-500">{{ $application->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600">
                                        আবেদন নং: <span class="font-medium">{{ $application->id }}</span>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($application->status === 'approved') bg-green-100 text-green-800
                                        @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        @if($application->status === 'pending') বিচারাধীন
                                        @elseif($application->status === 'approved') অনুমোদিত
                                        @elseif($application->status === 'rejected') প্রত্যাখ্যাত
                                        @elseif($application->status === 'processing') প্রক্রিয়াধীন
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500">কোন সাম্প্রতিক কার্যক্রম নেই</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Profile Progress -->
                <div class="dashboard-card gradient-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">আপনার প্রোফাইল</h3>
                    
                    <div class="space-y-6">
                        <div class="text-center">
                            <div class="progress-circle mx-auto mb-4">
                                <svg>
                                    <defs>
                                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#667eea" />
                                            <stop offset="100%" stop-color="#764ba2" />
                                        </linearGradient>
                                    </defs>
                                    <circle class="progress-bg" cx="60" cy="60" r="50" />
                                    <circle class="progress-value" cx="60" cy="60" r="50" 
                                            style="--progress-value: {{ 314 - (314 * $profileProgress / 100) }};" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-bold">{{ $profileProgress }}%</span>
                                </div>
                            </div>
                            <p class="text-gray-600">
                                @if($profileComplete)
                                ✅ আপনার প্রোফাইল সম্পূর্ণ
                                @else
                                ⚠️ প্রোফাইল সম্পূর্ণ করুন
                                @endif
                            </p>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700">ব্যক্তিগত তথ্য</span>
                                <span class="text-green-600 font-medium">✓ সম্পূর্ণ</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700">যোগাযোগ তথ্য</span>
                                <span class="text-green-600 font-medium">✓ সম্পূর্ণ</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700">ঠিকানা তথ্য</span>
                                <span class="{{ $profileComplete ? 'text-green-600' : 'text-yellow-600' }} font-medium">
                                    {{ $profileComplete ? '✓ সম্পূর্ণ' : 'পূরণ করুন' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="dashboard-card gradient-5">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">সিস্টেম স্ট্যাটাস 🟢</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-white/50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-server text-green-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium">সার্ভার স্ট্যাটাস</div>
                                    <div class="text-sm text-gray-500">অনলাইন</div>
                                </div>
                            </div>
                            <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-white/50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-database text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium">ডাটাবেস</div>
                                    <div class="text-sm text-gray-500">সক্রিয়</div>
                                </div>
                            </div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-white/50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-purple-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium">সুরক্ষা</div>
                                    <div class="text-sm text-gray-500">সক্রিয়</div>
                                </div>
                            </div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-white/50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                                    <i class="fas fa-bolt text-orange-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium">পারফরমেন্স</div>
                                    <div class="text-sm text-gray-500">দ্রুত</div>
                                </div>
                            </div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="dashboard-card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">নোটিফিকেশন 🔔</h3>
                        <div class="notification-bell">
                            <i class="fas fa-bell bell-icon text-gray-600 text-xl"></i>
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-xl">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">আপনার নতুন আবেদন প্রাপ্ত হয়েছে</p>
                                <p class="text-xs text-gray-500 mt-1">২ ঘন্টা আগে</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 p-3 bg-green-50 rounded-xl">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">পেমেন্ট সফল হয়েছে</p>
                                <p class="text-xs text-gray-500 mt-1">আজ</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 p-3 bg-yellow-50 rounded-xl">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">প্রোফাইল সম্পূর্ণ করুন</p>
                                <p class="text-xs text-gray-500 mt-1">গুরুত্বপূর্ণ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold">৯৯.৯%</div>
                        <div class="text-blue-100 mt-1">সিস্টেম আপটাইম</div>
                    </div>
                    <i class="fas fa-arrow-up text-2xl opacity-50"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-2xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold">২.৩সে</div>
                        <div class="text-green-100 mt-1">গড় রেসপন্স টাইম</div>
                    </div>
                    <i class="fas fa-bolt text-2xl opacity-50"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold">২৪/৭</div>
                        <div class="text-orange-100 mt-1">সাপোর্ট উপলব্ধ</div>
                    </div>
                    <i class="fas fa-headset text-2xl opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize AOS (Animate On Scroll)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    }

    // Create floating particles
    document.addEventListener('DOMContentLoaded', function() {
        createParticles();
        animateStats();
        initDashboardAnimations();
    });

    // Floating particles effect
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        const particleCount = 30;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            // Random size between 2px and 8px
            const size = Math.random() * 6 + 2;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            // Random position
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;
            
            // Random animation delay
            particle.style.animationDelay = `${Math.random() * 20}s`;
            
            // Random opacity
            particle.style.opacity = Math.random() * 0.5 + 0.1;
            
            particlesContainer.appendChild(particle);
        }
    }

    // Animate statistics counters
    function animateStats() {
        const statElements = {
            totalApps: {{ $totalApplications }},
            pendingApps: {{ $pendingApplications }},
            approvedApps: {{ $approvedApplications }}
        };

        Object.keys(statElements).forEach(key => {
            const element = document.getElementById(key);
            if (element) {
                animateCounter(element, statElements[key]);
            }
        });
    }

    function animateCounter(element, finalValue) {
        let startValue = 0;
        const duration = 2000;
        const increment = finalValue / (duration / 16); // 60fps
        
        const timer = setInterval(() => {
            startValue += increment;
            if (startValue >= finalValue) {
                element.textContent = finalValue;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(startValue);
            }
        }, 16);
    }

    // Initialize dashboard animations
    function initDashboardAnimations() {
        // Add hover effects to cards
        const cards = document.querySelectorAll('.dashboard-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-10px)';
                card.style.boxShadow = '0 25px 50px -12px rgba(0, 0, 0, 0.25)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '';
            });
        });

        // Notification bell animation
        const bell = document.querySelector('.bell-icon');
        if (bell) {
            setInterval(() => {
                bell.style.animation = 'none';
                setTimeout(() => {
                    bell.style.animation = 'bellRing 5s infinite';
                }, 10);
            }, 5000);
        }

        // Update time every minute
        function updateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            
            const timeElements = document.querySelectorAll('.text-blue-100.opacity-90');
            timeElements.forEach(element => {
                const dateStr = now.toLocaleDateString('bn-BD', options);
                const timeStr = now.toLocaleTimeString('bn-BD', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: true 
                });
                element.textContent = `${dateStr} • ${timeStr}`;
            });
        }
        
        updateTime();
        setInterval(updateTime, 60000);
    }

    // Add CSS for dynamic animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .dashboard-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .stats-item {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .welcome-banner {
            animation: fadeInUp 0.8s ease-out;
        }
        
        /* Stagger animations for stats grid */
        .stats-grid > *:nth-child(1) { animation-delay: 0.1s; }
        .stats-grid > *:nth-child(2) { animation-delay: 0.2s; }
        .stats-grid > *:nth-child(3) { animation-delay: 0.3s; }
        .stats-grid > *:nth-child(4) { animation-delay: 0.4s; }
        
        /* Gradient text animation */
        .gradient-text-animate {
            background: linear-gradient(45deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientText 8s ease infinite;
        }
        
        @keyframes gradientText {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush