@extends('layouts.layout')
@section('title', 'Manage PDF Files')
@section('container')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-pdf me-2"></i>Manage PDF Files
                        </h4>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- English PDF Section -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-language me-2"></i>English PDF
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Current File:</label>
                                        <div class="d-flex align-items-center justify-content-between p-2 border rounded">
                                            <span>English-Geokranti.pdf</span>
                                            <a href="/English-Geokranti.pdf" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </div>
                                    </div>

                                    <form action="{{ route('admin.pdf.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="english_pdf" class="form-label">Upload New English PDF:</label>
                                            <input type="file" class="form-control" id="english_pdf" name="english_pdf" accept=".pdf">
                                            <div class="form-text">Max file size: 100MB | PDF format only</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-upload me-1"></i> Update English PDF
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Hindi PDF Section -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-language me-2"></i>Hindi PDF
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Current File:</label>
                                        <div class="d-flex align-items-center justify-content-between p-2 border rounded">
                                            <span>Hindi-Geokranti.pdf</span>
                                            <a href="/Hindi-Geokranti.pdf" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </div>
                                    </div>

                                    <form action="{{ route('admin.pdf.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="hindi_pdf" class="form-label">Upload New Hindi PDF:</label>
                                            <input type="file" class="form-control" id="hindi_pdf" name="hindi_pdf" accept=".pdf">
                                            <div class="form-text">Max file size: 100MB | PDF format only</div>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-upload me-1"></i> Update Hindi PDF
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Combined Update Form -->
                    <div class="card mt-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-sync-alt me-2"></i>Update Both PDFs Together
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.pdf.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="english_pdf_combined" class="form-label">English PDF:</label>
                                        <input type="file" class="form-control" id="english_pdf_combined" name="english_pdf" accept=".pdf">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="hindi_pdf_combined" class="form-label">Hindi PDF:</label>
                                        <input type="file" class="form-control" id="hindi_pdf_combined" name="hindi_pdf" accept=".pdf">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-sync-alt me-1"></i> Update Both PDFs
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 0.5rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }
</style>

@endsection