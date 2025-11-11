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

// Ambil daftar anggota dan buku
$anggotaRes = daftarAnggota();
$bukuRes = daftarBuku();

if (isset($_POST['submit'])) {
    // Kumpulkan input sesuai format controller: id_buku[] dan jumlah[]
    $data = [
        'id_anggota' => (int)($_POST['id_anggota'] ?? 0),
        'tanggal_pinjam' => $_POST['tanggal_pinjam'] ?? date('Y-m-d'),
        // id_buku dan jumlah diambil sebagai array
        'id_buku' => $_POST['id_buku'] ?? [],
        'jumlah' => $_POST['jumlah'] ?? []
    ];

    $ok = tambahPinjam($data);
    if ($ok) {
        header("Location: index.php?status=sukses");
        exit;
    } else {
        $error = "Gagal menyimpan peminjaman. Pastikan stok mencukupi dan input valid.";
    }
}
?>

<div class="container py-4">
    <h3>➕ Tambah Peminjaman</h3>
    <hr>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Anggota</label>
            <select name="id_anggota" class="form-select" required>
                <option value="">-- Pilih Anggota --</option>
                <?php if ($anggotaRes): while($a = mysqli_fetch_assoc($anggotaRes)): ?>
                    <option value="<?= $a['id_anggota']; ?>"><?= htmlspecialchars($a['nama']); ?></option>
                <?php endwhile; endif; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Daftar Buku (centang buku yang dipinjam lalu isi jumlah)</label>
            <div class="list-group">
                <?php if ($bukuRes): while($b = mysqli_fetch_assoc($bukuRes)): ?>
                    <label class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <input type="checkbox" name="id_buku[]" value="<?= $b['id_buku']; ?>">
                            <strong><?= htmlspecialchars($b['judul']); ?></strong>
                            <small class="text-muted"> (stok: <?= (int)$b['stok']; ?>)</small>
                        </div>
                        <div style="width:120px;">
                            <input type="number" name="jumlah[]" class="form-control" min="1" placeholder="Jumlah">
                        </div>
                    </label>
                <?php endwhile; else: ?>
                    <div class="text-muted p-2">Tidak ada buku tersedia.</div>
                <?php endif; ?>
            </div>
            <div class="form-text">Pastikan checkbox dan input jumlah berada dalam urutan yang sesuai; controller memproses berdasarkan indeks array.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Pinjam</label>
            <input class="form-control" type="date" name="tanggal_pinjam" value="<?= date('Y-m-d'); ?>">
        </div>

        <button class="btn btn-primary" name="submit" type="submit">Simpan</button>
        <a class="btn btn-secondary" href="index.php">Kembali</a>
    </form>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
