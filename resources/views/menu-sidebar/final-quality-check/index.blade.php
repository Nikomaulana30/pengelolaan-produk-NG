@extends('layouts.app')

@section('title', 'Final Quality Check')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Final Quality Check</h3>
                <p class="text-subtitle text-muted">Pemeriksaan kualitas final sebelum pengiriman kembali ke customer</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Final Quality Check</li>
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
                        <div class="step active">
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
                                <i class="iconly-boldShield-Done"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total QC</h6>
                            <h6 class="font-extrabold mb-0">{{ $totalQC ?? 0 }}</h6>
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
                            <h6 class="text-muted font-semibold">Passed</h6>
                            <h6 class="font-extrabold mb-0">{{ $passedCount ?? 0 }}</h6>
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
                            <h6 class="text-muted font-semibold">Failed</h6>
                            <h6 class="font-extrabold mb-0">{{ $failedCount ?? 0 }}</h6>
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
                        <h5 class="card-title">Final Quality Check</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="btn-group">
                            <a href="{{ route('final-quality-check.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus"></i> New Quality Check
                            </a>
                            <button class="btn btn-success" onclick="bulkApprove()" id="bulkApproveBtn" style="display: none;">
                                <i class="bi bi-check-circle"></i> Approve Selected
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
                            <option value="passed">Passed</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="inspectorFilter">
                            <option value="">Semua Inspector</option>
                            <option value="QC001">Inspector A</option>
                            <option value="QC002">Inspector B</option>
                            <option value="QC003">Inspector C</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateFilter">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchFilter" 
                               placeholder="Cari nomor QC, customer, produk...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary" id="resetFilter">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-striped" id="qualityCheckTable">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>No. QC</th>
                                <th>No. Rework</th>
                                <th>Customer</th>
                                <th>Produk</th>
                                <th>Qty Checked</th>
                                <th>Result</th>
                                <th>Inspector</th>
                                <th>Date</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($finalQualityChecks as $qc)
                            <tr>
                                <td>
                                    @if($qc->status === 'draft')
                                    <div class="form-check">
                                        <input class="form-check-input qc-checkbox" 
                                               type="checkbox" value="{{ $qc->id }}">
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>#{{ $qc->id }}</strong>
                                </td>
                                <td>
                                    <a href="{{ route('production-rework.show', $qc->productionRework) }}" 
                                       class="text-decoration-none">
                                        {{ $qc->productionRework->nomor_rework }}
                                    </a>
                                </td>
                                <td>{{ $qc->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</td>
                                <td>{{ $qc->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ number_format($qc->quantity_checked, 0) }}</span>
                                        <small class="text-muted">pcs</small>
                                    </div>
                                </td>
                                <td>
                                    @if($qc->keputusan_final === 'approved_for_shipment')
                                        <div class="d-flex flex-column">
                                            <span class="badge bg-success">✅ APPROVED</span>
                                            <small class="text-success">Passed: {{ $qc->quantity_passed }}</small>
                                        </div>
                                    @elseif($qc->keputusan_final === 'rejected')
                                        <div class="d-flex flex-column">
                                            <span class="badge bg-danger">❌ REJECTED</span>
                                            <small class="text-danger">Failed: {{ $qc->quantity_failed }}</small>
                                        </div>
                                    @elseif($qc->keputusan_final === 'need_rework')
                                        <span class="badge bg-warning">🔄 NEED REWORK</span>
                                    @else
                                        <span class="badge bg-secondary">DRAFT</span>
                                    @endif
                                </td>
                                <td>{{ $qc->staffExim->name ?? '-' }}</td>
                                <td>{{ $qc->tanggal_check ? $qc->tanggal_check->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('final-quality-check.show', $qc) }}" 
                                           class="btn btn-sm btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('final-quality-check.edit', $qc) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($qc->keputusan_final === 'approved_for_shipment' && !$qc->returnShipment)
                                        <a href="{{ route('return-shipment.create') }}?final_check_id={{ $qc->id }}" 
                                           class="btn btn-sm btn-outline-primary" title="Create Shipment">
                                            <i class="bi bi-truck"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-clipboard-check" style="font-size: 3rem; color: #ccc;"></i>
                                        <h5 class="mt-3">Belum ada final quality check</h5>
                                        <p class="text-muted">Data akan muncul ketika production rework sudah completed</p>
                                        <a href="{{ route('final-quality-check.create') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-plus"></i> New Quality Check
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($finalQualityChecks) && $finalQualityChecks instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <p class="text-muted">
                            Menampilkan {{ $finalQualityChecks->firstItem() ?? 0 }} sampai {{ $finalQualityChecks->lastItem() ?? 0 }} 
                            dari {{ $finalQualityChecks->total() }} data
                        </p>
                    </div>
                    <div>
                        {{ $finalQualityChecks->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Modal for Inspection -->
<div class="modal fade" id="inspectionModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quality Inspection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="inspectionForm">
                    @csrf
                    <input type="hidden" id="qcId">
                    <input type="hidden" id="inspectionResult">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Checked Quantity</label>
                            <input type="number" class="form-control" id="checkedQuantity" 
                                   step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Inspector Name</label>
                            <input type="text" class="form-control" id="inspectorName" 
                                   value="{{ auth()->user()->name }}" required>
                        </div>
                    </div>
                    
                    <!-- Quality Check Points -->
                    <div class="mb-3">
                        <label class="form-label">Quality Check Points</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="dimensionCheck">
                                    <label class="form-check-label" for="dimensionCheck">
                                        Dimensional Check
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="visualCheck">
                                    <label class="form-check-label" for="visualCheck">
                                        Visual Inspection
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="functionalCheck">
                                    <label class="form-check-label" for="functionalCheck">
                                        Functional Test
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="materialCheck">
                                    <label class="form-check-label" for="materialCheck">
                                        Material Verification
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="surfaceCheck">
                                    <label class="form-check-label" for="surfaceCheck">
                                        Surface Quality
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="packagingCheck">
                                    <label class="form-check-label" for="packagingCheck">
                                        Packaging Check
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Defect Details -->
                    <div class="mb-3" id="defectSection" style="display: none;">
                        <label class="form-label">Defect Details</label>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Defect Count</label>
                                <input type="number" class="form-control" id="defectCount" min="0" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Severity</label>
                                <select class="form-select" id="defectSeverity">
                                    <option value="">Select severity</option>
                                    <option value="minor">Minor (Acceptable)</option>
                                    <option value="major">Major (Requires attention)</option>
                                    <option value="critical">Critical (Not acceptable)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="form-label">Defect Description</label>
                            <textarea class="form-control" id="defectDescription" rows="3" 
                                      placeholder="Describe the defects found..."></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Inspection Notes</label>
                        <textarea class="form-control" id="inspectionNotes" rows="3" 
                                  placeholder="Add inspection notes..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload Photos</label>
                        <input type="file" class="form-control" id="inspectionPhotos" 
                               multiple accept="image/*">
                        <small class="text-muted">Upload inspection photos (multiple files)</small>
                    </div>
                    
                    <!-- Result Summary -->
                    <div class="alert alert-info">
                        <h6>Inspection Result:</h6>
                        <div id="resultSummary">
                            <p class="mb-0">Complete all check points to see result summary</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitInspection('passed')">Pass & Approve</button>
                <button type="button" class="btn btn-danger" onclick="submitInspection('failed')">Fail & Reject</button>
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

/* Quality check styling */
.form-check {
    margin-bottom: 0.5rem;
}

.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

/* Alert styling */
.alert-info {
    background-color: #d1ecf1;
    border-color: #b6d4ea;
    color: #0c5460;
}

/* Badge styling */
.badge.bg-success {
    background-color: #198754 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
}

.badge.bg-primary {
    background-color: #0d6efd !important;
}

.badge.bg-secondary {
    background-color: #6c757d !important;
}

/* Defect severity styling */
.text-warning {
    color: #fd7e14 !important;
}

.text-danger {
    color: #dc3545 !important;
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
    
    .modal-xl {
        max-width: 95%;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#qualityCheckTable').DataTable({
        responsive: true,
        ordering: true,
        searching: false,
        paging: false,
        info: false,
        language: {
            emptyTable: "Tidak ada data final quality check",
            zeroRecords: "Tidak ditemukan data yang sesuai"
        }
    });

    // Custom filters
    $('#searchFilter').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#statusFilter').on('change', function() {
        let status = $(this).val();
        table.column(6).search(status).draw();
    });

    $('#inspectorFilter').on('change', function() {
        let inspector = $(this).val();
        table.column(7).search(inspector).draw();
    });

    $('#resetFilter').on('click', function() {
        $('#statusFilter, #inspectorFilter, #searchFilter, #dateFilter').val('');
        table.search('').columns().search('').draw();
    });

    // Checkbox functionality
    $('#selectAll').on('change', function() {
        $('.qc-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkButtons();
    });

    $(document).on('change', '.qc-checkbox', function() {
        updateBulkButtons();
        let totalCheckboxes = $('.qc-checkbox').length;
        let checkedCheckboxes = $('.qc-checkbox:checked').length;
        $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes);
    });

    function updateBulkButtons() {
        let checkedCount = $('.qc-checkbox:checked').length;
        $('#bulkApproveBtn').toggle(checkedCount > 0);
    }

    // Real-time result calculation
    $('.form-check-input, #defectCount, #defectSeverity').on('change', function() {
        updateResultSummary();
    });

    $('#defectCount').on('input', function() {
        let count = parseInt($(this).val()) || 0;
        $('#defectSection').toggle(count > 0);
        updateResultSummary();
    });
});

