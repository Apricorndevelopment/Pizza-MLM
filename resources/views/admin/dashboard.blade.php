@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

    <div class="mb-6 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ Auth::guard('admin')->user()->name }}!</h2>
            <p class="text-gray-500 text-sm mt-1">Here's what's happening with your store today.</p>
        </div>

        <div>
            <form action="{{ route('admin.shop.toggle') }}" method="POST" id="shopStatusForm">
                @csrf
                {{-- Toggle Container --}}
                <label class="relative inline-flex items-center cursor-pointer group">
                    <input type="checkbox" name="isShopOpen" value="1" class="sr-only peer"
                        onchange="document.getElementById('shopStatusForm').submit()"
                        {{ Auth::guard('admin')->user()->isShopOpen ? 'checked' : '' }}>

                    {{-- Toggle Background --}}
                    <div
                        class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500 shadow-inner">
                    </div>

                    {{-- Status Text --}}
                    <span
                        class="ml-3 text-sm font-bold {{ Auth::guard('admin')->user()->isShopOpen ? 'text-emerald-600' : 'text-slate-500' }}">
                        {{ Auth::guard('admin')->user()->isShopOpen ? 'Shop is Open' : 'Shop is Closed' }}
                    </span>
                </label>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">$24,580</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                <span class="text-green-500 font-medium">12.5%</span>
                <span class="text-gray-500 ml-2">from last month</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">1,248</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                <span class="text-green-500 font-medium">8.2%</span>
                <span class="text-gray-500 ml-2">from last month</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active Users</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">5,342</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                <span class="text-green-500 font-medium">3.7%</span>
                <span class="text-gray-500 ml-2">from last week</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Conversion Rate</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">4.8%</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-percentage text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                <span class="text-red-500 font-medium">1.2%</span>
                <span class="text-gray-500 ml-2">from last week</span>
            </div>
        </div>
    </div>

    <!-- Sample Chart and Table -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Overview</h3>
            <div class="h-64 flex items-center justify-center text-gray-400">
                <div class="text-center">
                    <i class="fas fa-chart-bar text-4xl mb-2"></i>
                    <p>Revenue chart would be displayed here</p>
                    <p class="text-sm">(In a real application)</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Orders</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="py-3 px-4">Order ID</th>
                            <th class="py-3 px-4">Customer</th>
                            <th class="py-3 px-4">Amount</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b">
                            <td class="py-3 px-4 font-medium">#ORD-7821</td>
                            <td class="py-3 px-4">Alex Johnson</td>
                            <td class="py-3 px-4">$245.99</td>
                            <td class="py-3 px-4"><span
                                    class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">Delivered</span>
                            </td>
                        </tr>
                        <tr class="bg-white border-b">
                            <td class="py-3 px-4 font-medium">#ORD-7820</td>
                            <td class="py-3 px-4">Maria Garcia</td>
                            <td class="py-3 px-4">$129.50</td>
                            <td class="py-3 px-4"><span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">Processing</span>
                            </td>
                        </tr>
                        <tr class="bg-white border-b">
                            <td class="py-3 px-4 font-medium">#ORD-7819</td>
                            <td class="py-3 px-4">David Smith</td>
                            <td class="py-3 px-4">$89.99</td>
                            <td class="py-3 px-4"><span
                                    class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full">Pending</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <h3 class="font-weight-bold">Welcome Admin</h3>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex justify-content-md-end">
                                <div class="dropdown flex-md-grow-0">
                                    <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button"
                                        id="dropdownMenuDate2" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="true">
                                        <?php
                                        $now = new DateTime();
                                        $formattedDate = $now->format('d M Y');
                                        ?>
                                        <i class="mdi mdi-calendar"></i> Today ( {{ $formattedDate }} ) </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                        <a class="dropdown-item" href="#">January - March</a>
                                        <a class="dropdown-item" href="#">March - June</a>
                                        <a class="dropdown-item" href="#">June - August</a>
                                        <a class="dropdown-item" href="#">August - November</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card tale-bg">
                        <div class="card-people mt-auto">
                            <img src="{{ asset('assets2/images/dashboard/people.svg') }}" alt="people">
                            <div class="weather-info">
                                <div class="d-flex">
                                    <div>
                                        <h2 class="mb-0 font-weight-normal"><i class="icon-sun me-2"></i>31<sup>C</sup>
                                        </h2>
                                    </div>
                                    <div class="ms-2">
                                        <h4 class="location font-weight-normal">India</h4>
                                        <h6 class="font-weight-normal">Haryana</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 grid-margin transparent">
                    <div class="row">
                        <div class="col-md-6 mb-4 stretch-card transparent">
                            <div class="card card-tale">
                                <div class="card-body">
                                    <p class="mb-4 fs-5">Total Users</p>
                                    <p class="fs-30 mb-2">{{ $userCount }}</p>
                                </div>
                            </div>
                        </div>

                        @php
                            function formatInTrillions($number)
                            {
                                if ($number >= 1000000000000) {
                                    return '₹' . number_format($number / 1000000000000, 3) . ' Trillion';
                                } elseif ($number >= 1000000000) {
                                    return '₹' . number_format($number / 1000000000, 2) . ' Billion';
                                } elseif ($number >= 10000000) {
                                    return '₹' . number_format($number / 10000000, 2) . ' Cr';
                                } elseif ($number >= 100000) {
                                    return '₹' . number_format($number / 100000, 2) . ' Lakh';
                                } else {
                                    return '₹' . number_format($number, 2);
                                }
                            }
                        @endphp
                        <div class="col-md-6 mb-4 stretch-card transparent">
                            <div class="card card-dark-blue">
                                <div class="card-body">
                                    <p class="mb-4 fs-5">Total Business</p>
                                    <p class="fs-30 mb-2">{{ formatInTrillions($businessCount) }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                            <div class="card card-light-blue">
                                <div class="card-body">
                                    <p class="mb-4">Total Type Package1</p>
                                    <p class="fs-30 mb-2">{{ $package1Count }}</p>
                                    <!-- <p>2.00% (30 days)</p> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 stretch-card transparent">
                            <div class="card card-light-danger">
                                <div class="card-body">
                                    <p class="mb-4">Total Type ProductPackage</p>
                                    <p class="fs-30 mb-2">{{ $package2Count }}</p>
                                    <!-- <p>0.22% (30 days)</p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <p class="card-title">Sales Report</p>
                                            <a href="#" class="text-info">View all</a>
                                        </div>
                                        <p class="font-weight-500">The total number of sessions within the date range. It is the period
                                            time a
                                            user is actively engaged with your website, page or app, etc</p>
                                        <div id="sales-chart-legend" class="chartjs-legend mt-4 mb-2"></div>
                                        <canvas id="sales-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div> -->
            <!-- <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card position-relative">
                                    <div class="card-body">
                                        <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2"
                                            data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="row">
                                                        <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                                            <div class="ml-xl-4 mt-3">
                                                                <p class="card-title">Detailed Reports</p>
                                                                <h1 class="text-primary">$34040</h1>
                                                                <h3 class="font-weight-500 mb-xl-4 text-primary">North America</h3>
                                                                <p class="mb-2 mb-xl-0">The total number of sessions within the date
                                                                    range. It is the
                                                                    period time a user is actively engaged with your website, page or
                                                                    app, etc</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-xl-9">
                                                            <div class="row">
                                                                <div class="col-md-6 border-right">
                                                                    <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                                        <table class="table table-borderless report-table">
                                                                            <tr>
                                                                                <td class="text-muted">Illinois</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-primary"
                                                                                            role="progressbar" style="width: 70%"
                                                                                            aria-valuenow="70" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">713</h5>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-muted">Washington</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-warning"
                                                                                            role="progressbar" style="width: 30%"
                                                                                            aria-valuenow="30" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">583</h5>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-muted">Mississippi</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-danger"
                                                                                            role="progressbar" style="width: 95%"
                                                                                            aria-valuenow="95" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">924</h5>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-muted">California</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-info"
                                                                                            role="progressbar" style="width: 60%"
                                                                                            aria-valuenow="60" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">664</h5>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-muted">Maryland</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-primary"
                                                                                            role="progressbar" style="width: 40%"
                                                                                            aria-valuenow="40" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">560</h5>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-muted">Alaska</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-danger"
                                                                                            role="progressbar" style="width: 75%"
                                                                                            aria-valuenow="75" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">793</h5>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-3">
                                                                    <div class="daoughnutchart-wrapper">
                                                                        <canvas id="north-america-chart"></canvas>
                                                                    </div>
                                                                    <div id="north-america-chart-legend">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="row">
                                                        <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                                            <div class="ml-xl-4 mt-3">
                                                                <p class="card-title">Detailed Reports</p>
                                                                <h1 class="text-primary">$34040</h1>
                                                                <h3 class="font-weight-500 mb-xl-4 text-primary">North America</h3>
                                                                <p class="mb-2 mb-xl-0">The total number of sessions within the date
                                                                    range. It is the
                                                                    period time a user is actively engaged with your website, page or
                                                                    app, etc</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-xl-9">
                                                            <div class="row">
                                                                <div class="col-md-6 border-right">
                                                                    <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                                        <table class="table table-borderless report-table">
                                                                            <tr>
                                                                                <td class="text-muted">Illinois</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-primary"
                                                                                            role="progressbar" style="width: 70%"
                                                                                            aria-valuenow="70" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">713</h5>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-muted">Washington</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-warning"
                                                                                            role="progressbar" style="width: 30%"
                                                                                            aria-valuenow="30" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">583</h5>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-muted">Mississippi</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-danger"
                                                                                            role="progressbar" style="width: 95%"
                                                                                            aria-valuenow="95" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">924</h5>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-muted">California</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-info"
                                                                                            role="progressbar" style="width: 60%"
                                                                                            aria-valuenow="60" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">664</h5>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-muted">Maryland</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-primary"
                                                                                            role="progressbar" style="width: 40%"
                                                                                            aria-valuenow="40" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">560</h5>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-muted">Alaska</td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-danger"
                                                                                            role="progressbar" style="width: 75%"
                                                                                            aria-valuenow="75" aria-valuemin="0"
                                                                                            aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0">793</h5>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-3">
                                                                    <div class="daoughnutchart-wrapper">
                                                                        <canvas id="south-america-chart"></canvas>
                                                                    </div>
                                                                    <div id="south-america-chart-legend"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="carousel-control-prev" href="#detailedReports" role="button"
                                                data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            </a>
                                            <a class="carousel-control-next" href="#detailedReports" role="button"
                                                data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
            <!-- <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="card-title mb-0">Top Products</p>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Price</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Search Engine Marketing</td>
                                                        <td class="font-weight-bold">$362</td>
                                                        <td>21 Sep 2018</td>
                                                        <td class="font-weight-medium">
                                                            <div class="badge badge-success">Completed</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Search Engine Optimization</td>
                                                        <td class="font-weight-bold">$116</td>
                                                        <td>13 Jun 2018</td>
                                                        <td class="font-weight-medium">
                                                            <div class="badge badge-success">Completed</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Display Advertising</td>
                                                        <td class="font-weight-bold">$551</td>
                                                        <td>28 Sep 2018</td>
                                                        <td class="font-weight-medium">
                                                            <div class="badge badge-warning">Pending</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pay Per Click Advertising</td>
                                                        <td class="font-weight-bold">$523</td>
                                                        <td>30 Jun 2018</td>
                                                        <td class="font-weight-medium">
                                                            <div class="badge badge-warning">Pending</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>E-Mail Marketing</td>
                                                        <td class="font-weight-bold">$781</td>
                                                        <td>01 Nov 2018</td>
                                                        <td class="font-weight-medium">
                                                            <div class="badge badge-danger">Cancelled</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Referral Marketing</td>
                                                        <td class="font-weight-bold">$283</td>
                                                        <td>20 Mar 2018</td>
                                                        <td class="font-weight-medium">
                                                            <div class="badge badge-warning">Pending</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Social media marketing</td>
                                                        <td class="font-weight-bold">$897</td>
                                                        <td>26 Oct 2018</td>
                                                        <td class="font-weight-medium">
                                                            <div class="badge badge-success">Completed</div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->


        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer bg-tr">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2025 Apricorn
                    Solutions <a href="https://apricornsolutions.com/" target="_blank"> apricornsolutions.com </a>
                    All rights reserved.</span>
                <!-- <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i
                                    class="ti-heart text-danger ms-1"></i></span> -->
            </div>
        </footer>
        <!-- partial -->
    </div> --}}

@endsection
