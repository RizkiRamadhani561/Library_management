<?php
/**
 * Proses Hapus Data Anggota
 * 
 * Fungsi: Menghapus data anggota dari database
 * Penjelasan: Script ini menangani penghapusan data dan redirect
 * kembali ke halaman index dengan status
 */

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../database/koneksi.php';
require_once __DIR__ . '/../../controller/anggota.php';

// Inisialisasi controller
$controllerAnggota = new ControllerAnggota($conn);

// Ambil id dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?status=error");
    exit;
}

$id = (int)$_GET['id'];

// Hapus data
$hasil = $controllerAnggota->hapusAnggota($id);

if ($hasil) {
    header("Location: index.php?status=hapus_sukses");
    exit;
} else {
    header("Location: index.php?status=gagal");
    exit;
}
?>