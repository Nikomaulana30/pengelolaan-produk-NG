# 🎯 Master Disposisi - Form UI untuk Relokasi

## 📌 Penjelasan
Master Disposisi sekarang harus menampilkan **Section Relokasi** dimana admin dapat menentukan:
- **TUJUAN** barang akan dipindahkan ke mana (Zone, Rack, Bin)
- **ALASAN** barang dipindahkan
- **TANGGAL** barang dipindahkan

Sama seperti yang ada di **Penyimpanan NG Edit Form**, tapi ini di level **Master Disposisi** (untuk default/template).

---

## 📊 Struktur Form yang Ditambahkan

### Di Master Disposisi Edit (master-disposisi-edit.blade.php)

```
✅ SUDAH ADA:
├─ Section: Identifikasi Disposisi
│  ├─ Kode Disposisi
│  └─ Nama Disposisi
├─ Section: Jenis Tindakan
│  ├─ Jenis Tindakan (dropdown)
│  └─ Perlu Approval (checkbox/select)
├─ Section: Deskripsi & Catatan
├─ Section: Status
│  └─ Aktifkan Disposisi (active/inactive)

❌ PERLU DITAMBAH:
└─ Section: LOKASI TUJUAN RELOKASI
   ├─ Zone Tujuan (dropdown: zona_a, zona_b, dst)
   ├─ Rack Tujuan (text input)
   ├─ Bin Tujuan (text input)
   └─ Lokasi Lengkap Tujuan (auto-generated, disabled)
```

---

## 🎨 Visual Mockup

```html
<!-- NEW SECTION: Lokasi Tujuan Relokasi -->
<div class="form-section mb-4">
    <h6 class="form-section-title mb-3">📍 Lokasi Tujuan Relokasi</h6>
    
    <div class="form-group-box mb-3">
        <p class="text-muted small mb-3">
            Tentukan lokasi default tujuan untuk barang dengan disposisi ini
        </p>
        
        <div class="row">
            <!-- Zone Tujuan -->
            <div class="col-md-3">
                <label for="zone_tujuan" class="form-label">Zone Tujuan</label>
                <select class="form-select @error('zone_tujuan') is-invalid @enderror" 
                        id="zone_tujuan" name="zone_tujuan" onchange="generateLokasiTujuan()">
                    <option value="">-- Pilih Zone --</option>
                    <option value="zona_a" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_a' ? 'selected' : '' }}>
                        Zona A
                    </option>
                    <option value="zona_b" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_b' ? 'selected' : '' }}>
                        Zona B
                    </option>
                    <option value="zona_c" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_c' ? 'selected' : '' }}>
                        Zona C
                    </option>
                    <option value="zona_d" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_d' ? 'selected' : '' }}>
                        Zona D
                    </option>
                    <option value="zona_return" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_return' ? 'selected' : '' }}>
                        📤 Zona Return
                    </option>
                    <option value="zona_scrap" {{ old('zone_tujuan', $masterDisposisi->zone_tujuan ?? '') == 'zona_scrap' ? 'selected' : '' }}>
                        🗑️ Zona Scrap
                    </option>
                </select>
                @error('zone_tujuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Lokasi tujuan barang</small>
            </div>

            <!-- Rack Tujuan -->
            <div class="col-md-3">
                <label for="rack_tujuan" class="form-label">Rack Tujuan</label>
                <input type="text" class="form-control @error('rack_tujuan') is-invalid @enderror" 
                       id="rack_tujuan" name="rack_tujuan" placeholder="Contoh: A1, B2, Return_Rack"
                       value="{{ old('rack_tujuan', $masterDisposisi->rack_tujuan ?? '') }}"
                       onchange="generateLokasiTujuan()">
                @error('rack_tujuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Nomor atau nama rack</small>
            </div>

            <!-- Bin Tujuan -->
            <div class="col-md-3">
                <label for="bin_tujuan" class="form-label">Bin Tujuan</label>
                <input type="text" class="form-control @error('bin_tujuan') is-invalid @enderror" 
                       id="bin_tujuan" name="bin_tujuan" placeholder="Contoh: 001, 002, A"
                       value="{{ old('bin_tujuan', $masterDisposisi->bin_tujuan ?? '') }}"
                       onchange="generateLokasiTujuan()">
                @error('bin_tujuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Nomor atau kode bin</small>
            </div>

            <!-- Lokasi Lengkap Tujuan (Auto-generated) -->
            <div class="col-md-3">
                <label for="lokasi_lengkap_tujuan" class="form-label">Lokasi Lengkap</label>
                <input type="text" class="form-control" 
                       id="lokasi_lengkap_tujuan" name="lokasi_lengkap_tujuan"
                       value="{{ old('lokasi_lengkap_tujuan', $masterDisposisi->lokasi_lengkap_tujuan ?? '') }}"
                       disabled>
                <small class="text-muted">Auto-generated</small>
            </div>
        </div>
    </div>
</div>

<script>
function generateLokasiTujuan() {
    const zone = document.getElementById('zone_tujuan').value;
    const rack = document.getElementById('rack_tujuan').value;
    const bin = document.getElementById('bin_tujuan').value;

    const zoneMap = {
        'zona_a': 'ZA',
        'zona_b': 'ZB',
        'zona_c': 'ZC',
        'zona_d': 'ZD',
        'zona_return': 'RET',
        'zona_scrap': 'SCR'
    };

    const zoneCode = zoneMap[zone] || '';
    const lokasi = zone && rack && bin ? `${zoneCode}-${rack}-${bin}` : '';
    document.getElementById('lokasi_lengkap_tujuan').value = lokasi;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    generateLokasiTujuan();
});
</script>
```

