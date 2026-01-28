@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-users mr-3 text-blue-600"></i>
                        User Management
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Manage all users in the system
                    </p>
                </div>
                @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                    <button type="button" 
                            onclick="openAddUserModal()"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Add New User
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Users -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total Users
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $stats['total'] }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <i class="fas fa-user-check text-white text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Active Users
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $stats['active'] }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inactive Users -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <i class="fas fa-user-clock text-white text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Inactive Users
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $stats['inactive'] }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banned Users -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <i class="fas fa-ban text-white text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Banned Users
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $stats['banned'] }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.users.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                   placeholder="Search name, email, mobile..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <select name="status" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                        </select>
                    </div>

                    <div class="sm:col-span-1">
                        <select name="role" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">All Roles</option>
                            <option value="citizen" {{ request('role') == 'citizen' ? 'selected' : '' }}>Citizen</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            @if(auth()->user()->role === 'super_admin')
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            @endif
                        </select>
                    </div>

                    <div class="sm:col-span-1">
                        <input type="date" 
                               name="date_from" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                               value="{{ request('date_from') }}">
                    </div>

                    <div class="sm:col-span-1">
                        <input type="date" 
                               name="date_to" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                               value="{{ request('date_to') }}">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if(session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-4">
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
            <div class="rounded-md bg-red-50 p-4 mb-4">
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

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                                    <input type="checkbox" 
                                           id="select-all" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Applications
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Registered
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                                        @if($user->id != auth()->id())
                                            <input type="checkbox" 
                                                   name="user_ids[]" 
                                                   value="{{ $user->id }}" 
                                                   class="user-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $user->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($user->profile_photo)
                                            <img class="h-10 w-10 rounded-full" 
                                                 src="{{ asset('storage/' . $user->profile_photo) }}" 
                                                 alt="{{ $user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-white font-semibold">
                                                    {{ substr($user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">@ {{ $user->username ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope text-gray-400 mr-2 text-xs"></i>
                                            {{ $user->email }}
                                        </div>
                                        <div class="flex items-center mt-1">
                                            <i class="fas fa-phone text-gray-400 mr-2 text-xs"></i>
                                            {{ $user->mobile ?? 'N/A' }}
                                        </div>
                                        @if($user->address)
                                            <div class="flex items-center mt-1">
                                                <i class="fas fa-map-marker-alt text-gray-400 mr-2 text-xs"></i>
                                                <span class="text-xs text-gray-600">{{ Str::limit($user->address, 25) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleColors = [
                                            'super_admin' => 'bg-red-100 text-red-800',
                                            'admin' => 'bg-blue-100 text-blue-800',
                                            'user' => 'bg-indigo-100 text-indigo-800',
                                            'citizen' => 'bg-green-100 text-green-800'
                                        ];
                                        $roleClass = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->status == 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Active
                                        </span>
                                    @elseif($user->status == 'inactive')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Inactive
                                        </span>
                                    @elseif($user->status == 'banned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-ban mr-1"></i> Banned
                                        </span>
                                    @endif
                                    <div class="mt-1">
                                        @if($user->email_verified_at)
                                            <span class="text-xs text-green-600 flex items-center">
                                                <i class="fas fa-check mr-1"></i> Verified
                                            </span>
                                        @else
                                            <span class="text-xs text-red-600 flex items-center">
                                                <i class="fas fa-times mr-1"></i> Unverified
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.applications.index', ['user_id' => $user->id]) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out">
                                        <i class="fas fa-file-alt mr-1"></i> {{ $user->applications_count ?? 0 }}
                                    </a>
                                    <div class="mt-2 flex space-x-3 text-xs">
                                        <span class="text-green-600">
                                            <i class="fas fa-check"></i> {{ $user->approved_applications_count ?? 0 }}
                                        </span>
                                        <span class="text-yellow-600">
                                            <i class="fas fa-clock"></i> {{ $user->pending_applications_count ?? 0 }}
                                        </span>
                                        <span class="text-red-600">
                                            <i class="fas fa-times"></i> {{ $user->rejected_applications_count ?? 0 }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('d M, Y') }}
                                    <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.show', $user->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if(auth()->user()->role === 'super_admin')
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50" 
                                               title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        
                                        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                                            @if($user->id != auth()->id())
                                                @if($user->status == 'active')
                                                    <button type="button" 
                                                            onclick="toggleStatus({{ $user->id }}, 'inactive')" 
                                                            class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-50" 
                                                            title="Deactivate">
                                                        <i class="fas fa-pause"></i>
                                                    </button>
                                                @elseif($user->status == 'inactive')
                                                    <button type="button" 
                                                            onclick="toggleStatus({{ $user->id }}, 'active')" 
                                                            class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50" 
                                                            title="Activate">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                @endif
                                                
                                                @if($user->status != 'banned')
                                                    <button type="button" 
                                                            onclick="toggleStatus({{ $user->id }}, 'banned')" 
                                                            class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50" 
                                                            title="Ban User">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @else
                                                    <button type="button" 
                                                            onclick="toggleStatus({{ $user->id }}, 'active')" 
                                                            class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50" 
                                                            title="Unban User">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Users Found</h3>
                                        <p class="text-gray-500">Try adjusting your search or filter criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ $users->firstItem() }}</span> to 
                                <span class="font-medium">{{ $users->lastItem() }}</span> of 
                                <span class="font-medium">{{ $users->total() }}</span> results
                            </p>
                        </div>
                        <div>
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bulk Actions -->
            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                    <div class="flex items-center">
                        <select id="bulk-action" 
                                class="block w-40 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Bulk Actions</option>
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                            <option value="ban">Ban Selected</option>
                            @if(auth()->user()->role === 'super_admin')
                                <option value="delete">Delete Selected</option>
                            @endif
                        </select>
                        <button type="button" 
                                onclick="applyBulkAction()"
                                class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Apply
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add User Modal -->
@if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
<div id="addUserModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="bg-blue-600 px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                            <i class="fas fa-user-plus mr-2"></i> Add New User
                        </h3>
                        <button type="button" onclick="closeAddUserModal()" class="text-white hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                                <input type="text" name="name" id="name" required 
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                                <input type="email" name="email" id="email" required 
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                                <input type="text" name="mobile" id="mobile" 
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                                <input type="text" name="username" id="username" 
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
                                <input type="password" name="password" id="password" required 
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required 
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Role *</label>
                                <select name="role" id="role" required 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="citizen">Citizen</option>
                                    <option value="user">User</option>
                                    @if(auth()->user()->role === 'super_admin')
                                        <option value="admin">Admin</option>
                                        <option value="super_admin">Super Admin</option>
                                    @endif
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                <select name="status" id="status" required 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" 
                                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-save mr-2"></i> Save User
                    </button>
                    <button type="button" 
                            onclick="closeAddUserModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Hidden Forms -->
<form id="statusForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" id="statusInput">
</form>

<form id="bulkActionForm" method="POST" action="{{ route('admin.users.bulk-action') }}" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulkActionInput">
    <input type="hidden" name="user_ids" id="bulkUserIds">
</form>

@push('scripts')
<script>
    // Modal Functions
    function openAddUserModal() {
        document.getElementById('addUserModal').classList.remove('hidden');
    }
    
    function closeAddUserModal() {
        document.getElementById('addUserModal').classList.add('hidden');
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAddUserModal();
        }
    });
    
    // Select All Checkbox
    @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
    document.getElementById('select-all')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    @endif

    // Status Toggle Function
    function toggleStatus(userId, status) {
        if(confirm('Are you sure you want to change this user\'s status?')) {
            const form = document.getElementById('statusForm');
            form.action = `/admin/users/${userId}/status`;
            document.getElementById('statusInput').value = status;
            form.submit();
        }
    }

    // Bulk Action Function
    function applyBulkAction() {
        const action = document.getElementById('bulk-action').value;
        const checkboxes = document.querySelectorAll('.user-checkbox:checked');
        const userIds = Array.from(checkboxes).map(cb => cb.value);
        
        if(!action) {
            alert('Please select an action');
            return;
        }
        
        if(userIds.length === 0) {
            alert('Please select at least one user');
            return;
        }
        
        if(confirm(`Are you sure you want to ${action} ${userIds.length} user(s)?`)) {
            document.getElementById('bulkActionInput').value = action;
            document.getElementById('bulkUserIds').value = JSON.stringify(userIds);
            document.getElementById('bulkActionForm').submit();
        }
    }

    // Auto-select based on individual checkboxes
    document.querySelectorAll('.user-checkbox')?.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allCheckboxes = document.querySelectorAll('.user-checkbox');
            const selectAll = document.getElementById('select-all');
            if(selectAll) {
                selectAll.checked = Array.from(allCheckboxes).every(cb => cb.checked);
            }
        });
    });
</script>
@endpush
@endsection

@push('styles')
<style>
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .pagination li {
        margin: 0 2px;
    }
    
    .pagination .active span {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .pagination a, .pagination span {
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        color: #374151;
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    
    .pagination a:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }
</style>
@endpush