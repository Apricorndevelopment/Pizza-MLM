<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Geo Kranti User</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets2/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets2/js/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="{{ asset('assets2/images/favicon.png') }}" />

    <style>
        .sub-menu,
        .sub-menu li,
        .sub-menu li::before,
        .sub-menu li::after {
            list-style: none !important;
            content: none !important;
            margin: 0 !important;
        }

        .breadcrumb-wrapper {
            padding: 0;
        }

        .breadcrumb {
            padding: 0.1rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: ">";
            padding: 0 0.3rem;
        }

        .breadcrumb-item a:hover {
            color: #2a288a;
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: #495057;
            font-weight: 500;
        }

        .content-wrapper {
            padding: 1.5rem 1.7rem;
            width: 100%;
            flex-grow: 1;
        }

        /* Quick Actions Button */
        .quick-actions-btn {
            position: fixed;
            right: 30px;
            bottom: 30px;
            z-index: 1000;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(45deg, #4b49ac, #2a288a);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quick-actions-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        }

        .quick-actions-btn i {
            font-size: 1.5rem;
        }

        .quick-actions-menu {
            position: fixed;
            right: 30px;
            bottom: 100px;
            z-index: 999;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 15px 0;
            width: 250px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .quick-actions-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .quick-action-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            color: #495057;
            text-decoration: none;
        }

        .quick-action-item:hover {
            background-color: #f8f9fa;
            color: #4b49ac;
        }

        .quick-action-item i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 8px 0;
        }

        @media(max-width:768px) {
            .content-wrapper {
                padding: 1.2rem 1rem;
            }

            .quick-actions-btn {
                right: 20px;
                bottom: 20px;
                width: 50px;
                height: 50px;
            }

            .quick-actions-menu {
                right: 20px;
                bottom: 80px;
                width: 220px;
            }
        }

        @media(max-width:450px) {
            .content-wrapper {
                padding: 0.8rem 0.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div
            class="text-center navbar-brand-wrapper ps-0 ps-sm-1 ps-xl-2 d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo me-5 d-flex align-items-center" href="">
                <img src="{{ asset('geokrantilogo.jpg') }}" alt="logo"
                    style="width: 55px; height: 55px; object-fit: cover;" class="me-2" />
                <h3 class="mb-0">Geokranti</h3>
            </a>
            <a class="navbar-brand brand-logo-mini" href="">
                <img src="{{ asset('geokrantilogo.jpg') }}" alt="logo" />
            </a>
        </div>

        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <h3 class="mb-0 d-block d-lg-none" style="position: absolute;left:58px;">Geokranti</h3>
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
            <ul class="navbar-nav mr-lg-2">
                <li class="nav-item nav-search d-none d-lg-block">
                    <div class="input-group">
                        <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                            <span class="input-group-text" id="search">
                                <i class="icon-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now"
                            aria-label="search" aria-describedby="search">
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item dropdown">
                    <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                        data-bs-toggle="dropdown">
                        <i class="icon-bell mx-0"></i>
                        <span class="count"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                        aria-labelledby="notificationDropdown">
                        <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-success">
                                    <i class="ti-info-alt mx-0"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">Application Error</h6>
                                <p class="font-weight-light small-text mb-0 text-muted"> Just now </p>
                            </div>
                        </a>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-warning">
                                    <i class="ti-settings mx-0"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">Settings</h6>
                                <p class="font-weight-light small-text mb-0 text-muted"> Private message </p>
                            </div>
                        </a>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-info">
                                    <i class="ti-user mx-0"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">New user registration</h6>
                                <p class="font-weight-light small-text mb-0 text-muted"> 2 days ago </p>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="" data-bs-toggle="dropdown" id="profileDropdown">
                        <?php
                        use Illuminate\Support\Facades\Auth;
                        $user = Auth::user();
                        ?>
                        @if ($user->profile_picture)
                            <img src="{{ asset('storage/profile-pictures/' . basename($user->profile_picture)) }}" alt="Profile Picture">
                        @else
                            <img src="{{ asset('geokrantilogo.jpg') }}" alt="profile" />
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <a href="{{ route('user.profile') }}" class="dropdown-item">
                            <i class="ti-settings text-primary"></i> Profile </a>
                        <a class="dropdown-item" href="">
                            <i class="ti-power-off text-primary"></i>
                            <form action="{{ route('logout') }}" method="get">
                                <input type="submit" value="logout"
                                    style="border: none; background: none; color: inherit; cursor: pointer;">
                            </form>
                        </a>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
        </div>
    </nav>

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav" style="list-style: none; padding-left: 0;">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('user.dashboard') }}">
                        <i class="icon-grid menu-icon me-3"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

                <!-- Network Section -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#tables5"
                        aria-expanded="false" aria-controls="tables5">
                        <i class="fa fa-sitemap menu-icon me-3"></i>
                        <span class="menu-title flex-grow-1">Network</span>
                        <i class="menu-arrow fa fa-angle-down transition-all"></i>
                    </a>
                    <div class="collapse" id="tables5">
                        <ul class="nav flex-column sub-menu ps-3"
                            style="border-left: 2px solid #4b49ac; list-style: none;">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center"
                                    href="{{ route('user.view.userTree') }}">
                                    <i class="fa fa-sitemap me-2" style="font-size: 0.8rem;"></i>
                                    <span>Network Explorer</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center"
                                    href="{{ route('user.network.summary') }}">
                                    <i class="fa fa-network-wired me-2" style="font-size: 0.8rem;"></i>
                                    <span>Network Summary</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('user.direct.team') }}">
                                    <i class="fas fa-users me-2" style="font-size: 0.8rem;"></i>
                                    <span>Direct Team</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Package Section -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#tables6"
                        aria-expanded="false" aria-controls="tables6">
                        <i class="fas fa-box menu-icon me-3"></i>
                        <span class="menu-title flex-grow-1">Package</span>
                        <i class="menu-arrow fa fa-angle-down transition-all"></i>
                    </a>
                    <div class="collapse" id="tables6">
                        <ul class="nav flex-column sub-menu ps-3"
                            style="border-left: 2px solid #4b49ac; list-style: none;">
                            <li class="nav-item">
                                @php
                                    $user = Auth::user();
                                @endphp
                                @if ($user->status === 'inactive')
                                    <a class="nav-link d-flex align-items-center"
                                        onclick="alert('Please activate your account first to purchase any package.')">
                                        <i class="fas fa-shopping-cart menu-icon me-2" style="font-size: 0.8rem;"></i>
                                        <span class="menu-title">Purchase Package</span>
                                    </a>
                                @else
                                    <a class="nav-link d-flex align-items-center"
                                        href="{{ route('package2.purchase') }}">
                                        <i class="fas fa-shopping-cart me-2" style="font-size: 0.8rem;"></i>
                                        <span class="menu-title">Purchase Package</span>
                                    </a>
                                @endif
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('user.packages') }}">
                                    <i class="fas fa-box-open me-2" style="font-size: 0.8rem;"></i>
                                    <span class="menu-title">Invoices</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('user.maturity.packages') }}">
                                    <i class="fas fa-box-open me-2" style="font-size: 0.8rem;"></i>
                                    <span class="menu-title">Maturity Package</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center"
                                    href="{{ route('user.monthly.profits') }}">
                                    <i class="fas fa-calendar-alt me-2"
                                        style="font-size: 0.8rem; margin-left:1.5px;"></i>
                                    <span>Monthly Income</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Shopping Card -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('user.activation.package') }}">
                        <i class="fas fa-box-open menu-icon me-3"></i>
                        <span class="menu-title">Shopping Card</span>
                    </a>
                </li>

                <!-- Wallet Section -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#tables3"
                        aria-expanded="false" aria-controls="tables3">
                        <i class="fa fa-wallet menu-icon me-3"></i>
                        <span class="menu-title flex-grow-1">Wallet</span>
                        <i class="menu-arrow fa fa-angle-down transition-all"></i>
                    </a>
                    <div class="collapse" id="tables3">
                        <ul class="nav flex-column sub-menu ps-3"
                            style="border-left: 2px solid #4b49ac; list-style: none;">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('user.viewwallet') }}">
                                    <i class="fa fa-coins me-2" style="font-size: 0.8rem;"></i>
                                    <span>Manage Wallet</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center"
                                    href="{{ route('user.transferPointsForm') }}">
                                    <i class="fa fa-exchange-alt me-2" style="font-size: 0.8rem;"></i>
                                    <span>Transfer Wallet</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Manage Stock Section -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#tables4"
                        aria-expanded="false" aria-controls="tables4">
                        <i class="fas fa-cubes menu-icon me-3"></i>
                        <span class="menu-title flex-grow-1">Manage Stock</span>
                        <i class="menu-arrow fa fa-angle-down transition-all"></i>
                    </a>
                    <div class="collapse" id="tables4">
                        <ul class="nav flex-column sub-menu ps-3"
                            style="border-left: 2px solid #4b49ac; list-style: none;">
                            @php
                                $user = Auth::user();
                                $eligibleRanks = DB::table('royalty_level_rewards')
                                    ->whereBetween('sr_no', [7, 13])
                                    ->pluck('level')
                                    ->toArray();
                                $hasAccess = in_array($user->current_rank, $eligibleRanks);
                            @endphp

                            <li class="nav-item">
                                @if ($hasAccess)
                                    <a class="nav-link d-flex align-items-center"
                                        href="{{ route('user.stock.form') }}">
                                    @else
                                        <a class="nav-link d-flex align-items-center text-muted" href="#"
                                            onclick="event.preventDefault(); alert('You must be Diamond Farmer rank or above to access this feature')">
                                @endif
                                <i class="fas fa-truck-moving me-2" style="font-size: 0.8rem;"></i>
                                <span>Transfer Stock</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                @if ($hasAccess)
                                    <a class="nav-link d-flex align-items-center"
                                        href="{{ route('user.stock.coupon-transfer') }}">
                                    @else
                                        <a class="nav-link d-flex align-items-center text-muted" href="#"
                                            onclick="event.preventDefault(); alert('You must be Diamond Farmer rank or above to access this feature')">
                                @endif
                                <i class="fas fa-truck-moving me-2" style="font-size: 0.8rem;"></i>
                                <span>Coupon Stock Transfer</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('user.viewStock') }}">
                                    <i class="fas fa-history me-2" style="font-size: 0.8rem;"></i>
                                    <span>View Stock History</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('user.allStocks') }}">
                                    <i class="fas fa-history me-2" style="font-size: 0.8rem;"></i>
                                    <span>View Stocks Location</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Incentives Section -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#tables1"
                        aria-expanded="false" aria-controls="tables1">
                        <i class="fas fa-award menu-icon me-3"></i>
                        <span class="menu-title flex-grow-1">Incentives</span>
                        <i class="menu-arrow fa fa-angle-down transition-all"></i>
                    </a>
                    <div class="collapse" id="tables1">
                        <ul class="nav flex-column sub-menu ps-3"
                            style="border-left: 2px solid #4b49ac; list-style: none;">
                            <li class="nav-item"><a class="nav-link d-flex align-items-center"
                                    href="{{ route('user.commissions.level1') }}">
                                    <i class="fas fa-hand-holding-usd me-2" style="font-size: 0.8rem;"></i>
                                    <span>Direct Commissions</span>
                                </a></li>
                            <li class="nav-item"><a class="nav-link d-flex align-items-center"
                                    href="{{ route('user.commissions.level2') }}">
                                    <i class="fas fa-network-wired me-2" style="font-size: 0.8rem;"></i>
                                    <span>Network Bonus</span>
                                </a></li>
                            <li class="nav-item"><a class="nav-link d-flex align-items-center"
                                    href="{{ route('user.reports.level-income') }}">
                                    <i class="fas fa-couch me-2" style="font-size: 0.8rem;"></i>
                                    <span>Passive Income</span>
                                </a></li>
                            <li class="nav-item"><a class="nav-link d-flex align-items-center"
                                    href="{{ route('user.rewards.rankRewards', $user->ulid) }}">
                                    <i class="fas fa-trophy me-2" style="font-size: 0.8rem;"></i>
                                    <span>Reward Income</span>
                                </a></li>
                            <li class="nav-item"><a class="nav-link d-flex align-items-center"
                                    href="{{ route('user.yearly.profits') }}">
                                    <i class="fas fa-crown me-2" style="font-size: 0.8rem;"></i>
                                    <span>Royalty Income</span>
                                </a></li>
                        </ul>
                    </div>
                </li>

                <!-- Download PDF Section -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#tables2"
                        aria-expanded="false" aria-controls="tables2">
                        <i class="fas fa-file-download menu-icon me-3"></i>
                        <span class="menu-title flex-grow-1">Download Pdf</span>
                        <i class="menu-arrow fa fa-angle-down transition-all"></i>
                    </a>
                    <div class="collapse" id="tables2">
                        <ul class="nav flex-column sub-menu ps-3"
                            style="border-left: 2px solid #4b49ac; list-style: none;">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="/English-Geokranti.pdf"
                                    download>
                                    <i class="fas fa-language me-2" style="font-size: 0.8rem;"></i>
                                    <span>In English</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="/Hindi-Geokranti.pdf"
                                    download>
                                    <i class="fas fa-language me-2" style="font-size: 0.8rem;"></i>
                                    <span>In Hindi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Support & Account Section -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('user.profile') }}">
                        <i class="ti-settings menu-icon me-3"></i>
                        <span class="menu-title">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('user.login-activity') }}">
                        <i class="fa fa-history menu-icon me-3"></i>
                        <span class="menu-title">Login Activity</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="https://wa.me/9416373249" target="_blank">
                        <i class="fa fa-headset menu-icon me-3"></i>
                        <span class="menu-title">Support</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('logout') }}">
                        <i class="fa fa-power-off menu-icon me-3"></i>
                        <span class="menu-title">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Breadcrumb -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row mb-0">
                    <div class="col-12">
                        <nav aria-label="breadcrumb" class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i
                                            class="fas fa-home"></i> Home</a></li>
                                @isset($breadcrumbs)
                                    @foreach ($breadcrumbs as $breadcrumb)
                                        @if ($loop->last)
                                            <li class="breadcrumb-item active" aria-current="page">
                                                {{ $breadcrumb['title'] }}</li>
                                        @else
                                            <li class="breadcrumb-item">
                                                <a href="{{ $breadcrumb['url'] ?? '#' }}">{{ $breadcrumb['title'] }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                @endisset
                            </ol>
                        </nav>
                    </div>
                </div>

                @section('container') @show
            </div>
        </div>

    </div>

    <!-- Quick Actions Button -->
    <div class="quick-actions-btn" id="quickActionsBtn">
        <i class="fas fa-bolt"></i>
    </div>

    <div class="quick-actions-menu" id="quickActionsMenu">
        <a href="{{ route('user.dashboard') }}" class="quick-action-item">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('user.viewwallet') }}" class="quick-action-item">
            <i class="fas fa-wallet"></i>
            <span>My Wallet</span>
        </a>

        <a href="{{ route('package2.purchase') }}" class="quick-action-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Purchase Package</span>
        </a>
        <a href="{{ route('user.direct.team') }}" class="quick-action-item">
            <i class="fas fa-users"></i>
            <span>Direct Team</span>
        </a>

        <a href="{{ route('user.commissions.level1') }}" class="quick-action-item">
            <i class="fas fa-hand-holding-usd"></i>
            <span>Commissions</span>
        </a>

        <div class="divider"></div>

        <a href="{{ route('user.profile') }}" class="quick-action-item">
            <i class="fas fa-user-cog"></i>
            <span>Profile Settings</span>
        </a>

        <a href="https://wa.me/9416373249" target="_blank" class="quick-action-item">
            <i class="fas fa-headset"></i>
            <span>Support</span>
        </a>
        <a class="quick-action-item" href="{{ route('logout') }}">
            <i class="fa fa-power-off "></i>
            <span>Logout</span>

    </div>
    <script src="{{ asset('assets2/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets2/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <script src="{{ asset('assets2/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets2/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets2/js/template.js') }}"></script>
    <script src="{{ asset('assets2/js/settings.js') }}"></script>
    <script src="{{ asset('assets2/js/todolist.js') }}"></script>
    <script src="{{ asset('assets2/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets2/js/dashboard.js') }}"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
    @stack('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector(
                '.navbar-toggler[data-toggle="offcanvas"]'); // Adjust if you use a different selector

            function closeSidebar() {
                sidebar.classList.remove('active'); // Replace 'active' if your show class is different
                // Also hide via Bootstrap if using collapse
                if (sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }

            // Toggle on menu button
            menuBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('show');
            });

            // Detect click outside
            document.addEventListener("click", function(event) {
                if (
                    sidebar.classList.contains('show') && // Only if sidebar is open
                    !sidebar.contains(event.target) && // Click is not inside sidebar
                    event.target !== menuBtn // Click is not menu button itself
                ) {
                    closeSidebar();
                }
            });

            // Optional: Prevent sidebar click from propagating to document
            sidebar.addEventListener("click", function(e) {
                e.stopPropagation();
            });

            document.getElementById('quickActionsBtn').addEventListener('click', function() {
                const menu = document.getElementById('quickActionsMenu');
                menu.classList.toggle('show');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                const btn = document.getElementById('quickActionsBtn');
                const menu = document.getElementById('quickActionsMenu');

                if (!btn.contains(event.target) && !menu.contains(event.target)) {
                    menu.classList.remove('show');
                }
            });
        });
    </script>

</body>

</html>
