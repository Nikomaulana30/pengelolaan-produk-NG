# PPIC Recap Report Integration with RCA & Finance Approval

## Executive Summary

The **Laporan Recap NG (Not Good)** report should integrate with both **RCA Analysis** and **Finance Approval** modules to create a complete traceability chain from defect discovery through financial impact management. This document outlines the recommended architecture and implementation strategy.

---

## 1. Current System Architecture

### Data Flow Relationships

```
┌─────────────────────────────────────────────────────────────┐
│               QUALITY INSPECTION (QC)                       │
│  - kode_barang (Master Produk reference)                   │
│  - kode_defect (Master Defect reference)                   │
│  - hasil: OK / NG                                           │
└────────┬────────────────────────────────────────────────────┘
         │
         ├─→ NG Cases trigger two parallel paths:
         │
         ├─────────────────────────────────┬──────────────────────────────┐
         │                                 │                              │
         ▼                                 ▼                              ▼
  ┌──────────────────┐           ┌──────────────────┐      ┌──────────────────────┐
  │  RCA ANALYSIS    │           │  RETUR BARANG    │      │  SCRAP DISPOSAL      │
  │ (Root Cause)     │           │ (Return Process) │      │  (Scrap Process)     │
  │                  │           │                  │      │                      │
  │ - nomor_rca      │           │ - no_retur       │      │ - nomor_disposal     │
  │ - kode_defect    │           │ - produk_id      │      │ - kode_produk        │
  │ - kode_barang    │           │ - alasan_retur   │      │ - jumlah_unit        │
  │ - status_rca     │           │ - status_approval│      │ - estimasi_biaya     │
  └────────┬─────────┘           └────────┬─────────┘      └────────┬─────────────┘
           │                               │                       │
           │  Approved RCA                 │  Approved Retur      │  Disposal Created
           │  nomor_rca → nomor_referensi  │  → Finance Claim     │  → Finance Claim
           │                               │                      │
           └───────────────┬───────────────┴──────────────────────┘
                           │
                    Finance Impact
                           │
                           ▼
           ┌───────────────────────────────────┐
           │   FINANCE APPROVAL (PPIC)         │
           │ (Budget & Cost Approval)         │
           │                                   │
           │ - nomor_approval                 │
           │ - nomor_referensi (RCA link)     │
           │ - estimasi_biaya                 │
           │ - jenis_dampak (claim/retur/etc) │
           │ - budget_approval                │
           │ - status_approval                │
           └───────────────────────────────────┘
```

---

## 2. Current Database Tables & Keys

### Master Tables
```sql
-- Quality Inspection Records
table: quality_inspections
- kode_barang (FK → master_products.kode_produk)
- kode_defect (FK → master_defects.kode_defect)
- hasil (OK/NG)
- status_approval (pending/approved/rejected)

-- Root Cause Analysis
table: rca_analyses
- nomor_rca (PRIMARY KEY - unique identifier)
- kode_barang (FK → master_products.kode_produk)
- kode_defect (FK → master_defects.kode_defect)
- retur_barang_id (FK → retur_barangs.id)
- status_rca (open/in_progress/closed)

-- Return Goods
table: retur_barangs
- no_retur (PRIMARY KEY - unique identifier)
- produk_id (FK → master_products.id)
- alasan_retur (reason code)
- status_approval (pending/approved/rejected)

-- Finance Approval (PPIC)
table: finance_approvals
- nomor_approval (PRIMARY KEY)
- nomor_referensi (FOREIGN KEY → rca_analyses.nomor_rca)
- jenis_dampak (claim/retur/scrap/rework_cost/tidak_ada)
- estimasi_biaya (numeric)
- budget_approval (dalam_budget/melebihi_budget/perlu_persetujuan_lebih_tinggi)
- status_approval (pending/approved/rejected/need_revision)

-- Scrap Disposal
table: scrap_disposals
- nomor_disposal (PRIMARY KEY)
- kode_produk (FK → master_products.kode_produk)
- estimasi_biaya (numeric)
- status (pending/approved/disposed)
```

---

## 3. Recommended Integration Strategy

### 3.1 Laporan Recap Should Display

The PPIC Recap Report (`laporan-recap.blade.php`) should show:

#### A. Current Data (✅ Already Implemented)
- Total NG units by location
- Rework, Retur, Scrap counts
- Cost impact by category
- Top defects ranking
- Trend charts by location

#### B. New Integration Points (⏳ Recommended Implementation)

1. **RCA Status Breakdown**
   - Total NG cases with RCA Analysis created
   - RCA closed vs. still open
   - Effectiveness: "NG Incidents Resolved" percentage
   
2. **Finance Approval Status**
   - Budget approvals awaiting decision
   - Total approved cost impact
   - Budget vs. actual variance
   - Approval bottlenecks (pending since X days)

3. **Cross-Reference Links**
   - Click defect → View RCA analyses for that defect
   - Click location → View RCA/Finance approvals from that location
   - Click time period → Show all related RCA/Finance records

