@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-graph-up-arrow"></i> Analytics Dashboard</h3>
                    <p class="text-subtitle text-muted">KPI Metrics & Performance Analytics</p>
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
        <!-- KPI Summary Cards -->
        <div class="row mb-4">
            <div class="col-12 col-md-2">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-primary">{{ $kpiMetrics->total_returns }}</h4>
                        <small class="text-muted">Total Returns</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-success">{{ $kpiMetrics->approved_returns }}</h4>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-warning">{{ $kpiMetrics->pending_returns }}</h4>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-danger">{{ $kpiMetrics->rejected_returns }}</h4>
                        <small class="text-muted">Rejected</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="card">
                    <div class="card-body text-center">
                        <h4>{{ $kpiMetrics->total_rcas }}</h4>
                        <small class="text-muted">RCA Analysis</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-info">{{ $kpiMetrics->total_vendors }}</h4>
                        <small class="text-muted">Vendors</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="row mb-4">
            <div class="col-12 col-md-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Approval Rate</h6>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" style="width: {{ $kpiMetrics->approval_rate }}%">
                                {{ $kpiMetrics->approval_rate }}%
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">{{ $kpiMetrics->approved_returns }} approved of {{ $kpiMetrics->total_returns }} returns</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">RCA Completion Rate</h6>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-info" style="width: {{ $kpiMetrics->rca_completion_rate }}%">
                                {{ $kpiMetrics->rca_completion_rate }}%
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">{{ $kpiMetrics->closed_rcas }} closed of {{ $kpiMetrics->total_rcas }} RCAs</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Return Trend (MoM)</h6>
                        <h3 class="mb-0">
                            @if ($kpiMetrics->return_trend > 0)
                                <span class="text-danger"><i class="bi bi-arrow-up"></i> {{ $kpiMetrics->return_trend }}%</span>
                            @elseif ($kpiMetrics->return_trend < 0)
                                <span class="text-success"><i class="bi bi-arrow-down"></i> {{ abs($kpiMetrics->return_trend) }}%</span>
                            @else
                                <span class="text-muted">0%</span>
                            @endif
                        </h3>
                        <small class="text-muted">This month vs last month</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Avg Qty per Return</h6>
                        <h3 class="mb-0">{{ $kpiMetrics->avg_qty_per_return }}</h3>
                        <small class="text-muted">Units (Total: {{ $kpiMetrics->total_qty_returned }})</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="row mb-4">
            <!-- Return Trend Chart -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📈 Return Trend (Last 12 Months)</h5>
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
                        <h5 class="card-title mb-0">📊 Return Status Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="returnStatusChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row mb-4">
            <!-- Vendor Performance Chart -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">⭐ Vendor Approval Rate (Top 8)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="vendorPerformanceChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            <!-- RCA Status Chart -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔍 RCA Status Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="rcaStatusChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Defect Distribution Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔴 Top Defects (Top 8)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="defectDistributionChart" height="60"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Vendors & Defects Tables -->
        <div class="row mb-4">
            <!-- Top Vendors -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🏆 Top Performing Vendors</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Vendor</th>
                                        <th class="text-center">Approval %</th>
                                        <th class="text-center">Returns</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topVendors as $vendor)
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
                                        <td colspan="3" class="text-center text-muted">No vendor data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Vendors -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">⚠️ Vendors Needing Attention</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Vendor</th>
                                        <th class="text-center">Approval %</th>
                                        <th class="text-center">Returns</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bottomVendors as $vendor)
                                    <tr>
                                        <td>
                                            <a href="{{ route('vendor-scorecard.show', $vendor['id']) }}">
                                                {{ $vendor['name'] }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $vendor['approval_rate'] }}%</span>
                                        </td>
                                        <td class="text-center">{{ $vendor['total_returns'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No vendor data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Defects Table -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🐛 Top 10 Defects</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Defect Code</th>
                                        <th>Defect Name</th>
                                        <th class="text-center">Count</th>
                                        <th class="text-center">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalDefects = collect($topDefects)->sum('count');
                                    @endphp
                                    @forelse ($topDefects as $defect)
                                    <tr>
                                        <td><strong>{{ $defect['code'] }}</strong></td>
                                        <td>{{ $defect['name'] }}</td>
                                        <td class="text-center">{{ $defect['count'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ round(($defect['count'] / $totalDefects * 100), 1) }}%</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No defect data</td>
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
        <div class="row">
            <!-- Recent Returns -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📋 Recent Returns</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No. Retur</th>
                                        <th>Vendor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentReturns as $retur)
                                    <tr>
                                        <td>
                                            <a href="{{ route('retur-barang.show', $retur) }}">
                                                {{ $retur->no_retur }}
                                            </a>
                                        </td>
                                        <td>{{ $retur->vendor?->nama_vendor ?? 'Vendor Tidak Ditemukan' }}</td>
                                        <td>
                                            @if ($retur->status_approval === 'approved')
                                                <span class="badge bg-success">✓</span>
                                            @elseif ($retur->status_approval === 'pending')
                                                <span class="badge bg-warning">⏳</span>
                                            @else
                                                <span class="badge bg-danger">✗</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No recent returns</td>
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
                        <h5 class="card-title mb-0">🔍 Recent RCA Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>RCA No.</th>
                                        <th>Defect</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentRCAs as $rca)
                                    <tr>
                                        <td>
                                            <a href="{{ route('rca-analysis.show', $rca) }}">
                                                {{ $rca->nomor_rca }}
                                            </a>
                                        </td>
                                        <td>{{ $rca->masterDefect->nama_defect ?? 'N/A' }}</td>
                                        <td>
                                            @if ($rca->status_rca === 'open')
                                                <span class="badge bg-danger">Open</span>
                                            @elseif ($rca->status_rca === 'in_progress')
                                                <span class="badge bg-warning">In Progress</span>
                                            @else
                                                <span class="badge bg-success">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No recent RCAs</td>
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
    const returnTrendCtx = document.getElementById('returnTrendChart').getContext('2d');
    new Chart(returnTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($returnTrendChart['labels']) !!},
            datasets: [{
                label: 'Returns',
                data: {!! json_encode($returnTrendChart['data']) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
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

    // Return Status Chart
    const returnStatusCtx = document.getElementById('returnStatusChart').getContext('2d');
    new Chart(returnStatusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($returnStatusChart['labels']) !!},
            datasets: [{
                data: {!! json_encode($returnStatusChart['data']) !!},
                backgroundColor: {!! json_encode($returnStatusChart['colors']) !!}
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

    // Vendor Performance Chart
    const vendorPerfCtx = document.getElementById('vendorPerformanceChart').getContext('2d');
    new Chart(vendorPerfCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($vendorPerformanceChart['labels']) !!},
            datasets: [{
                label: 'Approval Rate (%)',
                data: {!! json_encode($vendorPerformanceChart['data']) !!},
                backgroundColor: '#28a745'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { min: 0, max: 100 }
            }
        }
    });

    // RCA Status Chart
    const rcaStatusCtx = document.getElementById('rcaStatusChart').getContext('2d');
    new Chart(rcaStatusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($rcaStatusChart['labels']) !!},
            datasets: [{
                data: {!! json_encode($rcaStatusChart['data']) !!},
                backgroundColor: {!! json_encode($rcaStatusChart['colors']) !!}
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

    // Defect Distribution Chart
    const defectCtx = document.getElementById('defectDistributionChart').getContext('2d');
    new Chart(defectCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($defectDistributionChart['labels']) !!},
            datasets: [{
                label: 'Count',
                data: {!! json_encode($defectDistributionChart['data']) !!},
                backgroundColor: '#dc3545'
            }]
        },
        options: {
            indexAxis: 'x',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection
