{{-- @extends('layouts.layout')
@section('title', 'Edit FAQ')
@section('container')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header Section -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.faq.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h2 class="mb-0">Edit FAQ</h2>
            </div>

            <!-- Form Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.faq.update', $faq->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        
                        <!-- Question Field -->
                        <div class="mb-4">
                            <label for="question" class="form-label fw-semibold">Question <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('question') is-invalid @enderror" 
                                   id="question" name="question" value="{{ old('question', $faq->question) }}" 
                                   placeholder="Enter frequently asked question" required>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Answer Field -->
                        <div class="mb-4">
                            <label for="answer" class="form-label fw-semibold">Answer <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('answer') is-invalid @enderror" id="answer" 
                                      name="answer" rows="5" placeholder="Provide a clear and detailed answer" 
                                      required>{{ old('answer', $faq->answer) }}</textarea>
                            @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end pt-3">
                            <a href="{{ route('admin.faq.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Update FAQ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    .btn {
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-1px);
    }
</style>

@endsection --}}