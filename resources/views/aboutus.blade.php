<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - GeoKranti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2a288a;
            --secondary: #4b49ac;
            --accent: #ff6b35;
            --light: #f8f9ff;
            --dark: #212529;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }



        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--accent);
        }

        .section-title.center:after {
            left: 50%;
            transform: translateX(-50%);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
            color: var(--accent);
        }

        .message-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .cmd-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--light);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--secondary);
            border-color: var(--secondary);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(42, 40, 138, 0.2);
        }

        .nav-pills .nav-link.active {
            background: var(--primary);
            border-radius: 50px;
        }

        .nav-pills .nav-link {
            color: var(--dark);
            font-weight: 500;
            border-radius: 50px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .logo-container img {
            display: block;
            margin-right: 0;
        }

        .logo-container h1 {
            margin-left: 0;
            padding-left: 0;
        }
    </style>
    <style>
        .hero-section {
            background: linear-gradient(rgba(42, 40, 138, 0.85), rgba(42, 40, 138, 0.9)), url('https://images.unsplash.com/photo-1517486808906-6ca8b3f8e1c1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1740&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 120px 0 100px;
            color: white;
            position: relative;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .vision-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            height: 100%;
            border: none;
            position: relative;
        }

        .vision-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .vision-icon {
            width: 80px;
            height: 80px;
            background: var(--light);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .vision-card:hover .vision-icon {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        .highlight {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .highlight::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.05);
            transform: rotate(30deg);
        }

        .message-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: none;
            position: relative;
        }

        .message-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--accent));
        }

        .cmd-image {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--light);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-container img {
            display: block;
            transition: transform 0.3s ease;
        }

        .logo-container:hover img {
            transform: rotate(5deg);
        }

        .logo-container h1 {
            margin: 0;
            color: white;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .impact-section {
            position: relative;
            padding: 80px 0;
            background: url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80') fixed;
            background-size: cover;
            color: white;
        }

        .impact-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(42, 40, 138, 0.85);
        }

        blockquote {
            border-left: 4px solid var(--accent);
            padding-left: 20px;
            font-style: italic;
        }
    </style>
</head>

<body>

    <header class="w-100" id="header">
        <nav class="navbar navbar-dark bg-black p-2">
            <div class="container d-flex align-items-center justify-content-between">
                <div class="logo-container">
                    <a href="{{ '/' }}" class="p-0 m-0">
                        <img src="{{ asset('geokranti-white.jpg') }}"
                            alt="img" width="70px;" height="70px;" style="border-radius: 50%">
                    </a>
                    <a href="{{ '/' }}" class="m-0 h4 gradient_text text-decoration-none"
                        style="color: var(--secondary);">Geo Kranti</a>
                </div>

                <div class="d-none d-md-flex align-items-center gap-3">
                    <a href="/"
                        class="d-flex align-items-center gap-2 text-white text-decoration-none">
                        <img src="{{ asset('assetsfront/front_web/images/character.png') }}" alt="img"
                            class="img-fluid" width="20" height="20">
                        <span>Home</span>
                    </a>
                    <a href="{{ route('auth.login') }}"
                        class="d-flex align-items-center gap-2 text-white text-decoration-none">
                        <img src="{{ asset('assetsfront/front_web/images/download_app.png') }}" alt="img"
                            class="img-fluid" width="24" height="24">
                        <span>Login</span>
                    </a>
                    <a href="{{ route('auth.register') }}" class="btn btn_primary text-white">Create Account</a>
                </div>

                <button class="navbar-toggler text-white d-md-none border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>

        <div class="collapse d-md-none bg-black shadow-sm" id="mobileMenu">
            <div class="d-flex flex-column p-3 gap-2">
                <a href="/" class="text-white text-decoration-none p-2 rounded">
                    Home
                </a>

                <a href="{{ route('auth.login') }}" class="text-white text-decoration-none p-2 rounded">
                    Login
                </a>

                <a href="{{ route('auth.register') }}" class="btn btn_primary w-100 text-white mt-2">
                    Create Account
                </a>
            </div>
        </div>
    </header>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-3 fw-bold mb-4">About GeoKranti</h1>
                    <p class="lead mb-5">Building a healthier, wiser, and sustainably developed India through
                        heritage-rooted solutions</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Section -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="section-title center">Our Vision & Mission</h2>
                    <p class="lead">At GeoKranti, we are committed to nation-building through solutions that are
                        rooted in India's heritage and aligned with modern needs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Initiatives Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="section-title center">Our Initiatives</h2>
                    <p>We are turning vision into action through these transformative projects</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="vision-card p-4 text-center">
                        <div class="vision-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h4>Herbal Park Development</h4>
                        <p>Developing a 100-acre herbal park with integrated rainwater harvesting to nurture
                            biodiversity and water security.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="vision-card p-4 text-center">
                        <div class="vision-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h4>Vedic Education</h4>
                        <p>Delivering Vedic education blended with modern learning, so values and innovation grow
                            together.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="vision-card p-4 text-center">
                        <div class="vision-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h4>Self-Healing Practices</h4>
                        <p>Empowering communities with Yoga, Acupressure, and Ayurveda for accessible, preventive
                            healthcare.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="vision-card p-4 text-center">
                        <div class="vision-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <h4>Clean Energy Solutions</h4>
                        <p>Establishing large-scale Gobar Gas Plants to champion clean energy and rural self-reliance.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="vision-card p-4 text-center">
                        <div class="vision-icon">
                            <i class="fas fa-running"></i>
                        </div>
                        <h4>Sports Nutrition</h4>
                        <p>Nurturing sportspersons with organic nutrition and holistic lifestyle for enhanced
                            performance.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="vision-card p-4 text-center">
                        <div class="vision-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h4>People's Movement</h4>
                        <p>Creating a collective movement for building an India that is healthier, wiser, and
                            sustainably developed.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CMD Message Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="message-card p-4 p-md-5">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <img src="/dinesh.jpg" alt="CMD Dinesh Gaur" class="cmd-image">
                                <h4 class="mt-3 mb-0">Dinesh Gaur</h4>
                                <p class="text-muted">CMD, GeoKranti</p>
                            </div>
                            <div class="col-md-8">
                                <h3 class="section-title">Message from our CMD</h3>
                                <blockquote class="blockquote">
                                    "ऑर्गेनिक भारत, स्वस्थ भारत, शिक्षित भारत, विकसित भारत "
                                    <p class="font-italic">"Dear Fellow Citizens, At GeoKranti, our vision is clear and
                                        collective: We are committed to nation-building through solutions that are
                                        rooted in India's heritage and aligned with modern needs."</p>

                                    <p class="font-italic">"This is more than a program—it is a people's movement. I
                                        invite educators, farmers, healthcare practitioners, sportspersons, and
                                        conscious citizens to join hands with us. Together, let's build an India that is
                                        healthier, wiser, and sustainably developed."</p>

                                    <footer class="blockquote-footer mt-3">Jai Hind</footer>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="highlight">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="mb-4">Join the Movement</h2>
                    <p class="lead mb-5">Be part of India's transformation towards sustainable development and holistic
                        living</p>
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">Get Involved</a>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
