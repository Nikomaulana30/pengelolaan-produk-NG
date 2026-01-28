{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Penyimpanan NG')

{{-- Untuk menggunakan css --}}
@push('styles')
    <style>
        .section-header {
            background-color: #E7E6E6;
            padding: 10px 15px;
            font-weight: bold;
            border-left: 4px solid #FF6B35;
            margin-top: 20px;
            margin-bottom: 15px;
        }
    </style>
@endpush

{{-- Isi content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Penyimpanan NG (Storage Management)</h3>
                    <p class="text-subtitle text-muted">Form untuk mengelola penyimpanan dan tracking barang NG</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        {{-- Alert Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Validasi Error!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Penyimpanan Barang NG</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('penyimpanan-ng.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Nomor Storage -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_storage" class="form-label">Nomor Storage <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_storage" name="nomor_storage" 
                                           value="STR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}" 
                                           readonly style="background-color: #f0f0f0;">
                                    <small class="text-muted">Nomor storage akan di-generate otomatis</small>
                                </div>
                            </div>

                            <!-- Tanggal Penyimpanan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_penyimpanan" class="form-label">Tanggal Penyimpanan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tanggal_penyimpanan" name="tanggal_penyimpanan" 
                                           value="{{ date('Y-m-d H:i:s') }}" 
                                           readonly style="background-color: #f0f0f0;">
                                    <small class="text-muted">Format: Y-m-d H:i:s</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Nomor Referensi -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_referensi" class="form-label">Nomor Referensi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nomor_referensi') is-invalid @enderror" 
                                           id="nomor_referensi" name="nomor_referensi" 
                                           value="{{ old('nomor_referensi') }}"
                                           placeholder="Contoh: PB-20251216-0001" required>
                                    <small class="text-muted">Nomor rujukan dari dokumen terkait</small>
                                    @error('nomor_referensi')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Nama Barang -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" 
                                           id="nama_barang" name="nama_barang" 
                                           value="{{ old('nama_barang') }}"
                                           placeholder="Masukkan nama barang" required>
                                    <small class="text-muted">Nama barang yang akan disimpan</small>
                                    @error('nama_barang')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="section-header">📍 Lokasi Penyimpanan</div>

                        <div class="row">
                            <!-- Master Lokasi Gudang Dropdown -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="master_lokasi_gudang_id" class="form-label">
                                        Pilih Lokasi Gudang <span class="text-danger">*</span>
                                    </label>
                                    <select name="master_lokasi_gudang_id" id="master_lokasi_gudang_id" 
                                            class="form-select @error('master_lokasi_gudang_id') is-invalid @enderror" 
                                            required onchange="populateLokasiDetail()">
                                        <option value="">-- Pilih Lokasi Penyimpanan --</option>
                                        @foreach($lokasiGudangs as $lokasi)
                                            @php
                                                $currentQty = $lokasi->penyimpananNgs->sum('qty_awal');
                                                $util = $lokasi->kapasitas_max > 0 
                                                    ? round(($currentQty / $lokasi->kapasitas_max) * 100)
                                                    : 0;
                                            @endphp
                                            <option value="{{ $lokasi->id }}" 
                                                    data-zone="{{ $lokasi->zone }}"
                                                    data-rack="{{ $lokasi->rack }}"
                                                    data-bin="{{ $lokasi->bin }}"
                                                    data-lokasi="{{ $lokasi->lokasi_lengkap }}"
                                                    data-kapasitas="{{ $lokasi->kapasitas_max }}"
                                                    data-current="{{ $currentQty }}"
                                                    {{ old('master_lokasi_gudang_id') == $lokasi->id ? 'selected' : '' }}>
                                                {{ $lokasi->lokasi_lengkap }} - {{ $lokasi->nama_lokasi }}
                                                ({{ number_format($currentQty) }}/{{ number_format($lokasi->kapasitas_max) }} - {{ $util }}% used)
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Pilih lokasi dari master data, detail akan terisi otomatis</small>
                                    @error('master_lokasi_gudang_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Detail Lokasi (Auto-filled, Read-only) -->
                        <div class="row">
                            <!-- Zone -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Zone</label>
                                    <input type="text" id="zone_display" class="form-control bg-light" readonly placeholder="Auto dari lokasi">
                                    <input type="hidden" name="zone" id="zone">
                                </div>
                            </div>

                            <!-- Rack -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Rack</label>
                                    <input type="text" id="rack_display" class="form-control bg-light" readonly placeholder="Auto dari lokasi">
                                    <input type="hidden" name="rack" id="rack">
                                </div>
                            </div>

                            <!-- Bin -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Bin</label>
                                    <input type="text" id="bin_display" class="form-control bg-light" readonly placeholder="Auto dari lokasi">
                                    <input type="hidden" name="bin" id="bin">
                                </div>
                            </div>

                            <!-- Lokasi Lengkap -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Lokasi Lengkap</label>
                                    <input type="text" id="lokasi_lengkap_display" class="form-control bg-light" readonly placeholder="Auto dari lokasi">
                                    <input type="hidden" name="lokasi_lengkap" id="lokasi_lengkap">
                                </div>
                            </div>
                        </div>

                        <!-- Capacity Warning -->
                        <div id="capacity_warning" class="alert alert-warning" style="display: none;">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <span id="capacity_message"></span>
                        </div>

                        <div class="section-header">📦 Quantity Tracking</div>

                        <div class="row">
                            <!-- Quantity Awal -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="qty_awal" class="form-label">Quantity Awal (NG) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('qty_awal') is-invalid @enderror" 
                                           id="qty_awal" name="qty_awal" 
                                           value="{{ old('qty_awal') }}"
                                           placeholder="0" min="1" required onchange="calculateSelisih()">
                                    <small class="text-muted">Jumlah barang NG awal masuk storage</small>
                                    @error('qty_awal')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Quantity Setelah Perbaikan -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="qty_setelah_perbaikan" class="form-label">Quantity Setelah Perbaikan</label>
                                    <input type="number" class="form-control @error('qty_setelah_perbaikan') is-invalid @enderror" 
                                           id="qty_setelah_perbaikan" name="qty_setelah_perbaikan" 
                                           value="{{ old('qty_setelah_perbaikan') }}"
                                           placeholder="0" min="0" onchange="calculateSelisih()">
                                    <small class="text-muted">Jumlah barang yang lolos perbaikan</small>
                                    @error('qty_setelah_perbaikan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Selisih -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="selisih_qty" class="form-label">Selisih Quantity</label>
                                    <input type="number" class="form-control" id="selisih_qty" name="selisih_qty" 
                                           readonly style="background-color: #f0f0f0;" placeholder="0">
                                    <small class="text-muted">Otomatis: Qty Awal - Qty Setelah Perbaikan</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Status Barang -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_barang" class="form-label">Status Barang <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status_barang') is-invalid @enderror" 
                                            id="status_barang" name="status_barang" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="disimpan" {{ old('status_barang') == 'disimpan' ? 'selected' : '' }}>📦 Disimpan</option>
                                        <option value="dalam_perbaikan" {{ old('status_barang') == 'dalam_perbaikan' ? 'selected' : '' }}>🔧 Dalam Perbaikan</option>
                                        <option value="menunggu_approval" {{ old('status_barang') == 'menunggu_approval' ? 'selected' : '' }}>⏳ Menunggu Approval</option>
                                        <option value="siap_dipindahkan" {{ old('status_barang') == 'siap_dipindahkan' ? 'selected' : '' }}>✓ Siap Dipindahkan</option>
                                        <option value="dipindahkan" {{ old('status_barang') == 'dipindahkan' ? 'selected' : '' }}>↗ Sudah Dipindahkan</option>
                                    </select>
                                    <small class="text-muted">Status kondisi barang saat ini</small>
                                    @error('status_barang')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Catatan -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="catatan" class="form-label">Catatan</label>
                                    <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                              id="catatan" name="catatan" 
                                              rows="3" placeholder="Catatan tambahan mengenai penyimpanan...">{{ old('catatan') }}</textarea>
                                    <small class="text-muted">Informasi tambahan (opsional)</small>
                                    @error('catatan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bx bx-save"></i> Simpan Data Storage
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bx bx-arrow-clockwise"></i> Reset Form
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Data Storage Overview -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Data Penyimpanan NG</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Storage</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Lokasi</th>
                                    <th>Qty Awal</th>
                                    <th>Qty Setelah</th>
                                    <th>Selisih</th>
                                    <th>Status Barang</th>
                                    <th>Status Approval</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penyimpananNgs as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ($penyimpananNgs->currentPage() - 1) * $penyimpananNgs->perPage() }}</td>
                                        <td><strong>{{ $item->nomor_storage }}</strong></td>
                                        <td>{{ $item->tanggal_penyimpanan->format('d-m-Y H:i') }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td><small>{{ $item->lokasi_lengkap }}</small></td>
                                        <td><span class="badge bg-info">{{ $item->qty_awal }}</span></td>
                                        <td>{{ $item->qty_setelah_perbaikan ?? '-' }}</td>
                                        <td>{{ $item->selisih_qty ?? '-' }}</td>
                                        <td>{!! $item->barang_status_badge !!}</td>
                                        <td>{!! $item->status_badge !!}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <!-- View Detail -->
                                                <a href="{{ route('penyimpanan-ng.show', $item) }}" 
                                                   class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <!-- Edit (only draft) -->
                                                @if ($item->status === 'draft')
                                                    <a href="{{ route('penyimpanan-ng.edit', $item) }}" 
                                                       class="btn btn-sm btn-warning text-white" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                @endif

                                                <!-- Submit Button (only draft) -->
                                                @if ($item->status === 'draft')
                                                    <form action="{{ route('penyimpanan-ng.submit', $item) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                                title="Submit untuk Approval"
                                                                onclick="return confirm('Yakin ingin submit data ini?')">
                                                            <i class="bi bi-send-check"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- Approve Button (only submitted) -->
                                                @if ($item->status === 'submitted')
                                                    <form action="{{ route('penyimpanan-ng.approve', $item) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" 
                                                                title="Approve Data"
                                                                onclick="return confirm('Yakin ingin approve data ini?')">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- Delete (only draft) -->
                                                @if ($item->status === 'draft')
                                                    <form action="{{ route('penyimpanan-ng.destroy', $item) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                title="Hapus"
                                                                onclick="return confirm('Yakin ingin hapus data ini?')">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">
                                            <em>Belum ada data penyimpanan</em>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($penyimpananNgs->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $penyimpananNgs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <script>
        // Populate lokasi detail from dropdown
        function populateLokasiDetail() {
            const select = document.getElementById('master_lokasi_gudang_id');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                // Get data from selected option
                const zone = option.dataset.zone;
                const rack = option.dataset.rack;
                const bin = option.dataset.bin;
                const lokasi = option.dataset.lokasi;
                const kapasitas = parseFloat(option.dataset.kapasitas);
                const current = parseFloat(option.dataset.current);
                
                // Populate display fields
                document.getElementById('zone_display').value = zone.replace(/_/g, ' ').toUpperCase();
                document.getElementById('rack_display').value = rack;
                document.getElementById('bin_display').value = bin;
                document.getElementById('lokasi_lengkap_display').value = lokasi;
                
                // Populate hidden fields for submission
                document.getElementById('zone').value = zone;
                document.getElementById('rack').value = rack;
                document.getElementById('bin').value = bin;
                document.getElementById('lokasi_lengkap').value = lokasi;
                
                // Check capacity and show warning
                const utilization = (current / kapasitas) * 100;
                const warningDiv = document.getElementById('capacity_warning');
                const messageSpan = document.getElementById('capacity_message');
                
                if (utilization > 90) {
                    messageSpan.textContent = `⚠️ Lokasi hampir penuh! (${utilization.toFixed(1)}% terisi). Pertimbangkan lokasi lain.`;
                    warningDiv.className = 'alert alert-danger';
                    warningDiv.style.display = 'block';
                } else if (utilization > 75) {
                    messageSpan.textContent = `Lokasi ${utilization.toFixed(1)}% terisi`;
                    warningDiv.className = 'alert alert-warning';
                    warningDiv.style.display = 'block';
                } else {
                    warningDiv.style.display = 'none';
                }
            } else {
                // Clear all fields
                document.getElementById('zone_display').value = '';
                document.getElementById('rack_display').value = '';
                document.getElementById('bin_display').value = '';
                document.getElementById('lokasi_lengkap_display').value = '';
                document.getElementById('zone').value = '';
                document.getElementById('rack').value = '';
                document.getElementById('bin').value = '';
                document.getElementById('lokasi_lengkap').value = '';
                document.getElementById('capacity_warning').style.display = 'none';
            }
        }

        // Auto-generate lokasi lengkap (legacy - not used anymore)
        function updateLokasiLengkap() {
            const zone = document.getElementById('zone');
            const rack = document.getElementById('rack').value;
            const bin = document.getElementById('bin').value;
            const lokasiLengkap = document.getElementById('lokasi_lengkap');
            
            if (zone.value && rack && bin) {
                const zoneMap = {
                    'zona_a': 'ZA',
                    'zona_b': 'ZB',
                    'zona_c': 'ZC',
                    'zona_d': 'ZD',
                    'zona_e': 'ZE'
                };
                const zoneCode = zoneMap[zone.value] || zone.value;
                lokasiLengkap.value = `${zoneCode}-${rack}-${bin}`;
            } else {
                lokasiLengkap.value = '';
            }
        }

        document.getElementById('zone').addEventListener('change', updateLokasiLengkap);
        document.getElementById('rack').addEventListener('input', updateLokasiLengkap);
        document.getElementById('bin').addEventListener('input', updateLokasiLengkap);

        // Auto-calculate selisih quantity
        function calculateSelisih() {
            const qtyAwal = parseInt(document.getElementById('qty_awal').value) || 0;
            const qtySetelahPerbaikan = parseInt(document.getElementById('qty_setelah_perbaikan').value) || 0;
            const selisih = qtyAwal - qtySetelahPerbaikan;
            
            document.getElementById('selisih_qty').value = selisih;
        }

        document.getElementById('qty_awal').addEventListener('input', calculateSelisih);
        document.getElementById('qty_setelah_perbaikan').addEventListener('input', calculateSelisih);
    </script>
@endpush


