<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to - Registration Successful</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #FFF9F0 0%, #F0FFEE 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .header {
            background: linear-gradient(135deg, #FF6B35, #4CAF50);
            padding: 20px 15px;
            text-align: center;
            color: white;
        }

        .content {
            padding: 20px;
        }

        .check-icon {
            font-size: 4rem;
            color: #10B981;
            margin-bottom: 10px;
            animation: checkmark 0.6s ease-out;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            50% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .user-details {
            background: #f8fafc;
            border-radius: 12px;
            padding: 15px;
            margin: 15px 0;
            border: 1px solid #e2e8f0;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #4b5563;
            font-weight: 500;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 600;
            font-family: 'Courier New', monospace;
            background: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.95rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #FF6B35, #4CAF50);
            color: white;
            padding: 14px 30px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
        }

        .brand-name span {
            color: #FFD166;
        }

        .text-muted {
            color: #6b7280;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="card">
        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                <div class="logo">
                    <img src="{{ asset('images/ziddizone.jpeg') }}" alt="">
                </div>
                <div class="brand-name">
                    Ziddi<span>Zone</span>
                </div>
            </div>
            <h2 class="text-xl font-bold">Registration Successful!</h2>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Success Icon -->
            <div class="text-center">
                <div class="check-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    Welcome to , {{ $user->name }}! 🎉
                </h3>
                <p class="text-muted mb-4">
                    You're now part of India's fastest growing food network
                </p>
            </div>

            <!-- User Details -->
            <div class="user-details">
                <h4 class="font-semibold text-gray-800 mb-3 text-center">
                    <i class="bi bi-person-badge mr-2"></i>
                    Your Account Details
                </h4>

                <div class="detail-item">
                    <span class="detail-label">Full Name:</span>
                    <span class="detail-value">{{ $user->name }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Email Address:</span>
                    <span class="detail-value">{{ $user->email }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Unique ID:</span>
                    <span class="detail-value">{{ $user->ulid }}</span>
                </div>
            </div>

            <!-- Important Note -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <i class="bi bi-shield-check text-yellow-500 text-lg mr-3 mt-1"></i>
                    <div>
                        <p class="text-sm text-yellow-800 font-medium">Keep your login details secure</p>
                        <p class="text-xs text-yellow-600 mt-1">
                            Do not share your password with anyone. will never ask for your password.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Login Button -->
            <a href="{{ route('auth.login') }}" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Proceed to Login
            </a>

        </div>
    </div>

    <script>
        // Simple animation for the page
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>

</html>
