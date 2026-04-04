<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSave24 | India's No.1 Direct Selling Network</title>
    <link rel="shortcut icon" href="{{ asset('images/smartsave.png') }}" type="image/x-icon">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&family=Noto+Serif+Devanagari:wght@700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

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

        /* Hindi Font Class */
        .hindi-font {
            font-family: 'Noto Serif Devanagari', serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #FFF9F0 0%, #F0FFEE 100%);
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .food-badge {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
        }

        .nav-link {
            position: relative;
            padding: 6px 8px;
            color: #475569;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link.active {
            color: var(--primary);
            font-weight: 600;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 16px;
            right: 16px;
            height: 2px;
            background: var(--primary);
            border-radius: 2px;
        }

        .food-card {
            border-radius: 20px;
            overflow: hidden;
            border: none;
            background: white;
            position: relative;
        }

        .food-img {
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .food-card:hover .food-img {
            transform: scale(1.1);
        }

        .stats-card {
            background: linear-gradient(135deg, var(--dark), #374D7A);
            color: white;
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
        }

        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            border-left: 5px solid var(--primary);
        }

        .food-category {
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .category-organic {
            background: #E8F5E9;
            color: var(--secondary);
        }

        .category-premium {
            background: #FFF3E0;
            color: var(--primary);
        }

        .category-special {
            background: #F3E5F5;
            color: #9C27B0;
        }

        /* Swiper Adjustments */
        .swiper-pagination-bullet-active {
            background-color: var(--primary) !important;
        }

        .banner-img {
            width: 100%;
            height: 600px;
            object-fit: cover;
            border-radius: 15px;
        }

        @media (max-width: 950px) {
            .banner-img {
                height: 420px;
            }
        }

        @media (max-width: 768px) {
            .banner-img {
                height: 350px;
            }
        }

        @media (max-width: 500px) {
            .banner-img {
                height: 190px;
            }
        }

        /* Achiever Image */
        .achiever-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--accent);
            margin: 0 auto 15px;
        }

        /* Mobile Menu Animation */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .mobile-menu {
            animation: slideDown 0.3s ease;
        }

        /* cards wale section ki css */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        /* Subtle mesh pattern for attractive background */
        .bg-mesh-pattern {
            background-image: radial-gradient(at 40% 20%, rgba(16, 185, 129, 0.05) 0px, transparent 50%),
                radial-gradient(at 80% 0%, rgba(249, 115, 22, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 50%, rgba(251, 191, 36, 0.05) 0px, transparent 50%),
                radial-gradient(at 80% 50%, rgba(16, 185, 129, 0.05) 0px, transparent 50%);
        }

        /* hero section ki css */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>

<body class="gradient-bg">

    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <nav class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 group">
                    <a href="/">
                        <div
                            class="w-12 h-12 rounded-full overflow-hidden border border-gray-200 group-hover:border-orange-400 transition-colors shadow-sm">
                            <img src="{{ asset('images/smartsave.png') }}" alt="Logo"
                                class="w-full h-full object-cover bg-white">
                        </div>
                    </a>
                    <a href="/" class="font-poppins text-xl font-extrabold text-gray-800 tracking-tight">
                        Smart<span class="text-orange-500">Save24</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-2 lg:space-x-6">
                    <a href="#home" class="nav-link active">Home</a>
                    <a href="#about" class="nav-link">About Us</a>
                    <a href="#vendors" class="nav-link">Vendors</a>
                    <a href="#products" class="nav-link">Products</a>
                    <a href="#achievers" class="nav-link">Achievers</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </div>

                <div class="hidden md:flex items-center space-x-5">
                    <a href="{{ route('auth.login') }}"
                        class="text-gray-600 hover:text-orange-500 font-semibold transition flex items-center">
                        <i class="bi bi-box-arrow-in-right mr-1.5 text-lg"></i> Login
                    </a>
                    <a href="{{ route('auth.register') }}"
                        class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-2.5 rounded-full font-bold shadow-[0_4px_15px_rgba(249,115,22,0.3)] hover:shadow-[0_6px_20px_rgba(249,115,22,0.4)] transition-all transform hover:-translate-y-0.5">
                        Join Now
                    </a>
                </div>

                <button id="mobileMenuBtn" class="md:hidden text-gray-800 text-3xl focus:outline-none">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </nav>

        <div id="mobileMenu"
            class="hidden md:hidden absolute top-full left-0 w-full bg-white border-t border-gray-100 shadow-xl mobile-menu">
            <div class="flex flex-col px-6 py-5 space-y-4">
                <a href="#home"
                    class="mobile-link text-gray-700 font-medium hover:text-orange-500 border-b border-gray-50 pb-2">Home</a>
                <a href="#about"
                    class="mobile-link text-gray-700 font-medium hover:text-orange-500 border-b border-gray-50 pb-2">About
                    Us</a>
                <a href="#vendors"
                    class="mobile-link text-gray-700 font-medium hover:text-orange-500 border-b border-gray-50 pb-2">Vendors</a>
                <a href="#products"
                    class="mobile-link text-gray-700 font-medium hover:text-orange-500 border-b border-gray-50 pb-2">Products</a>
                <a href="#achievers"
                    class="mobile-link text-gray-700 font-medium hover:text-orange-500 border-b border-gray-50 pb-2">Achievers</a>
                <a href="#contact"
                    class="mobile-link text-gray-700 font-medium hover:text-orange-500 border-b border-gray-50 pb-2">Contact</a>

                <div class="flex flex-col gap-3 mt-4">
                    <a href="{{ route('auth.login') }}"
                        class="text-center text-gray-600 border border-gray-200 hover:text-orange-500 font-semibold py-2.5 rounded-xl transition">
                        <i class="bi bi-box-arrow-in-right mr-1.5"></i> Login
                    </a>
                    <a href="{{ route('auth.register') }}"
                        class="text-center bg-gradient-to-r from-orange-500 to-orange-600 text-white py-2.5 rounded-xl font-bold shadow-md transition">
                        Join Now
                    </a>
                </div>
            </div>
        </div>
    </header>

    <section id="home"
        class="relative pt-12 pb-20 md:pt-20 md:pb-28 overflow-hidden bg-slate-50 selection:bg-green-100 selection:text-green-900">

        <div class="absolute top-0 right-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div
                class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-orange-100/60 rounded-full mix-blend-multiply filter blur-[100px] animate-pulse">
            </div>
            <div class="absolute top-40 -left-32 w-[400px] h-[400px] bg-emerald-100/60 rounded-full mix-blend-multiply filter blur-[100px] animate-pulse"
                style="animation-delay: 2s;"></div>
            <div
                class="absolute bottom-0 right-1/4 w-[300px] h-[300px] bg-yellow-100/40 rounded-full mix-blend-multiply filter blur-[80px]">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 xl:px-16 relative z-10">

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-10 items-center">

                <div class="flex flex-col items-start text-left">

                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-md border border-green-100 shadow-sm mb-6 transition-transform hover:-translate-y-0.5" data-aos="flip-left" data-aos-delay="100">
                        <span
                            class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)] animate-pulse"></span>
                        <span class="text-xs font-bold text-slate-700 tracking-wider uppercase">India's #1 Direct
                            Selling Network</span>
                    </div>

                    <h2 style="font-family: 'Pacifico', cursive;"
                        class="text-2xl md:text-3xl text-red-500 tracking-wide mb-2 opacity-90" data-aos="fade-right" data-aos-delay="200">
                        Welcome To
                    </h2>
                    <div class="relative mb-8 w-full max-w-[250px] sm:max-w-[320px]">
                        <img src="{{ asset('images/smartsave1.png') }}"
                            class="w-full h-auto object-contain drop-shadow-lg" alt="SmartSave24 Logo" data-aos="zoom-in-up" data-aos-delay="300">
                    </div>

                    <div class="w-full space-y-7 mb-8" data-aos="fade-up" data-aos-delay="400">

                        <h3
                            class="text-4xl md:text-5xl lg:text-[3.25rem] font-extrabold text-slate-800 leading-[1.15] tracking-tight">
                            Smart Business for <br />
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500">Smart
                                People</span>
                        </h3>

                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 text-lg font-semibold text-slate-700">
                            <div
                                class="flex items-center gap-2.5 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100" data-aos="fade-up">
                                <i class="bi bi-cart-check-fill text-emerald-500 text-xl"></i>
                                <span class="text-slate-800">Shop</span>
                            </div>
                            <div
                                class="flex items-center gap-2.5 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100" data-aos="fade-up">
                                <i class="bi bi-basket-fill text-orange-500 text-xl"></i>
                                <span class="text-slate-800">Eat</span>
                            </div>
                            <div
                                class="flex items-center gap-2.5 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100" data-aos="fade-up">
                                <i class="bi bi-wallet-fill text-teal-600 text-xl"></i>
                                <span class="text-slate-800">Earn</span>
                            </div>
                        </div>

                        <div
                            class="relative bg-white shadow-md hover:shadow-xl transition-all duration-300 rounded-2xl p-6 border border-slate-100 overflow-hidden group" data-aos="fade-left">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-red-500 to-orange-400">
                            </div>
                            <i
                                class="bi bi-quote absolute -right-2 -top-4 text-[5rem] text-slate-50 group-hover:text-slate-100 transition-colors duration-300 z-0 rotate-12"></i>

                            <p
                                class="text-[17px] md:text-lg text-slate-600 font-medium leading-relaxed pl-3 relative z-10">
                                "स्मार्ट लोगों के लिए स्मार्ट बिजनेस: वह खरीदें जो आपको पसंद हो, वह खाएं जो आप चाहते
                                हैं, वह कमाएं जिसके आप हकदार हैं।"
                            </p>
                        </div>

                    </div>

                    <div class="flex flex-wrap gap-4 mt-2" data-aos="fade-up">
                        <a href="{{ route('auth.register') }}"
                            class="group relative inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-4 rounded-full font-bold text-lg overflow-hidden shadow-[0_10px_20px_rgba(16,185,129,0.25)] hover:shadow-[0_15px_30px_rgba(16,185,129,0.4)] transition-all duration-300 hover:-translate-y-1">
                            <span
                                class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black pointer-events-none"></span>
                            <span class="relative">Start Earning Now</span>
                            <i
                                class="bi bi-rocket-takeoff text-xl relative group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform duration-300"></i>
                        </a>
                    </div>

                </div>

                <div class="relative hidden lg:flex justify-center items-center h-full w-full py-10"
                    data-aos="fade-left">

                    <div
                        class="absolute inset-0 bg-gradient-to-br from-green-50 to-orange-50 rounded-[4rem] transform rotate-3 scale-90 -z-10 transition-transform duration-700 hover:rotate-6">
                    </div>

                    <div class="relative w-[400px] max-w-full group">
                        <img src="images/smart.png" alt="Smart Investment MLM"
                            class="w-full h-auto object-cover rounded-[2rem] border-[6px] border-white shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-all duration-500 group-hover:-translate-y-2 group-hover:shadow-[0_25px_50px_rgba(0,0,0,0.18)] relative z-10">

                        <div
                            class="absolute -bottom-6 -left-8 bg-white p-4 rounded-2xl shadow-xl z-20 border border-slate-50 animate-float flex items-center gap-3 hover:scale-105 transition-transform">
                            <div
                                class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center border border-emerald-200">
                                <i class="bi bi-graph-up-arrow text-emerald-600 text-xl"></i>
                            </div>
                            <div class="pr-2">
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-0.5">Smart
                                    Network</p>
                                <p class="text-base font-extrabold text-slate-800 leading-none">Earn While You Eat</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>



    <section
        class="py-20 md:py-24 bg-white relative overflow-hidden bg-mesh-pattern selection:bg-orange-100 selection:text-orange-900 border-t border-slate-100">

        <div
            class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-emerald-50 rounded-full blur-[120px] opacity-70 pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-amber-50 rounded-full blur-[100px] opacity-60 pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 xl:px-16 relative z-10">

            <div class="text-center mb-16 max-w-3xl mx-auto" data-aos="fade-up">
                <h2
                    class="text-4xl md:text-5xl lg:text-[3.5rem] font-extrabold text-slate-800 mb-4 hindi-font tracking-wide leading-tight">
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500">"ज़िद
                        है तो जीत है"</span>
                </h2>
                <p class="text-slate-600 font-medium text-lg md:text-xl">Join the fastest-growing network of smart
                    earners in India.</p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">

                @forelse($stats as $index => $stat)
                    <div data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}"
                        class="relative bg-white rounded-3xl p-8 text-center shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-2 border-transparent transition-all duration-300 hover:shadow-[0_20px_50px_rgba(249,115,22,0.1)] hover:-translate-y-2 hover:border-orange-100 group overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-orange-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>

                        <div class="relative z-10">
                            @if ($stat->icon)
                                <div
                                    class="w-16 h-16 mx-auto bg-orange-100/70 rounded-2xl flex items-center justify-center mb-6 border border-orange-200 shadow-sm transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6">
                                    <i class="{{ $stat->icon }} text-4xl text-orange-600"></i>
                                </div>
                            @endif
                            <div
                                class="text-4xl md:text-5xl font-black text-slate-900 mb-3 tracking-tighter group-hover:text-orange-700 transition-colors">
                                {{ $stat->value }}</div>
                            <div class="text-slate-600 font-bold uppercase text-sm tracking-wider">{{ $stat->title }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div data-aos="zoom-in" data-aos-delay="0"
                        class="relative bg-gradient-to-br from-emerald-50 to-white rounded-3xl p-8 text-center shadow-[0_10px_30px_rgba(16,185,129,0.05)] border-2 border-emerald-100 transition-all duration-300 hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)] hover:-translate-y-2 group">
                        <div
                            class="w-16 h-16 mx-auto bg-white rounded-2xl flex items-center justify-center mb-6 shadow-md border border-emerald-100 transition-transform duration-500 group-hover:scale-110">
                            <i class="bi bi-people-fill text-4xl text-emerald-500"></i>
                        </div>
                        <div
                            class="text-4xl md:text-5xl font-black text-emerald-900 mb-3 tracking-tighter group-hover:text-emerald-700">
                            10K+</div>
                        <div class="text-emerald-700 font-bold uppercase text-sm tracking-wider">Active Members</div>
                        <div
                            class="absolute -bottom-4 -right-4 w-16 h-16 bg-emerald-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500">
                        </div>
                    </div>

                    <div data-aos="zoom-in" data-aos-delay="100"
                        class="relative bg-gradient-to-br from-orange-50 to-white rounded-3xl p-8 text-center shadow-[0_10px_30px_rgba(249,115,22,0.05)] border-2 border-orange-100 transition-all duration-300 hover:shadow-[0_20px_50px_rgba(249,115,22,0.15)] hover:-translate-y-2 group">
                        <div
                            class="w-16 h-16 mx-auto bg-white rounded-2xl flex items-center justify-center mb-6 shadow-md border border-orange-100 transition-transform duration-500 group-hover:scale-110">
                            <i class="bi bi-box-seam-fill text-4xl text-orange-500"></i>
                        </div>
                        <div
                            class="text-4xl md:text-5xl font-black text-orange-900 mb-3 tracking-tighter group-hover:text-orange-700">
                            60+</div>
                        <div class="text-orange-700 font-bold uppercase text-sm tracking-wider">Products</div>
                        <div
                            class="absolute -bottom-4 -right-4 w-16 h-16 bg-orange-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500">
                        </div>
                    </div>

                    <div data-aos="zoom-in" data-aos-delay="200"
                        class="relative bg-gradient-to-br from-red-50 to-white rounded-3xl p-8 text-center shadow-[0_10px_30px_rgba(239,68,68,0.05)] border-2 border-red-100 transition-all duration-300 hover:shadow-[0_20px_50px_rgba(239,68,68,0.15)] hover:-translate-y-2 group">
                        <div
                            class="w-16 h-16 mx-auto bg-white rounded-2xl flex items-center justify-center mb-6 shadow-md border border-red-100 transition-transform duration-500 group-hover:scale-110">
                            <i class="bi bi-cash-stack text-4xl text-red-500"></i>
                        </div>
                        <div
                            class="text-4xl md:text-5xl font-black text-red-900 mb-3 tracking-tighter group-hover:text-red-700">
                            ₹80L+</div>
                        <div class="text-red-700 font-bold uppercase text-sm tracking-wider">Commissions</div>
                        <div
                            class="absolute -bottom-4 -right-4 w-16 h-16 bg-red-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500">
                        </div>
                    </div>

                    <div data-aos="zoom-in" data-aos-delay="300"
                        class="relative bg-gradient-to-br from-yellow-50 to-white rounded-3xl p-8 text-center shadow-[0_10px_30px_rgba(251,191,36,0.05)] border-2 border-yellow-100 transition-all duration-300 hover:shadow-[0_20px_50px_rgba(251,191,36,0.15)] hover:-translate-y-2 group">
                        <div
                            class="w-16 h-16 mx-auto bg-white rounded-2xl flex items-center justify-center mb-6 shadow-md border border-yellow-100 transition-transform duration-500 group-hover:scale-110">
                            <i class="bi bi-geo-alt-fill text-4xl text-yellow-600"></i>
                        </div>
                        <div
                            class="text-4xl md:text-5xl font-black text-yellow-950 mb-3 tracking-tighter group-hover:text-yellow-700">
                            50+</div>
                        <div class="text-yellow-700 font-bold uppercase text-sm tracking-wider">Cities</div>
                        <div
                            class="absolute -bottom-4 -right-4 w-16 h-16 bg-yellow-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500">
                        </div>
                    </div>
                @endforelse

            </div>
        </div>
    </section>

    <section id="about" class="py-16 md:py-24 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-orange-50 rounded-full blur-3xl opacity-60">
        </div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-green-50 rounded-full blur-3xl opacity-60">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16 max-w-4xl mx-auto" data-aos="fade-up">
                <span
                    class="bg-gradient-to-r from-orange-100 to-amber-100 text-orange-600 px-5 py-1.5 rounded-full text-sm font-black tracking-widest mb-4 inline-block shadow-sm border border-orange-200 uppercase">
                    Why Choose SmartSave24?
                </span>
                <h2 class="text-3xl md:text-5xl font-extrabold mb-4 text-gray-800 hindi-font">
                    अब हर <span class="text-orange-500">Customer</span> बनेगा <span
                        class="text-emerald-600">Businessman!</span>
                </h2>
                <p class="text-lg md:text-xl text-gray-600 font-medium font-poppins">
                    SmartSave24 सिर्फ एक प्लेटफ़ॉर्म नहीं, बल्कि आपकी तरक्की का नया address है।
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-10">

                <div data-aos="fade-right" data-aos-delay="100"
                    class="bg-gradient-to-br from-emerald-50 to-white p-8 md:p-10 rounded-[2rem] shadow-lg border border-emerald-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-emerald-200 rounded-full blur-3xl opacity-40 -mr-10 -mt-10">
                    </div>

                    <div
                        class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-emerald-100 relative z-10">
                        <i class="bi bi-wallet2 text-3xl text-emerald-500"></i>
                    </div>

                    <h3 class="text-2xl font-bold mb-4 text-gray-800 font-poppins relative z-10">
                        Eat Smart, Save Big, <span class="text-emerald-500">Earn More</span>
                    </h3>

                    <p class="text-gray-600 mb-6 font-medium hindi-font relative z-10 text-lg leading-snug">
                        अब आपका <span class="text-emerald-600 font-bold">खर्चा</span> ही आपकी सबसे बड़ी <span
                            class="text-emerald-600 font-bold">Income</span> है! हर ख़रीदारी पर मिलेगा फायदा:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 relative z-10">
                        <div
                            class="bg-white p-3.5 rounded-xl shadow-sm border border-emerald-50 flex items-center gap-3 hover:border-emerald-200 transition-colors">
                            <div class="bg-emerald-50 p-2 rounded-lg flex-shrink-0">
                                <i class="bi bi-flower1 text-emerald-500 text-lg"></i>
                            </div>
                            <span class="font-bold text-gray-700 text-sm leading-tight">Health & <br>Ayurveda</span>
                        </div>

                        <div
                            class="bg-white p-3.5 rounded-xl shadow-sm border border-emerald-50 flex items-center gap-3 hover:border-emerald-200 transition-colors">
                            <div class="bg-emerald-50 p-2 rounded-lg flex-shrink-0">
                                <i class="bi bi-shop text-emerald-500 text-lg"></i>
                            </div>
                            <span class="font-bold text-gray-700 text-sm leading-tight">Delicious <br>Fast Food</span>
                        </div>

                        <div
                            class="bg-white p-3.5 rounded-xl shadow-sm border border-emerald-50 flex items-center gap-3 hover:border-emerald-200 transition-colors">
                            <div class="bg-emerald-50 p-2 rounded-lg flex-shrink-0">
                                <i class="bi bi-phone-vibrate text-emerald-500 text-lg"></i>
                            </div>
                            <span class="font-bold text-gray-700 text-sm leading-tight">Mobile Recharge <br>& Bill
                                Pay</span>
                        </div>

                        <div
                            class="bg-white p-3.5 rounded-xl shadow-sm border border-emerald-50 flex items-center gap-3 hover:border-emerald-200 transition-colors">
                            <div class="bg-emerald-50 p-2 rounded-lg flex-shrink-0">
                                <i class="bi bi-cart-check-fill text-emerald-500 text-lg"></i>
                            </div>
                            <span class="font-bold text-gray-700 text-sm leading-tight">Garments & <br>Grocery</span>
                        </div>
                    </div>
                </div>

                <div data-aos="fade-left" data-aos-delay="200"
                    class="bg-gradient-to-br from-orange-50 to-white p-8 md:p-10 rounded-[2rem] shadow-lg border border-orange-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-orange-200 rounded-full blur-3xl opacity-40 -mr-10 -mt-10">
                    </div>

                    <div
                        class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-orange-100 relative z-10">
                        <i class="bi bi-shop text-3xl text-orange-500"></i>
                    </div>

                    <h3 class="text-2xl font-bold mb-4 text-gray-800 font-poppins relative z-10">
                        <span class="text-orange-500">Vendor</span> Network
                    </h3>

                    <p class="text-gray-600 mb-6 font-medium hindi-font relative z-10 text-lg">क्या आप अपने बिज़नेस को
                        नई ऊंचाइयों पर ले जाना चाहते हैं?</p>

                    <div class="space-y-4 relative z-10">
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-orange-50 flex gap-4 items-start">
                            <div class="mt-1"><i class="bi bi-house-door-fill text-orange-400 text-xl"></i></div>
                            <div>
                                <h4 class="font-bold text-gray-800">Reach Every Home</h4>
                                <p class="text-sm text-gray-600 hindi-font mt-1">क्या आप अपने Product को हर घर तक भेजना
                                    चाहते हैं? हम आपकी मदद करेंगे।</p>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-xl shadow-sm border border-orange-50 flex gap-4 items-start">
                            <div class="mt-1"><i class="bi bi-diagram-3-fill text-orange-400 text-xl"></i></div>
                            <div>
                                <h4 class="font-bold text-gray-800">Direct Selling Power</h4>
                                <p class="text-sm text-gray-600 hindi-font mt-1">अपने बिज़नेस को Direct Selling
                                    नेटवर्क के साथ मिलकर तेज़ रफ़्तार दें।</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="vendors" class="py-10 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800">SmartSave24 Vendors</h2>
                {{-- <p class="text-gray-500">Exclusive Offers from Top Vendors</p> --}}
            </div>

            <div class="swiper vendorSwiper pb-10" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">
                    @forelse($vendorBanners as $banner)
                        <div class="swiper-slide">
                            <div class="rounded-xl overflow-hidden shadow-lg relative group">
                                <img src="{{ asset($banner->banner_image) }}" class="banner-img"
                                    alt="{{ $banner->title }}">
                                @if ($banner->title)
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/50 p-4 text-white">
                                        <h3 class="text-xl font-bold">{{ $banner->title }}</h3>
                                        @if ($banner->link)
                                            <a href="{{ $banner->link }}"
                                                class="text-orange-300 text-sm hover:underline">Visit Store &rarr;</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center w-full text-gray-400 py-10">No active vendor banners available.</div>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section id="products" class="py-12 bg-slate-50">
        <div class="container mx-auto px-4" data-aos="fade-up">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Featured Offers</h2>
                <p class="text-gray-500 mt-2 font-medium">Unbeatable Deals on Our Best Products</p>
            </div>

            <div class="swiper productBannerSwiper pb-14 px-2 pt-2" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">
                    @forelse($productBanners as $pBanner)
                        <div class="swiper-slide h-auto">
                            <div
                                class="bg-white rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.04)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 group flex flex-col h-full">

                                <div class="relative h-[220px] w-full overflow-hidden bg-gray-100">
                                    <img src="{{ asset($pBanner->banner_image) }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                        alt="{{ $pBanner->title ?? 'Offer Image' }}">

                                    <div
                                        class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-md">
                                        Hot Deal
                                    </div>
                                </div>

                                <div class="p-5 flex flex-col flex-grow">
                                    @if ($pBanner->title)
                                        <h3 class="text-lg font-bold text-gray-800 mb-3 leading-snug line-clamp-2">
                                            {{ $pBanner->title }}
                                        </h3>
                                    @endif

                                    <div class="mt-auto pt-4 border-t border-gray-50">
                                        @if ($pBanner->link)
                                            <a href="{{ $pBanner->link }}"
                                                class="flex items-center justify-center w-full bg-emerald-50 text-emerald-600 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-emerald-500 hover:text-white transition-all duration-300">
                                                Shop Now <i class="bi bi-arrow-right ml-2 text-lg leading-none"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="text-center w-full flex flex-col items-center justify-center text-gray-400 py-12">
                            <i class="bi bi-box-seam text-4xl mb-3 text-gray-300"></i>
                            <p>No product offers available at the moment.</p>
                        </div>
                    @endforelse
                </div>

                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section id="achievers" class="py-12 md:py-18" style="background: #F8FFEE;">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="food-badge px-4 py-1 rounded-full text-sm font-semibold mb-4 inline-block">
                    Wall of Fame
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">Our Top Achievers</h2>
                <p class="text-lg text-gray-600">Celebrating the leaders who inspire us all.</p>
            </div>

            <div class="swiper achieverSwiper pb-12 px-4" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">
                    @forelse($achievers as $achiever)
                        <div class="swiper-slide h-auto">
                            <div
                                class="achiever-card h-full flex flex-col items-center justify-center p-6 bg-white rounded-2xl shadow-md border-t-4 border-orange-500 hover:shadow-xl transition-all duration-300">
                                <div class="relative mb-4">
                                    <div
                                        class="w-24 h-24 rounded-full p-1 bg-linear-to-r from-orange-400 to-yellow-400">
                                        <img src="{{ asset($achiever->photo) }}" alt="{{ $achiever->name }}"
                                            class="w-full h-full rounded-full object-cover border-4 border-white">
                                    </div>
                                    <div
                                        class="absolute -bottom-2 -right-2 bg-yellow-400 text-white w-8 h-8 flex items-center justify-center rounded-full text-xs shadow-sm">
                                        <i class="bi bi-trophy-fill"></i>
                                    </div>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 text-center">{{ $achiever->name }}</h3>
                                <span
                                    class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full mt-2 uppercase tracking-wide">
                                    {{ $achiever->rank }}
                                </span>
                                <div class="mt-3 flex gap-1 text-yellow-400 text-sm">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center w-full text-gray-500 py-10">No achievers listed yet.</div>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24" style="background: linear-gradient(135deg, var(--dark), #374D7A);">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6" data-aos="fade-up">
                Ready to Start Your Journey?
            </h2>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Join thousands of successful entrepreneurs who are building wealth while promoting health.
            </p>

            <div class="flex flex-col md:flex-row gap-6 justify-center items-center" >
                <a href="{{ route('auth.register') }}"
                    class="bg-orange-500 text-white px-10 py-4 rounded-full font-bold text-lg hover:shadow-2xl transition-all inline-flex items-center text-center" data-aos="fade-right" data-aos-delay="200">
                    <i class="bi bi-rocket-takeoff mr-3"></i>
                    Start Free Registration
                </a>

                <a href="https://wa.me/918278273737" target="_blank"
                    class="bg-white text-gray-800 px-10 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition-all inline-flex items-center" data-aos="fade-left" data-aos-delay="200">
                    <i class="bi bi-headset mr-3"></i>
                    Talk to Our Team
                </a>
            </div>

            <div class="mt-12 grid md:grid-cols-3 gap-8 text-white">
                <div class="flex items-center justify-center" data-aos="fade-up" data-aos-delay="300">
                    <i class="bi bi-shield-check text-3xl text-green-400 mr-4"></i>
                    <div>
                        <div class="font-bold">100% Legal</div>
                        <div class="text-sm text-gray-300">Registered Company</div>
                    </div>
                </div>
                <div class="flex items-center justify-center" data-aos="fade-up" data-aos-delay="400">
                    <i class="bi bi-cash-coin text-3xl text-yellow-400 mr-4"></i>
                    <div>
                        <div class="font-bold">Instant Payouts</div>
                        <div class="text-sm text-gray-300">Daily Withdrawals</div>
                    </div>
                </div>
                <div class="flex items-center justify-center" data-aos="fade-up" data-aos-delay="500">
                    <i class="bi bi-headphones text-3xl text-blue-400 mr-4"></i>
                    <div>
                        <div class="font-bold">24/7 Support</div>
                        <div class="text-sm text-gray-300">Dedicated Assistance</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="text-center mb-16" data-aos="fade-down" data-aos-delay="100">
                <span class="food-badge px-4 py-1 rounded-full text-sm font-semibold mb-4 inline-block">
                    Get In Touch
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">Contact</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                <div data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-2xl font-bold mb-6">Contact Details</h3>
                    <div class="space-y-6">
                        {{-- <div class="flex items-start">
                            <i class="bi bi-geo-alt text-2xl text-orange-500 mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-bold mb-1">Head Office</h4>
                                <p class="text-gray-600">Main GT Road, V.P.O Rai <br>Sonipat</p>
                            </div>
                        </div> --}}
                        <div class="flex items-start">
                            <i class="bi bi-telephone text-2xl text-green-500 mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-bold mb-1">Phone Number</h4>
                                <p class="text-gray-600">+91 827827 3737</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="bi bi-envelope text-2xl text-blue-500 mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-bold mb-1">Email Address</h4>
                                <p class="text-gray-600">smartsave24company@gmail.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10">
                        <h4 class="font-bold mb-4">Follow Us</h4>
                        <div class="flex space-x-4">
                            <a href="#"
                                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-orange-100">
                                <i class="bi bi-facebook text-gray-600"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-blue-100">
                                <i class="bi bi-twitter text-gray-600"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-pink-100">
                                <i class="bi bi-instagram text-gray-600"></i>
                            </a>
                            <a href="https://www.youtube.com/@SMARTSAVE24" target="_blank"
                                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-red-100">
                                <i class="bi bi-youtube text-gray-600"></i>
                            </a>
                            <a href="https://wa.me/918278273737" target="_blank"
                                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-green-100">
                                <i class="bi bi-whatsapp text-gray-600"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-2xl font-bold mb-6">Send Message</h3>
                    <form class="space-y-4" action="{{ route('contact.send') }}" method="POST">
                        {{-- 1. CSRF Token added --}}
                        @csrf

                        <div>
                            {{-- 2. Added name="name" --}}
                            <input type="text" name="name" placeholder="Your Name"
                                value="{{ old('name') }}" required
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            @error('name')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            {{-- Added name="email" --}}
                            <input type="email" name="email" placeholder="Your Email"
                                value="{{ old('email') }}" required
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            @error('email')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            {{-- Added name="uphone" (exactly matching your controller) --}}
                            <input type="tel" name="uphone" placeholder="Phone Number"
                                value="{{ old('uphone') }}" required
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            @error('uphone')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            {{-- Added name="message" --}}
                            <textarea name="message" placeholder="Your Message" rows="4" required
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">{{ old('message') }}</textarea>
                            @error('message')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit"
                            class="bg-orange-500 text-white px-8 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                            Send Message
                        </button>
                    </form>

                    {{-- Success Message Display (Add this right above or below the form if you haven't already) --}}
                    @if (session('success'))
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 mt-4">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white pt-12 pb-4">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-full overflow-hidden">
                            <img src="{{ asset('images/smartsave.png') }}" alt=""
                                class="w-full h-full object-cover">
                        </div>
                        <span class="text-xl font-bold">Smart<span class="text-orange-400">Save24</span></span>
                    </div>
                    <p class="text-gray-400"> SmartSave24 is a non-profit organization dedicated to
                        Promoting health through organic food while creating financial freedom for millions of Indians.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#products" class="text-gray-400 hover:text-white">Products</a></li>
                        <li><a href="#achievers" class="text-gray-400 hover:text-white">Achievers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms & Conditions</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Refund Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Disclaimers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Download App</h4>
                    <div class="space-y-3">
                        <a href="SmartSave24-app1.apk"
                            class="block bg-black text-white px-4 py-3 rounded-lg hover:bg-gray-800">
                            <i class="bi bi-google-play mr-2"></i> Google Play
                        </a>
                        <a href="SmartSave24-app1.apk"
                            class="block bg-black text-white px-4 py-3 rounded-lg hover:bg-gray-800">
                            <i class="bi bi-apple mr-2"></i> App Store
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; {{ date('Y') }} <span class="text-orange-400"></span> SmartSave24. All rights reserved.
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    This is a network marketing opportunity. Earnings shown are examples and not guarantees. Success
                    depends on individual effort.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize Animate On Scroll
        AOS.init({
            duration: 800, // Animation duration in milliseconds
            once: true, // Whether animation should happen only once - while scrolling down
            offset: 100, // Offset (in px) from the original trigger point
        });
    </script>

    <script>
        // Mobile Menu Toggle & Auto-Close
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        // Toggle menu on button click
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            // Icon change (List to Cross)
            const icon = this.querySelector('i');
            if (mobileMenu.classList.contains('hidden')) {
                icon.classList.remove('bi-x-lg');
                icon.classList.add('bi-list');
            } else {
                icon.classList.remove('bi-list');
                icon.classList.add('bi-x-lg');
            }
        });

        // Close menu when a link is clicked
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                mobileMenuBtn.querySelector('i').classList.remove('bi-x-lg');
                mobileMenuBtn.querySelector('i').classList.add('bi-list');
            });
        });

        var vendorSwiper = new Swiper(".vendorSwiper", {
            loop: true,
            effect: "fade",
            autoplay: {
                delay: 4000,
                disableOnInteraction: false
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
        });

        // 2. Product Banner Swiper (UPDATED FOR CARDS)
        var productSwiper = new Swiper(".productBannerSwiper", {
            loop: true,
            spaceBetween: 15, // Add space between cards
            autoplay: {
                delay: 3500,
                disableOnInteraction: false
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 320px
                320: {
                    slidesPerView: 1,
                    spaceBetween: 20
                },
                // when window width is >= 640px
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                // when window width is >= 1024px
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
                // when window width is >= 1280px
                1280: {
                    slidesPerView: 4,
                    spaceBetween: 30
                }
            }
        });

        // 3. Achiever Swiper (Responsive Grid Slider)
        var achieverSwiper = new Swiper(".achieverSwiper", {
            loop: true,
            spaceBetween: 30,
            autoplay: {
                delay: 3000
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 20
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
                1280: {
                    slidesPerView: 4,
                    spaceBetween: 30
                },
            },
        });

        // Active Navigation Link
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (scrollY >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
    </script>

</body>

</html>
