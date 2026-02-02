@extends('layouts.app')

@section('title', 'Approval - QC Supervisor')

@push('styles')
    <style>
        .section-header {
            background-color: #E7E6E6;
            padding: 10px 15px;
            font-weight: bold;
            border-left: 4px solid #00A8E8;
            margin-top: 20px;
            margin-bottom: 15px;
        }
    </style>
@endpush

@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Approval - QC Supervisor</h3>
                    <p class="text-subtitle text-muted">Validasi Inspeksi & Status Barang NG</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Approval QC Supervisor</h4>
                </div>
                <div class="card-body">
                    {{-- INFO ALERT --}}
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Informasi:</strong> Quality Approval digunakan untuk APPROVE inspection yang sudah dibuat. 
                        Jika belum ada inspection data, silakan 
                        <a href="{{ route('quality-reinspection.index') }}" class="alert-link">buat di menu Quality Inspection terlebih dahulu</a>.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('quality.approval.store') }}" method="POST">
                        @csrf
                        
                        {{-- ============ HEADER GLOBAL ============ --}}
                        <div class="section-header">📋 Header - Informasi Umum Approval</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_approval" class="form-label">Nomor Approval <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_approval" name="nomor_approval" 
                                           value="APV-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_pengajuan" class="form-label">Tanggal Pengajuan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan" 
                                           value="{{ date('d-m-Y H:i:s') }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_referensi" class="form-label">Nomor Referensi (Inspeksi QC) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_referensi" name="nomor_referensi" 
                                           placeholder="Contoh: QC-20251215-0001" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pengaju" class="form-label">Pengaju <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pengaju" name="pengaju" 
                                           value="{{ auth()->user()->name }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="deskripsi_pengajuan" class="form-label">Deskripsi Hasil Inspeksi <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="deskripsi_pengajuan" name="deskripsi_pengajuan" 
                                              rows="3" placeholder="Jelaskan hasil inspeksi QC..." required></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- ============ QC SUPERVISOR SECTION ============ --}}
                        <div class="section-header">✅ Validasi QC Supervisor</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_approval" class="form-label">Status Approval <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status_approval" name="status_approval" required>
                                        <option value="pending" selected>⏳ Pending (Menunggu)</option>
                                        <option value="approved">✓ Approved (Disetujui)</option>
                                        <option value="rejected">✗ Rejected (Ditolak)</option>
                                        <option value="need_revision">⚠ Need Revision (Perlu Revisi)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_approver" class="form-label">Nama QC Supervisor <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_approver" name="nama_approver" 
                                           value="{{ auth()->user()->name }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="catatan_approval" class="form-label">Catatan Approval</label>
                                    <textarea class="form-control" id="catatan_approval" name="catatan_approval" 
                                              rows="3" placeholder="Catatan hasil inspeksi dan approval..."></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- ============ TOMBOL SUBMIT ============ --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-send"></i> Submit Approval
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Form
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            {{-- ============ TABEL RIWAYAT ============ --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">📊 Riwayat Approval QC Supervisor</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr class="bg-light">
                                    <th>No</th>
                                    <th>Nomor Laporan QC</th>
                                    <th>Product</th>
                                    <th>Status Approval</th>
                                    <th>QC Supervisor</th>
                                    <th>Tanggal Approval</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvals as $approval)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $approval->nomor_laporan }}</span>
                                        </td>
                                        <td>
                                            @if($approval->masterProduk)
                                                <strong style="color: #333;">{{ $approval->masterProduk->kode_produk }}</strong><br>
                                                <small class="text-muted">{{ $approval->masterProduk->nama_produk }}</small>
                                            @else
                                                <strong style="color: #333;">{{ $approval->product ?? '-' }}</strong>
                                            @endif
                                        </td>
                                        <td>
                                            @if($approval->status_approval === 'approved')
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Approved</span>
                                            @elseif($approval->status_approval === 'rejected')
                                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Rejected</span>
                                            @elseif($approval->status_approval === 'need_revision')
                                                <span class="badge bg-warning"><i class="bi bi-exclamation-circle"></i> Need Revision</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="bi bi-clock"></i> Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $approval->nama_approver ?? '-' }}</td>
                                        <td>
                                            @if($approval->tanggal_approval)
                                                {{ \Carbon\Carbon::parse($approval->tanggal_approval)->format('d-m-Y H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('quality.approval.show', ['approval' => $approval->id]) }}" class="btn btn-sm btn-info" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('quality.approval.edit', ['approval' => $approval->id]) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Belum ada data approval
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($approvals->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $approvals->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('tanggal_approval').valueAsDate = new Date();
    </script>
@endpush
