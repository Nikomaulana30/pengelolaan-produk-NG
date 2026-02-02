@extends('layouts.app')

@section('title', 'Return Shipment Detail')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Return Shipment Detail</h3>
                <p class="text-subtitle text-muted">{{ $shipment->nomor_pengiriman }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('return-shipment.index') }}">Return Shipment</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $shipment->nomor_pengiriman }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-8">
                <!-- Main Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Shipment Information</h4>
                            <div>
                                <span class="badge bg-{{ 
                                    $shipment->status_pengiriman === 'preparing' ? 'secondary' : 
                                    ($shipment->status_pengiriman === 'shipped' ? 'primary' : 
                                    ($shipment->status_pengiriman === 'delivered' ? 'success' : 'danger')) 
                                }}">
                                    {{ strtoupper(str_replace('_', ' ', $shipment->status_pengiriman)) }}
                                </span>
                                <span class="badge bg-{{ 
                                    $shipment->status === 'draft' ? 'secondary' : 
                                    ($shipment->status === 'shipped' ? 'primary' : 'success') 
                                }}">
                                    {{ strtoupper($shipment->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Customer & Product Info -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="bi bi-person-circle me-2"></i>Customer & Product</h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Customer:</strong>
                                    <p class="mb-2">{{ $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nama_customer }}</p>
                                    <strong>Email:</strong>
                                    <p class="mb-0">{{ $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->email_customer ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Produk:</strong>
                                    <p class="mb-2">{{ $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->produk }}</p>
                                    <strong>Final QC:</strong>
                                    <p class="mb-0">
                                        <a href="{{ route('final-quality-check.show', $shipment->finalQualityCheck) }}">
                                            #{{ $shipment->finalQualityCheck->id }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Shipment Details -->
                        <h5 class="mb-3"><i class="bi bi-truck me-2"></i>Shipment Details</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Nomor Pengiriman</label>
                                <p class="fw-bold">{{ $shipment->nomor_pengiriman }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Tanggal Pengiriman</label>
                                <p class="fw-bold">{{ $shipment->tanggal_pengiriman->format('d F Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Quantity Shipped</label>
                                <p class="fw-bold text-primary">{{ number_format($shipment->quantity_shipped, 0) }} pcs</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Ekspedisi</label>
                                <p><span class="badge bg-info">{{ $shipment->ekspedisi }}</span></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Nomor Resi</label>
                                <p>
                                    @if($shipment->nomor_resi)
                                        <code>{{ $shipment->nomor_resi }}</code>
                                        @if($shipment->status_pengiriman === 'shipped')
                                            <a href="#" onclick="trackShipment('{{ $shipment->nomor_resi }}', '{{ $shipment->ekspedisi }}')" 
                                               class="ms-2 text-primary">
                                                <i class="bi bi-geo-alt"></i> Track
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Biaya Pengiriman</label>
                                <p class="fw-bold">
                                    @if($shipment->biaya_pengiriman)
                                        Rp {{ number_format($shipment->biaya_pengiriman, 0, ',', '.') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <h5 class="mb-3"><i class="bi bi-geo-alt me-2"></i>Alamat Pengiriman</h5>
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $shipment->alamat_pengiriman }}</p>
                            </div>
                        </div>

                        <!-- Notes -->
                        @if($shipment->catatan_pengiriman)
                        <h5 class="mb-3"><i class="bi bi-chat-left-text me-2"></i>Catatan Pengiriman</h5>
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $shipment->catatan_pengiriman }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Delivery Notes -->
                        @if($shipment->catatan_delivery)
                        <h5 class="mb-3"><i class="bi bi-clipboard-check me-2"></i>Catatan Delivery</h5>
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $shipment->catatan_delivery }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Documents -->
                        @if($shipment->dokumen_pengiriman && count($shipment->dokumen_pengiriman) > 0)
                        <h5 class="mb-3"><i class="bi bi-file-earmark-text me-2"></i>Dokumen Pengiriman</h5>
                        <div class="row mb-4">
                            @foreach($shipment->dokumen_pengiriman as $index => $doc)
                                @php
                                    $extension = pathinfo($doc, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                
                                @if($isImage)
                                    <div class="col-md-3 mb-3">
                                        <a href="{{ Storage::url($doc) }}" target="_blank">
                                            <img src="{{ Storage::url($doc) }}" class="img-thumbnail" alt="Document {{ $index + 1 }}">
                                        </a>
                                        <small class="d-block text-center mt-1">Dokumen {{ $index + 1 }}</small>
                                    </div>
                                @else
                                    <div class="col-md-6 mb-2">
                                        <div class="card">
                                            <div class="card-body py-2">
                                                <i class="bi bi-file-earmark-pdf me-2"></i>
                                                <a href="{{ Storage::url($doc) }}" target="_blank">Dokumen {{ $index + 1 }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        <!-- Rating -->
                        @if($shipment->rating_customer)
                        <h5 class="mb-3"><i class="bi bi-star me-2"></i>Rating Customer</h5>
                        <div class="mb-4">
                            <p class="mb-0">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $shipment->rating_customer)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-muted"></i>
                                    @endif
                                @endfor
                                <span class="ms-2 text-muted">({{ $shipment->rating_customer }}/5)</span>
                            </p>
                        </div>
                        @endif

                        <!-- Timeline -->
                        <h5 class="mb-3"><i class="bi bi-clock-history me-2"></i>Timeline</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td width="200"><strong>Dibuat:</strong></td>
                                        <td>{{ $shipment->created_at->format('d F Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Diupdate:</strong></td>
                                        <td>{{ $shipment->updated_at->format('d F Y H:i') }}</td>
                                    </tr>
                                    @if($shipment->delivered_at)
                                    <tr>
                                        <td><strong>Delivered:</strong></td>
                                        <td>{{ $shipment->delivered_at->format('d F Y H:i') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Warehouse Staff:</strong></td>
                                        <td>{{ $shipment->warehouseStaff->name ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Actions Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <!-- Debug Info (hapus setelah berhasil) -->
                        <div class="alert alert-info small mb-3">
                            <strong>Debug:</strong><br>
                            Status Pengiriman: <code>{{ $shipment->status_pengiriman }}</code><br>
                            Status: <code>{{ $shipment->status }}</code>
                        </div>

                        <div class="d-grid gap-2">
                            @if($shipment->status_pengiriman === 'preparing' || $shipment->status === 'draft')
                            <a href="{{ route('return-shipment.edit', $shipment) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-2"></i>Edit Shipment
                            </a>
                            @endif

                            @if($shipment->status_pengiriman === 'preparing')
                            <form action="{{ route('return-shipment.ship', $shipment) }}" method="POST" class="mb-0">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success w-100" 
                                        onclick="return confirm('Tandai shipment sebagai SHIPPED?')">
                                    <i class="bi bi-truck me-2"></i>Ship Now
                                </button>
                            </form>
                            @endif

                            @if($shipment->status_pengiriman === 'shipped')
                            <form action="{{ route('return-shipment.delivered', $shipment) }}" method="POST" class="mb-0">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-check-circle me-2"></i>Mark as Delivered
                                </button>
                            </form>
                            @else
                                @if($shipment->status_pengiriman !== 'preparing' && $shipment->status_pengiriman !== 'delivered')
                                <div class="alert alert-warning small mb-2">
                                    Status saat ini: {{ $shipment->status_pengiriman }}<br>
                                    Tombol "Mark as Delivered" hanya muncul jika status = 'shipped'
                                </div>
                                @endif
                            @endif

                            <button class="btn btn-outline-secondary" onclick="printLabel()">
                                <i class="bi bi-printer me-2"></i>Print Label
                            </button>

                            <button class="btn btn-outline-secondary" onclick="printPackingList()">
                                <i class="bi bi-list-ul me-2"></i>Print Packing List
                            </button>

                            @if($shipment->nomor_resi)
                            <button class="btn btn-outline-info" onclick="trackShipment('{{ $shipment->nomor_resi }}', '{{ $shipment->ekspedisi }}')">
                                <i class="bi bi-geo-alt me-2"></i>Track Shipment
                            </button>
                            @endif

                            <a href="{{ route('return-shipment.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Related Information -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Related Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Final Quality Check</label>
                            <p class="mb-0">
                                <a href="{{ route('final-quality-check.show', $shipment->finalQualityCheck) }}">
                                    #{{ $shipment->finalQualityCheck->id }}
                                </a>
                            </p>
                            <small class="text-muted">
                                Keputusan: 
                                <span class="badge bg-success">{{ strtoupper(str_replace('_', ' ', $shipment->finalQualityCheck->keputusan_final)) }}</span>
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Production Rework</label>
                            <p class="mb-0">
                                <a href="{{ route('production-rework.show', $shipment->finalQualityCheck->productionRework) }}">
                                    {{ $shipment->finalQualityCheck->productionRework->nomor_rework }}
                                </a>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Customer Complaint</label>
                            <p class="mb-0">
                                <a href="{{ route('customer-complaint.show', $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint) }}">
                                    {{ $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->nomor_complaint }}
                                </a>
                            </p>
                            <small class="text-muted">
                                {{ $shipment->finalQualityCheck->productionRework->qualityReinspection->warehouseVerification->dokumenRetur->customerComplaint->created_at->format('d M Y') }}
                            </small>
                        </div>

                        <hr>

                        <div class="mb-2">
                            <label class="text-muted small">Quantity Summary</label>
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td>Checked:</td>
                                    <td class="text-end">{{ $shipment->finalQualityCheck->quantity_checked }} pcs</td>
                                </tr>
                                <tr>
                                    <td>Passed:</td>
                                    <td class="text-end text-success">{{ $shipment->finalQualityCheck->quantity_passed }} pcs</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Shipped:</td>
                                    <td class="text-end text-primary">{{ $shipment->quantity_shipped }} pcs</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status Progress -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Status Progress</h5>
                    </div>
                    <div class="card-body">
                        <div class="progress-wrapper mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small">Preparing</span>
                                <i class="bi bi-{{ $shipment->status_pengiriman === 'preparing' || $shipment->status_pengiriman === 'shipped' || $shipment->status_pengiriman === 'delivered' ? 'check-circle-fill text-success' : 'circle text-muted' }}"></i>
                            </div>
                        </div>
                        <div class="progress-wrapper mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small">Shipped</span>
                                <i class="bi bi-{{ $shipment->status_pengiriman === 'shipped' || $shipment->status_pengiriman === 'delivered' ? 'check-circle-fill text-success' : 'circle text-muted' }}"></i>
                            </div>
                        </div>
                        <div class="progress-wrapper">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small">Delivered</span>
                                <i class="bi bi-{{ $shipment->status_pengiriman === 'delivered' ? 'check-circle-fill text-success' : 'circle text-muted' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Print Styles -->
<style>
    @media print {
        /* Hide non-printable elements */
        .no-print, .sidebar, .navbar, nav, .breadcrumb, .btn, .btn-group, button, form { display: none !important; }
        
        @page { size: A4; margin: 15mm; }
        body { font-size: 11pt; color: #000; }
        
        /* 2-column card layout */
        .row { display: flex; flex-wrap: wrap; page-break-inside: avoid; margin-bottom: 10px; }
        .col-md-6, .col-lg-6 { width: 48% !important; float: left; margin-right: 2%; page-break-inside: avoid; }
        .col-md-4 { width: 31% !important; float: left; margin-right: 2%; page-break-inside: avoid; }
        .col-md-12 { width: 100% !important; }
        
        .card { border: 1px solid #ddd !important; box-shadow: none !important; margin-bottom: 10px; page-break-inside: avoid; background: white; }
        .card-header { background-color: #f8f9fa !important; border-bottom: 1px solid #ddd !important; padding: 8px 12px !important; font-weight: bold; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .card-body { padding: 10px 12px !important; }
        
        .page-heading { margin-bottom: 15px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        h3, h4, h5, h6 { page-break-after: avoid; margin-top: 10px; margin-bottom: 8px; }
        .badge { border: 1px solid #000; padding: 2px 6px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        table { width: 100%; border-collapse: collapse; page-break-inside: avoid; }
        table th, table td { border: 1px solid #ddd; padding: 6px; font-size: 10pt; }
        .alert { border: 1px solid #ddd !important; padding: 8px !important; page-break-inside: avoid; }
        img { max-width: 100%; page-break-inside: avoid; }
        .sidebar, .navbar, .breadcrumb, .card-header .btn, .btn-group, nav {
            display: none !important;
        }
    }
</style>

@push('scripts')
<script>
function printLabel() {
    window.print();
}

function printPackingList() {
    alert('Packing list akan digenerate...');
}

function trackShipment(resi, ekspedisi) {
    let trackingUrl = '';
    
    switch(ekspedisi.toUpperCase()) {
        case 'JNE':
            trackingUrl = 'https://www.jne.co.id/id/tracking/trace';
            break;
        case 'TIKI':
            trackingUrl = 'https://www.tiki.id/id/tracking';
            break;
        case 'POS INDONESIA':
            trackingUrl = 'https://www.posindonesia.co.id/id/tracking';
            break;
        case 'J&T EXPRESS':
            trackingUrl = 'https://www.jet.co.id/track';
            break;
        case 'SICEPAT':
            trackingUrl = 'https://www.sicepat.com/checkAwb';
            break;
        default:
            alert('Nomor Resi: ' + resi + '\n\nSilakan cek di website ekspedisi: ' + ekspedisi);
            return;
    }
    
    window.open(trackingUrl, '_blank');
}
</script>
@endpush
@endsection
