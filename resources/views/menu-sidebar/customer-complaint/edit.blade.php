@extends('layouts.app')

@section('title', 'Edit Customer Complaint')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Customer Complaint</h3>
                <p class="text-subtitle text-muted">Ubah data complaint #{{ $complaint->nomor_complaint }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer-complaint.index') }}">Customer Complaint</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                        <h4 class="card-title">📝 Edit Form Customer Complaint</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('customer-complaint.update', $complaint->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_customer" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_customer') is-invalid @enderror" 
                                               id="nama_customer" name="nama_customer" 
                                               value="{{ old('nama_customer', $complaint->nama_customer) }}" required>
                                        @error('nama_customer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email_customer" class="form-label">Email Customer <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email_customer') is-invalid @enderror" 
                                               id="email_customer" name="email_customer" 
                                               value="{{ old('email_customer', $complaint->email_customer) }}" required>
                                        @error('email_customer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telepon_customer" class="form-label">Telepon Customer <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('telepon_customer') is-invalid @enderror" 
                                               id="telepon_customer" name="telepon_customer" 
                                               value="{{ old('telepon_customer', $complaint->telepon_customer) }}" required>
                                        @error('telepon_customer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                            <option value="">-- Pilih Priority --</option>
                                            <option value="low" {{ old('priority', $complaint->priority) == 'low' ? 'selected' : '' }}>🟢 Low</option>
                                            <option value="medium" {{ old('priority', $complaint->priority) == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                                            <option value="high" {{ old('priority', $complaint->priority) == 'high' ? 'selected' : '' }}>🟠 High</option>
                                            <option value="critical" {{ old('priority', $complaint->priority) == 'critical' ? 'selected' : '' }}>🔴 Critical</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alamat_customer" class="form-label">Alamat Customer <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('alamat_customer') is-invalid @enderror" 
                                                  id="alamat_customer" name="alamat_customer" rows="2" required>{{ old('alamat_customer', $complaint->alamat_customer) }}</textarea>
                                        @error('alamat_customer')
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
                                               id="produk" name="produk" 
                                               value="{{ old('produk', $complaint->produk) }}" required>
                                        @error('produk')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity_ng" class="form-label">Quantity NG <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('quantity_ng') is-invalid @enderror" 
                                               id="quantity_ng" name="quantity_ng" 
                                               value="{{ old('quantity_ng', $complaint->quantity_ng) }}" min="1" required>
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
                                                  placeholder="Jelaskan masalah yang dialami dengan produk...">{{ old('deskripsi_complaint', $complaint->deskripsi_complaint) }}</textarea>
                                        @error('deskripsi_complaint')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="draft" {{ old('status', $complaint->status) == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                            <option value="submitted" {{ old('status', $complaint->status) == 'submitted' ? 'selected' : '' }}>📤 Submitted</option>
                                            <option value="processing" {{ old('status', $complaint->status) == 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                                            <option value="completed" {{ old('status', $complaint->status) == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                            <option value="cancelled" {{ old('status', $complaint->status) == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="staff_exim_id" class="form-label">Assign to Staff EXIM</label>
                                        <select class="form-select @error('staff_exim_id') is-invalid @enderror" id="staff_exim_id" name="staff_exim_id">
                                            <option value="">-- Belum Ditentukan --</option>
                                            @foreach($staffEximOptions as $staff)
                                                <option value="{{ $staff->id }}" {{ old('staff_exim_id', $complaint->staff_exim_id) == $staff->id ? 'selected' : '' }}>
                                                    {{ $staff->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('staff_exim_id')
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
                                                  placeholder="Catatan internal staff (opsional)">{{ old('catatan_staff', $complaint->catatan_staff) }}</textarea>
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

                            <!-- Existing Files Display -->
                            @if(!empty($complaint->foto_complaint) && is_array($complaint->foto_complaint))
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <strong>📸 Foto Complaint Saat Ini ({{ count($complaint->foto_complaint) }} file):</strong>
                                        <div class="mt-2 d-flex flex-wrap gap-2">
                                            @foreach($complaint->foto_complaint as $index => $foto)
                                                <div class="position-relative">
                                                    <img src="{{ asset('storage/' . $foto) }}" 
                                                         alt="Foto {{ $index + 1 }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 100px; height: 100px; object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(!empty($complaint->dokumen_pendukung) && is_array($complaint->dokumen_pendukung))
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <strong>📄 Dokumen Pendukung Saat Ini ({{ count($complaint->dokumen_pendukung) }} file):</strong>
                                        <ul class="mt-2 mb-0">
                                            @foreach($complaint->dokumen_pendukung as $index => $dokumen)
                                                <li>
                                                    <a href="{{ asset('storage/' . $dokumen) }}" target="_blank">
                                                        {{ basename($dokumen) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto_complaint" class="form-label">Upload Foto Complaint Baru</label>
                                        <input type="file" class="form-control @error('foto_complaint.*') is-invalid @enderror" 
                                               id="foto_complaint" name="foto_complaint[]" multiple accept="image/*">
                                        <small class="form-text text-muted">Format: JPG, PNG. Max: 2MB per file. File baru akan ditambahkan ke yang sudah ada.</small>
                                        @error('foto_complaint.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dokumen_pendukung" class="form-label">Upload Dokumen Pendukung Baru</label>
                                        <input type="file" class="form-control @error('dokumen_pendukung.*') is-invalid @enderror" 
                                               id="dokumen_pendukung" name="dokumen_pendukung[]" multiple accept=".pdf,.doc,.docx">
                                        <small class="form-text text-muted">Format: PDF, DOC, DOCX. Max: 5MB per file. File baru akan ditambahkan ke yang sudah ada.</small>
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
                                        <a href="{{ route('customer-complaint.show', $complaint->id) }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Kembali
                                        </a>
                                        <button type="reset" class="btn btn-warning">
                                            <i class="bi bi-arrow-clockwise"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Update Complaint
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

@endsection

@push('styles')
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation enhancement
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = ['nama_customer', 'email_customer', 'telepon_customer', 'alamat_customer', 'produk', 'quantity_ng', 'deskripsi_complaint', 'priority', 'status'];
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
            alert('⚠️ Silakan lengkapi semua field yang wajib diisi!');
        }
    });
    
    // File upload preview
    const fotoInput = document.getElementById('foto_complaint');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const files = e.target.files;
            console.log(`✅ Selected ${files.length} foto baru`);
        });
    }
    
    const dokumenInput = document.getElementById('dokumen_pendukung');
    if (dokumenInput) {
        dokumenInput.addEventListener('change', function(e) {
            const files = e.target.files;
            console.log(`✅ Selected ${files.length} dokumen baru`);
        });
    }
});
</script>
@endpush
