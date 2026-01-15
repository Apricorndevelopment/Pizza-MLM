<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodVendor | Healthy Food Network</title>
    <link rel="shortcut icon" href="{{ asset('foodvendor-logo.png') }}" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
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
        
        h1, h2, h3, h4, h5, .font-poppins {
            font-family: 'Poppins', sans-serif;
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
        
        .hero-bg {
            background: linear-gradient(rgba(42, 67, 101, 0.9), rgba(42, 67, 101, 0.9)), 
                        url('https://images.unsplash.com/photo-1490818387583-1baba5e638af?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
        }
        
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .food-badge {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
        }
        
        .nav-link {
            position: relative;
            padding: 8px 16px;
            color: white;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--accent);
        }
        
        .nav-link.active {
            color: var(--accent);
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 16px;
            right: 16px;
            height: 2px;
            background: var(--accent);
        }
        
        .section-title {
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }
        
        .section-title.center::after {
            left: 50%;
            transform: translateX(-50%);
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

    <!-- Header -->
    <header class="sticky top-0 z-50 shadow-md" style="background: var(--dark);">
        <nav class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="/">
                        <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white">
                            <img src="{{ asset('foodvendor-logo.png') }}" alt="FoodVendor" class="w-full h-full object-cover">
                        </div>
                    </a>
                    <a href="/" class="font-poppins text-xl font-bold text-white">
                        Food<span class="text-orange-400">Vendor</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#home" class="nav-link active">Home</a>
                    <a href="#about" class="nav-link">About</a>
                    <a href="#products" class="nav-link">Products</a>
                    <a href="#network" class="nav-link">Network</a>
                    <a href="#testimonials" class="nav-link">Testimonials</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('auth.login') }}" class="text-white hover:text-orange-300 transition">
                        <i class="bi bi-box-arrow-in-right mr-1"></i> Login
                    </a>
                    <a href="{{ route('auth.register') }}" 
                       class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-2 rounded-full font-semibold hover:shadow-lg transition-all">
                        Join Now
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="md:hidden text-white text-2xl">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="md:hidden hidden mt-4 mobile-menu">
                <div class="flex flex-col space-y-3 bg-white rounded-lg p-4 shadow-lg">
                    <a href="#home" class="text-gray-800 hover:text-orange-500 py-2 px-3 rounded">Home</a>
                    <a href="#about" class="text-gray-800 hover:text-orange-500 py-2 px-3 rounded">About</a>
                    <a href="#products" class="text-gray-800 hover:text-orange-500 py-2 px-3 rounded">Products</a>
                    <a href="#network" class="text-gray-800 hover:text-orange-500 py-2 px-3 rounded">Network</a>
                    <a href="#testimonials" class="text-gray-800 hover:text-orange-500 py-2 px-3 rounded">Testimonials</a>
                    <a href="#contact" class="text-gray-800 hover:text-orange-500 py-2 px-3 rounded">Contact</a>
                    <div class="border-t pt-3">
                        <a href="{{ route('auth.login') }}" class="block text-center text-gray-700 hover:text-orange-500 py-2">Login</a>
                        <a href="{{ route('auth.register') }}" class="block text-center bg-orange-500 text-white py-2 rounded-full mt-2">Join Now</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero-bg text-white py-14 md:py-18">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="animate__animated animate__fadeInLeft">
                    <span class="bg-orange-500 text-white px-4 py-1 rounded-full text-sm font-semibold mb-4 inline-block">
                        <i class="bi bi-star-fill mr-1"></i> Premium Quality
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                        Healthy Food,<br>
                        <span class="text-orange-300">Healthy Income</span>
                    </h1>
                    <p class="text-lg mb-8 text-gray-200">
                        Join India's fastest growing food network. Earn while promoting organic, healthy food products. 
                        Build your network and create sustainable income with FoodVendor.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('auth.register') }}" 
                           class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-3 rounded-full font-semibold text-lg hover:shadow-xl transition-all inline-flex items-center">
                            Start Earning Now
                            <i class="bi bi-arrow-right ml-2"></i>
                        </a>
                        <a href="#products" 
                           class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold text-lg hover:bg-white hover:text-gray-800 transition-all">
                            View Products
                        </a>
                    </div>
                </div>
                <div class="relative animate__animated animate__fadeInRight">
                    <div class="relative z-10 h-[600px]">
                        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                             alt="Organic Food" 
                             class="rounded-3xl shadow-2xl object-cover h-full w-full">
                    </div>
                    <div class="absolute -bottom-6 -left-6 w-64 h-64 bg-gradient-to-r from-green-400 to-orange-400 rounded-full opacity-20 blur-3xl"></div>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-16">
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
                    <div class="text-gray-300">Paid in Commissions</div>
                </div>
                <div class="stats-card">
                    <div class="text-3xl font-bold mb-2">50+</div>
                    <div class="text-gray-300">Cities Covered</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 md:py-24">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="food-badge px-4 py-1 rounded-full text-sm font-semibold mb-4 inline-block">
                    About FoodVendor
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">
                    Revolutionizing Food & Income
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    FoodVendor is more than a network marketing company. We are a movement towards healthier living 
                    and financial freedom through premium organic food products.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-heart-fill text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Health First</h3>
                    <p class="text-gray-600">Promote 100% natural, chemical-free food products that enhance health and wellness.</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-currency-dollar text-3xl text-orange-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Earn Unlimited</h3>
                    <p class="text-gray-600">Build your network and earn commissions on every sale. Multiple income streams available.</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-people-fill text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Community Support</h3>
                    <p class="text-gray-600">Join a supportive community with training, mentorship, and growth opportunities.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="food-badge px-4 py-1 rounded-full text-sm font-semibold mb-4 inline-block">
                    Featured Products
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">
                    Premium Organic Products
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Explore our range of certified organic products that promote health and wellness.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Product 1 -->
                <div class="food-card shadow-lg card-hover">
                    <div class="overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                             alt="Organic Honey" 
                             class="food-img w-full">
                        <div class="absolute top-4 left-4">
                            <span class="category-organic food-category">Organic</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Pure Wild Honey</h3>
                        <p class="text-gray-600 mb-4">100% pure honey collected from wild forests, rich in nutrients and antioxidants.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-orange-600">₹499</span>
                            <span class="text-green-600 font-semibold">25% Commission</span>
                        </div>
                    </div>
                </div>
                
                <!-- Product 2 -->
                <div class="food-card shadow-lg card-hover">
                    <div class="overflow-hidden">
                        <img src="/images/momos.jpg" 
                             alt="Organic Spices" 
                             class="food-img w-full">
                        <div class="absolute top-4 left-4">
                            <span class="category-premium food-category">Premium</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Organic Spices Pack</h3>
                        <p class="text-gray-600 mb-4">7 essential spices, organically grown and sun-dried for maximum flavor.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-orange-600">₹899</span>
                            <span class="text-green-600 font-semibold">30% Commission</span>
                        </div>
                    </div>
                </div>
                
                <!-- Product 3 -->
                <div class="food-card shadow-lg card-hover">
                    <div class="overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1615485290382-441e4d049cb5?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                             alt="Health Supplements" 
                             class="food-img w-full">
                        <div class="absolute top-4 left-4">
                            <span class="category-special food-category">Special</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Ayurvedic Supplements</h3>
                        <p class="text-gray-600 mb-4">Traditional ayurvedic formulas for immunity, digestion, and vitality.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-orange-600">₹1299</span>
                            <span class="text-green-600 font-semibold">35% Commission</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('auth.register') }}" 
                   class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-3 rounded-full font-semibold text-lg hover:shadow-xl transition-all inline-flex items-center">
                    View All Products
                    <i class="bi bi-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Network Plan -->
    <section id="network" class="py-16 md:py-24" style="background: linear-gradient(135deg, #F8FFEE 0%, #FFF4E6 100%);">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="food-badge px-4 py-1 rounded-full text-sm font-semibold mb-4 inline-block">
                    Earning Plan
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">
                    10-Level Income System
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Our transparent 10-level commission system ensures you earn from your entire network.
                </p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <div class="text-4xl font-bold text-orange-500 mb-2">Level 1</div>
                    <div class="text-2xl font-bold mb-2">15%</div>
                    <p class="text-gray-600">Direct Referrals</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <div class="text-4xl font-bold text-orange-500 mb-2">Level 2</div>
                    <div class="text-2xl font-bold mb-2">10%</div>
                    <p class="text-gray-600">Team Sales</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <div class="text-4xl font-bold text-orange-500 mb-2">Level 3-6</div>
                    <div class="text-2xl font-bold mb-2">8%</div>
                    <p class="text-gray-600">Network Growth</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <div class="text-4xl font-bold text-orange-500 mb-2">Level 7-10</div>
                    <div class="text-2xl font-bold mb-2">5%</div>
                    <p class="text-gray-600">Deep Network</p>
                </div>
            </div>
            
            <div class="mt-12 bg-white rounded-2xl shadow-xl p-8">
                <h3 class="text-2xl font-bold mb-6 text-center">Additional Income Streams</h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="text-center p-6 border rounded-xl">
                        <i class="bi bi-trophy text-4xl text-yellow-500 mb-4"></i>
                        <h4 class="text-xl font-bold mb-2">Leadership Bonus</h4>
                        <p class="text-gray-600">Earn additional bonuses as you build larger teams.</p>
                    </div>
                    
                    <div class="text-center p-6 border rounded-xl">
                        <i class="bi bi-gift text-4xl text-purple-500 mb-4"></i>
                        <h4 class="text-xl font-bold mb-2">Performance Rewards</h4>
                        <p class="text-gray-600">Monthly incentives and international trips for top performers.</p>
                    </div>
                    
                    <div class="text-center p-6 border rounded-xl">
                        <i class="bi bi-currency-rupee text-4xl text-green-500 mb-4"></i>
                        <h4 class="text-xl font-bold mb-2">Residual Income</h4>
                        <p class="text-gray-600">Passive income from repeat purchases of your network.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-16 md:py-24 bg-white">
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
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" 
                             alt="Testimonial" 
                             class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold">Priya Sharma</h4>
                            <p class="text-gray-500 text-sm">Delhi • 2 years with FoodVendor</p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic mb-4">
                        "FoodVendor changed my life! I started part-time and now earn more than my corporate job. 
                        The products are amazing and the income potential is unlimited."
                    </p>
                    <div class="text-yellow-500">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
                
                <div class="testimonial-card shadow-lg">
                    <div class="flex items-center mb-6">
                        <img src="https://randomuser.me/api/portraits/men/54.jpg" 
                             alt="Testimonial" 
                             class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold">Rajesh Kumar</h4>
                            <p class="text-gray-500 text-sm">Bangalore • 3 years with FoodVendor</p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic mb-4">
                        "As a retired teacher, I found a new purpose with FoodVendor. I'm earning ₹50,000+ monthly 
                        while promoting healthy products. Best decision of my life!"
                    </p>
                    <div class="text-yellow-500">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                </div>
                
                <div class="testimonial-card shadow-lg">
                    <div class="flex items-center mb-6">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" 
                             alt="Testimonial" 
                             class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold">Anjali Mehta</h4>
                            <p class="text-gray-500 text-sm">Mumbai • 1 year with FoodVendor</p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic mb-4">
                        "Started with zero network marketing experience. The training and support from FoodVendor 
                        helped me build a team of 100+ members in just 6 months!"
                    </p>
                    <div class="text-yellow-500">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-24" style="background: linear-gradient(135deg, var(--dark), #374D7A);">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Ready to Start Your FoodVendor Journey?
            </h2>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto">
                Join thousands of successful entrepreneurs who are building wealth while promoting health.
            </p>
            
            <div class="flex flex-col md:flex-row gap-6 justify-center items-center">
                <a href="{{ route('auth.register') }}" 
                   class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-10 py-4 rounded-full font-bold text-lg hover:shadow-2xl transition-all inline-flex items-center text-center">
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

    <!-- Contact Section -->
    <section id="contact" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="text-center mb-16">
                <span class="food-badge px-4 py-1 rounded-full text-sm font-semibold mb-4 inline-block">
                    Get In Touch
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">
                    Contact FoodVendor
                </h2>
            </div>
            
            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-2xl font-bold mb-6">Corporate Office</h3>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <i class="bi bi-geo-alt text-2xl text-orange-500 mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-bold mb-1">Head Office</h4>
                                <p class="text-gray-600">123 Food Plaza, Green City<br>New Delhi - 110001</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="bi bi-telephone text-2xl text-green-500 mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-bold mb-1">Phone Numbers</h4>
                                <p class="text-gray-600">+91 98765 xxxxx<br>+91 91234 xxxxx</p>
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
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-orange-100">
                                <i class="bi bi-facebook text-gray-600"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-blue-100">
                                <i class="bi bi-twitter text-gray-600"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-pink-100">
                                <i class="bi bi-instagram text-gray-600"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-red-100">
                                <i class="bi bi-youtube text-gray-600"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-2xl font-bold mb-6">Send Message</h3>
                    <form class="space-y-4">
                        <div>
                            <input type="text" placeholder="Your Name" 
                                   class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div>
                            <input type="email" placeholder="Your Email" 
                                   class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div>
                            <input type="tel" placeholder="Phone Number" 
                                   class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div>
                            <textarea placeholder="Your Message" rows="4"
                                      class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-full overflow-hidden">
                            <img src="{{ asset('foodvendor-logo.png') }}" alt="FoodVendor" class="w-full h-full object-cover">
                        </div>
                        <span class="text-xl font-bold">Food<span class="text-orange-400">Vendor</span></span>
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
                        <li><a href="#network" class="text-gray-400 hover:text-white">Income Plan</a></li>
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
                        <a href="#" class="block bg-black text-white px-4 py-3 rounded-lg hover:bg-gray-800">
                            <i class="bi bi-google-play mr-2"></i> Google Play
                        </a>
                        <a href="#" class="block bg-black text-white px-4 py-3 rounded-lg hover:bg-gray-800">
                            <i class="bi bi-apple mr-2"></i> App Store
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; 2026 FoodVendor. All rights reserved. | 
                    <span class="text-orange-400">Designed & Developed By <a class="text-blue-600" href="https://apricornsolutions.com/">Apricorn Solutions</a></span>
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    This is a network marketing opportunity. Earnings shown are examples and not guarantees. 
                    Success depends on individual effort and commitment.
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });
        
        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    document.getElementById('mobileMenu').classList.add('hidden');
                }
            });
        });
        
        // Active Navigation Link
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if(scrollY >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if(link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>