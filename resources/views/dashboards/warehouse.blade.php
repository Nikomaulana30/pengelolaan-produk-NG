@extends('layouts.app')

@section('title', 'Warehouse Dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Warehouse Dashboard</h3>
                <p class="text-subtitle text-muted">Verifikasi barang dan kelola pengiriman</p>
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
                                    <i class="bi bi-clipboard-check"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Pending Verification</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['pending_verification'] }}</h6>
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
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Verified Today</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['verified_today'] }}</h6>
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
                                    <i class="bi bi-box-seam"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Pending Shipment</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['pending_shipment'] }}</h6>
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
                                    <i class="bi bi-truck"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Shipped Today</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['shipped_today'] }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12 col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Verifications</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Customer</th>
                                        <th>Kondisi</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['recent_verifications'] as $verification)
                                    <tr>
                                        <td><strong>{{ $verification->nomor_verifikasi }}</strong></td>
                                        <td>{{ $verification->dokumenRetur->customerComplaint->nama_customer }}</td>
                                        <td>
                                            @if($verification->kondisi_barang == 'baik')
                                                <span class="badge bg-success">Baik</span>
                                            @elseif($verification->kondisi_barang == 'rusak')
                                                <span class="badge bg-danger">Rusak</span>
                                            @else
                                                <span class="badge bg-warning">{{ ucfirst($verification->kondisi_barang) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $verification->masterLokasiGudang->nama_lokasi ?? $verification->lokasi_penyimpanan }}</td>
                                        <td>
                                            @if($verification->status == 'draft')
                                                <span class="badge bg-secondary">Draft</span>
                                            @elseif($verification->status == 'verified')
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-info">{{ ucfirst($verification->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('warehouse-verification.show', $verification) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No verifications yet</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h4>Pending Shipments</h4>
                    </div>
                    <div class="card-body">
                        @forelse($data['pending_shipments'] as $shipment)
                        <div class="alert alert-info mb-2">
                            <h6 class="mb-1"><strong>{{ $shipment->nomor_pengiriman }}</strong></h6>
                            <p class="mb-1"><small>Quantity: {{ $shipment->quantity_shipped }} pcs</small></p>
                            <a href="{{ route('return-shipment.show', $shipment) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-arrow-right"></i> Process
                            </a>
                        </div>
                        @empty
                        <p class="text-muted text-center">No pending shipments</p>
                        @endforelse
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('master-lokasi-gudang.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-geo-alt"></i> Master Lokasi Gudang
                            </a>
                            <a href="{{ route('dokumen-retur.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Document
                            </a>
                            <a href="{{ route('dokumen-retur.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-inbox"></i> Incoming Returns
                            </a>
                            <a href="{{ route('warehouse-verification.create') }}" class="btn btn-outline-warning">
                                <i class="bi bi-plus-circle"></i> Start Verification
                            </a>
                            <a href="{{ route('warehouse-verification.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-eye"></i> All Verifications
                            </a>
                            <a href="{{ route('return-shipment.create') }}" class="btn btn-outline-success">
                                <i class="bi bi-send"></i> Create Shipment
                            </a>
                            <a href="{{ route('return-shipment.index') }}" class="btn btn-outline-dark">
                                <i class="bi bi-box-seam"></i> Ready to Ship
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
