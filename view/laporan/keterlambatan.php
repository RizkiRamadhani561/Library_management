<?php
/**
 * Laporan Keterlambatan
 * 
 * Fungsi: Menampilkan peminjaman yang terlambat dikembalikan
 * Penjelasan: Periode peminjaman > 14 hari dan belum dikembalikan
 */

session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'kepala') {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../database/koneksi.php';
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/navigasi.php';

$query = "
    SELECT 
        p.id_pinjam,
        a.nama as nama_anggota,
        a.no_hp,
        pd.judul as nama_buku,
        p.tanggal_pinjam,
        DATEDIFF(CURDATE(), p.tanggal_pinjam) as hari_tertangguh,
        COALESCE(pdt.jumlah, 1) as jumlah_buku
    FROM pinjam p
    INNER JOIN anggota a ON a.id_anggota = p.id_anggota
    LEFT JOIN pinjam_detail pdt ON pdt.id_pinjam = p.id_pinjam
    LEFT JOIN buku pd ON pd.id_buku = pdt.id_buku
    WHERE p.tanggal_kembali IS NULL 
    AND DATEDIFF(CURDATE(), p.tanggal_pinjam) > 14
    ORDER BY p.tanggal_pinjam ASC
";

$result = $conn->query($query);
?>

<div class="container py-4">
    <h3>⏰ Laporan Keterlambatan</h3>
    <hr>

    <div class="mb-3">
        <button type="button" class="btn btn-success" onclick="window.print()">🖨️ Cetak Laporan</button>
    </div>

    <div class="alert alert-warning">
        <strong>⚠️ Perhatian!</strong> Laporan menampilkan peminjaman yang melampaui batas 14 hari dan belum dikembalikan.
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
                    <th>Tanggal Pinjam</th>
                    <th>Hari Tertangguh</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result && $result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                ?>
                <tr class="table-danger">
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['id_pinjam']); ?></td>
                    <td><?= htmlspecialchars($row['nama_anggota']); ?></td>
                    <td><?= htmlspecialchars($row['no_hp'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['nama_buku'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                    <td><strong><?= (int)$row['hari_tertangguh']; ?> hari</strong></td>
                    <td><?= (int)$row['jumlah_buku']; ?></td>
                </tr>
                <?php 
                    }
                } else {
                ?>
                <tr>
                    <td colspan="8" class="text-center text-success"><em>✅ Tidak ada peminjaman yang terlambat</em></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>