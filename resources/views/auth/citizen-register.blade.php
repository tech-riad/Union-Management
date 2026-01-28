<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>নাগরিক রেজিস্ট্রেশন</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Bangla -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --success-color: #4ade80;
            --danger-color: #f43f5e;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
        }
        
        body {
            font-family: 'Noto Sans Bengali', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--dark-color);
        }
        
        .register-container {
            width: 100%;
            max-width: 500px;
            animation: fadeIn 0.8s ease-out;
        }
        
        .register-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            border: none;
        }
        
        .register-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px 30px;
            text-align: center;
            position: relative;
        }
        
        .register-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--accent-color), #7209b7);
        }
        
        .register-header h3 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.8rem;
        }
        
        .register-header p {
            opacity: 0.9;
            font-size: 0.95rem;
            margin-bottom: 0;
        }
        
        .register-body {
            padding: 30px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 8px;
            color: var(--primary-color);
        }
        
        .form-control {
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Noto Sans Bengali', sans-serif;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .form-control::placeholder {
            color: #94a3b8;
            font-size: 0.9rem;
        }
        
        .input-group-text {
            background-color: #f1f5f9;
            border: 2px solid #e2e8f0;
            border-right: none;
        }
        
        .form-control.is-invalid {
            border-color: var(--danger-color);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: rgba(74, 222, 128, 0.15);
            color: #166534;
            border-left: 4px solid var(--success-color);
        }
        
        .alert-danger {
            background-color: rgba(244, 63, 94, 0.1);
            color: #9f1239;
            border-left: 4px solid var(--danger-color);
        }
        
        .btn-register {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 14px;
            font-weight: 600;
            font-size: 1.05rem;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
            font-family: 'Noto Sans Bengali', sans-serif;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn-register:active {
            transform: translateY(0);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
        }
        
        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        
        .login-link a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
        }
        
        .password-toggle:hover {
            color: var(--dark-color);
        }
        
        .password-field {
            position: relative;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 576px) {
            .register-container {
                padding: 10px;
            }
            
            .register-header, .register-body {
                padding: 20px;
            }
        }
        
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card animate__animated animate__fadeInUp">
            <div class="register-header">
                <div class="logo-container">
                    <div class="logo">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <h3>নাগরিক রেজিস্ট্রেশন</h3>
                <p>নতুন অ্যাকাউন্ট তৈরি করুন</p>
            </div>
            
            <div class="register-body">
                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success animate__animated animate__fadeIn">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Error Message --}}
                @if ($errors->any())
                    <div class="alert alert-danger animate__animated animate__shakeX">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>ত্রুটি!</strong> নিম্নলিখিত সমস্যাগুলো ঠিক করুন:
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.submit') }}" id="registerForm">
                    @csrf
                    
                    <!-- নাম -->
                    <div class="mb-4">
                        <label class="form-label" for="name">
                            <i class="fas fa-user"></i> নাম
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="আপনার পুরো নাম লিখুন" 
                                   required
                                   autofocus>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- মোবাইল নম্বর -->
                    <div class="mb-4">
                        <label class="form-label" for="mobile">
                            <i class="fas fa-mobile-alt"></i> মোবাইল নম্বর
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="text" 
                                   name="mobile" 
                                   id="mobile" 
                                   class="form-control @error('mobile') is-invalid @enderror" 
                                   value="{{ old('mobile') }}" 
                                   placeholder="01XXXXXXXXX" 
                                   required>
                        </div>
                        @error('mobile')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- ইমেইল -->
                    <div class="mb-4">
                        <label class="form-label" for="email">
                            <i class="fas fa-envelope"></i> ইমেইল (ঐচ্ছিক)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-at"></i>
                            </span>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" 
                                   placeholder="example@email.com">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- পাসওয়ার্ড -->
                    <div class="mb-4">
                        <label class="form-label" for="password">
                            <i class="fas fa-lock"></i> পাসওয়ার্ড
                        </label>
                        <div class="password-field">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="অন্তত ৮ অক্ষরের পাসওয়ার্ড দিন" 
                                       required>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- পাসওয়ার্ড নিশ্চিত করুন -->
                    <div class="mb-4">
                        <label class="form-label" for="password_confirmation">
                            <i class="fas fa-lock"></i> পাসওয়ার্ড নিশ্চিত করুন
                        </label>
                        <div class="password-field">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="form-control" 
                                       placeholder="পুনরায় পাসওয়ার্ড লিখুন" 
                                       required>
                                <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-plus me-2"></i> রেজিস্ট্রেশন করুন
                    </button>
                </form>

                <div class="login-link">
                    <p>আগেই একাউন্ট আছে? <a href="{{ route('login') }}">লগইন করুন</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>';
            });
            
            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>';
            });
            
            // Form validation and submission feedback
            const form = document.getElementById('registerForm');
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('.btn-register');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> প্রক্রিয়াধীন...';
                submitBtn.disabled = true;
            });
            
            // Mobile number formatting
            const mobileInput = document.getElementById('mobile');
            mobileInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0 && !value.startsWith('01')) {
                    value = '01' + value.substring(0, 9);
                }
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                e.target.value = value;
            });
        });
    </script>
</body>
</html>