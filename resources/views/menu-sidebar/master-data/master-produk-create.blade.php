@extends('layouts.app')

@section('title', 'Tambah Master Produk')

@section('content')
<style>
    .form-section-title {
        margin-top: 25px;
        margin-bottom: 15px;
        padding: 12px 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .form-section-title i {
        margin-right: 10px;
        font-size: 20px;
    }

    .form-group-box {
        background-color: #ffffff;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }

    .form-group-box:hover {
        border-color: #667eea;
        box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.15);
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .relationship-info {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-left: 4px solid #667eea;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .relationship-info h6 {
        color: #667eea;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .workflow-badge {
        display: inline-block;
        padding: 5px 12px;
        margin: 3px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
</style>

<div class="container-fluid">
    <!-- Enhanced Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Tambah Master Produk Baru</h3>
                    <p class="text-muted mb-0">Produk akan otomatis terintegrasi dengan workflow return</p>
                </div>
                <a href="{{ route('master-produk.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Relationship Info Box -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="relationship-info">
                <h6><i class="bi bi-diagram-3-fill me-2"></i>Relasi Workflow Otomatis</h6>
                <p class="mb-2 small">Produk yang dibuat akan secara otomatis dapat digunakan dalam:</p>
                <div>
                    <span class="workflow-badge bg-danger-subtle text-danger border border-danger">
                        <i class="bi bi-1-circle-fill me-1"></i>Customer Complaint
                    </span>
                    <span class="workflow-badge bg-primary-subtle text-primary border border-primary">
                        <i class="bi bi-2-circle-fill me-1"></i>Dokumen Retur
                    </span>
                    <span class="workflow-badge bg-info-subtle text-info border border-info">
                        <i class="bi bi-3-circle-fill me-1"></i>Quality Reinspection
                    </span>
                    <span class="workflow-badge bg-warning-subtle text-warning border border-warning">
                        <i class="bi bi-4-circle-fill me-1"></i>Production Rework
                    </span>
                    <span class="workflow-badge bg-success-subtle text-success border border-success">
                        <i class="bi bi-5-circle-fill me-1"></i>Final Quality Check
                    </span>
                    <span class="workflow-badge bg-dark-subtle text-dark border border-dark">
                        <i class="bi bi-6-circle-fill me-1"></i>Return Shipment
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Terdapat kesalahan pada form:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('master-produk.store') }}" method="POST">
                        @csrf

                        <!-- Section: Basic Information -->
                        <div class="form-section-title">
                            <i class="bi bi-info-circle-fill"></i>
                            <span>Informasi Dasar Produk</span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="kode_produk" class="form-label">
                                        <i class="bi bi-upc-scan me-1 text-primary"></i>
                                        Kode Produk (SKU) <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('kode_produk') is-invalid @enderror" 
                                        id="kode_produk" 
                                        name="kode_produk"
                                        placeholder="Contoh: PROD-2026-001"
                                        value="{{ old('kode_produk') }}"
                                        required>
                                    <small class="form-text text-muted">
                                        <i class="bi bi-info-circle me-1"></i>Kode unik untuk identifikasi produk
                                    </small>
                                    @error('kode_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="nama_produk" class="form-label">
                                        <i class="bi bi-box me-1 text-success"></i>
                                        Nama Produk <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('nama_produk') is-invalid @enderror" 
                                        id="nama_produk" 
                                        name="nama_produk"
                                        placeholder="Masukkan nama produk"
                                        value="{{ old('nama_produk') }}"
                                        required>
                                    @error('nama_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Vendor Relationship -->
                        <div class="form-section-title">
                            <i class="bi bi-shop"></i>
                            <span>Relasi Vendor Supplier</span>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <label for="vendor_id" class="form-label">
                                        <i class="bi bi-building me-1 text-info"></i>
                                        Pilih Vendor Supplier <span class="text-danger">*</span>
                                    </label>
                                    <select 
                                        class="form-select @error('vendor_id') is-invalid @enderror" 
                                        id="vendor_id" 
                                        name="vendor_id"
                                        required>
                                        <option value="">-- Pilih Vendor Supplier --</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" 
                                                {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}
                                                data-vendor-code="{{ $vendor->kode_vendor }}"
                                                data-vendor-contact="{{ $vendor->telepon ?? 'N/A' }}">
                                                {{ $vendor->kode_vendor }} - {{ $vendor->nama_vendor }}
                                                @if($vendor->kota) ({{ $vendor->kota }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        <i class="bi bi-link-45deg me-1"></i>
                                        Produk akan terhubung dengan vendor ini. Dapat digunakan untuk tracking return ke vendor.
                                    </small>
                                    @error('vendor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    <div class="mt-2">
                                        <a href="{{ route('master-vendor.create') }}" class="btn btn-sm btn-outline-info" target="_blank">
                                            <i class="bi bi-plus-circle me-1"></i>Tambah Vendor Baru
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Product Classification -->
                        <div class="form-section-title">
                            <i class="bi bi-tags-fill"></i>
                            <span>Klasifikasi Produk</span>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group-box">
                                    <label for="kategori" class="form-label">
                                        <i class="bi bi-bookmark me-1 text-warning"></i>
                                        Kategori Produk <span class="text-danger">*</span>
                                    </label>
                                    <select 
                                        class="form-select @error('kategori') is-invalid @enderror" 
                                        id="kategori" 
                                        name="kategori"
                                        required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="raw_material" {{ old('kategori') === 'raw_material' ? 'selected' : '' }}>
                                            <i class="bi bi-box-seam"></i> Raw Material
                                        </option>
                                        <option value="wip" {{ old('kategori') === 'wip' ? 'selected' : '' }}>
                                            <i class="bi bi-hourglass-split"></i> WIP (Work in Progress)
                                        </option>
                                        <option value="finished_goods" {{ old('kategori') === 'finished_goods' ? 'selected' : '' }}>
                                            <i class="bi bi-check2-square"></i> Finished Goods
                                        </option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-box">
                                    <label for="unit" class="form-label">
                                        <i class="bi bi-rulers me-1 text-secondary"></i>
                                        Unit (UoM) <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('unit') is-invalid @enderror" 
                                        id="unit" 
                                        name="unit"
                                        placeholder="Pcs, Kg, Box, dll"
                                        value="{{ old('unit', 'Pcs') }}"
                                        required>
                                    <small class="form-text text-muted">Satuan pengukuran</small>
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-box">
                                    <label for="harga" class="form-label">
                                        <i class="bi bi-cash-stack me-1 text-success"></i>
                                        Harga Satuan
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input 
                                            type="number" 
                                            class="form-control @error('harga') is-invalid @enderror" 
                                            id="harga" 
                                            name="harga"
                                            placeholder="0"
                                            value="{{ old('harga') }}"
                                            min="0" 
                                            step="0.01">
                                    </div>
                                    <small class="form-text text-muted">Opsional - untuk referensi harga</small>
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Technical Details -->
                        <div class="form-section-title">
                            <i class="bi bi-gear-fill"></i>
                            <span>Detail Teknis & Spesifikasi</span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="spesifikasi" class="form-label">
                                        <i class="bi bi-file-text me-1 text-primary"></i>
                                        Spesifikasi Produk
                                    </label>
                                    <textarea 
                                        class="form-control @error('spesifikasi') is-invalid @enderror" 
                                        id="spesifikasi" 
                                        name="spesifikasi"
                                        rows="5"
                                        placeholder="Contoh:
- Material: Stainless Steel 304
- Dimensi: 100x50x30 mm
- Berat: 500 gram
- Warna: Silver">{{ old('spesifikasi') }}</textarea>
                                    <small class="form-text text-muted">Detail spesifikasi untuk identifikasi produk</small>
                                    @error('spesifikasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="drawing_file" class="form-label">
                                        <i class="bi bi-file-earmark-image me-1 text-info"></i>
                                        File Drawing/Gambar Teknis
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('drawing_file') is-invalid @enderror" 
                                        id="drawing_file" 
                                        name="drawing_file"
                                        placeholder="Path atau nama file drawing (contoh: DWG-2026-001.pdf)"
                                        value="{{ old('drawing_file') }}">
                                    <small class="form-text text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Referensi file gambar teknis produk
                                    </small>
                                    @error('drawing_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Status -->
                        <div class="form-section-title">
                            <i class="bi bi-toggle-on"></i>
                            <span>Status & Aktivasi</span>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <div class="form-check form-switch">
                                        <input 
                                            type="checkbox" 
                                            class="form-check-input" 
                                            id="is_active" 
                                            name="is_active"
                                            value="1"
                                            {{ old('is_active', true) ? 'checked' : '' }}
                                            style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                        <label class="form-check-label ms-2" for="is_active" style="cursor: pointer;">
                                            <strong>Aktifkan Produk</strong>
                                            <div class="text-muted small">
                                                Produk aktif akan tersedia dalam workflow return dan dapat digunakan untuk complaint, retur, inspeksi, dan rework
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 border">
                                    <div class="text-muted small">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Pastikan semua data sudah benar sebelum menyimpan
                                    </div>
                                    <div>
                                        <a href="{{ route('master-produk.index') }}" class="btn btn-secondary me-2">
                                            <i class="bi bi-x-circle me-1"></i>Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-1"></i>Simpan Produk
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

