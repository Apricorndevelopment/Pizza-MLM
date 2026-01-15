@extends('userlayouts.layouts')
@section('title', 'Purchase Package 2')
@section('container')

<div class="container-fluid py-4">
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-box-open me-2"></i> Purchase Package
                </h4>
                <span class="badge bg-white text-primary">
                    <i class="fas fa-wallet me-1"></i>
                    Balance: ₹{{ auth()->user()->wallet1_balance }}
                </span>
            </div>
        </div>

        <div class="card-body p-4">
            @session('success')
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endsession

            @session('error')
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endsession

            <form action="{{ route('package2.process-purchase') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="package2_id" name="package2_id" required>
                                <option value="">Select Package</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->package_name }}</option>
                                @endforeach
                            </select>
                            <label for="package2_id">Package</label>
                            <div class="invalid-feedback">Please select a package</div>
                        </div>
                    </div>

                    <div class="col-md-6" id="rateSelectBox" style="display: none;">
                        <div class="form-floating">
                            <select class="form-select" id="package2_detail_id" name="package2_detail_id" required>
                                <option value="">Select Rate Plan</option>
                            </select>
                            <label for="package2_detail_id">Rate Plan</label>
                            <div class="invalid-feedback">Please select a rate plan</div>
                        </div>
                    </div>

                    <div class="col-md-4" id="quantityBox" style="display: none;">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
                            <label for="quantity">Quantity</label>
                            <div class="invalid-feedback">Please enter valid quantity</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4" id="purchaseSummary" style="display: none;">
                    <div class="card border-primary">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i> Order Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row" id="summaryDetails">
                                <!-- Dynamic content will appear here -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-1 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" onclick="return confirm('Are You sure you want to purchase this package')" class="btn btn-success px-3 py-2" id="confirmButton" style="display: none;">
                        <i class="fas fa-check-circle me-2"></i> Purchase
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Package selection handler
        document.getElementById('package2_id').addEventListener('change', function() {
            const packageId = this.value;
            const rateSelectBox = document.getElementById('rateSelectBox');
            const quantityBox = document.getElementById('quantityBox');
            const purchaseSummary = document.getElementById('purchaseSummary');
            const confirmButton = document.getElementById('confirmButton');

            if (!packageId) {
                [rateSelectBox, quantityBox, purchaseSummary, confirmButton].forEach(el => el.style.display = 'none');
                return;
            }

            // Show loading state
            const rateSelect = document.getElementById('package2_detail_id');
            rateSelect.innerHTML = '<option value="" disabled>Loading rates...</option>';
            rateSelectBox.style.display = 'block';
            quantityBox.style.display = 'none';
            purchaseSummary.style.display = 'none';
            confirmButton.style.display = 'none';

            // Fetch rates
            fetch(`/get-package-rates/${packageId}`)
                .then(response => response.json())
                .then(data => {
                    rateSelect.innerHTML = '<option value="">Select Rate Plan</option>';
                    
                    data.forEach(rate => {
                        const option = document.createElement('option');
                        option.value = rate.id;
                        option.textContent = `${rate.rate}% for ${rate.time ?? 0} years`;
                        option.setAttribute('data-details', JSON.stringify(rate));
                        rateSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    rateSelect.innerHTML = '<option value="">Error loading rates</option>';
                });
        });

        // Rate selection handler
        document.getElementById('package2_detail_id').addEventListener('change', function() {
            if (this.value) {
                document.getElementById('quantityBox').style.display = 'block';
                document.getElementById('purchaseSummary').style.display = 'block';
                document.getElementById('confirmButton').style.display = 'block';
                updateSummary();
            } else {
                document.getElementById('quantityBox').style.display = 'none';
                document.getElementById('purchaseSummary').style.display = 'none';
                document.getElementById('confirmButton').style.display = 'none';
            }
        });

        // Quantity input handler
        document.getElementById('quantity').addEventListener('input', updateSummary);

        function updateSummary() {
            const quantity = document.getElementById('quantity').value;
            const packageId = document.getElementById('package2_id').value;
            const selectedOption = document.querySelector('#package2_detail_id option:checked');

            if (!packageId || !selectedOption || !quantity) return;

            const rateDetails = JSON.parse(selectedOption.getAttribute('data-details'));

            fetch(`/get-package-price/${packageId}`)
                .then(response => response.json())
                .then(packageData => {
                    const finalPrice = packageData.price * quantity;
                    const remainingBalance = packageData.user_balance - finalPrice;
                    const profitShareText = rateDetails.profit_share == 1 ? 
                        '<span class="badge bg-success">Yes</span>' : 
                        '<span class="badge bg-secondary">No</span>';

                    document.getElementById('summaryDetails').innerHTML = `
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted">Package Details</h6>
                                <p class="mb-1"><strong>Package:</strong> ${document.querySelector('#package2_id option:checked').text}</p>
                                <p class="mb-1"><strong>Rate Plan:</strong> ${rateDetails.rate}% for ${rateDetails.time} years</p>
                                <p class="mb-1"><strong>Quantity in One Unit:</strong> ${packageData.quantity_in_one_unit} </p>
                                <p class="mb-1"><strong>Description:</strong> ${packageData.description} </p>
                                <p class="mb-1"><strong>Quantity:</strong> ${quantity}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted">Financial Summary</h6>
                                <p class="mb-1"><strong>Unit Price:</strong> ₹${packageData.price.toLocaleString()}</p>
                                <p class="mb-1"><strong>Total Price:</strong> ₹${finalPrice.toLocaleString()}</p>
                                <p class="mb-1"><strong>Capital Return:</strong> ${rateDetails.capital ?? '0'}%</p>
                                <p class="mb-1"><strong>Profit Share:</strong> ${profitShareText}</p>
                                <p class="mb-1"><strong>Your Balance:</strong> ₹${packageData.user_balance.toLocaleString()}</p>
                                <p class="mb-0 ${remainingBalance < 0 ? 'text-danger' : 'text-success'}">
                                    <strong>Remaining Balance:</strong> ₹${remainingBalance.toLocaleString()}
                                </p>
                            </div>
                        </div>
                    `;
                });
        }
    });
</script>

<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .form-floating label {
        color: #6c757d;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    #purchaseSummary .card {
        border-width: 2px;
    }
    
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-1px);
    }
    
    @media (max-width: 768px) {
        .form-floating {
            margin-bottom: 1rem;
        }
    }
</style>

@endsection