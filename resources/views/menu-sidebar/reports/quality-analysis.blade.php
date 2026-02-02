@extends('layouts.app')

@section('title', 'Quality Analysis - Return Reports')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>🔍 Quality Analysis</h3>
                <p class="text-subtitle text-muted">Analisis quality inspection & rework performance</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('return-reports.index') }}">Return Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quality Analysis</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <!-- Period Filter -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="row align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Period (days)</label>
                                <select name="period" class="form-select">
                                    <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 days</option>
                                    <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 days</option>
                                    <option value="60" {{ $period == 60 ? 'selected' : '' }}>Last 60 days</option>
                                    <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 days</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-filter"></i> Apply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quality Metrics -->
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        @php
                            $totalInspections = App\Models\QualityReinspection::whereBetween('created_at', [now()->subDays($period), now()])->count();
                        @endphp
                        <h3 class="text-primary">{{ $totalInspections }}</h3>
                        <p class="text-muted mb-0">Total Inspections</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        @php
                            $totalReworks = App\Models\ProductionRework::whereBetween('created_at', [now()->subDays($period), now()])->count();
                        @endphp
                        <h3 class="text-info">{{ $totalReworks }}</h3>
                        <p class="text-muted mb-0">Total Reworks</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        @php
                            $completedReworks = App\Models\ProductionRework::whereBetween('created_at', [now()->subDays($period), now()])->where('status', 'sent_to_warehouse')->count();
                            $successRate = $totalReworks > 0 ? round(($completedReworks / $totalReworks) * 100, 1) : 0;
                        @endphp
                        <h3 class="text-success">{{ $successRate }}%</h3>
                        <p class="text-muted mb-0">Rework Success Rate</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        @php
                            $approvedFinal = App\Models\FinalQualityCheck::whereBetween('created_at', [now()->subDays($period), now()])->where('keputusan_final', 'approved_for_shipment')->count();
                        @endphp
                        <h3 class="text-warning">{{ $approvedFinal }}</h3>
                        <p class="text-muted mb-0">Approved for Shipment</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Defect Analysis -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔬 Defect Type Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Defect Type</th>
                                        <th>Count</th>
                                        <th>Total Quantity</th>
                                        <th>Avg Quantity</th>
                                        <th>Severity Distribution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $defects = App\Models\QualityReinspection::selectRaw('
                                            jenis_defect,
                                            COUNT(*) as count,
                                            SUM(quantity_defect) as total_qty,
                                            AVG(quantity_defect) as avg_qty
                                        ')
                                        ->whereBetween('created_at', [now()->subDays($period), now()])
                                        ->whereNotNull('jenis_defect')
                                        ->groupBy('jenis_defect')
                                        ->orderByDesc('count')
                                        ->get();
                                    @endphp

                                    @forelse($defects as $defect)
                                    <tr>
                                        <td><strong>{{ $defect->jenis_defect }}</strong></td>
                                        <td>{{ $defect->count }}</td>
                                        <td>{{ number_format($defect->total_qty) }} pcs</td>
                                        <td>{{ round($defect->avg_qty, 1) }} pcs</td>
                                        <td>
                                            @php
                                                $severities = App\Models\QualityReinspection::where('jenis_defect', $defect->jenis_defect)
                                                    ->whereBetween('created_at', [now()->subDays($period), now()])
                                                    ->selectRaw('severity_level, COUNT(*) as count')
                                                    ->groupBy('severity_level')
                                                    ->get();
                                            @endphp
                                            @foreach($severities as $severity)
                                                <span class="badge bg-{{ $severity->severity_level == 'critical' ? 'danger' : ($severity->severity_level == 'major' ? 'warning' : 'info') }} me-1">
                                                    {{ ucfirst($severity->severity_level) }}: {{ $severity->count }}
                                                </span>
                                            @endforeach
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No defects recorded</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rework Methods Analysis -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔧 Rework Methods</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $methods = App\Models\ProductionRework::selectRaw('
                                metode_rework,
                                COUNT(*) as count,
                                AVG(actual_biaya) as avg_cost,
                                AVG(actual_waktu_hari) as avg_time
                            ')
                            ->whereBetween('created_at', [now()->subDays($period), now()])
                            ->whereNotNull('metode_rework')
                            ->groupBy('metode_rework')
                            ->orderByDesc('count')
                            ->get();
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th>Count</th>
                                        <th>Avg Cost</th>
                                        <th>Avg Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($methods as $method)
                                    <tr>
                                        <td><strong>{{ $method->metode_rework }}</strong></td>
                                        <td>{{ $method->count }}</td>
                                        <td>Rp {{ number_format($method->avg_cost) }}</td>
                                        <td>{{ round($method->avg_time, 1) }} days</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">✅ Final QC Results</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $finalResults = App\Models\FinalQualityCheck::selectRaw('
                                keputusan_final,
                                COUNT(*) as count,
                                SUM(quantity_passed) as total_passed,
                                SUM(quantity_failed) as total_failed
                            ')
                            ->whereBetween('created_at', [now()->subDays($period), now()])
                            ->groupBy('keputusan_final')
                            ->get();
                        @endphp

                        @forelse($finalResults as $result)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-{{ $result->keputusan_final == 'approved_for_shipment' ? 'success' : ($result->keputusan_final == 'need_rework' ? 'warning' : 'danger') }}">
                                    {{ strtoupper(str_replace('_', ' ', $result->keputusan_final)) }}
                                </span>
                                <strong>{{ $result->count }} checks</strong>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Passed:</small>
                                    <p class="mb-0 text-success"><strong>{{ number_format($result->total_passed) }} pcs</strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Failed:</small>
                                    <p class="mb-0 text-danger"><strong>{{ number_format($result->total_failed) }} pcs</strong></p>
                                </div>
                            </div>
                            <hr>
                        </div>
                        @empty
                        <p class="text-muted text-center">No final QC data</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
