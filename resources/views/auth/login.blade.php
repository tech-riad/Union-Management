@php
use App\Helpers\UnionHelper;
@endphp

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>লগইন করুন - {{ UnionHelper::getName() }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Dynamic CSS Variables -->
    <style>
        :root {
            --primary: {{ UnionHelper::getPrimaryColor() }};
            --primary-dark: {{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), -20) }};
            --primary-light: {{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), 40) }};
            --secondary: {{ UnionHelper::getSecondaryColor() }};
            --secondary-dark: {{ UnionHelper::adjustColor(UnionHelper::getSecondaryColor(), -20) }};
            --accent: {{ UnionHelper::getAccentColor() }};
            --gradient-primary: linear-gradient(135deg, {{ UnionHelper::getPrimaryColor() }} 0%, {{ UnionHelper::getAccentColor() }} 100%);
            --gradient-secondary: linear-gradient(135deg, {{ UnionHelper::getSecondaryColor() }} 0%, {{ UnionHelper::getPrimaryColor() }} 100%);
            --primary-rgb: {{ UnionHelper::hexToRgb(UnionHelper::getPrimaryColor()) }};
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans Bengali', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #fdf2f8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(var(--primary-rgb), 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(var(--primary-rgb), 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(var(--primary-rgb), 0.05) 0%, transparent 50%);
            z-index: -1;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 8px 32px rgba(var(--primary-rgb), 0.1),
                0 4px 16px rgba(0, 0, 0, 0.05);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(var(--primary-rgb), 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-primary);
            border-radius: 10px;
            border: 2px solid #f8fafc;
        }

        /* Input Focus Effect */
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.2);
            border-color: var(--primary);
        }

        /* Button Glow Effect */
        .btn-glow {
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .btn-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(var(--primary-rgb), 0.3);
        }

        .btn-glow:active {
            transform: translateY(0);
        }

        /* Pulse Animation */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* Logo Styles */
        .logo-container {
            position: relative;
        }

        .logo-badge {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 16px;
            height: 16px;
            background: #10b981;
            border-radius: 50%;
            border: 2px solid white;
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), 40) }}',
                            100: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), 30) }}',
                            200: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), 20) }}',
                            300: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), 10) }}',
                            400: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), 5) }}',
                            500: '{{ UnionHelper::getPrimaryColor() }}',
                            600: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), -10) }}',
                            700: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), -20) }}',
                            800: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), -30) }}',
                            900: '{{ UnionHelper::adjustColor(UnionHelper::getPrimaryColor(), -40) }}',
                        },
                        secondary: {
                            500: '{{ UnionHelper::getSecondaryColor() }}',
                            600: '{{ UnionHelper::adjustColor(UnionHelper::getSecondaryColor(), -10) }}',
                        }
                    },
                    fontFamily: {
                        'bangla': ['Noto Sans Bengali', 'sans-serif']
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse': 'pulse 2s infinite'
                    }
                }
            }
        }
    </script>
