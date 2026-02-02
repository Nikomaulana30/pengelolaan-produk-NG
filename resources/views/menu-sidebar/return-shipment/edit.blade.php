@extends('layouts.app')

@section('title', 'Edit Return Shipment')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Return Shipment</h3>
                <p class="text-subtitle text-muted">Update detail pengiriman kembali ke customer</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('return-shipment.index') }}">Return Shipment</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('return-shipment.show', $shipment) }}">{{ $shipment->nomor_pengiriman }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Return Shipment</h4>
            </div>
            <div class="card-body">
                <!-- Quality Check Info (Read-Only) -->
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Informasi Final Quality Check</h6>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>No. QC:</strong>
                            <p class="mb-1">#{{ $shipment->finalQualityCheck->id }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Customer:</strong>
                            <p class="mb-1">{{ $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Produk:</strong>
                            <p class="mb-1">{{ $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Qty Passed:</strong>
                            <p class="mb-0 text-success">{{ $shipment->finalQualityCheck->quantity_passed }} pcs</p>
                        </div>
                    </div>
                </div>

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

                <form action="{{ route('return-shipment.update', $shipment) }}" method="POST" enctype="multipart/form-data" id="shipmentEditForm">
                    @csrf
                    @method('PUT')

                    <!-- Shipment Details -->
                    <h5 class="mb-3"><i class="bi bi-truck me-2"></i>Detail Pengiriman</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_pengiriman" class="form-label required">Tanggal Pengiriman</label>
                            <input type="date" class="form-control" name="tanggal_pengiriman" id="tanggal_pengiriman" 
                                   value="{{ old('tanggal_pengiriman', $shipment->tanggal_pengiriman->format('Y-m-d')) }}" required>
                            @error('tanggal_pengiriman')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="quantity_shipped" class="form-label required">Quantity yang Dikirim</label>
                            <input type="number" class="form-control" name="quantity_shipped" id="quantity_shipped" 
                                   value="{{ old('quantity_shipped', $shipment->quantity_shipped) }}" min="1" required>
                            <small class="text-muted">Max: {{ $shipment->finalQualityCheck->quantity_passed }} pcs (Passed)</small>
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
                                <option value="JNE" {{ old('ekspedisi', $shipment->ekspedisi) == 'JNE' ? 'selected' : '' }}>JNE</option>
                                <option value="TIKI" {{ old('ekspedisi', $shipment->ekspedisi) == 'TIKI' ? 'selected' : '' }}>TIKI</option>
                                <option value="POS Indonesia" {{ old('ekspedisi', $shipment->ekspedisi) == 'POS Indonesia' ? 'selected' : '' }}>POS Indonesia</option>
                                <option value="J&T Express" {{ old('ekspedisi', $shipment->ekspedisi) == 'J&T Express' ? 'selected' : '' }}>J&T Express</option>
                                <option value="SiCepat" {{ old('ekspedisi', $shipment->ekspedisi) == 'SiCepat' ? 'selected' : '' }}>SiCepat</option>
                                <option value="AnterAja" {{ old('ekspedisi', $shipment->ekspedisi) == 'AnterAja' ? 'selected' : '' }}>AnterAja</option>
                                <option value="Ninja Express" {{ old('ekspedisi', $shipment->ekspedisi) == 'Ninja Express' ? 'selected' : '' }}>Ninja Express</option>
                                <option value="Lion Parcel" {{ old('ekspedisi', $shipment->ekspedisi) == 'Lion Parcel' ? 'selected' : '' }}>Lion Parcel</option>
                                <option value="Other" {{ old('ekspedisi', $shipment->ekspedisi) == 'Other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('ekspedisi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nomor_resi" class="form-label">Nomor Resi</label>
                            <input type="text" class="form-control" name="nomor_resi" id="nomor_resi" 
                                   value="{{ old('nomor_resi', $shipment->nomor_resi) }}" placeholder="Masukkan nomor resi pengiriman">
                            <small class="text-muted">Isi jika sudah ada nomor tracking</small>
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
                                       value="{{ old('biaya_pengiriman', $shipment->biaya_pengiriman) }}" min="0" step="1000" placeholder="0">
                            </div>
                            @error('biaya_pengiriman')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="alamat_pengiriman" class="form-label required">Alamat Pengiriman</label>
                            <textarea name="alamat_pengiriman" id="alamat_pengiriman" rows="3" 
                                      class="form-control" required 
                                      placeholder="Alamat lengkap customer...">{{ old('alamat_pengiriman', $shipment->alamat_pengiriman) }}</textarea>
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
                                      placeholder="Catatan tambahan untuk pengiriman...">{{ old('catatan_pengiriman', $shipment->catatan_pengiriman) }}</textarea>
                            @error('catatan_pengiriman')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Status Update -->
                    <h5 class="mb-3"><i class="bi bi-clipboard-check me-2"></i>Status Pengiriman</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status_pengiriman" class="form-label required">Status Pengiriman</label>
                            <select name="status_pengiriman" id="status_pengiriman" class="form-select" required>
                                <option value="preparing" {{ old('status_pengiriman', $shipment->status_pengiriman) == 'preparing' ? 'selected' : '' }}>Preparing (Persiapan)</option>
                                <option value="shipped" {{ old('status_pengiriman', $shipment->status_pengiriman) == 'shipped' ? 'selected' : '' }}>Shipped (Dalam Perjalanan)</option>
                                <option value="delivered" {{ old('status_pengiriman', $shipment->status_pengiriman) == 'delivered' ? 'selected' : '' }}>Delivered (Sudah Diterima)</option>
                                <option value="failed" {{ old('status_pengiriman', $shipment->status_pengiriman) == 'failed' ? 'selected' : '' }}>Failed (Gagal Kirim)</option>
                            </select>
                            <small class="text-muted">Update status sesuai progress pengiriman</small>
                            @error('status_pengiriman')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label required">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="draft" {{ old('status', $shipment->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="shipped" {{ old('status', $shipment->status) == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ old('status', $shipment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <small class="text-muted">Status untuk completed jika sudah selesai</small>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="catatan_delivery" class="form-label">Catatan Delivery</label>
                            <textarea name="catatan_delivery" id="catatan_delivery" rows="3" 
                                      class="form-control" 
                                      placeholder="Catatan saat delivery (penerima, kondisi barang, dll)...">{{ old('catatan_delivery', $shipment->catatan_delivery) }}</textarea>
                            @error('catatan_delivery')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="rating_customer" class="form-label">Rating Customer</label>
                            <select name="rating_customer" id="rating_customer" class="form-select">
                                <option value="">-- Pilih Rating --</option>
                                <option value="5" {{ old('rating_customer', $shipment->rating_customer) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 - Excellent)</option>
                                <option value="4" {{ old('rating_customer', $shipment->rating_customer) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4 - Good)</option>
                                <option value="3" {{ old('rating_customer', $shipment->rating_customer) == 3 ? 'selected' : '' }}>⭐⭐⭐ (3 - Average)</option>
                                <option value="2" {{ old('rating_customer', $shipment->rating_customer) == 2 ? 'selected' : '' }}>⭐⭐ (2 - Poor)</option>
                                <option value="1" {{ old('rating_customer', $shipment->rating_customer) == 1 ? 'selected' : '' }}>⭐ (1 - Very Poor)</option>
                            </select>
                            <small class="text-muted">Feedback dari customer (opsional)</small>
                            @error('rating_customer')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Documents -->
                    <h5 class="mb-3"><i class="bi bi-file-earmark-text me-2"></i>Dokumen Pengiriman</h5>

                    @if($shipment->dokumen_pengiriman && count($shipment->dokumen_pengiriman) > 0)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Dokumen yang Sudah Diupload:</label>
                                <div class="row">
                                    @foreach($shipment->dokumen_pengiriman as $index => $doc)
                                        @php
                                            $extension = pathinfo($doc, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                        @endphp
                                        
                                        @if($isImage)
                                            <div class="col-md-3 mb-2">
                                                <img src="{{ Storage::url($doc) }}" class="img-thumbnail" alt="Document {{ $index + 1 }}">
                                                <small class="d-block text-center mt-1">Dokumen {{ $index + 1 }}</small>
                                            </div>
                                        @else
                                            <div class="col-md-12 mb-2">
                                                <div class="alert alert-secondary py-2">
                                                    <i class="bi bi-file-earmark-pdf me-2"></i>
                                                    <a href="{{ Storage::url($doc) }}" target="_blank">Dokumen {{ $index + 1 }}</a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="dokumen_pengiriman" class="form-label">Upload Dokumen Tambahan (Opsional)</label>
                            <input type="file" class="form-control" name="dokumen_pengiriman[]" id="dokumen_pengiriman" 
                                   multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-muted">Format: PDF, DOC, DOCX, JPG, PNG. Max 5MB per file. Upload dokumen tambahan jika diperlukan.</small>
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
                                <i class="bi bi-save me-2"></i>Update Return Shipment
                            </button>
                            <a href="{{ route('return-shipment.show', $shipment) }}" class="btn btn-secondary">
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
    // Validate quantity shipped
    $('#shipmentEditForm').on('submit', function(e) {
        const qtyPassed = {{ $shipment->finalQualityCheck->quantity_passed }};
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

    // Auto-update status when status_pengiriman changes
    $('#status_pengiriman').on('change', function() {
        const statusPengiriman = $(this).val();
        const statusSelect = $('#status');
        
        if (statusPengiriman === 'preparing') {
            statusSelect.val('draft');
        } else if (statusPengiriman === 'shipped') {
            statusSelect.val('shipped');
        } else if (statusPengiriman === 'delivered') {
            statusSelect.val('completed');
        }
    });
});
</script>
@endpush
@endsection
