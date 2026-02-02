@extends('layouts.app')

@section('title', 'Quality Reinspection & RCA')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Quality Reinspection & RCA</h3>
                <p class="text-subtitle text-muted">Root Cause Analysis dan reinspeksi quality untuk barang NG</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quality Reinspection</li>
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
                        <div class="step active">
                            <div class="step-circle">4</div>
                            <span>Quality Reinspection</span>
                        </div>
                        <div class="step">
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
                                <i class="iconly-boldSearch"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total RCA</h6>
                            <h6 class="font-extrabold mb-0">{{ $totalRCA ?? 0 }}</h6>
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
                            <h6 class="text-muted font-semibold">Rework Required</h6>
                            <h6 class="font-extrabold mb-0">{{ $reworkRequiredCount ?? 0 }}</h6>
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
                        <h5 class="card-title">Quality Reinspection & RCA</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('quality-reinspection.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> New RCA Analysis
                        </a>
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
                            <option value="rework_required">Rework Required</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="severityFilter">
                            <option value="">Semua Severity</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateFilter">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchFilter" 
                               placeholder="Cari nomor RCA, customer, produk...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary" id="resetFilter">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-striped" id="rcaTable">
                        <thead>
                            <tr>
                                <th>No. Inspeksi</th>
                                <th>No. Verifikasi</th>
                                <th>Customer</th>
                                <th>Produk</th>
                                <th>Root Cause / Defect</th>
                                <th>Severity</th>
                                <th>Status</th>
                                <th>Quality Manager</th>
                                <th>Inspeksi Date</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($qualityReinspections ?? [] as $rca)
                            <tr>
                                <td>
                                    <strong>{{ $rca->nomor_inspeksi }}</strong>
                                </td>
                                <td>
                                    <a href="{{ route('warehouse-verification.show', $rca->warehouseVerification) }}" 
                                       class="text-decoration-none">
                                        {{ $rca->warehouseVerification->nomor_verifikasi }}
                                    </a>
                                </td>
                                <td>{{ $rca->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</td>
                                <td>{{ $rca->warehouseVerification->dokumenRetur->customerComplaint->produk }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong>{{ $rca->jenis_defect }}</strong>
                                        <small class="text-muted">{{ Str::limit($rca->root_cause_analysis, 80) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $rca->severity_level === 'minor' ? 'success' : 
                                        ($rca->severity_level === 'major' ? 'warning' : 
                                        ($rca->severity_level === 'critical' ? 'danger' : 'dark')) 
                                    }}">
                                        {{ ucfirst($rca->severity_level) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $rca->status === 'draft' ? 'secondary' : 
                                        ($rca->status === 'inspected' ? 'primary' : 
                                        ($rca->status === 'rework_completed' ? 'success' : 'warning')) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $rca->status)) }}
                                    </span>
                                </td>
                                <td>{{ $rca->qualityManager->name ?? '-' }}</td>
                                <td>
                                    @if($rca->inspected_at)
                                        <span class="text-success">
                                            {{ $rca->inspected_at->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('quality-reinspection.show', $rca) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($rca->status !== 'rework_completed')
                                        <a href="{{ route('quality-reinspection.edit', $rca) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @endif
                                        @if($rca->status === 'inspected' && $rca->disposisi === 'rework')
                                        <a href="{{ route('production-rework.create', ['rca' => $rca->id]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-arrow-right"></i> Rework
                                        </a>
                                        @endif
                                        @if($rca->status === 'inspected' && $rca->disposisi === 'scrap')
                                        <a href="{{ route('scrap-disposal.create', ['rca' => $rca->id]) }}" 
                                           class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Scrap
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-search display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada RCA analysis</h5>
                                        <p class="text-muted">Data akan muncul ketika warehouse verification sudah completed</p>
                                        <a href="{{ route('quality-reinspection.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus"></i> New RCA Analysis
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($qualityReinspections) && $qualityReinspections instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <p class="text-muted">
                            Menampilkan {{ $qualityReinspections->firstItem() ?? 0 }} sampai {{ $qualityReinspections->lastItem() ?? 0 }} 
                            dari {{ $qualityReinspections->total() }} data
                        </p>
                    </div>
                    <div>
                        {{ $qualityReinspections->links() }}
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
                <h5 class="modal-title">Update RCA Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    @csrf
                    <input type="hidden" id="rcaId">
                    <input type="hidden" id="newStatus">
                    
                    <div id="completionFields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Corrective Action</label>
                            <select class="form-select" id="correctiveAction">
                                <option value="">Select action</option>
                                <option value="rework">Rework/Repair</option>
                                <option value="scrap">Scrap/Dispose</option>
                                <option value="customer_sort">Customer Sorting</option>
                                <option value="return_as_is">Return As-Is</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prevention Action</label>
                            <textarea class="form-control" id="preventionAction" rows="3" 
                                      placeholder="Describe preventive actions to avoid future occurrence..."></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="statusNotes" rows="3" 
                                  placeholder="Add notes for status update..."></textarea>
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

<!-- Modal for Assignment -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign RCA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="assignForm">
                    @csrf
                    <input type="hidden" id="assignRcaId">
                    
                    <div class="mb-3">
                        <label class="form-label">Assign To</label>
                        <select class="form-select" id="assignTo" required>
                            <option value="">Select quality engineer</option>
                            <!-- This would be populated with quality engineers -->
                            <option value="QE001">John Doe - Senior QE</option>
                            <option value="QE002">Jane Smith - QE Specialist</option>
                            <option value="QE003">Mike Johnson - QE Manager</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Target Completion Date</label>
                        <input type="date" class="form-control" id="targetDate" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select class="form-select" id="priority">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Assignment Notes</label>
                        <textarea class="form-control" id="assignmentNotes" rows="3" 
                                  placeholder="Add assignment instructions or notes..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmAssignment()">Assign</button>
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

/* Severity badges */
.badge.bg-success {
    background-color: #198754 !important;
}
.badge.bg-warning {
    background-color: #fd7e14 !important;
}
.badge.bg-danger {
    background-color: #dc3545 !important;
}
.badge.bg-dark {
    background-color: #212529 !important;
}

/* Overdue dates */
.text-danger {
    color: #dc3545 !important;
    font-weight: 600;
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

/* Dropdown menu styling */
.dropdown-menu {
    min-width: 180px;
}

.dropdown-item {
    padding: 8px 16px;
}

.dropdown-item i {
    width: 16px;
    margin-right: 8px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#rcaTable').DataTable({
        responsive: true,
        ordering: true,
        searching: false,
        paging: false,
        info: false,
        language: {
            emptyTable: "Tidak ada data RCA analysis",
            zeroRecords: "Tidak ditemukan data yang sesuai"
        }
    });

    // Custom search functionality
    $('#searchFilter').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        let status = $(this).val();
        if (status) {
            table.column(6).search(status).draw();
        } else {
            table.column(6).search('').draw();
        }
    });

    // Severity filter
    $('#severityFilter').on('change', function() {
        let severity = $(this).val();
        if (severity) {
            table.column(5).search(severity).draw();
        } else {
            table.column(5).search('').draw();
        }
    });

    // Reset filters
    $('#resetFilter').on('click', function() {
        $('#statusFilter').val('');
        $('#severityFilter').val('');
        $('#dateFilter').val('');
        $('#searchFilter').val('');
        table.search('').columns().search('').draw();
    });

    // Set minimum date for target date to today
    $('#targetDate').attr('min', new Date().toISOString().split('T')[0]);
});

