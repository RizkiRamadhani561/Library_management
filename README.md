# 📚 Sistem Informasi Perpustakaan

Aplikasi web untuk mengelola sistem perpustakaan dengan fitur peminjaman, pengembalian, inventaris buku, laporan, dan manajemen pengguna.

## 📋 Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Cara Instalasi](#cara-instalasi)
- [Konfigurasi Database](#konfigurasi-database)
- [Struktur Folder](#struktur-folder)
- [Penggunaan Aplikasi](#penggunaan-aplikasi)
- [Akun Default](#akun-default)
- [Fitur Keamanan](#fitur-keamanan)
- [Troubleshooting](#troubleshooting)

---

## ✨ Fitur Utama

### Dashboard
- 📊 Statistik perpustakaan (total buku, anggota, peminjaman aktif, penulis)
- 📈 Ringkasan aktivitas terbaru

### Manajemen Data
- 👥 **Anggota**: Tambah, edit, hapus data anggota
- 📚 **Buku**: Manajemen katalog buku dengan penulis
- ✍️ **Penulis**: Manajemen data penulis buku
- 📤 **Peminjaman**: Pencatatan peminjaman buku
- 📥 **Pengembalian**: Pencatatan pengembalian buku

### Laporan & Analisis (Role: Kepala)
- 📤 Laporan Peminjaman (filter berdasarkan tanggal)
- 📥 Laporan Pengembalian (status ketepatan waktu)
- 👥 Laporan Anggota (data dan statistik)
- 📚 Laporan Inventaris (stok buku)
- ⏰ Laporan Keterlambatan (peminjaman yang belum dikembalikan)
- 💰 Laporan Denda (perhitungan otomatis Rp 5.000/hari)

### Keamanan
- 🔐 Authentication & Authorization
- 🛡️ CSRF Token Protection
- 📝 Activity Logging (Audit Trail)
- ⏱️ Session Timeout
- 🔒 Password Hashing (Bcrypt)
- 🚫 Rate Limiting (Brute Force Protection)

---

## 💻 Persyaratan Sistem

### Minimum Requirements
- **PHP**: 7.4 atau lebih tinggi
- **MySQL**: 5.7 atau lebih tinggi
- **Web Server**: Apache/Nginx
- **Browser**: Chrome, Firefox, Safari, Edge (terbaru)

### Rekomendasi
- PHP 8.0+
- MySQL 8.0+
- Apache 2.4+
- RAM: 2GB
- Storage: 500MB

---

## 🚀 Cara Instalasi

### 1. Download Project

**Opsi A: Clone dari Git**
```bash
https://github.com/RizkiRamadhani561/Library_management.git
cd Library_management
```

**Opsi B: Download Manual**
1. Buka GitHub/Repository
2. Klik tombol **Code** → **Download ZIP**
3. Extract file ZIP ke folder htdocs (XAMPP) atau www (WAMP)

```
Folder htdocs harus terlihat seperti:
C:\xampp\htdocs\Library_management\
                ├── view/
                ├── controller/
                ├── database/
                ├── layout/
                ├── auth_check.php
                ├── index.php
                └── README.md
```

### 2. Setup XAMPP/WAMP

**Untuk XAMPP (Windows):**
```bash
# Buka Command Prompt
cd C:\xampp
xampp-control.exe

# Klik Start pada Apache dan MySQL
```

**Untuk WAMP (Windows):**
- Buka WAMP Control Panel
- Pastikan Apache dan MySQL aktif (icon tray berwarna hijau)

### 3. Buka phpMyAdmin

```
Browser: http://localhost/phpmyadmin
Username: root
Password: (kosong)
```

### 4. Import Database

1. Di phpMyAdmin, klik **Database** → pilih atau buat database baru `perpustakaan`
2. Klik tab **Import**
3. Pilih file `database/perpustakaan.sql` 
4. Klik **Go** untuk import

**Atau via Command Line:**
```bash
mysql -u root -p perpustakaan < database/perpustakaan.sql
```

### 5. Akses Aplikasi

Buka browser dan akses:
```
http://localhost/Library_management
atau
http://localhost/Library_management/index.php
```

---

## 🗄️ Konfigurasi Database

### File Konfigurasi Database

**Lokasi:** `database/koneksi.php`

```php
<?php
// Konfigurasi database
$servername = "localhost";    // Server MySQL
$username = "root";           // Username MySQL
$password = "";               // Password MySQL (kosong untuk XAMPP default)
$dbname = "perpustakaan";     // Nama database

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset UTF-8
$conn->set_charset("utf8");
?>
```

### Edit Konfigurasi (Jika Perlu)

Jika Anda menggunakan password MySQL atau konfigurasi berbeda:

1. Buka file `database/koneksi.php`
2. Sesuaikan nilai:
   - `$servername` → Server MySQL Anda
   - `$username` → Username MySQL Anda
   - `$password` → Password MySQL Anda
   - `$dbname` → Nama database

**Contoh untuk WAMP dengan password:**
```php
$servername = "localhost";
$username = "root";
$password = "mypassword";  // Ganti dengan password Anda
$dbname = "perpustakaan";
```

---

## 📁 Struktur Folder

```
Library_management/
│
├── database/
│   ├── koneksi.php              (Konfigurasi koneksi database)
│   ├── perpustakaan.sql         (Query database lengkap)
│   └── perpustakaan_pinjam_detail.sql  (Tabel pinjam_detail)
│
├── controller/
│   ├── anggota.php              (Controller untuk anggota)
│   ├── buku.php                 (Controller untuk buku)
│   ├── penulis.php              (Controller untuk penulis)
│   ├── pinjam.php               (Controller untuk peminjaman)
│   └── login.php                (Controller untuk login)
│
├── view/
│   ├── anggota/
│   │   ├── index.php            (Daftar anggota)
│   │   ├── tambah.php           (Form tambah anggota)
│   │   ├── edit.php             (Form edit anggota)
│   │   └── hapus.php            (Proses hapus anggota)
│   │
│   ├── buku/
│   │   ├── index.php            (Daftar buku)
│   │   ├── tambah.php           (Form tambah buku)
│   │   ├── edit.php             (Form edit buku)
│   │   └── hapus.php            (Proses hapus buku)
│   │
│   ├── penulis/
│   │   ├── index.php            (Daftar penulis)
│   │   ├── tambah.php           (Form tambah penulis)
│   │   └── edit.php             (Form edit penulis)
│   │
│   ├── pinjam/
│   │   ├── index.php            (Daftar peminjaman)
│   │   ├── tambah.php           (Form tambah peminjaman)
│   │   ├── detail.php           (Detail peminjaman)
│   │   ├── kembali.php          (Proses pengembalian)
│   │   └── hapus.php            (Proses hapus peminjaman)
│   │
│   ├── laporan/
│   │   ├── index.php            (Menu laporan)
│   │   ├── peminjaman.php       (Laporan peminjaman)
│   │   ├── pengembalian.php     (Laporan pengembalian)
│   │   ├── anggota.php          (Laporan anggota)
│   │   ├── inventaris.php       (Laporan inventaris buku)
│   │   ├── keterlambatan.php    (Laporan keterlambatan)
│   │   └── denda.php            (Laporan denda)
│   │
│   └── dashboard/
│       └── index.php            (Halaman dashboard)
│
├── layout/
│   ├── header.php               (Header & navbar)
│   ├── navigasi.php             (Menu navigasi)
│   └── footer.php               (Footer)
│
├── index.php                    (Halaman login)
├── auth_check.php               (Fungsi keamanan & autentikasi)
├── README.md                    (Dokumentasi ini)
└── .gitignore                   (File yang diabaikan git)
```

---

## 🎯 Penggunaan Aplikasi

### Login

1. Akses aplikasi: `http://localhost/Library_management`
2. Masukkan **Username** dan **Password**
3. Klik **Login**

### Navigasi Menu

Setelah login, menu utama tersedia di navbar:

- 📊 **Dashboard** - Ringkasan data
- 👥 **Anggota** - Manajemen anggota
- 📚 **Buku** - Manajemen buku
- ✍️ **Penulis** - Manajemen penulis
- 📤 **Peminjaman** - Pencatatan peminjaman
- 📋 **Laporan** - Laporan (hanya untuk role Kepala)

### CRUD Operations

**Tambah Data:**
1. Klik tombol **➕ Tambah Data**
2. Isi form dengan data
3. Klik **💾 Simpan**

**Edit Data:**
1. Klik tombol **✏️ Edit** pada baris data
2. Ubah data yang diperlukan
3. Klik **💾 Simpan Perubahan**

**Hapus Data:**
1. Klik tombol **🗑️ Hapus** pada baris data
2. Konfirmasi penghapusan
3. Data akan dihapus dari database

---

## 🔐 Akun Default

Setelah import database, gunakan akun ini untuk login:

| Role | Username | Password | Akses |
|------|----------|----------|-------|
| Kepala | kepala | kepala123 | Semua menu + Laporan |
| Staff | staff | staff123 | Menu CRUD saja |

**Catatan:** Ganti password setelah login pertama untuk keamanan!

---

## 🛡️ Fitur Keamanan

### Implementasi Keamanan

1. **Authentication**
   - Login dengan username & password
   - Session management
   - Auto logout setelah 30 menit inaktif

2. **Authorization**
   - Role-based access control (RBAC)
   - Pembatasan menu berdasarkan role
   - Proteksi halaman khusus

3. **Data Protection**
   - CSRF Token pada setiap form
   - Input sanitization & validation
   - Prepared statements (SQL Injection prevention)
   - Password hashing dengan Bcrypt

4. **Monitoring**
   - Activity logging di tabel `activity_log`
   - Audit trail untuk semua perubahan data
   - IP address logging

5. **Session Security**
   - HTTPOnly cookies
   - Session fixation prevention
   - User-agent validation

6. **Rate Limiting**
   - Pencegahan brute force attack
   - Max 5 percobaan login dalam 5 menit

---

## 📝 Database Query Lengkap

Semua tabel dan data default sudah termasuk dalam file:
- `database/perpustakaan.sql` - Tabel dan data utama

Jika perlu setup manual, lihat bagian **Query Database** di bawah.

---

## 🐛 Troubleshooting

### Error: "Koneksi gagal"

**Penyebab:** MySQL tidak jalan atau konfigurasi salah

**Solusi:**
1. Pastikan MySQL sudah running (XAMPP Control Panel)
2. Cek file `database/koneksi.php`
3. Verifikasi username & password MySQL

### Error: "Table tidak ditemukan"

**Penyebab:** Database belum di-import

**Solusi:**
1. Import database melalui phpMyAdmin
2. Atau jalankan: `mysql -u root perpustakaan < database/perpustakaan.sql`

### Error: "Undefined column"

**Penyebab:** Tabel belum lengkap

**Solusi:**
1. Import file `database/perpustakaan_pinjam_detail.sql`
2. Pastikan semua tabel sudah ada di phpMyAdmin

### Error: "Session not found"

**Penyebab:** Header sudah di-send sebelum session_start()

**Solusi:**
1. Pastikan session_start() di awal file sebelum output HTML
2. Hapus spasi kosong sebelum `<?php`

### Halaman Blank

**Penyebab:** Error PHP tidak ditampilkan

**Solusi:**
1. Cek error log di `C:\xampp\apache\logs\error.log`
2. Aktifkan debug mode dengan menambahkan di awal:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Tidak bisa login

**Penyebab:** Username/password salah atau akun tidak ada

**Solusi:**
1. Pastikan database sudah di-import dengan sample data
2. Gunakan akun default: `kepala/kepala123` atau `staff/staff123`
3. Cek tabel pengguna di phpMyAdmin

---
---

## 📞 Support & Bantuan

Jika mengalami masalah:

1. Cek dokumentasi di README.md ini
2. Lihat bagian **Troubleshooting**
3. Periksa browser console (F12) untuk error
4. Cek server error log

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan pembelajaran/pendidikan.

---

## 👨‍💻 Penulis

**Rizki Ramadhani** - Programmer  
Tahun: 2025

---

**Terima kasih telah menggunakan Sistem Informasi Perpustakaan! 📚**

Semoga bermanfaat 😊
