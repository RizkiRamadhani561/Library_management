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
$bukuList = $controllerBuku->getBuku();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="m-0">📚 Data Buku</h3>
        <a href="tambah.php" class="btn btn-primary">➕ Tambah Buku</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th style="width:50px">No.</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th style="width:150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bukuList)) : $no=1; foreach($bukuList as $b): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($b['judul']); ?></td>
                    <td><?= htmlspecialchars($b['nama'] ?? $b['nama_penulis'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($b['tahun'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($b['stok'] ?? 0); ?></td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="edit.php?id=<?= $b['id_buku']; ?>">✏️ Edit</a>
                        <a class="btn btn-sm btn-danger" href="hapus.php?id=<?= $b['id_buku']; ?>" onclick="return confirm('Yakin ingin menghapus?')">🗑️ Hapus</a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center text-muted"><em>Tidak ada data buku</em></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>