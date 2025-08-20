@extends('userlayouts.layouts')
@section('title', 'Invoice')

@section('container')
    <div class="container py-4" >
        <div class="card border-0 shadow-lg" id="invoiceArea">
            <div class="card-body p-4">
                <!-- Invoice Header -->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('geokrantilogo.jpg') }}" alt="GeoKranti Logo" class="me-2" height="65">
                            <div>
                                <h2 class="h4 mb-0 text-primary">GEOKRANTI</h2>
                                <small class="text-muted">Innovative Solutions</small>
                            </div>
                        </div>
                        <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i> Ganaur, Sonipat Haryana, India</p>
                        <p class="mb-1"><i class="fas fa-phone me-2"></i> +91 XXXXX XXXXX</p>
                        <p class="mb-0"><i class="fas fa-envelope me-2"></i> geokranti@gmail.com</p>
                    </div>
                    <div class="col-sm-6 text-md-end mt-4 mt-sm-2">
                        <h1 class="h3 text-uppercase text-primary mb-2">INVOICE</h1>
                        <div class="border-bottom d-inline-block border-primary mb-2"></div>
                        <p class="mb-1"><strong>Invoice No:</strong> <span
                                class="badge bg-dark">{{ $transaction->invoice_no ?? 'N/A' }}</span></p>
                        <p class="mb-1"><strong>Date:</strong> {{ $transaction->created_at->format('d M Y, h:i A') }}</p>
                        <p class="mb-0"><strong>Bed No:</strong> <span
                                class="badge bg-info">{{ $transaction->bed_no ?? 'N/A' }}</span></p>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Package Details -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="text-uppercase border-bottom pb-2 mb-3">Package Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Package Name</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Rate</th>
                                        <th class="text-center">Duration</th>
                                        <th class="text-center">Profit Share</th>
                                        <th class="text-end">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $transaction->package_name }}</td>
                                        <td class="text-center">{{ $transaction->quantity }}</td>
                                        <td class="text-center">{{ $transaction->rate }}%</td>
                                        <td class="text-center">{{ $transaction->time }} years</td>
                                        <td class="text-center">{{ $transaction->profit_share == 1 ? 'Yes' : 'No' }}</td>
                                        <td class="text-end">₹{{ number_format($transaction->final_price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Total Amount:</td>
                                        <td class="text-end fw-bold">₹{{ number_format($transaction->final_price, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-uppercase border-bottom pb-2 mb-3">Terms & Conditions</h5>
                        <p class="small mb-1">1. This is a computer generated invoice.</p>
                        <p class="small mb-1">2. Goods once sold will not be taken back.</p>
                        <p class="small mb-0">3. Subject to jurisdiction of local courts.</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="row mt-5">
                    <div class="col-md-12 text-center">
                        <p class="mb-2">Thank you for your business!</p>
                        <div class="d-flex justify-content-center">
                            <div class="border-top pt-2 mx-auto" style="width: 200px;">
                                <p class="small mb-0">Authorized Signature</p>
                            </div>
                        </div>
                        <p class="small text-muted mt-4">For any queries, contact us at: geokranti@gmail.com | +91 XXXXX
                            XXXXX</p>
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12 text-center">
                <button class="btn btn-success px-4" onclick="printInvoice()">
                    <i class="fas fa-print me-2"></i>Print Invoice
                </button>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #invoiceArea,
            #invoiceArea * {
                visibility: visible;
            }

            #invoiceArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
            }

            .card {
                border: none;
                box-shadow: none;
            }

            .btn {
                visibility: hidden;
                display: none !important;
            }

            .badge {
                border: 1px solid #000;
                color: #000;
                background-color: transparent !important;
            }

            .table-light {
                background-color: #f8f9fa !important;
            }
        }

        .card {
            border-radius: 10px;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .border-bottom {
            width: 200px;
        }
    </style>

    <script>
        function printInvoice() {
            var originalContents = document.body.innerHTML;
            var printContents = document.getElementById('invoiceArea').innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endsection