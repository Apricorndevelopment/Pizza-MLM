@extends('userlayouts.layouts')
@section('title', 'Edit Profile')
@section('container')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white p-3">
                        <h3 class="mb-0">Edit Profile</h3>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab"
                                        data-bs-target="#personal" type="button" role="tab">Personal</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="kyc-tab" data-bs-toggle="tab" data-bs-target="#kyc"
                                        type="button" role="tab">KYC</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="nominee-tab" data-bs-toggle="tab" data-bs-target="#nominee"
                                        type="button" role="tab">Nominee</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank"
                                        type="button" role="tab">Bank</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="password-tab" data-bs-toggle="tab"
                                        data-bs-target="#password" type="button" role="tab">Password</button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="profileTabsContent">
                                <!-- Personal Tab -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 text-center mb-4">
                                            <div class="mb-3">
                                                @if ($user->profile_picture)
                                                    <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                                        class="profile-pic rounded-circle mb-2" id="profile-pic-preview">
                                                @else
                                                    <div class="profile-pic-placeholder rounded-circle mb-2"
                                                        id="profile-pic-preview">
                                                        <i class="fa fa-user-circle fa-5x text-secondary"></i>
                                                    </div>
                                                @endif

                                                <label class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-camera me-1"></i> Change Photo
                                                    <input type="file" class="d-none" id="profile_picture"
                                                        name="profile_picture" accept="image/*">
                                                </label>
                                                @error('profile_picture')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Full Name</label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name', $user->name) }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Email</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email', $user->email) }}" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Phone</label>
                                                    <input type="tel"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        name="phone" value="{{ old('phone', $user->phone) }}">
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Address</label>
                                                    <input type="text"
                                                        class="form-control @error('address') is-invalid @enderror"
                                                        name="address" value="{{ old('address', $user->address) }}">
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">City/State</label>
                                                    <input type="text"
                                                        class="form-control @error('state') is-invalid @enderror"
                                                        name="state" value="{{ old('state', $user->state) }}">
                                                    @error('state')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- KYC Tab -->
                                <div class="tab-pane fade" id="kyc" role="tabpanel">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Aadhaar Number</label>
                                            <input type="text"
                                                class="form-control @error('adhar_no') is-invalid @enderror"
                                                name="adhar_no" value="{{ old('adhar_no', $user->adhar_no) }}">
                                            @error('adhar_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">PAN Number</label>
                                            <input type="text"
                                                class="form-control @error('pan_no') is-invalid @enderror" name="pan_no"
                                                value="{{ old('pan_no', $user->pan_no) }}">
                                            @error('pan_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Aadhaar Card (Front)</label>
                                            <input type="file"
                                                class="form-control @error('adhar_photo') is-invalid @enderror"
                                                name="adhar_photo" accept="image/*">
                                            @if ($user->adhar_photo)
                                                <small class="text-muted">Current: <a
                                                        href="{{ asset('storage/' . $user->adhar_photo) }}"
                                                        target="_blank">View</a></small>
                                            @endif
                                            @error('adhar_photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">PAN Card</label>
                                            <input type="file"
                                                class="form-control @error('pan_photo') is-invalid @enderror"
                                                name="pan_photo" accept="image/*">
                                            @if ($user->pan_photo)
                                                <small class="text-muted">Current: <a
                                                        href="{{ asset('storage/' . $user->pan_photo) }}"
                                                        target="_blank">View</a></small>
                                            @endif
                                            @error('pan_photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Nominee Tab -->
                                <div class="tab-pane fade" id="nominee" role="tabpanel">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Nominee Name</label>
                                            <input type="text"
                                                class="form-control @error('nom_name') is-invalid @enderror"
                                                name="nom_name" value="{{ old('nom_name', $user->nom_name) }}">
                                            @error('nom_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Relationship</label>
                                            <input type="text"
                                                class="form-control @error('nom_relation') is-invalid @enderror"
                                                name="nom_relation"
                                                value="{{ old('nom_relation', $user->nom_relation) }}">
                                            @error('nom_relation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Bank Tab -->
                                <div class="tab-pane fade" id="bank" role="tabpanel">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Bank Name</label>
                                            <input type="text"
                                                class="form-control @error('bank_name') is-invalid @enderror"
                                                name="bank_name" value="{{ old('bank_name', $user->bank_name) }}">
                                            @error('bank_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Account Number</label>
                                            <input type="text"
                                                class="form-control @error('account_no') is-invalid @enderror"
                                                name="account_no" value="{{ old('account_no', $user->account_no) }}">
                                            @error('account_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">IFSC Code</label>
                                            <input type="text"
                                                class="form-control @error('ifsc_code') is-invalid @enderror"
                                                name="ifsc_code" value="{{ old('ifsc_code', $user->ifsc_code) }}">
                                            @error('ifsc_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">UPI ID</label>
                                            <input type="text"
                                                class="form-control @error('upi_id') is-invalid @enderror" name="upi_id"
                                                value="{{ old('upi_id', $user->upi_id) }}">
                                            @error('upi_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Passbook Photo</label>
                                            <input type="file"
                                                class="form-control @error('passbook_photo') is-invalid @enderror"
                                                name="passbook_photo" accept="image/*">
                                            @if ($user->passbook_photo)
                                                <small class="text-muted">Current: <a
                                                        href="{{ asset('storage/' . $user->passbook_photo) }}"
                                                        target="_blank">View</a></small>
                                            @endif
                                            @error('passbook_photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Password Tab -->
                                <div class="tab-pane fade" id="password" role="tabpanel">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Current Password</label>
                                            <input type="password"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                name="current_password">
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">New Password</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" name="password_confirmation">
                                        </div>

                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <small>
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Password must be at least 8 characters long and contain:
                                                    <ul class="mb-0">
                                                        <li>At least 1 uppercase letter</li>
                                                        <li>At least 1 lowercase letter</li>
                                                        <li>At least 1 number</li>
                                                        <li>At least 1 special character</li>
                                                    </ul>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('user.profile') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left me-1"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 3px solid #dee2e6;
        }

        .profile-pic-placeholder {
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border: 3px solid #dee2e6;
        }

        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
            border: none;
            padding: 10px 20px;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            background-color: transparent;
            border-bottom: 3px solid #0d6efd;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #0d6efd;
        }

        .tab-content {
            padding: 20px 0;
        }

        .form-control {
            padding: 10px 15px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Profile picture preview
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            const preview = document.getElementById('profile-pic-preview');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview.classList.contains('profile-pic-placeholder')) {
                        preview.outerHTML =
                            `<img src="${e.target.result}" class="profile-pic rounded-circle mb-2" id="profile-pic-preview">`;
                    } else {
                        preview.src = e.target.result;
                    }
                }
                reader.readAsDataURL(file);
            }
        });

    </script>
@endsection
