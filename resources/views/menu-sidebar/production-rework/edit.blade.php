@extends('layouts.app')

@section('title', 'Edit Production Rework')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Production Rework</h3>
                <p class="text-subtitle text-muted">Update informasi production rework</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('production-rework.index') }}">Production Rework</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Production Rework: {{ $rework->nomor_rework }}</h4>
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

                <form action="{{ route('production-rework.update', $rework) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Quality Reinspection Info (Read-only) -->
                    <div class="alert alert-info">
                        <h5 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Informasi Quality Reinspection</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Nomor Reinspection:</strong>
                                <p class="mb-0">{{ $rework->qualityReinspection->nomor_reinspeksi }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Customer:</strong>
                                <p class="mb-0">{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 mb-2">
                                <strong>Produk:</strong>
                                <p class="mb-0">{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Quantity Defect:</strong>
                                <p class="mb-0 text-danger">{{ $rework->qualityReinspection->quantity_defect }} unit</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Rework Details -->
                    <h5 class="mb-3"><i class="bi bi-gear-fill me-2"></i>Rework Details</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai_rework" class="form-label required">Tanggal Mulai Rework</label>
                            <input type="date" class="form-control" name="tanggal_mulai_rework" 
                                   id="tanggal_mulai_rework" 
                                   value="{{ old('tanggal_mulai_rework', $rework->tanggal_mulai_rework ? $rework->tanggal_mulai_rework->format('Y-m-d') : '') }}" 
                                   required>
                            @error('tanggal_mulai_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tanggal_selesai_rework" class="form-label">Tanggal Selesai Rework</label>
                            <input type="date" class="form-control" name="tanggal_selesai_rework" 
                                   id="tanggal_selesai_rework" 
                                   value="{{ old('tanggal_selesai_rework', $rework->tanggal_selesai_rework ? $rework->tanggal_selesai_rework->format('Y-m-d') : '') }}">
                            <small class="text-muted">Isi jika rework sudah selesai</small>
                            @error('tanggal_selesai_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="metode_rework" class="form-label required">Metode Rework</label>
                            <select name="metode_rework" id="metode_rework" class="form-select" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="melting" {{ old('metode_rework', $rework->metode_rework) == 'melting' ? 'selected' : '' }}>Melting (Peleburan)</option>
                                <option value="welding" {{ old('metode_rework', $rework->metode_rework) == 'welding' ? 'selected' : '' }}>Welding (Pengelasan)</option>
                                <option value="machining" {{ old('metode_rework', $rework->metode_rework) == 'machining' ? 'selected' : '' }}>Machining (Permesinan)</option>
                                <option value="surface_treatment" {{ old('metode_rework', $rework->metode_rework) == 'surface_treatment' ? 'selected' : '' }}>Surface Treatment</option>
                                <option value="assembly" {{ old('metode_rework', $rework->metode_rework) == 'assembly' ? 'selected' : '' }}>Assembly (Perakitan)</option>
                                <option value="other" {{ old('metode_rework', $rework->metode_rework) == 'other' ? 'selected' : '' }}>Other (Lainnya)</option>
                            </select>
                            @error('metode_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="pic_rework" class="form-label">PIC Rework</label>
                            <input type="text" class="form-control" name="pic_rework" 
                                   id="pic_rework" value="{{ old('pic_rework', $rework->pic_rework) }}" 
                                   placeholder="Nama PIC/Operator">
                            @error('pic_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="deskripsi_rework" class="form-label required">Deskripsi Rework</label>
                            <textarea name="deskripsi_rework" id="deskripsi_rework" rows="4" 
                                      class="form-control" required>{{ old('deskripsi_rework', $rework->deskripsi_rework) }}</textarea>
                            @error('deskripsi_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="instruksi_rework" class="form-label required">Instruksi Rework</label>
                            <textarea name="instruksi_rework" id="instruksi_rework" rows="5" 
                                      class="form-control" required>{{ old('instruksi_rework', $rework->instruksi_rework) }}</textarea>
                            @error('instruksi_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Quantity & Results -->
                    <h5 class="mb-3"><i class="bi bi-calculator me-2"></i>Quantity & Hasil</h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="quantity_rework" class="form-label required">Quantity Rework</label>
                            <input type="number" class="form-control" name="quantity_rework" 
                                   id="quantity_rework" value="{{ old('quantity_rework', $rework->quantity_rework) }}" 
                                   min="1" required>
                            @error('quantity_rework')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="quantity_hasil_ok" class="form-label">Quantity Hasil OK</label>
                            <input type="number" class="form-control" name="quantity_hasil_ok" 
                                   id="quantity_hasil_ok" value="{{ old('quantity_hasil_ok', $rework->quantity_hasil_ok) }}" 
                                   min="0">
                            <small class="text-muted">Hasil rework yang OK</small>
                            @error('quantity_hasil_ok')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="quantity_hasil_ng" class="form-label">Quantity Hasil NG</label>
                            <input type="number" class="form-control" name="quantity_hasil_ng" 
                                   id="quantity_hasil_ng" value="{{ old('quantity_hasil_ng', $rework->quantity_hasil_ng) }}" 
                                   min="0">
                            <small class="text-muted">Hasil rework yang masih NG</small>
                            @error('quantity_hasil_ng')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Cost & Time -->
                    <h5 class="mb-3"><i class="bi bi-cash me-2"></i>Biaya & Waktu</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estimasi_biaya" class="form-label required">Estimasi Biaya (Rp)</label>
                            <input type="number" class="form-control" name="estimasi_biaya" 
                                   id="estimasi_biaya" value="{{ old('estimasi_biaya', $rework->estimasi_biaya) }}" 
                                   step="0.01" min="0" required>
                            @error('estimasi_biaya')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="actual_biaya" class="form-label">Actual Biaya (Rp)</label>
                            <input type="number" class="form-control" name="actual_biaya" 
                                   id="actual_biaya" value="{{ old('actual_biaya', $rework->actual_biaya) }}" 
                                   step="0.01" min="0">
                            <small class="text-muted">Biaya aktual yang dikeluarkan</small>
                            @error('actual_biaya')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estimasi_waktu_hari" class="form-label required">Estimasi Waktu (Hari)</label>
                            <input type="number" class="form-control" name="estimasi_waktu_hari" 
                                   id="estimasi_waktu_hari" value="{{ old('estimasi_waktu_hari', $rework->estimasi_waktu_hari) }}" 
                                   min="1" required>
                            @error('estimasi_waktu_hari')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="actual_waktu_hari" class="form-label">Actual Waktu (Hari)</label>
                            <input type="number" class="form-control" name="actual_waktu_hari" 
                                   id="actual_waktu_hari" value="{{ old('actual_waktu_hari', $rework->actual_waktu_hari) }}" 
                                   min="0">
                            <small class="text-muted">Waktu aktual yang dibutuhkan</small>
                            @error('actual_waktu_hari')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Catatan Proses -->
                    <h5 class="mb-3"><i class="bi bi-journal-text me-2"></i>Catatan Proses</h5>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="catatan_proses" class="form-label">Catatan Proses Rework</label>
                            <textarea name="catatan_proses" id="catatan_proses" rows="4" 
                                      class="form-control" 
                                      placeholder="Catatan selama proses rework berlangsung...">{{ old('catatan_proses', $rework->catatan_proses) }}</textarea>
                            @error('catatan_proses')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Status -->
                    <h5 class="mb-3"><i class="bi bi-flag-fill me-2"></i>Status Rework</h5>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="status" class="form-label required">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="draft" {{ old('status', $rework->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="in_progress" {{ old('status', $rework->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $rework->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="sent_to_warehouse" {{ old('status', $rework->status) == 'sent_to_warehouse' ? 'selected' : '' }}>Sent to Warehouse</option>
                            </select>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Ubah status menjadi <strong>Completed</strong> jika rework sudah selesai. 
                                Status <strong>Sent to Warehouse</strong> untuk melaporkan barang siap dikirim.
                            </small>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Dokumen Proses -->
                    <h5 class="mb-3"><i class="bi bi-file-earmark-text me-2"></i>Dokumen Proses</h5>

                    @if($rework->dokumen_proses && count($rework->dokumen_proses) > 0)
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong>Dokumen Existing:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($rework->dokumen_proses as $doc)
                                        <li>{{ basename($doc) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="dokumen_proses" class="form-label">Upload Dokumen Proses Baru (Opsional)</label>
                            <input type="file" class="form-control" name="dokumen_proses[]" 
                                   id="dokumen_proses" multiple 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-muted">Upload dokumen tambahan. Format: PDF, DOC, DOCX, JPG, PNG. Max 5MB per file.</small>
                            @error('dokumen_proses.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row" id="file_preview"></div>

                    <hr class="my-4">

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Update Production Rework
                            </button>
                            <a href="{{ route('production-rework.show', $rework) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // File upload preview
    $('#dokumen_proses').on('change', function() {
        const files = this.files;
        let preview = '';
        
        if (files.length > 0) {
            preview += '<div class="col-12 mb-3"><div class="alert alert-info"><strong>File baru terpilih:</strong><ul class="mb-0 mt-2">';
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const size = (file.size / 1024 / 1024).toFixed(2);
                preview += `<li>${file.name} (${size} MB)</li>`;
            }
            
            preview += '</ul></div></div>';
        }
        
        $('#file_preview').html(preview);
    });

    // Quantity validation
    $('#quantity_hasil_ok, #quantity_hasil_ng').on('input', function() {
        const qtyRework = parseInt($('#quantity_rework').val()) || 0;
        const qtyOk = parseInt($('#quantity_hasil_ok').val()) || 0;
        const qtyNg = parseInt($('#quantity_hasil_ng').val()) || 0;
        
        if ((qtyOk + qtyNg) > qtyRework) {
            $(this).addClass('is-invalid');
            if (!$(this).siblings('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback d-block">Total hasil OK + NG tidak boleh melebihi quantity rework!</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        }
    });

    // Date validation
    $('#tanggal_selesai_rework').on('change', function() {
        const tanggalMulai = new Date($('#tanggal_mulai_rework').val());
        const tanggalSelesai = new Date($(this).val());
        
        if (tanggalSelesai < tanggalMulai) {
            $(this).addClass('is-invalid');
            if (!$(this).siblings('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback d-block">Tanggal selesai tidak boleh lebih awal dari tanggal mulai!</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        }
    });
});
</script>
@endpush
