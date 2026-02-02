@extends('layouts.app')

@section('title', 'Edit Dokumen Retur')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Dokumen Retur</h3>
                <p class="text-subtitle text-muted">Update informasi dokumen retur</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dokumen-retur.index') }}">Dokumen Retur</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">📄 Edit Dokumen Retur: {{ $dokumenRetur->nomor_dokumen }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dokumen-retur.update', $dokumenRetur) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <!-- Customer Complaint Info (Read-only) -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-light border">
                                        <h6><i class="bi bi-info-circle me-2"></i>Customer Complaint</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>No. Complaint:</strong> {{ $dokumenRetur->customerComplaint->nomor_complaint }}<br>
                                                <strong>Customer:</strong> {{ $dokumenRetur->customerComplaint->nama_customer }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Produk:</strong> {{ $dokumenRetur->customerComplaint->produk }}<br>
                                                <strong>Quantity:</strong> {{ $dokumenRetur->customerComplaint->quantity_ng }} pcs
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomor_referensi" class="form-label">Nomor Referensi</label>
                                        <input type="text" class="form-control @error('nomor_referensi') is-invalid @enderror" 
                                               id="nomor_referensi" name="nomor_referensi" 
                                               value="{{ old('nomor_referensi', $dokumenRetur->nomor_referensi) }}"
                                               placeholder="Nomor referensi eksternal (opsional)">
                                        @error('nomor_referensi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_retur" class="form-label">Jenis Retur <span class="text-danger">*</span></label>
                                        <select class="form-select @error('jenis_retur') is-invalid @enderror" 
                                                id="jenis_retur" name="jenis_retur" required>
                                            <option value="">-- Pilih Jenis Retur --</option>
                                            <option value="full_return" {{ old('jenis_retur', $dokumenRetur->jenis_retur) == 'full_return' ? 'selected' : '' }}>
                                                📦 Full Return
                                            </option>
                                            <option value="partial_return" {{ old('jenis_retur', $dokumenRetur->jenis_retur) == 'partial_return' ? 'selected' : '' }}>
                                                📦 Partial Return
                                            </option>
                                            <option value="replacement" {{ old('jenis_retur', $dokumenRetur->jenis_retur) == 'replacement' ? 'selected' : '' }}>
                                                🔄 Replacement
                                            </option>
                                            <option value="credit_note" {{ old('jenis_retur', $dokumenRetur->jenis_retur) == 'credit_note' ? 'selected' : '' }}>
                                                💳 Credit Note
                                            </option>
                                        </select>
                                        @error('jenis_retur')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="draft" {{ old('status', $dokumenRetur->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="sent_to_warehouse" {{ old('status', $dokumenRetur->status) == 'sent_to_warehouse' ? 'selected' : '' }}>Dikirim ke Warehouse</option>
                                            <option value="received_by_warehouse" {{ old('status', $dokumenRetur->status) == 'received_by_warehouse' ? 'selected' : '' }}>Diterima Warehouse</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="instruksi_retur" class="form-label">Instruksi Retur <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('instruksi_retur') is-invalid @enderror" 
                                                  id="instruksi_retur" name="instruksi_retur" rows="4" required 
                                                  placeholder="Jelaskan instruksi detail untuk proses retur ini...">{{ old('instruksi_retur', $dokumenRetur->instruksi_retur) }}</textarea>
                                        @error('instruksi_retur')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="catatan_tambahan" class="form-label">Catatan Tambahan</label>
                                        <textarea class="form-control @error('catatan_tambahan') is-invalid @enderror" 
                                                  id="catatan_tambahan" name="catatan_tambahan" rows="3" 
                                                  placeholder="Catatan atau informasi tambahan (opsional)">{{ old('catatan_tambahan', $dokumenRetur->catatan_tambahan) }}</textarea>
                                        @error('catatan_tambahan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Documents -->
                            @if($dokumenRetur->dokumen_retur && count($dokumenRetur->dokumen_retur) > 0)
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">Dokumen yang Sudah Diupload</label>
                                        <div class="list-group mb-3">
                                            @foreach($dokumenRetur->dokumen_retur as $index => $document)
                                                <a href="{{ Storage::url($document) }}" target="_blank" class="list-group-item list-group-item-action">
                                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                                    Dokumen {{ $index + 1 }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- New Document Upload -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="dokumen_retur" class="form-label">Upload Dokumen Tambahan</label>
                                        <input type="file" class="form-control @error('dokumen_retur.*') is-invalid @enderror" 
                                               id="dokumen_retur" name="dokumen_retur[]" multiple accept=".pdf,.doc,.docx">
                                        <small class="form-text text-muted">Upload dokumen baru jika diperlukan. Dokumen lama tidak akan dihapus.</small>
                                        @error('dokumen_retur.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('dokumen-retur.show', $dokumenRetur) }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Kembali
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Update Dokumen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.card-header {
    background: linear-gradient(135deg, #435ebe 0%, #6c7ae0 100%);
    color: white;
}

.card-header .card-title {
    color: white;
    margin: 0;
}

.form-group {
    margin-bottom: 1.2rem;
}
</style>
@endpush