---

## 🗂️ Field yang Ditambahkan ke Master Disposisi

| Field | Type | Required | Penjelasan |
|-------|------|----------|-----------|
| `zone_tujuan` | enum/string | Optional | Zone tujuan default (zona_a-e, zona_return, zona_scrap) |
| `rack_tujuan` | varchar(100) | Optional | Rack tujuan default |
| `bin_tujuan` | varchar(100) | Optional | Bin tujuan default |
| `lokasi_lengkap_tujuan` | varchar(255) | Optional | Lokasi lengkap otomatis (format: ZA-A1-001) |

---

## 📝 Migrasi yang Diperlukan

```php
Schema::table('master_disposisis', function (Blueprint $table) {
    // Lokasi Tujuan Relokasi
    $table->enum('zone_tujuan', [
        'zona_a', 'zona_b', 'zona_c', 'zona_d', 'zona_e',
        'zona_return', 'zona_scrap', 'zona_rework'
    ])->nullable()->after('deskripsi');
    
    $table->string('rack_tujuan', 100)->nullable()->after('zone_tujuan');
    $table->string('bin_tujuan', 100)->nullable()->after('rack_tujuan');
    $table->string('lokasi_lengkap_tujuan', 255)->nullable()->after('bin_tujuan');
    
    // Index untuk query performa
    $table->index('zone_tujuan');
});
```

---

## 💾 Update Model: MasterDisposisi.php

```php
protected $fillable = [
    'kode_disposisi',
    'nama_disposisi',
    'jenis_tindakan',
    'deskripsi',
    'proses_tindakan',
    'syarat_ketentuan',
    'memerlukan_approval',
    'is_active',
    // ✨ NEW FIELDS:
    'zone_tujuan',
    'rack_tujuan',
    'bin_tujuan',
    'lokasi_lengkap_tujuan',
];

// Jika ingin casts
protected $casts = [
    'memerlukan_approval' => 'boolean',
    'is_active' => 'boolean',
];
```

---

## 🔗 Workflow Penggunaan

### Skenario: Admin Membuat Disposisi "Return ke Vendor"

```
STEP 1: Admin membuat Master Disposisi
├─ Kode: RET-001
├─ Nama: Return ke Vendor
├─ Jenis: Return to Vendor
├─ Status: Aktif
│
└─ 📍 Lokasi Tujuan Relokasi:
   ├─ Zone: zona_return
   ├─ Rack: Return_Rack_A
   ├─ Bin: 001
   └─ Lokasi Lengkap: RET-Return_Rack_A-001

        ↓

STEP 2: Warehouse staff menerima barang NG
├─ Barang disimpan di zona_a/A1/001
├─ Status: disimpan

        ↓

STEP 3: QC melakukan Disposisi
├─ Barang di-assign ke disposisi: RET-001 (Return ke Vendor)
├─ System otomatis set:
│  ├─ zone_tujuan: zona_return
│  ├─ rack_tujuan: Return_Rack_A
│  ├─ bin_tujuan: 001
│  └─ status_barang: siap_dipindahkan

        ↓

STEP 4: Warehouse memindahkan barang
├─ Dari: zona_a/A1/001
├─ Ke: zona_return/Return_Rack_A/001 ✓
├─ tanggal_relokasi: 2026-01-23 14:30
└─ status_barang: dipindahkan
```

