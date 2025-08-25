@extends('layouts.layout')
@section('title', 'User Tree')

@section('container')


    <style>
        .tree,
        .tree ul {
            list-style-type: none;
            padding-left: 20px;
        }

        .tree li {
            margin: 3px 0;
        }

        .toggle-icon {
            width: 20px;
            font-size: 12px;
            display: inline-block;
            cursor: pointer;
            color: #6c63ff;
        }

        .node-label {
            color: white;
            padding: 0 5px;
            border-radius: 3px;
            font-family: monospace;
            cursor: pointer;
            font-size: 12px;
        }

        .nested {
            display: none;
        }

        .tree-container {
            max-height: 600px;
            overflow-y: auto;
        }
    </style>

    <div class="container mt-4">
        <h2>User Tree for: {{ $admin->name }}</h2>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Network Tree
                    </div>
                    <div class="card-body tree-container p-2 p-md-3">
                        {!! $treeText !!}
                    </div>
                </div>
            </div>

            <div class="col-md-5 col-sm-12">
                <div class="card shadow-sm border-0" style="font-size: 0.85rem;">
                    <div class="card-header bg-success text-white py-2 px-2">
                        <h6 class="mb-0 p-1" style="font-size: 0.95rem;">
                            <i class="fa fa-user-circle me-2"></i>User Details
                        </h6>
                    </div>
                    <div class="card-body p-2">
                        <form id="userDetailsForm" class="row g-2">
                            <!-- Row 1 -->
                            <div class="col-6 mb-1">
                                <label class="text-muted mb-0 small">Name</label>
                                <input type="text" id="detail_name" class="form-control bg-light p-1"
                                    style="font-size: 0.72rem; height: 30px;" readonly>
                            </div>
                            <div class="col-6 mb-1">
                                <label class="text-muted mb-0 small">Ulid</label>
                                <input type="text" id="detail_ulid" class="form-control bg-light p-1"
                                    style="font-size: 0.72rem; height: 30px;" readonly>
                            </div>

                            <!-- Row 2 -->
                            <div class="col-6 mb-1">
                                <label class="text-muted mb-0 small">Registered Date</label>
                                <input type="text" id="detail_reg" class="form-control bg-light p-1"
                                    style="font-size: 0.72rem; height: 30px;" readonly>
                            </div>
                            <div class="col-6 mb-1">
                                <label class="text-muted mb-0 small">Activation Date</label>
                                <input type="text" id="detail_active" class="form-control bg-light p-1"
                                    style="font-size: 0.72rem; height: 30px;" readonly>
                            </div>

                            <!-- Row 3 -->
                            <div class="col-6 mb-1">
                                <label class="text-muted mb-0 small">Purchase Amount</label>
                                <div class="input-group" style="height: 30px;">
                                    <input type="text" id="detail_purchase" class="form-control bg-light p-1"
                                        style="font-size: 0.72rem;" readonly>
                                    <span class="input-group-text bg-success text-white px-2 py-0"
                                        style="font-size: 0.7rem;">
                                        <i class="fas fa-coins"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6 mb-1">
                                <label class="text-muted mb-0 small">Rank</label>
                                <div class="input-group" style="height: 30px;">
                                    <input type="text" id="detail_rank" class="form-control bg-light p-1"
                                        style="font-size: 0.72rem;" readonly>
                                    <span class="input-group-text bg-warning text-dark px-2 py-0"
                                        style="font-size: 0.7rem;">
                                        <i class="fas fa-medal"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Row 4 -->
                            <div class="col-6 mb-1">
                                <label class="text-muted mb-0 small">Status</label>
                                <input type="text" id="detail_status" class="form-control bg-light p-1"
                                    style="font-size: 0.72rem; height: 30px;" readonly>
                            </div>
                            <div class="col-6 mb-1">
                                <label class="text-muted mb-0 small">Total Business</label>
                                <div class="input-group" style="height: 30px;">
                                    <input type="text" id="detail_business" class="form-control bg-light p-1"
                                        style="font-size: 0.72rem;" readonly>
                                    <span class="input-group-text bg-primary text-white px-2 py-0"
                                        style="font-size: 0.7rem;">
                                        <i class="fas fa-chart-line"></i>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .card {
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-control {
            border: 1px solid #dee2e6;
        }

        .input-group-text {
            border-radius: 0 4px 4px 0 !important;
        }
    </style>

    <script>
        function toggleNode(el) {
            const li = el.closest('li');
            const nested = li.querySelector('ul.nested');

            if (nested) {
                if (nested.style.display === "block") {
                    nested.style.display = "none";
                    el.textContent = "➕";
                } else {
                    nested.style.display = "block";
                    el.textContent = "➖";
                }
            }
        }

        function loadUserDetails(ulid) {
            fetch('/admin/get-user-details/' + ulid)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    let total_business = parseFloat(data.left_business) + parseFloat(data.right_business);
                    total_business = (total_business).toFixed(2);

                    document.getElementById('detail_name').value = data.name;
                    document.getElementById('detail_ulid').value = data.ulid;
                    document.getElementById('detail_reg').value = data.registered_date;
                    document.getElementById('detail_active').value = data.activation_date  || 'Not Active';
                    document.getElementById('detail_purchase').value = data.purchase_amount;
                    document.getElementById('detail_rank').value = data.rank || 'N/A';
                    document.getElementById('detail_status').value = data.status;
                    document.getElementById('detail_business').value = total_business;
                });
        }
    </script>

@endsection
