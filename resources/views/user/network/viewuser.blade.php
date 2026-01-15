@extends('userlayouts.layouts')

@section('title', 'Network Explorer')

@section('container')
    <div class="container">
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
                                <div class="card-header text-white py-2 d-flex justify-content-between align-items-center"
                                    id="detail_header">
                                    <span id="header_name" class="text-white me-1"></span>
                                    <span id="header_ulid" class="badge bg-secondary">ULID: -</span>
                                </div>
                                <div class="card-body p-0">
                                    <table class="custom-striped-table mb-0">
                                        <tbody>
                                            <tr>
                                                <td>Registered Date</td>
                                                <td id="detail_registered">-</td>
                                            </tr>
                                            <tr>
                                                <td>Activation Date</td>
                                                <td id="detail_doa">-</td>
                                            </tr>
                                            <tr>
                                                <td>Level</td>
                                                <td id="detail_level">-</td>
                                            </tr>
                                            <tr>
                                                <td>Purchase Amount</td>
                                                <td id="detail_purchase">-</td>
                                            </tr>
                                            <tr>
                                                <td>Designation</td>
                                                <td id="detail_rank">-</td>
                                            </tr>
                                            <tr style="flex-direction: column; align-items: center;justify-content: center;">
                                                <td class="pb-0">Total Team Business</td>
                                                <td class="pt-1" id="detail_business">-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
         
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

        /* Table Styling */
        .custom-striped-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .custom-striped-table tbody tr {
            display: flex;
            justify-content: space-between;
        }
     
        .custom-striped-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .custom-striped-table tbody tr:nth-child(even) {
            background-color: #e8f4ff;
            /* Light Blue for striped effect */
        }

        .custom-striped-table td {
            padding: 10px 12px;
            vertical-align: middle;
         
        }

        .custom-striped-table td:first-child {
            font-weight: 600;
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
                    if (data.activation_date) {
                        document.getElementById('detail_header').style.backgroundColor = '#28a745';
                    } else {
                        document.getElementById('detail_header').style.backgroundColor = '#49b3ff';
                    }

                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Failed to load user details');
                });
        }
    </script>
@endpush