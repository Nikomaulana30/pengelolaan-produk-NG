@extends('layouts.app')

@section('title', 'Buat Customer Complaint Baru')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Customer Complaint Baru</h3>
                <p class="text-subtitle text-muted">Tahap 1: Input complaint baru dari customer</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer-complaint.index') }}">Customer Complaint</a></li>
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
                        <h4 class="card-title">📝 Form Customer Complaint</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('customer-complaint.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Customer Information -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-section-title">
                                        <h5><i class="bi bi-person-circle me-2"></i>Informasi Customer</h5>
                                        <hr>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="master_customer_id" class="form-label">Pilih Customer <span class="text-danger">*</span></label>
                                        <select class="form-select select2-customer @error('master_customer_id') is-invalid @enderror" 
                                                id="master_customer_id" name="master_customer_id" required>
                                            <option value="">🔍 Cari dan pilih customer...</option>
                                            @foreach($masterCustomers as $customer)
                                                <option value="{{ $customer->id }}" 
                                                        data-email="{{ $customer->email_customer }}"
                                                        data-telepon="{{ $customer->telepon_customer }}"
                                                        data-alamat="{{ $customer->alamat_customer }}"
                                                        data-kategori="{{ $customer->kategori_customer }}"
                                                        {{ old('master_customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->kode_customer }} - {{ $customer->nama_customer }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('master_customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Pilih customer yang sudah terdaftar di master data</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label d-none d-md-block">&nbsp;</label>
                                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#modalAddCustomer">
                                            <i class="bi bi-plus-circle me-2"></i>Tambah Baru
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Auto-populated Customer Data (Read-only) -->
                            <div class="row" id="customerDataDisplay" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email Customer</label>
                                        <input type="email" class="form-control" id="display_email" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Telepon Customer</label>
                                        <input type="text" class="form-control" id="display_telepon" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="customerAddressDisplay" style="display: none;">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="form-label">Alamat Customer</label>
                                        <textarea class="form-control" id="display_alamat" rows="2" readonly></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Kategori</label>
                                        <input type="text" class="form-control" id="display_kategori" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                            <option value="">-- Pilih Priority --</option>
                                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟠 High</option>
                                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>🔴 Critical</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Product Information -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-section-title">
                                        <h5><i class="bi bi-box-seam me-2"></i>Informasi Produk</h5>
                                        <hr>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('produk') is-invalid @enderror" 
                                               id="produk" name="produk" value="{{ old('produk') }}" required>
                                        @error('produk')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity_ng" class="form-label">Quantity NG <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('quantity_ng') is-invalid @enderror" 
                                               id="quantity_ng" name="quantity_ng" value="{{ old('quantity_ng') }}" min="1" required>
                                        @error('quantity_ng')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Complaint Details -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-section-title">
                                        <h5><i class="bi bi-chat-text me-2"></i>Detail Complaint</h5>
                                        <hr>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="deskripsi_complaint" class="form-label">Deskripsi Complaint <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('deskripsi_complaint') is-invalid @enderror" 
                                                  id="deskripsi_complaint" name="deskripsi_complaint" rows="4" required 
                                                  placeholder="Jelaskan masalah yang dialami dengan produk...">{{ old('deskripsi_complaint') }}</textarea>
                                        @error('deskripsi_complaint')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="catatan_staff" class="form-label">Catatan Staff</label>
                                        <textarea class="form-control @error('catatan_staff') is-invalid @enderror" 
                                                  id="catatan_staff" name="catatan_staff" rows="3" 
                                                  placeholder="Catatan internal staff (opsional)">{{ old('catatan_staff') }}</textarea>
                                        @error('catatan_staff')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- File Uploads -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-section-title">
                                        <h5><i class="bi bi-paperclip me-2"></i>Lampiran</h5>
                                        <hr>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto_complaint" class="form-label">Foto Complaint</label>
                                        <input type="file" class="form-control @error('foto_complaint.*') is-invalid @enderror" 
                                               id="foto_complaint" name="foto_complaint[]" multiple accept="image/*">
                                        <small class="form-text text-muted">Format: JPG, PNG. Max: 2MB per file. Bisa upload multiple.</small>
                                        @error('foto_complaint.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dokumen_pendukung" class="form-label">Dokumen Pendukung</label>
                                        <input type="file" class="form-control @error('dokumen_pendukung.*') is-invalid @enderror" 
                                               id="dokumen_pendukung" name="dokumen_pendukung[]" multiple accept=".pdf,.doc,.docx">
                                        <small class="form-text text-muted">Format: PDF, DOC, DOCX. Max: 5MB per file. Bisa upload multiple.</small>
                                        @error('dokumen_pendukung.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('customer-complaint.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Kembali
                                        </a>
                                        <button type="reset" class="btn btn-warning">
                                            <i class="bi bi-arrow-clockwise"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Simpan Complaint
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

<!-- Modal Add New Customer -->
<div class="modal fade" id="modalAddCustomer" tabindex="-1" aria-labelledby="modalAddCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAddCustomerLabel">
                    <i class="bi bi-person-plus-fill me-2"></i>Tambah Customer Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    Tambah customer baru akan membuka halaman Master Customer di tab baru
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('master-customer.create') }}" target="_blank" class="btn btn-success">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Buka Form Master Customer
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Tutup
                    </button>
                </div>
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    <strong>Tips:</strong> Setelah menyimpan customer baru, refresh halaman ini untuk melihat customer di dropdown.
                </div>
            </div>
        </div>
    </div>
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

.required-field::after {
    content: " *";
    color: #dc3545;
}

.select2-customer {
    width: 100% !important;
}

#customerDataDisplay .form-control,
#customerAddressDisplay .form-control {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for customer dropdown
    $('.select2-customer').select2({
        theme: 'bootstrap-5',
        placeholder: '🔍 Cari dan pilih customer...',
        allowClear: true,
        width: '100%'
    });

    // Auto-populate customer data when selected
    $('#master_customer_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const email = selectedOption.data('email');
        const telepon = selectedOption.data('telepon');
        const alamat = selectedOption.data('alamat');
        const kategori = selectedOption.data('kategori');

        if (this.value) {
            // Show customer data
            $('#customerDataDisplay').slideDown();
            $('#customerAddressDisplay').slideDown();
            
            // Populate fields
            $('#display_email').val(email);
            $('#display_telepon').val(telepon);
            $('#display_alamat').val(alamat);
            
            // Format kategori display
            let kategoriDisplay = kategori.toUpperCase();
            if (kategori === 'vip') kategoriDisplay = '⭐ VIP';
            else if (kategori === 'regular') kategoriDisplay = '👤 Regular';
            else if (kategori === 'new') kategoriDisplay = '🆕 New';
            
            $('#display_kategori').val(kategoriDisplay);
        } else {
            // Hide customer data
            $('#customerDataDisplay').slideUp();
            $('#customerAddressDisplay').slideUp();
        }
    });

    // Trigger change if there's an old value (for validation errors)
    @if(old('master_customer_id'))
        $('#master_customer_id').trigger('change');
    @endif
    
    // Auto-generate complaint number preview
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    
    console.log(`Preview Complaint ID akan menjadi: CC-${year}${month}-${random}`);
    
    // Form validation enhancement
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const masterCustomerId = document.getElementById('master_customer_id').value;
        
        if (!masterCustomerId) {
            e.preventDefault();
            alert('⚠️ Silakan pilih customer terlebih dahulu!');
            $('#master_customer_id').select2('open');
            return false;
        }
        
        const requiredFields = ['master_customer_id', 'produk', 'quantity_ng', 'deskripsi_complaint', 'priority'];
        let hasError = false;
        
        requiredFields.forEach(function(fieldName) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (!field.value.trim()) {
                hasError = true;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (hasError) {
            e.preventDefault();
            alert('⚠️ Please fill in all required fields!');
        }
    });
    
    // File upload preview
    const fotoInput = document.getElementById('foto_complaint');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const files = e.target.files;
            console.log(`✅ Selected ${files.length} foto(s)`);
        });
    }
    
    const dokumenInput = document.getElementById('dokumen_pendukung');
    if (dokumenInput) {
        dokumenInput.addEventListener('change', function(e) {
            const files = e.target.files;
            console.log(`✅ Selected ${files.length} dokumen(s)`);
        });
    }
    
    // Quick refresh button after adding new customer
    window.addEventListener('storage', function(e) {
        if (e.key === 'newCustomerAdded') {
            if (confirm('🎉 Customer baru telah ditambahkan! Refresh halaman untuk melihatnya?')) {
                location.reload();
            }
        }
    });
});
</script>
@endpush