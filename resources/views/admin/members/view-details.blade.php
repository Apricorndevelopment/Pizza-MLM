@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

    <div class="container mt-3">
        <div class="card shadow-sm">
            <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 card-title text-white">Member Details</h4>
                <a class="btn btn-dark py-2 px-3" href="{{ route('admin.viewmember') }}">Go Back</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Personal Info -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Personal Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Name:</label>
                                    <p class="fw-bold">{{ $member->name }}</p>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Email:</label>
                                    <p class="fw-bold">{{ $member->email }}</p>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Phone:</label>
                                    <p class="fw-bold">{{ $member->phone }}</p>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Address:</label>
                                    <p class="fw-bold">{{ $member->address ?? 'Not Filled' }}</p>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-muted small">City/State:</label>
                                    <p class="fw-bold">{{ $member->state ?? 'Not Filled' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Account Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Sponsor ID:</label>
                                    <p class="fw-bold">{{ $member->sponsor_id }}</p>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Parent ID:</label>
                                    <p class="fw-bold">{{ $member->parent_id ?? 'No Parent' }}</p>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Activation Date:</label>
                                    <p class="fw-bold">{{ $member->user_doa ?? 'Not Active' }}</p>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Points Balance:</label>
                                    <p class="fw-bold">{{ $member->wallet1_balance }}</p>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Loyalty Balance:</label>
                                    <p class="fw-bold">{{ $member->wallet2_balance }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Business Info -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Business Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Left Business:</label>
                                    <p class="fw-bold">{{ $member->left_business }}</p>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Right Business:</label>
                                    <p class="fw-bold">{{ $member->right_business }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Bank Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label text-muted small">Account Number:</label>
                                        <p class="fw-bold">{{ $member->account_no ?? 'Not Filled' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label text-muted small">Bank Name:</label>
                                        <p class="fw-bold">{{ $member->bank_name ?? 'Not filled' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label text-muted small">IFSC Code:</label>
                                        <p class="fw-bold">{{ $member->ifsc_code ?? 'Not Filled' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label text-muted small">UPI ID:</label>
                                        <p class="fw-bold">{{ $member->upi_id ?? 'Not Filled' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ID Proofs -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">ID Proofs</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label text-muted small">Aadhar No:</label>
                                        <p class="fw-bold">{{ $member->adhar_no ?? 'Not Filled' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label text-muted small">Pan No:</label>
                                        <p class="fw-bold">{{ $member->pan_no ?? 'Not Filled' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nominee Details -->
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Nominee Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label text-muted small">Nominee Name:</label>
                                        <p class="fw-bold">{{ $member->nom_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label text-muted small">Relation:</label>
                                        <p class="fw-bold">{{ $member->nom_relation ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Images -->
                    <div class="col-md-8 mb-3">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Document Images</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 col-md-3 mb-3 text-center">
                                        <label class="form-label text-muted small d-block">Profile</label>
                                        @if($member->profile_picture)
                                        <img src="{{ asset('storage/'.$member->profile_picture) }}" class="img-thumbnail" style="max-height: 100px;">
                                        @else
                                        <p class="small mt-3">No Profile picture Uploaded</p>
                                        @endif
                                    </div>
                                    <div class="col-6 col-md-3 mb-3 text-center">
                                        <label class="form-label text-muted small d-block">Passbook</label>
                                        @if($member->passbook_photo)
                                        <img src="{{ asset('storage/'.$member->passbook_photo) }}" class="img-thumbnail" style="max-height: 100px;">
                                        @else
                                        <p class="small mt-3">No Passbook photo Uploaded</p>
                                        @endif
                                    </div>
                                    <div class="col-6 col-md-3 mb-3 text-center">
                                        <label class="form-label text-muted small d-block">Aadhar</label>
                                        @if($member->adhar_photo)
                                        <img src="{{ asset('storage/'.$member->adhar_photo) }}" class="img-thumbnail" style="max-height: 100px;">
                                        @else
                                        <p class="small mt-3">No Aadhar Photo Uploaded</p>
                                        @endif
                                    </div>
                                    <div class="col-6 col-md-3 mb-3 text-center">
                                        <label class="form-label text-muted small d-block">PAN</label>
                                        @if($member->pan_photo)
                                        <img src="{{ asset('storage/'.$member->pan_photo) }}" class="img-thumbnail" style="max-height: 100px;">
                                        @else
                                        <p class="small mt-3">No PAN Card Photo Uploaded</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 0.5rem;
        }
        .card-header {
            padding: 0.75rem 1.25rem;
        }
        .form-label {
            margin-bottom: 0.25rem;
        }
        .img-thumbnail {
            padding: 0.25rem;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            height: 100px;
            width: 100px;
        }
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }
            .card-header h5 {
                font-size: 1rem;
            }
        }
    </style>
@endsection