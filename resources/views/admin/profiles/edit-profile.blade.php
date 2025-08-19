@extends('layouts.layout')
@section('title', 'Edit Profile')
@section('container')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Edit Profile</h3>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-4">
                                <div class="col-md-4 text-center">
                                    <!-- Simplified Profile Picture Upload -->
                                    <div class="mb-3">

                                        @if ($user->profile_picture)
                                            <img src="{{ asset('storage/profile-pictures/' . basename($user->profile_picture)) }}"
                                                class="profile-pic rounded-circle mb-2" id="profile-pic-preview">
                                        @else
                                            <div class="profile-pic-placeholder rounded-circle mb-2"
                                                id="profile-pic-preview">
                                                <i class="fa fa-user-circle fa-5x text-secondary"></i>
                                            </div>
                                        @endif

                                        <label class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-camera me-1"></i> Change Photo
                                            <input type="file" class="d-none" id="profile_picture" name="profile_picture"
                                                accept="image/*">
                                        </label>
                                        @error('profile_picture')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <!-- Personal Information -->
                                    <div class="mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror p-3"
                                            name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror p-3"
                                            name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Password Change Section -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Change Password</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Current Password</label>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror p-2"
                                            name="current_password">
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror p-2"
                                            name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control p-2" name="password_confirmation">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.profile') }}" class="btn btn-secondary">
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

        .card {
            border-radius: 10px;
        }

        /* Hide the default file input */
        input[type="file"] {
            position: absolute;
            opacity: 0;
        }

        .card-header.bg-light {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6;
        }
    </style>

    <script>
        // Simple image preview for profile picture
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            const preview = document.getElementById('profile-pic-preview');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    if (preview.classList.contains('profile-pic-placeholder')) {
                        // Replace placeholder with image
                        preview.outerHTML =
                            `<img src="${e.target.result}" class="profile-pic rounded-circle mb-2" id="profile-pic-preview">`;
                    } else {
                        // Update existing image
                        preview.src = e.target.result;
                    }
                }

                reader.readAsDataURL(file);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            form.addEventListener('submit', function(e) {
                const currentPass = form.querySelector('[name="current_password"]').value;
                const newPass = form.querySelector('[name="password"]').value;
                const confirmPass = form.querySelector('[name="password_confirmation"]').value;

                if (currentPass && (!newPass || !confirmPass)) {
                    e.preventDefault();
                    alert('Please fill both new password and confirmation fields');
                } else if (newPass !== confirmPass) {
                    e.preventDefault();
                    alert('New password and confirmation do not match');
                }
            });
        });
    </script>
@endsection
