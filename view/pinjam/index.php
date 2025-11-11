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

// Ambil daftar peminjaman
$result = tampilData();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between mb-4">
        <h3>📤 Data Peminjaman</h3>
        <a class="btn btn-primary" href="tambah.php">➕ Tambah Peminjaman</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Anggota</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Jumlah Buku</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_kembali'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['jumlah_buku'] ?? 0); ?></td>
                    <td>
                        <a class="btn btn-sm btn-info" href="detail.php?id=<?= $row['id_pinjam']; ?>">Detail</a>
                        <?php if (empty($row['tanggal_kembali'])): ?>
                        <a class="btn btn-sm btn-success" href="kembali.php?id=<?= $row['id_pinjam']; ?>" onclick="return confirm('Tandai dikembalikan?')">Kembali</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="6" class="text-center text-muted"><em>Tidak ada data peminjaman</em></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>