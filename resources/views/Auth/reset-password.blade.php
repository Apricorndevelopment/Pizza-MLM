<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSave24 | Reset Password</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Premium gradient background */
            background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
        }
        
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }

        /* Subtle Dot Pattern Overlay */
        .bg-dots {
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative overflow-hidden selection:bg-emerald-500 selection:text-white">

    <div class="absolute inset-0 z-0 bg-dots opacity-50 pointer-events-none"></div>

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute -top-32 -left-24 w-[30rem] h-[30rem] bg-emerald-200/50 rounded-full mix-blend-multiply filter blur-[100px] animate-pulse"></div>
        <div class="absolute top-1/3 -right-32 w-[25rem] h-[25rem] bg-orange-200/40 rounded-full mix-blend-multiply filter blur-[100px] animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-40 left-1/3 w-[20rem] h-[20rem] bg-teal-200/40 rounded-full mix-blend-multiply filter blur-[80px] animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative z-10 w-full max-w-md px-4 sm:px-0">
        
        <div class="bg-white/80 backdrop-blur-2xl border border-white/60 rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] p-6 sm:p-7">
            
            <div class="flex flex-col items-center mb-4">
                <div class="w-14 h-14 rounded-full overflow-hidden border border-gray-100 shadow-md p-1 mb-2 bg-white">
                    <img src="{{ asset('images/smartsave.png') }}" alt="SmartSave24 Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="font-poppins text-2xl font-extrabold text-slate-800 tracking-tight">
                    Smart<span class="text-orange-500">Save24</span>
                </h1>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-slate-800 mb-2">Create New Password</h2>
            </div>

            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="otp" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Enter OTP</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="bi bi-shield-lock text-slate-400 text-lg"></i>
                        </div>
                        <input type="text" name="otp" id="otp" 
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 tracking-widest placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm" 
                            placeholder="• • • • • •" required autocomplete="off">
                    </div>
                    @error('otp')
                        <div class="mt-2 flex items-center gap-1.5 text-red-500 text-xs font-semibold">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="bi bi-key text-slate-400 text-lg"></i>
                        </div>
                        <input type="password" name="password" id="password" 
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm" 
                            placeholder="Enter new password" required>
                    </div>
                    @error('password')
                        <div class="mt-2 flex items-center gap-1.5 text-red-500 text-xs font-semibold">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="bi bi-key-fill text-slate-400 text-lg"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm" 
                            placeholder="Confirm new password" required>
                    </div>
                    @error('password_confirmation')
                        <div class="mt-2 flex items-center gap-1.5 text-red-500 text-xs font-semibold">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <button type="submit" 
                    class="w-full bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold py-3.5 rounded-xl shadow-[0_4px_15px_rgba(16,185,129,0.3)] hover:shadow-[0_6px_20px_rgba(16,185,129,0.4)] transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-4">
                    <i class="bi bi-check2-circle text-lg"></i> Update Password
                </button>
            </form>

            <div class="mt-4 text-center border-t border-gray-100/60">
                <a href="{{ route('auth.login') }}" class="text-sm text-slate-500 font-medium hover:text-emerald-600 transition-colors flex items-center justify-center gap-1.5">
                    <i class="bi bi-arrow-left"></i> Back to Login
                </a>
            </div>

        </div>
    </div>

        <!-- Bootstrap JS Bundle with Popper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>