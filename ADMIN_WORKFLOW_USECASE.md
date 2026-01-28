# 📋 USE CASE - Admin Workflow Management System

**Project**: Metinca Starter App  
**Date**: January 14, 2026  
**Version**: 1.0  
**Status**: Production Ready

---

## 🎯 Executive Summary

Sistem ini dirancang untuk mengelola workflow data warehouse, quality control, dan production planning dengan pembagian role yang jelas. Admin memiliki akses penuh untuk mengatur master data, mengelola user, dan memonitor keseluruhan sistem.

---

## 👥 ACTORS (Aktor)

| Actor | Role | Deskripsi |
|-------|------|-----------|
| **Admin** | Sistem Administrator | Mengelola master data, user, approval, dan reports |
| **PPIC Staff** | Production Planning | Input RCA data dan monitoring produksi |
| **Warehouse Staff** | Inventory Management | Input penerimaan, penyimpanan NG, scrap, retur |
| **Quality Staff** | Quality Control | Input inspeksi QC dan approval kualitas |

---

## 🔑 USE CASES

### ✅ UC-001: Admin Login & Dashboard

**Actor**: Admin  
**Precondition**: Admin memiliki akun aktif dengan email & password

**Main Flow**:
```
1. Admin membuka aplikasi → URL: localhost:8000/login
2. Enter email: admin@metinca.com
3. Enter password: admin123
4. Klik tombol Login
5. Sistem validasi kredensial di database users table
6. ✅ Login berhasil → Redirect ke Dashboard
7. Dashboard menampilkan:
   - Welcome message: "Welcome, Administrator"
   - Avatar dengan inisial "AD" (warna merah - admin badge)
   - Analytics overview (KPI dashboard)
   - Quick links ke semua menu
   - Menu sidebar dengan akses PENUH ke semua fitur
```

**Postcondition**: Admin dapat mengakses semua menu & fitur sistem

**Alternative Flow**:
```
Jika email/password salah:
  → Tampilkan error message "Invalid credentials"
  → Kembali ke halaman login

Jika akun inactive (is_active = false):
  → Tampilkan message "Account is inactive"
  → Hubungi administrator
```

---

### ✅ UC-002: Master Data Management

**Actor**: Admin  
**Precondition**: Admin sudah login

**Main Flow**:

#### **2.1 Create Master Produk**
```
1. Click Menu → DATA MASTER → Master Produk
2. Klik tombol "+ Tambah Produk"
3. Form muncul dengan field:
   - Kode Produk (unique)
   - Nama Produk
   - Kategori
   - Unit
   - Harga (optional)
4. Input data produk baru (misal: "PROD-001", "Bearing SKF")
5. Klik "Simpan"
6. Validasi:
   - Kode produk tidak boleh duplikat
   - Nama tidak boleh kosong
   - Unit harus dipilih
7. ✅ Data tersimpan → Sistem generate ID otomatis
8. Muncul notifikasi "Produk berhasil ditambahkan"
9. Redirect ke daftar produk
```

**Postcondition**: Master produk baru tersimpan di database (master_products)

---

#### **2.2 Edit Master Produk**
```
1. Di halaman daftar produk, pilih produk yang ingin diedit
2. Klik tombol "Edit"
3. Form pre-filled dengan data produk lama
4. Ubah data yang diperlukan (misal: ubah harga)
5. Klik "Update"
6. ✅ Perubahan tersimpan
7. Notifikasi: "Produk berhasil diperbarui"
```

**Postcondition**: Master produk diperbarui, referensi di dokumen lain tetap valid

---

#### **2.3 Delete Master Produk**
```
1. Di halaman daftar produk, pilih produk yang ingin dihapus
2. Klik tombol "Hapus"
3. Dialog konfirmasi: "Anda yakin ingin menghapus produk ini?"
4. Jika Klik "Ya":
   - Sistem check: apakah produk sudah digunakan di dokumen lain?
   - JIKA DIGUNAKAN: Tampilkan pesan "Tidak dapat dihapus, produk sudah digunakan"
   - JIKA BELUM DIGUNAKAN: Lanjut hapus
5. ✅ Produk dihapus dari master_products
6. Notifikasi: "Produk berhasil dihapus"
```

**Postcondition**: Master produk dihapus (soft delete recommended)

---

### ✅ UC-003: User Management

