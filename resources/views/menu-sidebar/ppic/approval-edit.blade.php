@extends('layouts.app')

@section('title', 'Edit Approval Finance')

@push('styles')
<style>
    .section-header {
        background-color: #E7E6E6;
        padding: 10px 15px;
        font-weight: bold;
        border-left: 4px solid #4472C4;
        margin-top: 20px;
        margin-bottom: 15px;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Approval Finance</h3>
                <p class="text-subtitle text-muted">Edit data approval finance {{ $approval->nomor_approval }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('ppic.approval.index') }}">Approval Finance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Approval</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('ppic.approval.update', $approval) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="section-header">📋 Informasi Approval</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Nomor Approval</label>
                                <input type="text" class="form-control" value="{{ $approval->nomor_approval }}" readonly style="background-color: #f0f0f0;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Approval</label>
                                <input type="text" class="form-control" value="{{ $approval->tanggal_approval?->format('d-m-Y H:i') }}" readonly style="background-color: #f0f0f0;">
                            </div>
                        </div>
                    </div>

                    <div class="section-header">✏️ Edit Status Approval</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status_approval" class="form-label">Status Approval <span class="text-danger">*</span></label>
                                <select class="form-select @error('status_approval') is-invalid @enderror" id="status_approval" name="status_approval" required>
                                    <option value="pending" {{ $approval->status_approval == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="approved" {{ $approval->status_approval == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                                    <option value="rejected" {{ $approval->status_approval == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                                    <option value="need_revision" {{ $approval->status_approval == 'need_revision' ? 'selected' : '' }}>🔄 Need Revision</option>
                                    <option value="not_applicable" {{ $approval->status_approval == 'not_applicable' ? 'selected' : '' }}>➖ Not Applicable</option>
                                </select>
                                @error('status_approval')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="budget_approval" class="form-label">Budget Approval <span class="text-danger">*</span></label>
                                <select class="form-select @error('budget_approval') is-invalid @enderror" id="budget_approval" name="budget_approval" required>
                                    <option value="dalam_budget" {{ $approval->budget_approval == 'dalam_budget' ? 'selected' : '' }}>✅ Dalam Budget</option>
                                    <option value="melebihi_budget" {{ $approval->budget_approval == 'melebihi_budget' ? 'selected' : '' }}>⚠️ Melebihi Budget</option>
                                    <option value="perlu_persetujuan_lebih_tinggi" {{ $approval->budget_approval == 'perlu_persetujuan_lebih_tinggi' ? 'selected' : '' }}>🔝 Perlu Persetujuan Lebih Tinggi</option>
                                </select>
                                @error('budget_approval')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan', $approval->catatan) }}</textarea>
                                @error('catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('ppic.approval.show', $approval) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
