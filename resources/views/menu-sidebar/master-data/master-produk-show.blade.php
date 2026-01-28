@extends('layouts.app')

@section('title', 'Detail Master Produk')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-box2"></i> Detail Master Produk: {{ $masterProduk->kode_produk }}</h5>
                    <a href="{{ route('master-produk.index') }}" class="btn btn-light btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 15px; margin-bottom: 12px;">
                                <small style="color: #6c757d; font-weight: 600;">KODE PRODUK</small>
                                <p style="font-size: 16px; font-weight: 500; margin-top: 5px;">{{ $masterProduk->kode_produk }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 15px; margin-bottom: 12px;">
                                <small style="color: #6c757d; font-weight: 600;">NAMA PRODUK</small>
                                <p style="font-size: 16px; font-weight: 500; margin-top: 5px;">{{ $masterProduk->nama_produk }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 15px; margin-bottom: 12px;">
                                <small style="color: #6c757d; font-weight: 600;">VENDOR SUPPLIER</small>
                                <p style="font-size: 16px; font-weight: 500; margin-top: 5px;">
                                    @if ($masterProduk->vendor)
                                        <a href="{{ route('master-vendor.show', $masterProduk->vendor) }}" class="text-decoration-none">
                                            <span class="badge bg-info">{{ $masterProduk->vendor->kode_vendor }} - {{ $masterProduk->vendor->nama_vendor }}</span>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 15px; margin-bottom: 12px;">
                                <small style="color: #6c757d; font-weight: 600;">UNIT (UOM)</small>
                                <p style="font-size: 16px; font-weight: 500; margin-top: 5px;">{{ $masterProduk->unit }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 15px; margin-bottom: 12px;">
                                <small style="color: #6c757d; font-weight: 600;">KATEGORI</small>
                                <p style="font-size: 16px; font-weight: 500; margin-top: 5px;">
                                    @switch($masterProduk->kategori)
                                        @case('raw_material')
                                            <span class="badge bg-warning">Raw Material</span>
                                        @break
                                        @case('wip')
                                            <span class="badge bg-info">WIP</span>
                                        @break
                                        @case('finished_goods')
                                            <span class="badge bg-success">Finished Goods</span>
                                        @break
                                    @endswitch
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 15px; margin-bottom: 12px;">
                                <small style="color: #6c757d; font-weight: 600;">HARGA SATUAN</small>
                                <p style="font-size: 16px; font-weight: 500; margin-top: 5px;">{{ $masterProduk->harga ? 'Rp ' . number_format($masterProduk->harga, 0, ',', '.') : '-' }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($masterProduk->spesifikasi)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 15px; margin-bottom: 12px;">
                                    <small style="color: #6c757d; font-weight: 600;">SPESIFIKASI</small>
                                    <p style="font-size: 14px; margin-top: 5px;">{{ $masterProduk->spesifikasi }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($masterProduk->drawing_file)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 15px; margin-bottom: 12px;">
                                    <small style="color: #6c757d; font-weight: 600;">FILE DRAWING</small>
                                    <p style="font-size: 14px; margin-top: 5px;">{{ $masterProduk->drawing_file }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 15px; margin-bottom: 12px;">
                                <small style="color: #6c757d; font-weight: 600;">STATUS</small>
                                <p style="font-size: 16px; font-weight: 500; margin-top: 5px;">
                                    @if ($masterProduk->is_active)
                                        <span class="badge bg-success">✓ Aktif</span>
                                    @else
                                        <span class="badge bg-danger">✗ Inaktif</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #e3e6f0;">
                                <a href="{{ route('master-produk.edit', $masterProduk) }}" class="btn btn-warning me-2">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('master-produk.destroy', $masterProduk) }}" method="POST" style="display:inline;" class="delete-form-show">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger me-2 delete-btn-show" data-name="{{ $masterProduk->nama_produk }}">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                                <a href="{{ route('master-produk.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-back"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quality Inspection History Section --}}
            @if ($masterProduk->inspeksi && count($masterProduk->inspeksi) > 0)
                <div class="card shadow-lg">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Riwayat QC Inspeksi ({{ count($masterProduk->inspeksi) }} records)</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor Laporan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Hasil</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($masterProduk->inspeksi->take(10) as $inspeksi)
                                    <tr>
                                        <td><strong>{{ $inspeksi->nomor_laporan }}</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($inspeksi->tanggal_inspeksi)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if ($inspeksi->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($inspeksi->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($inspeksi->hasil === 'ok')
                                                <span class="badge bg-success">✓ OK</span>
                                            @else
                                                <span class="badge bg-danger">✗ NG</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('inspeksi-qc.show', $inspeksi->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- SweetAlert2 untuk Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-btn-show').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form-show');
            const name = this.getAttribute('data-name');
            
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
                    form.submit();
                }
            });
        });
    });
</script>
@endsection

