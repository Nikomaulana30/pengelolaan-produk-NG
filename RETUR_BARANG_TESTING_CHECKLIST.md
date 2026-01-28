# RETUR BARANG MANUAL TESTING CHECKLIST
**Date**: ________________  
**Tester**: ________________  
**Build Version**: 1.0  
**Date Tested**: 2026-01-08

---

## ✅ PRE-TESTING CHECKLIST

- [ ] Laragon running (Apache ✓ MySQL ✓)
- [ ] Browser opened (Chrome/Firefox/Safari)
- [ ] URL accessible: `http://localhost/laravel_projects/metinca-starter-app/retur-barang`
- [ ] Logged in as admin/authorized user
- [ ] Network console open (F12 → Console tab)
- [ ] Database connected (check phpMyAdmin)

---

## 🧪 TEST 1: PAGE LOAD & EMPTY STATE

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 1.1 | Visit retur-barang page | Page loads without error | ☐ | |
| 1.2 | Check page title | Shows "Master Retur Barang" | ☐ | |
| 1.3 | Check statistics cards | 4 cards visible (Total, Pending, Approved, Rejected) | ☐ | |
| 1.4 | Check empty state | Table shows "Belum ada data" message | ☐ | |
| 1.5 | Check "Tambah Retur" button | Button visible and clickable | ☐ | |
| 1.6 | Check sidebar menu | Retur Barang link present under WAREHOUSE | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Issues**: ________________________________________________________________

---

## 🧪 TEST 2: CREATE OPERATION (CRUD-C)

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 2.1 | Click "Tambah Retur" | Redirects to create form | ☐ | |
| 2.2 | Check form fields | All fields visible (Vendor, Produk, Tanggal, Alasan, Qty, Deskripsi) | ☐ | |
| 2.3 | Select Vendor | Dropdown shows "V001 - PT. Supplier Terpercaya" and others | ☐ | |
| 2.4 | Select Produk | Dropdown populated with products | ☐ | |
| 2.5 | Select Date | Date picker functional | ☐ | |
| 2.6 | Select Alasan Retur | All 6 options visible (defect, qty_tidak_sesuai, kualitas_buruk, expired, rusak_pengiriman, lainnya) | ☐ | |
| 2.7 | Enter Jumlah Retur | Accepts positive integers | ☐ | |
| 2.8 | Enter Deskripsi | Text field accepts input | ☐ | |
| 2.9 | Submit empty form | Shows validation errors | ☐ | |
| 2.10 | Fill all required fields | Form ready to submit | ☐ | |
| 2.11 | Click "Simpan Retur" | Redirects to list, success message shows | ☐ | |
| 2.12 | Check new retur | Entry appears in table with no_retur RET-2026-XXXXX | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Auto-generated No_Retur**: ________________  
**Issues**: ________________________________________________________________

---

## 🧪 TEST 3: READ OPERATION (CRUD-R)

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 3.1 | Click Eye icon on retur | Redirects to detail page | ☐ | |
| 3.2 | Check detail fields | All fields display correctly | ☐ | |
| 3.3 | Check No_Retur | Format is RET-YYYY-XXXXX | ☐ | |
| 3.4 | Check Vendor info | Vendor name & code displayed | ☐ | |
| 3.5 | Check Produk info | Product name & code displayed | ☐ | |
| 3.6 | Check Status badge | "Pending" badge shows in warning color | ☐ | |
| 3.7 | Check Integration section | Shows vendor & product info | ☐ | |
| 3.8 | Check Edit button | Button present and clickable | ☐ | |
| 3.9 | Check Delete button | Button present and clickable | ☐ | |
| 3.10 | Check back navigation | Sidebar/breadcrumb navigation works | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Issues**: ________________________________________________________________

---

