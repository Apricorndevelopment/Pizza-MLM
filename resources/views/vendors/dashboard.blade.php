@extends('vendorlayouts.layout')
@section('title', 'Vendor Dashboard')

@section('container')
    <div class="container-fluid py-4" style="background-color: #f8fafc; min-height: 100vh;">


        @session('success')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession

        <div class="row mb-4">
            <div class="flex justify-between items-center">

                <div class="d-md-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="font-weight-bold mb-1" style="color: #064E3B;">Welcome back, {{ Auth::user()->name }}!
                        </h3>
                        <p class="text-muted mb-0">Here's what's happening with your shop today.</p>
                    </div>
                </div>

                {{-- SHOP STATUS TOGGLE FORM --}}
                <div>
                    <form action="{{ route('vendor.toggleShopStatus') }}" method="POST" class="flex items-center gap-3">
                        @csrf

                        {{-- Status Text --}}
                        <span class="text-sm font-medium text-gray-600">
                            {{-- Use the variable from the controller --}}
                            {{ $isShopOpen ? 'Shop Open' : 'Shop Closed' }}
                        </span>

                        <label class="relative inline-flex items-center cursor-pointer">

                            <input type="hidden" name="status" value="0">

                            {{-- Checkbox --}}
                            <input type="checkbox" name="status" value="1" class="sr-only peer"
                                onchange="this.form.submit()" {{-- Check if the variable is true --}} {{ $isShopOpen ? 'checked' : '' }}>

                            {{-- Design: Red Background (Closed) --}}
                            <div class="w-12 h-6 bg-red-500 rounded-full peer peer-checked:bg-green-600 transition-all">
                            </div>

                            {{-- Design: White Knob --}}
                            <span
                                class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-6"></span>
                        </label>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Stats Cards (Existing Code) --}}
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="p-2" style="background-color: #ECFDF5; border-radius: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 8.25H9m6 3H9m3 6l-3-3h1.5a3 3 0 100-6M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="badge badge-success px-2 py-1"
                                style="background-color: #D1FAE5; color: #065F46; border-radius: 8px;">+0%</span>
                        </div>
                        <h6 class="text-muted font-weight-normal">Total Sales</h6>
                        <h2 class="font-weight-bold mb-0" style="color: #111827;">₹0.00</h2>
                    </div>
                </div>
            </div>

            {{-- ... Other Cards ... --}}
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="p-2" style="background-color: #F0F9FF; border-radius: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Active Products</h6>
                        <h2 class="font-weight-bold mb-0" style="color: #111827;">0</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="p-2" style="background-color: #FEF3C7; border-radius: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Pending Orders</h6>
                        <h2 class="font-weight-bold mb-0" style="color: #111827;">0</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- AJAX Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('shopStatusToggle');
            const label = document.getElementById('statusLabel');
            const toast = document.getElementById('statusToast');

            toggle.addEventListener('change', function() {
                const isChecked = this.checked;
                const status = isChecked ? 1 : 0; // 1 for true, 0 for false

                // Optimistic UI Update
                label.textContent = isChecked ? 'Shop Open' : 'Shop Closed';

                // Send Request
                fetch("{{ route('vendor.toggleShopStatus') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show Success Toast
                            toast.classList.remove('opacity-0');
                            setTimeout(() => {
                                toast.classList.add('opacity-0');
                            }, 2000);
                        } else {
                            // Revert if error
                            toggle.checked = !isChecked;
                            label.textContent = !isChecked ? 'Shop Open' : 'Shop Closed';
                            alert('Something went wrong!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert UI on error
                        toggle.checked = !isChecked;
                        label.textContent = !isChecked ? 'Shop Open' : 'Shop Closed';
                    });
            });
        });
    </script>
@endsection
