{{-- @extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">FAQ Management</h2>
            <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New FAQ
            </a>
        </div>

        @session('success')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession

        @if($faqs->count() > 0)
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">#</th>
                                    <th scope="col">Question</th>
                                    <th scope="col">Answer</th>
                                    <th scope="col" class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($faqs as $index => $faq)
                                    <tr>
                                        <th scope="row" class="ps-4">{{ $index + 1 }}</th>
                                        <td class="fw-semibold">{{ Str::limit($faq->question, 50) }}</td>
                                        <td>{{ Str::limit($faq->answer, 70) }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('admin.faq.edit', $faq->id) }}" 
                                                   class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                                    <i class="bi bi-pencil me-1"></i>Edit
                                                </a>
                                                <form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center"
                                                            onclick="return confirm('Are you sure you want to delete this FAQ?')">
                                                        <i class="bi bi-trash me-1"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="py-5">
                    <i class="bi bi-question-circle display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">No FAQs Found</h4>
                    <p class="text-muted">Get started by creating your first FAQ</p>
                    <a href="{{ route('admin.faq.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle me-2"></i>Create FAQ
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection --}}