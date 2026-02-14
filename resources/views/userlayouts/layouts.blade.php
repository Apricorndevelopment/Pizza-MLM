<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Dashboard </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-menu-item.active {
            background-color: rgba(16, 185, 129, 0.15);
            border-left: 4px solid #10b981;
            color: #10b981;
        }

        .sidebar-menu-item.active i {
            color: #10b981;
        }

        .sidebar-submenu {
            display: none;
        }

        .sidebar-submenu.active {
            display: block;
        }

        .sidebar-submenu a.active {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            font-weight: 500;
        }

        .menu-header.parent-active {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border-left: 4px solid #10b981;
        }

        .menu-header.parent-active i:not(.bi-chevron-down) {
            color: #10b981;
        }

        .profile-pic-wrapper {
            position: relative;
            width: 40px;
            height: 40px;
        }

        .profile-pic-wrapper img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #10b981;
            object-fit: cover;
        }

        .online-status {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 10px;
            height: 10px;
            background: #10b981;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        /* Hide scrollbar but allow scrolling */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE & Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        /* Hide sidebar on mobile */
        @media (max-width: 1024px) {
            #sidebar {
                transform: translateX(-100%);
                position: fixed;
                height: 100vh;
                z-index: 50;
            }

            #sidebar.mobile-open {
                transform: translateX(0);
            }

            #sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }

            #sidebar-overlay.mobile-open {
                display: block;
            }
        }
    </style>
</head>

