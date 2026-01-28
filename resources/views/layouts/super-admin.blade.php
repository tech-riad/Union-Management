<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'সুপার অ্যাডমিন - ইউনিয়ন ডিজিটাল')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        darkblue: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#1e3a8a',
                            600: '#1e40af',
                            700: '#1d4ed8',
                            800: '#2563eb',
                            900: '#0f172a',
                        }
                    },
                    fontFamily: {
                        'bangla': ['Noto Sans Bengali', 'sans-serif']
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'shimmer': 'shimmer 3s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideDown: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '1', transform: 'translateY(0)' },
                            '100%': { opacity: '0', transform: 'translateY(-10px)' }
                        },
                        shimmer: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(100%)' }
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Noto Sans Bengali', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #334155;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Shimmer Effect */
        .shimmer-effect {
            position: relative;
            overflow: hidden;
        }
        
        .shimmer-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }
        
        /* Dark Blue Theme */
        .darkblue-theme {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
        }
        
        .darkblue-dropdown {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .darkblue-submenu {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Sidebar Styles */
        .sidebar-open .sidebar-submenu {
            display: block;
            animation: slideDown 0.3s ease-out;
        }
        
        .sidebar-closed .sidebar-submenu {
            display: none;
            animation: slideUp 0.3s ease-out;
        }
        
        /* Prevent horizontal scroll */
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }
        
        /* Fixed z-index management */
        #mobileBackdrop {
            z-index: 49;
        }
        
        #sidebar {
            z-index: 50;
        }
        
        /* Smooth transitions */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Hide scrollbar for sidebar */
        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }
        
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }
        
        /* Ensure proper stacking context */
        #mainContent {
            position: relative;
            z-index: 1;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-bangla">
    <!-- Mobile Sidebar Backdrop -->
    <div id="mobileBackdrop" 
         class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"
         style="opacity: 0; transition: opacity 0.3s ease;"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar" 
           class="fixed top-0 left-0 bottom-0 w-64 darkblue-theme text-white z-50 
                  transform -translate-x-full lg:translate-x-0 sidebar-transition
                  shadow-xl overflow-hidden">
        
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-white/10">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-center shadow-lg">
                    <i class="fas fa-university text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold gradient-text">সুপার অ্যাডমিন</h2>
                    <p class="text-sm text-blue-200">ইউনিয়ন ডিজিটাল</p>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Menu -->
        <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-140px)] sidebar-scroll">
            <!-- Dashboard -->
            <a href="{{ route('super_admin.dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200
                      {{ request()->routeIs('super_admin.dashboard') ? 
                         'bg-gradient-to-r from-blue-600/30 to-blue-800/30 text-white border-l-4 border-blue-400' : 
                         'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span class="flex-1">ড্যাশবোর্ড</span>
            </a>
            
            <!-- User Management Dropdown -->
            <div class="relative" id="userManagementDropdown">
                <button onclick="toggleSubmenu('userManagement')" 
                        class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all duration-200 group">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-users w-5 text-center"></i>
                        <span>ইউজার ম্যানেজমেন্ট</span>
                    </div>
                    <i id="userManagementIcon" class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>
                
                <!-- Dropdown Menu -->
                <div id="userManagementSubmenu" class="ml-4 mt-1 space-y-1 hidden">
                    <!-- All Users -->
                    <a href="{{ route('super_admin.users.citizens.index') }}" 
                       class="flex items-center justify-between space-x-3 px-4 py-2 rounded-lg transition-all duration-200
                              {{ request()->routeIs('super_admin.users.index') ? 
                                 'bg-gradient-to-r from-blue-600/20 to-blue-800/20 text-white' : 
                                 'text-blue-200 hover:bg-blue-900/30 hover:text-white' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-users-cog w-5 text-center"></i>
                            <span class="flex-1">সকল ইউজার</span>
                        </div>
                        <span class="text-xs bg-blue-600 px-2 py-1 rounded-full">
                            {{ \App\Models\User::where('role', 'citizen')->count() }}
                        </span>
                    </a>
                    
                    <!-- Add New User -->
                    <a href="{{ route('super_admin.users.citizens.create') }}" 
                       class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-200 hover:bg-blue-900/30 hover:text-white transition-all duration-200">
                        <i class="fas fa-user-plus w-5 text-center"></i>
                        <span class="flex-1">ইউজার যোগ করুন</span>
                    </a>
                    
                    <div class="border-t border-blue-700/30 my-2"></div>
                    
                    <!-- Admins -->
                    <a href="{{ route('super_admin.users.admins.index') }}" 
                       class="flex items-center justify-between space-x-3 px-4 py-2 rounded-lg text-blue-200 hover:bg-blue-900/30 hover:text-white transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user-shield w-5 text-center"></i>
                            <span class="flex-1">সকল অ্যাডমিন</span>
                        </div>
                        <span class="text-xs bg-purple-600 px-2 py-1 rounded-full">
                            {{ \App\Models\User::where('role', 'admin')->count() }}
                        </span>
                    </a>
                    
                    <!-- Secretaries -->
                    <a href="{{ route('super_admin.users.admins.create') }}" 
                       class="flex items-center justify-between space-x-3 px-4 py-2 rounded-lg text-blue-200 hover:bg-blue-900/30 hover:text-white transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user-plus w-5 text-center"></i>
                            <span class="flex-1">অ্যাডমিন যোগ করুন</span>
                        </div>
                        
                    </a>

                </div>
            </div>
            
            <!-- Applications -->
            <a href="{{ route('super_admin.applications.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200
                      {{ request()->routeIs('super_admin.applications.*') ? 
                         'bg-gradient-to-r from-blue-600/30 to-blue-800/30 text-white border-l-4 border-blue-400' : 
                         'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                <i class="fas fa-file-alt w-5 text-center"></i>
                <span class="flex-1">আবেদনসমূহ</span>
            </a>
            
            <!-- Certificates -->
            <a href="{{ route('super_admin.certificates.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200
                      {{ request()->routeIs('super_admin.certificates.*') ? 
                         'bg-gradient-to-r from-blue-600/30 to-blue-800/30 text-white border-l-4 border-blue-400' : 
                         'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                <i class="fas fa-certificate w-5 text-center"></i>
                <span class="flex-1">সার্টিফিকেট</span>
            </a>
            
            <!-- System Settings Dropdown -->
            <div class="relative" id="settingsDropdown">
                <button onclick="toggleSubmenu('settings')" 
                        class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all duration-200 group">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-cogs w-5 text-center"></i>
                        <span>সিস্টেম সেটিংস</span>
                    </div>
                    <i id="settingsIcon" class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>
                
                <!-- Dropdown Menu -->
                <div id="settingsSubmenu" class="ml-4 mt-1 space-y-1 hidden">
                    <!-- Union Settings -->
                    <a href="{{ route('super_admin.settings.union') }}" 
                       class="flex items-center space-x-3 px-4 py-2 rounded-lg transition-all duration-200
                              {{ request()->routeIs('super_admin.settings.union') ? 
                                 'bg-gradient-to-r from-blue-600/20 to-blue-800/20 text-white' : 
                                 'text-blue-200 hover:bg-blue-900/30 hover:text-white' }}">
                        <i class="fas fa-university w-5 text-center"></i>
                        <span class="flex-1">ইউনিয়ন সেটিংস</span>
                    </a>
                    
                    <!-- General Settings -->
                    <a href="{{ route('super_admin.settings.backup') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-200 hover:bg-blue-900/30 hover:text-white transition-all duration-200">
                        <i class="fas fa-sliders-h w-5 text-center"></i>
                        <span class="flex-1">ব্যাকআপ সেটিংস</span>
                    </a>
                    
                    <!-- Payment Settings -->
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-200 hover:bg-blue-900/30 hover:text-white transition-all duration-200">
                        <i class="fas fa-receipt w-5 text-center"></i>
                        <span class="flex-1">পেমেন্ট সেটিংস</span>
                    </a>
                    
                    <!-- Email Settings -->
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-200 hover:bg-blue-900/30 hover:text-white transition-all duration-200">
                        <i class="fas fa-envelope w-5 text-center"></i>
                        <span class="flex-1">ইমেইল সেটিংস</span>
                    </a>
                    
                    <!-- SMS Settings -->
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-200 hover:bg-blue-900/30 hover:text-white transition-all duration-200">
                        <i class="fas fa-sms w-5 text-center"></i>
                        <span class="flex-1">SMS সেটিংস</span>
                    </a>
                </div>
            </div>
            
            <!-- Reports Dropdown -->
            <div class="relative" id="reportsDropdown">
                <button onclick="toggleSubmenu('reports')" 
                        class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all duration-200 group">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-chart-bar w-5 text-center"></i>
                        <span>রিপোর্টস</span>
                    </div>
                    <i id="reportsIcon" class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>
                
                <!-- Dropdown Menu -->
                <div id="reportsSubmenu" class="ml-4 mt-1 space-y-1 hidden">
                    <a href="{{ route('super_admin.reports.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-200 hover:bg-blue-900/30 hover:text-white transition-all duration-200">
                        <i class="fas fa-chart-line w-5 text-center"></i>
                        <span class="flex-1">সকল রিপোর্ট</span>
                    </a>
                    
                    <a href="{{ route('super_admin.reports.revenue') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-200 hover:bg-blue-900/30 hover:text-white transition-all duration-200">
                        <i class="fas fa-money-bill-wave w-5 text-center"></i>
                        <span class="flex-1">রেভিনিউ রিপোর্ট</span>
                    </a>
                    
                    <a href="{{ route('super_admin.reports.applications') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-200 hover:bg-blue-900/30 hover:text-white transition-all duration-200">
                        <i class="fas fa-file-contract w-5 text-center"></i>
                        <span class="flex-1">আবেদন রিপোর্ট</span>
                    </a>
                </div>
            </div>

            <!-- Audit Log -->
            <a href="{{ route('super_admin.activity_logs.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all duration-200">
                <i class="fas fa-history w-5 text-center"></i>
                <span class="flex-1">অডিট লগ</span>
            </a>     
            
            <!-- Backup -->
            <a href="{{ route('super_admin.reports.backup') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all duration-200">
                <i class="fas fa-database w-5 text-center"></i>
                <span class="flex-1">ব্যাকআপ</span>
            </a>

            <!-- System Update -->
            <a href="{{ route('super_admin.system_update.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all duration-200">
                <i class="fas fa-sync-alt w-5 text-center"></i>
                <span class="flex-1">সিস্টেম আপডেট</span>
            </a>
        </nav>
        
        <!-- Sidebar Footer -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10 bg-blue-900/30">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="flex items-center justify-center space-x-2 w-full px-4 py-3 bg-gradient-to-r from-blue-700 to-blue-900 hover:from-blue-800 hover:to-blue-900 text-white rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>লগ আউট</span>
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div id="mainContent" class="min-h-screen lg:ml-64 flex flex-col">
        <!-- Top Navigation -->
        <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-200 shadow-sm">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Mobile Menu Button -->
                    <button id="sidebarToggle" 
                            class="lg:hidden w-10 h-10 rounded-lg bg-gradient-to-r from-blue-700 to-blue-900 text-white flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <!-- Page Title -->
                    <div class="flex-1 flex justify-center lg:justify-start">
                        <h1 class="text-xl font-bold text-gray-800 relative pl-4">
                            <span class="absolute left-0 top-1/2 transform -translate-y-1/2 w-1 h-6 bg-gradient-to-b from-blue-700 to-blue-900 rounded"></span>
                            @yield('title')
                        </h1>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center space-x-3 focus:outline-none">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-700 to-blue-900 rounded-full flex items-center justify-center text-white font-bold shadow-md hover:shadow-lg transition-all duration-200">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="hidden lg:block text-left">
                                <div class="font-bold text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-sm text-gray-500">সুপার অ্যাডমিন</div>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-56 darkblue-dropdown rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="p-4">
                                <!-- User Info -->
                                <div class="mb-4 p-3 bg-gradient-to-r from-blue-800/50 to-blue-900/50 rounded-lg">
                                    <h6 class="font-bold text-white">{{ Auth::user()->name }}</h6>
                                    <p class="text-sm text-blue-200">{{ Auth::user()->email }}</p>
                                    <span class="inline-block mt-1 px-2 py-1 text-xs bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded">সুপার অ্যাডমিন</span>
                                </div>
                                
                                <!-- Menu Items -->
                                <div class="space-y-1">
                                    <a href="{{ route('super_admin.users.admins.edit', Auth::user()) }}" class="flex items-center space-x-2 px-3 py-2 text-blue-200 hover:bg-blue-800/30 hover:text-white rounded-lg transition duration-150">
                                        <i class="fas fa-user text-blue-300 w-5"></i>
                                        <span>প্রোফাইল</span>
                                    </a>
                                    
                                    <a href="{{ route('super_admin.settings.union') }}" class="flex items-center space-x-2 px-3 py-2 text-blue-200 hover:bg-blue-800/30 hover:text-white rounded-lg transition duration-150">
                                        <i class="fas fa-cog text-blue-300 w-5"></i>
                                        <span>সেটিংস</span>
                                    </a>
                                    
                                    <a href="#" class="flex items-center space-x-2 px-3 py-2 text-blue-200 hover:bg-blue-800/30 hover:text-white rounded-lg transition duration-150">
                                        <i class="fas fa-shield-alt text-blue-300 w-5"></i>
                                        <span>নিরাপত্তা</span>
                                    </a>
                                    
                                    <div class="border-t border-blue-700/50 my-2"></div>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="flex items-center space-x-2 w-full px-3 py-2 text-red-300 hover:bg-red-900/30 hover:text-white rounded-lg transition duration-150">
                                            <i class="fas fa-sign-out-alt w-5"></i>
                                            <span>লগ আউট</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main Content Area -->
        <main class="flex-grow p-4 sm:p-6 lg:p-8">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-6 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-600 p-4 animate-fade-in shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-2xl text-green-600 mr-3"></i>
                    <div class="flex-1">
                        <h6 class="font-bold mb-1 text-green-800">সফল!</h6>
                        <p class="mb-0 text-green-700">{{ session('success') }}</p>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-600 p-4 animate-fade-in shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-2xl text-red-600 mr-3"></i>
                    <div class="flex-1">
                        <h6 class="font-bold mb-1 text-red-800">ত্রুটি!</h6>
                        <p class="mb-0 text-red-700">{{ session('error') }}</p>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-600 p-4 animate-fade-in shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600 mr-3"></i>
                    <div class="flex-1">
                        <h6 class="font-bold mb-1 text-red-800">নিম্নলিখিত ত্রুটি ঠিক করুন:</h6>
                        <ul class="mt-2 space-y-1">
                            @foreach($errors->all() as $error)
                            <li class="flex items-start text-red-700">
                                <i class="fas fa-circle text-xs mt-1 mr-2"></i>
                                {{ $error }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @endif
            
            <!-- Page Content -->
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-4 px-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="text-sm text-gray-600">
                    © {{ date('Y') }} ইউনিয়ন পোর্টাল। সর্বস্বত্ব সংরক্ষিত।
                    <span class="hidden md:inline">•</span>
                    <span class="block md:inline mt-1 md:mt-0">
                       ডেভেলপ করেছেন 
                        <a href="https://sinhaithost.com.bd"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-primary-600 hover:text-primary-700 font-medium">
                            সিনহা আইটি হোস্ট
                        </a>
                    </span>
                </div>
                <div class="mt-2 md:mt-0">
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <a href="#" class="hover:text-primary-600 transition duration-300">
                            <i class="fas fa-life-ring mr-1"></i> সাপোর্ট
                        </a>
                        <a href="#" class="hover:text-primary-600 transition duration-300">
                            <i class="fas fa-book mr-1"></i> ডকুমেন্টেশন
                        </a>
                        <a href="#" class="hover:text-primary-600 transition duration-300">
                            <i class="fas fa-shield-alt mr-1"></i> গোপনীয়তা
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mobileBackdrop = document.getElementById('mobileBackdrop');
            
            let isSidebarOpen = false;
            
            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                mobileBackdrop.classList.remove('hidden');
                setTimeout(() => {
                    mobileBackdrop.style.opacity = '1';
                }, 10);
                document.body.style.overflow = 'hidden';
                isSidebarOpen = true;
            }
            
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                mobileBackdrop.style.opacity = '0';
                setTimeout(() => {
                    mobileBackdrop.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
                isSidebarOpen = false;
            }
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (isSidebarOpen) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }
            
            // Close sidebar when clicking on backdrop
            if (mobileBackdrop) {
                mobileBackdrop.addEventListener('click', function() {
                    closeSidebar();
                });
            }
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isSidebarOpen) {
                    closeSidebar();
                }
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (isSidebarOpen && window.innerWidth < 1024) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        closeSidebar();
                    }
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    // On large screens, ensure sidebar is visible and backdrop is hidden
                    sidebar.classList.remove('-translate-x-full');
                    mobileBackdrop.classList.add('hidden');
                    mobileBackdrop.style.opacity = '0';
                    document.body.style.overflow = '';
                    isSidebarOpen = false;
                } else if (isSidebarOpen) {
                    // On small screens, if sidebar is open, show backdrop
                    mobileBackdrop.classList.remove('hidden');
                    mobileBackdrop.style.opacity = '1';
                }
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('main > div[class*="bg-gradient-to-r"]');
                alerts.forEach(alert => {
                    alert.style.transition = 'all 0.3s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    alert.style.marginBottom = '0';
                    alert.style.padding = '0';
                    alert.style.height = '0';
                    alert.style.overflow = 'hidden';
                    
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                });
            }, 5000);
        });
        
        // Submenu toggle function
        function toggleSubmenu(menuType) {
            const submenu = document.getElementById(menuType + 'Submenu');
            const icon = document.getElementById(menuType + 'Icon');
            
            // Close all other submenus first
            const allMenuTypes = ['userManagement', 'settings', 'reports'];
            allMenuTypes.forEach(type => {
                if (type !== menuType) {
                    const otherSubmenu = document.getElementById(type + 'Submenu');
                    const otherIcon = document.getElementById(type + 'Icon');
                    if (otherSubmenu && !otherSubmenu.classList.contains('hidden')) {
                        otherSubmenu.classList.add('hidden');
                        otherSubmenu.classList.remove('animate-slide-down');
                        if (otherIcon) {
                            otherIcon.classList.remove('fa-chevron-up');
                            otherIcon.classList.add('fa-chevron-down');
                        }
                    }
                }
            });
            
            // Toggle current submenu
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                submenu.classList.add('animate-slide-down');
                if (icon) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('animate-slide-down');
                if (icon) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            }
        }
        
        // Close submenus when clicking outside (for desktop)
        document.addEventListener('click', function(event) {
            const isMobile = window.innerWidth < 1024;
            if (isMobile) return;
            
            const userManagementDropdown = document.getElementById('userManagementDropdown');
            const settingsDropdown = document.getElementById('settingsDropdown');
            const reportsDropdown = document.getElementById('reportsDropdown');
            
            let shouldCloseUserManagement = true;
            let shouldCloseSettings = true;
            let shouldCloseReports = true;
            
            if (userManagementDropdown && userManagementDropdown.contains(event.target)) {
                shouldCloseUserManagement = false;
            }
            
            if (settingsDropdown && settingsDropdown.contains(event.target)) {
                shouldCloseSettings = false;
            }
            
            if (reportsDropdown && reportsDropdown.contains(event.target)) {
                shouldCloseReports = false;
            }
            
            // Close user management submenu
            if (shouldCloseUserManagement) {
                const userManagementSubmenu = document.getElementById('userManagementSubmenu');
                const userManagementIcon = document.getElementById('userManagementIcon');
                if (userManagementSubmenu && !userManagementSubmenu.classList.contains('hidden')) {
                    userManagementSubmenu.classList.add('hidden');
                    userManagementSubmenu.classList.remove('animate-slide-down');
                    if (userManagementIcon) {
                        userManagementIcon.classList.remove('fa-chevron-up');
                        userManagementIcon.classList.add('fa-chevron-down');
                    }
                }
            }
            
            // Close settings submenu
            if (shouldCloseSettings) {
                const settingsSubmenu = document.getElementById('settingsSubmenu');
                const settingsIcon = document.getElementById('settingsIcon');
                if (settingsSubmenu && !settingsSubmenu.classList.contains('hidden')) {
                    settingsSubmenu.classList.add('hidden');
                    settingsSubmenu.classList.remove('animate-slide-down');
                    if (settingsIcon) {
                        settingsIcon.classList.remove('fa-chevron-up');
                        settingsIcon.classList.add('fa-chevron-down');
                    }
                }
            }
            
            // Close reports submenu
            if (shouldCloseReports) {
                const reportsSubmenu = document.getElementById('reportsSubmenu');
                const reportsIcon = document.getElementById('reportsIcon');
                if (reportsSubmenu && !reportsSubmenu.classList.contains('hidden')) {
                    reportsSubmenu.classList.add('hidden');
                    reportsSubmenu.classList.remove('animate-slide-down');
                    if (reportsIcon) {
                        reportsIcon.classList.remove('fa-chevron-up');
                        reportsIcon.classList.add('fa-chevron-down');
                    }
                }
            }
        });
        
        // Global function for showing toast messages
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-lg z-[100] transition-all duration-300 transform translate-x-full`;
            
            const typeClasses = {
                success: 'bg-gradient-to-r from-green-500 to-emerald-600 text-white',
                error: 'bg-gradient-to-r from-red-500 to-rose-600 text-white',
                warning: 'bg-gradient-to-r from-yellow-500 to-orange-600 text-white',
                info: 'bg-gradient-to-r from-blue-500 to-cyan-600 text-white'
            };
            
            toast.classList.add(typeClasses[type]);
            
            const icon = type === 'success' ? 'check-circle' :
                        type === 'error' ? 'exclamation-circle' :
                        type === 'warning' ? 'exclamation-triangle' : 'info-circle';
            
            toast.innerHTML = `
                <div class="flex items-center space-x-3">
                    <i class="fas fa-${icon} text-xl"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.style.transform = 'translateX(0)';
            }, 10);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }, 3000);
        }
    </script>
    
    @stack('scripts')
</body>
</html>