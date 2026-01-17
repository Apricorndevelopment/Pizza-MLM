@extends('vendorlayouts.layout') @section('title', 'Vendor Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Welcome Vendor, {{ Auth::user()->name }}!</h3>
                <h6 class="font-weight-normal mb-0">Your shop is live and ready for orders.</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card card-dark-blue">
            <div class="card-body">
                <p class="mb-4">Total Sales</p>
                <p class="fs-30 mb-2">₹0.00</p>
                <p>Starting today</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card card-tale">
            <div class="card-body">
                <p class="mb-4">Active Products</p>
                <p class="fs-30 mb-2">0</p>
                <p>Inventory Status</p>
            </div>
        </div>
    </div>
</div>
@endsection