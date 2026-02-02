@extends('layouts.app')

@section('title', 'Production Rework')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Production Rework</h3>
                <p class="text-subtitle text-muted">Proses rework produksi (melting, grinding, dll) untuk barang NG</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Production Rework</li>
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
                        <div class="step active">
                            <div class="step-circle">5</div>
                            <span>Production Rework</span>
                        </div>
                        <div class="step">
                            <div class="step-circle">6</div>
                            <span>Final Quality Check</span>
                        </div>
                        <div class="step">
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
                                <i class="iconly-boldSetting"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Rework</h6>
                            <h6 class="font-extrabold mb-0">{{ $totalRework ?? 0 }}</h6>
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
                            <h6 class="text-muted font-semibold">In Progress</h6>
                            <h6 class="font-extrabold mb-0">{{ $inProgressCount ?? 0 }}</h6>
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
                            <h6 class="text-muted font-semibold">Completed</h6>
                            <h6 class="font-extrabold mb-0">{{ $completedCount ?? 0 }}</h6>
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
                            <div class="stats-icon red mb-2">
                                <i class="iconly-boldDanger"></i>
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
    </div>

    <!-- Main Content -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title">Production Rework</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="btn-group">
                            <a href="{{ route('production-rework.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus"></i> New Rework Order
                            </a>
                            <button class="btn btn-success" onclick="bulkStart()" id="bulkStartBtn" style="display: none;">
                                <i class="bi bi-play"></i> Start Selected
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
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="reworkTypeFilter">
                            <option value="">Semua Type</option>
                            <option value="melting">Melting</option>
                            <option value="grinding">Grinding</option>
                            <option value="machining">Machining</option>
                            <option value="welding">Welding</option>
                            <option value="surface_treatment">Surface Treatment</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="priorityFilter">
                            <option value="">Semua Priority</option>
                            <option value="low">Low</option>
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchFilter" 
                               placeholder="Cari nomor rework, customer, produk...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary" id="resetFilter">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-striped" id="reworkTable">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>No. Rework</th>
                                <th>No. Reinspection</th>
                                <th>Customer</th>
                                <th>Produk</th>
                                <th>Metode Rework</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th>Tanggal Mulai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productionReworks ?? [] as $rework)
                            <tr>
                                <td>
                                    @if($rework->status === 'pending')
                                    <div class="form-check">
                                        <input class="form-check-input rework-checkbox" 
                                               type="checkbox" value="{{ $rework->id }}">
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $rework->nomor_rework }}</strong>
                                </td>
                                <td>
                                    <a href="{{ route('quality-reinspection.show', $rework->qualityReinspection) }}" 
                                       class="text-decoration-none">
                                        {{ $rework->qualityReinspection->nomor_reinspeksi }}
                                    </a>
                                </td>
                                <td>{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</td>
                                <td>{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $rework->metode_rework)) }}
                                    </span>
                                </td>
                                <td>{{ number_format($rework->quantity_rework, 0) }} pcs</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $rework->status === 'draft' ? 'secondary' : 
                                        ($rework->status === 'in_progress' ? 'primary' : 
                                        ($rework->status === 'completed' ? 'success' : 
                                        ($rework->status === 'sent_to_quality_check' ? 'info' : 'warning'))) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $rework->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($rework->tanggal_mulai_rework)
                                        <span class="text-muted">{{ \Carbon\Carbon::parse($rework->tanggal_mulai_rework)->format('d M Y') }}</span>
                                        @if($rework->estimasi_waktu_hari)
                                            <br>
                                            <small class="text-muted">
                                                Est: {{ $rework->estimasi_waktu_hari }} hari
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('production-rework.show', $rework) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($rework->status !== 'completed')
                                        <a href="{{ route('production-rework.edit', $rework) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @endif
                                        @if($rework->status === 'pending')
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="updateStatus({{ $rework->id }}, 'in_progress')">
                                            <i class="bi bi-play"></i>
                                        </button>
                                        @endif
                                        @if($rework->status === 'in_progress')
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="updateProgress({{ $rework->id }})">
                                            <i class="bi bi-percent"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="updateStatus({{ $rework->id }}, 'completed')">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        @endif
                                        @if($rework->status === 'completed')
                                        <a href="{{ route('final-quality-check.create', ['rework' => $rework->id]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-arrow-right"></i> To QC
                                        </a>
                                        @endif
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                                    data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><button class="dropdown-item" onclick="assignOperator({{ $rework->id }})">
                                                    <i class="bi bi-person"></i> Assign Operator
                                                </button></li>
                                                <li><button class="dropdown-item" onclick="addMaterial({{ $rework->id }})">
                                                    <i class="bi bi-box"></i> Add Materials
                                                </button></li>
                                                <li><button class="dropdown-item" onclick="viewHistory({{ $rework->id }})">
                                                    <i class="bi bi-clock-history"></i> View History
                                                </button></li>
                                                @if($rework->status !== 'completed')
                                                <li><hr class="dropdown-divider"></li>
                                                <li><button class="dropdown-item text-danger" onclick="updateStatus({{ $rework->id }}, 'failed')">
                                                    <i class="bi bi-x-circle"></i> Mark as Failed
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
                                        <i class="bi bi-gear display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada rework order</h5>
                                        <p class="text-muted">Data akan muncul ketika RCA analysis sudah completed dengan corrective action 'rework'</p>
                                        <a href="{{ route('production-rework.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus"></i> New Rework Order
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($productionReworks) && $productionReworks instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <p class="text-muted">
                            Menampilkan {{ $productionReworks->firstItem() ?? 0 }} sampai {{ $productionReworks->lastItem() ?? 0 }} 
                            dari {{ $productionReworks->total() }} data
                        </p>
                    </div>
                    <div>
                        {{ $productionReworks->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Modal for Status Update -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Rework Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    @csrf
                    <input type="hidden" id="reworkId">
                    <input type="hidden" id="newStatus">
                    
                    <div id="completionFields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Final Quantity</label>
                            <input type="number" class="form-control" id="finalQuantity" 
                                   step="0.01" placeholder="Actual quantity after rework">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quality Result</label>
                            <select class="form-select" id="qualityResult">
                                <option value="">Select quality result</option>
                                <option value="pass">Pass - Ready for final QC</option>
                                <option value="partial">Partial - Some defects remain</option>
                                <option value="fail">Fail - Rework unsuccessful</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="failedFields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Failure Reason</label>
                            <select class="form-select" id="failureReason">
                                <option value="">Select reason</option>
                                <option value="material_issue">Material Issue</option>
                                <option value="equipment_failure">Equipment Failure</option>
                                <option value="process_limitation">Process Limitation</option>
                                <option value="quality_standard">Cannot Meet Quality Standard</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="statusNotes" rows="3" 
                                  placeholder="Add notes about the status update..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload Photos/Documents</label>
                        <input type="file" class="form-control" id="statusFiles" 
                               multiple accept="image/*,.pdf,.doc,.docx">
                        <small class="text-muted">Upload photos of completed work or documents</small>
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

<!-- Modal for Progress Update -->
<div class="modal fade" id="progressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Progress</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="progressForm">
                    @csrf
                    <input type="hidden" id="progressReworkId">
                    
                    <div class="mb-3">
                        <label class="form-label">Progress Percentage</label>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="range" class="form-range" id="progressRange" 
                                       min="0" max="100" value="0" oninput="updateProgressDisplay()">
                            </div>
                            <div class="col-md-4">
                                <input type="number" class="form-control" id="progressValue" 
                                       min="0" max="100" value="0" oninput="updateProgressRange()">
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 10px;">
                            <div class="progress-bar" id="progressBar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Current Step</label>
                        <input type="text" class="form-control" id="currentStep" 
                               placeholder="e.g., Melting in progress, Cooling down, etc.">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="progressNotes" rows="3" 
                                  placeholder="Add notes about current progress..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmProgressUpdate()">Update Progress</button>
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

.stats-icon.red {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

/* Progress bar styling */
.progress {
    background-color: #e9ecef;
}

.progress-bar {
    transition: width 0.6s ease;
}

/* Priority badges */
.badge.bg-success {
    background-color: #198754 !important;
}
.badge.bg-info {
    background-color: #0dcaf0 !important;
}
.badge.bg-warning {
    background-color: #fd7e14 !important;
}
.badge.bg-danger {
    background-color: #dc3545 !important;
}

/* Overdue styling */
.text-danger {
    color: #dc3545 !important;
    font-weight: 600;
}

/* Custom range slider */
.form-range::-webkit-slider-thumb {
    background: #0d6efd;
}

.form-range::-moz-range-thumb {
    background: #0d6efd;
    border: none;
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#reworkTable').DataTable({
        responsive: true,
        ordering: true,
        searching: false,
        paging: false,
        info: false,
        language: {
            emptyTable: "Tidak ada data production rework",
            zeroRecords: "Tidak ditemukan data yang sesuai"
        }
    });

    // Custom filters
    $('#searchFilter').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#statusFilter').on('change', function() {
        let status = $(this).val();
        table.column(7).search(status).draw();
    });

    $('#reworkTypeFilter').on('change', function() {
        let type = $(this).val();
        table.column(5).search(type).draw();
    });

    $('#priorityFilter').on('change', function() {
        let priority = $(this).val();
        table.column(8).search(priority).draw();
    });

    $('#resetFilter').on('click', function() {
        $('#statusFilter, #reworkTypeFilter, #priorityFilter, #searchFilter').val('');
        table.search('').columns().search('').draw();
    });

    // Checkbox functionality
    $('#selectAll').on('change', function() {
        $('.rework-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkButtons();
    });

    $(document).on('change', '.rework-checkbox', function() {
        updateBulkButtons();
        let totalCheckboxes = $('.rework-checkbox').length;
        let checkedCheckboxes = $('.rework-checkbox:checked').length;
        $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes);
    });

    function updateBulkButtons() {
        let checkedCount = $('.rework-checkbox:checked').length;
        $('#bulkStartBtn').toggle(checkedCount > 0);
    }
});

