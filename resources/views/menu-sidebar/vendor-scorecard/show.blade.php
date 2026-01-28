@extends('layouts.app')

@section('title', 'Vendor Scorecard Detail - ' . $vendor->nama_vendor)

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-graph-up"></i> {{ $vendor->nama_vendor }}</h3>
                    <p class="text-subtitle text-muted">Vendor Scorecard Detail - Performance Analysis</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('vendor-scorecard.index') }}" class="btn btn-outline-secondary float-end">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <!-- Vendor Info Card -->
        <div class="row mb-4">
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Contact Information</h6>
                        <p class="mb-2">
                            <strong>Phone:</strong><br>
                            {{ $vendor->telepon ?? 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <strong>Email:</strong><br>
                            {{ $vendor->email ?? 'N/A' }}
                        </p>
                        <p class="mb-0">
                            <strong>Return Policy:</strong><br>
                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $vendor->kebijakan_retur)) }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Performance Score -->
            <div class="col-12 col-md-8">
                <div class="row">
                    <div class="col-12 col-md-4 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h2 class="mb-0">{{ $metrics->performance_score }}</h2>
                                <p class="mb-0">Performance Score</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h2 class="mb-0">
                                    @if($metrics->performance_rating === 'Excellent')
                                        <span class="text-success">✓</span>
                                    @elseif($metrics->performance_rating === 'Good')
                                        <span class="text-info">✓</span>
                                    @elseif($metrics->performance_rating === 'Fair')
                                        <span class="text-warning">⚠</span>
                                    @else
                                        <span class="text-danger">✗</span>
                                    @endif
                                </h2>
                                <p class="mb-0">{{ $metrics->performance_rating }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h2 class="mb-0">{{ $metrics->total_returns }}</h2>
                                <p class="mb-0">Total Returns</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="row mb-4">
            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Approval Rate</h6>
                        <div class="progress mb-2" style="height: 25px;">
                            @php
                                $rate = $metrics->approval_rate;
                                $color = $rate >= 80 ? 'success' : ($rate >= 60 ? 'warning' : 'danger');
                            @endphp
                            <div class="progress-bar bg-{{ $color }}" style="width: {{ $rate }}%">
                                {{ round($rate, 1) }}%
                            </div>
                        </div>
                        <small class="text-muted">{{ $metrics->approved_returns }} approved of {{ $metrics->total_returns }} returns</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Return Status Breakdown</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <span class="badge bg-success">✓</span>
                                <small>Approved: {{ $metrics->approved_returns }}</small>
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-warning">⏳</span>
                                <small>Pending: {{ $metrics->pending_returns }}</small>
                            </li>
                            <li>
                                <span class="badge bg-danger">✗</span>
                                <small>Rejected: {{ $metrics->rejected_returns }}</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Return Statistics</h6>
                        <p class="mb-2">
                            <strong>Total Qty:</strong><br>
                            {{ $metrics->total_qty_returned }} unit
                        </p>
                        <p class="mb-0">
                            <strong>Avg per Return:</strong><br>
                            {{ $metrics->avg_qty_per_return }} unit
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">RCA Analysis</h6>
                        <h3 class="mb-2">{{ $metrics->rca_count }}</h3>
                        <small class="text-muted">
                            RCA analyses linked to this vendor's returns
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Defect Distribution -->
        @if($defectDistribution->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔴 Defect Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Defect Code</th>
                                        <th>Defect Name</th>
                                        <th class="text-end">Occurrences</th>
                                        <th class="text-end">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalDefects = $defectDistribution->sum('count'); @endphp
                                    @foreach($defectDistribution as $defect)
                                    <tr>
                                        <td><strong>{{ $defect['kode_defect'] }}</strong></td>
                                        <td>{{ $defect['nama_defect'] }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-danger">{{ $defect['count'] }}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="progress" style="width: 120px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ ($defect['count'] / $totalDefects * 100) }}%">
                                                </div>
                                            </div>
                                            <small>{{ round(($defect['count'] / $totalDefects * 100), 1) }}%</small>
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

        <!-- Similar Vendors Comparison -->
        @if($similarVendors->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📊 Similar Vendors Comparison</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Vendor Name</th>
                                        <th class="text-center">Score</th>
                                        <th class="text-center">Rating</th>
                                        <th class="text-center">Returns</th>
                                        <th class="text-center">Approval Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($similarVendors as $similar)
                                    <tr>
                                        <td>{{ $similar->nama_vendor }}</td>
                                        <td class="text-center">{{ $similar->performance_score }}</td>
                                        <td class="text-center">
                                            @if($similar->performance_rating === 'Excellent')
                                                <span class="badge bg-success">Excellent</span>
                                            @elseif($similar->performance_rating === 'Good')
                                                <span class="badge bg-info">Good</span>
                                            @elseif($similar->performance_rating === 'Fair')
                                                <span class="badge bg-warning">Fair</span>
                                            @else
                                                <span class="badge bg-danger">Poor</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $similar->total_returns }}</td>
                                        <td class="text-center">{{ round($similar->approval_rate, 1) }}%</td>
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

        <!-- Return History -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📋 Return History</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Retur</th>
                                        <th>Product</th>
                                        <th class="text-center">Qty</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">RCA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($returHistory as $retur)
                                    <tr>
                                        <td>
                                            <a href="{{ route('retur-barang.show', $retur) }}" class="text-decoration-none">
                                                <strong>{{ $retur->no_retur }}</strong>
                                            </a>
                                        </td>
                                        <td>
                                            {{ $retur->produk->kode_produk ?? 'N/A' }}<br>
                                            <small class="text-muted">{{ $retur->produk?->nama_produk ?? 'Produk Tidak Ditemukan' }}</small>
                                        </td>
                                        <td class="text-center">{{ $retur->jumlah_retur }}</td>
                                        <td>{{ $retur->tanggal_retur->format('d M Y') }}</td>
                                        <td class="text-center">
                                            @if($retur->status_approval === 'approved')
                                                <span class="badge bg-success">✓</span>
                                            @elseif($retur->status_approval === 'pending')
                                                <span class="badge bg-warning">⏳</span>
                                            @else
                                                <span class="badge bg-danger">✗</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($retur->rcaAnalyses->count() > 0)
                                                <span class="badge bg-info">{{ $retur->rcaAnalyses->count() }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada retur dari vendor ini</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $returHistory->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Linked RCA Analyses -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔍 Linked RCA Analyses</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>RCA No.</th>
                                        <th>Tanggal</th>
                                        <th>Defect</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rcaAnalyses as $rca)
                                    <tr>
                                        <td><strong>{{ $rca->nomor_rca }}</strong></td>
                                        <td>{{ $rca->tanggal_analisa->format('d M Y') }}</td>
                                        <td>
                                            {{ $rca->masterDefect->kode_defect ?? 'N/A' }}<br>
                                            <small class="text-muted">{{ $rca->masterDefect->nama_defect ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            @if($rca->metode_rca === '5_why')
                                                <span class="badge bg-info">5 Why</span>
                                            @elseif($rca->metode_rca === 'fishbone')
                                                <span class="badge bg-primary">Fishbone</span>
                                            @else
                                                <span class="badge bg-secondary">Kombinasi</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($rca->status_rca === 'open')
                                                <span class="badge bg-danger">Open</span>
                                            @elseif($rca->status_rca === 'in_progress')
                                                <span class="badge bg-warning">In Progress</span>
                                            @else
                                                <span class="badge bg-success">Closed</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('rca-analysis.show', $rca) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada RCA analysis untuk vendor ini</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $rcaAnalyses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
