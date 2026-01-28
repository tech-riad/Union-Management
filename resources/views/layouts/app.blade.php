@php
use App\Helpers\UnionHelper;
@endphp
<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', UnionHelper::getName())</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <!-- Dynamic Styles Based on Settings -->
    <style id="dynamic-styles">
        :root {
            --primary: {{ UnionHelper::getPrimaryColor() }};
            --primary-dark: {{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), -20) }};
            --primary-light: {{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), 40) }};
            --secondary: {{ UnionHelper::getSecondaryColor() }};
            --secondary-dark: {{ UnionHelper::adjustColor(UnionHelper::getSecondaryColor(), -20) }};
            --accent: #f59e0b;
            --accent-dark: #d97706;
            --dark: #1e293b;
            --light: #f8fafc;
            --gradient-primary: linear-gradient(135deg, {{ UnionHelper::getPrimaryColor() }} 0%, {{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), 20) }} 100%);
            --gradient-secondary: linear-gradient(135deg, {{ UnionHelper::getSecondaryColor() }} 0%, {{ UnionHelper::adjustColor(UnionHelper::getSecondaryColor(), 20) }} 100%);
            --gradient-accent: linear-gradient(135deg, #f59e0b 0%, #ec4899 100%);

            /* RGB Variables for rgba() usage */
            --primary-rgb: {{ UnionHelper::hexToRgb(UnionHelper::getPrimaryColor()) }};
            --primary-light-rgb: {{ UnionHelper::hexToRgb(UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), 40)) }};
            --secondary-rgb: {{ UnionHelper::hexToRgb(UnionHelper::getSecondaryColor()) }};
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            height: 100%;
        }

        /* FIXED BODY STYLES - WILL NOT CHANGE */
        body {
            font-family: 'Noto Sans Bengali', 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #fdf2f8 100%);
            min-height: 100vh;
            color: #334155;
            overflow-x: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f0f4ff 0%, #fdf2f8 100%);
            z-index: -1;
            pointer-events: none;
        }
    </style>

    <!-- Custom Styles -->
    <style>
        /* Responsive Typography */
        @media (max-width: 640px) {
            h1 { font-size: 1.5rem !important; }
            h2 { font-size: 1.25rem !important; }
            h3 { font-size: 1.125rem !important; }
            .text-lg { font-size: 1rem !important; }
            .text-xl { font-size: 1.125rem !important; }
            .text-2xl { font-size: 1.25rem !important; }
            .text-3xl { font-size: 1.5rem !important; }
            .text-4xl { font-size: 1.75rem !important; }
        }

        /* Premium Glass Effect */
        .glass-premium {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow:
                0 8px 32px rgba(31, 38, 135, 0.1),
                0 4px 16px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 768px) {
            .glass-premium {
                background: rgba(255, 255, 255, 0.98);
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(99, 102, 241, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-primary);
            border-radius: 10px;
            border: 2px solid #f8fafc;
        }

        /* Gradient Text */
        .gradient-text {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Floating Widget Container - Fixed on Left */
        .floating-widget-container {
            position: fixed;
            left: 1rem;
            bottom: 1rem;
            z-index: 40;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hide top widget when at top of page */
        .floating-widget-container.hide-top {
            transform: translateY(-60px);
        }

        /* Show all widgets when scrolled down */
        .floating-widget-container.show-all {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .floating-widget-container {
                left: 0.75rem;
                bottom: 0.75rem;
            }

            .floating-widget-container.hide-top {
                transform: translateY(-50px);
            }
        }

        /* Floating Widget Styling */
        .floating-widget {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .floating-widget:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        }

        .floating-widget:active {
            transform: scale(0.95);
        }

        @media (max-width: 768px) {
            .floating-widget {
                width: 3rem;
                height: 3rem;
            }
        }

        /* Widget 1 - Primary (Quick Actions) */
        .widget-primary {
            background: var(--gradient-primary);
            color: white;
        }

        /* Widget 2 - Secondary (Support) */
        .widget-secondary {
            background: var(--gradient-secondary);
            color: white;
        }

        /* Widget 3 - Dark (Back to Top) */
        .widget-dark {
            background: linear-gradient(135deg, #374151 0%, #111827 100%);
            color: white;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .floating-widget-container.show-all .widget-dark {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Widget Tooltip */
        .widget-tooltip {
            position: absolute;
            right: 4.5rem;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            pointer-events: none;
        }

        .widget-tooltip::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -6px;
            transform: translateY(-50%);
            border-left: 6px solid rgba(0, 0, 0, 0.8);
            border-top: 6px solid transparent;
            border-bottom: 6px solid transparent;
        }

        .floating-widget:hover .widget-tooltip {
            opacity: 1;
            visibility: visible;
        }

        /* Notification Dot */
        .notification-dot {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 0.75rem;
            height: 0.75rem;
            background: linear-gradient(135deg, #ef4444, #f59e0b);
            border-radius: 50%;
            border: 2px solid white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
        }

        /* Quick Actions Menu */
        .quick-actions-menu {
            position: absolute;
            right: 4.5rem;
            bottom: 0;
            width: 280px;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transform: translateX(20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
        }

        .quick-actions-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        .quick-actions-header {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .quick-action-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
            text-decoration: none;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .quick-action-item:hover {
            background: #f9fafb;
        }

        .quick-action-item:last-child {
            margin-bottom: 0;
        }

        .quick-action-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .quick-action-content {
            flex: 1;
        }

        .quick-action-title {
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.125rem;
        }

        .quick-action-desc {
            font-size: 0.75rem;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .quick-actions-menu {
                width: 260px;
                right: 3.5rem;
            }

            .quick-action-item {
                padding: 0.625rem;
            }

            .quick-action-icon {
                width: 2.25rem;
                height: 2.25rem;
            }
        }

        /* Menu Styles */
        .nav-link {
            position: relative;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
        }

        @media (max-width: 1024px) {
            .nav-link {
                padding: 0.4rem 0.75rem;
                gap: 0.4rem;
                font-size: 0.8rem;
            }
        }

        .nav-link:hover {
            background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.1), rgba(var(--primary-light-rgb), 0.1));
        }

        .nav-link.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.3);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 50%;
            background: white;
            border-radius: 2px;
        }

        /* Mobile Menu Styles */
        .mobile-nav-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            color: #374151;
            transition: all 0.3s;
            margin-bottom: 0.25rem;
            text-decoration: none;
        }

        .mobile-nav-item:hover,
        .mobile-nav-item.active {
            background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.1), rgba(var(--primary-light-rgb), 0.1));
            color: var(--primary);
        }

        .mobile-nav-item.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.3);
        }

        .mobile-nav-item i {
            width: 1.5rem;
            text-align: center;
            font-size: 1rem;
        }

        .badge {
            background-color: rgba(var(--primary-rgb), 0.1);
            color: var(--primary);
            font-size: 0.75rem;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.625rem 0.75rem;
            border-radius: 0.5rem;
            color: #374151;
            transition: all 0.3s;
            text-decoration: none;
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
        }

        .dropdown-item i {
            width: 1.25rem;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 0.875rem;
        }

        .footer-link {
            display: flex;
            align-items: center;
            color: #6b7280;
            transition: color 0.3s;
            font-size: 0.875rem;
            padding: 0.25rem 0;
            text-decoration: none;
        }

        .footer-link:hover {
            color: var(--primary);
        }

        /* Loading Animation */
        @keyframes spin-slow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .spin-slow {
            animation: spin-slow 3s linear infinite;
        }

        /* Table Responsive */
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }

            td, th {
                padding: 0.75rem 0.5rem !important;
            }
        }

        /* Form Responsive */
        @media (max-width: 640px) {
            input, select, textarea, button {
                font-size: 16px !important;
            }
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            padding-top: 4rem; /* For fixed nav */
            min-height: calc(100vh - 200px); /* Adjust based on footer height */
        }
    </style>

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), 40) }}',
                            100: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), 30) }}',
                            500: '{{ UnionHelper::getPrimaryColor() }}',
                            600: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), -10) }}',
                            700: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), -20) }}',
                        }
                    },
                    fontFamily: {
                        'bangla': ['Noto Sans Bengali', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif']
                    },
                    animation: {
                        'spin-slow': 'spin-slow 3s linear infinite',
                    }
                }
            },
            corePlugins: {
                container: false,
            }
        }
    </script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')
