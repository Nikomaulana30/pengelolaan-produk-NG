@extends('layouts.app')

@section('title', 'Detail Master Lokasi')

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
    [data-bs-theme="dark"] .field-label {
        color: #a1a1a1 !important;
    }
    
    [data-bs-theme="dark"] .field-box p,
    [data-bs-theme="dark"] .field-value {
        color: #e4e4e7 !important;
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
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-building"></i> Detail Master Lokasi</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap lokasi penyimpanan barang</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('master-lokasi.index') }}" class="btn btn-outline-secondary float-end">
                        <i class="bi bi-arrow-left"></i> Kembali
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
            <div class="row">
                <!-- Detail Card -->
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Informasi Lokasi</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Kode Lokasi</label>
                                        <p class="field-value"><strong>{{ $masterLokasi->kode_lokasi }}</strong></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Nama Lokasi</label>
                                        <p class="field-value">{{ $masterLokasi->nama_lokasi }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="field-box">
                                        <label class="field-label">Zona Gudang</label>
                                        <p class="field-value"><span class="badge bg-primary">{{ strtoupper(str_replace('zona_', '', $masterLokasi->zona_gudang)) }}</span></p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="field-box">
                                        <label class="field-label">Rack</label>
                                        <p class="field-value"><strong>{{ $masterLokasi->rack }}</strong></p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="field-box">
                                        <label class="field-label">Bin</label>
                                        <p class="field-value"><strong>{{ $masterLokasi->bin }}</strong></p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="field-box">
                                        <label class="field-label">Tipe Lokasi</label>
                                        <p class="field-value">
                                            @if ($masterLokasi->tipe_lokasi === 'regular')
                                                <span class="badge bg-info">🟢 Regular</span>
                                            @elseif ($masterLokasi->tipe_lokasi === 'karantina')
                                                <span class="badge bg-warning">🟡 Karantina</span>
                                            @elseif ($masterLokasi->tipe_lokasi === 'ng_storage')
                                                <span class="badge bg-danger">🔴 NG Storage</span>
                                            @elseif ($masterLokasi->tipe_lokasi === 'scrap')
                                                <span class="badge bg-dark">⚫ Scrap</span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $masterLokasi->tipe_lokasi }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Status</label>
                                        <p class="field-value">
                                            @if ($masterLokasi->is_active)
                                                <span class="badge bg-success">✓ Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">✗ Non-Aktif</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Deskripsi</label>
                                        <p class="field-value">
                                            {!! $masterLokasi->deskripsi ?? '<span class="text-muted">-</span>' !!}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted small mb-2">
                                        <strong>Dibuat:</strong>
                                    </p>
                                    <p class="small">{{ $masterLokasi->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted small mb-2">
                                        <strong>Diperbarui:</strong>
                                    </p>
                                    <p class="small">{{ $masterLokasi->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Aksi</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('master-lokasi.edit', $masterLokasi) }}" class="btn btn-warning w-100 mb-2">
                                <i class="bi bi-pencil"></i> Edit Lokasi
                            </a>
                            <form action="{{ route('master-lokasi.destroy', $masterLokasi) }}" method="POST" style="display:inline;" class="delete-form w-100">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger w-100 delete-btn" data-name="{{ $masterLokasi->kode_lokasi }}">
                                    <i class="bi bi-trash"></i> Hapus Lokasi
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Integration Info -->
                    <div class="card mt-3">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-diagram-3 me-2"></i>Integrasi Sistem
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-light">
                                    <small class="text-uppercase fw-bold text-muted">Kegunaan Lokasi Ini:</small>
                                </li>

                                {{-- Logika Tampilan Berdasarkan Tipe Lokasi --}}
                                @if ($masterLokasi->tipe_lokasi === 'regular')
                                    <li class="list-group-item d-flex align-items-start">
                                        <i class="bi bi-box-seam text-success fs-5 me-3 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">Good Stock Storage</span>
                                            <div class="text-muted small">Penyimpanan utama untuk barang kondisi bagus (OK)</div>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-start">
                                        <i class="bi bi-arrow-down-square text-primary fs-5 me-3 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">Inbound Receiving</span>
                                            <div class="text-muted small">Dapat dipilih saat penerimaan barang dari vendor</div>
                                        </div>
                                    </li>

                                @elseif ($masterLokasi->tipe_lokasi === 'karantina')
                                    <li class="list-group-item d-flex align-items-start">
                                        <i class="bi bi-shield-exclamation text-warning fs-5 me-3 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">Quarantine Area</span>
                                            <div class="text-muted small">Area isolasi barang menunggu keputusan QC</div>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-start">
                                        <i class="bi bi-hourglass-split text-info fs-5 me-3 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">QC Hold</span>
                                            <div class="text-muted small">Barang tidak boleh dimutasi keluar tanpa Approval</div>
                                        </div>
                                    </li>

                                @elseif ($masterLokasi->tipe_lokasi === 'ng_storage')
                                    <li class="list-group-item d-flex align-items-start">
                                        <i class="bi bi-x-octagon text-danger fs-5 me-3 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">Defect / NG Storage</span>
                                            <div class="text-muted small">Penyimpanan khusus barang rusak (Not Good)</div>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-start">
                                        <i class="bi bi-clipboard-data text-secondary fs-5 me-3 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">RCA Tracking</span>
                                            <div class="text-muted small">Sumber data untuk analisa cacat produk</div>
                                        </div>
                                    </li>

                                @elseif ($masterLokasi->tipe_lokasi === 'scrap')
                                    <li class="list-group-item d-flex align-items-start">
                                        <i class="bi bi-trash3 text-dark fs-5 me-3 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">Scrap / Disposal</span>
                                            <div class="text-muted small">Barang siap untuk proses pemusnahan/penjualan limbah</div>
                                        </div>
                                    </li>
                                @endif
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
        console.log('Script delete SweetAlert2 Master Lokasi Show loaded');
        
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
                    title: 'Hapus Lokasi?',
                    html: `Apakah Anda yakin ingin menghapus lokasi <strong>${name}</strong>?<br><small class="text-muted">Data akan dipindahkan ke trash dan dapat di-restore jika diperlukan.</small>`,
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
@endsection

@push('styles')
<style>
    .field-box {
        padding: 12px;
        background-color: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 12px;
    }

    .field-label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 6px;
    }

    .field-value {
        font-size: 14px;
        color: #333;
        margin: 0;
    }

    .badge {
        font-size: 12px;
        padding: 6px 10px;
    }

    .btn {
        border-radius: 6px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
    }

    .alert {
        border-radius: 8px;
        border: none;
    }
</style>
@endpush
