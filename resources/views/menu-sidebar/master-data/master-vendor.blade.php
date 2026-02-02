@extends('layouts.app')

@section('title', 'Master Vendor')

@section('content')
<div class="container-fluid">
    <!-- Enhanced Header Section -->
    <div class="page-heading mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="stats-icon blue me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                        <i class="bi bi-shop fs-4"></i>
                    </div>
                    <div>
                        <h3 class="mb-1">Master Vendor</h3>
                        <p class="text-subtitle text-muted mb-0">Kelola supplier & tracking produk vendor</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('master-vendor.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Vendor Baru
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

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
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
                            <div class="stats-icon blue mb-2">
                                <i class="bi bi-shop"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                            <h6 class="text-muted font-semibold">Total Vendor</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($totalVendor) }}</h6>
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
                            <h6 class="text-muted font-semibold">Vendor Aktif</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($vendorAktif) }}</h6>
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
                            <div class="stats-icon purple mb-2">
                                <i class="bi bi-box2"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                            <h6 class="text-muted font-semibold">Total Produk</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($totalProdukVendor) }}</h6>
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
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                            <h6 class="text-muted font-semibold">Quality Issues</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($totalQualityIssues) }}</h6>
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
                        <i class="bi bi-table me-2 text-primary"></i>Daftar Master Vendor
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
                            <tr class="table-info">
                                <th><i class="bi bi-upc me-1"></i>Kode Vendor</th>
                                <th><i class="bi bi-building me-1"></i>Nama Vendor</th>
                                <th><i class="bi bi-geo-alt me-1"></i>Lokasi</th>
                                <th><i class="bi bi-telephone me-1"></i>Kontak</th>
                                <th><i class="bi bi-box2 me-1"></i>Produk</th>
                                <th><i class="bi bi-arrow-left-square me-1"></i>Retur</th>
                                <th><i class="bi bi-toggle-on me-1"></i>Status</th>
                                <th class="text-center"><i class="bi bi-gear me-1"></i>Aksi</th>
                            </tr>
                        </thead>
                                    <i class="bi bi-shop"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-body pb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-2">Vendor Aktif</h6>
                                    <h4 class="mb-0 text-success">{{ \App\Models\MasterVendor::where('is_active', true)->count() }}</h4>
                                </div>
                                <div class="text-success" style="font-size: 2rem;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-body pb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-2">Vendor Inactive</h6>
                                    <h4 class="mb-0 text-secondary">{{ \App\Models\MasterVendor::where('is_active', false)->count() }}</h4>
                                </div>
                                <div class="text-secondary" style="font-size: 2rem;">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Links -->
        <section class="section mb-3">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card border-left-primary">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-muted mb-2">📊 Vendor Scorecard</h6>
                                    <p class="mb-0 small text-secondary">Evaluasi performa vendor dan performance metrics</p>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('vendor-scorecard.index') }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-arrow-right"></i> Lihat Scorecard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Daftar Vendor -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Daftar Master Vendor</h5>
                </div>
                <div class="card-body" style="padding-top: 24px;">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 15%">Kode Vendor</th>
                                    <th style="width: 25%">Nama Vendor</th>
                                    <th style="width: 15%">Kota</th>
                                    <th style="width: 12%">Kebijakan Retur</th>
                                    <th style="width: 8%">Retur</th>
                                    <th style="width: 8%">Status</th>
                                    <th style="width: 12%">Aksi</th>
                                </tr>
                            </thead>
                        <tbody>
                            @forelse ($vendors as $vendor)
                                <tr>
                                    <td>
                                        <span class="badge bg-dark font-monospace">{{ $vendor->kode_vendor }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $vendor->nama_vendor }}</div>
                                        @if ($vendor->person_in_charge)
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>{{ $vendor->person_in_charge }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($vendor->kota)
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $vendor->kota }}
                                            </span>
                                            @if($vendor->provinsi)
                                                <div class="small text-muted mt-1">{{ $vendor->provinsi }}</div>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($vendor->telepon)
                                            <div class="small">
                                                <i class="bi bi-telephone-fill me-1 text-success"></i>{{ $vendor->telepon }}
                                            </div>
                                        @endif
                                        @if ($vendor->email)
                                            <div class="small text-muted">
                                                <i class="bi bi-envelope me-1"></i>{{ $vendor->email }}
                                            </div>
                                        @endif
                                        @if (!$vendor->telepon && !$vendor->email)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('master-produk.index') }}?vendor={{ $vendor->id }}" class="text-decoration-none">
                                            <span class="badge bg-purple-subtle text-purple border border-purple">
                                                <i class="bi bi-box2 me-1"></i>{{ $vendor->produks_count ?? 0 }} Produk
                                            </span>
                                        </a>
                                    </td>
                                    <td>
                                        {{-- TODO: Uncomment when ReturBarang model is created
                                        @php
                                            $totalRetur = $vendor->returBarangs()->count();
                                            $pendingRetur = $vendor->returBarangs()->where('status_approval', 'pending')->count();
                                        @endphp
                                        <a href="#" class="text-decoration-none text-muted" title="Fitur dalam pengembangan">
                                            <span class="badge bg-secondary">{{ $totalRetur }}</span>
                                            @if ($pendingRetur > 0)
                                                <span class="badge bg-secondary text-light" title="Retur pending">{{ $pendingRetur }}</span>
                                            @endif
                                        </a>
                                        --}}
                                        <span class="badge bg-light text-muted">
                                            <i class="bi bi-hourglass me-1"></i>Pending
                                        </span>
                                    </td>
                                    <td>
                                        @if ($vendor->is_active)
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
                                            <a href="{{ route('master-vendor.show', $vendor) }}" class="btn btn-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('master-vendor.edit', $vendor) }}" class="btn btn-warning" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('master-vendor.destroy', $vendor) }}" method="POST" style="display:inline;" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger delete-btn" title="Hapus" data-name="{{ $vendor->nama_vendor }}">
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
                                                <h6>Tidak ada data Master Vendor</h6>
                                                <p class="small">Silakan tambahkan vendor baru untuk memulai</p>
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
                            Menampilkan {{ $vendors->firstItem() ?? 0 }} - {{ $vendors->lastItem() ?? 0 }} dari {{ $vendors->total() }} vendor
                        </div>
                        <div>
                            {{ $vendors->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Relationship Info Section -->
        <div class="row mt-4">
            <!-- Vendor Relationships -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-gradient-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-diagram-3 me-2"></i>Relasi & Integrasi Vendor
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item bg-light">
                                <strong><i class="bi bi-info-circle me-2"></i>Setiap vendor terintegrasi dengan:</strong>
                            </div>
                            
                            <!-- Master Produk -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon purple me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-box2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Master Produk</h6>
                                        <p class="mb-0 small text-muted">Produk yang disupply oleh vendor (belongsTo relationship)</p>
                                    </div>
                                    <span class="badge bg-purple-subtle text-purple">hasMany</span>
                                </div>
                            </div>

                            <!-- Quality Reinspection -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon blue me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">Quality Reinspection</h6>
                                        <p class="mb-0 small text-muted">Inspeksi quality untuk produk dari vendor</p>
                                    </div>
                                    <span class="badge bg-info-subtle text-info">hasMany</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-4">
                <!-- Related Master Data -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-success text-white">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-link-45deg me-2"></i>Master Data Terkait
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('master-produk.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-box2 text-purple me-3 fs-5"></i>
                                <div>
                                    <div class="fw-semibold">Master Produk</div>
                                    <small class="text-muted">Produk dari vendor</small>
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
                                <small class="text-muted">Relasi Database:</small>
                                <span class="badge bg-primary">2 Modules</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <div class="alert alert-primary-subtle border-primary mb-0">
                            <i class="bi bi-check-circle me-2"></i>
                            <small>Vendor terintegrasi dengan produk dan quality tracking</small>
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
                    html: `Apakah Anda yakin ingin menghapus vendor <strong>${name}</strong>?<br><small class="text-muted">Data akan dipindahkan ke trash dan dapat di-restore jika diperlukan.</small>`,
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
                        form.submit();
                    }
                });
            }
        });
    });
</script>
@endsection
