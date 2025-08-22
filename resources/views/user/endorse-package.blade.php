@extends('userlayouts.layouts')
@section('title', 'Endorse Package')
@section('container')

<div class="container-fluid py-4">
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-success text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-exchange-alt me-2"></i> Endorse Maturity Package
                </h4>
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

            <!-- Maturity Package Info -->
            <div class="card mb-1 border-success">
                <div class="card-header bg-light-success">
                    <h5 class="mb-0">Maturity Package Being Endorsed</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Package Name:</strong> {{ $maturityPackage->package_name }}</p>
                            <p><strong>Quantity:</strong> {{ $maturityPackage->quantity }}</p>
                            <p><strong>Purchase Price:</strong> ₹{{ number_format($maturityPackage->final_price, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Rate:</strong> {{ $maturityPackage->rate }}%</p>
                            <p><strong>Time:</strong> {{ $maturityPackage->time }} years</p>
                            <p><strong>Purchase Date:</strong> {{ $maturityPackage->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Conversion Rule:</strong> 2 maturity packages = 1 regular package
                    </div>
                </div>
            </div>

            <form action="{{ route('user.packages.process-endorsement') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" name="maturity_package_id" value="{{ $maturityPackage->id }}">

                <div class="row g-3">
                    <!-- Regular Package Selection -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="regular_package_id" name="regular_package_id" required>
                                <option value="">Select Regular Package</option>
                                @foreach($regularPackages as $package)
                                    <option value="{{ $package->id }}">{{ $package->package_name }}</option>
                                @endforeach
                            </select>
                            <label for="regular_package_id">Convert To Package</label>
                            <div class="invalid-feedback">Please select a regular package</div>
                        </div>
                    </div>

                    <!-- Rate Plan Selection -->
                    <div class="col-md-6" id="rateSelectBox" style="display: none;">
                        <div class="form-floating">
                            <select class="form-select" id="package_detail_id" name="package_detail_id" required>
                                <option value="">Select Rate Plan</option>
                            </select>
                            <label for="package_detail_id">Rate Plan</label>
                            <div class="invalid-feedback">Please select a rate plan</div>
                        </div>
                    </div>
                </div>

                <!-- Package Details Summary -->
                <div class="mt-4" id="packageSummary" style="display: none;">
                    <div class="card border-primary">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Conversion Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row" id="summaryDetails">
                                <!-- Dynamic content will appear here -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('user.packages') }}" class="btn btn-secondary me-md-2">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success" id="confirmButton" style="display: none;">
                        <i class="fas fa-check-circle me-1"></i> Confirm Endorsement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const maturityQuantity = {{ $maturityPackage->quantity }};
        
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
        document.getElementById('regular_package_id').addEventListener('change', function() {
            const packageId = this.value;
            const rateSelectBox = document.getElementById('rateSelectBox');
            const packageSummary = document.getElementById('packageSummary');
            const confirmButton = document.getElementById('confirmButton');

            if (!packageId) {
                [rateSelectBox, packageSummary, confirmButton].forEach(el => el.style.display = 'none');
                return;
            }

            // Show loading state
            const rateSelect = document.getElementById('package_detail_id');
            rateSelect.innerHTML = '<option value="" disabled>Loading rates...</option>';
            rateSelectBox.style.display = 'block';
            packageSummary.style.display = 'none';
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
        document.getElementById('package_detail_id').addEventListener('change', function() {
            if (this.value) {
                document.getElementById('packageSummary').style.display = 'block';
                document.getElementById('confirmButton').style.display = 'block';
                updateSummary();
            } else {
                document.getElementById('packageSummary').style.display = 'none';
                document.getElementById('confirmButton').style.display = 'none';
            }
        });

        function updateSummary() {
            const selectedOption = document.querySelector('#package_detail_id option:checked');
            const packageId = document.getElementById('regular_package_id').value;
            const packageName = document.querySelector('#regular_package_id option:checked').textContent;

            if (!packageId || !selectedOption) return;

            const rateDetails = JSON.parse(selectedOption.getAttribute('data-details'));
            const profitShareText = rateDetails.profit_share == 1 ? 
                '<span class="badge bg-success">Yes</span>' : 
                '<span class="badge bg-secondary">No</span>';

            // Calculate how many regular packages user will get
            const regularPackagesCount = Math.floor(maturityQuantity * 0.5);

            document.getElementById('summaryDetails').innerHTML = `
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-muted">New Package Details</h6>
                        <p class="mb-1"><strong>Package:</strong> ${packageName}</p>
                        <p class="mb-1"><strong>Rate Plan:</strong> ${rateDetails.rate}% for ${rateDetails.time} years</p>
                        <p class="mb-1"><strong>Capital Return:</strong> ${rateDetails.capital ?? '0'}%</p>
                        <p class="mb-1"><strong>Profit Share:</strong> ${profitShareText}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-muted">Conversion Details</h6>
                        <p class="mb-1"><strong>Current Maturity Quantity:</strong> ${maturityQuantity}</p>
                        <p class="mb-1"><strong>You Will Get:</strong> ${regularPackagesCount} regular package(s)</p>
                        <p class="mb-1"><strong>Value:</strong> ₹{{ number_format($maturityPackage->final_price, 2) }}</p>
                        <p class="mb-0 text-success"><strong>Status:</strong> Converting to regular package</p>
                    </div>
                </div>
            `;
        }
    });
</script>

<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .bg-light-success {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
    
    .border-success {
        border-color: #28a745 !important;
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
</style>

@endsection