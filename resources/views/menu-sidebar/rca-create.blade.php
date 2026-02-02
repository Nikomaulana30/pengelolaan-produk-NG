@extends('layouts.app')

@section('title', 'Tambah RCA Analysis')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-plus-square"></i> Tambah RCA Analysis</h3>
                    <p class="text-subtitle text-muted">Root Cause Analysis - Analisis Akar Penyebab Masalah</p>
                </div>
                <div class="col-12 col-md-4">
                    {{-- <a href="{{ route('rca-analysis.index') }}" class="btn btn-outline-secondary float-end"> --}}
                    <a href="#" class="btn btn-outline-secondary float-end disabled">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
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
                            <h5 class="card-title">Form RCA Analysis</h5>
                            <p class="text-muted small">Analisa akar penyebab masalah dan tentukan tindakan perbaikan</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('rca-analysis.store') }}" method="POST">
                                @csrf
                                
                                <!-- Validation Errors Display -->
                                @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Validation Errors</h6>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                @endif
                                
                                <div class="row">
                                    <!-- Nomor RCA -->
                                    <div class="col-md-6 mb-3">
                                        <label for="nomor_rca" class="form-label">Nomor RCA <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control bg-light" id="nomor_rca" name="nomor_rca" 
                                               value="RCA-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}" 
                                               readonly>
                                        <small class="text-muted">Nomor RCA akan di-generate otomatis</small>
                                    </div>

                                    <!-- Tanggal Analisa -->
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_analisa" class="form-label">Tanggal Analisa <span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-control @error('tanggal_analisa') is-invalid @enderror" 
                                               id="tanggal_analisa" name="tanggal_analisa" 
                                               value="{{ old('tanggal_analisa', now()->format('Y-m-d\TH:i')) }}" required>
                                        @error('tanggal_analisa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Metode RCA -->
                                    <div class="col-md-6 mb-3">
                                        <label for="metode_rca" class="form-label">Metode RCA <span class="text-danger">*</span></label>
                                        <select class="form-select @error('metode_rca') is-invalid @enderror" id="metode_rca" name="metode_rca" required>
                                            <option value="">-- Pilih Metode --</option>
                                            <option value="5_why" @selected(old('metode_rca') == '5_why')>5 Why Analysis</option>
                                            <option value="fishbone" @selected(old('metode_rca') == 'fishbone')>Fishbone Diagram</option>
                                            <option value="kombinasi" @selected(old('metode_rca') == 'kombinasi')>Kombinasi</option>
                                        </select>
                                        @error('metode_rca')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Kode Defect -->
                                    <div class="col-md-6 mb-3">
                                        <label for="kode_defect" class="form-label">Kode Defect <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kode_defect') is-invalid @enderror" id="kode_defect" name="kode_defect" required>
                                            <option value="">-- Pilih Defect --</option>
                                            @foreach ($masterDefects as $defect)
                                                <option value="{{ $defect->kode_defect }}" 
                                                        data-nama="{{ $defect->nama_defect }}"
                                                        data-criticality="{{ $defect->criticality_level }}"
                                                        @selected(old('kode_defect') == $defect->kode_defect)>
                                                    {{ $defect->kode_defect }} - {{ $defect->nama_defect }}
                                                    ({{ ucfirst($defect->criticality_level) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kode_defect')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Kode Barang -->
                                    <div class="col-md-6 mb-3">
                                        <label for="kode_barang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kode_barang') is-invalid @enderror" id="kode_barang" name="kode_barang" required>
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach ($masterProduk as $produk)
                                                <option value="{{ $produk->kode_produk }}" @selected(old('kode_barang') == $produk->kode_produk)>
                                                    {{ $produk->kode_produk }} - {{ $produk->nama_produk }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kode_barang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Retur Barang (Optional) -->
                                    <div class="col-md-6 mb-3">
                                        <label for="retur_barang_id" class="form-label">Link ke Retur Barang (Opsional)</label>
                                        <select class="form-select @error('retur_barang_id') is-invalid @enderror" id="retur_barang_id" name="retur_barang_id">
                                            <option value="">-- Tidak Ada --</option>
                                            @foreach ($returBarangList as $retur)
                                                <option value="{{ $retur->id }}" @selected(old('retur_barang_id') == $retur->id)>
                                                    {{ $retur->no_retur }} - {{ $retur->vendor?->nama_vendor }} - {{ $retur->produk?->nama_produk }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Jika RCA ini berkaitan dengan Retur Barang</small>
                                        @error('retur_barang_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Criticality Level -->
                                    <div class="col-md-6 mb-3">
                                        <label for="criticality_level" class="form-label">Tingkat Kekritisan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('criticality_level') is-invalid @enderror" id="criticality_level" name="criticality_level" required>
                                            <option value="">-- Pilih Tingkat --</option>
                                            <option value="low" @selected(old('criticality_level') == 'low')>🟢 Low - Rendah</option>
                                            <option value="medium" @selected(old('criticality_level') == 'medium')>🟡 Medium - Sedang</option>
                                            <option value="high" @selected(old('criticality_level') == 'high')>🟠 High - Tinggi</option>
                                            <option value="critical" @selected(old('criticality_level') == 'critical')>🔴 Critical - Kritis</option>
                                        </select>
                                        @error('criticality_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Sumber Masalah -->
                                    <div class="col-md-6 mb-3">
                                        <label for="sumber_masalah" class="form-label">Sumber Masalah <span class="text-danger">*</span></label>
                                        <select class="form-select @error('sumber_masalah') is-invalid @enderror" id="sumber_masalah" name="sumber_masalah" required>
                                            <option value="">-- Pilih Sumber --</option>
                                            <option value="material" @selected(old('sumber_masalah') == 'material')>Material</option>
                                            <option value="proses" @selected(old('sumber_masalah') == 'proses')>Proses Produksi</option>
                                            <option value="mesin" @selected(old('sumber_masalah') == 'mesin')>Mesin/Equipment</option>
                                            <option value="manusia" @selected(old('sumber_masalah') == 'manusia')>Manusia/SDM</option>
                                            <option value="metode" @selected(old('sumber_masalah') == 'metode')>Metode Kerja</option>
                                            <option value="lingkungan" @selected(old('sumber_masalah') == 'lingkungan')>Lingkungan</option>
                                            <option value="vendor" @selected(old('sumber_masalah') == 'vendor')>Vendor/Supplier</option>
                                        </select>
                                        @error('sumber_masalah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Deskripsi Masalah -->
                                    <div class="col-12 mb-3">
                                        <label for="deskripsi_masalah" class="form-label">Deskripsi Masalah <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('deskripsi_masalah') is-invalid @enderror" 
                                                  id="deskripsi_masalah" name="deskripsi_masalah" rows="3" required 
                                                  placeholder="Jelaskan masalah yang terjadi secara detail...">{{ old('deskripsi_masalah') }}</textarea>
                                        @error('deskripsi_masalah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Penyebab Utama -->
                                    <div class="col-12 mb-3">
                                        <label for="penyebab_utama" class="form-label">Penyebab Utama <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('penyebab_utama') is-invalid @enderror" 
                                                  id="penyebab_utama" name="penyebab_utama" rows="2" required 
                                                  placeholder="Akar penyebab masalah...">{{ old('penyebab_utama') }}</textarea>
                                        @error('penyebab_utama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Analisa Detail (JSON) -->
                                    <div class="col-12 mb-3">
                                        <label for="analisa_detail" class="form-label">Analisa Detail (JSON Format)</label>
                                        <textarea class="form-control @error('analisa_detail') is-invalid @enderror font-monospace" 
                                                  id="analisa_detail" name="analisa_detail" rows="5" 
                                                  placeholder='{"why_1": "Alasan pertama", "why_2": "Alasan kedua", ...}'>{{ old('analisa_detail') }}</textarea>
                                        <small class="text-muted">
                                            <strong>Contoh 5 Why:</strong> {"why_1": "...", "why_2": "...", "why_3": "...", "why_4": "...", "why_5": "..."}<br>
                                            <strong>Contoh Fishbone:</strong> {"man": ["..."], "machine": ["..."], "material": ["..."], "method": ["..."], "environment": ["..."]}
                                        </small>
                                        @error('analisa_detail')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Corrective Action -->
                                    <div class="col-md-6 mb-3">
                                        <label for="corrective_action" class="form-label">Corrective Action (Tindakan Korektif) <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('corrective_action') is-invalid @enderror" 
                                                  id="corrective_action" name="corrective_action" rows="3" required 
                                                  placeholder="Tindakan perbaikan segera...">{{ old('corrective_action') }}</textarea>
                                        <small class="text-muted">Tindakan untuk mengatasi masalah yang sudah terjadi</small>
                                        @error('corrective_action')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Preventive Action -->
                                    <div class="col-md-6 mb-3">
                                        <label for="preventive_action" class="form-label">Preventive Action (Tindakan Preventif) <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('preventive_action') is-invalid @enderror" 
                                                  id="preventive_action" name="preventive_action" rows="3" required 
                                                  placeholder="Tindakan pencegahan jangka panjang...">{{ old('preventive_action') }}</textarea>
                                        <small class="text-muted">Tindakan untuk mencegah masalah terulang di masa depan</small>
                                        @error('preventive_action')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- PIC Analisa -->
                                    <div class="col-md-4 mb-3">
                                        <label for="pic_analisa" class="form-label">PIC Analisa <span class="text-danger">*</span></label>
                                        <select class="form-select @error('pic_analisa') is-invalid @enderror" id="pic_analisa" name="pic_analisa" required>
                                            <option value="">-- Pilih PIC --</option>
                                            <option value="qc" @selected(old('pic_analisa') == 'qc')>QC</option>
                                            <option value="engineering" @selected(old('pic_analisa') == 'engineering')>Engineering</option>
                                            <option value="warehouse" @selected(old('pic_analisa') == 'warehouse')>Warehouse</option>
                                            <option value="production" @selected(old('pic_analisa') == 'production')>Production</option>
                                            <option value="maintenance" @selected(old('pic_analisa') == 'maintenance')>Maintenance</option>
                                            <option value="ppic" @selected(old('pic_analisa') == 'ppic')>PPIC</option>
                                        </select>
                                        @error('pic_analisa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Nama Analis -->
                                    <div class="col-md-4 mb-3">
                                        <label for="nama_analis" class="form-label">Nama Analis <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_analis') is-invalid @enderror" 
                                               id="nama_analis" name="nama_analis" 
                                               value="{{ old('nama_analis', auth()->user()->name) }}" required>
                                        @error('nama_analis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Due Date -->
                                    <div class="col-md-4 mb-3">
                                        <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                               id="due_date" name="due_date" 
                                               value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" required>
                                        <small class="text-muted">Target penyelesaian RCA</small>
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Status RCA -->
                                    <div class="col-md-6 mb-3">
                                        <label for="status_rca" class="form-label">Status RCA <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status_rca') is-invalid @enderror" id="status_rca" name="status_rca" required>
                                            <option value="open" @selected(old('status_rca', 'open') == 'open')>🔴 Open</option>
                                            <option value="in_progress" @selected(old('status_rca') == 'in_progress')>🟡 In Progress</option>
                                            <option value="closed" @selected(old('status_rca') == 'closed')>🟢 Closed</option>
                                        </select>
                                        @error('status_rca')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Catatan -->
                                    <div class="col-md-6 mb-3">
                                        <label for="catatan" class="form-label">Catatan</label>
                                        <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                                  id="catatan" name="catatan" rows="2" 
                                                  placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                                        @error('catatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 justify-content-end">
                                    {{-- <a href="{{ route('rca-analysis.index') }}" class="btn btn-secondary"> --}}
                                    <a href="#" class="btn btn-secondary disabled">
                                        <i class="bi bi-x-circle"></i> Batal
                                    </a>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Simpan RCA
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('styles')
<style>
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 0.375rem;
    }

    .font-monospace {
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-fill from defect selection
    document.getElementById('kode_defect').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const criticality = selected.getAttribute('data-criticality');
        
        if (criticality) {
            document.getElementById('criticality_level').value = criticality;
        }
    });

    // Validate JSON format on submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const analisa_detail = document.getElementById('analisa_detail').value;
        
        if (analisa_detail.trim()) {
            try {
                JSON.parse(analisa_detail);
            } catch (error) {
                e.preventDefault();
                alert('Format JSON tidak valid pada field "Analisa Detail". Mohon periksa kembali.');
                document.getElementById('analisa_detail').focus();
            }
        }
    });
</script>
@endpush
@endsection
