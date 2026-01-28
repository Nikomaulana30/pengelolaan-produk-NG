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
                    <h5 class="mb-0"><i class="bx bx-edit"></i> Edit Penyimpanan NG</h5>
                    <a href="{{ route('penyimpanan-ng.index') }}" class="btn btn-light btn-sm">Kembali</a>
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

                    <form action="{{ route('penyimpanan-ng.update', $penyimpananNg) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Section: Storage Information -->
                        <div class="form-section-title">📦 Storage Information</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label class="form-label">Nomor Storage</label>
                                    <input type="text" class="form-control" value="{{ $penyimpananNg->nomor_storage }}" disabled>
                                    <small class="form-text text-muted">Auto-generated</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label class="form-label">Tanggal Penyimpanan</label>
                                    <input type="text" class="form-control" value="{{ $penyimpananNg->tanggal_penyimpanan->format('d-m-Y H:i') }}" disabled>
                                    <small class="form-text text-muted">Auto-generated</small>
                                </div>
                            </div>
                        </div>

                        <!-- Section: References & Status -->
                        <div class="form-section-title">📋 References & Status</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="nomor_referensi" class="form-label">Nomor Referensi <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('nomor_referensi') is-invalid @enderror" 
                                        id="nomor_referensi" 
                                        name="nomor_referensi"
                                        value="{{ old('nomor_referensi', $penyimpananNg->nomor_referensi) }}"
                                        required>
                                    @error('nomor_referensi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="status_barang" class="form-label">Status Barang <span class="text-danger">*</span></label>
                                    <select 
                                        class="form-select @error('status_barang') is-invalid @enderror" 
                                        id="status_barang" 
                                        name="status_barang"
                                        required>
                                        <option value="">-- Pilih Status Barang --</option>
                                        <option value="disimpan" {{ old('status_barang', $penyimpananNg->status_barang) == 'disimpan' ? 'selected' : '' }}>📦 Disimpan</option>
                                        <option value="dalam_perbaikan" {{ old('status_barang', $penyimpananNg->status_barang) == 'dalam_perbaikan' ? 'selected' : '' }}>🔧 Dalam Perbaikan</option>
                                        <option value="menunggu_approval" {{ old('status_barang', $penyimpananNg->status_barang) == 'menunggu_approval' ? 'selected' : '' }}>⏳ Menunggu Approval</option>
                                        <option value="siap_dipindahkan" {{ old('status_barang', $penyimpananNg->status_barang) == 'siap_dipindahkan' ? 'selected' : '' }}>✓ Siap Dipindahkan</option>
                                        <option value="dipindahkan" {{ old('status_barang', $penyimpananNg->status_barang) == 'dipindahkan' ? 'selected' : '' }}>↗ Sudah Dipindahkan</option>
                                    </select>
                                    @error('status_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Item Information -->
                        <div class="form-section-title">📝 Item Details</div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('nama_barang') is-invalid @enderror" 
                                        id="nama_barang" 
                                        name="nama_barang"
                                        value="{{ old('nama_barang', $penyimpananNg->nama_barang) }}"
                                        required>
                                    @error('nama_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Location & Zone -->
                        <div class="form-section-title">📍 Location & Zone</div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <label for="master_lokasi_gudang_id" class="form-label">
                                        Pilih Lokasi Gudang <span class="text-danger">*</span>
                                    </label>
                                    <select name="master_lokasi_gudang_id" id="master_lokasi_gudang_id" 
                                            class="form-select @error('master_lokasi_gudang_id') is-invalid @enderror" 
                                            required onchange="populateLokasiDetail()">
                                        <option value="">-- Pilih Lokasi Penyimpanan --</option>
                                        @foreach($lokasiGudangs as $lokasi)
                                            @php
                                                $currentQty = $lokasi->penyimpananNgs->sum('qty_awal');
                                                $util = $lokasi->kapasitas_max > 0 
                                                    ? round(($currentQty / $lokasi->kapasitas_max) * 100)
                                                    : 0;
                                            @endphp
                                            <option value="{{ $lokasi->id }}" 
                                                    data-zone="{{ $lokasi->zone }}"
                                                    data-rack="{{ $lokasi->rack }}"
                                                    data-bin="{{ $lokasi->bin }}"
                                                    data-lokasi="{{ $lokasi->lokasi_lengkap }}"
                                                    data-kapasitas="{{ $lokasi->kapasitas_max }}"
                                                    data-current="{{ $currentQty }}"
                                                    {{ old('master_lokasi_gudang_id', $penyimpananNg->master_lokasi_gudang_id) == $lokasi->id ? 'selected' : '' }}>
                                                {{ $lokasi->lokasi_lengkap }} - {{ $lokasi->nama_lokasi }}
                                                ({{ number_format($currentQty) }}/{{ number_format($lokasi->kapasitas_max) }} - {{ $util }}% used)
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Pilih lokasi dari master data, detail akan terisi otomatis</small>
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
                                    <input type="text" id="zone_display" class="form-control bg-light" readonly>
                                    <input type="hidden" name="zone" id="zone">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label class="form-label">Rack</label>
                                    <input type="text" id="rack_display" class="form-control bg-light" readonly>
                                    <input type="hidden" name="rack" id="rack">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label class="form-label">Bin</label>
                                    <input type="text" id="bin_display" class="form-control bg-light" readonly>
                                    <input type="hidden" name="bin" id="bin">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label class="form-label">Lokasi Lengkap</label>
                                    <input type="text" id="lokasi_lengkap_display" class="form-control bg-light" readonly>
                                    <input type="hidden" name="lokasi_lengkap" id="lokasi_lengkap">
                                </div>
                            </div>
                        </div>

                        <!-- Section: Relokasi & Disposisi -->
                        <div class="form-section-title">📍 Relokasi & Disposisi</div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label for="zone_tujuan" class="form-label">Zone Tujuan</label>
                                    <select 
                                        class="form-select @error('zone_tujuan') is-invalid @enderror" 
                                        id="zone_tujuan" 
                                        name="zone_tujuan"
                                        onchange="generateLokasiTujuan()">
                                        <option value="">-- Pilih Zone --</option>
                                        <option value="zona_a" {{ old('zone_tujuan', $penyimpananNg->zone_tujuan) == 'zona_a' ? 'selected' : '' }}>Zona A</option>
                                        <option value="zona_b" {{ old('zone_tujuan', $penyimpananNg->zone_tujuan) == 'zona_b' ? 'selected' : '' }}>Zona B</option>
                                        <option value="zona_c" {{ old('zone_tujuan', $penyimpananNg->zone_tujuan) == 'zona_c' ? 'selected' : '' }}>Zona C</option>
                                        <option value="zona_d" {{ old('zone_tujuan', $penyimpananNg->zone_tujuan) == 'zona_d' ? 'selected' : '' }}>Zona D</option>
                                        <option value="zona_e" {{ old('zone_tujuan', $penyimpananNg->zone_tujuan) == 'zona_e' ? 'selected' : '' }}>Zona E</option>
                                    </select>
                                    @error('zone_tujuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Zona tujuan relokasi</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label for="rack_tujuan" class="form-label">Rack Tujuan</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('rack_tujuan') is-invalid @enderror" 
                                        id="rack_tujuan" 
                                        name="rack_tujuan"
                                        placeholder="Contoh: A1, B2"
                                        value="{{ old('rack_tujuan', $penyimpananNg->rack_tujuan) }}"
                                        onchange="generateLokasiTujuan()">
                                    @error('rack_tujuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Rack tujuan</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label for="bin_tujuan" class="form-label">Bin Tujuan</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('bin_tujuan') is-invalid @enderror" 
                                        id="bin_tujuan" 
                                        name="bin_tujuan"
                                        placeholder="Contoh: 001, 002"
                                        value="{{ old('bin_tujuan', $penyimpananNg->bin_tujuan) }}"
                                        onchange="generateLokasiTujuan()">
                                    @error('bin_tujuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Bin tujuan</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-box">
                                    <label for="lokasi_lengkap_tujuan" class="form-label">Lokasi Lengkap</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="lokasi_lengkap_tujuan" 
                                        name="lokasi_lengkap_tujuan"
                                        value="{{ old('lokasi_lengkap_tujuan', $penyimpananNg->lokasi_lengkap_tujuan) }}"
                                        disabled>
                                    <small class="form-text text-muted">Auto-generated</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="master_disposisi_id" class="form-label">Master Disposisi <span class="text-danger">*</span></label>
                                    <select 
                                        class="form-select @error('master_disposisi_id') is-invalid @enderror" 
                                        id="master_disposisi_id" 
                                        name="master_disposisi_id"
                                        required>
                                        <option value="">-- Pilih Disposisi --</option>
                                        @foreach($disposisis as $disposisi)
                                            <option value="{{ $disposisi->id }}" {{ old('master_disposisi_id', $penyimpananNg->master_disposisi_id) == $disposisi->id ? 'selected' : '' }}>
                                                {{ $disposisi->nama_disposisi }} ({{ $disposisi->jenis_tindakan }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('master_disposisi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Pilih jenis tindakan/disposisi untuk barang</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-box">
                                    <label for="tanggal_relokasi" class="form-label">Tanggal Relokasi</label>
                                    <input 
                                        type="datetime-local" 
                                        class="form-control @error('tanggal_relokasi') is-invalid @enderror" 
                                        id="tanggal_relokasi" 
                                        name="tanggal_relokasi"
                                        value="{{ old('tanggal_relokasi', $penyimpananNg->tanggal_relokasi ? $penyimpananNg->tanggal_relokasi->format('Y-m-d\TH:i') : '') }}">
                                    @error('tanggal_relokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Tanggal relokasi barang</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <label for="alasan_relokasi" class="form-label">Alasan Relokasi</label>
                                    <textarea 
                                        class="form-control @error('alasan_relokasi') is-invalid @enderror" 
                                        id="alasan_relokasi" 
                                        name="alasan_relokasi"
                                        rows="3"
                                        placeholder="Jelaskan alasan relokasi...">{{ old('alasan_relokasi', $penyimpananNg->alasan_relokasi) }}</textarea>
                                    @error('alasan_relokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Alasan perpindahan barang ke lokasi tujuan</small>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Quantity -->

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group-box">
                                    <label for="qty_awal" class="form-label">Quantity Awal <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('qty_awal') is-invalid @enderror" 
                                        id="qty_awal" 
                                        name="qty_awal"
                                        value="{{ old('qty_awal', $penyimpananNg->qty_awal) }}"
                                        onchange="calculateSelisih()"
                                        required>
                                    @error('qty_awal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-box">
                                    <label for="qty_setelah_perbaikan" class="form-label">Qty Setelah Perbaikan <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('qty_setelah_perbaikan') is-invalid @enderror" 
                                        id="qty_setelah_perbaikan" 
                                        name="qty_setelah_perbaikan"
                                        value="{{ old('qty_setelah_perbaikan', $penyimpananNg->qty_setelah_perbaikan) }}"
                                        onchange="calculateSelisih()"
                                        required>
                                    @error('qty_setelah_perbaikan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-box">
                                    <label for="selisih_qty" class="form-label">Selisih Quantity</label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="selisih_qty" 
                                        name="selisih_qty"
                                        value="{{ old('selisih_qty', $penyimpananNg->selisih_qty) }}"
                                        disabled>
                                    <small class="form-text text-muted">Auto-calculated</small>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Additional Notes -->
                        <div class="form-section-title">📝 Notes & Additional Info</div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-box">
                                    <label for="catatan" class="form-label">Catatan Tambahan</label>
                                    <textarea 
                                        class="form-control @error('catatan') is-invalid @enderror" 
                                        id="catatan" 
                                        name="catatan"
                                        rows="4"
                                        placeholder="Masukkan catatan tambahan...">{{ old('catatan', $penyimpananNg->catatan) }}</textarea>
                                    @error('catatan')
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
                                    <a href="{{ route('penyimpanan-ng.show', $penyimpananNg) }}" class="btn btn-secondary">
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

<script>
    function generateLokasi() {
        const zone = document.getElementById('zone').value;
        const rack = document.getElementById('rack').value;
        const bin = document.getElementById('bin').value;

        const zoneMap = {
            'zona_a': 'ZA',
            'zona_b': 'ZB',
            'zona_c': 'ZC',
            'zona_d': 'ZD',
            'zona_e': 'ZE'
        };

        const zoneCode = zoneMap[zone] || '';
        const lokasi = `${zoneCode}-${rack}-${bin}`;
        document.getElementById('lokasi_lengkap').value = lokasi;
    }

    function populateLokasiDetail() {
        const select = document.getElementById('master_lokasi_gudang_id');
        const option = select.options[select.selectedIndex];
        
        if (option.value) {
            const zone = option.dataset.zone;
            const rack = option.dataset.rack;
            const bin = option.dataset.bin;
            const lokasi = option.dataset.lokasi;
            
            document.getElementById('zone_display').value = zone.replace(/_/g, ' ').toUpperCase();
            document.getElementById('rack_display').value = rack;
            document.getElementById('bin_display').value = bin;
            document.getElementById('lokasi_lengkap_display').value = lokasi;
            
            document.getElementById('zone').value = zone;
            document.getElementById('rack').value = rack;
            document.getElementById('bin').value = bin;
            document.getElementById('lokasi_lengkap').value = lokasi;
        }
    }

    function generateLokasiTujuan() {
        const zone = document.getElementById('zone_tujuan').value;
        const rack = document.getElementById('rack_tujuan').value;
        const bin = document.getElementById('bin_tujuan').value;

        const zoneMap = {
            'zona_a': 'ZA',
            'zona_b': 'ZB',
            'zona_c': 'ZC',
            'zona_d': 'ZD',
            'zona_e': 'ZE'
        };

        const zoneCode = zoneMap[zone] || '';
        const lokasi = zone && rack && bin ? `${zoneCode}-${rack}-${bin}` : '';
        document.getElementById('lokasi_lengkap_tujuan').value = lokasi;
    }

    function calculateSelisih() {
        const qtyAwal = parseInt(document.getElementById('qty_awal').value) || 0;
        const qtySetelahPerbaikan = parseInt(document.getElementById('qty_setelah_perbaikan').value) || 0;
        const selisih = qtyAwal - qtySetelahPerbaikan;
        document.getElementById('selisih_qty').value = selisih;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        populateLokasiDetail();
        generateLokasiTujuan();
        calculateSelisih();
    });
</script>
@endsection
