{{-- Admin Products Section --}}
@if($adminProducts->count() > 0)
<div class="mb-4">
    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-patch-check-fill text-primary"></i> 
        <span>Premium Packages <span class="text-muted text-sm fw-normal">(Admin)</span></span>
    </h5>
    
    <div class="row g-2 g-md-3"> 
        @foreach($adminProducts as $prod)
        <div class="col-6 col-md-4 col-lg-3 mb-3"> 
            <div class="h-100 max-w-sm rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 ease-in-out bg-white border border-transparent hover:border-[#DBF5EC] group flex flex-col">
                
                {{-- Image Container --}}
                <div class="relative overflow-hidden rounded-t-xl bg-gray-100 aspect-[4/3]">
                    <img class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110" 
                         src="{{ asset($prod->product_image) }}" 
                         alt="{{ $prod->product_name }}"
                         loading="lazy">
                    
                    {{-- Price Overlay --}}
                    <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent w-full p-2 pt-6 flex items-end justify-between">
                        <div>
                            <p class="text-gray-300 text-[10px] font-medium uppercase tracking-wider mb-0 leading-none">DP Price</p>
                            <span class="text-[#DBF5EC] font-extrabold text-lg md:text-xl tracking-tighter shadow-black drop-shadow-md">
                                ₹{{ number_format($prod->dp) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-2 md:p-3 flex flex-col justify-between flex-grow">
                    <div class="mb-2">
                        <h3 class="font-bold text-sm md:text-base text-gray-800 leading-tight line-clamp-2 min-h-[2.5em]" title="{{ $prod->product_name }}">
                            {{ $prod->product_name }}
                        </h3>
                        <div class="flex items-center gap-1 mt-1">
                            <i class="bi bi-star-fill text-yellow-400 text-[10px]"></i>
                            <span class="text-[10px] md:text-xs text-gray-500 font-medium">Verified Admin</span>
                        </div>
                    </div>
                    
                    <div class="product-action w-full mt-auto" id="action-admin-{{ $prod->id }}">
                        <button 
                            onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->product_name) }}', {{ $prod->dp }}, 'admin', {{ $prod->max_coupon_usage ?? 0 }})"
                            class="w-full bg-[#DBF5EC] hover:bg-[#cbf0e3] active:scale-95 text-teal-900 font-bold py-1.5 md:py-2 rounded-lg flex items-center justify-center gap-1 md:gap-2 transition-all duration-200 text-xs md:text-sm shadow-sm">
                            ADD <i class="bi bi-bag-plus-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Vendor Products Section --}}
@if($vendorProducts->count() > 0)
<div class="mb-5">
    <h5 class="fw-bold mb-3">Vendor Products</h5>
    <div class="row g-2 g-md-3">
        @foreach($vendorProducts as $prod)
        <div class="col-6 col-md-4 col-lg-3 mb-3">
            <div class="h-100 max-w-sm rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 ease-in-out bg-white border border-transparent hover:border-red-100 group flex flex-col">
                
                {{-- Image Container --}}
                <div class="relative overflow-hidden rounded-t-xl bg-gray-100 aspect-[4/3]">
                    <img class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110" 
                         src="{{ asset($prod->product_image) }}" 
                         alt="{{ $prod->product_name }}"
                         loading="lazy">
                    
                    {{-- Vendor Badge --}}
                    <div class="absolute top-1.5 right-1.5 bg-white/95 backdrop-blur-sm px-1.5 py-0.5 rounded text-[9px] md:text-[10px] font-bold shadow-sm text-gray-700 max-w-[80%] truncate">
                        <i class="bi bi-shop"></i> {{ $prod->vendor->company_name ?? 'Vendor' }}
                    </div>

                    {{-- Price Overlay --}}
                    <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent w-full p-2 pt-6 flex items-end justify-between">
                        <div>
                            <p class="text-gray-300 text-[10px] font-medium uppercase tracking-wider mb-0 leading-none">Best Price</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-white font-extrabold text-lg md:text-xl tracking-tighter shadow-black drop-shadow-md">
                                    ₹{{ number_format($prod->dp) }}
                                </span>
                                <span class="text-gray-400 text-[10px] md:text-xs line-through decoration-red-400">₹{{ number_format($prod->mrp) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-2 md:p-3 flex flex-col justify-between flex-grow">
                    <div class="mb-2">
                        <h3 class="font-bold text-sm md:text-base text-gray-800 leading-tight line-clamp-2 min-h-[2.5em]" title="{{ $prod->product_name }}">
                            {{ $prod->product_name }}
                        </h3>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            <span class="text-[10px] md:text-xs text-gray-500 font-medium">Vendor Verified</span>
                        </div>
                    </div>
                    
                    <div class="product-action w-full mt-auto" id="action-vendor-{{ $prod->id }}">
                        <button 
                            onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->product_name) }}', {{ $prod->dp }}, 'vendor', {{ $prod->max_coupon_usage ?? 0 }})"
                            class="w-full bg-red-50 hover:bg-red-100 active:scale-95 text-red-900 font-bold py-1.5 md:py-2 rounded-lg flex items-center justify-center gap-1 md:gap-2 transition-all duration-200 text-xs md:text-sm shadow-sm">
                            ADD <i class="bi bi-cart-plus-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- No Results State --}}
@if($adminProducts->isEmpty() && $vendorProducts->isEmpty())
    <div class="text-center py-10">
        <div class="bg-gray-50 rounded-full p-6 inline-block mb-4">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" width="120" class="mx-auto opacity-75 grayscale-[20%]">
        </div>
        <h6 class="font-bold text-gray-600 text-lg">No Items Found</h6>
        <p class="mt-1 text-sm text-gray-400">We couldn't find any products matching your search.</p>
        <button onclick="window.location.reload()" class="mt-4 text-primary text-sm font-semibold hover:underline">Clear Filters</button>
    </div>
@endif