// Inspection functions
function startInspection(qcId) {
    $('#qcId').val(qcId);
    $('#inspectionForm')[0].reset();
    $('#qcId').val(qcId);
    $('#defectSection').hide();
    updateResultSummary();
    
    $('#inspectionModal').modal('show');
}

function completeInspection(qcId, result) {
    // Quick completion without detailed modal
    if (confirm(`Are you sure you want to mark this as ${result.toUpperCase()}?`)) {
        updateQCStatus(qcId, result, {});
    }
}

function submitInspection(result) {
    let qcId = $('#qcId').val();
    let formData = new FormData();
    
    // Basic data
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('_method', 'PUT');
    formData.append('status', result);
    formData.append('checked_quantity', $('#checkedQuantity').val());
    formData.append('inspector_name', $('#inspectorName').val());
    formData.append('inspection_notes', $('#inspectionNotes').val());
    
    // Check points
    let checkPoints = [];
    $('.form-check-input:checked').each(function() {
        if ($(this).attr('id') !== 'selectAll') {
            checkPoints.push($(this).attr('id'));
        }
    });
    formData.append('check_points', JSON.stringify(checkPoints));
    
    // Defect data
    formData.append('defect_count', $('#defectCount').val() || 0);
    formData.append('defect_severity', $('#defectSeverity').val() || '');
    formData.append('defect_description', $('#defectDescription').val() || '');
    
    // Photos
    let photos = $('#inspectionPhotos')[0].files;
    for (let i = 0; i < photos.length; i++) {
        formData.append('photos[]', photos[i]);
    }
    
    // Validation
    if (!$('#checkedQuantity').val()) {
        alert('Checked quantity is required');
        return;
    }
    
    if (!$('#inspectorName').val()) {
        alert('Inspector name is required');
        return;
    }
    
    let defectCount = parseInt($('#defectCount').val()) || 0;
    if (result === 'failed' && defectCount === 0) {
        alert('Please specify defect count for failed inspection');
        return;
    }
    
    // Show loading
    let buttons = $('#inspectionModal .modal-footer button');
    buttons.prop('disabled', true);
    
    $.ajax({
        url: `{{ route('final-quality-check.index') }}/${qcId}/complete-inspection`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#inspectionModal').modal('hide');
            
            let alertClass = result === 'passed' ? 'alert-success' : 'alert-warning';
            let alertMessage = `Quality check completed - ${result.toUpperCase()}`;
            
            $('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                alertMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>').insertBefore('.card');
            
            setTimeout(() => location.reload(), 1500);
        },
        error: function(xhr) {
            let errorMessage = 'Error completing quality check';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            $('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                errorMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>').insertBefore('.card');
        },
        complete: function() {
            buttons.prop('disabled', false);
        }
    });
}

