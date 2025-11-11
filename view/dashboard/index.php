<?php
/**
 * View Dashboard
 * 
 * Fungsi: Menampilkan halaman utama/dashboard aplikasi
 * Penjelasan: Halaman yang pertama kali dilihat user setelah login
 * Menampilkan ringkasan statistik perpustakaan
 */

// Cek session login
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

// Include database connection
require_once __DIR__ . '/../../database/koneksi.php';

// Include layout header
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/navigasi.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?>!</h1>
            <p class="text-muted">Role: <?= htmlspecialchars($_SESSION['role']); ?></p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">📚 Total Buku</h5>
                    <p class="card-text">
                        <?php $r = $conn->query("SELECT COUNT(*) as total FROM buku")->fetch_assoc(); echo $r['total']; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">👥 Total Anggota</h5>
                    <p class="card-text">
                        <?php $r = $conn->query("SELECT COUNT(*) as total FROM anggota")->fetch_assoc(); echo $r['total']; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">📤 Peminjaman Aktif</h5>
                    <p class="card-text">
                        <?php $r = $conn->query("SELECT COUNT(*) as total FROM pinjam WHERE status='aktif'")->fetch_assoc(); echo $r['total']; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">✍️ Total Penulis</h5>
                    <p class="card-text">
                        <?php $r = $conn->query("SELECT COUNT(*) as total FROM penulis")->fetch_assoc(); echo $r['total']; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>