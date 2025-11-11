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
if (!isset($_GET['id'])) { header("Location: index.php?status=error"); exit; }
$id = (int)$_GET['id'];
$data = $controller->getPenulisById($id);
if (!$data) { header("Location: index.php?status=error"); exit; }

if (isset($_POST['submit'])) {
    $update = [
        'nama' => trim($_POST['nama'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'negara' => trim($_POST['negara'] ?? ''),
    ];
    $ok = $controller->updatePenulis($id, $update);
    if ($ok) header("Location: index.php?status=update_sukses");
    else $error = "Gagal mengubah data penulis.";
}
?>

<div class="container py-4">
    <h3>✏️ Ubah Penulis</h3>
    <hr>
    <?php if (isset($error)) : ?><div class="alert alert-danger"><?= htmlspecialchars($error); ?></div><?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input class="form-control" name="nama" required value="<?= htmlspecialchars($data['nama']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" name="email" type="email" value="<?= htmlspecialchars($data['email'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Negara</label>
            <input class="form-control" name="negara" value="<?= htmlspecialchars($data['negara'] ?? ''); ?>">
        </div>
        <button class="btn btn-primary" name="submit" type="submit">Simpan Perubahan</button>
        <a class="btn btn-secondary" href="index.php">Kembali</a>
    </form>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>