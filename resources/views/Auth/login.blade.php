<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Healthy Food Network</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #FF6B35;
            --secondary: #4CAF50;
            --accent: #FFD166;
            --dark: #2A4365;
            --light: #FFF9F0;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background:
                linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.8)),
                url('https://images.unsplash.com/photo-1490818387583-1baba5e638af?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        /* Animated Gradient Background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(255, 107, 53, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(76, 175, 80, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 209, 102, 0.1) 0%, transparent 50%);
            z-index: -1;
        }

        /* Floating Animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        @keyframes gradient-shift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-pulse-slow {
            animation: pulse 2s ease-in-out infinite;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #FF6B35, #FFD166, #4CAF50);
            border-radius: 24px 24px 0 0;
        }

        .card::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(255, 107, 53, 0.1) 0%, transparent 70%);
            z-index: 0;
            pointer-events: none;
        }

        .card>* {
            position: relative;
            z-index: 1;
        }

        .form-input {
            border: 2px solid rgba(229, 231, 235, 0.8);
            border-radius: 14px;
            padding: 16px 20px;
            width: 100%;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            color: #374151;
        }

        .form-input:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            outline: none;
            background: white;
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: #9CA3AF;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon input {
            padding-left: 56px;
        }

        .form-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #FF6B35;
            z-index: 10;
            font-size: 1.2rem;
        }

        .toggle-password {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
            cursor: pointer;
            z-index: 10;
            background: rgba(255, 255, 255, 0.8);
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .toggle-password:hover {
            background: rgba(255, 107, 53, 0.1);
            color: #FF6B35;
        }

        .btn-login {
            background: linear-gradient(135deg, #FF6B35, #4CAF50);
            color: white;
            padding: 18px;
            border-radius: 14px;
            font-weight: 600;
            width: 100%;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255, 107, 53, 0.4);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 14px;
        }

        .logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid white;
            box-shadow: 0 10px 30px rgba(255, 251, 249, 0.445);
        }

        .logo img {
            object-fit: contain;
            border-radius: 50%;
        }

        .brand-name {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #FF6B35, #4CAF50);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .food-badge {
            display: inline-block;
            background: linear-gradient(135deg, #FF6B35, #FFD166);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
            margin-bottom: 14px;
        }

        .success-badge {
            background: linear-gradient(135deg, #10B981, #34D399);
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.2);
        }

        .error-badge {
            background: linear-gradient(135deg, #EF4444, #F87171);
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.2);
        }

        .form-label {
            color: #374151;
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            font-size: 1rem;
        }

        /* Checkbox Styling */
        .form-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid #D1D5DB;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-checkbox:checked {
            background-color: #FF6B35;
            border-color: #FF6B35;
        }

        .form-checkbox:focus {
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }

        /* Floating Elements */
        .floating-element {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            filter: blur(40px);
        }

        .floating-1 {
            width: 200px;
            height: 200px;
            background: #FF6B35;
            top: 10%;
            left: 10%;
            animation: float 8s ease-in-out infinite;
        }

        .floating-2 {
            width: 150px;
            height: 150px;
            background: #4CAF50;
            bottom: 15%;
            right: 10%;
            animation: float 10s ease-in-out infinite reverse;
        }

        .floating-3 {
            width: 100px;
            height: 100px;
            background: #FFD166;
            top: 40%;
            right: 20%;
            animation: float 12s ease-in-out infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card {
                padding: 30px 25px;
            }

            .logo-container {
                gap: 12px;
            }

            .logo {
                width: 60px;
                height: 60px;
            }

            .brand-name {
                font-size: 2rem;
            }

            .form-input {
                padding: 14px 18px;
            }

            .btn-login {
                padding: 16px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .card {
                padding: 25px 20px;
            }

            .brand-name {
                font-size: 1.8rem;
            }

            .food-badge {
                padding: 8px 20px;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Floating Background Elements -->
    <div class="floating-element floating-1"></div>
    <div class="floating-element floating-2"></div>
    <div class="floating-element floating-3"></div>

    <div class="container max-w-xl mx-auto animate-float">
        <div class="card px-6 sm:px-8 py-5 sm:py-6">
            <div class="text-center mb-10 relative">

                <a href="/"
                    class="absolute -top-2 -right-4 w-8 h-8 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-slate-200 hover:text-slate-800 transition-all border border-slate-100 shadow-sm z-10"
                    title="Back to Home">
                    <i class="bi bi-x-lg font-bold"></i>
                </a>

                <div class="logo-container">
                    <div class="logo">
                        <a href="/">
                            <img src="{{ asset('images/smartsave.png') }}" alt="">
                        </a>
                    </div>
                    <div class="brand-name font-poppins">
                        Smart<span
                            style="background: linear-gradient(135deg, #FFD166, #4CAF50); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Save24</span>
                    </div>
                </div>

                <div class="mb-2">
                    <span class="food-badge">
                        <i class="bi bi-heart-fill mr-2"></i>
                        India's no 1 Direct selling network
                    </span>
                </div>

                <h2 class="text-3xl font-bold text-gray-800 mb-2 font-poppins">
                    Welcome Back
                </h2>
                <p class="text-gray-600 text-lg mb-2">
                    Nice to see you again. Sign in to continue to your account
                </p>
            </div>

            <!-- Messages -->
            @if (session('success'))
                <div class="mb-8 success-badge">
                    <i class="bi bi-check-circle-fill text-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-8 error-badge">
                    <i class="bi bi-x-circle-fill text-xl"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('auth.login') }}" method="POST">
                @csrf

                <div class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label class="form-label">Email Address</label>
                        <div class="input-with-icon">
                            <i class="form-icon bi bi-envelope-fill"></i>
                            <input type="text" name="email" class="form-input" placeholder="your@email.com"
                                value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1 ml-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="form-label">Password</label>
                        <div class="input-with-icon">
                            <i class="form-icon bi bi-lock-fill"></i>
                            <input type="password" name="password" id="password" class="form-input"
                                placeholder="Enter your password" required>
                            <span class="toggle-password" data-target="#password">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1 ml-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="rememberMe" name="remember" class="form-checkbox">
                            <label for="rememberMe" class="text-gray-700 cursor-pointer select-none">
                                Remember me
                            </label>
                        </div>
                        <a href="{{ route('password.request') }}"
                            class="text-orange-500 hover:text-orange-600 font-semibold text-sm hover:underline transition-colors">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" onclick="return confirm('Are you sure you want to login to SmartSave24?')"
                        class="btn-login">
                        <i class="bi bi-box-arrow-in-right mr-3"></i>
                        Sign In to Your Account
                    </button>
                </div>
            </form>

            <!-- Registration Link -->
            <div class="text-center mt-6 pt-4 border-t border-gray-200">
                <p class="text-gray-600 text-lg">
                    Don't have an account?
                    <a href="{{ route('auth.register') }}"
                        class="text-orange-500 font-semibold hover:text-orange-600 hover:underline transition-colors">
                        Create Account
                    </a>
                </p>
            </div>
        </div>

        <!-- Support Information -->
        <div class="text-center mt-6 pt-4 border-t border-gray-200/50">
            <p class="text-gray-300 mb-2 text-lg">Need help with login?</p>
            <div class="flex items-center justify-center space-x-6 text-sm">
                <a href="tel:+919876543210"
                    class="text-gray-200 hover:text-orange-300 transition-colors flex items-center">
                    <i class="bi bi-telephone-fill mr-2 text-lg"></i>
                    <span class="text-lg">+91 98765 xxxxx</span>
                </a>
                <a href="mailto:support@foodvendor.com"
                    class="text-gray-200 hover:text-orange-300 transition-colors flex items-center">
                    <i class="bi bi-envelope-fill mr-2 text-lg"></i>
                    <span class="text-lg">support@foodvendor.com</span>
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('.toggle-password').click(function() {
                const target = $(this).data('target');
                const input = $(target);
                const icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });

            // Auto-focus email field if there's an error
            @if ($errors->has('email') || $errors->has('password'))
                setTimeout(() => {
                    $('input[name="email"]').focus();
                }, 100);
            @endif
        });
    </script>
</body>

</html>
