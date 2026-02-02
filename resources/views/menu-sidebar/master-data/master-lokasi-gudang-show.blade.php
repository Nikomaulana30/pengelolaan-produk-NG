@extends('layouts.app')

@section('title', 'Detail Master Lokasi Gudang')

@push('styles')
<style>
    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    .stats-icon.purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .stats-icon.blue { background: linear-gradient(135deg, #667eea 0%, #007bff 100%); }
    .stats-icon.green { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
    .stats-icon.orange { background: linear-gradient(135deg, #fd7e14 0%, #ff6b6b 100%); }
    .stats-icon.red { background: linear-gradient(135deg, #dc3545 0%, #c92a2a 100%); }
    
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
    }
    
    [data-bs-theme="dark"] .card-body {
        background-color: #1e1e2d !important;
        color: #e4e4e7 !important;
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
    
    [data-bs-theme="dark"] .form-label {
        color: #a1a1a1 !important;
    }
    
    [data-bs-theme="dark"] .form-control-plaintext {
        color: #e4e4e7 !important;
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .field-box {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .field-box label {
        color: #a1a1a1 !important;
    }
    
    [data-bs-theme="dark"] .field-box p {
        color: #e4e4e7 !important;
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
    
    [data-bs-theme="dark"] .border-bottom {
        border-color: #3a3f51 !important;
    }
    
    [data-bs-theme="dark"] code {
        background-color: #2c3142 !important;
        color: #667eea !important;
    }
    
    [data-bs-theme="dark"] .badge {
        border-color: #3a3f51 !important;
    }
    
    [data-bs-theme="dark"] .list-group-item {
        background-color: #1e1e2d !important;
        border-color: #2c3142 !important;
        color: #e4e4e7 !important;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Master Lokasi Gudang</h3>
                <p class="text-subtitle text-muted">{{ $masterLokasiGudang->nama_lokasi }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('master-lokasi-gudang.index') }}">Master Lokasi Gudang</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <section class="section">
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon purple mb-2">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Items</h6>
                                <h6 class="font-extrabold mb-0">{{ $stats['total_items'] }}</h6>
                                <small class="text-muted">Barang tersimpan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2">
                                    <i class="bi bi-boxes"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Quantity</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($stats['total_quantity']) }}</h6>
                                <small class="text-muted">Unit total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon green mb-2">
                                    <i class="bi bi-clipboard-check"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Kapasitas Max</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($masterLokasiGudang->kapasitas_max) }}</h6>
                                <small class="text-muted">Unit maksimal</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon 
                                    @if($stats['utilization_percentage'] > 90) red 
                                    @elseif($stats['utilization_percentage'] > 75) orange
                                    @else green @endif mb-2">
                                    <i class="bi bi-pie-chart"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Utilization</h6>
                                <h6 class="font-extrabold mb-0">{{ $stats['utilization_percentage'] }}%</h6>
                                <small class="text-muted">Kapasitas terpakai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Info Card -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">📍 Informasi Lokasi</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">Kode Lokasi</th>
                                <td>: <span class="badge bg-primary">{{ $masterLokasiGudang->kode_lokasi }}</span></td>
                            </tr>
                            <tr>
                                <th>Nama Lokasi</th>
                                <td>: {{ $masterLokasiGudang->nama_lokasi }}</td>
                            </tr>
                            <tr>
                                <th>Zone</th>
                                <td>: <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $masterLokasiGudang->zone)) }}</span></td>
                            </tr>
                            <tr>
                                <th>Rack</th>
                                <td>: {{ $masterLokasiGudang->rack }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">Bin</th>
                                <td>: {{ $masterLokasiGudang->bin }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi Lengkap</th>
                                <td>: <code>{{ $masterLokasiGudang->lokasi_lengkap }}</code></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>: 
                                    @if($masterLokasiGudang->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>: {{ $masterLokasiGudang->deskripsi ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Capacity Bar -->
                <div class="mt-3">
                    <h6 class="mb-2">
                        <i class="bi bi-bar-chart-fill"></i> Kapasitas Penyimpanan
                    </h6>
                    <div class="progress progress-lg mb-2" style="height: 30px;">
                        <div class="progress-bar 
                            @if($stats['utilization_percentage'] > 90) bg-danger 
                            @elseif($stats['utilization_percentage'] > 75) bg-warning 
                            @elseif($stats['utilization_percentage'] > 50) bg-info
                            @else bg-success @endif" 
                             role="progressbar" 
                             style="width: {{ min($stats['utilization_percentage'], 100) }}%" 
                             aria-valuenow="{{ $stats['utilization_percentage'] }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <strong>{{ $stats['utilization_percentage'] }}%</strong>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="bi bi-box-seam"></i> {{ number_format($stats['total_quantity']) }} unit tersimpan
                        </small>
                        <small class="text-muted">
                            Tersisa: {{ number_format($masterLokasiGudang->kapasitas_max - $stats['total_quantity']) }} unit
                        </small>
                    </div>
                    
                    @if($stats['utilization_percentage'] > 90)
                        <div class="alert alert-danger mt-2 mb-0">
                            <i class="bi bi-exclamation-triangle-fill"></i> 
                            <strong>Peringatan!</strong> Lokasi hampir penuh ({{ $stats['utilization_percentage'] }}%). Pertimbangkan relokasi atau gunakan lokasi lain.
                        </div>
                    @elseif($stats['utilization_percentage'] > 75)
                        <div class="alert alert-warning mt-2 mb-0">
                            <i class="bi bi-exclamation-circle"></i> 
                            Lokasi {{ $stats['utilization_percentage'] }}% terisi. Monitoring diperlukan.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        @if($stats['status_breakdown']->count() > 0)
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">📊 Status Breakdown Barang</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($stats['status_breakdown'] as $status => $count)
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                        <div class="card border-start border-4 
                            @if($status == 'disimpan') border-success
                            @elseif($status == 'dalam_perbaikan') border-info
                            @elseif($status == 'dalam_disposisi') border-warning
                            @elseif($status == 'selesai') border-secondary
                            @else border-primary
                            @endif">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">{{ ucfirst(str_replace('_', ' ', $status)) }}</h6>
                                <h3 class="mb-0">{{ $count }}</h3>
                                <small class="text-muted">items</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Top Defects -->
        @if($stats['defect_summary']->count() > 0)
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">🔍 Top 5 Defect di Lokasi Ini</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($stats['defect_summary'] as $defectName => $count)
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <strong class="text-white">{{ $count }}</strong>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $defectName }}</h6>
                                <small class="text-muted">{{ $count }} occurrence(s)</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- PENERIMAAN BARANG SECTION - DISABLED (Model doesn't exist) -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <h4 class="card-title mb-0 text-muted">📥 Penerimaan Barang di Lokasi Ini</h4>
                <span class="badge bg-secondary">Coming Soon</span>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Fitur Penerimaan Barang belum tersedia.</strong><br>
                    <small>Model PenerimaanBarang belum dibuat. Hubungi developer untuk mengaktifkan fitur ini.</small>
                </div>
            </div>
        </div>

        <!-- Items Stored (Penyimpanan NG) -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">📦 Barang yang Tersimpan di Lokasi Ini</h4>
                <div>
                    <span class="badge bg-primary me-2">{{ $stats['total_items'] }} items</span>
                    <span class="badge bg-info">{{ number_format($stats['total_quantity']) }} total unit</span>
                </div>
            </div>
            <div class="card-body">
                @if($masterLokasiGudang->penyimpananNgs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No. Storage</th>
                                    <th>Produk</th>
                                    <th>Defect</th>
                                    <th>Qty</th>
                                    <th>Status</th>
                                    <th>Disposisi</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_additions'] as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('penyimpanan-ng.show', $item->id) }}" class="text-primary fw-bold">
                                            {{ $item->nomor_storage }}
                                        </a>
                                    </td>
                                    <td>
                                        <strong>{{ $item->produk->nama_produk ?? $item->nama_barang }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $item->produk->kode_produk ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @if($item->defects->count() > 0)
                                            @foreach($item->defects->take(2) as $defect)
                                                <span class="badge bg-warning mb-1">{{ $defect->nama_defect }}</span>
                                            @endforeach
                                            @if($item->defects->count() > 2)
                                                <span class="badge bg-secondary">+{{ $item->defects->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ number_format($item->qty_sisa) }}</strong> {{ $item->satuan }}
                                        @if($item->qty_sisa != $item->qty_awal)
                                            <br><small class="text-muted">(Awal: {{ number_format($item->qty_awal) }})</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($item->status_barang) {
                                                'disimpan' => 'bg-success',
                                                'dalam_perbaikan' => 'bg-info',
                                                'dalam_disposisi' => 'bg-warning',
                                                'selesai' => 'bg-secondary',
                                                default => 'bg-primary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status_barang)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->disposisi)
                                            <span class="badge bg-info">{{ $item->disposisi->nama_disposisi }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->tanggal_masuk_ng ? $item->tanggal_masuk_ng->format('d M Y') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('penyimpanan-ng.show', $item->id) }}" 
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($masterLokasiGudang->penyimpananNgs->count() > 10)
                    <div class="text-center mt-3">
                        <p class="text-muted">Menampilkan 10 dari {{ $masterLokasiGudang->penyimpananNgs->count() }} barang</p>
                        <a href="{{ route('penyimpanan-ng.index') }}?lokasi={{ $masterLokasiGudang->id }}" 
                           class="btn btn-primary">
                            <i class="bi bi-list"></i> Lihat Semua Barang di Lokasi Ini
                        </a>
                    </div>
                    @endif
                @else
                    <div class="alert alert-light-info">
                        <i class="bi bi-info-circle"></i> Tidak ada barang yang tersimpan di lokasi ini.
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('master-lokasi-gudang.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <div>
                        <a href="{{ route('master-lokasi-gudang.edit', $masterLokasiGudang->id) }}" 
                           class="btn btn-warning me-2">
                            <i class="bi bi-pencil"></i> Edit Lokasi
                        </a>
                        <form action="{{ route('master-lokasi-gudang.destroy', $masterLokasiGudang->id) }}" 
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini? Semua relasi dengan penyimpanan NG akan dihapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    @if($masterLokasiGudang->penyimpananNgs->count() > 0) 
                                        disabled 
                                        title="Tidak bisa dihapus, masih ada {{ $masterLokasiGudang->penyimpananNgs->count() }} barang tersimpan" 
                                    @endif>
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
