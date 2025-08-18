@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-2xl">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Hello, {{ $user->name }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <!-- Profile picture display -->
                                @if ($user->profile_picture)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/profile-pictures/' . basename($user->profile_picture)) }}"
                                            class="profile-pic img-thumbnail rounded-circle" alt="Profile Picture">
                                    </div>
                                @else
                                    <div class="profile-pic-placeholder mb-3">
                                        <i class="fa fa-user-circle fa-5x text-secondary"></i>
                                    </div>
                                @endif

                            </div>

                            <div class="col-md-8">
                                <div class="row g-3">
                                    <!-- Personal Info -->
                                    <div class="col-md-6">
                                        <h5 class="text-muted">Personal Information</h5>
                                        <p><strong>AUID:</strong> {{ $user->auid }}</p>
                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                                <i class="fa fa-edit me-2"></i> Edit Profile
                            </a>
                        </div>
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
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f8f9fa;
            border: 3px solid #dee2e6;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }
    </style>
@endsection
