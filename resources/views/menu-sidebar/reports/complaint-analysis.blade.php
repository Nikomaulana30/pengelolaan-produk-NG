@extends('layouts.app')

@section('title', 'Complaint Analysis - Return Reports')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>📋 Complaint Analysis</h3>
                <p class="text-subtitle text-muted">Analisis detail customer complaints</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('return-reports.index') }}">Return Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Complaint Analysis</li>
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

        <!-- Complaint by Customer -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">👥 Complaints by Customer</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Total Complaints</th>
                                        <th>Completed</th>
                                        <th>In Progress</th>
                                        <th>Completion Rate</th>
                                        <th>Avg Resolution Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $customerComplaints = App\Models\CustomerComplaint::selectRaw('
                                            nama_customer,
                                            COUNT(*) as total,
                                            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                                            SUM(CASE WHEN status = "processing" THEN 1 ELSE 0 END) as in_progress
                                        ')
                                        ->whereBetween('created_at', [now()->subDays($period), now()])
                                        ->groupBy('nama_customer')
                                        ->orderByDesc('total')
                                        ->get();
                                    @endphp

                                    @forelse($customerComplaints as $customer)
                                    <tr>
                                        <td><strong>{{ $customer->nama_customer }}</strong></td>
                                        <td>{{ $customer->total }}</td>
                                        <td><span class="badge bg-success">{{ $customer->completed }}</span></td>
                                        <td><span class="badge bg-warning">{{ $customer->in_progress }}</span></td>
                                        <td>
                                            {{ $customer->total > 0 ? round(($customer->completed / $customer->total) * 100) : 0 }}%
                                        </td>
                                        <td>
                                            @php
                                                $avgDays = App\Models\CustomerComplaint::where('nama_customer', $customer->nama_customer)
                                                    ->where('status', 'completed')
                                                    ->whereBetween('created_at', [now()->subDays($period), now()])
                                                    ->get()
                                                    ->avg(function($c) {
                                                        return $c->created_at->diffInDays($c->updated_at);
                                                    });
                                            @endphp
                                            {{ round($avgDays ?? 0, 1) }} days
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No data available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complaint by Product -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📦 Complaints by Product</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Total Complaints</th>
                                        <th>Status</th>
                                        <th>Total Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $productComplaints = App\Models\CustomerComplaint::selectRaw('
                                            produk,
                                            COUNT(*) as total,
                                            SUM(quantity_complaint) as total_qty,
                                            status
                                        ')
                                        ->whereBetween('created_at', [now()->subDays($period), now()])
                                        ->groupBy('produk', 'status')
                                        ->orderByDesc('total')
                                        ->get()
                                        ->groupBy('produk');
                                    @endphp

                                    @forelse($productComplaints as $produk => $complaints)
                                    <tr>
                                        <td><strong>{{ $produk }}</strong></td>
                                        <td>{{ $complaints->sum('total') }}</td>
                                        <td>
                                            @foreach($complaints as $c)
                                                <span class="badge bg-{{ $c->status == 'completed' ? 'success' : ($c->status == 'processing' ? 'warning' : 'secondary') }} me-1">
                                                    {{ ucfirst($c->status) }}: {{ $c->total }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>{{ number_format($complaints->sum('total_qty')) }} pcs</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No data available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Priority Distribution -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔥 Priority Distribution</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $priorities = App\Models\CustomerComplaint::selectRaw('
                                priority_level,
                                COUNT(*) as count
                            ')
                            ->whereBetween('created_at', [now()->subDays($period), now()])
                            ->groupBy('priority_level')
                            ->get();
                        @endphp

                        @forelse($priorities as $priority)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-{{ $priority->priority_level == 'high' ? 'danger' : ($priority->priority_level == 'medium' ? 'warning' : 'info') }}">
                                    {{ strtoupper($priority->priority_level) }}
                                </span>
                                <strong>{{ $priority->count }}</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-{{ $priority->priority_level == 'high' ? 'danger' : ($priority->priority_level == 'medium' ? 'warning' : 'info') }}" 
                                     style="width: {{ $priorities->sum('count') > 0 ? ($priority->count / $priorities->sum('count')) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">No data available</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📊 Status Overview</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $statuses = App\Models\CustomerComplaint::selectRaw('
                                status,
                                COUNT(*) as count
                            ')
                            ->whereBetween('created_at', [now()->subDays($period), now()])
                            ->groupBy('status')
                            ->get();
                        @endphp

                        @forelse($statuses as $status)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ strtoupper(str_replace('_', ' ', $status->status)) }}</span>
                                <strong>{{ $status->count }}</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-{{ $status->status == 'completed' ? 'success' : ($status->status == 'processing' ? 'warning' : 'secondary') }}" 
                                     style="width: {{ $statuses->sum('count') > 0 ? ($status->count / $statuses->sum('count')) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">No data available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
