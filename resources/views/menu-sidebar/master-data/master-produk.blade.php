@extends('layouts.app')

@section('title', 'Master Produk')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-box2"></i> Master Produk</h3>
                    <p class="text-subtitle text-muted">Kelola data dasar barang/produk yang akan diproses</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('master-produk.create') }}" class="btn btn-primary float-end">
                        <i class="bi bi-plus-circle"></i> Tambah Produk
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
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Daftar Master Produk</h5>
                </div>
                <div class="card-body" style="padding-top: 24px;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Vendor</th>
                                    <th>Kategori</th>
                                    <th>Unit</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produks as $produk)
                                    <tr>
                                        <td><strong>{{ $produk->kode_produk }}</strong></td>
                                        <td>
                                            <div>{{ $produk->nama_produk }}</div>
                                            @if ($produk->spesifikasi)
                                                <small class="text-muted">{{ Str::limit($produk->spesifikasi, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($produk->vendor)
                                                <a href="{{ route('master-vendor.show', $produk->vendor) }}" class="text-decoration-none">
                                                    <span class="badge bg-info">{{ $produk->vendor->nama_vendor }}</span>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
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
                                                @default
                                                    <span class="text-muted">-</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $produk->unit }}</td>
                                        <td>{{ $produk->harga ? 'Rp ' . number_format($produk->harga, 0, ',', '.') : '-' }}</td>
                                        <td>
                                            @if ($produk->is_active)
                                                <span class="badge bg-success">✓ Active</span>
                                            @else
                                                <span class="badge bg-secondary">✗ Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('master-produk.show', $produk) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('master-produk.edit', $produk) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('master-produk.destroy', $produk) }}" method="POST" style="display:inline;" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-outline-danger delete-btn" title="Delete" data-name="{{ $produk->nama_produk }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Tidak ada data Master Produk
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $produks->links() }}
                    </div>
                </div>
            </div>
        </section>

        <!-- Integration Info Section -->
        <section class="section">
            <div class="row">
                <!-- Quick Links -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-link-45deg me-2"></i>Quick Links</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('master-vendor.index') }}" class="list-group-item list-group-item-action py-2">
                                    <i class="bi bi-shop text-info me-2"></i>Master Vendor
                                </a>
                                <a href="{{ route('penerimaan-barang.index') }}" class="list-group-item list-group-item-action py-2">
                                    <i class="bi bi-box-seam text-primary me-2"></i>Penerimaan Barang
                                </a>
                                <a href="{{ route('penyimpanan-ng.index') }}" class="list-group-item list-group-item-action py-2">
                                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>Penyimpanan NG
                                </a>
                                <a href="{{ route('rca-analysis.index') }}" class="list-group-item list-group-item-action py-2">
                                    <i class="bi bi-search text-danger me-2"></i>RCA Analysis
                                </a>
                                <a href="{{ route('scrap-disposal.index') }}" class="list-group-item list-group-item-action py-2">
                                    <i class="bi bi-trash text-secondary me-2"></i>Scrap/Disposal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-info-circle me-2"></i>Informasi Relasi Produk</h5>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-light">
                                    <strong>Master Produk berhubungan dengan:</strong>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-arrow-return-right text-info me-2 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">Master Vendor</span>
                                            <div class="text-muted small">Setiap produk harus memiliki vendor supplier</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-arrow-return-right text-primary me-2 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">Penerimaan Barang</span>
                                            <div class="text-muted small">Identifikasi barang masuk dari vendor</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-arrow-return-right text-warning me-2 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">Penyimpanan NG</span>
                                            <div class="text-muted small">Tracking barang rusak/defect per produk</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-arrow-return-right text-danger me-2 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">RCA Analysis</span>
                                            <div class="text-muted small">Analisa akar masalah defect produk</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-arrow-return-right text-secondary me-2 mt-1"></i>
                                        <div>
                                            <span class="fw-bold text-dark">Scrap/Disposal</span>
                                            <div class="text-muted small">Pencatatan barang yang di-scrap per produk</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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