</head>
<body>
    <!-- Maintenance Mode Check -->
    @if(UnionHelper::isMaintenanceMode())
    <div class="fixed top-0 left-0 right-0 z-50 bg-yellow-500 text-white text-center py-2 px-4">
        <i class="fas fa-tools mr-2"></i>
        {{ UnionHelper::getMaintenanceMessage() }}
    </div>
    @endif

    <!-- Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-gradient-to-r from-primary-500/10 to-purple-500/10 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-gradient-to-r from-green-500/5 to-blue-500/5 rounded-full blur-3xl float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-3/4 left-3/4 w-48 h-48 bg-gradient-to-r from-purple-500/5 to-pink-500/5 rounded-full blur-3xl float-animation" style="animation-delay: 4s;"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-4xl flex flex-col lg:flex-row rounded-3xl overflow-hidden shadow-2xl glass-effect">
        
        <!-- Left Side - Branding & Info -->
        <div class="lg:w-2/5 bg-gradient-to-br from-primary-600 via-primary-500 to-purple-600 p-8 lg:p-12 text-white relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 h-full flex flex-col">
                <!-- Dynamic Logo -->
                <div class="flex items-center space-x-3 mb-8">
                    <div class="logo-container">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            @if(UnionHelper::getLogoUrl() && UnionHelper::getLogoUrl() != asset('images/default-logo.png'))
                                <img src="{{ UnionHelper::getLogoUrl() }}" alt="{{ UnionHelper::getName() }}" class="w-10 h-10 object-contain">
                            @else
                                <i class="fas fa-landmark text-xl"></i>
                            @endif
                        </div>
                        <div class="logo-badge"></div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ UnionHelper::getName() }}</h1>
                        <p class="text-sm opacity-90">ডিজিটাল গভর্নেন্স প্ল্যাটফর্ম</p>
                    </div>
                </div>

                <!-- Welcome Message -->
                <div class="flex-1 flex flex-col justify-center">
                    <h2 class="text-3xl lg:text-4xl font-bold mb-4">স্বাগতম!</h2>
                    <p class="text-lg opacity-90 mb-8">
                        {{ UnionHelper::getName() }} এর ডিজিটাল সেবায় প্রবেশ করুন। ইউনিয়ন সেবাসমূহ এখন আপনার হাতের মুঠোয়।
                    </p>

                    <!-- Features List -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <span>সুরক্ষিত ও নিরাপদ লগইন</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <span>দ্রুত সেবা প্রাপ্তি</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <span>যেকোনো ডিভাইস থেকে অ্যাক্সেস</span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-white/20">
                    <p class="text-sm opacity-80">
                        <i class="fas fa-info-circle mr-2"></i>
                        নতুন ইউজার? 
                        <a href="{{ route('register') }}" class="font-semibold hover:underline">
                            নিবন্ধন করুন
                        </a>
                    </p>
                    
                    <!-- Contact Info -->
                    <div class="mt-2 text-sm opacity-80">
                        <i class="fas fa-phone-alt mr-2"></i>
                        সহায়তা: {{ UnionHelper::getContactNumber() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="lg:w-3/5 p-8 lg:p-12">
            <!-- Form Header -->
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ UnionHelper::getName() }} এ লগইন</h2>
                <p class="text-gray-600">আপনার তথ্য দিয়ে প্রবেশ করুন</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-green-800">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email/Phone Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-primary-500"></i>
                        ইমেইল অ্যাড্রেস
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            autocomplete="email"
                            placeholder="আপনার ইমেইল অ্যাড্রেস লিখুন"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-transparent input-focus transition-all duration-300"
                        >
                    </div>
                    @error('email')
                        <div class="mt-2 flex items-center text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-lock mr-2 text-primary-500"></i>
                            পাসওয়ার্ড
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-800 transition-colors">
                                <i class="fas fa-key mr-1"></i>
                                পাসওয়ার্ড ভুলে গেছেন?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="আপনার পাসওয়ার্ড লিখুন"
                            class="w-full pl-10 pr-10 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-transparent input-focus transition-all duration-300"
                        >
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" id="togglePassword">
                            <i class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="mt-2 flex items-center text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Terms -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember"
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                        >
                        <label for="remember_me" class="ml-2 text-sm text-gray-700">
                            আমাকে মনে রাখুন
                        </label>
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        <a href="#" class="text-primary-600 hover:text-primary-800">
                            <i class="fas fa-file-alt mr-1"></i>
                            শর্তাবলী
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-primary-600 to-purple-600 text-white font-semibold rounded-xl btn-glow pulse-animation transition-all duration-300 hover:from-primary-700 hover:to-purple-700">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    লগইন করুন
                </button>

                <!-- Social Login -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">অথবা অন্য মাধ্যমে লগইন করুন</span>
                    </div>
                </div>

                <!-- Social Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" 
                            class="flex items-center justify-center space-x-2 py-3 px-4 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors duration-300">
                        <i class="fab fa-google text-red-600"></i>
                        <span class="text-sm font-medium text-gray-700">গুগল</span>
                    </button>
                    <button type="button" 
                            class="flex items-center justify-center space-x-2 py-3 px-4 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors duration-300">
                        <i class="fab fa-facebook text-blue-600"></i>
                        <span class="text-sm font-medium text-gray-700">ফেসবুক</span>
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center pt-6 border-t border-gray-200">
                    <p class="text-gray-600">
                        নতুন ইউজার? 
                        <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-800 transition-colors">
                            <i class="fas fa-user-plus mr-1"></i>
                            {{ UnionHelper::getName() }} এ রেজিস্টার করুন
                        </a>
                    </p>
                </div>
            </form>

            <!-- Dynamic Support Info -->
            <div class="mt-8 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-headset text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">সাহায্য প্রয়োজন?</p>
                        <p class="text-sm text-gray-600">
                            কল করুন: <a href="tel:{{ UnionHelper::getContactNumber() }}" class="text-primary-600 hover:text-primary-800 font-semibold">
                                {{ UnionHelper::getContactNumber() }}
                            </a>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            ইমেইল: <a href="mailto:{{ UnionHelper::getEmail() }}" class="hover:text-primary-600">
                                {{ UnionHelper::getEmail() }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Back Button -->
    <a href="{{ url('/') }}" 
       class="fixed top-6 left-6 w-12 h-12 rounded-full bg-white shadow-lg flex items-center justify-center text-gray-700 hover:text-primary-600 hover:shadow-xl transition-all duration-300 group z-50">
        <i class="fas fa-arrow-left"></i>
        <span class="absolute left-full ml-3 px-3 py-1 bg-gray-900 text-white text-sm rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
            হোমপেজে ফিরে যান
        </span>
    </a>

    <!-- Theme Color Meta Tag -->
    <meta name="theme-color" content="{{ UnionHelper::getPrimaryColor() }}">

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Password Visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' 
                    ? '<i class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>'
                    : '<i class="fas fa-eye-slash text-gray-400 hover:text-gray-600 cursor-pointer"></i>';
            });

            // Form submission animation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> লগইন করা হচ্ছে...';
                submitBtn.disabled = true;
            });

            // Add floating animation to background elements
            const bgElements = document.querySelectorAll('.float-animation');
            bgElements.forEach((el, index) => {
                el.style.animationDelay = `${index * 2}s`;
            });

            // Input focus effects
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-primary-500', 'ring-opacity-50');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-primary-500', 'ring-opacity-50');
                });
            });

            // Error message auto-hide
            setTimeout(() => {
                const errorMessages = document.querySelectorAll('.text-red-600');
                errorMessages.forEach(msg => {
                    msg.style.transition = 'opacity 0.5s';
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                });
            }, 5000);

            // Update theme color dynamically
            function updateThemeColor() {
                const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();
                const metaThemeColor = document.querySelector('meta[name="theme-color"]');
                
                if (metaThemeColor) {
                    metaThemeColor.content = primaryColor;
                }
            }

            // Dynamic gradient animation
            function animateGradient() {
                const primary = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();
                const accent = getComputedStyle(document.documentElement).getPropertyValue('--accent').trim();
                
                const gradientBtn = document.querySelector('.btn-glow.pulse-animation');
                if (gradientBtn) {
                    const hue1 = Math.floor(Math.random() * 360);
                    const hue2 = (hue1 + 60) % 360;
                    
                    gradientBtn.style.background = `linear-gradient(135deg, hsl(${hue1}, 100%, 65%) 0%, hsl(${hue2}, 100%, 65%) 100%)`;
                    
                    setTimeout(animateGradient, 2000);
                }
            }

            // Initialize dynamic features
            updateThemeColor();
            // animateGradient(); // Uncomment for animated gradient effect

            // Add click animation to social buttons
            const socialButtons = document.querySelectorAll('button[type="button"]');
            socialButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            // Add ripple effect to login button
            const loginBtn = document.querySelector('button[type="submit"]');
            if (loginBtn) {
                loginBtn.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.7);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        width: ${size}px;
                        height: ${size}px;
                        top: ${y}px;
                        left: ${x}px;
                    `;
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            }

            // Add ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });

        // Add custom CSS for responsive design
        const responsiveStyle = document.createElement('style');
        responsiveStyle.textContent = `
            @media (max-width: 768px) {
                .glass-effect {
                    border-radius: 1.5rem;
                }
                
                .float-animation {
                    animation-duration: 8s;
                }
                
                .pulse-animation {
                    animation-duration: 3s;
                }
                
                body::before {
                    opacity: 0.5;
                }
            }
            
            @media (max-width: 640px) {
                body {
                    padding: 10px;
                }
                
                .fixed.top-6.left-6 {
                    top: 1rem;
                    left: 1rem;
                    width: 3rem;
                    height: 3rem;
                }
                
                .glass-effect {
                    margin-top: 3rem;
                }
            }
            
            /* Smooth transitions */
            * {
                transition: background-color 0.3s, border-color 0.3s, transform 0.3s, box-shadow 0.3s;
            }
            
            /* Better focus outlines */
            :focus-visible {
                outline: 2px solid var(--primary);
                outline-offset: 2px;
            }
            
            /* Logo hover effect */
            .logo-container:hover {
                transform: rotate(-5deg);
                transition: transform 0.3s ease;
            }
            
            /* Input validation styles */
            input:valid {
                border-color: #10b981;
            }
            
            input:invalid:not(:placeholder-shown) {
                border-color: #ef4444;
            }
            
            /* Dark mode support */
            @media (prefers-color-scheme: dark) {
                body {
                    background: linear-gradient(135deg, #1e293b 0%, #111827 100%);
                }
                
                .glass-effect {
                    background: rgba(30, 41, 59, 0.9);
                    border-color: rgba(255, 255, 255, 0.1);
                }
                
                .text-gray-900 {
                    color: #f1f5f9;
                }
                
                .text-gray-600 {
                    color: #cbd5e1;
                }
                
                .text-gray-700 {
                    color: #e2e8f0;
                }
                
                .border-gray-300 {
                    border-color: #475569;
                }
                
                .bg-white {
                    background-color: #1e293b;
                }
                
                .bg-gradient-to-r.from-blue-50.to-indigo-50 {
                    background: linear-gradient(to right, rgba(30, 41, 59, 0.5), rgba(30, 41, 59, 0.7));
                    border-color: #475569;
                }
            }
        `;
        document.head.appendChild(responsiveStyle);
    </script>
</body>
</html>