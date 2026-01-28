@extends('layouts.app')

@section('title', 'Detail Approval Finance')

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
    
    .detail-label {
        font-weight: 600;
        color: var(--bs-secondary-color, #666);
        font-size: 13px;
        margin-bottom: 5px;
    }
    
    .detail-value {
        font-size: 15px;
        color: var(--bs-body-color);
        padding: 8px 12px;
        background: var(--bs-tertiary-bg, #f8f9fa);
        border-radius: 6px;
        margin-bottom: 15px;
        border: 1px solid var(--bs-border-color, #dee2e6);
    }
    
    .section-header {
        background-color: var(--bs-secondary-bg, #E7E6E6);
        padding: 10px 15px;
        font-weight: bold;
        border-left: 4px solid #4472C4;
        margin-top: 20px;
        margin-bottom: 15px;
        color: var(--bs-body-color);
    }
    
    [data-bs-theme="dark"] .detail-label {
        color: #adb5bd;
    }
    
    [data-bs-theme="dark"] .detail-value {
        background: #2c3142;
        border-color: #495057;
    }
    
    [data-bs-theme="dark"] .section-header {
        background-color: #2c3142;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Approval Finance</h3>
                <p class="text-subtitle text-muted">{{ $approval->nomor_approval }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('ppic.approval.index') }}">Approval Finance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Informasi Approval</h4>
                <div>
                    <a href="{{ route('ppic.approval.edit', $approval) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="{{ route('ppic.approval.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="section-header">📋 Informasi Umum</div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">Nomor Approval</div>
                        <div class="detail-value">{{ $approval->nomor_approval ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Tanggal Approval</div>
                        <div class="detail-value">{{ $approval->tanggal_approval?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">Pengaju</div>
                        <div class="detail-value">{{ $approval->pengaju ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Nama Approver</div>
                        <div class="detail-value">{{ $approval->nama_approver ?? '-' }}</div>
                    </div>
                </div>

                <div class="section-header">💰 Detail Biaya</div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">Jenis Dampak</div>
                        <div class="detail-value">
                            @switch($approval->jenis_dampak)
                                @case('claim') 💰 Claim @break
                                @case('retur') ↩ Retur @break
                                @case('scrap') 🗑 Scrap @break
                                @case('rework_cost') 🔧 Rework Cost @break
                                @case('tidak_ada') ➖ Tidak Ada @break
                                @default {{ $approval->jenis_dampak ?? '-' }}
                            @endswitch
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Estimasi Biaya</div>
                        <div class="detail-value">Rp {{ number_format($approval->estimasi_biaya ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">Biaya Perbaikan</div>
                        <div class="detail-value">Rp {{ number_format($approval->biaya_perbaikan ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Asal Permohonan</div>
                        <div class="detail-value">
                            @switch($approval->asal_permohonan)
                                @case('qc') 🔍 QC Supervisor @break
                                @case('warehouse') 🏢 Warehouse @break
                                @case('manager') ⚙ Production Manager @break
                                @case('internal_ppic') 📊 Internal PPIC @break
                                @default {{ $approval->asal_permohonan ?? '-' }}
                            @endswitch
                        </div>
                    </div>
                </div>

                <div class="section-header">✅ Status Approval</div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">Status Approval</div>
                        <div class="detail-value">
                            @php
                                $statusColor = match($approval->status_approval) {
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'pending' => 'warning',
                                    'need_revision' => 'info',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $approval->status_approval ?? '-')) }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Budget Approval</div>
                        <div class="detail-value">
                            @php
                                $budgetColor = match($approval->budget_approval) {
                                    'dalam_budget' => 'success',
                                    'melebihi_budget' => 'warning',
                                    'perlu_persetujuan_lebih_tinggi' => 'info',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $budgetColor }}">{{ ucfirst(str_replace('_', ' ', $approval->budget_approval ?? '-')) }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-label">Catatan</div>
                        <div class="detail-value">{{ $approval->catatan ?? '-' }}</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-label">Deskripsi Pengajuan</div>
                        <div class="detail-value">{{ $approval->deskripsi_pengajuan ?? '-' }}</div>
                    </div>
                </div>

                @if($approval->rcaAnalysis)
                <div class="section-header">🔗 RCA Analysis Terkait</div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">Nomor RCA</div>
                        <div class="detail-value">
                            <a href="{{ route('rca-analysis.show', $approval->rcaAnalysis->id) }}">
                                {{ $approval->rcaAnalysis->nomor_rca }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Status RCA</div>
                        <div class="detail-value">
                            @php
                                $rcaColor = match($approval->rcaAnalysis->status_rca) {
                                    'open' => 'danger',
                                    'in_progress' => 'warning',
                                    'closed' => 'success',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $rcaColor }}">{{ ucfirst($approval->rcaAnalysis->status_rca) }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