## 🧪 TEST 4: UPDATE OPERATION (CRUD-U)

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 4.1 | Click Edit (pencil icon) | Redirects to edit form | ☐ | |
| 4.2 | Check form pre-filled | All current values visible | ☐ | |
| 4.3 | Change status to "approved" | Status dropdown has options | ☐ | |
| 4.4 | Add approval note | Catatan field editable | ☐ | |
| 4.5 | Modify other fields | Can change vendor/produk/alasan | ☐ | |
| 4.6 | Click "Update" button | Form submits successfully | ☐ | |
| 4.7 | Check redirect | Returns to list view | ☐ | |
| 4.8 | Check updated data | Status changed to "approved" in table | ☐ | |
| 4.9 | Check status badge | Now shows green "approved" badge | ☐ | |
| 4.10 | Verify detail page | Detail view shows updated data | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Issues**: ________________________________________________________________

---

## 🧪 TEST 5: DELETE OPERATION (CRUD-D)

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 5.1 | Create new test retur | New entry in list | ☐ | |
| 5.2 | Click Delete (trash icon) | SweetAlert2 dialog appears | ☐ | |
| 5.3 | Check dialog title | Shows "Hapus Retur?" or similar | ☐ | |
| 5.4 | Check dialog message | Shows retur number/name | ☐ | |
| 5.5 | Check warning icon | Dialog shows warning icon | ☐ | |
| 5.6 | Check "Ya, Hapus" button | Red delete button present | ☐ | |
| 5.7 | Check "Batal" button | Gray cancel button present | ☐ | |
| 5.8 | Click "Batal" | Dialog closes, entry still in table | ☐ | |
| 5.9 | Click Delete again | Dialog reappears | ☐ | |
| 5.10 | Click "Ya, Hapus" | Entry removed from table | ☐ | |
| 5.11 | Check success message | Success notification shows | ☐ | |
| 5.12 | Verify soft delete | Check database (data still exists but deleted_at filled) | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Issues**: ________________________________________________________________

---

## 🧪 TEST 6: STATISTICS & PAGINATION

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 6.1 | Check statistics cards | All 4 cards show count = 1 | ☐ | |
| 6.2 | Create retur (status: pending) | Pending count increases | ☐ | |
| 6.3 | Create retur (status: approved) | Approved count increases | ☐ | |
| 6.4 | Create retur (status: rejected) | Rejected count increases | ☐ | |
| 6.5 | Check total count | Total = sum of all statuses | ☐ | |
| 6.6 | Create 15+ returs | Reach pagination limit | ☐ | |
| 6.7 | Check pagination links | Next/Previous buttons appear | ☐ | |
| 6.8 | Click page 2 | Different entries shown | ☐ | |
| 6.9 | Check rows per page | Showing max 15 items per page | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Total Created**: ________  
**Issues**: ________________________________________________________________

---

## 🧪 TEST 7: FORM VALIDATION

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 7.1 | Leave Vendor empty | Error message shown | ☐ | |
| 7.2 | Leave Produk empty | Error message shown | ☐ | |
| 7.3 | Leave Date empty | Error message shown | ☐ | |
| 7.4 | Leave Alasan empty | Error message shown | ☐ | |
| 7.5 | Leave Qty empty | Error message shown | ☐ | |
| 7.6 | Enter Qty = 0 | Error: min:1 validation | ☐ | |
| 7.7 | Enter Qty = -5 | Error: integer/min validation | ☐ | |
| 7.8 | Enter Qty = "abc" | Error: integer validation | ☐ | |
| 7.9 | Check error styling | Errors shown in red with icons | ☐ | |
| 7.10 | Fix errors & submit | Form accepts valid data | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Issues**: ________________________________________________________________

---

## 🧪 TEST 8: INTEGRATION & NAVIGATION

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 8.1 | Go to Master Vendor page | Page loads | ☐ | |
| 8.2 | Check Integration section | "Retur Barang" link visible | ☐ | |
| 8.3 | Click Retur Barang link | Navigates to retur list | ☐ | |
| 8.4 | Check sidebar menu | WAREHOUSE section expanded | ☐ | |
| 8.5 | Click Retur Barang in menu | Navigates to retur list | ☐ | |
| 8.6 | Check menu highlight | Retur Barang link highlighted when active | ☐ | |
| 8.7 | Click Penerimaan Barang link | Navigates to penerimaan list | ☐ | |
| 8.8 | Return to Retur Barang | Navigation works smoothly | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Issues**: ________________________________________________________________

