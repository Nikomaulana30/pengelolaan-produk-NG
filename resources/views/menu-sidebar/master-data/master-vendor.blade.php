@extends('layouts.app')

@section('title', 'Master Vendor')

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
    
    [data-bs-theme="dark"] .page-content {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .table {
        color: #e4e4e7 !important;
        border-color: #2c3142 !important;
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .table thead {
        background-color: #2c3142 !important;
    }
    
    [data-bs-theme="dark"] .table td,
    [data-bs-theme="dark"] .table th {
        border-color: #2c3142 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    
    [data-bs-theme="dark"] .text-muted {
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
                    <h3><i class="bi bi-shop"></i> Master Vendor</h3>
                    <p class="text-subtitle text-muted">Kelola data supplier dan vendor perusahaan</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('master-vendor.create') }}" class="btn btn-primary float-end">
                        <i class="bi bi-plus-circle"></i> Tambah Vendor
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

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistics Row -->
        <section class="section mb-3">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-body pb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-2">Total Vendor</h6>
                                    <h4 class="mb-0">{{ $vendors->total() }}</h4>
                                </div>
                                <div class="text-primary" style="font-size: 2rem;">
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
                                            <span class="badge bg-light text-dark">{{ $loop->iteration + ($vendors->currentPage() - 1) * $vendors->perPage() }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $vendor->kode_vendor }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <p class="mb-1"><strong>{{ $vendor->nama_vendor }}</strong></p>
                                                <small class="text-muted">
                                                    @if ($vendor->phone)
                                                        <i class="bi bi-telephone"></i> {{ $vendor->phone }}
                                                    @endif
                                                    @if ($vendor->email)
                                                        <br><i class="bi bi-envelope"></i> {{ $vendor->email }}
                                                    @endif
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $vendor->kota }}</span>
                                        </td>
                                        <td>
                                            @if ($vendor->kebijakan_retur === 'no_return')
                                                <span class="badge bg-danger">No Return</span>
                                            @elseif ($vendor->kebijakan_retur === 'full_return')
                                                <span class="badge bg-success">Full Return</span>
                                            @elseif ($vendor->kebijakan_retur === 'partial_return')
                                                <span class="badge bg-warning text-dark">Partial Return</span>
                                            @else
                                                <span class="badge bg-info">{{ $vendor->kebijakan_retur }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $totalRetur = $vendor->returBarangs()->count();
                                                $pendingRetur = $vendor->returBarangs()->where('status_approval', 'pending')->count();
                                            @endphp
                                            <a href="{{ route('retur-barang.index', ['vendor_id' => $vendor->id]) }}" 
                                               class="text-decoration-none" title="Lihat retur vendor ini">
                                                <span class="badge bg-primary">{{ $totalRetur }}</span>
                                                @if ($pendingRetur > 0)
                                                    <span class="badge bg-warning text-dark" title="Retur pending">{{ $pendingRetur }}</span>
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            @if ($vendor->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('master-vendor.show', $vendor) }}" class="btn btn-info" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('master-vendor.edit', $vendor) }}" class="btn btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('retur-barang.index', ['vendor_id' => $vendor->id]) }}" 
                                                   class="btn btn-primary" title="Lihat Retur">
                                                    <i class="bi bi-arrow-left-square"></i>
                                                </a>
                                                <form action="{{ route('master-vendor.destroy', $vendor) }}" method="POST" style="display:inline;" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger delete-btn" title="Hapus" data-name="{{ $vendor->nama_vendor }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                                <p class="mt-2">Belum ada data Master Vendor</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $vendors->links() }}
                    </div>
                </div>
            </div>
        </section>

        <!-- Integration Section -->
        <section class="section mt-4">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-diagram-3 me-2"></i>Integrasi Sistem
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-light">
                                    <small class="text-uppercase fw-bold text-muted">Modul Terkait:</small>
                                </li>

                                <li class="list-group-item d-flex align-items-start">
                                    <i class="bi bi-box-arrow-in-down text-primary fs-5 me-3 mt-1"></i>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text-dark">Penerimaan Barang</span>
                                                <div class="text-muted small">Barang diterima dari vendor</div>
                                            </div>
                                            <a href="{{ route('penerimaan-barang.index') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-arrow-right"></i> Buka
                                            </a>
                                        </div>
                                    </div>
                                </li>

                                <li class="list-group-item d-flex align-items-start">
                                    <i class="bi bi-arrow-left-square text-warning fs-5 me-3 mt-1"></i>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text-dark">Retur Barang</span>
                                                <div class="text-muted small">Pengembalian barang dari vendor</div>
                                            </div>
                                            <a href="{{ route('retur-barang.index') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-arrow-right"></i> Buka
                                            </a>
                                        </div>
                                    </div>
                                </li>

                                <li class="list-group-item d-flex align-items-start">
                                    <i class="bi bi-graph-up text-success fs-5 me-3 mt-1"></i>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text-dark">RCA Analysis</span>
                                                <div class="text-muted small">Analisis akar masalah dari defect</div>
                                            </div>
                                            <a href="{{ route('rca-analysis.index') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-arrow-right"></i> Buka
                                            </a>
                                        </div>
                                    </div>
                                </li>

                                <li class="list-group-item d-flex align-items-start">
                                    <i class="bi bi-award text-info fs-5 me-3 mt-1"></i>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text-dark">Vendor Scorecard</span>
                                                <div class="text-muted small">Evaluasi performa vendor</div>
                                            </div>
                                            <a href="{{ route('vendor-scorecard.index') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-arrow-right"></i> Buka
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>Informasi
                            </h5>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-3">Kebijakan Retur Vendor</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between py-2">
                                    <span><small>No Return</small></span>
                                    <span class="badge bg-danger">Tidak boleh retur</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between py-2">
                                    <span><small>Partial Return</small></span>
                                    <span class="badge bg-warning text-dark">Retur terbatas</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between py-2">
                                    <span><small>Full Return</small></span>
                                    <span class="badge bg-success">Retur 100%</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-group .btn {
        border: 1px solid #dee2e6;
    }

    .list-group-item {
        border: 1px solid #e9ecef;
        padding: 1rem;
    }

    .list-group-item.bg-light {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.6rem;
    }
</style>
@endpush
@endsection
