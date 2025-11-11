<?php
/**
 * Laporan Pengembalian
 * 
 * Fungsi: Menampilkan laporan data pengembalian buku
 * Penjelasan: Filter berdasarkan tanggal pengembalian
 */

session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'kepala') {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../database/koneksi.php';
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/navigasi.php';

$tanggal_mulai = $_GET['tanggal_mulai'] ?? date('Y-m-01');
$tanggal_akhir = $_GET['tanggal_akhir'] ?? date('Y-m-d');

$query = "
    SELECT 
        p.id_pinjam,
        a.nama as nama_anggota,
        pd.judul as nama_buku,
        p.tanggal_pinjam,
        p.tanggal_kembali,
        DATEDIFF(p.tanggal_kembali, p.tanggal_pinjam) as hari_dipinjam,
        CASE 
            WHEN DATEDIFF(p.tanggal_kembali, p.tanggal_pinjam) > 14 THEN 'Terlambat'
            ELSE 'Tepat Waktu'
        END as status_ketepatan,
        COALESCE(pdt.jumlah, 1) as jumlah_buku
    FROM pinjam p
    INNER JOIN anggota a ON a.id_anggota = p.id_anggota
    LEFT JOIN pinjam_detail pdt ON pdt.id_pinjam = p.id_pinjam
    LEFT JOIN buku pd ON pd.id_buku = pdt.id_buku
    WHERE p.tanggal_kembali BETWEEN ? AND ?
    ORDER BY p.tanggal_kembali DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $tanggal_mulai, $tanggal_akhir);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-4">
    <h3>📥 Laporan Pengembalian</h3>
    <hr>

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
                    <a href="pengembalian.php" class="btn btn-secondary ms-2">Reset</a>
                    <button type="button" class="btn btn-success ms-2" onclick="window.print()">🖨️ Cetak</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>ID Pinjam</th>
                    <th>Nama Anggota</th>
                    <th>Nama Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Hari Dipinjam</th>
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
                    <td><?= htmlspecialchars($row['tanggal_kembali']); ?></td>
                    <td><?= (int)$row['hari_dipinjam']; ?> hari</td>
                    <td>
                        <span class="badge <?= ($row['status_ketepatan'] == 'Terlambat') ? 'bg-danger' : 'bg-success'; ?>">
                            <?= htmlspecialchars($row['status_ketepatan']); ?>
                        </span>
                    </td>
                    <td><?= (int)$row['jumlah_buku']; ?></td>
                </tr>
                <?php 
                    }
                } else {
                ?>
                <tr>
                    <td colspan="9" class="text-center text-muted"><em>Tidak ada data pengembalian pada periode ini</em></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>