**Actor**: Admin  
**Precondition**: Admin sudah login & di halaman User Management

**Main Flow**:

#### **3.1 Create New User**
```
1. Click Menu → USER MANAGEMENT
2. Klik tombol "+ Tambah User"
3. Form Input User:
   - Nama Lengkap: "Budi Warehouse"
   - Email: "warehouse@metinca.com"
   - Role: Pilih "Warehouse" (dropdown: Admin/PPIC/Warehouse/Quality)
   - Status: Pilih "Active"
   - Password: Auto-generate atau input manual
4. Klik "Simpan"
5. Validasi:
   - Email harus unique
   - Email format valid
   - Role harus dipilih
   - Password minimal 8 karakter
6. ✅ User baru tersimpan di database users table
7. Notifikasi: "User berhasil dibuat. Email: warehouse@metinca.com, Password: warehouse123"
8. Optional: Tampilkan QR code / credential untuk dikirim ke user
```

**Database Result**:
```sql
INSERT INTO users (name, email, password, role, is_active) 
VALUES ('Budi Warehouse', 'warehouse@metinca.com', 'hashed_password', 'warehouse', true)
```

**Postcondition**: User baru dapat login dengan credential yang diberikan

---

#### **3.2 View User List**
```
1. Click Menu → USER MANAGEMENT
2. Tampilkan daftar semua user dalam bentuk tabel:
   - No | Nama | Email | Role | Status | Action
3. Tabel dilengkapi dengan:
   - Sorting (berdasarkan Nama, Role, Status)
   - Filtering (Search, Filter by Role, Filter by Status)
   - Pagination (10 user per halaman)
4. Di baris setiap user tampilkan:
   - Badge role dengan warna:
     * Admin (Merah)
     * PPIC (Biru)
     * Warehouse (Hijau)
     * Quality (Kuning)
   - Status badge (Active/Inactive)
   - Tombol: Edit, Reset Password, Deactivate, Delete
```

**Postcondition**: Admin dapat melihat overview semua user

---

#### **3.3 Edit User**
```
1. Di daftar user, klik tombol "Edit" pada user tertentu
2. Form pre-filled dengan data user
3. Admin dapat mengubah:
   - Nama Lengkap
   - Email
   - Role (ubah dari Warehouse → PPIC misalnya)
   - Status (ubah dari Active → Inactive)
   - Password (optional)
4. Klik "Update"
5. ✅ Perubahan tersimpan
6. Notifikasi: "User berhasil diperbarui"
7. PENTING: Jika role berubah, user akan melihat menu berbeda di login berikutnya
```

**Example**:
```
BEFORE: Budi (Warehouse) - Hanya akses menu Warehouse
AFTER: Budi (PPIC) - Hanya akses menu PPIC & Shared (Reports)
```

---

#### **3.4 Reset Password User**
```
1. Di daftar user, klik tombol "Reset Password" pada user tertentu
2. Dialog konfirmasi: "Reset password user ini menjadi default?"
3. Jika Klik "Ya":
   - Generate password baru (misal: "NewPass@2026")
   - Hash password
   - Update di database
4. ✅ Password direset
5. Tampilkan credential baru ke Admin untuk dikirim ke user
6. Notifikasi: "Password berhasil direset. Password baru: NewPass@2026"
```

---

#### **3.5 Deactivate/Activate User**
```
1. Di daftar user, klik tombol "Deactivate" pada user aktif
2. Dialog: "Deactivate user ini? User tidak bisa login."
3. Jika Klik "Ya":
   - Set is_active = false di database
   - User tidak bisa login
4. ✅ User status berubah jadi "Inactive"

UNTUK REAKTIVASI:
1. Klik tombol "Activate" pada user inactive
2. Set is_active = true
3. ✅ User dapat login kembali
```

---

### ✅ UC-004: Monitoring & Approval Management

**Actor**: Admin  
**Precondition**: Admin sudah login

**Main Flow**:

#### **4.1 Monitor Incoming Data**
```
1. Di Dashboard, Admin bisa melihat:
   - Total Penerimaan Barang (bulan ini)
   - Total Penyimpanan NG
   - Total Scrap Disposal
   - Total Retur Barang
   - Pending Approvals (count)

2. Click tombol "View Details" pada setiap section:
   → Lihat list data detail dengan status approval
   → Bisa filter berdasarkan tanggal, status, user input

3. Data yang berstatus "Pending" ditandai dengan badge kuning
4. Data yang sudah "Approved" ditandai dengan badge hijau
5. Data yang "Rejected" ditandai dengan badge merah
```