---

## 📱 UI Tampilan di Edit Form

### SEBELUM (Tanpa Relokasi):
```
┌─ Master Disposisi Edit ──────────────────┐
├─ 📌 Identifikasi Disposisi
│  ├─ Kode: RET-001
│  └─ Nama: Return ke Vendor
├─ ⚙️ Jenis Tindakan
│  ├─ Jenis: Return to Vendor
│  └─ Perlu Approval: Ya
├─ 📝 Deskripsi
├─ 📊 Status
└─ [Simpan] [Batal]
```

### SETELAH (Dengan Relokasi):
```
┌─ Master Disposisi Edit ──────────────────┐
├─ 📌 Identifikasi Disposisi
│  ├─ Kode: RET-001
│  └─ Nama: Return ke Vendor
├─ ⚙️ Jenis Tindakan
│  ├─ Jenis: Return to Vendor
│  └─ Perlu Approval: Ya
├─ 📝 Deskripsi
├─ 📍 Lokasi Tujuan Relokasi ✨ NEW
│  ├─ Zone Tujuan: zona_return
│  ├─ Rack Tujuan: Return_Rack_A
│  ├─ Bin Tujuan: 001
│  └─ Lokasi Lengkap: RET-Return_Rack_A-001
├─ 📊 Status
└─ [Simpan] [Batal]
```

---

## 🎯 Keuntungan Menambahkan di Master Disposisi

✅ **Konsistensi**: Setiap barang dengan disposisi yang sama akan dipindahkan ke lokasi yang sama
✅ **Efisiensi**: Warehouse staff tidak perlu menentukan lokasi tujuan manual
✅ **Traceability**: Mudah audit trail - tahu barang seharusnya pindah ke mana
✅ **Default Value**: Jika diperlukan, bisa di-override per barang di Penyimpanan NG

---

## 📋 Checklist Implementasi

- [ ] Buat migration untuk tambah 4 fields ke `master_disposisis` table
- [ ] Update `MasterDisposisi.php` model dengan fillables baru
- [ ] Update `master-disposisi-edit.blade.php` dengan section lokasi tujuan
- [ ] Update `master-disposisi-create.blade.php` dengan section lokasi tujuan
- [ ] Update `master-disposisi-show.blade.php` untuk tampilkan lokasi tujuan
- [ ] Update `MasterDisposisiController` untuk handle 4 fields baru
- [ ] Test: Buat disposisi baru dengan lokasi tujuan
- [ ] Test: Verify auto-generate lokasi lengkap bekerja
- [ ] Test: Verify barang di-assign ke disposisi auto-set lokasi tujuan

---

## 💡 Next Steps

Setelah implementasi UI ini, **Controller harus di-update** untuk:
1. Saat create/edit Penyimpanan NG dengan disposisi → auto-fill lokasi tujuan dari Master Disposisi
2. Saat update status barang menjadi "dipindahkan" → validate lokasi tujuan

**Contoh:**
```php
// Di PenyimpananNgController@store
if ($request->master_disposisi_id) {
    $disposisi = MasterDisposisi::find($request->master_disposisi_id);
    
    // Auto-fill dari Master Disposisi
    $validated['zone_tujuan'] = $validated['zone_tujuan'] ?? $disposisi->zone_tujuan;
    $validated['rack_tujuan'] = $validated['rack_tujuan'] ?? $disposisi->rack_tujuan;
    $validated['bin_tujuan'] = $validated['bin_tujuan'] ?? $disposisi->bin_tujuan;
}
```

---

## 📚 Referensi

- Penyimpanan NG Edit Form: `resources/views/menu-sidebar/warehouse/penyimpanan-ng-edit.blade.php`
- Master Disposisi Edit Form: `resources/views/menu-sidebar/master-data/master-disposisi-edit.blade.php`
- Database: `penyimpanan_ngs` & `master_disposisis` tables
