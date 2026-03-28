# PPDB Online — Laravel 11 + Breeze

Sistem Informasi Penerimaan Peserta Didik Baru (PPDB) berbasis web.

## 🎨 Color Palette
| Token     | Hex       | Penggunaan                 |
|-----------|-----------|----------------------------|
| Primary   | `#33528A` | Sidebar aktif, tombol utama|
| Secondary | `#33A9A0` | Aksen, badge info          |
| Highlight | `#C4E81D` | CTA, badge baru            |
| Success   | `#8AB62E` | Status lulus, badge sukses |
| Dark      | `#597001` | Sidebar aktif gelap        |

## 📁 Struktur File Utama

```
├── app/Http/Controllers/
│   ├── BerandaController.php        ← Halaman publik
│   ├── DashboardController.php      ← Dashboard admin
│   ├── PendaftaranController.php    ← CRUD pendaftaran
│   ├── KlasifikasiController.php    ← Proses seleksi otomatis
│   ├── KelasController.php          ← Pembagian kelas
│   ├── LaporanController.php        ← Export PDF/Excel
│   ├── SiswaController.php
│   ├── MasterController.php
│   └── SettingsController.php
│
├── app/Models/
│   ├── Pendaftaran.php
│   └── Kelas.php
│
├── database/migrations/
│   ├── ..._create_pendaftarans_table.php
│   └── ..._create_kelas_table.php
│
├── database/seeders/
│   └── DatabaseSeeder.php           ← Data awal: admin + kelas + sampel siswa
│
├── resources/views/
│   ├── layouts/app.blade.php        ← Layout utama (sidebar + topbar)
│   ├── beranda/index.blade.php      ← Landing page publik
│   ├── auth/login.blade.php         ← Halaman login
│   ├── dashboard/index.blade.php    ← Dashboard admin
│   └── pendaftaran/index.blade.php  ← Tabel pendaftaran + filter
│
├── routes/web.php                   ← Semua route
└── public/css/ppdb.css              ← Custom stylesheet
```

## 🚀 Cara Install

### 1. Buat project Laravel 11 baru
```bash
composer create-project laravel/laravel ppdb-app
cd ppdb-app
```

### 2. Install Laravel Breeze
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
```

### 3. Salin semua file dari repo ini ke dalam project
Timpa file yang sama, tambahkan file baru sesuai struktur di atas.

### 4. Install dependencies frontend
```bash
npm install && npm run build
```

### 5. Konfigurasi database
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_DATABASE=ppdb_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 6. Migrasi & seed database
```bash
php artisan migrate
php artisan db:seed
```

### 7. Jalankan server
```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

## 🔐 Login Default
- **Email:** admin@ppdb.sch.id
- **Password:** password

## 📦 Package Tambahan yang Disarankan

```bash
# Export PDF
composer require barryvdh/laravel-dompdf

# Export Excel
composer require maatwebsite/excel

# Carbon (sudah termasuk di Laravel)
# Digunakan untuk format tanggal di sidebar
```

## 🗂️ Halaman yang Tersedia
| URL                    | Keterangan                     |
|------------------------|-------------------------------|
| `/`                    | Landing page publik            |
| `/login`               | Halaman login                  |
| `/dashboard`           | Dashboard admin                |
| `/pendaftaran`         | Daftar & CRUD pendaftaran      |
| `/master`              | Data master                    |
| `/siswa`               | Data siswa                     |
| `/klasifikasi`         | Proses klasifikasi otomatis    |
| `/kelas`               | Pembagian kelas                |
| `/laporan`             | Laporan & export               |
| `/settings`            | Pengaturan sistem              |

## 🎯 Fitur Utama
- ✅ Landing page publik dengan alur, jadwal, dan info sekolah
- ✅ Autentikasi via Laravel Breeze
- ✅ Dashboard admin dengan statistik real-time
- ✅ CRUD pendaftaran dengan filter & pagination
- ✅ Proses klasifikasi otomatis (siap dikembangkan dengan algoritma C4.5 / Naive Bayes)
- ✅ Pembagian kelas otomatis
- ✅ Laporan dengan export PDF & Excel
- ✅ Sidebar navigasi responsif
- ✅ Color palette konsisten sesuai desain