---

#### **4.2 Approve/Reject Data**
```
1. Admin melihat list data pending approval
2. Klik tombol "Review" pada data tertentu
3. Tampilkan detail data:
   - Info dasar (nomor dokumen, tanggal, user input)
   - Data item detail (produk, qty, lokasi, dll)
   - Foto/attachment (jika ada)
   - Catatan user input

4. Admin review dan pilih:
   - Tombol "Approve" → Data approved, perubahan final
   - Tombol "Reject" + input alasan → Data rejected, user harus perbaiki
   - Tombol "Request Info" + tanya detail → Tanyakan ke user

5. Jika APPROVE:
   - Status berubah "Approved"
   - is_approved = true, approved_by = admin_id, approved_at = now()
   - Notifikasi ke user: "Data Anda sudah diapprove"
   - Data bisa masuk laporan

6. Jika REJECT:
   - Status berubah "Rejected"
   - is_approved = false, rejection_reason = "..."
   - Notifikasi ke user: "Data ditolak. Alasan: ..."
   - User harus input ulang atau perbaiki
```

---

### ✅ UC-005: Generate Reports & Export Data

**Actor**: Admin  
**Precondition**: Admin sudah login, data sudah approved

**Main Flow**:

#### **5.1 Generate Laporan Recap**
```
1. Click Menu → REPORTS → Return Analysis
2. Halaman menampilkan Laporan Recap dengan filter:
   - Filter by Date Range (dari - sampai)
   - Filter by Type (Penerimaan/Penyimpanan/Scrap/Retur)
   - Filter by Status (All/Approved/Pending)

3. Default: Tampilkan data bulan ini, status Approved

4. Tabel menampilkan:
   ┌─────────────────────────────────────────────────────────┐
   │ LAPORAN RECAP - January 2026                            │
   ├─────────────────────────────────────────────────────────┤
   │ A. PENERIMAAN BARANG                                    │
   │ No | Nomor Dokumen | Produk | Qty | Unit | Input Date  │
   │ 1  | PEN-2026-001  | Bearing| 100 | PCS  | 2026-01-10  │
   │                                                         │
   │ B. PENYIMPANAN NG                                       │
   │ No | Nomor Storage | Produk | Qty | Lokasi | Tgl Input │
   │ 1  | PSG-2026-001  | Bolt   | 50  | A-02   | 2026-01-11│
   │                                                         │
   │ C. SCRAP DISPOSAL                                       │
   │ No | Nomor Scrap | Produk | Qty | Metode | Tgl Input  │
   │ 1  | SCR-2026-001| Plate  | 20  | Bakar  | 2026-01-12 │
   │                                                         │
   │ D. RETUR BARANG                                         │
   │ No | No Retur | Produk | Vendor | Qty | Alasan | Tgl  │
   │ 1  | RET-2026 | Shaft  | PT ABC | 30  | Rusak  | 01-13 │
   └─────────────────────────────────────────────────────────┘

5. Statistik Summary:
   - Total item diterima: 100
   - Total item NG: 50
   - Total item scrap: 20
   - Total item retur: 30

6. Admin bisa apply filter & search sesuai kebutuhan
```

---

#### **5.2 Export Data to CSV**
```
1. Di halaman Laporan Recap, klik tombol "Export CSV"
2. Sistem generate file CSV dengan nama: 
   "Laporan_Recap_Jan_2026.csv"
3. Struktur CSV:
   - Header dengan informasi laporan (tanggal, dibuat oleh)
   - Section untuk setiap type (Penerimaan, Penyimpanan, Scrap, Retur)
   - Footer dengan total & summary

4. File otomatis download ke komputer user

5. File bisa dibuka di Excel untuk analisis lebih lanjut:
   - Create pivot table
   - Buat chart/graph
   - Filter & sort data
   - Export ke format lain (XLSX, PDF)
```

