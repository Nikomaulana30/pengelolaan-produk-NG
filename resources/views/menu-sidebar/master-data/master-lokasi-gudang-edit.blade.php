@extends('layouts.app')

@section('title', 'Edit Lokasi Gudang')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Lokasi Gudang</h3>
                <p class="text-subtitle text-muted">Update informasi lokasi penyimpanan barang NG</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('master-lokasi-gudang.index') }}">Master Lokasi Gudang</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('master-lokasi-gudang.show', $masterLokasiGudang) }}">Detail</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                <h4 class="card-title">Form Edit Lokasi Gudang</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('master-lokasi-gudang.update', $masterLokasiGudang) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Kode Lokasi -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="kode_lokasi" class="form-label">Kode Lokasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kode_lokasi') is-invalid @enderror" 
                                       id="kode_lokasi" name="kode_lokasi" 
                                       placeholder="Contoh: LOC-A01" 
                                       value="{{ old('kode_lokasi', $masterLokasiGudang->kode_lokasi) }}" required>
                                @error('kode_lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Kode unik untuk identifikasi lokasi</small>
                            </div>
                        </div>

                        <!-- Nama Lokasi -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nama_lokasi" class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_lokasi') is-invalid @enderror" 
                                       id="nama_lokasi" name="nama_lokasi" 
                                       placeholder="Contoh: Rak A Bin 01" 
                                       value="{{ old('nama_lokasi', $masterLokasiGudang->nama_lokasi) }}" required>
                                @error('nama_lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Zone -->
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="zone" class="form-label">Zone <span class="text-danger">*</span></label>
                                <select class="form-select @error('zone') is-invalid @enderror" id="zone" name="zone" required>
                                    <option value="">-- Pilih Zone --</option>
                                    <option value="zona_a" {{ old('zone', $masterLokasiGudang->zone) == 'zona_a' ? 'selected' : '' }}>Zona A</option>
                                    <option value="zona_b" {{ old('zone', $masterLokasiGudang->zone) == 'zona_b' ? 'selected' : '' }}>Zona B</option>
                                    <option value="zona_c" {{ old('zone', $masterLokasiGudang->zone) == 'zona_c' ? 'selected' : '' }}>Zona C</option>
                                    <option value="zona_d" {{ old('zone', $masterLokasiGudang->zone) == 'zona_d' ? 'selected' : '' }}>Zona D</option>
                                </select>
                                @error('zone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Rack -->
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="rack" class="form-label">Rack <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('rack') is-invalid @enderror" 
                                       id="rack" name="rack" 
                                       placeholder="Contoh: R01" 
                                       value="{{ old('rack', $masterLokasiGudang->rack) }}" required>
                                @error('rack')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bin -->
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="bin" class="form-label">Bin <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('bin') is-invalid @enderror" 
                                       id="bin" name="bin" 
                                       placeholder="Contoh: B01" 
                                       value="{{ old('bin', $masterLokasiGudang->bin) }}" required>
                                @error('bin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Lokasi Lengkap Preview -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Lokasi Lengkap (Auto-generate)</label>
                                <input type="text" class="form-control bg-light" id="lokasi_lengkap_preview" readonly 
                                       value="{{ $masterLokasiGudang->lokasi_lengkap }}">
                                <small class="text-muted">Format: [Zone][Rack]-[Bin]</small>
                            </div>
                        </div>

                        <!-- Kapasitas Max -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="kapasitas_max" class="form-label">Kapasitas Maksimum <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('kapasitas_max') is-invalid @enderror" 
                                       id="kapasitas_max" name="kapasitas_max" min="1" 
                                       placeholder="Contoh: 1000" 
                                       value="{{ old('kapasitas_max', $masterLokasiGudang->kapasitas_max) }}" required>
                                @error('kapasitas_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Kapasitas maksimum dalam unit pcs</small>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" name="deskripsi" rows="3" 
                                          placeholder="Deskripsi atau catatan lokasi gudang">{{ old('deskripsi', $masterLokasiGudang->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status Active -->
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           {{ old('is_active', $masterLokasiGudang->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                </div>
                                <small class="text-muted">Centang jika lokasi ini aktif dan dapat digunakan</small>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('master-lokasi-gudang.show', $masterLokasiGudang) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Update
                                </button>
                            </div>
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
    // Auto-generate lokasi lengkap preview
    function updateLokasiLengkap() {
        const zone = $('#zone').val();
        const rack = $('#rack').val();
        const bin = $('#bin').val();
        
        if (zone && rack && bin) {
            const zoneLetter = zone.substring(5, 6).toUpperCase(); // Get 'a' from 'zona_a' and make it 'A'
            const lokasiLengkap = zoneLetter + rack + '-' + bin;
            $('#lokasi_lengkap_preview').val(lokasiLengkap);
        } else {
            $('#lokasi_lengkap_preview').val('');
        }
    }

    $('#zone, #rack, #bin').on('change keyup', updateLokasiLengkap);
});
</script>
@endpush
@endsection
