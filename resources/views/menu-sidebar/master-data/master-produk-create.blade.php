@extends('layouts.app')

@section('title', 'Tambah Master Produk')

@section('content')
<style>
    .form-section-title {
        margin-top: 25px;
        margin-bottom: 15px;
        padding: 10px 0;
        border-bottom: 2px solid #007bff;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .form-group-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 12px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Master Produk</h5>
                    <a href="{{ route('master-produk.index') }}" class="btn btn-light btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bx bx-error"></i> Ada kesalahan:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('master-produk.store') }}" method="POST">
                        @csrf

                        <!-- Section: Basic Information -->
                        <div class="form-section-title">📦 Informasi Dasar Produk</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="kode_produk" class="form-label">Kode Produk (SKU) <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('kode_produk') is-invalid @enderror" 
                                        id="kode_produk" 
                                        name="kode_produk"
                                        placeholder="e.g., PROD-001"
                                        value="{{ old('kode_produk') }}"
                                        required>
                                    <small class="form-text text-muted">Identitas unik produk</small>
                                    @error('kode_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                                    <select 
                                        class="form-select @error('vendor_id') is-invalid @enderror" 
                                        id="vendor_id" 
                                        name="vendor_id"
                                        required>
                                        <option value="">-- Pilih Vendor --</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->kode_vendor }} - {{ $vendor->nama_vendor }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Pilih vendor supplier produk</small>
                                    @error('vendor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label for="unit" class="form-label">Unit (UoM) <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('unit') is-invalid @enderror" 
                                        id="unit" 
                                        name="unit"
                                        placeholder="e.g., Pcs, Kg"
                                        value="{{ old('unit', 'Pcs') }}"
                                        required>
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select 
                                        class="form-select @error('kategori') is-invalid @enderror" 
                                        id="kategori" 
                                        name="kategori"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="raw_material" {{ old('kategori') === 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                                        <option value="wip" {{ old('kategori') === 'wip' ? 'selected' : '' }}>WIP</option>
                                        <option value="finished_goods" {{ old('kategori') === 'finished_goods' ? 'selected' : '' }}>Finished Goods</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Pricing & Details -->
                        <div class="form-section-title">💰 Harga & Detail Teknis</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="harga" class="form-label">Harga Satuan</label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('harga') is-invalid @enderror" 
                                        id="harga" 
                                        name="harga"
                                        placeholder="0.00"
                                        value="{{ old('harga') }}"
                                        min="0" step="0.01">
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="drawing_file" class="form-label">File Drawing/Gambar</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('drawing_file') is-invalid @enderror" 
                                        id="drawing_file" 
                                        name="drawing_file"
                                        placeholder="Path atau nama file drawing"
                                        value="{{ old('drawing_file') }}">
                                    <small class="form-text text-muted">Referensi gambar teknis produk</small>
                                    @error('drawing_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Specification -->
                        <div class="form-section-title">📝 Spesifikasi</div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <label for="spesifikasi" class="form-label">Spesifikasi & Deskripsi Produk</label>
                                    <textarea 
                                        class="form-control @error('spesifikasi') is-invalid @enderror" 
                                        id="spesifikasi" 
                                        name="spesifikasi"
                                        rows="4"
                                        placeholder="Masukkan spesifikasi teknis, material, dimensi, dll...">{{ old('spesifikasi') }}</textarea>
                                    @error('spesifikasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Status -->
                        <div class="form-section-title">⚙️ Status</div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <div class="form-check">
                                        <input 
                                            type="checkbox" 
                                            class="form-check-input" 
                                            id="is_active" 
                                            name="is_active"
                                            value="1"
                                            {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Aktifkan produk ini (akan tersedia untuk penerimaan & QC)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #e3e6f0;">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-save"></i> Simpan Produk
                                    </button>
                                    <a href="{{ route('master-produk.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-back"></i> Batal
                                    </a>
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

