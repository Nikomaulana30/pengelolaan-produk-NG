# 📦 Penyimpanan NG - Disposisi & Relokasi Integration

## Overview

Sistem ini memungkinkan tracking perpindahan barang NG dari lokasi asal ke lokasi tujuan dengan disposisi yang jelas.

## 🔄 Alur Proses

```
1. Barang NG disimpan di lokasi awal
   └─ zone: "zona_a", rack: "A1", bin: "B1"

2. Disposisi ditentukan (Retur, Scrap, Rework, dll)
   └─ master_disposisi_id: 1 (Retur ke Vendor)

3. Lokasi tujuan ditentukan
   └─ zone_tujuan: "zona_b", rack_tujuan: "B2", bin_tujuan: "B2"

4. Status berubah → "siap_dipindahkan"

5. Barang dipindahkan dan status → "dipindahkan"
   └─ tanggal_relokasi tercatat
```

## 📊 Field Struktur Tabel

### Lokasi Awal (Original Storage)
```sql
- zone          : zona_a, zona_b, zona_c, zona_d, zona_e
- rack          : String (misal: "A1", "B2", dll)
- bin           : String (misal: "B1", "B2", dll)
- lokasi_lengkap: String (generated dari zone + rack + bin)
```

### Lokasi Tujuan (NEW - untuk relokasi)
```sql
- zone_tujuan         : zona_a, zona_b, zona_c, zona_d, zona_e (nullable)
- rack_tujuan         : String (misal: "C1") (nullable)
- bin_tujuan          : String (misal: "C2") (nullable)
- lokasi_lengkap_tujuan: String (generated) (nullable)
```

### Tracking Relokasi
```sql
- tanggal_relokasi : DateTime (Kapan barang dipindahkan) (nullable)
- alasan_relokasi  : String (Alasan perpindahan) (nullable)
```

### Link ke Disposisi
```sql
- master_disposisi_id : FK ke master_disposisis (nullable)
```

## 🔗 Model Relationships

### PenyimpananNg Model

```php
// Akses disposisi langsung
$penyimpananNg->disposisi
// Returns: MasterDisposisi (retur, scrap, rework, dll)

// Contoh:
echo $penyimpananNg->disposisi->nama_disposisi; // "Retur ke Vendor"
echo $penyimpananNg->disposisi->jenis_tindakan;  // "return_to_vendor"
```

### Menampilkan Informasi Perpindahan

```php
// Lokasi asal
echo $penyimpananNg->zone;            // "zona_a"
echo $penyimpananNg->rack;            // "A1"
echo $penyimpananNg->bin;             // "B1"

// Disposisi yang diterapkan
echo $penyimpananNg->disposisi->nama_disposisi; // "Retur ke Vendor"

// Lokasi tujuan
echo $penyimpananNg->zone_tujuan;     // "zona_b"
echo $penyimpananNg->rack_tujuan;     // "B2"
echo $penyimpananNg->bin_tujuan;      // "B2"

// Tracking relokasi
echo $penyimpananNg->tanggal_relokasi; // "2026-01-23 14:30:00"
echo $penyimpananNg->alasan_relokasi;  // "Siap untuk return vendor"
```

## 💾 Database Schema

### Tabel: penyimpanan_ngs (UPDATED)

```sql
CREATE TABLE penyimpanan_ngs (
    -- Existing fields
    id BIGINT PRIMARY KEY,
    nomor_storage VARCHAR(255) UNIQUE,
    tanggal_penyimpanan DATETIME,
    zona VARCHAR(50),
    rack VARCHAR(50),
    bin VARCHAR(50),
    lokasi_lengkap VARCHAR(255),
    status_barang ENUM(...),
    
    -- NEW: Lokasi Tujuan
    zone_tujuan VARCHAR(50) NULL,
    rack_tujuan VARCHAR(50) NULL,
    bin_tujuan VARCHAR(50) NULL,
    lokasi_lengkap_tujuan VARCHAR(255) NULL,
    
    -- NEW: Tracking Relokasi
    tanggal_relokasi DATETIME NULL,
    alasan_relokasi VARCHAR(255) NULL,
    
    -- NEW: Link ke Disposisi
    master_disposisi_id BIGINT NULL REFERENCES master_disposisis(id),
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- Indexes
INDEX idx_zone_tujuan (zone_tujuan);
INDEX idx_master_disposisi_id (master_disposisi_id);
```

## 📋 Contoh Use Cases

### Use Case 1: Barang akan dikembalikan ke Vendor
```php
$penyimpananNg = PenyimpananNg::find(1);

// Lokasi awal: zona_a, rack A1
$penyimpananNg->update([
    'master_disposisi_id' => MasterDisposisi::where('nama_disposisi', 'Retur ke Vendor')->first()->id,
    'zone_tujuan' => 'zona_b',
    'rack_tujuan' => 'return_rack',
    'bin_tujuan' => '001',
    'alasan_relokasi' => 'Return to vendor - rejected by QC',
    'status_barang' => 'siap_dipindahkan'
]);

// Kemudian setelah dipindahkan
$penyimpananNg->update([
    'tanggal_relokasi' => now(),
    'status_barang' => 'dipindahkan'
]);

// Display
echo "Barang: {$penyimpananNg->nama_barang}";
echo "Asal: {$penyimpananNg->zone} {$penyimpananNg->rack} {$penyimpananNg->bin}";
echo "Tujuan: {$penyimpananNg->zone_tujuan} {$penyimpananNg->rack_tujuan} {$penyimpananNg->bin_tujuan}";
echo "Disposisi: {$penyimpananNg->disposisi->nama_disposisi}";
echo "Dipindahkan pada: {$penyimpananNg->tanggal_relokasi}";
```

