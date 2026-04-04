{{-- Admin Products Section --}}
@if ($adminProducts->count() > 0)
    <div class="mb-4">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-patch-check-fill text-primary"></i>
            <span>Admin Products</span>
        </h5>

        @if (isset($admin) && $admin->isShopOpen)
            <div class="row g-2 g-md-3" id="admin-product-grid">
                @foreach ($adminProducts as $prod)
                    @php
                        $isOutOfStock = ($prod->manage_stock == 1 && $prod->stock_quantity <= 0);
                        $gst = $prod->gst ?? 0;
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <div class="h-100 max-w-sm rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 ease-in-out bg-white border border-transparent hover:border-[#DBF5EC] group flex flex-col {{ $isOutOfStock ? 'opacity-80' : '' }}">

                            {{-- Image Container --}}
                            <div class="relative overflow-hidden rounded-t-xl bg-gray-100 aspect-[4/3]">
                                <img class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110"
                                    src="{{ asset($prod->product_image) }}" alt="{{ $prod->product_name }}"
                                    loading="lazy">

                                @if($isOutOfStock)
                                    <div class="absolute inset-0 bg-white/40 z-10 backdrop-blur-[1px]"></div>
                                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
                                        <span class="bg-red-600 text-white px-3 py-1.5 rounded-md text-[10px] md:text-xs font-bold uppercase tracking-wider shadow-lg whitespace-nowrap">Out of Stock</span>
                                    </div>
                                @endif

                                {{-- Price Overlay (Shows DP Price Only) --}}
                                <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent w-full p-2 pt-6 flex items-end justify-between {{ $isOutOfStock ? 'z-0' : 'z-10' }}">
                                    <div>
                                        <p class="text-gray-300 text-[10px] font-medium uppercase tracking-wider mb-0 leading-none">Price</p>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-[#DBF5EC] font-extrabold text-lg md:text-xl tracking-tighter shadow-black drop-shadow-md">
                                                ₹{{ number_format($prod->dp, 2) }}
                                            </span>
                                            <span class="text-gray-400 text-[10px] md:text-xs line-through decoration-red-400">
                                                ₹{{ number_format($prod->mrp, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-2 md:p-3 flex flex-col justify-between flex-grow">
                                <div class="mb-2">
                                    <h3 class="font-bold text-sm md:text-base text-gray-800 leading-tight line-clamp-1" title="{{ $prod->product_name }}">
                                        {{ $prod->product_name }}
                                    </h3>
                                    
                                    {{-- ACTUAL PRODUCT DESCRIPTION (MODIFIED FOR READ MORE) --}}
                                    <div class="mt-1 mb-2">
                                        <p id="desc-admin-{{ $prod->id }}" class="text-[10px] md:text-xs text-gray-500 line-clamp-5 transition-all duration-300 m-0" title="{{ $prod->description }}">
                                            {{ $prod->description ?? 'No description available.' }}
                                        </p>
                                        <button id="btn-admin-{{ $prod->id }}" onclick="toggleDescription('admin-{{ $prod->id }}')" class="hidden text-teal-600 hover:text-teal-800 text-[10px] md:text-xs font-bold mt-1 bg-transparent border-0 p-0 cursor-pointer">Read more</button>
                                    </div>

                                    <div class="flex items-center gap-2 flex-wrap">
                                        <div class="flex items-center gap-1">
                                            <i class="bi bi-star-fill text-yellow-400 text-[10px]"></i>
                                            <span class="text-[10px] md:text-xs text-gray-500 font-medium">Verified Admin</span>
                                        </div>
                                        <span class="bg-blue-50 text-blue-700 border border-blue-100 px-1.5 py-0.5 rounded text-[9px] md:text-[10px] font-bold">
                                            PV: {{ $prod->pv ?? 0 }}
                                        </span>
                                        @if($gst > 0)
                                            <span class="bg-orange-50 text-orange-600 border border-orange-100 px-1.5 py-0.5 rounded text-[9px] md:text-[10px] font-bold">
                                                +{{ $gst }}% GST
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="product-action w-full mt-auto" id="action-admin-{{ $prod->id }}">
                                    @if($isOutOfStock)
                                        <button disabled class="w-full bg-gray-100 text-gray-400 font-bold py-1.5 md:py-2 rounded-lg flex items-center justify-center gap-1 md:gap-2 transition-all duration-200 text-xs md:text-sm cursor-not-allowed border border-gray-200">
                                            OUT OF STOCK <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                    @else
                                        {{-- Passing GST as 6th Parameter --}}
                                        <button onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->product_name) }}', {{ $prod->dp }}, 'admin', {{ $prod->max_coupon_usage ?? 0 }}, {{ $gst }})"
                                            class="w-full bg-[#DBF5EC] hover:bg-[#cbf0e3] active:scale-95 text-teal-900 font-bold py-1.5 md:py-2 rounded-lg flex items-center justify-center gap-1 md:gap-2 transition-all duration-200 text-xs md:text-sm shadow-sm">
                                            ADD <i class="bi bi-bag-plus-fill"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- LOAD MORE BUTTON FOR ADMIN --}}
            @if($adminProducts->hasMorePages())
                <div class="text-center mt-3 mb-4" id="admin-load-more-container">
                    <button onclick="loadMoreProducts('{!! $adminProducts->nextPageUrl() !!}', 'admin')" class="bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-teal-700 font-bold py-2 px-6 rounded-full text-sm shadow-sm transition-all flex items-center justify-center gap-2 mx-auto">
                        Show more Admin Products <i class="bi bi-chevron-down"></i>
                    </button>
                </div>
            @endif

        @else
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800 flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                <span>The admin shop is currently closed. Please check back later for premium packages.</span>
            </div>
        @endif
    </div>
