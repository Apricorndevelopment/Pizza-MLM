@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

    <div class="container">
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

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white p-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <h4 class="mb-3 mb-md-0 fw-semibold">All Registered Members</h4>
                    
                    <div class="d-flex flex-column flex-md-row gap-2 w-100 w-md-auto">
                        <!-- Search Form -->
                        <form action="{{ route('admin.viewmember') }}" method="GET" class="flex-grow-1">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" name="ulid" class="form-control border-start-0" 
                                       placeholder="Search by ULID..." value="{{ request('ulid') }}">
                                <input type="hidden" name="status" value="{{ $status }}">
                                <button class="btn btn-light border bg-white" type="submit">
                                    <i class="fas fa-arrow-right text-primary"></i>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Status Filter -->
                        <form method="GET" action="{{ route('admin.viewmember') }}" class="w-auto">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white">Status:</span>
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </form>
                        <a class="btn btn-success text-white" href="{{ route('admin.viewmember') }}">Reset</a>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">#</th>
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
                                    <td>{{ ($member->currentPage() - 1) * $member->perPage() + $index + 1 }}</td>
                                    <td class="fw-medium">{{ $user->name }}</td>
                                    <td><code>{{ $user->ulid }}</code></td>
                                    <td>{{ $user->sponsor_id }}</td>
                                    <td>{{ $user->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <span class="badge @if ($user->status == 'active') bg-success
                                            @elseif($user->status == 'inactive') bg-secondary
                                            @else bg-info @endif">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.editmember', $user->id) }}"
                                                class="btn btn-sm btn-outline-primary px-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.viewmemberdetails', $user->id) }}"
                                                class="btn btn-sm btn-outline-info px-2" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.deletemember', $user->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2"
                                                    onclick="return confirm('Are you sure you want to delete {{ $user->name }}({{ $user->ulid }}) ?')"
                                                    title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($member->hasPages())
                <div class="px-3 py-3 border-top">
                    {{ $member->appends(['status' => $status, 'ulid' => request('ulid')])->links('vendor.pagination.custom-bootstrap') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .card-header {
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .table {
            font-size: 14px;
        }
        
        .table th {
            font-weight: 600;
            padding: 12px 10px;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
        }
        
        .table td {
            padding: 12px 10px;
            vertical-align: middle;
        }
        
        .badge {
            padding: 6px 8px;
            font-size: 11px;
            border-radius: 4px;
        }
        
        .btn-sm {
            padding: 5px 8px;
            border-radius: 4px;
        }
        
        .input-group-sm {
            height: 38px;
        }
        
        .input-group-text {
            border-right: none;
        }
        
        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
        
        @media (max-width: 768px) {
            .card-header {
                padding: 12px;
            }
            
            .table-responsive {
                border: 0;
            }
            
            .table th, 
            .table td {
                padding: 8px 5px;
            }
            
            .d-flex.gap-2 {
                gap: 6px !important;
            }
        }
    </style>
@endsection