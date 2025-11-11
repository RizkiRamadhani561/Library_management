<?php
/**
 * View Anggota - Tambah Data Anggota Baru
 * 
 * Fungsi: Menampilkan form untuk menambah anggota baru
 * Penjelasan: Form ini menangkap input nama, email, no_hp, dan alamat
 * kemudian mengirimnya ke proses tambah data
 */

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../database/koneksi.php';
require_once __DIR__ . '/../../controller/anggota.php';
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/navigasi.php';

// Inisialisasi controller
$controllerAnggota = new ControllerAnggota($conn);

// Proses form submit
if(isset($_POST['submit'])) {
    // Validasi input
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    
    // Cek input kosong
    if (empty($nama) || empty($email) || empty($no_hp)) {
        $error = "Nama, Email, dan No HP harus diisi!";
    } else {
        // Persiapkan data
        $data = [
            'nama' => $nama,
            'email' => $email,
            'no_hp' => $no_hp,
            'alamat' => $alamat
        ];
        
        // Coba tambah data
        $hasil = $controllerAnggota->tambahAnggota($data);
        
        if ($hasil) {
            header("Location: index.php?status=sukses");
            exit;
        } else {
            $error = "Gagal menambah data anggota!";
        }
    }
}
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <h3>➕ Tambah Data Anggota Baru</h3>
            <hr>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="" method="POST" novalidate>
                <!-- Nama Anggota -->
                <div class="mb-3">
                    <label for="nama" class="form-label">
                        Nama Anggota <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="nama"
                           name="nama" 
                           required 
                           placeholder="Masukkan nama anggota"
                           value="<?= htmlspecialchars($_POST['nama'] ?? ''); ?>">
                </div>
                
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           class="form-control" 
                           id="email"
                           name="email" 
                           required 
                           placeholder="Masukkan email"
                           value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <!-- No HP -->
                <div class="mb-3">
                    <label for="no_hp" class="form-label">
                        No HP <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="no_hp"
                           name="no_hp" 
                           required 
                           placeholder="Masukkan nomor HP"
                           value="<?= htmlspecialchars($_POST['no_hp'] ?? ''); ?>">
                </div>
                
                <!-- Alamat -->
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" 
                              id="alamat"
                              name="alamat" 
                              rows="3" 
                              placeholder="Masukkan alamat"><?= htmlspecialchars($_POST['alamat'] ?? ''); ?></textarea>
                </div>
                
                <!-- Tombol -->
                <div class="mb-3">
                    <button class="btn btn-primary" type="submit" name="submit">
                        💾 Simpan Data
                    </button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>