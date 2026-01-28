@extends('layouts.app')

@section('content')
<style>
    .section-title {
        margin-top: 25px;
        margin-bottom: 15px;
        padding: 10px 0;
        border-bottom: 2px solid #007bff;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .field-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 12px;
        min-height: 70px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .field-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .field-value {
        font-size: 15px;
        color: #212529;
        font-weight: 500;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bx bx-box"></i> Detail Penyimpanan NG</h5>
                    <span class="badge bg-light text-dark">{{ ucfirst($penyimpananNg->status) }}</span>
                </div>

                <div class="card-body">
                    <!-- Section: Storage Numbers & Dates -->
                    <div class="section-title">📦 Storage Information</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Nomor Storage</div>
                                <div class="field-value">{{ $penyimpananNg->nomor_storage }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Tanggal Penyimpanan</div>
                                <div class="field-value">{{ $penyimpananNg->tanggal_penyimpanan->format('d-m-Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Nomor Referensi</div>
                                <div class="field-value">{{ $penyimpananNg->nomor_referensi ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Status Barang</div>
                                <div class="field-value">
                                    @switch($penyimpananNg->status_barang)
                                        @case('disimpan')
                                            <span class="badge bg-primary">📦 Disimpan</span>
                                            @break
                                        @case('dalam_perbaikan')
                                            <span class="badge bg-warning">🔧 Dalam Perbaikan</span>
                                            @break
                                        @case('menunggu_approval')
                                            <span class="badge bg-info">⏳ Menunggu Approval</span>
                                            @break
                                        @case('siap_dipindahkan')
                                            <span class="badge bg-success">✓ Siap Dipindahkan</span>
                                            @break
                                        @case('dipindahkan')
                                            <span class="badge bg-secondary">↗ Sudah Dipindahkan</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $penyimpananNg->status_barang ?? '-' }}</span>
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Item Information -->
                    <div class="section-title">📋 Item Details</div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-box">
                                <div class="field-label">Nama Barang</div>
                                <div class="field-value">{{ $penyimpananNg->nama_barang }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Location Information -->
                    <div class="section-title">📍 Location & Zone</div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="field-box">
                                <div class="field-label">Lokasi Gudang</div>
                                <div class="field-value">
                                    @if($penyimpananNg->lokasiGudang)
                                        <a href="{{ route('master-lokasi-gudang.show', $penyimpananNg->lokasiGudang->id) }}" 
                                           class="text-primary text-decoration-none">
                                            <i class="bi bi-geo-alt-fill"></i> 
                                            <code>{{ $penyimpananNg->lokasiGudang->lokasi_lengkap }}</code> - {{ $penyimpananNg->lokasiGudang->nama_lokasi }}
                                        </a>
                                    @else
                                        <span class="text-danger">
                                            <i class="bi bi-exclamation-triangle"></i> 
                                            Tidak terhubung ke Master Lokasi
                                        </span>
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
                                    <span class="badge bg-info">{{ strtoupper($penyimpananNg->zone) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="field-box">
                                <div class="field-label">Rack</div>
                                <div class="field-value">{{ $penyimpananNg->rack }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="field-box">
                                <div class="field-label">Bin</div>
                                <div class="field-value">{{ $penyimpananNg->bin }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="field-box">
                                <div class="field-label">Lokasi Lengkap</div>
                                <div class="field-value">
                                    <code>{{ $penyimpananNg->lokasi_lengkap }}</code>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Relocation & Disposition -->
                    @if ($penyimpananNg->master_disposisi_id || $penyimpananNg->zone_tujuan || $penyimpananNg->tanggal_relokasi)
                        <div class="section-title">📍 Relokasi & Disposisi</div>

                        <div class="row">
                            @if ($penyimpananNg->master_disposisi_id && $penyimpananNg->disposisi)
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <div class="field-label">Master Disposisi</div>
                                        <div class="field-value">
                                            <strong>{{ $penyimpananNg->disposisi->nama_disposisi }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $penyimpananNg->disposisi->jenis_tindakan }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($penyimpananNg->zone_tujuan)
                                <div class="col-md-3">
                                    <div class="field-box">
                                        <div class="field-label">Zone Tujuan</div>
                                        <div class="field-value">
                                            <span class="badge bg-info">{{ strtoupper($penyimpananNg->zone_tujuan) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($penyimpananNg->rack_tujuan)
                                <div class="col-md-3">
                                    <div class="field-box">
                                        <div class="field-label">Rack Tujuan</div>
                                        <div class="field-value">{{ $penyimpananNg->rack_tujuan }}</div>
                                    </div>
                                </div>
                            @endif

                            @if ($penyimpananNg->bin_tujuan)
                                <div class="col-md-3">
                                    <div class="field-box">
                                        <div class="field-label">Bin Tujuan</div>
                                        <div class="field-value">{{ $penyimpananNg->bin_tujuan }}</div>
                                    </div>
                                </div>
                            @endif

                            @if ($penyimpananNg->lokasi_lengkap_tujuan)
                                <div class="col-md-3">
                                    <div class="field-box">
                                        <div class="field-label">Lokasi Lengkap Tujuan</div>
                                        <div class="field-value">{{ $penyimpananNg->lokasi_lengkap_tujuan }}</div>
                                    </div>
                                </div>
                            @endif

                            @if ($penyimpananNg->tanggal_relokasi)
                                <div class="col-md-3">
                                    <div class="field-box">
                                        <div class="field-label">Tanggal Relokasi</div>
                                        <div class="field-value">{{ $penyimpananNg->tanggal_relokasi->format('d-m-Y') }}</div>
                                    </div>
                                </div>
                            @endif

                            @if ($penyimpananNg->alasan_relokasi)
                                <div class="col-md-12">
                                    <div class="field-box">
                                        <div class="field-label">Alasan Relokasi</div>
                                        <div class="field-value">{{ $penyimpananNg->alasan_relokasi }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Section: Quantity Information -->
                    <div class="section-title">📊 Quantity Details</div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="field-box">
                                <div class="field-label">Quantity Awal</div>
                                <div class="field-value">{{ $penyimpananNg->qty_awal }} unit</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="field-box">
                                <div class="field-label">Quantity Setelah Perbaikan</div>
                                <div class="field-value">{{ $penyimpananNg->qty_setelah_perbaikan }} unit</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="field-box">
                                <div class="field-label">Selisih Quantity</div>
                                <div class="field-value">
                                    @if ($penyimpananNg->selisih_qty > 0)
                                        <span class="badge bg-success">+{{ $penyimpananNg->selisih_qty }}</span>
                                    @elseif ($penyimpananNg->selisih_qty < 0)
                                        <span class="badge bg-danger">{{ $penyimpananNg->selisih_qty }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Notes -->
                    @if ($penyimpananNg->catatan)
                        <div class="section-title">📝 Notes</div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-box">
                                    <div class="field-label">Catatan</div>
                                    <div class="field-value">{{ $penyimpananNg->catatan }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Section: Approval Status -->
                    <div class="section-title">✅ Approval Status</div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-box">
                                <div class="field-label">Current Status</div>
                                <div class="field-value">
                                    {!! $penyimpananNg->status_badge !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($penyimpananNg->submitted_at)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-box">
                                    <div class="field-label">Submitted At</div>
                                    <div class="field-value">{{ $penyimpananNg->submitted_at->format('d-m-Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($penyimpananNg->approved_at)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-box">
                                    <div class="field-label">Approved At</div>
                                    <div class="field-value">{{ $penyimpananNg->approved_at->format('d-m-Y H:i') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-box">
                                    <div class="field-label">Approved By</div>
                                    <div class="field-value">{{ $penyimpananNg->approved_by }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Section: User Information -->
                    <div class="section-title">👥 User Information</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Created By</div>
                                <div class="field-value">{{ $penyimpananNg->user->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-box">
                                <div class="field-label">Created At</div>
                                <div class="field-value">{{ $penyimpananNg->created_at->format('d-m-Y H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #e3e6f0;">
                                @if ($penyimpananNg->status === 'draft')
                                    <a href="{{ route('penyimpanan-ng.edit', $penyimpananNg) }}" class="btn btn-warning btn-sm me-2">
                                        <i class="bx bx-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('penyimpanan-ng.submit', $penyimpananNg) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm me-2" onclick="return confirm('Submit untuk approval?')">
                                            <i class="bx bx-check"></i> Submit
                                        </button>
                                    </form>
                                    <form action="{{ route('penyimpanan-ng.destroy', $penyimpananNg) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm me-2" onclick="return confirm('Hapus record ini?')">
                                            <i class="bx bx-trash"></i> Delete
                                        </button>
                                    </form>
                                @elseif ($penyimpananNg->status === 'submitted')
                                    <form action="{{ route('penyimpanan-ng.approve', $penyimpananNg) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm me-2" onclick="return confirm('Approve record ini?')">
                                            <i class="bx bx-check-circle"></i> Approve
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('penyimpanan-ng.index') }}" class="btn btn-secondary btn-sm">
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
