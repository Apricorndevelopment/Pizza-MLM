@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

    <div class="container mt-3">
        @session('success')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession
        @session('error')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white py-2">
                <h4 class="mb-0 fs-4 fw-normal">All Registered Members</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30px;">#</th>
                                <th>Name</th>
                                <th>ULID</th>
                                <th>Sponsor</th>
                                <th>Registered</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($member as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->ulid }}</td>
                                    <td>{{ $user->sponsor_id }}</td>
                                    <td>{{ $user->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <span
                                            class="badge @if ($user->status == 'active') bg-success
                                            @elseif($user->status == 'inactive') bg-secondary
                                            @else bg-info @endif">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1 gap-md-2">
                                            <a href="{{ route('admin.editmember', $user->id) }}"
                                                class="btn btn-sm btn-outline-primary px-2">
                                                Edit
                                            </a>
                                            <a href="{{ route('admin.viewmemberdetails', $user->id) }}"
                                                class="btn btn-sm btn-outline-secondary px-2">
                                                View
                                            </a>
                                            <form action="{{ route('admin.deletemember', $user->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2"
                                                    onclick="return confirm('Are you sure you want to delete {{ $user->name }}({{ $user->ulid }}) ?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-2 py-2">
                    {{ $member->links('vendor.pagination.custom-bootstrap') }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .table {
            font-size: 13px;
            min-width: 900px;
        }

        .table th {
            font-weight: 500;
            font-size: 20px;
            padding: 15px 10px;
        }

        .table td {
            padding: 8px 6px;
            vertical-align: middle;
            font-size: 15px;
            padding: 15px 10px;
        }

        .badge {
            padding: 8px 10px;
            font-size: 11px;
        }

        .card-header {
            padding: 8px 12px;
        }

        .btn-sm {
            padding: 5px 8px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .container {
                padding-left: 8px;
                padding-right: 8px;
            }

            .card-header h4 {
                font-size: 15px;
            }
        }
    </style>
@endsection
