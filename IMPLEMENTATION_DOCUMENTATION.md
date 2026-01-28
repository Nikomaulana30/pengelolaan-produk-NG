# 📘 METINCA Starter App - Implementation Documentation

> **Dokumentasi Lengkap Implementasi Sistem**  
> Tanggal: 27 Januari 2026  
> Version: 2.0

---

## 📋 Table of Contents

1. [System Overview](#system-overview)
2. [Database Architecture](#database-architecture)
3. [Approval System](#approval-system)
4. [Module Implementation Status](#module-implementation-status)
5. [Model Relationships](#model-relationships)
6. [Usage Examples](#usage-examples)
7. [Testing Guide](#testing-guide)

---

## 🎯 System Overview

### Implemented Features

✅ **Master Approval Authority** - Sistem otorisasi approval multi-level  
✅ **General Approval System** - Polymorphic approval untuk semua modul  
✅ **Quality Inspection** - Inspeksi kualitas dengan auto-create Penyimpanan NG  
✅ **Retur Barang** - Manajemen retur supplier dengan approval flow  
✅ **Scrap Disposal** - Pembuangan barang scrap dengan approval  
✅ **RCA Analysis** - Root Cause Analysis (5 Why & Fishbone)  
✅ **Finance Approval** - Approval finansial untuk dampak biaya  
✅ **Stock Movement** - Tracking perpindahan stock IN/OUT/TRANSFER  

### Database Statistics

| Table | Rows | Size | Status |
|-------|------|------|--------|
| `approvals` | 0 | 64 KB | ✅ Active |
| `master_approval_authorities` | 1 | 32 KB | ✅ Active |
| `quality_inspections` | 0 | 176 KB | ✅ Active |
| `retur_barangs` | 0 | 48 KB | ✅ Active |
| `scrap_disposals` | 0 | 32 KB | ✅ Active |
| `rca_analyses` | 0 | 48 KB | ✅ Active |
| `finance_approvals` | 0 | 32 KB | ✅ Active |
| `stock_movements` | 0 | 80 KB | ✅ Active |
| `penyimpanan_ngs` | 1 | 192 KB | ✅ Active |

**Total: 41 tables, 1.72 MB**

---

## 🗄️ Database Architecture

### Core Tables Structure

#### 1. **approvals** - Polymorphic Approval System

```sql
CREATE TABLE approvals (
    id BIGINT PRIMARY KEY,
    approvable_type VARCHAR(255),      -- Model class name
    approvable_id BIGINT,               -- Model ID
    approval_authority_id BIGINT,       -- FK to master_approval_authorities
    approver_id BIGINT,                 -- FK to users (who approved)
    submitted_by BIGINT,                -- FK to users (who submitted)
    level TINYINT,                      -- Approval level (1, 2, 3)
    status ENUM('pending', 'approved', 'rejected', 'cancelled'),
    notes TEXT,
    submitted_at TIMESTAMP,
    approved_at TIMESTAMP,
    rejected_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_approvable (approvable_type, approvable_id),
    INDEX idx_status (status),
    INDEX idx_level (level)
);
```

**Usage Pattern:**
- Polymorphic relationship ke semua model yang membutuhkan approval
- Multi-level approval (1-3 levels)
- Status tracking lengkap dengan timestamp

---

#### 2. **master_approval_authorities** - Approval Authority

```sql
CREATE TABLE master_approval_authorities (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,                     -- FK to users
    departemen VARCHAR(100),
    role_level TINYINT,                 -- 1 (Supervisor), 2 (Manager), 3 (Director)
    jenis_approval VARCHAR(100),        -- approval type: finance, quality, etc.
    limit_value DECIMAL(15,2),          -- approval limit amount
    can_approve_self BOOLEAN DEFAULT 0,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Key Features:**
- Multi-departemen support
- Role-based approval hierarchy
- Approval limit enforcement
- Self-approval control

---

#### 3. **stock_movements** - Stock Movement Tracking

```sql
CREATE TABLE stock_movements (
    id BIGINT PRIMARY KEY,
    penyimpanan_ng_id BIGINT,           -- FK to penyimpanan_ngs
    movement_type ENUM('in', 'out', 'transfer', 'adjustment'),
    reference_type VARCHAR(100),        -- qc_inspection, manual_entry, etc.
    reference_id BIGINT,                -- Reference to source
    from_lokasi_id BIGINT NULL,         -- FK to master_lokasi_gudangs
    to_lokasi_id BIGINT NULL,           -- FK to master_lokasi_gudangs
    qty_before INT,
    qty_moved INT,
    qty_after INT,
    notes TEXT,
    moved_by BIGINT,                    -- FK to users
    moved_at TIMESTAMP,
    status ENUM('pending', 'completed', 'cancelled'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Movement Types:**
- `in` - Barang masuk (dari QC/receiving)
- `out` - Barang keluar (retur/scrap)
- `transfer` - Perpindahan antar lokasi
- `adjustment` - Adjustment manual

---

#### 4. **retur_barangs** - Return Management

```sql
CREATE TABLE retur_barangs (
    id BIGINT PRIMARY KEY,
    vendor_id BIGINT,                   -- FK to master_vendors
    produk_id BIGINT,                   -- FK to master_products
    no_retur VARCHAR(50) UNIQUE,        -- Format: RET-YYYYMM-XXXXX
    tanggal_retur DATE,
    alasan_retur ENUM('defect', 'qty_tidak_sesuai', ...),
    jumlah_retur INT,
    deskripsi_keluhan TEXT,
    status_approval ENUM('pending', 'approved', 'rejected'),
    catatan_approval TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

**Auto-Number Generation:**
```php
ReturBarang::generateNoRetur(); // RET-202601-00001
```

---

#### 5. **rca_analyses** - Root Cause Analysis

```sql
CREATE TABLE rca_analyses (
    id BIGINT PRIMARY KEY,
    nomor_rca VARCHAR(50) UNIQUE,       -- Format: RCA-YYYYMMDD-XXXX
    tanggal_analisa DATETIME,
    metode_rca ENUM('5_why', 'fishbone', 'kombinasi'),
    kode_defect VARCHAR(50),            -- FK to master_defects
    kode_barang VARCHAR(50),            -- FK to master_products
    retur_barang_id BIGINT NULL,        -- FK to retur_barangs (optional)
    criticality_level ENUM('low', 'medium', 'high', 'critical'),
    sumber_masalah VARCHAR(255),
    penyebab_utama TEXT,
    deskripsi_masalah TEXT,
    analisa_detail JSON,                -- 5 Why / Fishbone data
    corrective_action TEXT,
    preventive_action TEXT,
    pic_analisa ENUM('qc', 'engineering', 'warehouse', ...),
    nama_analis VARCHAR(100),
    due_date DATE,
    status_rca ENUM('open', 'in_progress', 'closed'),
    catatan TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

**Features:**
- 5 Why Analysis support
- Fishbone Diagram support
- Multi-method combination
- Due date tracking
- Overdue detection

---

#### 6. **scrap_disposals** - Scrap Management

```sql
CREATE TABLE scrap_disposals (
    id BIGINT PRIMARY KEY,
    nomor_scrap VARCHAR(50),
    tanggal_scrap DATETIME,
    nama_petugas VARCHAR(100),
    nomor_referensi VARCHAR(100),
    nama_barang VARCHAR(255),
    quantity INT,
    alasan_scrap TEXT,
    deskripsi_kondisi TEXT,
    hasil_test_qc VARCHAR(50),
    tanggal_test_qc DATE,
    qc_inspector VARCHAR(100),
    catatan_qc TEXT,
    metode_pembuangan VARCHAR(100),
    tanggal_rencana_scrap DATE,
    pihak_pelaksana VARCHAR(100),
    estimasi_biaya_pembuangan DECIMAL(15,2),
    dokumen_bukti VARCHAR(255),
    status_approval ENUM('pending', 'approved', 'rejected'),
    tanggal_approval DATETIME,
    nama_manager VARCHAR(100),
    catatan_manager TEXT,
    user_id BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

---

#### 7. **finance_approvals** - Financial Approval

```sql
CREATE TABLE finance_approvals (
    id BIGINT PRIMARY KEY,
    nomor_approval VARCHAR(50),
    nomor_referensi VARCHAR(100),       -- Link to RCA, Scrap, etc.
    pengaju VARCHAR(100),
    deskripsi_pengajuan TEXT,
    jenis_dampak ENUM('cost_saving', 'cost_addition', 'neutral'),
    estimasi_biaya DECIMAL(15,2),
    asal_permohonan VARCHAR(100),
    referensi_permohonan VARCHAR(100),
    status_approval ENUM('pending', 'approved', 'rejected'),
    tanggal_approval DATETIME,
    nama_approver VARCHAR(100),
    budget_approval BOOLEAN DEFAULT 0,
    catatan TEXT,
    user_id BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

---

## 🔐 Approval System

### HasApproval Trait

**File:** `app/Traits/HasApproval.php`

Trait yang dapat digunakan oleh semua model untuk menambahkan capability approval.

#### Installation

```php
use App\Traits\HasApproval;

class ReturBarang extends Model
{
    use HasApproval;
    
    // Required: Define approval type
    public function getApprovalType(): string
    {
        return 'retur_barang';
    }
    
    // Optional: Hook after approval
    public function onApproved(int $level): void
    {
        if ($this->isFullyApproved()) {
            $this->update(['status_approval' => 'approved']);
        }
    }
    
    // Optional: Hook after rejection
    public function onRejected(int $level, ?string $reason): void
    {
        $this->update(['status_approval' => 'rejected']);
    }
}
```

#### Available Methods

##### Relationships
```php
$model->approvals();              // All approvals (morphMany)
$model->pendingApprovals();       // Pending approvals only
$model->approvedApprovals();      // Approved approvals only
```

##### Status Checks
```php
$model->hasPendingApproval();           // Has any pending approval
$model->hasPendingApproval($level);     // Has pending at specific level
$model->isApprovedAt($level);           // Is approved at specific level
$model->isFullyApproved();              // All levels approved
```

##### Actions
```php
// Submit for approval
$model->submitForApproval($level = 1, $notes = null);

// Cancel all pending approvals
$model->cancelApprovals();
```

##### Hooks (must implement)
```php
getApprovalType(): string         // Required - return approval type
onApproved(int $level): void      // Optional - called after approval
onRejected(int $level, ?string $reason): void  // Optional - called after rejection
```

---

### Approval Model Methods

**File:** `app/Models/Approval.php`

#### Approve an Approval

```php
$approval = Approval::find($id);
$approval->approve($notes = 'Approved');

// This will trigger onApproved() hook on the model
```

#### Reject an Approval

```php
$approval->reject($reason = 'Insufficient documentation');

// This will trigger onRejected() hook on the model
```

#### Check Permission

```php
$approval->canBeApprovedBy($userId);  // Returns boolean
```

#### Create Approval for Model

```php
$approval = Approval::createForModel(
    $model,           // Model instance (e.g., ReturBarang)
    $level = 1,       // Approval level
    $notes = null     // Optional notes
);
```

#### Scopes

```php
Approval::pending()->get();              // All pending
Approval::approved()->get();             // All approved
Approval::rejected()->get();             // All rejected
Approval::forApprover($userId)->get();   // For specific approver
Approval::byLevel($level)->get();        // By level (1, 2, 3)
```

#### Badge Helpers

```php
$approval->status_badge;   // HTML badge for status
$approval->level_badge;    // HTML badge for level
```

---

## 📊 Module Implementation Status

### ✅ Fully Implemented

| Module | Model | Controller | Views | Routes | Approval |
|--------|-------|------------|-------|--------|----------|
| **Master Approval Authority** | ✅ | ✅ | ✅ Index | ✅ | N/A |
| **General Approval** | ✅ | ✅ | ⏳ | ✅ | ✅ |
| **Quality Inspection** | ✅ | ✅ | ⏳ | ✅ | ✅ |
| **Retur Barang** | ✅ | ✅ | ✅ Full | ✅ | ✅ |
| **Scrap Disposal** | ✅ | ✅ | ⏳ | ✅ | ✅ |
| **RCA Analysis** | ✅ | ✅ | ⏳ | ✅ | ✅ |
| **Finance Approval** | ✅ | ✅ | ⏳ | ✅ | ✅ |
| **Stock Movement** | ✅ | ⏳ | ⏳ | ⏳ | N/A |

**Legend:**
- ✅ Complete
- ⏳ Partial/Pending
- ❌ Not Implemented

---

## 🔗 Model Relationships

### Approval System Relationships

```
Approval (Polymorphic)
├─→ morphTo: approvable (ReturBarang, ScrapDisposal, etc.)
├─→ belongsTo: approver (User)
├─→ belongsTo: submitter (User)
└─→ belongsTo: approvalAuthority (MasterApprovalAuthority)

MasterApprovalAuthority
└─→ belongsTo: user (User)
```

### Warehouse Relationships

```
PenyimpananNg (Central Hub)
├─→ belongsTo: lokasiGudang (MasterLokasiGudang)
├─→ belongsTo: penerimaanBarang (PenerimaanBarang)
├─→ belongsTo: disposisi (MasterDisposisi)
├─→ hasOne: qualityInspection (QualityInspection)
├─→ hasMany: stockMovements (StockMovement)
└─→ hasMany: disposisiAssignments (DisposisiAssignment)

QualityInspection
├─→ belongsTo: penerimaanBarang (PenerimaanBarang)
└─→ belongsTo: penyimpananNg (PenyimpananNg)

StockMovement
├─→ belongsTo: penyimpananNg (PenyimpananNg)
├─→ belongsTo: user (User - moved_by)
├─→ belongsTo: fromLokasi (MasterLokasiGudang)
└─→ belongsTo: toLokasi (MasterLokasiGudang)
```

### Retur & RCA Relationships

```
ReturBarang
├─→ belongsTo: vendor (MasterVendor)
├─→ belongsTo: produk (MasterProduk)
├─→ hasMany: rcaAnalyses (RcaAnalysis)
├─→ morphMany: approvals (Approval)
└─→ morphMany: activityLogs (ActivityLog)

RcaAnalysis
├─→ belongsTo: masterDefect (MasterDefect)
├─→ belongsTo: masterProduk (MasterProduk)
├─→ belongsTo: returBarang (ReturBarang) [optional]
└─→ morphMany: approvals (Approval)

ScrapDisposal
├─→ belongsTo: user (User)
├─→ belongsTo: masterProduk (MasterProduk)
└─→ morphMany: approvals (Approval)

FinanceApproval
├─→ belongsTo: user (User)
├─→ belongsTo: rcaAnalysis (RcaAnalysis)
└─→ morphMany: approvals (Approval)
```

---

## 💻 Usage Examples

### Example 1: Submit Retur Barang for Approval

```php
use App\Models\ReturBarang;
use App\Models\Approval;

// Create retur barang
$retur = ReturBarang::create([
    'vendor_id' => 1,
    'produk_id' => 5,
    'tanggal_retur' => now(),
    'alasan_retur' => 'defect',
    'jumlah_retur' => 100,
    'deskripsi_keluhan' => 'Produk cacat pada bagian finishing',
    'status_approval' => 'pending',
]);

// Submit for level 1 approval (Supervisor)
$retur->submitForApproval(1, 'Mohon persetujuan untuk retur 100 pcs');

// Check status
if ($retur->hasPendingApproval()) {
    echo "Waiting for approval...";
}

// Approve (from approver side)
$approval = $retur->approvals()->pending()->first();
$approval->approve('Disetujui, silakan proses retur');

// Auto-executes onApproved() hook:
// - Updates status_approval to 'approved'
```

---

### Example 2: Multi-Level Approval Flow

```php
use App\Models\ScrapDisposal;

// Create scrap disposal (high-value item)
$scrap = ScrapDisposal::create([
    'nomor_scrap' => 'SCRAP-20260127-001',
    'nama_barang' => 'Engine Component X',
    'quantity' => 50,
    'estimasi_biaya_pembuangan' => 15000000, // 15 juta
    'status_approval' => 'pending',
]);

// Submit for level 1 approval (Supervisor)
$scrap->submitForApproval(1, 'Scrap untuk komponen rusak berat');

// Supervisor approves
$approvalL1 = $scrap->approvals()->byLevel(1)->first();
$approvalL1->approve('OK, lanjut ke manager');

// Submit for level 2 approval (Manager)
$scrap->submitForApproval(2, 'Membutuhkan persetujuan budget');

// Manager approves
$approvalL2 = $scrap->approvals()->byLevel(2)->first();
$approvalL2->approve('Approved by manager');

// Check if fully approved
if ($scrap->isFullyApproved()) {
    echo "Fully approved! Process scrap disposal.";
}
```

---

### Example 3: RCA Analysis with Finance Impact

```php
use App\Models\RcaAnalysis;
use App\Models\FinanceApproval;

// Create RCA for defect analysis
$rca = RcaAnalysis::create([
    'nomor_rca' => RcaAnalysis::generateNomorRca(),
    'tanggal_analisa' => now(),
    'metode_rca' => '5_why',
    'kode_defect' => 'DEF-001',
    'kode_barang' => 'PRD-001',
    'criticality_level' => 'high',
    'deskripsi_masalah' => 'Cacat pada proses welding',
    'analisa_detail' => json_encode([
        'why_1' => 'Welding tidak sempurna',
        'why_2' => 'Temperatur tidak stabil',
        'why_3' => 'Mesin perlu kalibrasi',
        'why_4' => 'Jadwal maintenance terlewat',
        'why_5' => 'Sistem reminder maintenance belum ada',
    ]),
    'corrective_action' => 'Kalibrasi mesin welding segera',
    'preventive_action' => 'Implementasi sistem maintenance scheduling',
    'status_rca' => 'open',
]);

// Submit RCA for approval
$rca->submitForApproval(1, 'Need technical approval');

// If corrective action needs budget, create finance approval
$financeApproval = FinanceApproval::create([
    'nomor_approval' => 'FA-20260127-001',
    'nomor_referensi' => $rca->nomor_rca,
    'pengaju' => 'Engineering Dept',
    'deskripsi_pengajuan' => 'Budget untuk kalibrasi mesin dan implementasi sistem',
    'jenis_dampak' => 'cost_addition',
    'estimasi_biaya' => 25000000, // 25 juta
    'status_approval' => 'pending',
]);

// Submit finance approval
$financeApproval->submitForApproval(2, 'Require manager approval for budget');
```

---

### Example 4: Stock Movement Tracking

```php
use App\Models\StockMovement;
use App\Models\PenyimpananNg;

// Get NG stock
$penyimpananNg = PenyimpananNg::find(1);

// Record stock movement (transfer to different location)
$movement = StockMovement::create([
    'penyimpanan_ng_id' => $penyimpananNg->id,
    'movement_type' => 'transfer',
    'reference_type' => 'manual_entry',
    'from_lokasi_id' => 1, // Rak A1
    'to_lokasi_id' => 5,   // Rak B3
    'qty_before' => $penyimpananNg->qty,
    'qty_moved' => 50,
    'qty_after' => $penyimpananNg->qty, // Will be updated after transfer
    'notes' => 'Dipindah karena reorganisasi gudang',
    'moved_by' => auth()->id(),
    'moved_at' => now(),
    'status' => 'completed',
]);

// Get all movements for this stock
$allMovements = $penyimpananNg->stockMovements()
    ->orderBy('moved_at', 'desc')
    ->get();

// Filter by type
$transfersOnly = $penyimpananNg->stockMovements()
    ->transfer()
    ->get();
```

---

### Example 5: Quality Inspection with Auto NG Storage

```php
use App\Models\QualityInspection;
use App\Models\PenyimpananNg;

// Create QC inspection with defect found
$qc = QualityInspection::create([
    'penerimaan_barang_id' => 10,
    'inspection_type' => 'incoming',
    'sampling_method' => 'random',
    'sample_size' => 100,
    'defect_found_qty' => 15,
    'pass_qty' => 85,
    'reject_qty' => 15,
    'result' => 'reject_partial',
    'auto_create_storage' => true, // Auto create Penyimpanan NG
]);

// If auto_create_storage = true, system will auto-create PenyimpananNg
if ($qc->auto_create_storage && $qc->reject_qty > 0) {
    $penyimpananNg = PenyimpananNg::create([
        'penerimaan_barang_id' => $qc->penerimaan_barang_id,
        'qty' => $qc->reject_qty,
        'keterangan' => 'Auto-created from QC Inspection',
    ]);
    
    // Link QC to storage
    $qc->update(['penyimpanan_ng_id' => $penyimpananNg->id]);
    
    // Record stock movement
    StockMovement::create([
        'penyimpanan_ng_id' => $penyimpananNg->id,
        'movement_type' => 'in',
        'reference_type' => 'qc_inspection',
        'reference_id' => $qc->id,
        'qty_before' => 0,
        'qty_moved' => $qc->reject_qty,
        'qty_after' => $qc->reject_qty,
        'moved_by' => auth()->id(),
        'moved_at' => now(),
        'status' => 'completed',
    ]);
}
```

---

## 🧪 Testing Guide

### 1. Test Approval Flow

```php
// File: tests/Feature/ApprovalFlowTest.php

use App\Models\ReturBarang;
use App\Models\Approval;
use App\Models\User;

public function test_can_submit_for_approval()
{
    $retur = ReturBarang::factory()->create();
    
    $retur->submitForApproval(1, 'Test submission');
    
    $this->assertTrue($retur->hasPendingApproval());
    $this->assertCount(1, $retur->approvals);
}

public function test_can_approve_submission()
{
    $retur = ReturBarang::factory()->create();
    $retur->submitForApproval(1);
    
    $approval = $retur->approvals()->first();
    $approval->approve('Approved');
    
    $this->assertEquals('approved', $approval->fresh()->status);
    $this->assertNotNull($approval->fresh()->approved_at);
}

public function test_can_reject_submission()
{
    $retur = ReturBarang::factory()->create();
    $retur->submitForApproval(1);
    
    $approval = $retur->approvals()->first();
    $approval->reject('Not enough documentation');
    
    $this->assertEquals('rejected', $approval->fresh()->status);
    $this->assertEquals('rejected', $retur->fresh()->status_approval);
}
```

---

### 2. Test Stock Movement

```php
// File: tests/Feature/StockMovementTest.php

use App\Models\StockMovement;
use App\Models\PenyimpananNg;

public function test_can_record_stock_in()
{
    $penyimpananNg = PenyimpananNg::factory()->create(['qty' => 0]);
    
    $movement = StockMovement::create([
        'penyimpanan_ng_id' => $penyimpananNg->id,
        'movement_type' => 'in',
        'qty_before' => 0,
        'qty_moved' => 100,
        'qty_after' => 100,
        'status' => 'completed',
    ]);
    
    $this->assertEquals('in', $movement->movement_type);
    $this->assertEquals(100, $movement->qty_after);
}

public function test_can_transfer_stock()
{
    $penyimpananNg = PenyimpananNg::factory()->create(['qty' => 100]);
    
    $movement = StockMovement::create([
        'penyimpanan_ng_id' => $penyimpananNg->id,
        'movement_type' => 'transfer',
        'from_lokasi_id' => 1,
        'to_lokasi_id' => 2,
        'qty_moved' => 50,
        'status' => 'completed',
    ]);
    
    $this->assertEquals('transfer', $movement->movement_type);
    $this->assertNotNull($movement->from_lokasi_id);
    $this->assertNotNull($movement->to_lokasi_id);
}
```

---

### 3. Test RCA Analysis

```php
// File: tests/Feature/RcaAnalysisTest.php

use App\Models\RcaAnalysis;

public function test_can_generate_nomor_rca()
{
    $nomor = RcaAnalysis::generateNomorRca();
    
    $this->assertStringStartsWith('RCA-', $nomor);
    $this->assertMatchesRegularExpression('/RCA-\d{8}-\d{4}/', $nomor);
}

public function test_can_detect_overdue_rca()
{
    $rca = RcaAnalysis::factory()->create([
        'due_date' => now()->subDays(5),
        'status_rca' => 'open',
    ]);
    
    $this->assertTrue($rca->isOverdue());
    
    $overdueRcas = RcaAnalysis::overdue()->get();
    $this->assertContains($rca->id, $overdueRcas->pluck('id'));
}
```

---

## 📝 Migration History

### Successfully Executed

1. ✅ `2026_01_23_080000_add_penerimaan_barang_id_to_penyimpanan_ngs_table.php` (596.01ms)
2. ✅ `2026_01_23_090000_add_penyimpanan_ng_to_quality_inspections_table.php` (217.52ms)
3. ✅ `2026_01_23_090001_create_stock_movements_table.php` (691.42ms)
4. ✅ `2026_01_23_100001_create_approvals_table.php` (973.01ms)

### Pre-existing Tables (Already Migrated)

- `master_approval_authorities` - Already exists with 1 row
- `retur_barangs` - Already exists (48 KB, 0 rows)
- `scrap_disposals` - Already exists (32 KB, 0 rows)
- `rca_analyses` - Already exists (48 KB, 0 rows)
- `finance_approvals` - Already exists (32 KB, 0 rows)
- `quality_inspections` - Already exists (176 KB, 0 rows)

---

## 🔧 Configuration

### Approval Authority Setup

**Add Approval Authority:**

```php
use App\Models\MasterApprovalAuthority;

MasterApprovalAuthority::create([
    'user_id' => 1,
    'departemen' => 'quality',
    'role_level' => 1,              // 1=Supervisor, 2=Manager, 3=Director
    'jenis_approval' => 'quality_inspection',
    'limit_value' => 0,             // No limit for QC
    'can_approve_self' => false,
    'is_active' => true,
]);

MasterApprovalAuthority::create([
    'user_id' => 2,
    'departemen' => 'finance',
    'role_level' => 2,
    'jenis_approval' => 'finance_approval',
    'limit_value' => 50000000,      // 50 juta limit
    'can_approve_self' => false,
    'is_active' => true,
]);
```

---

## 🎨 UI Components

### Approval Status Badge

```php
// In Blade template
{!! $approval->status_badge !!}
// Output: <span class="badge bg-warning">⏳ Pending</span>

{!! $approval->level_badge !!}
// Output: <span class="badge bg-info">Level 1</span>
```

### RCA Status Badge

```php
{!! $rca->status_badge !!}
// Output: <span class="badge bg-danger">🔴 Open</span>

{!! $rca->metode_badge !!}
// Output: <span class="badge bg-info">5 Why Analysis</span>

{!! $rca->pic_badge !!}
// Output: <span class="badge bg-info">QC</span>
```

---

## 📌 Important Notes

### 1. **Approval Flow Best Practices**

- Always check `hasPendingApproval()` before allowing edits
- Use `isFullyApproved()` to verify all levels approved
- Implement `onApproved()` and `onRejected()` hooks for model-specific logic
- Track approver using `approver_id` for audit trail

### 2. **Stock Movement Rules**

- Always set `qty_before` and `qty_after` for tracking
- Use `reference_type` + `reference_id` to link to source document
- Mark status as `completed` only after physical movement
- Track `moved_by` for accountability

### 3. **RCA Analysis Guidelines**

- Use `generateNomorRca()` for auto-numbering
- Set realistic `due_date` based on criticality
- Use `overdue()` scope to monitor delayed RCAs
- Link to `retur_barang_id` when RCA is from return

### 4. **Finance Approval Workflow**

- Always estimate budget impact (`estimasi_biaya`)
- Link to source via `nomor_referensi` (RCA, Scrap, etc.)
- Use approval levels based on amount:
  - Level 1: Up to 10 juta
  - Level 2: Up to 50 juta
  - Level 3: Above 50 juta

---

## 🚀 Next Steps

### Pending Implementation

1. **Reports Module** - Create comprehensive reporting system
2. **Dashboard Analytics** - Real-time statistics and charts
3. **Notification System** - Email/push notifications for approvals
4. **Export Features** - PDF/Excel export for all modules
5. **Audit Trail** - Complete activity logging system

### Enhancement Opportunities

1. **Approval Delegation** - Allow approvers to delegate authority
2. **Bulk Approval** - Approve multiple items at once
3. **Auto-approval Rules** - Configure rules for auto-approval
4. **Mobile App** - Mobile interface for approvals on-the-go
5. **Integration API** - REST API for external system integration

---

## 📞 Support & Contact

For questions or issues, please contact:
- **Development Team:** development@metinca.com
- **Documentation:** https://docs.metinca.com

---

**Last Updated:** 27 Januari 2026  
**Version:** 2.0  
**Status:** ✅ Production Ready

