@extends('layouts.app')

@section('title', 'Return Reports Dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Return Reports Dashboard</h3>
                <p class="text-subtitle text-muted">Analytics and insights for customer return process</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Return Reports</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <!-- Date Range Filter -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">📊 Analytics Dashboard</h4>
                            <form method="GET" class="d-flex align-items-center gap-3">
                                <div>
                                    <label class="form-label mb-0 me-2">Date Range:</label>
                                </div>
                                <div>
                                    <input type="date" name="start_date" class="form-control form-control-sm" 
                                           value="{{ $startDate }}" style="width: 140px;">
                                </div>
                                <span class="text-muted">to</span>
                                <div>
                                    <input type="date" name="end_date" class="form-control form-control-sm" 
                                           value="{{ $endDate }}" style="width: 140px;">
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
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
                                <h6 class="text-muted font-semibold">Total Complaints</h6>
                                <h6 class="font-extrabold mb-0">{{ $dashboardData['total_complaints'] ?? 0 }}</h6>
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
                                <div class="stats-icon blue mb-2">
                                    <i class="bi bi-gear"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Reworks</h6>
                                <h6 class="font-extrabold mb-0">{{ $dashboardData['total_reworks'] ?? 0 }}</h6>
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
                                <h6 class="text-muted font-semibold">Completed</h6>
                                <h6 class="font-extrabold mb-0">{{ $dashboardData['completed_complaints'] ?? 0 }}</h6>
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
                                    <i class="bi bi-truck"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Delivered</h6>
                                <h6 class="font-extrabold mb-0">{{ $dashboardData['delivered_shipments'] ?? 0 }}</h6>
                                <small class="text-muted">{{ number_format($dashboardData['total_shipped_quantity'] ?? 0) }} pcs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Return Trends</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="returnTrendsChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Status Distribution</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivered Shipments Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">📦 Delivered Shipments (Barang Terkirim ke Customer)</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Shipment</th>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Ekspedisi</th>
                                        <th>Delivered At</th>
                                        <th>Rating</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tableData['delivered_shipments'] ?? [] as $shipment)
                                    <tr>
                                        <td><strong>{{ $shipment->nomor_pengiriman }}</strong></td>
                                        <td>{{ $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</td>
                                        <td>{{ $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</td>
                                        <td><span class="badge bg-success">{{ number_format($shipment->quantity_shipped) }} pcs</span></td>
                                        <td><span class="badge bg-info">{{ $shipment->ekspedisi }}</span></td>
                                        <td>
                                            @if($shipment->delivered_at)
                                                {{ $shipment->delivered_at->format('d M Y H:i') }}
                                            @else
                                                <span class="badge bg-warning">{{ $shipment->tanggal_pengiriman->format('d M Y H:i') }}</span>
                                                <br><small class="text-muted">Shipped</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($shipment->rating_customer)
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $shipment->rating_customer)
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @else
                                                        <i class="bi bi-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('return-shipment.show', $shipment) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('customer-complaint.show', $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="View Complaint">
                                                <i class="bi bi-file-text"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox display-4 mb-2"></i>
                                            <p>Belum ada shipment yang delivered di periode ini</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Complaints Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Recent Customer Complaints</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Complaint ID</th>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tableData['recent_complaints'] ?? [] as $complaint)
                                    <tr>
                                        <td><strong>{{ $complaint->nomor_complaint }}</strong></td>
                                        <td>{{ $complaint->nama_customer }}</td>
                                        <td>{{ $complaint->produk }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $complaint->status === 'completed' ? 'success' : 
                                                ($complaint->status === 'processing' ? 'warning' : 'secondary') 
                                            }}">
                                                {{ strtoupper($complaint->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $complaint->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('customer-complaint.show', $complaint) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="bi bi-inbox"></i> No complaints for the selected period
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Return Trends Chart
    const returnCtx = document.getElementById('returnTrendsChart').getContext('2d');
    new Chart(returnCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
            datasets: [{
                label: 'Customer Returns',
                data: {!! json_encode($chartData['returns'] ?? [10, 15, 12, 18, 9, 14]) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartData['status_labels'] ?? ['Completed', 'In Progress', 'Pending']) !!},
            datasets: [{
                data: {!! json_encode($chartData['status_data'] ?? [45, 30, 25]) !!},
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)', 
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderWidth: 2
            }]
        },
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
});
</script>
@endpush