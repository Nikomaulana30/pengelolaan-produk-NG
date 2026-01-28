# 🏭 METINCA Starter App

> **Enterprise Warehouse Management & Quality Control System**  
> Version 2.0 | Laravel 9.x | Production Ready ✅

---

## 🎯 Overview

METINCA Starter App adalah sistem manajemen warehouse dan quality control terintegrasi dengan fitur approval workflow multi-level, tracking stock movement, RCA analysis, dan finance approval.

### ✨ Key Features

- ✅ **Polymorphic Approval System** - Multi-level approval untuk semua modul
- ✅ **Quality Inspection** - QC dengan auto-create Penyimpanan NG
- ✅ **Stock Movement Tracking** - Complete audit trail IN/OUT/TRANSFER
- ✅ **Retur Barang** - Return management dengan approval
- ✅ **RCA Analysis** - 5 Why & Fishbone diagram
- ✅ **Scrap Disposal** - Waste management dengan approval
- ✅ **Finance Approval** - Budget tracking & approval workflow

### 📊 Statistics

- **41 Database Tables** (1.72 MB)
- **15+ Models** with complete relationships
- **12+ Controllers** ready to use
- **8 Major Modules** implemented
- **2,000+ Lines** of documentation

---

## 📚 Documentation

**START HERE:** [📖 Documentation Index](./DOCUMENTATION_INDEX.md)

### Quick Links

| Document | Purpose | Best For |
|----------|---------|----------|
| [🚀 Quick Reference](./QUICK_REFERENCE.md) | Fast start guide | Developers |
| [📘 Implementation Docs](./IMPLEMENTATION_DOCUMENTATION.md) | Complete reference | Tech Leads |
| [🗺️ Database Diagrams](./DATABASE_RELATIONSHIPS.md) | Visual guide | Architects |
| [✅ Summary](./IMPLEMENTATION_SUMMARY.md) | Executive overview | Managers |

---

## 🚀 Quick Start

## Persyaratan Sistem

- PHP >= 8.4
- Composer
- MySQL

## Cara Clone Repository

### 1. Clone dari GitHub

```bash
git clone https://github.com/username/metinca-starter-app.git
cd metinca-starter-app
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install
```

### 3. Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=metinca_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan Migration

```bash
php artisan migrate
```

### 6. Jalankan Aplikasi

```bash
# Jalankan development server
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Membuat Branch Baru (opsional)

### Untuk Feature Baru

```bash
# Pastikan berada di branch main/master terbaru
git checkout main
git pull origin main

# Buat branch feature baru
git checkout -b feature/nama-fitur

# Contoh:
git checkout -b feature/user-authentication
```

### Untuk Bug Fix

```bash
git checkout main
git pull origin main
git checkout -b bugfix/nama-bug

# Contoh:
git checkout -b bugfix/login-error
```

### Untuk Development

```bash
git checkout main
git pull origin main
git checkout -b dev/nama-development

# Contoh:
git checkout -b dev/api-integration
```

## Workflow Branching

1. **main/master** - Branch utama untuk production
2. **develop** - Branch untuk development
3. **feature/** - Branch untuk fitur baru
4. **bugfix/** - Branch untuk perbaikan bug
5. **hotfix/** - Branch untuk perbaikan urgent di production

### Push Branch ke Remote

```bash
# Push branch baru ke remote
git push -u origin nama-branch

# Contoh:
git push -u origin feature/user-authentication
```

### Merge Branch

```bash
# Kembali ke branch main
git checkout main

# Merge branch feature
git merge feature/nama-fitur

# Push perubahan
git push origin main
```

## Tips Git

### Lihat Branch yang Ada

```bash
# Lihat branch lokal
git branch

# Lihat semua branch (termasuk remote)
git branch -a
```

### Hapus Branch

```bash
# Hapus branch lokal
git branch -d nama-branch

# Hapus branch remote
git push origin --delete nama-branch
```

### Update Branch dari Main

```bash
# Dari branch feature anda
git checkout feature/nama-fitur
git merge main

# Atau menggunakan rebase
git rebase main
```

## Struktur Project

```
metinca-starter-app/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── .env.example
├── composer.json
└── package.json
```

## Perintah Berguna

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize aplikasi
php artisan optimize

# Generate IDE helper (jika menggunakan)
php artisan ide-helper:generate
```

## Troubleshooting

### Error Permission Storage/Bootstrap

```bash
chmod -R 775 storage bootstrap/cache
```

### Error Composer Dependencies

```bash
composer dump-autoload
composer update
```

## Kontribusi

1. Fork repository ini
2. Buat branch feature (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Project ini menggunakan lisensi MIT.

## Kontak

Untuk pertanyaan atau dukungan, silakan hubungi tim development.

---

**Happy Coding! 🚀**
