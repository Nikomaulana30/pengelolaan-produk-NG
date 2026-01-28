@extends('layouts.app')

@section('title', 'Tambah Retur Barang')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-plus-square"></i> Tambah Retur Barang</h3>
                    <p class="text-subtitle text-muted">Input pengembalian barang dari vendor</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('retur-barang.index') }}" class="btn btn-outline-secondary float-end">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Form Tambah Retur Barang</h5>
                            <p class="text-muted small">Isi data pengembalian barang dari vendor</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('retur-barang.store') }}" method="POST">
                                @csrf

                                <!-- Vendor -->
                                <div class="mb-3">
                                    <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                                    <select class="form-select @error('vendor_id') is-invalid @enderror" 
                                            id="vendor_id" name="vendor_id" required onchange="checkVendorPolicy()">
                                        <option value="">-- Pilih Vendor --</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" 
                                                    data-kebijakan="{{ $vendor->kebijakan_retur }}"
                                                    @selected(old('vendor_id') == $vendor->id)>
                                                {{ $vendor->kode_vendor }} - {{ $vendor->nama_vendor }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted mt-2 d-none" id="vendor_policy_info">
                                        <i class="bi bi-info-circle"></i> <span id="policy_text"></span>
                                    </small>
                                    @error('vendor_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Produk -->
                                <div class="mb-3">
                                    <label for="produk_id" class="form-label">Produk <span class="text-danger">*</span></label>
                                    <select class="form-select @error('produk_id') is-invalid @enderror" 
                                            id="produk_id" name="produk_id" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($produks as $produk)
                                            <option value="{{ $produk->id }}" @selected(old('produk_id') == $produk->id)>
                                                {{ $produk->kode_produk }} - {{ $produk->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('produk_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tanggal Retur -->
                                <div class="mb-3">
                                    <label for="tanggal_retur" class="form-label">Tanggal Retur <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_retur') is-invalid @enderror" 
                                           id="tanggal_retur" name="tanggal_retur" value="{{ old('tanggal_retur') }}" required>
                                    @error('tanggal_retur')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Alasan Retur - HANYA DEFECT -->
                                <div class="mb-3">
                                    <label for="alasan_retur" class="form-label">Alasan Retur <span class="text-danger">*</span></label>
                                    <input type="hidden" name="alasan_retur" value="defect">
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Alasan: Defect / Cacat</strong>
                                        <p class="small mb-0 mt-2">Retur barang hanya untuk kasus cacat. Untuk alasan lain, gunakan Penyimpanan NG.</p>
                                    </div>
                                </div>

                                <!-- Jumlah Retur -->
                                <div class="mb-3">
                                    <label for="jumlah_retur" class="form-label">Jumlah Retur <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah_retur') is-invalid @enderror" 
                                           id="jumlah_retur" name="jumlah_retur" placeholder="Berapa unit barang dikembalikan?"
                                           value="{{ old('jumlah_retur') }}" min="1" required>
                                    @error('jumlah_retur')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deskripsi Keluhan -->
                                <div class="mb-3">
                                    <label for="deskripsi_keluhan" class="form-label">Deskripsi Keluhan</label>
                                    <textarea class="form-control @error('deskripsi_keluhan') is-invalid @enderror" 
                                              id="deskripsi_keluhan" name="deskripsi_keluhan" rows="4"
                                              placeholder="Jelaskan detail keluhan/masalah dengan barang...">{{ old('deskripsi_keluhan') }}</textarea>
                                    @error('deskripsi_keluhan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Contoh: Produk ada goresan di permukaan, tidak berfungsi dengan baik, dll</small>
                                </div>

                                <hr>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('retur-barang.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> Batal
                                    </a>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Simpan Retur
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('styles')
<style>
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .text-danger {
        color: #dc3545;
    }

    .text-muted {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .btn {
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
</style>
@endpush

<script>
    function checkVendorPolicy() {
        const vendorSelect = document.getElementById('vendor_id');
        const selectedOption = vendorSelect.options[vendorSelect.selectedIndex];
        const kebijakan = selectedOption.getAttribute('data-kebijakan');
        const policyInfo = document.getElementById('vendor_policy_info');
        const policyText = document.getElementById('policy_text');

        if (kebijakan) {
            policyInfo.classList.remove('d-none');
            
            if (kebijakan === 'full_return') {
                policyText.innerHTML = '<strong>✅ Kebijakan Full Return</strong> - Retur barang disetujui otomatis';
            } else if (kebijakan === 'partial_return') {
                policyText.innerHTML = '<strong>⏳ Kebijakan Partial Return</strong> - Retur perlu persetujuan lebih lanjut';
            } else if (kebijakan === 'no_return') {
                policyText.innerHTML = '<strong>❌ Kebijakan No Return</strong> - Vendor tidak boleh return! Gunakan Penyimpanan NG.';
            }
        } else {
            policyInfo.classList.add('d-none');
        }
    }

    // Check on page load jika ada vendor yang sudah dipilih
    document.addEventListener('DOMContentLoaded', function() {
        checkVendorPolicy();
    });
</script>

@endsection
