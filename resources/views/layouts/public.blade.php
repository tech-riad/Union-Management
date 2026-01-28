@php
use App\Helpers\UnionHelper;
@endphp
<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    
    <!-- Dynamic Title -->
    <title>@yield('title', UnionHelper::getName())</title>
    
    <!-- Dynamic Favicon -->
    <link rel="icon" href="{{ UnionHelper::getFaviconUrl() }}" type="image/png">
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dynamic Styles -->
    {!! UnionHelper::generateDynamicStyles() !!}
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Noto Sans Bengali', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-400);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-600);
        }
        
        /* Custom Classes */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(var(--primary-rgb), 0.1);
        }
        
        .gradient-text {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .gradient-primary {
            background: var(--gradient-primary);
        }
        
        .gradient-success {
            background: linear-gradient(135deg, #10b981 0%, var(--primary) 100%);
        }
        
        .gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, var(--accent) 100%);
        }
        
        .gradient-danger {
            background: linear-gradient(135deg, #ef4444 0%, var(--primary) 100%);
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        /* Dynamic Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(var(--primary-rgb), 0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, var(--primary) 100%);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }
        
        /* Card Styles */
        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }
        
        .card-primary {
            border-top: 4px solid var(--primary);
        }
        
        /* Form Styles */
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            font-family: 'Noto Sans Bengali', sans-serif;
            font-size: 1rem;
            transition: all 0.3s;
            background: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
        }
        
        /* Alert Styles */
        .alert {
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: linear-gradient(to right, #d1fae5, #ecfdf5);
            border-left-color: #10b981;
            color: #065f46;
        }
        
        .alert-error {
            background: linear-gradient(to right, #fee2e2, #fef2f2);
            border-left-color: #ef4444;
            color: #991b1b;
        }
        
        .alert-warning {
            background: linear-gradient(to right, #fef3c7, #fffbeb);
            border-left-color: #f59e0b;
            color: #92400e;
        }
        
        /* Nav Link Styles */
        .nav-link {
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
            font-weight: 500;
            color: #4b5563;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-link:hover {
            background: rgba(var(--primary-rgb), 0.1);
            color: var(--primary);
        }
        
        .nav-link.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.3);
        }
        
        /* Badge Styles */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-primary {
            background: rgba(var(--primary-rgb), 0.1);
            color: var(--primary-700);
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-info {
            background: rgba(var(--primary-rgb), 0.1);
            color: var(--primary-700);
        }
        
        /* Mobile Menu */
        @media (max-width: 768px) {
            .mobile-menu-open {
                transform: translateX(0) !important;
            }
            
            .mobile-menu-backdrop {
                opacity: 1 !important;
                visibility: visible !important;
            }
            
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .text-4xl {
                font-size: 1.875rem !important;
            }
            
            .text-3xl {
                font-size: 1.5rem !important;
            }
            
            .text-2xl {
                font-size: 1.25rem !important;
            }
        }
    </style>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col">
    <!-- Maintenance Mode Check -->
    @if(UnionHelper::isMaintenanceMode())
    <div class="bg-yellow-500 text-white text-center py-2 px-4">
        <i class="fas fa-tools mr-2"></i>
        {{ UnionHelper::getMaintenanceMessage() }}
    </div>
    @endif

    <!-- Navigation -->
    <nav class="glass-effect fixed top-0 left-0 right-0 z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Dynamic Logo -->
                <a href="{{ route('welcome') }}" class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center shadow-lg">
                            @if(UnionHelper::getLogoUrl() && UnionHelper::getLogoUrl() != asset('images/default-logo.png'))
                                <img src="{{ UnionHelper::getLogoUrl() }}" alt="{{ UnionHelper::getName() }}" class="w-8 h-8">
                            @else
                                <i class="fas fa-landmark text-white text-lg"></i>
                            @endif
                            <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">{{ UnionHelper::getName() }}</h1>
                        <p class="text-xs text-gray-500">নাগরিক পোর্টাল</p>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    <div class="flex items-center space-x-1 bg-gray-100/50 rounded-xl p-1">
                        <a href="{{ route('welcome') }}" 
                           class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                            <i class="fas fa-home text-sm"></i>
                            <span class="hidden lg:inline">হোম</span>
                        </a>
                        
                        <a href="{{ route('certificate.verify.form') }}" 
                           class="nav-link {{ request()->is('verify') || request()->is('certificate/verify') ? 'active' : '' }}">
                            <i class="fas fa-search text-sm"></i>
                            <span class="hidden lg:inline">যাচাই করুন</span>
                        </a>
                        
                        <a href="{{ route('login') }}" 
                           class="nav-link {{ request()->is('login') ? 'active' : '' }}">
                            <i class="fas fa-sign-in-alt text-sm"></i>
                            <span class="hidden lg:inline">লগইন</span>
                        </a>
                        
                        <a href="{{ route('register') }}" 
                           class="nav-link {{ request()->is('register') ? 'active' : '' }}">
                            <i class="fas fa-user-plus text-sm"></i>
                            <span class="hidden lg:inline">রেজিস্টার</span>
                        </a>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuButton" class="md:hidden w-10 h-10 rounded-xl gradient-primary text-white flex items-center justify-center">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Navigation Menu -->
    <div id="mobileMenu" class="fixed inset-0 z-50 hidden md:hidden">
        <div class="absolute inset-0 bg-black/50 mobile-menu-backdrop" 
             id="mobileMenuBackdrop" 
             style="opacity: 0; visibility: hidden; transition: all 0.3s;"></div>
        
        <div class="absolute right-0 top-0 bottom-0 w-64 bg-white shadow-2xl transform transition-transform duration-300 translate-x-full">
            <div class="p-4 h-full flex flex-col">
                <!-- Mobile Menu Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center">
                            @if(UnionHelper::getLogoUrl() && UnionHelper::getLogoUrl() != asset('images/default-logo.png'))
                                <img src="{{ UnionHelper::getLogoUrl() }}" alt="{{ UnionHelper::getName() }}" class="w-8 h-8">
                            @else
                                <i class="fas fa-landmark text-white text-lg"></i>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">মেনু</h2>
                            <p class="text-sm text-gray-500">নেভিগেশন</p>
                        </div>
                    </div>
                    <button id="closeMobileMenu" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>
                
                <!-- Mobile Menu Items -->
                <div class="flex-1 space-y-1 overflow-y-auto">
                    <a href="{{ route('welcome') }}" 
                       class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>হোম</span>
                    </a>
                    
                    <a href="{{ route('certificate.verify.form') }}" 
                       class="nav-link {{ request()->is('verify') || request()->is('certificate/verify') ? 'active' : '' }}">
                        <i class="fas fa-search"></i>
                        <span>সার্টিফিকেট যাচাই</span>
                    </a>
                    
                    <a href="{{ route('login') }}" 
                       class="nav-link {{ request()->is('login') ? 'active' : '' }}">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>লগইন</span>
                    </a>
                    
                    <a href="{{ route('register') }}" 
                       class="nav-link {{ request()->is('register') ? 'active' : '' }}">
                        <i class="fas fa-user-plus"></i>
                        <span>নতুন অ্যাকাউন্ট</span>
                    </a>
                </div>
                
                <!-- Dynamic Contact Info in Mobile Menu -->
                <div class="pt-4 border-t">
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone text-blue-500 mr-2"></i>
                            <span>সাহায্য: {{ UnionHelper::getContactNumber() }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-envelope text-blue-500 mr-2"></i>
                            <span>{{ UnionHelper::getEmail() }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock text-blue-500 mr-2"></i>
                            <span>{{ UnionHelper::getOfficeHours() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow pt-16">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4">
            <!-- Messages -->
            @if(session('success'))
            <div class="alert alert-success fade-in mb-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error fade-in mb-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-error fade-in mb-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle mt-1 mr-2"></i>
                    <div>
                        <p class="font-medium mb-1">নিম্নলিখিত ত্রুটি ঠিক করুন:</p>
                        <ul class="text-sm space-y-0.5">
                            @foreach($errors->all() as $error)
                            <li class="flex items-start">
                                <i class="fas fa-circle text-xs mt-1 mr-2"></i>
                                {{ $error }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- Dynamic Footer -->
    <footer class="bg-gray-900 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand Info -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center">
                            @if(UnionHelper::getLogoUrl() && UnionHelper::getLogoUrl() != asset('images/default-logo.png'))
                                <img src="{{ UnionHelper::getLogoUrl() }}" alt="{{ UnionHelper::getName() }}" class="w-8 h-8">
                            @else
                                <i class="fas fa-landmark text-white"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">{{ UnionHelper::getName() }}</h3>
                            <p class="text-sm text-gray-400">ডিজিটাল গভর্নেন্স</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">
                        উদ্ভাবনী ডিজিটাল সেবা এবং স্বচ্ছ গভর্নেন্স সমাধানের মাধ্যমে নাগরিকদের ক্ষমতায়ন।
                    </p>
                    
                    <!-- Social Links -->
                    @php $socialLinks = UnionHelper::getSocialLinks(); @endphp
                    <div class="flex space-x-3 mt-4">
                        @if($socialLinks['facebook'] != '#')
                        <a href="{{ $socialLinks['facebook'] }}" target="_blank" 
                           class="w-8 h-8 rounded-full bg-gray-800 hover:bg-primary-500 transition flex items-center justify-center">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        @endif
                        
                        @if($socialLinks['website'] != '#')
                        <a href="{{ $socialLinks['website'] }}" target="_blank"
                           class="w-8 h-8 rounded-full bg-gray-800 hover:bg-primary-500 transition flex items-center justify-center">
                            <i class="fas fa-globe text-sm"></i>
                        </a>
                        @endif
                        
                        @if($socialLinks['youtube'] != '#')
                        <a href="{{ $socialLinks['youtube'] }}" target="_blank"
                           class="w-8 h-8 rounded-full bg-gray-800 hover:bg-primary-500 transition flex items-center justify-center">
                            <i class="fab fa-youtube text-sm"></i>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold text-lg mb-4">দ্রুত লিংক</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('welcome') }}" class="text-gray-400 hover:text-white transition">হোম পেজ</a></li>
                        <li><a href="{{ route('certificate.verify.form') }}" class="text-gray-400 hover:text-white transition">সার্টিফিকেট যাচাই</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition">লগইন করুন</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition">রেজিস্টার করুন</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="font-bold text-lg mb-4">আমাদের সেবা</h4>
                    <ul class="space-y-2">
                        <li class="text-gray-400">অনলাইন সার্টিফিকেট</li>
                        <li class="text-gray-400">সার্টিফিকেট যাচাই</li>
                        <li class="text-gray-400">অনলাইন আবেদন</li>
                        <li class="text-gray-400">ডিজিটাল পেমেন্ট</li>
                    </ul>
                </div>

                <!-- Dynamic Contact -->
                <div>
                    <h4 class="font-bold text-lg mb-4">যোগাযোগ করুন</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-blue-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-400">সাহায্যের জন্য কল করুন</p>
                                <p class="font-medium">{{ UnionHelper::getContactNumber() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-400">ইমেইল</p>
                                <p class="font-medium">{{ UnionHelper::getEmail() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-blue-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-400">অফিস সময়</p>
                                <p class="font-medium">{{ UnionHelper::getOfficeHours() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar-day text-blue-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-400">কাজের দিন</p>
                                <p class="font-medium">{{ UnionHelper::getWorkingDays() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 mt-8 pt-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} {{ UnionHelper::getName() }}। সকল অধিকার সংরক্ষিত।
                    </p>
                    <div class="flex space-x-4 mt-4 md:mt-0">
                        <p class="text-gray-400 text-sm">
                            কারিগরী সহায়তায় SINHA IT HOST
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('{{ UnionHelper::getName() }} Loaded');
            
            // Mobile Menu
            initMobileMenu();
            
            // Auto-hide messages
            initMessages();
            
            // Print button
            initPrintButton();
            
            // Dynamic theme color
            updateThemeColor();
        });

        function initMobileMenu() {
            const menuButton = document.getElementById('mobileMenuButton');
            const closeButton = document.getElementById('closeMobileMenu');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuBackdrop = document.getElementById('mobileMenuBackdrop');
            
            if (!menuButton || !mobileMenu) return;
            
            function openMobileMenu() {
                mobileMenu.classList.remove('hidden');
                setTimeout(() => {
                    mobileMenuBackdrop.style.opacity = '1';
                    mobileMenuBackdrop.style.visibility = 'visible';
                    mobileMenu.querySelector('.absolute.right-0').classList.remove('translate-x-full');
                }, 10);
            }
            
            function closeMobileMenu() {
                mobileMenu.querySelector('.absolute.right-0').classList.add('translate-x-full');
                mobileMenuBackdrop.style.opacity = '0';
                mobileMenuBackdrop.style.visibility = 'hidden';
                
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
            }
            
            menuButton.addEventListener('click', openMobileMenu);
            closeButton.addEventListener('click', closeMobileMenu);
            mobileMenuBackdrop.addEventListener('click', closeMobileMenu);
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                    closeMobileMenu();
                }
            });
            
            document.querySelectorAll('.mobile-nav-item').forEach(item => {
                item.addEventListener('click', function() {
                    setTimeout(closeMobileMenu, 300);
                });
            });
        }

        function initMessages() {
            const messages = document.querySelectorAll('.alert');
            
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

        function initPrintButton() {
            // Add print button if not exists
            if (window.location.pathname.includes('verify')) {
                const printBtn = document.createElement('button');
                printBtn.innerHTML = '<i class="fas fa-print mr-2"></i> প্রিন্ট';
                printBtn.className = 'fixed bottom-6 right-6 z-50 gradient-primary text-white p-3 rounded-full shadow-lg hover:shadow-xl transition-all';
                printBtn.style.zIndex = '9999';
                printBtn.onclick = function() { window.print(); };
                document.body.appendChild(printBtn);
            }
        }

        // Update theme color meta tag
        function updateThemeColor() {
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();
            const metaThemeColor = document.querySelector('meta[name="theme-color"]');
            
            if (!metaThemeColor) {
                const meta = document.createElement('meta');
                meta.name = 'theme-color';
                meta.content = primaryColor;
                document.head.appendChild(meta);
            } else {
                metaThemeColor.content = primaryColor;
            }
        }

        // Toast Notification
        function showToast(message, type = 'info') {
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();
            
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 fade-in`;
            toast.style.background = type === 'success' ? '#10b981' : 
                                   type === 'error' ? '#ef4444' : 
                                   primaryColor;
            toast.style.color = 'white';
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.transition = 'opacity 0.3s, transform 0.3s';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Dynamic color manipulation for charts etc.
        window.UnionTheme = {
            getPrimaryColor: function() {
                return getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();
            },
            getPrimaryColorRgb: function() {
                return getComputedStyle(document.documentElement).getPropertyValue('--primary-rgb').trim();
            },
            adjustColor: function(hex, percent) {
                // This function would be used in charts or dynamic UI elements
                // Implementation similar to PHP version but in JavaScript
                return hex; // Simplified return
            }
        };
    </script>
    
    @stack('scripts')
</body>
</html>