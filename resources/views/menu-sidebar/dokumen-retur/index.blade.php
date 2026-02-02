@extends('layouts.app')

@section('title', 'Dokumen Retur')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dokumen Retur</h3>
                <p class="text-subtitle text-muted">Kelola dokumen retur untuk customer complaint</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dokumen Retur</li>
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
                        <div class="step active">
                            <div class="step-circle">2</div>
                            <span>Dokumen Retur</span>
                        </div>
                        <div class="step">
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
                                <i class="iconly-boldDocument"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Dokumen</h6>
                            <h6 class="font-extrabold mb-0">{{ $totalDokumen ?? 0 }}</h6>
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
                            <h6 class="text-muted font-semibold">Draft</h6>
                            <h6 class="font-extrabold mb-0">{{ $draftCount ?? 0 }}</h6>
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
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Submitted</h6>
                            <h6 class="font-extrabold mb-0">{{ $submittedCount ?? 0 }}</h6>
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
                            <h6 class="text-muted font-semibold">Approved</h6>
                            <h6 class="font-extrabold mb-0">{{ $approvedCount ?? 0 }}</h6>
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
                        <h5 class="card-title">Dokumen Retur</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('dokumen-retur.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Buat Dokumen Retur
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="draft">Draft</option>
                            <option value="submitted">Submitted</option>
                            <option value="approved">Approved</option>
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
                    <table class="table table-striped" id="dokumenReturTable">
                        <thead>
                            <tr>
                                <th>No. Dokumen</th>
                                <th>No. Complaint</th>
                                <th>Customer</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokumenReturs ?? [] as $dokumen)
                            <tr>
                                <td>
                                    <strong>{{ $dokumen->nomor_dokumen }}</strong>
                                </td>
                                <td>
                                    <a href="{{ route('customer-complaint.show', $dokumen->customerComplaint) }}" 
                                       class="text-decoration-none">
                                        {{ $dokumen->customerComplaint->nomor_complaint }}
                                    </a>
                                </td>
                                <td>{{ $dokumen->customerComplaint->nama_customer }}</td>
                                <td>{{ $dokumen->customerComplaint->produk }}</td>
                                <td>{{ $dokumen->customerComplaint->quantity_ng ?? 0 }} pcs</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $dokumen->status === 'draft' ? 'secondary' : 
                                        ($dokumen->status === 'submitted' ? 'warning' : 
                                        ($dokumen->status === 'approved' ? 'success' : 'danger')) 
                                    }}">
                                        {{ ucfirst($dokumen->status) }}
                                    </span>
                                </td>
                                <td>{{ $dokumen->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dokumen-retur.show', $dokumen) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($dokumen->status === 'draft')
                                        <a href="{{ route('dokumen-retur.edit', $dokumen) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @endif
                                        @if(in_array($dokumen->status, ['draft', 'submitted']) && auth()->user()->role === 'warehouse_staff')
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="updateStatus({{ $dokumen->id }}, 'approved')">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="updateStatus({{ $dokumen->id }}, 'rejected')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada dokumen retur</h5>
                                        <p class="text-muted">Silakan buat dokumen retur pertama Anda</p>
                                        <a href="{{ route('dokumen-retur.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus"></i> Buat Dokumen Retur
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($dokumenReturs) && $dokumenReturs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <p class="text-muted">
                            Menampilkan {{ $dokumenReturs->firstItem() ?? 0 }} sampai {{ $dokumenReturs->lastItem() ?? 0 }} 
                            dari {{ $dokumenReturs->total() }} data
                        </p>
                    </div>
                    <div>
                        {{ $dokumenReturs->links() }}
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
                <h5 class="modal-title">Update Status Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    @csrf
                    <input type="hidden" id="dokumenId">
                    <input type="hidden" id="newStatus">
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" id="statusNotes" rows="3" 
                                  placeholder="Berikan catatan untuk perubahan status..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="confirmStatusUpdate()">Update Status</button>
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

.stats-icon.green {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stats-icon.purple {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stats-icon.red {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
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

/* Button Groups */
.btn-group .btn {
    border-radius: 4px !important;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Empty State */
.table tbody tr:only-child td {
    border: none;
}

/* Filter Section */
.form-control, .form-select {
    border-radius: 6px;
}

/* Card Styling */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#dokumenReturTable').DataTable({
        responsive: true,
        ordering: true,
        searching: false, // We'll use custom search
        paging: false, // We'll use Laravel pagination
        info: false,
        language: {
            emptyTable: "Tidak ada data dokumen retur",
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
            table.column(5).search(status).draw();
        } else {
            table.column(5).search('').draw();
        }
    });

    // Reset filters
    $('#resetFilter').on('click', function() {
        $('#statusFilter').val('');
        $('#dateFilter').val('');
        $('#searchFilter').val('');
        table.search('').columns().search('').draw();
    });

    // Auto-refresh every 30 seconds
    setInterval(function() {
        if (!$('#statusModal').hasClass('show')) {
            location.reload();
        }
    }, 30000);
});

// Status update functions
function updateStatus(dokumenId, newStatus) {
    $('#dokumenId').val(dokumenId);
    $('#newStatus').val(newStatus);
    $('#statusNotes').val('');
    
    let modalTitle = newStatus === 'approved' ? 'Approve Dokumen' : 'Reject Dokumen';
    $('#statusModal .modal-title').text(modalTitle);
    
    $('#statusModal').modal('show');
}

function confirmStatusUpdate() {
    let dokumenId = $('#dokumenId').val();
    let newStatus = $('#newStatus').val();
    let notes = $('#statusNotes').val();
    
    if (newStatus === 'rejected' && !notes.trim()) {
        alert('Catatan wajib diisi untuk penolakan');
        return;
    }
    
    // Show loading
    let submitBtn = $('#statusModal .btn-primary');
    let originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Processing...');
    
    $.ajax({
        url: `{{ route('dokumen-retur.index') }}/${dokumenId}/update-status`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        data: JSON.stringify({
            status: newStatus,
            notes: notes
        }),
        success: function(response) {
            $('#statusModal').modal('hide');
            
            // Show success message
            let alertClass = newStatus === 'approved' ? 'alert-success' : 'alert-warning';
            let alertMessage = newStatus === 'approved' ? 'Dokumen berhasil diapprove' : 'Dokumen berhasil ditolak';
            
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
            let errorMessage = 'Terjadi kesalahan saat update status';
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

// Notification sound for new documents (optional)
function playNotificationSound() {
    let audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEcBzaQ1/LNeSsFJHfH8N2QQAoUXrTp66hVFA==');
    audio.play().catch(e => console.log('Audio play failed:', e));
}
</script>
@endpush