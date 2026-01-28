{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'RCA Analysis')

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
                    <h3>Root Cause Analysis (RCA)</h3>
                    <p class="text-subtitle text-muted">Analisis akar penyebab masalah untuk menemukan solusi permanen</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <!-- Success Message -->
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Top Defect Chart -->
        @if($topDefects->count() > 0)
        <section class="section mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">📊 Top 10 Defect (Pareto Chart - 80/20 Rule)</h5>
                    <p class="text-muted small">Defect yang paling sering terjadi untuk difokuskan dalam RCA</p>
                </div>
                <div class="card-body">
                    <canvas id="paretoChart" height="80"></canvas>
                </div>
            </div>
        </section>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form RCA Analysis</h4>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_rca" class="form-label">Nomor RCA <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_rca" name="nomor_rca" 
                                           value="RCA-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}" 
                                           readonly style="background-color: #f0f0f0;">
                                    <small class="text-muted">Nomor RCA akan di-generate otomatis</small>
                                </div>
                            </div>

                            <!-- Tanggal Analisa -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_analisa" class="form-label">Tanggal Analisa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tanggal_analisa" name="tanggal_analisa" 
                                           value="{{ date('d-m-Y H:i:s') }}" 
                                           readonly style="background-color: #f0f0f0;">
                                    <small class="text-muted">Tanggal otomatis saat analisa dibuat</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Metode RCA -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="metode_rca" class="form-label">Metode RCA <span class="text-danger">*</span></label>
                                    <select class="form-select" id="metode_rca" name="metode_rca" required>
                                        <option value="">-- Pilih Metode RCA --</option>
                                        <option value="5_why">5 Why (Five Whys)</option>
                                        <option value="fishbone">Fishbone / Ishikawa Diagram</option>
                                        <option value="kombinasi">Kombinasi (5 Why + Fishbone)</option>
                                    </select>
                                    <small class="text-muted">Pilih metode analisis akar penyebab</small>
                                </div>
                            </div>

                            <!-- Kode Defect (dari Master Defect) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_defect" class="form-label">Pilih Defect <span class="text-danger">*</span></label>
                                    <select class="form-select" id="kode_defect" name="kode_defect">
                                        <option value="">-- Pilih Defect dari Master --</option>
                                        @foreach ($topDefects->take(5) as $defect)
                                            <option value="{{ $defect->kode_defect }}"
                                                data-criticality="{{ $defect->criticality_level ?? '' }}"
                                                data-sumber="{{ $defect->sumber_masalah ?? '' }}"
                                                data-nama="{{ $defect->nama_defect ?? '' }}">
                                                [{{ $defect->criticality_level }}] {{ $defect->kode_defect }} - {{ $defect->nama_defect }} ({{ $defect->total }}x)
                                            </option>
                                        @endforeach
                                        <optgroup label="Defect Lainnya">
                                            @foreach ($masterDefects as $defect)
                                                <option value="{{ $defect->kode_defect }}"
                                                    data-criticality="{{ $defect->criticality_level ?? '' }}"
                                                    data-sumber="{{ $defect->sumber_masalah ?? '' }}"
                                                    data-nama="{{ $defect->nama_defect ?? '' }}">
                                                    [{{ $defect->criticality_level }}] {{ $defect->kode_defect }} - {{ $defect->nama_defect }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    <small class="text-muted">Top 5 Defect muncul di atas untuk quick selection</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Kode Produk (dari Master Produk) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_barang" class="form-label">Pilih Produk (Opsional)</label>
                                    <select class="form-select" id="kode_barang" name="kode_barang">
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($topProduk->take(5) as $produk)
                                            <option value="{{ $produk->kode_produk }}"
                                                data-nama="{{ $produk->nama_produk }}">
                                                {{ $produk->kode_produk }} - {{ $produk->nama_produk }} ({{ $produk->total }}x NG)
                                            </option>
                                        @endforeach
                                        <optgroup label="Produk Lainnya">
                                            @foreach ($masterProduk as $produk)
                                                <option value="{{ $produk->kode_produk }}"
                                                    data-nama="{{ $produk->nama_produk }}">
                                                    {{ $produk->kode_produk }} - {{ $produk->nama_produk }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    <small class="text-muted">Top 5 Produk NG muncul di atas untuk quick selection</small>
                                </div>
                            </div>

                            <!-- Criticality Level (Auto-fill) -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="criticality_level" class="form-label">Criticality Level</label>
                                    <input type="text" class="form-control" id="criticality_level" name="criticality_level" 
                                           placeholder="Auto-fill" readonly style="background-color: #e9ecef;">
                                    <small class="text-muted">Auto-fill dari Master Defect</small>
                                </div>
                            </div>

                            <!-- Sumber Masalah (Auto-fill) -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sumber_masalah" class="form-label">Sumber Masalah</label>
                                    <input type="text" class="form-control" id="sumber_masalah" name="sumber_masalah" 
                                           placeholder="Auto-fill" readonly style="background-color: #e9ecef;">
                                    <small class="text-muted">Auto-fill dari Master Defect</small>
                                </div>
                            </div>
                        </div>

                        <!-- Link Retur Barang Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card bg-light border-info mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">🔗 Link Retur Barang (Opsional)</h6>
                                        <small>Hubungkan dengan transaksi retur barang untuk investigasi terpadu</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Retur Barang Selector -->
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="retur_barang_id" class="form-label">Cari Retur Barang</label>
                                                    <select class="form-select" id="retur_barang_id" name="retur_barang_id">
                                                        <option value="">-- Pilih Retur Barang (Opsional) --</option>
                                                        @forelse ($returBarangList ?? [] as $retur)
                                                            <option value="{{ $retur->id }}"
                                                                data-no-retur="{{ $retur->no_retur }}"
                                                                data-vendor="{{ $retur->vendor?->nama_vendor ?? 'Vendor Tidak Ditemukan' }}"
                                                                data-vendor-phone="{{ $retur->vendor?->telepon ?? '-' }}"
                                                                data-vendor-email="{{ $retur->vendor?->email ?? '-' }}"
                                                                data-produk="{{ $retur->produk?->nama_produk ?? 'Produk Tidak Ditemukan' }}"
                                                                data-kode-barang="{{ $retur->produk?->kode_produk ?? '' }}"
                                                                data-qty="{{ $retur->jumlah_retur }}"
                                                                data-satuan="{{ $retur->produk?->unit ?? 'unit' }}"
                                                                data-tanggal="{{ $retur->tanggal_retur->format('d-m-Y') }}"
                                                                data-deskripsi="{{ $retur->deskripsi_keluhan }}"
                                                                data-status="{{ $retur->status_approval }}">
                                                                {{ $retur->no_retur }} - {{ $retur->vendor?->nama_vendor ?? 'Vendor Tidak Ditemukan' }} ({{ $retur->produk?->nama_produk ?? 'Produk Tidak Ditemukan' }})
                                                            </option>
                                                        @empty
                                                            <option value="" disabled>Tidak ada retur yang tersedia</option>
                                                        @endforelse
                                                    </select>
                                                    <small class="text-muted d-block mt-2">
                                                        ✓ Hanya menampilkan retur dengan status Approved/Pending<br>
                                                        ✓ Biarkan kosong jika RCA untuk analisis standalone NG
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Unlink Button -->
                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="form-group w-100">
                                                    <button type="button" id="btnUnlinkRetur" class="btn btn-sm btn-outline-warning w-100" disabled>
                                                        <i class="bi bi-x-circle"></i> Bersihkan Pilihan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Retur Details Preview Card -->
                                        <div id="returPreview" class="mt-3" style="display: none;">
                                            <div class="alert alert-info alert-permanent border-start border-4 border-info">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-2"><strong>📋 No. Retur:</strong> <span id="previewNoRetur">-</span></p>
                                                        <p class="mb-2"><strong>🏢 Vendor:</strong> <span id="previewVendor">-</span></p>
                                                        <p class="mb-2"><strong>📦 Produk:</strong> <span id="previewProduk">-</span></p>
                                                        <p class="mb-2"><strong>📊 Qty:</strong> <span id="previewQty">-</span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-2"><strong>📅 Tanggal:</strong> <span id="previewTanggal">-</span></p>
                                                        <p class="mb-2"><strong>☎️ Kontak:</strong> <span id="previewKontak">-</span></p>
                                                        <p class="mb-2"><strong>📝 Keluhan:</strong> <span id="previewDeskripsi">-</span></p>
                                                        <p class="mb-0"><strong>✓ Status:</strong> <span id="previewStatus">-</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Penyebab Utama -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="penyebab_utama" class="form-label">Penyebab Utama (6M) <span class="text-danger">*</span></label>
                                    <select class="form-select" id="penyebab_utama" name="penyebab_utama" required>
                                        <option value="">-- Pilih Penyebab Utama --</option>
                                        <option value="human_error">🧑 Human Error (Kesalahan Manusia - Man)</option>
                                        <option value="metode_kerja">📋 Metode Kerja (Prosedur/SOP - Method)</option>
                                        <option value="material">📦 Material (Bahan Baku/Komponen - Material)</option>
                                        <option value="mesin">⚙️ Mesin (Equipment/Tools - Machine)</option>
                                        <option value="lingkungan">🌍 Lingkungan (Environment - Milieu)</option>
                                        <option value="pengukuran">📊 Pengukuran (Measurement - Measurement)</option>
                                    </select>
                                    <small class="text-muted">Kategori utama penyebab masalah berdasarkan model 6M</small>
                                </div>
                            </div>
                        </div>
                                    <textarea class="form-control" id="deskripsi_masalah" name="deskripsi_masalah" 
                                              rows="3" placeholder="Jelaskan masalah yang terjadi secara detail..." required></textarea>
                                    <small class="text-muted">Uraikan masalah yang akan dianalisis</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Analisa Detail (5 Why / Fishbone) -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="analisa_detail" class="form-label">Analisa Detail (5 Why / Fishbone) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="analisa_detail" name="analisa_detail" 
                                              rows="6" placeholder="Tulis hasil analisa menggunakan metode 5 Why atau Fishbone diagram..." required></textarea>
                                    <small class="text-muted">
                                        <strong>Contoh 5 Why:</strong> Why 1? ... Why 2? ... Why 3? ... Why 4? ... Why 5? (Root Cause)<br>
                                        <strong>Contoh Fishbone:</strong> Man: ... | Machine: ... | Material: ... | Method: ... | Environment: ...
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- PIC Analisa -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pic_analisa" class="form-label">PIC Analisa (Person in Charge) <span class="text-danger">*</span></label>
                                    <select class="form-select" id="pic_analisa" name="pic_analisa" required>
                                        <option value="">-- Pilih PIC Analisa --</option>
                                        <option value="qc">QC (Quality Control)</option>
                                        <option value="engineering">Engineering</option>
                                        <option value="warehouse">Warehouse</option>
                                        <option value="production">Production</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                    <small class="text-muted">Departemen yang bertanggung jawab melakukan analisa</small>
                                </div>
                            </div>

                            <!-- Nama Analis -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_analis" class="form-label">Nama Analis <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_analis" name="nama_analis" 
                                           value="{{ auth()->user()->name }}" 
                                           readonly style="background-color: #f0f0f0;">
                                    <small class="text-muted">Nama petugas yang melakukan analisa</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Due Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due_date" class="form-label">Due Date (Target Penyelesaian) <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" required>
                                    <small class="text-muted">Estimasi tanggal penyelesaian tindakan perbaikan</small>
                                </div>
                            </div>

                            <!-- Status RCA -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_rca" class="form-label">Status RCA <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status_rca" name="status_rca" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="open" selected>Open (Sedang Dikerjakan)</option>
                                        <option value="in_progress">In Progress (Dalam Proses)</option>
                                        <option value="closed">Closed (Selesai)</option>
                                    </select>
                                    <small class="text-muted">Status progress analisa dan perbaikan</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Recommendation / Tindakan Perbaikan</h5>

                        <div class="row">
                            <!-- Corrective Action (Tindakan Korektif) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="corrective_action" class="form-label">Corrective Action (Tindakan Korektif) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="corrective_action" name="corrective_action" 
                                              rows="5" placeholder="Tindakan untuk memperbaiki masalah yang sudah terjadi..." required></textarea>
                                    <small class="text-muted">
                                        <strong>Contoh:</strong> Mengganti komponen yang rusak, memperbaiki proses, training ulang operator
                                    </small>
                                </div>
                            </div>

                            <!-- Preventive Action (Tindakan Preventif) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="preventive_action" class="form-label">Preventive Action (Tindakan Preventif) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="preventive_action" name="preventive_action" 
                                              rows="5" placeholder="Tindakan untuk mencegah masalah terulang di masa depan..." required></textarea>
                                    <small class="text-muted">
                                        <strong>Contoh:</strong> Update SOP, implementasi checklist, scheduled maintenance, training berkala
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Catatan Tambahan -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="catatan" class="form-label">Catatan Tambahan</label>
                                    <textarea class="form-control" id="catatan" name="catatan" 
                                              rows="3" placeholder="Catatan atau informasi tambahan (opsional)"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-save"></i> Simpan RCA
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Form
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Tabel Data RCA -->
            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Riwayat Root Cause Analysis</h4>
                        <span class="badge bg-primary">{{ $rcaAnalyses->total() }} Data</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No RCA</th>
                                    <th>Tanggal</th>
                                    <th>Defect</th>
                                    <th>Metode</th>
                                    <th>PIC</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rcaAnalyses as $rca)
                                    <tr>
                                        <td><strong>{{ $rca->nomor_rca }}</strong></td>
                                        <td>{{ $rca->tanggal_analisa->format('d M Y') }}</td>
                                        <td>
                                            @if ($rca->masterDefect)
                                                <small>
                                                    <strong>{{ $rca->masterDefect->kode_defect }}</strong><br>
                                                    {{ $rca->masterDefect->nama_defect }}
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rca->metode_rca === '5_why')
                                                <span class="badge bg-info">5 Why</span>
                                            @elseif ($rca->metode_rca === 'fishbone')
                                                <span class="badge bg-primary">Fishbone</span>
                                            @else
                                                <span class="badge bg-secondary">Kombinasi</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rca->pic_analisa === 'qc')
                                                <span class="badge bg-info">QC</span>
                                            @elseif ($rca->pic_analisa === 'engineering')
                                                <span class="badge bg-primary">Engineering</span>
                                            @elseif ($rca->pic_analisa === 'warehouse')
                                                <span class="badge bg-success">Warehouse</span>
                                            @elseif ($rca->pic_analisa === 'production')
                                                <span class="badge bg-warning">Production</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($rca->pic_analisa) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rca->isOverdue())
                                                <strong class="text-danger">{{ $rca->due_date->format('d M Y') }} ⏰</strong>
                                            @else
                                                {{ $rca->due_date->format('d M Y') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rca->status_rca === 'open')
                                                <span class="badge bg-danger">🔴 Open</span>
                                            @elseif ($rca->status_rca === 'in_progress')
                                                <span class="badge bg-warning">🟡 In Progress</span>
                                            @else
                                                <span class="badge bg-success">🟢 Closed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('rca-analysis.show', $rca) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('rca-analysis.edit', $rca) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('rca-analysis.destroy', $rca) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus RCA ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Belum ada data RCA Analysis
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $rcaAnalyses->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

{{-- Untuk menggunakan js --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Auto-fill Criticality Level dan Sumber Masalah saat defect dipilih
        document.getElementById('kode_defect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const criticality = selectedOption.getAttribute('data-criticality');
            const sumber = selectedOption.getAttribute('data-sumber');
            
            document.getElementById('criticality_level').value = criticality || '';
            document.getElementById('sumber_masalah').value = sumber || '';
        });

        // Pareto Chart
        const paretoChartElement = document.getElementById('paretoChart');
        if (paretoChartElement) {
            const topDefects = @json($topDefects);
            const labels = topDefects.map(d => d.kode_defect + ' (' + d.total + 'x)');
            const data = topDefects.map(d => d.total);
            
            // Hitung cumulative percentage
            const total = data.reduce((a, b) => a + b, 0);
            let cumulative = 0;
            const cumulativePercentage = data.map(d => {
                cumulative += d;
                return Math.round((cumulative / total) * 100);
            });

            new Chart(paretoChartElement, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Frekuensi Defect',
                            data: data,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Cumulative %',
                            data: cumulativePercentage,
                            type: 'line',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 3,
                            pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                            pointRadius: 5,
                            yAxisID: 'y1',
                            tension: 0.4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        annotation: {
                            annotations: {
                                box1: {
                                    type: 'box',
                                    xScaleID: 'x',
                                    yScaleID: 'y',
                                    xMin: 0,
                                    xMax: 3,
                                    backgroundColor: 'rgba(255, 0, 0, 0.1)',
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Frekuensi'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Cumulative %'
                            },
                            min: 0,
                            max: 100,
                            grid: {
                                drawOnChartArea: false,
                            }
                        }
                    }
                }
            });
        }

        // Handle Retur Barang selection
        document.getElementById('retur_barang_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const noRetur = selectedOption.getAttribute('data-no-retur');
            const btnUnlink = document.getElementById('btnUnlinkRetur');
            const returPreview = document.getElementById('returPreview');

            if (this.value) {
                // Show preview
                document.getElementById('previewNoRetur').textContent = noRetur || '-';
                document.getElementById('previewVendor').textContent = selectedOption.getAttribute('data-vendor') || '-';
                document.getElementById('previewProduk').textContent = selectedOption.getAttribute('data-produk') || '-';
                document.getElementById('previewQty').textContent = selectedOption.getAttribute('data-qty') + ' ' + selectedOption.getAttribute('data-satuan');
                document.getElementById('previewTanggal').textContent = selectedOption.getAttribute('data-tanggal') || '-';
                
                const phone = selectedOption.getAttribute('data-vendor-phone');
                const email = selectedOption.getAttribute('data-vendor-email');
                document.getElementById('previewKontak').textContent = (phone !== '-' ? phone : '') + (phone !== '-' && email !== '-' ? ' / ' : '') + (email !== '-' ? email : '') || '-';
                
                document.getElementById('previewDeskripsi').textContent = selectedOption.getAttribute('data-deskripsi') || '-';
                
                const status = selectedOption.getAttribute('data-status');
                const statusBadge = status === 'approved' 
                    ? '<span class="badge bg-success">Approved</span>' 
                    : status === 'pending'
                    ? '<span class="badge bg-warning text-dark">Pending</span>'
                    : '<span class="badge bg-secondary">Rejected</span>';
                document.getElementById('previewStatus').innerHTML = statusBadge;

                // Auto-fill product code if not already selected
                const kodeBarang = selectedOption.getAttribute('data-kode-barang');
                if (kodeBarang && !document.getElementById('kode_barang').value) {
                    document.getElementById('kode_barang').value = kodeBarang;
                }

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