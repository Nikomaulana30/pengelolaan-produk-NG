{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Detail Scrap/Disposal - ' . $scrap->nomor_scrap)

{{-- Isi content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Scrap/Disposal</h3>
                    <p class="text-subtitle text-muted">Nomor: <strong>{{ $scrap->nomor_scrap }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Informasi Scrap/Disposal</h4>
                        <div>
                            @if ($scrap->status_approval === 'pending')
                                <a href="{{ route('scrap-disposal.edit', $scrap) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('scrap-disposal.destroy', $scrap) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('scrap-disposal.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Status Badge -->
                        <div class="mb-3">
                            <strong>Status Approval:</strong>
                            @if ($scrap->status_approval === 'pending')
                                <span class="badge bg-warning">⏳ Pending</span>
                            @elseif ($scrap->status_approval === 'approved')
                                <span class="badge bg-success">✓ Approved</span>
                            @elseif ($scrap->status_approval === 'rejected')
                                <span class="badge bg-danger">✗ Rejected</span>
                            @else
                                <span class="badge bg-info">⚠ Need Revision</span>
                            @endif
                        </div>

                        <hr>

                        <!-- Informasi Umum -->
                        <h5 class="mb-3">
                            <i class="bi bi-info-circle me-2"></i>Informasi Umum
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Nomor Scrap:</strong><br>{{ $scrap->nomor_scrap }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Nomor Referensi:</strong><br>{{ $scrap->nomor_referensi ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Tanggal Pengajuan:</strong><br>{{ $scrap->tanggal_scrap->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Petugas Pengaju:</strong><br>{{ $scrap->nama_petugas }}</p>
                            </div>
                        </div>

                        <!-- Informasi Barang -->
                        <hr>
                        <h5 class="mb-3">
                            <i class="bi bi-box me-2"></i>Informasi Barang
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Nama Barang:</strong><br>
                                    {{ $scrap->nama_barang ?? '-' }}
                                    @if ($scrap->masterProduk)
                                        <br>
                                        <a href="{{ route('master-produk.show', $scrap->masterProduk) }}" class="badge bg-info text-decoration-none" target="_blank">
                                            <i class="bi bi-box2"></i> Lihat Master Produk
                                        </a>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Quantity:</strong><br>{{ $scrap->quantity ?? '-' }} unit</p>
                            </div>
                        </div>

                        <!-- Alasan & Kondisi -->
                        <hr>
                        <h5 class="mb-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>Alasan & Kondisi
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Alasan Scrap:</strong><br>
                                    @if ($scrap->alasan_scrap === 'tidak_bisa_diperbaiki')
                                        <span class="badge bg-danger">❌ Tidak Bisa Diperbaiki</span>
                                    @elseif ($scrap->alasan_scrap === 'obsolete')
                                        <span class="badge bg-warning">📦 Obsolete</span>
                                    @elseif ($scrap->alasan_scrap === 'expired')
                                        <span class="badge bg-info">⏰ Expired</span>
                                    @elseif ($scrap->alasan_scrap === 'cacat_permanen')
                                        <span class="badge bg-danger">🔴 Cacat Permanen</span>
                                    @else
                                        <span class="badge bg-secondary">💰 Tidak Ekonomis</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Hasil Test QC:</strong><br>{{ $scrap->hasil_test_qc ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <p><strong>Deskripsi Kondisi:</strong><br>{{ $scrap->deskripsi_kondisi ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- QC Information -->
                        @if ($scrap->tanggal_test_qc || $scrap->qc_inspector)
                            <hr>
                            <h5 class="mb-3">
                                <i class="bi bi-clipboard-check me-2"></i>Informasi QC
                            </h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Tanggal Test:</strong><br>{{ $scrap->tanggal_test_qc?->format('d/m/Y') ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>QC Inspector:</strong><br>{{ $scrap->qc_inspector ?? '-' }}</p>
                                </div>
                            </div>
                            @if ($scrap->catatan_qc)
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <p><strong>Catatan QC:</strong><br>{{ $scrap->catatan_qc }}</p>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Metode Pembuangan -->
                        <hr>
                        <h5 class="mb-3">
                            <i class="bi bi-tools me-2"></i>Metode Pembuangan
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Metode:</strong><br>
                                    @if ($scrap->metode_pembuangan === 'pembakaran')
                                        <span class="badge bg-light text-dark">🔥 Pembakaran</span>
                                    @elseif ($scrap->metode_pembuangan === 'penguburan')
                                        <span class="badge bg-light text-dark">⛰️ Penguburan</span>
                                    @elseif ($scrap->metode_pembuangan === 'daur_ulang')
                                        <span class="badge bg-light text-dark">♻️ Daur Ulang</span>
                                    @elseif ($scrap->metode_pembuangan === 'penjualan_scrap')
                                        <span class="badge bg-light text-dark">💵 Penjualan Scrap</span>
                                    @else
                                        <span class="badge bg-light text-dark">📋 Lainnya</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Tanggal Rencana:</strong><br>{{ $scrap->tanggal_rencana_scrap?->format('d/m/Y') ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Pihak Pelaksana:</strong><br>{{ $scrap->pihak_pelaksana ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Estimasi Biaya:</strong><br>
                                    @if ($scrap->estimasi_biaya_pembuangan)
                                        Rp {{ number_format($scrap->estimasi_biaya_pembuangan, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Approval Information -->
                        @if ($scrap->status_approval !== 'pending')
                            <hr>
                            <h5 class="mb-3">
                                <i class="bi bi-person-check-fill me-2"></i>Approval Information
                            </h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Tanggal Approval:</strong><br>{{ $scrap->tanggal_approval?->format('d/m/Y H:i:s') ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Nama Manager:</strong><br>{{ $scrap->nama_manager ?? '-' }}</p>
                                </div>
                            </div>
                            @if ($scrap->catatan_manager)
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <p><strong>Catatan Manager:</strong><br>{{ $scrap->catatan_manager }}</p>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- File Documentation -->
                        @if ($scrap->dokumen_bukti)
                            <hr>
                            <h5 class="mb-3">
                                <i class="bi bi-camera me-2"></i>Dokumentasi
                            </h5>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <p><strong>File Dokumentasi:</strong><br>
                                        <a href="{{ asset('storage/scrap-disposals/' . $scrap->dokumen_bukti) }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="bi bi-download"></i> Download/View
                                        </a>
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Metadata -->
                        <hr>
                        <h5 class="mb-3">
                            <i class="bi bi-clock-history me-2"></i>Metadata
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Dibuat oleh:</strong><br>{{ $scrap->user?->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Dibuat pada:</strong><br>{{ $scrap->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Diperbarui pada:</strong><br>{{ $scrap->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