**CSV Format**:
```csv
Laporan Recap - January 2026
Dicetak oleh: Administrator
Tanggal: 2026-01-14

=== PENERIMAAN BARANG ===
Nomor Dokumen,Produk,Kode Produk,Qty Baik,Qty Rusak,Penginput,Tanggal
PEN-2026-001,Bearing,PROD-001,100,0,Budi,2026-01-10

=== PENYIMPANAN NG ===
Nomor Storage,Produk,Qty Awal,Lokasi,Nomor Referensi,Tanggal
PSG-2026-001,Bolt,50,A-02,PEN-2026-001,2026-01-11

TOTAL RECORDS: 4
```

---

#### **5.3 Vendor Scorecard Analysis**
```
1. Click Menu → REPORTS → Vendor Scorecard
2. Tampilkan performance setiap vendor dalam periode tertentu

3. Metrics yang ditampilkan:
   - Jumlah barang diterima dari vendor
   - Qty baik vs qty rusak (%)
   - Average quality score
   - On-time delivery rate
   - Return/complaint rate

4. Tabel Vendor Performance:
   ┌──────────────────────────────────────┐
   │ Vendor | Qty | Good% | Return% | Score
   ├──────────────────────────────────────┤
   │ PT ABC | 500 | 98%   | 2%      | A+
   │ PT XYZ | 300 | 95%   | 5%      | A
   │ PT DEF | 200 | 90%   | 10%     | B
   └──────────────────────────────────────┘

5. Admin bisa:
   - Klik vendor untuk detail historis
   - Export report per vendor
   - Print scorecard
```

---

### ✅ UC-006: System Monitoring & Audit

**Actor**: Admin  
**Precondition**: Admin sudah login

**Main Flow**:

#### **6.1 View Activity Log**
```
1. Click Menu → ADMIN → Activity Log (jika tersedia)
2. Tampilkan log semua aktivitas:
   - User login/logout
   - Data create/update/delete
   - Approval/rejection
   - Export actions

3. Log format:
   │ Time | User | Action | Model | Details | Status │
   │ 14:30| Budi | CREATE | Penerimaan | PEN-2026-001 | Success │
   │ 14:25| Admin| APPROVE| Penerimaan | PEN-2026-001 | Success │
   │ 14:15| Budi | LOGOUT | - | - | Success │

4. Bisa filter by user, action, date range
```

---

#### **6.2 Dashboard Statistics**
```
1. Admin Dashboard menampilkan:

   KPI DASHBOARD
   ┌──────────────────────────────────┐
   │ Total Users: 4                   │
   │ Active Users: 3  | Inactive: 1  │
   ├──────────────────────────────────┤
   │ Data This Month:                 │
   │ • Penerimaan: 10 (Approved: 10)  │
   │ • Penyimpanan: 5 (Approved: 5)   │
   │ • Scrap: 3 (Approved: 3)         │
   │ • Retur: 2 (Pending: 1)          │
   ├──────────────────────────────────┤
   │ Pending Actions: 1               │
   │ Pending Approvals: 1             │
   └──────────────────────────────────┘

2. Charts & Graphs:
   - Monthly data trend
   - Vendor performance
   - Quality metrics
   - Approval status breakdown
```

---

### ✅ UC-007: Logout & Session Management

**Actor**: Admin (All users)  
**Precondition**: User sedang logged in

**Main Flow**:
```
1. Di sidebar, Admin bisa lihat section "Account" di bawah
2. Section menampilkan:
   - Avatar circle dengan inisial (misal: "AD" untuk Admin)
   - Nama user: "Administrator"
   - Role badge: "Administrator" (warna merah)

3. Di bawah info user, ada tombol "Logout"
4. Klik tombol "Logout"
5. Dialog konfirmasi: "Anda yakin ingin logout?"
6. Jika Klik "Ya":
   - Session user dihapus
   - Cookie authentication dihapus
   - Redirect ke halaman login
7. ✅ User berhasil logout
```

**Postcondition**: Session ended, user harus login kembali

---

## 🔐 Role-Based Access Control (RBAC)

### **Admin Full Access**
```
✅ Dashboard
✅ Data Master (Create/Read/Update/Delete)
✅ PPIC (View, dapat approve)
✅ Warehouse (View, dapat approve)
✅ Quality (View, dapat approve)
✅ Reports & Analytics
✅ User Management
✅ System Settings (future)
❌ Tidak bisa: Hanya dapat logout sendiri, tidak force logout user lain
```

