{{-- Comprehensive Laporan Recap with PPIC, Warehouse, QA Data --}}
@extends('layouts.app')

@section('title', 'Laporan Recap Comprehensive')

@push('styles')
<style>
    .stats-card {
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-radius: 8px;
    }
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .stats-icon {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
    }
    .stats-icon.ppic { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .stats-icon.qa { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .stats-icon.warehouse { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    
    .nav-tabs .nav-link {
        border: none;
        color: #666;
        padding: 10px 20px;
        border-radius: 8px 8px 0 0;
    }
    .nav-tabs .nav-link:hover { color: #667eea; background: #f8f9fa; }
    .nav-tabs .nav-link.active {
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .export-btn {
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .export-btn-ppic { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .export-btn-qa { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
    .export-btn-warehouse { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; }
    .export-btn-comprehensive { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; }
    .export-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); color: white; }
    
    .table thead th {
        background: #f8f9fa;
        color: #667eea;
        font-weight: 600;
        font-size: 13px;
    }
    .badge-status {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
    }
    .empty-state {
        text-align: center;
        padding: 30px;
        color: #999;
    }
    .empty-state i { font-size: 40px; color: #ddd; }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan Recap Comprehensive</h3>
                <p class="text-subtitle text-muted">Laporan terintegrasi dari PPIC, Warehouse, dan Quality Assurance</p>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <!-- Filter Section -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-funnel me-2"></i>Filter Data</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan-recap.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Filter</button>
                        <a href="{{ route('laporan-recap.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Export Buttons with Dropdown -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-download me-2"></i>Export Data</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <!-- Export PPIC Dropdown -->
                    <div class="btn-group" role="group">
                        <button type="button" class="export-btn export-btn-ppic dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-file-earmark-check me-1"></i>Export PPIC
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('laporan-recap.export.ppic', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'format' => 'excel']) }}">
                                <i class="bi bi-file-earmark-excel"></i> Excel
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('laporan-recap.export.ppic', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'format' => 'pdf']) }}">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a></li>
                        </ul>
                    </div>

                    <!-- Export QA Dropdown -->
                    <div class="btn-group" role="group">
                        <button type="button" class="export-btn export-btn-qa dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-file-earmark-check me-1"></i>Export QA
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('laporan-recap.export.qa', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'format' => 'excel']) }}">
                                <i class="bi bi-file-earmark-excel"></i> Excel
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('laporan-recap.export.qa', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'format' => 'pdf']) }}">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a></li>
                        </ul>
                    </div>

                    <!-- Export Warehouse Dropdown -->
                    <div class="btn-group" role="group">
                        <button type="button" class="export-btn export-btn-warehouse dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-file-earmark-check me-1"></i>Export Warehouse
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('laporan-recap.export.warehouse', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'format' => 'excel']) }}">
                                <i class="bi bi-file-earmark-excel"></i> Excel
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('laporan-recap.export.warehouse', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'format' => 'pdf']) }}">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a></li>
                        </ul>
                    </div>

                    <!-- Export Semua Dropdown -->
                    <div class="btn-group" role="group">
                        <button type="button" class="export-btn export-btn-comprehensive dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-file-earmark-check me-1"></i>Export Semua
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('laporan-recap.export.comprehensive', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'format' => 'excel']) }}">
                                <i class="bi bi-file-earmark-excel"></i> Excel
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('laporan-recap.export.comprehensive', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'format' => 'pdf']) }}">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Summary Statistics -->
    <section class="section">
        <div class="row">
            <!-- PPIC Stats -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon ppic me-3"><i class="bi bi-diagram-3"></i></div>
                            <div>
                                <h6 class="mb-0 text-muted small">RCA Analysis</h6>
                                <h4 class="mb-0">{{ $summary['ppic']['rca_total'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon ppic me-3"><i class="bi bi-check-circle"></i></div>
                            <div>
                                <h6 class="mb-0 text-muted small">Finance Approved</h6>
                                <h4 class="mb-0">{{ $summary['ppic']['finance_approved'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- QA Stats -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon qa me-3"><i class="bi bi-check2-square"></i></div>
                            <div>
                                <h6 class="mb-0 text-muted small">QA Inspections</h6>
                                <h4 class="mb-0">{{ $summary['qa']['inspection_total'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon qa me-3"><i class="bi bi-exclamation-circle"></i></div>
                            <div>
                                <h6 class="mb-0 text-muted small">Total NG</h6>
                                <h4 class="mb-0">{{ $summary['qa']['defect_total'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Warehouse Stats -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon warehouse me-3"><i class="bi bi-box-seam"></i></div>
                            <div>
                                <h6 class="mb-0 text-muted small">Penerimaan</h6>
                                <h4 class="mb-0">{{ $summary['warehouse']['penerimaan_total'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon warehouse me-3"><i class="bi bi-arrow-return-left"></i></div>
                            <div>
                                <h6 class="mb-0 text-muted small">Retur Barang</h6>
                                <h4 class="mb-0">{{ $summary['warehouse']['retur_total'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon warehouse me-3"><i class="bi bi-archive"></i></div>
                            <div>
                                <h6 class="mb-0 text-muted small">Penyimpanan NG</h6>
                                <h4 class="mb-0">{{ $summary['warehouse']['penyimpanan_active'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon warehouse me-3"><i class="bi bi-trash"></i></div>
                            <div>
                                <h6 class="mb-0 text-muted small">Scrap/Disposal</h6>
                                <h4 class="mb-0">{{ $summary['warehouse']['scrap_total'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Data Tables with Tabs -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-table me-2"></i>Detail Data</h5>
            </div>
            <div class="card-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#ppic-tab"><i class="bi bi-diagram-3 me-1"></i>PPIC</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#qa-tab"><i class="bi bi-check2-square me-1"></i>QA</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#warehouse-tab"><i class="bi bi-box-seam me-1"></i>Warehouse</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- PPIC Tab -->
                    <div class="tab-pane fade show active" id="ppic-tab">
                        <h6 class="mb-3"><i class="bi bi-clipboard-data me-1"></i>RCA Analysis</h6>
                        @if($rcaAnalysis->count() > 0)
                        <div class="table-responsive mb-4">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Nomor RCA</th>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Status</th>
                                        <th>Root Cause</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rcaAnalysis as $rca)
                                    <tr>
                                        <td><strong>{{ $rca->nomor_rca ?? '-' }}</strong></td>
                                        <td>{{ $rca->tanggal_analisa?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $rca->masterProduk->nama_produk ?? '-' }}</td>
                                        <td>
                                            @php
                                                $statusColor = match($rca->status_rca) {
                                                    'open' => 'danger', 'in_progress' => 'warning', 'closed' => 'success', default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">{{ ucfirst($rca->status_rca ?? '-') }}</span>
                                        </td>
                                        <td>{{ Str::limit($rca->penyebab_utama ?? $rca->deskripsi_masalah ?? '-', 25) }}</td>
                                        <td><a href="{{ route('rca-analysis.show', $rca->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state"><i class="bi bi-inbox d-block mb-2"></i><p>Tidak ada data RCA</p></div>
                        @endif

                        <h6 class="mb-3"><i class="bi bi-currency-dollar me-1"></i>Finance Approval</h6>
                        @if($financeApprovals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Nomor RCA</th>
                                        <th>Tanggal</th>
                                        <th>Biaya</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($financeApprovals as $finance)
                                    <tr>
                                        <td><strong>{{ $finance->rcaAnalysis?->nomor_rca ?? $finance->nomor_referensi ?? '-' }}</strong></td>
                                        <td>{{ $finance->tanggal_approval?->format('d/m/Y') ?? '-' }}</td>
                                        <td>Rp {{ number_format($finance->estimasi_biaya ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $statusColor = match($finance->status_approval) {
                                                    'approved' => 'success', 'rejected' => 'danger', 'pending' => 'warning', default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">{{ ucfirst($finance->status_approval ?? '-') }}</span>
                                        </td>
                                        <td><a href="{{ route('ppic.approval.show', $finance->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state"><i class="bi bi-inbox d-block mb-2"></i><p>Tidak ada data Finance Approval</p></div>
                        @endif
                    </div>

                    <!-- QA Tab -->
                    <div class="tab-pane fade" id="qa-tab">
                        <h6 class="mb-3"><i class="bi bi-clipboard-check me-1"></i>Quality Inspection</h6>
                        @if($qualityInspections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Nomor Laporan</th>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Part No</th>
                                        <th>Hasil</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($qualityInspections as $inspection)
                                    <tr>
                                        <td><strong>{{ $inspection->nomor_laporan ?? '-' }}</strong></td>
                                        <td>{{ $inspection->tanggal_inspeksi?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $inspection->product ?? '-' }}</td>
                                        <td>{{ $inspection->part_no ?? '-' }}</td>
                                        <td>
                                            @php $hasilColor = match($inspection->hasil) { 'OK' => 'success', 'NG' => 'danger', 'REWORK' => 'warning', default => 'secondary' }; @endphp
                                            <span class="badge bg-{{ $hasilColor }}">{{ $inspection->hasil ?? 'Pending' }}</span>
                                        </td>
                                        <td>
                                            @php $statusColor = match($inspection->status_approval) { 'approved' => 'success', 'rejected' => 'danger', 'pending' => 'warning', default => 'secondary' }; @endphp
                                            <span class="badge bg-{{ $statusColor }}">{{ ucfirst($inspection->status_approval ?? '-') }}</span>
                                        </td>
                                        <td><a href="{{ route('inspeksi-qc.show', $inspection->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state"><i class="bi bi-inbox d-block mb-2"></i><p>Tidak ada data Quality Inspection</p></div>
                        @endif
                    </div>

                    <!-- Warehouse Tab -->
                    <div class="tab-pane fade" id="warehouse-tab">
                        <!-- Penerimaan Barang -->
                        <h6 class="mb-3"><i class="bi bi-box-arrow-in-down me-1"></i>Penerimaan Barang</h6>
                        @if($penerimaanBarang->count() > 0)
                        <div class="table-responsive mb-4">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Nomor Dokumen</th>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Qty Baik</th>
                                        <th>Penginput</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penerimaanBarang as $penerimaan)
                                    <tr>
                                        <td><strong>{{ $penerimaan->nomor_dokumen ?? '-' }}</strong></td>
                                        <td>{{ $penerimaan->tanggal_input?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $penerimaan->nama_barang ?? '-' }}</td>
                                        <td>{{ $penerimaan->qty_baik ?? 0 }}</td>
                                        <td>{{ $penerimaan->penginput ?? '-' }}</td>
                                        <td><a href="{{ route('penerimaan-barang.show', $penerimaan->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state mb-4"><i class="bi bi-inbox d-block mb-2"></i><p>Tidak ada data Penerimaan</p></div>
                        @endif

                        <!-- Retur Barang -->
                        <h6 class="mb-3"><i class="bi bi-arrow-return-left me-1"></i>Retur Barang</h6>
                        @if($returBarang->count() > 0)
                        <div class="table-responsive mb-4">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Alasan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($returBarang as $retur)
                                    <tr>
                                        <td><strong>{{ $retur->no_retur ?? '-' }}</strong></td>
                                        <td>{{ $retur->tanggal_retur?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $retur->produk->nama_produk ?? '-' }}</td>
                                        <td>{{ $retur->jumlah_retur ?? 0 }}</td>
                                        <td>{{ Str::limit($retur->alasan_retur, 20) ?? '-' }}</td>
                                        <td><a href="{{ route('retur-barang.show', $retur->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state mb-4"><i class="bi bi-inbox d-block mb-2"></i><p>Tidak ada data Retur</p></div>
                        @endif

                        <!-- Penyimpanan NG -->
                        <h6 class="mb-3"><i class="bi bi-archive me-1"></i>Penyimpanan NG</h6>
                        @if($penyimpanan->count() > 0)
                        <div class="table-responsive mb-4">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Lokasi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penyimpanan as $simpan)
                                    <tr>
                                        <td><strong>{{ $simpan->nomor_storage ?? '-' }}</strong></td>
                                        <td>{{ $simpan->tanggal_penyimpanan?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $simpan->nama_barang ?? '-' }}</td>
                                        <td>{{ $simpan->qty_awal ?? 0 }}</td>
                                        <td>{{ $simpan->lokasi_lengkap ?? '-' }}</td>
                                        <td><a href="{{ route('penyimpanan-ng.show', $simpan->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state mb-4"><i class="bi bi-inbox d-block mb-2"></i><p>Tidak ada data Penyimpanan</p></div>
                        @endif

                        <!-- Scrap/Disposal -->
                        <h6 class="mb-3"><i class="bi bi-trash me-1"></i>Scrap/Disposal</h6>
                        @if($scrapDisposal->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Jenis</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scrapDisposal as $scrap)
                                    <tr>
                                        <td><strong>{{ $scrap->nomor_scrap ?? '-' }}</strong></td>
                                        <td>{{ $scrap->tanggal_scrap?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $scrap->nama_barang ?? '-' }}</td>
                                        <td>{{ $scrap->quantity ?? 0 }}</td>
                                        <td>{{ $scrap->metode_pembuangan ?? '-' }}</td>
                                        <td><a href="{{ route('scrap-disposal.show', $scrap->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state"><i class="bi bi-inbox d-block mb-2"></i><p>Tidak ada data Scrap/Disposal</p></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tabs
        var triggerTabList = [].slice.call(document.querySelectorAll('.nav-tabs a'));
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl);
            triggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });
    });
</script>
@endpush
