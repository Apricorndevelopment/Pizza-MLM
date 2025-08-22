@extends('userlayouts.layouts')
@section('title', 'Dashboard')
@section('container')
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Hello, {{ $user->name }}</h3>
                    </div>

                    <div class="card-body">
                        <!-- Referral Link Section -->
                        <div>
                            @session('success')
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endsession
                            @session('error')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            @endsession            
                        </div>
                        <div class="alert alert-info mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong><i class="fas fa-link me-2"></i>Your Referral Link:</strong>
                                    <span id="referralLink">{{ url('/register') }}?sponsor_id={{ $user->ulid }}</span>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" onclick="copyReferralLink()">
                                    <i class="fas fa-copy me-1"></i> Copy
                                </button>
                            </div>

                            <div class="share-buttons">
                                <strong class="d-block mb-2"><i class="fas fa-share-alt me-2"></i>Share Referral Link
                                    via:</strong>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-success" onclick="shareOnWhatsApp()">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="shareOnFacebook()">
                                        <i class="fab fa-facebook"></i> Facebook
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="shareOnTelegram()">
                                        <i class="fab fa-telegram"></i> Telegram
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#profile" type="button" role="tab">Profile</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="kyc-tab" data-bs-toggle="tab" data-bs-target="#kyc"
                                    type="button" role="tab">KYC Details</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="nominee-tab" data-bs-toggle="tab" data-bs-target="#nominee"
                                    type="button" role="tab">Nominee Update</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank"
                                    type="button" role="tab">Bank Details</button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="profileTabsContent">
                            <!-- Profile Tab -->
                            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                                <div class="row">
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

                                        <p class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                            Status: {{ ucfirst($user->status) }}
                                        </p>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <h5 class="text-muted">Personal Information</h5>
                                                <p><strong>ULID:</strong> {{ $user->ulid }}</p>
                                                <p><strong>Name:</strong> {{ $user->name }}</p>
                                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                                <p><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
                                            </div>

                                            <div class="col-md-6">
                                                <h5 class="text-muted">Address Information</h5>
                                                <p><strong>Address:</strong> {{ $user->address ?? 'Not provided' }}</p>
                                                <p><strong>City/State:</strong> {{ $user->state ?? 'Not provided' }}</p>
                                            </div>

                                            <div class="col-md-6">
                                                <h5 class="text-muted">Network Information</h5>
                                                <p><strong>Sponsor ID:</strong> {{ $user->sponsor_id ?? 'None' }}</p>
                                                <p><strong>Parent ID:</strong> {{ $user->parent_id ?? 'None' }}</p>
                                            </div>

                                            <div class="col-md-6">
                                                <h5 class="text-muted">Balance Information</h5>
                                                <p><strong>Points Balance:</strong>
                                                    <span class="badge bg-info">{{ $user->points_balance ?? 0 }}</span>
                                                </p>
                                                <p><strong>Loyalty Balance:</strong>
                                                    <span class="badge bg-success">{{ $user->loyalty_balance ?? 0 }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- KYC Tab -->
                            <div class="tab-pane fade" id="kyc" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0">Aadhaar Details</h5>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Aadhaar Number:</strong> {{ $user->adhar_no ?? 'Not provided' }}
                                                </p>
                                                @if ($user->adhar_photo)
                                                    <div class="mt-3">
                                                        <img src="{{ asset('storage/aadhaar-documents/' . basename($user->adhar_photo)) }}"
                                                            class="img-thumbnail" style="max-width: 100%; height: 200px;"
                                                            alt="Aadhaar Card">
                                                    </div>
                                                @else
                                                    <p class="text-danger mt-3">Aadhaar photo not uploaded</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0">PAN Details</h5>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>PAN Number:</strong> {{ $user->pan_no ?? 'Not provided' }}</p>
                                                @if ($user->pan_photo)
                                                    <div class="mt-3">
                                                        <img src="{{ asset('storage/pan-documents/' . basename($user->pan_photo)) }}"
                                                            class="img-thumbnail" style="max-width: 100%; height: 200px;"
                                                            alt="PAN Card">
                                                    </div>
                                                @else
                                                    <p class="text-danger mt-3">PAN photo not uploaded</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Nominee Tab -->
                            <div class="tab-pane fade" id="nominee" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-muted">Nominee Details</h5>
                                        <p><strong>Nominee Name:</strong> {{ $user->nom_name ?? 'Not provided' }}</p>
                                        <p><strong>Relationship:</strong> {{ $user->nom_relation ?? 'Not provided' }}
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <!-- Bank Tab -->
                            <div class="tab-pane fade" id="bank" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-muted">Bank Account Details</h5>
                                        <p><strong>Account Number:</strong> {{ $user->account_no ?? 'Not provided' }}</p>
                                        <p><strong>Bank Name:</strong> {{ $user->bank_name ?? 'Not provided' }}</p>
                                        <p><strong>IFSC Code:</strong> {{ $user->ifsc_code ?? 'Not provided' }}</p>
                                        <p><strong>UPI ID:</strong> {{ $user->upi_id ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="text-muted">Bank Proof</h5>
                                        @if ($user->passbook_photo)
                                            <img src="{{ asset('storage/passbook-photos/' . basename($user->passbook_photo)) }}"
                                                class="img-thumbnail" style="max-width: 100%; max-height: 150px;"
                                                alt="Passbook Proof">
                                        @else
                                            <p class="text-danger">No passbook proof uploaded</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Profile Button -->
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('user.profile.edit') }}" class="btn btn-primary">
                                <i class="fa fa-edit me-2"></i> Edit Details
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

        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
            border: none;
            padding: 10px 20px;
            margin-right: 5px;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            background-color: transparent;
            border-bottom: 3px solid #0d6efd;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #0d6efd;
        }

        .tab-content {
            padding: 20px 0;
        }
    </style>

    <script>
        function copyReferralLink() {
            // Get the referral link text
            const referralLink = document.getElementById('referralLink').innerText;

            // Create a temporary textarea element
            const textarea = document.createElement('textarea');
            textarea.value = referralLink;
            textarea.style.position = 'fixed'; // Prevent scrolling to bottom
            document.body.appendChild(textarea);
            textarea.select();

            try {
                // Execute the copy command
                const successful = document.execCommand('copy');

                // Get the button that was clicked
                const button = event.target.tagName === 'BUTTON' ? event.target : event.target.closest('button');

                if (successful) {
                    // Show success message
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
                    button.classList.remove('btn-outline-primary');
                    button.classList.add('btn-success');

                    // Revert after 2 seconds
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.classList.remove('btn-success');
                        button.classList.add('btn-outline-primary');
                    }, 2000);
                } else {
                    alert('Failed to copy referral link. Please try again.');
                }
            } catch (err) {
                console.error('Failed to copy text: ', err);
                alert('Failed to copy referral link. Please try again.');
            } finally {
                document.body.removeChild(textarea);
            }
        }

        function shareOnWhatsApp() {
            const referralLink = document.getElementById('referralLink').innerText;
            const message = `Join me on this platform! Use my referral link: ${referralLink}`;
            window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
        }

        function shareOnFacebook() {
            const referralLink = document.getElementById('referralLink').innerText;
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(referralLink)}`, '_blank');
        }

        function shareOnTelegram() {
            const referralLink = document.getElementById('referralLink').innerText;
            const message = `Join me on this platform! Use my referral link: ${referralLink}`;
            window.open(
                `https://t.me/share/url?url=${encodeURIComponent(referralLink)}&text=${encodeURIComponent(message)}`,
                '_blank');
        }
    </script>

    <!-- Bootstrap JS for tabs -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
