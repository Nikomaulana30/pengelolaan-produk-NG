@extends('layouts.app')

@section('title', 'Assign Disposisi')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12">
                    <h3><i class="bi bi-plus-circle"></i> Assign Disposisi Baru</h3>
                    <p class="text-subtitle text-muted">Assign disposisi untuk barang NG</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Form Assign Disposisi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('disposisi-assignment.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="penyimpanan_ng_id" class="form-label">Pilih Barang dari Penyimpanan NG <span class="text-danger">*</span></label>
                                    <select name="penyimpanan_ng_id" id="penyimpanan_ng_id" class="form-select @error('penyimpanan_ng_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Barang yang Akan Didisposisi --</option>
                                        @foreach ($penyimpananNgs as $penyimpanan)
                                            <option value="{{ $penyimpanan->id }}" 
                                                    {{ old('penyimpanan_ng_id') == $penyimpanan->id ? 'selected' : '' }}
                                                    data-lokasi="{{ $penyimpanan->lokasi_lengkap }}"
                                                    data-qty="{{ $penyimpanan->qty_awal }}"
                                                    data-status="{{ $penyimpanan->status_barang }}">
                                                {{ $penyimpanan->nomor_storage }} - {{ $penyimpanan->nama_barang }} 
                                                ({{ $penyimpanan->lokasi_lengkap }}) - Qty: {{ $penyimpanan->qty_awal }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('penyimpanan_ng_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Pilih barang dari storage NG yang akan didisposisi</small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="master_disposisi_id" class="form-label">Pilih Jenis Disposisi <span class="text-danger">*</span></label>
                                    <select name="master_disposisi_id" id="master_disposisi_id" class="form-select @error('master_disposisi_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Jenis Disposisi --</option>
                                        @foreach ($disposisis as $disposisi)
                                            <option value="{{ $disposisi->id }}" 
                                                    {{ old('master_disposisi_id') == $disposisi->id ? 'selected' : '' }}
                                                    data-jenis="{{ $disposisi->jenis_tindakan }}"
                                                    data-lokasi="{{ $disposisi->lokasi_lengkap_tujuan }}">
                                                {{ $disposisi->nama_disposisi }} 
                                                ({{ ucfirst(str_replace('_', ' ', $disposisi->jenis_tindakan)) }})
                                                @if($disposisi->lokasi_lengkap_tujuan)
                                                    → {{ $disposisi->lokasi_lengkap_tujuan }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('master_disposisi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Pilih tindakan disposisi yang akan dilakukan</small>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Lokasi Tujuan Relokasi -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">📍 Lokasi Tujuan Relokasi</h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-3">
                                <div class="form-group mb-3">
                                    <label for="zone_tujuan" class="form-label">Zone Tujuan</label>
                                    <select name="zone_tujuan" id="zone_tujuan" class="form-select @error('zone_tujuan') is-invalid @enderror" onchange="generateLokasiTujuan()">
                                        <option value="">-- Pilih Zone --</option>
                                        <option value="zona_a" {{ old('zone_tujuan') == 'zona_a' ? 'selected' : '' }}>Zona A</option>
                                        <option value="zona_b" {{ old('zone_tujuan') == 'zona_b' ? 'selected' : '' }}>Zona B</option>
                                        <option value="zona_c" {{ old('zone_tujuan') == 'zona_c' ? 'selected' : '' }}>Zona C</option>
                                        <option value="zona_d" {{ old('zone_tujuan') == 'zona_d' ? 'selected' : '' }}>Zona D</option>
                                        <option value="zona_e" {{ old('zone_tujuan') == 'zona_e' ? 'selected' : '' }}>Zona E</option>
                                    </select>
                                    @error('zone_tujuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-group mb-3">
                                    <label for="rack_tujuan" class="form-label">Rack Tujuan</label>
                                    <input type="text" name="rack_tujuan" id="rack_tujuan" class="form-control @error('rack_tujuan') is-invalid @enderror" placeholder="Contoh: A1, B2" value="{{ old('rack_tujuan') }}" onchange="generateLokasiTujuan()">
                                    @error('rack_tujuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-group mb-3">
                                    <label for="bin_tujuan" class="form-label">Bin Tujuan</label>
                                    <input type="text" name="bin_tujuan" id="bin_tujuan" class="form-control @error('bin_tujuan') is-invalid @enderror" placeholder="Contoh: 001, 002" value="{{ old('bin_tujuan') }}" onchange="generateLokasiTujuan()">
                                    @error('bin_tujuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-group mb-3">
                                    <label for="lokasi_lengkap_tujuan" class="form-label">Lokasi Lengkap</label>
                                    <input type="text" name="lokasi_lengkap_tujuan" id="lokasi_lengkap_tujuan" class="form-control @error('lokasi_lengkap_tujuan') is-invalid @enderror" value="{{ old('lokasi_lengkap_tujuan') }}" readonly style="background-color: #f8f9fa;">
                                    @error('lokasi_lengkap_tujuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Auto-generated dari Zone-Rack-Bin</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_relokasi" class="form-label">Tanggal Relokasi</label>
                                    <input type="datetime-local" name="tanggal_relokasi" id="tanggal_relokasi" class="form-control @error('tanggal_relokasi') is-invalid @enderror" value="{{ old('tanggal_relokasi') }}">
                                    @error('tanggal_relokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="alasan_relokasi" class="form-label">Alasan Relokasi</label>
                                    <input type="text" name="alasan_relokasi" id="alasan_relokasi" class="form-control @error('alasan_relokasi') is-invalid @enderror" placeholder="Masukkan alasan relokasi" value="{{ old('alasan_relokasi') }}">
                                    @error('alasan_relokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="catatan" class="form-label">Catatan</label>
                                    <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="4" placeholder="Masukkan catatan untuk disposisi ini...">{{ old('catatan') }}</textarea>
                                    @error('catatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Assign Disposisi
                                    </button>
                                    <a href="{{ route('disposisi-assignment.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Batal
                                    </a>
                                </div>
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        generateLokasiTujuan();
    });
</script>
@endpush
