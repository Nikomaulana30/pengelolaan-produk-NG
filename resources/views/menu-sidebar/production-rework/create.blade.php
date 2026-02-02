@extends('layouts.app')

@section('title', 'Start Production Rework')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Start Production Rework</h3>
                <p class="text-subtitle text-muted">Proses rework produk berdasarkan hasil quality reinspection</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('production-rework.index') }}">Production Rework</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Production Rework</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('production-rework.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Quality Reinspection Selection -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="quality_reinspection_id" class="form-label required">Quality Reinspection</label>
                            <select name="quality_reinspection_id" id="quality_reinspection_id" 
                                    class="form-select" required>
                                <option value="">-- Pilih Quality Reinspection --</option>
                                @forelse($availableInspections as $inspection)
                                    <option value="{{ $inspection->id }}"
                                            data-nomor-inspeksi="{{ $inspection->nomor_inspeksi }}"
                                            data-customer="{{ $inspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer ?? '' }}"
                                            data-produk="{{ $inspection->warehouseVerification->dokumenRetur->customerComplaint->produk ?? '' }}"
                                            data-qty-defect="{{ $inspection->quantity_defect }}"
                                            data-jenis-defect="{{ $inspection->jenis_defect }}"
                                            data-deskripsi-defect="{{ $inspection->deskripsi_defect }}"
                                            data-rca="{{ $inspection->root_cause_analysis }}"
                                            data-corrective="{{ $inspection->corrective_action }}"
                                            data-preventive="{{ $inspection->preventive_action }}"
                                            data-estimasi-biaya="{{ $inspection->estimasi_biaya_rework ?? 0 }}">
                                        {{ $inspection->nomor_inspeksi }} - 
                                        {{ $inspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }} - 
                                        {{ $inspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}
                                    </option>
                                @empty
                                    <option value="" disabled>Belum ada quality reinspection yang siap untuk rework</option>
                                @endforelse
                            </select>
                            <small class="text-muted">
                                @if($availableInspections->count() > 0)
                                    Pilih quality reinspection yang sudah approved untuk dirework
                                @else
                                    <span class="text-warning">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Belum ada quality reinspection dengan status "sent_to_production" dan disposisi "rework"
                                    </span>
                                @endif
                            </small>
                            @error('quality_reinspection_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Auto-populated Customer & Product Info -->
                    <div class="alert alert-info" id="info_alert" style="display: none;">
                        <h5 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Informasi dari Quality Reinspection</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Customer:</strong>
                                <p class="mb-0" id="display_customer_text">-</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Produk:</strong>
                                <p class="mb-0" id="display_produk_text">-</p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 mb-2">
                                <strong>Nomor Reinspection:</strong>
                                <p class="mb-0" id="display_nomor_reinspeksi">-</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Quantity Defect:</strong>
                                <p class="mb-0 text-danger" id="display_qty_defect_text">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" id="display_customer">
                    <input type="hidden" id="display_produk">

                    <hr class="my-4">

                    <!-- Rework Details -->
                    <h5 class="mb-3"><i class="bi bi-gear-fill me-2"></i>Rework Details</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai_rework" class="form-label required">Tanggal Mulai Rework</label>
                            <input type="date" class="form-control" name="tanggal_mulai_rework" 
                                   id="tanggal_mulai_rework" value="{{ old('tanggal_mulai_rework', date('Y-m-d')) }}" required>
                            @error('tanggal_mulai_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="metode_rework" class="form-label required">Metode Rework</label>
                            <select name="metode_rework" id="metode_rework" class="form-select" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="melting" {{ old('metode_rework') == 'melting' ? 'selected' : '' }}>
                                    <i class="bi bi-fire"></i> Melting (Peleburan)
                                </option>
                                <option value="welding" {{ old('metode_rework') == 'welding' ? 'selected' : '' }}>
                                    <i class="bi bi-lightning"></i> Welding (Pengelasan)
                                </option>
                                <option value="machining" {{ old('metode_rework') == 'machining' ? 'selected' : '' }}>
                                    <i class="bi bi-cpu"></i> Machining (Permesinan)
                                </option>
                                <option value="surface_treatment" {{ old('metode_rework') == 'surface_treatment' ? 'selected' : '' }}>
                                    <i class="bi bi-palette"></i> Surface Treatment
                                </option>
                                <option value="assembly" {{ old('metode_rework') == 'assembly' ? 'selected' : '' }}>
                                    <i class="bi bi-puzzle"></i> Assembly (Perakitan)
                                </option>
                                <option value="other" {{ old('metode_rework') == 'other' ? 'selected' : '' }}>
                                    <i class="bi bi-three-dots"></i> Other (Lainnya)
                                </option>
                            </select>
                            @error('metode_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="deskripsi_rework" class="form-label required">
                                <i class="bi bi-pencil me-1"></i>Deskripsi Rework <span class="badge bg-warning">Editable</span>
                            </label>
                            <textarea name="deskripsi_rework" id="deskripsi_rework" rows="4" 
                                      class="form-control border-primary" 
                                      placeholder="Tuliskan detail proses rework yang akan dilakukan. Field ini akan otomatis terisi sebagai suggestion dari RCA, tapi Anda bisa edit sesuai kebutuhan..." 
                                      required>{{ old('deskripsi_rework') }}</textarea>
                            <small class="text-primary"><i class="bi bi-info-circle me-1"></i>Field ini bisa Anda edit. Auto-fill dari RCA hanya sebagai panduan.</small>
                            @error('deskripsi_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="instruksi_rework" class="form-label required">
                                <i class="bi bi-pencil me-1"></i>Instruksi Rework <span class="badge bg-warning">Editable</span>
                            </label>
                            <textarea name="instruksi_rework" id="instruksi_rework" rows="5" 
                                      class="form-control border-primary" 
                                      placeholder="Tuliskan instruksi teknis step-by-step untuk operator. Field ini akan otomatis terisi sebagai suggestion, tapi Anda bisa sesuaikan..." 
                                      required>{{ old('instruksi_rework') }}</textarea>
                            <small class="text-primary"><i class="bi bi-info-circle me-1"></i>Field ini bisa Anda edit bebas. Auto-fill hanya sebagai template awal.</small>
                            @error('instruksi_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Quantity & Estimations -->
                    <h5 class="mb-3"><i class="bi bi-calculator me-2"></i>Quantity & Estimasi</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity_rework" class="form-label required">Quantity Rework</label>
                            <input type="number" class="form-control" name="quantity_rework" 
                                   id="quantity_rework" value="{{ old('quantity_rework') }}" min="1" required>
                            <small class="text-muted">Jumlah produk yang akan dirework</small>
                            @error('quantity_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="pic_rework" class="form-label">PIC Rework</label>
                            <input type="text" class="form-control" name="pic_rework" 
                                   id="pic_rework" value="{{ old('pic_rework') }}" placeholder="Nama PIC/Operator">
                            <small class="text-muted">Person in Charge untuk proses rework ini</small>
                            @error('pic_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estimasi_biaya" class="form-label required">Estimasi Biaya (Rp)</label>
                            <input type="number" class="form-control" name="estimasi_biaya" 
                                   id="estimasi_biaya" value="{{ old('estimasi_biaya') }}" 
                                   step="0.01" min="0" required>
                            <small class="text-muted">Estimasi biaya total rework (material + labor)</small>
                            @error('estimasi_biaya')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="estimasi_waktu_hari" class="form-label required">Estimasi Waktu (Hari)</label>
                            <input type="number" class="form-control" name="estimasi_waktu_hari" 
                                   id="estimasi_waktu_hari" value="{{ old('estimasi_waktu_hari', 1) }}" 
                                   min="1" required>
                            <small class="text-muted">Estimasi waktu penyelesaian dalam hari kerja</small>
                            @error('estimasi_waktu_hari')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Reference Information (Auto-filled from Quality Reinspection) -->
                    <h5 class="mb-3">
                        <i class="bi bi-clipboard-data me-2"></i>Informasi Referensi dari Quality Inspection
                        <span class="badge bg-secondary">Read-Only (Referensi)</span>
                    </h5>
                    
                    <div class="alert alert-warning" id="rca_alert" style="display: none;">
                        <p class="mb-2"><i class="bi bi-exclamation-triangle me-2"></i><strong>Informasi di bawah HANYA untuk referensi:</strong></p>
                        <ul class="mb-0">
                            <li>Data ini dari hasil Quality Reinspection (tidak bisa diedit)</li>
                            <li>Gunakan informasi RCA sebagai panduan untuk mengisi Deskripsi & Instruksi Rework di atas</li>
                            <li>Field Deskripsi & Instruksi Rework (yang berwarna biru) bisa Anda edit sesuai kebutuhan</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label"><i class="bi bi-bug me-1 text-muted"></i>Jenis & Deskripsi Defect (Referensi)</label>
                            <textarea class="form-control bg-light text-muted" id="display_defect_info" rows="3" readonly 
                                      style="cursor: not-allowed;"
                                      placeholder="Informasi defect akan muncul setelah memilih quality reinspection..."></textarea>
                            <small class="text-muted"><i class="bi bi-lock me-1"></i>Read-only: Informasi dari hasil quality inspection</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label"><i class="bi bi-search me-1 text-muted"></i>Root Cause Analysis (Referensi)</label>
                            <textarea class="form-control bg-light text-muted" id="display_rca" rows="3" readonly
                                      style="cursor: not-allowed;"
                                      placeholder="Analisis penyebab akar masalah akan muncul di sini..."></textarea>
                            <small class="text-muted"><i class="bi bi-lock me-1"></i>Read-only: Hasil analisis penyebab defect dari quality team</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-tools me-1 text-muted"></i>Corrective Action (Referensi)</label>
                            <textarea class="form-control bg-light text-muted" id="display_corrective" rows="3" readonly
                                      style="cursor: not-allowed;"
                                      placeholder="Tindakan perbaikan yang direkomendasikan..."></textarea>
                            <small class="text-muted"><i class="bi bi-lock me-1"></i>Read-only: Rekomendasi dari Quality Team</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-shield-check me-1 text-muted"></i>Preventive Action (Referensi)</label>
                            <textarea class="form-control bg-light text-muted" id="display_preventive" rows="3" readonly
                                      style="cursor: not-allowed;"
                                      placeholder="Tindakan pencegahan untuk masa depan..."></textarea>
                            <small class="text-muted"><i class="bi bi-lock me-1"></i>Read-only: Rekomendasi dari Quality Team</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Document Uploads -->
                    <h5 class="mb-3"><i class="bi bi-file-earmark-text me-2"></i>Dokumen Proses</h5>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="dokumen_proses" class="form-label">Upload Dokumen Proses</label>
                            <input type="file" class="form-control" name="dokumen_proses[]" 
                                   id="dokumen_proses" multiple 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-muted">
                                Upload work instruction, process flow, atau foto proses. 
                                Format: PDF, DOC, DOCX, JPG, PNG. Max 5MB per file.
                            </small>
                            @error('dokumen_proses.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- File Preview -->
                    <div class="row" id="file_preview"></div>

                    <hr class="my-4">

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Create Production Rework
                            </button>
                            <a href="{{ route('production-rework.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

@endsection

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#quality_reinspection_id').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Pilih Quality Reinspection...'
    });

    // Function to populate form
    function populateFormData() {
        const selectedOption = $('#quality_reinspection_id option:selected');
        const selectedValue = selectedOption.val();
        
        console.log('Selected value:', selectedValue);
        
        if (selectedValue && selectedValue !== '') {
            // Show info alerts
            $('#info_alert').slideDown();
            $('#rca_alert').slideDown();
            
            // Basic info
            const customer = selectedOption.attr('data-customer') || '';
            const produk = selectedOption.attr('data-produk') || '';
            const nomorReinspeksi = selectedOption.text().split(' - ')[0] || '';
            const qtyDefect = selectedOption.attr('data-qty-defect') || '';
            
            console.log('Customer:', customer, 'Produk:', produk);
            
            $('#display_customer').val(customer);
            $('#display_produk').val(produk);
            $('#display_customer_text').text(customer);
            $('#display_produk_text').text(produk);
            $('#display_nomor_reinspeksi').text(nomorReinspeksi);
            $('#display_qty_defect_text').text(qtyDefect + ' unit');
            
            // Quantity
            $('#quantity_rework').val(qtyDefect);
            
            // Defect info
            const jenisDefect = selectedOption.attr('data-jenis-defect') || '';
            const deskripsiDefect = selectedOption.attr('data-deskripsi-defect') || '';
            $('#display_defect_info').val('Jenis Defect: ' + jenisDefect + '\n\nDeskripsi:\n' + deskripsiDefect);
            
            // RCA info
            const rca = selectedOption.attr('data-rca') || '';
            const corrective = selectedOption.attr('data-corrective') || '';
            const preventive = selectedOption.attr('data-preventive') || '';
            
            $('#display_rca').val(rca);
            $('#display_corrective').val(corrective);
            $('#display_preventive').val(preventive);
            
            // Auto-fill deskripsi rework dengan suggestion dari RCA (TAPI TETAP BISA DIEDIT)
            if (!$('#deskripsi_rework').val()) {
                const suggestion = 'Berdasarkan RCA:\n' + rca + '\n\nRework akan dilakukan dengan:\n' + corrective;
                $('#deskripsi_rework').val(suggestion);
                
                // Flash animation untuk menunjukkan field bisa diedit
                $('#deskripsi_rework').addClass('border-warning').css('background-color', '#fff8dc');
                setTimeout(() => {
                    $('#deskripsi_rework').removeClass('border-warning').css('background-color', '');
                }, 3000);
            }
            
            // Auto-fill instruksi rework dengan suggestion dari corrective action (TAPI TETAP BISA DIEDIT)
            if (!$('#instruksi_rework').val()) {
                const instruction = 'Instruksi Rework:\n\n1. ' + corrective + '\n\n2. Pastikan semua langkah preventive action diterapkan:\n   ' + preventive + '\n\n3. Lakukan quality check setiap tahap\n\n4. Dokumentasikan hasil rework';
                $('#instruksi_rework').val(instruction);
                
                // Flash animation untuk menunjukkan field bisa diedit
                $('#instruksi_rework').addClass('border-warning').css('background-color', '#fff8dc');
                setTimeout(() => {
                    $('#instruksi_rework').removeClass('border-warning').css('background-color', '');
                }, 3000);
            }
            
            // Estimasi biaya
            const estimasiBiaya = selectedOption.attr('data-estimasi-biaya') || '0';
            $('#estimasi_biaya').val(estimasiBiaya);
            
            // Highlight fields yang sudah ter-populate
            $('#quantity_rework, #estimasi_biaya').addClass('border-success');
            setTimeout(() => {
                $('#quantity_rework, #estimasi_biaya').removeClass('border-success');
            }, 2000);
            
        } else {
            // Clear all fields
            $('#info_alert, #rca_alert').slideUp();
            $('#display_customer, #display_produk').val('');
            $('#display_customer_text, #display_produk_text, #display_nomor_reinspeksi, #display_qty_defect_text').text('-');
            $('#quantity_rework, #estimasi_biaya').val('');
            $('#display_defect_info, #display_rca, #display_corrective, #display_preventive').val('');
        }
    }

    // Auto-populate form when quality reinspection is selected
    $('#quality_reinspection_id').on('select2:select', function(e) {
        console.log('Select2 select event triggered');
        populateFormData();
    });
    
    // Fallback for regular change event
    $('#quality_reinspection_id').on('change', function() {
        console.log('Change event triggered');
        populateFormData();
    });

    // Visual feedback untuk field editable
    $('#deskripsi_rework, #instruksi_rework').on('focus', function() {
        $(this).removeClass('border-primary').addClass('border-success');
        $(this).closest('.mb-3').find('.badge').removeClass('bg-warning').addClass('bg-success').text('✓ Editing');
    });
    
    $('#deskripsi_rework, #instruksi_rework').on('blur', function() {
        $(this).removeClass('border-success').addClass('border-primary');
        $(this).closest('.mb-3').find('.badge').removeClass('bg-success').addClass('bg-warning').text('Editable');
    });
    
    // Tooltip untuk field readonly
    $('textarea[readonly]').on('click', function() {
        const tooltip = $('<div class="alert alert-info position-fixed" style="top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; max-width: 400px;"><i class="bi bi-info-circle me-2"></i>Field ini read-only (hanya referensi). Silakan edit field <strong>Deskripsi Rework</strong> dan <strong>Instruksi Rework</strong> yang ditandai dengan badge <span class="badge bg-warning">Editable</span></div>');
        $('body').append(tooltip);
        setTimeout(() => tooltip.fadeOut(() => tooltip.remove()), 3000);
    });

    // File upload preview
    $('#dokumen_proses').on('change', function() {
        const files = this.files;
        let preview = '';
        
        if (files.length > 0) {
            preview += '<div class="col-12 mb-3"><div class="alert alert-info"><strong>File terpilih:</strong><ul class="mb-0 mt-2">';
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const size = (file.size / 1024 / 1024).toFixed(2);
                preview += `<li>${file.name} (${size} MB)</li>`;
            }
            
            preview += '</ul></div></div>';
        }
        
        $('#file_preview').html(preview);
    });

    // Form validation before submit
    $('form').on('submit', function(e) {
        const qtyRework = parseInt($('#quantity_rework').val()) || 0;
        
        if (qtyRework <= 0) {
            e.preventDefault();
            alert('Quantity rework harus lebih dari 0!');
            $('#quantity_rework').focus();
            return false;
        }
    });
});
</script>
@endpush
