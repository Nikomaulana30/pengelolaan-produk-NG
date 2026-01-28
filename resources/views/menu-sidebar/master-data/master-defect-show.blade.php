@extends('layouts.app')

@section('title', 'Detail Master Defect')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-exclamation-triangle"></i> Detail Master Defect</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap jenis kerusakan dan standar penanganannya</p>
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('master-defect.edit', $masterDefect) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('master-defect.index') }}" class="btn btn-outline-secondary">
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
                    @if ($masterDefect->is_active)
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Status Aktif</strong> - Defect ini sedang digunakan dalam sistem QC dan RCA Analysis
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @else
                        <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <strong>Status Non-Aktif</strong> - Defect ini tidak lagi digunakan untuk input baru
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
                            <h5 class="card-title mb-0">📌 Identifikasi Defect</h5>
                        </div>
                        <div class="card-body">
                            <div class="field-box mb-3">
                                <label class="field-label">Kode Defect</label>
                                <p class="field-value"><strong>{{ $masterDefect->kode_defect }}</strong></p>
                            </div>

                            <div class="field-box mb-3">
                                <label class="field-label">Nama Defect</label>
                                <p class="field-value">{{ $masterDefect->nama_defect }}</p>
                            </div>

                            <div class="field-box">
                                <label class="field-label">Deskripsi Detail</label>
                                <p class="field-value">{{ nl2br(e($masterDefect->deskripsi)) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">⚠️ Klasifikasi & Severity</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-box mb-3">
                                        <label class="field-label">Tingkat Keparahan</label>
                                        <p class="field-value">
                                            @if ($masterDefect->criticality_level === 'minor')
                                                <span class="badge bg-info" style="font-size: 12px; padding: 6px 10px;">
                                                    🟢 Minor (Kecil/Kosmetik)
                                                </span>
                                            @elseif ($masterDefect->criticality_level === 'major')
                                                <span class="badge bg-warning" style="font-size: 12px; padding: 6px 10px;">
                                                    🟡 Major (Sedang/Fungsional)
                                                </span>
                                            @elseif ($masterDefect->criticality_level === 'critical')
                                                <span class="badge bg-danger" style="font-size: 12px; padding: 6px 10px;">
                                                    🔴 Critical (Berat/Keselamatan)
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field-box mb-3">
                                        <label class="field-label">Sumber Masalah</label>
                                        <p class="field-value">
                                            @if ($masterDefect->sumber_masalah === 'supplier')
                                                <span class="badge bg-secondary">📦 Supplier (Material Baku)</span>
                                            @elseif ($masterDefect->sumber_masalah === 'proses_produksi')
                                                <span class="badge bg-secondary">🏭 Proses Produksi (Mesin/Line)</span>
                                            @elseif ($masterDefect->sumber_masalah === 'handling_gudang')
                                                <span class="badge bg-secondary">📍 Handling Gudang (Penyimpanan/Pengiriman)</span>
                                            @else
                                                <span class="badge bg-secondary">❓ Lainnya</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">🔧 Solusi & Tindakan Awal</h5>
                        </div>
                        <div class="card-body">
                            <div class="field-box mb-3">
                                <label class="field-label">Solusi Standar</label>
                                <p class="field-value">{{ nl2br(e($masterDefect->solusi_standar)) }}</p>
                                <small class="text-muted">Quick fix atau temporary solution</small>
                            </div>

                            <div class="field-box mb-3">
                                <label class="field-label">Tindakan Awal</label>
                                <p class="field-value">{{ nl2br(e($masterDefect->tindakan_rekomendasi)) }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-lightbulb text-warning"></i>
                                    Immediate action yang harus dilakukan operator/QC. Untuk analisis mendalam, lihat <strong>RCA Analysis</strong>.
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Dapat Dirework?</label>
                                        <p class="field-value">
                                            @if ($masterDefect->is_rework_possible)
                                                <span class="badge bg-success">✅ Ya, dapat diperbaiki</span>
                                            @else
                                                <span class="badge bg-danger">❌ Tidak dapat diperbaiki (Scrap)</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Status</label>
                                        <p class="field-value">
                                            @if ($masterDefect->is_active)
                                                <span class="badge bg-success">🟢 Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">⚫ Non-Aktif</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Integration & Analytics -->
                <div class="col-12 col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">🔗 Integrasi Sistem</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">
                                Defect ini terhubung ke modul-modul berikut dalam sistem:
                            </p>

                            <div class="integration-item mb-3">
                                <div class="integration-icon">
                                    <i class="bi bi-search text-primary"></i>
                                </div>
                                <div class="integration-content">
                                    <p class="integration-title">Inspeksi/QC</p>
                                    <p class="integration-desc">Digunakan sebagai dropdown saat QC menginput hasil inspeksi</p>
                                </div>
                            </div>

                            <div class="integration-item mb-3">
                                <div class="integration-icon">
                                    <i class="bi bi-diagram-3 text-warning"></i>
                                </div>
                                <div class="integration-content">
                                    <p class="integration-title">RCA Analysis</p>
                                    <p class="integration-desc">Dihitung untuk Top Defect Pareto Chart dan analisa akar penyebab</p>
                                </div>
                            </div>

                            <div class="integration-item mb-3">
                                <div class="integration-icon">
                                    <i class="bi bi-trash text-danger"></i>
                                </div>
                                <div class="integration-content">
                                    <p class="integration-title">Scrap/Disposal</p>
                                    <p class="integration-desc">Menentukan rekomendasi tindakan (Rework atau Scrap)</p>
                                </div>
                            </div>

                            @if ($masterDefect->sumber_masalah === 'supplier')
                            <div class="integration-item">
                                <div class="integration-icon">
                                    <i class="bi bi-building text-info"></i>
                                </div>
                                <div class="integration-content">
                                    <p class="integration-title">Vendor Scorecard</p>
                                    <p class="integration-desc">Tracking kinerja supplier terkait defect ini</p>
                                </div>
                            </div>
                            @endif

                            @if ($masterDefect->sumber_masalah === 'proses_produksi')
                            <div class="integration-item">
                                <div class="integration-icon">
                                    <i class="bi bi-gear text-success"></i>
                                </div>
                                <div class="integration-content">
                                    <p class="integration-title">Mesin/Line Tracking</p>
                                    <p class="integration-desc">Tracking defect yang dihasilkan mesin/line produksi</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">📊 Informasi Teknis</h5>
                        </div>
                        <div class="card-body">
                            <div class="field-box mb-3">
                                <label class="field-label">ID Sistem</label>
                                <p class="field-value small"><code>{{ $masterDefect->id }}</code></p>
                            </div>

                            <div class="field-box mb-3">
                                <label class="field-label">Dibuat Tanggal</label>
                                <p class="field-value">{{ $masterDefect->created_at->format('d M Y, H:i:s') }}</p>
                            </div>

                            <div class="field-box mb-3">
                                <label class="field-label">Terakhir Diperbarui</label>
                                <p class="field-value">{{ $masterDefect->updated_at->format('d M Y, H:i:s') }}</p>
                            </div>

                            <div class="field-box">
                                <label class="field-label">Status Aktif</label>
                                <p class="field-value">
                                    @if ($masterDefect->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Non-Aktif</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">⚙️ Aksi</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('master-defect.edit', $masterDefect) }}" class="btn btn-warning w-100 mb-2">
                                <i class="bi bi-pencil"></i> Edit Defect
                            </a>
                            <form action="{{ route('master-defect.destroy', $masterDefect) }}" method="POST" style="display:inline;" class="delete-form w-100">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger w-100 delete-btn" data-name="{{ $masterDefect->nama_defect }}">
                                    <i class="bi bi-trash"></i> Hapus Defect
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
    .field-label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
    }

    .field-value {
        font-size: 14px;
        color: #333;
        margin-bottom: 0;
    }

    .field-box {
        padding: 12px;
        background-color: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid #007bff;
    }

    .card {
        border: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 16px;
    }

    .card-title {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-bottom: 0;
    }

    .card-body {
        padding: 16px;
    }

    .badge {
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 500;
    }

    .integration-item {
        display: flex;
        gap: 12px;
        padding: 12px;
        background-color: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid #e9ecef;
    }

    .integration-icon {
        font-size: 20px;
        min-width: 24px;
    }

    .integration-content {
        flex: 1;
    }

    .integration-title {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
    }

    .integration-desc {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 0;
    }

    .btn {
        border-radius: 6px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
    }

    .text-muted {
        color: #6c757d;
    }

    code {
        background-color: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
        color: #e83e8c;
        font-size: 12px;
    }
</style>
@endpush

<!-- SweetAlert2 Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Script delete SweetAlert2 Master Defect Show loaded');
        
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-btn')) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                const button = e.target.closest('.delete-btn');
                const form = button.closest('.delete-form');
                const name = button.getAttribute('data-name');
                
                console.log('Delete button clicked for:', name);
                
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
                        console.log('User confirmed delete');
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
