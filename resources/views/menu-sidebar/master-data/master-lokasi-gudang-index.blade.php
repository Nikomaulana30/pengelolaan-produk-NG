@extends('layouts.app')

@section('title', 'Master Lokasi Gudang')

@push('styles')
<style>
    /* Dark mode comprehensive styling */
    [data-bs-theme="dark"] .page-content {
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
    }
    
    [data-bs-theme="dark"] .card-body {
        background-color: #1e1e2d !important;
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
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Master Lokasi Gudang</h3>
                <p class="text-subtitle text-muted">Manajemen lokasi penyimpanan barang NG</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Master Lokasi Gudang</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Daftar Lokasi Gudang</h4>
                <a href="{{ route('master-lokasi-gudang.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Lokasi
                </a>
            </div>
            <div class="card-body">
                <!-- Filter & Search -->
                <form method="GET" action="{{ route('master-lokasi-gudang.index') }}" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari kode, nama, atau lokasi..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="zone" class="form-select">
                                <option value="">Semua Zone</option>
                                <option value="zona_a" {{ request('zone') == 'zona_a' ? 'selected' : '' }}>Zona A</option>
                                <option value="zona_b" {{ request('zone') == 'zona_b' ? 'selected' : '' }}>Zona B</option>
                                <option value="zona_c" {{ request('zone') == 'zona_c' ? 'selected' : '' }}>Zona C</option>
                                <option value="zona_d" {{ request('zone') == 'zona_d' ? 'selected' : '' }}>Zona D</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Lokasi</th>
                                <th>Nama Lokasi</th>
                                <th>Zone</th>
                                <th>Lokasi Lengkap</th>
                                <th>Penerimaan</th>
                                <th>Items Stored</th>
                                <th>Capacity</th>
                                <th style="min-width: 180px;">Utilization</th>
                                <th>Status</th>
                                <th style="min-width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($masterLokasiGudangs as $lokasi)
                            <tr>
                                <td><span class="badge bg-primary">{{ $lokasi->kode_lokasi }}</span></td>
                                <td>{{ $lokasi->nama_lokasi }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $lokasi->zone)) }}
                                    </span>
                                </td>
                                <td><code>{{ $lokasi->lokasi_lengkap }}</code></td>
                                
                                <!-- Penerimaan Barang Count -->
                                <td>
                                    <a href="{{ route('master-lokasi-gudang.show', $lokasi->id) }}#penerimaan" 
                                       class="badge bg-success" 
                                       title="Lihat penerimaan barang">
                                        <i class="bi bi-download"></i> {{ $lokasi->penerimaan_barangs_count }}
                                    </a>
                                </td>

                                <!-- Items Stored -->
                                <td>
                                    <a href="{{ route('master-lokasi-gudang.show', $lokasi->id) }}" 
                                       class="badge bg-primary" 
                                       title="Lihat detail barang">
                                        <i class="bi bi-box-seam"></i> {{ $lokasi->penyimpanan_ngs_count }} items
                                    </a>
                                </td>

                                <!-- Capacity -->
                                <td>
                                    @php
                                        $currentQty = $lokasi->penyimpananNgs->sum('qty_sisa');
                                    @endphp
                                    <small class="text-muted">
                                        {{ number_format($currentQty) }} / {{ number_format($lokasi->kapasitas_max) }}
                                    </small>
                                </td>

                                <!-- Utilization Progress Bar -->
                                <td>
                                    @php
                                        $util = $lokasi->kapasitas_max > 0 
                                            ? round(($currentQty / $lokasi->kapasitas_max) * 100, 1)
                                            : 0;
                                        $colorClass = $util > 90 ? 'danger' : ($util > 75 ? 'warning' : ($util > 50 ? 'info' : 'success'));
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $colorClass }}" 
                                             role="progressbar"
                                             style="width: {{ min($util, 100) }}%"
                                             aria-valuenow="{{ $util }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <small>{{ $util }}%</small>
                                        </div>
                                    </div>
                                    @if($util > 90)
                                        <small class="text-danger"><i class="bi bi-exclamation-triangle"></i> Hampir Penuh</small>
                                    @endif
                                </td>

                                <td>
                                    @if($lokasi->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('master-lokasi-gudang.show', $lokasi->id) }}" 
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('master-lokasi-gudang.edit', $lokasi->id) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('master-lokasi-gudang.destroy', $lokasi->id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="Hapus"
                                                    @if($lokasi->penyimpanan_ngs_count > 0) 
                                                        disabled 
                                                        title="Tidak bisa dihapus, masih ada {{ $lokasi->penyimpanan_ngs_count }} barang tersimpan" 
                                                    @endif>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd;"></i>
                                    <p class="text-muted mt-2">Tidak ada data lokasi gudang</p>
                                    <a href="{{ route('master-lokasi-gudang.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle"></i> Tambah Lokasi Baru
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $masterLokasiGudangs->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
