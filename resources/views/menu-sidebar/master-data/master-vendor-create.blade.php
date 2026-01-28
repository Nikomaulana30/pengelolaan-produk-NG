@extends('layouts.app')

@section('title', 'Tambah Master Vendor')

@push('styles')
<style>
    /* Dark mode card styling */
    [data-bs-theme="dark"] .card {
        background-color: #1e1e2d !important;
        border-color: #2c3142 !important;
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
    
    [data-bs-theme="dark"] .page-content {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .form-label {
        color: #a1a1a1 !important;
    }
    
    [data-bs-theme="dark"] .text-muted {
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
                    <h3><i class="bi bi-plus-circle"></i> Tambah Master Vendor</h3>
                    <p class="text-subtitle text-muted">Tambahkan vendor/supplier baru ke sistem</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('master-vendor.index') }}" class="btn btn-secondary float-end">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong>Validasi Gagal!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi Vendor Baru
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('master-vendor.store') }}" method="POST">
                            @csrf

                            <!-- Kode Vendor -->
                            <div class="mb-3">
                                <label for="kode_vendor" class="form-label">Kode Vendor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kode_vendor') is-invalid @enderror" 
                                    id="kode_vendor" name="kode_vendor" value="{{ old('kode_vendor') }}"
                                    placeholder="e.g., V001" required>
                                <small class="text-muted">Kode unik untuk identifikasi vendor (tidak dapat diubah)</small>
                                @error('kode_vendor')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nama Vendor -->
                            <div class="mb-3">
                                <label for="nama_vendor" class="form-label">Nama Vendor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_vendor') is-invalid @enderror" 
                                    id="nama_vendor" name="nama_vendor" value="{{ old('nama_vendor') }}"
                                    placeholder="Masukkan nama vendor" required>
                                @error('nama_vendor')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="mb-3">
                                <label for="alamat_vendor" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat_vendor') is-invalid @enderror" 
                                    id="alamat_vendor" name="alamat_vendor" rows="3"
                                    placeholder="Masukkan alamat lengkap vendor" required>{{ old('alamat_vendor') }}</textarea>
                                @error('alamat_vendor')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kota, Provinsi, Kode Pos -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="kota" class="form-label">Kota</label>
                                        <input type="text" class="form-control @error('kota') is-invalid @enderror" 
                                            id="kota" name="kota" value="{{ old('kota') }}"
                                            placeholder="Masukkan kota">
                                        @error('kota')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="provinsi" class="form-label">Provinsi</label>
                                        <input type="text" class="form-control @error('provinsi') is-invalid @enderror" 
                                            id="provinsi" name="provinsi" value="{{ old('provinsi') }}"
                                            placeholder="Masukkan provinsi">
                                        @error('provinsi')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="kode_pos" class="form-label">Kode Pos</label>
                                        <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" 
                                            id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}"
                                            placeholder="12345">
                                        @error('kode_pos')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Telepon dan Email -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telepon" class="form-label">Telepon</label>
                                        <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                                            id="telepon" name="telepon" value="{{ old('telepon') }}"
                                            placeholder="Masukkan nomor telepon">
                                        @error('telepon')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="vendor@example.com">
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Person in Charge -->
                            <div class="mb-3">
                                <label for="person_in_charge" class="form-label">Nama Kontak (PIC)</label>
                                <input type="text" class="form-control @error('person_in_charge') is-invalid @enderror" 
                                    id="person_in_charge" name="person_in_charge" value="{{ old('person_in_charge') }}"
                                    placeholder="Nama person in charge">
                                @error('person_in_charge')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kebijakan Retur -->
                            <div class="mb-3">
                                <label for="kebijakan_retur" class="form-label">Kebijakan Retur <span class="text-danger">*</span></label>
                                <select class="form-select @error('kebijakan_retur') is-invalid @enderror" 
                                    id="kebijakan_retur" name="kebijakan_retur" required>
                                    <option value="">-- Pilih Kebijakan Retur --</option>
                                    <option value="retur_fisik" {{ old('kebijakan_retur') === 'retur_fisik' ? 'selected' : '' }}>
                                        📦 Retur Fisik (barang dikembalikan)
                                    </option>
                                    <option value="debit_note" {{ old('kebijakan_retur') === 'debit_note' ? 'selected' : '' }}>
                                        📄 Debit Note (ganti rugi)
                                    </option>
                                    <option value="keduanya" {{ old('kebijakan_retur') === 'keduanya' ? 'selected' : '' }}>
                                        📦📄 Keduanya (fleksibel)
                                    </option>
                                </select>
                                <small class="text-muted">Tentukan kebijakan penanganan barang retur</small>
                                @error('kebijakan_retur')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                    id="deskripsi" name="deskripsi" rows="3"
                                    placeholder="Masukkan informasi tambahan tentang vendor">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status Active -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                        name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Aktifkan vendor ini</strong> (tersedia untuk penerimaan & transaksi)
                                    </label>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-save me-1"></i> Simpan Vendor
                                </button>
                                <a href="{{ route('master-vendor.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="col-12 col-lg-4">
                <div class="card bg-light">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi Penting
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            <strong>Kode Vendor:</strong><br>
                            <small>Gunakan kode unik yang mudah diidentifikasi, misal V001, V002, atau singkatan nama vendor</small>
                        </p>
                        <p class="mb-3">
                            <strong>Kebijakan Retur:</strong><br>
                            <small>Tentukan bagaimana vendor menangani barang cacat/retur:</small>
                            <ul class="mt-2 mb-0 small">
                                <li>📦 <strong>Retur Fisik:</strong> Barang dikembalikan ke vendor</li>
                                <li>📄 <strong>Debit Note:</strong> Ganti rugi tanpa mengembalikan barang</li>
                                <li>📦📄 <strong>Keduanya:</strong> Fleksibel sesuai kesepakatan</li>
                            </ul>
                        </p>
                        <p class="mb-0">
                            <strong>Status:</strong><br>
                            <small>Cek "Aktifkan vendor ini" agar vendor tersedia di sistem untuk transaksi</small>
                        </p>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-link-45deg me-2"></i>Quick Links</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('master-vendor.index') }}" class="list-group-item list-group-item-action">
                                <i class="bi bi-shop me-2"></i>Daftar Vendor
                            </a>
                            <a href="{{ route('master-produk.index') }}" class="list-group-item list-group-item-action">
                                <i class="bi bi-box-open me-2"></i>Master Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
