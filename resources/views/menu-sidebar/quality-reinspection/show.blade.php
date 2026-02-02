@extends('layouts.app')

@section('title', 'Detail Quality Reinspection')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Quality Reinspection</h3>
                <p class="text-subtitle text-muted">{{ $inspection->nomor_inspeksi }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('quality-reinspection.index') }}">Quality Reinspection</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="row">
        <!-- Left Column: Main Details -->
        <div class="col-lg-8">
            <!-- Header Card -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-white mb-0">{{ $inspection->nomor_inspeksi }}</h4>
                            <small>Inspeksi: {{ $inspection->tanggal_inspeksi->format('d M Y') }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $inspection->status === 'draft' ? 'secondary' : ($inspection->status === 'inspected' ? 'success' : 'warning') }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $inspection->status)) }}
                            </span>
                            <br>
                            <span class="badge bg-{{ 
                                $inspection->severity_level === 'minor' ? 'success' : 
                                ($inspection->severity_level === 'major' ? 'warning' : 'danger') 
                            }} mt-1">
                                {{ ucfirst($inspection->severity_level) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Warehouse Verification</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>No. Verifikasi:</strong></td>
                                    <td>
                                        <a href="{{ route('warehouse-verification.show', $inspection->warehouseVerification) }}">
                                            {{ $inspection->warehouseVerification->nomor_verifikasi }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Customer:</strong></td>
                                    <td>{{ $inspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Produk:</strong></td>
                                    <td>{{ $inspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Quality Manager</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>Inspector:</strong></td>
                                    <td>{{ $inspection->qualityManager->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Inspected At:</strong></td>
                                    <td>
                                        @if($inspection->inspected_at)
                                            <span class="text-success">{{ $inspection->inspected_at->format('d M Y H:i') }}</span>
                                        @else
                                            <span class="text-muted">Belum selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $inspection->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Defect Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Informasi Defect
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Jenis Defect</label>
                            <h6>{{ $inspection->jenis_defect }}</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Quantity Defect</label>
                            <h6><span class="badge bg-danger">{{ $inspection->quantity_defect }} pcs</span></h6>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Disposisi</label>
                            <h6>
                                <span class="badge bg-{{ 
                                    $inspection->disposisi === 'rework' ? 'primary' : 
                                    ($inspection->disposisi === 'scrap' ? 'danger' : 'info') 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $inspection->disposisi)) }}
                                </span>
                            </h6>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Deskripsi Defect</label>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $inspection->deskripsi_defect }}</p>
                        </div>
                    </div>

                    @if($inspection->estimasi_biaya_rework)
                    <div>
                        <label class="text-muted small">Estimasi Biaya Rework</label>
                        <h5 class="text-danger mb-0">Rp {{ number_format($inspection->estimasi_biaya_rework, 2, ',', '.') }}</h5>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Root Cause Analysis -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title text-white mb-0">
                        <i class="bi bi-diagram-3 me-2"></i>Root Cause Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $inspection->root_cause_analysis }}</p>
                    </div>
                </div>
            </div>

            <!-- Corrective & Preventive Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-tools me-2"></i>Action Plans
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="border-start border-4 border-warning ps-3">
                                <h6 class="text-warning">
                                    <i class="bi bi-wrench me-2"></i>Corrective Action
                                </h6>
                                <p style="white-space: pre-wrap;">{{ $inspection->corrective_action }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border-start border-4 border-success ps-3">
                                <h6 class="text-success">
                                    <i class="bi bi-shield-check me-2"></i>Preventive Action
                                </h6>
                                <p style="white-space: pre-wrap;">{{ $inspection->preventive_action }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Foto Defect -->
            @if(!empty($inspection->foto_defect) && is_array($inspection->foto_defect) && count($inspection->foto_defect) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-images me-2"></i>Foto Defect
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($inspection->foto_defect as $foto)
                        <div class="col-md-4 mb-3">
                            <a href="{{ Storage::url($foto) }}" data-lightbox="defect-photos" data-title="Foto Defect - {{ $inspection->nomor_inspeksi }}">
                                <img src="{{ Storage::url($foto) }}" class="img-fluid rounded shadow-sm" style="cursor: pointer; object-fit: cover; height: 200px; width: 100%;">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Dokumen RCA -->
            @if(!empty($inspection->dokumen_rca) && is_array($inspection->dokumen_rca) && count($inspection->dokumen_rca) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Dokumen RCA
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($inspection->dokumen_rca as $dokumen)
                        <a href="{{ Storage::url($dokumen) }}" target="_blank" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-pdf text-danger fs-3 me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ basename($dokumen) }}</h6>
                                    <small class="text-muted">{{ number_format(Storage::size('public/' . $dokumen) / 1024, 2) }} KB</small>
                                </div>
                                <i class="bi bi-download text-primary"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Production Rework Link -->
            @if($inspection->productionRework)
            <div class="card mb-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-arrow-right-circle text-primary fs-2 me-3"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">Lanjut ke Production Rework</h6>
                            <small class="text-muted">{{ $inspection->productionRework->nomor_rework }}</small>
                        </div>
                        <a href="{{ route('production-rework.show', $inspection->productionRework) }}" class="btn btn-primary">
                            <i class="bi bi-eye me-2"></i>Lihat Rework
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Actions & Status -->
        <div class="col-lg-4">
            <!-- Action Buttons -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning-fill me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($inspection->status !== 'rework_completed')
                        <a href="{{ route('quality-reinspection.edit', $inspection) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit Inspeksi
                        </a>
                        @endif

                        @if($inspection->status === 'draft')
                        <form action="{{ route('quality-reinspection.update', $inspection) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="inspected">
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Tandai inspeksi sebagai selesai?')">
                                <i class="bi bi-check-circle me-2"></i>Tandai Selesai
                            </button>
                        </form>
                        @endif

                        @if($inspection->status === 'inspected' && $inspection->disposisi === 'rework' && !$inspection->productionRework)
                        <a href="{{ route('production-rework.create', ['rca' => $inspection->id]) }}" class="btn btn-primary">
                            <i class="bi bi-arrow-right me-2"></i>Kirim ke Production Rework
                        </a>
                        @endif

                        @if($inspection->status === 'inspected' && $inspection->disposisi === 'scrap')
                        <a href="{{ route('scrap-disposal.create', ['rca' => $inspection->id]) }}" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>Proses Scrap Disposal
                        </a>
                        @endif

                        <a href="{{ route('quality-reinspection.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $inspection->status === 'draft' ? 'active' : 'completed' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Draft Created</h6>
                                <small class="text-muted">{{ $inspection->created_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>

                        @if($inspection->inspected_at)
                        <div class="timeline-item {{ $inspection->status === 'inspected' ? 'active' : 'completed' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Inspected</h6>
                                <small class="text-muted">{{ $inspection->inspected_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                        @endif

                        @if($inspection->status === 'sent_to_production')
                        <div class="timeline-item active">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Sent to Production</h6>
                                <small class="text-muted">{{ $inspection->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                        @endif

                        @if($inspection->productionRework)
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Rework Created</h6>
                                <small class="text-muted">{{ $inspection->productionRework->created_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="card-title text-white mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Statistik
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Quantity NG</small>
                            <strong class="text-danger">{{ $inspection->quantity_defect }} pcs</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-danger" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Severity Level</small>
                            <strong class="text-{{ 
                                $inspection->severity_level === 'minor' ? 'success' : 
                                ($inspection->severity_level === 'major' ? 'warning' : 'danger') 
                            }}">
                                {{ ucfirst($inspection->severity_level) }}
                            </strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Disposisi</small>
                            <strong>{{ ucfirst(str_replace('_', ' ', $inspection->disposisi)) }}</strong>
                        </div>
                    </div>

                    @if($inspection->estimasi_biaya_rework)
                    <hr>
                    <div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Estimasi Biaya</small>
                            <strong class="text-danger">Rp {{ number_format($inspection->estimasi_biaya_rework, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -26px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #e0e0e0;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-item.active .timeline-marker {
    background: #ffc107;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
}

.timeline-content h6 {
    font-size: 14px;
    margin-bottom: 2px;
}

.timeline-content small {
    font-size: 12px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'albumLabel': 'Foto %1 dari %2'
});
</script>
@endpush
@endsection
