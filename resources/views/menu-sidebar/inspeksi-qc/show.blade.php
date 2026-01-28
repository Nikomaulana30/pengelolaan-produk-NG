{{-- Include layout utama --}}
@extends('layouts.app')

{{-- Set title --}}
@section('title', 'Detail Inspeksi QC - ' . $inspection->nomor_laporan)

{{-- Styles --}}
@push('styles')
    <style>
        .detail-card {
            border-left: 4px solid #4472C4;
            background-color: #f9f9f9;
        }
        .detail-row {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
        }
        .detail-item {
            flex: 1;
            min-width: 250px;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #212529;
            font-size: 1rem;
            word-wrap: break-word;
        }
        .section-title {
            background-color: #4472C4;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-top: 20px;
            margin-bottom: 15px;
            font-weight: 600;
        }
    </style>
@endpush

{{-- Content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Inspeksi QC</h3>
                    <p class="text-subtitle text-muted">Nomor Laporan: <strong>{{ $inspection->nomor_laporan }}</strong></p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('inspeksi-qc.index') }}">Inspeksi QC</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card detail-card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">📋 Detail Inspeksi QC</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('inspeksi-qc.edit', ['inspection' => $inspection->id]) }}" class="btn btn-warning btn-sm me-2">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('inspeksi-qc.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Header Section --}}
                    <div class="section-title">📝 Informasi Umum</div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Nomor Laporan QC</div>
                            <div class="detail-value text-primary fw-bold">{{ $inspection->nomor_laporan }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Tanggal Inspeksi</div>
                            <div class="detail-value">{{ $inspection->tanggal_inspeksi }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Waktu Input</div>
                            <div class="detail-value">{{ $inspection->created_at->format('d-m-Y H:i:s') }}</div>
                        </div>
                    </div>

                    {{-- Product Section --}}
                    <div class="section-title">🏭 Informasi Produk</div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Product</div>
                            <div class="detail-value">{{ $inspection->product }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Part No</div>
                            <div class="detail-value">{{ $inspection->part_no }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Material</div>
                            <div class="detail-value">{{ $inspection->material }}</div>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Drawing No</div>
                            <div class="detail-value">{{ $inspection->drawing_no }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Drawing Revision</div>
                            <div class="detail-value">{{ $inspection->drawing_rev ?? '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Customer</div>
                            <div class="detail-value">{{ $inspection->customer ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Batch No</div>
                            <div class="detail-value">{{ $inspection->batch_no ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- Approval Section --}}
                    <div class="section-title">✅ Approval & Petugas</div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Dibuat Oleh</div>
                            <div class="detail-value">{{ $inspection->made_by ?? '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Disetujui Oleh</div>
                            <div class="detail-value">{{ $inspection->approved_by ?? '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Input Oleh (User)</div>
                            <div class="detail-value">{{ $inspection->user->name ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- Metadata Section --}}
                    <div class="section-title">ℹ️ Metadata</div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Created At</div>
                            <div class="detail-value">{{ $inspection->created_at->format('d-m-Y H:i:s') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Updated At</div>
                            <div class="detail-value">{{ $inspection->updated_at->format('d-m-Y H:i:s') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">User ID</div>
                            <div class="detail-value">{{ $inspection->user_id }}</div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('inspeksi-qc.edit', ['inspection' => $inspection->id]) }}" class="btn btn-warning me-2">
                                    <i class="bi bi-pencil"></i> Edit Data
                                </a>
                                <form action="{{ route('inspeksi-qc.destroy', ['inspection' => $inspection->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Yakin ingin menghapus inspeksi ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                                <a href="{{ route('inspeksi-qc.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
