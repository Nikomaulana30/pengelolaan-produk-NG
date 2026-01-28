# 🎯 Quick Reference - Penyimpanan NG & Disposisi

## The Problem You Asked
> "Data tabel dari penyimpanan NG terhubung dengan MasterDisposisi jadi dapat diketahui akan dipindahkan dari rack a ke b misal"

## The Solution ✅

### Before (Missing Information)
```
Penyimpanan NG:
├─ nomor_storage: STR-20260123-0001
├─ zona: zona_a
├─ rack: A1
├─ bin: B1
└─ ❌ Tidak tahu akan kemana atau untuk apa
```

### After (Complete Information)
```
Penyimpanan NG:
├─ nomor_storage: STR-20260123-0001
├─ 📍 Lokasi Asal: zona_a, A1, B1
├─ 🎯 Lokasi Tujuan: zona_b, B2, B2  ← NEW
├─ 📋 Disposisi: Retur ke Vendor     ← NEW (FK)
├─ 💬 Alasan: Rejected by QC         ← NEW
└─ ⏰ Tanggal Relokasi: 2026-01-23   ← NEW
```

---

## Quick Code Examples

### 1️⃣ Set Disposisi & Rencana Relokasi
```php
$penyimpanan = PenyimpananNg::find(1);

$penyimpanan->update([
    'master_disposisi_id' => 1,        // Link ke MasterDisposisi
    'zone_tujuan' => 'zona_b',         // Ke mana
    'rack_tujuan' => 'return_rack',    // Ke rack apa
    'bin_tujuan' => '001',             // Ke bin apa
    'alasan_relokasi' => 'QC reject',  // Mengapa
    'status_barang' => 'siap_dipindahkan'
]);
```

### 2️⃣ Akses Disposisi & Info Perpindahan
```php
$penyimpanan = PenyimpananNg::with('disposisi')->find(1);

// Asal
echo $penyimpanan->zone . "/" . $penyimpanan->rack;  // zona_a/A1

// Disposisi
echo $penyimpanan->disposisi->nama_disposisi;        // Retur ke Vendor

// Tujuan
echo $penyimpanan->zone_tujuan . "/" . $penyimpanan->rack_tujuan; // zona_b/return_rack
```

### 3️⃣ Confirm Perpindahan Fisik
```php
$penyimpanan->update([
    'tanggal_relokasi' => now(),
    'status_barang' => 'dipindahkan'
]);
```

### 4️⃣ Query Rencana Relokasi
```php
// Semua barang yang siap dipindahkan
PenyimpananNg::where('status_barang', 'siap_dipindahkan')
    ->with('disposisi')
    ->get();
```

---

## Database Fields Added

| Field | Type | Purpose |
|-------|------|---------|
| `master_disposisi_id` | FK | Link ke MasterDisposisi |
| `zone_tujuan` | enum | Zona tujuan relokasi |
| `rack_tujuan` | string | Rack tujuan relokasi |
| `bin_tujuan` | string | Bin tujuan relokasi |
| `alasan_relokasi` | string | Alasan perpindahan |
| `tanggal_relokasi` | datetime | Kapan dipindahkan |

---

## Files Modified

✅ `app/Models/PenyimpananNg.php` - Added relationships & fillables  
✅ `database/migrations/2026_01_23_000001_add_relokasi_fields_to_penyimpanan_ngs.php` - Migration created  
✅ `PENYIMPANAN_NG_DISPOSISI_RELOKASI.md` - Full documentation  
✅ `PENYIMPANAN_NG_DISPOSISI_RELOKASI_SUMMARY.md` - Visual guide  

---

## Status Workflow

```
disimpan (awal)
    ↓
siap_dipindahkan (+ disposisi & lokasi tujuan diset)
    ↓
dipindahkan (+ tanggal_relokasi tercatat)
```

---

## Ready to Use ✅

Migration: `php artisan migrate`

```
Sekarang sistem dapat menjawab:
"Barang STR-20260123-0001 akan DIPINDAHKAN dari zona_a/A1/B1 ke zona_b/return_rack/001
karena disposisinya adalah RETUR KE VENDOR, dan sudah tercatat relokasi pada 23-01-2026"
```