### **PPIC Limited Access**
```
✅ Dashboard
❌ Data Master
✅ PPIC (Input, edit own data)
✅ Reports (View & Export)
❌ Warehouse
❌ Quality
❌ User Management
┌─ QC Inspection (8)
│  ├── Inspect Goods
│  ├── Record Results
│  ├── Identify Defects
│  ├── Grade Items
│  ├── Create Report
│  ├── Update Status
│  ├── Add Comments
│  └── Generate Certificate
│
├─ Quality Approval (6)
│  ├── Review Inspection
│  ├── Approve Result
│  ├── Reject Result
│  ├── Request Rework
│  ├── Add Notes
│  └── Sign Off
│
├─ Vendor Scorecard (4)
│  ├── View Performance
│  ├── Calculate Metrics
│  ├── Generate Report
│  └── Export Data
│
└─ Reports (2)
   ├── QC Summary
   └── Defect Analysis```

### **Warehouse Limited Access**
```
✅ Dashboard
❌ Data Master
❌ PPIC
✅ Warehouse (Input penerimaan, penyimpanan, scrap, retur)
✅ Reports (View & Export)
❌ Quality
❌ User Management
```

### **Quality Limited Access**
```
✅ Dashboard
❌ Data Master
❌ PPIC
❌ Warehouse (View only, tidak bisa input)
✅ Quality (Inspeksi QC, Approval)
✅ Reports (View & Export)
❌ User Management
```

---

## 📊 SCENARIO - Complete Workflow Example

### **Scenario: Proses Approval Retur Barang**

```
STEP 1: WAREHOUSE INPUT
├─ Tanggal: 2026-01-14 10:00
├─ User: warehouse@metinca.com (Warehouse staff)
├─ Action: Input Retur Barang
├─ Data Input:
│  ├─ No Retur: RET-2026-001
│  ├─ Produk: Bearing SKF (dari master produk)
│  ├─ Vendor: PT ABC
│  ├─ Qty: 30
│  ├─ Alasan: Bearing rusak, tidak berputar
│  └─ Status: Pending Approval
└─ Database: INSERT ke retur_barangs table

STEP 2: ADMIN MONITORING
├─ Tanggal: 2026-01-14 10:30
├─ User: admin@metinca.com
├─ Action: Check Dashboard
├─ Melihat: Pending Approvals: 1
├─ Navigate ke: Menu → Warehouse → Retur Barang
└─ View: Tabel dengan status Pending

STEP 3: ADMIN REVIEW
├─ Tanggal: 2026-01-14 10:45
├─ User: admin@metinca.com
├─ Action: Klik "Review" pada RET-2026-001
├─ View Detail:
│  ├─ No Retur, Produk, Vendor, Qty, Alasan
│  ├─ Input date: 2026-01-14 10:00
│  ├─ Input by: Budi (Warehouse)
│  └─ Status: Pending (Pending Approval badge kuning)
├─ Admin verify:
│  ├─ Produk valid? ✓ (ada di master)
│  ├─ Qty valid? ✓ (reasonable)
│  └─ Alasan clear? ✓ (Bearing rusak)
└─ Action: Klik tombol "APPROVE"

STEP 4: APPROVAL PROCESS
├─ Tanggal: 2026-01-14 10:47
├─ Database Update:
│  ├─ UPDATE retur_barangs SET:
│  │  ├─ is_approved = true
│  │  ├─ approved_by = admin_id
│  │  ├─ approved_at = 2026-01-14 10:47
│  │  └─ status = 'Approved'
│  └─ INSERT activity_log (Admin approve RET-2026-001)
├─ Notification: Email ke Budi
│  └─ Subject: "Data Retur RET-2026-001 Sudah Diapprove"
└─ Success message: "Data berhasil diapprove"

STEP 5: REPORTING
├─ Tanggal: 2026-01-20 (akhir bulan)
├─ User: admin@metinca.com
├─ Action: Generate Laporan Recap
├─ Filter: Date: 2026-01-01 to 2026-01-31, Status: Approved
├─ Report Include:
│  └─ Section Retur Barang:
│     ├─ No Retur: RET-2026-001
│     ├─ Produk: Bearing SKF
│     ├─ Vendor: PT ABC
│     ├─ Qty: 30
│     └─ Status: Approved
├─ Export: Click "Export CSV"
└─ File: Laporan_Recap_Jan_2026.csv (downloaded)

STEP 6: VENDOR PERFORMANCE
├─ Tanggal: 2026-01-20
├─ User: admin@metinca.com
├─ Action: View Vendor Scorecard
├─ For PT ABC:
│  ├─ Total Retur: 1
│  ├─ Retur Rate: 30 dari 500 qty = 6%
│  ├─ Quality Score: Decreased
│  └─ Action: Consider penalizing atau meeting dengan vendor
└─ Export: Print scorecard untuk dokumentasi
```

