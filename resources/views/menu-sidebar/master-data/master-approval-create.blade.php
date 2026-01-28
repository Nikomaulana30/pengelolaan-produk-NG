@extends('layouts.app')

@section('title', 'Tambah Master Approval Authority')

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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Master Approval Authority</h5>
                    <a href="{{ route('master-approval.index') }}" class="btn btn-light btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bi bi-exclamation-triangle"></i> Ada kesalahan:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('master-approval.store') }}" method="POST">
                        @csrf

                        <!-- Section: User & Department -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom"><i class="bi bi-person-badge"></i> Informasi User & Departemen</h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user_id" class="form-label">User <span class="text-danger">*</span></label>
                                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                            <option value="">-- Pilih User --</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="departemen" class="form-label">Departemen <span class="text-danger">*</span></label>
                                        <select class="form-select @error('departemen') is-invalid @enderror" id="departemen" name="departemen" required>
                                            <option value="">-- Pilih Departemen --</option>
                                            <option value="warehouse" {{ old('departemen') === 'warehouse' ? 'selected' : '' }}>Warehouse</option>
                                            <option value="quality" {{ old('departemen') === 'quality' ? 'selected' : '' }}>Quality</option>
                                            <option value="ppic" {{ old('departemen') === 'ppic' ? 'selected' : '' }}>PPIC</option>
                                        </select>
                                        @error('departemen')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Role & Authority -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom"><i class="bi bi-shield-check"></i> Role & Otorisasi</h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role_level" class="form-label">Role Level <span class="text-danger">*</span></label>
                                        <select class="form-select @error('role_level') is-invalid @enderror" id="role_level" name="role_level" required>
                                            <option value="">-- Pilih Role Level --</option>
                                            <option value="supervisor" {{ old('role_level') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                            <option value="manager" {{ old('role_level') === 'manager' ? 'selected' : '' }}>Manager</option>
                                            <option value="director" {{ old('role_level') === 'director' ? 'selected' : '' }}>Director</option>
                                        </select>
                                        @error('role_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_approval" class="form-label">Jenis Approval <span class="text-danger">*</span></label>
                                        <select class="form-select @error('jenis_approval') is-invalid @enderror" id="jenis_approval" name="jenis_approval" required>
                                            <option value="">-- Pilih Jenis Approval --</option>
                                            <option value="penerimaan_barang" {{ old('jenis_approval') === 'penerimaan_barang' ? 'selected' : '' }}>Penerimaan Barang</option>
                                            <option value="penyimpanan_ng" {{ old('jenis_approval') === 'penyimpanan_ng' ? 'selected' : '' }}>Penyimpanan NG</option>
                                            <option value="scrap_disposal" {{ old('jenis_approval') === 'scrap_disposal' ? 'selected' : '' }}>Scrap Disposal</option>
                                            <option value="retur_vendor" {{ old('jenis_approval') === 'retur_vendor' ? 'selected' : '' }}>Retur Vendor</option>
                                            <option value="rework" {{ old('jenis_approval') === 'rework' ? 'selected' : '' }}>Rework</option>
                                            <option value="rca_analysis" {{ old('jenis_approval') === 'rca_analysis' ? 'selected' : '' }}>RCA Analysis</option>
                                        </select>
                                        @error('jenis_approval')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Approval Limit -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom"><i class="bi bi-currency-dollar"></i> Batas Approval</h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="approval_limit" class="form-label">Approval Limit (Rp) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('approval_limit') is-invalid @enderror" id="approval_limit" name="approval_limit" placeholder="0" value="{{ old('approval_limit', 0) }}" step="1" min="0" required>
                                        <small class="form-text text-muted">Masukkan dalam rupiah. Gunakan 0 untuk unlimited</small>
                                        @error('approval_limit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="can_approve_self" class="form-label">&nbsp;</label>
                                        <div class="form-check">
                                            <input type="hidden" name="can_approve_self" value="0">
                                            <input class="form-check-input" type="checkbox" id="can_approve_self" name="can_approve_self" value="1" {{ old('can_approve_self') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="can_approve_self">
                                                Dapat Approve Dokumen Sendiri
                                            </label>
                                        </div>
                                        <small class="form-text text-muted d-block mt-2">Centang jika user boleh approve dokumen yang mereka buat sendiri</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Description & Status -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom"><i class="bi bi-info-circle"></i> Deskripsi & Status</h6>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi atau catatan">{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('master-approval.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
