<?php
/**
 * Laporan Anggota
 * 
 * Fungsi: Menampilkan laporan data anggota perpustakaan
 * Penjelasan: Laporan lengkap semua anggota dengan info kontak
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
        a.*,
        COUNT(p.id_pinjam) as total_pinjam,
        SUM(CASE WHEN p.status = 'aktif' THEN 1 ELSE 0 END) as pinjam_aktif
    FROM anggota a
    LEFT JOIN pinjam p ON p.id_anggota = a.id_anggota
    GROUP BY a.id_anggota
    ORDER BY a.nama ASC
";

$result = $conn->query($query);
?>

<div class="container py-4">
    <h3>👥 Laporan Anggota</h3>
    <hr>

    <div class="mb-3">
        <button type="button" class="btn btn-success" onclick="window.print()">🖨️ Cetak Laporan</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Nama Anggota</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Total Pinjam</th>
                    <th>Pinjam Aktif</th>
                    <th>Tgl Daftar</th>
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
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['email'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['no_hp'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['alamat'] ?? '-'); ?></td>
                    <td><?= (int)($row['total_pinjam'] ?? 0); ?></td>
                    <td><?= (int)($row['pinjam_aktif'] ?? 0); ?></td>
                    <td><?= htmlspecialchars($row['created_at'] ?? '-'); ?></td>
                </tr>
                <?php 
                    }
                } else {
                ?>
                <tr>
                    <td colspan="8" class="text-center text-muted"><em>Tidak ada data anggota</em></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>