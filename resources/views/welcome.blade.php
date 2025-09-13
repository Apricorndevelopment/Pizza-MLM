<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from thesoilverse.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 25 Jul 2025 04:44:45 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geo Kranti | Virtual Real Estate</title>
    <link rel="shortcut icon" href="{{ asset('geokrantilogo-removebg.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assetsfront/front_web/fonts/font.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsfront/front_web/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsfront/front_web/css/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsfront/front_web/css/aos.css') }}"
        media='screen and (min-width: 576px)'>
    <link rel="stylesheet" href="{{ asset('assetsfront/site-assets.fontawesome.com/releases/v6.7.1/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsfront/front_web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsfront/front_web/css/responsive.css') }}">
    {{-- <link href="{{ asset('assetsfront/cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel='stylesheet' href="cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        var base_url = "index.html";
        var bscScan_url = "https://bscscan.com/";
    </script>
</head>

<body class="p-0">

    <style>
        .form-group.has-error small {
            color: red;
        }

        .btn.disabled,
        .btn:disabled,
        fieldset:disabled .btn {
            background: #4031D2;
            color: #fff;
        }

        #loaderCall {
            opacity: 999;
            -webkit-transition: all 0.3s ease-out;
            transition: all 0.3s ease-out;
            -moz-transition: all 0.3s ease-out;
            -ms-transition: all 0.3s ease-out;
            -o-transition: all 0.3s ease-out;
        }

        #loaderCall {
            left: 30%;
            position: relative;
            /* height: 100vh; */
            width: 200px;
            height: 200px;
            z-index: 1001;
            text-align: center;
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
        }

        #loaderCall:before {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #BE2F92;
            -webkit-animation: spin 3s linear infinite;
            animation: spin 3s linear infinite;
        }

        #loaderCall:after {
            content: "";
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #F2F2F3;
            -webkit-animation: spin 1.5s linear infinite;
            animation: spin 1.5s linear infinite;
        }
    </style>
    <div id="showLoaderAjax" class="loader-overlay" style="display: none;">
        <div class="loader-content loader-center text-center">

            <div id="loaderCall"> <img
                    src="{{ asset('assetsfront/front_web/images/geokranti-dark-removebg-preview.png') }}"
                    class="img-fluid" alt="loader-logo" width="145" height="145"></div>
            <div class="loader-center loader-text">Do not close or reload the page</div>
        </div>
    </div>
    <!-- =========== loader =========== -->
    <div id="loader-wrapper" class="page-loading">
        <div id="preloader">
            <div id="loader">
                <img src="{{ asset('assetsfront/front_web/images/geokranti-dark-removebg-preview.png') }}"
                    class="img-fluid" alt="loader-logo" width="165" height="165">
            </div>
        </div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <style>
        .text-purple {
            color: #6f42c1;
        }

        .card {
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        #load-more {
            transition: all 0.3s ease;
        }

        #load-more:hover {
            transform: scale(1.05);
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

    <header class="w-100" id="header">
        <nav class="navbar p-0">
            <div class="container d-flex align-items-center justify-content-between">

                <div class="logo-container">
                    <a href="{{ '/' }}" class="p-0 m-0">
                        <img src="{{ asset('assetsfront/front_web/images/geokranti-dark-removebg-preview.png') }}"
                            alt="img" width="79px;" height="79px;">
                    </a>
                    <a href="{{ '/' }}" class="m-0 h5 gradient_text">Geo Kranti</a>
                </div>

                <!-- Right Side: Login/Register -->
                <div class="d-flex align-items-center gap-sm-3 gap-2">
                    <a href="{{ route('aboutus') }}"
                        class="d-sm-flex d-none align-items-center gap-2 download_app_btn">
                        <img src="{{ asset('assetsfront/front_web/images/character.png') }}" alt="img"
                            class="img-fluid" width="20" height="20">
                        <span class="d-none d-md-block">About Us</span>
                    </a>
                    <a href="{{ route('auth.login') }}"
                        class="d-sm-flex d-none align-items-center gap-2 download_app_btn">
                        <img src="{{ asset('assetsfront/front_web/images/download_app.png') }}" alt="img"
                            class="img-fluid" width="24" height="24">
                        <span class="d-none d-md-block">Login</span>
                    </a>
                    <a href="{{ route('auth.register') }}" class="btn btn_primary text-white">Create
                        Account</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- =========== header section end =========== -->
    <!--========= TSV Celebrating Day Modal end ==========-->
    <style>
        #tsvCelebratingday .modal-body,
        #tsvAnnouncement .modal-body {
            padding: 0px !important;
        }

        #tsvCelebratingday .btn-close,
        #tsvAnnouncement .btn-close {
            position: absolute;
            right: 5px;
            top: 5px;
            z-index: 9999;
            color: #fff;
            background-color: #fff;
            padding: 4px;
            background-size: 10px;
        }
    </style>
    <!--========= TSV Popup Modal start ==========-->
    <!-- =========== banner section =========== -->
    <section class="main_banner_section position-relative">
        <div class="wgl-canvas-outer">
            <canvas id="wgl-webgl-fluid"></canvas>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-12 z-1">
                    <h6 class="text-white">The Future of Virtual Land Investment</h6>
                    <h1 class="gradient_text">Geo Kranti !</h1>
                    <p class="text-white py-2">
                        Step into the future of geospatial innovation with Geo Kranti, where technology empowers land
                        management and environmental awareness.
                        Our dynamic platform bridges digital solutions with real-world impact, making land data more
                        accessible, transparent, and community-focused for everyone.
                    </p>
                    <a class="btn btn-primary arrow_btn" href="{{ route('auth.register') }}">Get Started</a>
                </div>
                <div class="col-xl-6 col-lg-6 col-12 text-center">
                    <div class="banner_right_vector position-absolute">
                    </div>
                </div>
                <div class="col-12">
                    <ul
                        class="banner_sub_data flex-column flex-sm-row d-flex gap-md-4 gap-2 mt-sm-5 pt-5 text-white align-items-center justify-content-sm-start justify-content-center">
                        <li class="d-flex gap-2 align-items-center">
                            <h2 class="inter_font m-0 fs-4">200K+</h2>
                            Community Members
                        </li>
                        <li class="banner_content_seprator d-none d-sm-block">|</li>
                        <li class="d-flex gap-2 align-items-center">
                            <h2 class="inter_font m-0 fs-4">5+</h2>
                            Over Market Experience
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- =========== banner section end =========== -->

    <!-- =========== banner sub section =========== -->
    <section class="banner_sub_section common_section">
        <div class="container">
            <div class="row row-cols-lg-3 row-cols-1 gy-4 gy-lg-0 gx-xxl-5">
                <div class="col position-relative d-flex justify-content-between align-items-start gap-2"
                    data-aos="zoom-in" data-aos-duration="300">
                    <div>
                        <h3 class="text-white fw-light fs-5">Vision and Mission</h3>
                        <p class="m-0">
                            🌿 Vision Statement (Future-Oriented, Inspirational)
                            "To lead the world toward a healthier planet by making organic living accessible,
                            sustainable, and a natural choice for all."
                            <br>
                            <br class="d-none d-lg-block">
                            🌱 Mission Statement (Present-Focused, Action-Oriented)
                            "To provide high-quality, certified organic products that promote wellness,
                            protect the environment, and support ethical farming communities."
                            {{-- Help redefine how we manage natural resources with transparency, innovation, and impact. --}}
                        </p>

                    </div>
                    <img src="{{ asset('assetsfront/front_web/images/banner_sub_ing_1.png') }}" alt="img"
                        class="img-fluid" width="40" height="40">
                </div>
                <div class="col position-relative d-flex justify-content-between align-items-start gap-2"
                    data-aos="zoom-in" data-aos-duration="300">
                    <div>
                        <h4 class="text-white fw-light fs-5">BENEFITS OF ORGANIC FARMING
                        </h4>
                        <p class="m-0">
                            🌿 More Nutrients Available For Crops. NO Pollution, Improves Soil Properties. Contains PGR
                            And
                            Enzymes. Generates Employments .Better Use of Waste, Best for Enviornment, Earth, Human and
                            Animals
                            <br>
                            <br class="d-none d-lg-block">
                            🌱 Improves soil health through crop rotation, compost, and natural fertilizers. Reduces
                            pollution avoids chemical runoff into water bodies.
                        </p>

                    </div>
                    <img src="{{ asset('assetsfront/front_web/images/banner_sub_ing_2.png') }}" alt="img"
                        class="img-fluid" width="40" height="40">
                </div>
                <div class="col d-flex justify-content-between align-items-start gap-2" data-aos="zoom-in"
                    data-aos-duration="300">
                    <div>
                        <h5 class="text-white fw-light">Unique Selling Point</h5>
                        <p class="m-0">
                            🌿 Collaborate with change-makers to build a more informed and sustainable future. Eco
                            friendly, Highly Qualified Management, Noble vision & Mission, Promote Healthy life,
                            Promote chemical free farming
                            <br class="d-none d-lg-block">
                            <br>
                            🌱 Protect us from dangerous diseases, Helps in geological balance, Total Transparency,
                            Nominee facility & KYC, Land became organic Free of cost.


                        </p>

                    </div>
                    <img src="{{ asset('assetsfront/front_web/images/banner_sub_ing_3.png') }}" alt="img"
                        class="img-fluid" width="40" height="40">
                </div>
            </div>
        </div>
    </section>

    <section class="common_section" id="about">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3 class="color_purple">Empowering Agriculture through Organic Innovation</h3>
                    <h4 class="color_theme_dark fs-2">
                        Join Geo Kranti's mission to build a healthier planet through sustainable farming,<br>
                        ethical land practices, and inclusive community development.
                    </h4>

                    <ul class="nav nav-pills concept_section_tabs gap-4 mt-5" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation" data-aos="fade-right" data-aos-duration="200">
                            <button class="nav-link active" id="pills-vision-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-vision" type="button" role="tab"
                                aria-controls="pills-vision" aria-selected="true">
                                Our Silver Packege
                            </button>
                        </li>
                        <li class="nav-item" role="presentation" data-aos="fade-right" data-aos-duration="300">
                            <button class="nav-link" id="pills-benefits-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-benefits" type="button" role="tab"
                                aria-controls="pills-benefits" aria-selected="false">
                                Our Golden Packege
                            </button>
                        </li>
                        <li class="nav-item" role="presentation" data-aos="fade-right" data-aos-duration="400">
                            <button class="nav-link" id="pills-usp-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-usp" type="button" role="tab" aria-controls="pills-usp"
                                aria-selected="false">
                                Why Choose Us
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content concept_section_tabs_content mt-4 pt-2" id="pills-tabContent"
                        data-aos="fade-up" data-aos-duration="400">

                        <!-- Vision & Mission -->
                        <div class="tab-pane fade show active" id="pills-vision" role="tabpanel"
                            aria-labelledby="pills-vision-tab" tabindex="0">
                            <div class="card">
                                <div class="row">
                                    <div class="col-lg-6 col-12 slider_img_col slide_1"></div>
                                    <div class="col-lg-6 col-12 p-sm-5 p-4">
                                        <h4 class="color_theme_dark">SILVER PACKAGE - 100 Beds</h4>

                                        <p class="paragraph_purple_color fw-normal">
                                        <h5> REQUIREMENT- </h5>
                                        <div class="text" style="margin-left: 30px;">
                                            1. Amount-10 Lakhs.<br>
                                            2. 1 Acre Land.<br>
                                            3. Water Source & Covered Farm.<br>
                                            4. Accommodation For Workers.<br>
                                            5. Dung Availability.<br>
                                            6. Ground level must be High.<br>
                                            7. Boundary Security.
                                        </div>

                                        </p>

                                        <a class="btn btn_primary arrow_btn mt-3"
                                            href="{{ route('auth.login') }}">Join the Mission</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="pills-benefits" role="tabpanel"
                            aria-labelledby="pills-benefits-tab" tabindex="0">
                            <div class="card">
                                <div class="row">
                                    <div class="col-lg-6 col-12 slider_img_col slide_2"></div>
                                    <div class="col-lg-6 col-12 p-sm-5 p-4">
                                        <h4 class="color_theme_dark">Golden Packege - 200 Beds</h4>
                                        <p class="paragraph_purple_color fw-normal">
                                        <h5> REQUIREMENT- </h5>
                                        <div class="text" style="margin-left: 30px;">
                                            1. Amount-20 Lakhs.<br>
                                            2. 2 Acre Land.<br>
                                            3. Water Source & Covered Farm.<br>
                                            4. Accommodation For Workers.<br>
                                            5. Dung Availability.<br>
                                            6. Ground level must be High.<br>
                                            7. Boundary Security.
                                        </div>

                                        </p>
                                        <a class="btn btn_primary arrow_btn mt-3"
                                            href="{{ route('auth.login') }}">Join the Mission</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unique Selling Points -->
                        <div class="tab-pane fade" id="pills-usp" role="tabpanel" aria-labelledby="pills-usp-tab"
                            tabindex="0">
                            <div class="card">
                                <div class="row">
                                    <div class="col-lg-6 col-12 slider_img_col slide_3"></div>
                                    <div class="col-lg-6 col-12 p-sm-5 p-4">
                                        <h4 class="color_theme_dark">What Sets Geo Kranti Apart</h4>
                                        <ul class="paragraph_purple_color fw-normal pt-2">
                                            <li>Eco-friendly and sustainable approach</li>
                                            <li>Focus on chemical-free farming practices</li>
                                            <li>Supports healthy lifestyles and disease prevention</li>
                                            <li>Transparent operations with total clarity</li>
                                            <li>Qualified and ethical leadership team</li>
                                            <li>Land becomes organic at zero cost</li>
                                            <li>Nominee and KYC facilities for members</li>
                                            <li>Strong community support and employment generation</li>
                                        </ul>
                                        <a class="btn btn_primary arrow_btn mt-3" href="{{ route('auth.login') }}">Be
                                            a Changemaker</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- tab content -->
                </div>
            </div>
        </div>
    </section>

    <!-- =========== the concept section end =========== -->

    <!-- =========== real estate section =========== -->


    {{-- <section class="common_section real_estate_section position-relative overflow-hidden">
        <div class="wgl-canvas-outer">
            <canvas id="wgl-webgl-fluid_2"></canvas>
        </div>
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-6 col-12 position-relative z-1">
                    <h3 class="color_purple">Geo Kranti & Organic Agriculture</h3>
                    <h2 class="text-white">Unlocking Rural Prosperity</h2>
                    <p class="text-white pt-2">
                        Geo Kranti is transforming traditional farming with modern, sustainable, and chemical-free
                        methods.
                        We aim to create a healthier planet while opening doors for employment, rural empowerment, and
                        ethical land use — all through the power of organic farming.
                    </p>
                    <div class="card card_type_1 mt-sm-5" data-aos="fade-up" data-aos-duration="400">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="text-white fs-5">Sustainable & Transparent Farming</h3>
                            <img src="{{ asset('assetsfront/front_web/images/banner_sub_ing_1.png') }}"
                                alt="img" class="img-fluid" width="40" height="40">
                        </div>
                        <p class="text-white pt-2">
                            Say goodbye to chemical-laden farming. Geo Kranti promotes eco-friendly cultivation that
                            improves soil,
                            reduces pollution, and respects the planet. All while creating transparency in land
                            ownership and food production.
                        </p>
                    </div>
                    <div class="card card_type_1 mt-4" data-aos="fade-up" data-aos-duration="400">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="text-white fs-5">Income & Employment for All</h3>
                            <img src="{{ asset('assetsfront/front_web/images/banner_sub_ing_1.png') }}"
                                alt="img" class="img-fluid" width="40" height="40">
                        </div>
                        <p class="text-white pt-2">
                            Our organic farm initiatives generate rural jobs, support local economies, and offer income
                            through multi-level farming models. Whether you're a farmer, investor, or entrepreneur — Geo
                            Kranti is
                            your opportunity for lifelong financial freedom and social impact.
                        </p>
                    </div>
                </div>
                <div class="col-xl-6 col-12 d-flex align-items-end justify-content-center">
                    <div class="realestate_custom_outer position-relative">
                        <img src="{{ asset('assetsfront/front_web/images/organic-farming.jpg') }}" alt="img"
                            width="700" height="580" class="img-fluid" loading="lazy">
                        <div class="gradient_card"></div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <section class="common_section real_estate_section position-relative overflow-hidden">
        <div class="wgl-canvas-outer">
            <canvas id="wgl-webgl-fluid_2"></canvas>
        </div>
        <div class="container">
            <div class="row gy-4">
                <div class="col-12 position-relative z-1">
                    <h3 class="color_purple">Geo Kranti & Organic Agriculture</h3>
                    <h2 class="text-white">Unlocking Rural Prosperity</h2>
                    <p class="text-white pt-2">
                        Geo Kranti is transforming traditional farming with modern, sustainable, and chemical-free
                        methods.
                        We aim to create a healthier planet while opening doors for employment, rural empowerment, and
                        ethical land use — all through the power of organic farming.
                    </p>

                    <div class="row mt-5 gap-4 flex-md-nowrap flex-wrap">
                        <!-- Card 1 -->
                        <div class="col-md-6 col-12 card card_type_1" data-aos="fade-up" data-aos-duration="400">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="text-white fs-5">Sustainable & Transparent Farming</h3>
                            </div>
                            <p class="text-white pt-2">
                                Say goodbye to chemical-laden farming. Geo Kranti promotes eco-friendly cultivation that
                                improves soil,
                                reduces pollution, and respects the planet. All while creating transparency in land
                                ownership and food production.
                            </p>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-md-6 col-12 card card_type_1" data-aos="fade-up" data-aos-duration="400">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="text-white fs-5">Income & Employment for All</h3>
                            </div>
                            <p class="text-white pt-2">
                                Our organic farm initiatives generate rural jobs, support local economies, and offer
                                income
                                through multi-level farming models. Whether you're a farmer, investor, or entrepreneur —
                                Geo Kranti is
                                your opportunity for lifelong financial freedom and social impact.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <!-- =========== real estate section end =========== -->

    <!-- =========== investors section =========== -->
    <section class="common_section border-bottom">
        <div class="container">
            <div class="row row-cols-xl-2 row-cols-1">
                <div class="col" data-aos="fade-right" data-aos-duration="400">
                    <h3 class="color_purple">INVESTORS</h3>
                    <h2 class="color_theme_dark">
                        Real-world land innovation is<br> an emerging frontier for investors.
                    </h2>
                    <p class="fw-normal pt-2">
                        Geo Kranti opens up a new landscape for impact-driven investment — focusing on sustainable land
                        use, digital infrastructure, and geospatial solutions. As land data becomes more accessible and
                        transparent, opportunities for meaningful, long-term investment grow.
                    </p>
                    <p class="fw-normal">
                        From smart agriculture and rural digitization to environmental management, Geo Kranti invites
                        visionary investors to be part of a future-ready ecosystem that values real-world impact
                        alongside economic growth.
                    </p>
                    <a class="btn btn_primary arrow_btn mt-sm-3" href="{{ route('auth.login') }}">Get Started</a>
                </div>

                <div class="col position-relative d-none d-xl-block" data-aos="zoom-in" data-aos-duration="400">
                    <div class="phone_2_bg_div"></div>
                    <div class="phone_2"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- =========== investors section end =========== -->

    <!-- =========== explor lands section =========== -->
    <section class="common_section">
        <div class="container">
            <div class="row row-cols-1">
                <div class="col text-center">
                    <h3 class="color_purple">Explore Lands</h3>
                    <h2 class="color_theme_dark">
                        Discover Verified Land Opportunities to Build
                        <br class="d-none d-lg-block">
                        a Smarter and Sustainable Future!
                    </h2>
                    <div id="carouselExampleFade" class="carousel explore_lands_slider slide mt-5"
                        data-bs-ride="carousel">
                        <div class="carousel-inner">
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        <div class="carousel-indicators mt-3 m-0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =========== explor lands section end =========== -->

    <!-- =========== the future section =========== -->
    <section class="common_section the_future_section">
        <div class="container">
            <div class="row row-cols-lg-2 row-cols-1 gy-3">
                <div class="col">
                    <h3 class="color_purple">THE FUTURE</h3>
                    <h2 class="text-white">Transforming India Through Geo-Enabled Land Innovation</h2>
                    <p class="text-white position-relative z-2 pt-2">
                        Geo Kranti is not just a platform — it’s a movement toward redefining land governance,
                        empowering communities,
                        and bringing technological innovation to the grassroots. By integrating satellite mapping, GIS
                        intelligence,
                        and land record digitization, Geo Kranti aims to bring transparency and opportunity to every
                        corner of the nation.
                    </p>
                    <p class="text-white position-relative z-2">
                        In the near future, Geo Kranti will collaborate with government bodies, private developers, and
                        local communities
                        to build smart rural and urban landscapes. Our mission is to create a connected ecosystem where
                        land utilization
                        is data-driven, community-centered, and future-ready — empowering citizens and driving national
                        progress.
                    </p>

                    <a class="btn btn_primary arrow_btn my-3" href="login.html">Get Started</a>
                </div>
                <div
                    class="col d-flex flex-column justify-content-center align-items-lg-end align-items-center position-relative z-2 gap-4 pe-lg-5">
                    <img src="{{ asset('assetsfront/front_web/images/character.png') }}" alt="img"
                        class="img-fluid position-relative z-1 character" width="250" height="262"
                        loading="lazy">
                    <img src="{{ asset('assetsfront/front_web/images/character_bottom.png') }}" alt="img"
                        class="img-fluid position-relative z-1 character_bottom" width="300" height="96"
                        loading="lazy">
                    <img src="{{ asset('assetsfront/front_web/images/aeroplane_img.png') }}" alt="img"
                        class="img-fluid airplane" id="airplane_animate" width="150" height="177">
                </div>
            </div>
        </div>
    </section>
    <!-- =========== the future section end =========== -->
    <section class="container py-5">
        <div class="row">
            <div class="col text-center">
                <h3 class="text-purple">Gallery</h3>
                <h2 class="text-dark">
                    Explore Our Gallery
                </h2>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-2" id="gallery-container">
            @foreach ($photos->take(3) as $photo)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('storage/photos/' . basename($photo->photo)) }}"
                            alt="{{ $photo->title }}" class="card-img-top img-fluid"
                            style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $photo->title }}</h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($photos->count() > 3)
            <div class="row mt-5">
                <div class="col text-center">
                    {{-- Start with page=1 (first load after 3 photos) --}}
                    <button id="load-more" class="btn btn-primary px-4 py-2" data-page="1">
                        Show More <span class="ms-2">+</span>
                    </button>
                </div>
            </div>
        @endif
    </section>

    <!-- =========== get in touch section =========== -->
    <section class="common_section get_in_touch_section" id="contact">
        <div class="container">
            <div class="row row-cols-1">
                <div class="col">
                    <div class="card">
                        <div class="row row-cols-lg-2 row-cols-1 align-items-center">
                            <div class="col" data-aos="fade-right" data-aos-duration="400">
                                <h3 class="color_purple">GET IN TOUCH</h3>
                                <h2 class="color_theme_dark fw-semibold">
                                    Need help? Contact our support team for assistance.
                                </h2>
                                <p class="fw-normal fs-14 pe-md-5 fw-medium">
                                    We are here to provide you with the support you need. Feel free to reach out to us
                                    for any queries or concerns.
                                </p>
                                <h3 class="color_purple mt-lg-5 fs-4">We are always happy to help you!</h3>
                                <!-- <h4 class="color_theme_dark fw-semibold fs-5"><a href="cdn-cgi/l/email-protection.html"
                                        class="__cf_email__"
                                        data-cfemail="54272124243b262014203c31273b3d3822312627317a373b39">[email&#160;protected]</a>
                                </h4> -->
                                <h4 class="color_theme_dark fw-semibold fs-5">
                                    geokranti@gmail.com
                                </h4>
                            </div>
                            <div class="col">
                                {{-- <form class="row g-3 pt-md-0 pt-4" method="POST" id="contactform">
                                    <input name="csrf_name" value="" type="hidden">
                                    <div class="col-12 form-group">
                                        <input type="text" placeholder="Your name" class="form-control"
                                            id="name" name="name">
                                    </div>
                                    <div class="col-sm-6 col-12 form-group">
                                        <input type="email" placeholder="Your email" class="form-control"
                                            id="email" name="email">
                                    </div>
                                    <div class="col-sm-6 col-12 form-group">
                                        <input type="number" placeholder="Your phone" class="form-control"
                                            id="uphone" name="uphone">
                                    </div>
                                    <div class="col-12 form-group">
                                        <textarea placeholder="Your message" rows="5" class="form-control" id="message" name="message"></textarea>
                                    </div>
                                    <div class="col-12 form-group">
                                        <button type="submit" class="btn btn_primary">Send Message</button>
                                    </div>
                                </form> --}}
                                <form class="row g-3 pt-md-0 pt-4" method="POST"
                                    action="{{ route('contact.send') }}">
                                    @csrf
                                    <div class="col-12 form-group">
                                        <input type="text" placeholder="Your name" class="form-control"
                                            name="name" required>
                                    </div>
                                    <div class="col-sm-6 col-12 form-group">
                                        <input type="email" placeholder="Your email" class="form-control"
                                            name="email" required>
                                    </div>
                                    <div class="col-sm-6 col-12 form-group">
                                        <input type="number" placeholder="Your phone" class="form-control"
                                            name="uphone" required>
                                    </div>
                                    <div class="col-12 form-group">
                                        <textarea placeholder="Your message" rows="5" class="form-control" name="message" required></textarea>
                                    </div>
                                    <div class="col-12 form-group">
                                        <button type="submit" class="btn btn_primary">Send Message</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ Section --}}
   <section class="py-5" style="background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center mb-5">
                <span class="badge bg-primary rounded-pill px-4 py-2 mb-3">SUPPORT</span>
                <h2 class="display-5 fw-bold text-dark mb-3">Frequently Asked Questions</h2>
                <p class="lead text-muted">Get answers to common questions about GeoKranti website</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion custom-accordion" id="faqAccordion">
                    <!-- Vision -->
                    <div class="accordion-item shadow-lg mb-4 border-0 rounded-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq1">
                                <i class="fas fa-eye me-3 text-primary"></i>
                                What is the vision of GeoKranti?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                Our vision is "Organic Bharat, Svasth Bharat, Shikshit Bharat, Viksit Bharat", which
                                means building a healthier, educated, and developed India rooted in organic living
                                and sustainable growth.
                            </div>
                        </div>
                    </div>

                    <!-- Herbal Park -->
                    <div class="accordion-item shadow-lg mb-4 border-0 rounded-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq2">
                                <i class="fas fa-leaf me-3 text-primary"></i>
                                What is the purpose of the 100-acre herbal park?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                The herbal park will serve as a center for medicinal plant cultivation, biodiversity
                                conservation, and rainwater harvesting, promoting sustainability and natural
                                healthcare.
                            </div>
                        </div>
                    </div>

                    <!-- Education -->
                    <div class="accordion-item shadow-lg mb-4 border-0 rounded-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq3">
                                <i class="fas fa-graduation-cap me-3 text-primary"></i>
                                How does GeoKranti combine Vedic and modern education?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                We aim to blend ancient Vedic wisdom with modern learning systems so that students
                                grow with strong values, knowledge, and innovative skills for the future.
                            </div>
                        </div>
                    </div>

                    <!-- Wellness -->
                    <div class="accordion-item shadow-lg mb-4 border-0 rounded-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq4">
                                <i class="fas fa-spa me-3 text-primary"></i>
                                What role do Yoga, Acupressure, and Ayurveda play in your mission?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                These practices promote self-healing, preventive healthcare, and holistic
                                well-being, empowering individuals to take charge of their health naturally.
                            </div>
                        </div>
                    </div>

                    <!-- Energy -->
                    <div class="accordion-item shadow-lg mb-4 border-0 rounded-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq5">
                                <i class="fas fa-bolt me-3 text-primary"></i>
                                Why is GeoKranti setting up a Gobar Gas Plant?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                The plant will generate clean, renewable energy from organic waste, reducing
                                dependence on non-renewable resources and making rural communities more
                                self-reliant.
                            </div>
                        </div>
                    </div>

                    <!-- Sports -->
                    <div class="accordion-item shadow-lg mb-4 border-0 rounded-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq6">
                                <i class="fas fa-running me-3 text-primary"></i>
                                How is GeoKranti supporting sports and athletes?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                We provide organic food, holistic lifestyle practices, and natural health support to
                                nurture sportspersons, enhancing both their health and performance.
                            </div>
                        </div>
                    </div>

                    <!-- Membership -->
                    <div class="accordion-item shadow-lg mb-4 border-0 rounded-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq7">
                                <i class="fas fa-users me-3 text-primary"></i>
                                Who can join or support GeoKranti?
                            </button>
                        </h2>
                        <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                We welcome educators, farmers, healthcare practitioners, sportspersons, and
                                conscious citizens who believe in holistic living, sustainability, and
                                nation-building.
                            </div>
                        </div>
                    </div>

                    <!-- Nation Building -->
                    <div class="accordion-item shadow-lg mb-4 border-0 rounded-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq8">
                                <i class="fas fa-flag me-3 text-primary"></i>
                                How is GeoKranti contributing to nation-building?
                            </button>
                        </h2>
                        <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                By integrating organic farming, renewable energy, traditional wisdom, modern
                                education, and holistic health, we are creating a sustainable model that benefits
                                both society and the nation.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <style>
        .custom-accordion .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #4b49ac 0%, #2a288a 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(74, 73, 172, 0.3);
        }

        .custom-accordion .accordion-button:focus {
            border-color: #4b49ac;
            box-shadow: 0 0 0 0.25rem rgba(74, 73, 172, 0.25);
        }

        .custom-accordion .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%234b49ac'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        }

        .custom-accordion .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        }

        .accordion-body {
            font-size: 1.05rem;
            line-height: 1.6;
        }
    </style>

    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <script>
        window.onload = () => {

            $('.openNotification').first().modal('show');

            $(".closeModal").on('click', function(event) {
                $('.openNotification').modal('hide');
                var counter = $(this).data('length') + 1;
                $(`.openNotification:eq(${counter})`).modal('show');
            });

        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- =========== footer section =========== -->
    <footer>
        <div class="container">
            <div class="row pb-4 gy-4 gy-lg-0 fs-14 border-bottom position-relative z-1">
                <div class="col-lg-4 col-12">
                    <a href="{{ '/' }}">
                        <img src="{{ asset('assetsfront/front_web/images/geokranti-dark-removebg-preview.png') }}"
                            alt="img" class="iimg-fluid" style="width: 40%;
    height: 159px;">
                    </a>
                    <p class="pt-3 fw-light footer_main_para">
                        Embark on your journey today and unlock the boundless opportunities of virtual real estate with
                        TheSoilverse. Create, innovate, invest, and prosper within the ever-expanding metaverse.
                    </p>

                </div>

                <div class="col-md-4 col-12 mt-4">
                    <div class="ruf" style=" width: 50%; height: 169px;"></div>
                    <div class="w-max-content mx-auto">

                        <h4 class="text-white pb-sm-4 pb-2 fs-5">Contact Us</h4>
                        <div class="d-flex gap-4 align-items-start">
                            <img src="{{ asset('assetsfront/front_web/images/footer_address_icon.png') }}"
                                alt="img" class="img-fluid" width="20" height="18">
                            <span class="fw-light">Ganaur, Sonipat
                                <br>
                                Haryana, India
                            </span>
                        </div>
                        <div class="d-flex gap-4 align-items-start pt-3">
                            <img src="{{ asset('assetsfront/front_web/images/mail_icon.png') }}" alt="img"
                                class="img-fluid" width="20" height="20">
                            <span class="fw-light">
                                geokranti@gmail.com
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-cols-1 py-3">
                <div class="col text-center">
                    <small class="fw-light">&copy; 2025 Geo Kranti. All Rights Reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery (required for Bootstrap JavaScript components) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreButton = document.getElementById('load-more');

            if (loadMoreButton) {
                loadMoreButton.addEventListener('click', function() {
                    const page = parseInt(this.getAttribute('data-page'));
                    const nextPage = page + 1;

                    // Show loading spinner
                    this.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

                    // Build request URL
                    const url = new URL('{{ route('gallery.load-more') }}');
                    url.searchParams.append('page', page);

                    fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            if (data.html) {
                                const galleryContainer = document.getElementById('gallery-container');
                                galleryContainer.insertAdjacentHTML('beforeend', data.html);

                                // Update button page
                                loadMoreButton.setAttribute('data-page', nextPage);

                                if (!data.hasMore) {
                                    loadMoreButton.style.display = 'none';
                                }
                            }

                            this.innerHTML = 'Show More <span class="ms-2">+</span>';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error loading more photos');
                            this.innerHTML = 'Show More <span class="ms-2">+</span>';
                        });
                });
            }
        });
    </script>
    <!-- =========== footer section end =========== -->
    <!-- <script src="https://thesoilverse.com/assets/front_web/js/jquery.js" defer></script>  -->
    {{-- <script data-cfasync="false" src="cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script> --}}
    <script src="{{ asset('assetsfront/front_web/js/aos.js') }}" defer></script>
    <script src="{{ asset('assetsfront/front_web/js/script.js') }}" defer></script>
    <script src="{{ asset('assetsfront/front_web/js/loader.js') }}"></script>
    <!-- <script src="https://thesoilverse.com/assets/front_web/js/cursor-animation.js" defer></script> -->
    <script defer>
        $(document).ready(function() {
            function checkWidth() {
                if (screen.width > 575) {
                    // Dynamically load and execute the external script
                    $.getScript("assets/front_web/js/cursor-animation.js")
                        .done(function(script, textStatus) {})
                        .fail(function(jqxhr, settings, exception) {
                            console.error("Error loading the script.");
                        });
                }
            }
            checkWidth(); // Check on page load
        });
    </script>
    <script defer>
        var b = 0;

        function fly() {
            let oTop2 = $("#airplane_animate").offset().top - window.innerHeight;
            if (b == 0 && $(window).scrollTop() > oTop2) {
                let plane = document.getElementById('airplane_animate');
                plane.classList.add('fly')
                b = 1;
            }
        }
        $(window).scroll(function() {
            fly();
        });
        window.addEventListener("load", fly);
    </script>
    <script src="{{ asset('cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>
    <script src="{{ asset('cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js') }}">
    </script>
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.js"></script> -->
    <script src="http://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#contactform').bootstrapValidator({
                excluded: ':disabled',
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Please Enter Name'
                            }
                        }
                    },
                    uphone: {
                        validators: {
                            notEmpty: {
                                message: 'Please Enter Mobile No'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Please Enter Email'
                            },
                            //  regexp: {
                            //      regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            //      message: 'Please enter valid email format.'
                            //  }
                        }
                    },
                    message: {
                        validators: {
                            notEmpty: {
                                message: 'Please Enter Message'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();
                var form = $(e.target);
                var mydata = form.serialize();
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: base_url + "contactSubmit",
                    data: mydata,
                    success: function(data) {
                        if (data.status == 0) {
                            toastr.error(data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.success(data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function(xhr, textStatus) {
                        toastr.error('Something went wrong, try again later.');
                    }
                });
            });
            $('#newsform').bootstrapValidator({
                excluded: ':disabled',
                fields: {
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Please Enter Email'
                            },
                            regexp: {
                                regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                                message: 'Please enter valid email format.'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();
                var form = $(e.target);
                var mydata = form.serialize();
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: base_url + "subscriptionSubmit",
                    data: mydata,
                    success: function(data) {
                        if (data.status == 0) {
                            toastr.error(data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.success(data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function(xhr, textStatus) {
                        toastr.error('Something went wrong, try again later.');
                    }
                });
            });
        });
    </script>
    <script src="{{ asset('assetsfront/js/jquery-guard%401.12502.js?v=1.1.15') }}"></script>
</body>

<!-- Mirrored from thesoilverse.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 25 Jul 2025 04:45:07 GMT -->

</html>
