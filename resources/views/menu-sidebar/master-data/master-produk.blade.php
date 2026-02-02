@extends('layouts.app')

@section('title', 'Master Produk')

@section('content')
<div class="container-fluid">
    <!-- Enhanced Header Section -->
    <div class="page-heading mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="stats-icon purple me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                        <i class="bi bi-box2 fs-4"></i>
                    </div>
                    <div>
                        <h3 class="mb-1">Master Produk</h3>
                        <p class="text-subtitle text-muted mb-0">Kelola data produk & tracking relasi workflow</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('master-produk.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru
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
    <div class="row mb-4">
        <div class="col-md-3 col-6">
            <div class="card">
                <div class="card-body px-4 py-4">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="bi bi-box2"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                            <h6 class="text-muted font-semibold">Total Produk</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($totalProduk) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card">
                <div class="card-body px-4 py-4">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start">
                            <div class="stats-icon green mb-2">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                            <h6 class="text-muted font-semibold">Produk Aktif</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($produkAktif) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card">
                <div class="card-body px-4 py-4">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="bi bi-exclamation-circle"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                            <h6 class="text-muted font-semibold">Complaint Aktif</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($complaintAktif) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card">
                <div class="card-body px-4 py-4">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start">
                            <div class="stats-icon red mb-2">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                            <h6 class="text-muted font-semibold">Dalam Rework</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($dalamRework) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Section -->
    <section class="section">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-table me-2 text-primary"></i>Daftar Master Produk
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <button class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download me-1"></i>Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr class="table-primary">
                                <th><i class="bi bi-upc me-1"></i>Kode Produk</th>
                                <th><i class="bi bi-box me-1"></i>Nama Produk</th>
                                <th><i class="bi bi-shop me-1"></i>Vendor</th>
                                <th><i class="bi bi-tag me-1"></i>Kategori</th>
                                <th><i class="bi bi-rulers me-1"></i>Unit</th>
                                <th><i class="bi bi-cash me-1"></i>Harga</th>
                                <th><i class="bi bi-toggle-on me-1"></i>Status</th>
                                <th class="text-center"><i class="bi bi-gear me-1"></i>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produks as $produk)
                                <tr>
                                    <td>
                                        <span class="badge bg-dark font-monospace">{{ $produk->kode_produk }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $produk->nama_produk }}</div>
                                        @if ($produk->spesifikasi)
                                            <small class="text-muted d-block">
                                                <i class="bi bi-info-circle me-1"></i>{{ Str::limit($produk->spesifikasi, 60) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($produk->vendor)
                                            <a href="{{ route('master-vendor.show', $produk->vendor) }}" class="text-decoration-none">
                                                <span class="badge bg-info-subtle text-info border border-info">
                                                    <i class="bi bi-shop me-1"></i>{{ $produk->vendor->nama_vendor }}
                                                </span>
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Tidak ada vendor</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($produk->kategori)
                                            @case('raw_material')
                                                <span class="badge bg-warning-subtle text-warning border border-warning">
                                                    <i class="bi bi-box-seam me-1"></i>Raw Material
                                                </span>
                                            @break
                                            @case('wip')
                                                <span class="badge bg-info-subtle text-info border border-info">
                                                    <i class="bi bi-hourglass-split me-1"></i>WIP
                                                </span>
                                            @break
                                            @case('finished_goods')
                                                <span class="badge bg-success-subtle text-success border border-success">
                                                    <i class="bi bi-check2-square me-1"></i>Finished Goods
                                                </span>
                                            @break
                                            @default
                                                <span class="text-muted">-</span>
                                        @endswitch
                                    </td>
                                    <td><span class="badge bg-light text-dark">{{ $produk->unit }}</span></td>
                                    <td>
                                        @if($produk->harga)
                                            <span class="text-success fw-semibold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($produk->is_active)
                                            <span class="badge bg-success-subtle text-success border border-success">
                                                <i class="bi bi-check-circle-fill me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary">
                                                <i class="bi bi-x-circle-fill me-1"></i>Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('master-produk.show', $produk) }}" class="btn btn-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('master-produk.edit', $produk) }}" class="btn btn-warning" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('master-produk.destroy', $produk) }}" method="POST" style="display:inline;" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger delete-btn" title="Hapus" data-name="{{ $produk->nama_produk }}">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <h6>Tidak ada data Master Produk</h6>
                                            <p class="small">Silakan tambahkan produk baru untuk memulai</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Enhanced Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Menampilkan {{ $produks->firstItem() ?? 0 }} - {{ $produks->lastItem() ?? 0 }} dari {{ $produks->total() }} produk
                    </div>
                    <div>
                        {{ $produks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- Integration Info Section -->
        <div class="row">
            <!-- Workflow Relationship -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-diagram-3 me-2"></i>Workflow & Relasi Produk
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item bg-light">
                                <strong><i class="bi bi-info-circle me-2"></i>Setiap produk terintegrasi dengan modul:</strong>
                            </div>
                            
                            <!-- Customer Complaint -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon red me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Customer Complaint</h6>
                                        <p class="mb-0 small text-muted">Pencatatan keluhan dari customer untuk produk tertentu</p>
                                    </div>
                                    <span class="badge bg-danger-subtle text-danger">Workflow Start</span>
                                </div>
                            </div>

                            <!-- Dokumen Retur -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon blue me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-file-earmark-arrow-down"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Dokumen Retur</h6>
                                        <p class="mb-0 small text-muted">Dokumentasi pengembalian barang dari customer</p>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary">Step 2</span>
                                </div>
                            </div>

                            <!-- Quality Reinspection -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon purple me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Quality Reinspection</h6>
                                        <p class="mb-0 small text-muted">Inspeksi ulang oleh team Quality terhadap produk retur</p>
                                    </div>
                                    <span class="badge bg-info-subtle text-info">Step 3</span>
                                </div>
                            </div>

                            <!-- Production Rework -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon orange me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Production Rework</h6>
                                        <p class="mb-0 small text-muted">Proses perbaikan produk defect oleh team Production</p>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning">Step 4</span>
                                </div>
                            </div>

                            <!-- Final Quality Check -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon green me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-check2-circle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Final Quality Check</h6>
                                        <p class="mb-0 small text-muted">Pemeriksaan akhir hasil rework sebelum dikirim kembali</p>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Step 5</span>
                                </div>
                            </div>

                            <!-- Return Shipment -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon purple me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Return Shipment</h6>
                                        <p class="mb-0 small text-muted">Pengiriman kembali produk yang sudah di-rework ke customer</p>
                                    </div>
                                    <span class="badge bg-dark-subtle text-dark">Workflow End</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-4">
                <!-- Master Data Links -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-link-45deg me-2"></i>Master Data Terkait
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('master-vendor.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-shop text-info me-3 fs-5"></i>
                                <div>
                                    <div class="fw-semibold">Master Vendor</div>
                                    <small class="text-muted">Supplier produk</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi Sistem
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Relasi Database:</small>
                                <span class="badge bg-success">6 Modules</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <div class="alert alert-success-subtle border-success mb-0">
                            <i class="bi bi-check-circle me-2"></i>
                            <small>Semua relasi produk sudah terintegrasi dengan workflow return</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- SweetAlert2 untuk Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Tunggu DOM siap sebelum attach event listener
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Script delete SweetAlert2 loaded');
        
        // Gunakan event delegation untuk handle dinamis
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-btn')) {
                e.preventDefault();
                e.stopPropagation();
                
                const button = e.target.closest('.delete-btn');
                const form = button.closest('.delete-form');
                const name = button.getAttribute('data-name');
                
                console.log('Delete button clicked for:', name);
                
                Swal.fire({
                    title: 'Hapus Produk?',
                    html: `Apakah Anda yakin ingin menghapus produk <strong>${name}</strong>?<br><small class="text-muted">Data akan dipindahkan ke trash dan dapat di-restore jika diperlukan.</small>`,
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
                        console.log('User confirmed delete');
                        form.submit();
                    }
                });
            }
        });
    });
</script>
@endsection

