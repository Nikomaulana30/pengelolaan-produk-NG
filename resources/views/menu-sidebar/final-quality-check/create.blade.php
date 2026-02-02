@extends('layouts.app')

@section('title', 'Create Final Quality Check')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Create Final Decision</h3>
                <p class="text-subtitle text-muted">Buat keputusan final untuk pengiriman ulang ke customer</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('final-quality-check.index') }}">Final Decision</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Final Decision</h4>
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

                @if($availableReworks->isEmpty())
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Tidak ada production rework yang siap untuk final decision.</strong><br>
                        Production rework harus berstatus "Sent to Warehouse" terlebih dahulu.
                    </div>
                    <a href="{{ route('production-rework.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Lihat Production Rework
                    </a>
                @else
                    <form action="{{ route('final-quality-check.store') }}" method="POST" enctype="multipart/form-data" id="finalCheckForm">
                        @csrf

                        <!-- Production Rework Selection -->
                        <h5 class="mb-3"><i class="bi bi-box-seam me-2"></i>Pilih Production Rework</h5>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="production_rework_id" class="form-label required">Production Rework</label>
                                <select name="production_rework_id" id="production_rework_id" class="form-select select2" required>
                                    <option value="">-- Pilih Production Rework --</option>
                                    @foreach($availableReworks as $rework)
                                        <option value="{{ $rework->id }}" 
                                                data-nomor-rework="{{ $rework->nomor_rework }}"
                                                data-customer="{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}"
                                                data-produk="{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}"
                                                data-quantity-ok="{{ $rework->quantity_hasil_ok ?? 0 }}"
                                                data-quantity-ng="{{ $rework->quantity_hasil_ng ?? 0 }}"
                                                data-tanggal-selesai="{{ $rework->tanggal_selesai_rework ? $rework->tanggal_selesai_rework->format('d M Y') : '-' }}"
                                                {{ old('production_rework_id') == $rework->id ? 'selected' : '' }}>
                                            {{ $rework->nomor_rework }} - 
                                            {{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }} -
                                            {{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('production_rework_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Rework Info Display -->
                        <div id="rework_info" class="alert alert-info d-none mb-4">
                            <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Informasi Production Rework</h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Nomor Rework:</strong>
                                    <p class="mb-1" id="info_nomor_rework">-</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Customer:</strong>
                                    <p class="mb-1" id="info_customer">-</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Produk:</strong>
                                    <p class="mb-1" id="info_produk">-</p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <strong>Quantity OK:</strong>
                                    <p class="mb-0 text-success" id="info_qty_ok">-</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Quantity NG:</strong>
                                    <p class="mb-0 text-danger" id="info_qty_ng">-</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Tanggal Selesai:</strong>
                                    <p class="mb-0" id="info_tanggal">-</p>
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
                                       value="{{ old('tanggal_check', date('Y-m-d')) }}" required>
                                @error('tanggal_check')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity_checked" class="form-label required">Quantity yang Diperiksa</label>
                                <input type="number" class="form-control" name="quantity_checked" id="quantity_checked" 
                                       value="{{ old('quantity_checked') }}" min="1" required>
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
                                       value="{{ old('quantity_passed') }}" min="0" required>
                                <small class="text-success">Quantity yang lolos pemeriksaan</small>
                                @error('quantity_passed')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity_failed" class="form-label required">Quantity Failed</label>
                                <input type="number" class="form-control border-danger" name="quantity_failed" id="quantity_failed" 
                                       value="{{ old('quantity_failed') }}" min="0" required>
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
                                          placeholder="Deskripsikan hasil pemeriksaan final...">{{ old('hasil_pemeriksaan') }}</textarea>
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
                                          placeholder="Catatan tambahan mengenai quality...">{{ old('catatan_quality') }}</textarea>
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
                                    <option value="approved_for_shipment" {{ old('keputusan_final') == 'approved_for_shipment' ? 'selected' : '' }}>
                                        ✅ Approved for Shipment (Siap dikirim ke customer)
                                    </option>
                                    <option value="need_rework" {{ old('keputusan_final') == 'need_rework' ? 'selected' : '' }}>
                                        🔄 Need Rework (Perlu perbaikan ulang)
                                    </option>
                                    <option value="rejected" {{ old('keputusan_final') == 'rejected' ? 'selected' : '' }}>
                                        ❌ Rejected (Ditolak/Scrap)
                                    </option>
                                </select>
                                @error('keputusan_final')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Documents Upload -->
                        <h5 class="mb-3"><i class="bi bi-file-earmark-image me-2"></i>Dokumen & Foto</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="foto_hasil_check" class="form-label">Upload Foto Hasil Check</label>
                                <input type="file" class="form-control" name="foto_hasil_check[]" id="foto_hasil_check" 
                                       multiple accept="image/jpeg,image/png,image/jpg">
                                <small class="text-muted">Format: JPG, PNG. Max 2MB per file. Bisa multiple.</small>
                                @error('foto_hasil_check.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dokumen_quality" class="form-label">Upload Dokumen Quality</label>
                                <input type="file" class="form-control" name="dokumen_quality[]" id="dokumen_quality" 
                                       multiple accept=".pdf,.doc,.docx">
                                <small class="text-muted">Format: PDF, DOC, DOCX. Max 5MB per file. Bisa multiple.</small>
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
                                    <i class="bi bi-save me-2"></i>Simpan Final Decision
                                </button>
                                <a href="{{ route('final-quality-check.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>

@push('scripts')
<!-- jQuery (REQUIRED for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#production_rework_id').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Pilih Production Rework --'
    });

    // Show rework info when selected
    $('#production_rework_id').on('select2:select', function(e) {
        const selected = $(this).find(':selected');
        
        $('#info_nomor_rework').text(selected.attr('data-nomor-rework'));
        $('#info_customer').text(selected.attr('data-customer'));
        $('#info_produk').text(selected.attr('data-produk'));
        $('#info_qty_ok').text(selected.attr('data-quantity-ok') + ' pcs');
        $('#info_qty_ng').text(selected.attr('data-quantity-ng') + ' pcs');
        $('#info_tanggal').text(selected.attr('data-tanggal-selesai'));
        
        $('#rework_info').removeClass('d-none');
        
        // Auto-fill quantity_checked with quantity_ok
        const qtyOk = parseInt(selected.attr('data-quantity-ok')) || 0;
        $('#quantity_checked').val(qtyOk);
    });

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
