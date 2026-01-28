@extends('layouts.app')

@section('title', 'Edit Master Lokasi')

@push('styles')
<style>
    /* Dark mode comprehensive styling */
    [data-bs-theme="dark"] .page-content {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .row {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .col,
    [data-bs-theme="dark"] [class*="col-"] {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .card {
        background-color: #1e1e2d !important;
        border-color: #2c3142 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .card-header {
        background-color: #2c3142 !important;
        border-color: #3a3f51 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .card-body {
        background-color: #1e1e2d !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] h1,
    [data-bs-theme="dark"] h2,
    [data-bs-theme="dark"] h3,
    [data-bs-theme="dark"] h4,
    [data-bs-theme="dark"] h5,
    [data-bs-theme="dark"] h6 {
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] p {
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .form-label {
        color: #a1a1a1 !important;
    }
    
    [data-bs-theme="dark"] .text-muted {
        color: #a1a1a1 !important;
    }
    
    [data-bs-theme="dark"] small {
        color: #a1a1a1 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-building"></i> Edit Master Lokasi</h3>
                    <p class="text-subtitle text-muted">Perbarui data lokasi penyimpanan barang</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('master-lokasi.index') }}" class="btn btn-outline-secondary float-end">
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
                    <h5 class="card-title">Form Edit Master Lokasi</h5>
                    <p class="text-muted small">Ubah data lokasi penyimpanan barang</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('master-lokasi.update', $masterLokasi) }}" method="POST" class="needs-validation">
                        @csrf
                        @method('PUT')

                        <!-- Section 1: Identifikasi Lokasi -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">📌 Identifikasi Lokasi</h6>
                            
                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="kode_lokasi" class="form-label">Kode Lokasi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('kode_lokasi') is-invalid @enderror" 
                                               id="kode_lokasi" name="kode_lokasi" 
                                               value="{{ old('kode_lokasi', $masterLokasi->kode_lokasi) }}" disabled>
                                        <small class="text-muted">Kode tidak dapat diubah (read-only)</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="nama_lokasi" class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_lokasi') is-invalid @enderror" 
                                               id="nama_lokasi" name="nama_lokasi" placeholder="Contoh: Rak A Baris 1, Area Karantina"
                                               value="{{ old('nama_lokasi', $masterLokasi->nama_lokasi) }}" required>
                                        @error('nama_lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Nama deskriptif lokasi</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Koordinat Lokasi -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">📍 Koordinat Lokasi</h6>
                            
                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="zona_gudang" class="form-label">Zona Gudang <span class="text-danger">*</span></label>
                                        <select class="form-select @error('zona_gudang') is-invalid @enderror" 
                                                id="zona_gudang" name="zona_gudang" required>
                                            <option value="">-- Pilih Zona --</option>
                                            <option value="zona_a" @selected(old('zona_gudang', $masterLokasi->zona_gudang) === 'zona_a')>Zona A</option>
                                            <option value="zona_b" @selected(old('zona_gudang', $masterLokasi->zona_gudang) === 'zona_b')>Zona B</option>
                                            <option value="zona_c" @selected(old('zona_gudang', $masterLokasi->zona_gudang) === 'zona_c')>Zona C</option>
                                            <option value="zona_d" @selected(old('zona_gudang', $masterLokasi->zona_gudang) === 'zona_d')>Zona D</option>
                                            <option value="zona_e" @selected(old('zona_gudang', $masterLokasi->zona_gudang) === 'zona_e')>Zona E</option>
                                        </select>
                                        @error('zona_gudang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="rack" class="form-label">Rack <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('rack') is-invalid @enderror" 
                                               id="rack" name="rack" placeholder="Contoh: 01, A, 1A"
                                               value="{{ old('rack', $masterLokasi->rack) }}" required>
                                        @error('rack')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="bin" class="form-label">Bin <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('bin') is-invalid @enderror" 
                                               id="bin" name="bin" placeholder="Contoh: 01, A, 1A"
                                               value="{{ old('bin', $masterLokasi->bin) }}" required>
                                        @error('bin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Tipe & Karakteristik -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">⚙️ Tipe & Karakteristik Lokasi</h6>
                            
                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="tipe_lokasi" class="form-label">Tipe Lokasi <span class="text-danger">*</span></label>
                                        <select class="form-select @error('tipe_lokasi') is-invalid @enderror" 
                                                id="tipe_lokasi" name="tipe_lokasi" required>
                                            <option value="">-- Pilih Tipe --</option>
                                            <option value="regular" @selected(old('tipe_lokasi', $masterLokasi->tipe_lokasi) === 'regular')>
                                                🟢 Regular (Stok Normal)
                                            </option>
                                            <option value="karantina" @selected(old('tipe_lokasi', $masterLokasi->tipe_lokasi) === 'karantina')>
                                                🟡 Karantina (NG/Hold)
                                            </option>
                                            <option value="ng_storage" @selected(old('tipe_lokasi', $masterLokasi->tipe_lokasi) === 'ng_storage')>
                                                🔴 NG Storage (Penyimpanan NG)
                                            </option>
                                            <option value="scrap" @selected(old('tipe_lokasi', $masterLokasi->tipe_lokasi) === 'scrap')>
                                                ⚫ Scrap (Barang Rusak)
                                            </option>
                                        </select>
                                        @error('tipe_lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Menentukan penggunaan lokasi</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="status_lokasi" class="form-label">Status Lokasi <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status_lokasi') is-invalid @enderror" 
                                                id="status_lokasi" name="status_lokasi" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="available" @selected(old('status_lokasi', $masterLokasi->status_lokasi) === 'available')>
                                                ✓ Available (Tersedia)
                                            </option>
                                            <option value="full" @selected(old('status_lokasi', $masterLokasi->status_lokasi) === 'full')>
                                                ✗ Full (Penuh)
                                            </option>
                                            <option value="maintenance" @selected(old('status_lokasi', $masterLokasi->status_lokasi) === 'maintenance')>
                                                ⚙ Maintenance (Perbaikan)
                                            </option>
                                            <option value="blocked" @selected(old('status_lokasi', $masterLokasi->status_lokasi) === 'blocked')>
                                                ⛔ Blocked (Terblokir)
                                            </option>
                                        </select>
                                        @error('status_lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Kapasitas -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">📦 Kapasitas Penyimpanan</h6>
                            
                            <div class="form-group-box">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="kapasitas_maksimal" class="form-label">Kapasitas Maksimal (unit)</label>
                                        <input type="number" class="form-control @error('kapasitas_maksimal') is-invalid @enderror" 
                                               id="kapasitas_maksimal" name="kapasitas_maksimal" placeholder="Contoh: 100, 500, 1000"
                                               value="{{ old('kapasitas_maksimal', $masterLokasi->kapasitas_maksimal) }}" min="0">
                                        @error('kapasitas_maksimal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Jumlah maksimal item yang dapat disimpan</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="is_active" class="form-label">Status Aktivasi</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                                   type="checkbox" id="is_active" name="is_active" value="1" 
                                                   @checked(old('is_active', $masterLokasi->is_active))>
                                            <label class="form-check-label" for="is_active">
                                                Aktifkan lokasi ini
                                            </label>
                                        </div>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Lokasi aktif akan muncul di dropdown</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Informasi Tambahan -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">📝 Informasi Tambahan</h6>
                            
                            <div class="form-group-box">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                                  id="deskripsi" name="deskripsi" rows="3"
                                                  placeholder="Catatan tambahan tentang lokasi ini...">{{ old('deskripsi', $masterLokasi->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Informasi opsional untuk referensi</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Informasi Teknis -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">📊 Informasi Teknis</h6>
                            
                            <div class="form-group-box">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-muted small mb-2">
                                            <strong>Dibuat:</strong>
                                        </p>
                                        <p class="small">{{ $masterLokasi->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-muted small mb-2">
                                            <strong>Diperbarui:</strong>
                                        </p>
                                        <p class="small">{{ $masterLokasi->updated_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-section">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('master-lokasi.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="reset" class="btn btn-warning">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Update Lokasi
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

    .form-control:disabled {
        background-color: #e9ecef;
        opacity: 1;
        cursor: not-allowed;
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

    .form-check-label {
        font-size: 14px;
        color: #495057;
        cursor: pointer;
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
