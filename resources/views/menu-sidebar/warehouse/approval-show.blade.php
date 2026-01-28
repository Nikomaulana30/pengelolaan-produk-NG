{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Detail Approval - ' . $approval->nomor_approval)

{{-- Isi content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Approval Warehouse & Manager</h3>
                    <p class="text-subtitle text-muted">Nomor: <strong>{{ $approval->nomor_approval }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Informasi Approval</h4>
                        <div>
                            @if ($approval->status_keseluruhan === 'pending')
                                <a href="{{ route('warehouse.approval.edit', $approval) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('warehouse.approval.destroy', $approval) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('warehouse.approval.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Overall Status Badge -->
                        <div class="mb-3">
                            <strong>Status Keseluruhan:</strong>
                            @if ($approval->status_keseluruhan === 'approved')
                                <span class="badge bg-success">✓ Approved</span>
                            @elseif ($approval->status_keseluruhan === 'rejected')
                                <span class="badge bg-danger">✗ Rejected</span>
                            @elseif ($approval->status_keseluruhan === 'need_revision')
                                <span class="badge bg-warning">⚠ Need Revision</span>
                            @else
                                <span class="badge bg-secondary">⏳ Pending</span>
                            @endif
                        </div>

                        <hr>

                        <!-- Header Information -->
                        <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Umum</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Nomor Approval:</strong><br>{{ $approval->nomor_approval }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Nomor Referensi:</strong><br>{{ $approval->nomor_referensi }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Tanggal Pengajuan:</strong><br>{{ $approval->tanggal_pengajuan->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Pengaju:</strong><br>{{ $approval->pengaju }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <p><strong>Deskripsi Pengajuan:</strong><br>{{ $approval->deskripsi_pengajuan }}</p>
                            </div>
                        </div>

                        <hr>

                        <!-- Warehouse Supervisor Approval -->
                        <h5 class="mb-3"><i class="bi bi-check-circle me-2"></i>Approval Warehouse Supervisor</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Status:</strong><br>
                                    @if ($approval->ws_status_approval === 'approved')
                                        <span class="badge bg-success">✓ Approved</span>
                                    @elseif ($approval->ws_status_approval === 'rejected')
                                        <span class="badge bg-danger">✗ Rejected</span>
                                    @elseif ($approval->ws_status_approval === 'need_revision')
                                        <span class="badge bg-warning">⚠ Need Revision</span>
                                    @else
                                        <span class="badge bg-secondary">⏳ Pending</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Tanggal Approval:</strong><br>{{ $approval->ws_tanggal_approval?->format('d/m/Y') ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Nama WS:</strong><br>{{ $approval->ws_nama_approver ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Kondisi Barang:</strong><br>
                                    @if ($approval->ws_kondisi_barang === 'aman')
                                        <span class="badge bg-success">✓ Aman</span>
                                    @elseif ($approval->ws_kondisi_barang === 'perlu_penanganan')
                                        <span class="badge bg-warning">⚠ Perlu Penanganan</span>
                                    @elseif ($approval->ws_kondisi_barang === 'tidak_layak')
                                        <span class="badge bg-danger">✗ Tidak Layak</span>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if ($approval->ws_catatan)
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <p><strong>Catatan:</strong><br>{{ $approval->ws_catatan }}</p>
                                </div>
                            </div>
                        @endif

                        <hr>

                        <!-- Production Manager Approval -->
                        <h5 class="mb-3"><i class="bi bi-check-circle me-2"></i>Approval Production Manager</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Status:</strong><br>
                                    @if ($approval->pm_status_approval === 'approved')
                                        <span class="badge bg-success">✓ Approved</span>
                                    @elseif ($approval->pm_status_approval === 'rejected')
                                        <span class="badge bg-danger">✗ Rejected</span>
                                    @elseif ($approval->pm_status_approval === 'need_revision')
                                        <span class="badge bg-warning">⚠ Need Revision</span>
                                    @else
                                        <span class="badge bg-secondary">⏳ Pending</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Tanggal Approval:</strong><br>{{ $approval->pm_tanggal_approval?->format('d/m/Y') ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Nama PM:</strong><br>{{ $approval->pm_nama_approver ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Keputusan:</strong><br>
                                    @if ($approval->pm_keputusan === 'rework')
                                        <span class="badge bg-info">⚙ Rework</span>
                                    @elseif ($approval->pm_keputusan === 'repair')
                                        <span class="badge bg-warning">🔧 Repair</span>
                                    @elseif ($approval->pm_keputusan === 'scrap')
                                        <span class="badge bg-danger">✗ Scrap</span>
                                    @elseif ($approval->pm_keputusan === 'use_as_is')
                                        <span class="badge bg-success">✓ Use As Is</span>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if ($approval->pm_catatan)
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <p><strong>Catatan:</strong><br>{{ $approval->pm_catatan }}</p>
                                </div>
                            </div>
                        @endif

                        <hr>

                        <!-- Metadata -->
                        <h5 class="mb-3"><i class="bi bi-clock-history me-2"></i>Metadata</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Dibuat oleh:</strong><br>{{ $approval->user?->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Dibuat pada:</strong><br>{{ $approval->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Diperbarui pada:</strong><br>{{ $approval->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
