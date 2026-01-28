@extends('layouts.app')

@section('title', 'Retur Barang')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-arrow-left-square"></i> Retur Barang</h3>
                    <p class="text-subtitle text-muted">Kelola pengembalian barang dari vendor</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('retur-barang.create') }}" class="btn btn-primary float-end">
                        <i class="bi bi-plus-circle"></i> Tambah Retur
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

        <section class="section">
            <!-- Statistics -->
            <div class="row mb-3">
                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-body pb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-2">Total Retur</h6>
                                    <h4 class="mb-0">{{ $returs->total() }}</h4>
                                </div>
                                <div class="text-primary" style="font-size: 2rem;">
                                    <i class="bi bi-arrow-left-square"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-body pb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-2">Pending</h6>
                                    <h4 class="mb-0 text-warning">{{ \App\Models\ReturBarang::where('status_approval', 'pending')->count() }}</h4>
                                </div>
                                <div class="text-warning" style="font-size: 2rem;">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-body pb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-2">Approved</h6>
                                    <h4 class="mb-0 text-success">{{ \App\Models\ReturBarang::where('status_approval', 'approved')->count() }}</h4>
                                </div>
                                <div class="text-success" style="font-size: 2rem;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-body pb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-2">Rejected</h6>
                                    <h4 class="mb-0 text-danger">{{ \App\Models\ReturBarang::where('status_approval', 'rejected')->count() }}</h4>
                                </div>
                                <div class="text-danger" style="font-size: 2rem;">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Retur -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Retur Barang</h5>
                        <div style="width: 250px;">
                            <form method="GET" class="d-flex gap-2">
                                <select name="vendor_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">📋 Semua Vendor</option>
                                    @foreach (\App\Models\MasterVendor::where('is_active', true)->orderBy('nama_vendor')->get() as $vendor)
                                        <option value="{{ $vendor->id }}" 
                                            {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->nama_vendor }}
                                        </option>
                                    @endforeach
                                </select>
                                @if (request('vendor_id'))
                                    <a href="{{ route('retur-barang.index') }}" class="btn btn-sm btn-secondary" title="Clear Filter">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>No Retur</th>
                                    <th>Vendor</th>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Alasan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($returs as $retur)
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $loop->iteration + ($returs->currentPage() - 1) * $returs->perPage() }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $retur->no_retur }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $retur->vendor?->nama_vendor ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $retur->produk?->nama_produk ?? '-' }}</span>
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
                                                <form action="{{ route('retur-barang.destroy', $retur) }}" method="POST" style="display:inline;" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn" title="Hapus" data-name="{{ $retur->no_retur }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                                <p class="mt-2">Belum ada retur barang</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $returs->links() }}
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
</style>
@endpush
@endsection
