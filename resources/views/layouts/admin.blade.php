<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Union Portal - Admin')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --primary-light: #10b981;
            --secondary: #8b5cf6;
            --accent: #f59e0b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            color: #334155;
        }
        
        .sidebar-gradient {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .shadow-soft {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .shadow-hard {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Animation classes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Badge styles */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-primary {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
        }
        
        .badge-accent {
            background: linear-gradient(135deg, #fbbf24, var(--accent));
            color: white;
        }
        
        .badge-secondary {
            background: linear-gradient(135deg, #a78bfa, var(--secondary));
            color: white;
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Mobile menu styles */
        @media (max-width: 768px) {
            .mobile-menu-open {
                transform: translateX(0) !important;
            }
            
            .mobile-menu-closed {
                transform: translateX(-100%);
            }
            
            .backdrop-blur {
                backdrop-filter: blur(5px);
            }
        }
        
        /* CRITICAL FIXES - PREVENT BUTTON HIDING */
        
        /* Ensure all buttons and form elements are always visible */
        button, a.btn, input[type="submit"], input[type="button"], 
        input[type="reset"], .btn, .button, [role="button"] {
            visibility: visible !important;
            opacity: 1 !important;
            display: inline-flex !important;
        }
        
        /* Prevent flash messages auto-hide from affecting buttons */
        .mb-6 [class*="bg-gradient-to-r"] {
            position: relative;
            z-index: 10;
        }
        
        /* Make sure table buttons are always visible */
        table button, table .btn, table .inline-flex,
        table form, table form button {
            visibility: visible !important;
            opacity: 1 !important;
            display: inline-flex !important;
            position: static !important;
            transform: none !important;
        }
        
        /* Prevent any element with bg-gradient from being hidden */
        [class*="bg-gradient"]:not(.hidden):not(.opacity-0) {
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* Form buttons protection */
        form button, form input[type="submit"], 
        form input[type="button"], .approve-form, 
        .reject-form, form[class*="bg-gradient"] {
            display: inline-block !important;
            visibility: visible !important;
        }
        
        /* Specific protection for dashboard buttons */
        .bg-gradient-to-r.from-green-500.to-emerald-600,
        .bg-gradient-to-r.from-red-500.to-rose-600,
        .bg-gradient-to-r.from-blue-500.to-indigo-600,
        .bg-gradient-to-r.from-gray-100.to-gray-200 {
            animation: none !important;
            transition: none !important;
        }
        
        /* Important: Disable auto-hide for all critical buttons */
        button:not([class*="flash"]):not([class*="alert"]):not([class*="message"]),
        .btn:not([class*="flash"]):not([class*="alert"]):not([class*="message"]) {
            animation: none !important;
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        },
                        secondary: {
                            500: '#8b5cf6',
                            600: '#7c3aed',
                        },
                        accent: {
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'spin-slow': 'spin 3s linear infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'bounce-slow': 'bounce 2s infinite',
                    }
                }
            }
        }
    </script>
    
    @stack('styles')
</head>
<body class="font-inter">
    <!-- Mobile Menu Button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button id="mobileMenuToggle" class="w-12 h-12 rounded-xl bg-white shadow-hard flex items-center justify-center hover-lift">
            <i class="fas fa-bars text-gray-700"></i>
        </button>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobileOverlay" class="lg:hidden fixed inset-0 bg-black/50 z-40 hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-gradient text-white lg:w-64 lg:relative fixed top-0 left-0 h-full z-50 mobile-menu-closed lg:transform-none transition-transform duration-300 lg:translate-x-0">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-emerald-400 flex items-center justify-center shadow-lg">
                            <i class="fas fa-landmark text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold">Union Portal</h1>
                            <p class="text-xs text-gray-400">Admin Panel</p>
                        </div>
                    </div>
                    <button id="closeMobileMenu" class="lg:hidden w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- User Profile -->
                <div class="mt-6 flex items-center space-x-3 p-3 rounded-xl bg-gray-800/50 hover:bg-gray-800 transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                        <i class="fas fa-user-shield text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        <p class="text-xs text-emerald-400 mt-1">
                            <i class="fas fa-circle text-xs mr-1"></i>
                            Admin
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <div class="p-4 overflow-y-auto h-[calc(100vh-200px)]">
                <nav class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-800 transition duration-300 group {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center group-hover:scale-110 transition duration-300">
                            <i class="fas fa-home"></i>
                        </div>
                        <div>
                            <p class="font-medium">Dashboard</p>
                            <p class="text-xs text-gray-400">Overview & Stats</p>
                        </div>
                    </a>

                    <!-- Applications -->
                    <a href="{{ route('admin.applications.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-800 transition duration-300 group {{ request()->routeIs('admin.applications.*') ? 'bg-gray-800' : '' }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-400 flex items-center justify-center group-hover:scale-110 transition duration-300">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-medium">Applications</p>
                                @php
                                    // Get pending applications older than 7 days
                                    $pendingCount = \App\Models\Application::where('status', 'Pending')
                                        ->whereDate('created_at', '<=', \Carbon\Carbon::now()->subDays(7))
                                        ->count();
                                @endphp
                                @if($pendingCount > 0)
                                <span class="badge badge-accent">{{ $pendingCount }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400">Manage applications</p>
                        </div>
                    </a>

                    <!-- Users -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-800 transition duration-300 group">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-400 flex items-center justify-center group-hover:scale-110 transition duration-300">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <p class="font-medium">Users</p>
                            <p class="text-xs text-gray-400">Manage users</p>
                        </div>
                    </a>

                    <!-- Reports -->
                    <a href="{{ route('admin.reports.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-800 transition duration-300 group">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-rose-500 to-pink-400 flex items-center justify-center group-hover:scale-110 transition duration-300">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <p class="font-medium">Reports</p>
                            <p class="text-xs text-gray-400">Analytics & insights</p>
                        </div>
                    </a>
                </nav>

                <!-- Divider -->
                <div class="my-6 border-t border-gray-800"></div>

                <!-- Quick Actions -->
                <div class="p-3">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-3 tracking-wider">Quick Actions</p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.applications.index') }}?status=Pending&filter=old" 
                           class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-800 transition duration-300 text-sm">
                            <i class="fas fa-bolt text-amber-400"></i>
                            <span>Process Old Pending</span>
                        </a>
                        <a href="{{ route('admin.reports.index') }}" 
                           class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-800 transition duration-300 text-sm">
                            <i class="fas fa-download text-blue-400"></i>
                            <span>Export Reports</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-800">
                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center space-x-2 p-3 rounded-xl bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 transition duration-300 text-white shadow-lg">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-soft border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <!-- Breadcrumb -->
                        <div class="flex items-center space-x-2">
                            <h2 class="text-xl font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                            <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
                                <i class="fas fa-chevron-right text-xs"></i>
                                <span>@yield('breadcrumb', 'Overview')</span>
                            </div>
                        </div>

                        <!-- Right Side Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div class="relative">
                                @php
                                    // Get pending applications older than 7 days for notification badge
                                    $oldPendingCount = \App\Models\Application::where('status', 'Pending')
                                        ->whereDate('created_at', '<=', \Carbon\Carbon::now()->subDays(7))
                                        ->count();
                                    
                                    // Get the actual old pending applications
                                    $oldPendingApps = \App\Models\Application::where('status', 'Pending')
                                        ->whereDate('created_at', '<=', \Carbon\Carbon::now()->subDays(7))
                                        ->orderBy('created_at', 'asc')
                                        ->limit(10)
                                        ->get();
                                @endphp
                                
                                <button id="notificationsButton" 
                                        class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 transition duration-300 flex items-center justify-center relative">
                                    <i class="fas fa-bell text-gray-600"></i>
                                    @if($oldPendingCount > 0)
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs rounded-full flex items-center justify-center border-2 border-white">
                                        {{ $oldPendingCount }}
                                    </span>
                                    @endif
                                </button>
                                
                                <!-- Notifications Dropdown - Showing only old pending applications -->
                                <div id="notificationsDropdown" 
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-hard z-50 hidden animate-fade-in">
                                    <div class="p-4 border-b">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-gray-800">Old Pending Applications</h3>
                                            <span class="text-xs text-gray-500">Older than 7 days</span>
                                        </div>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        @if($oldPendingCount > 0)
                                            @foreach($oldPendingApps as $app)
                                            <div class="p-4 border-b hover:bg-gray-50">
                                                <div class="flex items-start space-x-3">
                                                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                                                        <i class="fas fa-clock text-amber-600 text-sm"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-800">
                                                            Application #{{ $app->id }}
                                                            @if($app->certificateType)
                                                            - {{ $app->certificateType->name }}
                                                            @endif
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $app->user->name ?? 'Unknown User' }}
                                                        </p>
                                                        <div class="flex items-center justify-between mt-1">
                                                            <span class="text-xs text-amber-600">
                                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                                {{ $app->created_at->diffForHumans() }}
                                                            </span>
                                                            <span class="text-xs text-gray-500">
                                                                {{ $app->created_at->format('d/m/Y') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 flex space-x-2">
                                                    <a href="{{ route('admin.applications.index') }}?search={{ $app->id }}" 
                                                       class="text-xs text-primary-600 hover:text-primary-700">
                                                        <i class="fas fa-eye mr-1"></i> View
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.applications.approve', $app->id) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-xs text-green-600 hover:text-green-700">
                                                            <i class="fas fa-check mr-1"></i> Approve
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                        <div class="p-8 text-center">
                                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-check text-green-600"></i>
                                            </div>
                                            <p class="text-sm text-gray-600">No old pending applications</p>
                                            <p class="text-xs text-gray-500 mt-1">All pending applications are within 7 days</p>
                                        </div>
                                        @endif
                                    </div>
                                    @if($oldPendingCount > 0)
                                    <div class="p-4 border-t">
                                        <a href="{{ route('admin.applications.index') }}?status=Pending&filter=old" 
                                           class="block text-center text-sm font-medium text-primary-600 hover:text-primary-700">
                                            <i class="fas fa-list mr-1"></i> View all old pending ({{ $oldPendingCount }})
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- User Menu -->
                            <div class="relative">
                                <button id="userMenuButton" 
                                        class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-100 transition duration-300">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-emerald-400 flex items-center justify-center">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div class="hidden md:block text-left">
                                        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">Administrator</p>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-400 text-sm hidden md:block"></i>
                                </button>

                                <!-- User Dropdown -->
                                <div id="userDropdown" 
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-hard z-50 hidden animate-fade-in">
                                    <div class="p-4 border-b">
                                        <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="p-2">
                                        <a href="{{ route('admin.profile.index') }}" 
                                           class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition duration-300 text-sm">
                                            <i class="fas fa-user text-gray-500"></i>
                                            <span>My Profile</span>
                                        </a>
                                        <a href="{{ route('admin.profile.edit') }}" 
                                           class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition duration-300 text-sm">
                                            <i class="fas fa-cog text-gray-500"></i>
                                            <span>Edit Profile</span>
                                        </a>
                                        <a href="{{ route('admin.password.change') }}" 
                                           class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition duration-300 text-sm">
                                            <i class="fas fa-question-circle text-gray-500"></i>
                                            <span>Update Password</span>
                                        </a>
                                        <div class="border-t mt-2 pt-2">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" 
                                                        class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-red-50 transition duration-300 text-sm w-full text-red-600">
                                                    <i class="fas fa-sign-out-alt"></i>
                                                    <span>Logout</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 animate-fade-in flash-message">
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 border-l-4 border-emerald-500 rounded-r-xl p-4 shadow-soft">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                <i class="fas fa-check-circle text-emerald-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-emerald-900">{{ session('success') }}</p>
                            </div>
                            <button class="ml-auto text-emerald-600 hover:text-emerald-800" onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 animate-fade-in flash-message">
                    <div class="bg-gradient-to-r from-rose-50 to-red-50 border-l-4 border-rose-500 rounded-r-xl p-4 shadow-soft">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-rose-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-rose-900">{{ session('error') }}</p>
                            </div>
                            <button class="ml-auto text-rose-600 hover:text-rose-800" onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 animate-fade-in flash-message">
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 rounded-r-xl p-4 shadow-soft">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-amber-600"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-medium text-amber-900 mb-2">Please fix the following errors:</p>
                                <ul class="text-sm text-amber-800 space-y-1">
                                    @foreach($errors->all() as $error)
                                    <li class="flex items-center">
                                        <i class="fas fa-circle text-[6px] mr-2"></i>
                                        {{ $error }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <button class="ml-4 text-amber-600 hover:text-amber-800" onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Page Content -->
                <div class="animate-fade-in">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="text-sm text-gray-600">
                        © {{ date('Y') }} Union Portal. All rights reserved.
                        <span class="hidden md:inline">•</span>
                        <span class="block md:inline mt-1 md:mt-0">
                           Developed by 
                            <a href="https://sinhaithost.com.bd"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-primary-600 hover:text-primary-700 font-medium">
                                Sinha It Host
                            </a>
                        </span>
                    </div>
                    <div class="mt-2 md:mt-0">
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <a href="#" class="hover:text-primary-600 transition duration-300">
                                <i class="fas fa-life-ring mr-1"></i> Support
                            </a>
                            <a href="#" class="hover:text-primary-600 transition duration-300">
                                <i class="fas fa-book mr-1"></i> Documentation
                            </a>
                            <a href="#" class="hover:text-primary-600 transition duration-300">
                                <i class="fas fa-shield-alt mr-1"></i> Privacy
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- JavaScript - FIXED VERSION -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Premium Admin Panel Loaded - Fixed Version');

            // Mobile Menu Toggle
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const closeMobileMenu = document.getElementById('closeMobileMenu');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const sidebar = document.getElementById('sidebar');

            function openMobileMenu() {
                sidebar.classList.remove('mobile-menu-closed');
                sidebar.classList.add('mobile-menu-open');
                mobileOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenuFunc() {
                sidebar.classList.remove('mobile-menu-open');
                sidebar.classList.add('mobile-menu-closed');
                mobileOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', openMobileMenu);
            }

            if (closeMobileMenu) {
                closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
            }

            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', closeMobileMenuFunc);
            }

            // Close mobile menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenuFunc();
                }
            });

            // Notifications Dropdown
            const notificationsButton = document.getElementById('notificationsButton');
            const notificationsDropdown = document.getElementById('notificationsDropdown');

            if (notificationsButton && notificationsDropdown) {
                notificationsButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    notificationsDropdown.classList.toggle('hidden');
                    
                    // Close other dropdowns
                    if (userDropdown) userDropdown.classList.add('hidden');
                });
            }

            // User Dropdown
            const userMenuButton = document.getElementById('userMenuButton');
            const userDropdown = document.getElementById('userDropdown');

            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                    
                    // Close other dropdowns
                    if (notificationsDropdown) notificationsDropdown.classList.add('hidden');
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (notificationsButton && !notificationsButton.contains(e.target) && notificationsDropdown && !notificationsDropdown.contains(e.target)) {
                    notificationsDropdown.classList.add('hidden');
                }
                
                if (userMenuButton && !userMenuButton.contains(e.target) && userDropdown && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });

            // Logout confirmation
            const logoutForm = document.getElementById('logoutForm');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to logout?')) {
                        e.preventDefault();
                    }
                });
            }

            // FIXED: Auto-hide ONLY flash messages (not buttons)
            setTimeout(() => {
                const flashMessages = document.querySelectorAll('.flash-message');
                flashMessages.forEach(alert => {
                    if (alert.closest('.mb-6')) {
                        alert.style.transition = 'opacity 0.5s, transform 0.5s';
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-20px)';
                        
                        setTimeout(() => {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 500);
                    }
                });
            }, 5000);

            // Add hover effects to interactive elements
            const interactiveElements = document.querySelectorAll('a, button, .hover-lift');
            interactiveElements.forEach(el => {
                el.addEventListener('mouseenter', function() {
                    this.classList.add('hover-lift');
                });
            });

            // Performance monitoring
            window.addEventListener('load', function() {
                const loadTime = window.performance.timing.domContentLoadedEventEnd - window.performance.timing.navigationStart;
                console.log(`Page loaded in ${loadTime}ms`);
            });

            // Real-time clock in sidebar
            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: true 
                });
                const dateString = now.toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });
            }
            
            updateClock();
            setInterval(updateClock, 60000);
            
            // FIXED: Prevent button hiding - Critical fix
            function protectButtonsFromHiding() {
                // Select all buttons and important elements
                const allButtons = document.querySelectorAll('button, .btn, .inline-flex, input[type="submit"], input[type="button"]');
                const allForms = document.querySelectorAll('form');
                const tableButtons = document.querySelectorAll('table button, table .btn, table form');
                
                // Ensure they stay visible
                allButtons.forEach(button => {
                    if (!button.classList.contains('hidden')) {
                        button.style.visibility = 'visible';
                        button.style.opacity = '1';
                        button.style.display = 'inline-flex';
                        button.style.position = 'static';
                        button.style.transform = 'none';
                    }
                });
                
                allForms.forEach(form => {
                    form.style.display = 'block';
                });
                
                tableButtons.forEach(button => {
                    button.style.visibility = 'visible';
                    button.style.opacity = '1';
                });
                
                // Specifically protect dashboard buttons
                const dashboardButtons = document.querySelectorAll('.bg-gradient-to-r.from-green-500, .bg-gradient-to-r.from-red-500, .bg-gradient-to-r.from-blue-500');
                dashboardButtons.forEach(btn => {
                    btn.style.animation = 'none';
                    btn.style.transition = 'none';
                });
            }
            
            // Run protection multiple times
            protectButtonsFromHiding();
            setTimeout(protectButtonsFromHiding, 100);
            setTimeout(protectButtonsFromHiding, 500);
            setTimeout(protectButtonsFromHiding, 1000);
            setTimeout(protectButtonsFromHiding, 5000);
            
            // Also protect on resize
            window.addEventListener('resize', protectButtonsFromHiding);
            
            // Watch for any style changes that might hide buttons
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                        const target = mutation.target;
                        if (target.tagName === 'BUTTON' || target.classList.contains('btn')) {
                            protectButtonsFromHiding();
                        }
                    }
                });
            });
            
            // Start observing
            observer.observe(document.body, {
                attributes: true,
                subtree: true,
                attributeFilter: ['style', 'class']
            });
            
            // Auto-refresh notification count every 30 seconds
            function refreshNotificationCount() {
                // This would typically make an AJAX call to get updated count
                // For now, we'll just simulate it
                console.log('Refreshing notification count...');
                
                // In a real application, you would fetch from an API endpoint
                // fetch('/api/admin/notifications/count')
                //     .then(response => response.json())
                //     .then(data => {
                //         const badge = document.querySelector('.absolute.-top-1.-right-1');
                //         if (badge) {
                //             badge.textContent = data.count;
                //             if (data.count > 0) {
                //                 badge.classList.remove('hidden');
                //             } else {
                //                 badge.classList.add('hidden');
                //             }
                //         }
                //     });
            }
            
            // Refresh notification count every 30 seconds
            setInterval(refreshNotificationCount, 30000);
        });

        // Toast notification system
        window.showToast = function(message, type = 'success') {
            const toast = document.createElement('div');
            const colors = {
                success: 'from-emerald-500 to-green-500',
                error: 'from-rose-500 to-red-500',
                warning: 'from-amber-500 to-orange-500',
                info: 'from-blue-500 to-cyan-500'
            };
            
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-hard z-50 bg-gradient-to-r ${colors[type]} text-white transform translate-x-full opacity-0 transition-all duration-300`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 10);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 5000);
        };
    </script>
    
    @stack('scripts')
</body>
</html>