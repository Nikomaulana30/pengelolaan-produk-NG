{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Dashboard NG Management')

{{-- Untuk menggunakan css --}}
@push('styles')
    <style>
        .alert-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }
        .alert-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .alert-card.critical { border-left-color: #dc3545; }
        .alert-card.warning { border-left-color: #ffc107; }
        .alert-card.info { border-left-color: #0dcaf0; }
        .alert-card.success { border-left-color: #198754; }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 12px;
        }
        
        .status-pending { color: #ffc107; }
        .status-approved { color: #198754; }
        .status-rejected { color: #dc3545; }
        
        .live-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            background-color: #dc3545;
            border-radius: 50%;
            animation: pulse 1s infinite;
            margin-right: 5px;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
@endpush

{{-- Isi content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3><span class="live-indicator"></span>Dashboard NG Management - Live</h3>
                    <p class="text-subtitle text-muted">Data real-time: {{ now()->format('H:i:s d-m-Y') }}</p>
                </div>
                <div class="col-12 col-md-4 order-md-2 order-first">
                    <button class="btn btn-primary float-end" id="refreshBtn">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <!-- Quick Stats -->
        <section class="section">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon red mb-2">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Approval Pending</h6>
                                    <h6 class="font-extrabold mb-0">12</h6>
                                    <small class="text-danger">Perlu Approval</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon orange mb-2">
                                        <i class="bi bi-hourglass-split"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Antrian QC</h6>
                                    <h6 class="font-extrabold mb-0">34</h6>
                                    <small class="text-warning">Menunggu Inspeksi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon purple mb-2">
                                        <i class="bi bi-boxes"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Gudang Penuh</h6>
                                    <h6 class="font-extrabold mb-0">87%</h6>
                                    <small class="text-danger">Kapasitas Kritis</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Selesai Hari Ini</h6>
                                    <h6 class="font-extrabold mb-0">156</h6>
                                    <small class="text-success">Unit Selesai</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Approval Notifications (Actionable) -->
        <section class="section">
            <div class="row">
                <div class="col-12 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-bell-fill text-danger me-2"></i>Notifikasi Approval Menunggu
                                </h5>
                                <span class="badge bg-danger">12 Item</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Approval 1 -->
                            <div class="card alert-card critical mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <span class="badge bg-danger">URGENT</span>
                                                Persetujuan Rework - PB-20251223-0001
                                            </h6>
                                            <p class="mb-2 text-muted small">
                                                <i class="bi bi-box me-1"></i>Produk: Bearing AS-2024 | Qty: 50 unit
                                            </p>
                                            <p class="mb-2 small">
                                                <strong>Alasan NG:</strong> Cacat permukaan pada 15% produk
                            <br>
                                                <strong>Diajukan:</strong> 23 Des 2025, 10:45 | <strong>Pengaju:</strong> Rudi Hartanto
                                            </p>
                                            <div class="progress mb-2" style="height: 6px;">
                                                <div class="progress-bar bg-danger" style="width: 100%"></div>
                                            </div>
                                            <small class="text-danger">⏰ Menunggu sejak 2 jam lalu</small>
                                        </div>
                                        <button class="btn btn-sm btn-success ms-2">
                                            <i class="bi bi-check-lg"></i> Proses
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Approval 2 -->
                            <div class="card alert-card warning mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <span class="badge bg-warning">HIGH</span>
                                                Persetujuan Scrap - SCR-20251223-0005
                                            </h6>
                                            <p class="mb-2 text-muted small">
                                                <i class="bi bi-trash me-1"></i>Produk: Silinder Pump | Qty: 30 unit
                                            </p>
                                            <p class="mb-2 small">
                                                <strong>Alasan:</strong> Tidak bisa diperbaiki (Rusak total)
                            <br>
                                                <strong>Diajukan:</strong> 23 Des 2025, 11:20 | <strong>Pengaju:</strong> Siti Nurhayati
                                            </p>
                                            <div class="progress mb-2" style="height: 6px;">
                                                <div class="progress-bar bg-warning" style="width: 60%"></div>
                                            </div>
                                            <small class="text-warning">⏰ Menunggu sejak 1 jam lalu</small>
                                        </div>
                                        <button class="btn btn-sm btn-success ms-2">
                                            <i class="bi bi-check-lg"></i> Proses
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Approval 3 -->
                            <div class="card alert-card info mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <span class="badge bg-info">NORMAL</span>
                                                Persetujuan Retur - RET-20251223-0008
                                            </h6>
                                            <p class="mb-2 text-muted small">
                                                <i class="bi bi-box-arrow-left me-1"></i>Produk: Valve Control | Qty: 20 unit
                                            </p>
                                            <p class="mb-2 small">
                                                <strong>Status:</strong> Menunggu approval dari Finance Manager
                            <br>
                                                <strong>Diajukan:</strong> 23 Des 2025, 09:15 | <strong>Pengaju:</strong> Budi Santoso
                                            </p>
                                            <div class="progress mb-2" style="height: 6px;">
                                                <div class="progress-bar bg-info" style="width: 40%"></div>
                                            </div>
                                            <small class="text-info">⏰ Menunggu sejak 3 jam lalu</small>
                                        </div>
                                        <button class="btn btn-sm btn-success ms-2">
                                            <i class="bi bi-check-lg"></i> Proses
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('quality.approval.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-right"></i> Lihat Semua Approval
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Summary -->
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-list-check me-2"></i>Ringkasan Status
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Menunggu Approval</span>
                                    <span class="badge bg-danger">12</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" style="width: 35%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Dalam Proses</span>
                                    <span class="badge bg-warning">8</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: 24%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Sudah Diapprove</span>
                                    <span class="badge bg-success">15</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: 44%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Ditolak</span>
                                    <span class="badge bg-secondary">2</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-secondary" style="width: 6%"></div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Perhatian!</strong> 87% kapasitas gudang penuh. Percepat proses scrap/retur.
                            </div>

                            <button class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-refresh"></i> Refresh Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- QC Queue & Warehouse Alert -->
        <section class="section">
            <div class="row">
                <!-- QC Inspection Queue -->
                <div class="col-12 col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-clipboard-check me-2 text-warning"></i>Antrian Inspeksi QC
                                </h5>
                                <span class="badge bg-warning">34 Item</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No Referensi</th>
                                            <th>Produk</th>
                                            <th>Qty</th>
                                            <th>Waktu Tunggu</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><small>PB-20251223-0045</small></td>
                                            <td><small>Bearing AS-2024</small></td>
                                            <td><small><strong>50</strong> unit</small></td>
                                            <td><small><span class="badge bg-danger">2.5 jam</span></small></td>
                                            <td>
                                                <button class="btn btn-xs btn-outline-primary">
                                                    <i class="bi bi-play-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><small>PB-20251223-0043</small></td>
                                            <td><small>Silinder Pump</small></td>
                                            <td><small><strong>75</strong> unit</small></td>
                                            <td><small><span class="badge bg-danger">2.2 jam</span></small></td>
                                            <td>
                                                <button class="btn btn-xs btn-outline-primary">
                                                    <i class="bi bi-play-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><small>PB-20251223-0041</small></td>
                                            <td><small>Valve Control</small></td>
                                            <td><small><strong>120</strong> unit</small></td>
                                            <td><small><span class="badge bg-warning">1.8 jam</span></small></td>
                                            <td>
                                                <button class="btn btn-xs btn-outline-primary">
                                                    <i class="bi bi-play-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><small>PB-20251223-0039</small></td>
                                            <td><small>Ring Piston</small></td>
                                            <td><small><strong>200</strong> unit</small></td>
                                            <td><small><span class="badge bg-warning">1.5 jam</span></small></td>
                                            <td>
                                                <button class="btn btn-xs btn-outline-primary">
                                                    <i class="bi bi-play-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-2">
                                <a href="{{ route('quality-reinspection.index') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-arrow-right"></i> Buka QC Inspection
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warehouse Alert -->
                <div class="col-12 col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Alert Gudang
                                </h5>
                                <span class="badge bg-danger">Kritis</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger" role="alert">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">🚨 Kapasitas Gudang Penuh!</h6>
                                        <p class="mb-0 small">Penggunaan: <strong>87%</strong> dari kapasitas maksimal</p>
                                    </div>
                                    <div class="text-end">
                                        <div style="font-size: 24px; font-weight: bold; color: #dc3545;">87%</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded text-center">
                                        <small class="text-muted d-block">Kapasitas Terpakai</small>
                                        <h6 class="mb-0">4,350 unit</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded text-center">
                                        <small class="text-muted d-block">Kapasitas Tersisa</small>
                                        <h6 class="mb-0 text-danger">650 unit</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="progress mb-3" style="height: 20px;">
                                <div class="progress-bar bg-danger" style="width: 87%">87%</div>
                            </div>

                            <div class="small">
                                <p class="mb-2"><i class="bi bi-info-circle text-info me-2"></i>Rekomendasi Tindakan:</p>
                                <ul class="ps-3">
                                    <li>Percepat proses pemindahan barang NG ke Storage</li>
                                    <li>Akselerasi scrap untuk unit yang tidak bisa diperbaiki</li>
                                    <li>Prioritaskan retur ke supplier</li>
                                </ul>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('penyimpanan-ng.index') }}" class="btn btn-sm btn-danger w-100">
                                    <i class="bi bi-boxes"></i> Kelola Penyimpanan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

{{-- Untuk menggunakan js --}}
@push('scripts')
    <script>
        // Auto-refresh dashboard setiap 30 detik
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                location.reload();
            });
        }

        // Auto-refresh setiap 30 detik untuk live data
        setInterval(() => {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const newPage = new DOMParser().parseFromString(html, 'text/html');
                    // Update spesifik bagian yang perlu refresh
                    const newStats = newPage.querySelector('.page-content');
                    if (newStats) {
                        document.querySelector('.page-content').innerHTML = newStats.innerHTML;
                    }
                })
                .catch(error => console.log('Auto-refresh error:', error));
        }, 30000); // 30 detik

        // Tooltip initialization
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Highlight card yang diklik
        document.querySelectorAll('.alert-card').forEach(card => {
            card.addEventListener('click', function() {
                this.style.backgroundColor = '#f8f9fa';
                setTimeout(() => {
                    this.style.backgroundColor = '';
                }, 300);
            });
        });
    </script>
@endpush