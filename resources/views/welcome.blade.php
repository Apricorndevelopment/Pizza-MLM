<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSave24 | Healthy Food Network</title>
    <link rel="shortcut icon" href="{{ asset('images/smartsave.png') }}" type="image/x-icon">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&family=Noto+Serif+Devanagari:wght@700&display=swap"
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

        /* .hero-bg {
            background: linear-gradient(rgba(42, 67, 101, 0.9), rgba(42, 67, 101, 0.9)),
                url('https://images.unsplash.com/photo-1490818387583-1baba5e638af?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
        } */

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
            /* Slate 600 - Darker color for light background */
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
            height: 450px;
            object-fit: cover;
            border-radius: 15px;
        }

        @media (max-width: 768px) {
            .banner-img {
                height: 250px;
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
    </header>

    <section id="home" class="relative pt-12 pb-20 md:pt-16 md:pb-24 overflow-hidden bg-slate-50">

        <div class="absolute top-0 right-0 w-full h-full overflow-hidden z-0">
            <div
                class="absolute -top-24 -right-24 w-96 h-96 bg-orange-100 rounded-full mix-blend-multiply filter blur-[80px] opacity-70 animate-pulse">
            </div>
            <div class="absolute top-40 -left-24 w-80 h-80 bg-green-100 rounded-full mix-blend-multiply filter blur-[80px] opacity-70 animate-pulse"
                style="animation-delay: 2s;"></div>
            <div
                class="absolute bottom-0 right-1/4 w-72 h-72 bg-yellow-100 rounded-full mix-blend-multiply filter blur-[60px] opacity-50">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <div class="flex flex-col items-start text-left">

                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-green-100 shadow-sm mb-6">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-xs font-bold text-green-700 tracking-wider uppercase">India's #1 Direct
                            selling Network</span>
                    </div>

                    <h2 class="text-2xl md:text-3xl font-bold text-gray-500 mb-3 tracking-widest uppercase">
                        Welcome To
                    </h2>

                    <div class="relative mb-6">
                        <img src="{{ asset('images/smartsave1.png') }}"
                            class="relative w-[250px] sm:w-[320px] md:w-[400px] object-contain drop-shadow-xl"
                            alt="SmartSave24">
                    </div>

                    {{-- NEW SLOGAN SECTION --}}
                    <div class="mb-10 max-w-xl space-y-4">
                        <h3 class="text-xl md:text-[22px] text-gray-800 font-extrabold leading-snug font-poppins">
                            Smart Business for Smart People: <br class="hidden sm:block">
                            <span class="text-emerald-600">Shop</span> what you love,
                            <span class="text-orange-500">Eat</span> what you want,
                            <span class="text-teal-700">Earn</span> what you deserve!
                        </h3>

                        <div class="relative">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-gradient-to-b from-orange-400 to-emerald-500 rounded-full">
                            </div>
                            <p
                                class="text-lg md:text-[19px] text-gray-600 font-medium hindi-font leading-relaxed pl-5 py-1 bg-gradient-to-r from-gray-50 to-transparent rounded-r-xl">
                                "स्मार्ट लोगों के लिए स्मार्ट बिजनेस: वह खरीदें जो आपको पसंद हो, वह खाएं जो आप चाहते
                                हैं, वह कमाएं जिसके आप हकदार हैं।"
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('auth.register') }}"
                            class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-full font-bold text-lg shadow-[0_10px_25px_rgba(16,185,129,0.3)] hover:shadow-[0_15px_35px_rgba(16,185,129,0.4)] hover:-translate-y-1 transition-all flex items-center">
                            Start Earning Now <i class="bi bi-rocket-takeoff ml-2"></i>
                        </a>
                    </div>
                </div>

                <div class="relative hidden lg:flex justify-center items-center h-full">

                    <div
                        class="relative bg-white/70 border border-white p-10 rounded-[2.5rem] backdrop-blur-xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] w-[85%] text-center z-10 animate-float transition-all hover:shadow-[0_20px_60px_rgba(0,0,0,0.08)]">
                        <div
                            class="w-24 h-24 mx-auto bg-gradient-to-br from-green-100 to-emerald-50 rounded-2xl rotate-12 flex items-center justify-center mb-8 shadow-sm border border-green-200">
                            <i class="bi bi-graph-up-arrow text-5xl text-green-600 -rotate-12"></i>
                        </div>
                        <h3 class="text-3xl font-extrabold text-gray-800 mb-4 tracking-tight">Smart Investment</h3>
                        <p class="text-gray-600 text-base leading-relaxed font-medium">
                            "अब खाना और खरीदना ही एक इन्वेस्टमेंट है।"<br>
                            <span class="text-sm font-normal text-gray-500 mt-3 block">Grow your network, increase your
                                wealth, and secure your financial future today.</span>
                        </p>
                    </div>

                    <div
                        class="absolute -left-16 top-16 bg-white border border-gray-100 p-4 rounded-2xl shadow-xl z-20 animate-float-delayed">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center text-2xl shadow-sm border border-orange-100">
                                🍔</div>
                            <div class="text-left">
                                <p class="text-gray-800 font-bold text-sm leading-tight">Top Vendors</p>
                                <p class="text-orange-500 text-xs font-semibold">Partnered</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="absolute -right-6 bottom-16 bg-white border border-gray-100 p-4 rounded-2xl shadow-xl z-20 animate-float">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-2xl shadow-sm border border-green-100 text-green-600">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <div class="text-left">
                                <p class="text-gray-800 font-bold text-sm leading-tight">Daily Income</p>
                                <p class="text-green-600 text-xs font-semibold">Guaranteed</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <section class="py-12" style="background: var(--light);">
        <div class="container mx-auto px-4">

            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-2 hindi-font"
                    style="color: var(--primary);">
                    "ज़िद है तो जीत है"
                </h2>
                {{-- <p class="text-xl md:text-2xl text-gray-600 font-medium hindi-font mt-3">
                    "अब खाना और खरीदना ही एक इन्वेस्टमेंट है"
                </p> --}}
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @forelse($stats as $stat)
                    <div class="stats-card">
                        @if ($stat->icon)
                            <div class="text-3xl text-orange-300 mb-2"><i class="{{ $stat->icon }}"></i></div>
                        @endif
                        <div class="text-3xl font-bold mb-2">{{ $stat->value }}</div>
                        <div class="text-gray-300 text-sm">{{ $stat->title }}</div>
                    </div>
                @empty
                    <div class="stats-card">
                        <div class="text-3xl font-bold mb-2">10K+</div>
                        <div class="text-gray-300">Active Members</div>
                    </div>
                    <div class="stats-card">
                        <div class="text-3xl font-bold mb-2">60+</div>
                        <div class="text-gray-300">Products</div>
                    </div>
                    <div class="stats-card">
                        <div class="text-3xl font-bold mb-2">₹80Lakh+</div>
                        <div class="text-gray-300">Paid Commissions</div>
                    </div>
                    <div class="stats-card">
                        <div class="text-3xl font-bold mb-2">50+</div>
                        <div class="text-gray-300">Cities Covered</div>
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
            <div class="text-center mb-16 max-w-4xl mx-auto">
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

                <div
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
                            class="text-emerald-600 font-bold">Income</span> है! हर ख़रीदारी पर मिलेगा बेनिफ़िट:
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
                                <i class="bi bi-cup-hot-fill text-emerald-500 text-lg"></i>
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

                <div
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
                                <p class="text-sm text-gray-600 hindi-font mt-1">अपने बिज़नेस को Direct Selling के
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
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">SmartSave24 Vendors</h2>
                {{-- <p class="text-gray-500">Exclusive Offers from Top Vendors</p> --}}
            </div>

            <div class="swiper vendorSwiper pb-10">
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

    <section id="products" class="py-10 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Featured Offers</h2>
                <p class="text-gray-500">Unbeatable Deals on Our Best Products</p>
            </div>

            <div class="swiper productBannerSwiper pb-10">
                <div class="swiper-wrapper">
                    @forelse($productBanners as $pBanner)
                        <div class="swiper-slide">
                            <div class="rounded-xl overflow-hidden shadow-lg relative group h-[350px] md:h-[450px]">
                                <img src="{{ asset($pBanner->banner_image) }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    alt="{{ $pBanner->title }}">
                                @if ($pBanner->title)
                                    <div
                                        class="absolute bottom-0 left-0 right-0 bg-linear-to-t from-black/80 via-black/40 to-transparent p-6 text-white">
                                        <h3 class="text-2xl font-bold mb-2">{{ $pBanner->title }}</h3>
                                        @if ($pBanner->link)
                                            <a href="{{ $pBanner->link }}"
                                                class="inline-block bg-white text-gray-900 px-6 py-2 rounded-full font-bold text-sm hover:bg-orange-100 transition">Shop
                                                Now</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center w-full text-gray-400 py-10">No product offers available.</div>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section id="achievers" class="py-12 md:py-18" style="background: #F8FFEE;">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <span class="food-badge px-4 py-1 rounded-full text-sm font-semibold mb-4 inline-block">
                    Wall of Fame
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">Our Top Achievers</h2>
                <p class="text-lg text-gray-600">Celebrating the leaders who inspire us all.</p>
            </div>

            <div class="swiper achieverSwiper pb-12 px-4">
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

    {{-- <section id="testimonials" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="food-badge px-4 py-1 rounded-full text-sm font-semibold mb-4 inline-block">
                    Success Stories
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">
                    Hear From Our Members
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="testimonial-card shadow-lg">
                    <div class="flex items-center mb-6">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Testimonial"
                            class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold">Priya Sharma</h4>
                            <p class="text-gray-500 text-sm">Delhi • 2 years with </p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic mb-4">
                        "SmartSave24 changed my life! I started part-time and now earn more than my corporate job."
                    </p>
                    <div class="text-yellow-500">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                            class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                            class="bi bi-star-fill"></i>
                    </div>
                </div>

                <div class="testimonial-card shadow-lg">
                    <div class="flex items-center mb-6">
                        <img src="https://randomuser.me/api/portraits/men/54.jpg" alt="Testimonial"
                            class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold">Rajesh Kumar</h4>
                            <p class="text-gray-500 text-sm">Bangalore • 3 years with </p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic mb-4">
                        "As a retired teacher, I found a new purpose. Earning ₹50,000+ monthly while promoting healthy
                        products."
                    </p>
                    <div class="text-yellow-500">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                            class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                            class="bi bi-star-half"></i>
                    </div>
                </div>

                <div class="testimonial-card shadow-lg">
                    <div class="flex items-center mb-6">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Testimonial"
                            class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold">Anjali Mehta</h4>
                            <p class="text-gray-500 text-sm">Mumbai • 1 year with </p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic mb-4">
                        "Started with zero experience. The training helped me build a team of 100+ members!"
                    </p>
                    <div class="text-yellow-500">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                            class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                            class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <section class="py-16 md:py-24" style="background: linear-gradient(135deg, var(--dark), #374D7A);">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Ready to Start Your Journey?
            </h2>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto">
                Join thousands of successful entrepreneurs who are building wealth while promoting health.
            </p>

            <div class="flex flex-col md:flex-row gap-6 justify-center items-center">
                <a href="{{ route('auth.register') }}"
                    class="bg-orange-500 text-white px-10 py-4 rounded-full font-bold text-lg hover:shadow-2xl transition-all inline-flex items-center text-center">
                    <i class="bi bi-rocket-takeoff mr-3"></i>
                    Start Free Registration
                </a>

                <a href="#contact"
                    class="bg-white text-gray-800 px-10 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition-all inline-flex items-center">
                    <i class="bi bi-headset mr-3"></i>
                    Talk to Our Team
                </a>
            </div>

            <div class="mt-12 grid md:grid-cols-3 gap-8 text-white">
                <div class="flex items-center justify-center">
                    <i class="bi bi-shield-check text-3xl text-green-400 mr-4"></i>
                    <div>
                        <div class="font-bold">100% Legal</div>
                        <div class="text-sm text-gray-300">Registered Company</div>
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <i class="bi bi-cash-coin text-3xl text-yellow-400 mr-4"></i>
                    <div>
                        <div class="font-bold">Instant Payouts</div>
                        <div class="text-sm text-gray-300">Daily Withdrawals</div>
                    </div>
                </div>
                <div class="flex items-center justify-center">
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
            <div class="text-center mb-16">
                <span class="food-badge px-4 py-1 rounded-full text-sm font-semibold mb-4 inline-block">
                    Get In Touch
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">Contact</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-2xl font-bold mb-6">Corporate Office</h3>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <i class="bi bi-geo-alt text-2xl text-orange-500 mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-bold mb-1">Head Office</h4>
                                <p class="text-gray-600">Main GT Road, V.P.O Rai <br>Sonipat</p>
                            </div>
                        </div>
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
                                <p class="text-gray-600">support@foodvendor.com<br>careers@foodvendor.com</p>
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

                <div>
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

    <footer class="bg-gray-900 text-white py-12">
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
                    <p class="text-gray-400">
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
                        <a href="SmartSave24-app.apk"
                            class="block bg-black text-white px-4 py-3 rounded-lg hover:bg-gray-800">
                            <i class="bi bi-google-play mr-2"></i> Google Play
                        </a>
                        <a href="SmartSave24-app.apk"
                            class="block bg-black text-white px-4 py-3 rounded-lg hover:bg-gray-800">
                            <i class="bi bi-apple mr-2"></i> App Store
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; {{ date('Y') }} SmartSave24. All rights reserved. |
                    <span class="text-orange-400">Designed & Developed By <a class="text-blue-600"
                            href="https://apricornsolutions.com/">Apricorn Solutions</a></span>
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    This is a network marketing opportunity. Earnings shown are examples and not guarantees. Success
                    depends on individual effort.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
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

        // 2. Product Banner Swiper (Middle - Full Width Effect)
        var productSwiper = new Swiper(".productBannerSwiper", {
            loop: true,
            effect: "fade", // Changed to fade for full-width impact like vendors
            autoplay: {
                delay: 3500,
                disableOnInteraction: false
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
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
