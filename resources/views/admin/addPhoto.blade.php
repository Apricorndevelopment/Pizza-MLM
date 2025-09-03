@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')
<style>
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 0.75rem;
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .gallery-img {
            /* border-radius: 0.5rem; */
            transition: transform 0.3s ease;
        }
        .gallery-img:hover {
            transform: scale(1.05);
        }
        .pagination {
            margin-bottom: 0;
        }
        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }
        .section-title {
            position: relative;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: #0d6efd;
            border-radius: 2px;
        }
    </style>

   <div class="container p-4">
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-5 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="section-title">Add New Photo</h3>
                        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" id="title" class="form-control @error('title') is-invalid @enderror" 
                                       placeholder="Enter photo title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" id="photo" class="form-control @error('photo') is-invalid @enderror" 
                                       name="photo" required>
                                <div class="form-text">Accepted formats: jpeg, png, jpg, webp. Max size: 5MB.</div>
                                @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-1"></i> Add Photo
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h3 class="section-title">Photo Gallery</h3>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="60">#</th>
                                        <th>Title</th>
                                        <th>Photo</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $index = ($photos->currentPage() - 1) * $photos->perPage() + 1; @endphp
                                    @forelse($photos as $photo)
                                    <tr>
                                        <td>{{ $index++ }}</td>
                                        <td>{{ $photo->title }}</td>
                                        <td class="p-2">
                                            <div class="w-60 h-60 overflow-hidden">
                                                <img src="{{ asset('storage/photos/' . basename($photo->photo)) }}" 
                                                alt="{{ $photo->title }}" style="object-fit: cover;width:80px;height:80px;border-radius:0" class="gallery-img">
                                            </div>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.gallery.delete', $photo->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger btn-action" 
                                                        onclick="return confirm('Are you sure you want to delete this photo?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-images fa-2x mb-2 text-muted"></i>
                                            <p class="text-muted">No photos found. Upload your first photo!</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($photos->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $photos->firstItem() }} to {{ $photos->lastItem() }} of {{ $photos->total() }} entries
                            </div>
                            <nav>
                                {{ $photos->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                new bootstrap.Alert(alert).close();
            });
        }, 5000);
    </script>
@endsection