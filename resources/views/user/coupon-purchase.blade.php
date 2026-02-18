@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Purchase Coupons')

@section('container')
<div class="container-fluid px-4 bg-gray-50/50 min-h-screen">
    
    {{-- Page Header & Wallet Section --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8 text-center">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
                Coupon Packages
            </h1>

            {{-- Wallet Balance Card (Compact & Professional) --}}
            <div class="inline-flex items-center bg-white rounded-full shadow-md border border-slate-200 px-6 py-3 gap-4 transform hover:scale-105 transition-transform duration-300">
                <div class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-lg"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-0">Wallet Balance</p>
                    <p class="text-xl font-bold text-slate-800 leading-none m-0">
                        ₹ {{ number_format(Auth::user()->wallet1_balance, 2) }}
                    </p>
                </div>
                <a href="{{ route('user.funds.create') }}" class="ml-2 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    Top up <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-sm mb-4 flex items-center justify-between" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-xl mr-3"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="text-green-600 hover:text-green-800" data-bs-dismiss="alert">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-sm mb-4 flex items-center justify-between" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-xl mr-3"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="text-red-600 hover:text-red-800" data-bs-dismiss="alert">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Packages Grid --}}
    <div class="row g-4 justify-content-center">
        @forelse($packages as $package)
            @php
                $perUnitCost = $package->coupon_qyt > 0 ? ($package->coupon_price / $package->coupon_qyt) : 0;
                // Optional: Highlight "Best Value" if needed logic here
            @endphp
            <div class="col-sm-6 col-lg-4 d-flex">
                <div class="w-full bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-200 transition-all duration-300 flex flex-col relative overflow-hidden group">
                    
                    {{-- Top Accent Line --}}
                    <div class="h-2 w-full bg-gradient-to-r from-indigo-500 to-purple-600"></div>

                    {{-- Card Header --}}
                    <div class="p-3 text-center border-b border-slate-100">
                        <h3 class="text-slate-500 font-bold text-sm tracking-widest uppercase mb-2">Bundle #{{ $loop->iteration }}</h3>
                        <div class="flex items-center justify-center text-slate-800">
                            <span class="text-2xl font-bold self-start mt-2">₹</span>
                            <span class="text-4xl font-extrabold tracking-tight">{{ number_format($package->coupon_price * $package->coupon_qyt, 0) }}</span>
                        </div>
                        <p class="text-slate-400 text-sm font-medium mt-2">One-time payment</p>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-3 flex-grow flex flex-col items-center justify-center bg-slate-50/50">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="bg-indigo-100 text-indigo-700 p-2 rounded-lg">
                                <i class="fas fa-layer-group"></i>
                            </span>
                            <span class="text-3xl font-bold text-slate-800">{{ $package->coupon_qyt }}</span>
                            <span class="text-lg font-medium text-slate-600">Coupons</span>
                        </div>
                        <p class="text-sm text-slate-500 font-medium">
                            @ ₹{{ number_format($perUnitCost, 2) }} per unit
                        </p>
                        
                        {{-- Visual Divider --}}
                        <div class="w-12 h-1 bg-slate-200 rounded-full my-3"></div>

                        <ul class="text-sm text-slate-600 space-y-2 mb-0 text-center">
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Instant Crediting</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Lifetime Validity</li>
                        </ul>
                    </div>

                    {{-- Card Footer --}}
                    <div class="p-3 mt-auto bg-white border-t border-slate-100">
                        <form action="{{ route('user.coupons.process') }}" method="POST"
                              onsubmit="return confirm('Please confirm purchase:\n\nPackage: {{ $package->coupon_qyt }} Coupons\nCost: ₹{{ number_format($package->coupon_price, 2) }}\n\nAre you sure?');">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            
                            <button type="submit" 
                                class="w-full py-3 px-4 bg-slate-900 hover:bg-indigo-600 text-white font-bold rounded-xl transition-colors duration-200 flex items-center justify-center gap-2 group-hover:shadow-lg">
                                <span>Purchase Package</span>
                                <i class="fas fa-chevron-right text-xs transition-transform group-hover:translate-x-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-16">
                <div class="bg-white rounded-3xl p-10 shadow-sm border border-slate-100 inline-block max-w-lg">
                    <div class="bg-slate-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i class="fas fa-box-open text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">No Packages Available</h3>
                    <p class="text-slate-500">We are currently updating our pricing bundles. Please check back shortly.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection