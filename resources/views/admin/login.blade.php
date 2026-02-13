<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        :root {
            --admin-blue: #1e40af;
            --admin-dark: #0f172a;
            --admin-light: #3b82f6;
            --admin-accent: #60a5fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }

        .admin-gradient {
            background: linear-gradient(135deg, var(--admin-blue), var(--admin-accent));
        }

        .glow-effect {
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.3);
        }

        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        /* Glass morphism effect */
        .glass-panel {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Loading animation */
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Floating animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        /* Security badge animation */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>

<body class="flex items-center justify-center p-4">

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0"
            style="background-image: linear-gradient(rgba(59, 130, 246, 0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(59, 130, 246, 0.05) 1px, transparent 1px); background-size: 40px 40px;">
        </div>

        <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-blue-500/10 blur-3xl floating"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 rounded-full bg-blue-900/10 blur-3xl floating"
            style="animation-delay: 1s;"></div>

        <div class="absolute top-10 left-10 text-blue-400/20">
            <i class="fas fa-shield-alt text-6xl"></i>
        </div>
        <div class="absolute bottom-10 right-10 text-blue-400/20">
            <i class="fas fa-lock text-6xl"></i>
        </div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <div class="flex justify-center mb-4">
            <div
                class="admin-gradient text-white px-6 py-3 rounded-full font-semibold text-sm tracking-wider uppercase glow-effect">
                <i class="fas fa-crown mr-2"></i>
                Admin Portal
            </div>
        </div>

        <div class="glass-panel rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-5 text-center border-b border-slate-700/50">
                <div class="flex items-center justify-center space-x-3 mb-2">
                    <a href="/">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center shadow-lg">
                            <i class="fas fa-cube text-white text-xl"></i>
                        </div>
                    </a>
                    <div class="text-left">
                        <h1 class="text-2xl font-bold text-white"></h1>
                        <p class="text-slate-400 text-sm">Administration System</p>
                    </div>
                </div>
            </div>

            <div class="px-6 pt-4">
                @if (session('success'))
                    <div
                        class="mb-4 bg-emerald-500/10 border border-emerald-500/30 rounded-lg p-4 flex items-center space-x-3">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        <p class="text-emerald-400 text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-500/10 border border-red-500/30 rounded-lg p-4 flex items-center space-x-3">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <p class="text-red-400 text-sm">{{ $errors->first() }}</p>
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('admin.login.post') }}" id="loginForm" class="px-6 py-4 pt-4">
                @csrf

                <div class="mb-6">
                    <label class="block text-slate-300 text-sm font-medium mb-2">
                        <i class="fas fa-user-shield mr-2"></i>
                        Admin Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-500"></i>
                        </div>
                        <input type="text" name="email" required value="{{ old('email') }}"
                            class="w-full pl-10 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all input-glow"
                            placeholder="Enter your admin email">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-slate-300 text-sm font-medium mb-2">
                        <i class="fas fa-key mr-2"></i>
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-500"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="w-full pl-10 pr-12 py-3 bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all input-glow"
                            placeholder="••••••••">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-eye text-slate-500 hover:text-blue-500 transition-colors"></i>
                        </button>
                    </div>
                </div>


                <button type="submit" id="loginButton"
                    class="w-full admin-gradient text-white py-4 rounded-lg font-semibold text-lg hover:shadow-xl hover:shadow-blue-500/20 transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-blue-500/40 flex items-center justify-center space-x-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Access Dashboard</span>
                </button>

                <div id="loadingSpinner" class="hidden mt-4">
                    <div class="flex items-center justify-center space-x-3 text-blue-400">
                        <div class="spinner"></div>
                        <span class="text-sm">Authenticating...</span>
                    </div>
                </div>
            </form>

            <div class="p-6 border-t border-slate-700/50 text-center">
                <div class="text-slate-500 text-sm">
                    <p class="flex items-center justify-center space-x-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Restricted Access - Authorized Personnel Only</span>
                    </p>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = togglePassword.querySelector('i');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle eye icon
                if (type === 'text') {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });

            // Add focus effects
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-500/20');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-500/20');
                });
            });

            // Auto-focus email field if there's an error
            @if ($errors->any())
                setTimeout(() => {
                    const emailInput = document.querySelector('input[name="email"]');
                    if (emailInput) emailInput.focus();
                }, 100);
            @endif

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl + Enter to submit
                if (e.ctrlKey && e.key === 'Enter') {
                    const form = document.getElementById('loginForm');
                    if (form) form.requestSubmit();
                }

                // Escape to clear
                if (e.key === 'Escape') {
                    passwordInput.value = '';
                    passwordInput.focus();
                }
            });
        });
    </script>
</body>

</html>
