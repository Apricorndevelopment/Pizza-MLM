@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Shop Products')

@section('container')
    <style>
        /* Product Card Styles */
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #eee;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .vendor-badge {
            font-size: 0.7rem;
            background: #f8f9fa;
            color: #666;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Sticky Bar */
        .sticky-cart-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 15px;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
            z-index: 999;
            display: none;
            /* Hidden by default */
        }

        /* Loader */
        #search-loader {
            display: none;
            position: absolute;
            right: 100px;
            top: 12px;
            color: #666;
        }

        /* Modal Styles */
        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .tracking-wide {
            letter-spacing: 0.05em;
        }

        .hover-scale:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        /* Scrollbar */
        #cartItemsList::-webkit-scrollbar {
            width: 6px;
        }

        #cartItemsList::-webkit-scrollbar-thumb {
            background-color: #dee2e6;
            border-radius: 4px;
        }

        #cartItemsList::-webkit-scrollbar-track {
            background-color: transparent;
        }

        /* Remove arrows/spinners from number input */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
            /* Firefox */
        }
    </style>

    <div class="container py-2">
        @if (session('success'))
            <div id="alert-success" class="mb-4 alert alert-success d-flex justify-content-between align-items-center">
                <div><i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div id="alert-error" class="mb-4 alert alert-danger d-flex justify-content-between align-items-center">
                <div><i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="sticky z-10 mb-4">
            <div class="container mx-auto max-w-3xl px-4">
                <div class="relative">

                    <div
                        class="group bg-white rounded-full flex items-center p-1.5 shadow-[0_4px_20px_rgba(0,0,0,0.08)] border border-gray-100 transition-all duration-300 focus-within:-translate-y-0.5 focus-within:shadow-[0_8px_25px_rgba(0,0,0,0.12)] focus-within:border-gray-200">

                        <div class="pl-5 pr-2 text-gray-400 text-lg flex items-center justify-center">
                            <i class="bi bi-search"></i>
                        </div>

                        <input type="text" id="searchInput"
                            class="w-full bg-transparent border-none focus:ring-0 text-gray-700 placeholder-gray-400 text-base h-full outline-none py-2"
                            value="{{ $query }}" placeholder="Search for products (e.g. Pizza, Shoes)..."
                            autocomplete="off">

                        <div id="search-loader" class="hidden mr-3">
                            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        <button type="button" onclick="performSearch()"
                            class="bg-gradient-to-br from-gray-900 to-gray-800 text-white font-bold rounded-full px-8 py-2.5 shadow-md hover:scale-105 hover:shadow-lg active:scale-95 transition-all duration-200 flex-shrink-0">
                            Search
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div id="products-container">
            @include('user.shop.partials.products')
        </div>
    </div>

    <div class="sticky-cart-bar" id="cartBar">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-bold"><span id="cartCount">0</span> Items Added</h6>
                <small class="text-muted">Total: ₹<span id="cartTotal">0.00</span></small>
            </div>
            <button onclick="renderCartModal()" data-bs-toggle="modal" data-bs-target="#cartModal"
                class="bg-[#DAF4EB] text-teal-900 hover:bg-[#cbf0e3] hover:-translate-y-0.5 active:scale-95 transition-all duration-200 shadow-sm hover:shadow-md font-bold px-6 py-2.5 rounded-full flex items-center gap-2">
                View Cart <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>

    <div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem; overflow: hidden;">

                {{-- Modal Header --}}
                <div class="modal-header bg-white border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-bag-check-fill text-primary"></i> Review Your Cart
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body p-0">
                    <div class="row g-0">

                        {{-- LEFT COLUMN: Cart Items --}}
                        <div class="col-lg-7 bg-white">
                            <div class="p-4 d-flex flex-column h-100">
                                <h6 class="text-muted text-uppercase small fw-bold mb-3 tracking-wide">
                                    <i class="bi bi-basket3 me-1"></i> Items Selected
                                </h6>
                                <div class="flex-grow-1" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                                    <div id="cartItemsList" class="d-flex flex-column gap-2">
                                        {{-- Items injected via JS --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: Payment Summary --}}
                        <div class="col-lg-5 bg-light border-start">
                            <div class="p-4 h-100 d-flex flex-column justify-content-between overflow-y-auto">
                                <div>
                                    {{-- 1. CONTACT DETAILS (NEW) --}}
                                    <div class="mb-3">
                                        <h6 class="text-muted text-uppercase small fw-bold mb-2 tracking-wide">
                                            <i class="bi bi-geo-alt me-1"></i> Delivery Details
                                        </h6>
                                        <div class="bg-white p-3 rounded-3 shadow-sm border">
                                            <div class="mb-2">
                                                <label class="text-xs text-muted">Phone Number</label>
                                                <input type="text" name="phone_number" form="checkoutForm"
                                                    class="form-control form-control-sm bg-light" required
                                                    placeholder="Enter Phone">
                                            </div>
                                            <div class="mb-2">
                                                <label class="text-xs text-muted">Location (City/Area)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="location" id="locationInput"
                                                        form="checkoutForm" class="form-control bg-light" required
                                                        placeholder="Enter Location">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        onclick="getLocation()" title="Get Current Location">
                                                        <i class="bi bi-geo-alt-fill"></i>
                                                    </button>
                                                </div>
                                                <small id="locationStatus" class="text-xs text-muted"></small>
                                            </div>
                                            <div class="mb-0">
                                                <label class="text-xs text-muted">Full Address</label>
                                                <textarea name="address" form="checkoutForm" class="form-control form-control-sm bg-light" rows="2" required
                                                    placeholder="House No, Street..."></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 2. COUPON SECTION (Kept same logic, just minor layout tweak if needed) --}}
                                    <div class="mb-3">
                                        <h6 class="text-muted text-uppercase small fw-bold mb-2 tracking-wide">
                                            <i class="bi bi-ticket-perforated me-1"></i> Apply Coupons
                                        </h6>
                                        <div
                                            class="card border border-warning border-opacity-50 shadow-sm bg-warning bg-opacity-10">
                                            <div class="card-body p-2">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="small fw-bold text-dark">Your Balance</span>
                                                    <span
                                                        class="badge bg-warning text-dark border border-warning shadow-sm">
                                                        <i class="bi bi-ticket-fill"></i> <span
                                                            id="userCouponDisplay">{{ $userCouponCount }}</span>
                                                    </span>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-between bg-white rounded-3 p-1 border shadow-sm">
                                                    <button class="btn btn-sm btn-light text-warning fw-bold border-0 px-3"
                                                        onclick="updateCouponQty(-1)" type="button"><i
                                                            class="bi bi-dash-lg"></i></button>
                                                    <input type="number" id="couponSelect" value="0"
                                                        class="form-control form-control-sm border-0 text-center fw-bold bg-transparent text-dark shadow-none"
                                                        style="width: 50px;" oninput="validateCouponInput(this)">
                                                    <button class="btn btn-sm btn-light text-warning fw-bold border-0 px-3"
                                                        onclick="updateCouponQty(1)" type="button"><i
                                                            class="bi bi-plus-lg"></i></button>
                                                </div>
                                                <div class="d-flex justify-content-between mt-2 text-xs text-muted">
                                                    <span>1 Coupon = ₹10 OFF</span>
                                                    <span class="fw-bold text-warning-emphasis" id="maxCouponMessage">Max:
                                                        0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 3. WALLET 2 (UPDATED to Input) --}}
                                    <div class="mb-3">
                                        <h6 class="text-muted text-uppercase small fw-bold mb-2 tracking-wide">
                                            <i class="bi bi-wallet2 me-1"></i> Cashback Wallet
                                        </h6>
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="small fw-bold text-secondary">Wallet 2</span>
                                                    <span class="badge bg-primary bg-opacity-10 text-white">
                                                        Bal: ₹{{ number_format(Auth::user()->wallet2_balance, 2) }}
                                                    </span>
                                                </div>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-white border-end-0">₹</span>
                                                    <input type="number" id="wallet2InputRaw"
                                                        class="form-control bg-white border-start-0 shadow-none"
                                                        placeholder="Enter amount" min="0"
                                                        max="{{ Auth::user()->wallet2_balance }}"
                                                        oninput="calculateFinalTotal()">
                                                </div>
                                                <div class="text-xs text-muted mt-1 ms-1">Enter amount to redeem (Max:
                                                    ₹{{ number_format(Auth::user()->wallet2_balance, 2) }})</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 4. BILL SUMMARY --}}
                                    <div class="vstack gap-2 border-bottom pb-3 mb-2">
                                        <div class="d-flex justify-content-between text-sm">
                                            <span class="text-secondary">Subtotal (DP)</span>
                                            <span class="fw-bold text-dark">₹<span id="modalTotal">0.00</span></span>
                                        </div>
                                        <div class="d-flex justify-content-between text-sm text-warning text-opacity-100">
                                            <span><i class="bi bi-tag-fill me-1"></i> Coupon Discount</span>
                                            <span class="fw-bold">- ₹<span id="modalCouponDisc">0.00</span></span>
                                        </div>
                                        <div class="d-flex justify-content-between text-sm text-success">
                                            <span><i class="bi bi-dash-circle me-1"></i> Cashback Applied</span>
                                            <span class="fw-bold">- ₹<span id="modalW2">0.00</span></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- 5. FINAL PAY --}}
                                <div>
                                    <div class="d-flex justify-content-between align-items-end mb-1">
                                        <span class="small text-muted fw-bold">PAYABLE AMOUNT</span>
                                        <span class="fs-6 fw-bolder text-dark">₹<span id="modalW1">0.00</span></span>
                                    </div>

                                    <div class="text-end mb-3">
                                        <small class="text-muted">
                                            Via Main Wallet (Bal: <span
                                                class="fw-bold text-dark">₹{{ number_format(Auth::user()->wallet1_balance, 2) }}</span>)
                                        </small>
                                    </div>

                                    <form action="{{ route('user.shop.purchase') }}" method="POST" id="checkoutForm">
                                        @csrf
                                        <input type="hidden" name="cart" id="cartInput">
                                        <input type="hidden" name="wallet2_usage" id="wallet2Input">
                                        <input type="hidden" name="coupons_used" id="couponInput">

                                        <button type="submit"
                                            class="btn bg-black text-white w-100 py-1 fw-bold rounded-3 shadow-sm transition-all hover-scale">
                                            Confirm Payment <i class="bi bi-arrow-right-short fs-5 align-middle"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ==========================================
        // 1. Toast & Alert Auto-Dismiss
        // ==========================================
        function dismissAlert(alertId) {
            const element = document.getElementById(alertId);
            if (element) {
                element.style.opacity = '0';
                setTimeout(() => element.remove(), 500);
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('[id^="alert-"]').forEach(el => dismissAlert(el.id));
            }, 50000);
        });

        // ==========================================
        // 2. Search Logic
        // ==========================================
        let debounceTimer;
        const searchInput = document.getElementById('searchInput');
        const productsContainer = document.getElementById('products-container');

        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                clearTimeout(debounceTimer);
                let query = this.value;
                debounceTimer = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });
        }

        function performSearch(query = '') {
            const xhr = new XMLHttpRequest();
            let url = "{{ route('user.shop.index') }}" + "?search=" + encodeURIComponent(query);
            xhr.open('GET', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (this.status >= 200) productsContainer.innerHTML = this.response;
            };
            xhr.send();
        }

        // ==========================================
        // 3. Cart & Coupon Logic (THE FIX)
        // ==========================================

        const userWallet2 = {{ Auth::user()->wallet2_balance }};
        const userCouponBalance = {{ $userCouponCount }};
        let cart = {};

        // Global variable to store the calculated limit
        let currentMaxCoupons = 0;

        // 1. ADD TO CART - Now saves max_coupon
        function addToCart(id, name, price, type, max_coupon = 0) {
            let key = type + '_' + id;
            if (!cart[key]) {
                cart[key] = {
                    id: id,
                    name: name,
                    price: price,
                    type: type,
                    qty: 1,
                    max_coupon: parseInt(max_coupon) // Ensure it's a number
                };
            } else {
                cart[key].qty++;
            }
            updateUI();
        }

        // 2. UPDATE ITEM QTY
        function updateQty(key, change) {
            if (cart[key]) {
                cart[key].qty += change;
                if (cart[key].qty <= 0) delete cart[key];
            }
            updateUI();
            renderCartModal();
        }

        // 3. UPDATE UI (Calculates Limits)
        function updateUI() {
            let count = 0;
            let total = 0;

            // YOUR LOGIC IMPLEMENTATION:
            // Calculate total allowed coupons based on cart items
            let cartAllowedCoupons = 0;

            for (let key in cart) {
                let item = cart[key];
                count += item.qty;
                total += item.qty * item.price;

                // Logic: (Item Qty * Item Max Coupon Limit)
                // Example: 2 qty * 2 limit = 4 coupons allowed for this item
                cartAllowedCoupons += (item.qty * item.max_coupon);
            }

            // Determine the REAL limit:
            // It is the smaller number between: 
            // A) What the user has in their account
            // B) What the items in the cart allow
            currentMaxCoupons = Math.min(userCouponBalance, cartAllowedCoupons);

            // Update Standard UI Elements
            if (document.getElementById('cartCount')) document.getElementById('cartCount').innerText = count;
            if (document.getElementById('cartTotal')) document.getElementById('cartTotal').innerText = total.toFixed(2);
            if (document.getElementById('cartBar')) document.getElementById('cartBar').style.display = count > 0 ? 'block' :
                'none';
            if (document.getElementById('modalTotal')) document.getElementById('modalTotal').innerText = total.toFixed(2);

            // Update Coupon Limit Text
            const msgEl = document.getElementById('maxCouponMessage');
            if (msgEl) msgEl.innerText = `Max allowed: ${currentMaxCoupons}`;

            // Validate Input (Reset if current value > new limit)
            const inputEl = document.getElementById('couponSelect');
            if (inputEl) {
                let currentVal = parseInt(inputEl.value) || 0;
                if (currentVal > currentMaxCoupons) inputEl.value = currentMaxCoupons;
                if (currentMaxCoupons === 0) inputEl.value = 0;
            }

            calculateFinalTotal();
        }

        // 4. STEPPER FUNCTION (+ / - Buttons)
        function updateCouponQty(change) {
            console.log("Stepper Clicked. Change:", change); // Debugging

            const inputEl = document.getElementById('couponSelect');
            if (!inputEl) return;

            let currentVal = parseInt(inputEl.value) || 0;
            let newVal = currentVal + change;

            // Enforce Limits using the calculated global variable
            if (newVal < 0) newVal = 0;
            if (newVal > currentMaxCoupons) newVal = currentMaxCoupons;

            inputEl.value = newVal;
            calculateFinalTotal();
        }

        // 5. CALCULATE FINALS (Updated for flexible Wallet 2)
        function calculateFinalTotal() {
            const modalTotalEl = document.getElementById('modalTotal');
            if (!modalTotalEl) return;

            let total = parseFloat(modalTotalEl.innerText);

            // 1. Coupon Logic
            let coupons = parseInt(document.getElementById('couponSelect').value) || 0;
            let couponDiscount = coupons * 10;

            if (couponDiscount > total) {
                couponDiscount = total;
                coupons = Math.floor(total / 10);
                document.getElementById('couponSelect').value = coupons;
            }

            let remainingAfterCoupon = total - couponDiscount;

            // 2. Wallet 2 Logic (Flexible Input)
            const wallet2InputEl = document.getElementById('wallet2InputRaw');
            let w2Entered = parseFloat(wallet2InputEl.value) || 0;

            // Constraints for Wallet 2
            // A. Cannot exceed user balance
            if (w2Entered > userWallet2) {
                w2Entered = userWallet2;
                wallet2InputEl.value = w2Entered.toFixed(2);
            }
            // B. Cannot exceed remaining total after coupons
            if (w2Entered > remainingAfterCoupon) {
                w2Entered = remainingAfterCoupon;
                wallet2InputEl.value = w2Entered.toFixed(2);
            }

            // 3. Wallet 1 Logic (Remainder)
            let w1 = remainingAfterCoupon - w2Entered;

            // Update UI
            document.getElementById('modalCouponDisc').innerText = couponDiscount.toFixed(2);
            document.getElementById('modalW2').innerText = w2Entered.toFixed(2);
            document.getElementById('modalW1').innerText = w1.toFixed(2);

            // Update Form Inputs
            document.getElementById('cartInput').value = JSON.stringify(Object.values(cart));
            document.getElementById('wallet2Input').value = w2Entered;
            document.getElementById('couponInput').value = coupons;
        }

        // Validate manual typing in Coupon Input
        function validateCouponInput(input) {
            let val = parseInt(input.value) || 0;

            // Cannot be less than 0
            if (val < 0) {
                input.value = 0;
            }

            // Cannot be more than the dynamically calculated max coupons
            if (val > currentMaxCoupons) {
                input.value = currentMaxCoupons;
            }

            // Update the totals
            calculateFinalTotal();
        }

        // REMOVE the function 'populateWallet2Options' calls from 'updateUI', it is no longer needed.

        // 6. WALLET 2 DROPDOWN
        // function populateWallet2Options(total) {
        //     let select = document.getElementById('wallet2Select');
        //     if (!select) return;
        //     let currentSelection = parseInt(select.value) || 0;
        //     select.innerHTML = '<option value="0">Do not use Cashback</option>';
        //     let maxRedeem = Math.min(userWallet2, total);
        //     for (let i = 50; i <= maxRedeem; i += 50) {
        //         let option = document.createElement('option');
        //         option.value = i;
        //         option.text = `Use ₹${i}`;
        //         if (i === currentSelection) option.selected = true;
        //         select.appendChild(option);
        //     }
        // }

        // 7. RENDER CART LIST
        function renderCartModal() {
            let container = document.getElementById('cartItemsList');
            if (!container) return;
            container.innerHTML = '';

            if (Object.keys(cart).length === 0) {
                container.innerHTML = `<div class="text-center py-5 opacity-50"><p>Your cart is empty.</p></div>`;
                return;
            }

            for (let key in cart) {
                let item = cart[key];
                let html = `
                <div class="d-flex align-items-center p-2 bg-white border rounded-3 shadow-sm mb-2">
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 fw-semibold text-dark">${item.name}</h6>
                        <small class="text-muted">₹${item.price} x ${item.qty}</small>
                        <div style="font-size:10px; color:orange;">Max Coupons: ${item.max_coupon * item.qty}</div>
                    </div>
                    <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 mx-2">
                        <button class="btn btn-sm p-0 border-0" onclick="updateQty('${key}', -1)"><i class="bi bi-dash"></i></button>
                        <span class="mx-2 fw-bold small">${item.qty}</span>
                        <button class="btn btn-sm p-0 border-0" onclick="updateQty('${key}', 1)"><i class="bi bi-plus"></i></button>
                    </div>
                    <div class="fw-bold">₹${(item.price * item.qty).toFixed(2)}</div>
                </div>`;
                container.insertAdjacentHTML('beforeend', html);
            }
        }


        // ==========================================
        // 8. GEOLOCATION LOGIC
        // ==========================================
        function getLocation() {
            const status = document.getElementById('locationStatus');
            const input = document.getElementById('locationInput');

            if (!navigator.geolocation) {
                status.innerText = "Geolocation is not supported by your browser";
                status.className = "text-xs text-danger";
                return;
            }

            status.innerText = "Locating...";
            status.className = "text-xs text-info";

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    // Use OpenStreetMap Nominatim API (Free)
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                        .then(response => response.json())
                        .then(data => {
                            // Try to find the most relevant city/area name
                            const address = data.address;
                            const city = address.city || address.town || address.village || address.suburb || '';
                            const state = address.state || '';

                            // Format: City, State (or just City if state is missing)
                            let locationString = city;
                            if (city && state) locationString += `, ${state}`;
                            else if (!city && state) locationString = state;
                            else if (!city && !state) locationString = "Location Found";

                            input.value = locationString;
                            status.innerText = "";
                        })
                        .catch(() => {
                            status.innerText = "Unable to retrieve address.";
                            status.className = "text-xs text-danger";
                        });
                },
                () => {
                    status.innerText = "Unable to retrieve your location.";
                    status.className = "text-xs text-danger";
                }
            );
        }
    </script>
@endsection
