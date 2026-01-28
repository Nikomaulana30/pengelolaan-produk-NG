@extends('layouts.app')

@section('title', 'Detail Master Approval Authority')

@push('styles')
<style>
    /* Dark mode comprehensive styling */
    [data-bs-theme="dark"] .page-content {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .section {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .row {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .col,
    [data-bs-theme="dark"] [class*="col-"] {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .card {
        background-color: #1e1e2d !important;
        border-color: #2c3142 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .card-header {
        background-color: #2c3142 !important;
        border-color: #3a3f51 !important;
        color: #e4e4e7 !important;
        padding-bottom: 1rem !important;
    }
    
    [data-bs-theme="dark"] .card-body {
        background-color: #1e1e2d !important;
        color: #e4e4e7 !important;
        padding-top: 1.5rem !important;
    }
    
    [data-bs-theme="dark"] .card-title,
    [data-bs-theme="dark"] .card-text {
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] h1,
    [data-bs-theme="dark"] h2,
    [data-bs-theme="dark"] h3,
    [data-bs-theme="dark"] h4,
    [data-bs-theme="dark"] h5,
    [data-bs-theme="dark"] h6 {
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] p {
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .text-muted {
        color: #a1a1a1 !important;
    }
    
    [data-bs-theme="dark"] small {
        color: #a1a1a1 !important;
    }
    
    [data-bs-theme="dark"] .field-box {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .field-box label,
    [data-bs-theme="dark"] .form-label,
    [data-bs-theme="dark"] label {
        color: #a1a1a1 !important;
    }
    
    [data-bs-theme="dark"] .field-box p {
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .badge {
        border-color: #3a3f51 !important;
    }
    
    [data-bs-theme="dark"] .list-group-item {
        background-color: #1e1e2d !important;
        border-color: #2c3142 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .bg-light {
        background-color: #2c3142 !important;
    }
    
    [data-bs-theme="dark"] .shadow-sm,
    [data-bs-theme="dark"] .shadow-lg {
        box-shadow: none !important;
    }
    
    /* Fix nested card spacing */
    .card-body > .row:first-child {
        margin-top: 0;
    }
    
    .card-body .card:first-child {
        margin-top: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-eye"></i> Detail Master Approval Authority</h5>
                    <a href="{{ route('master-approval.index') }}" class="btn btn-light btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Left Column: Information -->
                        <div class="col-md-8">
                            <!-- Section: User & Department -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="bi bi-person-badge"></i> Informasi User & Departemen</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="text-muted small">User:</label>
                                            <p class="fw-bold">{{ $masterApproval->user->name ?? 'N/A' }}</p>
                                            <small class="text-muted">{{ $masterApproval->user->email ?? 'N/A' }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small">Departemen:</label>
                                            <p>
                                                @if ($masterApproval->departemen === 'warehouse')
                                                    <span class="badge bg-primary">Warehouse</span>
                                                @elseif ($masterApproval->departemen === 'quality')
                                                    <span class="badge bg-info">Quality</span>
                                                @elseif ($masterApproval->departemen === 'ppic')
                                                    <span class="badge bg-success">PPIC</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $masterApproval->departemen }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Role & Authority -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="bi bi-shield-check"></i> Role & Otorisasi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="text-muted small">Role Level:</label>
                                            <p>
                                                @if ($masterApproval->role_level === 'supervisor')
                                                    <span class="badge bg-secondary">Supervisor</span>
                                                @elseif ($masterApproval->role_level === 'manager')
                                                    <span class="badge bg-info">Manager</span>
                                                @elseif ($masterApproval->role_level === 'director')
                                                    <span class="badge bg-primary">Director</span>
                                                @else
                                                    <span class="badge bg-light text-dark">{{ $masterApproval->role_level }}</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small">Jenis Approval:</label>
                                            <p>
                                                @if ($masterApproval->jenis_approval === 'penerimaan_barang')
                                                    <span class="badge bg-warning">Penerimaan Barang</span>
                                                @elseif ($masterApproval->jenis_approval === 'penyimpanan_ng')
                                                    <span class="badge bg-danger">Penyimpanan NG</span>
                                                @elseif ($masterApproval->jenis_approval === 'scrap_disposal')
                                                    <span class="badge bg-dark">Scrap Disposal</span>
                                                @elseif ($masterApproval->jenis_approval === 'retur_vendor')
                                                    <span class="badge bg-info">Retur Vendor</span>
                                                @elseif ($masterApproval->jenis_approval === 'rework')
                                                    <span class="badge bg-secondary">Rework</span>
                                                @elseif ($masterApproval->jenis_approval === 'rca_analysis')
                                                    <span class="badge bg-primary">RCA Analysis</span>
                                                @else
                                                    <span class="badge bg-light text-dark">{{ $masterApproval->jenis_approval }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Approval Limit -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="bi bi-currency-dollar"></i> Batas Approval</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="text-muted small">Approval Limit:</label>
                                            <p class="fw-bold">
                                                @if ($masterApproval->approval_limit == 0)
                                                    <span class="badge bg-success">Unlimited</span>
                                                @else
                                                    Rp {{ number_format($masterApproval->approval_limit, 0, ',', '.') }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small">Dapat Approve Sendiri:</label>
                                            <p>
                                                @if ($masterApproval->can_approve_self)
                                                    <span class="badge bg-success">✓ Ya</span>
                                                @else
                                                    <span class="badge bg-secondary">✗ Tidak</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Description -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Deskripsi</h6>
                                </div>
                                <div class="card-body">
                                    <p>{{ $masterApproval->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Status & Actions -->
                        <div class="col-md-4">
                            <!-- Status Card -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="bi bi-status"></i> Status</h6>
                                </div>
                                <div class="card-body text-center">
                                    @if ($masterApproval->is_active)
                                        <div class="mb-3">
                                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                        </div>
                                        <p class="mb-1"><strong>Aktif</strong></p>
                                        <small class="text-muted">Authority ini sedang aktif</small>
                                    @else
                                        <div class="mb-3">
                                            <i class="bi bi-x-circle text-danger" style="font-size: 3rem;"></i>
                                        </div>
                                        <p class="mb-1"><strong>Tidak Aktif</strong></p>
                                        <small class="text-muted">Authority ini tidak aktif</small>
                                    @endif
                                </div>
                            </div>

                            <!-- Timestamps -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Catatan Waktu</h6>
                                </div>
                                <div class="card-body small">
                                    <div class="mb-2">
                                        <label class="text-muted">Dibuat:</label>
                                        <p class="mb-0">{{ $masterApproval->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <label class="text-muted">Diperbarui:</label>
                                        <p class="mb-0">{{ $masterApproval->updated_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('master-approval.edit', $masterApproval) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square me-2"></i> Edit
                                </a>
                                <form action="{{ route('master-approval.destroy', $masterApproval) }}" method="POST" onsubmit="return confirm('Hapus Authority ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        <i class="bi bi-trash me-2"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