---

## ⚠️ EXCEPTION SCENARIOS

### **Scenario: Invalid Data Approval**

```
STEP 1: User input data dengan error
├─ No Retur: RET-2026-002
├─ Produk: "Unknown Product" (tidak valid)
├─ Qty: -5 (negatif - tidak valid)
└─ Status: Pending Approval

STEP 2: Admin review
├─ Melihat error:
│  ├─ Produk tidak ada di master ❌
│  └─ Qty negative ❌
└─ Action: Klik "REJECT"

STEP 3: Reject process
├─ Input alasan: "Produk tidak terdaftar di master, Qty tidak valid (negatif)"
├─ Klik "Submit Rejection"
├─ Database Update:
│  ├─ is_approved = false
│  ├─ rejection_reason = "..."
│  └─ status = 'Rejected'
├─ Notification ke user:
│  └─ "Data ditolak. Silakan perbaiki dan input ulang."
└─ User harus input ulang dengan data correct

STEP 4: User retry
├─ User input ulang dengan data yang benar
├─ No Retur: RET-2026-002 (rev)
├─ Produk: Bearing SKF (valid)
├─ Qty: 30 (valid)
├─ Status: Pending Approval (ulang)
└─ Admin approve → Success
```

---

## 📋 PRECONDITIONS & POSTCONDITIONS

### **Global Preconditions**
- ✓ Database sudah tersetup
- ✓ Koneksi database stable
- ✓ Master data sudah diinisialisasi
- ✓ Default user (admin, ppic, warehouse, quality) sudah di-seed

### **Global Postconditions**
- ✓ Data tersimpan ke database
- ✓ Activity log tercatat
- ✓ Notification terkirim (email/in-app)
- ✓ User dapat melihat perubahan di sistem
- ✓ Report dapat generated dengan data updated

---

## 🛠️ SYSTEM CONSTRAINTS

| Constraint | Value | Reason |
|-----------|-------|--------|
| Max users | Unlimited | Scalable architecture |
| Session timeout | 2 hours | Security |
| Password min length | 8 chars | Security requirement |
| Data retention | 2 years | Business requirement |
| Concurrent users | Unlimited | Laravel + Database pool |
| Report generation | <5 sec | Performance requirement |

---

## 🎓 KEY FEATURES DEMONSTRATED

✅ **Role-Based Access Control** - Setiap user hanya akses sesuai role  
✅ **Multi-level Approval** - Admin approve data sebelum masuk laporan  
✅ **Master Data Management** - Admin setup & maintain master data  
✅ **User Management** - Create, edit, deactivate user accounts  
✅ **Audit Trail** - Semua aktivitas tercatat (future enhancement)  
✅ **Reporting & Export** - Generate laporan & export ke CSV  
✅ **Dashboard & Analytics** - KPI monitoring real-time  
✅ **Notification System** - User notified saat data approve/reject  

---

## 🚀 Implementation Status

| Feature | Status | Notes |
|---------|--------|-------|
| Authentication | ✅ Complete | Login, Logout working |
| RBAC | ✅ Complete | 4 roles implemented |
| User Management | ✅ Complete | CRUD operations |
| Master Data | ✅ Complete | Create, Edit, Delete |
| Approval Workflow | ✅ Complete | Approve/Reject implemented |
| Reporting | ✅ Complete | Laporan recap & export |
| Vendor Scorecard | ✅ Complete | Performance analytics |
| Dashboard | ✅ Complete | KPI overview |
| Notification | 🟡 Partial | Email notification (future) |
| Audit Log | 🟡 Partial | Activity tracking (future) |

---

## 📞 Support & Documentation

- **Admin Guide**: [Sistem ini untuk administrator]
- **User Guide**: [Panduan untuk PPIC, Warehouse, Quality]
- **API Documentation**: [Jika ada external integration]
- **Database Schema**: [Lihat file migration]

---

**Last Updated**: January 14, 2026  
**Created By**: Development Team  
**Version**: 1.0
