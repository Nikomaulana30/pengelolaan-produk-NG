@extends('layouts.app')

@section('title', 'Quality Dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Quality Dashboard</h3>
                <p class="text-subtitle text-muted">Quality reinspection dan final quality check</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon red mb-2">
                                    <i class="bi bi-search"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Pending Inspection</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['pending_inspection'] }}</h6>
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
                                    <i class="bi bi-clipboard-check"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Inspected Today</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['inspected_today'] }}</h6>
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
                                    <i class="bi bi-shield-check"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Pending Final Check</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['pending_final_check'] }}</h6>
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
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Passed Today</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['passed_today'] }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Inspections</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Produk</th>
                                        <th>Defect Type</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['recent_inspections'] as $inspection)
                                    <tr>
                                        <td><strong>{{ $inspection->nomor_inspeksi }}</strong></td>
                                        <td>{{ $inspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</td>
                                        <td>{{ $inspection->jenis_defect }}</td>
                                        <td>
                                            @if($inspection->severity_level == 'critical')
                                                <span class="badge bg-danger">Critical</span>
                                            @elseif($inspection->severity_level == 'major')
                                                <span class="badge bg-warning">Major</span>
                                            @elseif($inspection->severity_level == 'minor')
                                                <span class="badge bg-info">Minor</span>
                                            @else
                                                <span class="badge bg-success">Low</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($inspection->status == 'draft')
                                                <span class="badge bg-secondary">Draft</span>
                                            @elseif($inspection->status == 'inspected')
                                                <span class="badge bg-success">Inspected</span>
                                            @elseif($inspection->status == 'sent_to_production')
                                                <span class="badge bg-warning">Sent to Production</span>
                                            @else
                                                <span class="badge bg-info">{{ ucfirst($inspection->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('quality-reinspection.show', $inspection) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No inspections yet</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Top Defects</h4>
                    </div>
                    <div class="card-body">
                        @forelse($data['defect_distribution'] as $defect)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ $defect->jenis_defect }}</h6>
                            </div>
                            <div>
                                <span class="badge bg-danger">{{ $defect->count }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">No defect data</p>
                        @endforelse
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('master-defect.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-exclamation-triangle"></i> Master Defect
                            </a>
                            <a href="{{ route('master-disposisi.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-arrow-left-right"></i> Master Disposisi
                            </a>
                            <a href="{{ route('master-vendor.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-building"></i> Master Vendor
                            </a>
                            <a href="{{ route('quality-reinspection.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Start Inspection
                            </a>
                            <a href="{{ route('quality-reinspection.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-clipboard-data"></i> Pending Inspection
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
