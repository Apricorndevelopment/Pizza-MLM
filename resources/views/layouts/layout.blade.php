<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Save24 Vendor Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-menu-item {
            transition: all 0.2s ease-in-out;
        }

        .sidebar-menu-item:hover,
        .menu-header:hover {
            background-color: #f3f4f6;
            /* gray-50 */
            color: #2563eb;
            /* blue-600 */
        }

        .sidebar-menu-item.active {
            background-color: #eff6ff;
            /* blue-50 */
            border-left: 4px solid #2563eb;
            color: #2563eb;
        }

        .menu-header.parent-active {
            background-color: #eff6ff;
            border-left: 4px solid #2563eb;
            color: #2563eb;
        }

        .sidebar-submenu {
            display: none;
        }

        .sidebar-submenu.active {
            display: block;
            animation: slideDown 0.3s ease-in-out;
        }

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

        /* Profile Picture & Online Badge */
        .profile-pic-wrapper {
            position: relative;
            width: 40px;
            height: 40px;
        }

        .profile-pic-wrapper img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #27ae60;
            object-fit: cover;
        }

        .online-status {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 10px;
            height: 10px;
            background: #2ecc71;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        /* Hide scrollbar but allow scrolling */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .sidebar-submenu a.active {
            background-color: #eff6ff;
            color: #2563eb;
            font-weight: 600;
            border-radius: 6px;
        }

        /* Hide sidebar by default on mobile */
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

