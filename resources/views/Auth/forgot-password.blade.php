<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSave24 | Forgot Password</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* slate-50 */
        }
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative overflow-hidden selection:bg-emerald-500 selection:text-white">

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-100 rounded-full mix-blend-multiply filter blur-[80px] opacity-70 animate-pulse"></div>
        <div class="absolute top-1/2 -right-24 w-80 h-80 bg-orange-100 rounded-full mix-blend-multiply filter blur-[80px] opacity-70 animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 w-full max-w-md px-4 sm:px-0">
        
        <div class="bg-white/90 backdrop-blur-xl border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 sm:p-10">
            
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 rounded-full overflow-hidden border border-gray-100 shadow-sm p-1 mb-3 bg-white">
                    <img src="{{ asset('images/smartsave.png') }}" alt="SmartSave24 Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="font-poppins text-2xl font-extrabold text-slate-800 tracking-tight">
                    Smart<span class="text-orange-500">Save24</span>
                </h1>
            </div>

            <div class="text-center mb-8">
                <h2 class="text-xl font-bold text-slate-800 mb-2">Reset Password</h2>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">
                    Enter your registered email address and we'll send you an OTP to reset your password.
                </p>
            </div>

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="bi bi-envelope text-slate-400 text-lg"></i>
                        </div>
                        <input type="email" name="email" id="email" 
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200" 
                            placeholder="e.g. name@example.com" required value="{{ old('email') }}">
                    </div>
                    
                    @error('email')
                        <div class="mt-2 flex items-center gap-1.5 text-red-500 text-xs font-semibold">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <button type="submit" 
                    class="w-full bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold py-3.5 rounded-xl shadow-[0_4px_15px_rgba(16,185,129,0.3)] hover:shadow-[0_6px_20px_rgba(16,185,129,0.4)] transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="bi bi-send-fill"></i> Send OTP
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-slate-500 font-medium">
                    Remember your password? 
                    <a href="{{ route('auth.login') }}" class="text-emerald-600 font-bold hover:text-emerald-700 transition-colors">
                        Back to Login
                    </a>
                </p>
            </div>

        </div>
    </div>

</body>
</html>