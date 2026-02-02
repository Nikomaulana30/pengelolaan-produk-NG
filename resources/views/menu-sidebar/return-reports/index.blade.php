@extends('layouts.app')

@section('title', 'Return Reports Dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Return Reports Dashboard</h3>
                <p class="text-subtitle text-muted">Analytics dan laporan lengkap untuk admin dan staff export/import</p>
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

    <!-- Workflow Progress Indicator -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <div class="progress-steps">
                        <div class="step completed">
                            <div class="step-circle">1</div>
                            <span>Customer Complaint</span>
                        </div>
                        <div class="step completed">
                            <div class="step-circle">2</div>
                            <span>Dokumen Retur</span>
                        </div>
                        <div class="step completed">
                            <div class="step-circle">3</div>
                            <span>Warehouse Verification</span>
                        </div>
                        <div class="step completed">
                            <div class="step-circle">4</div>
                            <span>Quality Reinspection</span>
                        </div>
                        <div class="step completed">
                            <div class="step-circle">5</div>
                            <span>Production Rework</span>
                        </div>
                        <div class="step completed">
                            <div class="step-circle">6</div>
                            <span>Final Quality Check</span>
                        </div>
                        <div class="step completed">
                            <div class="step-circle">7</div>
                            <span>Return Shipment</span>
                        </div>
                        <div class="step active">
                            <div class="step-circle">8</div>
                            <span>Reports</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Report Filters</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="startDate" value="{{ date('Y-m-01') }}">
                                <input type="date" class="form-control" id="endDate" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Customer</label>
                            <select class="form-select" id="customerFilter">
                                <option value="">All Customers</option>
                                <option value="toyota">Toyota</option>
                                <option value="honda">Honda</option>
                                <option value="suzuki">Suzuki</option>
                                <option value="daihatsu">Daihatsu</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Product</label>
                            <select class="form-select" id="productFilter">
                                <option value="">All Products</option>
                                <option value="brake_pad">Brake Pad</option>
                                <option value="disc_brake">Disc Brake</option>
                                <option value="suspension">Suspension</option>
                                <option value="engine_parts">Engine Parts</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="completed">Completed</option>
                                <option value="in_progress">In Progress</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" onclick="updateReports()">
                                    <i class="bi bi-funnel"></i> Apply Filters
                                </button>
                                <button class="btn btn-outline-secondary" onclick="resetFilters()">
                                    <i class="bi bi-arrow-clockwise"></i> Reset
                                </button>
                                <button class="btn btn-success" onclick="exportReport()">
                                    <i class="bi bi-download"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="iconly-boldActivity"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Returns</h6>
                            <h6 class="font-extrabold mb-0" id="totalReturns">{{ $totalReturns ?? 0 }}</h6>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +12% from last month
                            </small>
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
                                <i class="iconly-boldTicket"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Completed</h6>
                            <h6 class="font-extrabold mb-0" id="completedReturns">{{ $completedReturns ?? 0 }}</h6>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +8% from last month
                            </small>
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
                                <i class="iconly-boldTimeCircle"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Avg. Cycle Time</h6>
                            <h6 class="font-extrabold mb-0" id="avgCycleTime">{{ $avgCycleTime ?? '14.2' }} days</h6>
                            <small class="text-danger">
                                <i class="bi bi-arrow-down"></i> -2.3 days improved
                            </small>
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
                            <div class="stats-icon red mb-2">
                                <i class="iconly-boldDanger"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Return Rate</h6>
                            <h6 class="font-extrabold mb-0" id="returnRate">{{ $returnRate ?? '2.8' }}%</h6>
                            <small class="text-warning">
                                <i class="bi bi-arrow-up"></i> +0.3% from last month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Return Trend Analysis</h5>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="chartPeriod" id="daily" value="daily" checked>
                        <label class="btn btn-outline-primary btn-sm" for="daily">Daily</label>
                        <input type="radio" class="btn-check" name="chartPeriod" id="weekly" value="weekly">
                        <label class="btn btn-outline-primary btn-sm" for="weekly">Weekly</label>
                        <input type="radio" class="btn-check" name="chartPeriod" id="monthly" value="monthly">
                        <label class="btn btn-outline-primary btn-sm" for="monthly">Monthly</label>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="returnTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Return Reasons Breakdown</h5>
                </div>
                <div class="card-body">
                    <canvas id="returnReasonsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Customer Return Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="customerDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Process Performance Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Process Performance Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="mb-3">
                                    <canvas id="customerComplaintGauge" width="150" height="150"></canvas>
                                </div>
                                <h6>Customer Complaint</h6>
                                <p class="text-muted">Avg: 2.3 days</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="mb-3">
                                    <canvas id="warehouseGauge" width="150" height="150"></canvas>
                                </div>
                                <h6>Warehouse Process</h6>
                                <p class="text-muted">Avg: 1.8 days</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="mb-3">
                                    <canvas id="reworkGauge" width="150" height="150"></canvas>
                                </div>
                                <h6>Rework Process</h6>
                                <p class="text-muted">Avg: 7.2 days</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="mb-3">
                                    <canvas id="shipmentGauge" width="150" height="150"></canvas>
                                </div>
                                <h6>Shipment Process</h6>
                                <p class="text-muted">Avg: 2.9 days</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Tables Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Top Customers by Return Volume</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Returns</th>
                                    <th>Rate</th>
                                    <th>Trend</th>
                                </tr>
                            </thead>
                            <tbody id="topCustomersTable">
                                <tr>
                                    <td>Toyota Indonesia</td>
                                    <td>45</td>
                                    <td>3.2%</td>
                                    <td><span class="text-success"><i class="bi bi-arrow-down"></i> -0.5%</span></td>
                                </tr>
                                <tr>
                                    <td>Honda Motor</td>
                                    <td>32</td>
                                    <td>2.8%</td>
                                    <td><span class="text-success"><i class="bi bi-arrow-down"></i> -0.3%</span></td>
                                </tr>
                                <tr>
                                    <td>Suzuki Motors</td>
                                    <td>28</td>
                                    <td>4.1%</td>
                                    <td><span class="text-danger"><i class="bi bi-arrow-up"></i> +0.7%</span></td>
                                </tr>
                                <tr>
                                    <td>Daihatsu</td>
                                    <td>19</td>
                                    <td>2.1%</td>
                                    <td><span class="text-success"><i class="bi bi-arrow-down"></i> -0.2%</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Top Products by Return Frequency</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Returns</th>
                                    <th>Rate</th>
                                    <th>Main Cause</th>
                                </tr>
                            </thead>
                            <tbody id="topProductsTable">
                                <tr>
                                    <td>Brake Pad Premium</td>
                                    <td>35</td>
                                    <td>4.2%</td>
                                    <td><small class="text-muted">Quality Issue</small></td>
                                </tr>
                                <tr>
                                    <td>Disc Brake Rotor</td>
                                    <td>28</td>
                                    <td>3.1%</td>
                                    <td><small class="text-muted">Dimension</small></td>
                                </tr>
                                <tr>
                                    <td>Suspension Spring</td>
                                    <td>22</td>
                                    <td>2.8%</td>
                                    <td><small class="text-muted">Material</small></td>
                                </tr>
                                <tr>
                                    <td>Engine Mount</td>
                                    <td>18</td>
                                    <td>3.5%</td>
                                    <td><small class="text-muted">Packaging</small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Items Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Action Items & Recommendations</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-danger">Critical Issues</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                        <div>
                                            <strong>Suzuki return rate increasing</strong>
                                            <br><small class="text-muted">Up 0.7% this month - requires immediate attention</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock-fill text-warning me-2"></i>
                                        <div>
                                            <strong>Rework process taking too long</strong>
                                            <br><small class="text-muted">7.2 days average vs 5 days target</small>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">Positive Trends</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-trend-up text-success me-2"></i>
                                        <div>
                                            <strong>Overall cycle time improved</strong>
                                            <br><small class="text-muted">14.2 days vs 16.5 days last month</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <div>
                                            <strong>Honda & Toyota showing improvement</strong>
                                            <br><small class="text-muted">Return rates decreased consistently</small>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Progress Steps Styling - Same as previous views */
.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    margin: 20px 0;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 25px;
    right: 25px;
    height: 2px;
    background-color: #dee2e6;
    z-index: 1;
}