<body class="bg-gray-50 text-gray-800">

    @php $user = Auth::user(); @endphp
    <div id="sidebar-overlay" class="lg:hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar"
            class="w-72 bg-white border-r border-gray-200 flex flex-col shrink-0 transition-transform duration-300 lg:translate-x-0 shadow-sm">
            <div class="h-16 flex items-center px-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <i class="fas fa-cube text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">AdminPanel</span>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto no-scrollbar py-6 px-3 scroll-smooth">
                <div class="space-y-1">

                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg group">
                        <i class="bi bi-grid-fill text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.viewmember') }}"
                        class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg group">
                        <i class="bi bi-people-fill text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">User Management</span>
                    </a>

                    <a href="{{ route('admin.orders.index') }}"
                        class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg group">
                        <i class="bi bi-cart-check-fill text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">All Orders</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                        class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg group">
                        <i class="bi bi-box-seam-fill text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">Vendor Products</span>
                    </a>

                    <a href="{{ route('admin.coupons.process_transfer') }}"
                        class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg group">
                        <i class="bi bi-arrow-left-right text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">Transfer Coupons</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Network & Funds</p>
                    </div>

                    @php
                        $adminId = Auth::guard('admin')->id();
                    @endphp

                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-600 rounded-lg cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-diagram-3-fill text-lg group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Network</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform duration-200"></i>
                        </div>
                        <div class="sidebar-submenu pl-10 mt-1 space-y-1">
                            <a href="{{ route('admin.user.tree', $adminId) }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                User Tree
                            </a>
                            <a href="{{ route('admin.network.summary') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Network Summary
                            </a>
                        </div>
                    </div>

                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-600 rounded-lg cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-wallet-fill text-lg group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Wallet & Funds</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform duration-200"></i>
                        </div>
                        <div class="sidebar-submenu pl-10 mt-1 space-y-1">
                            <a href="{{ route('admin.wallet') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Transfer Money
                            </a>
                            <a href="{{ route('admin.funds.index') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Funding Requests
                            </a>
                            <a href="{{ route('admin.withdrawls.index') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Withdrawal Requests
                            </a>
                            <a href="{{ route('admin.wallet-transactions') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Transactions Log
                            </a>
                        </div>
                    </div>

                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-600 rounded-lg cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-box2-heart-fill text-lg group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Packages</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform duration-200"></i>
                        </div>
                        <div class="sidebar-submenu pl-10 mt-1 space-y-1">
                            <a href="{{ route('admin.package') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Vendor Package
                            </a>
                            <a href="{{ route('admin.product-package') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Admin Products
                            </a>
                        </div>
                    </div>

                    <div class="pt-4 pb-2">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Settings & Content
                        </p>
                    </div>

                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-600 rounded-lg cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <i
                                    class="bi bi-currency-exchange text-lg group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Income Settings</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform duration-200"></i>
                        </div>
                        <div class="sidebar-submenu pl-10 mt-1 space-y-1">
                            <a href="{{ route('admin.percentage.index') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Level Percentage
                            </a>
                            <a href="{{ route('admin.income.index') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Income Distribution
                            </a>
                            <a href="{{ route('admin.rewards.index') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Rewards Settings
                            </a>
                        </div>
                    </div>

                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-600 rounded-lg cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-images text-lg group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Banners</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform duration-200"></i>
                        </div>
                        <div class="sidebar-submenu pl-10 mt-1 space-y-1">
                            <a href="{{ route('admin.vendor.banners') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Vendor Banners
                            </a>
                            <a href="{{ route('admin.product.banners') }}"
                                class="block px-3 py-2 text-sm text-gray-500 rounded-md hover:text-blue-600 hover:bg-blue-50">
                                Product Banners
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('admin.complaints.index') }}"
                        class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg group">
                        <i class="bi bi-headset text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">Complaints</span>
                    </a>

                    <a href="{{ route('admin.coupons.index') }}"
                        class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg group">
                        <i class="bi bi-ticket-perforated-fill text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">Coupon Packages</span>
                    </a>

                    <a href="{{ route('admin.slider.index') }}"
                        class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg group">
                        <i class="bi bi-sliders text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">Home Sliders</span>
                    </a>

                    <a href="{{ route('admin.media.index') }}"
                        class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg group">
                        <i class="bi bi-collection-play-fill text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">Media Library</span>
                    </a>

                    <a href="{{ route('admin.stats.index') }}"
                        class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg group">
                        <i class="bi bi-bar-chart-line-fill text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">Home Stats</span>
                    </a>

                    <a href="{{ route('admin.payment.settings') }}"
                        class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg group">
                        <i
                            class="bi bi-credit-card-2-front-fill text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">Payment Methods</span>
                    </a>

                </div>
            </nav>

            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-md">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Super Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header
                class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0 z-30">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-blue-600 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-lg font-bold text-gray-800 tracking-tight" id="page-title">Dashboard</h1>
                </div>

                <div class="flex items-center gap-5">
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Search..."
                            class="w-64 pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        <i class="bi bi-search absolute left-3.5 top-2.5 text-gray-400"></i>
                    </div>

                    <div class="relative">
                        <button id="profile-dropdown-toggle" class="flex items-center gap-3 focus:outline-none group">
                            <div class="relative">
                                @php $user = Auth::user(); @endphp
                                @if ($user->profile_picture)
                                    <img src="{{ asset('storage/profile-pictures/' . basename($user->profile_picture)) }}"
                                        class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm group-hover:border-blue-100 transition-colors">
                                @else
                                    <img src="{{ asset('images/smartsave.png') }}"
                                        class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
                                @endif
                                <span
                                    class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                            </div>
                            <div class="hidden md:block text-left">
                                <p
                                    class="text-sm font-semibold text-gray-700 group-hover:text-blue-600 transition-colors">
                                    {{ $user->name }}</p>
                                <p class="text-xs text-gray-500">Admin</p>
                            </div>
                            <i id="profile-arrow"
                                class="bi bi-chevron-down text-xs text-gray-400 group-hover:text-gray-600 transition-transform duration-300"></i>
                        </button>

                        <div id="profile-dropdown-menu"
                            class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 hidden py-2 animate-fade-in-up">
                            <div class="px-4 py-2 border-b border-gray-50 mb-1">
                                <p class="text-xs font-semibold text-gray-400 uppercase">Account</p>
                            </div>
                            <a href="{{ route('admin.profile') }}"
                                class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-person-circle mr-3 text-lg"></i> My Profile
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form action="{{ route('admin.logout') }}" method="POST" id="logout-form">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                    <i class="bi bi-box-arrow-right mr-3 text-lg"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto px-6 py-6 bg-gray-50 scroll-smooth">
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

            // Click outside logic
            document.addEventListener('click', (e) => {
                if (profileDropdownToggle && !profileDropdownToggle.contains(e.target) &&
                    profileDropdownMenu && !profileDropdownMenu.contains(e.target)) {

                    profileDropdownMenu.classList.add('hidden');
                    profileArrow.classList.remove('rotate-180');
                }
            });

            // Close dropdown when pressing Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && profileDropdownMenu && !profileDropdownMenu.classList.contains(
                        'hidden')) {
                    profileDropdownMenu.classList.add('hidden');
                }
            });

            // Toggle submenus in sidebar
            const menuHeaders = document.querySelectorAll('.menu-header');

            menuHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    const submenu = header.nextElementSibling;
                    const icon = header.querySelector('.bi-chevron-down');

                    // Close other submenus
                    menuHeaders.forEach(otherHeader => {
                        if (otherHeader !== header) {
                            const otherSubmenu = otherHeader.nextElementSibling;
                            const otherIcon = otherHeader.querySelector('.bi-chevron-down');

                            if (otherSubmenu.classList.contains('active')) {
                                otherSubmenu.classList.remove('active');
                                otherHeader.classList.remove('parent-active');
                                otherIcon.classList.remove('rotate-180');
                            }
                        }
                    });

                    header.classList.toggle('parent-active');
                    submenu.classList.toggle('active');
                    icon.classList.toggle('rotate-180');
                });
            });

            // Active State Logic
            const currentUrl = window.location.href;

            // Highlight regular links
            document.querySelectorAll('.sidebar-menu-item').forEach(link => {
                if (link.href === currentUrl) {
                    link.classList.add('active');
                    document.getElementById('page-title').textContent = link.querySelector('span')
                        .textContent;
                }
            });

            // Highlight Submenu items
            document.querySelectorAll('.sidebar-submenu a').forEach(link => {
                if (link.href === currentUrl) {
                    link.classList.add('text-blue-600', 'bg-blue-50',
                        'font-medium'); // Tailwind active styles

                    const submenu = link.closest('.sidebar-submenu');
                    submenu.classList.add('active');

                    const header = submenu.previousElementSibling;
                    header.classList.add('parent-active');
                    header.querySelector('.bi-chevron-down').classList.add('rotate-180');

                    document.getElementById('page-title').textContent = link.textContent.trim();
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

</body>

</html>
