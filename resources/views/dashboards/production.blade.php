@extends('layouts.app')

@section('title', 'Production Dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Production Dashboard</h3>
                <p class="text-subtitle text-muted">Monitor rework process dan production tasks</p>
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
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Pending Rework</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['pending_rework'] }}</h6>
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
                                    <i class="bi bi-gear-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">In Progress</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['in_progress_rework'] }}</h6>
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
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Completed Today</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['completed_today'] }}</h6>
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
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Cost This Month</h6>
                                <h6 class="font-extrabold mb-0">Rp {{ number_format($data['total_cost_month'], 0, ',', '.') }}</h6>
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
                        <h4>Recent Reworks</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Produk</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th>Biaya</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['recent_reworks'] as $rework)
                                    <tr>
                                        <td><strong>{{ $rework->nomor_rework }}</strong></td>
                                        <td>{{ $rework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk ?? '-' }}</td>
                                        <td>{{ $rework->metode_rework }}</td>
                                        <td>
                                            @if($rework->status == 'draft')
                                                <span class="badge bg-secondary">Draft</span>
                                            @elseif($rework->status == 'sent_to_production')
                                                <span class="badge bg-warning">Sent to Production</span>
                                            @elseif($rework->status == 'in_progress')
                                                <span class="badge bg-info">In Progress</span>
                                            @elseif($rework->status == 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($rework->status) }}</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($rework->actual_biaya ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('production-rework.show', $rework) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No reworks yet</td>
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
                        <h4>Rework Status Distribution</h4>
                    </div>
                    <div class="card-body">
                        @foreach($data['rework_by_status'] as $status)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ ucwords(str_replace('_', ' ', $status->status)) }}</h6>
                            </div>
                            <div>
                                @if($status->status == 'draft')
                                    <span class="badge bg-secondary">{{ $status->count }}</span>
                                @elseif($status->status == 'in_progress')
                                    <span class="badge bg-info">{{ $status->count }}</span>
                                @elseif($status->status == 'completed')
                                    <span class="badge bg-success">{{ $status->count }}</span>
                                @else
                                    <span class="badge bg-warning">{{ $status->count }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('master-produk.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-box"></i> Master Produk
                            </a>
                            <a href="{{ route('production-rework.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Start Rework
                            </a>
                            <a href="{{ route('production-rework.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-list-task"></i> Rework Queue
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