@endif

{{-- Vendor Products Section --}}
@if ($vendorProducts->count() > 0)
    <div class="mb-5">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-shop text-orange-500"></i>
            <span>Vendor Products</span>
        </h5>
        <div class="row g-2 g-md-3" id="vendor-product-grid">
            @foreach ($vendorProducts as $prod)
                @php
                    $isOutOfStock = ($prod->manage_stock == 1 && $prod->stock_quantity <= 0);
                    $gst = $prod->gst ?? 0;
                @endphp
                <div class="col-6 col-md-4 col-lg-3 mb-3">
                    <div class="h-100 max-w-sm rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 ease-in-out bg-white border border-transparent hover:border-orange-100 group flex flex-col {{ $isOutOfStock ? 'opacity-80' : '' }}">

                        {{-- Image Container --}}
                        <div class="relative overflow-hidden rounded-t-xl bg-gray-100 aspect-[4/3]">
                            <img class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110"
                                src="{{ asset($prod->product_image) }}" alt="{{ $prod->product_name }}"
                                loading="lazy">

                            <div class="absolute top-1.5 right-1.5 bg-white/95 backdrop-blur-sm px-1.5 py-0.5 rounded text-[9px] md:text-[10px] font-bold shadow-sm text-gray-700 max-w-[80%] truncate z-30">
                                <i class="bi bi-shop"></i> {{ $prod->vendor->company_name ?? 'Vendor' }}
                            </div>

                            @if($isOutOfStock)
                                <div class="absolute inset-0 bg-white/40 z-10 backdrop-blur-[1px]"></div>
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
                                    <span class="bg-red-600 text-white px-3 py-1.5 rounded-md text-[10px] md:text-xs font-bold uppercase tracking-wider shadow-lg whitespace-nowrap">Out of Stock</span>
                                </div>
                            @endif

                            {{-- Price Overlay --}}
                            <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent w-full p-2 pt-6 flex items-end justify-between {{ $isOutOfStock ? 'z-0' : 'z-10' }}">
                                <div>
                                    <p class="text-gray-300 text-[10px] font-medium uppercase tracking-wider mb-0 leading-none">Price</p>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-white font-extrabold text-lg md:text-xl tracking-tighter shadow-black drop-shadow-md">
                                            ₹{{ number_format($prod->dp, 2) }}
                                        </span>
                                        <span class="text-gray-400 text-[10px] md:text-xs line-through decoration-red-400">₹{{ number_format($prod->mrp, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-2 md:p-3 flex flex-col justify-between flex-grow">
                            <div class="mb-2">
                                <h3 class="font-bold text-sm md:text-base text-gray-800 leading-tight line-clamp-1" title="{{ $prod->product_name }}">
                                    {{ $prod->product_name }}
                                </h3>

                                {{-- PRODUCT DESCRIPTION (MODIFIED FOR READ MORE) --}}
                                <div class="mt-1 mb-2">
                                    <p id="desc-vendor-{{ $prod->id }}" class="text-[10px] md:text-xs text-gray-500 line-clamp-5 transition-all duration-300 m-0" title="{{ $prod->description }}">
                                        {{ $prod->description ?? 'No description available.' }}
                                    </p>
                                    <button id="btn-vendor-{{ $prod->id }}" onclick="toggleDescription('vendor-{{ $prod->id }}')" class="hidden text-orange-600 hover:text-orange-800 text-[10px] md:text-xs font-bold mt-1 bg-transparent border-0 p-0 cursor-pointer">Read more</button>
                                </div>

                                <div class="flex items-center gap-2 flex-wrap">
                                    <div class="flex items-center gap-1">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        <span class="text-[10px] md:text-xs text-gray-500 font-medium">Vendor Verified</span>
                                    </div>
                                    <span class="bg-blue-50 text-blue-700 border border-blue-100 px-1.5 py-0.5 rounded text-[9px] md:text-[10px] font-bold">
                                        PV: {{ $prod->pv ?? 0 }}
                                    </span>
                                    @if($gst > 0)
                                        <span class="bg-orange-50 text-orange-600 border border-orange-100 px-1.5 py-0.5 rounded text-[9px] md:text-[10px] font-bold">
                                            +{{ $gst }}% GST
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="product-action w-full mt-auto" id="action-vendor-{{ $prod->id }}">
                                @if($isOutOfStock)
                                    <button disabled class="w-full bg-gray-100 text-gray-400 font-bold py-1.5 md:py-2 rounded-lg flex items-center justify-center gap-1 md:gap-2 transition-all duration-200 text-xs md:text-sm cursor-not-allowed border border-gray-200">
                                        OUT OF STOCK <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                @else
                                    <button onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->product_name) }}', {{ $prod->dp }}, 'vendor', {{ $prod->max_coupon_usage ?? 0 }}, {{ $gst }})"
                                        class="w-full bg-orange-50 hover:bg-orange-100 active:scale-95 text-orange-900 font-bold py-1.5 md:py-2 rounded-lg flex items-center justify-center gap-1 md:gap-2 transition-all duration-200 text-xs md:text-sm shadow-sm border border-orange-200">
                                        ADD <i class="bi bi-cart-plus-fill"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- LOAD MORE BUTTON FOR VENDORS --}}
        @if($vendorProducts->hasMorePages())
            <div class="text-center mt-3 mb-4" id="vendor-load-more-container">
                <button onclick="loadMoreProducts('{!! $vendorProducts->nextPageUrl() !!}', 'vendor')" class="bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-orange-600 font-bold py-2 px-6 rounded-full text-sm shadow-sm transition-all flex items-center justify-center gap-2 mx-auto">
                    Show more Vendor Products <i class="bi bi-chevron-down"></i>
                </button>
            </div>
        @endif
    </div>
