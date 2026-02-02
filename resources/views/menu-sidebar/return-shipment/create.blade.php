@extends('layouts.app')

@section('title', 'Create Return Shipment')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Create Return Shipment</h3>
                <p class="text-subtitle text-muted">Buat pengiriman kembali ke customer</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('return-shipment.index') }}">Return Shipment</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Return Shipment</h4>
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

                @if($availableQualityChecks->isEmpty())
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Tidak ada final quality check yang siap untuk dikirim.</strong><br>
                        Final quality check harus memiliki keputusan "Approved for Shipment" terlebih dahulu.
                    </div>
                    <a href="{{ route('final-quality-check.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Lihat Final Quality Check
                    </a>
                @else
                    <form action="{{ route('return-shipment.store') }}" method="POST" enctype="multipart/form-data" id="shipmentForm">
                        @csrf

                        <!-- Final Quality Check Selection -->
                        <h5 class="mb-3"><i class="bi bi-clipboard-check me-2"></i>Pilih Final Quality Check</h5>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="final_quality_check_id" class="form-label required">Final Quality Check</label>
                                <select name="final_quality_check_id" id="final_quality_check_id" class="form-select select2" required>
                                    <option value="">-- Pilih Final Quality Check --</option>
                                    @foreach($availableQualityChecks as $qc)
                                        <option value="{{ $qc->id }}" 
                                                data-customer="{{ $qc->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}"
                                                data-produk="{{ $qc->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}"
                                                data-quantity-passed="{{ $qc->quantity_passed }}"
                                                data-alamat="{{ $qc->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->alamat_customer ?? '' }}"
                                                {{ old('final_quality_check_id') == $qc->id ? 'selected' : '' }}
                                                {{ request('final_check_id') == $qc->id ? 'selected' : '' }}>
                                            #{{ $qc->id }} - 
                                            {{ $qc->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }} -
                                            {{ $qc->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}
                                            ({{ $qc->quantity_passed }} pcs passed)
                                        </option>
                                    @endforeach
                                </select>
                                @error('final_quality_check_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Quality Check Info Display -->
                        <div id="qc_info" class="alert alert-info d-none mb-4">
                            <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Informasi Quality Check</h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Customer:</strong>
                                    <p class="mb-1" id="info_customer">-</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Produk:</strong>
                                    <p class="mb-1" id="info_produk">-</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Quantity Passed:</strong>
                                    <p class="mb-0 text-success" id="info_qty_passed">-</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Shipment Details -->
                        <h5 class="mb-3"><i class="bi bi-truck me-2"></i>Detail Pengiriman</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_pengiriman" class="form-label required">Tanggal Pengiriman</label>
                                <input type="date" class="form-control" name="tanggal_pengiriman" id="tanggal_pengiriman" 
                                       value="{{ old('tanggal_pengiriman', date('Y-m-d')) }}" required>
                                @error('tanggal_pengiriman')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity_shipped" class="form-label required">Quantity yang Dikirim</label>
                                <input type="number" class="form-control" name="quantity_shipped" id="quantity_shipped" 
                                       value="{{ old('quantity_shipped') }}" min="1" required>
                                <small class="text-muted">Quantity yang akan dikirim ke customer</small>
                                @error('quantity_shipped')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ekspedisi" class="form-label required">Ekspedisi</label>
                                <select name="ekspedisi" id="ekspedisi" class="form-select" required>
                                    <option value="">-- Pilih Ekspedisi --</option>
                                    <option value="JNE" {{ old('ekspedisi') == 'JNE' ? 'selected' : '' }}>JNE</option>
                                    <option value="TIKI" {{ old('ekspedisi') == 'TIKI' ? 'selected' : '' }}>TIKI</option>
                                    <option value="POS Indonesia" {{ old('ekspedisi') == 'POS Indonesia' ? 'selected' : '' }}>POS Indonesia</option>
                                    <option value="J&T Express" {{ old('ekspedisi') == 'J&T Express' ? 'selected' : '' }}>J&T Express</option>
                                    <option value="SiCepat" {{ old('ekspedisi') == 'SiCepat' ? 'selected' : '' }}>SiCepat</option>
                                    <option value="AnterAja" {{ old('ekspedisi') == 'AnterAja' ? 'selected' : '' }}>AnterAja</option>
                                    <option value="Ninja Express" {{ old('ekspedisi') == 'Ninja Express' ? 'selected' : '' }}>Ninja Express</option>
                                    <option value="Lion Parcel" {{ old('ekspedisi') == 'Lion Parcel' ? 'selected' : '' }}>Lion Parcel</option>
                                    <option value="Other" {{ old('ekspedisi') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('ekspedisi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nomor_resi" class="form-label">Nomor Resi</label>
                                <input type="text" class="form-control" name="nomor_resi" id="nomor_resi" 
                                       value="{{ old('nomor_resi') }}" placeholder="Masukkan nomor resi pengiriman">
                                <small class="text-muted">Opsional, bisa diisi kemudian</small>
                                @error('nomor_resi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="biaya_pengiriman" class="form-label">Biaya Pengiriman</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="biaya_pengiriman" id="biaya_pengiriman" 
                                           value="{{ old('biaya_pengiriman') }}" min="0" step="1000" placeholder="0">
                                </div>
                                <small class="text-muted">Opsional</small>
                                @error('biaya_pengiriman')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="alamat_pengiriman" class="form-label required">Alamat Pengiriman</label>
                                <textarea name="alamat_pengiriman" id="alamat_pengiriman" rows="3" 
                                          class="form-control" required 
                                          placeholder="Alamat lengkap customer...">{{ old('alamat_pengiriman') }}</textarea>
                                @error('alamat_pengiriman')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="catatan_pengiriman" class="form-label">Catatan Pengiriman</label>
                                <textarea name="catatan_pengiriman" id="catatan_pengiriman" rows="3" 
                                          class="form-control" 
                                          placeholder="Catatan tambahan untuk pengiriman...">{{ old('catatan_pengiriman') }}</textarea>
                                @error('catatan_pengiriman')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Documents Upload -->
                        <h5 class="mb-3"><i class="bi bi-file-earmark-text me-2"></i>Dokumen Pengiriman</h5>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="dokumen_pengiriman" class="form-label">Upload Dokumen Pengiriman</label>
                                <input type="file" class="form-control" name="dokumen_pengiriman[]" id="dokumen_pengiriman" 
                                       multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Format: PDF, DOC, DOCX, JPG, PNG. Max 5MB per file. Bisa multiple (surat jalan, packing list, dll).</small>
                                @error('dokumen_pengiriman.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row" id="doc_preview"></div>

                        <hr class="my-4">

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Simpan Return Shipment
                                </button>
                                <a href="{{ route('return-shipment.index') }}" class="btn btn-secondary">
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#final_quality_check_id').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Pilih Final Quality Check --'
    });

    // Show quality check info when selected
    $('#final_quality_check_id').on('select2:select', function(e) {
        const selected = $(this).find(':selected');
        
        $('#info_customer').text(selected.attr('data-customer'));
        $('#info_produk').text(selected.attr('data-produk'));
        $('#info_qty_passed').text(selected.attr('data-quantity-passed') + ' pcs');
        
        $('#qc_info').removeClass('d-none');
        
        // Auto-fill quantity_shipped with quantity_passed
        const qtyPassed = parseInt(selected.attr('data-quantity-passed')) || 0;
        $('#quantity_shipped').val(qtyPassed);
        
        // Auto-fill alamat_pengiriman
        const alamat = selected.attr('data-alamat');
        if (alamat) {
            $('#alamat_pengiriman').val(alamat);
        }
    });
    
    // Trigger select2:select if there's a pre-selected value (from query param)
    if ($('#final_quality_check_id').val()) {
        $('#final_quality_check_id').trigger('select2:select');
    }

    // Validate quantity shipped
    $('#shipmentForm').on('submit', function(e) {
        const selected = $('#final_quality_check_id').find(':selected');
        const qtyPassed = parseInt(selected.attr('data-quantity-passed')) || 0;
        const qtyShipped = parseInt($('#quantity_shipped').val()) || 0;
        
        if (qtyShipped > qtyPassed) {
            e.preventDefault();
            alert('Quantity yang dikirim tidak boleh lebih dari quantity passed!\n\nPassed: ' + qtyPassed + '\nShipped: ' + qtyShipped);
            return false;
        }
    });

    // Preview uploaded documents
    $('#dokumen_pengiriman').on('change', function(e) {
        const files = e.target.files;
        $('#doc_preview').html('');
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const isImage = file.type.startsWith('image/');
            
            if (isImage) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#doc_preview').append(`
                        <div class="col-md-3 mb-2">
                            <img src="${e.target.result}" class="img-thumbnail" alt="Preview">
                            <small class="d-block text-center mt-1">${file.name}</small>
                        </div>
                    `);
                }
                reader.readAsDataURL(file);
            } else {
                $('#doc_preview').append(`
                    <div class="col-md-12 mb-2">
                        <div class="alert alert-secondary py-2">
                            <i class="bi bi-file-earmark-pdf me-2"></i>${file.name} (${(file.size / 1024).toFixed(2)} KB)
                        </div>
                    </div>
                `);
            }
        }
    });

    // Format currency input
    $('#biaya_pengiriman').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        $(this).val(value);
    });
});
</script>
@endpush
@endsection
