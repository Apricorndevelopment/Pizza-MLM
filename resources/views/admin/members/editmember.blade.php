@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Edit Member Details</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.updatemember', $member->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Personal Information Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Full Name</label>
                                <input type="text" class="form-control p-2" id="name" name="name"
                                    value="{{ old('name', $member->name) }}" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control p-2" id="email" name="email"
                                    value="{{ old('email', $member->email) }}" required>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold">Phone</label>
                                <input type="text" class="form-control p-2" id="phone" name="phone"
                                    value="{{ old('phone', $member->phone) }}">
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Information Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label fw-bold">Address</label>
                                <input type="text" class="form-control p-2" id="address" name="address"
                                    value="{{ old('address', $member->address) }}">
                                @error('address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="state" class="form-label fw-bold">City/State</label>
                                <input type="text" class="form-control p-2" id="state" name="state"
                                    value="{{ old('state', $member->state) }}">
                                @error('state')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label fw-bold">Status</label>
                                <select class="form-select p-2" id="status" name="status">
                                    <option value="active" {{ $member->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $member->status == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password </label>
                        <input type="password" class="form-control p-2" id="password" name="password">
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                        <input type="password" class="form-control p-2" id="password_confirmation"
                            name="password_confirmation">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.viewmember') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Members
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