</head>
<body class="font-bangla">
    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-premium border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo - Dynamic from Settings -->
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        @if(UnionHelper::getLogoUrl() && UnionHelper::getLogoUrl() != asset('images/default-logo.png'))
                            <img src="{{ UnionHelper::getLogoUrl() }}"
                                 alt="{{ UnionHelper::getName() }}"
                                 class="w-10 h-10 rounded-xl object-cover shadow-lg">
                            <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
                        @else
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center shadow-lg">
                                <i class="fas fa-landmark text-white text-lg"></i>
                                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">{{ UnionHelper::getName() }}</h1>
                        <p class="text-xs text-gray-500">নাগরিক পোর্টাল</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    <div class="flex items-center space-x-1 bg-gray-100/50 rounded-xl p-1">
                        <a href="{{ route('citizen.dashboard') }}"
                           class="nav-link {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home text-sm"></i>
                            <span class="hidden lg:inline">ড্যাশবোর্ড</span>
                            <div class="notification-dot"></div>
                        </a>

                        <a href="{{ route('citizen.applications.index') }}"
                           class="nav-link {{ request()->routeIs('citizen.applications.*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt text-sm"></i>
                            <span class="hidden lg:inline">আবেদন</span>
                            <span class="ml-2 bg-primary-100 text-primary-600 text-xs px-2 py-0.5 rounded-full">5</span>
                        </a>

                        <a href="{{ route('citizen.invoices.index') }}"
                           class="nav-link {{ request()->routeIs('citizen.invoices.*') ? 'active' : '' }}">
                            <i class="fas fa-receipt text-sm"></i>
                            <span class="hidden lg:inline">ইনভয়েস</span>
                        </a>

                        <a href="{{ route('citizen.profile.show') }}"
                           class="nav-link {{ request()->routeIs('citizen.profile.*') ? 'active' : '' }}">
                            <i class="fas fa-user text-sm"></i>
                            <span class="hidden lg:inline">প্রোফাইল</span>
                        </a>
                    </div>

                    <!-- User Menu -->
                    <div class="relative group ml-4">
                        <button class="flex items-center space-x-2 px-4 py-2 rounded-xl bg-gradient-to-r from-gray-50 to-white border border-gray-200 hover:border-primary-200 transition-all duration-300">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-primary-500 to-purple-500 flex items-center justify-center">
                                <i class="fas fa-user text-white text-xs"></i>
                            </div>
                            <div class="text-left hidden lg:block">
                                <p class="text-sm font-medium text-gray-900 truncate max-w-[120px]">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">নাগরিক অ্যাকাউন্ট</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform -translate-y-2 group-hover:translate-y-0 z-50">
                            <div class="p-2">
                                <div class="px-3 py-3 border-b">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-purple-500 flex items-center justify-center">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-2">
                                    <a href="{{ route('citizen.profile.show') }}" class="dropdown-item">
                                        <i class="fas fa-user-circle text-gray-400"></i>
                                        <span>আমার প্রোফাইল</span>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <i class="fas fa-cog text-gray-400"></i>
                                        <span>সেটিংস</span>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <i class="fas fa-question-circle text-gray-400"></i>
                                        <span>সাহায্য</span>
                                    </a>
                                    <div class="border-t mt-2 pt-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-red-600">
                                                <i class="fas fa-sign-out-alt"></i>
                                                <span>লগ আউট</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Bell -->
                    <button class="relative w-10 h-10 rounded-xl bg-gray-100/50 flex items-center justify-center hover:bg-gray-200/50 transition">
                        <i class="fas fa-bell text-gray-600"></i>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-red-500 to-orange-500 text-white text-xs rounded-full flex items-center justify-center">
                            3
                        </span>
                    </button>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuButton" class="md:hidden w-10 h-10 rounded-xl bg-gradient-to-r from-primary-500 to-purple-500 text-white flex items-center justify-center">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Navigation Menu -->
    <div id="mobileMenu" class="fixed inset-0 z-50 hidden md:hidden">
        <div class="absolute inset-0 bg-black/50" id="mobileMenuBackdrop"></div>
        <div class="absolute right-0 top-0 bottom-0 w-80 bg-white shadow-2xl transform transition-transform duration-300 translate-x-full">
            <div class="p-4 h-full flex flex-col">
                <!-- Mobile Menu Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        @if(UnionHelper::getLogoUrl() && UnionHelper::getLogoUrl() != asset('images/default-logo.png'))
                            <img src="{{ UnionHelper::getLogoUrl() }}"
                                 alt="{{ UnionHelper::getName() }}"
                                 class="w-10 h-10 rounded-xl object-cover">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-primary-500 to-purple-500 flex items-center justify-center">
                                <i class="fas fa-landmark text-white text-lg"></i>
                            </div>
                        @endif
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">{{ UnionHelper::getName() }}</h2>
                            <p class="text-sm text-gray-500">নেভিগেশন</p>
                        </div>
                    </div>
                    <button id="closeMobileMenu" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>

                <!-- Mobile Menu Items -->
                <div class="flex-1 space-y-1 overflow-y-auto">
                    <a href="{{ route('citizen.dashboard') }}"
                       class="mobile-nav-item {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>ড্যাশবোর্ড</span>
                        <div class="notification-dot"></div>
                    </a>

                    <a href="{{ route('citizen.applications.index') }}"
                       class="mobile-nav-item {{ request()->routeIs('citizen.applications.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>আবেদনসমূহ</span>
                        <span class="badge">5</span>
                    </a>

                    <a href="{{ route('citizen.invoices.index') }}"
                       class="mobile-nav-item {{ request()->routeIs('citizen.invoices.*') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        <span>ইনভয়েস</span>
                    </a>

                    <a href="{{ route('citizen.profile.show') }}"
                       class="mobile-nav-item {{ request()->routeIs('citizen.profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>প্রোফাইল</span>
                    </a>
                </div>

                <!-- Mobile User Info -->
                <div class="pt-4 border-t">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-purple-500 flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 rounded-xl bg-gradient-to-r from-red-50 to-red-100 text-red-600 font-medium flex items-center justify-center space-x-2">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>লগ আউট</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Messages Container -->
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 pt-4">
            @if(session('success'))
            <div class="mb-4">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-r-xl p-3 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-sm"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
                        </div>
                        <button class="ml-2 text-green-600 hover:text-green-800" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4">
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-r-xl p-3 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-red-600 text-sm"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-red-900">{{ session('error') }}</p>
                        </div>
                        <button class="ml-2 text-red-600 hover:text-red-800" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4">
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-r-xl p-3 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-red-900 mb-1">নিম্নলিখিত ত্রুটি ঠিক করুন:</p>
                            <ul class="text-xs text-red-700 space-y-0.5">
                                @foreach($errors->all() as $error)
                                <li class="flex items-center">
                                    <i class="fas fa-circle text-[8px] mr-2"></i>
                                    {{ $error }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <button class="ml-2 text-red-600 hover:text-red-800" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Page Content -->
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4">
            @yield('content')
        </div>
    </main>

    <!-- Floating Widgets - Fixed on Left -->
    <div class="floating-widget-container" id="floatingWidgets">
        <!-- Widget 1: Quick Actions -->
        <div class="floating-widget widget-primary" id="quickActionsWidget">
            <i class="fas fa-bolt"></i>
            <span class="widget-tooltip">দ্রুত কাজ</span>
            <div class="notification-dot"></div>

            <!-- Quick Actions Menu -->
            <div class="quick-actions-menu" id="quickActionsMenu">
                <div class="quick-actions-header">দ্রুত কাজ</div>

                <a href="{{ route('citizen.certificates.index') }}" class="quick-action-item">
                    <div class="quick-action-icon bg-blue-100">
                        <i class="fas fa-plus text-blue-600"></i>
                    </div>
                    <div class="quick-action-content">
                        <div class="quick-action-title">নতুন আবেদন করুন</div>
                        <div class="quick-action-desc">সার্টিফিকেটের জন্য আবেদন করুন</div>
                    </div>
                </a>

                <a href="{{ route('citizen.invoices.index') }}" class="quick-action-item">
                    <div class="quick-action-icon bg-green-100">
                        <i class="fas fa-credit-card text-green-600"></i>
                    </div>
                    <div class="quick-action-content">
                        <div class="quick-action-title">পেমেন্ট করুন</div>
                        <div class="quick-action-desc">বকেয়া পেমেন্ট পরিশোধ করুন</div>
                    </div>
                </a>

                <a href="#" class="quick-action-item" onclick="downloadDocuments()">
                    <div class="quick-action-icon bg-purple-100">
                        <i class="fas fa-download text-purple-600"></i>
                    </div>
                    <div class="quick-action-content">
                        <div class="quick-action-title">ডকুমেন্ট ডাউনলোড</div>
                        <div class="quick-action-desc">আপনার সার্টিফিকেট ডাউনলোড করুন</div>
                    </div>
                </a>

                <a href="tel:{{ UnionHelper::getContactNumber() }}" class="quick-action-item">
                    <div class="quick-action-icon bg-orange-100">
                        <i class="fas fa-phone text-orange-600"></i>
                    </div>
                    <div class="quick-action-content">
                        <div class="quick-action-title">সাপোর্ট কল করুন</div>
                        <div class="quick-action-desc">২৪/৭ সাপোর্ট: {{ UnionHelper::getContactNumber() }}</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Widget 2: Support -->
        <div class="floating-widget widget-secondary" onclick="callSupport()">
            <i class="fas fa-headset"></i>
            <span class="widget-tooltip">সাহায্য</span>
        </div>

        <!-- Widget 3: Back to Top -->
        <div class="floating-widget widget-dark" id="backToTopWidget">
            <i class="fas fa-arrow-up"></i>
            <span class="widget-tooltip">উপরে যান</span>
        </div>
    </div>

    <!-- Footer - Dynamic from Settings -->
    <footer class="bg-gradient-to-b from-white via-gray-50 to-gray-100 border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-6 md:py-12">
            <!-- Footer Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 mb-6 md:mb-8">
                <!-- Brand Info -->
                <div class="space-y-3 md:space-y-4">
                    <div class="flex items-center space-x-3">
                        @if(UnionHelper::getLogoUrl() && UnionHelper::getLogoUrl() != asset('images/default-logo.png'))
                            <img src="{{ UnionHelper::getLogoUrl() }}"
                                 alt="{{ UnionHelper::getName() }}"
                                 class="w-10 h-10 md:w-12 md:h-12 rounded-xl object-cover">
                        @else
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gradient-to-r from-primary-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-landmark text-white text-lg md:text-xl"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-lg md:text-xl font-bold text-gray-900">{{ UnionHelper::getName() }}</h3>
                            <p class="text-xs md:text-sm text-gray-600">ডিজিটাল গভর্নেন্স প্ল্যাটফর্ম</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-xs md:text-sm">
                        উদ্ভাবনী ডিজিটাল সেবা এবং স্বচ্ছ গভর্নেন্স সমাধানের মাধ্যমে নাগরিকদের ক্ষমতায়ন।
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">দ্রুত অ্যাক্সেস</h4>
                    <div class="space-y-1 md:space-y-2">
                        <a href="{{ route('citizen.dashboard') }}" class="footer-link">
                            <i class="fas fa-chevron-right text-primary-500 text-xs mr-2"></i>
                            ড্যাশবোর্ড
                        </a>
                        <a href="{{ route('citizen.applications.index') }}" class="footer-link">
                            <i class="fas fa-chevron-right text-primary-500 text-xs mr-2"></i>
                            আমার আবেদন
                        </a>
                        <a href="{{ route('citizen.invoices.index') }}" class="footer-link">
                            <i class="fas fa-chevron-right text-primary-500 text-xs mr-2"></i>
                            পেমেন্ট
                        </a>
                        <a href="{{ route('citizen.profile.show') }}" class="footer-link">
                            <i class="fas fa-chevron-right text-primary-500 text-xs mr-2"></i>
                            প্রোফাইল সেটিংস
                        </a>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">আমাদের সেবা</h4>
                    <div class="space-y-1 md:space-y-2">
                        <div class="flex items-center text-gray-600 text-xs md:text-sm">
                            <div class="w-2 h-2 rounded-full bg-primary-500 mr-2"></div>
                            সার্টিফিকেট সেবা
                        </div>
                        <div class="flex items-center text-gray-600 text-xs md:text-sm">
                            <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                            অনলাইন পেমেন্ট
                        </div>
                        <div class="flex items-center text-gray-600 text-xs md:text-sm">
                            <div class="w-2 h-2 rounded-full bg-blue-500 mr-2"></div>
                            ডকুমেন্ট যাচাই
                        </div>
                        <div class="flex items-center text-gray-600 text-xs md:text-sm">
                            <div class="w-2 h-2 rounded-full bg-purple-500 mr-2"></div>
                            স্ট্যাটাস ট্র্যাকিং
                        </div>
                    </div>
                </div>

                <!-- Contact Info - Dynamic from Settings -->
                <div>
                    <h4 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">যোগাযোগ করুন</h4>
                    <div class="space-y-2 md:space-y-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-blue-50 flex items-center justify-center mr-2 md:mr-3">
                                <i class="fas fa-phone text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">২৪/৭ সাপোর্ট</p>
                                <p class="font-medium text-gray-900 text-sm md:text-base">+৮৮০ {{ UnionHelper::getContactNumber() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-green-50 flex items-center justify-center mr-2 md:mr-3">
                                <i class="fas fa-envelope text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">ইমেইল</p>
                                <p class="font-medium text-gray-900 text-sm md:text-base">{{ UnionHelper::getEmail() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="pt-4 md:pt-8 border-t border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-3 md:mb-0">
                        <p class="text-gray-600 text-xs md:text-sm">
                            &copy; {{ date('Y') }} {{ UnionHelper::getName() }}। সকল অধিকার সংরক্ষিত।
                        </p>
                        <p class="text-gray-500 text-xs mt-1">
                            ভার্সন ২.১.০ • ডিজিটাল বাংলাদেশের জন্য তৈরি
                        </p>
                    </div>

                    <div class="flex items-center space-x-3 md:space-x-4">
                        @php
                            $socialLinks = UnionHelper::getSocialLinks();
                        @endphp
                        @if($socialLinks['facebook'] != '#')
                        <a href="{{ $socialLinks['facebook'] }}" class="text-gray-500 hover:text-primary-600 transition text-sm">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif
                        @if($socialLinks['twitter'] != '#')
                        <a href="{{ $socialLinks['twitter'] }}" class="text-gray-500 hover:text-blue-400 transition text-sm">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif
                        @if($socialLinks['youtube'] != '#')
                        <a href="{{ $socialLinks['youtube'] }}" class="text-gray-500 hover:text-pink-600 transition text-sm">
                            <i class="fab fa-youtube"></i>
                        </a>
                        @endif
                        @if($socialLinks['website'] != '#')
                        <a href="{{ $socialLinks['website'] }}" class="text-gray-500 hover:text-blue-700 transition text-sm">
                            <i class="fas fa-globe"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center hidden">
        <div class="text-center p-4">
            <div class="w-12 h-12 md:w-16 md:h-16 rounded-full border-4 border-t-primary-500 border-r-transparent border-b-purple-500 border-l-transparent animate-spin-slow mx-auto mb-3 md:mb-4"></div>
            <p class="text-white font-medium text-base md:text-lg mb-1 md:mb-2">অনুরোধ প্রক্রিয়াধীন</p>
            <p class="text-gray-300 text-xs md:text-sm">আপনার অ্যাকশন সম্পন্ন হওয়া পর্যন্ত অপেক্ষা করুন</p>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Premium Union Platform Loaded');

            // Initialize all components
            initFloatingWidgets();
            initMobileMenu();
            initQuickActionsMenu();
            initBackToTop();
            initMessages();
            // initFormLoading();
            initTouchSupport();

            // Protect body styles from being changed
            protectBodyStyles();
        });

        // Function to protect body styles from being changed
        function protectBodyStyles() {
            const fixedStyles = document.getElementById('dynamic-styles');
            const originalBodyStyles = fixedStyles.innerHTML;

            // Monitor for style changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' || mutation.type === 'attributes') {
                        // Check if body styles are being changed
                        if (fixedStyles.innerHTML !== originalBodyStyles) {
                            console.warn('Attempt to modify fixed body styles detected. Reverting...');
                            fixedStyles.innerHTML = originalBodyStyles;
                        }
                    }
                });
            });

            // Start observing
            observer.observe(fixedStyles, {
                attributes: true,
                childList: true,
                characterData: true,
                subtree: true
            });

            // Also protect the body element itself
            const bodyElement = document.body;
            const originalBodyClass = bodyElement.className;

            setInterval(function() {
                if (bodyElement.className !== originalBodyClass) {
                    console.warn('Body class being modified. Reverting...');
                    bodyElement.className = originalBodyClass + ' font-bangla';
                }
            }, 100);
        }

        // Floating Widgets Behavior
        function initFloatingWidgets() {
            const floatingWidgets = document.getElementById('floatingWidgets');
            const backToTopWidget = document.getElementById('backToTopWidget');
            let lastScrollTop = 0;
            let isAtTop = true;

            // Initial check
            checkScrollPosition();

            // Listen to scroll events
            window.addEventListener('scroll', function() {
                checkScrollPosition();
            });

            function checkScrollPosition() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                // Check if at top of page
                isAtTop = scrollTop < 100;

                // Update widget container class
                if (isAtTop) {
                    floatingWidgets.classList.add('hide-top');
                    floatingWidgets.classList.remove('show-all');
                } else {
                    floatingWidgets.classList.remove('hide-top');
                    floatingWidgets.classList.add('show-all');
                }

                // Show/hide back to top widget based on scroll position
                if (scrollTop > 300) {
                    backToTopWidget.style.opacity = '1';
                    backToTopWidget.style.visibility = 'visible';
                    backToTopWidget.style.transform = 'translateY(0)';
                } else {
                    backToTopWidget.style.opacity = '0';
                    backToTopWidget.style.visibility = 'hidden';
                    backToTopWidget.style.transform = 'translateY(10px)';
                }

                lastScrollTop = scrollTop;
            }
        }

        // Quick Actions Menu
        function initQuickActionsMenu() {
            const quickActionsWidget = document.getElementById('quickActionsWidget');
            const quickActionsMenu = document.getElementById('quickActionsMenu');
            let isMenuOpen = false;

            // Toggle menu on widget click
            quickActionsWidget.addEventListener('click', function(e) {
                e.stopPropagation();
                isMenuOpen = !isMenuOpen;

                if (isMenuOpen) {
                    quickActionsMenu.classList.add('show');
                } else {
                    quickActionsMenu.classList.remove('show');
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!quickActionsWidget.contains(e.target) && !quickActionsMenu.contains(e.target)) {
                    quickActionsMenu.classList.remove('show');
                    isMenuOpen = false;
                }
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isMenuOpen) {
                    quickActionsMenu.classList.remove('show');
                    isMenuOpen = false;
                }
            });
        }

        // Back to Top Functionality
        function initBackToTop() {
            const backToTopWidget = document.getElementById('backToTopWidget');

            backToTopWidget.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Support Call Function
        function callSupport() {
            const contactNumber = "{{ UnionHelper::getContactNumber() }}";
            if (confirm('আপনি কি ' + contactNumber + ' নম্বরে কল করতে চান?')) {
                window.open('tel:' + contactNumber);
            }
        }

        // Download Documents Function
        function downloadDocuments() {
            // Show loading
            document.getElementById('loadingOverlay').classList.remove('hidden');

            // Simulate download process
            setTimeout(() => {
                document.getElementById('loadingOverlay').classList.add('hidden');
                alert('ডকুমেন্ট ডাউনলোড শুরু হয়েছে।');
            }, 1500);
        }

        // Mobile Menu
        function initMobileMenu() {
            const menuButton = document.getElementById('mobileMenuButton');
            const closeButton = document.getElementById('closeMobileMenu');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuBackdrop = document.getElementById('mobileMenuBackdrop');

            if (!menuButton || !mobileMenu) return;

            // Function to open mobile menu
            function openMobileMenu() {
                mobileMenu.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Add a small delay for smooth animation
                setTimeout(() => {
                    mobileMenu.querySelector('.absolute.right-0').classList.remove('translate-x-full');
                }, 10);
            }

            // Function to close mobile menu
            function closeMobileMenu() {
                mobileMenu.querySelector('.absolute.right-0').classList.add('translate-x-full');

                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 300);
            }

            // Open menu when hamburger button is clicked
            menuButton.addEventListener('click', openMobileMenu);

            // Close menu when X button is clicked
            closeButton.addEventListener('click', closeMobileMenu);

            // Close menu when backdrop is clicked
            mobileMenuBackdrop.addEventListener('click', closeMobileMenu);

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                    closeMobileMenu();
                }
            });

            // Close menu when clicking on menu items
            document.querySelectorAll('.mobile-nav-item').forEach(item => {
                item.addEventListener('click', function() {
                    setTimeout(closeMobileMenu, 300);
                });
            });
        }

        // Auto-hide Messages
        function initMessages() {
            const messages = document.querySelectorAll('.bg-gradient-to-r');

            messages.forEach(message => {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s, transform 0.5s';
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-20px)';

                    setTimeout(() => {
                        if (message.parentNode) {
                            message.remove();
                        }
                    }, 500);
                }, 5000);
            });
        }

        // Form Loading Overlay
        function initFormLoading() {
            const forms = document.querySelectorAll('form');

            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!form.classList.contains('ajax-form')) {
                        document.getElementById('loadingOverlay').classList.remove('hidden');
                    }
                });
            });

            window.addEventListener('load', function() {
                document.getElementById('loadingOverlay').classList.add('hidden');
            });
        }

        // Touch Support Improvements
        function initTouchSupport() {
            // Prevent zoom on double tap
            let lastTouchEnd = 0;
            document.addEventListener('touchend', function(event) {
                const now = (new Date()).getTime();
                if (now - lastTouchEnd <= 300) {
                    event.preventDefault();
                }
                lastTouchEnd = now;
            }, false);

            // Improve touch feedback for widgets
            document.querySelectorAll('.floating-widget').forEach(widget => {
                widget.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.95)';
                });

                widget.addEventListener('touchend', function() {
                    this.style.transform = '';
                });
            });
        }

        // Add dynamic CSS for mobile menu
        const style = document.createElement('style');
        style.textContent = `
            /* Mobile Menu Animation */
            #mobileMenu .absolute.right-0 {
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                transform: translateX(100%);
            }

            #mobileMenu .absolute.right-0:not(.translate-x-full) {
                transform: translateX(0);
            }

            /* Floating widgets adjustment when menu is open */
            #mobileMenu:not(.hidden) ~ .floating-widget-container {
                opacity: 0.5;
                pointer-events: none;
            }

            @media (max-width: 768px) {
                .floating-widget-container {
                    gap: 0.5rem;
                }

                .quick-actions-menu {
                    width: calc(100vw - 5rem);
                    max-width: 280px;
                }

                .widget-tooltip {
                    display: none;
                }

                .floating-widget:active {
                    transform: scale(0.9);
                }
            }

            /* Better focus styles for accessibility */
            .floating-widget:focus-visible {
                outline: 2px solid var(--primary);
                outline-offset: 2px;
            }

            button:focus-visible,
            a:focus-visible,
            input:focus-visible,
            select:focus-visible,
            textarea:focus-visible {
                outline: 2px solid var(--primary);
                outline-offset: 2px;
                border-radius: 4px;
            }
        `;
        document.head.appendChild(style);
    </script>

    @stack('scripts')
</body>
</html>
