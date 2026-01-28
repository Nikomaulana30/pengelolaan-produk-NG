@extends('layouts.app')

@section('title', 'Edit Retur Barang')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-pencil-square"></i> Edit Retur Barang</h3>
                    <p class="text-subtitle text-muted">Ubah data pengembalian barang</p>
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
                            <h5 class="card-title">Form Edit Retur Barang</h5>
                            <p class="text-muted small">Perbarui data retur: {{ $retur_barang->no_retur }}</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('retur-barang.update', $retur_barang) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Vendor -->
                                <div class="mb-3">
                                    <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                                    <select class="form-select @error('vendor_id') is-invalid @enderror" 
                                            id="vendor_id" name="vendor_id" required>
                                        <option value="">-- Pilih Vendor --</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" @selected(old('vendor_id', $retur_barang->vendor_id) == $vendor->id)>
                                                {{ $vendor->kode_vendor }} - {{ $vendor->nama_vendor }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                            <option value="{{ $produk->id }}" @selected(old('produk_id', $retur_barang->produk_id) == $produk->id)>
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
                                           id="tanggal_retur" name="tanggal_retur" value="{{ old('tanggal_retur', $retur_barang->tanggal_retur->format('Y-m-d')) }}" required>
                                    @error('tanggal_retur')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Alasan Retur -->
                                <div class="mb-3">
                                    <label for="alasan_retur" class="form-label">Alasan Retur <span class="text-danger">*</span></label>
                                    <select class="form-select @error('alasan_retur') is-invalid @enderror" 
                                            id="alasan_retur" name="alasan_retur" required>
                                        <option value="">-- Pilih Alasan --</option>
                                        <option value="defect" @selected(old('alasan_retur', $retur_barang->alasan_retur) == 'defect')>Defect / Cacat</option>
                                        <option value="qty_tidak_sesuai" @selected(old('alasan_retur', $retur_barang->alasan_retur) == 'qty_tidak_sesuai')>Qty Tidak Sesuai</option>
                                        <option value="kualitas_buruk" @selected(old('alasan_retur', $retur_barang->alasan_retur) == 'kualitas_buruk')>Kualitas Buruk</option>
                                        <option value="expired" @selected(old('alasan_retur', $retur_barang->alasan_retur) == 'expired')>Expired</option>
                                        <option value="rusak_pengiriman" @selected(old('alasan_retur', $retur_barang->alasan_retur) == 'rusak_pengiriman')>Rusak Pengiriman</option>
                                        <option value="lainnya" @selected(old('alasan_retur', $retur_barang->alasan_retur) == 'lainnya')>Lainnya</option>
                                    </select>
                                    @error('alasan_retur')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Jumlah Retur -->
                                <div class="mb-3">
                                    <label for="jumlah_retur" class="form-label">Jumlah Retur <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah_retur') is-invalid @enderror" 
                                           id="jumlah_retur" name="jumlah_retur"
                                           value="{{ old('jumlah_retur', $retur_barang->jumlah_retur) }}" min="1" required>
                                    @error('jumlah_retur')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deskripsi Keluhan -->
                                <div class="mb-3">
                                    <label for="deskripsi_keluhan" class="form-label">Deskripsi Keluhan</label>
                                    <textarea class="form-control @error('deskripsi_keluhan') is-invalid @enderror" 
                                              id="deskripsi_keluhan" name="deskripsi_keluhan" rows="4">{{ old('deskripsi_keluhan', $retur_barang->deskripsi_keluhan) }}</textarea>
                                    @error('deskripsi_keluhan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>

                                <!-- Status Approval (Only for edit) -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status_approval" class="form-label">Status Approval <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status_approval') is-invalid @enderror" 
                                                    id="status_approval" name="status_approval" required>
                                                <option value="pending" @selected(old('status_approval', $retur_barang->status_approval) == 'pending')>Pending</option>
                                                <option value="approved" @selected(old('status_approval', $retur_barang->status_approval) == 'approved')>Approved</option>
                                                <option value="rejected" @selected(old('status_approval', $retur_barang->status_approval) == 'rejected')>Rejected</option>
                                            </select>
                                            @error('status_approval')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Catatan Approval -->
                                <div class="mb-3">
                                    <label for="catatan_approval" class="form-label">Catatan Approval</label>
                                    <textarea class="form-control @error('catatan_approval') is-invalid @enderror" 
                                              id="catatan_approval" name="catatan_approval" rows="3"
                                              placeholder="Catatan untuk approver...">{{ old('catatan_approval', $retur_barang->catatan_approval) }}</textarea>
                                    @error('catatan_approval')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
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
                                        <i class="bi bi-check-circle"></i> Simpan Perubahan
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
@endsection
