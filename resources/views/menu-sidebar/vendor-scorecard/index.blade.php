@extends('layouts.app')

@section('title', 'Vendor Scorecard')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-graph-up"></i> Vendor Scorecard</h3>
                    <p class="text-subtitle text-muted">Evaluasi kinerja vendor berdasarkan return dan RCA analysis</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <!-- Summary Statistics -->
        <div class="row mb-4">
            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Vendors</h6>
                                <h3 class="mb-0">{{ $totalVendors }}</h3>
                            </div>
                            <div class="text-primary" style="font-size: 32px;">
                                <i class="bi bi-building"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Avg Return Rate</h6>
                                <h3 class="mb-0">{{ $avgReturnRate }}</h3>
                            </div>
                            <div class="text-warning" style="font-size: 32px;">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Performance Distribution</h6>
                        <div class="row text-center">
                            <div class="col-6 col-md-3 mb-3">
                                <h4 class="text-success mb-1">{{ $performanceDistribution['excellent'] }}</h4>
                                <small class="text-muted">Excellent</small>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <h4 class="text-info mb-1">{{ $performanceDistribution['good'] }}</h4>
                                <small class="text-muted">Good</small>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <h4 class="text-warning mb-1">{{ $performanceDistribution['fair'] }}</h4>
                                <small class="text-muted">Fair</small>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <h4 class="text-danger mb-1">{{ $performanceDistribution['poor'] }}</h4>
                                <small class="text-muted">Poor</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Defects -->
        @if($topDefects->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔴 Top Defects Across All Vendors</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Defect Code</th>
                                        <th>Defect Name</th>
                                        <th class="text-end">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topDefects as $defect)
                                    <tr>
                                        <td><strong>{{ $defect['kode_defect'] }}</strong></td>
                                        <td>{{ $defect['nama_defect'] }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-danger">{{ $defect['count'] }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Vendor Scorecard Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📊 Vendor Performance Scorecard</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Vendor Name</th>
                                        <th class="text-center">Total Returns</th>
                                        <th class="text-center">Approval Rate</th>
                                        <th class="text-center">RCA Count</th>
                                        <th class="text-center">Performance Score</th>
                                        <th class="text-center">Rating</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vendors as $vendor)
                                    <tr>
                                        <td>
                                            <strong>{{ $vendor->nama_vendor }}</strong><br>
                                            <small class="text-muted">{{ $vendor->phone ?? 'N/A' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $vendor->total_returns }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 20px;">
                                                @php
                                                    $rate = $vendor->approval_rate;
                                                    $color = $rate >= 80 ? 'success' : ($rate >= 60 ? 'warning' : 'danger');
                                                @endphp
                                                <div class="progress-bar bg-{{ $color }}" role="progressbar" 
                                                     style="width: {{ $rate }}%" aria-valuenow="{{ $rate }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    {{ round($rate, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $vendor->rca_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <h5 class="mb-0">{{ $vendor->performance_score }}/100</h5>
                                            <small class="text-muted">
                                                @if($vendor->performance_score >= 80)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @elseif($vendor->performance_score >= 60)
                                                    <i class="bi bi-star-half text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-muted"></i>
                                                @endif
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            @if($vendor->performance_rating === 'Excellent')
                                                <span class="badge bg-success">✓ Excellent</span>
                                            @elseif($vendor->performance_rating === 'Good')
                                                <span class="badge bg-info">✓ Good</span>
                                            @elseif($vendor->performance_rating === 'Fair')
                                                <span class="badge bg-warning">⚠ Fair</span>
                                            @else
                                                <span class="badge bg-danger">✗ Poor</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('vendor-scorecard.show', $vendor->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Belum ada data vendor
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $vendors->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Performance distribution chart
    const ctx = document.createElement('canvas');
    if (document.querySelector('[data-chart="performance-distribution"]')) {
        const perfChart = new Chart(document.querySelector('[data-chart="performance-distribution"]'), {
            type: 'doughnut',
            data: {
                labels: ['Excellent', 'Good', 'Fair', 'Poor'],
                datasets: [{
                    data: [
                        {{ $performanceDistribution['excellent'] }},
                        {{ $performanceDistribution['good'] }},
                        {{ $performanceDistribution['fair'] }},
                        {{ $performanceDistribution['poor'] }}
                    ],
                    backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
</script>
@endpush