4. **Key Metrics**
   - Average time: NG → RCA Closed
   - Average time: RCA Closed → Finance Approved
   - Success rate: RCA created within 24 hours of NG
   - Approval rate by department

---

## 4. Implementation Plan

### Phase 1: Add RCA Analysis Summary Section to Report

**File:** `resources/views/menu-sidebar/laporan-recap.blade.php`

**Changes:**
```php
// Add new section after "Top Defect" section

<!-- RCA Analysis Summary -->
<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>RCA Analysis Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded p-3 me-3">
                                    <i class="bi bi-file-earmark-text fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">RCA Created</h6>
                                    <h4 class="mb-0">{{ $rcaCount ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning text-white rounded p-3 me-3">
                                    <i class="bi bi-hourglass-split fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">In Progress</h6>
                                    <h4 class="mb-0">{{ $rcaInProgress ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded p-3 me-3">
                                    <i class="bi bi-check-circle fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Closed/Resolved</h6>
                                    <h4 class="mb-0">{{ $rcaClosed ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info text-white rounded p-3 me-3">
                                    <i class="bi bi-percent fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Resolution Rate</h6>
                                    <h4 class="mb-0">{{ $rcaResolutionRate ?? 0 }}%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <strong>ℹ Info:</strong> RCA Analysis mencatat akar penyebab dari setiap NG (Not Good).
                        <a href="{{ route('rca-analysis.index') }}" class="alert-link">Lihat RCA Analysis</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
```

### Phase 2: Add Finance Approval Summary Section

**File:** `resources/views/menu-sidebar/laporan-recap.blade.php`

**Changes:**
```php
<!-- Finance Approval Summary -->
<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-currency-dollar me-2"></i>Finance Approval Status (PPIC)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger text-white rounded p-3 me-3">
                                    <i class="bi bi-clock-history fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Pending</h6>
                                    <h4 class="mb-0">{{ $financePending ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded p-3 me-3">
                                    <i class="bi bi-check-circle fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Approved</h6>
                                    <h4 class="mb-0">{{ $financeApproved ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary text-white rounded p-3 me-3">
                                    <i class="bi bi-x-circle fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Rejected</h6>
                                    <h4 class="mb-0">{{ $financeRejected ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning text-white rounded p-3 me-3">
                                    <i class="bi bi-exclamation-triangle fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Exceeding Budget</h6>
                                    <h4 class="mb-0">{{ $financeExceedingBudget ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info text-white rounded p-3 me-3">
                                    <i class="bi bi-cash-stack fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total Approved Cost</h6>
                                    <h4 class="mb-0">Rp {{ number_format($totalApprovedCost ?? 0, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <strong>ℹ Info:</strong> Finance Approval mengelola dampak finansial dari NG (claim, retur, scrap, rework).
                        <a href="{{ route('ppic.approval.index') }}" class="alert-link">Lihat Finance Approval</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
```

### Phase 3: Update Controller to Fetch Aggregated Data

**File:** `app/Http/Controllers/` (Create New or Update Existing Reports Controller)

**New Method:** Create a Reports controller method that aggregates data:

```php
<?php

namespace App\Http\Controllers;

use App\Models\RcaAnalysis;
use App\Models\FinanceApproval;
use App\Models\QualityInspection;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function recapNG(Request $request)
    {
        // Get date range from filters
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tanggalSelesai = $request->input('tanggal_selesai', date('Y-m-d'));
        $lokasi = $request->input('lokasi', null);

        // === RCA ANALYSIS METRICS ===
        $rcaQuery = RcaAnalysis::whereBetween('tanggal_analisa', [$tanggalMulai, $tanggalSelesai]);
        
        $rcaCount = $rcaQuery->count();
        $rcaInProgress = $rcaQuery->where('status_rca', 'in_progress')->count();
        $rcaClosed = $rcaQuery->where('status_rca', 'closed')->count();
        $rcaResolutionRate = $rcaCount > 0 ? round(($rcaClosed / $rcaCount) * 100) : 0;

        // === FINANCE APPROVAL METRICS ===
        $financeQuery = FinanceApproval::whereBetween('tanggal_approval', [$tanggalMulai, $tanggalSelesai]);
        
        $financePending = $financeQuery->where('status_approval', 'pending')->count();
        $financeApproved = $financeQuery->where('status_approval', 'approved')->count();
        $financeRejected = $financeQuery->where('status_approval', 'rejected')->count();
        $financeExceedingBudget = $financeQuery
            ->where('budget_approval', 'melebihi_budget')
            ->orWhere('budget_approval', 'perlu_persetujuan_lebih_tinggi')
            ->count();
        
        $totalApprovedCost = $financeQuery
            ->where('status_approval', 'approved')
            ->sum('estimasi_biaya');

        return view('menu-sidebar.laporan-recap', compact(
            'rcaCount',
            'rcaInProgress',
            'rcaClosed',
            'rcaResolutionRate',
            'financePending',
            'financeApproved',
            'financeRejected',
            'financeExceedingBudget',
            'totalApprovedCost',
            // ... existing variables ...
        ));
    }
}
```

