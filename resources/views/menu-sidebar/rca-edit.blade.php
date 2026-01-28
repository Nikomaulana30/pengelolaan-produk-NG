@extends('layouts.app')

@section('title', 'Edit RCA Analysis')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-diagram-3"></i> Edit RCA Analysis</h3>
                    <p class="text-subtitle text-muted">Perbarui analisis akar penyebab masalah</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('rca-analysis.index') }}" class="btn btn-outline-secondary float-end">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Form Edit RCA Analysis</h5>
                    <p class="text-muted small">Nomor RCA: <strong>{{ $rcaAnalysis->nomor_rca }}</strong></p>
                </div>
                <div class="card-body">
                    <form action="{{ route('rca-analysis.update', $rcaAnalysis) }}" method="POST" class="needs-validation">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Metode RCA -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="metode_rca" class="form-label">Metode RCA <span class="text-danger">*</span></label>
                                    <select class="form-select @error('metode_rca') is-invalid @enderror" id="metode_rca" name="metode_rca" required>
                                        <option value="">-- Pilih Metode RCA --</option>
                                        <option value="5_why" @selected(old('metode_rca', $rcaAnalysis->metode_rca) === '5_why')>5 Why</option>
                                        <option value="fishbone" @selected(old('metode_rca', $rcaAnalysis->metode_rca) === 'fishbone')>Fishbone</option>
                                        <option value="kombinasi" @selected(old('metode_rca', $rcaAnalysis->metode_rca) === 'kombinasi')>Kombinasi</option>
                                    </select>
                                    @error('metode_rca')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kode Defect -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kode_defect" class="form-label">Pilih Defect</label>
                                    <select class="form-select @error('kode_defect') is-invalid @enderror" id="kode_defect" name="kode_defect">
                                        <option value="">-- Pilih Defect --</option>
                                        @foreach ($masterDefects as $defect)
                                            <option value="{{ $defect->kode_defect }}"
                                                @selected(old('kode_defect', $rcaAnalysis->kode_defect) === $defect->kode_defect)
                                                data-criticality="{{ $defect->criticality_level }}"
                                                data-sumber="{{ $defect->sumber_masalah }}">
                                                [{{ $defect->criticality_level }}] {{ $defect->kode_defect }} - {{ $defect->nama_defect }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kode_defect')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Kode Produk -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kode_barang" class="form-label">Pilih Produk (Opsional)</label>
                                    <select class="form-select @error('kode_barang') is-invalid @enderror" id="kode_barang" name="kode_barang">
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($masterProduk as $produk)
                                            <option value="{{ $produk->kode_produk }}"
                                                @selected(old('kode_barang', $rcaAnalysis->kode_barang) === $produk->kode_produk)>
                                                {{ $produk->kode_produk }} - {{ $produk->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kode_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Penyebab Utama -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="penyebab_utama" class="form-label">Penyebab Utama (6M) <span class="text-danger">*</span></label>
                                    <select class="form-select @error('penyebab_utama') is-invalid @enderror" id="penyebab_utama" name="penyebab_utama" required>
                                        <option value="">-- Pilih Penyebab Utama --</option>
                                        <option value="human_error" @selected(old('penyebab_utama', $rcaAnalysis->penyebab_utama) === 'human_error')>Human Error</option>
                                        <option value="metode_kerja" @selected(old('penyebab_utama', $rcaAnalysis->penyebab_utama) === 'metode_kerja')>Metode Kerja</option>
                                        <option value="material" @selected(old('penyebab_utama', $rcaAnalysis->penyebab_utama) === 'material')>Material</option>
                                        <option value="mesin" @selected(old('penyebab_utama', $rcaAnalysis->penyebab_utama) === 'mesin')>Mesin</option>
                                        <option value="lingkungan" @selected(old('penyebab_utama', $rcaAnalysis->penyebab_utama) === 'lingkungan')>Lingkungan</option>
                                        <option value="pengukuran" @selected(old('penyebab_utama', $rcaAnalysis->penyebab_utama) === 'pengukuran')>Pengukuran</option>
                                    </select>
                                    @error('penyebab_utama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Criticality Level -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="criticality_level" class="form-label">Criticality Level (Auto-fill)</label>
                                    <input type="text" class="form-control" id="criticality_level" name="criticality_level" 
                                           value="{{ old('criticality_level', $rcaAnalysis->criticality_level) }}" readonly style="background-color: #e9ecef;">
                                </div>
                            </div>

                            <!-- Sumber Masalah -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sumber_masalah" class="form-label">Sumber Masalah (Auto-fill)</label>
                                    <input type="text" class="form-control" id="sumber_masalah" name="sumber_masalah" 
                                           value="{{ old('sumber_masalah', $rcaAnalysis->sumber_masalah) }}" readonly style="background-color: #e9ecef;">
                                </div>
                            </div>
                        </div>

                        <!-- Link Retur Barang Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card bg-light border-info mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">🔗 Link Retur Barang (Opsional)</h6>
                                        <small>Ubah atau bersihkan hubungan dengan transaksi retur barang</small>
                                    </div>
                                    <div class="card-body">
                                        <!-- Current Retur Info -->
                                        @if ($rcaAnalysis->returBarang)
                                        <div class="alert alert-info alert-permanent mb-3" role="alert" style="border-left: 4px solid #17a2b8; background-color: #e7f3ff;">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong>📌 Retur Terkait Saat Ini:</strong><br>
                                                    {{ $rcaAnalysis->returBarang->no_retur }} - 
                                                    {{ $rcaAnalysis->returBarang->vendor?->nama_vendor ?? 'Vendor Tidak Ditemukan' }}
                                                    ({{ $rcaAnalysis->returBarang->produk?->nama_produk ?? 'Produk Tidak Ditemukan' }})
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="alert alert-secondary alert-permanent mb-3" role="alert" style="border-left: 4px solid #6c757d; background-color: #f0f0f0;">
                                            <strong>📌 Status:</strong> Tidak ada retur yang terhubung (Analisis Standalone)
                                        </div>
                                        @endif

                                        <div class="row">
                                            <!-- Retur Barang Selector -->
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="retur_barang_id" class="form-label">Cari Retur Barang</label>
                                                    <select class="form-select @error('retur_barang_id') is-invalid @enderror" id="retur_barang_id" name="retur_barang_id">
                                                        <option value="">-- Pilih Retur Barang (Opsional) --</option>
                                                        @foreach ($returBarangList as $retur)
                                                            <option value="{{ $retur->id }}"
                                                                @selected(old('retur_barang_id', $rcaAnalysis->retur_barang_id) == $retur->id)
                                                                data-no-retur="{{ $retur->no_retur }}"
                                                                data-vendor="{{ $retur->vendor?->nama_vendor ?? 'Vendor Tidak Ditemukan' }}"
                                                                data-vendor-phone="{{ $retur->vendor?->telepon ?? '-' }}"
                                                                data-vendor-email="{{ $retur->vendor?->email ?? '-' }}"
                                                                data-produk="{{ $retur->produk?->nama_produk ?? 'Produk Tidak Ditemukan' }}"
                                                                data-kode-barang="{{ $retur->produk?->kode_barang ?? '' }}"
                                                                data-qty="{{ $retur->jumlah_retur }}"
                                                                data-satuan="{{ $retur->produk?->unit ?? 'unit' }}"
                                                                data-tanggal="{{ $retur->tanggal_retur->format('d-m-Y') }}"
                                                                data-deskripsi="{{ $retur->deskripsi_keluhan }}"
                                                                data-status="{{ $retur->status_approval }}">
                                                                {{ $retur->no_retur }} - {{ $retur->vendor?->nama_vendor ?? 'Vendor Tidak Ditemukan' }} ({{ $retur->produk?->nama_produk ?? 'Produk Tidak Ditemukan' }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('retur_barang_id')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted d-block mt-2">
                                                        ✓ Hanya menampilkan retur dengan status Approved/Pending<br>
                                                        ✓ Pilih kosong untuk mengubah menjadi analisis standalone
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Unlink Button -->
                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="form-group w-100">
                                                    <button type="button" id="btnUnlinkRetur" class="btn btn-sm btn-outline-warning w-100" 
                                                        @disabled(!$rcaAnalysis->returBarang)>
                                                        <i class="bi bi-x-circle"></i> Bersihkan Pilihan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Retur Details Preview Card -->
                                        <div id="returPreview" class="mt-3" @style(['display: none' => !$rcaAnalysis->returBarang])>
                                            <div class="alert alert-info alert-permanent border-start border-4 border-info">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-2"><strong>📋 No. Retur:</strong> <span id="previewNoRetur">{{ $rcaAnalysis->returBarang?->no_retur ?? '-' }}</span></p>
                                                        <p class="mb-2"><strong>🏢 Vendor:</strong> <span id="previewVendor">{{ $rcaAnalysis->returBarang?->vendor?->nama_vendor ?? 'Vendor Tidak Ditemukan' }}</span></p>
                                                        <p class="mb-2"><strong>📦 Produk:</strong> <span id="previewProduk">{{ $rcaAnalysis->returBarang?->produk?->nama_produk ?? 'Produk Tidak Ditemukan' }}</span></p>
                                                        <p class="mb-2"><strong>📊 Qty:</strong> <span id="previewQty">{{ $rcaAnalysis->returBarang?->jumlah_retur ?? '-' }} {{ $rcaAnalysis->returBarang?->produk?->unit ?? 'unit' }}</span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-2"><strong>📅 Tanggal:</strong> <span id="previewTanggal">{{ $rcaAnalysis->returBarang?->tanggal_retur->format('d M Y') ?? '-' }}</span></p>
                                                        <p class="mb-2"><strong>☎️ Kontak:</strong> <span id="previewKontak">{{ $rcaAnalysis->returBarang?->vendor?->telepon ?? '-' }}</span> / <span>{{ $rcaAnalysis->returBarang?->vendor?->email ?? '-' }}</span></p>
                                                        <p class="mb-2"><strong>📝 Keluhan:</strong> <span id="previewDeskripsi" class="text-truncate">{{ Str::limit($rcaAnalysis->returBarang?->deskripsi_keluhan, 50) ?? '-' }}</span></p>
                                                        <p class="mb-0"><strong>✓ Status:</strong> <span id="previewStatus">
                                                            @if ($rcaAnalysis->returBarang)
                                                                @if ($rcaAnalysis->returBarang->status_approval === 'approved')
                                                                    <span class="badge bg-success">Approved</span>
                                                                @elseif ($rcaAnalysis->returBarang->status_approval === 'pending')
                                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                                @else
                                                                    <span class="badge bg-secondary">Rejected</span>
                                                                @endif
                                                            @endif
                                                        </span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <!-- Deskripsi Masalah -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="deskripsi_masalah" class="form-label">Deskripsi Masalah <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('deskripsi_masalah') is-invalid @enderror" id="deskripsi_masalah" name="deskripsi_masalah" rows="3" required>{{ old('deskripsi_masalah', $rcaAnalysis->deskripsi_masalah) }}</textarea>
                                    @error('deskripsi_masalah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Analisa Detail -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="analisa_detail" class="form-label">Analisa Detail (5 Why / Fishbone) <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('analisa_detail') is-invalid @enderror" id="analisa_detail" name="analisa_detail" rows="6" required>{{ old('analisa_detail', $rcaAnalysis->analisa_detail) }}</textarea>
                                    @error('analisa_detail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <!-- Corrective Action -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="corrective_action" class="form-label">Corrective Action <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('corrective_action') is-invalid @enderror" id="corrective_action" name="corrective_action" rows="4" required>{{ old('corrective_action', $rcaAnalysis->corrective_action) }}</textarea>
                                    @error('corrective_action')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Preventive Action -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="preventive_action" class="form-label">Preventive Action <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('preventive_action') is-invalid @enderror" id="preventive_action" name="preventive_action" rows="4" required>{{ old('preventive_action', $rcaAnalysis->preventive_action) }}</textarea>
                                    @error('preventive_action')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- PIC Analisa -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="pic_analisa" class="form-label">PIC Analisa <span class="text-danger">*</span></label>
                                    <select class="form-select @error('pic_analisa') is-invalid @enderror" id="pic_analisa" name="pic_analisa" required>
                                        <option value="">-- Pilih PIC --</option>
                                        <option value="qc" @selected(old('pic_analisa', $rcaAnalysis->pic_analisa) === 'qc')>QC</option>
                                        <option value="engineering" @selected(old('pic_analisa', $rcaAnalysis->pic_analisa) === 'engineering')>Engineering</option>
                                        <option value="warehouse" @selected(old('pic_analisa', $rcaAnalysis->pic_analisa) === 'warehouse')>Warehouse</option>
                                        <option value="production" @selected(old('pic_analisa', $rcaAnalysis->pic_analisa) === 'production')>Production</option>
                                        <option value="maintenance" @selected(old('pic_analisa', $rcaAnalysis->pic_analisa) === 'maintenance')>Maintenance</option>
                                    </select>
                                    @error('pic_analisa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status RCA -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status_rca" class="form-label">Status RCA <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status_rca') is-invalid @enderror" id="status_rca" name="status_rca" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="open" @selected(old('status_rca', $rcaAnalysis->status_rca) === 'open')>Open</option>
                                        <option value="in_progress" @selected(old('status_rca', $rcaAnalysis->status_rca) === 'in_progress')>In Progress</option>
                                        <option value="closed" @selected(old('status_rca', $rcaAnalysis->status_rca) === 'closed')>Closed</option>
                                    </select>
                                    @error('status_rca')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Due Date -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" 
                                           value="{{ old('due_date', $rcaAnalysis->due_date->format('Y-m-d')) }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Catatan -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="catatan" class="form-label">Catatan Tambahan</label>
                                    <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="2">{{ old('catatan', $rcaAnalysis->catatan) }}</textarea>
                                    @error('catatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-save"></i> Update RCA
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset Form
                                </button>
                                <a href="{{ route('rca-analysis.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-fill Criticality Level dan Sumber Masalah saat defect dipilih
    document.getElementById('kode_defect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('criticality_level').value = selectedOption.getAttribute('data-criticality') || '';
        document.getElementById('sumber_masalah').value = selectedOption.getAttribute('data-sumber') || '';
    });

    // Handle Retur Barang selection
    document.getElementById('retur_barang_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const noRetur = selectedOption.getAttribute('data-no-retur');
        const btnUnlink = document.getElementById('btnUnlinkRetur');
        const returPreview = document.getElementById('returPreview');

        if (this.value) {
            // Show preview dengan fallback untuk N/A values
            const vendor = selectedOption.getAttribute('data-vendor') || 'Vendor Tidak Ditemukan';
            const produk = selectedOption.getAttribute('data-produk') || 'Produk Tidak Ditemukan';
            const phone = selectedOption.getAttribute('data-vendor-phone') || '-';
            const email = selectedOption.getAttribute('data-vendor-email') || '-';
            const satuan = selectedOption.getAttribute('data-satuan') || 'unit';
            
            document.getElementById('previewNoRetur').textContent = noRetur || '-';
            document.getElementById('previewVendor').textContent = vendor;
            document.getElementById('previewProduk').textContent = produk;
            document.getElementById('previewQty').textContent = (selectedOption.getAttribute('data-qty') || '-') + ' ' + satuan;
            document.getElementById('previewTanggal').textContent = selectedOption.getAttribute('data-tanggal') || '-';
            
            // Format kontak dengan baik
            let kontak = '';
            if (phone !== '-') kontak += phone;
            if (phone !== '-' && email !== '-') kontak += ' / ';
            if (email !== '-') kontak += email;
            document.getElementById('previewKontak').textContent = kontak || '-';
            
            document.getElementById('previewDeskripsi').textContent = selectedOption.getAttribute('data-deskripsi') || '-';
            
            const status = selectedOption.getAttribute('data-status');
            const statusBadge = status === 'approved' 
                ? '<span class="badge bg-success">Approved</span>' 
                : status === 'pending'
                ? '<span class="badge bg-warning text-dark">Pending</span>'
                : '<span class="badge bg-secondary">Rejected</span>';
            document.getElementById('previewStatus').innerHTML = statusBadge;

            returPreview.style.display = 'block';
            btnUnlink.disabled = false;
        } else {
            returPreview.style.display = 'none';
            btnUnlink.disabled = true;
        }
    });

    // Handle unlink button
    document.getElementById('btnUnlinkRetur').addEventListener('click', function() {
        document.getElementById('retur_barang_id').value = '';
        document.getElementById('retur_barang_id').dispatchEvent(new Event('change'));
    });
</script>
@endpush
