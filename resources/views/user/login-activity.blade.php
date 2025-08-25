@extends('userlayouts.layouts')
@section('title', 'Login Activity')
@section('container')

<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-history me-2"></i> Login Activity
            </h5>
        </div>
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="card-body">
            @if($activities->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>IP Address</th>
                            <th>Device & Browser</th>
                            <th>Platform</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        <tr class="{{ $activity->isCurrentSession() ? 'table-success' : '' }}">
                            <td>
                                {{ $activity->login_time->format('d M Y, h:i A') }}
                                @if($activity->logout_time)
                                <br><small class="text-muted">Logged out: {{ $activity->logout_time->format('h:i A') }}</small>
                                @endif
                            </td>
                            <td>{{ $activity->ip_address }}</td>
                            <td>
                                <i class="fas fa-{{ $activity->device_type == 'mobile' ? 'mobile-alt' : 'desktop' }} me-1"></i>
                                {{ $activity->device_type }} - {{ $activity->browser }}
                            </td>
                            <td>{{ $activity->platform }}</td>
                            <td>
                                @if($activity->isCurrentSession())
                                <span class="badge bg-success">Current Session</span>
                                @elseif($activity->logout_time)
                                <span class="badge bg-secondary">Logged Out</span>
                                @else
                                <span class="badge bg-warning">Active</span>
                                @endif
                            </td>
                            <td>
                                @if(!$activity->isCurrentSession())
                                <form action="{{ route('user.login-activity.destroy', $activity->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <nav class="pagination mb-0 mt-2 d-flex justify-content-end">
                {{ $activities->onEachSide(1)->links('pagination::bootstrap-4') }}
            </nav>
            @else
            <div class="alert alert-info">No login activity found.</div>
            @endif

            {{-- <div class="mt-3">
                <form action="{{ route('user.login-activity.logout-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Log out from all other devices?')">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout from All Other Devices
                    </button>
                </form>
            </div> --}}
        </div>
    </div>
</div>

@endsection