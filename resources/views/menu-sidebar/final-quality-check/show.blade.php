@extends('layouts.app')

@section('title', 'Detail Final Quality Check')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Final Decision</h3>
                <p class="text-subtitle text-muted">{{ $qualityCheck->nomor_final_check ?? 'Final Quality Check' }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('final-quality-check.index') }}">Final Decision</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Informasi Final Quality Check</h4>
                        <div class="btn-group">
                            <button onclick="window.print()" class="btn btn-sm btn-info">
                                <i class="bi bi-printer me-1"></i>Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Status Badge -->
                        <div class="mb-3">
                            <span class="badge bg-{{ 
                                $qualityCheck->status === 'draft' ? 'secondary' : 
                                ($qualityCheck->status === 'approved' ? 'success' : 
                                ($qualityCheck->status === 'rejected' ? 'danger' : 'warning'))
                            }} fs-6">
                                {{ ucwords(str_replace('_', ' ', $qualityCheck->status)) }}
                            </span>
                            
                            @if($qualityCheck->keputusan_final)
                                <span class="badge bg-{{ 
                                    $qualityCheck->keputusan_final === 'approved_for_shipment' ? 'success' : 
                                    ($qualityCheck->keputusan_final === 'need_rework' ? 'warning' : 'danger')
                                }} fs-6 ms-2">
                                    {{ $qualityCheck->keputusan_final === 'approved_for_shipment' ? '✅ Approved for Shipment' : 
                                       ($qualityCheck->keputusan_final === 'need_rework' ? '🔄 Need Rework' : '❌ Rejected') }}
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <a href="{{ route('final-quality-check.edit', $qualityCheck) }}" class="btn btn-primary">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <a href="{{ route('final-quality-check.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                            
                            @if($qualityCheck->keputusan_final === 'approved_for_shipment' && !$qualityCheck->returnShipment)
                                <a href="{{ route('return-shipment.create') }}?final_check_id={{ $qualityCheck->id }}" class="btn btn-success">
                                    <i class="bi bi-truck me-1"></i> Proses Pengiriman
                                </a>
                            @endif
                        </div>

                        <hr>

                        <!-- Production Rework Info -->
                        <h5 class="mb-3"><i class="bi bi-box-seam me-2"></i>Production Rework Information</h5>
                        
                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>Nomor Rework</strong></td>
                                <td>
                                    <a href="{{ route('production-rework.show', $qualityCheck->productionRework) }}">
                                        {{ $qualityCheck->productionRework->nomor_rework }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Customer</strong></td>
                                <td>{{ $qualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</td>
                            </tr>
                            <tr>
                                <td><strong>Produk</strong></td>
                                <td>{{ $qualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</td>
                            </tr>
                            <tr>
                                <td><strong>Metode Rework</strong></td>
                                <td>{{ ucwords(str_replace('_', ' ', $qualityCheck->productionRework->metode_rework)) }}</td>
                            </tr>
                        </table>

                        <hr>

                        <!-- Quality Check Details -->
                        <h5 class="mb-3"><i class="bi bi-clipboard-check me-2"></i>Detail Pemeriksaan</h5>
                        
                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>Tanggal Check</strong></td>
                                <td>{{ $qualityCheck->tanggal_check ? $qualityCheck->tanggal_check->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Staff Exim</strong></td>
                                <td>{{ $qualityCheck->staffExim->name ?? '-' }}</td>
                            </tr>
                        </table>

                        <!-- Quantity Results -->
                        <h5 class="mb-3 mt-4"><i class="bi bi-calculator me-2"></i>Quantity Results</h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-primary bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">
                                            <i class="bi bi-box me-1"></i>Quantity Checked
                                        </h6>
                                        <h3 class="mb-0 text-primary">{{ number_format($qualityCheck->quantity_checked, 0) }}</h3>
                                        <small class="text-muted">pcs</small>
                                        <div class="mt-2">
                                            <small class="badge bg-primary">Total</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-success bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">
                                            <i class="bi bi-check-circle me-1"></i>Quantity Passed
                                        </h6>
                                        <h3 class="mb-0 text-success">{{ number_format($qualityCheck->quantity_passed, 0) }}</h3>
                                        <small class="text-muted">pcs</small>
                                        <div class="mt-2">
                                            <small class="badge bg-success">
                                                {{ $qualityCheck->quantity_checked > 0 ? round(($qualityCheck->quantity_passed / $qualityCheck->quantity_checked) * 100, 1) : 0 }}%
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-danger bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">
                                            <i class="bi bi-x-circle me-1"></i>Quantity Failed
                                        </h6>
                                        <h3 class="mb-0 text-danger">{{ number_format($qualityCheck->quantity_failed, 0) }}</h3>
                                        <small class="text-muted">pcs</small>
                                        <div class="mt-2">
                                            <small class="badge bg-danger">
                                                {{ $qualityCheck->quantity_checked > 0 ? round(($qualityCheck->quantity_failed / $qualityCheck->quantity_checked) * 100, 1) : 0 }}%
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="row">
                            <div class="col-12">
                                <div class="progress" style="height: 25px;">
                                    @php
                                        $percentPassed = $qualityCheck->quantity_checked > 0 ? ($qualityCheck->quantity_passed / $qualityCheck->quantity_checked) * 100 : 0;
                                        $percentFailed = $qualityCheck->quantity_checked > 0 ? ($qualityCheck->quantity_failed / $qualityCheck->quantity_checked) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $percentPassed }}%">
                                        @if($percentPassed > 0) Passed: {{ round($percentPassed, 1) }}% @endif
                                    </div>
                                    <div class="progress-bar bg-danger" style="width: {{ $percentFailed }}%">
                                        @if($percentFailed > 0) Failed: {{ round($percentFailed, 1) }}% @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Hasil Pemeriksaan -->
                        <h5 class="mb-3"><i class="bi bi-file-text me-2"></i>Hasil Pemeriksaan & Catatan</h5>
                        
                        <div class="mb-3">
                            <strong>Hasil Pemeriksaan:</strong>
                            <p class="text-muted mt-2" style="white-space: pre-wrap;">{{ $qualityCheck->hasil_pemeriksaan }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Catatan Quality:</strong>
                            <p class="text-muted mt-2" style="white-space: pre-wrap;">{{ $qualityCheck->catatan_quality }}</p>
                        </div>

                        <hr>

                        <!-- Foto Hasil Check -->
                        @if($qualityCheck->foto_hasil_check && count($qualityCheck->foto_hasil_check) > 0)
                        <h5 class="mb-3"><i class="bi bi-images me-2"></i>Foto Hasil Check</h5>
                        
                        <div class="row">
                            @foreach($qualityCheck->foto_hasil_check as $foto)
                            <div class="col-md-3 mb-3">
                                <a href="{{ Storage::url($foto) }}" target="_blank">
                                    <img src="{{ Storage::url($foto) }}" class="img-thumbnail" alt="Foto Check">
                                </a>
                            </div>
                            @endforeach
                        </div>
                        <hr>
                        @endif

                        <!-- Dokumen Quality -->
                        @if($qualityCheck->dokumen_quality && count($qualityCheck->dokumen_quality) > 0)
                        <h5 class="mb-3"><i class="bi bi-file-earmark-text me-2"></i>Dokumen Quality</h5>
                        
                        <div class="row">
                            @foreach($qualityCheck->dokumen_quality as $doc)
                            <div class="col-md-6 mb-2">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                        <a href="{{ Storage::url($doc) }}" target="_blank">
                                            {{ basename($doc) }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <hr>
                        @endif

                        <!-- Timeline -->
                        <h5 class="mb-3"><i class="bi bi-clock-history me-2"></i>Timeline</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <td width="200"><strong>Created At</strong></td>
                                    <td>{{ $qualityCheck->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated</strong></td>
                                    <td>{{ $qualityCheck->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Return Shipment Card -->
                @if($qualityCheck->returnShipment)
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-truck me-2"></i>Return Shipment</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nomor Shipment:</strong></p>
                        <p class="mb-2">
                            <a href="{{ route('return-shipment.show', $qualityCheck->returnShipment) }}">
                                {{ $qualityCheck->returnShipment->nomor_shipment }}
                            </a>
                        </p>
                        <p><strong>Status:</strong></p>
                        <span class="badge bg-{{ 
                            $qualityCheck->returnShipment->status === 'delivered' ? 'success' : 
                            ($qualityCheck->returnShipment->status === 'in_transit' ? 'primary' : 'secondary') 
                        }}">
                            {{ ucwords(str_replace('_', ' ', $qualityCheck->returnShipment->status)) }}
                        </span>
                    </div>
                </div>
                @endif

                <!-- Production Rework Summary -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-gear-fill me-2"></i>Production Rework</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nomor Rework:</strong></p>
                        <p class="mb-2">{{ $qualityCheck->productionRework->nomor_rework }}</p>
                        
                        <p><strong>Hasil Rework:</strong></p>
                        <ul class="mb-2">
                            <li class="text-success">OK: {{ $qualityCheck->productionRework->quantity_hasil_ok ?? 0 }} pcs</li>
                            <li class="text-danger">NG: {{ $qualityCheck->productionRework->quantity_hasil_ng ?? 0 }} pcs</li>
                        </ul>
                        
                        <a href="{{ route('production-rework.show', $qualityCheck->productionRework) }}" class="btn btn-sm btn-outline-info w-100">
                            <i class="bi bi-eye me-1"></i>Lihat Detail Rework
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
@media print {
    /* Hide non-printable elements */
    .no-print,
    #sidebar,
    .sidebar,
    .sidebar-wrapper,
    #main-navbar,
    .navbar,
    nav,
    header,
    .breadcrumb,
    .btn,
    .btn-group,
    button,
    form,
    .page-title .col-md-6:last-child,
    .d-flex.gap-2 {
        display: none !important;
    }
    
    @page { 
        size: A4; 
        margin: 15mm; 
    }
    
    body { 
        font-size: 11pt; 
        color: #000; 
        margin: 0;
        padding: 0;
    }
    
    #app {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    #main {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* 2-column card layout */
    .row { 
        display: flex; 
        flex-wrap: wrap; 
        page-break-inside: avoid; 
        margin-bottom: 10px; 
    }
    
    .col-md-6, .col-lg-6 { 
        width: 48% !important; 
        float: left; 
        margin-right: 2%; 
        page-break-inside: avoid; 
    }
    
    .col-md-4 { 
        width: 31% !important; 
        float: left; 
        margin-right: 2%; 
        page-break-inside: avoid; 
    }
    
    .col-md-12 { 
        width: 100% !important; 
    }
    
    .card { 
        border: 1px solid #ddd !important; 
        box-shadow: none !important; 
        margin-bottom: 10px; 
        page-break-inside: avoid; 
        background: white; 
    }
    
    .card-header { 
        background-color: #f8f9fa !important; 
        border-bottom: 1px solid #ddd !important; 
        padding: 8px 12px !important; 
        font-weight: bold; 
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact; 
    }
    
    .card-body { 
        padding: 10px 12px !important; 
    }
    
    .page-heading { 
        margin-bottom: 15px; 
        border-bottom: 2px solid #000; 
        padding-bottom: 10px; 
    }
    
    .page-title h3 {
        font-size: 18pt;
        margin: 0;
    }
    
    h3, h4, h5, h6 { 
        page-break-after: avoid; 
        margin-top: 10px; 
        margin-bottom: 8px; 
    }
    
    .badge { 
        border: 1px solid #000; 
        padding: 2px 6px; 
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact; 
    }
    
    table { 
        width: 100%; 
        border-collapse: collapse; 
        page-break-inside: avoid; 
    }
    
    table th, table td { 
        border: 1px solid #ddd; 
        padding: 6px; 
        font-size: 10pt; 
    }
    
    .alert { 
        border: 1px solid #ddd !important; 
        padding: 8px !important; 
        page-break-inside: avoid; 
    }
    
    .progress {
        border: 1px solid #ddd;
        height: 15px !important;
    }
    
    .progress-bar {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    img { 
        max-width: 100%; 
        page-break-inside: avoid; 
    }
    
    .col-lg-8 {
        width: 100% !important;
        max-width: 100% !important;
    }
}
</style>
@endsection
