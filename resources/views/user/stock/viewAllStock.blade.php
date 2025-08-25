@extends('userlayouts.layouts')
@section('title', 'View Stock')
@section('container')
<div class="container py-2">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white p-3">
            <h5 class="mb-0">Stock Inventory</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Location</th>
                            <th>Contact</th>
                            <th>Product</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stocks as $stock)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $stock->user->name }}</div>
                                <small class="text-muted">{{ $stock->user_ulid }}</small>
                            </td>
                            <td>{{ $stock->location }}</td>
                            <td>{{ $stock->user->phone }}</td>
                            <td>{{ $stock->product->product_name }}</td>
                            <td>
                                <span class="badge bg-success rounded-pill">{{ $stock->quantity }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection