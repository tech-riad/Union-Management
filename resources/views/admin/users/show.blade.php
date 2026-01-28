@extends('layouts.admin')

@section('title', $user->name . ' - User Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <a href="{{ route('admin.users.index') }}" 
                           class="text-gray-500 hover:text-gray-700 mr-4">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                <i class="fas fa-user mr-3 text-blue-600"></i>
                                {{ $user->name }}
                            </h1>
                            <p class="mt-1 text-sm text-gray-500">
                                User ID: #{{ $user->id }} | Registered: {{ $user->created_at->format('d M, Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        @if(auth()->user()->role === 'super_admin')
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-edit mr-2"></i>
                                Edit User
                            </a>
                        @endif
                        <button onclick="window.print()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-print mr-2"></i>
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Alerts -->
        @if(session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - User Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- User Profile Card -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-600 to-blue-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @if($user->profile_photo)
                                    <img class="h-16 w-16 rounded-full border-4 border-white" 
                                         src="{{ asset('storage/' . $user->profile_photo) }}" 
                                         alt="{{ $user->name }}">
                                @else
                                    <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 border-4 border-white flex items-center justify-center">
                                        <span class="text-white text-2xl font-bold">
                                            {{ substr($user->name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-white">{{ $user->name }}</h3>
                                    <p class="text-blue-100">
                                        @if($user->username)
                                            @{{ $user->username }}
                                        @else
                                            No username set
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                @php
                                    $roleColors = [
                                        'super_admin' => 'bg-red-100 text-red-800',
                                        'admin' => 'bg-blue-100 text-blue-800',
                                        'user' => 'bg-indigo-100 text-indigo-800',
                                        'citizen' => 'bg-green-100 text-green-800'
                                    ];
                                    $roleClass = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $roleClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <!-- Personal Information -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    <i class="fas fa-id-card mr-2"></i>
                                    Personal Information
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="font-medium">Email Address</p>
                                            <p class="text-gray-600">{{ $user->email }}</p>
                                            @if($user->email_verified_at)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                    <i class="fas fa-check mr-1"></i> Verified
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                    <i class="fas fa-times mr-1"></i> Unverified
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium">Mobile Number</p>
                                            <p class="text-gray-600">{{ $user->mobile ?? 'Not provided' }}</p>
                                        </div>
                                    </div>
                                </dd>
                            </div>

                            <!-- Status Information -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Account Status
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div class="flex items-center space-x-4">
                                        @if($user->status == 'active')
                                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Active
                                            </span>
                                        @elseif($user->status == 'inactive')
                                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> Inactive
                                            </span>
                                        @elseif($user->status == 'banned')
                                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-ban mr-1"></i> Banned
                                            </span>
                                        @endif
                                        
                                        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                                            @if($user->id != auth()->id())
                                                <div class="flex space-x-2">
                                                    @if($user->status == 'active')
                                                        <button type="button" 
                                                                onclick="toggleStatus('inactive')" 
                                                                class="text-sm text-yellow-600 hover:text-yellow-900">
                                                            <i class="fas fa-pause mr-1"></i> Deactivate
                                                        </button>
                                                    @elseif($user->status == 'inactive')
                                                        <button type="button" 
                                                                onclick="toggleStatus('active')" 
                                                                class="text-sm text-green-600 hover:text-green-900">
                                                            <i class="fas fa-play mr-1"></i> Activate
                                                        </button>
                                                    @endif
                                                    
                                                    @if($user->status != 'banned')
                                                        <button type="button" 
                                                                onclick="toggleStatus('banned')" 
                                                                class="text-sm text-red-600 hover:text-red-900">
                                                            <i class="fas fa-ban mr-1"></i> Ban User
                                                        </button>
                                                    @else
                                                        <button type="button" 
                                                                onclick="toggleStatus('active')" 
                                                                class="text-sm text-green-600 hover:text-green-900">
                                                            <i class="fas fa-check mr-1"></i> Unban User
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </dd>
                            </div>

                            <!-- Address Information -->
                            @if($user->address)
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    Address
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $user->address }}
                                </dd>
                            </div>
                            @endif

                            <!-- Additional Information -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Additional Information
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="font-medium">Member Since</p>
                                            <p class="text-gray-600">{{ $user->created_at->format('F d, Y') }}</p>
                                            <p class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Last Updated</p>
                                            <p class="text-gray-600">{{ $user->updated_at->format('F d, Y h:i A') }}</p>
                                            <p class="text-sm text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </dd>
                            </div>

                            <!-- Remarks -->
                            @if($user->remarks)
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    <i class="fas fa-sticky-note mr-2"></i>
                                    Remarks
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $user->remarks }}
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Application Statistics -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-file-alt mr-2 text-blue-600"></i>
                            Application Statistics
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <!-- Total Applications -->
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                        <i class="fas fa-file-alt text-white text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Total</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_applications'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Approved Applications -->
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <i class="fas fa-check text-white text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Approved</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_applications'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Applications -->
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                        <i class="fas fa-clock text-white text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Pending</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_applications'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejected Applications -->
                            <div class="bg-red-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                        <i class="fas fa-times text-white text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Rejected</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected_applications'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Applications -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-history mr-2 text-blue-600"></i>
                            Recent Applications
                        </h3>
                        <a href="{{ route('admin.applications.index', ['user_id' => $user->id]) }}" 
                           class="text-sm text-blue-600 hover:text-blue-900">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        @if($recentApplications->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentApplications as $application)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium text-gray-900">
                                                    {{ $application->certificateType->name ?? 'Unknown Certificate' }}
                                                </h4>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    Applied: {{ $application->created_at->format('M d, Y h:i A') }}
                                                </p>
                                            </div>
                                            <div>
                                                @if($application->status == 'approved')
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                        <i class="fas fa-check mr-1"></i> Approved
                                                    </span>
                                                @elseif($application->status == 'pending')
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-clock mr-1"></i> Pending
                                                    </span>
                                                @elseif($application->status == 'rejected')
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                        <i class="fas fa-times mr-1"></i> Rejected
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-3 flex justify-between items-center">
                                            <div class="text-sm text-gray-600">
                                                ID: #{{ $application->id }}
                                                @if($application->certificate_number)
                                                    | Certificate: {{ $application->certificate_number }}
                                                @endif
                                            </div>
                                            <a href="{{ route('admin.applications.show', $application->id) }}" 
                                               class="text-sm text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye mr-1"></i> View Details
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-file-alt text-gray-400 text-4xl mb-4"></i>
                                <p class="text-gray-500">No applications found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Actions & Info -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-bolt mr-2 text-blue-600"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-3">
                            <a href="{{ route('admin.applications.index', ['user_id' => $user->id]) }}" 
                               class="w-full flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-100 p-2 rounded-md">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">View Applications</p>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </a>

                            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                                @if($user->id != auth()->id())
                                    <button onclick="sendEmail()"
                                            class="w-full flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-green-100 p-2 rounded-md">
                                                <i class="fas fa-envelope text-green-600"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Send Email</p>
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    </button>

                                    <button onclick="sendSMS()"
                                            class="w-full flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-purple-100 p-2 rounded-md">
                                                <i class="fas fa-sms text-purple-600"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Send SMS</p>
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    </button>
                                @endif
                            @endif

                            <button onclick="generateReport()"
                                    class="w-full flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-yellow-100 p-2 rounded-md">
                                        <i class="fas fa-chart-bar text-yellow-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Generate Report</p>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-user-shield mr-2 text-blue-600"></i>
                            Account Information
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <ul class="space-y-4">
                            <li class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Email Verified</span>
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Yes
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i> No
                                    </span>
                                @endif
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Account Status</span>
                                @if($user->status == 'active')
                                    <span class="text-sm font-medium text-green-600">Active</span>
                                @elseif($user->status == 'inactive')
                                    <span class="text-sm font-medium text-yellow-600">Inactive</span>
                                @elseif($user->status == 'banned')
                                    <span class="text-sm font-medium text-red-600">Banned</span>
                                @endif
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">User Role</span>
                                <span class="text-sm font-medium text-gray-900">{{ ucfirst($user->role) }}</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Last Login</span>
                                <span class="text-sm text-gray-900">
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        Never
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Danger Zone -->
                @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                    @if($user->id != auth()->id())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-red-800 mb-4">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Danger Zone
                            </h3>
                            <div class="space-y-3">
                                @if($user->status != 'banned')
                                    <button onclick="banUser()"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-ban mr-2"></i>
                                        Ban User
                                    </button>
                                @else
                                    <button onclick="unbanUser()"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="fas fa-check mr-2"></i>
                                        Unban User
                                    </button>
                                @endif
                                
                                @if(auth()->user()->role === 'super_admin')
                                    <button onclick="deleteUser()"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-600 text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-trash mr-2"></i>
                                        Delete User
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms -->
<form id="statusForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" id="statusInput">
</form>

@push('scripts')
<script>
    // Status Toggle Function
    function toggleStatus(status) {
        if(confirm(`Are you sure you want to change this user's status to ${status}?`)) {
            const form = document.getElementById('statusForm');
            form.action = `/admin/users/{{ $user->id }}/status`;
            document.getElementById('statusInput').value = status;
            form.submit();
        }
    }

    // Quick Action Functions
    function sendEmail() {
        window.location.href = `mailto:{{ $user->email }}`;
    }

    function sendSMS() {
        const mobile = '{{ $user->mobile }}';
        if(mobile) {
            window.open(`sms:${mobile}`, '_blank');
        } else {
            alert('No mobile number provided for this user.');
        }
    }

    function generateReport() {
        // Implement report generation logic
        alert('Report generation feature coming soon.');
    }

    // Danger Zone Functions
    function banUser() {
        if(confirm('Are you sure you want to ban this user? They will not be able to access the system.')) {
            toggleStatus('banned');
        }
    }

    function unbanUser() {
        if(confirm('Are you sure you want to unban this user?')) {
            toggleStatus('active');
        }
    }

    function deleteUser() {
        if(confirm('⚠️ WARNING: This action cannot be undone!\n\nAre you sure you want to permanently delete this user and all their data?')) {
            // Implement delete logic
            alert('Delete feature coming soon.');
        }
    }
</script>
@endpush
@endsection

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .shadow-sm, .shadow {
            box-shadow: none !important;
        }
        
        .border, .border-t, .border-b {
            border-color: #000 !important;
        }
        
        .bg-gray-50, .bg-white {
            background: white !important;
        }
    }
</style>
@endpush