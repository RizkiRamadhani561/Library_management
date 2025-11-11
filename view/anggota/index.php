<?php
/**
 * View Anggota - Daftar Semua Anggota
 * 
 * Fungsi: Menampilkan tabel semua data anggota perpustakaan
 * Penjelasan: Halaman ini menampilkan data anggota dalam bentuk tabel
 * dengan fitur tambah, edit, dan hapus data
 */

// Cek session login
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

// Include database dan controller
require_once __DIR__ . '/../../database/koneksi.php';
require_once __DIR__ . '/../../controller/anggota.php';
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/navigasi.php';

// Inisialisasi controller
$controllerAnggota = new ControllerAnggota($conn);

// Ambil semua data anggota
$dataAnggota = $controllerAnggota->getAnggota();
?>

<div class="container py-4">
    <!-- Alert Status -->
    <?php 
    $status = $_GET['status'] ?? '';
    if ($status == 'sukses') {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                ✅ Data anggota berhasil ditambahkan!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } elseif ($status == 'update_sukses') {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                ✅ Data anggota berhasil diubah!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } elseif ($status == 'hapus_sukses') {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                ✅ Data anggota berhasil dihapus!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } elseif ($status == 'gagal') {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                ❌ Data gagal disimpan!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    }
    ?>

    <!-- Header dengan Tombol Tambah -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="m-0">👥 Data Anggota Perpustakaan</h3>
        <a href="tambah.php" class="btn btn-primary">
            ➕ Tambah Data Anggota
        </a>
    </div>

    <!-- Tabel Data Anggota -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">No.</th>
                    <th>Nama Anggota</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($dataAnggota) && count($dataAnggota) > 0) {
                    $no = 1;
                    foreach($dataAnggota as $row) {
                ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><strong><?= htmlspecialchars($row['nama']); ?></strong></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['no_hp']); ?></td>
                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id_anggota']; ?>" class="btn btn-sm btn-warning" title="Edit">
                            ✏️ Edit
                        </a>
                        <a href="hapus.php?id=<?= $row['id_anggota']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                            🗑️ Hapus
                        </a>
                    </td>
                </tr>
                <?php 
                    $no++;
                    }
                } else {
                ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        <em>Tidak ada data anggota</em>
                    </td>
                </tr>
                <?php 
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>