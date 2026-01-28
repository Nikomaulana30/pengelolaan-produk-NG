{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Scrap/Disposal')

{{-- Untuk menggunakan css --}}
@push('styles')
    {{-- contoh --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/static/css/pages/dashboard.css') }}"> --}}
@endpush

{{-- Isi content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Scrap/Disposal (Pembuangan Barang NG)</h3>
                    <p class="text-subtitle text-muted">Form untuk proses pembuangan barang yang tidak layak pakai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Scrap/Disposal</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('scrap-disposal.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
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
                        
                        <div class="row">
                            <!-- Nomor Scrap -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_scrap" class="form-label">Nomor Scrap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_scrap" name="nomor_scrap" 
                                           value="SCR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}" 
                                           readonly style="background-color: #f0f0f0;">
                                    <small class="text-muted">Nomor scrap akan di-generate otomatis</small>
                                </div>
                            </div>

                            <!-- Tanggal Scrap -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_scrap" class="form-label">Tanggal Scrap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tanggal_scrap" name="tanggal_scrap" 
                                           value="{{ date('d-m-Y H:i:s') }}" 
                                           readonly style="background-color: #f0f0f0;">
                                    <small class="text-muted">Tanggal otomatis saat pengajuan scrap</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Nomor Referensi -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_referensi" class="form-label">Nomor Referensi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_referensi" name="nomor_referensi" 
                                           placeholder="Contoh: QC-20251216-0001 / STR-20251216-0001" required>
                                    <small class="text-muted">Nomor rujukan dari dokumen terkait</small>
                                </div>
                            </div>

                            <!-- Nama Barang -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" 
                                           placeholder="Masukkan nama barang" required>
                                    <small class="text-muted">Nama barang yang akan di-scrap</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Quantity -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" 
                                           placeholder="0" min="0" required>
                                    <small class="text-muted">Jumlah barang yang akan di-scrap</small>
                                </div>
                            </div>

                            <!-- Pengaju -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_petugas" class="form-label">Nama Petugas <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" 
                                           value="{{ auth()->user()->name }}" 
                                           readonly style="background-color: #f0f0f0;">
                                    <small class="text-muted">Nama petugas yang mengajukan scrap</small>
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
                                    <select class="form-select" id="alasan_scrap" name="alasan_scrap" required>
                                        <option value="">-- Pilih Alasan Scrap --</option>
                                        <option value="tidak_bisa_diperbaiki">❌ Tidak Bisa Diperbaiki (Unrepairable)</option>
                                        <option value="obsolete">📦 Obsolete (Usang/Tidak Terpakai)</option>
                                        <option value="expired">⏰ Expired (Kadaluarsa)</option>
                                        <option value="cacat_permanen">🔴 Cacat Permanen (Permanent Defect)</option>
                                        <option value="tidak_ekonomis">💰 Tidak Ekonomis untuk Diperbaiki</option>
                                    </select>
                                    <small class="text-muted">Pilih alasan mengapa barang harus di-scrap</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Deskripsi Kondisi -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="deskripsi_kondisi" class="form-label">Deskripsi Kondisi Barang <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="deskripsi_kondisi" name="deskripsi_kondisi" 
                                              rows="3" placeholder="Jelaskan detail kondisi barang yang tidak layak pakai..." required></textarea>
                                    <small class="text-muted">Uraikan kondisi dan alasan detail mengapa barang harus di-scrap</small>
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
                                    <select class="form-select" id="hasil_test_qc" name="hasil_test_qc" required>
                                        <option value="">-- Pilih Hasil Test --</option>
                                        <option value="tidak_lolos">✗ Tidak Lolos (Failed)</option>
                                        <option value="tidak_memenuhi_standar">⚠ Tidak Memenuhi Standar</option>
                                        <option value="rusak_total">❌ Rusak Total (Totally Damaged)</option>
                                        <option value="tidak_berfungsi">🔧 Tidak Berfungsi (Not Functional)</option>
                                    </select>
                                    <small class="text-muted">Hasil test final dari QC</small>
                                </div>
                            </div>

                            <!-- Tanggal Test QC -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_test_qc" class="form-label">Tanggal Test QC <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_test_qc" name="tanggal_test_qc" required>
                                    <small class="text-muted">Tanggal dilakukan test QC</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- QC Inspector -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qc_inspector" class="form-label">QC Inspector <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="qc_inspector" name="qc_inspector" 
                                           placeholder="Nama QC Inspector" required>
                                    <small class="text-muted">Nama petugas QC yang melakukan test</small>
                                </div>
                            </div>

                            <!-- Catatan QC -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="catatan_qc" class="form-label">Catatan QC</label>
                                    <textarea class="form-control" id="catatan_qc" name="catatan_qc" 
                                              rows="2" placeholder="Catatan tambahan dari QC..."></textarea>
                                    <small class="text-muted">Catatan hasil test QC (opsional)</small>
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
                                    <select class="form-select" id="metode_pembuangan" name="metode_pembuangan" required>
                                        <option value="">-- Pilih Metode Pembuangan --</option>
                                        <option value="pembakaran">🔥 Pembakaran</option>
                                        <option value="penguburan">⛰️ Penguburan</option>
                                        <option value="daur_ulang">♻️ Daur Ulang</option>
                                        <option value="penjualan_scrap">💵 Penjualan Scrap</option>
                                        <option value="lainnya">📋 Lainnya</option>
                                    </select>
                                    <small class="text-muted">Metode pembuangan yang akan dilakukan</small>
                                </div>
                            </div>

                            <!-- Tanggal Rencana Scrap -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_rencana_scrap" class="form-label">Tanggal Rencana Scrap <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_rencana_scrap" name="tanggal_rencana_scrap" required>
                                    <small class="text-muted">Rencana tanggal pelaksanaan scrap</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Pihak Pelaksana -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pihak_pelaksana" class="form-label">Pihak Pelaksana</label>
                                    <input type="text" class="form-control" id="pihak_pelaksana" name="pihak_pelaksana" 
                                           placeholder="Nama pihak yang melaksanakan scrap">
                                    <small class="text-muted">Nama perusahaan/pihak yang akan melakukan scrap (opsional)</small>
                                </div>
                            </div>

                            <!-- Estimasi Biaya -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estimasi_biaya_pembuangan" class="form-label">Estimasi Biaya Pembuangan (Rp)</label>
                                    <input type="number" class="form-control" id="estimasi_biaya_pembuangan" name="estimasi_biaya_pembuangan" 
                                           placeholder="0" min="0" step="1000">
                                    <small class="text-muted">Estimasi biaya yang timbul untuk proses pembuangan (opsional)</small>
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
                                    <input type="file" class="form-control" id="dokumen_bukti" name="dokumen_bukti" 
                                           accept="image/*,.pdf" maxlength="5120">
                                    <small class="text-muted">Upload foto atau PDF sebagai bukti (opsional, max 5MB)</small>
                                </div>
                                
                                <!-- Preview Area -->
                                <div id="preview-dokumentasi" class="d-flex flex-wrap gap-2 mt-3">
                                    <!-- Preview akan muncul di sini -->
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">
                            <i class="bi bi-person-check-fill me-2"></i>Approval (Manager Wajib!)
                        </h5>

                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Penting!</strong> Proses scrap memerlukan persetujuan dari Manager. Pastikan semua data sudah benar sebelum mengajukan.
                        </div>

                        <div class="row">
                            <!-- Status Approval -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_approval" class="form-label">Status Approval Manager <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status_approval" name="status_approval" required>
                                        <option value="pending" selected>⏳ Pending (Menunggu Approval)</option>
                                        <option value="approved">✓ Approved (Disetujui)</option>
                                        <option value="rejected">✗ Rejected (Ditolak)</option>
                                        <option value="need_revision">⚠ Need Revision (Perlu Revisi)</option>
                                    </select>
                                    <small class="text-muted">Status persetujuan dari Manager</small>
                                </div>
                            </div>

                            <!-- Tanggal Approval -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_approval" class="form-label">Tanggal Approval</label>
                                    <input type="date" class="form-control" id="tanggal_approval" name="tanggal_approval">
                                    <small class="text-muted">Tanggal persetujuan Manager (akan diisi saat approved)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Nama Manager -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_manager" class="form-label">Nama Manager</label>
                                    <input type="text" class="form-control" id="nama_manager" name="nama_manager" 
                                           placeholder="Nama Manager yang memberikan approval">
                                    <small class="text-muted">Nama Manager yang menyetujui (akan diisi saat approved)</small>
                                </div>
                            </div>

                            <!-- Catatan Manager -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="catatan_manager" class="form-label">Catatan Manager</label>
                                    <textarea class="form-control" id="catatan_manager" name="catatan_manager" 
                                              rows="2" placeholder="Catatan dari Manager..."></textarea>
                                    <small class="text-muted">Catatan atau komentar dari Manager (opsional)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-danger me-2">
                                    <i class="bi bi-trash"></i> Submit Pengajuan Scrap
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Form
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Data Scrap/Disposal -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Riwayat Scrap/Disposal</h4>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Scrap</th>
                                    <th>Tanggal</th>
                                    <th>Petugas</th>
                                    <th>Alasan</th>
                                    <th>Metode</th>
                                    <th>Biaya</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($scraps as $scrap)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $scrap->nomor_scrap }}</strong></td>
                                        <td>{{ $scrap->tanggal_scrap->format('d/m/Y H:i') }}</td>
                                        <td>{{ $scrap->nama_petugas }}</td>
                                        <td>
                                            @if ($scrap->alasan_scrap === 'tidak_bisa_diperbaiki')
                                                <span class="badge bg-danger">❌ Tidak Bisa Diperbaiki</span>
                                            @elseif ($scrap->alasan_scrap === 'obsolete')
                                                <span class="badge bg-warning">📦 Obsolete</span>
                                            @elseif ($scrap->alasan_scrap === 'expired')
                                                <span class="badge bg-info">⏰ Expired</span>
                                            @elseif ($scrap->alasan_scrap === 'cacat_permanen')
                                                <span class="badge bg-danger">🔴 Cacat Permanen</span>
                                            @else
                                                <span class="badge bg-secondary">💰 Tidak Ekonomis</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($scrap->metode_pembuangan === 'pembakaran')
                                                <span class="badge bg-light text-dark">🔥 Pembakaran</span>
                                            @elseif ($scrap->metode_pembuangan === 'penguburan')
                                                <span class="badge bg-light text-dark">⛰️ Penguburan</span>
                                            @elseif ($scrap->metode_pembuangan === 'daur_ulang')
                                                <span class="badge bg-light text-dark">♻️ Daur Ulang</span>
                                            @elseif ($scrap->metode_pembuangan === 'penjualan_scrap')
                                                <span class="badge bg-light text-dark">💵 Penjualan Scrap</span>
                                            @else
                                                <span class="badge bg-light text-dark">📋 Lainnya</span>
                                            @endif
                                        </td>
                                        <td>@if ($scrap->estimasi_biaya_pembuangan)
                                                Rp {{ number_format($scrap->estimasi_biaya_pembuangan, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($scrap->status_approval === 'pending')
                                                <span class="badge bg-warning">⏳ Pending</span>
                                            @elseif ($scrap->status_approval === 'approved')
                                                <span class="badge bg-success">✓ Approved</span>
                                            @elseif ($scrap->status_approval === 'rejected')
                                                <span class="badge bg-danger">✗ Rejected</span>
                                            @else
                                                <span class="badge bg-info">⚠ Need Revision</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('scrap-disposal.show', $scrap) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if ($scrap->status_approval === 'pending')
                                                    <a href="{{ route('scrap-disposal.edit', $scrap) }}" class="btn btn-outline-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('scrap-disposal.destroy', $scrap) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Belum ada data scrap/disposal
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($scraps->count() > 0)
                        <div class="d-flex justify-content-center mt-3">
                            {{ $scraps->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

{{-- Untuk menggunakan js --}}
@push('scripts')
    <script>
        // Preview dokumentasi (foto/video)
        document.getElementById('dokumentasi_bukti').addEventListener('change', function(e) {
            const previewContainer = document.getElementById('preview-dokumentasi');
            previewContainer.innerHTML = '';
            
            const files = e.target.files;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                if (file.type.startsWith('image/')) {
                    // Preview untuk gambar
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'position-relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" 
                                 style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd;">
                            <small class="d-block text-center mt-1 text-muted">${file.name}</small>
                        `;
                        previewContainer.appendChild(div);
                    }
                    
                    reader.readAsDataURL(file);
                } else if (file.type.startsWith('video/')) {
                    // Preview untuk video
                    const div = document.createElement('div');
                    div.className = 'position-relative text-center';
                    div.innerHTML = `
                        <div style="width: 150px; height: 150px; display: flex; align-items: center; justify-content: center; border: 2px solid #ddd; border-radius: 8px; background-color: #f8f9fa;">
                            <i class="bi bi-camera-video" style="font-size: 48px; color: #6c757d;"></i>
                        </div>
                        <small class="d-block text-center mt-1 text-muted">${file.name}</small>
                    `;
                    previewContainer.appendChild(div);
                }
            }
        });

        // Auto disable/enable fields based on approval status
        document.getElementById('status_approval').addEventListener('change', function() {
            const tanggalApproval = document.getElementById('tanggal_approval');
            const namaManager = document.getElementById('nama_manager');
            
            if (this.value === 'approved') {
                tanggalApproval.required = true;
                namaManager.required = true;
                tanggalApproval.value = new Date().toISOString().split('T')[0];
            } else {
                tanggalApproval.required = false;
                namaManager.required = false;
            }
        });

        // Format currency for estimasi biaya
        document.getElementById('estimasi_biaya').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                e.target.value = parseInt(value);
            }
        });
    </script>
@endpush