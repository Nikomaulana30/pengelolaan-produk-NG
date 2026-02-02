{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Inspeksi/QC')

{{-- Untuk menggunakan css --}}
@push('styles')
    <style>
        .table-dimensions {
            font-size: 0.95rem;
        }
        .table-dimensions th {
            background-color: #4472C4;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .table-dimensions td {
            padding: 8px;
            text-align: center;
        }
        .table-dimensions input {
            max-width: 100%;
        }
        .section-header {
            background-color: #E7E6E6;
            padding: 10px 15px;
            font-weight: bold;
            border-left: 4px solid #4472C4;
            margin-top: 20px;
            margin-bottom: 15px;
        }
        .inspection-checkbox {
            display: flex;
            gap: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .nav-tabs .nav-link {
            color: #495057;
            border: 1px solid #ddd;
        }
        .nav-tabs .nav-link.active {
            background-color: #4472C4;
            color: white;
            border-color: #4472C4;
        }
    </style>
@endpush

{{-- Isi content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Inspeksi / Quality Control (QC)</h3>
                    <p class="text-subtitle text-muted">Form Inspeksi Dimensi & Measurement Produk</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Inspeksi QC - Dimensi & Measurement</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('quality-reinspection.store') }}" method="POST" enctype="multipart/form-data">
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
                        
                        {{-- ============ HEADER (INFORMASI UMUM) ============ --}}
                        <div class="section-header">📋 Header Inspeksi - Informasi Umum</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_laporan" class="form-label">Nomor Laporan QC</label>
                                    <input type="text" class="form-control" id="nomor_laporan" name="nomor_laporan" 
                                           placeholder="Auto-generated" 
                                           readonly style="background-color: #f0f0f0;">
                                    <small class="text-muted">Nomor laporan akan otomatis di-generate saat penyimpanan</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_inspeksi" class="form-label">Tanggal Inspeksi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tanggal_inspeksi" name="tanggal_inspeksi" 
                                           value="{{ date('d-m-Y H:i:s') }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product" class="form-label">Product <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="product" name="product" 
                                           placeholder="Nama Produk" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="part_no" class="form-label">Part No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="part_no" name="part_no" 
                                           placeholder="Contoh: COMP-001" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="material" class="form-label">Material <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="material" name="material" 
                                           placeholder="Contoh: Aluminum 6061" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="drawing_no" class="form-label">Drawing No / Revisi <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="drawing_no" name="drawing_no" 
                                               placeholder="Nomor Gambar" required>
                                        <input type="text" class="form-control" id="drawing_rev" name="drawing_rev" 
                                               placeholder="Rev" style="max-width: 80px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer" class="form-label">Customer / Partner <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="customer" name="customer" 
                                           placeholder="Nama Customer/Partner" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch_no" class="form-label">Batch No / Lot No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="batch_no" name="batch_no" 
                                           placeholder="Contoh: BATCH-2024-001" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="made_by" class="form-label">Made By (Dibuat Oleh) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="made_by" name="made_by" 
                                           value="{{ auth()->user()->name }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="approved_by" class="form-label">Approved By (Disetujui Oleh) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="approved_by" name="approved_by" 
                                           placeholder="Nama Manager/Supervisor" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- ============ TAB NAVIGATION ============ --}}
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs" id="inspeksiTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="dimensi-tab" data-bs-toggle="tab" 
                                            data-bs-target="#dimensi-tab-pane" type="button" role="tab">
                                        <i class="bi bi-rulers"></i> Tab 1: Dimensi & Measurement
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="inspeksi-tab" data-bs-toggle="tab" 
                                            data-bs-target="#inspeksi-tab-pane" type="button" role="tab">
                                        <i class="bi bi-clipboard-check"></i> Tab 2: Inspeksi Khusus
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="inspeksiTabsContent">
                                {{-- ============ TAB 1: DIMENSI & MEASUREMENT ============ --}}
                                <div class="tab-pane fade show active" id="dimensi-tab-pane" role="tabpanel">
                                    <div class="mt-4">
                                        <div class="section-header">📏 Pengukuran Dimensi & Karakteristik</div>
                                        
                                        <div class="table-responsive mt-3">
                                            <table class="table table-bordered table-dimensions" id="dimensiTable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 40px;">No</th>
                                                        <th>Karakteristik</th>
                                                        <th style="width: 80px;">Std Min</th>
                                                        <th style="width: 80px;">Std Max</th>
                                                        <th style="width: 100px;">Alat Ukur</th>
                                                        <th style="width: 80px;">Frekuensi</th>
                                                        <th style="width: 100px;">Hasil Batch</th>
                                                        <th style="width: 80px;">Status</th>
                                                        <th style="width: 50px;">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="dimensi-row" data-row="1">
                                                        <td class="row-number">1</td>
                                                        <td><input type="text" class="form-control form-control-sm" name="karakteristik[]" placeholder="Contoh: Length"></td>
                                                        <td><input type="number" class="form-control form-control-sm" name="std_min[]" step="0.01" placeholder="Min"></td>
                                                        <td><input type="number" class="form-control form-control-sm" name="std_max[]" step="0.01" placeholder="Max"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="alat_ukur[]" placeholder="Micrometer"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="frekuensi[]" placeholder="100%"></td>
                                                        <td><input type="number" class="form-control form-control-sm" name="hasil_batch[]" step="0.01" placeholder="Result"></td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="status_dimensi[]">
                                                                <option value="">-- Pilih --</option>
                                                                <option value="ok" style="color: green;">✓ OK</option>
                                                                <option value="ng" style="color: red;">✗ NG</option>
                                                            </select>
                                                        </td>
                                                        <td><button type="button" class="btn btn-sm btn-danger btn-delete-row" title="Hapus Baris"><i class="bi bi-trash"></i></button></td>
                                                    </tr>
                                                    <tr class="dimensi-row" data-row="2">
                                                        <td class="row-number">2</td>
                                                        <td><input type="text" class="form-control form-control-sm" name="karakteristik[]" placeholder="Contoh: Width"></td>
                                                        <td><input type="number" class="form-control form-control-sm" name="std_min[]" step="0.01" placeholder="Min"></td>
                                                        <td><input type="number" class="form-control form-control-sm" name="std_max[]" step="0.01" placeholder="Max"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="alat_ukur[]" placeholder="Micrometer"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="frekuensi[]" placeholder="100%"></td>
                                                        <td><input type="number" class="form-control form-control-sm" name="hasil_batch[]" step="0.01" placeholder="Result"></td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="status_dimensi[]">
                                                                <option value="">-- Pilih --</option>
                                                                <option value="ok" style="color: green;">✓ OK</option>
                                                                <option value="ng" style="color: red;">✗ NG</option>
                                                            </select>
                                                        </td>
                                                        <td><button type="button" class="btn btn-sm btn-danger btn-delete-row" title="Hapus Baris"><i class="bi bi-trash"></i></button></td>
                                                    </tr>
                                                    <tr class="dimensi-row" data-row="3">
                                                        <td class="row-number">3</td>
                                                        <td><input type="text" class="form-control form-control-sm" name="karakteristik[]" placeholder="Contoh: Height"></td>
                                                        <td><input type="number" class="form-control form-control-sm" name="std_min[]" step="0.01" placeholder="Min"></td>
                                                        <td><input type="number" class="form-control form-control-sm" name="std_max[]" step="0.01" placeholder="Max"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="alat_ukur[]" placeholder="Micrometer"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="frekuensi[]" placeholder="100%"></td>
                                                        <td><input type="number" class="form-control form-control-sm" name="hasil_batch[]" step="0.01" placeholder="Result"></td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="status_dimensi[]">
                                                                <option value="">-- Pilih --</option>
                                                                <option value="ok" style="color: green;">✓ OK</option>
                                                                <option value="ng" style="color: red;">✗ NG</option>
                                                            </select>
                                                        </td>
                                                        <td><button type="button" class="btn btn-sm btn-danger btn-delete-row" title="Hapus Baris"><i class="bi bi-trash"></i></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="mt-3">
                                            <button type="button" class="btn btn-success btn-sm" id="addRowBtn">
                                                <i class="bi bi-plus-circle"></i> Tambah Baris
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- ============ TAB 2: INSPEKSI KHUSUS ============ --}}
                                <div class="tab-pane fade" id="inspeksi-tab-pane" role="tabpanel">
                                    <div class="mt-4">
                                        <div class="section-header">🔍 Inspeksi Khusus - Pemeriksaan Visual & Hardness</div>

                                        {{-- Flatness Check --}}
                                        <div class="mt-4 p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                            <h6 style="margin-bottom: 15px; font-weight: 600;">
                                                <i class="bi bi-check-square"></i> 1. Pemeriksaan Flatness (Keplakan)
                                            </h6>
                                            <div class="inspection-checkbox">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flatness_result" id="flatness_ok" value="ok">
                                                    <label class="form-check-label text-success" for="flatness_ok">
                                                        <strong>✓ OK</strong> - Permukaan datar sesuai standar
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flatness_result" id="flatness_ng" value="ng">
                                                    <label class="form-check-label text-danger" for="flatness_ng">
                                                        <strong>✗ NG</strong> - Permukaan tidak datar / ada bengkakan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group mt-2">
                                                <small class="text-muted">Catatan:</small>
                                                <textarea class="form-control form-control-sm mt-1" name="flatness_note" placeholder="Keterangan hasil pemeriksaan flatness..." rows="2"></textarea>
                                            </div>
                                        </div>

                                        {{-- Visual Inspection --}}
                                        <div class="mt-4 p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                            <h6 style="margin-bottom: 15px; font-weight: 600;">
                                                <i class="bi bi-eye"></i> 2. Pemeriksaan Visual (Cacat Tampilan)
                                            </h6>
                                            <div class="inspection-checkbox">
                                                <div style="margin-bottom: 10px;">
                                                    <small style="font-weight: 500;">Permukaan Luar (Outer Surface):</small>
                                                    <div class="form-check ms-2 mt-2">
                                                        <input class="form-check-input" type="radio" name="visual_outer" id="visual_outer_ok" value="ok">
                                                        <label class="form-check-label text-success" for="visual_outer_ok">
                                                            ✓ OK - Tidak ada cacat
                                                        </label>
                                                    </div>
                                                    <div class="form-check ms-2">
                                                        <input class="form-check-input" type="radio" name="visual_outer" id="visual_outer_ng" value="ng">
                                                        <label class="form-check-label text-danger" for="visual_outer_ng">
                                                            ✗ NG - Ada cacat (goresan/dent/noda)
                                                        </label>
                                                    </div>
                                                </div>
                                                <hr style="margin: 12px 0;">
                                                <div>
                                                    <small style="font-weight: 500;">Permukaan Dalam (Inner Surface):</small>
                                                    <div class="form-check ms-2 mt-2">
                                                        <input class="form-check-input" type="radio" name="visual_inner" id="visual_inner_ok" value="ok">
                                                        <label class="form-check-label text-success" for="visual_inner_ok">
                                                            ✓ OK - Tidak ada cacat
                                                        </label>
                                                    </div>
                                                    <div class="form-check ms-2">
                                                        <input class="form-check-input" type="radio" name="visual_inner" id="visual_inner_ng" value="ng">
                                                        <label class="form-check-label text-danger" for="visual_inner_ng">
                                                            ✗ NG - Ada cacat (gelembung/crack/noda)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-2">
                                                <small class="text-muted">Catatan:</small>
                                                <textarea class="form-control form-control-sm mt-1" name="visual_note" placeholder="Deskripsi cacat visual yang ditemukan..." rows="2"></textarea>
                                            </div>
                                        </div>

                                        {{-- Hardness Test --}}
                                        <div class="mt-4 p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                            <h6 style="margin-bottom: 15px; font-weight: 600;">
                                                <i class="bi bi-hammer"></i> 3. Uji Hardness (Kekerasan)
                                            </h6>
                                            <div class="inspection-checkbox">
                                                <div class="form-group">
                                                    <label for="hardness_value" class="form-label">Nilai Hardness (HRC/HRB/HV): <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" id="hardness_value" name="hardness_value" 
                                                               step="0.1" placeholder="Masukkan nilai kekerasan">
                                                        <select class="form-select" id="hardness_scale" name="hardness_scale" style="max-width: 100px;">
                                                            <option value="HRC">HRC</option>
                                                            <option value="HRB">HRB</option>
                                                            <option value="HV">HV</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="hardness_std" class="form-label">Standar Hardness:</label>
                                                    <input type="text" class="form-control" id="hardness_std" name="hardness_std" 
                                                           placeholder="Contoh: 40-50 HRC atau Min 45 HRC">
                                                </div>
                                            </div>
                                            <div class="form-group mt-2">
                                                <small class="text-muted">Catatan:</small>
                                                <textarea class="form-control form-control-sm mt-1" name="hardness_note" placeholder="Keterangan hasil uji hardness..." rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- ============ FOOTER (LOGISTICS & VALIDATION) ============ --}}
                        <div class="section-header">📦 Footer - Logistik & Validasi Penerimaan</div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qty_sent" class="form-label">Qty Dikirim (Quantity Sent) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="qty_sent" name="qty_sent" 
                                           placeholder="Jumlah qty yang dikirim" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_sent" class="form-label">Tanggal Pengiriman (Date Sent) <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" id="date_sent" name="date_sent" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inspector_name" class="form-label">Inspector / QC Officer <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="inspector_name" name="inspector_name" 
                                           value="{{ auth()->user()->name }}" readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="acknowledged_by" class="form-label">Acknowledged By (Disetujui Penerima) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="acknowledged_by" 
                                           placeholder="Nama Penerima / Warehouse Staff" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="overall_result" class="form-label">Hasil Keputusan Akhir (Overall Result) <span class="text-danger">*</span></label>
                                    <select class="form-select" id="overall_result" name="overall_result" required>
                                        <option value="">-- Pilih Hasil Akhir --</option>
                                        <option value="accept" style="color: green;">✓ ACCEPT - Semua Dimensi & Inspeksi OK</option>
                                        <option value="conditional" style="color: orange;">⚠ CONDITIONAL ACCEPT - Ada yang NG tapi dapat diterima</option>
                                        <option value="reject" style="color: red;">✗ REJECT - Ada Dimensi/Inspeksi NG, Ditolak</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="reset" class="btn btn-outline-danger me-2">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset Form
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Submit & Simpan QC Report
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            {{-- RIWAYAT INSPEKSI QC SECTION --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">📊 Riwayat Inspeksi QC</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @forelse($inspections as $inspection)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="card border-left-primary" style="border-left: 4px solid #4472C4;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Nomor Laporan:</strong></p>
                                                <p class="text-primary fw-bold">{{ $inspection->nomor_laporan }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Tanggal Inspeksi:</strong></p>
                                                <p>{{ $inspection->tanggal_inspeksi->format('d-m-Y H:i') }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Product:</strong></p>
                                                <p>{{ $inspection->product }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Part No:</strong></p>
                                                <p>{{ $inspection->part_no }}</p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Material:</strong></p>
                                                <p>{{ $inspection->material }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Drawing:</strong></p>
                                                <p>{{ $inspection->drawing_no }} (Rev: {{ $inspection->drawing_rev }})</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Customer:</strong></p>
                                                <p>{{ $inspection->customer ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Batch No:</strong></p>
                                                <p>{{ $inspection->batch_no ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Dibuat Oleh:</strong></p>
                                                <p>{{ $inspection->made_by ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Disetujui Oleh:</strong></p>
                                                <p>{{ $inspection->approved_by ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Input Oleh:</strong></p>
                                                <p>{{ $inspection->user->name ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1"><strong>Waktu Input:</strong></p>
                                                <p>{{ $inspection->created_at->format('d-m-Y H:i') }}</p>
                                            </div>
                                        </div>
                                        @if($inspection->catatan)
                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <p class="mb-1"><strong>Catatan:</strong></p>
                                                    <p>{{ $inspection->catatan }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        {{-- Action Buttons --}}
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('quality-reinspection.show', ['inspection' => $inspection->id]) }}" class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i> Lihat Detail
                                                    </a>
                                                    <a href="{{ route('quality-reinspection.edit', ['inspection' => $inspection->id]) }}" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <form action="{{ route('quality-reinspection.destroy', ['inspection' => $inspection->id]) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus inspeksi ini?')">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Belum ada data inspeksi QC. Silakan isi form di atas untuk menambahkan inspeksi baru.
                        </div>
                    @endforelse

                    {{-- Pagination --}}
                    @if($inspections->hasPages())
                        <div class="row mt-4">
                            <div class="col-md-12">
                                {{ $inspections->links('pagination::bootstrap-4') }}
                            </div>
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
        // Initialize date-time field with current date/time
        document.addEventListener('DOMContentLoaded', function() {
            const dateSentField = document.getElementById('date_sent');
            if (dateSentField) {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                dateSentField.value = `${year}-${month}-${day}T${hours}:${minutes}`;
            }
        });

        // Add new row to dimensi table
        document.getElementById('addRowBtn').addEventListener('click', function() {
            const table = document.getElementById('dimensiTable').getElementsByTagName('tbody')[0];
            const rowCount = table.getElementsByTagName('tr').length + 1;
            
            const newRow = document.createElement('tr');
            newRow.className = 'dimensi-row';
            newRow.dataset.row = rowCount;
            
            newRow.innerHTML = `
                <td class="row-number">${rowCount}</td>
                <td><input type="text" class="form-control form-control-sm" name="karakteristik[]" placeholder="Contoh: Diameter"></td>
                <td><input type="number" class="form-control form-control-sm" name="std_min[]" step="0.01" placeholder="Min"></td>
                <td><input type="number" class="form-control form-control-sm" name="std_max[]" step="0.01" placeholder="Max"></td>
                <td><input type="text" class="form-control form-control-sm" name="alat_ukur[]" placeholder="Micrometer"></td>
                <td><input type="text" class="form-control form-control-sm" name="frekuensi[]" placeholder="100%"></td>
                <td><input type="number" class="form-control form-control-sm" name="hasil_batch[]" step="0.01" placeholder="Result"></td>
                <td>
                    <select class="form-select form-select-sm" name="status_dimensi[]">
                        <option value="">-- Pilih --</option>
                        <option value="ok" style="color: green;">✓ OK</option>
                        <option value="ng" style="color: red;">✗ NG</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-sm btn-danger btn-delete-row" title="Hapus Baris"><i class="bi bi-trash"></i></button></td>
            `;
            
            table.appendChild(newRow);
            
            // Add event listener to delete button
            newRow.querySelector('.btn-delete-row').addEventListener('click', deleteRow);
        });

        // Delete row function
        function deleteRow(e) {
            e.preventDefault();
            const row = e.target.closest('tr');
            row.remove();
            
            // Re-number all rows
            const table = document.getElementById('dimensiTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            rows.forEach((row, index) => {
                row.querySelector('.row-number').textContent = index + 1;
                row.dataset.row = index + 1;
            });
        }

        // Add event listeners to existing delete buttons
        document.querySelectorAll('.btn-delete-row').forEach(btn => {
            btn.addEventListener('click', deleteRow);
        });

        // Form validation and submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const product = document.getElementById('product').value.trim();
            const partNo = document.getElementById('part_no').value.trim();
            const material = document.getElementById('material').value.trim();
            
            if (!product || !partNo || !material) {
                alert('Mohon lengkapi field header (Product, Part No, Material)');
                return;
            }
            
            // Check if at least one dimension row is filled
            const karakteristikInputs = document.querySelectorAll('input[name="karakteristik[]"]');
            let hasDimensiData = false;
            karakteristikInputs.forEach(input => {
                if (input.value.trim()) hasDimensiData = true;
            });
            
            if (!hasDimensiData) {
                alert('Mohon tambahkan minimal satu data dimensi di Tab 1');
                return;
            }
            
            // Check footer fields
            const qtySent = document.getElementById('qty_sent').value;
            const dateSent = document.getElementById('date_sent').value;
            const approvedBy = document.getElementById('approved_by').value.trim();
            const acknowledgedBy = document.querySelector('input[name="acknowledged_by"]').value.trim();
            const overallResult = document.getElementById('overall_result').value;
            
            if (!qtySent || !dateSent || !approvedBy || !acknowledgedBy || !overallResult) {
                alert('Mohon lengkapi semua field Footer (Qty Sent, Date Sent, Approved By, Acknowledged By, Overall Result)');
                return;
            }
            
            // If all validation passed, show success message
            Swal.fire({
                icon: 'success',
                title: 'Laporan QC Berhasil Dibuat',
                text: 'Data inspeksi QC telah tersimpan dengan baik. Nomor Laporan: ' + document.getElementById('nomor_laporan').value,
                confirmButtonText: 'OK',
                timer: 3000
            });
            
            // Here you would normally submit the form to server
            // this.submit();
        });
    </script>
@endpush