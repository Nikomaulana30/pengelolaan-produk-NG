@extends('layouts.app')

@section('title', 'Detail Retur Barang')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-arrow-left-square"></i> Detail Retur Barang</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap retur barang {{ $retur_barang->no_retur }}</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('retur-barang.index') }}" class="btn btn-outline-secondary float-end">
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
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Informasi Retur</h5>
                        </div>
                        <div class="card-body">
                            <!-- Row 1 -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">No Retur</label>
                                        <p class="field-value"><strong>{{ $retur_barang->no_retur }}</strong></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Tanggal Retur</label>
                                        <p class="field-value">{{ $retur_barang->tanggal_retur->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2: Vendor & Produk -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Vendor</label>
                                        <p class="field-value">
                                            <strong>{{ $retur_barang->vendor->nama_vendor }}</strong><br>
                                            <small class="text-muted">{{ $retur_barang->vendor->kode_vendor }}</small>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Produk</label>
                                        <p class="field-value">
                                            <strong>{{ $retur_barang->produk->nama_produk ?? '-' }}</strong><br>
                                            <small class="text-muted">{{ $retur_barang->produk->kode_produk ?? '-' }}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 3: Alasan & Qty -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Alasan Retur</label>
                                        <p class="field-value">
                                            @php
                                                $alasanMap = [
                                                    'defect' => 'Defect / Cacat',
                                                    'qty_tidak_sesuai' => 'Qty Tidak Sesuai',
                                                    'kualitas_buruk' => 'Kualitas Buruk',
                                                    'expired' => 'Expired',
                                                    'rusak_pengiriman' => 'Rusak Pengiriman',
                                                    'lainnya' => 'Lainnya'
                                                ];
                                            @endphp
                                            <strong>{{ $alasanMap[$retur_barang->alasan_retur] ?? $retur_barang->alasan_retur }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Jumlah Retur</label>
                                        <p class="field-value">
                                            <span class="badge bg-info" style="font-size: 1rem; padding: 0.5rem 0.75rem;">{{ $retur_barang->jumlah_retur }} unit</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Deskripsi Keluhan -->
                            <div class="mb-3">
                                <label class="field-label">Deskripsi Keluhan</label>
                                <p class="field-value">
                                    @if ($retur_barang->deskripsi_keluhan)
                                        {!! nl2br(e($retur_barang->deskripsi_keluhan)) !!}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>

                            <hr>

                            <!-- Timestamps -->
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted small mb-2">
                                        <strong>Dibuat:</strong>
                                    </p>
                                    <p class="small">{{ $retur_barang->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted small mb-2">
                                        <strong>Diperbarui:</strong>
                                    </p>
                                    <p class="small">{{ $retur_barang->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Status Approval</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="field-label">Status</label>
                                <p class="field-value">
                                    @if ($retur_barang->status_approval === 'pending')
                                        <span class="badge bg-warning text-dark" style="font-size: 1rem; padding: 0.5rem 0.75rem;">Pending Approval</span>
                                    @elseif ($retur_barang->status_approval === 'approved')
                                        <span class="badge bg-success" style="font-size: 1rem; padding: 0.5rem 0.75rem;">Approved</span>
                                    @else
                                        <span class="badge bg-danger" style="font-size: 1rem; padding: 0.5rem 0.75rem;">Rejected</span>
                                    @endif
                                </p>
                            </div>

                            @if ($retur_barang->catatan_approval)
                                <div class="mb-3">
                                    <label class="field-label">Catatan Approval</label>
                                    <p class="field-value">
                                        {!! nl2br(e($retur_barang->catatan_approval)) !!}
                                    </p>
                                </div>
                            @endif
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
                            <a href="{{ route('retur-barang.edit', $retur_barang) }}" class="btn btn-warning w-100 mb-2">
                                <i class="bi bi-pencil"></i> Edit Retur
                            </a>
                            <form action="{{ route('retur-barang.destroy', $retur_barang) }}" method="POST" style="display:inline;" class="delete-form w-100">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger w-100 delete-btn" data-name="{{ $retur_barang->no_retur }}">
                                    <i class="bi bi-trash"></i> Hapus Retur
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Integration Card -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Integrasi Sistem</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <span><small>Kode Vendor</small></span>
                                    <span class="badge bg-primary">{{ $retur_barang->vendor->kode_vendor }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <span><small>Kode Produk</small></span>
                                    <span class="badge bg-primary">{{ $retur_barang->produk->kode_produk ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <span><small>Status RCA</small></span>
                                    <span class="badge bg-secondary">Belum Dianalisis</span>
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
                    title: 'Hapus Retur Barang?',
                    html: `Apakah Anda yakin ingin menghapus retur <strong>${name}</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
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
    .field-box {
        padding: 0.75rem;
        background-color: #f8f9fa;
        border-radius: 0.375rem;
        margin-bottom: 0.75rem;
    }

    .field-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 0.5rem;
    }

    .field-value {
        font-size: 0.875rem;
        color: #333;
        margin: 0;
    }

    .btn {
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .badge {
        font-size: 0.75rem;
    }

    .list-group-item {
        border: none;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
        font-size: 0.875rem;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .text-muted {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .text-subtitle {
        color: #6c757d;
        font-size: 0.875rem;
    }
</style>
@endpush
@endsection
