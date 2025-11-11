<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../controller/pinjam.php';

// pastikan $koneksi tersedia untuk fungsi di controller/pinjam.php
global $koneksi;
if (!isset($koneksi) && isset($conn)) $koneksi = $conn;

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = (int)$_GET['id'];

$ok = kembali($id);
if ($ok) header("Location: index.php?status=kembali_sukses");
else header("Location: index.php?status=gagal");
exit;
?>