@extends('layouts.app')

@section('title', 'Edit Master Disposisi')

@push('styles')
<style>
    /* Dark mode comprehensive styling */
    [data-bs-theme="dark"] .page-content {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .section {
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
        padding-bottom: 1rem !important;
    }
    
    [data-bs-theme="dark"] .card-body {
        background-color: #1e1e2d !important;
        color: #e4e4e7 !important;
        padding-top: 1.5rem !important;
    }
    
    [data-bs-theme="dark"] .card-title,
    [data-bs-theme="dark"] .card-text {
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
    
    [data-bs-theme="dark"] .form-section-title {
        color: #e4e4e7 !important;
    }
    
    /* Form controls dark mode */
    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background-color: #2c3142 !important;
        border-color: #3a3f51 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        background-color: #2c3142 !important;
        border-color: #667eea !important;
        color: #e4e4e7 !important;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
    }
    
    [data-bs-theme="dark"] .form-control::placeholder {
        color: #6b7280 !important;
    }
    
    [data-bs-theme="dark"] .form-select option {
        background-color: #2c3142 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] textarea.form-control {
        background-color: #2c3142 !important;
        border-color: #3a3f51 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .form-control.bg-light {
        background-color: #1a1d2e !important;
        color: #6b7280 !important;
    }
    
    [data-bs-theme="dark"] .form-group-box {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .form-section {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .form-check-label {
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .form-check-input {
        background-color: #2c3142 !important;
        border-color: #3a3f51 !important;
    }
    
    [data-bs-theme="dark"] .form-check-input:checked {
        background-color: #667eea !important;
        border-color: #667eea !important;
    }
    
    .card-body > form .form-section:first-child {
        margin-top: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-pencil-fill"></i> Edit Master Disposisi</h3>
                    <p class="text-subtitle text-muted">Perbarui jenis disposisi dan cara penanganan barang defect</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('master-disposisi.index') }}" class="btn btn-outline-secondary float-end">
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
                    <h5 class="card-title">Form Edit Master Disposisi</h5>
                    <p class="text-muted small">Ubah data disposisi yang sudah terdaftar dalam sistem</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('master-disposisi.update', $masterDisposisi) }}" method="POST" class="needs-validation">
                        @csrf
                        @method('PUT')

                        <!-- Section 1: Identifikasi Disposisi -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">📌 Identifikasi Disposisi</h6>
                            
                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="kode_disposisi" class="form-label">Kode Disposisi</label>
                                        <input type="text" class="form-control bg-light" 
                                               id="kode_disposisi" name="kode_disposisi" 
                                               value="{{ old('kode_disposisi', $masterDisposisi->kode_disposisi) }}" readonly>
                                        <small class="text-muted">
                                            <i class="bi bi-lock"></i> Auto-generate dari nama
                                        </small>
                                    </div>

                                    <div class="col-md-8">
                                        <label for="nama_disposisi" class="form-label">Nama Disposisi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_disposisi') is-invalid @enderror" 
                                               id="nama_disposisi" name="nama_disposisi" placeholder="Contoh: Rework Produk Grade A, Scrap Langsung"
                                               value="{{ old('nama_disposisi', $masterDisposisi->nama_disposisi) }}" required>
                                        @error('nama_disposisi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i> 
                                            Jika nama diubah, kode akan di-regenerate otomatis
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Jenis Tindakan -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">⚙️ Jenis Tindakan</h6>
                            
                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <!-- Nomor Storage Barang -->
                                    <div class="col-md-12 mb-3">
                                        <label for="penyimpanan_ng_id" class="form-label">Nomor Storage Barang</label>
                                        <select class="form-select @error('penyimpanan_ng_id') is-invalid @enderror" 
                                                id="penyimpanan_ng_id" name="penyimpanan_ng_id">
                                            <option value="">-- Pilih Barang dari Storage NG --</option>
                                            @foreach($penyimpanans as $penyimpanan)
                                                <option value="{{ $penyimpanan->id }}" 
                                                        {{ old('penyimpanan_ng_id', $masterDisposisi->penyimpanan_ng_id) == $penyimpanan->id ? 'selected' : '' }}
                                                        data-nama="{{ $penyimpanan->nama_barang }}"
                                                        data-lokasi="{{ $penyimpanan->lokasi_lengkap }}"
                                                        data-qty="{{ $penyimpanan->qty_awal }}">
                                                    {{ $penyimpanan->nomor_storage }} - {{ $penyimpanan->nama_barang }} 
                                                    ({{ $penyimpanan->lokasi_lengkap }}) - Qty: {{ $penyimpanan->qty_awal }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('penyimpanan_ng_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Pilih barang yang akan didisposisi</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="jenis_tindakan" class="form-label">Jenis Tindakan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('jenis_tindakan') is-invalid @enderror" 
                                                id="jenis_tindakan" name="jenis_tindakan" required>
                                            <option value="">-- Pilih Jenis Tindakan --</option>
                                            <option value="rework" @selected(old('jenis_tindakan', $masterDisposisi->jenis_tindakan) === 'rework')>
                                                🔧 Rework (Produksi Ulang)
                                            </option>
                                            <option value="scrap_disposal" @selected(old('jenis_tindakan', $masterDisposisi->jenis_tindakan) === 'scrap_disposal')>
                                                🗑️ Scrap Disposal (Buang/Musnahkan)
                                            </option>
                                            <option value="return_to_vendor" @selected(old('jenis_tindakan', $masterDisposisi->jenis_tindakan) === 'return_to_vendor')>
                                                📤 Return to Vendor (Kembalikan ke Vendor)
                                            </option>
                                            <option value="downgrade" @selected(old('jenis_tindakan', $masterDisposisi->jenis_tindakan) === 'downgrade')>
                                                📊 Downgrade (Turunkan Grade)
                                            </option>
                                            <option value="repurpose" @selected(old('jenis_tindakan', $masterDisposisi->jenis_tindakan) === 'repurpose')>
                                                🔄 Repurpose (Ubah Fungsi)
                                            </option>
                                        </select>
                                        @error('jenis_tindakan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Pilih tindakan yang akan dilakukan pada barang</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="memerlukan_approval" class="form-label">Perlu Approval <span class="text-danger">*</span></label>
                                        <select class="form-select @error('memerlukan_approval') is-invalid @enderror" 
                                                id="memerlukan_approval" name="memerlukan_approval" required>
                                            <option value="">-- Pilih Opsi --</option>
                                            <option value="1" @selected(old('memerlukan_approval', $masterDisposisi->memerlukan_approval) == 1)>
                                                ✓ Ya, Perlu Approval
                                            </option>
                                            <option value="0" @selected(old('memerlukan_approval', $masterDisposisi->memerlukan_approval) == 0)>
                                                ✗ Tidak Perlu Approval
                                            </option>
                                        </select>
                                        @error('memerlukan_approval')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Apakah tindakan ini memerlukan persetujuan dari atasan?</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Detail Proses -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">📋 Detail Proses Tindakan</h6>
                            
                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                                  id="deskripsi" name="deskripsi" rows="2"
                                                  placeholder="Deskripsi singkat tentang disposisi ini...">{{ old('deskripsi', $masterDisposisi->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="proses_tindakan" class="form-label">Proses Tindakan</label>
                                        <textarea class="form-control @error('proses_tindakan') is-invalid @enderror" 
                                                  id="proses_tindakan" name="proses_tindakan" rows="3"
                                                  placeholder="Jelaskan langkah-langkah proses tindakan yang harus dilakukan...">{{ old('proses_tindakan', $masterDisposisi->proses_tindakan) }}</textarea>
                                        @error('proses_tindakan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Informasi ini membantu dalam pelaksanaan tindakan</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-box">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="syarat_ketentuan" class="form-label">Syarat & Ketentuan</label>
                                        <textarea class="form-control @error('syarat_ketentuan') is-invalid @enderror" 
                                                  id="syarat_ketentuan" name="syarat_ketentuan" rows="3"
                                                  placeholder="Persyaratan atau aturan khusus yang harus diikuti dalam pelaksanaan tindakan ini...">{{ old('syarat_ketentuan', $masterDisposisi->syarat_ketentuan) }}</textarea>
                                        @error('syarat_ketentuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Kondisi atau batasan yang harus dipenuhi</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3A: Lokasi Tujuan Relokasi -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">📍 Lokasi Tujuan Relokasi</h6>
                            <p class="text-muted small mb-3">Tentukan lokasi tujuan default untuk barang dengan disposisi ini</p>
                            
                            <div class="form-group-box mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="zone_tujuan" class="form-label">Zone Tujuan</label>
                                        <select class="form-select @error('zone_tujuan') is-invalid @enderror" 
                                                id="zone_tujuan" name="zone_tujuan" onchange="generateLokasiTujuan()">
                                            <option value="">-- Pilih Zone --</option>
                                            <option value="zona_a" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_a' ? 'selected' : '' }}>Zona A</option>
                                            <option value="zona_b" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_b' ? 'selected' : '' }}>Zona B</option>
                                            <option value="zona_c" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_c' ? 'selected' : '' }}>Zona C</option>
                                            <option value="zona_d" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_d' ? 'selected' : '' }}>Zona D</option>
                                            <option value="zona_e" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_e' ? 'selected' : '' }}>Zona E</option>
                                            <option value="zona_return" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_return' ? 'selected' : '' }}>📤 Zona Return</option>
                                            <option value="zona_scrap" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_scrap' ? 'selected' : '' }}>🗑️ Zona Scrap</option>
                                            <option value="zona_rework" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_rework' ? 'selected' : '' }}>🔧 Zona Rework</option>
                                        </select>
                                        @error('zone_tujuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Lokasi tujuan barang</small>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="rack_tujuan" class="form-label">Rack Tujuan</label>
                                        <input type="text" class="form-control @error('rack_tujuan') is-invalid @enderror" 
                                               id="rack_tujuan" name="rack_tujuan" placeholder="Contoh: A1, B2, Return_Rack"
                                               value="{{ old('rack_tujuan', $masterDisposisi->rack_tujuan ?? '') }}"
                                               onchange="generateLokasiTujuan()">
                                        @error('rack_tujuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Nomor atau nama rack</small>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="bin_tujuan" class="form-label">Bin Tujuan</label>
                                        <input type="text" class="form-control @error('bin_tujuan') is-invalid @enderror" 
                                               id="bin_tujuan" name="bin_tujuan" placeholder="Contoh: 001, 002, A"
                                               value="{{ old('bin_tujuan', $masterDisposisi->bin_tujuan ?? '') }}"
                                               onchange="generateLokasiTujuan()">
                                        @error('bin_tujuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Nomor atau kode bin</small>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="lokasi_lengkap_tujuan" class="form-label">Lokasi Lengkap</label>
                                        <input type="text" class="form-control" 
                                               id="lokasi_lengkap_tujuan" name="lokasi_lengkap_tujuan"
                                               value="{{ old('lokasi_lengkap_tujuan', $masterDisposisi->lokasi_lengkap_tujuan ?? '') }}"
                                               disabled>
                                        <small class="text-muted">Auto-generated</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Status -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title mb-3">📊 Status</h6>
                            
                            <div class="form-group-box">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="is_active" class="form-label">Aktifkan Disposisi <span class="text-danger">*</span></label>
                                        <select class="form-select @error('is_active') is-invalid @enderror" 
                                                id="is_active" name="is_active" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="1" @selected(old('is_active', $masterDisposisi->is_active) == true)>
                                                🟢 Aktif
                                            </option>
                                            <option value="0" @selected(old('is_active', $masterDisposisi->is_active) == false)>
                                                🔴 Tidak Aktif
                                            </option>
                                        </select>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Disposisi aktif akan tersedia untuk penugasan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Button Group -->
                        <div class="form-section">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Perbarui Disposisi
                                </button>
                                <a href="{{ route('master-disposisi.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    .form-section {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 20px;
    }

    .form-section:last-of-type {
        border-bottom: none;
    }

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }

    .form-group-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 3px solid #0d6efd;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px 12px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
</style>

<script>
function generateLokasiTujuan() {
    const zone = document.getElementById('zone_tujuan').value;
    const rack = document.getElementById('rack_tujuan').value;
    const bin = document.getElementById('bin_tujuan').value;

    const zoneMap = {
        'zona_a': 'ZA',
        'zona_b': 'ZB',
        'zona_c': 'ZC',
        'zona_d': 'ZD',
        'zona_e': 'ZE',
        'zona_return': 'RET',
        'zona_scrap': 'SCR',
        'zona_rework': 'RWK'
    };

    const zoneCode = zoneMap[zone] || '';
    const lokasi = zone && rack && bin ? `${zoneCode}-${rack}-${bin}` : '';
    document.getElementById('lokasi_lengkap_tujuan').value = lokasi;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    generateLokasiTujuan();
});
</script>
@endsection
