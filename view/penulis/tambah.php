<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../database/koneksi.php';
require_once __DIR__ . '/../../controller/penulis.php';
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/navigasi.php';

$controller = new ControllerPenulis($conn);

if (isset($_POST['submit'])) {
    $data = [
        'nama' => trim($_POST['nama'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'negara' => trim($_POST['negara'] ?? ''),
    ];
    if (empty($data['nama'])) $error = "Nama wajib diisi.";
    else {
        $ok = $controller->tambahPenulis($data);
        if ($ok) header("Location: index.php?status=sukses");
        else $error = "Gagal menambah penulis.";
    }
}
?>

<div class="container py-4">
    <h3>➕ Tambah Penulis</h3>
    <hr>
    <?php if (isset($error)) : ?><div class="alert alert-danger"><?= htmlspecialchars($error); ?></div><?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input class="form-control" name="nama" required value="<?= htmlspecialchars($_POST['nama'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" name="email" type="email" value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Negara</label>
            <input class="form-control" name="negara" value="<?= htmlspecialchars($_POST['negara'] ?? ''); ?>">
        </div>
        <button class="btn btn-primary" name="submit" type="submit">Simpan</button>
        <a class="btn btn-secondary" href="index.php">Kembali</a>
    </form>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>