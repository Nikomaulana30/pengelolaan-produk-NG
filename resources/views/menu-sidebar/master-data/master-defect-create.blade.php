@extends('layouts.app')

@section('title', 'Tambah Master Defect')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-exclamation-triangle"></i> Tambah Master Defect</h3>
                    <p class="text-subtitle text-muted">Definisikan jenis kerusakan dan standar penanganannya</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('master-defect.index') }}" class="btn btn-outline-secondary float-end">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Form Input Master Defect</h5>
                    <p class="text-muted small">Data defect akan digunakan oleh QC Inspection dan RCA Analysis untuk tracking Top Defect</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('master-defect.store') }}" method="POST" class="needs-validation">
                        @csrf

                        <!-- Section 1: Identifikasi Defect -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3" style="margin-top: 24px;">📌 Identifikasi Defect</h6>
                            
                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="kode_defect" class="form-label">Kode Defect <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('kode_defect') is-invalid @enderror" 
                                               id="kode_defect" name="kode_defect" placeholder="Contoh: DEF-001, CACAT-BRT"
                                               value="{{ old('kode_defect') }}" required>
                                        @error('kode_defect')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Format unik untuk identifikasi defect di lapangan</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="nama_defect" class="form-label">Nama Defect <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_defect') is-invalid @enderror" 
                                               id="nama_defect" name="nama_defect" placeholder="Contoh: Cacat Permukaan, Goresan, Penyok"
                                               value="{{ old('nama_defect') }}" required>
                                        @error('nama_defect')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Nama deskriptif untuk laporan dan analisa</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-box">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="deskripsi" class="form-label">Deskripsi Detail <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                                  id="deskripsi" name="deskripsi" rows="3" 
                                                  placeholder="Jelaskan karakteristik defect ini, cara mengidentifikasinya, dan dampaknya pada produk..."
                                                  required>{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Informasi ini membantu QC dalam pengenalan dan klasifikasi defect</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Klasifikasi & Severity -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">⚠️ Klasifikasi & Severity Level</h6>
                            
                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="criticality_level" class="form-label">Tingkat Keparahan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('criticality_level') is-invalid @enderror" 
                                                id="criticality_level" name="criticality_level" required>
                                            <option value="">-- Pilih Tingkat Keparahan --</option>
                                            <option value="minor" @selected(old('criticality_level') === 'minor')>
                                                🟢 Minor (Kecil/Kosmetik)
                                            </option>
                                            <option value="major" @selected(old('criticality_level') === 'major')>
                                                🟡 Major (Sedang/Fungsional)
                                            </option>
                                            <option value="critical" @selected(old('criticality_level') === 'critical')>
                                                🔴 Critical (Berat/Keselamatan)
                                            </option>
                                        </select>
                                        @error('criticality_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Menentukan prioritas di RCA Analysis (Top Defect)</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="sumber_masalah" class="form-label">Sumber Masalah <span class="text-danger">*</span></label>
                                        <select class="form-select @error('sumber_masalah') is-invalid @enderror" 
                                                id="sumber_masalah" name="sumber_masalah" required>
                                            <option value="">-- Pilih Sumber Masalah --</option>
                                            <option value="supplier" @selected(old('sumber_masalah') === 'supplier')>
                                                📦 Supplier (Material Baku)
                                            </option>
                                            <option value="customer" @selected(old('sumber_masalah') === 'customer')>
                                                👤 Customer (Keluhan Pelanggan)
                                            </option>
                                            <option value="proses_produksi" @selected(old('sumber_masalah') === 'proses_produksi')>
                                                🏭 Proses Produksi (Mesin/Line)
                                            </option>
                                            <option value="handling_gudang" @selected(old('sumber_masalah') === 'handling_gudang')>
                                                📍 Handling Gudang (Penyimpanan/Pengiriman)
                                            </option>
                                            <option value="lainnya" @selected(old('sumber_masalah') === 'lainnya')>
                                                ❓ Lainnya
                                            </option>
                                        </select>
                                        @error('sumber_masalah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Untuk tracking penyebab di Vendor Scorecard atau Mesin/Line</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Solusi & Tindakan Awal -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">🔧 Solusi & Tindakan Awal</h6>
                            
                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="solusi_standar" class="form-label">Solusi Standar <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('solusi_standar') is-invalid @enderror" 
                                                  id="solusi_standar" name="solusi_standar" rows="3"
                                                  placeholder="Tuliskan langkah-langkah standar untuk mengatasi defect ini. Contoh: 1. Pembersihan dengan air, 2. Pengeringan, 3. Inspeksi ulang"
                                                  required>{{ old('solusi_standar') }}</textarea>
                                        @error('solusi_standar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Quick fix atau temporary solution untuk defect ini</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="is_rework_possible" class="form-label">Dapat Dirework? <span class="text-danger">*</span></label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="radio" name="is_rework_possible" 
                                                   id="rework_yes" value="1" @checked(old('is_rework_possible') == '1')>
                                            <label class="form-check-label" for="rework_yes">
                                                ✅ Ya, dapat diperbaiki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_rework_possible" 
                                                   id="rework_no" value="0" @checked(old('is_rework_possible') == '0')>
                                            <label class="form-check-label" for="rework_no">
                                                ❌ Tidak dapat diperbaiki (Scrap)
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">Menentukan jalur disposisi (Rework vs Scrap)</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="radio" name="is_active" 
                                                   id="status_active" value="1" @checked(old('is_active', 1) == '1')>
                                            <label class="form-check-label" for="status_active">
                                                🟢 Aktif (Digunakan)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_active" 
                                                   id="status_inactive" value="0" @checked(old('is_active') == '0')>
                                            <label class="form-check-label" for="status_inactive">
                                                ⚫ Non-Aktif (Tidak Digunakan)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Informasi Teknis -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">📊 Informasi Teknis</h6>
                            
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Catatan Sistem:</strong> Defect ini akan otomatis tersedia di dropdown menu Inspeksi/QC. 
                                Setiap kali QC memilih defect ini, sistem akan mencatat dan mengagregasi data untuk laporan Top Defect di menu RCA Analysis.
                            </div>

                            <div class="form-group-box">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-muted mb-2">
                                            <i class="bi bi-diagram-3 me-2"></i>
                                            <strong>Koneksi Sistem:</strong>
                                        </p>
                                        <ul class="list-unstyled ms-4 text-muted small">
                                            <li>✓ Inspeksi/QC → Dropdown Master Defect</li>
                                            <li>✓ RCA Analysis → Perhitungan Top Defect (Pareto Chart)</li>
                                            <li>✓ Scrap/Disposal → Rekomendasi tindakan (Rework/Scrap)</li>
                                            <li>✓ Vendor Scorecard → Tracking defect per supplier (jika Sumber: Supplier)</li>
                                            <li>✓ Mesin/Line Tracking → Tracking defect per mesin (jika Sumber: Proses Produksi)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-section">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('master-defect.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="reset" class="btn btn-warning">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Simpan Master Defect
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-section-title {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-bottom: 12px;
        border-bottom: 2px solid #007bff;
        margin-bottom: 1rem;
    }

    .form-group-box {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 10px 12px;
        font-size: 14px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-check {
        padding-left: 0;
    }

    .form-check-input {
        margin-right: 8px;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-check-input {
        margin-top: 0.3rem !important;
        margin-right: 0 !important;
        margin-left: 0 !important;
        flex-shrink: 0;
    }

    .form-check-label {
        font-size: 14px;
        color: #495057;
        cursor: pointer;
        margin-bottom: 0 !important;
        margin-right: 0 !important;
        margin-left: 0 !important;
        line-height: 1;
    }

    /* Radio button styling for better visibility */
    .form-check-input[type="radio"] {
        width: 1.2em;
        height: 1.2em;
        border: 2px solid #6c757d;
        cursor: pointer;
    }

    .form-check-input[type="radio"]:checked {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
    }

    .form-check-input[type="radio"]:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    /* Dark mode form check styling */
    [data-bs-theme="dark"] .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    [data-bs-theme="dark"] .form-check-input[type="radio"] {
        background-color: #2c3142 !important;
        border-color: #3a3f51 !important;
    }

    [data-bs-theme="dark"] .form-check-input[type="radio"]:checked {
        background-color: #667eea !important;
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
    }

    [data-bs-theme="dark"] .form-check-input[type="radio"]:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    [data-bs-theme="dark"] .form-check-input {
        background-color: #2c3142 !important;
        border-color: #495057 !important;
        margin-top: 0 !important;
        margin-right: 0 !important;
        margin-left: 0 !important;
        margin-bottom: 0 !important;
        flex-shrink: 0;
        width: 1rem;
        height: 1rem;
    }

    [data-bs-theme="dark"] .form-check-input:checked {
        background-color: #667eea !important;
        border-color: #667eea !important;
    }

    [data-bs-theme="dark"] .form-check-label {
        font-size: 14px;
        color: #e4e4e7 !important;
        cursor: pointer;
        margin-bottom: 0 !important;
        margin-right: 0 !important;
        margin-left: 0 !important;
        line-height: 1;
    }

    /* Light mode form check styling */
    [data-bs-theme="light"] .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    [data-bs-theme="light"] .form-check-input {
        background-color: #fff !important;
        border-color: #dee2e6 !important;
        margin-top: 0 !important;
        margin-right: 0 !important;
        margin-left: 0 !important;
        margin-bottom: 0 !important;
        flex-shrink: 0;
        width: 1rem;
        height: 1rem;
    }

    [data-bs-theme="light"] .form-check-label {
        font-size: 14px;
        color: #212529 !important;
        cursor: pointer;
        margin-bottom: 0 !important;
        margin-right: 0 !important;
        margin-left: 0 !important;
        line-height: 1;
    }

    .alert {
        border-radius: 8px;
        border: none;
    }

    .btn {
        border-radius: 6px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
    }

    .text-danger {
        color: #dc3545;
    }

    .text-muted {
        color: #6c757d;
        font-size: 13px;
    }
</style>
@endpush
