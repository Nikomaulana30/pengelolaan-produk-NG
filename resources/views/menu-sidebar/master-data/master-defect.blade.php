@extends('layouts.app')

@section('title', 'Master Defect')

@section('content')
<div class="container-fluid">
    <!-- Enhanced Header Section -->
    <div class="page-heading mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="stats-icon red me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                    <div>
                        <h3 class="mb-1">Master Defect</h3>
                        <p class="text-subtitle text-muted mb-0">Kelola jenis defect & tracking quality</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('master-defect.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Defect Baru
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
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                            <h6 class="text-muted font-semibold">Total Defect</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($totalDefect) }}</h6>
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
                            <h6 class="text-muted font-semibold">Defect Aktif</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($defectAktif) }}</h6>
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
                                <i class="bi bi-exclamation-diamond"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                            <h6 class="text-muted font-semibold">Critical Level</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($criticalDefect) }}</h6>
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
                                <i class="bi bi-tools"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                            <h6 class="text-muted font-semibold">Bisa Rework</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($reworkable) }}</h6>
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
                        <i class="bi bi-table me-2 text-danger"></i>Daftar Master Defect
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
                            <tr class="table-danger">
                                <th><i class="bi bi-hash me-1"></i>Kode</th>
                                <th><i class="bi bi-card-text me-1"></i>Nama Defect</th>
                                <th><i class="bi bi-speedometer me-1"></i>Criticality</th>
                                <th><i class="bi bi-diagram-3 me-1"></i>Sumber</th>
                                <th><i class="bi bi-tools me-1"></i>Rework</th>
                                <th><i class="bi bi-bar-chart me-1"></i>Occurrences</th>
                                <th><i class="bi bi-toggle-on me-1"></i>Status</th>
                                <th class="text-center"><i class="bi bi-gear me-1"></i>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($defects as $defect)
                                <tr>
                                    <td>
                                        <span class="badge bg-dark font-monospace">{{ $defect->kode_defect }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $defect->nama_defect }}</div>
                                        @if ($defect->deskripsi)
                                            <small class="text-muted">{{ Str::limit($defect->deskripsi, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($defect->criticality_level === 'critical')
                                            <span class="badge bg-danger-subtle text-danger border border-danger">
                                                <i class="bi bi-exclamation-octagon-fill me-1"></i>Critical
                                            </span>
                                        @elseif ($defect->criticality_level === 'major')
                                            <span class="badge bg-warning-subtle text-warning border border-warning">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Major
                                            </span>
                                        @elseif ($defect->criticality_level === 'minor')
                                            <span class="badge bg-info-subtle text-info border border-info">
                                                <i class="bi bi-info-circle-fill me-1"></i>Minor
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ $defect->criticality_level }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($defect->sumber_masalah === 'supplier')
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-shop me-1"></i>Supplier
                                            </span>
                                        @elseif ($defect->sumber_masalah === 'proses_produksi')
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-gear-fill me-1"></i>Produksi
                                            </span>
                                        @elseif ($defect->sumber_masalah === 'handling_gudang')
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-box-seam me-1"></i>Gudang
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-three-dots me-1"></i>{{ ucfirst($defect->sumber_masalah) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($defect->is_rework_possible)
                                            <span class="badge bg-success-subtle text-success border border-success">
                                                <i class="bi bi-check-circle-fill me-1"></i>Ya
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger">
                                                <i class="bi bi-x-circle-fill me-1"></i>Tidak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-purple-subtle text-purple" title="Quality Reinspections">
                                            <i class="bi bi-search me-1"></i>{{ $defect->quality_reinspections_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($defect->is_active)
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
                                            <a href="{{ route('master-defect.show', $defect) }}" class="btn btn-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('master-defect.edit', $defect) }}" class="btn btn-warning" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('master-defect.destroy', $defect) }}" method="POST" style="display:inline;" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger delete-btn" title="Hapus" data-name="{{ $defect->nama_defect }}">
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
                                            <h6>Tidak ada data Master Defect</h6>
                                            <p class="small">Silakan tambahkan defect baru untuk memulai</p>
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
                        Menampilkan {{ $defects->firstItem() ?? 0 }} - {{ $defects->lastItem() ?? 0 }} dari {{ $defects->total() }} defect
                    </div>
                    <div>
                        {{ $defects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Relationship Info Section -->
    <div class="row mt-4">
        <!-- Defect Relationships -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-diagram-3 me-2"></i>Relasi & Integrasi Defect
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item bg-light">
                            <strong><i class="bi bi-info-circle me-2"></i>Master Defect digunakan dalam workflow:</strong>
                        </div>
                        
                        <!-- Quality Reinspection -->
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon purple me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                    <i class="bi bi-search"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">Quality Reinspection</h6>
                                    <p class="mb-0 small text-muted">Inspeksi ulang produk defect untuk menentukan tindakan</p>
                                </div>
                                <span class="badge bg-purple-subtle text-purple">hasMany</span>
                            </div>
                        </div>

                        <!-- Production Rework -->
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon blue me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                    <i class="bi bi-arrow-repeat"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">Production Rework</h6>
                                    <p class="mb-0 small text-muted">Proses perbaikan produk berdasarkan jenis defect</p>
                                </div>
                                <span class="badge bg-info-subtle text-info">hasMany</span>
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
                        <a href="{{ route('quality-reinspection.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-search text-purple me-3 fs-5"></i>
                            <div>
                                <div class="fw-semibold">Quality Reinspection</div>
                                <small class="text-muted">Inspeksi defect</small>
                            </div>
                        </a>
                        <a href="{{ route('production-rework.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-arrow-repeat text-blue me-3 fs-5"></i>
                            <div>
                                <div class="fw-semibold">Production Rework</div>
                                <small class="text-muted">Proses perbaikan</small>
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
                            <span class="badge bg-primary">2 Modules</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                    
                    <div class="alert alert-primary-subtle border-primary mb-0">
                        <i class="bi bi-check-circle me-2"></i>
                        <small>Defect tracking terintegrasi dengan quality control & production</small>
                    </div>
                </div>
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
                    title: 'Hapus Defect?',
                    html: `Apakah Anda yakin ingin menghapus defect <strong>${name}</strong>?<br><small class="text-muted">Data akan dipindahkan ke trash dan dapat di-restore jika diperlukan.</small>`,
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
