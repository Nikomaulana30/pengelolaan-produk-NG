@extends('layouts.app')

@section('title', 'Master Disposisi')

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
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-shuffle"></i> Master Disposisi</h3>
                    <p class="text-subtitle text-muted">Kelola jenis-jenis disposisi untuk barang cacat/defect</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('master-disposisi.create') }}" class="btn btn-primary float-end">
                        <i class="bi bi-plus-circle"></i> Tambah Disposisi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Daftar Master Disposisi</h5>
                </div>
                <div class="card-body">
                    <!-- Search & Filter Section -->
                    <form method="GET" action="{{ route('master-disposisi.index') }}" id="filterForm">
                        <div class="row mb-3">
                            <div class="col-12 col-md-3">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari kode atau nama..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <select name="jenis_tindakan" class="form-select" onchange="document.getElementById('filterForm').submit()">
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
                                <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                @if (request('search') || request('jenis_tindakan') || request('status'))
                                    <a href="{{ route('master-disposisi.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </a>
                                @else
                                    <button type="submit" class="btn btn-primary w-100" style="visibility: hidden;">
                                        Submit
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Disposisi</th>
                                    <th>Nama Disposisi</th>
                                    <th>Jenis Tindakan</th>
                                    <th>Lokasi Tujuan</th>
                                    <th>Penyimpanan NG</th>
                                    <th>Butuh Approval</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($disposisis as $disposisi)
                                    <tr>
                                        <td><strong>{{ $disposisi->kode_disposisi }}</strong></td>
                                        <td>{{ $disposisi->nama_disposisi }}</td>
                                        <td>
                                            @if ($disposisi->jenis_tindakan === 'rework')
                                                <span class="badge bg-info">🔧 Rework</span>
                                            @elseif ($disposisi->jenis_tindakan === 'scrap_disposal')
                                                <span class="badge bg-danger">🗑️ Scrap Disposal</span>
                                            @elseif ($disposisi->jenis_tindakan === 'return_to_vendor')
                                                <span class="badge bg-warning">📤 Return to Vendor</span>
                                            @elseif ($disposisi->jenis_tindakan === 'downgrade')
                                                <span class="badge bg-secondary">📊 Downgrade</span>
                                            @elseif ($disposisi->jenis_tindakan === 'repurpose')
                                                <span class="badge bg-primary">🔄 Repurpose</span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $disposisi->jenis_tindakan }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($disposisi->lokasi_lengkap_tujuan)
                                                <code style="background-color: #f5f5f5; padding: 4px 8px; border-radius: 3px; font-size: 0.85rem;">
                                                    {{ $disposisi->lokasi_lengkap_tujuan }}
                                                </code>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $countPng = $disposisi->penyimpananNgs()->count();
                                            @endphp
                                            @if ($countPng > 0)
                                                <span class="badge bg-info">{{ $countPng }} item</span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($disposisi->butuh_approval)
                                                <span class="badge bg-success"><i class="bi bi-check"></i> Ya</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="bi bi-x"></i> Tidak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($disposisi->is_active)
                                                <span class="badge bg-success">✓ Active</span>
                                            @else
                                                <span class="badge bg-secondary">✗ Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('master-disposisi.show', $disposisi) }}" class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('master-disposisi.edit', $disposisi) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('master-disposisi.toggle-status', $disposisi) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-{{ $disposisi->is_active ? 'success' : 'secondary' }}" title="{{ $disposisi->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="bi bi-{{ $disposisi->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('master-disposisi.destroy', $disposisi) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Tidak ada data Master Disposisi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $disposisis->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
