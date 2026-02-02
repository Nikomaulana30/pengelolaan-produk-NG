@extends('layouts.app')

@section('title', 'Dashboard Overview - Return Reports')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>📊 Dashboard Overview</h3>
                <p class="text-subtitle text-muted">Ringkasan performa penanganan complaint & return</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('return-reports.index') }}">Return Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Overview</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Today's Summary -->
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📅 Today's Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="p-3 border rounded text-center">
                                    <h3 class="text-primary mb-1">{{ $data['today']['complaints_new'] }}</h3>
                                    <small class="text-muted">New Complaints</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded text-center">
                                    <h3 class="text-success mb-1">{{ $data['today']['complaints_resolved'] }}</h3>
                                    <small class="text-muted">Resolved Today</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded text-center">
                                    <h3 class="text-info mb-1">{{ $data['today']['reworks_completed'] }}</h3>
                                    <small class="text-muted">Reworks Completed</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded text-center">
                                    <h3 class="text-warning mb-1">{{ $data['today']['shipments_delivered'] }}</h3>
                                    <small class="text-muted">Delivered</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month Summary -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📈 This Month Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="text-muted mb-1">Total Complaints</p>
                                                <h4 class="mb-0">{{ $data['this_month']['total_complaints'] }}</h4>
                                            </div>
                                            <div class="stats-icon blue">
                                                <i class="bi bi-exclamation-circle"></i>
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            Completed: {{ $data['this_month']['completed_complaints'] }} 
                                            ({{ $data['this_month']['total_complaints'] > 0 ? round(($data['this_month']['completed_complaints'] / $data['this_month']['total_complaints']) * 100) : 0 }}%)
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="text-muted mb-1">Total Cost</p>
                                                <h4 class="mb-0">Rp {{ number_format($data['this_month']['total_cost']) }}</h4>
                                            </div>
                                            <div class="stats-icon red">
                                                <i class="bi bi-cash-stack"></i>
                                            </div>
                                        </div>
                                        <small class="text-muted">Rework costs this month</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="text-muted mb-1">Avg Resolution</p>
                                                <h4 class="mb-0">{{ $data['this_month']['avg_resolution_time'] }} days</h4>
                                            </div>
                                            <div class="stats-icon purple">
                                                <i class="bi bi-clock-history"></i>
                                            </div>
                                        </div>
                                        <small class="text-muted">Average time to complete</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="text-muted mb-1">Status Distribution</p>
                                                <small>
                                                    @foreach($data['status_distribution'] as $status => $count)
                                                        <span class="badge bg-{{ $status == 'completed' ? 'success' : ($status == 'processing' ? 'warning' : 'secondary') }} me-1">
                                                            {{ ucfirst($status) }}: {{ $count }}
                                                        </span>
                                                    @endforeach
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🎯 Performance Metrics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>On-Time Completion</span>
                                <strong>{{ $data['performance']['on_time_completion'] }}%</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ $data['performance']['on_time_completion'] }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>First Time Fix Rate</span>
                                <strong>{{ $data['performance']['first_time_fix_rate'] }}%</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width: {{ $data['performance']['first_time_fix_rate'] }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Rework Success Rate</span>
                                <strong>{{ $data['performance']['rework_success_rate'] }}%</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-primary" style="width: {{ $data['performance']['rework_success_rate'] }}%"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Cost per Complaint</span>
                            <strong>Rp {{ number_format($data['performance']['cost_per_complaint']) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">⚠️ Critical Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-{{ $data['critical_items']['overdue_complaints'] > 0 ? 'danger' : 'success' }} mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Overdue Complaints</strong>
                                    <p class="mb-0 small">Complaints > 7 days old</p>
                                </div>
                                <h3 class="mb-0">{{ $data['critical_items']['overdue_complaints'] }}</h3>
                            </div>
                        </div>
                        <div class="alert alert-{{ $data['critical_items']['high_cost_reworks'] > 0 ? 'warning' : 'info' }} mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>High Cost Reworks</strong>
                                    <p class="mb-0 small">Cost > Rp 1,000,000</p>
                                </div>
                                <h3 class="mb-0">{{ $data['critical_items']['high_cost_reworks'] }}</h3>
                            </div>
                        </div>
                        <div class="alert alert-{{ $data['critical_items']['critical_defects'] > 0 ? 'danger' : 'success' }} mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Critical Defects</strong>
                                    <p class="mb-0 small">This week</p>
                                </div>
                                <h3 class="mb-0">{{ $data['critical_items']['critical_defects'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
// Auto refresh every 5 minutes
setTimeout(function() {
    location.reload();
}, 300000);
</script>
@endpush
@endsection
