{{-- Include layout utama --}}
@extends('layouts.app')

{{-- Set title --}}
@section('title', 'Edit Inspeksi QC - ' . $inspection->nomor_laporan)

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
    </style>
@endpush

{{-- Content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Inspeksi QC</h3>
                    <p class="text-subtitle text-muted">Nomor Laporan: <strong>{{ $inspection->nomor_laporan }}</strong></p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('quality-reinspection.index') }}">Inspeksi QC</a></li>
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
                    <h4 class="card-title">✏️ Form Edit Inspeksi QC</h4>
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

                    <form action="{{ route('inspeksi-qc.update', ['inspection' => $inspection->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Header Section --}}
                        <div class="form-section">
                            <div class="section-title">📋 Informasi Umum</div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomor_laporan" class="form-label">Nomor Laporan QC</label>
                                        <input type="text" class="form-control" id="nomor_laporan" 
                                               value="{{ $inspection->nomor_laporan }}" readonly 
                                               style="background-color: #f0f0f0;">
                                        <small class="text-muted">Nomor laporan tidak dapat diubah (auto-generated)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_inspeksi" class="form-label">Tanggal Inspeksi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tanggal_inspeksi') is-invalid @enderror" 
                                               id="tanggal_inspeksi" name="tanggal_inspeksi" 
                                               value="{{ old('tanggal_inspeksi', $inspection->tanggal_inspeksi) }}" required>
                                        @error('tanggal_inspeksi')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Product Information --}}
                        <div class="form-section">
                            <div class="section-title">🏭 Informasi Produk</div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product" class="form-label">Product <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('product') is-invalid @enderror" 
                                               id="product" name="product" placeholder="Nama Produk"
                                               value="{{ old('product', $inspection->product) }}" required>
                                        @error('product')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="part_no" class="form-label">Part No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('part_no') is-invalid @enderror" 
                                               id="part_no" name="part_no" placeholder="Contoh: COMP-001"
                                               value="{{ old('part_no', $inspection->part_no) }}" required>
                                        @error('part_no')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="material" class="form-label">Material <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('material') is-invalid @enderror" 
                                               id="material" name="material" placeholder="Contoh: Aluminum 6061"
                                               value="{{ old('material', $inspection->material) }}" required>
                                        @error('material')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="drawing_no" class="form-label">Drawing No / Revisi <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('drawing_no') is-invalid @enderror" 
                                                   id="drawing_no" name="drawing_no" placeholder="Nomor Gambar"
                                                   value="{{ old('drawing_no', $inspection->drawing_no) }}" required>
                                            <input type="text" class="form-control @error('drawing_rev') is-invalid @enderror" 
                                                   id="drawing_rev" name="drawing_rev" placeholder="Rev"
                                                   value="{{ old('drawing_rev', $inspection->drawing_rev) }}" 
                                                   style="max-width: 80px;">
                                        </div>
                                        @error('drawing_no')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                        @error('drawing_rev')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer" class="form-label">Customer <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('customer') is-invalid @enderror" 
                                               id="customer" name="customer" placeholder="Nama Customer"
                                               value="{{ old('customer', $inspection->customer) }}" required>
                                        @error('customer')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="batch_no" class="form-label">Batch No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('batch_no') is-invalid @enderror" 
                                               id="batch_no" name="batch_no" placeholder="Nomor Batch"
                                               value="{{ old('batch_no', $inspection->batch_no) }}" required>
                                        @error('batch_no')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Approval Section --}}
                        <div class="form-section">
                            <div class="section-title">✅ Approval & Petugas</div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="made_by" class="form-label">Dibuat Oleh <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('made_by') is-invalid @enderror" 
                                               id="made_by" name="made_by" placeholder="Nama Petugas"
                                               value="{{ old('made_by', $inspection->made_by) }}" required>
                                        @error('made_by')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="approved_by" class="form-label">Disetujui Oleh</label>
                                        <input type="text" class="form-control @error('approved_by') is-invalid @enderror" 
                                               id="approved_by" name="approved_by" placeholder="Nama Supervisor/Manager"
                                               value="{{ old('approved_by', $inspection->approved_by) }}">
                                        @error('approved_by')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('inspeksi-qc.show', ['inspection' => $inspection->id]) }}" class="btn btn-outline-secondary me-2">
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
