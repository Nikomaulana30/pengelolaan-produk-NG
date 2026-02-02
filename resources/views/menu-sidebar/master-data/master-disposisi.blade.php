@extends('layouts.app')

@section('title', 'Master Disposisi')

@push('styles')
<style>
    /* Modern Card Styling */
    .card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .stats-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .stats-icon.purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .stats-icon.green {
        background: linear-gradient(135deg, #81FBB8 0%, #28C76F 100%);
        color: white;
    }
    
    .stats-icon.blue {
        background: linear-gradient(135deg, #5EFCE8 0%, #736EFE 100%);
        color: white;
    }
    
    .stats-icon.red {
        background: linear-gradient(135deg, #FFB199 0%, #FF0844 100%);
        color: white;
    }
    
    /* Enhanced Table Styling */
    .table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border: none;
        padding: 1rem;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9ff;
        transform: scale(1.01);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.1);
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem;
    }
    
    /* Enhanced Badges */
    .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
        border-radius: 8px;
        font-size: 0.75rem;
        letter-spacing: 0.3px;
    }
    
    .badge.bg-info { background: linear-gradient(135deg, #5EFCE8 0%, #736EFE 100%) !important; }
    .badge.bg-danger { background: linear-gradient(135deg, #FFB199 0%, #FF0844 100%) !important; }
    .badge.bg-warning { background: linear-gradient(135deg, #FFA41B 0%, #FF5733 100%) !important; }
    .badge.bg-secondary { background: linear-gradient(135deg, #868CFF 0%, #4801FF 100%) !important; }
    .badge.bg-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
    .badge.bg-success { background: linear-gradient(135deg, #81FBB8 0%, #28C76F 100%) !important; }
    
    /* Action Buttons */
    .btn-action {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .btn-action.btn-info { background: linear-gradient(135deg, #5EFCE8 0%, #736EFE 100%); color: white; }
    .btn-action.btn-warning { background: linear-gradient(135deg, #FFA41B 0%, #FF5733 100%); color: white; }
    .btn-action.btn-danger { background: linear-gradient(135deg, #FFB199 0%, #FF0844 100%); color: white; }
    
    /* Page Header */
    .page-heading {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 12px;
        color: white;
        margin-bottom: 2rem;
    }
    
    .page-heading h3 {
        color: white;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .page-heading .text-subtitle {
        color: rgba(255,255,255,0.9);
        font-size: 0.95rem;
    }
    
    /* Search & Filter */
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    /* Code Badge */
    code {
        background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #667eea;
        border: 1px solid #e0e0e0;
    }
    
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
        margin-bottom: 0 !important;
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
    
    [data-bs-theme="dark"] .table {
        color: #e4e4e7 !important;
        border-color: #2c3142 !important;
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .table thead {
        background-color: #2c3142 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .table tbody {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .table td,
    [data-bs-theme="dark"] .table th {
        border-color: #2c3142 !important;
        color: #e4e4e7 !important;
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    
    [data-bs-theme="dark"] .badge {
        border-color: #3a3f51 !important;
    }
    
    [data-bs-theme="dark"] code {
        background-color: #2c3142 !important;
        color: #667eea !important;
    }
    
    /* Fix spacing between card header and filter form */
    .card-body > form:first-child {
        margin-top: 0;
    }
    
    .card-body > form:first-child .row:first-child {
        margin-top: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Enhanced Header Section -->
    <div class="page-heading shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="me-3" style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-shuffle" style="font-size: 32px; color: white;"></i>
                    </div>
                    <div>
                        <h3 class="mb-1" style="font-size: 1.75rem; font-weight: 700;">Master Disposisi</h3>
                        <p class="text-subtitle mb-0" style="font-size: 1rem;">Kelola tindakan untuk barang defect & NG dalam workflow quality control</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('master-disposisi.create') }}" class="btn btn-light btn-lg shadow-sm" style="font-weight: 600;">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Disposisi Baru
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">TOTAL DISPOSISI</p>
                            <h2 class="mb-0 fw-bold" style="color: #667eea;">{{ number_format($totalDisposisi) }}</h2>
                            <small class="text-muted">Semua jenis</small>
                        </div>
                        <div class="stats-icon purple">
                            <i class="bi bi-shuffle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">DISPOSISI AKTIF</p>
                            <h2 class="mb-0 fw-bold" style="color: #28C76F;">{{ number_format($disposisiAktif) }}</h2>
                            <small class="text-success">Dapat digunakan</small>
                        </div>
                        <div class="stats-icon green">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">NEED APPROVAL</p>
                            <h2 class="mb-0 fw-bold" style="color: #736EFE;">{{ number_format($needApproval) }}</h2>
                            <small class="text-info">Butuh persetujuan</small>
                        </div>
                        <div class="stats-icon blue">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">TOTAL USAGE</p>
                            <h2 class="mb-0 fw-bold" style="color: #FF0844;">{{ number_format($totalUsage) }}</h2>
                            <small class="text-danger">Digunakan dalam workflow</small>
                        </div>
                        <div class="stats-icon red">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <div>
                            <h5 class="card-title mb-1" style="font-weight: 700; color: #667eea;">
                                <i class="bi bi-table me-2"></i>Daftar Master Disposisi
                            </h5>
                            <p class="text-muted small mb-0">Kelola dan monitor semua disposisi</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            <button class="btn btn-sm btn-outline-success" style="border-radius: 8px;">
                                <i class="bi bi-download me-1"></i>Export Excel
                            </button>
                            <button class="btn btn-sm btn-outline-danger" style="border-radius: 8px;">
                                <i class="bi bi-file-pdf me-1"></i>Export PDF
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Search & Filter Section -->
                    <form method="GET" action="{{ route('master-disposisi.index') }}" id="filterForm">
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small text-muted">PENCARIAN</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari kode atau nama..." value="{{ request('search') }}" style="border-radius: 0 8px 8px 0;">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small text-muted">JENIS TINDAKAN</label>
                                <select name="jenis_tindakan" class="form-select" onchange="document.getElementById('filterForm').submit()" style="border-radius: 8px;">
                                    <option value="">Semua Jenis Tindakan</option>
                                    @foreach ($jenisTindakan as $jenis)
                                        <option value="{{ $jenis }}" {{ request('jenis_tindakan') === $jenis ? 'selected' : '' }}>
                                            @if ($jenis === 'rework')
                                                🔧 Rework
                                            @elseif ($jenis === 'scrap_disposal')
                                                🗑️ Scrap Disposal
                                            @elseif ($jenis === 'return_to_vendor')
                                                📤 Return to Vendor
                                            @elseif ($jenis === 'downgrade')
                                                📊 Downgrade
                                            @else
                                                🔄 Repurpose
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small text-muted">STATUS</label>
                                <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()" style="border-radius: 8px;">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>✅ Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>⭕ Inactive</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small text-muted">AKSI</label>
                                @if (request('search') || request('jenis_tindakan') || request('status'))
                                    <a href="{{ route('master-disposisi.index') }}" class="btn btn-outline-secondary w-100" style="border-radius: 8px;">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset Filter
                                    </a>
                                @else
                                    <button type="submit" class="btn btn-primary w-100" style="visibility: hidden; border-radius: 8px;">
                                        Submit
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 12%;">Kode Disposisi</th>
                                    <th style="width: 18%;">Nama Disposisi</th>
                                    <th style="width: 13%;">Jenis Tindakan</th>
                                    <th style="width: 15%;">Lokasi Tujuan</th>
                                    <th style="width: 10%;">Penyimpanan NG</th>
                                    <th style="width: 10%;">Need Approval</th>
                                    <th style="width: 8%;">Status</th>
                                    <th style="width: 9%;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($disposisis as $index => $disposisi)
                                    <tr>
                                        <td class="text-muted fw-semibold">{{ $disposisis->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2" style="width: 8px; height: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%;"></div>
                                                <code style="font-size: 0.8rem;">{{ $disposisi->kode_disposisi }}</code>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold" style="color: #2c3e50;">{{ $disposisi->nama_disposisi }}</div>
                                            @if($disposisi->deskripsi)
                                                <small class="text-muted">{{ Str::limit($disposisi->deskripsi, 40) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($disposisi->jenis_tindakan === 'rework')
                                                <span class="badge bg-info" style="font-size: 0.8rem;"><i class="bi bi-wrench me-1"></i>Rework</span>
                                            @elseif ($disposisi->jenis_tindakan === 'scrap_disposal')
                                                <span class="badge bg-danger" style="font-size: 0.8rem;"><i class="bi bi-trash me-1"></i>Scrap</span>
                                            @elseif ($disposisi->jenis_tindakan === 'return_to_vendor')
                                                <span class="badge bg-warning" style="font-size: 0.8rem;"><i class="bi bi-box-arrow-left me-1"></i>Return</span>
                                            @elseif ($disposisi->jenis_tindakan === 'downgrade')
                                                <span class="badge bg-secondary" style="font-size: 0.8rem;"><i class="bi bi-arrow-down-circle me-1"></i>Downgrade</span>
                                            @elseif ($disposisi->jenis_tindakan === 'repurpose')
                                                <span class="badge bg-primary" style="font-size: 0.8rem;"><i class="bi bi-arrow-repeat me-1"></i>Repurpose</span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $disposisi->jenis_tindakan }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($disposisi->lokasi_lengkap_tujuan)
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                                    <code>{{ $disposisi->lokasi_lengkap_tujuan }}</code>
                                                </div>
                                            @else
                                                <span class="text-muted small"><i class="bi bi-dash-circle"></i> Tidak ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $countPng = $disposisi->penyimpananNgs()->count();
                                            @endphp
                                            @if ($countPng > 0)
                                                <span class="badge bg-info-subtle text-info" style="font-size: 0.8rem;">
                                                    <i class="bi bi-box-seam me-1"></i>{{ $countPng }} item
                                                </span>
                                            @else
                                                <span class="text-muted small">0 item</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($disposisi->memerlukan_approval)
                                                <span class="badge bg-success" style="font-size: 0.8rem;"><i class="bi bi-check-circle me-1"></i>Ya</span>
                                            @else
                                                <span class="badge bg-secondary" style="font-size: 0.8rem;"><i class="bi bi-x-circle me-1"></i>Tidak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($disposisi->is_active)
                                                <span class="badge bg-success-subtle text-success" style="font-size: 0.8rem; font-weight: 600;">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.8rem; font-weight: 600;">
                                                    <i class="bi bi-x-circle-fill me-1"></i>Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('master-disposisi.show', $disposisi) }}" class="btn-action btn-info" title="Lihat Detail">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                                <a href="{{ route('master-disposisi.edit', $disposisi) }}" class="btn-action btn-warning" title="Edit Disposisi">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('master-disposisi.destroy', $disposisi) }}" method="POST" style="display:inline;" onsubmit="return confirm('⚠️ Apakah Anda yakin ingin menghapus disposisi {{ $disposisi->nama_disposisi }}?\n\nData yang terhapus tidak dapat dikembalikan!')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action btn-danger" title="Hapus Disposisi">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-inbox" style="font-size: 40px; color: #999;"></i>
                                                </div>
                                                <h5 class="text-muted mb-2">Tidak Ada Data</h5>
                                                <p class="text-muted small mb-3">Belum ada disposisi yang tersedia</p>
                                                <a href="{{ route('master-disposisi.create') }}" class="btn btn-primary" style="border-radius: 8px;">
                                                    <i class="bi bi-plus-circle me-2"></i>Tambah Disposisi Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            Menampilkan <strong>{{ $disposisis->firstItem() ?? 0 }}</strong> - <strong>{{ $disposisis->lastItem() ?? 0 }}</strong> dari <strong>{{ $disposisis->total() }}</strong> disposisi
                        </div>
                        <div>
                            {{ $disposisis->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Relationship Info Section -->
        <div class="row mt-4">
            <!-- Disposisi Relationships -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-gradient-purple text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-diagram-3 me-2"></i>Relasi & Integrasi Disposisi
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item bg-light">
                                <strong><i class="bi bi-info-circle me-2"></i>Master Disposisi terhubung dengan:</strong>
                            </div>
                            
                            <!-- Penyimpanan NG -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon red me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Penyimpanan NG</h6>
                                        <p class="mb-0 small text-muted">Barang NG yang menggunakan disposisi ini untuk penanganan</p>
                                    </div>
                                    <span class="badge bg-danger-subtle text-danger">hasMany</span>
                                </div>
                            </div>
                            
                            <!-- Quality Reinspection (Enum) -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon purple me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Quality Reinspection</h6>
                                        <p class="mb-0 small text-muted">Disposisi dipilih dari enum (rework, scrap, return_to_vendor)</p>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning">Enum Reference</span>
                                </div>
                            </div>

                            <!-- Warehouse Verification -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon blue me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Warehouse Verification</h6>
                                        <p class="mb-0 small text-muted">Verifikasi gudang sebelum menentukan penyimpanan NG</p>
                                    </div>
                                    <span class="badge bg-info-subtle text-info">Indirect</span>
                                </div>
                            </div>

                            <!-- Final Quality Check -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon green me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Final Quality Check</h6>
                                        <p class="mb-0 small text-muted">Keputusan final setelah proses rework (via Production Rework)</p>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Indirect</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links & Info -->
            <div class="col-lg-4">
                <!-- Related Modules -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-success text-white">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-link-45deg me-2"></i>Module Terkait
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex align-items-center">
                                <i class="bi bi-box-seam text-red me-3 fs-5"></i>
                                <div>
                                    <div class="fw-semibold">Penyimpanan NG</div>
                                    <small class="text-muted">Direct FK (coming soon)</small>
                                </div>
                            </div>
                            <a href="{{ route('warehouse-verification.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-building text-blue me-3 fs-5"></i>
                                <div>
                                    <div class="fw-semibold">Warehouse Verification</div>
                                    <small class="text-muted">Verifikasi gudang</small>
                                </div>
                            </a>
                            <a href="{{ route('quality-reinspection.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-search text-purple me-3 fs-5"></i>
                                <div>
                                    <div class="fw-semibold">Quality Reinspection</div>
                                    <small class="text-muted">Enum reference</small>
                                </div>
                            </a>
                            <a href="{{ route('final-quality-check.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-shield-check text-green me-3 fs-5"></i>
                                <div>
                                    <div class="fw-semibold">Final Quality Check</div>
                                    <small class="text-muted">Indirect via Production</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi Sistem
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Workflow Integration:</small>
                                <span class="badge bg-primary">4 Modules</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info-subtle border-info mb-3">
                            <i class="bi bi-lightbulb me-2"></i>
                            <small><strong>Direct FK:</strong> Penyimpanan NG via master_disposisi_id</small>
                        </div>
                        
                        <div class="alert alert-warning-subtle border-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <small><strong>Enum Reference:</strong> Quality Reinspection pilih disposisi dari enum, bukan FK</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