function updateResultSummary() {
    let checkedPoints = $('.form-check-input:checked').length - ($('#selectAll').is(':checked') ? 1 : 0);
    let totalPoints = $('.form-check-input').length - 1; // Exclude selectAll
    let defectCount = parseInt($('#defectCount').val()) || 0;
    let defectSeverity = $('#defectSeverity').val();
    
    let summaryHTML = '';
    
    if (checkedPoints === 0) {
        summaryHTML = '<p class="mb-0 text-muted">Complete check points to see result summary</p>';
    } else {
        let percentage = Math.round((checkedPoints / totalPoints) * 100);
        
        summaryHTML = `
            <p class="mb-2"><strong>Check Points:</strong> ${checkedPoints}/${totalPoints} completed (${percentage}%)</p>
            <p class="mb-2"><strong>Defects:</strong> ${defectCount} ${defectSeverity ? `(${defectSeverity})` : ''}</p>
        `;
        
        // Determine recommendation
        if (defectCount === 0 && checkedPoints === totalPoints) {
            summaryHTML += '<p class="mb-0 text-success"><strong>Recommendation: PASS</strong> - All checks passed, no defects found</p>';
        } else if (defectCount > 0 && defectSeverity === 'critical') {
            summaryHTML += '<p class="mb-0 text-danger"><strong>Recommendation: FAIL</strong> - Critical defects found</p>';
        } else if (defectCount > 0 && defectSeverity === 'major') {
            summaryHTML += '<p class="mb-0 text-warning"><strong>Recommendation: CONDITIONAL PASS</strong> - Major defects require review</p>';
        } else if (defectCount > 0 && defectSeverity === 'minor') {
            summaryHTML += '<p class="mb-0 text-info"><strong>Recommendation: PASS</strong> - Minor defects acceptable</p>';
        } else if (checkedPoints < totalPoints) {
            summaryHTML += '<p class="mb-0 text-warning"><strong>Status: INCOMPLETE</strong> - Complete all check points</p>';
        }
    }
    
    $('#resultSummary').html(summaryHTML);
}

// Additional functions
function requestRework(qcId) {
    if (confirm('Request re-rework for this item? This will create a new rework order.')) {
        // Implementation for requesting re-rework
        alert('Re-rework request functionality would be implemented here');
    }
}

function viewInspectionDetails(qcId) {
    // Implementation for viewing detailed inspection results
    alert('View inspection details functionality would be implemented here');
}

function printCertificate(qcId) {
    // Implementation for printing quality certificate
    window.open(`{{ route('final-quality-check.index') }}/${qcId}/certificate`, '_blank');
}

function assignInspector(qcId) {
    // Implementation for assigning inspector
    alert('Assign inspector functionality would be implemented here');
}

function bulkApprove() {
    let selectedIds = $('.qc-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        alert('Please select at least one quality check');
        return;
    }
    
    if (confirm(`Are you sure you want to approve ${selectedIds.length} quality checks?`)) {
        // Implementation for bulk approval
        alert('Bulk approve functionality would be implemented here');
    }
}

// Auto-refresh for real-time updates
setInterval(function() {
    if (!$('#inspectionModal').hasClass('show')) {
        // Only reload if modal is not open
        // window.location.reload();
    }
}, 180000); // 3 minutes
</script>
@endpush