@extends('layouts.app')

@section('title', 'Cost Analysis - Return Reports')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>💰 Cost Analysis</h3>
                <p class="text-subtitle text-muted">Analisis biaya rework & pengiriman</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('return-reports.index') }}">Return Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cost Analysis</li>
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

        <!-- Cost Summary -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        @php
                            $totalReworkCost = App\Models\ProductionRework::whereBetween('created_at', [now()->subDays($period), now()])->sum('actual_biaya');
                        @endphp
                        <h6 class="text-muted">Total Rework Cost</h6>
                        <h3 class="text-danger mb-0">Rp {{ number_format($totalReworkCost) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        @php
                            $totalShipmentCost = App\Models\ReturnShipment::whereBetween('created_at', [now()->subDays($period), now()])->sum('biaya_pengiriman');
                        @endphp
                        <h6 class="text-muted">Total Shipment Cost</h6>
                        <h3 class="text-warning mb-0">Rp {{ number_format($totalShipmentCost) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        @php
                            $totalCost = $totalReworkCost + $totalShipmentCost;
                        @endphp
                        <h6 class="text-muted">Total Cost</h6>
                        <h3 class="text-primary mb-0">Rp {{ number_format($totalCost) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cost by Product -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📦 Cost by Product</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Complaints</th>
                                        <th>Rework Cost</th>
                                        <th>Shipment Cost</th>
                                        <th>Total Cost</th>
                                        <th>Avg Cost/Item</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $productCosts = App\Models\ProductionRework::with('qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint')
                                            ->whereBetween('created_at', [now()->subDays($period), now()])
                                            ->get()
                                            ->groupBy(function($rework) {
                                                return $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk ?? 'Unknown';
                                            })
                                            ->map(function($group) {
                                                return [
                                                    'count' => $group->count(),
                                                    'rework_cost' => $group->sum('actual_biaya'),
                                                    'produk' => $group->first()->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk ?? 'Unknown'
                                                ];
                                            });
                                    @endphp

                                    @forelse($productCosts as $product => $cost)
                                    @php
                                        $shipmentCost = App\Models\ReturnShipment::whereHas('finalQualityCheck.productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint', function($q) use ($product) {
                                            $q->where('produk', $product);
                                        })
                                        ->whereBetween('created_at', [now()->subDays($period), now()])
                                        ->sum('biaya_pengiriman');
                                        
                                        $totalProductCost = $cost['rework_cost'] + $shipmentCost;
                                        $avgCost = $cost['count'] > 0 ? $totalProductCost / $cost['count'] : 0;
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $product }}</strong></td>
                                        <td>{{ $cost['count'] }}</td>
                                        <td class="text-danger">Rp {{ number_format($cost['rework_cost']) }}</td>
                                        <td class="text-warning">Rp {{ number_format($shipmentCost) }}</td>
                                        <td class="text-primary"><strong>Rp {{ number_format($totalProductCost) }}</strong></td>
                                        <td>Rp {{ number_format($avgCost) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No cost data available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cost by Defect Type -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔬 Cost by Defect Type</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $defectCosts = App\Models\ProductionRework::with('qualityReinspection')
                                ->whereBetween('created_at', [now()->subDays($period), now()])
                                ->get()
                                ->groupBy(function($rework) {
                                    return $rework->qualityReinspection->jenis_defect ?? 'Unknown';
                                })
                                ->map(function($group) {
                                    return [
                                        'count' => $group->count(),
                                        'total_cost' => $group->sum('actual_biaya'),
                                        'avg_cost' => $group->avg('actual_biaya')
                                    ];
                                })
                                ->sortByDesc('total_cost');
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Defect Type</th>
                                        <th>Count</th>
                                        <th>Total Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($defectCosts as $defect => $cost)
                                    <tr>
                                        <td><strong>{{ $defect }}</strong></td>
                                        <td>{{ $cost['count'] }}</td>
                                        <td class="text-danger">Rp {{ number_format($cost['total_cost']) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No data</td>
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
                        <h5 class="card-title mb-0">🚚 Shipment Cost by Ekspedisi</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $shipmentByEkspedisi = App\Models\ReturnShipment::selectRaw('
                                ekspedisi,
                                COUNT(*) as count,
                                SUM(biaya_pengiriman) as total_cost,
                                AVG(biaya_pengiriman) as avg_cost
                            ')
                            ->whereBetween('created_at', [now()->subDays($period), now()])
                            ->whereNotNull('biaya_pengiriman')
                            ->groupBy('ekspedisi')
                            ->orderByDesc('total_cost')
                            ->get();
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Ekspedisi</th>
                                        <th>Shipments</th>
                                        <th>Total Cost</th>
                                        <th>Avg Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($shipmentByEkspedisi as $ekspedisi)
                                    <tr>
                                        <td><span class="badge bg-info">{{ $ekspedisi->ekspedisi }}</span></td>
                                        <td>{{ $ekspedisi->count }}</td>
                                        <td class="text-warning">Rp {{ number_format($ekspedisi->total_cost) }}</td>
                                        <td>Rp {{ number_format($ekspedisi->avg_cost) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No shipment cost data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- High Cost Items -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">⚠️ High Cost Reworks (> Rp 1,000,000)</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $highCostReworks = App\Models\ProductionRework::with('qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint')
                                ->whereBetween('created_at', [now()->subDays($period), now()])
                                ->where('actual_biaya', '>', 1000000)
                                ->orderByDesc('actual_biaya')
                                ->limit(10)
                                ->get();
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nomor Rework</th>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Defect Type</th>
                                        <th>Cost</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($highCostReworks as $rework)
                                    <tr>
                                        <td>
                                            <a href="{{ route('production-rework.show', $rework) }}">
                                                {{ $rework->nomor_rework }}
                                            </a>
                                        </td>
                                        <td>{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</td>
                                        <td>{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</td>
                                        <td>{{ $rework->qualityReinspection->jenis_defect }}</td>
                                        <td class="text-danger"><strong>Rp {{ number_format($rework->actual_biaya) }}</strong></td>
                                        <td>{{ $rework->actual_waktu_hari }} days</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No high cost reworks in this period</td>
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
