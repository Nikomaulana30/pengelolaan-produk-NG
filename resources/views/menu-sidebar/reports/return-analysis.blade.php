@extends('layouts.app')

@section('title', 'Return Analysis Report')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-graph-up-arrow"></i> Return Analysis Report</h3>
                    <p class="text-subtitle text-muted">Analisis performa return barang dan RCA</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('reports.export') }}" class="btn btn-primary float-end">
                        <i class="bi bi-download"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">📊 Return Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12 col-md-3">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <div>
                                    <h5 class="mb-0 text-primary">{{ $kpiMetrics->total_returns ?? 0 }}</h5>
                                    <small class="text-muted">Total Return</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <div>
                                    <h5 class="mb-0 text-success">{{ $kpiMetrics->approved_returns ?? 0 }}</h5>
                                    <small class="text-muted">Approved</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <div>
                                    <h5 class="mb-0 text-warning">{{ $kpiMetrics->pending_returns ?? 0 }}</h5>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <div>
                                    <h5 class="mb-0 text-danger">{{ $kpiMetrics->rejected_returns ?? 0 }}</h5>
                                    <small class="text-muted">Rejected</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Return Trend Chart -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📈 Return Trend (12 Bulan)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="returnTrendChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Return Status Breakdown -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📊 Status Return</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="returnStatusChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tables Row -->
        <div class="row">
            <!-- Top Vendors -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🏆 Top Vendor</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Vendor</th>
                                        <th class="text-center">Approval %</th>
                                        <th class="text-center">Return</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topVendors ?? [] as $vendor)
                                    <tr>
                                        <td>
                                            <a href="{{ route('vendor-scorecard.show', $vendor['id']) }}">
                                                {{ $vendor['name'] }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $vendor['approval_rate'] }}%</span>
                                        </td>
                                        <td class="text-center">{{ $vendor['total_returns'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Defect Type -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🐛 Top Defect</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Defect</th>
                                        <th class="text-center">Count</th>
                                        <th class="text-center">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalDefects = collect($topDefects ?? [])->sum('count');
                                    @endphp
                                    @forelse ($topDefects ?? [] as $defect)
                                    <tr>
                                        <td>{{ $defect['name'] }}</td>
                                        <td class="text-center">{{ $defect['count'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $totalDefects > 0 ? round(($defect['count'] / $totalDefects * 100), 1) : 0 }}%</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row mt-4">
            <!-- Recent Returns -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📋 Return Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Retur</th>
                                        <th>Vendor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentReturns ?? [] as $retur)
                                    <tr>
                                        <td>
                                            <a href="{{ route('retur-barang.show', $retur->id) }}">
                                                {{ $retur->no_retur }}
                                            </a>
                                        </td>
                                        <td>{{ $retur->vendor?->nama_vendor ?? 'Vendor Tidak Ditemukan' }}</td>
                                        <td>
                                            @if ($retur->status_approval === 'approved')
                                                <span class="badge bg-success">✓ Approved</span>
                                            @elseif ($retur->status_approval === 'pending')
                                                <span class="badge bg-warning">⏳ Pending</span>
                                            @else
                                                <span class="badge bg-danger">✗ Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent RCAs -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔍 RCA Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>RCA No.</th>
                                        <th>Defect</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentRCAs ?? [] as $rca)
                                    <tr>
                                        <td>
                                            <a href="{{ route('rca-analysis.show', $rca->id) }}">
                                                {{ $rca->nomor_rca }}
                                            </a>
                                        </td>
                                        <td>{{ $rca->masterDefect->nama_defect ?? 'N/A' }}</td>
                                        <td>
                                            @if ($rca->status_rca === 'open')
                                                <span class="badge bg-danger">Open</span>
                                            @elseif ($rca->status_rca === 'in_progress')
                                                <span class="badge bg-warning">Progress</span>
                                            @else
                                                <span class="badge bg-success">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
    // Return Trend Chart
    if (document.getElementById('returnTrendChart')) {
        const returnTrendCtx = document.getElementById('returnTrendChart').getContext('2d');
        new Chart(returnTrendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($returnTrendChart['labels'] ?? []) !!},
                datasets: [{
                    label: 'Returns',
                    data: {!! json_encode($returnTrendChart['data'] ?? []) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: true, position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Return Status Chart
    if (document.getElementById('returnStatusChart')) {
        const returnStatusCtx = document.getElementById('returnStatusChart').getContext('2d');
        new Chart(returnStatusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($returnStatusChart['labels'] ?? []) !!},
                datasets: [{
                    data: {!! json_encode($returnStatusChart['data'] ?? []) !!},
                    backgroundColor: {!! json_encode($returnStatusChart['colors'] ?? ['#0d6efd', '#198754', '#dc3545']) !!}
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: true, position: 'bottom' }
                }
            }
        });
    }
</script>
@endsection
