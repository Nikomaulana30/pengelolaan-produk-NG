@extends('layouts.app')

@section('title', 'Detail Customer Complaint')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Customer Complaint</h3>
                <p class="text-subtitle text-muted">Informasi lengkap complaint #{{ $complaint->nomor_complaint }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer-complaint.index') }}">Customer Complaint</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <!-- Header Card -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">📋 {{ $complaint->nomor_complaint }}</h4>
                        <p class="mb-0">Tanggal Complaint: {{ $complaint->tanggal_complaint->format('d F Y H:i') }}</p>
                    </div>
                    <div class="text-end">
                        @if($complaint->status === 'pending')
                            <span class="badge bg-warning fs-6">⏳ Pending</span>
                        @elseif($complaint->status === 'in_progress')
                            <span class="badge bg-info fs-6">🔄 In Progress</span>
                        @elseif($complaint->status === 'resolved')
                            <span class="badge bg-success fs-6">✅ Resolved</span>
                        @elseif($complaint->status === 'closed')
                            <span class="badge bg-secondary fs-6">🔒 Closed</span>
                        @endif
                        <br>
                        @if($complaint->priority === 'low')
                            <span class="badge bg-secondary mt-2">Low Priority</span>
                        @elseif($complaint->priority === 'medium')
                            <span class="badge bg-warning mt-2">Medium Priority</span>
                        @elseif($complaint->priority === 'high')
                            <span class="badge bg-danger mt-2">High Priority</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Customer Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-person-circle me-2"></i>Informasi Customer</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Nama Customer</label>
                                <div class="fw-bold">{{ $complaint->nama_customer }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Email</label>
                                <div>{{ $complaint->email_customer }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Telepon</label>
                                <div>{{ $complaint->telepon_customer }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Alamat</label>
                                <div>{{ $complaint->alamat_customer }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-box me-2"></i>Informasi Produk</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label text-muted small">Nama Produk</label>
                                <div class="fw-bold">{{ $complaint->produk }}</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted small">Quantity NG</label>
                                <div><span class="badge bg-danger fs-6">{{ $complaint->quantity_ng }} unit</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Complaint Detail -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-chat-left-text me-2"></i>Detail Complaint</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Deskripsi Complaint</label>
                            <div class="p-3 bg-light rounded">{{ $complaint->deskripsi_complaint }}</div>
                        </div>

                        @if($complaint->foto_complaint && count($complaint->foto_complaint) > 0)
                        <div class="mb-3">
                            <label class="form-label text-muted small">Foto Complaint</label>
                            <div class="row g-2">
                                @foreach($complaint->foto_complaint as $foto)
                                <div class="col-md-4">
                                    <a href="{{ Storage::url($foto) }}" target="_blank">
                                        <img src="{{ Storage::url($foto) }}" class="img-fluid rounded border" alt="Foto Complaint">
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($complaint->dokumen_pendukung && count($complaint->dokumen_pendukung) > 0)
                        <div class="mb-3">
                            <label class="form-label text-muted small">Dokumen Pendukung</label>
                            <div class="list-group">
                                @foreach($complaint->dokumen_pendukung as $index => $dokumen)
                                <a href="{{ Storage::url($dokumen) }}" target="_blank" class="list-group-item list-group-item-action">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                    Dokumen {{ $index + 1 }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Staff Notes -->
                @if($complaint->catatan_staff)
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-sticky me-2"></i>Catatan Staff EXIM</h5>
                    </div>
                    <div class="card-body">
                        <div class="p-3 bg-light rounded">{{ $complaint->catatan_staff }}</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-tools me-2"></i>Aksi</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('customer-complaint.edit', $complaint) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square me-2"></i>Edit Complaint
                            </a>
                            
                            @if($complaint->status === 'pending')
                            <form action="{{ route('customer-complaint.update-status', $complaint) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="bi bi-play-circle me-2"></i>Mulai Proses
                                </button>
                            </form>
                            @endif

                            @if($complaint->status === 'in_progress')
                            <form action="{{ route('customer-complaint.update-status', $complaint) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="resolved">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle me-2"></i>Tandai Selesai
                                </button>
                            </form>
                            @endif

                            <a href="{{ route('customer-complaint.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>

                            <form action="{{ route('customer-complaint.destroy', $complaint) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus complaint ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-trash me-2"></i>Hapus Complaint
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i>Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <p class="mb-0 small text-muted">{{ $complaint->created_at->format('d M Y H:i') }}</p>
                                    <p class="mb-0">Complaint dibuat</p>
                                </div>
                            </div>
                            
                            @if($complaint->updated_at != $complaint->created_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <p class="mb-0 small text-muted">{{ $complaint->updated_at->format('d M Y H:i') }}</p>
                                    <p class="mb-0">Terakhir diupdate</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    
    .timeline-item:not(:last-child):before {
        content: '';
        position: absolute;
        left: -21px;
        top: 20px;
        height: calc(100% - 10px);
        width: 2px;
        background: #e0e0e0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -26px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
    }
    
    .timeline-content {
        padding-left: 10px;
    }
</style>
@endpush
@endsection
