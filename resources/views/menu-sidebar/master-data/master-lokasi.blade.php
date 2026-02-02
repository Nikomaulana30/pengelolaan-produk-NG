@extends('layouts.app')

@section('title', 'Master Lokasi')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-building"></i> Master Lokasi Gudang</h3>
                    <p class="text-subtitle text-muted">Kelola data lokasi penyimpanan barang di gudang</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('master-lokasi.create') }}" class="btn btn-primary float-end">
                        <i class="bi bi-plus-circle"></i> Tambah Lokasi
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
                    <h5 class="card-title">Daftar Master Lokasi</h5>
                </div>
                <div class="card-body" style="padding-top: 24px;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Lokasi</th>
                                    <th>Nama Lokasi</th>
                                    <th>Zona</th>
                                    <th>Rack</th>
                                    <th>Bin</th>
                                    <th>Tipe</th>
                                    <th>Status Lokasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lokasis as $lokasi)
                                    <tr>
                                        <td><strong>{{ $lokasi->kode_lokasi }}</strong></td>
                                        <td>{{ $lokasi->nama_lokasi }}</td>
                                        <td>{{ $lokasi->zona }}</td>
                                        <td>{{ $lokasi->rack }}</td>
                                        <td>{{ $lokasi->bin }}</td>
                                        <td>
                                            @if ($lokasi->tipe === 'regular')
                                                <span class="badge bg-info">Regular</span>
                                            @elseif ($lokasi->tipe === 'quarantine')
                                                <span class="badge bg-warning">Quarantine</span>
                                            @elseif ($lokasi->tipe === 'temporary')
                                                <span class="badge bg-secondary">Temporary</span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $lokasi->tipe }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($lokasi->is_active)
                                                <span class="badge bg-success">✓ Active</span>
                                            @else
                                                <span class="badge bg-secondary">✗ Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('master-lokasi.show', $lokasi) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('master-lokasi.edit', $lokasi) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('master-lokasi.destroy', $lokasi) }}" method="POST" style="display:inline;" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-outline-danger delete-btn" title="Delete" data-name="{{ $lokasi->kode_lokasi }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Tidak ada data Master Lokasi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $lokasis->links() }}
                    </div>

                    <!-- Integration Section -->
                    <div class="row mt-5">
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bi bi-box-seam"></i> Penerimaan Barang</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small">Lokasi adalah tujuan penyimpanan barang yang diterima dari vendor</p>
                                    {{-- <a href="{{ route('penerimaan-barang.index') }}" class="btn btn-sm btn-primary"> --}}
                                    <a href="#" class="btn btn-sm btn-secondary disabled">
                                        <i class="bi bi-link-45deg"></i> Ke Penerimaan Barang
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Penyimpanan NG</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small">Lokasi Quarantine digunakan untuk menyimpan barang defect/NG</p>
                                    <a href="{{ route('penyimpanan-ng.index') }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-link-45deg"></i> Ke Penyimpanan NG
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0"><i class="bi bi-trash"></i> Scrap/Disposal</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small">Tracking lokasi asal barang yang akan dimusnahkan</p>
                                    <a href="{{ route('scrap-disposal.index') }}" class="btn btn-sm btn-danger">
                                        <i class="bi bi-link-45deg"></i> Ke Scrap/Disposal
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="bi bi-archive"></i> Quick Reference</h6>
                                </div>
                                <div class="card-body">
                                    <small class="text-muted">
                                        Total Lokasi: <strong class="text-primary">{{ $lokasis->total() }}</strong> |
                                        Aktif: <strong class="text-success">{{ $lokasis->where('is_active', true)->count() }}</strong> |
                                        Quarantine: <strong class="text-warning">{{ $lokasis->where('tipe', 'quarantine')->count() }}</strong>
                                    </small>
                                </div>
                            </div>
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
        console.log('Script delete SweetAlert2 Master Lokasi loaded');
        
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
