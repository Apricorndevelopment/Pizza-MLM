@extends('userlayouts.layouts') @section('container')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3>🚀 Become a Certified Vendor</h3>
                    <p class="mb-0">Expand your business with us!</p>
                </div>
                <div class="card-body p-5">
                    {{-- <div class="text-center mb-4">
                        <img src="{{ asset('images/vendor-badge.png') }}" alt="Vendor" style="width: 100px;">
                    </div> --}}
                    
                    <h4 class="text-center mb-4">Vendor Activation Package</h4>
                    
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">✅ List unlimited products</li>
                        <li class="list-group-item">✅ Access to Vendor Dashboard</li>
                        <li class="list-group-item">✅ Direct Payouts to Wallet</li>
                        <li class="list-group-item">✅ 24/7 Priority Support</li>
                    </ul>

                    <div class="alert alert-info">
                        <strong>Package Cost:</strong> ₹{{ number_format($package->price, 2) }} <br>
                        <strong>Your Wallet Balance:</strong> ₹{{ number_format(Auth::user()->wallet1_balance, 2) }}
                    </div>

                    <form action="{{ route('user.purchase_vendor') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Company/Shop Name</label>
                            <input type="text" name="company_name" class="form-control" placeholder="Enter your shop name" required>
                        </div>
                        <div class="mb-3">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" placeholder="City" required>
                        </div>

                        <div class="d-grid gap-2">
                             @if(Auth::user()->wallet1_balance >= $package->price)
                                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Are you sure you want to purchase vendor membership?')">
                                    Pay ₹{{ $package->price }} & Activate
                                </button>
                             @else
                                <button type="button" class="btn btn-secondary btn-lg" disabled>
                                    Insufficient Balance (Need ₹{{ $package->price - Auth::user()->wallet1_balance }} more)
                                </button>
                                <a href="#" class="btn btn-outline-primary mt-2">Add Money to Wallet</a>
                             @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection