<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/navigasi.php';
require_once __DIR__ . '/../../controller/pinjam.php';

// pastikan $koneksi tersedia untuk fungsi di controller/pinjam.php
global $koneksi;
if (!isset($koneksi) && isset($conn)) $koneksi = $conn;

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = (int)$_GET['id'];

$data = getDataByID($id);
if (!$data || !$data['head'] || mysqli_num_rows($data['head']) == 0) {
    header("Location: index.php");
    exit;
}

$head = mysqli_fetch_assoc($data['head']);
?>

<div class="container py-4">
    <h3>Detail Peminjaman #<?= $head['id_pinjam']; ?></h3>
    <hr>

    <dl class="row">
        <dt class="col-sm-3">Anggota</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($head['nama']); ?></dd>

        <dt class="col-sm-3">Tanggal Pinjam</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($head['tanggal_pinjam']); ?></dd>

        <dt class="col-sm-3">Tanggal Kembali</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($head['tanggal_kembali'] ?? '-'); ?></dd>

        <dt class="col-sm-3">Jumlah Buku</dt>
        <dd class="col-sm-9"><?= (int)$head['jumlah_buku']; ?></dd>
    </dl>

    <h5>Rincian Buku</h5>
    <table class="table table-bordered">
        <thead class="table-dark"><tr><th>No.</th><th>Judul</th><th>Jumlah</th></tr></thead>
        <tbody>
            <?php if ($data['detail'] && mysqli_num_rows($data['detail'])>0): $no=1; while($d = mysqli_fetch_assoc($data['detail'])): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($d['judul']); ?></td>
                <td><?= (int)$d['jumlah']; ?></td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="3" class="text-center text-muted">Tidak ada detail</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a class="btn btn-secondary" href="index.php">Kembali</a>
    <?php if (empty($head['tanggal_kembali'])): ?>
        <a class="btn btn-success" href="kembali.php?id=<?= $head['id_pinjam']; ?>" onclick="return confirm('Tandai dikembalikan?')">Tandai Dikembalikan</a>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>