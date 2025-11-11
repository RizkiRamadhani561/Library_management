<?php
/**
 * File Layout Index
 * 
 * Fungsi: Menggabungkan header, navigasi, content, dan footer
 * Penjelasan: File ini adalah template utama yang digunakan
 * oleh semua halaman untuk konsistensi layout
 */

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

// Include header
include 'header.php';

// Include navigasi
include 'navigasi.php';
?>

<!-- Main Content Area -->
<div class="container mt-4">
    <!-- Konten akan ditampilkan di sini -->
</div>

<?php
// Include footer
include 'footer.php';
?>