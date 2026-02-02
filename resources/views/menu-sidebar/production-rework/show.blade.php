@extends('layouts.app')

@section('title', 'Detail Production Rework')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Production Rework</h3>
                <p class="text-subtitle text-muted">{{ $rework->nomor_rework }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('production-rework.index') }}">Production Rework</a></li>
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
                        <h4 class="card-title mb-0">Informasi Production Rework</h4>
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
                                $rework->status === 'draft' ? 'secondary' : 
                                ($rework->status === 'in_progress' ? 'primary' : 
                                ($rework->status === 'completed' ? 'success' : 
                                ($rework->status === 'sent_to_warehouse' ? 'info' : 'warning'))) 
                            }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $rework->status)) }}
                            </span>
                        </div>
                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            @if($rework->status === 'draft')
                                <form action="{{ route('production-rework.start', $rework->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-play-fill me-1"></i> Start Rework
                                    </button>
                                </form>
                            @elseif($rework->status === 'in_progress')
                                <form action="{{ route('production-rework.complete', $rework->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> Complete Rework
                                    </button>
                                </form>
                            @elseif($rework->status === 'completed')
                                <form action="{{ route('production-rework.send-to-warehouse', $rework->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-box-seam me-1"></i> Lapor Barang Siap ke Warehouse
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('production-rework.edit', $rework) }}" class="btn btn-primary">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <a href="{{ route('production-rework.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                        
                        @if($rework->status === 'sent_to_warehouse')
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Status:</strong> Barang sudah dilaporkan ke Warehouse. Menunggu Staff Export/Import membuat Final Decision untuk pengiriman ulang ke customer.
                        </div>
                        @endif

                        <hr>
                        <!-- Rework Information -->
                        <h5 class="mb-3"><i class="bi bi-gear-fill me-2"></i>Detail Rework</h5>
                        
                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>Nomor Rework</strong></td>
                                <td>{{ $rework->nomor_rework }}</td>
                            </tr>
                            <tr>
                                <td><strong>Quality Reinspection</strong></td>
                                <td>
                                    <a href="{{ route('quality-reinspection.show', $rework->qualityReinspection) }}">
                                        {{ $rework->qualityReinspection->nomor_reinspeksi }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Metode Rework</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $rework->metode_rework)) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>PIC Rework</strong></td>
                                <td>{{ $rework->pic_rework ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Production Manager</strong></td>
                                <td>{{ $rework->productionManager->name ?? '-' }}</td>
                            </tr>
                        </table>

                        <hr>

                        <h5 class="mb-3"><i class="bi bi-file-text me-2"></i>Deskripsi & Instruksi</h5>
                        
                        <div class="mb-3">
                            <strong>Deskripsi Rework:</strong>
                            <p class="text-muted mt-2" style="white-space: pre-wrap;">{{ $rework->deskripsi_rework }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Instruksi Rework:</strong>
                            <p class="text-muted mt-2" style="white-space: pre-wrap;">{{ $rework->instruksi_rework }}</p>
                        </div>

                        @if($rework->catatan_proses)
                        <div class="mb-3">
                            <strong>Catatan Proses:</strong>
                            <p class="text-muted mt-2" style="white-space: pre-wrap;">{{ $rework->catatan_proses }}</p>
                        </div>
                        @endif

                        <hr>

                        <!-- Quantity Information -->
                        <h5 class="mb-3"><i class="bi bi-calculator me-2"></i>Quantity</h5>
                        
                        @if($rework->status === 'draft' || ($rework->quantity_hasil_ok === null && $rework->quantity_hasil_ng === null))
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Info:</strong> Quantity hasil OK dan NG akan diisi setelah proses rework selesai. 
                            Klik tombol <strong>Edit</strong> untuk mengisi hasil rework.
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-primary bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">
                                            <i class="bi bi-box me-1"></i>Quantity Rework
                                        </h6>
                                        <h3 class="mb-0 text-primary">{{ number_format($rework->quantity_rework, 0) }}</h3>
                                        <small class="text-muted">pcs</small>
                                        <div class="mt-2">
                                            <small class="badge bg-primary">Target</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-success bg-opacity-10 {{ $rework->quantity_hasil_ok === null ? 'opacity-50' : '' }}">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">
                                            <i class="bi bi-check-circle me-1"></i>Hasil OK
                                        </h6>
                                        <h3 class="mb-0 text-success">{{ number_format($rework->quantity_hasil_ok ?? 0, 0) }}</h3>
                                        <small class="text-muted">pcs</small>
                                        <div class="mt-2">
                                            @if($rework->quantity_hasil_ok !== null)
                                                <small class="badge bg-success">
                                                    {{ $rework->quantity_rework > 0 ? round(($rework->quantity_hasil_ok / $rework->quantity_rework) * 100, 1) : 0 }}%
                                                </small>
                                            @else
                                                <small class="badge bg-secondary">Belum diisi</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-danger bg-opacity-10 {{ $rework->quantity_hasil_ng === null ? 'opacity-50' : '' }}">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">
                                            <i class="bi bi-x-circle me-1"></i>Hasil NG
                                        </h6>
                                        <h3 class="mb-0 text-danger">{{ number_format($rework->quantity_hasil_ng ?? 0, 0) }}</h3>
                                        <small class="text-muted">pcs</small>
                                        <div class="mt-2">
                                            @if($rework->quantity_hasil_ng !== null)
                                                <small class="badge bg-danger">
                                                    {{ $rework->quantity_rework > 0 ? round(($rework->quantity_hasil_ng / $rework->quantity_rework) * 100, 1) : 0 }}%
                                                </small>
                                            @else
                                                <small class="badge bg-secondary">Belum diisi</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($rework->quantity_hasil_ok !== null || $rework->quantity_hasil_ng !== null)
                        <div class="row">
                            <div class="col-12">
                                <div class="progress" style="height: 25px;">
                                    @php
                                        $totalHasil = ($rework->quantity_hasil_ok ?? 0) + ($rework->quantity_hasil_ng ?? 0);
                                        $percentOk = $rework->quantity_rework > 0 ? (($rework->quantity_hasil_ok ?? 0) / $rework->quantity_rework) * 100 : 0;
                                        $percentNg = $rework->quantity_rework > 0 ? (($rework->quantity_hasil_ng ?? 0) / $rework->quantity_rework) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $percentOk }}%">
                                        @if($percentOk > 0) OK: {{ round($percentOk, 1) }}% @endif
                                    </div>
                                    <div class="progress-bar bg-danger" style="width: {{ $percentNg }}%">
                                        @if($percentNg > 0) NG: {{ round($percentNg, 1) }}% @endif
                                    </div>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    Total hasil: {{ number_format($totalHasil, 0) }} / {{ number_format($rework->quantity_rework, 0) }} pcs
                                    @if($totalHasil < $rework->quantity_rework)
                                        ({{ number_format($rework->quantity_rework - $totalHasil, 0) }} pcs belum diproses)
                                    @endif
                                </small>
                            </div>
                        </div>
                        @endif

                        <hr>

                        <!-- Cost & Time Information -->
                        <h5 class="mb-3"><i class="bi bi-cash me-2"></i>Biaya & Waktu</h5>
                        
                        @if($rework->actual_biaya === null && $rework->actual_waktu_hari === null)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Info:</strong> Actual biaya dan waktu akan diisi setelah proses rework selesai. 
                            Klik tombol <strong>Edit</strong> untuk update data actual.
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <i class="bi bi-currency-dollar me-2"></i>Biaya Rework
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm mb-0">
                                            <tr>
                                                <td><i class="bi bi-calculator text-primary me-1"></i><strong>Estimasi</strong></td>
                                                <td class="text-end">Rp {{ number_format($rework->estimasi_biaya, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr class="{{ $rework->actual_biaya === null ? 'opacity-50' : '' }}">
                                                <td>
                                                    <i class="bi bi-receipt text-success me-1"></i><strong>Actual</strong>
                                                    @if($rework->actual_biaya === null)
                                                        <span class="badge bg-secondary badge-sm ms-1">Belum diisi</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if($rework->actual_biaya)
                                                        <span class="{{ $rework->actual_biaya > $rework->estimasi_biaya ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                                            Rp {{ number_format($rework->actual_biaya, 0, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($rework->actual_biaya)
                                            <tr class="table-light">
                                                <td><i class="bi bi-graph-{{ $rework->actual_biaya > $rework->estimasi_biaya ? 'up' : 'down' }} me-1"></i><strong>Selisih</strong></td>
                                                <td class="text-end">
                                                    <span class="{{ $rework->actual_biaya > $rework->estimasi_biaya ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                                        {{ $rework->actual_biaya > $rework->estimasi_biaya ? '+' : '' }}
                                                        Rp {{ number_format($rework->actual_biaya - $rework->estimasi_biaya, 0, ',', '.') }}
                                                        @php
                                                            $biayaVariance = $rework->estimasi_biaya > 0 ? (($rework->actual_biaya - $rework->estimasi_biaya) / $rework->estimasi_biaya) * 100 : 0;
                                                        @endphp
                                                        <small>({{ round($biayaVariance, 1) }}%)</small>
                                                    </span>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <i class="bi bi-clock-history me-2"></i>Waktu Rework
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm mb-0">
                                            <tr>
                                                <td><i class="bi bi-calendar-check text-primary me-1"></i><strong>Estimasi</strong></td>
                                                <td class="text-end">{{ $rework->estimasi_waktu_hari }} hari</td>
                                            </tr>
                                            <tr class="{{ $rework->actual_waktu_hari === null ? 'opacity-50' : '' }}">
                                                <td>
                                                    <i class="bi bi-calendar2-check text-success me-1"></i><strong>Actual</strong>
                                                    @if($rework->actual_waktu_hari === null)
                                                        <span class="badge bg-secondary badge-sm ms-1">Belum diisi</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if($rework->actual_waktu_hari)
                                                        <span class="{{ $rework->actual_waktu_hari > $rework->estimasi_waktu_hari ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                                            {{ $rework->actual_waktu_hari }} hari
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($rework->actual_waktu_hari)
                                            <tr class="table-light">
                                                <td><i class="bi bi-graph-{{ $rework->actual_waktu_hari > $rework->estimasi_waktu_hari ? 'up' : 'down' }} me-1"></i><strong>Selisih</strong></td>
                                                <td class="text-end">
                                                    <span class="{{ $rework->actual_waktu_hari > $rework->estimasi_waktu_hari ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                                        {{ $rework->actual_waktu_hari > $rework->estimasi_waktu_hari ? '+' : '' }}
                                                        {{ $rework->actual_waktu_hari - $rework->estimasi_waktu_hari }} hari
                                                        @php
                                                            $waktuVariance = $rework->estimasi_waktu_hari > 0 ? (($rework->actual_waktu_hari - $rework->estimasi_waktu_hari) / $rework->estimasi_waktu_hari) * 100 : 0;
                                                        @endphp
                                                        <small>({{ round($waktuVariance, 1) }}%)</small>
                                                    </span>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Dokumen Proses -->
                        @if($rework->dokumen_proses && count($rework->dokumen_proses) > 0)
                        <h5 class="mb-3"><i class="bi bi-file-earmark-text me-2"></i>Dokumen Proses</h5>
                        
                        <div class="row">
                            @foreach($rework->dokumen_proses as $doc)
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
                        @endif

                        <hr>

                        <!-- Timeline -->
                        <h5 class="mb-3"><i class="bi bi-calendar-event me-2"></i>Timeline</h5>
                        
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Dibuat:</strong>
                                <small class="text-muted">{{ $rework->created_at->format('d M Y H:i') }}</small>
                            </li>
                            @if($rework->tanggal_mulai_rework)
                            <li class="mb-2">
                                <strong>Mulai Rework:</strong>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($rework->tanggal_mulai_rework)->format('d M Y') }}</small>
                            </li>
                            @endif
                            @if($rework->tanggal_selesai_rework)
                            <li class="mb-2">
                                <strong>Selesai Rework:</strong>
                                <small class="text-success">{{ \Carbon\Carbon::parse($rework->tanggal_selesai_rework)->format('d M Y') }}</small>
                            </li>
                            @endif
                            <li>
                                <strong>Terakhir Diupdate:</strong>
                                <small class="text-muted">{{ $rework->updated_at->format('d M Y H:i') }}</small>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                @if($rework->status !== 'completed' && $rework->status !== 'sent_to_quality_check')
                <div class="card no-print">
                    <div class="card-body">
                        <h5 class="card-title">Actions</h5>
                        <div class="d-flex gap-2">
                            @if($rework->status === 'draft')
                            <form action="{{ route('production-rework.start', $rework) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Start rework process?')">
                                    <i class="bi bi-play-fill me-1"></i>Start Rework
                                </button>
                            </form>
                            @endif

                            @if($rework->status === 'in_progress')
                            <form action="{{ route('production-rework.complete', $rework) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success" onclick="return confirm('Mark rework as completed?')">
                                    <i class="bi bi-check-circle me-1"></i>Complete Rework
                                </button>
                            </form>
                            @endif

                            @if($rework->status === 'completed')
                            <form action="{{ route('production-rework.send-to-quality-check', $rework) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-info" onclick="return confirm('Send to Final Quality Check?')">
                                    <i class="bi bi-send me-1"></i>Send to Quality Check
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Customer & Product Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-building me-2"></i>Customer & Produk</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Customer</small>
                            <p class="mb-0"><strong>{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</strong></p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Produk</small>
                            <p class="mb-0"><strong>{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</strong></p>
                        </div>
                        <div>
                            <small class="text-muted">Complaint</small>
                            <p class="mb-0">{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->deskripsi_complaint }}</p>
                        </div>
                    </div>
                </div>

                <!-- RCA Reference -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-search me-2"></i>RCA Reference</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Root Cause Analysis</small>
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $rework->qualityReinspection->root_cause_analysis }}</p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Corrective Action</small>
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $rework->qualityReinspection->corrective_action }}</p>
                        </div>
                        <div>
                            <small class="text-muted">Preventive Action</small>
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $rework->qualityReinspection->preventive_action }}</p>
                        </div>
                    </div>
                </div>

                <!-- Final Quality Check -->
                @if($rework->finalQualityCheck)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-check-circle me-2"></i>Final Quality Check</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>No. QC:</strong><br>
                            <a href="{{ route('final-quality-check.show', $rework->finalQualityCheck) }}">
                                {{ $rework->finalQualityCheck->nomor_qc }}
                            </a>
                        </p>
                        <p class="mb-2">
                            <strong>Status:</strong><br>
                            <span class="badge bg-{{ $rework->finalQualityCheck->status === 'approved' ? 'success' : 'warning' }}">
                                {{ ucfirst($rework->finalQualityCheck->status) }}
                            </span>
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Print Styles -->
<style media="print">
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
    .page-title .col-md-6:last-child {
        display: none !important;
    }
    
    /* Page setup */
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
    
    /* Card layout with columns */
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
    
    .col-md-4, .col-lg-4 {
        width: 31% !important;
        float: left;
        margin-right: 2%;
        page-break-inside: avoid;
    }
    
    .col-md-12 {
        width: 100% !important;
        page-break-inside: avoid;
    }
    
    /* Card styling for print */
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
    
    /* Header styling */
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
    
    /* Badge colors */
    .badge {
        border: 1px solid #000;
        padding: 2px 6px;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    /* Table styling */
    table {
        width: 100%;
        border-collapse: collapse;
        page-break-inside: avoid;
    }
    
    table th,
    table td {
        border: 1px solid #ddd;
        padding: 6px;
        font-size: 10pt;
    }
    
    /* Alert boxes */
    .alert {
        border: 1px solid #ddd !important;
        padding: 8px !important;
        page-break-inside: avoid;
    }
    
    /* Progress bars */
    .progress {
        border: 1px solid #ddd;
        height: 15px !important;
    }
    
    .progress-bar {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    /* Images */
    img {
        max-width: 100%;
        page-break-inside: avoid;
    }
    
    /* Force page breaks */
    .page-break-before {
        page-break-before: always;
    }
    
    .page-break-after {
        page-break-after: always;
    }
    
    /* Avoid page breaks */
    .no-page-break {
        page-break-inside: avoid;
    }
}
</style>

@endsection
