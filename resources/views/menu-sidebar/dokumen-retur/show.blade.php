@extends('layouts.app')

@section('title', 'Detail Dokumen Retur')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Dokumen Retur</h3>
                <p class="text-subtitle text-muted">Informasi lengkap dokumen retur</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dokumen-retur.index') }}">Dokumen Retur</a></li>
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
                        <h4 class="card-title mb-0">{{ $dokumenRetur->nomor_dokumen }}</h4>
                        <div class="btn-group" role="group">
                            <a href="{{ route('dokumen-retur.index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            @if($dokumenRetur->status === 'draft')
                                <a href="{{ route('dokumen-retur.edit', $dokumenRetur) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil me-2"></i>Edit
                                </a>
                            @endif
                            <button type="button" class="btn btn-sm btn-info" onclick="window.print()">
                                <i class="bi bi-printer me-2"></i>Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Document Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="text-muted mb-2">Nomor Dokumen</p>
                                <h5>{{ $dokumenRetur->nomor_dokumen }}</h5>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-2">Status</p>
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'sent_to_warehouse' => 'warning',
                                        'received_by_warehouse' => 'success',
                                    ];
                                    $statusLabels = [
                                        'draft' => 'Draft',
                                        'sent_to_warehouse' => 'Dikirim ke Warehouse',
                                        'received_by_warehouse' => 'Diterima Warehouse',
                                    ];
                                @endphp
                                <h5><span class="badge bg-{{ $statusColors[$dokumenRetur->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$dokumenRetur->status] ?? $dokumenRetur->status }}
                                </span></h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="text-muted mb-2">Tanggal Dokumen</p>
                                <p>{{ $dokumenRetur->tanggal_dokumen?->format('d M Y H:i') ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-2">Jenis Retur</p>
                                @php
                                    $jenisLabels = [
                                        'full_return' => '📦 Full Return',
                                        'partial_return' => '📦 Partial Return',
                                        'replacement' => '🔄 Replacement',
                                        'credit_note' => '💳 Credit Note',
                                    ];
                                @endphp
                                <p>{{ $jenisLabels[$dokumenRetur->jenis_retur] ?? $dokumenRetur->jenis_retur }}</p>
                            </div>
                        </div>

                        @if($dokumenRetur->nomor_referensi)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <p class="text-muted mb-2">Nomor Referensi</p>
                                    <p>{{ $dokumenRetur->nomor_referensi }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Customer Complaint Info -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary"><i class="bi bi-link-45deg me-2"></i>Customer Complaint Terkait</h6>
                                <div class="alert alert-light border">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>No. Complaint:</strong><br>
                                            <a href="{{ route('customer-complaint.show', $dokumenRetur->customerComplaint->id) }}">
                                                {{ $dokumenRetur->customerComplaint->nomor_complaint }}
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Customer:</strong><br>
                                            {{ $dokumenRetur->customerComplaint->nama_customer }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Produk:</strong><br>
                                            {{ $dokumenRetur->customerComplaint->produk }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Quantity NG:</strong><br>
                                            {{ $dokumenRetur->customerComplaint->quantity_ng }} pcs
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary"><i class="bi bi-card-text me-2"></i>Instruksi Retur</h6>
                                <div class="alert alert-info">
                                    {{ $dokumenRetur->instruksi_retur }}
                                </div>
                            </div>
                        </div>

                        @if($dokumenRetur->catatan_tambahan)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary"><i class="bi bi-sticky me-2"></i>Catatan Tambahan</h6>
                                    <p>{{ $dokumenRetur->catatan_tambahan }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Documents -->
                        @if($dokumenRetur->dokumen_retur && count($dokumenRetur->dokumen_retur) > 0)
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-primary"><i class="bi bi-paperclip me-2"></i>Lampiran Dokumen</h6>
                                    <div class="list-group">
                                        @foreach($dokumenRetur->dokumen_retur as $index => $document)
                                            <a href="{{ Storage::url($document) }}" target="_blank" class="list-group-item list-group-item-action">
                                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                                Dokumen {{ $index + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Actions Card -->
                @if($dokumenRetur->status === 'draft')
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Actions</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dokumen-retur.send-to-warehouse', $dokumenRetur) }}" method="POST" onsubmit="return confirm('Kirim dokumen ke warehouse?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i class="bi bi-send me-2"></i>Kirim ke Warehouse
                                </button>
                            </form>
                            <a href="{{ route('dokumen-retur.edit', $dokumenRetur) }}" class="btn btn-warning w-100 mb-2">
                                <i class="bi bi-pencil me-2"></i>Edit Dokumen
                            </a>
                            <form action="{{ route('dokumen-retur.destroy', $dokumenRetur) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-trash me-2"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

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
                                    {{ $dokumenRetur->created_at->format('d M Y H:i') }}<br>
                                    oleh {{ $dokumenRetur->staffExim->name ?? 'System' }}
                                </small>
                            </li>
                            @if($dokumenRetur->tanggal_kirim)
                                <li class="mb-3">
                                    <strong>Dikirim ke Warehouse</strong><br>
                                    <small class="text-muted">{{ $dokumenRetur->tanggal_kirim->format('d M Y H:i') }}</small>
                                </li>
                            @endif
                            <li>
                                <strong>Terakhir Diupdate</strong><br>
                                <small class="text-muted">{{ $dokumenRetur->updated_at->format('d M Y H:i') }}</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
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
@endsection
