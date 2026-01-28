@extends('layouts.app')

@section('title', 'Detail Disposisi Assignment')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-diagram-3"></i> Detail Disposisi Assignment</h3>
                    <p class="text-subtitle text-muted">{{ $disposisiAssignment->penyimpananNg->nomor_penyimpanan }}</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('disposisi-assignment.index') }}" class="btn btn-secondary float-end">
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

        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Informasi Assignment</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted">No. Penyimpanan</label>
                                <p class="fw-bold">{{ $disposisiAssignment->penyimpananNg->nomor_penyimpanan }}</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted">Status Assignment</label>
                                <p>
                                    @if ($disposisiAssignment->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif ($disposisiAssignment->status === 'in_progress')
                                        <span class="badge bg-primary">In Progress</span>
                                    @elseif ($disposisiAssignment->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-secondary">Cancelled</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted">Produk</label>
                                <p class="fw-bold">{{ $disposisiAssignment->penyimpananNg->nama_barang }}</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted">Quantity</label>
                                <p class="fw-bold">{{ $disposisiAssignment->penyimpananNg->qty_awal }} unit</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted">Lokasi Asal</label>
                                <p>
                                    <code style="background-color: #fff3cd; padding: 6px 10px; border-radius: 4px; color: #856404;">
                                        {{ $disposisiAssignment->penyimpananNg->lokasi_lengkap }}
                                    </code>
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted">Disposisi</label>
                                <p>
                                    @if($disposisiAssignment->disposisi)
                                        <span class="badge bg-info">{{ $disposisiAssignment->disposisi->nama_disposisi }}</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Ada Disposisi</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted">Jenis Tindakan</label>
                                <p class="fw-bold">
                                    @if($disposisiAssignment->disposisi)
                                        {{ ucfirst(str_replace('_', ' ', $disposisiAssignment->disposisi->jenis_tindakan)) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted">Butuh Approval</label>
                                <p>
                                    @if($disposisiAssignment->disposisi)
                                        @if ($disposisiAssignment->disposisi->butuh_approval)
                                            <span class="badge bg-success">Ya</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label text-muted">Catatan</label>
                                <p>{{ $disposisiAssignment->catatan ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Lokasi Tujuan Section dari Assignment (prioritas) atau dari Disposisi -->
                        @if ($disposisiAssignment->zone_tujuan || $disposisiAssignment->lokasi_lengkap_tujuan || $disposisiAssignment->disposisi->zone_tujuan || $disposisiAssignment->disposisi->lokasi_lengkap_tujuan)
                            <hr>
                            <h6 class="text-primary mb-3">📍 Lokasi Tujuan Relokasi</h6>
                            
                            <div class="row mb-3">
                                @php
                                    // Prioritas: Data dari assignment, fallback ke disposisi
                                    $zoneTujuan = $disposisiAssignment->zone_tujuan ?? $disposisiAssignment->disposisi->zone_tujuan;
                                    $rackTujuan = $disposisiAssignment->rack_tujuan ?? $disposisiAssignment->disposisi->rack_tujuan;
                                    $binTujuan = $disposisiAssignment->bin_tujuan ?? $disposisiAssignment->disposisi->bin_tujuan;
                                    $lokasiLengkapTujuan = $disposisiAssignment->lokasi_lengkap_tujuan ?? $disposisiAssignment->disposisi->lokasi_lengkap_tujuan;
                                    $tanggalRelokasi = $disposisiAssignment->tanggal_relokasi;
                                    $alasanRelokasi = $disposisiAssignment->alasan_relokasi;
                                @endphp

                                @if ($zoneTujuan)
                                    <div class="col-12 col-md-3">
                                        <label class="form-label text-muted">Zone Tujuan</label>
                                        <p>
                                            <span class="badge bg-info">
                                                {{ strtoupper(str_replace('zona_', 'Zona ', $zoneTujuan)) }}
                                            </span>
                                        </p>
                                    </div>
                                @endif

                                @if ($rackTujuan)
                                    <div class="col-12 col-md-3">
                                        <label class="form-label text-muted">Rack Tujuan</label>
                                        <p class="fw-bold">{{ $rackTujuan }}</p>
                                    </div>
                                @endif

                                @if ($binTujuan)
                                    <div class="col-12 col-md-3">
                                        <label class="form-label text-muted">Bin Tujuan</label>
                                        <p class="fw-bold">{{ $binTujuan }}</p>
                                    </div>
                                @endif

                                @if ($lokasiLengkapTujuan)
                                    <div class="col-12 col-md-3">
                                        <label class="form-label text-muted">Lokasi Lengkap</label>
                                        <p>
                                            <code style="background-color: #f5f5f5; padding: 6px 10px; border-radius: 4px;">
                                                {{ $lokasiLengkapTujuan }}
                                            </code>
                                        </p>
                                    </div>
                                @endif
                            </div>

                            @if ($tanggalRelokasi || $alasanRelokasi)
                                <div class="row mb-3">
                                    @if ($tanggalRelokasi)
                                        <div class="col-12 col-md-6">
                                            <label class="form-label text-muted">Tanggal Relokasi</label>
                                            <p class="fw-bold">{{ $tanggalRelokasi->format('d M Y H:i') }}</p>
                                        </div>
                                    @endif

                                    @if ($alasanRelokasi)
                                        <div class="col-12 col-md-6">
                                            <label class="form-label text-muted">Alasan Relokasi</label>
                                            <p>{{ $alasanRelokasi }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif

                        <hr>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted">Assigned By</label>
                                <p class="fw-bold">{{ $disposisiAssignment->assignedBy?->name ?? 'System' }}</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted">Assigned At</label>
                                <p>{{ $disposisiAssignment->assigned_at?->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        @if ($disposisiAssignment->status !== 'pending')
                            <div class="row mb-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label text-muted">Executed By</label>
                                    <p class="fw-bold">{{ $disposisiAssignment->executedBy?->name ?? '-' }}</p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label text-muted">Executed At</label>
                                    <p>{{ $disposisiAssignment->executed_at?->format('d M Y H:i') ?? '-' }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($disposisiAssignment->hasil_eksekusi)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label text-muted">Hasil Eksekusi</label>
                                    <p>{{ $disposisiAssignment->hasil_eksekusi }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Section -->
                @if ($disposisiAssignment->status === 'pending' || $disposisiAssignment->status === 'in_progress')
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-2 flex-wrap">
                                @if ($disposisiAssignment->status === 'pending')
                                    <form action="{{ route('disposisi-assignment.mark-in-progress', $disposisiAssignment) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-play-fill"></i> Mulai Eksekusi
                                        </button>
                                    </form>
                                @endif

                                @if ($disposisiAssignment->status === 'in_progress')
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#completeModal">
                                        <i class="bi bi-check-circle"></i> Selesaikan
                                    </button>
                                @endif

                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    <i class="bi bi-x-circle"></i> Batalkan
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Status Timeline -->
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Pending</h6>
                                    <p class="text-muted small">{{ $disposisiAssignment->assigned_at?->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            @if ($disposisiAssignment->status !== 'pending')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">In Progress</h6>
                                        <p class="text-muted small">Started execution</p>
                                    </div>
                                </div>
                            @endif

                            @if ($disposisiAssignment->status === 'completed')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Completed</h6>
                                        <p class="text-muted small">{{ $disposisiAssignment->executed_at?->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            @elseif ($disposisiAssignment->status === 'cancelled')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-secondary"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Cancelled</h6>
                                        <p class="text-muted small">{{ $disposisiAssignment->executed_at?->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeModalLabel">Selesaikan Eksekusi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('disposisi-assignment.mark-completed', $disposisiAssignment) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="hasil_eksekusi" class="form-label">Hasil Eksekusi <span class="text-danger">*</span></label>
                        <textarea name="hasil_eksekusi" id="hasil_eksekusi" class="form-control" rows="4" required placeholder="Jelaskan hasil eksekusi disposisi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Selesaikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Batalkan Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('disposisi-assignment.mark-cancelled', $disposisiAssignment) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="alasan" class="form-label">Alasan Pembatalan <span class="text-danger">*</span></label>
                        <textarea name="alasan" id="alasan" class="form-control" rows="4" required placeholder="Jelaskan alasan pembatalan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Batalkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        display: flex;
        margin-bottom: 30px;
    }

    .timeline-marker {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 15px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .timeline-content h6 {
        margin-bottom: 5px;
    }
</style>
@endsection