.step {
    text-align: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #dee2e6;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-weight: bold;
    font-size: 14px;
    transition: all 0.3s ease;
}

.step span {
    font-size: 12px;
    color: #6c757d;
    max-width: 80px;
    line-height: 1.2;
    display: block;
}

.step.completed .step-circle {
    background-color: #198754;
    color: white;
}

.step.completed span {
    color: #198754;
    font-weight: 600;
}

.step.active .step-circle {
    background-color: #0d6efd;
    color: white;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
}

.step.active span {
    color: #0d6efd;
    font-weight: 600;
}

/* Stats Icons */
.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stats-icon.blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stats-icon.green {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stats-icon.orange {
    background: linear-gradient(135deg, #fd746c 0%, #ff9068 100%);
}

.stats-icon.red {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

/* Chart containers */
.chart-container {
    position: relative;
    height: 300px;
}

/* Gauge styling */
.gauge-container {
    position: relative;
    display: inline-block;
}

/* Button group for chart periods */
.btn-check:checked + .btn-outline-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

/* Tables */
.table-sm td, .table-sm th {
    padding: 0.5rem;
    font-size: 0.875rem;
}

/* Action items styling */
.list-unstyled li {
    padding: 0.5rem;
    border-radius: 0.375rem;
    background-color: #f8f9fa;
    margin-bottom: 0.5rem;
}

/* Trend indicators */
.text-success i, .text-danger i, .text-warning i {
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .progress-steps {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .progress-steps::before {
        display: none;
    }
    
    .step {
        width: 100%;
        text-align: left;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .step-circle {
        margin-right: 15px;
        margin-bottom: 0;
        flex-shrink: 0;
    }
    
    .step span {
        font-size: 14px;
        max-width: none;
    }
    
    .chart-container {
        height: 250px;
    }
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let charts = {};

$(document).ready(function() {
    initializeCharts();
    
    // Chart period change handler
    $('input[name="chartPeriod"]').on('change', function() {
        updateReturnTrendChart($(this).val());
    });
});

function initializeCharts() {
    // Return Trend Chart
    const trendCtx = document.getElementById('returnTrendChart').getContext('2d');
    charts.returnTrend = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Returns',
                data: [12, 19, 15, 22, 18, 25, 20, 28, 22, 30, 26, 35],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Completed',
                data: [10, 16, 13, 20, 16, 23, 18, 25, 20, 27, 24, 32],
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Return Reasons Chart
    const reasonsCtx = document.getElementById('returnReasonsChart').getContext('2d');
    charts.returnReasons = new Chart(reasonsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Quality Issues', 'Dimensional Problems', 'Material Defects', 'Packaging Issues', 'Others'],
            datasets: [{
                data: [35, 28, 18, 12, 7],
                backgroundColor: [
                    '#dc3545',
                    '#fd7e14',
                    '#ffc107',
                    '#20c997',
                    '#6f42c1'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Customer Distribution Chart
    const customerCtx = document.getElementById('customerDistributionChart').getContext('2d');
    charts.customerDistribution = new Chart(customerCtx, {
        type: 'bar',
        data: {
            labels: ['Toyota', 'Honda', 'Suzuki', 'Daihatsu', 'Others'],
            datasets: [{
                label: 'Return Count',
                data: [45, 32, 28, 19, 15],
                backgroundColor: [
                    '#0d6efd',
                    '#198754',
                    '#dc3545',
                    '#fd7e14',
                    '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Initialize gauge charts
    initializeGauges();
}

function initializeGauges() {
    // Customer Complaint Gauge
    const customerGaugeCtx = document.getElementById('customerComplaintGauge').getContext('2d');
    charts.customerGauge = new Chart(customerGaugeCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [77, 23], // 77% performance
                backgroundColor: ['#28a745', '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
            circumference: 180,
            rotation: 270,
            cutout: '80%',
            responsive: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });

    // Warehouse Gauge
    const warehouseGaugeCtx = document.getElementById('warehouseGauge').getContext('2d');
    charts.warehouseGauge = new Chart(warehouseGaugeCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [85, 15],
                backgroundColor: ['#17a2b8', '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
            circumference: 180,
            rotation: 270,
            cutout: '80%',
            responsive: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });

    // Rework Gauge
    const reworkGaugeCtx = document.getElementById('reworkGauge').getContext('2d');
    charts.reworkGauge = new Chart(reworkGaugeCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [65, 35],
                backgroundColor: ['#ffc107', '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
            circumference: 180,
            rotation: 270,
            cutout: '80%',
            responsive: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });

    // Shipment Gauge
    const shipmentGaugeCtx = document.getElementById('shipmentGauge').getContext('2d');
    charts.shipmentGauge = new Chart(shipmentGaugeCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [82, 18],
                backgroundColor: ['#6f42c1', '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
            circumference: 180,
            rotation: 270,
            cutout: '80%',
            responsive: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });
}

function updateReturnTrendChart(period) {
    let labels, data1, data2;
    
    switch(period) {
        case 'daily':
            labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            data1 = [3, 5, 2, 7, 4, 1, 2];
            data2 = [2, 4, 2, 6, 3, 1, 2];
            break;
        case 'weekly':
            labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            data1 = [24, 31, 18, 27];
            data2 = [22, 28, 16, 24];
            break;
        case 'monthly':
        default:
            labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            data1 = [12, 19, 15, 22, 18, 25, 20, 28, 22, 30, 26, 35];
            data2 = [10, 16, 13, 20, 16, 23, 18, 25, 20, 27, 24, 32];
            break;
    }
    
    charts.returnTrend.data.labels = labels;
    charts.returnTrend.data.datasets[0].data = data1;
    charts.returnTrend.data.datasets[1].data = data2;
    charts.returnTrend.update();
}

function updateReports() {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();
    const customer = $('#customerFilter').val();
    const product = $('#productFilter').val();
    const status = $('#statusFilter').val();
    
    // Show loading state
    $('.card').addClass('loading');
    
    // Simulate API call
    setTimeout(() => {
        // Update metrics (this would come from API)
        updateMetrics();
        updateCharts();
        updateTables();
        
        $('.card').removeClass('loading');
        
        // Show success message
        $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
            'Reports updated successfully' +
            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
        '</div>').prependTo('.page-heading');
        
        // Auto-hide success message
        setTimeout(() => {
            $('.alert-success').fadeOut();
        }, 3000);
    }, 1500);
}

function updateMetrics() {
    // Simulate updated metrics
    $('#totalReturns').text(Math.floor(Math.random() * 100) + 50);
    $('#completedReturns').text(Math.floor(Math.random() * 80) + 40);
    $('#avgCycleTime').text((Math.random() * 5 + 10).toFixed(1) + ' days');
    $('#returnRate').text((Math.random() * 2 + 1).toFixed(1) + '%');
}

function updateCharts() {
    // Update charts with new data
    Object.values(charts).forEach(chart => {
        if (chart.data.datasets[0].data) {
            chart.data.datasets.forEach(dataset => {
                dataset.data = dataset.data.map(() => Math.floor(Math.random() * 40) + 10);
            });
            chart.update();
        }
    });
}

function updateTables() {
    // Update tables with new data (simplified)
    // In real implementation, this would fetch from API
}

function resetFilters() {
    $('#startDate').val('{{ date("Y-m-01") }}');
    $('#endDate').val('{{ date("Y-m-d") }}');
    $('#customerFilter, #productFilter, #statusFilter').val('');
    
    updateReports();
}

function exportReport() {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();
    const customer = $('#customerFilter').val();
    const product = $('#productFilter').val();
    const status = $('#statusFilter').val();
    
    // Show export options modal
    const exportModal = $(`
        <div class="modal fade" id="exportModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Export Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Export Format</label>
                            <select class="form-select" id="exportFormat">
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="pdf">PDF Report</option>
                                <option value="csv">CSV Data</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Include</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="includeCharts" checked>
                                <label class="form-check-label">Charts & Graphs</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="includeDetails" checked>
                                <label class="form-check-label">Detailed Data</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="includeRecommendations" checked>
                                <label class="form-check-label">Recommendations</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" onclick="confirmExport()">
                            <i class="bi bi-download"></i> Export Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `);
    
    $('body').append(exportModal);
    $('#exportModal').modal('show');
    
    // Remove modal after hiding
    $('#exportModal').on('hidden.bs.modal', function() {
        $(this).remove();
    });
}

function confirmExport() {
    const format = $('#exportFormat').val();
    const includeCharts = $('#includeCharts').is(':checked');
    const includeDetails = $('#includeDetails').is(':checked');
    const includeRecommendations = $('#includeRecommendations').is(':checked');
    
    $('#exportModal').modal('hide');
    
    // Show download progress
    $('<div class="alert alert-info alert-dismissible fade show" role="alert">' +
        '<i class="bi bi-download"></i> Preparing export... This may take a few moments.' +
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
    '</div>').prependTo('.page-heading');
    
    // Simulate export process
    setTimeout(() => {
        $('.alert-info').removeClass('alert-info').addClass('alert-success')
            .html('<i class="bi bi-check-circle"></i> Report exported successfully! Download should start automatically.' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
        
        // Trigger download (mock)
        const link = document.createElement('a');
        link.href = `{{ route('return-reports.index') }}/export?format=${format}`;
        link.download = `return-report-${new Date().toISOString().split('T')[0]}.${format}`;
        link.click();
    }, 2000);
}

// Auto-refresh data every 5 minutes
setInterval(function() {
    updateReports();
}, 300000);
</script>
@endpush