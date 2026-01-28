@extends('layouts.app')

@section('title', 'Detail Master Disposisi')

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
    
    [data-bs-theme="dark"] .field-box {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .field-box label,
    [data-bs-theme="dark"] .form-label {
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
    
    [data-bs-theme="dark"] .badge {
        border-color: #3a3f51 !important;
    }
    
    [data-bs-theme="dark"] code {
        background-color: #2c3142 !important;
        color: #667eea !important;
    }
    
    [data-bs-theme="dark"] .list-group-item {
        background-color: #1e1e2d !important;
        border-color: #2c3142 !important;
        color: #e4e4e7 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-shuffle"></i> Detail Master Disposisi</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap jenis disposisi dan penanganannya</p>
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('master-disposisi.edit', $masterDisposisi) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('master-disposisi.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <!-- Status Alert -->
            <div class="row mb-3">
                <div class="col-12">
                    @if ($masterDisposisi->is_active)
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Status Aktif</strong> - Disposisi ini tersedia untuk penugasan pada barang defect
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @else
                        <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <strong>Status Non-Aktif</strong> - Disposisi ini tidak lagi digunakan untuk penugasan baru
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- Left Column: Main Info -->
                <div class="col-12 col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">📌 Identifikasi Disposisi</h5>
                        </div>
                        <div class="card-body">
                            <div class="field-box mb-3">
                                <label class="field-label">Kode Disposisi</label>
                                <p class="field-value"><strong>{{ $masterDisposisi->kode_disposisi }}</strong></p>
                            </div>

                            <div class="field-box mb-3">
                                <label class="field-label">Nama Disposisi</label>
                                <p class="field-value">{{ $masterDisposisi->nama_disposisi }}</p>
                            </div>

                            <div class="field-box">
                                <label class="field-label">Deskripsi</label>
                                <p class="field-value">{{ $masterDisposisi->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">⚙️ Jenis Tindakan</h5>
                        </div>
                        <div class="card-body">
                            <div class="field-box">
                                <label class="field-label">Tindakan yang Dilakukan</label>
                                <p class="field-value">
                                    @if ($masterDisposisi->jenis_tindakan === 'rework')
                                        <span class="badge bg-info" style="font-size: 12px; padding: 6px 10px;">
                                            🔧 Rework (Produksi Ulang)
                                        </span>
                                    @elseif ($masterDisposisi->jenis_tindakan === 'scrap_disposal')
                                        <span class="badge bg-danger" style="font-size: 12px; padding: 6px 10px;">
                                            🗑️ Scrap Disposal (Buang/Musnahkan)
                                        </span>
                                    @elseif ($masterDisposisi->jenis_tindakan === 'return_to_vendor')
                                        <span class="badge bg-warning" style="font-size: 12px; padding: 6px 10px;">
                                            📤 Return to Vendor (Kembalikan ke Vendor)
                                        </span>
                                    @elseif ($masterDisposisi->jenis_tindakan === 'downgrade')
                                        <span class="badge bg-secondary" style="font-size: 12px; padding: 6px 10px;">
                                            📊 Downgrade (Turunkan Grade)
                                        </span>
                                    @elseif ($masterDisposisi->jenis_tindakan === 'repurpose')
                                        <span class="badge bg-primary" style="font-size: 12px; padding: 6px 10px;">
                                            🔄 Repurpose (Ubah Fungsi)
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">📋 Detail Proses</h5>
                        </div>
                        <div class="card-body">
                            <div class="field-box mb-3">
                                <label class="field-label">Proses Tindakan</label>
                                <p class="field-value">{{ $masterDisposisi->proses_tindakan ?? 'Tidak ada informasi proses' }}</p>
                            </div>

                            <div class="field-box">
                                <label class="field-label">Syarat & Ketentuan</label>
                                <p class="field-value">{{ $masterDisposisi->syarat_ketentuan ?? 'Tidak ada syarat khusus' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- NEW: Card Barang yang Didisposisi (Direct Link) -->
                    @if ($masterDisposisi->penyimpanan_ng_id && $masterDisposisi->penyimpananNg)
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">📦 Barang yang Didisposisi</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-box mb-3">
                                        <label class="field-label">Nomor Storage</label>
                                        <p class="field-value">
                                            <a href="{{ route('penyimpanan-ng.show', $masterDisposisi->penyimpananNg) }}" class="text-decoration-none">
                                                <strong>{{ $masterDisposisi->penyimpananNg->nomor_storage }}</strong>
                                                <i class="bi bi-box-arrow-up-right ms-1"></i>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field-box mb-3">
                                        <label class="field-label">Nama Barang</label>
                                        <p class="field-value">{{ $masterDisposisi->penyimpananNg->nama_barang }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-box mb-3">
                                        <label class="field-label">Lokasi Saat Ini</label>
                                        <p class="field-value">
                                            <code style="background-color: #f5f5f5; padding: 6px 10px; border-radius: 4px;">
                                                {{ $masterDisposisi->penyimpananNg->lokasi_lengkap }}
                                            </code>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field-box mb-3">
                                        <label class="field-label">Quantity</label>
                                        <p class="field-value">
                                            <strong>{{ $masterDisposisi->penyimpananNg->qty_awal }}</strong> unit
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="field-box">
                                        <label class="field-label">Status Barang</label>
                                        <p class="field-value">
                                            @switch($masterDisposisi->penyimpananNg->status_barang)
                                                @case('disimpan')
                                                    <span class="badge bg-primary">📦 Disimpan</span>
                                                    @break
                                                @case('dalam_perbaikan')
                                                    <span class="badge bg-warning">🔧 Dalam Perbaikan</span>
                                                    @break
                                                @case('menunggu_approval')
                                                    <span class="badge bg-info">⏳ Menunggu Approval</span>
                                                    @break
                                                @case('siap_dipindahkan')
                                                    <span class="badge bg-success">✓ Siap Dipindahkan</span>
                                                    @break
                                                @case('dipindahkan')
                                                    <span class="badge bg-secondary">↗ Sudah Dipindahkan</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $masterDisposisi->penyimpananNg->status_barang }}</span>
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- NEW: Card Lokasi Tujuan Relokasi -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">📍 Lokasi Tujuan Relokasi</h5>
                        </div>
                        <div class="card-body">
                            @if ($masterDisposisi->zone_tujuan)
                                <div class="field-box mb-3">
                                    <label class="field-label">Zone Tujuan</label>
                                    <p class="field-value">
                                        <span class="badge bg-info">
                                            @if ($masterDisposisi->zone_tujuan === 'zona_a')
                                                Zona A
                                            @elseif ($masterDisposisi->zone_tujuan === 'zona_b')
                                                Zona B
                                            @elseif ($masterDisposisi->zone_tujuan === 'zona_c')
                                                Zona C
                                            @elseif ($masterDisposisi->zone_tujuan === 'zona_d')
                                                Zona D
                                            @elseif ($masterDisposisi->zone_tujuan === 'zona_e')
                                                Zona E
                                            @elseif ($masterDisposisi->zone_tujuan === 'zona_return')
                                                📤 Zona Return
                                            @elseif ($masterDisposisi->zone_tujuan === 'zona_scrap')
                                                🗑️ Zona Scrap
                                            @elseif ($masterDisposisi->zone_tujuan === 'zona_rework')
                                                🔧 Zona Rework
                                            @else
                                                {{ $masterDisposisi->zone_tujuan }}
                                            @endif
                                        </span>
                                    </p>
                                </div>

                                <div class="field-box mb-3">
                                    <label class="field-label">Rack Tujuan</label>
                                    <p class="field-value">{{ $masterDisposisi->rack_tujuan ?? '-' }}</p>
                                </div>

                                <div class="field-box mb-3">
                                    <label class="field-label">Bin Tujuan</label>
                                    <p class="field-value">{{ $masterDisposisi->bin_tujuan ?? '-' }}</p>
                                </div>

                                <div class="field-box">
                                    <label class="field-label">Lokasi Lengkap Tujuan</label>
                                    <p class="field-value">
                                        <code style="background-color: #f5f5f5; padding: 6px 10px; border-radius: 4px;">
                                            {{ $masterDisposisi->lokasi_lengkap_tujuan ?? '-' }}
                                        </code>
                                    </p>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Belum ada lokasi tujuan yang dikonfigurasi untuk disposisi ini.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- NEW: Card Penyimpanan NG yang Terhubung -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">📦 Penyimpanan NG Terhubung <span class="badge bg-secondary ms-2">{{ $masterDisposisi->penyimpananNgs()->count() }}</span></h5>
                        </div>
                        <div class="card-body">
                            @if ($masterDisposisi->penyimpananNgs()->exists())
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No. Storage</th>
                                                <th>Lokasi Asal</th>
                                                <th>Lokasi Tujuan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($masterDisposisi->penyimpananNgs()->limit(10)->get() as $png)
                                                <tr>
                                                    <td><strong>{{ $png->nomor_storage }}</strong></td>
                                                    <td>
                                                        <small>{{ $png->zone }}/{{ $png->rack }}/{{ $png->bin }}</small>
                                                    </td>
                                                    <td>
                                                        @if ($png->zone_tujuan)
                                                            <small><code>{{ $png->lokasi_lengkap_tujuan }}</code></small>
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($png->status_barang === 'disimpan')
                                                            <span class="badge bg-primary">📦 Disimpan</span>
                                                        @elseif ($png->status_barang === 'siap_dipindahkan')
                                                            <span class="badge bg-success">✓ Siap</span>
                                                        @elseif ($png->status_barang === 'dipindahkan')
                                                            <span class="badge bg-secondary">↗ Pindah</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ $png->status_barang }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('penyimpanan-ng.show', $png) }}" class="btn btn-sm btn-outline-primary" title="Lihat detail">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if ($masterDisposisi->penyimpananNgs()->count() > 10)
                                    <div class="mt-3">
                                        <a href="{{ route('penyimpanan-ng.index', ['master_disposisi_id' => $masterDisposisi->id]) }}" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="bi bi-list"></i> Lihat Semua ({{ $masterDisposisi->penyimpananNgs()->count() }} item)
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Belum ada penyimpanan NG yang terhubung dengan disposisi ini.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Additional Info -->
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">ℹ️ Informasi Tambahan</h5>
                        </div>
                        <div class="card-body">
                            <div class="field-box mb-3">
                                <label class="field-label">Memerlukan Approval</label>
                                <p class="field-value">
                                    @if ($masterDisposisi->memerlukan_approval)
                                        <span class="badge bg-success"><i class="bi bi-check"></i> Ya, Perlu Approval</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bi bi-x"></i> Tidak Perlu Approval</span>
                                    @endif
                                </p>
                            </div>

                            <div class="field-box mb-3">
                                <label class="field-label">Status</label>
                                <p class="field-value">
                                    @if ($masterDisposisi->is_active)
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aktif</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Tidak Aktif</span>
                                    @endif
                                </p>
                            </div>

                            <div class="field-box mb-3">
                                <label class="field-label">Total Penyimpanan NG</label>
                                <p class="field-value">
                                    <span class="badge bg-info">{{ $masterDisposisi->penyimpananNgs()->count() }}</span> item
                                </p>
                            </div>

                            <div class="field-box mb-3">
                                <label class="field-label">Dibuat pada</label>
                                <p class="field-value">{{ $masterDisposisi->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            <div class="field-box">
                                <label class="field-label">Diperbarui pada</label>
                                <p class="field-value">{{ $masterDisposisi->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">🔧 Aksi</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('master-disposisi.destroy', $masterDisposisi) }}" method="POST" onsubmit="return confirm('Hapus data ini? Tindakan ini tidak dapat dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-trash"></i> Hapus Disposisi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    .field-box {
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .field-box:last-child {
        border-bottom: none;
    }

    .field-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #666;
        margin-bottom: 5px;
        display: block;
    }

    .field-value {
        margin-bottom: 0;
        color: #333;
    }
</style>
@endsection
