<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Geo Kranti Admin</title>
    <link rel="stylesheet" href="{{ asset('assets2/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/vendors/mdi/css/materialdesignicons.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('assets2/vendors/datatables.net-bs5/dataTables.bootstrap5.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('assets2/vendors/ti-icons/css/themify-icons.css') }}">
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
    </style>
</head>

<body>
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">

        <div class="text-center navbar-brand-wrapper ps-0 ps-sm-1 ps-xl-2 d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo me-5 d-flex align-items-center" href="">
                <img src="{{ asset('geokrantilogo.jpg') }}" alt="logo"
                    style="width: 55px; height: 55px; object-fit: cover;" class="me-2" />
                <h3 class="mb-0">Geokranti</h3>
            </a>
            <a class="navbar-brand brand-logo-mini" href=""><img src="{{ asset('geokrantilogo.jpg') }}"
                    alt="logo" /></a>
        </div>

        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
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
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture">
                        @else
                            <img src="{{ asset('assets2/images/faces/face28.jpg') }}" alt="profile" />
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
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('user.dashboard') }}">
                        <i class="icon-grid menu-icon me-3"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

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
                                <a class="nav-link d-flex align-items-center" href="{{ route('user.view.userTree') }}">
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
                                <a class="nav-link d-flex align-items-center"
                                    href="{{ route('user.direct.team') }}">
                                    <i class="fas fa-users me-2" style="font-size: 0.8rem;"></i>
                                    <span>Direct Team</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    @php
                        $user = Auth::user();
                    @endphp
                    @if ($user->status === 'inactive')
                        <a class="nav-link d-flex align-items-center"
                            onclick="alert('Please activate your account first to purchase any package.')">
                            <i class="fas fa-shopping-cart menu-icon me-3"></i>
                            <span class="menu-title">Purchase Package</span>
                        </a>
                    @else
                        <a class="nav-link d-flex align-items-center" href="{{ route('package2.purchase') }}">
                            <i class="fas fa-shopping-cart menu-icon me-3"></i>
                            <span class="menu-title">Purchase Package</span>
                        </a>
                    @endif
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('user.packages') }}">
                        <i class="fas fa-box-open menu-icon me-3"></i>
                        <span class="menu-title">My Packages</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('user.activation.package') }}">
                        <i class="fas fa-box-open menu-icon me-3"></i>
                        <span class="menu-title">Activation Package</span>
                    </a>
                </li>

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
                                    <span>Transfer Points</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

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
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('user.stock.form') }}">
                                    <i class="fas fa-truck-moving me-2" style="font-size: 0.8rem;"></i>
                                    <span>Transfer Stock</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('user.stock.coupon-transfer') }}">
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
                        </ul>
                    </div>
                </li>

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
                            <li class="nav-item"><a class="nav-link d-flex align-items-center"
                                    href="{{ route('user.monthly.profits') }}">
                                    <i class="fas fa-calendar-alt me-2" style="font-size: 0.8rem;"></i>
                                    <span>Monthly Income</span>
                                </a></li>
                        </ul>
                    </div>
                </li>

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
                                <a class="nav-link d-flex align-items-center" href="/English-Geokranti.com.pdf"
                                    download>
                                    <i class="fas fa-language me-2" style="font-size: 0.8rem;"></i>
                                    <span>In English</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="/Hindi-Geokranti.com.pdf"
                                    download>
                                    <i class="fas fa-language me-2" style="font-size: 0.8rem;"></i>
                                    <span>In Hindi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>

        {{-- <section class="main-content"> --}}
        {{-- <div class="section__content"> --}}

        @section('container') @show

        {{-- </div>
        </section> --}}
    </div>

    </div>
    <script src="{{ asset('assets2/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets2/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <!-- <script src="{{ asset('assets2/vendors/datatables.net/jquery.dataTables.js') }}"></script> -->
    <!-- <script src="{{ asset('assets2/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script> -->
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

</body>

</html>