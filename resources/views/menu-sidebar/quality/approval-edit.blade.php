{{-- Include layout utama --}}
@extends('layouts.app')

{{-- Set title --}}
@section('title', 'Edit Approval QC - ' . $approval->nomor_laporan)

{{-- Styles --}}
@push('styles')
    <style>
        .form-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #4472C4;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #4472C4;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4472C4;
        }
        .readonly-field {
            background-color: #f0f0f0;
        }
    </style>
@endpush

{{-- Content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Approval QC</h3>
                    <p class="text-subtitle text-muted">Nomor Laporan: <strong>{{ $approval->nomor_laporan }}</strong></p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('quality.approval.index') }}">Quality Approval</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">✏️ Form Edit Approval QC Supervisor</h4>
                </div>
                <div class="card-body">
                    {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Validation Errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('quality.approval.update', ['approval' => $approval->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Inspection Reference Section --}}
                        <div class="form-section">
                            <div class="section-title">🔍 Referensi Inspeksi QC</div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomor_laporan" class="form-label">Nomor Laporan QC</label>
                                        <input type="text" class="form-control readonly-field" id="nomor_laporan" 
                                               value="{{ $approval->nomor_laporan }}" readonly>
                                        <small class="text-muted">Tidak dapat diubah</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product" class="form-label">Product</label>
                                        <input type="text" class="form-control readonly-field" id="product" 
                                               value="{{ $approval->product }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="part_no" class="form-label">Part No</label>
                                        <input type="text" class="form-control readonly-field" id="part_no" 
                                               value="{{ $approval->part_no }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="material" class="form-label">Material</label>
                                        <input type="text" class="form-control readonly-field" id="material" 
                                               value="{{ $approval->material }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Approval Section --}}
                        <div class="form-section">
                            <div class="section-title">✅ Data Approval</div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status_approval" class="form-label">Status Approval <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status_approval') is-invalid @enderror" 
                                                id="status_approval" name="status_approval" required>
                                            <option value="pending" {{ $approval->status_approval === 'pending' ? 'selected' : '' }}>
                                                ⏳ Pending (Menunggu)
                                            </option>
                                            <option value="approved" {{ $approval->status_approval === 'approved' ? 'selected' : '' }}>
                                                ✓ Approved (Disetujui)
                                            </option>
                                            <option value="rejected" {{ $approval->status_approval === 'rejected' ? 'selected' : '' }}>
                                                ✗ Rejected (Ditolak)
                                            </option>
                                            <option value="need_revision" {{ $approval->status_approval === 'need_revision' ? 'selected' : '' }}>
                                                ⚠ Need Revision (Perlu Revisi)
                                            </option>
                                        </select>
                                        @error('status_approval')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_approver" class="form-label">QC Supervisor <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_approver') is-invalid @enderror" 
                                               id="nama_approver" name="nama_approver" 
                                               value="{{ old('nama_approver', $approval->nama_approver) }}" 
                                               placeholder="Nama QC Supervisor" required>
                                        @error('nama_approver')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="catatan_approval" class="form-label">Catatan Approval</label>
                                        <textarea class="form-control @error('catatan_approval') is-invalid @enderror" 
                                                  id="catatan_approval" name="catatan_approval" rows="4"
                                                  placeholder="Masukkan catatan approval...">{{ old('catatan_approval', $approval->catatan_approval) }}</textarea>
                                        @error('catatan_approval')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Original Data Section (Read-only) --}}
                        <div class="form-section">
                            <div class="section-title">📝 Informasi Inspeksi Asal (Read-Only)</div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_inspeksi" class="form-label">Tanggal Inspeksi</label>
                                        <input type="text" class="form-control readonly-field" id="tanggal_inspeksi" 
                                               value="{{ $approval->tanggal_inspeksi }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="made_by" class="form-label">Dibuat Oleh</label>
                                        <input type="text" class="form-control readonly-field" id="made_by" 
                                               value="{{ $approval->made_by }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="approved_by" class="form-label">Disetujui Oleh (QC)</label>
                                        <input type="text" class="form-control readonly-field" id="approved_by" 
                                               value="{{ $approval->approved_by ?? '-' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="batch_no" class="form-label">Batch No</label>
                                        <input type="text" class="form-control readonly-field" id="batch_no" 
                                               value="{{ $approval->batch_no }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('quality.approval.show', ['approval' => $approval->id]) }}" class="btn btn-outline-secondary me-2">
                                        <i class="bi bi-x-circle"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

@endsection
