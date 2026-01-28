# 🚀 Quick Reference Guide - METINCA Starter App

> **Panduan Cepat untuk Developer**  
> Version 2.0 | 27 Januari 2026

---

## ⚡ Quick Start

### 1. Using Approval System

```php
use App\Traits\HasApproval;

// In your model
class YourModel extends Model
{
    use HasApproval;
    
    public function getApprovalType(): string
    {
        return 'your_module';
    }
}

// Usage
$model->submitForApproval(1, 'Need approval');
$model->hasPendingApproval();
$model->isFullyApproved();
```

---

## 📋 Available Models & Controllers

| Model | Controller | Route Name | Status |
|-------|------------|------------|--------|
| `Approval` | `ApprovalController` | `approvals.*` | ✅ |
| `MasterApprovalAuthority` | `MasterApprovalAuthorityController` | `master-approval-authority.*` | ✅ |
| `QualityInspection` | `QualityInspectionController` | `quality-inspections.*` | ✅ |
| `ReturBarang` | `ReturBarangController` | `retur-barang.*` | ✅ |
| `ScrapDisposal` | `ScrapDisposalController` | `scrap-disposals.*` | ✅ |
| `RcaAnalysis` | `RcaAnalysisController` | `rca-analyses.*` | ✅ |
| `FinanceApproval` | `FinanceApprovalController` | `finance-approvals.*` | ✅ |
| `StockMovement` | - | - | ⏳ |
| `PenyimpananNg` | `PenyimpananNgController` | `penyimpanan-ng.*` | ✅ |

---

## 🔗 Key Relationships

### Approval Flow
```php
// Any model with HasApproval trait
$model->approvals()              // All approvals
$model->pendingApprovals()       // Pending only
$model->approvedApprovals()      // Approved only

// Approval model
$approval->approvable()          // Polymorphic to source model
$approval->approver()            // User who approved
$approval->submitter()           // User who submitted
```

### Warehouse System
```php
// PenyimpananNg (Central hub)
$penyimpanan->lokasiGudang()           // Storage location
$penyimpanan->penerimaanBarang()       // Source receiving
$penyimpanan->qualityInspection()      // Related QC
$penyimpanan->stockMovements()         // All movements
$penyimpanan->disposisiAssignments()   // Dispositions

// QualityInspection
$qc->penyimpananNg()             // Auto-created storage
$qc->penerimaanBarang()          // Source receiving

// StockMovement
$movement->penyimpananNg()       // NG stock
$movement->fromLokasi()          // From location
$movement->toLokasi()            // To location
$movement->user()                // Who moved
```

### Retur & RCA
```php
// ReturBarang
$retur->vendor()                 // Supplier
$retur->produk()                 // Product
$retur->rcaAnalyses()            // Related RCA
$retur->approvals()              // Approval history

// RcaAnalysis
$rca->masterDefect()             // Defect type
$rca->masterProduk()             // Product
$rca->returBarang()              // Source retur (optional)
$rca->approvals()                // Approval history

// ScrapDisposal
$scrap->user()                   // Creator
$scrap->masterProduk()           // Product
$scrap->approvals()              // Approval history

// FinanceApproval
$finance->user()                 // Requestor
$finance->rcaAnalysis()          // Related RCA
$finance->approvals()            // Approval history
```

---

## 🎯 Common Tasks

### Submit for Approval
```php
$model->submitForApproval(
    $level = 1,                  // 1, 2, or 3
    $notes = 'Please review'
);
```

### Approve/Reject
```php
$approval = Approval::find($id);
$approval->approve('Looks good');
$approval->reject('Need more info');
```

### Check Approval Status
```php
$model->hasPendingApproval();          // Boolean
$model->hasPendingApproval($level);    // At specific level
$model->isApprovedAt($level);          // Boolean
$model->isFullyApproved();             // All levels approved
```

### Cancel Approvals
```php
$model->cancelApprovals();             // Cancel all pending
```

### Record Stock Movement
```php
StockMovement::create([
    'penyimpanan_ng_id' => $id,
    'movement_type' => 'transfer',     // in, out, transfer, adjustment
    'from_lokasi_id' => 1,
    'to_lokasi_id' => 2,
    'qty_before' => 100,
    'qty_moved' => 50,
    'qty_after' => 50,
    'moved_by' => auth()->id(),
    'moved_at' => now(),
    'status' => 'completed',
]);
```

### Generate Auto Numbers
```php
ReturBarang::generateNoRetur();        // RET-202601-00001
RcaAnalysis::generateNomorRca();       // RCA-20260127-0001
```

---

## 🔍 Useful Scopes

### Approval Scopes
```php
Approval::pending()->get();
Approval::approved()->get();
Approval::rejected()->get();
Approval::forApprover($userId)->get();
Approval::byLevel($level)->get();
```

### RCA Scopes
```php
RcaAnalysis::active()->get();          // Open or in_progress
RcaAnalysis::overdue()->get();         // Past due date
RcaAnalysis::byStatus($status)->get();
RcaAnalysis::byDefect($code)->get();
RcaAnalysis::byProduk($code)->get();
```

### Stock Movement Scopes
```php
StockMovement::in()->get();
StockMovement::out()->get();
StockMovement::transfer()->get();
StockMovement::completed()->get();
```

### Scrap & Finance Scopes
```php
ScrapDisposal::pending()->get();
ScrapDisposal::approved()->get();
ScrapDisposal::rejected()->get();

FinanceApproval::pending()->get();
FinanceApproval::approved()->get();
FinanceApproval::rejected()->get();
```

---

## 🎨 Blade Helpers

