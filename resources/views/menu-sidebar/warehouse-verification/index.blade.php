@extends('layouts.app')

@section('title', 'Warehouse Verification')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Warehouse Verification</h3>
                <p class="text-subtitle text-muted">Verifikasi penerimaan dan kondisi barang NG</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Warehouse Verification</li>
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
                        <div class="step active">
                            <div class="step-circle">3</div>
                            <span>Warehouse Verification</span>
                        </div>
                        <div class="step">
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
                                <i class="iconly-boldBox"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Verifikasi</h6>
                            <h6 class="font-extrabold mb-0">{{ $totalVerifikasi ?? 0 }}</h6>
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
                            <h6 class="text-muted font-semibold">Verified</h6>
                            <h6 class="font-extrabold mb-0">{{ $verifiedCount ?? 0 }}</h6>
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
                                <i class="iconly-boldCloseSquare"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Rejected</h6>
                            <h6 class="font-extrabold mb-0">{{ $rejectedCount ?? 0 }}</h6>
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
                        <h5 class="card-title">Warehouse Verification</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('warehouse-verification.create') }}" class="btn btn-primary me-2">
                            <i class="bi bi-plus-circle"></i> Buat Verifikasi
                        </a>
                        <button class="btn btn-success" onclick="bulkAction('verify')" id="bulkVerifyBtn" style="display: none;">
                            <i class="bi bi-check-circle"></i> Verify Selected
                        </button>
                        <button class="btn btn-danger" onclick="bulkAction('reject')" id="bulkRejectBtn" style="display: none;">
                            <i class="bi bi-x-circle"></i> Reject Selected
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="verified">Verified</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="dateFilter" placeholder="Filter Tanggal">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchFilter" placeholder="Cari nomor dokumen, customer...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary" id="resetFilter">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-striped" id="verificationTable">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>No. Verifikasi</th>
                                <th>No. Dokumen</th>
                                <th>Customer</th>
                                <th>Produk</th>
                                <th>Received Qty</th>
                                <th>Kondisi</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouseVerifications ?? [] as $verification)
                            <tr>
                                <td>
                                    @if($verification->status === 'pending')
                                    <div class="form-check">
                                        <input class="form-check-input verification-checkbox" 
                                               type="checkbox" value="{{ $verification->id }}">
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $verification->nomor_verifikasi }}</strong>
                                </td>
                                <td>
                                    <a href="{{ route('dokumen-retur.show', $verification->dokumenRetur) }}" 
                                       class="text-decoration-none">
                                        {{ $verification->dokumenRetur->nomor_dokumen }}
                                    </a>
                                </td>
                                <td>{{ $verification->dokumenRetur->customerComplaint->nama_customer }}</td>
                                <td>{{ $verification->dokumenRetur->customerComplaint->produk }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ number_format($verification->quantity_diterima, 0) }} pcs</span>
                                        <small class="text-muted">
                                            Expected: {{ number_format($verification->dokumenRetur->customerComplaint->quantity_ng, 0) }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    @if($verification->kondisi_fisik_barang)
                                        <span class="badge bg-info">
                                            {{ \Illuminate\Support\Str::limit($verification->kondisi_fisik_barang, 20) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $verification->status === 'pending' ? 'warning' : 
                                        ($verification->status === 'verified' ? 'success' : 'danger') 
                                    }}">
                                        {{ ucfirst($verification->status) }}
                                    </span>
                                </td>
                                <td>{{ $verification->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('warehouse-verification.show', $verification) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($verification->status === 'pending')
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="showVerificationModal({{ $verification->id }}, 'verified')">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="showVerificationModal({{ $verification->id }}, 'rejected')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                        @endif
                                        @if($verification->status === 'verified')
                                        <a href="{{ route('quality-reinspection.create', ['verification' => $verification->id]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-arrow-right"></i> To QC
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada verifikasi warehouse</h5>
                                        <p class="text-muted">Data akan muncul ketika ada dokumen retur yang diapprove</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($warehouseVerifications) && $warehouseVerifications instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <p class="text-muted">
                            Menampilkan {{ $warehouseVerifications->firstItem() ?? 0 }} sampai {{ $warehouseVerifications->lastItem() ?? 0 }} 
                            dari {{ $warehouseVerifications->total() }} data
                        </p>
                    </div>
                    <div>
                        {{ $warehouseVerifications->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Modal for Verification -->
<div class="modal fade" id="verificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warehouse Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="verificationForm">
                    @csrf
                    <input type="hidden" id="verificationId">
                    <input type="hidden" id="actionType">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Received Quantity</label>
                                <input type="number" class="form-control" id="receivedQuantity" 
                                       step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Unit</label>
                                <input type="text" class="form-control" id="unit" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kondisi Barang</label>
                        <select class="form-select" id="kondisiBarang" required>
                            <option value="">Pilih kondisi barang</option>
                            <option value="sesuai">Sesuai dengan dokumen</option>
                            <option value="tidak_sesuai">Tidak sesuai dengan dokumen</option>
                            <option value="rusak">Rusak/Cacat</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Storage Location</label>
                        <input type="text" class="form-control" id="storageLocation" 
                               placeholder="Lokasi penyimpanan barang NG">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="verificationNotes" rows="3" 
                                  placeholder="Catatan verifikasi..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload Photos</label>
                        <input type="file" class="form-control" id="verificationPhotos" 
                               multiple accept="image/*">
                        <small class="text-muted">Upload foto kondisi barang (multiple files)</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="submitVerification()">Submit Verification</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Bulk Actions -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <span id="bulkActionText"></span> <strong id="selectedCount"></strong> items?</p>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="bulkNotes" rows="3" 
                              placeholder="Add notes for this bulk action..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmBulkAction()">Confirm</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Progress Steps Styling */
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

/* Table Styling */
.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
    font-size: 14px;
    color: #495057;
    padding: 12px;
}

.table td {
    padding: 12px;
    vertical-align: middle;
}

/* Checkbox styling */
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

/* Button Groups */
.btn-group .btn {
    border-radius: 4px !important;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#verificationTable').DataTable({
        responsive: true,
        ordering: true,
        searching: false,
        paging: false,
        info: false,
        language: {
            emptyTable: "Tidak ada data verifikasi warehouse",
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
            table.column(7).search(status).draw();
        } else {
            table.column(7).search('').draw();
        }
    });

    // Reset filters
    $('#resetFilter').on('click', function() {
        $('#statusFilter').val('');
        $('#dateFilter').val('');
        $('#searchFilter').val('');
        table.search('').columns().search('').draw();
    });

    // Select all functionality
    $('#selectAll').on('change', function() {
        $('.verification-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkActionButtons();
    });

    // Individual checkbox change
    $(document).on('change', '.verification-checkbox', function() {
        updateBulkActionButtons();
        
        // Update select all checkbox
        let totalCheckboxes = $('.verification-checkbox').length;
        let checkedCheckboxes = $('.verification-checkbox:checked').length;
        $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes);
    });

    function updateBulkActionButtons() {
        let checkedCount = $('.verification-checkbox:checked').length;
        if (checkedCount > 0) {
            $('#bulkVerifyBtn, #bulkRejectBtn').show();
        } else {
            $('#bulkVerifyBtn, #bulkRejectBtn').hide();
        }
    }
});

// Verification modal functions
function showVerificationModal(verificationId, action) {
    $('#verificationId').val(verificationId);
    $('#actionType').val(action);
    
    let modalTitle = action === 'verified' ? 'Verify Warehouse Item' : 'Reject Warehouse Item';
    $('#verificationModal .modal-title').text(modalTitle);
    
    // Reset form
    $('#verificationForm')[0].reset();
    $('#verificationId').val(verificationId);
    $('#actionType').val(action);
    
    // Load data for this verification (you might want to make an AJAX call here)
    // For now, we'll just show the modal
    $('#verificationModal').modal('show');
}

function submitVerification() {
    let verificationId = $('#verificationId').val();
    let action = $('#actionType').val();
    let formData = new FormData();
    
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('_method', 'PUT');
    formData.append('status', action);
    formData.append('received_quantity', $('#receivedQuantity').val());
    formData.append('kondisi_barang', $('#kondisiBarang').val());
    formData.append('storage_location', $('#storageLocation').val());
    formData.append('notes', $('#verificationNotes').val());
    
    // Add photos
    let photos = $('#verificationPhotos')[0].files;
    for (let i = 0; i < photos.length; i++) {
        formData.append('photos[]', photos[i]);
    }
    
    // Validation
    if (!$('#receivedQuantity').val()) {
        alert('Received quantity is required');
        return;
    }
    
    if (!$('#kondisiBarang').val()) {
        alert('Kondisi barang is required');
        return;
    }
    
    // Show loading
    let submitBtn = $('#verificationModal .btn-primary');
    let originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Processing...');
    
    $.ajax({
        url: `{{ route('warehouse-verification.index') }}/${verificationId}/update-status`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#verificationModal').modal('hide');
            
            // Show success message
            let alertClass = action === 'verified' ? 'alert-success' : 'alert-warning';
            let alertMessage = action === 'verified' ? 'Verification completed successfully' : 'Item rejected successfully';
            
            $('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                alertMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>').insertBefore('.card');
            
            // Reload page after 1.5 seconds
            setTimeout(() => {
                location.reload();
            }, 1500);
        },
        error: function(xhr) {
            let errorMessage = 'Error updating verification status';
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

// Bulk action functions
let bulkActionType = '';

function bulkAction(action) {
    let selectedIds = $('.verification-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        alert('Please select at least one item');
        return;
    }
    
    bulkActionType = action;
    $('#bulkActionText').text(action === 'verify' ? 'verify' : 'reject');
    $('#selectedCount').text(selectedIds.length);
    $('#bulkNotes').val('');
    
    $('#bulkActionModal').modal('show');
}

function confirmBulkAction() {
    let selectedIds = $('.verification-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    let notes = $('#bulkNotes').val();
    
    // Show loading
    let confirmBtn = $('#bulkActionModal .btn-primary');
    let originalText = confirmBtn.text();
    confirmBtn.prop('disabled', true).text('Processing...');
    
    $.ajax({
        url: `{{ route('warehouse-verification.index') }}/bulk-action`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        data: JSON.stringify({
            ids: selectedIds,
            action: bulkActionType,
            notes: notes
        }),
        success: function(response) {
            $('#bulkActionModal').modal('hide');
            
            // Show success message
            let alertClass = bulkActionType === 'verify' ? 'alert-success' : 'alert-warning';
            let alertMessage = `${selectedIds.length} items ${bulkActionType === 'verify' ? 'verified' : 'rejected'} successfully`;
            
            $('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                alertMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>').insertBefore('.card');
            
            // Reload page after 1.5 seconds
            setTimeout(() => {
                location.reload();
            }, 1500);
        },
        error: function(xhr) {
            let errorMessage = 'Error processing bulk action';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            $('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                errorMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>').insertBefore('.card');
        },
        complete: function() {
            confirmBtn.prop('disabled', false).text(originalText);
        }
    });
}
</script>
@endpush