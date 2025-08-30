@extends('layouts.layout')
@section('title', 'Edit Package 2')
@section('container')

    <div class="container-fluid p-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Package Type 2</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Validation Error</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.package2.update', $package->id) }}" method="POST" id="packageForm">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <!-- Basic Information Section -->
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Basic
                                Information</h5>
                        </div>

                        <div class="col-md-6">
                            <label for="package_name" class="form-label required">Package Name</label>
                            <input type="text" class="form-control @error('package_name') is-invalid @enderror"
                                id="package_name" name="package_name"
                                value="{{ old('package_name', $package->package_name) }}" required>
                            @error('package_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="package_quantity" class="form-label required">Quantity</label>
                            <input type="number" step="0.01"
                                class="form-control @error('package_quantity') is-invalid @enderror" id="package_quantity"
                                name="package_quantity" value="{{ old('package_quantity', $package->package_quantity) }}"
                                required>
                            @error('package_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label required">Price (&#8377;)</label>
                            <div class="input-group">
                                <span class="input-group-text">&#8377;</span>
                                <input type="number" step="0.01"
                                    class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                                    value="{{ old('price', $package->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="maturity" class="form-label required">Maturity Package</label>
                            <select class="form-select @error('maturity') is-invalid @enderror" name="maturity"
                                id="maturity">
                                <option value="" disabled>Select Option</option>
                                <option value="0" {{ old('maturity', $package->maturity) == '0' ? 'selected' : '' }}>No
                                </option>
                                <option value="1" {{ old('maturity', $package->maturity) == '1' ? 'selected' : '' }}>
                                    Yes</option>
                            </select>
                            @error('maturity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rate Details Section -->
                        <div class="col-12 mt-4">
                            <h5 class="border-bottom pb-2 text-primary mb-3">
                                <i class="bi bi-graph-up me-2"></i>Rate Details
                                <span class="text-danger">*</span>
                            </h5>
                            <div id="rate-details-wrapper">
                                @php
                                    $oldRates = old('rates');
                                    $oldTimes = old('times');
                                    $oldCapitals = old('capitals');
                                    $oldProfitShares = old('profit_shares');
                                @endphp

                                @if ($oldRates)
                                    @foreach ($oldRates as $index => $rate)
                                        <div class="card mb-3 rate-group">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label">Rate (%)</label>
                                                        <input type="number" step="0.01" name="rates[]"
                                                            class="form-control @error('rates.' . $index) is-invalid @enderror"
                                                            value="{{ $rate }}" required>
                                                        @error('rates.' . $index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Time (years)</label>
                                                        <input type="number" step="0.01" name="times[]"
                                                            class="form-control @error('times.' . $index) is-invalid @enderror"
                                                            value="{{ $oldTimes[$index] ?? '' }}">
                                                        @error('times.' . $index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Capital Return</label>
                                                        <input type="text" name="capitals[]"
                                                            class="form-control @error('capitals.' . $index) is-invalid @enderror"
                                                            value="{{ $oldCapitals[$index] ?? '' }}">
                                                        @error('capitals.' . $index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Profit Share</label>
                                                        <select
                                                            class="form-select @error('profit_shares.' . $index) is-invalid @enderror"
                                                            name="profit_shares[]">
                                                            <option value="" disabled>Select option</option>
                                                            <option value="0"
                                                                {{ isset($oldProfitShares[$index]) && $oldProfitShares[$index] == '0' ? 'selected' : '' }}>
                                                                No</option>
                                                            <option value="1"
                                                                {{ isset($oldProfitShares[$index]) && $oldProfitShares[$index] == '1' ? 'selected' : '' }}>
                                                                Yes</option>
                                                        </select>
                                                        @error('profit_shares.' . $index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <button type="button" class="btn btn-outline-danger remove-rate">
                                                            <i class="bi bi-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach ($package->details as $index => $detail)
                                        <div class="card mb-3 rate-group">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label">Rate (%)</label>
                                                        <input type="number" step="0.01" name="rates[]"
                                                            class="form-control" value="{{ $detail->rate }}" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Time (years)</label>
                                                        <input type="number" step="0.01" name="times[]"
                                                            class="form-control" value="{{ $detail->time }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Capital Return</label>
                                                        <input type="text" name="capitals[]" class="form-control"
                                                            value="{{ $detail->capital }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Profit Share</label>
                                                        <select class="form-select" name="profit_shares[]">
                                                            <option value="" disabled>Select option</option>
                                                            <option value="0"
                                                                {{ $detail->profit_share == '0' ? 'selected' : '' }}>No
                                                            </option>
                                                            <option value="1"
                                                                {{ $detail->profit_share == '1' ? 'selected' : '' }}>Yes
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <button type="button" class="btn btn-outline-danger remove-rate">
                                                            <i class="bi bi-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-primary mt-2" id="add-rate-btn">
                                <i class="bi bi-plus-circle"></i> Add Rate
                            </button>
                        </div>

                        <!-- Form Actions -->
                        <div class="col-12 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Update Package
                            </button>
                            <a href="{{ route('admin.package') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-label.required:after {
            content: " *";
            color: #dc3545;
        }

        .card-header {
            border-radius: 0.375rem 0.375rem 0 0 !important;
        }

        .rate-group {
            transition: all 0.3s ease;
        }

        .remove-rate {
            transition: all 0.2s ease;
        }

        .remove-rate:hover {
            transform: scale(1.05);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('rate-details-wrapper');
            let rateCount = {{ $package->details->count() }};

            // Add rate group
            document.getElementById('add-rate-btn').addEventListener('click', function() {
                const html = `
                <div class="card mb-3 rate-group">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">Rate (%)</label>
                                <input type="number" step="0.01" name="rates[]" class="form-control" placeholder="Rate" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Time (years)</label>
                                <input type="number" step="0.01" name="times[]" class="form-control" placeholder="Time">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Capital Return</label>
                                <input type="text" name="capitals[]" class="form-control" placeholder="Capital Return">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Profit Share</label>
                                <select class="form-select" name="profit_shares[]">
                                    <option value="" selected disabled>Select option</option>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger remove-rate">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                wrapper.insertAdjacentHTML('beforeend', html);
                rateCount++;
            });

            // Remove rate group
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-rate') || e.target.closest('.remove-rate')) {
                    const btn = e.target.classList.contains('remove-rate') ? e.target : e.target.closest(
                        '.remove-rate');
                    const group = btn.closest('.rate-group');

                    // Add fade out animation
                    group.style.opacity = '0';
                    setTimeout(() => {
                        group.remove();
                    }, 300);
                }
            });

            // Form validation
            document.getElementById('packageForm').addEventListener('submit', function(e) {
                const rateGroups = document.querySelectorAll('.rate-group');
                if (rateGroups.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one rate detail.');
                    document.getElementById('add-rate-btn').scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

@endsection