### Badges
```php
{!! $approval->status_badge !!}        // Status badge
{!! $approval->level_badge !!}         // Level badge
{!! $rca->status_badge !!}             // RCA status
{!! $rca->metode_badge !!}             // RCA method
{!! $rca->pic_badge !!}                // PIC badge
{!! $movement->movement_type_badge !!} // Movement type
{!! $movement->status_badge !!}        // Movement status
```

### Check RCA Overdue
```php
@if($rca->isOverdue())
    <span class="badge bg-danger">Overdue!</span>
@endif
```

---

## 📊 Common Queries

### Get Pending Approvals for User
```php
$approvals = Approval::forApprover(auth()->id())
    ->pending()
    ->with('approvable')
    ->get();
```

### Get All Returns Needing Approval
```php
$returs = ReturBarang::whereHas('approvals', function($q) {
    $q->pending();
})->get();
```

### Get Overdue RCAs
```php
$overdueRcas = RcaAnalysis::overdue()
    ->with(['masterDefect', 'masterProduk'])
    ->get();
```

### Get Stock Movement History
```php
$history = StockMovement::where('penyimpanan_ng_id', $id)
    ->orderBy('moved_at', 'desc')
    ->with(['user', 'fromLokasi', 'toLokasi'])
    ->get();
```

### Get Finance Approvals by Budget Range
```php
$highValue = FinanceApproval::where('estimasi_biaya', '>', 50000000)
    ->pending()
    ->get();
```

---

## ⚙️ Configuration

### Approval Levels
- **Level 1:** Supervisor (up to 10M)
- **Level 2:** Manager (up to 50M)
- **Level 3:** Director (above 50M)

### Movement Types
- `in` - Stock masuk
- `out` - Stock keluar
- `transfer` - Perpindahan lokasi
- `adjustment` - Adjustment manual

### RCA Methods
- `5_why` - 5 Why Analysis
- `fishbone` - Fishbone Diagram
- `kombinasi` - Combination

### Criticality Levels
- `low` - Low priority
- `medium` - Medium priority
- `high` - High priority
- `critical` - Critical/urgent

---

## 🧪 Testing Commands

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter ApprovalFlowTest

# Run feature tests
php artisan test tests/Feature

# Run with coverage
php artisan test --coverage
```

---

## 🛠️ Artisan Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Run migrations
php artisan migrate
php artisan migrate:fresh --seed

# Generate auto numbers (via tinker)
php artisan tinker
>>> ReturBarang::generateNoRetur()
>>> RcaAnalysis::generateNomorRca()

# Check database
php artisan db:show
php artisan db:show --counts
```

---

## 📝 Code Snippets

### Add Approval to Existing Model

```php
// 1. Add trait to model
use App\Traits\HasApproval;

class YourModel extends Model
{
    use HasApproval;
    
    // Required method
    public function getApprovalType(): string
    {
        return 'your_model_type';
    }
    
    // Optional hooks
    public function onApproved(int $level): void
    {
        if ($this->isFullyApproved()) {
            $this->update(['status' => 'approved']);
        }
    }
    
    public function onRejected(int $level, ?string $reason): void
    {
        $this->update(['status' => 'rejected']);
    }
}

// 2. Usage in controller
public function submit($id)
{
    $model = YourModel::findOrFail($id);
    $model->submitForApproval(1, request('notes'));
    
    return redirect()->back()->with('success', 'Submitted for approval');
}
```

---

### Create Stock Movement

```php
// In controller or observer
use App\Models\StockMovement;

public function recordMovement($penyimpananNg, $type, $qty)
{
    return StockMovement::create([
        'penyimpanan_ng_id' => $penyimpananNg->id,
        'movement_type' => $type,
        'reference_type' => 'manual_entry',
        'qty_before' => $penyimpananNg->qty,
        'qty_moved' => $qty,
        'qty_after' => $penyimpananNg->qty + ($type === 'in' ? $qty : -$qty),
        'moved_by' => auth()->id(),
        'moved_at' => now(),
        'status' => 'completed',
    ]);
}
```

---

### Handle Approval in Controller

```php
use App\Models\Approval;

public function approve($id)
{
    $approval = Approval::findOrFail($id);
    
    // Check permission
    if (!$approval->canBeApprovedBy(auth()->id())) {
        abort(403, 'Not authorized to approve');
    }
    
    // Approve
    $approval->approve(request('notes'));
    
    return redirect()->back()->with('success', 'Approved successfully');
}

public function reject($id)
{
    $approval = Approval::findOrFail($id);
    
    if (!$approval->canBeApprovedBy(auth()->id())) {
        abort(403, 'Not authorized to reject');
    }
    
    $approval->reject(request('reason'));
    
    return redirect()->back()->with('success', 'Rejected');
}
```

---

## 🔐 Security Tips

1. **Always validate approver permissions:**
   ```php
   if (!$approval->canBeApprovedBy(auth()->id())) {
       abort(403);
   }
   ```

2. **Check pending approvals before editing:**
   ```php
   if ($model->hasPendingApproval()) {
       return back()->with('error', 'Cannot edit while pending approval');
   }
   ```

3. **Use soft deletes for audit trail:**
   ```php
   use SoftDeletes;
   ```

4. **Log important actions:**
   ```php
   $model->activityLogs()->create([
       'action' => 'approved',
       'user_id' => auth()->id(),
       'description' => 'Approved by manager',
   ]);
   ```

---

## 📞 Need Help?

- 📖 **Full Documentation:** `IMPLEMENTATION_DOCUMENTATION.md`
- 🐛 **Bug Reports:** development@metinca.com
- 💡 **Feature Requests:** submit via issue tracker

---

**Last Updated:** 27 Januari 2026  
**Quick Ref Version:** 2.0
