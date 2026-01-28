@extends('layouts.app')

@section('content')
<style>
    .form-section-title {
        margin-top: 25px;
        margin-bottom: 15px;
        padding: 10px 0;
        border-bottom: 2px solid #007bff;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .form-group-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 12px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bx bx-edit"></i> Edit Penerimaan Barang NG</h5>
                    <a href="{{ route('penerimaan-barang.index') }}" class="btn btn-light btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bx bx-error"></i> Ada kesalahan:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('penerimaan-barang.update', $penerimaanBarang) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Section: Document Information -->
                        <div class="form-section-title">📋 Document Information</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label class="form-label">Nomor Dokumen</label>
                                    <input type="text" class="form-control" value="{{ $penerimaanBarang->nomor_dokumen }}" disabled>
                                    <small class="form-text text-muted">Auto-generated</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label class="form-label">Tanggal Input</label>
                                    <input type="text" class="form-control" value="{{ $penerimaanBarang->tanggal_input->format('d-m-Y H:i') }}" disabled>
                                    <small class="form-text text-muted">Auto-generated</small>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Type & Location -->
                        <div class="form-section-title">📍 Type & Location</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="jenis_pengembalian" class="form-label">Jenis Pengembalian <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis_pengembalian') is-invalid @enderror" id="jenis_pengembalian" name="jenis_pengembalian" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="internal" {{ old('jenis_pengembalian', $penerimaanBarang->jenis_pengembalian) === 'internal' ? 'selected' : '' }}>Internal</option>
                                        <option value="customer_return" {{ old('jenis_pengembalian', $penerimaanBarang->jenis_pengembalian) === 'customer_return' ? 'selected' : '' }}>Customer Return</option>
                                        <option value="rework" {{ old('jenis_pengembalian', $penerimaanBarang->jenis_pengembalian) === 'rework' ? 'selected' : '' }}>Rework</option>
                                    </select>
                                    @error('jenis_pengembalian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="lokasi_temuan" class="form-label">Lokasi Temuan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('lokasi_temuan') is-invalid @enderror" id="lokasi_temuan" name="lokasi_temuan" required>
                                        <option value="">-- Pilih Lokasi --</option>
                                        <option value="warehouse" {{ old('lokasi_temuan', $penerimaanBarang->lokasi_temuan) === 'warehouse' ? 'selected' : '' }}>Warehouse</option>
                                        <option value="production" {{ old('lokasi_temuan', $penerimaanBarang->lokasi_temuan) === 'production' ? 'selected' : '' }}>Production</option>
                                        <option value="quality" {{ old('lokasi_temuan', $penerimaanBarang->lokasi_temuan) === 'quality' ? 'selected' : '' }}>Quality</option>
                                        <option value="other" {{ old('lokasi_temuan', $penerimaanBarang->lokasi_temuan) === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('lokasi_temuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Item Information -->
                        <div class="form-section-title">📦 Item Information</div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('nama_barang') is-invalid @enderror" 
                                        id="nama_barang" 
                                        name="nama_barang"
                                        value="{{ old('nama_barang', $penerimaanBarang->nama_barang) }}"
                                        required>
                                    @error('nama_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group-box">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('sku') is-invalid @enderror" 
                                        id="sku" 
                                        name="sku"
                                        value="{{ old('sku', $penerimaanBarang->sku) }}">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-box">
                                    <label for="batch_number" class="form-label">Batch Number</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('batch_number') is-invalid @enderror" 
                                        id="batch_number" 
                                        name="batch_number"
                                        value="{{ old('batch_number', $penerimaanBarang->batch_number) }}">
                                    @error('batch_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-box">
                                    <label for="penginput" class="form-label">Penginput</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('penginput') is-invalid @enderror" 
                                        id="penginput" 
                                        name="penginput"
                                        value="{{ old('penginput', $penerimaanBarang->penginput) }}">
                                    @error('penginput')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Quantity Details -->
                        <div class="form-section-title">📊 Quantity Details</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="qty_baik" class="form-label">Quantity Baik <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('qty_baik') is-invalid @enderror" 
                                        id="qty_baik" 
                                        name="qty_baik"
                                        value="{{ old('qty_baik', $penerimaanBarang->qty_baik) }}"
                                        min="0"
                                        required>
                                    @error('qty_baik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="qty_rusak" class="form-label">Quantity Rusak</label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('qty_rusak') is-invalid @enderror" 
                                        id="qty_rusak" 
                                        name="qty_rusak"
                                        value="{{ old('qty_rusak', $penerimaanBarang->qty_rusak) }}"
                                        min="0">
                                    @error('qty_rusak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Lokasi Penyimpanan -->
                        <div class="form-section-title">📍 Lokasi Penyimpanan</div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <label for="master_lokasi_gudang_id" class="form-label">Pilih Lokasi Penyimpanan</label>
                                    <select name="master_lokasi_gudang_id" id="master_lokasi_gudang_id_edit" 
                                            class="form-select @error('master_lokasi_gudang_id') is-invalid @enderror" 
                                            onchange="populateLokasiEdit()">
                                        <option value="">-- Pilih Lokasi Penyimpanan (Opsional) --</option>
                                        @if(isset($lokasiGudangs))
                                            @foreach($lokasiGudangs as $lokasi)
                                                @php
                                                    $currentUsage = $lokasi->penyimpananNgs->sum('qty_sisa');
                                                    $utilization = $lokasi->kapasitas_max > 0 
                                                        ? round(($currentUsage / $lokasi->kapasitas_max) * 100)
                                                        : 0;
                                                @endphp
                                                <option value="{{ $lokasi->id }}" 
                                                        data-zone="{{ $lokasi->zone }}"
                                                        data-rack="{{ $lokasi->rack }}"
                                                        data-bin="{{ $lokasi->bin }}"
                                                        data-lokasi="{{ $lokasi->lokasi_lengkap }}"
                                                        data-kapasitas="{{ $lokasi->kapasitas_max }}"
                                                        data-current="{{ $currentUsage }}"
                                                        {{ old('master_lokasi_gudang_id', $penerimaanBarang->master_lokasi_gudang_id) == $lokasi->id ? 'selected' : '' }}>
                                                    {{ $lokasi->lokasi_lengkap }} - {{ $lokasi->nama_lokasi }}
                                                    ({{ number_format($currentUsage) }}/{{ number_format($lokasi->kapasitas_max) }} - {{ $utilization }}% used)
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('master_lokasi_gudang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label class="form-label">Zone</label>
                                    <input type="text" id="zone_display_edit" class="form-control bg-light" readonly 
                                           value="{{ $penerimaanBarang->zone ? ucfirst(str_replace('_', ' ', $penerimaanBarang->zone)) : '-' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label class="form-label">Rack</label>
                                    <input type="text" id="rack_display_edit" class="form-control bg-light" readonly 
                                           value="{{ $penerimaanBarang->rack ?? '-' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label class="form-label">Bin</label>
                                    <input type="text" id="bin_display_edit" class="form-control bg-light" readonly 
                                           value="{{ $penerimaanBarang->bin ?? '-' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label class="form-label">Lokasi Lengkap</label>
                                    <input type="text" id="lokasi_lengkap_display_edit" class="form-control bg-light" readonly 
                                           value="{{ $penerimaanBarang->lokasi_lengkap ?? '-' }}">
                                </div>
                            </div>
                        </div>

                        <div id="capacity_warning_edit" class="alert alert-warning" style="display: none;">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <span id="capacity_message_edit"></span>
                        </div>

                        <!-- Section: Status & QC -->
                        <div class="form-section-title">🔍 Status & Quality Control</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="status_penerimaan" class="form-label">Status Penerimaan <span class="text-danger">*</span></label>
                                    <select name="status_penerimaan" id="status_penerimaan" 
                                            class="form-select @error('status_penerimaan') is-invalid @enderror" required>
                                        <option value="diterima" {{ old('status_penerimaan', $penerimaanBarang->status_penerimaan ?? 'diterima') == 'diterima' ? 'selected' : '' }}>
                                            Diterima (Belum Inspeksi)
                                        </option>
                                        <option value="sedang_inspeksi" {{ old('status_penerimaan', $penerimaanBarang->status_penerimaan) == 'sedang_inspeksi' ? 'selected' : '' }}>
                                            Sedang Inspeksi
                                        </option>
                                        <option value="selesai_inspeksi" {{ old('status_penerimaan', $penerimaanBarang->status_penerimaan) == 'selesai_inspeksi' ? 'selected' : '' }}>
                                            Selesai Inspeksi
                                        </option>
                                        <option value="disimpan" {{ old('status_penerimaan', $penerimaanBarang->status_penerimaan) == 'disimpan' ? 'selected' : '' }}>
                                            Sudah Disimpan
                                        </option>
                                        <option value="ditolak" {{ old('status_penerimaan', $penerimaanBarang->status_penerimaan) == 'ditolak' ? 'selected' : '' }}>
                                            Ditolak
                                        </option>
                                    </select>
                                    @error('status_penerimaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="ada_defect" class="form-label">Ada Defect/NG?</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" 
                                               id="ada_defect" name="ada_defect" value="1"
                                               {{ old('ada_defect', $penerimaanBarang->ada_defect) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ada_defect">
                                            Ya, barang memiliki defect
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <label for="hasil_inspeksi" class="form-label">Hasil Inspeksi / Catatan QC</label>
                                    <textarea name="hasil_inspeksi" id="hasil_inspeksi" rows="3"
                                              class="form-control @error('hasil_inspeksi') is-invalid @enderror"
                                              placeholder="Catatan hasil inspeksi quality control...">{{ old('hasil_inspeksi', $penerimaanBarang->hasil_inspeksi) }}</textarea>
                                    @error('hasil_inspeksi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Additional Notes -->
                        <div class="form-section-title">📝 Notes & Additional Info</div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea 
                                        class="form-control @error('keterangan') is-invalid @enderror" 
                                        id="keterangan" 
                                        name="keterangan"
                                        rows="4"
                                        placeholder="Masukkan keterangan tambahan...">{{ old('keterangan', $penerimaanBarang->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #e3e6f0;">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bx bx-save"></i> Update
                                    </button>
                                    <a href="{{ route('penerimaan-barang.show', $penerimaanBarang) }}" class="btn btn-secondary">
                                        <i class="bx bx-arrow-back"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle ada_defect checkbox toggle
        const adaDefectCheckbox = document.getElementById('ada_defect');
        const hasilInspeksiGroup = document.querySelector('#hasil_inspeksi').closest('.form-group-box');
        
        // Function to toggle hasil inspeksi visibility and highlighting
        function toggleDefectFields() {
            if (adaDefectCheckbox.checked) {
                // Highlight the hasil_inspeksi field when defect is checked
                hasilInspeksiGroup.style.border = '2px solid #dc3545';
                hasilInspeksiGroup.style.padding = '15px';
                hasilInspeksiGroup.style.borderRadius = '8px';
                hasilInspeksiGroup.style.backgroundColor = '#fff3cd';
                
                // Make label bold and add icon
                const label = hasilInspeksiGroup.querySelector('label');
                label.innerHTML = '<i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Hasil Inspeksi / Catatan QC <span class="text-danger">*</span>';
                
                // Make field required
                document.getElementById('hasil_inspeksi').setAttribute('required', 'required');
            } else {
                // Remove highlighting
                hasilInspeksiGroup.style.border = '';
                hasilInspeksiGroup.style.padding = '15px';
                hasilInspeksiGroup.style.borderRadius = '';
                hasilInspeksiGroup.style.backgroundColor = '';
                
                // Reset label
                const label = hasilInspeksiGroup.querySelector('label');
                label.innerHTML = 'Hasil Inspeksi / Catatan QC';
                
                // Remove required
                document.getElementById('hasil_inspeksi').removeAttribute('required');
            }
        }
        
        // Initial state on page load
        toggleDefectFields();
        
        // Listen to checkbox changes
        adaDefectCheckbox.addEventListener('change', toggleDefectFields);
    });

    function populateLokasiEdit() {
        const select = document.getElementById('master_lokasi_gudang_id_edit');
        const option = select.options[select.selectedIndex];
        
        if (option.value) {
            // Get data from selected option
            const zone = option.dataset.zone;
            const rack = option.dataset.rack;
            const bin = option.dataset.bin;
            const lokasi = option.dataset.lokasi;
            const kapasitas = parseFloat(option.dataset.kapasitas);
            const current = parseFloat(option.dataset.current);
            
            // Populate display fields
            document.getElementById('zone_display_edit').value = zone ? zone.replace('_', ' ').toUpperCase() : '-';
            document.getElementById('rack_display_edit').value = rack || '-';
            document.getElementById('bin_display_edit').value = bin || '-';
            document.getElementById('lokasi_lengkap_display_edit').value = lokasi || '-';
            
            // Check capacity and show warning
            const utilization = (current / kapasitas) * 100;
            const warningDiv = document.getElementById('capacity_warning_edit');
            const messageSpan = document.getElementById('capacity_message_edit');
            
            if (utilization > 90) {
                messageSpan.textContent = `⚠️ Lokasi hampir penuh! (${utilization.toFixed(1)}% terisi). Pertimbangkan lokasi lain.`;
                warningDiv.className = 'alert alert-danger';
                warningDiv.style.display = 'block';
            } else if (utilization > 75) {
                messageSpan.textContent = `Lokasi ${utilization.toFixed(1)}% terisi`;
                warningDiv.className = 'alert alert-warning';
                warningDiv.style.display = 'block';
            } else {
                warningDiv.style.display = 'none';
            }
        } else {
            // Clear all fields
            document.getElementById('zone_display_edit').value = '-';
            document.getElementById('rack_display_edit').value = '-';
            document.getElementById('bin_display_edit').value = '-';
            document.getElementById('lokasi_lengkap_display_edit').value = '-';
            document.getElementById('capacity_warning_edit').style.display = 'none';
        }
    }
</script>
@endpush