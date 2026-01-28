@extends('layouts.app')

@section('title', 'Master Defect')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-exclamation-triangle"></i> Master Defect</h3>
                    <p class="text-subtitle text-muted">Kelola daftar jenis defect dan karakteristiknya</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('master-defect.create') }}" class="btn btn-primary float-end">
                        <i class="bi bi-plus-circle"></i> Tambah Defect
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
                    <h5 class="card-title">Daftar Master Defect</h5>
                </div>
                <div class="card-body" style="padding-top: 24px;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Defect</th>
                                    <th>Nama Defect</th>
                                    <th>Criticality Level</th>
                                    <th>Sumber Masalah</th>
                                    <th>Bisa Rework</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($defects as $defect)
                                    <tr>
                                        <td><strong>{{ $defect->kode_defect }}</strong></td>
                                        <td>{{ $defect->nama_defect }}</td>
                                        <td>
                                            @if ($defect->criticality_level === 'critical')
                                                <span class="badge bg-danger">Critical</span>
                                            @elseif ($defect->criticality_level === 'major')
                                                <span class="badge bg-warning">Major</span>
                                            @elseif ($defect->criticality_level === 'minor')
                                                <span class="badge bg-info">Minor</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $defect->criticality_level }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($defect->sumber_masalah === 'supplier')
                                                <span class="badge bg-light text-dark">📦 Supplier</span>
                                            @elseif ($defect->sumber_masalah === 'proses_produksi')
                                                <span class="badge bg-light text-dark">🏭 Proses Produksi</span>
                                            @elseif ($defect->sumber_masalah === 'handling_gudang')
                                                <span class="badge bg-light text-dark">📍 Handling Gudang</span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $defect->sumber_masalah }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($defect->is_rework_possible)
                                                <span class="badge bg-success"><i class="bi bi-check"></i> Ya</span>
                                            @else
                                                <span class="badge bg-danger"><i class="bi bi-x"></i> Tidak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($defect->is_active)
                                                <span class="badge bg-success">✓ Active</span>
                                            @else
                                                <span class="badge bg-secondary">✗ Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('master-defect.show', $defect) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('master-defect.edit', $defect) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('master-defect.destroy', $defect) }}" method="POST" style="display:inline;" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-outline-danger delete-btn" title="Delete" data-name="{{ $defect->nama_defect }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Tidak ada data Master Defect
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $defects->links() }}
                    </div>

                    <!-- Integration Section -->
                    <div class="row mt-5">
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bi bi-lightning"></i> RCA Analysis</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="text-muted small">Gunakan master defect untuk analisis root cause</p>
                                    <a href="{{ route('rca-analysis.index') }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-link-45deg"></i> Ke RCA Analysis
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
                                        Total Defect: <strong class="text-danger">{{ $defects->total() }}</strong> |
                                        Critical: <strong class="text-warning">{{ $defects->where('criticality_level', 'critical')->count() }}</strong>
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
        console.log('Script delete SweetAlert2 Master Defect loaded');
        
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
@endsection
