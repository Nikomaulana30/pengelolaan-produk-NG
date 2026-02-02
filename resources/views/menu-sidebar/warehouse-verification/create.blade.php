@extends('layouts.app')

@section('title', 'Buat Verifikasi Warehouse')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Verifikasi Warehouse</h3>
                <p class="text-subtitle text-muted">Verifikasi penerimaan barang NG dari dokumen retur</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('warehouse-verification.index') }}">Warehouse Verification</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buat Verifikasi</li>
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
                <h4 class="card-title">Form Verifikasi Penerimaan Barang</h4>
            </div>
            <div class="card-body">
                @if($availableDokumens->isEmpty())
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Tidak ada dokumen retur yang siap diverifikasi. Dokumen retur harus sudah dikirim ke warehouse terlebih dahulu.
                    </div>
                    <a href="{{ route('dokumen-retur.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>Ke Dokumen Retur
                    </a>
                @else
                    <form action="{{ route('warehouse-verification.store') }}" method="POST" enctype="multipart/form-data" id="verificationForm">
                        @csrf

                        <!-- Pilih Dokumen Retur -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">
                                            <i class="bi bi-file-earmark-text me-2"></i>Pilih Dokumen Retur
                                        </h5>
                                        <div class="form-group">
                                            <label for="dokumen_retur_id" class="form-label">Dokumen Retur <span class="text-danger">*</span></label>
                                            <select class="form-select select2" id="dokumen_retur_id" name="dokumen_retur_id" required>
                                                <option value="">-- Pilih Dokumen Retur --</option>
                                                @foreach($availableDokumens as $dokumen)
                                                    <option value="{{ $dokumen->id }}" 
                                                            data-nomor="{{ $dokumen->nomor_dokumen }}"
                                                            data-customer="{{ $dokumen->customerComplaint->nama_customer }}"
                                                            data-produk="{{ $dokumen->customerComplaint->produk }}"
                                                            data-quantity="{{ $dokumen->customerComplaint->quantity_ng }}"
                                                            data-unit="pcs"
                                                            data-complaint="{{ $dokumen->customer_complaint_id }}">
                                                        {{ $dokumen->nomor_dokumen }} - {{ $dokumen->customerComplaint->nama_customer }} - {{ $dokumen->customerComplaint->produk }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('dokumen_retur_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Detail Dokumen (Auto-populate) -->
                                        <div id="dokumenDetail" style="display: none;" class="mt-3">
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
                                                        <strong>Quantity NG Expected:</strong>
                                                        <span id="detail_quantity" class="text-muted"></span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>No. Complaint:</strong>
                                                        <span id="detail_complaint" class="text-muted"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Penerimaan -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_terima" class="form-label">Tanggal Terima <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_terima') is-invalid @enderror" 
                                           id="tanggal_terima" name="tanggal_terima" 
                                           value="{{ old('tanggal_terima', date('Y-m-d')) }}" required>
                                    @error('tanggal_terima')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="master_lokasi_gudang_id" class="form-label">Lokasi Penyimpanan <span class="text-danger">*</span></label>
                                    <select class="form-select select2-lokasi @error('master_lokasi_gudang_id') is-invalid @enderror" 
                                            id="master_lokasi_gudang_id" name="master_lokasi_gudang_id" required>
                                        <option value="">-- Pilih Lokasi Gudang --</option>
                                        @foreach($lokasiGudangs as $lokasi)
                                            <option value="{{ $lokasi->id }}" 
                                                    data-kode="{{ $lokasi->kode_lokasi }}"
                                                    data-lokasi="{{ $lokasi->lokasi_lengkap }}"
                                                    data-kapasitas="{{ $lokasi->kapasitas_max }}"
                                                    data-usage="{{ $lokasi->current_usage ?? 0 }}"
                                                    {{ old('master_lokasi_gudang_id') == $lokasi->id ? 'selected' : '' }}>
                                                {{ $lokasi->lokasi_lengkap }} - {{ $lokasi->nama_lokasi }} ({{ $lokasi->kode_lokasi }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('master_lokasi_gudang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <a href="{{ route('master-lokasi-gudang.create') }}" target="_blank" class="text-primary">
                                            <i class="bi bi-plus-circle me-1"></i>Tambah Lokasi Baru
                                        </a>
                                    </small>
                                </div>
                                <!-- Lokasi Detail Info -->
                                <div id="lokasiDetail" style="display: none;" class="alert alert-info py-2">
                                    <small>
                                        <strong>Kapasitas:</strong> <span id="lokasi_kapasitas"></span><br>
                                        <strong>Penggunaan Saat Ini:</strong> <span id="lokasi_usage"></span><br>
                                        <strong>Tersedia:</strong> <span id="lokasi_available"></span>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes (Optional) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="lokasi_penyimpanan" class="form-label">Catatan Lokasi Tambahan</label>
                                    <input type="text" class="form-control @error('lokasi_penyimpanan') is-invalid @enderror" 
                                           id="lokasi_penyimpanan" name="lokasi_penyimpanan" 
                                           placeholder="Contoh: Detail posisi spesifik (opsional)" 
                                           value="{{ old('lokasi_penyimpanan') }}">
                                    @error('lokasi_penyimpanan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Opsional: Tambahkan detail posisi spesifik jika diperlukan</small>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity Details -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="quantity_diterima" class="form-label">Quantity Diterima <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity_diterima') is-invalid @enderror" 
                                           id="quantity_diterima" name="quantity_diterima" min="0" 
                                           value="{{ old('quantity_diterima') }}" required>
                                    @error('quantity_diterima')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Total barang yang diterima di warehouse</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="quantity_ng_confirmed" class="form-label">Quantity NG Confirmed <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity_ng_confirmed') is-invalid @enderror" 
                                           id="quantity_ng_confirmed" name="quantity_ng_confirmed" min="0" 
                                           value="{{ old('quantity_ng_confirmed') }}" required>
                                    @error('quantity_ng_confirmed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Barang yang dikonfirmasi NG</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="quantity_ok" class="form-label">Quantity OK <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity_ok') is-invalid @enderror" 
                                           id="quantity_ok" name="quantity_ok" min="0" 
                                           value="{{ old('quantity_ok') }}" required>
                                    @error('quantity_ok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Barang yang masih OK</small>
                                </div>
                            </div>
                        </div>

                        <!-- Kondisi & Catatan -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="kondisi_fisik_barang" class="form-label">Kondisi Fisik Barang <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('kondisi_fisik_barang') is-invalid @enderror" 
                                              id="kondisi_fisik_barang" name="kondisi_fisik_barang" rows="3" 
                                              required>{{ old('kondisi_fisik_barang') }}</textarea>
                                    @error('kondisi_fisik_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Deskripsikan kondisi fisik barang yang diterima</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="catatan_penerimaan" class="form-label">Catatan Penerimaan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('catatan_penerimaan') is-invalid @enderror" 
                                              id="catatan_penerimaan" name="catatan_penerimaan" rows="3" 
                                              required>{{ old('catatan_penerimaan') }}</textarea>
                                    @error('catatan_penerimaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Catatan tambahan terkait penerimaan barang</small>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Barang NG -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="foto_barang_ng" class="form-label">Foto Barang NG</label>
                                    <input type="file" class="form-control @error('foto_barang_ng.*') is-invalid @enderror" 
                                           id="foto_barang_ng" name="foto_barang_ng[]" accept="image/*" multiple>
                                    @error('foto_barang_ng.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Upload foto kondisi barang NG (Multiple files allowed, Max: 2MB per file)</small>
                                </div>
                                <div id="imagePreview" class="row mt-2"></div>
                            </div>
                        </div>

                        <!-- Alert untuk validasi quantity -->
                        <div class="alert alert-warning" id="quantityAlert" style="display: none;">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <span id="quantityAlertText"></span>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('warehouse-verification.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Kembali
                                    </a>
                                    <div>
                                        <button type="submit" name="action" value="draft" class="btn btn-warning me-2">
                                            <i class="bi bi-save me-2"></i>Simpan sebagai Draft
                                        </button>
                                        <button type="submit" name="action" value="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-2"></i>Verifikasi & Simpan
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
        placeholder: '-- Pilih Dokumen Retur --'
    });

    $('.select2-lokasi').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Pilih Lokasi Gudang --'
    });

    // Show dokumen details when selected
    $('#dokumen_retur_id').on('change', function() {
        const selected = $(this).find(':selected');
        if (selected.val()) {
            $('#detail_customer').text(selected.data('customer'));
            $('#detail_produk').text(selected.data('produk'));
            $('#detail_quantity').text(selected.data('quantity') + ' ' + selected.data('unit'));
            $('#detail_complaint').text('CC-' + selected.data('complaint'));
            $('#dokumenDetail').slideDown();
        } else {
            $('#dokumenDetail').slideUp();
        }
    });

    // Show lokasi gudang details when selected
    $('#master_lokasi_gudang_id').on('change', function() {
        const selected = $(this).find(':selected');
        if (selected.val()) {
            const kapasitas = parseInt(selected.data('kapasitas'));
            const usage = parseInt(selected.data('usage'));
            const available = kapasitas - usage;
            
            $('#lokasi_kapasitas').text(kapasitas.toLocaleString() + ' pcs');
            $('#lokasi_usage').text(usage.toLocaleString() + ' pcs');
            $('#lokasi_available').text(available.toLocaleString() + ' pcs');
            $('#lokasiDetail').slideDown();
        } else {
            $('#lokasiDetail').slideUp();
        }
    });

    // Image preview
    $('#foto_barang_ng').on('change', function(e) {
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

    // Validate quantity calculations
    function validateQuantities() {
        const qtyDiterima = parseInt($('#quantity_diterima').val()) || 0;
        const qtyNG = parseInt($('#quantity_ng_confirmed').val()) || 0;
        const qtyOK = parseInt($('#quantity_ok').val()) || 0;
        const total = qtyNG + qtyOK;

        if (qtyDiterima > 0 && total !== qtyDiterima) {
            $('#quantityAlert').show();
            $('#quantityAlertText').text(
                `Total Quantity NG (${qtyNG}) + OK (${qtyOK}) = ${total} harus sama dengan Quantity Diterima (${qtyDiterima})`
            );
            return false;
        } else {
            $('#quantityAlert').hide();
            return true;
        }
    }

    $('#quantity_diterima, #quantity_ng_confirmed, #quantity_ok').on('input', validateQuantities);

    // Form validation before submit
    $('#verificationForm').on('submit', function(e) {
        if (!validateQuantities()) {
            e.preventDefault();
            alert('Pastikan total Quantity NG + OK sama dengan Quantity Diterima!');
            return false;
        }
    });

    // Listen for new lokasi added (from popup window)
    window.addEventListener('storage', function(e) {
        if (e.key === 'new_lokasi_added' && e.newValue) {
            location.reload();
        }
    });
});
</script>
@endpush
@endsection
