{{-- Include layout utama --}}
@extends('layouts.app')

{{-- Set title --}}
@section('title', 'Detail Approval QC - ' . $approval->nomor_laporan)

{{-- Styles --}}
@push('styles')
    <style>
        :root {
            --bs-body-bg: #fff;
            --bs-body-color: #212529;
        }
        
        [data-bs-theme="dark"] {
            --bs-body-bg: #23282f;
            --bs-body-color: #ced4da;
        }
        
        .detail-card {
            border-left: 4px solid #4472C4;
            background-color: var(--bs-tertiary-bg, #f9f9f9);
            color: var(--bs-body-color);
        }
        
        .detail-row {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: var(--bs-secondary-bg, white);
            border-radius: 5px;
            color: var(--bs-body-color);
            border: 1px solid var(--bs-border-color, #dee2e6);
        }
        
        .detail-item {
            flex: 1;
            min-width: 250px;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--bs-secondary-color, #495057);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .detail-value {
            color: var(--bs-body-color);
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
        
        [data-bs-theme="dark"] .detail-card {
            background-color: #2c3142;
        }
        
        [data-bs-theme="dark"] .detail-row {
            background-color: #2c3142;
            border-color: #495057;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-revision {
            background-color: #e7d4f5;
            color: #663399;
        }
    </style>
@endpush

{{-- Content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Approval QC</h3>
                    <p class="text-subtitle text-muted">Nomor Laporan: <strong>{{ $approval->nomor_laporan }}</strong></p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('quality.approval.index') }}">Quality Approval</a></li>
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
                            <h4 class="card-title">📋 Detail Approval QC Supervisor</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('quality.approval.edit', ['approval' => $approval->id]) }}" class="btn btn-warning btn-sm me-2">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('quality.approval.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Inspection Info Section --}}
                    <div class="section-title">🔍 Informasi Inspeksi QC</div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Nomor Laporan</div>
                            <div class="detail-value text-primary fw-bold">{{ $approval->nomor_laporan }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Product</div>
                            <div class="detail-value">{{ $approval->product }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Part No</div>
                            <div class="detail-value">{{ $approval->part_no }}</div>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Material</div>
                            <div class="detail-value">{{ $approval->material }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Drawing No (Rev)</div>
                            <div class="detail-value">{{ $approval->drawing_no }} ({{ $approval->drawing_rev ?? '-' }})</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Customer</div>
                            <div class="detail-value">{{ $approval->customer }}</div>
                        </div>
                    </div>

                    {{-- Approval Info Section --}}
                    <div class="section-title">✅ Informasi Approval</div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Status Approval</div>
                            <div class="detail-value">
                                @if($approval->status_approval === 'approved')
                                    <span class="status-badge status-approved">✓ APPROVED</span>
                                @elseif($approval->status_approval === 'rejected')
                                    <span class="status-badge status-rejected">✗ REJECTED</span>
                                @elseif($approval->status_approval === 'need_revision')
                                    <span class="status-badge status-revision">⚠ NEED REVISION</span>
                                @else
                                    <span class="status-badge status-pending">⏳ PENDING</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">QC Supervisor</div>
                            <div class="detail-value">{{ $approval->nama_approver ?? '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Tanggal Approval</div>
                            <div class="detail-value">
                                @if($approval->tanggal_approval)
                                    {{ \Carbon\Carbon::parse($approval->tanggal_approval)->format('d-m-Y H:i:s') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($approval->catatan_approval)
                        <div class="detail-row">
                            <div class="detail-item">
                                <div class="detail-label">Catatan Approval</div>
                                <div class="detail-value">
                                    <div class="alert alert-info" role="alert">
                                        {{ $approval->catatan_approval }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Original Inspection Info --}}
                    <div class="section-title">📝 Informasi Inspeksi Asal</div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Tanggal Inspeksi</div>
                            <div class="detail-value">{{ $approval->tanggal_inspeksi }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Dibuat Oleh</div>
                            <div class="detail-value">{{ $approval->made_by }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Disetujui Oleh (QC)</div>
                            <div class="detail-value">{{ $approval->approved_by ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- Metadata --}}
                    <div class="section-title">ℹ️ Metadata</div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Input Oleh (User)</div>
                            <div class="detail-value">{{ $approval->user->name ?? '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Created At</div>
                            <div class="detail-value">{{ $approval->created_at->format('d-m-Y H:i:s') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Updated At</div>
                            <div class="detail-value">{{ $approval->updated_at->format('d-m-Y H:i:s') }}</div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('quality.approval.edit', ['approval' => $approval->id]) }}" class="btn btn-warning me-2">
                                    <i class="bi bi-pencil"></i> Edit Approval
                                </a>
                                <form action="{{ route('quality.approval.destroy', ['approval' => $approval->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Yakin ingin menghapus approval ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                                <a href="{{ route('quality.approval.index') }}" class="btn btn-secondary">
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
