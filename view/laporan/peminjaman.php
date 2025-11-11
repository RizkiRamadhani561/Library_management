<?php
/**
 * Laporan Peminjaman
 * 
 * Fungsi: Menampilkan laporan data peminjaman dalam periode tertentu
 * Penjelasan: User bisa filter berdasarkan tanggal mulai dan tanggal akhir
 */

session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'kepala') {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../database/koneksi.php';
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/navigasi.php';

// Filter tanggal
$tanggal_mulai = $_GET['tanggal_mulai'] ?? date('Y-m-01');
$tanggal_akhir = $_GET['tanggal_akhir'] ?? date('Y-m-d');

// Query laporan peminjaman
$query = "
    SELECT 
        p.id_pinjam,
        a.nama as nama_anggota,
        pd.judul as nama_buku,
        p.tanggal_pinjam,
        p.tanggal_kembali,
        CASE 
            WHEN p.tanggal_kembali IS NULL THEN 'Belum Dikembalikan'
            WHEN p.tanggal_kembali > DATE_ADD(p.tanggal_pinjam, INTERVAL 14 DAY) THEN 'Terlambat'
            ELSE 'Tepat Waktu'
        END as status_pengembalian,
        COALESCE(pdt.jumlah, 1) as jumlah_buku
    FROM pinjam p
    INNER JOIN anggota a ON a.id_anggota = p.id_anggota
    LEFT JOIN pinjam_detail pdt ON pdt.id_pinjam = p.id_pinjam
    LEFT JOIN buku pd ON pd.id_buku = pdt.id_buku
    WHERE p.tanggal_pinjam BETWEEN ? AND ?
    ORDER BY p.tanggal_pinjam DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $tanggal_mulai, $tanggal_akhir);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-4">
    <h3>📤 Laporan Peminjaman</h3>
    <hr>

    <!-- Form Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="tanggal_mulai" value="<?= htmlspecialchars($tanggal_mulai); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="tanggal_akhir" value="<?= htmlspecialchars($tanggal_akhir); ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="peminjaman.php" class="btn btn-secondary ms-2">Reset</a>
                    <button type="button" class="btn btn-success ms-2" onclick="window.print()">🖨️ Cetak</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>ID Pinjam</th>
                    <th>Nama Anggota</th>
                    <th>Nama Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Jumlah</th>
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
                    <td><?= htmlspecialchars($row['nama_buku'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_kembali'] ?? '-'); ?></td>
                    <td>
                        <span class="badge 
                            <?php 
                            if ($row['status_pengembalian'] == 'Belum Dikembalikan') echo 'bg-warning';
                            elseif ($row['status_pengembalian'] == 'Terlambat') echo 'bg-danger';
                            else echo 'bg-success';
                            ?>
                        ">
                            <?= htmlspecialchars($row['status_pengembalian']); ?>
                        </span>
                    </td>
                    <td><?= (int)$row['jumlah_buku']; ?></td>
                </tr>
                <?php 
                    }
                } else {
                ?>
                <tr>
                    <td colspan="8" class="text-center text-muted"><em>Tidak ada data peminjaman pada periode ini</em></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>