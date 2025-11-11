<?php
/**
 * Laporan Denda
 * 
 * Fungsi: Menampilkan laporan denda peminjaman
 * Penjelasan: Menghitung denda berdasarkan keterlambatan
 * Tarif: Rp 5.000 per hari keterlambatan
 */

session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'kepala') {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../database/koneksi.php';
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/navigasi.php';

$tarif_denda = 5000; // Rp 5.000 per hari

$query = "
    SELECT 
        p.id_pinjam,
        a.nama as nama_anggota,
        a.no_hp,
        pd.judul as nama_buku,
        p.tanggal_pinjam,
        p.tanggal_kembali,
        DATEDIFF(
            IF(p.tanggal_kembali IS NULL, CURDATE(), p.tanggal_kembali),
            p.tanggal_pinjam
        ) - 14 as hari_keterlambatan,
        CASE 
            WHEN DATEDIFF(
                IF(p.tanggal_kembali IS NULL, CURDATE(), p.tanggal_kembali),
                p.tanggal_pinjam
            ) > 14 THEN (DATEDIFF(
                IF(p.tanggal_kembali IS NULL, CURDATE(), p.tanggal_kembali),
                p.tanggal_pinjam
            ) - 14) * $tarif_denda
            ELSE 0
        END as total_denda,
        CASE 
            WHEN p.tanggal_kembali IS NULL THEN 'Belum Dikembalikan'
            ELSE 'Sudah Dikembalikan'
        END as status_pengembalian
    FROM pinjam p
    INNER JOIN anggota a ON a.id_anggota = p.id_anggota
    LEFT JOIN pinjam_detail pdt ON pdt.id_pinjam = p.id_pinjam
    LEFT JOIN buku pd ON pd.id_buku = pdt.id_buku
    WHERE (DATEDIFF(
        IF(p.tanggal_kembali IS NULL, CURDATE(), p.tanggal_kembali),
        p.tanggal_pinjam
    ) - 14) > 0
    ORDER BY total_denda DESC
";

$result = $conn->query($query);

// Hitung total denda
$totalQuery = "
    SELECT SUM(
        CASE 
            WHEN DATEDIFF(
                IF(p.tanggal_kembali IS NULL, CURDATE(), p.tanggal_kembali),
                p.tanggal_pinjam
            ) > 14 THEN (DATEDIFF(
                IF(p.tanggal_kembali IS NULL, CURDATE(), p.tanggal_kembali),
                p.tanggal_pinjam
            ) - 14) * $tarif_denda
            ELSE 0
        END
    ) as total_denda FROM pinjam p
    WHERE (DATEDIFF(
        IF(p.tanggal_kembali IS NULL, CURDATE(), p.tanggal_kembali),
        p.tanggal_pinjam
    ) - 14) > 0
";

$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalDenda = $totalRow['total_denda'] ?? 0;
?>

<div class="container py-4">
    <h3>💰 Laporan Denda</h3>
    <hr>

    <div class="mb-3">
        <button type="button" class="btn btn-success" onclick="window.print()">🖨️ Cetak Laporan</button>
    </div>

    <div class="card mb-4 bg-light">
        <div class="card-body">
            <h5>Total Denda yang Harus Dibayar:</h5>
            <h3 class="text-danger">Rp <?= number_format($totalDenda, 0, ',', '.'); ?></h3>
            <small class="text-muted">Tarif: Rp <?= number_format($tarif_denda, 0, ',', '.'); ?> per hari keterlambatan</small>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>ID Pinjam</th>
                    <th>Nama Anggota</th>
                    <th>No HP</th>
                    <th>Nama Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Hari Terlambat</th>
                    <th>Total Denda</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result && $result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['id_pinjam']); ?></td>
                    <td><?= htmlspecialchars($row['nama_anggota']); ?></td>
                    <td><?= htmlspecialchars($row['no_hp'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['nama_buku'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_kembali'] ?? '-'); ?></td>
                    <td><?= (int)$row['hari_keterlambatan']; ?> hari</td>
                    <td><strong>Rp <?= number_format($row['total_denda'], 0, ',', '.'); ?></strong></td>
                    <td>
                        <span class="badge <?= ($row['status_pengembalian'] == 'Belum Dikembalikan') ? 'bg-danger' : 'bg-warning text-dark'; ?>">
                            <?= htmlspecialchars($row['status_pengembalian']); ?>
                        </span>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                ?>
                <tr>
                    <td colspan="10" class="text-center text-success"><em>✅ Tidak ada denda yang harus dibayar</em></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>