<body class="bg-gray-50">

    <div id="sidebar-overlay" class="lg:hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar"
            class="w-64 bg-white border-r border-gray-200 flex flex-col shrink-0 transition-transform duration-300 lg:translate-x-0">
            <div class="h-16 flex items-center px-4 border-b border-gray-200 bg-emerald-50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center">
                        <i class="bi bi-shop text-white text-xl"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800">ZiddiZone</span>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto no-scrollbar py-4 scroll-smooth">
                <div class="px-3 space-y-1">

                    <a href="{{ route('user.dashboard') }}"
                        class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <i class="bi bi-speedometer2 mr-3 text-lg"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('user.profile') }}"
                        class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <i class="bi bi-person-circle mr-3 text-lg"></i>
                        <span class="font-medium">My Profile</span>
                    </a>

                    {{-- Order --}}
                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors cursor-pointer">
                            <div class="flex items-center">
                                <i class="bi bi-bag-check mr-3 text-lg"></i>
                                <span class="font-medium">Orders</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform"></i>
                        </div>
                        <div class="sidebar-submenu pl-8 mt-1 space-y-1">
                            <a href="{{ route('user.shop.index') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-cart-plus mr-2"></i>
                                Order Products
                            </a>

                            <a href="{{ route('user.orders.index') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-card-list mr-2"></i>
                                View Orders
                            </a>
                        </div>
                    </div>

                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors cursor-pointer">
                            <div class="flex items-center">
                                <i class="bi bi-wallet2 mr-3 text-lg"></i>
                                <span class="font-medium">My Wallet</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform"></i>
                        </div>
                        <div class="sidebar-submenu pl-8 mt-1 space-y-1">
                            <a href="{{ route('user.viewwallet') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-cash-stack mr-2"></i>
                                Manage Wallet
                            </a>
                            <a href="{{ route('user.funds.create') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Add Money
                            </a>
                        </div>
                    </div>
                    
                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors cursor-pointer">
                            <div class="flex items-center">
                                <i class="bi bi-diagram-3 mr-3 text-lg"></i>
                                <span class="font-medium">My Network</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform"></i>
                        </div>
                        <div class="sidebar-submenu pl-6 mt-1 space-y-1">
                            <a href="{{ route('user.view.userTree') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-tree mr-2"></i>
                                Network Tree
                            </a>
                            <a href="{{ route('user.network.summary') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-clipboard-data mr-2"></i>
                                Network Summary
                            </a>
                            <a href="{{ route('user.direct.team') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-people mr-2"></i>
                                Direct Team
                            </a>
                        </div>
                    </div>

                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors cursor-pointer">
                            <div class="flex items-center">
                                <i class="bi bi-cash-coin mr-3 text-lg"></i>
                                <span class="font-medium">Incentives</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform"></i>
                        </div>
                        <div class="sidebar-submenu pl-6 mt-1 space-y-1">
                            <a href="{{ route('user.income.direct') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-person-check mr-2"></i>
                                Direct Income
                            </a>
                            <a href="{{ route('user.income.bonus') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-gift mr-2"></i>
                                Bonus Income
                            </a>
                            <a href="{{ route('user.income.level') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-bar-chart-steps mr-2"></i>
                                Level Income
                            </a>
                            <a href="{{ route('user.income.reward') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-trophy mr-2"></i>
                                Reward Income
                            </a>
                            <a href="{{ route('user.income.repurchase') }}"
                                class="flex items-center px-3 py-2 text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="bi bi-arrow-repeat mr-2"></i>
                                Repurchase Income
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('user.complaints.index') }}"
                        class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <i class="bi bi-headset mr-3 text-lg"></i>
                        <span class="font-medium">Need Help?</span>
                    </a>

                    <a href="{{ route('user.coupons.purchase') }}"
                        class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <i class="bi bi-ticket-perforated mr-3 text-lg"></i>
                        <span class="font-medium">Purchase Coupons</span>
                    </a>

                    <a href="{{ route('logout') }}"
                        class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <i class="bi bi-box-arrow-right mr-3 text-lg"></i>
                        <span class="font-medium">Logout</span>
                    </a>
                </div>
            </nav>

            <div class="p-3 border-t border-gray-200">
                <div class="flex items-center">
                    <div class="profile-pic-wrapper">
                        @if (Auth::user()->profile_picture)
                            <img src="{{ asset('storage/profile-pictures/' . basename(Auth::user()->profile_picture)) }}"
                                alt="Profile">
                        @else
                            <img src="{{ asset('images/ziddizone.jpeg') }}" alt="Profile">
                        @endif
                        <span class="online-status"></span>
                    </div>
                    <div class="ml-2">
                        <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-500">Member Since {{ Auth::user()->created_at->format('M Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header
                class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0">
                <div class="flex items-center">
                    <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700 mr-4">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-800" id="page-title">Dashboard</h1>
                </div>

                <div class="flex items-center space-x-4">

                    <div class="relative">
                        <button id="profile-dropdown-toggle" class="flex items-center space-x-3 focus:outline-none">
                            <div class="profile-pic-wrapper">
                                @if (Auth::user()->profile_picture)
                                    <img src="{{ asset('storage/profile-pictures/' . basename(Auth::user()->profile_picture)) }}"
                                        alt="Profile">
                                @else
                                    <img src="{{ asset('images/ziddizone.jpeg') }}" alt="profile" />
                                @endif
                                <span class="online-status"></span>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-500">Member</p>
                            </div>
                            <i id="profile-arrow"
                                class="bi bi-chevron-down text-gray-500 hidden md:block transition-transform duration-300"></i>
                        </button>

                        <div id="profile-dropdown-menu"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden">
                            <a href="{{ route('user.profile') }}"
                                class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                                <i class="bi bi-person-circle mr-3 text-gray-500"></i>
                                <span>Profile</span>
                            </a>
                            <a href="{{ route('user.viewwallet') }}"
                                class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                                <i class="bi bi-wallet2 mr-3 text-gray-500"></i>
                                <span>Wallet</span>
                            </a>
                            <div class="border-t border-gray-200 my-2"></div>
                            <a href="{{ route('logout') }}"
                                class="flex items-center w-full px-4 py-3 text-gray-700 hover:bg-gray-50">
                                <i class="bi bi-box-arrow-right mr-3 text-gray-500"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50">
                @section('container')
                @show
            </main>
        </div>
    </div>

    <script>
        // DOM ready function
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar for mobile
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('mobile-open');
                sidebarOverlay.classList.toggle('mobile-open');
            });

            // Close sidebar when clicking on overlay
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.remove('mobile-open');
            });

            // Toggle profile dropdown
            const profileDropdownToggle = document.getElementById('profile-dropdown-toggle');
            const profileDropdownMenu = document.getElementById('profile-dropdown-menu');
            const profileArrow = document.getElementById('profile-arrow'); 

            if (profileDropdownToggle) {
                profileDropdownToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileDropdownMenu.classList.toggle('hidden');
                    profileArrow.classList.toggle('rotate-180');
                });
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (profileDropdownToggle && !profileDropdownToggle.contains(e.target) && !
                    profileDropdownMenu.contains(e.target)) {
                    profileDropdownMenu.classList.add('hidden');
                    profileArrow.classList.remove('rotate-180'); 
                }
            });

            // Toggle submenus in sidebar
            const menuHeaders = document.querySelectorAll('.menu-header');

            menuHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    const submenu = header.nextElementSibling;
                    const icon = header.querySelector('.bi-chevron-down');

                    submenu.classList.toggle('active');
                    icon.classList.toggle('rotate-180');

                    // Close other submenus if needed
                    menuHeaders.forEach(otherHeader => {
                        if (otherHeader !== header) {
                            const otherSubmenu = otherHeader.nextElementSibling;
                            const otherIcon = otherHeader.querySelector('.bi-chevron-down');

                            if (otherSubmenu.classList.contains('active')) {
                                otherSubmenu.classList.remove('active');
                                otherIcon.classList.remove('rotate-180');
                            }
                        }
                    });
                });
            });

            // Set active menu item on click
            const menuItems = document.querySelectorAll('.sidebar-menu-item');

            menuItems.forEach(item => {
                item.addEventListener('click', (e) => {

                    // Remove active class from all items
                    menuItems.forEach(otherItem => {
                        otherItem.classList.remove('active');
                    });

                    // Add active class to clicked item
                    item.classList.add('active');

                    // Update page title
                    const pageTitle = item.querySelector('span').textContent;
                    document.getElementById('page-title').textContent = pageTitle;

                    // Close sidebar on mobile after clicking a menu item
                    if (window.innerWidth < 1024) {
                        sidebar.classList.remove('mobile-open');
                        sidebarOverlay.classList.remove('mobile-open');
                    }
                });
            });

            const currentUrl = window.location.href;

            /* Main menu active */
            document.querySelectorAll('.sidebar-menu-item').forEach(link => {
                if (link.href === currentUrl) {
                    link.classList.add('active');

                    document.getElementById('page-title').textContent = link.querySelector('span')
                        .textContent;
                }
            });

            /* Submenu + Parent active */
            document.querySelectorAll('.sidebar-submenu a').forEach(subLink => {
                if (subLink.href === currentUrl) {
                    // Submenu active
                    subLink.classList.add('active');

                    const submenu = subLink.closest('.sidebar-submenu');
                    submenu.classList.add('active');

                    const header = submenu.previousElementSibling;
                    const icon = header.querySelector('.bi-chevron-down');

                    // 🔥 PARENT ACTIVE EFFECT
                    header.classList.add('parent-active');
                    icon.classList.add('rotate-180');

                    document.getElementById('page-title').textContent = subLink.textContent.trim();
                }
            });

            // Close sidebar when window is resized to desktop size
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('mobile-open');
                }
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>