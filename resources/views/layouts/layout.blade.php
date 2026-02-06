<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Food Vendor Admin Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-menu-item.active {
            background-color: rgba(59, 130, 246, 0.1);
            border-left: 4px solid #3b82f6;
            color: #3b82f6;
        }

        .sidebar-submenu {
            display: none;
        }

        .sidebar-submenu.active {
            display: block;
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
            /* IE & Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .sidebar-submenu a.active {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            font-weight: 500;
            border-radius: 6px;
        }

        /* Parent menu active (when submenu is active) */
        .menu-header.parent-active {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-left: 4px solid #3b82f6;
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

<body class="bg-gray-50">

    @php $user = Auth::user(); @endphp
    <!-- Mobile overlay for sidebar -->
    <div id="sidebar-overlay" class="lg:hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-72 bg-white border-r border-gray-200 flex flex-col shrink-0 transition-transform duration-300 lg:translate-x-0">
            <!-- Logo -->
            <div class="h-16 flex items-center px-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-cube text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800">AdminPanel</span>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="flex-1 overflow-y-auto no-scrollbar py-4 scroll-smooth">
                <div class="px-3 space-y-1.5">

                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <i class="bi bi-speedometer2 mr-3 text-lg"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.viewmember') }}"
                        class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <i class="bi bi-people mr-3 text-lg"></i>
                        <span class="font-medium">User/Member</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                        class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <i class="bi bi-box-seam mr-3 text-lg"></i>
                        <span class="font-medium">Vendor Products</span>
                    </a>

                    <a href="{{ route('admin.orders.index') }}"
                        class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <i class="bi bi-cart-check mr-3 text-lg text-gray-500"></i>
                        <span class="font-medium">View Orders</span>
                    </a>

                    @php
                        $adminId = Auth::guard('admin')->id();
                    @endphp

                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors cursor-pointer">
                            <div class="flex items-center">
                                <i class="bi bi-diagram-3 mr-3 text-lg"></i>
                                <span class="font-medium">Admin Network</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform"></i>
                        </div>
                        <div class="sidebar-submenu pl-4 mt-1 space-y-1">
                            <a href="{{ route('admin.user.tree', $adminId) }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-tree mr-1.5"></i> User Tree
                            </a>
                            <a href="{{ route('admin.network.summary') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-grid-3x3 mr-1.5"></i> Network Summary
                            </a>
                        </div>
                    </div>

                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors cursor-pointer">
                            <div class="flex items-center">
                                <i class="bi bi-wallet2 mr-3 text-lg"></i>
                                <span class="font-medium">Wallet Management</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform"></i>
                        </div>
                        <div class="sidebar-submenu pl-4 mt-1 space-y-1">
                            <a href="{{ route('admin.wallet') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-arrow-left-right mr-1.5"></i> Transfer Money
                            </a>
                            <a href="{{ route('admin.withdrawls.index') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-cash-stack mr-1.5"></i> Withdrawal Requests
                            </a>
                            <a href="{{ route('admin.wallet-transactions') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-list-check mr-1.5"></i> All Transactions
                            </a>
                        </div>
                    </div>

                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors cursor-pointer">
                            <div class="flex items-center">
                                <i class="bi bi-gift mr-3 text-lg"></i>
                                <span class="font-medium">Package Management</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform"></i>
                        </div>
                        <div class="sidebar-submenu pl-4 mt-1 space-y-1">
                            <a href="{{ route('admin.package') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-eye mr-1.5"></i> View Packages
                            </a>
                            {{-- <a href="{{ route('admin.packages.assign') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-person-plus mr-1.5"></i> Assign Package
                            </a>
                            <a href="{{ route('admin.package-purchases') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-bag-check mr-1.5"></i> View User Packages
                            </a> --}}
                        </div>
                    </div>

                    {{-- <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors cursor-pointer">
                            <div class="flex items-center">
                                <i class="bi bi-graph-up-arrow mr-3 text-lg"></i>
                                <span class="font-medium">Profit Distribution</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform"></i>
                        </div>
                        <div class="sidebar-submenu pl-4 mt-1 space-y-1">
                            <a href="{{ route('admin.profit.distribution') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-cash-coin mr-1.5"></i> Distribute Profit
                            </a>
                            <a href="{{ route('admin.view.monthlyDistribution') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-calendar-month mr-1.5"></i> Monthly Records
                            </a>
                            <a href="{{ route('admin.view.distribution') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-calendar-check mr-1.5"></i> Yearly Records
                            </a>
                        </div>
                    </div> --}}

                    {{-- <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors cursor-pointer">
                            <div class="flex items-center">
                                <i class="bi bi-boxes mr-3 text-lg"></i>
                                <span class="font-medium">Manage Stock</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform"></i>
                        </div>
                        <div class="sidebar-submenu pl-4 mt-1 space-y-1">
                            <a href="{{ route('admin.stock.form') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-arrow-right-circle mr-1.5"></i> Transfer Stock
                            </a>
                            <a href="{{ route('admin.sales.stock') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-journal-text mr-1.5"></i> Record Sales Stock
                            </a>
                            <a href="{{ route('admin.viewStock') }}"
                                class="block px-4 py-2 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-clipboard-data mr-1.5"></i> View User's Stock
                            </a>
                        </div>
                    </div> --}}

                    <div class="menu-group">
                        <div
                            class="menu-header flex items-center justify-between px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors cursor-pointer">
                            <div class="flex items-center">
                                <i class="bi bi-gear mr-3 text-lg"></i>
                                <span class="font-medium">Configure Incomes</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform"></i>
                        </div>
                        <div class="sidebar-submenu pl-4 mt-1 space-y-1">
                            <a href="{{ route('admin.percentage.index') }}"
                                class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-bar-chart-steps mr-3 text-lg text-gray-500"></i>
                                <span class="font-medium">Level Percentage</span>
                            </a>

                            <a href="{{ route('admin.income.index') }}"
                                class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-percent mr-3 text-lg text-gray-500"></i>
                                <span class="font-medium">Percentage Income</span>
                            </a>

                            <a href="{{ route('admin.rewards.index') }}"
                                class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="bi bi-trophy mr-3 text-lg text-gray-500"></i>
                                <span class="font-medium">Rewards Percentage</span>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('admin.complaints.index') }}"
                        class="sidebar-menu-item flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <i class="bi bi-headset mr-3 text-lg text-gray-500"></i>
                        <span class="font-medium">Complaints</span>
                    </a>

                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="px-4 py-2.5 border-t border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header
                class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0">
                <!-- Left side: Toggle sidebar button (for mobile) and page title -->
                <div class="flex items-center">
                    <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700 mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-800" id="page-title">Dashboard</h1>
                </div>

                <!-- Right side: Search and Profile -->
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Search..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <!-- Notifications -->
                    {{-- <button class="relative p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </button> --}}

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profile-dropdown-toggle" class="flex items-center space-x-3 focus:outline-none">
                            <div class="profile-pic-wrapper">
                                @php $user = Auth::user(); @endphp
                                @if ($user->profile_picture)
                                    <img src="{{ asset('storage/profile-pictures/' . basename($user->profile_picture)) }}"
                                        alt="Profile">
                                @else
                                    <img src="{{ asset('foodvendor-logo.png') }}" alt="profile" />
                                @endif
                                <span class="online-status"></span>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">Administrator</p>
                            </div>
                            <i id="profile-arrow"
                                class="fas fa-chevron-down text-gray-500 hidden md:block transition-transform duration-300"></i>
                        </button>

                        <!-- Profile Dropdown Menu -->
                        <div id="profile-dropdown-menu"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">
                            <a href="{{ route('admin.profile') }}"
                                class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-user-circle mr-3 text-gray-500"></i>
                                <span>Profile</span>
                            </a>
                            {{-- <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-cog mr-3 text-gray-500"></i>
                                <span>Settings</span>
                            </a>
                            <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-bell mr-3 text-gray-500"></i>
                                <span>Notifications</span>
                                <span
                                    class="ml-auto bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">5</span>
                            </a> --}}
                            <div class="border-t border-gray-200"></div>
                            <form action="{{ route('admin.logout') }}" method="POST" id="logout-form"
                                class="flex items-center">
                                @csrf
                                <button type="submit"
                                    class="dropdown-item text-danger px-4 py-3 text-gray-700 hover:bg-gray-50"
                                    style="padding: 0">
                                    <i class="fa fa-power-off me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto px-4 pt-2 bg-gray-50">

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
            const profileArrow = document.getElementById('profile-arrow'); // Arrow ko select kiya

            if (profileDropdownToggle) {
                profileDropdownToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileDropdownMenu.classList.toggle('hidden');

                    // Arrow ko rotate karne ke liye ye line add karein
                    profileArrow.classList.toggle('rotate-180');
                });
            }

            // Click outside logic mein bhi reset karna hoga
            document.addEventListener('click', (e) => {
                if (profileDropdownToggle && !profileDropdownToggle.contains(e.target) &&
                    profileDropdownMenu && !profileDropdownMenu.contains(e.target)) {

                    profileDropdownMenu.classList.add('hidden');
                    profileArrow.classList.remove(
                        'rotate-180'); // Bahar click ho to arrow wapas seedha ho jaye
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
                item.addEventListener('click', () => {

                    // Remove active class from all items
                    menuItems.forEach(otherItem => {
                        otherItem.classList.remove('active');
                    });

                    // Add active class
                    item.classList.add('active');

                    // Close sidebar on mobile
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

                    document.getElementById('page-title').textContent =
                        link.querySelector('span').textContent;
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
                    const icon = header.querySelector('.fa-chevron-down');

                    // 🔥 PARENT ACTIVE EFFECT
                    header.classList.add('parent-active');
                    icon.classList.add('rotate-180');

                    document.getElementById('page-title').textContent =
                        subLink.textContent.trim();
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
