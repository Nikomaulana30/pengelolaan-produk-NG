@extends('layouts.app')

@section('title', 'Edit Final Quality Check')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Final Decision</h3>
                <p class="text-subtitle text-muted">Update keputusan final untuk pengiriman ulang</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('final-quality-check.index') }}">Final Decision</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Final Decision #{{ $qualityCheck->id }}</h4>
            </div>
            <div class="card-body">
                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Terdapat kesalahan dalam form!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('final-quality-check.update', $qualityCheck) }}" method="POST" enctype="multipart/form-data" id="finalCheckForm">
                    @csrf
                    @method('PUT')

                    <!-- Production Rework Info (Read-only) -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Production Rework Information</h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Nomor Rework:</strong>
                                <p class="mb-1">{{ $qualityCheck->productionRework->nomor_rework }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Customer:</strong>
                                <p class="mb-1">{{ $qualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Produk:</strong>
                                <p class="mb-1">{{ $qualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Quality Check Details -->
                    <h5 class="mb-3"><i class="bi bi-clipboard-check me-2"></i>Detail Pemeriksaan Final</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_check" class="form-label required">Tanggal Pemeriksaan</label>
                            <input type="date" class="form-control" name="tanggal_check" id="tanggal_check" 
                                   value="{{ old('tanggal_check', $qualityCheck->tanggal_check ? $qualityCheck->tanggal_check->format('Y-m-d') : '') }}" required>
                            @error('tanggal_check')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="quantity_checked" class="form-label required">Quantity yang Diperiksa</label>
                            <input type="number" class="form-control" name="quantity_checked" id="quantity_checked" 
                                   value="{{ old('quantity_checked', $qualityCheck->quantity_checked) }}" min="1" required>
                            <small class="text-muted">Total quantity yang akan diperiksa ulang</small>
                            @error('quantity_checked')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity_passed" class="form-label required">Quantity Passed</label>
                            <input type="number" class="form-control border-success" name="quantity_passed" id="quantity_passed" 
                                   value="{{ old('quantity_passed', $qualityCheck->quantity_passed) }}" min="0" required>
                            <small class="text-success">Quantity yang lolos pemeriksaan</small>
                            @error('quantity_passed')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="quantity_failed" class="form-label required">Quantity Failed</label>
                            <input type="number" class="form-control border-danger" name="quantity_failed" id="quantity_failed" 
                                   value="{{ old('quantity_failed', $qualityCheck->quantity_failed) }}" min="0" required>
                            <small class="text-danger">Quantity yang tidak lolos pemeriksaan</small>
                            @error('quantity_failed')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="hasil_pemeriksaan" class="form-label required">Hasil Pemeriksaan</label>
                            <textarea name="hasil_pemeriksaan" id="hasil_pemeriksaan" rows="4" 
                                      class="form-control" required 
                                      placeholder="Deskripsikan hasil pemeriksaan final...">{{ old('hasil_pemeriksaan', $qualityCheck->hasil_pemeriksaan) }}</textarea>
                            @error('hasil_pemeriksaan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="catatan_quality" class="form-label required">Catatan Quality</label>
                            <textarea name="catatan_quality" id="catatan_quality" rows="4" 
                                      class="form-control" required 
                                      placeholder="Catatan tambahan mengenai quality...">{{ old('catatan_quality', $qualityCheck->catatan_quality) }}</textarea>
                            @error('catatan_quality')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Final Decision -->
                    <h5 class="mb-3"><i class="bi bi-flag-fill me-2"></i>Keputusan Final</h5>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="keputusan_final" class="form-label required">Keputusan Final</label>
                            <select name="keputusan_final" id="keputusan_final" class="form-select" required>
                                <option value="">-- Pilih Keputusan --</option>
                                <option value="approved_for_shipment" {{ old('keputusan_final', $qualityCheck->keputusan_final) == 'approved_for_shipment' ? 'selected' : '' }}>
                                    ✅ Approved for Shipment (Siap dikirim ke customer)
                                </option>
                                <option value="need_rework" {{ old('keputusan_final', $qualityCheck->keputusan_final) == 'need_rework' ? 'selected' : '' }}>
                                    🔄 Need Rework (Perlu perbaikan ulang)
                                </option>
                                <option value="rejected" {{ old('keputusan_final', $qualityCheck->keputusan_final) == 'rejected' ? 'selected' : '' }}>
                                    ❌ Rejected (Ditolak/Scrap)
                                </option>
                            </select>
                            @error('keputusan_final')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Existing Documents -->
                    @if($qualityCheck->foto_hasil_check && count($qualityCheck->foto_hasil_check) > 0)
                    <h5 class="mb-3"><i class="bi bi-images me-2"></i>Foto Existing</h5>
                    <div class="row mb-3">
                        @foreach($qualityCheck->foto_hasil_check as $foto)
                        <div class="col-md-3 mb-2">
                            <img src="{{ Storage::url($foto) }}" class="img-thumbnail" alt="Foto">
                            <small class="d-block text-center">{{ basename($foto) }}</small>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($qualityCheck->dokumen_quality && count($qualityCheck->dokumen_quality) > 0)
                    <h5 class="mb-3"><i class="bi bi-file-earmark-text me-2"></i>Dokumen Existing</h5>
                    <div class="row mb-3">
                        @foreach($qualityCheck->dokumen_quality as $doc)
                        <div class="col-md-6 mb-2">
                            <div class="alert alert-secondary py-2">
                                <i class="bi bi-file-earmark-pdf me-2"></i>{{ basename($doc) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Upload New Documents -->
                    <h5 class="mb-3"><i class="bi bi-file-earmark-image me-2"></i>Upload Dokumen & Foto Baru (Opsional)</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="foto_hasil_check" class="form-label">Upload Foto Hasil Check Baru</label>
                            <input type="file" class="form-control" name="foto_hasil_check[]" id="foto_hasil_check" 
                                   multiple accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Format: JPG, PNG. Max 2MB per file.</small>
                            @error('foto_hasil_check.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="dokumen_quality" class="form-label">Upload Dokumen Quality Baru</label>
                            <input type="file" class="form-control" name="dokumen_quality[]" id="dokumen_quality" 
                                   multiple accept=".pdf,.doc,.docx">
                            <small class="text-muted">Format: PDF, DOC, DOCX. Max 5MB per file.</small>
                            @error('dokumen_quality.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row" id="foto_preview"></div>
                    <div class="row" id="doc_preview"></div>

                    <hr class="my-4">

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Update Final Decision
                            </button>
                            <a href="{{ route('final-quality-check.show', $qualityCheck) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
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
    // Validate quantity totals
    $('#finalCheckForm').on('submit', function(e) {
        const checked = parseInt($('#quantity_checked').val()) || 0;
        const passed = parseInt($('#quantity_passed').val()) || 0;
        const failed = parseInt($('#quantity_failed').val()) || 0;
        
        if ((passed + failed) !== checked) {
            e.preventDefault();
            alert('Total Quantity Passed + Failed harus sama dengan Quantity Checked!\n\nChecked: ' + checked + '\nPassed: ' + passed + '\nFailed: ' + failed);
            return false;
        }
    });

    // Auto-calculate on input
    $('#quantity_passed, #quantity_failed').on('input', function() {
        const checked = parseInt($('#quantity_checked').val()) || 0;
        const passed = parseInt($('#quantity_passed').val()) || 0;
        const failed = parseInt($('#quantity_failed').val()) || 0;
        const total = passed + failed;
        
        if (total > checked) {
            $(this).addClass('border-warning');
        } else if (total === checked) {
            $('#quantity_passed, #quantity_failed').removeClass('border-warning').addClass('border-success');
        } else {
            $(this).removeClass('border-warning border-success');
        }
    });

    // Preview uploaded photos
    $('#foto_hasil_check').on('change', function(e) {
        const files = e.target.files;
        $('#foto_preview').html('');
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                $('#foto_preview').append(`
                    <div class="col-md-3 mb-2">
                        <img src="${e.target.result}" class="img-thumbnail" alt="Preview">
                        <small class="d-block text-center mt-1">${file.name}</small>
                    </div>
                `);
            }
            
            reader.readAsDataURL(file);
        }
    });

    // Preview uploaded documents
    $('#dokumen_quality').on('change', function(e) {
        const files = e.target.files;
        $('#doc_preview').html('');
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            $('#doc_preview').append(`
                <div class="col-md-12 mb-2">
                    <div class="alert alert-secondary py-2">
                        <i class="bi bi-file-earmark-pdf me-2"></i>${file.name} (${(file.size / 1024).toFixed(2)} KB)
                    </div>
                </div>
            `);
        }
    });
});
</script>
@endpush
@endsection
