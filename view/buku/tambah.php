<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../database/koneksi.php';
require_once __DIR__ . '/../../controller/buku.php';
require_once __DIR__ . '/../../controller/penulis.php';
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/navigasi.php';

$controllerBuku = new ControllerBuku($conn);
$controllerPenulis = new ControllerPenulis($conn);
$penulisList = $controllerPenulis->getPenulis();

if (isset($_POST['submit'])) {
    $data = [
        'judul' => trim($_POST['judul'] ?? ''),
        'id_penulis' => (int)($_POST['id_penulis'] ?? 0),
        'tahun' => (int)($_POST['tahun'] ?? 0),
        'stok' => (int)($_POST['stok'] ?? 0),
    ];
    if (empty($data['judul']) || $data['id_penulis'] <= 0) {
        $error = "Judul dan Penulis harus diisi.";
    } else {
        $ok = $controllerBuku->tambahBuku($data);
        if ($ok) header("Location: index.php?status=sukses");
        else $error = "Gagal menyimpan data buku.";
    }
}
?>

<div class="container py-4">
    <h3>➕ Tambah Buku</h3>
    <hr>
    <?php if (isset($error)) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input class="form-control" name="judul" required value="<?= htmlspecialchars($_POST['judul'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Penulis</label>
            <select name="id_penulis" class="form-select" required>
                <option value="">-- Pilih Penulis --</option>
                <?php foreach($penulisList as $p): ?>
                    <option value="<?= $p['id_penulis']; ?>" <?= (isset($_POST['id_penulis']) && $_POST['id_penulis']==$p['id_penulis'])? 'selected' : ''; ?>><?= htmlspecialchars($p['nama']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Tahun</label>
            <input class="form-control" name="tahun" type="number" value="<?= htmlspecialchars($_POST['tahun'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Stok</label>
            <input class="form-control" name="stok" type="number" value="<?= htmlspecialchars($_POST['stok'] ?? 0); ?>">
        </div>
        <button class="btn btn-primary" name="submit" type="submit">Simpan</button>
        <a class="btn btn-secondary" href="index.php">Kembali</a>
    </form>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>