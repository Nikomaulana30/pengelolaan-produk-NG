{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Edit Scrap/Disposal - ' . $scrap->nomor_scrap)

{{-- Isi content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Scrap/Disposal</h3>
                    <p class="text-subtitle text-muted">Nomor: <strong>{{ $scrap->nomor_scrap }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Edit Scrap/Disposal</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('scrap-disposal.update', $scrap) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- Display Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Validation Errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Catatan:</strong> Hanya data dengan status <strong>Pending</strong> yang dapat diedit.
                        </div>

                        <div class="row">
                            <!-- Nomor Scrap -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_scrap" class="form-label">Nomor Scrap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_scrap" name="nomor_scrap" 
                                           value="{{ $scrap->nomor_scrap }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>

                            <!-- Tanggal Scrap -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_scrap" class="form-label">Tanggal Scrap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tanggal_scrap" name="tanggal_scrap" 
                                           value="{{ $scrap->tanggal_scrap->format('d-m-Y H:i:s') }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Nomor Referensi -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_referensi" class="form-label">Nomor Referensi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nomor_referensi') is-invalid @enderror" id="nomor_referensi" name="nomor_referensi" 
                                           value="{{ old('nomor_referensi', $scrap->nomor_referensi) }}" 
                                           placeholder="Contoh: QC-20251216-0001 / STR-20251216-0001" required>
                                    @error('nomor_referensi')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Nama Barang -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang" name="nama_barang" 
                                           value="{{ old('nama_barang', $scrap->nama_barang) }}" 
                                           placeholder="Masukkan nama barang" required>
                                    @error('nama_barang')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Quantity -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" 
                                           value="{{ old('quantity', $scrap->quantity) }}" 
                                           placeholder="0" min="0" required>
                                    @error('quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pengaju -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_petugas" class="form-label">Nama Petugas <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" 
                                           value="{{ $scrap->nama_petugas }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>Alasan Scrap
                        </h5>

                        <div class="row">
                            <!-- Alasan Scrap -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="alasan_scrap" class="form-label">Alasan Scrap <span class="text-danger">*</span></label>
                                    <select class="form-select @error('alasan_scrap') is-invalid @enderror" id="alasan_scrap" name="alasan_scrap" required>
                                        <option value="">-- Pilih Alasan Scrap --</option>
                                        <option value="tidak_bisa_diperbaiki" @if(old('alasan_scrap', $scrap->alasan_scrap) === 'tidak_bisa_diperbaiki') selected @endif>❌ Tidak Bisa Diperbaiki (Unrepairable)</option>
                                        <option value="obsolete" @if(old('alasan_scrap', $scrap->alasan_scrap) === 'obsolete') selected @endif>📦 Obsolete (Usang/Tidak Terpakai)</option>
                                        <option value="expired" @if(old('alasan_scrap', $scrap->alasan_scrap) === 'expired') selected @endif>⏰ Expired (Kadaluarsa)</option>
                                        <option value="cacat_permanen" @if(old('alasan_scrap', $scrap->alasan_scrap) === 'cacat_permanen') selected @endif>🔴 Cacat Permanen (Permanent Defect)</option>
                                        <option value="tidak_ekonomis" @if(old('alasan_scrap', $scrap->alasan_scrap) === 'tidak_ekonomis') selected @endif>💰 Tidak Ekonomis untuk Diperbaiki</option>
                                    </select>
                                    @error('alasan_scrap')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Deskripsi Kondisi -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="deskripsi_kondisi" class="form-label">Deskripsi Kondisi Barang <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('deskripsi_kondisi') is-invalid @enderror" id="deskripsi_kondisi" name="deskripsi_kondisi" 
                                              rows="3" placeholder="Jelaskan detail kondisi barang yang tidak layak pakai..." required>{{ old('deskripsi_kondisi', $scrap->deskripsi_kondisi) }}</textarea>
                                    @error('deskripsi_kondisi')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">
                            <i class="bi bi-clipboard-check me-2"></i>Test Final QC (Bukti Tidak Layak)
                        </h5>

                        <div class="row">
                            <!-- Hasil Test QC -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hasil_test_qc" class="form-label">Hasil Test QC <span class="text-danger">*</span></label>
                                    <select class="form-select @error('hasil_test_qc') is-invalid @enderror" id="hasil_test_qc" name="hasil_test_qc" required>
                                        <option value="">-- Pilih Hasil Test --</option>
                                        <option value="tidak_lolos" @if(old('hasil_test_qc', $scrap->hasil_test_qc) === 'tidak_lolos') selected @endif>✗ Tidak Lolos (Failed)</option>
                                        <option value="tidak_memenuhi_standar" @if(old('hasil_test_qc', $scrap->hasil_test_qc) === 'tidak_memenuhi_standar') selected @endif>⚠ Tidak Memenuhi Standar</option>
                                        <option value="rusak_total" @if(old('hasil_test_qc', $scrap->hasil_test_qc) === 'rusak_total') selected @endif>❌ Rusak Total (Totally Damaged)</option>
                                        <option value="tidak_berfungsi" @if(old('hasil_test_qc', $scrap->hasil_test_qc) === 'tidak_berfungsi') selected @endif>🔧 Tidak Berfungsi (Not Functional)</option>
                                    </select>
                                    @error('hasil_test_qc')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tanggal Test QC -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_test_qc" class="form-label">Tanggal Test QC <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_test_qc') is-invalid @enderror" id="tanggal_test_qc" name="tanggal_test_qc" 
                                           value="{{ old('tanggal_test_qc', $scrap->tanggal_test_qc?->format('Y-m-d')) }}" required>
                                    @error('tanggal_test_qc')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- QC Inspector -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qc_inspector" class="form-label">QC Inspector <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('qc_inspector') is-invalid @enderror" id="qc_inspector" name="qc_inspector" 
                                           value="{{ old('qc_inspector', $scrap->qc_inspector) }}" 
                                           placeholder="Nama QC Inspector" required>
                                    @error('qc_inspector')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Catatan QC -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="catatan_qc" class="form-label">Catatan QC</label>
                                    <textarea class="form-control @error('catatan_qc') is-invalid @enderror" id="catatan_qc" name="catatan_qc" 
                                              rows="2" placeholder="Catatan tambahan dari QC...">{{ old('catatan_qc', $scrap->catatan_qc) }}</textarea>
                                    @error('catatan_qc')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">
                            <i class="bi bi-tools me-2"></i>Metode Scrap
                        </h5>

                        <div class="row">
                            <!-- Metode Scrap -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="metode_pembuangan" class="form-label">Metode Pembuangan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('metode_pembuangan') is-invalid @enderror" id="metode_pembuangan" name="metode_pembuangan" required>
                                        <option value="">-- Pilih Metode Pembuangan --</option>
                                        <option value="pembakaran" @if(old('metode_pembuangan', $scrap->metode_pembuangan) === 'pembakaran') selected @endif>🔥 Pembakaran</option>
                                        <option value="penguburan" @if(old('metode_pembuangan', $scrap->metode_pembuangan) === 'penguburan') selected @endif>⛰️ Penguburan</option>
                                        <option value="daur_ulang" @if(old('metode_pembuangan', $scrap->metode_pembuangan) === 'daur_ulang') selected @endif>♻️ Daur Ulang</option>
                                        <option value="penjualan_scrap" @if(old('metode_pembuangan', $scrap->metode_pembuangan) === 'penjualan_scrap') selected @endif>💵 Penjualan Scrap</option>
                                        <option value="lainnya" @if(old('metode_pembuangan', $scrap->metode_pembuangan) === 'lainnya') selected @endif>📋 Lainnya</option>
                                    </select>
                                    @error('metode_pembuangan')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tanggal Rencana Scrap -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_rencana_scrap" class="form-label">Tanggal Rencana Scrap <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_rencana_scrap') is-invalid @enderror" id="tanggal_rencana_scrap" name="tanggal_rencana_scrap" 
                                           value="{{ old('tanggal_rencana_scrap', $scrap->tanggal_rencana_scrap?->format('Y-m-d')) }}" required>
                                    @error('tanggal_rencana_scrap')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Pihak Pelaksana -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pihak_pelaksana" class="form-label">Pihak Pelaksana</label>
                                    <input type="text" class="form-control @error('pihak_pelaksana') is-invalid @enderror" id="pihak_pelaksana" name="pihak_pelaksana" 
                                           value="{{ old('pihak_pelaksana', $scrap->pihak_pelaksana) }}" 
                                           placeholder="Nama pihak yang melaksanakan scrap">
                                    @error('pihak_pelaksana')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Estimasi Biaya -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estimasi_biaya_pembuangan" class="form-label">Estimasi Biaya Pembuangan (Rp)</label>
                                    <input type="number" class="form-control @error('estimasi_biaya_pembuangan') is-invalid @enderror" id="estimasi_biaya_pembuangan" name="estimasi_biaya_pembuangan" 
                                           value="{{ old('estimasi_biaya_pembuangan', $scrap->estimasi_biaya_pembuangan) }}" 
                                           placeholder="0" min="0" step="1000">
                                    @error('estimasi_biaya_pembuangan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">
                            <i class="bi bi-camera me-2"></i>Dokumentasi Bukti (Foto atau Video)
                        </h5>

                        <div class="row">
                            <!-- Upload Dokumentasi -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="dokumen_bukti" class="form-label">Upload Dokumentasi</label>
                                    <input type="file" class="form-control @error('dokumen_bukti') is-invalid @enderror" id="dokumen_bukti" name="dokumen_bukti" 
                                           accept="image/*,.pdf" maxlength="5120">
                                    <small class="text-muted">Upload foto atau PDF sebagai bukti (opsional, max 5MB)</small>
                                    @if ($scrap->dokumen_bukti)
                                        <br><small class="text-success">Dokumen saat ini: {{ $scrap->dokumen_bukti }}</small>
                                    @endif
                                    @error('dokumen_bukti')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-check"></i> Update Data
                                </button>
                                <a href="{{ route('scrap-disposal.show', $scrap) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Batal
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </section>
    </div>

@endsection
