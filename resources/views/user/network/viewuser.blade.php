@extends('userlayouts.layouts')

@section('title', 'Network Explorer')

@section('container')
    <div class="container mt-4">
        <h3 class="mb-4">Network Explorer</h3>

        <div class="row">
            <!-- Left: Tree -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white py-2">
                        My Network
                    </div>
                    <div class="row card-body tree-container p-2 p-sm-3 p-md-4" style="max-height: 600px; overflow-y: auto;">
                        <div class="col-md-6">
                            {!! $treeHtml !!}
                        </div>
                        <!-- Right: User Details -->
                        <div class="col-md-6 mt-3 mt-md-0" id="detail_section" style="display:none;">
                            <div class="card shadow-sm h-100">
                                <div class="card-header text-white py-2 d-flex justify-content-between align-items-center" style="background-color:#49b3ff ">
                                    <h6 class="mb-0">User Details</h6>
                                    <div class="user-header-info">
                                        <span id="header_name" class="badge bg-light text-dark me-1">No selection</span>
                                        <span id="header_ulid" class="badge bg-secondary">ULID: -</span>
                                    </div>
                                </div>
                                <div class="card-body p-2 p-md-3">
                                    <div class="user-details-grid">
                                        <!-- Row 1: Registered + Activation -->
                                        <div class="detail-item">
                                            <small class="detail-label">Registered Date</small>
                                            <div class="detail-value" id="detail_registered">-</div>
                                        </div>
                                        <div class="detail-item">
                                            <small class="detail-label">Activation Date</small>
                                            <div class="detail-value" id="detail_doa">-</div>
                                        </div>

                                        <!-- Row 2: Level + Rank -->
                                        <div class="detail-item">
                                            <small class="detail-label">Level</small>
                                            <div class="detail-value" id="detail_level">-</div>
                                        </div>
                                        <div class="detail-item">
                                            <small class="detail-label">Purchase Amount</small>
                                            <div class="detail-value" id="detail_purchase">-</div>
                                        </div>
                                        <!-- Row 3: Purchase + Business -->
                                        <div class="detail-item">
                                            <small class="detail-label">Designation</small>
                                            <div class="detail-value" id="detail_rank">-</div>
                                        </div>
                                        <div class="detail-item">
                                            <small class="detail-label">Total Team Business</small>
                                            <div class="detail-value" id="detail_business">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .user-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .detail-item {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 8px;
            min-height: 50px;
        }

        .detail-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-weight: 500;
            color: #212529;
            font-size: 13px;
            margin-top: 4px;
        }

        .user-header-info {
            display: flex;
            align-items: center;
        }

        .card-header h6 {
            font-size: 1rem;
            font-weight: 600;
        }

        .tree-container {
            max-height: 600px;
            overflow-y: auto;
        }

        .card {
            border-radius: 8px;
            border: none;
        }

        .card-header {
            border-radius: 8px 8px 0 0 !important;
        }

        .active-user {
            background-color: #30baffd2 !important;
            color: white !important;
            border-radius: 4px;
            padding: 2px 5px;
        }

        .active-user .text-muted {
            color: #e0e0e0 !important;
        }

        .tree-node {
            transition: background-color 0.2s ease;
            cursor: pointer;
            padding: 2px 5px;
            border-radius: 4px;
        }

        .tree-node:hover {
            background-color: #f0f0f0;
        }
        
    </style>
@endsection

@push('scripts')
    <script>
        function toggleNode(el) {
            const li = el.closest('li');
            const nested = li.querySelector('ul.nested');
            const icon = el.querySelector('.toggle-icon');

            if (nested) {
                nested.style.display = nested.style.display === "none" || nested.style.display === "" ? "block" : "none";

                // Update the icon with border
                if (nested.style.display === "block") {
                    icon.innerHTML =
                        '<span style="border:1.3px solid black; display:inline-block; padding:1px;font-size:10px;">➖</span> <i class="fa-solid fa-folder-open text-primary"></i>';
                } else {
                    icon.innerHTML =
                        '<span style="border:1.3px solid black; display:inline-block; padding:1px;font-size:10px;">➕</span> <i class="fa-solid fa-folder text-primary"></i>';
                }
            }
        }

        function loadUserDetails(ulid) {
            // Remove active class from all nodes first
            document.querySelectorAll('.tree-node').forEach(node => {
                node.classList.remove('active-user');
            });

            // Add active class to the selected node using data attribute
            const selectedNode = document.querySelector(`.tree-node[data-user-ulid="${ulid}"]`);
            if (selectedNode) {
                selectedNode.classList.add('active-user');
                // Scroll to the selected node
                selectedNode.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }

            fetch('/get-user-details/' + ulid)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Update header info
                    document.getElementById('detail_section').style.display = "block";
                    document.getElementById('header_name').textContent = data.name || 'N/A';
                    document.getElementById('header_ulid').textContent = 'ULID: ' + (data.ulid || '-');

                    // Update details grid
                    document.getElementById('detail_registered').textContent = data.registered_date || '-';
                    document.getElementById('detail_doa').textContent = data.activation_date || 'Not Active';
                    document.getElementById('detail_level').textContent = data.level;
                    document.getElementById('detail_rank').textContent = data.rank || 'N/A';
                    document.getElementById('detail_purchase').textContent = data.purchase_amount;
                    document.getElementById('detail_business').textContent = (parseFloat(data.left_business) +
                        parseFloat(data.right_business)).toFixed(2);
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Failed to load user details');
                });
        }
    </script>
@endpush