---

## 5. Data Relationship Mapping

### RCA ↔ Finance Approval Mapping

| RCA Table Field | Finance Approval Table Field | Purpose |
|-----------------|------------------------------|---------|
| nomor_rca | nomor_referensi | Link RCA to Finance Request |
| kode_barang | (via rcaAnalysis→masterProduk) | Product traceability |
| status_rca | (depends on jenis_dampak) | Determine impact type |
| penyebab_utama | deskripsi_pengajuan | Context for approval |
| corrective_action | catatan | Remediation tracking |

### Finance Approval ↔ RCA Analysis (Reverse)

```php
// In FinanceApproval Model (ALREADY IMPLEMENTED)
public function rcaAnalysis()
{
    return $this->belongsTo(RcaAnalysis::class, 'nomor_referensi', 'nomor_rca');
}
```

### Recommended Inverse Relationship

```php
// In RcaAnalysis Model (TO BE ADDED)
public function financeApproval()
{
    return $this->hasOne(FinanceApproval::class, 'nomor_referensi', 'nomor_rca');
}
```

---

## 6. Implementation Steps

### Step 1: Add Inverse Relationship to RcaAnalysis Model
```php
// In app/Models/RcaAnalysis.php - add after rcaAnalysis() method (if exists)
public function financeApproval()
{
    return $this->hasOne(FinanceApproval::class, 'nomor_referensi', 'nomor_rca');
}
```

### Step 2: Create/Update Reports Controller Method
- Create method `recapNG()` with aggregated data calculations
- Handle date range filtering
- Calculate metrics for RCA and Finance Approval

### Step 3: Update laporan-recap.blade.php View
- Add RCA Analysis Summary section
- Add Finance Approval Summary section
- Add info alerts with navigation links
- Wire up variables from controller

### Step 4: Add Routes (if needed)
```php
// In routes/web.php
Route::get('/reports/recap-ng', [ReportController::class, 'recapNG'])->name('reports.recap-ng');
```

### Step 5: Test Integration
- Filter by date range
- Verify RCA metrics display correctly
- Verify Finance Approval metrics display correctly
- Verify navigation links work

---

## 7. Benefits of This Integration

| Benefit | Impact |
|---------|--------|
| **Complete Traceability** | Track NG → RCA → Finance Approval → Resolution |
| **Management Visibility** | See financial impact of defects in real-time |
| **Process Efficiency** | Identify bottlenecks in RCA or approval workflow |
| **Data Integrity** | Links ensure consistency across modules |
| **Decision Making** | Budget trend analysis for future planning |
| **Compliance** | Complete audit trail of defect management |

---

## 8. Future Enhancements

1. **Dashboard Link** - Add metrics to Analytics Dashboard
2. **Email Alerts** - Notify when approval exceeds budget
3. **SLA Tracking** - Monitor RCA closure time vs target
4. **Vendor Impact** - Link to supplier defects for scorecarding
5. **Predictive Analysis** - Forecast cost impact based on trends
6. **Automated Actions** - Auto-escalate if RCA takes > 5 days

---

## 9. Mapping Summary

### Three Core Modules

```
┌──────────────────────┐
│  QUALITY INSPECTION  │ → Identifies NG cases
│     (Quality Check)  │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│  RCA ANALYSIS        │ → Analyzes root cause
│ (Root Cause)         │   nomor_rca: unique ID
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ FINANCE APPROVAL     │ → Approves financial impact
│   (PPIC Decision)    │   nomor_referensi = nomor_rca
└──────────────────────┘
```

### Report Integration

```
Laporan Recap NG displays:
├── Current metrics (existing)
│   ├── Total NG by location
│   ├── Rework/Retur/Scrap counts
│   ├── Top defects
│   └── Cost breakdown
│
├── RCA Status (NEW)
│   ├── Total RCA created
│   ├── In Progress count
│   ├── Closed/Resolved count
│   └── Resolution rate %
│
└── Finance Approval (NEW)
    ├── Pending approvals
    ├── Approved count
    ├── Rejected count
    ├── Exceeding budget count
    └── Total approved cost Rp
```

---

## 10. Configuration Checklist

- [ ] Add `financeApproval()` relationship to RcaAnalysis model
- [ ] Create Reports controller with `recapNG()` method
- [ ] Update `laporan-recap.blade.php` with RCA and Finance sections
- [ ] Update route for report controller
- [ ] Test date range filtering
- [ ] Test all navigation links
- [ ] Verify relationship queries execute correctly
- [ ] Clear application cache
- [ ] Verify no syntax errors

---

## Conclusion

The PPIC Recap Report should serve as a **central hub** for monitoring the complete quality-to-finance workflow. By integrating RCA Analysis and Finance Approval data, the report provides:

1. **Visibility** - See the full lifecycle of NG incidents
2. **Accountability** - Track who approved what and when
3. **Financial Impact** - Monitor cost implications in one place
4. **Process Optimization** - Identify efficiency opportunities

This integration maintains data integrity through proper relationship mapping while improving decision-making capability for management.
