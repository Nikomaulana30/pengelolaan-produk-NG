@extends('layouts.app')

@section('title', 'Return Shipment')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Return Shipment</h3>
                <p class="text-subtitle text-muted">Pengiriman kembali barang yang sudah di-rework ke customer</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Return Shipment</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Workflow Progress Indicator -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <div class="progress-steps">
                        <div class="step completed">
                            <div class="step-circle">1</div>
                            <span>Customer Complaint</span>
                        </div>
                        <div class="step completed">
                            <div class="step-circle">2</div>
                            <span>Dokumen Retur</span>
                        </div>
                        <div class="step completed">
                            <div class="step-circle">3</div>
                            <span>Warehouse Verification</span>
                        </div>
                        <div class="step completed">
                            <div class="step-circle">4</div>
                            <span>Quality Reinspection</span>
                        </div>
                        <div class="step completed">
                            <div class="step-circle">5</div>
                            <span>Production Rework</span>
                        </div>
                        <div class="step completed">
                            <div class="step-circle">6</div>
                            <span>Final Quality Check</span>
                        </div>
                        <div class="step active">
                            <div class="step-circle">7</div>
                            <span>Return Shipment</span>
                        </div>
                        <div class="step">
                            <div class="step-circle">8</div>
                            <span>Reports</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="iconly-boldBuy"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Shipments</h6>
                            <h6 class="font-extrabold mb-0">{{ $totalShipments ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon orange mb-2">
                                <i class="iconly-boldTimeCircle"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Pending</h6>
                            <h6 class="font-extrabold mb-0">{{ $pendingCount ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon green mb-2">
                                <i class="iconly-boldTicket"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Shipped</h6>
                            <h6 class="font-extrabold mb-0">{{ $shippedCount ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldHeart"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Delivered</h6>
                            <h6 class="font-extrabold mb-0">{{ $deliveredCount ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title">Return Shipment</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="btn-group">
                            <a href="{{ route('return-shipment.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus"></i> New Shipment
                            </a>
                            <button class="btn btn-success" onclick="bulkShip()" id="bulkShipBtn" style="display: none;">
                                <i class="bi bi-truck"></i> Ship Selected
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="packed">Packed</option>
                            <option value="shipped">Shipped</option>
                            <option value="in_transit">In Transit</option>
                            <option value="delivered">Delivered</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="carrierFilter">
                            <option value="">Semua Kurir</option>
                            <option value="jne">JNE</option>
                            <option value="tiki">TIKI</option>
                            <option value="pos">Pos Indonesia</option>
                            <option value="fedex">FedEx</option>
                            <option value="dhl">DHL</option>
                            <option value="internal">Internal Delivery</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateFilter">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchFilter" 
                               placeholder="Cari nomor shipment, customer, tracking...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary" id="resetFilter">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-striped" id="shipmentTable">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>No. Shipment</th>
                                <th>No. QC</th>
                                <th>Customer</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Carrier</th>
                                <th>Tracking</th>
                                <th>Status</th>
                                <th>Ship Date</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($returnShipments ?? [] as $shipment)
                            <tr>
                                <td>
                                    @if($shipment->status_pengiriman === 'preparing')
                                    <div class="form-check">
                                        <input class="form-check-input shipment-checkbox" 
                                               type="checkbox" value="{{ $shipment->id }}">
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $shipment->nomor_pengiriman }}</strong>
                                </td>
                                <td>
                                    <a href="{{ route('final-quality-check.show', $shipment->finalQualityCheck) }}" 
                                       class="text-decoration-none">
                                        #{{ $shipment->finalQualityCheck->id }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong>{{ $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</strong>
                                        <small class="text-muted">{{ Str::limit($shipment->alamat_pengiriman, 40) }}</small>
                                    </div>
                                </td>
                                <td>{{ $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</td>
                                <td>{{ number_format($shipment->quantity_shipped, 0) }} pcs</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        @if($shipment->ekspedisi)
                                            <span class="badge bg-info">{{ strtoupper($shipment->ekspedisi) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($shipment->nomor_resi)
                                        <div class="d-flex flex-column">
                                            <code>{{ $shipment->nomor_resi }}</code>
                                            @if($shipment->status_pengiriman === 'shipped')
                                                <small>
                                                    <a href="#" onclick="trackShipment('{{ $shipment->nomor_resi }}', '{{ $shipment->ekspedisi }}')" 
                                                       class="text-primary">
                                                        <i class="bi bi-geo-alt"></i> Track
                                                    </a>
                                                </small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $shipment->status_pengiriman === 'preparing' ? 'secondary' : 
                                        ($shipment->status_pengiriman === 'shipped' ? 'primary' : 
                                        ($shipment->status_pengiriman === 'delivered' ? 'success' : 'danger')) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status_pengiriman)) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $shipment->tanggal_pengiriman->format('d/m/Y') }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('return-shipment.show', $shipment) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($shipment->status_pengiriman === 'preparing' || $shipment->status === 'draft')
                                        <a href="{{ route('return-shipment.edit', $shipment) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @endif
                                        @if($shipment->status_pengiriman === 'preparing')
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="updateStatus({{ $shipment->id }}, 'shipped')" 
                                                title="Ship Now">
                                            <i class="bi bi-truck"></i>
                                        </button>
                                        @endif
                                        @if($shipment->status_pengiriman === 'shipped')
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="updateStatus({{ $shipment->id }}, 'delivered')" 
                                                title="Mark as Delivered">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        @endif
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                                    data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><button class="dropdown-item" onclick="printLabel({{ $shipment->id }})">
                                                    <i class="bi bi-printer"></i> Print Label
                                                </button></li>
                                                <li><button class="dropdown-item" onclick="printPackingList({{ $shipment->id }})">
                                                    <i class="bi bi-list-ul"></i> Packing List
                                                </button></li>
                                                @if($shipment->tracking_number)
                                                <li><hr class="dropdown-divider"></li>
                                                <li><button class="dropdown-item" onclick="trackShipment('{{ $shipment->tracking_number }}', '{{ $shipment->carrier }}')">
                                                    <i class="bi bi-geo-alt"></i> Track Package
                                                </button></li>
                                                @endif
                                                @if($shipment->status === 'delivered')
                                                <li><hr class="dropdown-divider"></li>
                                                <li><button class="dropdown-item" onclick="requestFeedback({{ $shipment->id }})">
                                                    <i class="bi bi-chat-dots"></i> Request Feedback
                                                </button></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-truck display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada return shipment</h5>
                                        <p class="text-muted">Data akan muncul ketika final quality check sudah passed</p>
                                        <a href="{{ route('return-shipment.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus"></i> New Shipment
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($returnShipments) && $returnShipments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <p class="text-muted">
                            Menampilkan {{ $returnShipments->firstItem() ?? 0 }} sampai {{ $returnShipments->lastItem() ?? 0 }} 
                            dari {{ $returnShipments->total() }} data
                        </p>
                    </div>
                    <div>
                        {{ $returnShipments->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Modal for Status Update -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Shipment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    @csrf
                    <input type="hidden" id="shipmentId">
                    <input type="hidden" id="newStatus">
                    
                    <div id="packedFields" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Package Weight (kg)</label>
                                <input type="number" class="form-control" id="packageWeight" step="0.1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Package Dimensions (LxWxH cm)</label>
                                <input type="text" class="form-control" id="packageDimensions" 
                                       placeholder="e.g., 30x20x15">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Packing Notes</label>
                            <textarea class="form-control" id="packingNotes" rows="2" 
                                      placeholder="Special packing instructions or notes..."></textarea>
                        </div>
                    </div>
                    
                    <div id="shippedFields" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Carrier</label>
                                <select class="form-select" id="carrier">
                                    <option value="">Select carrier</option>
                                    <option value="jne">JNE</option>
                                    <option value="tiki">TIKI</option>
                                    <option value="pos">Pos Indonesia</option>
                                    <option value="fedex">FedEx</option>
                                    <option value="dhl">DHL</option>
                                    <option value="internal">Internal Delivery</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Carrier Service</label>
                                <input type="text" class="form-control" id="carrierService" 
                                       placeholder="e.g., Regular, Express, Same Day">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tracking Number</label>
                                <input type="text" class="form-control" id="trackingNumber" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estimated Delivery Date</label>
                                <input type="date" class="form-control" id="estimatedDeliveryDate">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shipment Date</label>
                            <input type="datetime-local" class="form-control" id="shipmentDate" 
                                   value="{{ now()->format('Y-m-d\\TH:i') }}">
                        </div>
                    </div>
                    
                    <div id="deliveredFields" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Delivery Date</label>
                                <input type="datetime-local" class="form-control" id="deliveryDate" 
                                       value="{{ now()->format('Y-m-d\\TH:i') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Received By</label>
                                <input type="text" class="form-control" id="receivedBy" 
                                       placeholder="Name of person who received">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Delivery Notes</label>
                            <textarea class="form-control" id="deliveryNotes" rows="2" 
                                      placeholder="Any delivery notes or customer feedback..."></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">General Notes</label>
                        <textarea class="form-control" id="statusNotes" rows="3" 
                                  placeholder="Add notes about the status update..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload Photos/Documents</label>
                        <input type="file" class="form-control" id="statusFiles" 
                               multiple accept="image/*,.pdf,.doc,.docx">
                        <small class="text-muted">Upload photos of package, delivery confirmation, etc.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmStatusUpdate()">Update Status</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Tracking -->
<div class="modal fade" id="trackingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Package Tracking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="trackingContent">
                    <div class="text-center py-4">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading tracking information...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Progress Steps Styling - Same as previous views */
.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    margin: 20px 0;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 25px;
    right: 25px;
    height: 2px;
    background-color: #dee2e6;
    z-index: 1;
}

.step {
    text-align: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #dee2e6;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-weight: bold;
    font-size: 14px;
    transition: all 0.3s ease;
}

.step span {
    font-size: 12px;
    color: #6c757d;
    max-width: 80px;
    line-height: 1.2;
    display: block;
}

.step.completed .step-circle {
    background-color: #198754;
    color: white;
}

.step.completed span {
    color: #198754;
    font-weight: 600;
}

.step.active .step-circle {
    background-color: #0d6efd;
    color: white;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
}

.step.active span {
    color: #0d6efd;
    font-weight: 600;
}

/* Stats Icons */
.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stats-icon.blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stats-icon.orange {
    background: linear-gradient(135deg, #fd746c 0%, #ff9068 100%);
}

.stats-icon.green {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stats-icon.purple {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

/* Tracking number styling */
code {
    background-color: #f8f9fa;
    color: #e83e8c;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 12px;
    font-family: 'Courier New', monospace;
}

/* Status badges */
.badge.bg-secondary {
    background-color: #6c757d !important;
}

.badge.bg-info {
    background-color: #0dcaf0 !important;
}

.badge.bg-primary {
    background-color: #0d6efd !important;
}

.badge.bg-warning {
    background-color: #fd7e14 !important;
}

.badge.bg-success {
    background-color: #198754 !important;
}

/* ETA styling */
.text-muted {
    font-size: 11px;
}

/* Responsive */
@media (max-width: 768px) {
    .progress-steps {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .progress-steps::before {
        display: none;
    }
    
    .step {
        width: 100%;
        text-align: left;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .step-circle {
        margin-right: 15px;
        margin-bottom: 0;
        flex-shrink: 0;
    }
    
    .step span {
        font-size: 14px;
        max-width: none;
    }
}

/* Tracking timeline */
.tracking-timeline {
    position: relative;
}

.tracking-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #dee2e6;
}

.tracking-item {
    position: relative;
    padding-left: 40px;
    padding-bottom: 20px;
}

.tracking-item::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 6px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #198754;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #198754;
}

.tracking-item.pending::before {
    background-color: #6c757d;
    box-shadow: 0 0 0 2px #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#shipmentTable').DataTable({
        responsive: true,
        ordering: true,
        searching: false,
        paging: false,
        info: false,
        language: {
            emptyTable: "Tidak ada data return shipment",
            zeroRecords: "Tidak ditemukan data yang sesuai"
        }
    });

    // Custom filters
    $('#searchFilter').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#statusFilter').on('change', function() {
        let status = $(this).val();
        table.column(8).search(status).draw();
    });

    $('#carrierFilter').on('change', function() {
        let carrier = $(this).val();
        table.column(6).search(carrier).draw();
    });

    $('#resetFilter').on('click', function() {
        $('#statusFilter, #carrierFilter, #searchFilter, #dateFilter').val('');
        table.search('').columns().search('').draw();
    });

    // Checkbox functionality
    $('#selectAll').on('change', function() {
        $('.shipment-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkButtons();
    });

    $(document).on('change', '.shipment-checkbox', function() {
        updateBulkButtons();
        let totalCheckboxes = $('.shipment-checkbox').length;
        let checkedCheckboxes = $('.shipment-checkbox:checked').length;
        $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes);
    });

    function updateBulkButtons() {
        let checkedCount = $('.shipment-checkbox:checked').length;
        $('#bulkShipBtn').toggle(checkedCount > 0);
    }

    // Set minimum date for delivery to today
    $('#estimatedDeliveryDate, #deliveryDate').attr('min', new Date().toISOString().split('T')[0]);
});

// Status update functions
function updateStatus(shipmentId, newStatus) {
    let message = '';
    let route = '';
    
    if (newStatus === 'shipped') {
        message = 'Tandai shipment sebagai SHIPPED (dalam pengiriman)?';
        route = `/return-shipment/${shipmentId}/ship`;
    } else if (newStatus === 'delivered') {
        message = 'Tandai shipment sebagai DELIVERED (sudah diterima customer)?\n\nIni akan menyelesaikan seluruh workflow complaint.';
        route = `/return-shipment/${shipmentId}/delivered`;
    } else {
        alert('Invalid status');
        return;
    }
    
    if (confirm(message)) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = route;
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        // Add method spoofing for PUT
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmStatusUpdate() {
    let shipmentId = $('#shipmentId').val();
    let newStatus = $('#newStatus').val();
    let formData = new FormData();
    
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('_method', 'PUT');
    formData.append('status', newStatus);
    formData.append('notes', $('#statusNotes').val());
    
    // Add status-specific fields
    if (newStatus === 'packed') {
        formData.append('package_weight', $('#packageWeight').val());
        formData.append('package_dimensions', $('#packageDimensions').val());
        formData.append('packing_notes', $('#packingNotes').val());
    } else if (newStatus === 'shipped') {
        formData.append('carrier', $('#carrier').val());
        formData.append('carrier_service', $('#carrierService').val());
        formData.append('tracking_number', $('#trackingNumber').val());
        formData.append('estimated_delivery_date', $('#estimatedDeliveryDate').val());
        formData.append('shipment_date', $('#shipmentDate').val());
    } else if (newStatus === 'delivered') {
        formData.append('delivery_date', $('#deliveryDate').val());
        formData.append('received_by', $('#receivedBy').val());
        formData.append('delivery_notes', $('#deliveryNotes').val());
    }
    
    // Add files
    let files = $('#statusFiles')[0].files;
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    
    // Validation
    if (newStatus === 'shipped') {
        if (!$('#carrier').val()) {
            alert('Carrier is required for shipping');
            return;
        }
        if (!$('#trackingNumber').val()) {
            alert('Tracking number is required for shipping');
            return;
        }
    }
    
    if (newStatus === 'delivered') {
        if (!$('#deliveryDate').val()) {
            alert('Delivery date is required');
            return;
        }
    }
    
    // Show loading
    let submitBtn = $('#statusModal .btn-primary');
    let originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Processing...');
    
    $.ajax({
        url: `{{ route('return-shipment.index') }}/${shipmentId}/update-status`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#statusModal').modal('hide');
            
            let alertMessage = `Shipment status updated to ${newStatus.toUpperCase()}`;
            $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                alertMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>').insertBefore('.card');
            
            setTimeout(() => location.reload(), 1500);
        },
        error: function(xhr) {
            let errorMessage = 'Error updating shipment status';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            $('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                errorMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>').insertBefore('.card');
        },
        complete: function() {
            submitBtn.prop('disabled', false).text(originalText);
        }
    });
}

// Tracking functions
function trackShipment(trackingNumber, carrier) {
    $('#trackingModal').modal('show');
    
    // Load tracking information
    $('#trackingContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading tracking information...</p>
        </div>
    `);
    
    // Simulate tracking API call
    setTimeout(() => {
        let mockTrackingData = generateMockTrackingData(trackingNumber, carrier);
        displayTrackingInfo(mockTrackingData);
    }, 2000);
}

function generateMockTrackingData(trackingNumber, carrier) {
    return {
        trackingNumber: trackingNumber,
        carrier: carrier.toUpperCase(),
        status: 'In Transit',
        estimatedDelivery: '2024-12-25',
        events: [
            { date: '2024-12-20 14:30', location: 'Jakarta Hub', description: 'Package picked up', status: 'completed' },
            { date: '2024-12-20 18:45', location: 'Jakarta Sorting Center', description: 'In transit to destination city', status: 'completed' },
            { date: '2024-12-21 08:15', location: 'Surabaya Hub', description: 'Arrived at destination city', status: 'completed' },
            { date: '2024-12-21 10:30', location: 'Surabaya Delivery Center', description: 'Out for delivery', status: 'current' },
            { date: 'Est. 2024-12-21 16:00', location: 'Customer Address', description: 'Estimated delivery', status: 'pending' }
        ]
    };
}

function displayTrackingInfo(data) {
    let trackingHTML = `
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Tracking Number</h6>
                <code>${data.trackingNumber}</code>
            </div>
            <div class="col-md-6">
                <h6>Carrier</h6>
                <span class="badge bg-info">${data.carrier}</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Current Status</h6>
                <span class="badge bg-primary">${data.status}</span>
            </div>
            <div class="col-md-6">
                <h6>Estimated Delivery</h6>
                <span>${data.estimatedDelivery}</span>
            </div>
        </div>
        <h6>Tracking History</h6>
        <div class="tracking-timeline">
    `;
    
    data.events.forEach(event => {
        trackingHTML += `
            <div class="tracking-item ${event.status}">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>${event.description}</strong>
                        <br><small class="text-muted">${event.location}</small>
                    </div>
                    <div class="text-end">
                        <small>${event.date}</small>
                    </div>
                </div>
            </div>
        `;
    });
    
    trackingHTML += '</div>';
    
    $('#trackingContent').html(trackingHTML);
}

// Additional functions
function printLabel(shipmentId) {
    window.open(`{{ route('return-shipment.index') }}/${shipmentId}/label`, '_blank');
}

function printPackingList(shipmentId) {
    window.open(`{{ route('return-shipment.index') }}/${shipmentId}/packing-list`, '_blank');
}

function requestFeedback(shipmentId) {
    if (confirm('Send feedback request to customer?')) {
        // Implementation for sending feedback request
        alert('Feedback request sent to customer');
    }
}

function bulkShip() {
    let selectedIds = $('.shipment-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        alert('Please select at least one shipment');
        return;
    }
    
    if (confirm(`Are you sure you want to ship ${selectedIds.length} packages?`)) {
        // Implementation for bulk shipping
        alert('Bulk shipping functionality would be implemented here');
    }
}
</script>
@endpush