@extends('layouts.app')

@section('title', 'Approval - Finance (PPIC)')

@push('styles')
    <style>
        .section-header {
            background-color: #E7E6E6;
            padding: 10px 15px;
            font-weight: bold;
            border-left: 4px solid #4472C4;
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
                    <h3>Approval - Finance (PPIC)</h3>
                    <p class="text-subtitle text-muted">Approval Biaya Claim, Retur, dan Scrap</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Approval Finance (PPIC)</h4>
                </div>
                <div class="card-body">
                    {{-- INFO ALERT --}}
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Informasi:</strong> Finance Approval digunakan untuk APPROVE permintaan biaya yang berasal dari RCA Analysis atau Quality Inspection.
                        Jika belum ada RCA record, silakan 
                        <a href="{{ route('rca-analysis.index') }}" class="alert-link">buat di menu RCA Analysis terlebih dahulu</a>.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('ppic.approval.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- ============ HEADER GLOBAL ============ --}}
                        <div class="section-header">📋 Informasi Umum Approval</div>

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
                                    <label for="nomor_referensi" class="form-label">Pilih RCA Analysis <span class="text-danger">*</span></label>
                                    <select class="form-select" id="nomor_referensi" name="nomor_referensi" required>
                                        <option value="">-- Pilih RCA Analysis --</option>
                                        @forelse($rcaAnalyses as $rca)
                                            <option value="{{ $rca->nomor_rca }}"
                                                data-defect="{{ $rca->masterDefect?->nama_defect ?? 'N/A' }}"
                                                data-status="{{ $rca->status_rca }}">
                                                {{ $rca->nomor_rca }} - [{{ strtoupper($rca->status_rca) }}] {{ $rca->masterDefect?->nama_defect ?? 'RCA Standalone' }}
                                            </option>
                                        @empty
                                            <option disabled>Tidak ada RCA yang tersedia</option>
                                        @endforelse
                                    </select>
                                    <small class="text-muted">💡 Pilih nomor RCA dari list atau input manual</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pengaju" class="form-label">Pengaju (PPIC) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pengaju" name="pengaju" 
                                           value="{{ auth()->user()->name }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="deskripsi_pengajuan" class="form-label">Deskripsi Pengajuan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="deskripsi_pengajuan" name="deskripsi_pengajuan" 
                                              rows="3" placeholder="Jelaskan dampak biaya yang timbul..." required></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- ============ FINANCE SECTION ============ --}}
                        <div class="section-header">💰 Detail Biaya - Finance Approval</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_dampak" class="form-label">Jenis Dampak Biaya <span class="text-danger">*</span></label>
                                    <select class="form-select" id="jenis_dampak" name="jenis_dampak" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="claim">💰 Claim (Klaim Kerugian)</option>
                                        <option value="retur">↩ Retur (Pengembalian ke Supplier)</option>
                                        <option value="scrap">🗑 Scrap Cost (Biaya Pembuangan)</option>
                                        <option value="rework_cost">🔧 Rework Cost (Biaya Pengerjaan Ulang)</option>
                                        <option value="tidak_ada">➖ Tidak Ada Dampak Biaya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estimasi_biaya" class="form-label">Estimasi Biaya (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="estimasi_biaya" name="estimasi_biaya" 
                                           placeholder="Contoh: 5000000" min="0" step="1000" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asal_permohonan" class="form-label">Asal Permohonan <span class="text-danger">*</span></label>
                                    <select class="form-select" id="asal_permohonan" name="asal_permohonan" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="qc">🔍 QC Supervisor</option>
                                        <option value="warehouse">🏢 Warehouse Supervisor</option>
                                        <option value="manager">⚙ Production Manager</option>
                                        <option value="internal_ppic">📊 Internal PPIC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="referensi_permohonan" class="form-label">Referensi Permohonan</label>
                                    <input type="text" class="form-control" id="referensi_permohonan" name="referensi_permohonan" 
                                           placeholder="No dokumen asal permohonan (opsional)">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_approval" class="form-label">Status Approval <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status_approval" name="status_approval" required>
                                        <option value="pending" selected>⏳ Pending (Menunggu)</option>
                                        <option value="approved">✓ Approved (Disetujui)</option>
                                        <option value="rejected">✗ Rejected (Ditolak)</option>
                                        <option value="need_revision">⚠ Need Revision (Perlu Revisi)</option>
                                        <option value="not_applicable">➖ Not Applicable (Tidak Ada Dampak)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_approval" class="form-label">Tanggal Approval <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_approval" name="tanggal_approval" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_approver" class="form-label">Nama Finance <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_approver" name="nama_approver" 
                                           value="{{ auth()->user()->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budget_approval" class="form-label">Budget Approval <span class="text-danger">*</span></label>
                                    <select class="form-select" id="budget_approval" name="budget_approval" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="dalam_budget">✓ Dalam Budget</option>
                                        <option value="melebihi_budget">⚠ Melebihi Budget</option>
                                        <option value="perlu_persetujuan_lebih_tinggi">🔓 Perlu Persetujuan Lebih Tinggi</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="catatan" class="form-label">Catatan Finance</label>
                                    <textarea class="form-control" id="catatan" name="catatan" 
                                              rows="3" placeholder="Detail perhitungan biaya atau catatan lainnya..."></textarea>
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
                    <h4 class="card-title">Riwayat Approval Finance (PPIC)</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Approval</th>
                                    <th>Referensi</th>
                                    <th>Jenis Dampak</th>
                                    <th>Estimasi Biaya</th>
                                    <th>Approver</th>
                                    <th>Budget</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($approvals as $approval)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $approval->nomor_approval }}</strong></td>
                                        <td>
                                            @if ($approval->rcaAnalysis)
                                                <a href="{{ route('rca-analysis.show', $approval->rcaAnalysis) }}" class="badge bg-primary text-white" style="text-decoration: none;">
                                                    {{ $approval->nomor_referensi }}
                                                </a>
                                            @else
                                                <span style="color: #333;">{{ $approval->nomor_referensi }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($approval->jenis_dampak === 'claim')
                                                <span class="badge bg-info">💰 Claim</span>
                                            @elseif ($approval->jenis_dampak === 'retur')
                                                <span class="badge bg-warning">↩ Retur</span>
                                            @elseif ($approval->jenis_dampak === 'scrap')
                                                <span class="badge bg-danger">🗑 Scrap Cost</span>
                                            @elseif ($approval->jenis_dampak === 'rework_cost')
                                                <span class="badge bg-primary">🔧 Rework Cost</span>
                                            @else
                                                <span class="badge bg-secondary">➖ Tidak Ada</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($approval->estimasi_biaya, 0, ',', '.') }}</td>
                                        <td>{{ $approval->nama_approver }}</td>
                                        <td>
                                            @if ($approval->budget_approval === 'dalam_budget')
                                                <span class="badge bg-success">✓ Dalam Budget</span>
                                            @elseif ($approval->budget_approval === 'melebihi_budget')
                                                <span class="badge bg-warning">⚠ Melebihi</span>
                                            @else
                                                <span class="badge bg-info">🔓 Perlu Approval</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($approval->status_approval === 'pending')
                                                <span class="badge bg-warning">⏳ Pending</span>
                                            @elseif ($approval->status_approval === 'approved')
                                                <span class="badge bg-success">✓ Approved</span>
                                            @elseif ($approval->status_approval === 'rejected')
                                                <span class="badge bg-danger">✗ Rejected</span>
                                            @elseif ($approval->status_approval === 'need_revision')
                                                <span class="badge bg-info">⚠ Need Revision</span>
                                            @else
                                                <span class="badge bg-secondary">➖ Not Applicable</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('ppic.approval.show', $approval) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('ppic.approval.edit', $approval) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('ppic.approval.destroy', $approval) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Belum ada data approval
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
