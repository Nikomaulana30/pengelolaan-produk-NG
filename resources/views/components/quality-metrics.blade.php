<!-- Quality Metrics Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up"></i> Quality Metrics Dashboard
                </h5>
            </div>
            <div class="card-body">
                <!-- KPI Cards -->
                <div class="row mb-4">
                    <!-- Total NG -->
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Total NG Items</h6>
                                <h2 class="text-primary mb-1">{{ $qualityMetrics['summary']['total_ng'] ?? 0 }}</h2>
                                <small class="text-muted">
                                    @php
                                        $trend = $qualityMetrics['trending']['ng_trend'] ?? 0;
                                    @endphp
                                    @if($trend > 0)
                                        <span class="text-danger">↑ {{ abs($trend) }}%</span>
                                    @elseif($trend < 0)
                                        <span class="text-success">↓ {{ abs($trend) }}%</span>
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif
                                    vs Last Month
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Total Retur -->
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Retur Items</h6>
                                <h2 class="text-warning mb-1">{{ $qualityMetrics['summary']['total_retur'] ?? 0 }}</h2>
                                <small class="text-muted">
                                    {{ $qualityMetrics['disposition']['retur_pct'] ?? 0 }}% of Total
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Total Scrap -->
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Scrap Items</h6>
                                <h2 class="text-danger mb-1">{{ $qualityMetrics['summary']['total_scrap'] ?? 0 }}</h2>
                                <small class="text-muted">
                                    {{ $qualityMetrics['disposition']['scrap_pct'] ?? 0 }}% of Total
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Total Rework -->
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Rework Items</h6>
                                <h2 class="text-info mb-1">{{ $qualityMetrics['summary']['total_rework'] ?? 0 }}</h2>
                                <small class="text-muted">
                                    {{ $qualityMetrics['disposition']['rework_pct'] ?? 0 }}% of Total
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disposition Breakdown Chart -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Disposition Breakdown</h6>
                        <canvas id="dispositionChart" height="80"></canvas>
                    </div>

                    <!-- Top Defects -->
                    <div class="col-md-6">
                        <h6 class="mb-3">Top 5 Defect Types</h6>
                        <div class="list-group">
                            @forelse($qualityMetrics['top_defects'] as $defect)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $defect['defect_type'] }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $defect['frequency'] }} occurrences</small>
                                    </div>
                                    <span class="badge bg-danger">{{ $defect['total_qty'] }} units</span>
                                </div>
                            @empty
                                <div class="alert alert-info mb-0">No defects recorded</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Vendors -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="mb-3">Top Vendors by Return Rate</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Vendor Name</th>
                                        <th class="text-center">Return Count</th>
                                        <th class="text-center">Total Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($qualityMetrics['top_vendors'] as $vendor)
                                        <tr>
                                            <td>{{ $vendor['vendor_name'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-warning">{{ $vendor['retur_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $vendor['total_qty'] }}</strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No return data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Monthly Trend -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="mb-3">6-Month Trend</h6>
                        <canvas id="monthlyTrendChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Disposition Pie Chart
    const dispositionCtx = document.getElementById('dispositionChart');
    if (dispositionCtx) {
        const dispositionData = {
            labels: ['Retur', 'Scrap', 'Rework'],
            datasets: [{
                data: [
                    {{ $qualityMetrics['disposition']['retur_pct'] ?? 0 }},
                    {{ $qualityMetrics['disposition']['scrap_pct'] ?? 0 }},
                    {{ $qualityMetrics['disposition']['rework_pct'] ?? 0 }}
                ],
                backgroundColor: ['#ffc107', '#dc3545', '#0dcaf0'],
                borderColor: ['#fff', '#fff', '#fff'],
                borderWidth: 2
            }]
        };

        new Chart(dispositionCtx, {
            type: 'doughnut',
            data: dispositionData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Monthly Trend Line Chart
    const trendCtx = document.getElementById('monthlyTrendChart');
    if (trendCtx) {
        const monthlyData = @json($qualityMetrics['monthly_trend']);
        
        const trendData = {
            labels: monthlyData.map(d => d.month),
            datasets: [
                {
                    label: 'Total NG',
                    data: monthlyData.map(d => d.total_ng),
                    borderColor: '#6f42c1',
                    backgroundColor: 'rgba(111, 66, 193, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Retur',
                    data: monthlyData.map(d => d.retur),
                    borderColor: '#ffc107',
                    backgroundColor: 'transparent',
                    tension: 0.4
                },
                {
                    label: 'Scrap',
                    data: monthlyData.map(d => d.scrap),
                    borderColor: '#dc3545',
                    backgroundColor: 'transparent',
                    tension: 0.4
                },
                {
                    label: 'Rework',
                    data: monthlyData.map(d => d.rework),
                    borderColor: '#0dcaf0',
                    backgroundColor: 'transparent',
                    tension: 0.4
                }
            ]
        };

        new Chart(trendCtx, {
            type: 'line',
            data: trendData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
@endpush
