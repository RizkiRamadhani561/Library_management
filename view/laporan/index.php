<?php
/**
 * View Laporan - Daftar Laporan
 * 
 * Fungsi: Menampilkan menu laporan yang tersedia
 * Penjelasan: Halaman ini hanya bisa diakses oleh role 'kepala'
 * Menyediakan link ke berbagai jenis laporan
 */

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

// Cek role: hanya kepala yang bisa akses laporan
if ($_SESSION['role'] !== 'kepala') {
    header('Location: ../../view/dashboard/?error=Akses%20ditolak');
    exit;
}

require_once __DIR__ . '/../../database/koneksi.php';
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/navigasi.php';
?>

<div class="container py-4">
    <h3>📊 Menu Laporan</h3>
    <hr>
    <p class="text-muted">Halaman ini hanya bisa diakses oleh Kepala Perpustakaan</p>

    <div class="row">
        <!-- Laporan Peminjaman -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">📤 Laporan Peminjaman</h5>
                    <p class="card-text">Lihat laporan data peminjaman buku berdasarkan periode tanggal</p>
                    <a href="peminjaman.php" class="btn btn-primary btn-sm">Buka Laporan</a>
                </div>
            </div>
        </div>

        <!-- Laporan Pengembalian -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">📥 Laporan Pengembalian</h5>
                    <p class="card-text">Lihat laporan data pengembalian buku berdasarkan periode tanggal</p>
                    <a href="pengembalian.php" class="btn btn-primary btn-sm">Buka Laporan</a>
                </div>
            </div>
        </div>

        <!-- Laporan Anggota -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">👥 Laporan Anggota</h5>
                    <p class="card-text">Lihat laporan data anggota perpustakaan</p>
                    <a href="anggota.php" class="btn btn-primary btn-sm">Buka Laporan</a>
                </div>
            </div>
        </div>

        <!-- Laporan Inventaris Buku -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">📚 Laporan Inventaris Buku</h5>
                    <p class="card-text">Lihat laporan stok buku yang tersedia</p>
                    <a href="inventaris.php" class="btn btn-primary btn-sm">Buka Laporan</a>
                </div>
            </div>
        </div>

        <!-- Laporan Keterlambatan -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">⏰ Laporan Keterlambatan</h5>
                    <p class="card-text">Lihat laporan peminjaman yang terlambat dikembalikan</p>
                    <a href="keterlambatan.php" class="btn btn-primary btn-sm">Buka Laporan</a>
                </div>
            </div>
        </div>

        <!-- Laporan Denda -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">💰 Laporan Denda</h5>
                    <p class="card-text">Lihat laporan denda peminjaman yang belum terbayar</p>
                    <a href="denda.php" class="btn btn-primary btn-sm">Buka Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>