@extends('layouts.app')

@section('title', 'Detail Warehouse Verification')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Warehouse Verification</h3>
                <p class="text-subtitle text-muted">Informasi lengkap verifikasi warehouse</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('warehouse-verification.index') }}">Warehouse Verification</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">{{ $verification->nomor_verifikasi }}</h4>
                        <div class="btn-group" role="group">
                            <a href="{{ route('warehouse-verification.index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            @if($verification->status === 'draft')
                                <a href="{{ route('warehouse-verification.edit', $verification) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil me-2"></i>Edit
                                </a>
                            @endif
                            <button type="button" class="btn btn-sm btn-info" onclick="window.print()">
                                <i class="bi bi-printer me-2"></i>Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Verification Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="text-muted mb-2">No. Verifikasi</p>
                                <h5>{{ $verification->nomor_verifikasi }}</h5>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-2">Status</p>
                                <span class="badge bg-{{ 
                                    $verification->status === 'draft' ? 'secondary' : 
                                    ($verification->status === 'verified' ? 'success' : 'info') 
                                }} fs-6">
                                    {{ ucfirst($verification->status) }}
                                </span>
                            </div>
                        </div>

                        <hr>

                        <!-- Dokumen Retur Reference -->
                        <div class="mb-4">
                            <h5><i class="bi bi-link-45deg me-2"></i>Dokumen Retur Terkait</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong>No. Dokumen:</strong> 
                                                <a href="{{ route('dokumen-retur.show', $verification->dokumenRetur) }}">
                                                    {{ $verification->dokumenRetur->nomor_dokumen }}
                                                </a>
                                            </p>
                                            <p class="mb-2">
                                                <strong>Customer:</strong> 
                                                {{ $verification->dokumenRetur->customerComplaint->nama_customer }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong>Produk:</strong> 
                                                {{ $verification->dokumenRetur->customerComplaint->produk }}
                                            </p>
                                            <p class="mb-2">
                                                <strong>Jenis Retur:</strong> 
                                                <span class="badge bg-info">
                                                    {{ ucfirst(str_replace('_', ' ', $verification->dokumenRetur->jenis_retur)) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Penerimaan Details -->
                        <div class="mb-4">
                            <h5><i class="bi bi-calendar-check me-2"></i>Informasi Penerimaan</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Tanggal Terima</strong></td>
                                    <td>{{ $verification->tanggal_terima->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Lokasi Penyimpanan</strong></td>
                                    <td>
                                        @if($verification->lokasiGudang)
                                            <span class="badge bg-primary">{{ $verification->lokasiGudang->lokasi_lengkap }}</span>
                                            {{ $verification->lokasiGudang->nama_lokasi }}
                                            @if($verification->lokasi_penyimpanan)
                                                <br><small class="text-muted">Detail: {{ $verification->lokasi_penyimpanan }}</small>
                                            @endif
                                        @else
                                            {{ $verification->lokasi_penyimpanan ?? 'N/A' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Staff Warehouse</strong></td>
                                    <td>{{ $verification->warehouseStaff->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Quantity Details -->
                        <div class="mb-4">
                            <h5><i class="bi bi-box-seam me-2"></i>Detail Quantity</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h3 class="mb-0">{{ number_format($verification->quantity_diterima) }}</h3>
                                            <small>Qty Diterima</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body text-center">
                                            <h3 class="mb-0">{{ number_format($verification->quantity_ng_confirmed) }}</h3>
                                            <small>Qty NG Confirmed</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h3 class="mb-0">{{ number_format($verification->quantity_ok) }}</h3>
                                            <small>Qty OK</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kondisi & Catatan -->
                        <div class="mb-4">
                            <h5><i class="bi bi-clipboard-check me-2"></i>Kondisi & Catatan</h5>
                            <div class="mb-3">
                                <strong>Kondisi Fisik Barang:</strong>
                                <p class="mt-2 p-3 bg-light rounded">{{ $verification->kondisi_fisik_barang }}</p>
                            </div>
                            <div>
                                <strong>Catatan Penerimaan:</strong>
                                <p class="mt-2 p-3 bg-light rounded">{{ $verification->catatan_penerimaan }}</p>
                            </div>
                        </div>

                        <!-- Foto Barang NG -->
                        @if($verification->foto_barang_ng && count($verification->foto_barang_ng) > 0)
                            <div class="mb-4">
                                <h5><i class="bi bi-images me-2"></i>Foto Barang NG</h5>
                                <div class="row">
                                    @foreach($verification->foto_barang_ng as $foto)
                                        <div class="col-md-3 mb-3">
                                            <a href="{{ Storage::url($foto) }}" data-lightbox="barang-ng" data-title="Foto Barang NG">
                                                <img src="{{ Storage::url($foto) }}" class="img-thumbnail" alt="Foto Barang NG">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        @if($verification->status === 'verified')
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                Verifikasi telah dikonfirmasi pada {{ $verification->verified_at->format('d F Y H:i') }}
                            </div>
                        @endif

                        @if($verification->status === 'draft')
                            <div class="d-flex gap-2">
                                <form action="{{ route('warehouse-verification.verify', $verification) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi verifikasi ini?')">
                                        <i class="bi bi-check-circle me-2"></i>Verifikasi
                                    </button>
                                </form>
                                <form action="{{ route('warehouse-verification.send-to-quality', $verification) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Kirim ke Quality Reinspection?')">
                                        <i class="bi bi-send me-2"></i>Kirim ke Quality
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Timeline Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i>Timeline</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <strong>Dibuat</strong><br>
                                <small class="text-muted">
                                    {{ $verification->created_at->format('d M Y H:i') }}<br>
                                    oleh {{ $verification->warehouseStaff->name ?? 'System' }}
                                </small>
                            </li>
                            @if($verification->verified_at)
                                <li class="mb-3">
                                    <strong>Diverifikasi</strong><br>
                                    <small class="text-muted">{{ $verification->verified_at->format('d M Y H:i') }}</small>
                                </li>
                            @endif
                            <li>
                                <strong>Terakhir Diupdate</strong><br>
                                <small class="text-muted">{{ $verification->updated_at->format('d M Y H:i') }}</small>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Quality Reinspection (if exists) -->
                @if($verification->qualityReinspection)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="bi bi-search me-2"></i>Quality Reinspection</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>No. Reinspection:</strong><br>
                                <a href="{{ route('quality-reinspection.show', $verification->qualityReinspection) }}">
                                    {{ $verification->qualityReinspection->nomor_reinspeksi }}
                                </a>
                            </p>
                            <p class="mb-0">
                                <strong>Status:</strong><br>
                                <span class="badge bg-info">{{ ucfirst($verification->qualityReinspection->status) }}</span>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<style>
    @media print {
        /* Hide non-printable elements */
        .no-print, .sidebar, .navbar, nav, .breadcrumb, .btn, .btn-group, button, form { display: none !important; }
        
        @page { size: A4; margin: 15mm; }
        body { font-size: 11pt; color: #000; }
        
        /* 2-column card layout */
        .row { display: flex; flex-wrap: wrap; page-break-inside: avoid; margin-bottom: 10px; }
        .col-md-6, .col-lg-6 { width: 48% !important; float: left; margin-right: 2%; page-break-inside: avoid; }
        .col-md-4 { width: 31% !important; float: left; margin-right: 2%; page-break-inside: avoid; }
        .col-md-12 { width: 100% !important; }
        
        .card { border: 1px solid #ddd !important; box-shadow: none !important; margin-bottom: 10px; page-break-inside: avoid; background: white; }
        .card-header { background-color: #f8f9fa !important; border-bottom: 1px solid #ddd !important; padding: 8px 12px !important; font-weight: bold; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .card-body { padding: 10px 12px !important; }
        
        .page-heading { margin-bottom: 15px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        h3, h4, h5, h6 { page-break-after: avoid; margin-top: 10px; margin-bottom: 8px; }
        .badge { border: 1px solid #000; padding: 2px 6px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        table { width: 100%; border-collapse: collapse; page-break-inside: avoid; }
        table th, table td { border: 1px solid #ddd; padding: 6px; font-size: 10pt; }
        .alert { border: 1px solid #ddd !important; padding: 8px !important; page-break-inside: avoid; }
        img { max-width: 100%; page-break-inside: avoid; }
        /* Hide non-printable elements */
        #sidebar,
        .navbar,
        .page-heading,
        .breadcrumb,
        .btn-group,
        .card-footer,
        .no-print,
        body > #app > #main > #sidebar {
            display: none !important;
        }

        /* Adjust main content for print */
        #main {
            margin-left: 0 !important;
            padding: 0 !important;
        }

        .page-content {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Make cards full width */
        .col-lg-8,
        .col-lg-4 {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Remove shadows and borders for cleaner print */
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }

        /* Ensure proper page breaks */
        .card {
            page-break-inside: avoid;
        }

        /* Remove unnecessary padding */
        body {
            background: white !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endpush
@endsection