### Use Case 2: Barang untuk Scrap/Disposal
```php
$penyimpananNg->update([
    'master_disposisi_id' => MasterDisposisi::where('jenis_tindakan', 'scrap_disposal')->first()->id,
    'zone_tujuan' => 'zona_d', // Scrap area
    'rack_tujuan' => 'scrap_rack',
    'bin_tujuan' => '999',
    'alasan_relokasi' => 'Disposal - Non repairable',
    'status_barang' => 'siap_dipindahkan'
]);
```

### Use Case 3: Barang untuk Rework
```php
$penyimpananNg->update([
    'master_disposisi_id' => MasterDisposisi::where('jenis_tindakan', 'rework')->first()->id,
    'zone_tujuan' => 'zona_c', // Rework area
    'rack_tujuan' => 'rework_rack',
    'bin_tujuan' => '001',
    'alasan_relokasi' => 'Rework di production line',
    'status_barang' => 'dalam_perbaikan'
]);
```

## 🎨 Blade Template Display

```blade
<!-- Show perpindahan yang direncanakan -->
@if ($penyimpananNg->disposisi)
    <div class="relocation-plan">
        <h5>📍 Rencana Perpindahan</h5>
        
        <div class="location-flow">
            <div class="location-asal">
                <strong>Dari:</strong>
                <span>{{ $penyimpananNg->zone }} / {{ $penyimpananNg->rack }} / {{ $penyimpananNg->bin }}</span>
            </div>
            
            <div class="arrow">→</div>
            
            <div class="location-tujuan">
                <strong>Ke:</strong>
                <span>{{ $penyimpananNg->zone_tujuan ?? 'Belum ditentukan' }} / 
                      {{ $penyimpananNg->rack_tujuan ?? '-' }} / 
                      {{ $penyimpananNg->bin_tujuan ?? '-' }}</span>
            </div>
        </div>
        
        <p><strong>Disposisi:</strong> 
           <span class="badge">{{ $penyimpananNg->disposisi->nama_disposisi }}</span>
        </p>
        
        <p><strong>Alasan:</strong> {{ $penyimpananNg->alasan_relokasi }}</p>
        
        @if ($penyimpananNg->tanggal_relokasi)
            <p class="text-success">✓ Sudah dipindahkan pada: {{ $penyimpananNg->tanggal_relokasi->format('d-m-Y H:i') }}</p>
        @else
            <p class="text-warning">⏳ Belum dipindahkan</p>
        @endif
    </div>
@endif
```

## 🔄 Status Workflow dengan Disposisi

```
┌─────────────────┐
│    DISIMPAN     │ ← Initial status (zona_a, rack A1)
└────────┬────────┘
         │
         ↓ Disposisi ditentukan & Lokasi tujuan diset
┌─────────────────────────────────────┐
│ SIAP_DIPINDAHKAN                     │
│ (Rencana relokasi sudah ada)        │
│ - master_disposisi_id diset         │
│ - zone_tujuan, rack_tujuan diset   │
└────────┬────────────────────────────┘
         │
         ↓ Barang dipindahkan secara fisik
┌──────────────────────────────────────────┐
│ DIPINDAHKAN                              │
│ (Barang sudah di lokasi baru)           │
│ - tanggal_relokasi tercatat             │
│ - lokasi awal: zona_a, rack A1          │
│ - lokasi baru: zona_b, rack B2          │
└──────────────────────────────────────────┘
```

## 📝 Migrasi & Deployment

### Langkah 1: Run migration
```bash
php artisan migrate
```

### Langkah 2: Update model fillables
✅ Sudah dilakukan di `PenyimpananNg.php`

### Langkah 3: Update forms/controllers untuk:
- Input zone_tujuan, rack_tujuan, bin_tujuan
- Select master_disposisi_id
- Input alasan_relokasi
- Record tanggal_relokasi saat relokasi

## ✅ Verification

```php
// Test hubungan
$penyimpananNg = PenyimpananNg::with('disposisi')->find(1);

// Check lokasi asal
echo "Lokasi asal: " . $penyimpananNg->lokasi_lengkap;

// Check disposisi
echo "Disposisi: " . $penyimpananNg->disposisi->nama_disposisi;

// Check lokasi tujuan
echo "Lokasi tujuan: " . $penyimpananNg->lokasi_lengkap_tujuan;

// Check tracking
echo "Tanggal relokasi: " . $penyimpananNg->tanggal_relokasi;
```

---

## 📌 Summary

✅ **Field relokasi** sudah ditambahkan  
✅ **Direct FK ke MasterDisposisi** untuk mengetahui aksi yang akan dilakukan  
✅ **Lokasi tujuan** tercatat untuk tracking perpindahan  
✅ **Status workflow** terintegrasi dengan disposisi  
✅ **Siap untuk digunakan** di UI/Form untuk menentukan rencana relokasi

Sekarang data tabel penyimpanan NG terhubung langsung dengan MasterDisposisi, sehingga dapat diketahui: "Barang ini akan dipindahkan dari Rack A ke Rack B karena disposisi: Retur ke Vendor"
