@extends('layouts.app')

@section('title', 'Edit Quality Reinspection')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Quality Reinspection</h3>
                <p class="text-subtitle text-muted">Edit data inspeksi ulang barang NG</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('quality-reinspection.index') }}">Quality Reinspection</a></li>
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
            <div class="card-header bg-primary text-white">
                <h4 class="card-title text-white mb-0">
                    <i class="bi bi-pencil-square me-2"></i>Form Edit Quality Reinspection
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('quality-reinspection.update', $inspection) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Info Warehouse Verification -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">
                                        <i class="bi bi-info-circle me-2"></i>Warehouse Verification Info
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <strong>Nomor Verifikasi:</strong>
                                                <span class="text-muted">{{ $inspection->warehouseVerification->nomor_verifikasi }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Customer:</strong>
                                                <span class="text-muted">{{ $inspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Produk:</strong>
                                                <span class="text-muted">{{ $inspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <strong>Nomor Inspeksi:</strong>
                                                <span class="badge bg-info">{{ $inspection->nomor_inspeksi }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Quantity NG:</strong>
                                                <span class="text-danger fw-bold">{{ $inspection->warehouseVerification->quantity_ng_confirmed }} pcs</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Status Saat Ini:</strong>
                                                <span class="badge bg-{{ $inspection->status === 'draft' ? 'secondary' : ($inspection->status === 'inspected' ? 'success' : 'warning') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $inspection->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Inspeksi -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tanggal_inspeksi" class="form-label">Tanggal Inspeksi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_inspeksi') is-invalid @enderror" 
                                       id="tanggal_inspeksi" name="tanggal_inspeksi" 
                                       value="{{ old('tanggal_inspeksi', $inspection->tanggal_inspeksi->format('Y-m-d')) }}" required>
                                @error('tanggal_inspeksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="severity_level" class="form-label">Severity Level <span class="text-danger">*</span></label>
                                <select class="form-select @error('severity_level') is-invalid @enderror" id="severity_level" name="severity_level" required>
                                    <option value="">-- Pilih Severity --</option>
                                    <option value="critical" {{ old('severity_level', $inspection->severity_level) == 'critical' ? 'selected' : '' }}>Critical</option>
                                    <option value="major" {{ old('severity_level', $inspection->severity_level) == 'major' ? 'selected' : '' }}>Major</option>
                                    <option value="minor" {{ old('severity_level', $inspection->severity_level) == 'minor' ? 'selected' : '' }}>Minor</option>
                                </select>
                                @error('severity_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Defect Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="jenis_defect" class="form-label">Jenis Defect <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('jenis_defect') is-invalid @enderror" 
                                       id="jenis_defect" name="jenis_defect" 
                                       placeholder="Contoh: Crack, Scratch, Dent" 
                                       value="{{ old('jenis_defect', $inspection->jenis_defect) }}" required>
                                @error('jenis_defect')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="quantity_defect" class="form-label">Quantity Defect <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('quantity_defect') is-invalid @enderror" 
                                       id="quantity_defect" name="quantity_defect" min="1" 
                                       placeholder="Jumlah unit yang defect" 
                                       value="{{ old('quantity_defect', $inspection->quantity_defect) }}" required>
                                @error('quantity_defect')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="deskripsi_defect" class="form-label">Deskripsi Defect <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('deskripsi_defect') is-invalid @enderror" 
                                          id="deskripsi_defect" name="deskripsi_defect" rows="3" 
                                          placeholder="Jelaskan detail kondisi defect yang ditemukan"
                                          required>{{ old('deskripsi_defect', $inspection->deskripsi_defect) }}</textarea>
                                @error('deskripsi_defect')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Root Cause Analysis -->
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="root_cause_analysis" class="form-label">Root Cause Analysis <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('root_cause_analysis') is-invalid @enderror" 
                                          id="root_cause_analysis" name="root_cause_analysis" rows="6" 
                                          placeholder="Analisis penyebab akar masalah (5 Why, Fishbone, dll)"
                                          required>{{ old('root_cause_analysis', $inspection->root_cause_analysis) }}</textarea>
                                @error('root_cause_analysis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Corrective & Preventive Action -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="corrective_action" class="form-label">Corrective Action <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('corrective_action') is-invalid @enderror" 
                                          id="corrective_action" name="corrective_action" rows="4" 
                                          placeholder="Tindakan perbaikan untuk mengatasi masalah saat ini"
                                          required>{{ old('corrective_action', $inspection->corrective_action) }}</textarea>
                                @error('corrective_action')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="preventive_action" class="form-label">Preventive Action <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('preventive_action') is-invalid @enderror" 
                                          id="preventive_action" name="preventive_action" rows="4" 
                                          placeholder="Tindakan pencegahan agar tidak terulang di masa depan"
                                          required>{{ old('preventive_action', $inspection->preventive_action) }}</textarea>
                                @error('preventive_action')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Disposisi & Estimasi Biaya -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="disposisi" class="form-label">Disposisi <span class="text-danger">*</span></label>
                                <select class="form-select @error('disposisi') is-invalid @enderror" id="disposisi" name="disposisi" required>
                                    <option value="">-- Pilih Disposisi --</option>
                                    <option value="rework" {{ old('disposisi', $inspection->disposisi) == 'rework' ? 'selected' : '' }}>Rework</option>
                                    <option value="scrap" {{ old('disposisi', $inspection->disposisi) == 'scrap' ? 'selected' : '' }}>Scrap</option>
                                    <option value="return_to_vendor" {{ old('disposisi', $inspection->disposisi) == 'return_to_vendor' ? 'selected' : '' }}>Return to Vendor</option>
                                    <option value="return_to_customer" {{ old('disposisi', $inspection->disposisi) == 'return_to_customer' ? 'selected' : '' }}>Return to Customer</option>
                                </select>
                                @error('disposisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="draft" {{ old('status', $inspection->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="inspected" {{ old('status', $inspection->status) == 'inspected' ? 'selected' : '' }}>Inspected (Selesai)</option>
                                    <option value="sent_to_production" {{ old('status', $inspection->status) == 'sent_to_production' ? 'selected' : '' }}>Sent to Production</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="estimasi_biaya_rework" class="form-label">Estimasi Biaya Rework</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('estimasi_biaya_rework') is-invalid @enderror" 
                                           id="estimasi_biaya_rework" name="estimasi_biaya_rework" min="0" step="0.01"
                                           placeholder="0.00" 
                                           value="{{ old('estimasi_biaya_rework', $inspection->estimasi_biaya_rework) }}">
                                </div>
                                @error('estimasi_biaya_rework')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Existing Photos -->
                    @if(!empty($inspection->foto_defect) && is_array($inspection->foto_defect))
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Foto Defect Existing</label>
                            <div class="row">
                                @foreach($inspection->foto_defect as $index => $foto)
                                <div class="col-md-3 mb-2">
                                    <div class="position-relative">
                                        <img src="{{ Storage::url($foto) }}" class="img-thumbnail" style="max-height: 150px; width: 100%; object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                                onclick="removePhoto({{ $index }}, 'foto')">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Foto Defect - Upload New -->
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="foto_defect" class="form-label">Upload Foto Defect Baru</label>
                                <input type="file" class="form-control @error('foto_defect.*') is-invalid @enderror" 
                                       id="foto_defect" name="foto_defect[]" accept="image/*" multiple>
                                @error('foto_defect.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upload foto defect tambahan (Multiple files, Max: 2MB per file)</small>
                            </div>
                            <div id="imagePreview" class="row mt-2"></div>
                        </div>
                    </div>

                    <!-- Existing Documents -->
                    @if(!empty($inspection->dokumen_rca) && is_array($inspection->dokumen_rca))
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Dokumen RCA Existing</label>
                            <div class="list-group">
                                @foreach($inspection->dokumen_rca as $index => $dokumen)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                        <a href="{{ Storage::url($dokumen) }}" target="_blank">{{ basename($dokumen) }}</a>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removePhoto({{ $index }}, 'dokumen')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Dokumen RCA - Upload New -->
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="dokumen_rca" class="form-label">Upload Dokumen RCA Baru</label>
                                <input type="file" class="form-control @error('dokumen_rca.*') is-invalid @enderror" 
                                       id="dokumen_rca" name="dokumen_rca[]" accept=".pdf,.doc,.docx" multiple>
                                @error('dokumen_rca.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upload dokumen RCA tambahan (PDF, DOC, DOCX, Max: 5MB per file)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('quality-reinspection.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Update Inspeksi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview for foto defect
    $('#foto_defect').on('change', function(e) {
        const files = e.target.files;
        $('#imagePreview').empty();
        
        if (files.length > 0) {
            $.each(files, function(i, file) {
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').append(`
                            <div class="col-md-3 mb-2">
                                <img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px;">
                                <small class="d-block text-muted">${file.name}</small>
                            </div>
                        `);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
});

function removePhoto(index, type) {
    if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
        // Add logic to remove file via AJAX
        const inspectionId = {{ $inspection->id }};
        
        $.ajax({
            url: `/quality-reinspection/${inspectionId}/remove-file`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                index: index,
                type: type
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus file');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus file');
            }
        });
    }
}
</script>
@endpush
@endsection
