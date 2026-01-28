@extends('layouts.app')

@section('title', 'Detail RCA Analysis')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-diagram-3"></i> Detail RCA Analysis</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap analisis akar penyebab masalah</p>
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('rca-analysis.edit', $rcaAnalysis) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('rca-analysis.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <!-- Status Alert -->
        <section class="section mb-3">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Nomor RCA:</strong> {{ $rcaAnalysis->nomor_rca }} | 
                <strong>Status:</strong> 
                @if ($rcaAnalysis->status_rca === 'open')
                    <span class="badge bg-danger">🔴 Open</span>
                @elseif ($rcaAnalysis->status_rca === 'in_progress')
                    <span class="badge bg-warning">🟡 In Progress</span>
                @else
                    <span class="badge bg-success">🟢 Closed</span>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </section>

        <div class="row">
            <!-- Left Column -->
            <div class="col-12 col-lg-8">
                <!-- Basic Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📋 Informasi Dasar</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">Tanggal Analisa</label>
                                    <p class="field-value">{{ $rcaAnalysis->tanggal_analisa->format('d M Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">Due Date</label>
                                    <p class="field-value">
                                        {{ $rcaAnalysis->due_date->format('d M Y') }}
                                        @if ($rcaAnalysis->isOverdue())
                                            <span class="badge bg-danger ms-2">⏰ Overdue</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">Metode RCA</label>
                                    <p class="field-value">
                                        @if ($rcaAnalysis->metode_rca === '5_why')
                                            <span class="badge bg-info" style="font-size: 12px;">5 Why Analysis</span>
                                        @elseif ($rcaAnalysis->metode_rca === 'fishbone')
                                            <span class="badge bg-primary" style="font-size: 12px;">Fishbone Diagram</span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 12px;">Kombinasi</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">Penyebab Utama (6M)</label>
                                    <p class="field-value">{{ ucfirst(str_replace('_', ' ', $rcaAnalysis->penyebab_utama)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Defect & Product Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔍 Defect & Produk</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">Defect</label>
                                    @if ($rcaAnalysis->masterDefect)
                                        <p class="field-value">
                                            <strong>{{ $rcaAnalysis->masterDefect->kode_defect }}</strong><br>
                                            {{ $rcaAnalysis->masterDefect->nama_defect }}
                                        </p>
                                    @else
                                        <p class="field-value text-muted">-</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">Criticality Level</label>
                                    <p class="field-value">
                                        @if ($rcaAnalysis->criticality_level === 'critical')
                                            <span class="badge bg-danger">🔴 Critical</span>
                                        @elseif ($rcaAnalysis->criticality_level === 'major')
                                            <span class="badge bg-warning">🟡 Major</span>
                                        @elseif ($rcaAnalysis->criticality_level === 'minor')
                                            <span class="badge bg-info">🔵 Minor</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">Produk</label>
                                    @if ($rcaAnalysis->masterProduk)
                                        <p class="field-value">
                                            <strong>{{ $rcaAnalysis->masterProduk->kode_produk }}</strong><br>
                                            {{ $rcaAnalysis->masterProduk->nama_produk }}
                                        </p>
                                    @else
                                        <p class="field-value text-muted">-</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">Sumber Masalah</label>
                                    <p class="field-value">
                                        @if ($rcaAnalysis->sumber_masalah === 'supplier')
                                            <span class="badge bg-secondary">📦 Supplier</span>
                                        @elseif ($rcaAnalysis->sumber_masalah === 'proses_produksi')
                                            <span class="badge bg-secondary">🏭 Proses Produksi</span>
                                        @elseif ($rcaAnalysis->sumber_masalah === 'handling_gudang')
                                            <span class="badge bg-secondary">📍 Handling Gudang</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Return Section -->
                @if ($rcaAnalysis->returBarang)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔗 Retur Barang Terkait</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">No. Retur</label>
                                    <p class="field-value">
                                        <a href="{{ route('retur-barang.show', $rcaAnalysis->returBarang) }}" class="text-decoration-none">
                                            <strong>{{ $rcaAnalysis->returBarang->no_retur }}</strong>
                                            <i class="bi bi-box-arrow-up-right ms-2"></i>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">Status Approval</label>
                                    <p class="field-value">
                                        @if ($rcaAnalysis->returBarang->status_approval === 'approved')
                                            <span class="badge bg-success">✓ Approved</span>
                                        @elseif ($rcaAnalysis->returBarang->status_approval === 'pending')
                                            <span class="badge bg-warning text-dark">⏳ Pending</span>
                                        @else
                                            <span class="badge bg-danger">✗ Rejected</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">Vendor</label>
                                    <p class="field-value">
                                        <strong>{{ $rcaAnalysis->returBarang->vendor->nama_vendor }}</strong><br>
                                        <small class="text-muted">
                                            {{ $rcaAnalysis->returBarang->vendor->telepon ?? '-' }} | 
                                            {{ $rcaAnalysis->returBarang->vendor->email ?? '-' }}
                                        </small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-box mb-3">
                                    <label class="field-label">Tanggal Retur</label>
                                    <p class="field-value">{{ $rcaAnalysis->returBarang->tanggal_retur->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-box mb-3">
                                    <label class="field-label">Produk</label>
                                    <p class="field-value">
                                        <strong>{{ $rcaAnalysis->returBarang->produk->kode_produk }}</strong> - 
                                        {{ $rcaAnalysis->returBarang->produk->nama_produk }}<br>
                                        <small class="text-muted">Qty: {{ $rcaAnalysis->returBarang->jumlah_retur }} {{ $rcaAnalysis->returBarang->produk->unit }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-box">
                                    <label class="field-label">Deskripsi Keluhan</label>
                                    <p class="field-value">{{ nl2br(e($rcaAnalysis->returBarang->deskripsi_keluhan)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔗 Retur Barang Terkait</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Analisis Standalone</strong> - RCA ini tidak terhubung dengan transaksi retur barang.
                            Ini bisa berupa analisis NG storage, internal process improvement, atau investigasi preventif.
                        </div>
                    </div>
                </div>
                @endif

                <!-- Analysis Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔬 Detail Analisa</h5>
                    </div>
                    <div class="card-body">
                        <div class="field-box mb-3">
                            <label class="field-label">Deskripsi Masalah</label>
                            <p class="field-value">{{ nl2br(e($rcaAnalysis->deskripsi_masalah)) }}</p>
                        </div>

                        <div class="field-box">
                            <label class="field-label">Analisa Detail (5 Why / Fishbone)</label>
                            <p class="field-value">{{ nl2br(e($rcaAnalysis->analisa_detail)) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">✅ Tindakan Perbaikan</h5>
                    </div>
                    <div class="card-body">
                        <div class="field-box mb-3">
                            <label class="field-label">Corrective Action (Tindakan Korektif)</label>
                            <p class="field-value">{{ nl2br(e($rcaAnalysis->corrective_action)) }}</p>
                        </div>

                        <div class="field-box mb-3">
                            <label class="field-label">Preventive Action (Tindakan Preventif)</label>
                            <p class="field-value">{{ nl2br(e($rcaAnalysis->preventive_action)) }}</p>
                        </div>

                        @if ($rcaAnalysis->catatan)
                        <div class="field-box">
                            <label class="field-label">Catatan Tambahan</label>
                            <p class="field-value">{{ nl2br(e($rcaAnalysis->catatan)) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-12 col-lg-4">
                <!-- PIC Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">👤 Informasi PIC</h5>
                    </div>
                    <div class="card-body">
                        <div class="field-box mb-3">
                            <label class="field-label">PIC Analisa</label>
                            <p class="field-value">
                                @if ($rcaAnalysis->pic_analisa === 'qc')
                                    <span class="badge bg-info">QC</span>
                                @elseif ($rcaAnalysis->pic_analisa === 'engineering')
                                    <span class="badge bg-primary">Engineering</span>
                                @elseif ($rcaAnalysis->pic_analisa === 'warehouse')
                                    <span class="badge bg-success">Warehouse</span>
                                @elseif ($rcaAnalysis->pic_analisa === 'production')
                                    <span class="badge bg-warning">Production</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($rcaAnalysis->pic_analisa) }}</span>
                                @endif
                            </p>
                        </div>

                        <div class="field-box">
                            <label class="field-label">Nama Analis</label>
                            <p class="field-value">{{ $rcaAnalysis->nama_analis }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📊 Status & Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="field-box mb-3">
                            <label class="field-label">Status RCA</label>
                            <p class="field-value">
                                @if ($rcaAnalysis->status_rca === 'open')
                                    <span class="badge bg-danger" style="font-size: 12px; padding: 6px 10px;">🔴 Open</span>
                                @elseif ($rcaAnalysis->status_rca === 'in_progress')
                                    <span class="badge bg-warning" style="font-size: 12px; padding: 6px 10px;">🟡 In Progress</span>
                                @else
                                    <span class="badge bg-success" style="font-size: 12px; padding: 6px 10px;">🟢 Closed</span>
                                @endif
                            </p>
                        </div>

                        <div class="field-box mb-3">
                            <label class="field-label">Dibuat</label>
                            <p class="field-value small">{{ $rcaAnalysis->created_at->format('d M Y H:i:s') }}</p>
                        </div>

                        <div class="field-box mb-3">
                            <label class="field-label">Diperbarui</label>
                            <p class="field-value small">{{ $rcaAnalysis->updated_at->format('d M Y H:i:s') }}</p>
                        </div>

                        <div class="field-box">
                            <label class="field-label">Due Date</label>
                            <p class="field-value small">
                                {{ $rcaAnalysis->due_date->format('d M Y') }}
                                @if ($rcaAnalysis->isOverdue())
                                    <span class="badge bg-danger ms-2">Overdue</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">⚙️ Aksi</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('rca-analysis.edit', $rcaAnalysis) }}" class="btn btn-warning w-100 mb-2">
                            <i class="bi bi-pencil"></i> Edit RCA
                        </a>
                        <form action="{{ route('rca-analysis.destroy', $rcaAnalysis) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus RCA ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash"></i> Hapus RCA
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .field-label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
    }

    .field-value {
        font-size: 14px;
        color: #333;
        margin-bottom: 0;
    }

    .field-box {
        padding: 12px;
        background-color: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid #007bff;
    }

    .card {
        border: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 16px;
    }

    .card-title {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-bottom: 0;
    }

    .card-body {
        padding: 16px;
    }

    .badge {
        font-size: 12px;
        font-weight: 500;
    }

    .btn {
        border-radius: 6px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
    }
</style>
@endpush
