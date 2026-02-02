@extends('layouts.app')

@section('title', 'Detail Master Vendor')

@push('styles')
<style>
    /* Dark mode card styling */
    [data-bs-theme="dark"] .card {
        background-color: #1e1e2d !important;
        border-color: #2c3142 !important;
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
    
    [data-bs-theme="dark"] .card-title {
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .form-control-plaintext {
        color: #e4e4e7 !important;
        background-color: transparent !important;
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
    
    /* Specific for white boxes/sections */
    [data-bs-theme="dark"] .page-content {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .row {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .col-12,
    [data-bs-theme="dark"] .col-lg-8,
    [data-bs-theme="dark"] .col-lg-4,
    [data-bs-theme="dark"] .col-md-4 {
        background-color: transparent !important;
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
    }
    
    [data-bs-theme="dark"] .table-light {
        background-color: #2c3142 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    
    [data-bs-theme="dark"] .nav-tabs {
        border-color: #3a3f51 !important;
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .nav-tabs .nav-link {
        color: #a1a1a1 !important;
        border-color: transparent !important;
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .nav-tabs .nav-link:hover {
        color: #e4e4e7 !important;
        border-color: transparent transparent #3a3f51 !important;
        background-color: rgba(255, 255, 255, 0.03) !important;
    }
    
    [data-bs-theme="dark"] .nav-tabs .nav-link.active {
        background-color: #1e1e2d !important;
        color: #667eea !important;
        border-color: #3a3f51 #3a3f51 #1e1e2d !important;
    }
    
    [data-bs-theme="dark"] .tab-content {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .tab-pane {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] code {
        background-color: #2c3142 !important;
        color: #667eea !important;
    }
    
    [data-bs-theme="dark"] .border-bottom {
        border-color: #3a3f51 !important;
    }
    
    [data-bs-theme="dark"] .text-muted {
        color: #a1a1a1 !important;
    }
    
    [data-bs-theme="dark"] small {
        color: #a1a1a1 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-shop"></i> Detail Master Vendor</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap vendor/supplier</p>
                </div>
                <div class="col-12 col-md-4">
                    <div class="btn-group float-end" role="group">
                        <a href="{{ route('master-vendor.edit', $masterVendor) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('master-vendor.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
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

        <div class="row">
            <!-- Main Information -->
            <div class="col-12 col-lg-8">
                <div class="card mb-4">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi Umum
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Kode Vendor -->
                        <div class="field-box mb-3">
                            <label class="form-label fw-bold text-muted">Kode Vendor</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary">{{ $masterVendor->kode_vendor }}</span>
                            </p>
                        </div>

                        <!-- Nama Vendor -->
                        <div class="field-box mb-3">
                            <label class="form-label fw-bold text-muted">Nama Vendor</label>
                            <p class="form-control-plaintext fs-5 fw-bold">{{ $masterVendor->nama_vendor }}</p>
                        </div>

                        <!-- Alamat -->
                        <div class="field-box mb-3">
                            <label class="form-label fw-bold text-muted">Alamat</label>
                            <p class="form-control-plaintext">{{ $masterVendor->alamat_vendor ?? '-' }}</p>
                        </div>

                        <!-- Kota, Provinsi, Kode Pos -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="field-box mb-3">
                                    <label class="form-label fw-bold text-muted">Kota</label>
                                    <p class="form-control-plaintext">{{ $masterVendor->kota ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="field-box mb-3">
                                    <label class="form-label fw-bold text-muted">Provinsi</label>
                                    <p class="form-control-plaintext">{{ $masterVendor->provinsi ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="field-box mb-3">
                                    <label class="form-label fw-bold text-muted">Kode Pos</label>
                                    <p class="form-control-plaintext">{{ $masterVendor->kode_pos ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card mb-4">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-telephone me-2"></i>Informasi Kontak
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Telepon -->
                        <div class="field-box mb-3">
                            <label class="form-label fw-bold text-muted">Telepon</label>
                            <p class="form-control-plaintext">
                                @if ($masterVendor->telepon)
                                    <i class="bi bi-telephone me-2"></i>{{ $masterVendor->telepon }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>

                        <!-- Email -->
                        <div class="field-box mb-3">
                            <label class="form-label fw-bold text-muted">Email</label>
                            <p class="form-control-plaintext">
                                @if ($masterVendor->email)
                                    <i class="bi bi-envelope me-2"></i>
                                    <a href="mailto:{{ $masterVendor->email }}" class="text-primary">{{ $masterVendor->email }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>

                        <!-- Person In Charge -->
                        <div class="field-box mb-3">
                            <label class="form-label fw-bold text-muted">Person In Charge</label>
                            <p class="form-control-plaintext">{{ $masterVendor->person_in_charge ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Kebijakan Retur -->
                <div class="card mb-4">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-arrow-left-right me-2"></i>Kebijakan Retur
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="field-box mb-3">
                            <label class="form-label fw-bold text-muted">Tipe Kebijakan Retur</label>
                            <p class="form-control-plaintext">
                                @if ($masterVendor->kebijakan_retur === 'retur_fisik')
                                    <span class="badge bg-primary">Retur Fisik</span>
                                    <small class="d-block text-muted mt-2">Barang dikembalikan secara fisik ke vendor</small>
                                @elseif ($masterVendor->kebijakan_retur === 'debit_note')
                                    <span class="badge bg-warning">Debit Note</span>
                                    <small class="d-block text-muted mt-2">Pengembalian melalui debit note tanpa retur fisik</small>
                                @elseif ($masterVendor->kebijakan_retur === 'keduanya')
                                    <span class="badge bg-success">Retur Fisik & Debit Note</span>
                                    <small class="d-block text-muted mt-2">Mendukung kedua tipe pengembalian</small>
                                @else
                                    <span class="badge bg-secondary">{{ $masterVendor->kebijakan_retur }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                @if ($masterVendor->deskripsi)
                    <div class="card mb-4">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-file-text me-2"></i>Deskripsi
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="form-control-plaintext">{{ $masterVendor->deskripsi }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Status & Action -->
            <div class="col-12 col-lg-4">
                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-circle-fill me-2"></i>Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="form-control-plaintext">
                            @if ($masterVendor->is_active)
                                <span class="badge bg-success" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                </span>
                                <small class="d-block text-success mt-2">Vendor dalam status aktif</small>
                            @else
                                <span class="badge bg-secondary" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                    <i class="bi bi-x-circle me-1"></i>Inactive
                                </span>
                                <small class="d-block text-muted mt-2">Vendor tidak aktif</small>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Tab Section: Info & Retur Barang -->
                <div class="card mt-4">
                    <div class="card-header border-bottom">
                        <ul class="nav nav-tabs mb-0" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#info-tab" role="tab">
                                    <i class="bi bi-info-circle me-2"></i>Informasi Sistem
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#retur-tab" role="tab">
                                    <i class="bi bi-arrow-left-square me-2"></i>Retur Barang 
                                    <span class="badge bg-primary ms-2">{{ $masterVendor->returBarangs()->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#produk-tab" role="tab">
                                    <i class="bi bi-box-open me-2"></i>Master Produk 
                                    <span class="badge bg-success ms-2">{{ $masterVendor->produks()->count() }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Tab 1: Informasi Sistem -->
                            <div id="info-tab" class="tab-pane fade show active">
                                <!-- Metadata -->
                                <div class="card mb-4">
                                    <div class="card-header border-bottom">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-clock-history me-2"></i>Informasi Sistem
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="field-box mb-2">
                                            <label class="form-label fw-bold text-muted">Dibuat Pada</label>
                                            <p class="form-control-plaintext small">
                                                {{ $masterVendor->created_at?->format('d M Y H:i') ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="field-box mb-2">
                                            <label class="form-label fw-bold text-muted">Diupdate Pada</label>
                                            <p class="form-control-plaintext small">
                                                {{ $masterVendor->updated_at?->format('d M Y H:i') ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="field-box">
                                            <label class="form-label fw-bold text-muted">ID Vendor</label>
                                            <p class="form-control-plaintext small">
                                                <code>{{ $masterVendor->id }}</code>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Retur Barang -->
                            <div id="retur-tab" class="tab-pane fade">
                                @if ($masterVendor->returBarangs()->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>No Retur</th>
                                                    <th>Produk</th>
                                                    <th>Qty</th>
                                                    <th>Alasan</th>
                                                    <th>Tanggal</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($masterVendor->returBarangs()->get() as $retur)
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                                                        </td>
                                                        <td>
                                                            <strong>{{ $retur->no_retur }}</strong>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted">{{ $retur->produk->nama_barang }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">{{ $retur->jumlah_retur }} unit</span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $alasanMap = [
                                                                    'defect' => 'Defect',
                                                                    'qty_tidak_sesuai' => 'Qty Tidak Sesuai',
                                                                    'kualitas_buruk' => 'Kualitas Buruk',
                                                                    'expired' => 'Expired',
                                                                    'rusak_pengiriman' => 'Rusak Pengiriman',
                                                                    'lainnya' => 'Lainnya'
                                                                ];
                                                            @endphp
                                                            <small class="text-muted">{{ $alasanMap[$retur->alasan_retur] ?? $retur->alasan_retur }}</small>
                                                        </td>
                                                        <td>
                                                            {{ $retur->tanggal_retur->format('d M Y') }}
                                                        </td>
                                                        <td>
                                                            @if ($retur->status_approval === 'pending')
                                                                <span class="badge bg-warning text-dark">Pending</span>
                                                            @elseif ($retur->status_approval === 'approved')
                                                                <span class="badge bg-success">Approved</span>
                                                            @else
                                                                <span class="badge bg-danger">Rejected</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <a href="{{ route('retur-barang.show', $retur) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                                <a href="{{ route('retur-barang.edit', $retur) }}" class="btn btn-sm btn-warning" title="Edit">
                                                                    <i class="bi bi-pencil"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Tidak ada data</strong> - Belum ada retur barang dari vendor ini
                                    </div>
                                @endif
                            </div>

                            <!-- Tab 3: Master Produk -->
                            <div id="produk-tab" class="tab-pane fade">
                                @if ($masterVendor->produks()->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Kode Produk</th>
                                                    <th>Nama Produk</th>
                                                    <th>Kategori</th>
                                                    <th>Unit</th>
                                                    <th>Harga</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($masterVendor->produks()->get() as $produk)
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                                                        </td>
                                                        <td>
                                                            <strong>{{ $produk->kode_produk }}</strong>
                                                        </td>
                                                        <td>
                                                            {{ $produk->nama_produk }}
                                                        </td>
                                                        <td>
                                                            @switch($produk->kategori)
                                                                @case('raw_material')
                                                                    <span class="badge bg-warning">Raw Material</span>
                                                                @break
                                                                @case('wip')
                                                                    <span class="badge bg-info">WIP</span>
                                                                @break
                                                                @case('finished_goods')
                                                                    <span class="badge bg-success">Finished Goods</span>
                                                                @break
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            <small>{{ $produk->unit }}</small>
                                                        </td>
                                                        <td>
                                                            {{ $produk->harga ? 'Rp ' . number_format($produk->harga, 0, ',', '.') : '-' }}
                                                        </td>
                                                        <td>
                                                            @if ($produk->is_active)
                                                                <span class="badge bg-success">✓ Aktif</span>
                                                            @else
                                                                <span class="badge bg-danger">✗ Inaktif</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <a href="{{ route('master-produk.show', $produk) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                                <a href="{{ route('master-produk.edit', $produk) }}" class="btn btn-sm btn-warning" title="Edit">
                                                                    <i class="bi bi-pencil"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Tidak ada produk</strong> - Vendor ini belum memiliki produk terdaftar. 
                                        <a href="{{ route('master-produk.create') }}" class="alert-link">Tambah produk sekarang</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Integration Section -->
                <div class="card mt-4">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-diagram-3 me-2"></i>Integrasi
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-arrow-left-square me-2"></i>Retur Barang
                                    </small>
                                    {{-- <a href="{{ route('retur-barang.index', ['vendor_id' => $masterVendor->id]) }}" class="btn btn-sm btn-outline-primary"> --}}
                                    <a href="#" class="btn btn-sm btn-outline-secondary disabled">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-box-arrow-in-down me-2"></i>Penerimaan Barang
                                    </small>
                                    {{-- <a href="{{ route('penerimaan-barang.index', ['vendor_id' => $masterVendor->id]) }}" class="btn btn-sm btn-outline-primary"> --}}
                                    <a href="#" class="btn btn-sm btn-outline-secondary disabled">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Button -->
        <div class="row mt-4">
            <div class="col-12">
                <form action="{{ route('master-vendor.destroy', $masterVendor) }}" method="POST" style="display:inline;" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger delete-btn" data-name="{{ $masterVendor->nama_vendor }}">
                        <i class="bi bi-trash me-2"></i>Hapus Vendor
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-btn')) {
                e.preventDefault();
                e.stopPropagation();

                const button = e.target.closest('.delete-btn');
                const form = button.closest('.delete-form');
                const name = button.getAttribute('data-name');

                Swal.fire({
                    title: 'Hapus Vendor?',
                    html: `Apakah Anda yakin ingin menghapus vendor <strong>${name}</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-trash"></i> Ya, Hapus',
                    cancelButtonText: 'Batal',
                    buttonsStyling: true,
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        setTimeout(() => {
                            form.submit();
                        }, 300);
                    }
                });

                return false;
            }
        });
    });
</script>

@push('styles')
<style>
    .text-subtitle {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .field-box {
        padding: 0.75rem;
        background-color: #f8f9fa;
        border-radius: 0.375rem;
    }

    .form-control-plaintext {
        padding-top: 0.375rem;
        padding-bottom: 0.375rem;
    }

    .card {
        border: 1px solid #e9ecef;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .list-group-item {
        padding: 0.75rem 1rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.6rem;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s;
    }

    .nav-tabs .nav-link:hover {
        color: #495057;
        border-bottom-color: #dee2e6;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom-color: #0d6efd;
    }

    .tab-content {
        padding-top: 1rem;
    }
</style>
@endpush
@endsection
