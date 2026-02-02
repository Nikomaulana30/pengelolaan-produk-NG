@extends('layouts.app')

@push('styles')
    <style>
        .field-box {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        .field-box:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            background-color: #ffffff;
        }
        .field-label {
            color: #6c757d;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .field-value {
            color: #212529;
            font-size: 1rem;
            font-weight: 500;
        }
        .section-title {
            background-color: #e7e6e6;
            padding: 12px 15px;
            font-weight: bold;
            border-left: 4px solid #FF6B35;
            margin-top: 25px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Penerimaan Barang NG</h5>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <!-- Section: Informasi Dasar -->
                    <div class="section-title">📋 Informasi Dasar</div>

                    <!-- Nomor Dokumen & Tanggal Input -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Nomor Dokumen</div>
                                <div class="field-value">{{ $penerimaanBarang->nomor_dokumen }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Tanggal Input</div>
                                <div class="field-value">{{ $penerimaanBarang->tanggal_input->format('d-m-Y H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Jenis & Lokasi -->
                    <div class="section-title">🏷️ Jenis & Lokasi</div>

                    <!-- Jenis Pengembalian & Lokasi Temuan -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Jenis Pengembalian</div>
                                <div class="field-value">
                                    @if ($penerimaanBarang->jenis_pengembalian === 'internal')
                                        <span class="badge bg-info">🔧 Internal</span>
                                    @elseif ($penerimaanBarang->jenis_pengembalian === 'customer_return')
                                        <span class="badge bg-warning">🔄 Customer Return</span>
                                    @else
                                        <span class="badge bg-primary">📦 Supplier</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Lokasi Temuan</div>
                                <div class="field-value">
                                    @if ($penerimaanBarang->lokasi_temuan === 'produksi')
                                        <span class="badge bg-danger">🏭 Produksi</span>
                                    @elseif ($penerimaanBarang->lokasi_temuan === 'gudang')
                                        <span class="badge bg-secondary">📦 Gudang</span>
                                    @elseif ($penerimaanBarang->lokasi_temuan === 'customer')
                                        <span class="badge bg-warning">👥 Customer</span>
                                    @else
                                        <span class="badge bg-info">🏢 Supplier</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Data Barang -->
                    <div class="section-title">📦 Data Barang</div>

                    <!-- Nama Barang & SKU -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Nama Barang</div>
                                <div class="field-value">{{ $penerimaanBarang->nama_barang }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">SKU / Kode Barang</div>
                                <div class="field-value">{{ $penerimaanBarang->sku ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Batch Number -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-box">
                                <div class="field-label">Batch Number</div>
                                <div class="field-value">{{ $penerimaanBarang->batch_number ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Quantity -->
                    <div class="section-title">📊 Quantity Information</div>

                    <!-- Quantity Baik & Rusak -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Qty Baik</div>
                                <div class="field-value"><span class="badge bg-success">{{ $penerimaanBarang->qty_baik }} unit</span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Qty Rusak (NG)</div>
                                <div class="field-value"><span class="badge bg-danger">{{ $penerimaanBarang->qty_rusak }} unit</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Petugas & Catatan -->
                    <div class="section-title">👤 Petugas & Catatan</div>

                    <!-- Penginput -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-box">
                                <div class="field-label">Penginput (Warehouse Officer)</div>
                                <div class="field-value">{{ $penerimaanBarang->penginput }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-box">
                                <div class="field-label">Keterangan</div>
                                <div class="field-value">{{ $penerimaanBarang->keterangan ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Lokasi Penyimpanan -->
                    <div class="section-title">📍 Lokasi Penyimpanan</div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-box">
                                <div class="field-label">Lokasi Gudang</div>
                                <div class="field-value">
                                    @if($penerimaanBarang->lokasiGudang)
                                        <a href="{{ route('master-lokasi-gudang.show', $penerimaanBarang->lokasiGudang->id) }}" 
                                           class="text-primary">
                                            <code>{{ $penerimaanBarang->lokasiGudang->lokasi_lengkap }}</code>
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $penerimaanBarang->lokasiGudang->nama_lokasi }}</small>
                                    @else
                                        <span class="text-danger">Lokasi belum ditentukan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="field-box">
                                <div class="field-label">Zone</div>
                                <div class="field-value">
                                    @if($penerimaanBarang->zone)
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $penerimaanBarang->zone)) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="field-box">
                                <div class="field-label">Rack</div>
                                <div class="field-value">{{ $penerimaanBarang->rack ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="field-box">
                                <div class="field-label">Bin</div>
                                <div class="field-value">{{ $penerimaanBarang->bin ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="field-box">
                                <div class="field-label">Lokasi Lengkap</div>
                                <div class="field-value">{{ $penerimaanBarang->lokasi_lengkap ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Status Penerimaan & QC -->
                    <div class="section-title">🔍 Status Penerimaan & Quality Control</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Status Penerimaan</div>
                                <div class="field-value">
                                    @php
                                        $statusClass = match($penerimaanBarang->status_penerimaan ?? 'diterima') {
                                            'diterima' => 'bg-primary',
                                            'sedang_inspeksi' => 'bg-warning',
                                            'selesai_inspeksi' => 'bg-info',
                                            'disimpan' => 'bg-success',
                                            'ditolak' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $penerimaanBarang->status_penerimaan ?? 'diterima')) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Ada Defect/NG?</div>
                                <div class="field-value">
                                    @if($penerimaanBarang->ada_defect)
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-triangle"></i> Ya, Ada Defect
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Tidak Ada Defect
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-box">
                                <div class="field-label">Hasil Inspeksi / Catatan QC</div>
                                <div class="field-value">{{ $penerimaanBarang->hasil_inspeksi ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    @if($penerimaanBarang->ada_defect && $penerimaanBarang->penyimpananNgs && $penerimaanBarang->penyimpananNgs->count() > 0)
                    <div class="alert alert-warning mt-3">
                        <h6><i class="bi bi-link-45deg"></i> Linked Penyimpanan NG:</h6>
                        <ul class="mb-0">
                            @foreach($penerimaanBarang->penyimpananNgs as $penyimpanan)
                            <li>
                                <a href="{{ route('penyimpanan-ng.show', $penyimpanan->id) }}">
                                    {{ $penyimpanan->nomor_storage }} - {{ $penyimpanan->nama_barang }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Section: Status & Approval -->
                    <div class="section-title">✅ Status & Approval</div>

                    <!-- Status -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-box">
                                <div class="field-label">Status</div>
                                <div class="field-value">{!! $penerimaanBarang->status_badge !!}</div>
                            </div>
                        </div>
                    </div>

                    @if ($penerimaanBarang->submitted_at)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-box">
                                    <div class="field-label">Submitted At</div>
                                    <div class="field-value">{{ $penerimaanBarang->submitted_at->format('d-m-Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($penerimaanBarang->approved_at)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-box">
                                    <div class="field-label">Approved At</div>
                                    <div class="field-value">{{ $penerimaanBarang->approved_at->format('d-m-Y H:i') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-box">
                                    <div class="field-label">Approved By</div>
                                    <div class="field-value">{{ $penerimaanBarang->approved_by }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Section: User Information -->
                    <div class="section-title">👥 User Information</div>

                    <!-- Dibuat Oleh & Created At -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Dibuat Oleh</div>
                                <div class="field-value">{{ $penerimaanBarang->user->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Created At</div>
                                <div class="field-value">{{ $penerimaanBarang->created_at->format('d-m-Y H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #e3e6f0;">
                                @if ($penerimaanBarang->status === 'draft')
                                    <a href="{{ route('penerimaan-barang.edit', $penerimaanBarang) }}" class="btn btn-warning btn-sm me-2">
                                        <i class="bx bx-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('penerimaan-barang.submit', $penerimaanBarang) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm me-2" onclick="return confirm('Submit untuk approval?')">
                                            <i class="bx bx-check"></i> Submit
                                        </button>
                                    </form>
                                    <form action="{{ route('penerimaan-barang.destroy', $penerimaanBarang) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm me-2" onclick="return confirm('Hapus record ini?')">
                                            <i class="bx bx-trash"></i> Delete
                                        </button>
                                    </form>
                                @elseif ($penerimaanBarang->status === 'submitted')
                                    <form action="{{ route('penerimaan-barang.approve', $penerimaanBarang) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm me-2" onclick="return confirm('Approve record ini?')">
                                            <i class="bx bx-check-circle"></i> Approve
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                                    <i class="bx bx-arrow-back"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
