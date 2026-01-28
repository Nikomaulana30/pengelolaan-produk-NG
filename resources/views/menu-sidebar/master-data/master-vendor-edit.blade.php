@extends('layouts.app')

@section('title', 'Edit Master Vendor')

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
                    <h3><i class="bi bi-pencil"></i> Edit Master Vendor</h3>
                    <p class="text-subtitle text-muted">Ubah informasi vendor/supplier</p>
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
                            <i class="bi bi-info-circle me-2"></i>Informasi Vendor
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('master-vendor.update', $masterVendor) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Kode Vendor (Readonly) -->
                            <div class="mb-3">
                                <label for="kode_vendor" class="form-label">Kode Vendor</label>
                                <input type="text" class="form-control" id="kode_vendor" 
                                    value="{{ $masterVendor->kode_vendor }}" disabled>
                                <small class="text-muted">Kode vendor tidak dapat diubah</small>
                            </div>

                            <!-- Nama Vendor -->
                            <div class="mb-3">
                                <label for="nama_vendor" class="form-label">Nama Vendor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_vendor') is-invalid @enderror" 
                                    id="nama_vendor" name="nama_vendor" value="{{ old('nama_vendor', $masterVendor->nama_vendor) }}"
                                    placeholder="Masukkan nama vendor" required>
                                @error('nama_vendor')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="mb-3">
                                <label for="alamat_vendor" class="form-label">Alamat</label>
                                <textarea class="form-control @error('alamat_vendor') is-invalid @enderror" 
                                    id="alamat_vendor" name="alamat_vendor" rows="3"
                                    placeholder="Masukkan alamat vendor">{{ old('alamat_vendor', $masterVendor->alamat_vendor) }}</textarea>
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
                                            id="kota" name="kota" value="{{ old('kota', $masterVendor->kota) }}"
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
                                            id="provinsi" name="provinsi" value="{{ old('provinsi', $masterVendor->provinsi) }}"
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
                                            id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $masterVendor->kode_pos) }}"
                                            placeholder="Masukkan kode pos">
                                        @error('kode_pos')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="card mb-3" style="border: 1px solid #e9ecef;">
                                <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                                    <h6 class="mb-0">
                                        <i class="bi bi-telephone me-2"></i>Informasi Kontak
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Telepon -->
                                    <div class="mb-3">
                                        <label for="telepon" class="form-label">Telepon</label>
                                        <input type="tel" class="form-control @error('telepon') is-invalid @enderror" 
                                            id="telepon" name="telepon" value="{{ old('telepon', $masterVendor->telepon) }}"
                                            placeholder="Masukkan nomor telepon">
                                        @error('telepon')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                            id="email" name="email" value="{{ old('email', $masterVendor->email) }}"
                                            placeholder="Masukkan email">
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Person In Charge -->
                                    <div class="mb-0">
                                        <label for="person_in_charge" class="form-label">Person In Charge</label>
                                        <input type="text" class="form-control @error('person_in_charge') is-invalid @enderror" 
                                            id="person_in_charge" name="person_in_charge" value="{{ old('person_in_charge', $masterVendor->person_in_charge) }}"
                                            placeholder="Masukkan nama PIC">
                                        @error('person_in_charge')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Kebijakan Retur -->
                            <div class="mb-3">
                                <label for="kebijakan_retur" class="form-label">Kebijakan Retur <span class="text-danger">*</span></label>
                                <select class="form-select @error('kebijakan_retur') is-invalid @enderror" 
                                    id="kebijakan_retur" name="kebijakan_retur" required>
                                    <option value="">-- Pilih Kebijakan Retur --</option>
                                    <option value="retur_fisik" @selected(old('kebijakan_retur', $masterVendor->kebijakan_retur) === 'retur_fisik')>
                                        Retur Fisik
                                    </option>
                                    <option value="debit_note" @selected(old('kebijakan_retur', $masterVendor->kebijakan_retur) === 'debit_note')>
                                        Debit Note
                                    </option>
                                    <option value="keduanya" @selected(old('kebijakan_retur', $masterVendor->kebijakan_retur) === 'keduanya')>
                                        Retur Fisik & Debit Note
                                    </option>
                                </select>
                                @error('kebijakan_retur')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">
                                    Pilih kebijakan pengembalian barang yang didukung vendor
                                </small>
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                    id="deskripsi" name="deskripsi" rows="3"
                                    placeholder="Masukkan deskripsi atau catatan tentang vendor">{{ old('deskripsi', $masterVendor->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status Aktif -->
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                        @checked(old('is_active', $masterVendor->is_active))>
                                    <label class="form-check-label" for="is_active">
                                        Vendor Aktif
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    Centang untuk mengaktifkan vendor dalam sistem
                                </small>
                            </div>

                            <!-- Buttons -->
                            <div class="mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                                </button>
                                <a href="{{ route('master-vendor.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Batal
                                </a>
                                <a href="{{ route('master-vendor.show', $masterVendor) }}" class="btn btn-info">
                                    <i class="bi bi-eye me-2"></i>Lihat Detail
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-12 col-lg-4">
                <!-- Quick Info -->
                <div class="card mb-3">
                    <div class="card-header border-bottom">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi Cepat
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            <strong>Kode Vendor:</strong><br>
                            <code>{{ $masterVendor->kode_vendor }}</code>
                        </p>
                        <p class="text-muted small mb-2">
                            <strong>Status Saat Ini:</strong><br>
                            @if ($masterVendor->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                        <p class="text-muted small mb-0">
                            <strong>Terakhir Diupdate:</strong><br>
                            {{ $masterVendor->updated_at?->diffForHumans() ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card">
                    <div class="card-header border-bottom">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-question-circle me-2"></i>Bantuan
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-2">Kebijakan Retur:</h6>
                        <ul class="small text-muted">
                            <li>
                                <strong>Retur Fisik:</strong> Barang dikembalikan secara fisik
                            </li>
                            <li>
                                <strong>Debit Note:</strong> Pengembalian via debit note tanpa retur fisik
                            </li>
                            <li>
                                <strong>Keduanya:</strong> Mendukung kedua metode pengembalian
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .text-subtitle {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .card {
        border: 1px solid #e9ecef;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .text-danger {
        color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
@endpush
@endsection
