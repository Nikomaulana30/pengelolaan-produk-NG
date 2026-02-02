@extends('layouts.app')

@section('title', 'Buat Quality Reinspection')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Quality Reinspection</h3>
                <p class="text-subtitle text-muted">Inspeksi ulang barang NG dari warehouse verification</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('quality-reinspection.index') }}">Quality Reinspection</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buat Inspeksi</li>
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
                <h4 class="card-title">Form Quality Reinspection</h4>
            </div>
            <div class="card-body">
                @if($availableVerifications->isEmpty())
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Tidak ada warehouse verification yang siap untuk quality reinspection. Warehouse verification harus sudah dikirim ke quality terlebih dahulu.
                    </div>
                    <a href="{{ route('warehouse-verification.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>Ke Warehouse Verification
                    </a>
                @else
                    <form action="{{ route('quality-reinspection.store') }}" method="POST" enctype="multipart/form-data" id="inspectionForm">
                        @csrf

                        <!-- Pilih Warehouse Verification -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">
                                            <i class="bi bi-check-square me-2"></i>Pilih Warehouse Verification
                                        </h5>
                                        <div class="form-group">
                                            <label for="warehouse_verification_id" class="form-label">Warehouse Verification <span class="text-danger">*</span></label>
                                            <select class="form-select select2" id="warehouse_verification_id" name="warehouse_verification_id" required>
                                                <option value="">-- Pilih Warehouse Verification --</option>
                                                @foreach($availableVerifications as $verification)
                                                    <option value="{{ $verification->id }}" 
                                                            data-nomor="{{ $verification->nomor_verifikasi }}"
                                                            data-customer="{{ $verification->dokumenRetur->customerComplaint->nama_customer }}"
                                                            data-produk="{{ $verification->dokumenRetur->customerComplaint->produk }}"
                                                            data-qty-ng="{{ $verification->quantity_ng_confirmed }}"
                                                            data-qty-ok="{{ $verification->quantity_ok }}"
                                                            data-complaint-desc="{{ $verification->dokumenRetur->customerComplaint->deskripsi_complaint }}"
                                                            data-kondisi="{{ $verification->kondisi_fisik_barang }}">
                                                        {{ $verification->nomor_verifikasi }} - {{ $verification->dokumenRetur->customerComplaint->nama_customer }} - {{ $verification->dokumenRetur->customerComplaint->produk }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('warehouse_verification_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Detail Verification (Auto-populate) -->
                                        <div id="verificationDetail" style="display: none;" class="mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <strong>Customer:</strong>
                                                        <span id="detail_customer" class="text-muted"></span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Produk:</strong>
                                                        <span id="detail_produk" class="text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <strong>Quantity NG Confirmed:</strong>
                                                        <span id="detail_qty_ng" class="text-danger fw-bold"></span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Quantity OK:</strong>
                                                        <span id="detail_qty_ok" class="text-success fw-bold"></span>
                                                    </div>
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
                                           value="{{ old('tanggal_inspeksi', date('Y-m-d')) }}" required>
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
                                        <option value="critical" {{ old('severity_level') == 'critical' ? 'selected' : '' }}>Critical</option>
                                        <option value="major" {{ old('severity_level') == 'major' ? 'selected' : '' }}>Major</option>
                                        <option value="minor" {{ old('severity_level') == 'minor' ? 'selected' : '' }}>Minor</option>
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
                                           value="{{ old('jenis_defect') }}" required>
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
                                           value="{{ old('quantity_defect') }}" required>
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
                                              required>{{ old('deskripsi_defect') }}</textarea>
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
                                              id="root_cause_analysis" name="root_cause_analysis" rows="4" 
                                              placeholder="Analisis penyebab akar masalah (5 Why, Fishbone, dll)"
                                              required>{{ old('root_cause_analysis') }}</textarea>
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
                                              id="corrective_action" name="corrective_action" rows="3" 
                                              placeholder="Tindakan perbaikan untuk mengatasi masalah saat ini"
                                              required>{{ old('corrective_action') }}</textarea>
                                    @error('corrective_action')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="preventive_action" class="form-label">Preventive Action <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('preventive_action') is-invalid @enderror" 
                                              id="preventive_action" name="preventive_action" rows="3" 
                                              placeholder="Tindakan pencegahan agar tidak terulang di masa depan"
                                              required>{{ old('preventive_action') }}</textarea>
                                    @error('preventive_action')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Disposisi & Estimasi Biaya -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="disposisi" class="form-label">Disposisi <span class="text-danger">*</span></label>
                                    <select class="form-select @error('disposisi') is-invalid @enderror" id="disposisi" name="disposisi" required>
                                        <option value="">-- Pilih Disposisi --</option>
                                        <option value="rework" {{ old('disposisi') == 'rework' ? 'selected' : '' }}>Rework</option>
                                        <option value="scrap" {{ old('disposisi') == 'scrap' ? 'selected' : '' }}>Scrap</option>
                                        <option value="return_to_vendor" {{ old('disposisi') == 'return_to_vendor' ? 'selected' : '' }}>Return to Vendor</option>
                                        <option value="return_to_customer" {{ old('disposisi') == 'return_to_customer' ? 'selected' : '' }}>Return to Customer</option>
                                    </select>
                                    @error('disposisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="estimasi_biaya_rework" class="form-label">Estimasi Biaya Rework</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('estimasi_biaya_rework') is-invalid @enderror" 
                                               id="estimasi_biaya_rework" name="estimasi_biaya_rework" min="0" step="0.01"
                                               placeholder="0.00" 
                                               value="{{ old('estimasi_biaya_rework') }}">
                                    </div>
                                    @error('estimasi_biaya_rework')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Opsional: Estimasi biaya untuk rework</small>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Defect -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="foto_defect" class="form-label">Foto Defect</label>
                                    <input type="file" class="form-control @error('foto_defect.*') is-invalid @enderror" 
                                           id="foto_defect" name="foto_defect[]" accept="image/*" multiple>
                                    @error('foto_defect.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Upload foto defect (Multiple files, Max: 2MB per file)</small>
                                </div>
                                <div id="imagePreview" class="row mt-2"></div>
                            </div>
                        </div>

                        <!-- Dokumen RCA -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="dokumen_rca" class="form-label">Dokumen RCA</label>
                                    <input type="file" class="form-control @error('dokumen_rca.*') is-invalid @enderror" 
                                           id="dokumen_rca" name="dokumen_rca[]" accept=".pdf,.doc,.docx" multiple>
                                    @error('dokumen_rca.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Upload dokumen RCA (PDF, DOC, DOCX, Max: 5MB per file)</small>
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
                                        <button type="submit" name="action" value="draft" class="btn btn-warning me-2">
                                            <i class="bi bi-save me-2"></i>Simpan sebagai Draft
                                        </button>
                                        <button type="submit" name="action" value="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-2"></i>Submit Inspeksi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Pilih Warehouse Verification --'
    });

    // Show verification details when selected - Use select2:select event
    $('#warehouse_verification_id').on('select2:select change', function(e) {
        const selectedOption = $(this).find('option:selected');
        const selectedVal = selectedOption.val();
        
        console.log('Selected:', selectedVal); // Debug log
        
        if (selectedVal) {
            // Display info
            const customer = selectedOption.attr('data-customer');
            const produk = selectedOption.attr('data-produk');
            const qtyNg = selectedOption.attr('data-qty-ng');
            const qtyOk = selectedOption.attr('data-qty-ok');
            const complaintDesc = selectedOption.attr('data-complaint-desc');
            const kondisi = selectedOption.attr('data-kondisi');
            
            console.log('Data:', {customer, produk, qtyNg, qtyOk, complaintDesc, kondisi}); // Debug
            
            $('#detail_customer').text(customer);
            $('#detail_produk').text(produk);
            $('#detail_qty_ng').text(qtyNg + ' pcs');
            $('#detail_qty_ok').text(qtyOk + ' pcs');
            $('#verificationDetail').slideDown();
            
            // Auto-populate form fields
            // Set jenis defect placeholder dari nama produk
            if (produk) {
                $('#jenis_defect').attr('placeholder', 'Berdasarkan produk: ' + produk);
            }
            
            // Set quantity defect dengan qty NG confirmed
            if (qtyNg) {
                $('#quantity_defect').val(qtyNg).attr('max', qtyNg);
            }
            
            // Build deskripsi defect dari complaint dan kondisi warehouse
            let deskripsiDefect = '';
            
            if (complaintDesc) {
                deskripsiDefect = 'Complaint Customer:\n' + complaintDesc;
            }
            
            if (kondisi) {
                if (deskripsiDefect) {
                    deskripsiDefect += '\n\n';
                }
                deskripsiDefect += 'Kondisi dari Warehouse Verification:\n' + kondisi;
            }
            
            if (deskripsiDefect) {
                $('#deskripsi_defect').val(deskripsiDefect);
            }
            
            console.log('Auto-populated successfully'); // Debug
        } else {
            $('#verificationDetail').slideUp();
            // Clear auto-populated fields
            $('#jenis_defect').val('').attr('placeholder', 'Contoh: Crack, Scratch, Dent');
            $('#quantity_defect').val('').removeAttr('max');
            $('#deskripsi_defect').val('');
        }
    });

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
</script>
@endpush
@endsection
