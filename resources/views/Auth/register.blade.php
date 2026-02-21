<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join - Smart Save24</title>

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

        .gradient-animate {
            background: linear-gradient(-45deg, #FF6B35, #FFD166, #4CAF50, #2A4365);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
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
            padding: 12px 16px;
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

        .btn-register {
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

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255, 107, 53, 0.4);
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:active {
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
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
        }

        .logo img {
            object-fit: contain;
            border-radius: 50%;
        }

        .brand-name {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #5E17EB, #FF6B35);
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

        .info-box {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.1), rgba(76, 175, 80, 0.1));
            border-left: 4px solid #FF6B35;
            padding: 15px;
            border-radius: 12px;
            margin-top: 12px;
            backdrop-filter: blur(5px);
        }

        .form-label {
            color: #374151;
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            font-size: 1rem;
        }

        .form-label span {
            font-weight: 400;
            font-size: 0.9rem;
        }

        .password-requirements ul {
            list-style: none;
            padding-left: 0;
        }

        .password-requirements li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: #6B7280;
        }

        .password-requirements li::before {
            content: '•';
            color: #FF6B35;
            font-size: 1.2rem;
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
                padding: 10px 14px;
            }

            .btn-register {
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

    <div class="container max-w-2xl mx-auto animate-float">
        <div class="card px-6 sm:px-8 py-5 sm:py-6">
            <!-- Header -->
            <div class="text-center mb-6 relative">
                <a href="/"
                    class="absolute -top-2 -right-4 w-8 h-8 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-slate-200 hover:text-slate-800 transition-all border border-slate-100 shadow-sm z-10"
                    title="Back to Home">
                    <i class="bi bi-x-lg font-bold"></i>
                </a>

                <div class="logo-container">
                    <div class="logo">
                        <img src="{{ asset('images/smartsave.png') }}" alt="">
                    </div>
                    <div class="brand-name font-poppins">
                        Smart<span
                            style="background: linear-gradient(135deg, #ffba19,#7232f1d9); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Save24</span>
                    </div>
                </div>

                <div class="mb-2">
                    <span class="food-badge">
                        <i class="bi bi-rocket-takeoff-fill mr-2"></i>
                        Join Our Network
                    </span>
                </div>
                {{-- 
    <h2 class="text-3xl font-bold text-gray-800 mb-2 font-poppins">
        Start Your Food Journey
    </h2> --}}

            </div>

            <!-- Messages -->
            @if (session('success'))
                <div class="mb-6 success-badge">
                    <i class="bi bi-check-circle-fill text-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 error-badge">
                    <i class="bi bi-x-circle-fill text-xl"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" id="registrationForm">
                @csrf

                <div class="space-y-5">
                    <!-- Full Name -->
                    <div>
                        <label class="form-label">Full Name</label>
                        <div class="input-with-icon">
                            <i class="form-icon bi bi-person-fill"></i>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-input"
                                placeholder="Enter your full name" required>
                        </div>
                        @error('full_name')
                            <p class="text-red-500 text-sm mt-1 ml-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sponsor ID -->
                    <div>
                        <label class="form-label">Sponsor ID <span
                                class="text-gray-500 font-normal">(Required)</span></label>
                        <div class="input-with-icon">
                            <i class="form-icon bi bi-person-plus-fill"></i>
                            <input type="text" name="sponsor_id"
                                value="{{ old('sponsor_id', request('sponsor_id')) }}" id="sponsor_id"
                                class="form-input" placeholder="Enter sponsor ID" required>
                        </div>
                        <div id="sponsor-message" class="text-sm mt-1 ml-2"></div>
                        @error('sponsor_id')
                            <p class="text-red-500 text-sm mt-1 ml-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="form-label">Email Address</label>
                        <div class="input-with-icon">
                            <i class="form-icon bi bi-envelope-fill"></i>
                            <input type="email" name="email" value="{{ old('email') }}" id="email"
                                class="form-input" placeholder="your@email.com" required>
                        </div>
                        <div id="email-message" class="text-sm mt-1 ml-2"></div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1 ml-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="form-label">Phone Number</label>
                        <div class="input-with-icon">
                            <i class="form-icon bi bi-telephone-fill"></i>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="form-input"
                                placeholder="+91 98765 43210" required>
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1 ml-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="form-label">Password</label>
                        <div class="input-with-icon">
                            <i class="form-icon bi bi-lock-fill"></i>
                            <input type="password" name="password" id="password" class="form-input"
                                placeholder="Create a strong password" required>
                            <span class="toggle-password" data-target="#password">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        <div class="info-box">
                            <p class="text-sm text-gray-700 font-semibold mb-2">Password must contain:</p>
                            <div class="password-requirements">
                                <ul>
                                    <li>At least 8 characters</li>
                                    <li>1 uppercase letter</li>
                                    <li>1 lowercase letter</li>
                                    <li>1 number</li>
                                    <li>1 special character (@$!%*#?&)</li>
                                </ul>
                            </div>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1 ml-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="form-label">Confirm Password</label>
                        <div class="input-with-icon">
                            <i class="form-icon bi bi-shield-lock-fill"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-input" placeholder="Confirm your password" required>
                            <span class="toggle-password" data-target="#password_confirmation">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="pt-4">
                        <div class="flex items-start space-x-4">
                            <input type="checkbox" id="privacy_policy" name="privacy_policy"
                                class="mt-1 rounded border-gray-300 text-orange-500 focus:ring-orange-500 focus:ring-2 focus:ring-offset-0"
                                style="width: 20px; height: 20px;" required>
                            <label for="privacy_policy" class="text-gray-700">
                                I agree to the
                                <a href="#"
                                    class="text-orange-500 hover:text-orange-600 font-semibold hover:underline transition-colors">Terms
                                    of Service</a>
                                and
                                <a href="#"
                                    class="text-orange-500 hover:text-orange-600 font-semibold hover:underline transition-colors">Privacy
                                    Policy</a>.
                                I understand this is a network marketing opportunity.
                            </label>
                        </div>
                        @error('privacy_policy')
                            <p class="text-red-500 text-sm mt-1 ml-6">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        onclick="return confirm('Are you sure you want to register with SmartSave24?')"
                        class="btn-register mt-8">
                        <i class="bi bi-person-plus-fill mr-3"></i>
                        Create Account & Start Earning
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6 pt-4 border-t border-gray-200">
                <p class="text-gray-600 text-lg">
                    Already have an account?
                    <a href="{{ route('auth.login') }}"
                        class="text-orange-500 font-semibold hover:text-orange-600 hover:underline transition-colors">
                        Sign In Here
                    </a>
                </p>
            </div>
        </div>

        <!-- Support Information -->
        <div class="text-center mt-6 pt-4 border-t border-gray-200/50">
            <p class="text-gray-300 mb-2 text-lg">Need help with registration?</p>
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

            // Check sponsor ID
            $('#sponsor_id').on('blur', function() {
                const sponsorId = $(this).val();
                const messageDiv = $('#sponsor-message');

                if (sponsorId) {
                    $.ajax({
                        url: '/check-sponsor/' + sponsorId,
                        type: 'GET',
                        beforeSend: function() {
                            messageDiv.html(
                                '<span class="text-blue-500 animate-pulse">Checking sponsor...</span>'
                            );
                        },
                        success: function(response) {
                            if (response.exists) {
                                messageDiv.html(`
                                    <span class="text-green-600 font-semibold flex items-center">
                                        <i class="bi bi-check-circle-fill mr-2"></i>
                                        Sponsor found: <strong class="ml-1">${response.name}</strong>
                                    </span>
                                `);
                            } else {
                                messageDiv.html(`
                                    <span class="text-red-500 font-semibold flex items-center">
                                        <i class="bi bi-x-circle-fill mr-2"></i>
                                        Sponsor not found
                                    </span>
                                `);
                            }
                        },
                        error: function() {
                            messageDiv.html(
                                '<span class="text-red-500">Error checking sponsor</span>');
                        }
                    });
                }
            });

            // Check email availability
            $('#email').on('blur', function() {
                const email = $(this).val();
                const messageDiv = $('#email-message');

                if (email && validateEmail(email)) {
                    $.ajax({
                        url: '/check-email',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            email: email
                        },
                        beforeSend: function() {
                            messageDiv.html(
                                '<span class="text-blue-500 animate-pulse">Checking email...</span>'
                            );
                        },
                        success: function(response) {
                            if (response.exists) {
                                messageDiv.html(`
                                    <span class="text-red-500 font-semibold flex items-center">
                                        <i class="bi bi-x-circle-fill mr-2"></i>
                                        Email already registered
                                    </span>
                                `);
                            } else {
                                messageDiv.html(`
                                    <span class="text-green-600 font-semibold flex items-center">
                                        <i class="bi bi-check-circle-fill mr-2"></i>
                                        Email available
                                    </span>
                                `);
                            }
                        },
                        error: function() {
                            messageDiv.html(
                                '<span class="text-red-500">Error checking email</span>');
                        }
                    });
                } else if (email) {
                    messageDiv.html(`
                        <span class="text-red-500 font-semibold flex items-center">
                            <i class="bi bi-x-circle-fill mr-2"></i>
                            Please enter a valid email
                        </span>
                    `);
                }
            });

            // Validate email format
            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            // Check URL for sponsor_id parameter
            const urlParams = new URLSearchParams(window.location.search);
            const sponsorId = urlParams.get('sponsor_id');
            if (sponsorId) {
                $('#sponsor_id').val(sponsorId);
                // Trigger the blur event to check sponsor
                setTimeout(() => {
                    $('#sponsor_id').trigger('blur');
                }, 500);
            }
        });
    </script>
</body>

</html>