// Status update functions
function updateStatus(rcaId, newStatus) {
    $('#rcaId').val(rcaId);
    $('#newStatus').val(newStatus);
    $('#statusNotes').val('');
    
    let modalTitle = 'Update RCA Status';
    if (newStatus === 'completed') {
        modalTitle = 'Complete RCA Analysis';
        $('#completionFields').show();
    } else {
        $('#completionFields').hide();
    }
    
    $('#statusModal .modal-title').text(modalTitle);
    $('#statusModal').modal('show');
}

function confirmStatusUpdate() {
    let rcaId = $('#rcaId').val();
    let newStatus = $('#newStatus').val();
    let notes = $('#statusNotes').val();
    let correctiveAction = $('#correctiveAction').val();
    let preventionAction = $('#preventionAction').val();
    
    // Validation for completed status
    if (newStatus === 'completed') {
        if (!correctiveAction) {
            alert('Corrective action is required for completion');
            return;
        }
        if (!preventionAction.trim()) {
            alert('Prevention action is required for completion');
            return;
        }
    }
    
    // Show loading
    let submitBtn = $('#statusModal .btn-primary');
    let originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Processing...');
    
    $.ajax({
        url: `{{ route('quality-reinspection.index') }}/${rcaId}/update-status`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        data: JSON.stringify({
            status: newStatus,
            notes: notes,
            corrective_action: correctiveAction,
            prevention_action: preventionAction
        }),
        success: function(response) {
            $('#statusModal').modal('hide');
            
            // Show success message
            let alertMessage = 'RCA status updated successfully';
            if (newStatus === 'completed') {
                alertMessage = 'RCA analysis completed successfully';
            }
            
            $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                alertMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>').insertBefore('.card');
            
            // Reload page after 1.5 seconds
            setTimeout(() => {
                location.reload();
            }, 1500);
        },
        error: function(xhr) {
            let errorMessage = 'Error updating RCA status';
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

// Assignment functions
function assignTo(rcaId) {
    $('#assignRcaId').val(rcaId);
    $('#assignForm')[0].reset();
    $('#assignRcaId').val(rcaId); // Reset form clears this, so set again
    
    $('#assignModal').modal('show');
}

function confirmAssignment() {
    let rcaId = $('#assignRcaId').val();
    let assignTo = $('#assignTo').val();
    let targetDate = $('#targetDate').val();
    let priority = $('#priority').val();
    let notes = $('#assignmentNotes').val();
    
    // Validation
    if (!assignTo) {
        alert('Please select someone to assign to');
        return;
    }
    
    if (!targetDate) {
        alert('Please set target completion date');
        return;
    }
    
    // Show loading
    let submitBtn = $('#assignModal .btn-primary');
    let originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Processing...');
    
    $.ajax({
        url: `{{ route('quality-reinspection.index') }}/${rcaId}/assign`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        data: JSON.stringify({
            assigned_to: assignTo,
            target_completion_date: targetDate,
            priority: priority,
            assignment_notes: notes
        }),
        success: function(response) {
            $('#assignModal').modal('hide');
            
            $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                'RCA assigned successfully' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>').insertBefore('.card');
            
            // Reload page after 1.5 seconds
            setTimeout(() => {
                location.reload();
            }, 1500);
        },
        error: function(xhr) {
            let errorMessage = 'Error assigning RCA';
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

// Auto-refresh for updates (every 2 minutes)
setInterval(function() {
    if (!$('#statusModal').hasClass('show') && !$('#assignModal').hasClass('show')) {
        // Only reload if no modals are open
        window.location.reload();
    }
}, 120000);
</script>
@endpush