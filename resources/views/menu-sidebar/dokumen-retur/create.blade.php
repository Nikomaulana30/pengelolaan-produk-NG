@extends('layouts.app')

@section('title', 'Buat Dokumen Retur Baru')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Dokumen Retur Baru</h3>
                <p class="text-subtitle text-muted">Tahap 2: Buat dokumen retur dari customer complaint yang disetujui</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dokumen-retur.index') }}">Dokumen Retur</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create New</li>
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
                        <h4 class="card-title">📄 Form Dokumen Retur</h4>
                    </div>
                    <div class="card-body">
                        @if($availableComplaints->isEmpty())
                            <div class="alert alert-warning">
                                <h5 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Tidak Ada Complaint yang Tersedia</h5>
                                <p class="mb-0">Tidak ada customer complaint yang siap dibuatkan dokumen retur. Complaint harus dalam status "processing" dan belum memiliki dokumen retur.</p>
                                <hr>
                                <a href="{{ route('customer-complaint.index') }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-arrow-left me-2"></i>Lihat Customer Complaints
                                </a>
                            </div>
                        @else
                            <form action="{{ route('dokumen-retur.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Customer Complaint Selection -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-section-title">
                                            <h5><i class="bi bi-file-earmark-text me-2"></i>Pilih Customer Complaint</h5>
                                            <hr>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="customer_complaint_id" class="form-label">Customer Complaint <span class="text-danger">*</span></label>
                                            <select class="form-select select2-complaint @error('customer_complaint_id') is-invalid @enderror" 
                                                    id="customer_complaint_id" name="customer_complaint_id" required>
                                                <option value="">🔍 Pilih complaint yang akan diproses...</option>
                                                @foreach($availableComplaints as $complaint)
                                                    <option value="{{ $complaint->id }}" 
                                                            data-customer="{{ $complaint->nama_customer }}"
                                                            data-produk="{{ $complaint->produk }}"
                                                            data-quantity="{{ $complaint->quantity_ng }}"
                                                            data-priority="{{ $complaint->priority }}"
                                                            {{ old('customer_complaint_id') == $complaint->id ? 'selected' : '' }}>
                                                        {{ $complaint->nomor_complaint }} - {{ $complaint->nama_customer }} ({{ $complaint->produk }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('customer_complaint_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Pilih complaint yang akan dibuatkan dokumen retur</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Complaint Details Display -->
                                <div class="row" id="complaintDetails" style="display: none;">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Detail Complaint</h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Customer:</strong><br>
                                                    <span id="detail_customer"></span>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Produk:</strong><br>
                                                    <span id="detail_produk"></span>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Quantity NG:</strong><br>
                                                    <span id="detail_quantity"></span> pcs
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Priority:</strong><br>
                                                    <span id="detail_priority"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Return Document Details -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-section-title">
                                            <h5><i class="bi bi-card-list me-2"></i>Detail Dokumen Retur</h5>
                                            <hr>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nomor_referensi" class="form-label">Nomor Referensi</label>
                                            <input type="text" class="form-control @error('nomor_referensi') is-invalid @enderror" 
                                                   id="nomor_referensi" name="nomor_referensi" 
                                                   value="{{ old('nomor_referensi') }}"
                                                   placeholder="Nomor referensi eksternal (opsional)">
                                            @error('nomor_referensi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Nomor dokumen akan di-generate otomatis</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jenis_retur" class="form-label">Jenis Retur <span class="text-danger">*</span></label>
                                            <select class="form-select @error('jenis_retur') is-invalid @enderror" 
                                                    id="jenis_retur" name="jenis_retur" required>
                                                <option value="">-- Pilih Jenis Retur --</option>
                                                <option value="full_return" {{ old('jenis_retur') == 'full_return' ? 'selected' : '' }}>
                                                    📦 Full Return - Seluruh produk dikembalikan
                                                </option>
                                                <option value="partial_return" {{ old('jenis_retur') == 'partial_return' ? 'selected' : '' }}>
                                                    📦 Partial Return - Sebagian produk dikembalikan
                                                </option>
                                                <option value="replacement" {{ old('jenis_retur') == 'replacement' ? 'selected' : '' }}>
                                                    🔄 Replacement - Penggantian produk
                                                </option>
                                                <option value="credit_note" {{ old('jenis_retur') == 'credit_note' ? 'selected' : '' }}>
                                                    💳 Credit Note - Nota kredit
                                                </option>
                                            </select>
                                            @error('jenis_retur')
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
                                                      placeholder="Jelaskan instruksi detail untuk proses retur ini...">{{ old('instruksi_retur') }}</textarea>
                                            @error('instruksi_retur')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Instruksi untuk warehouse dalam memproses retur</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="catatan_tambahan" class="form-label">Catatan Tambahan</label>
                                            <textarea class="form-control @error('catatan_tambahan') is-invalid @enderror" 
                                                      id="catatan_tambahan" name="catatan_tambahan" rows="3" 
                                                      placeholder="Catatan atau informasi tambahan (opsional)">{{ old('catatan_tambahan') }}</textarea>
                                            @error('catatan_tambahan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- File Upload -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-section-title">
                                            <h5><i class="bi bi-paperclip me-2"></i>Lampiran Dokumen</h5>
                                            <hr>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="dokumen_retur" class="form-label">Upload Dokumen Retur</label>
                                            <input type="file" class="form-control @error('dokumen_retur.*') is-invalid @enderror" 
                                                   id="dokumen_retur" name="dokumen_retur[]" multiple accept=".pdf,.doc,.docx">
                                            <small class="form-text text-muted">Format: PDF, DOC, DOCX. Max: 5MB per file. Bisa upload multiple.</small>
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
                                            <a href="{{ route('dokumen-retur.index') }}" class="btn btn-secondary">
                                                <i class="bi bi-arrow-left"></i> Kembali
                                            </a>
                                            <button type="reset" class="btn btn-warning">
                                                <i class="bi bi-arrow-clockwise"></i> Reset
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle"></i> Simpan Dokumen Retur
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
.form-section-title {
    margin-bottom: 1rem;
}

.form-section-title h5 {
    color: #435ebe;
    font-weight: 600;
}

.form-section-title hr {
    margin-top: 0.5rem;
    margin-bottom: 1rem;
    border-top: 2px solid #435ebe;
}

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

.select2-complaint {
    width: 100% !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2-complaint').select2({
        theme: 'bootstrap-5',
        placeholder: '🔍 Pilih complaint yang akan diproses...',
        allowClear: true,
        width: '100%'
    });

    // Show complaint details when selected
    $('#customer_complaint_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const customer = selectedOption.data('customer');
        const produk = selectedOption.data('produk');
        const quantity = selectedOption.data('quantity');
        const priority = selectedOption.data('priority');

        if (this.value) {
            // Show details
            $('#complaintDetails').slideDown();
            
            // Populate details
            $('#detail_customer').text(customer);
            $('#detail_produk').text(produk);
            $('#detail_quantity').text(quantity);
            
            // Format priority with badge
            let priorityBadge = '';
            switch(priority) {
                case 'low':
                    priorityBadge = '<span class="badge bg-success">🟢 Low</span>';
                    break;
                case 'medium':
                    priorityBadge = '<span class="badge bg-warning">🟡 Medium</span>';
                    break;
                case 'high':
                    priorityBadge = '<span class="badge bg-orange">🟠 High</span>';
                    break;
                case 'critical':
                    priorityBadge = '<span class="badge bg-danger">🔴 Critical</span>';
                    break;
            }
            $('#detail_priority').html(priorityBadge);
        } else {
            $('#complaintDetails').slideUp();
        }
    });

    // Trigger change if old value exists
    @if(old('customer_complaint_id'))
        $('#customer_complaint_id').trigger('change');
    @endif

    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const complaintId = document.getElementById('customer_complaint_id')?.value;
            const jenisRetur = document.getElementById('jenis_retur')?.value;
            const instruksiRetur = document.getElementById('instruksi_retur')?.value;
            
            if (!complaintId) {
                e.preventDefault();
                alert('⚠️ Silakan pilih customer complaint terlebih dahulu!');
                $('#customer_complaint_id').select2('open');
                return false;
            }
            
            if (!jenisRetur) {
                e.preventDefault();
                alert('⚠️ Silakan pilih jenis retur!');
                document.getElementById('jenis_retur').focus();
                return false;
            }
            
            if (!instruksiRetur?.trim()) {
                e.preventDefault();
                alert('⚠️ Instruksi retur harus diisi!');
                document.getElementById('instruksi_retur').focus();
                return false;
            }
        });
    }

    // File upload preview
    const dokumenInput = document.getElementById('dokumen_retur');
    if (dokumenInput) {
        dokumenInput.addEventListener('change', function(e) {
            const files = e.target.files;
            console.log(`✅ Selected ${files.length} dokumen(s)`);
        });
    }

    // Auto-generate document number preview
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    console.log(`📄 Preview Document ID akan menjadi: DR-${year}${month}-########`);
});
</script>
@endpush
