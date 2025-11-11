<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../database/koneksi.php';
require_once __DIR__ . '/../../controller/buku.php';

$controllerBuku = new ControllerBuku($conn);

if (!isset($_GET['id'])) {
    header("Location: index.php?status=error");
    exit;
}
$id = (int)$_GET['id'];

$ok = $controllerBuku->hapusBuku($id);
if ($ok) header("Location: index.php?status=hapus_sukses");
else header("Location: index.php?status=gagal");
exit;
?>
