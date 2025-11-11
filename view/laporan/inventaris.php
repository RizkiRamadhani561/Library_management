<?php
/**
 * Laporan Inventaris Buku
 * 
 * Fungsi: Menampilkan laporan stok buku yang tersedia
 * Penjelasan: Menampilkan semua buku dengan stok dan informasi detail
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
        b.id_buku,
        b.judul,
        p.nama as nama_penulis,
        b.tahun,
        b.stok,
        COUNT(pd.id_pinjam_detail) as sedang_dipinjam,
        (b.stok - COALESCE(COUNT(pd.id_pinjam_detail), 0)) as stok_tersedia,
        CASE 
            WHEN b.stok = 0 THEN 'Habis'
            WHEN (b.stok - COALESCE(COUNT(pd.id_pinjam_detail), 0)) < 2 THEN 'Kritis'
            ELSE 'Normal'
        END as status_stok
    FROM buku b
    LEFT JOIN penulis p ON p.id_penulis = b.id_penulis
    LEFT JOIN pinjam_detail pd ON pd.id_buku = b.id_buku
    GROUP BY b.id_buku
    ORDER BY b.judul ASC
";

$result = $conn->query($query);
?>

<div class="container py-4">
    <h3>📚 Laporan Inventaris Buku</h3>
    <hr>

    <div class="mb-3">
        <button type="button" class="btn btn-success" onclick="window.print()">🖨️ Cetak Laporan</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Tahun</th>
                    <th>Stok Total</th>
                    <th>Sedang Dipinjam</th>
                    <th>Stok Tersedia</th>
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
                    <td><?= htmlspecialchars($row['judul']); ?></td>
                    <td><?= htmlspecialchars($row['nama_penulis'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['tahun'] ?? '-'); ?></td>
                    <td><?= (int)$row['stok']; ?></td>
                    <td><?= (int)$row['sedang_dipinjam']; ?></td>
                    <td><?= (int)$row['stok_tersedia']; ?></td>
                    <td>
                        <span class="badge 
                            <?php 
                            if ($row['status_stok'] == 'Habis') echo 'bg-danger';
                            elseif ($row['status_stok'] == 'Kritis') echo 'bg-warning text-dark';
                            else echo 'bg-success';
                            ?>
                        ">
                            <?= htmlspecialchars($row['status_stok']); ?>
                        </span>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                ?>
                <tr>
                    <td colspan="8" class="text-center text-muted"><em>Tidak ada data buku</em></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>