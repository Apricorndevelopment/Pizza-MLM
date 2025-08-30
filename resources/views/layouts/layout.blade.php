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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets2/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets2/js/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets2/images/favicon.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
    <nav class="navbar col-lg-12 col-12 fixed-top d-flex flex-row py-0">
        <div
            class="text-center navbar-brand-wrapper ps-0 ps-sm-1 ps-xl-2 d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo me-5 d-flex align-items-center" href="">
                <img src="{{ asset('geokrantilogo.jpg') }}" alt="logo"
                    style="width: 55px; height: 55px; object-fit: cover;" class="me-2" />
                <h3 class="mb-0">Geokranti</h3>
            </a>
            <a class="navbar-brand brand-logo-mini" href=""><img src="{{ asset('geokrantilogo.jpg') }}"
                    alt="logo" /></a>
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
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                        <?php
                        
                        use Illuminate\Support\Facades\Auth;
                        $user = Auth::user();
                        ?>
                        @if ($user->profile_picture)
                            <img src="{{ asset('storage/profile-pictures/' . basename($user->profile_picture)) }}"
                                alt="Profile Picture">
                        @else
                            <img src="{{ asset('assets2/images/faces/face28.jpg') }}" alt="profile" />
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <a href="{{ route('admin.profile') }}" class="dropdown-item">
                            <i class="ti-settings text-primary"></i> Profile </a>
                        <a class="dropdown-item" href="">
                            <i class="ti-power-off text-primary"></i>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
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
                    <a class="nav-link d-flex align-items-center {{ Route::is('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="icon-grid menu-icon me-3"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

                @php
                    $adminId = Auth::guard('admin')->id();
                @endphp

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#tables3"
                        aria-expanded="false" aria-controls="tables3">
                        <i class="fa fa-sitemap menu-icon me-3"></i>
                        <span class="menu-title flex-grow-1">Admin Network</span>
                        <i class="menu-arrow fa fa-angle-down transition-all"></i>
                    </a>
                    <div class="collapse" id="tables3">
                        <ul class="nav flex-column sub-menu ps-3"
                            style="border-left: 2px solid #4b49ac; list-style: none;">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center"
                                    href="{{ route('admin.user.tree', $adminId) }}">
                                    <i class="fa fa-sitemap me-2"></i>
                                    <span class="menu-title">User Tree</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center"
                                    href="{{ route('admin.network.summary') }}">
                                    <i class="fa fa-network-wired me-2"></i>
                                    <span class="menu-title">Network Summary</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.viewmember') }}">
                        <i class="fa fa-users menu-icon me-3"></i>
                        <span class="menu-title">User/Member</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.products.index') }}">
                        <i class="fa fa-tags menu-icon me-3"></i>
                        <span class="menu-title">Manage Products</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#tables2"
                        aria-expanded="false" aria-controls="tables2">
                        <i class="fa fa-money menu-icon me-3"></i>
                        <span class="menu-title flex-grow-1">Wallet Management</span>
                        <i class="menu-arrow fa fa-angle-down transition-all"></i>
                    </a>
                    <div class="collapse" id="tables2">
                        <ul class="nav flex-column sub-menu ps-3"
                            style="border-left: 2px solid #4b49ac; list-style: none;">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('admin.wallet') }}">
                                    <i class="fa fa-share me-2" style="font-size: 0.8rem;"></i>
                                    <span>Provide Points</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center"
                                    href="{{ route('admin.withdrawls.index') }}">
                                    <i class="fa fa-credit-card me-2" style="font-size: 0.8rem;"></i>
                                    <span>Withdrawal Requests</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center"
                                    href="{{ route('admin.wallet-transactions') }}">
                                    <i class="fa fa-exchange-alt me-2" style="font-size: 0.8rem;"></i>
                                    <span>All Transactions</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#tables4"
                        aria-expanded="false" aria-controls="tables4">
                        <i class="fa fa-cubes menu-icon me-3"></i>
                        <span class="menu-title flex-grow-1">Manage Stock</span>
                        <i class="menu-arrow fa fa-angle-down transition-all"></i>
                    </a>
                    <div class="collapse" id="tables4">
                        <ul class="nav flex-column sub-menu ps-3"
                            style="border-left: 2px solid #4b49ac; list-style:none;">
                            <li class="nav-item" style="list-style: none;">
                                <a class="nav-link d-flex align-items-center" href="{{ route('admin.stock.form') }}">
                                    <i class="fa fa-truck me-2" style="font-size: 0.8rem;"></i>
                                    <span>Transfer Stock</span>
                                </a>
                            </li>
                            <li class="nav-item" style="list-style: none;">
                                <a class="nav-link d-flex align-items-center"
                                    href="{{ route('admin.sales.stock') }}">
                                    <i class="fa fa-credit-card me-2" style="font-size: 0.8rem;"></i>
                                    <span>Record Stock Sales</span>
                                </a>
                            </li>
                            <li class="nav-item" style="list-style: none;">
                                <a class="nav-link d-flex align-items-center" href="{{ route('admin.viewStock') }}">
                                    <i class="fa fa-credit-card me-2" style="font-size: 0.8rem;"></i>
                                    <span>View User's Stock</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#tables"
                        aria-expanded="false" aria-controls="tables">
                        <i class="fa fa-suitcase menu-icon me-3"></i>
                        <span class="menu-title flex-grow-1">Package Management</span>
                        <i class="menu-arrow fa fa-angle-down transition-all"></i>
                    </a>
                    <div class="collapse" id="tables">
                        <ul class="nav flex-column sub-menu ps-3"
                            style="border-left: 2px solid #4b49ac; list-style: none;">
                            <li class="nav-item m-0 p-0">
                                <a class="nav-link d-flex align-items-center" href="{{ route('admin.package') }}">
                                    <i class="fa fa-eye me-2" style="font-size: 0.8rem;"></i>
                                    <span>View Packages</span>
                                </a>
                            </li>
                            <li class="nav-item m-0 p-0">
                                <a class="nav-link d-flex align-items-center"
                                    href="{{ route('admin.packages.assign') }}">
                                    <i class="fa fa-check-circle me-2" style="font-size: 0.8rem;"></i>
                                    <span>Active</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#tables1"
                        aria-expanded="false" aria-controls="tables1">
                        <i class="fa fa-balance-scale menu-icon me-3"></i>
                        <span class="menu-title flex-grow-1">Profit Distribution</span>
                        <i class="menu-arrow fa fa-angle-down transition-all"></i>
                    </a>
                    <div class="collapse" id="tables1">
                        <ul class="nav flex-column sub-menu ps-3" style="border-left: 2px solid #4b49ac;">
                            <li class="nav-item list-unstyled m-0 p-0">
                                <a class="nav-link d-flex align-items-center py-2"
                                    href="{{ route('admin.profit.distribution') }}">
                                    <i class="fa fa-share-alt me-2" style="font-size: 0.8rem;"></i>
                                    <span>Distribute Profit</span>
                                </a>
                            </li>
                            <li class="nav-item list-unstyled m-0 p-0">
                                <a class="nav-link d-flex align-items-center py-2"
                                    href="{{ route('admin.view.monthlyDistribution') }}">
                                    <i class="fa fa-calendar me-2" style="font-size: 0.8rem;"></i>
                                    <span>Monthly Profit</span>
                                </a>
                            </li>
                            <li class="nav-item list-unstyled m-0 p-0">
                                <a class="nav-link d-flex align-items-center py-2"
                                    href="{{ route('admin.view.distribution') }}">
                                    <i class="fa fa-calendar-check-o me-2" style="font-size: 0.8rem;"></i>
                                    <span>Yearly Profit</span>
                                </a>
                            </li>
                        </ul>

                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.pdf.edit') }}">
                        <i class="fas fa-file-pdf menu-icon me-3" style="font-size: 1.5rem"></i>
                        <span class="menu-title">Manage PDFs</span>
                    </a>
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
    {{-- <!-- <script src="{{ asset('assets2/vendors/datatables.net/jquery.dataTables.js') }}"></script> -->
    <!-- <script src="{{ asset('assets2/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script> --> --}}
    <script src="{{ asset('assets2/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets2/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets2/js/template.js') }}"></script>
    <script src="{{ asset('assets2/js/settings.js') }}"></script>
    <script src="{{ asset('assets2/js/todolist.js') }}"></script>
    <script src="{{ asset('assets2/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets2/js/dashboard.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</body>

</html>
