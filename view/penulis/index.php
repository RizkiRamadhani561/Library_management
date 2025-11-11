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
$penulis = $controller->getPenulis();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between mb-4">
        <h3>✍️ Data Penulis</h3>
        <a class="btn btn-primary" href="tambah.php">➕ Tambah Penulis</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr><th>No.</th><th>Nama</th><th>Email</th><th>Negara</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if (!empty($penulis)): $no=1; foreach($penulis as $p): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($p['nama']); ?></td>
                    <td><?= htmlspecialchars($p['email']); ?></td>
                    <td><?= htmlspecialchars($p['negara'] ?? '-'); ?></td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="edit.php?id=<?= $p['id_penulis']; ?>">✏️ Edit</a>
                        <a class="btn btn-sm btn-danger" href="hapus.php?id=<?= $p['id_penulis']; ?>" onclick="return confirm('Yakin hapus?')">🗑️ Hapus</a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center text-muted"><em>Tidak ada data penulis</em></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>