---

## 🧪 TEST 9: RESPONSIVE DESIGN

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 9.1 | Desktop (1920px) | All columns visible, proper spacing | ☐ | |
| 9.2 | Desktop - scroll | Horizontal scroll not needed | ☐ | |
| 9.3 | Tablet (768px) | Columns responsive, readable | ☐ | |
| 9.4 | Tablet - buttons | Action buttons accessible | ☐ | |
| 9.5 | Mobile (375px) | Table horizontal scroll works | ☐ | |
| 9.6 | Mobile - form | Form fields stacked properly | ☐ | |
| 9.7 | Mobile - buttons | Buttons full width and touchable | ☐ | |
| 9.8 | Mobile - menu | Sidebar collapses, hamburger works | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Issues**: ________________________________________________________________

---

## 🧪 TEST 10: BOOTSTRAP STYLING

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 10.1 | Inspect HTML | No Tailwind classes found | ☐ | |
| 10.2 | Check cards | Uses Bootstrap card classes | ☐ | |
| 10.3 | Check buttons | Consistent Bootstrap styling | ☐ | |
| 10.4 | Check badges | Status badges correctly styled | ☐ | |
| 10.5 | Check forms | Form controls use Bootstrap classes | ☐ | |
| 10.6 | Check grid | Grid system responsive | ☐ | |
| 10.7 | Check colors | Color scheme consistent | ☐ | |
| 10.8 | Check spacing | Proper margins & padding | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Issues**: ________________________________________________________________

---

## 🧪 TEST 11: DATABASE RELATIONSHIPS

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 11.1 | Open Retur detail | Vendor relationship loads | ☐ | |
| 11.2 | Check vendor data | Name & code displayed | ☐ | |
| 11.3 | Check product data | Name & code displayed | ☐ | |
| 11.4 | Try invalid vendor_id | Should show validation error on create | ☐ | |
| 11.5 | Try invalid produk_id | Should show validation error on create | ☐ | |
| 11.6 | Test soft delete | Record hides but data preserved | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Issues**: ________________________________________________________________

---

## 🧪 TEST 12: PERFORMANCE & ERRORS

| # | Step | Expected | Pass | Notes |
|---|------|----------|------|-------|
| 12.1 | Page load time | < 2 seconds | ☐ | ______s |
| 12.2 | Form submission | < 1 second | ☐ | ______s |
| 12.3 | Check console errors | No JavaScript errors | ☐ | |
| 12.4 | Check network tab | All resources load successfully | ☐ | |
| 12.5 | Check database logs | No SQL errors | ☐ | |
| 12.6 | Memory usage | Normal (< 50MB) | ☐ | |
| 12.7 | Create 50 returs | System handles load | ☐ | |
| 12.8 | Pagination with 50+ items | Smooth navigation | ☐ | |

**Test Result**: ☐ PASS  ☐ FAIL  
**Issues**: ________________________________________________________________

---

## 📊 FINAL RESULTS

### Summary
- **Total Tests**: 96
- **Passed**: ___ / 96
- **Failed**: ___ / 96
- **Pass Rate**: ____%

### Overall Status
☐ ✅ **ALL TESTS PASSED** - Ready for UAT  
☐ ⚠️ **MINOR ISSUES** - Review notes below  
☐ ❌ **CRITICAL ISSUES** - Fix before deployment  

### Critical Issues (if any)
1. ____________________________________________________________
2. ____________________________________________________________
3. ____________________________________________________________

### Recommendations
1. ____________________________________________________________
2. ____________________________________________________________
3. ____________________________________________________________

---

## ✍️ SIGN-OFF

**Tester Name**: ________________________  
**Date**: ________________________  
**Time Spent**: _______ hours  
**Environment**: ☐ Development ☐ Staging ☐ Production  

**Signature**: ________________________

---

**Next Steps**:
- [ ] Share results with team
- [ ] Create bug tickets if issues found
- [ ] Schedule UAT with stakeholders
- [ ] Plan production deployment

**Report Generated**: 2026-01-08  
**Version**: Retur Barang Module v1.0