// Status update functions
function updateStatus(reworkId, newStatus) {
    $('#reworkId').val(reworkId);
    $('#newStatus').val(newStatus);
    $('#statusForm')[0].reset();
    $('#reworkId').val(reworkId);
    $('#newStatus').val(newStatus);
    
    let modalTitle = 'Update Rework Status';
    $('#completionFields, #failedFields').hide();
    
    if (newStatus === 'completed') {
        modalTitle = 'Complete Rework';
        $('#completionFields').show();
    } else if (newStatus === 'failed') {
        modalTitle = 'Mark Rework as Failed';
        $('#failedFields').show();
    }
    
    $('#statusModal .modal-title').text(modalTitle);
    $('#statusModal').modal('show');
}

function confirmStatusUpdate() {
    let reworkId = $('#reworkId').val();
    let newStatus = $('#newStatus').val();
    let formData = new FormData();
    
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('_method', 'PUT');
    formData.append('status', newStatus);
    formData.append('notes', $('#statusNotes').val());
    
    // Add completion fields if needed
    if (newStatus === 'completed') {
        formData.append('final_quantity', $('#finalQuantity').val());
        formData.append('quality_result', $('#qualityResult').val());
    }
    
    // Add failed fields if needed
    if (newStatus === 'failed') {
        formData.append('failure_reason', $('#failureReason').val());
    }
    
    // Add files
    let files = $('#statusFiles')[0].files;
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    
    // Basic validation
    if (newStatus === 'completed' && !$('#qualityResult').val()) {
        alert('Quality result is required for completion');
        return;
    }
    
    if (newStatus === 'failed' && !$('#failureReason').val()) {
        alert('Failure reason is required');
        return;
    }
    
    // Show loading
    let submitBtn = $('#statusModal .btn-primary');
    let originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Processing...');
    
    $.ajax({
        url: `{{ route('production-rework.index') }}/${reworkId}/update-status`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#statusModal').modal('hide');
            
            let alertMessage = 'Rework status updated successfully';
            $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                alertMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>').insertBefore('.card');
            
            setTimeout(() => location.reload(), 1500);
        },
        error: function(xhr) {
            let errorMessage = 'Error updating rework status';
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

// Progress update functions
function updateProgress(reworkId) {
    $('#progressReworkId').val(reworkId);
    $('#progressForm')[0].reset();
    $('#progressReworkId').val(reworkId);
    $('#progressRange').val(0);
    $('#progressValue').val(0);
    updateProgressDisplay();
    
    $('#progressModal').modal('show');
}

function updateProgressDisplay() {
    let value = $('#progressRange').val();
    $('#progressValue').val(value);
    $('#progressBar').css('width', value + '%').text(value + '%');
}

function updateProgressRange() {
    let value = $('#progressValue').val();
    $('#progressRange').val(value);
    updateProgressDisplay();
}

function confirmProgressUpdate() {
    let reworkId = $('#progressReworkId').val();
    let progress = $('#progressValue').val();
    let currentStep = $('#currentStep').val();
    let notes = $('#progressNotes').val();
    
    if (!progress || progress < 0 || progress > 100) {
        alert('Please enter a valid progress percentage (0-100)');
        return;
    }
    
    let submitBtn = $('#progressModal .btn-primary');
    let originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Updating...');
    
    $.ajax({
        url: `{{ route('production-rework.index') }}/${reworkId}/update-progress`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        data: JSON.stringify({
            progress_percentage: progress,
            current_step: currentStep,
            notes: notes
        }),
        success: function(response) {
            $('#progressModal').modal('hide');
            
            $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                'Progress updated successfully' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>').insertBefore('.card');
            
            setTimeout(() => location.reload(), 1500);
        },
        error: function(xhr) {
            let errorMessage = 'Error updating progress';
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

// Additional functions (stubs - would need backend implementation)
function assignOperator(reworkId) {
    alert('Assign operator functionality would be implemented here');
}

function addMaterial(reworkId) {
    alert('Add material functionality would be implemented here');
}

function viewHistory(reworkId) {
    alert('View history functionality would be implemented here');
}

function bulkStart() {
    let selectedIds = $('.rework-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        alert('Please select at least one rework order');
        return;
    }
    
    if (confirm(`Are you sure you want to start ${selectedIds.length} rework orders?`)) {
        // Implementation for bulk start
        alert('Bulk start functionality would be implemented here');
    }
}
</script>
@endpush