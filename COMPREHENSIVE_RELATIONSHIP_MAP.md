# COMPREHENSIVE RELATIONSHIP MAP
*Complete Entity Relationship Documentation for Metinca Starter App*

## 🚀 AUTO-GENERATION SYSTEMS

### Document Number Auto-Generation
All workflow models now automatically generate unique document numbers using boot methods:

| Model | Prefix | Format | Example |
|-------|--------|--------|---------|
| CustomerComplaint | CC- | CC-YYYYMM-######## | CC-202412-00000001 |
| DokumenRetur | DR- | DR-YYYYMM-######## | DR-202412-00000001 |
| WarehouseVerification | WV- | WV-YYYYMM-######## | WV-202412-00000001 |
| QualityReinspection | QR- | QR-YYYYMM-######## | QR-202412-00000001 |
| ProductionRework | PR- | PR-YYYYMM-######## | PR-202412-00000001 |
| FinalQualityCheck | FQ- | FQ-YYYYMM-######## | FQ-202412-00000001 |
| ReturnShipment | RS- | RS-YYYYMM-######## | RS-202412-00000001 |

## 🔗 PRIMARY WORKFLOW CHAIN

### Linear Workflow Progression
```
CustomerComplaint → DokumenRetur → WarehouseVerification → QualityReinspection → ProductionRework → FinalQualityCheck → ReturnShipment
```

### Relationship Structure
1. **CustomerComplaint** (1) → **DokumenRetur** (1)
2. **DokumenRetur** (1) → **WarehouseVerification** (1)
3. **WarehouseVerification** (1) → **QualityReinspection** (1)
4. **QualityReinspection** (1) → **ProductionRework** (1)
5. **ProductionRework** (1) → **FinalQualityCheck** (1)
6. **FinalQualityCheck** (1) → **ReturnShipment** (1)

## 📋 MASTER DATA RELATIONSHIPS

### MasterCustomer Relationships
- **Has Many:** CustomerComplaints, DokumenReturs, ReturnShipments
- **Statistics:** Total complaints, completion rate, resolution time
- **Features:** Customer performance analysis, workflow tracking

### MasterProduk Relationships
- **Belongs To:** MasterVendor
- **Has Many:** CustomerComplaints, DokumenReturs, QualityReinspections, ProductionReworks
- **Statistics:** Quality score, complaint rate, rework statistics

### MasterDefect Relationships
- **Has Many:** QualityReinspections, ProductionReworks
- **Analytics:** Occurrence statistics, rework success rate, affected products

### MasterDisposisi Relationships
- **Belongs To:** PenyimpananNg, MasterLokasiGudang
- **Has Many:** QualityReinspections, ProductionReworks, FinalQualityChecks
- **Analytics:** Usage statistics, success rate, approval workflow

## 👥 USER & ROLE RELATIONSHIPS

### User Model Enhanced Relationships
- **Division-Based Access:** Role-specific workflow visibility
- **Workload Tracking:** Task counts per workflow stage
- **Performance Metrics:** User efficiency analytics

### Role-Based Workflow Access
| Role | Accessible Workflow Stages |
|------|---------------------------|
| admin | All stages (overview) |
| staff-exim | Customer Complaints, Return Shipments |
| warehouse | Warehouse Verification, Document Management |
| quality | Quality Reinspection, Final Quality Check |
| production | Production Rework, Manufacturing |

## 🔧 ADVANCED RELATIONSHIP METHODS

### Chain Navigation Methods
Each workflow model includes methods to navigate the complete chain:

```php
// Get original complaint from any workflow stage
$complaint = $anyWorkflowModel->getOriginalComplaint();

// Get next workflow stage
$nextStage = $currentModel->getNextWorkflowStage();

// Get previous workflow stage
$previousStage = $currentModel->getPreviousWorkflowStage();

// Get complete workflow chain
$completeChain = $currentModel->getCompleteWorkflowChain();
```

### Status Tracking Methods
```php
// Check workflow stage completion
$isCompleted = $model->isWorkflowStageCompleted();

// Get workflow progress percentage
$progress = $complaint->getWorkflowProgress();

// Get current workflow stage
$currentStage = $complaint->getCurrentWorkflowStage();
```

## 📊 ANALYTICS & REPORTING FEATURES

### Master Data Statistics
- **Customer Analytics:** Performance metrics, resolution times
- **Product Quality:** Defect rates, complaint analysis
- **Defect Tracking:** Occurrence patterns, resolution success
- **Disposition Effectiveness:** Usage analytics, approval workflows

### Workflow Performance
- **Stage Duration:** Time tracking per workflow stage
- **Bottleneck Detection:** Identify slow processes
- **User Productivity:** Role-based performance metrics
- **Customer Satisfaction:** Rating and feedback analysis

## 🎯 SCOPE QUERIES

### Powerful Query Scopes
Each model includes comprehensive scopes for filtering:

```php
// Workflow stage filtering
CustomerComplaint::pending()->get();
DokumenRetur::completed()->get();
QualityReinspection::failed()->get();

// Date-based filtering
ReturnShipment::thisWeek()->get();
ProductionRework::thisMonth()->get();

// User-based filtering
$userWorkload = CustomerComplaint::forUser($userId)->get();

// Chain-based filtering
$customerReturns = ReturnShipment::byCustomer($customerId)->get();
```

## 🔄 WORKFLOW INTEGRATION

### Complete Chain Loading
```php
// Load complete workflow chain with one query
$complaintWithChain = CustomerComplaint::with([
    'dokumenRetur.warehouseVerification.qualityReinspection.productionRework.finalQualityCheck.returnShipment'
])->find($id);
```

### Auto-Number Generation
All models automatically generate sequential numbers on creation:
- Monthly reset for easy categorization
- Unique constraints prevent duplicates
- Consistent format across all document types

## 📈 KEY BENEFITS

### Data Integrity
- ✅ Complete relationship mapping
- ✅ Foreign key constraints
- ✅ Automatic number generation
- ✅ Chain validation

### Performance
- ✅ Optimized queries with eager loading
- ✅ Efficient scope-based filtering
- ✅ Indexed relationship keys
- ✅ Cached statistics calculations

### User Experience
- ✅ Role-based access control
- ✅ Division-specific workflows
- ✅ Real-time progress tracking
- ✅ Comprehensive reporting

### Analytics
- ✅ Customer performance metrics
- ✅ Product quality tracking
- ✅ Defect pattern analysis
- ✅ Workflow efficiency monitoring

---

## 🚦 IMPLEMENTATION STATUS

| Component | Status | Description |
|-----------|--------|-------------|
| Auto-Generation | ✅ Complete | All document numbers auto-generate |
| Workflow Chain | ✅ Complete | Linear progression fully implemented |
| Master Data | ✅ Complete | All master relationships connected |
| User Roles | ✅ Complete | Division-specific access implemented |
| Analytics | ✅ Complete | Statistics and reporting ready |
| Scopes | ✅ Complete | Query filtering available |

**Total Relationship Count:** 50+ relationships across 11+ models  
**Auto-Generated Numbers:** 7 document types  
**Workflow Stages:** 7 sequential stages  
**Master Data Entities:** 4+ connected masters  

*System ready for complete workflow tracking and auto-generation!*