@endif

{{-- No Results State --}}
@if ($adminProducts->isEmpty() && $vendorProducts->isEmpty())
    <div class="text-center py-10">
        <div class="bg-gray-50 rounded-full p-6 inline-block mb-4">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png"
                width="120" class="mx-auto opacity-75 grayscale-[20%]">
        </div>
        <h6 class="font-bold text-gray-600 text-lg">No Items Found</h6>
        <p class="mt-1 text-sm text-gray-400">We couldn't find any products matching your search.</p>
        <button onclick="window.location.reload()" class="mt-4 text-primary text-sm font-semibold hover:underline">Clear Filters</button>
    </div>
@endif

{{-- ADD THIS SCRIPT AT THE VERY BOTTOM OF YOUR BLADE FILE --}}
<script>
    // 1. Function to check if text is actually clamped
    function initializeReadMoreButtons() {
        const descriptions = document.querySelectorAll('p[id^="desc-"]');
        
        descriptions.forEach(desc => {
            // If the actual height of the text is greater than the visible clamped container height
            if (desc.scrollHeight > desc.clientHeight) {
                const buttonId = desc.id.replace('desc-', 'btn-');
                const btn = document.getElementById(buttonId);
                if (btn) {
                    btn.classList.remove('hidden'); // Show the read more button
                }
            }
        });
    }

    // 2. Function to toggle the clamping on click
    function toggleDescription(id) {
        const desc = document.getElementById('desc-' + id);
        const btn = document.getElementById('btn-' + id);
        
        if (desc.classList.contains('line-clamp-5')) {
            desc.classList.remove('line-clamp-5');
            btn.innerText = 'Read less';
        } else {
            desc.classList.add('line-clamp-5');
            btn.innerText = 'Read more';
        }
    }

    // 3. Run the check when the page loads
    document.addEventListener('DOMContentLoaded', initializeReadMoreButtons);
</script>