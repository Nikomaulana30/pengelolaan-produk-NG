{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Penerimaan Barang')

{{-- Untuk menggunakan css --}}
@push('styles')
    {{-- contoh --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/static/css/pages/dashboard.css') }}"> --}}
@endpush

{{-- Isi content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Penerimaan Barang NG (Not Good)</h3>
                    <p class="text-subtitle text-muted">Form untuk mencatat penerimaan barang yang tidak memenuhi standar kualitas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <!-- Alert untuk success/error messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi Kesalahan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Penerimaan Barang NG</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('penerimaan-barang.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Nomor Dokumen -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_dokumen" class="form-label">Nomor Dokumen / No Laporan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nomor_dokumen') is-invalid @enderror" id="nomor_dokumen" name="nomor_dokumen" 
                                           value="{{ old('nomor_dokumen', 'NG-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)) }}" 
                                           readonly style="background-color: #f0f0f0;">
                                    @error('nomor_dokumen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Nomor dokumen akan di-generate otomatis</small>
                                </div>
                            </div>

                            <!-- Tanggal Input -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_input" class="form-label">Tanggal Input <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tanggal_input') is-invalid @enderror" id="tanggal_input" name="tanggal_input" 
                                           value="{{ old('tanggal_input', date('Y-m-d H:i:s')) }}" 
                                           readonly style="background-color: #f0f0f0;">
                                    @error('tanggal_input')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Tanggal otomatis saat form dibuat (Format: YYYY-MM-DD HH:MM:SS)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Jenis Pengembalian -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_pengembalian" class="form-label">Jenis Pengembalian <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis_pengembalian') is-invalid @enderror" id="jenis_pengembalian" name="jenis_pengembalian" required>
                                        <option value="">-- Pilih Jenis Pengembalian --</option>
                                        <option value="internal" @selected(old('jenis_pengembalian') == 'internal')>Internal (Produksi)</option>
                                        <option value="customer_return" @selected(old('jenis_pengembalian') == 'customer_return')>Customer Return</option>
                                        <option value="supplier" @selected(old('jenis_pengembalian') == 'supplier')>Supplier</option>
                                    </select>
                                    @error('jenis_pengembalian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Pilih sumber pengembalian barang</small>
                                </div>
                            </div>

                            <!-- Lokasi Temuan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lokasi_temuan" class="form-label">Lokasi Temuan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('lokasi_temuan') is-invalid @enderror" id="lokasi_temuan" name="lokasi_temuan" required>
                                        <option value="">-- Pilih Lokasi Temuan --</option>
                                        <option value="produksi" @selected(old('lokasi_temuan') == 'produksi')>Produksi</option>
                                        <option value="gudang" @selected(old('lokasi_temuan') == 'gudang')>Gudang</option>
                                        <option value="customer" @selected(old('lokasi_temuan') == 'customer')>Customer</option>
                                        <option value="supplier" @selected(old('lokasi_temuan') == 'supplier')>Supplier</option>
                                    </select>
                                    @error('lokasi_temuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Lokasi dimana barang NG ditemukan</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Nama Barang -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang" name="nama_barang" 
                                           value="{{ old('nama_barang') }}"
                                           placeholder="Masukkan nama barang" required>
                                    @error('nama_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- SKU -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sku" class="form-label">SKU / Kode Barang</label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" 
                                           value="{{ old('sku') }}"
                                           placeholder="Masukkan SKU">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Batch Number -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="batch_number" class="form-label">Batch Number</label>
                                    <input type="text" class="form-control @error('batch_number') is-invalid @enderror" id="batch_number" name="batch_number" 
                                           value="{{ old('batch_number') }}"
                                           placeholder="Masukkan batch number">
                                    @error('batch_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Quantity Baik -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qty_baik" class="form-label">Quantity Baik <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('qty_baik') is-invalid @enderror" id="qty_baik" name="qty_baik" 
                                           value="{{ old('qty_baik', 0) }}"
                                           placeholder="0" min="0" required>
                                    @error('qty_baik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Jumlah barang yang masih layak pakai</small>
                                </div>
                            </div>

                            <!-- Quantity Rusak -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qty_rusak" class="form-label">Quantity Rusak (NG) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('qty_rusak') is-invalid @enderror" id="qty_rusak" name="qty_rusak" 
                                           value="{{ old('qty_rusak') }}"
                                           placeholder="0" min="1" required>
                                    @error('qty_rusak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Jumlah barang yang tidak memenuhi standar</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Petugas Warehouse -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="penginput" class="form-label">Petugas Warehouse (Penginput) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="penginput" name="penginput" 
                                           value="{{ auth()->user()->name }}" 
                                           readonly style="background-color: #f0f0f0;">
                                    <small class="text-muted">Nama petugas yang menginput data</small>
                                </div>
                            </div>

                            <!-- Keterangan Tambahan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" 
                                              rows="3" placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Lokasi Penyimpanan Section -->
                        <hr class="my-4">
                        <h5 class="text-success mb-3">
                            <i class="bi bi-geo-alt-fill me-2"></i>Lokasi Penyimpanan Barang
                        </h5>
                        
                        <div class="row">
                            <!-- Master Lokasi Gudang Dropdown -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="master_lokasi_gudang_id" class="form-label">
                                        Pilih Lokasi Penyimpanan
                                    </label>
                                    <select name="master_lokasi_gudang_id" id="master_lokasi_gudang_id" 
                                            class="form-select @error('master_lokasi_gudang_id') is-invalid @enderror" 
                                            onchange="populateLokasiPenerimaan()">
                                        <option value="">-- Pilih Lokasi Penyimpanan (Opsional) --</option>
                                        @if(isset($lokasiGudangs))
                                            @foreach($lokasiGudangs as $lokasi)
                                                @php
                                                    $currentUsage = $lokasi->penyimpananNgs->sum('qty_sisa');
                                                    $utilization = $lokasi->kapasitas_max > 0 
                                                        ? round(($currentUsage / $lokasi->kapasitas_max) * 100)
                                                        : 0;
                                                @endphp
                                                <option value="{{ $lokasi->id }}" 
                                                        data-zone="{{ $lokasi->zone }}"
                                                        data-rack="{{ $lokasi->rack }}"
                                                        data-bin="{{ $lokasi->bin }}"
                                                        data-lokasi="{{ $lokasi->lokasi_lengkap }}"
                                                        data-kapasitas="{{ $lokasi->kapasitas_max }}"
                                                        data-current="{{ $currentUsage }}"
                                                        {{ old('master_lokasi_gudang_id') == $lokasi->id ? 'selected' : '' }}>
                                                    {{ $lokasi->lokasi_lengkap }} - {{ $lokasi->nama_lokasi }}
                                                    ({{ number_format($currentUsage) }}/{{ number_format($lokasi->kapasitas_max) }} - {{ $utilization }}% used)
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-muted">Lokasi dimana barang akan disimpan setelah diterima</small>
                                    @error('master_lokasi_gudang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Detail Lokasi (Auto-filled, Read-only) -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Zone</label>
                                    <input type="text" id="zone_display" class="form-control bg-light" readonly placeholder="-">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Rack</label>
                                    <input type="text" id="rack_display" class="form-control bg-light" readonly placeholder="-">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Bin</label>
                                    <input type="text" id="bin_display" class="form-control bg-light" readonly placeholder="-">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Lokasi Lengkap</label>
                                    <input type="text" id="lokasi_lengkap_display" class="form-control bg-light" readonly placeholder="-">
                                </div>
                            </div>
                        </div>

                        <!-- Capacity Warning -->
                        <div id="capacity_warning_penerimaan" class="alert alert-warning mt-3" style="display: none;">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <span id="capacity_message_penerimaan"></span>
                        </div>

                        <!-- Status Penerimaan & QC Section -->
                        <hr class="my-4">
                        <h5 class="text-info mb-3">
                            <i class="bi bi-clipboard-check me-2"></i>Status & Quality Control
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_penerimaan" class="form-label">
                                        Status Penerimaan <span class="text-danger">*</span>
                                    </label>
                                    <select name="status_penerimaan" id="status_penerimaan" 
                                            class="form-select @error('status_penerimaan') is-invalid @enderror" required>
                                        <option value="diterima" {{ old('status_penerimaan', 'diterima') == 'diterima' ? 'selected' : '' }}>
                                            Diterima (Belum Inspeksi)
                                        </option>
                                        <option value="sedang_inspeksi" {{ old('status_penerimaan') == 'sedang_inspeksi' ? 'selected' : '' }}>
                                            Sedang Inspeksi
                                        </option>
                                        <option value="selesai_inspeksi" {{ old('status_penerimaan') == 'selesai_inspeksi' ? 'selected' : '' }}>
                                            Selesai Inspeksi
                                        </option>
                                        <option value="disimpan" {{ old('status_penerimaan') == 'disimpan' ? 'selected' : '' }}>
                                            Sudah Disimpan
                                        </option>
                                        <option value="ditolak" {{ old('status_penerimaan') == 'ditolak' ? 'selected' : '' }}>
                                            Ditolak
                                        </option>
                                    </select>
                                    @error('status_penerimaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ada_defect" class="form-label">Ada Defect/NG?</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" 
                                               id="ada_defect" name="ada_defect" value="1"
                                               {{ old('ada_defect') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ada_defect">
                                            Ya, barang memiliki defect
                                        </label>
                                    </div>
                                    <small class="text-muted">Jika ada defect, akan otomatis create record Penyimpanan NG</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="hasil_inspeksi" class="form-label">
                                        Hasil Inspeksi / Catatan QC
                                    </label>
                                    <textarea name="hasil_inspeksi" id="hasil_inspeksi" rows="3"
                                              class="form-control @error('hasil_inspeksi') is-invalid @enderror"
                                              placeholder="Catatan hasil inspeksi quality control...">{{ old('hasil_inspeksi') }}</textarea>
                                    @error('hasil_inspeksi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-save"></i> Simpan Data
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Form
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Tabel Data Penerimaan Barang -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Riwayat Penerimaan Barang NG</h4>
                    <span class="badge bg-primary">Total: {{ $barangs->total() }} Data</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Dokumen</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Pengembalian</th>
                                    <th>Nama Barang</th>
                                    <th>Qty Baik</th>
                                    <th>Qty Rusak</th>
                                    <th>Lokasi Temuan</th>
                                    <th>Penginput</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangs as $index => $barang)
                                    <tr>
                                        <td>{{ $barangs->firstItem() + $index }}</td>
                                        <td><strong>{{ $barang->nomor_dokumen }}</strong></td>
                                        <td>{{ $barang->tanggal_input->format('d-m-Y H:i') }}</td>
                                        <td>
                                            @if($barang->jenis_pengembalian == 'internal')
                                                <span class="badge bg-info">Internal</span>
                                            @elseif($barang->jenis_pengembalian == 'customer_return')
                                                <span class="badge bg-warning">Customer</span>
                                            @else
                                                <span class="badge bg-secondary">Supplier</span>
                                            @endif
                                        </td>
                                        <td>{{ $barang->nama_barang }}</td>
                                        <td><span class="badge bg-success">{{ $barang->qty_baik }}</span></td>
                                        <td><span class="badge bg-danger">{{ $barang->qty_rusak }}</span></td>
                                        <td>{{ ucfirst($barang->lokasi_temuan) }}</td>
                                        <td>{{ $barang->penginput }}</td>
                                        <td>
                                            @if($barang->status == 'draft')
                                                <span class="badge bg-secondary">Draft</span>
                                            @elseif($barang->status == 'submitted')
                                                <span class="badge bg-primary">Submitted</span>
                                            @elseif($barang->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('penerimaan-barang.show', $barang->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($barang->status == 'draft')
                                                    <a href="{{ route('penerimaan-barang.edit', $barang->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('penerimaan-barang.destroy', $barang->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Belum ada data penerimaan barang
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($barangs->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $barangs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

@endsection

{{-- Untuk menggunakan js --}}
@push('scripts')
    <script>
        // Set tanggal input ke current datetime dengan format Y-m-d H:i:s
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalInput = document.getElementById('tanggal_input');
            const now = new Date();
            
            // Format: YYYY-MM-DD HH:MM:SS
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            tanggalInput.value = formattedDateTime;

            // Handle ada_defect checkbox toggle
            const adaDefectCheckbox = document.getElementById('ada_defect');
            const hasilInspeksiGroup = document.querySelector('#hasil_inspeksi').closest('.form-group');
            
            // Function to toggle hasil inspeksi visibility and highlighting
            function toggleDefectFields() {
                if (adaDefectCheckbox.checked) {
                    // Highlight the hasil_inspeksi field when defect is checked
                    hasilInspeksiGroup.style.border = '2px solid #dc3545';
                    hasilInspeksiGroup.style.padding = '15px';
                    hasilInspeksiGroup.style.borderRadius = '8px';
                    hasilInspeksiGroup.style.backgroundColor = '#fff3cd';
                    
                    // Make label bold and add icon
                    const label = hasilInspeksiGroup.querySelector('label');
                    label.innerHTML = '<i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Hasil Inspeksi / Catatan QC <span class="text-danger">*</span>';
                    
                    // Make field required
                    document.getElementById('hasil_inspeksi').setAttribute('required', 'required');
                } else {
                    // Remove highlighting
                    hasilInspeksiGroup.style.border = '';
                    hasilInspeksiGroup.style.padding = '';
                    hasilInspeksiGroup.style.borderRadius = '';
                    hasilInspeksiGroup.style.backgroundColor = '';
                    
                    // Reset label
                    const label = hasilInspeksiGroup.querySelector('label');
                    label.innerHTML = 'Hasil Inspeksi / Catatan QC';
                    
                    // Remove required
                    document.getElementById('hasil_inspeksi').removeAttribute('required');
                }
            }
            
            // Initial state on page load
            toggleDefectFields();
            
            // Listen to checkbox changes
            adaDefectCheckbox.addEventListener('change', toggleDefectFields);
        });

        // Auto-populate lokasi penyimpanan dari master lokasi gudang
        function populateLokasiPenerimaan() {
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
                document.getElementById('zone_display').value = zone ? zone.replace('_', ' ').toUpperCase() : '-';
                document.getElementById('rack_display').value = rack || '-';
                document.getElementById('bin_display').value = bin || '-';
                document.getElementById('lokasi_lengkap_display').value = lokasi || '-';
                
                // Check capacity and show warning
                const utilization = (current / kapasitas) * 100;
                const warningDiv = document.getElementById('capacity_warning_penerimaan');
                const messageSpan = document.getElementById('capacity_message_penerimaan');
                
                if (utilization > 90) {
                    messageSpan.textContent = `⚠️ Lokasi hampir penuh! (${utilization.toFixed(1)}% terisi). Pertimbangkan lokasi lain.`;
                    warningDiv.className = 'alert alert-danger mt-3';
                    warningDiv.style.display = 'block';
                } else if (utilization > 75) {
                    messageSpan.textContent = `Lokasi ${utilization.toFixed(1)}% terisi`;
                    warningDiv.className = 'alert alert-warning mt-3';
                    warningDiv.style.display = 'block';
                } else {
                    warningDiv.style.display = 'none';
                }
            } else {
                // Clear all fields
                document.getElementById('zone_display').value = '-';
                document.getElementById('rack_display').value = '-';
                document.getElementById('bin_display').value = '-';
                document.getElementById('lokasi_lengkap_display').value = '-';
                document.getElementById('capacity_warning_penerimaan').style.display = 'none';
            }
        }
    </script>
@endpush