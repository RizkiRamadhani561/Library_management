<?php
/**
 * View Anggota - Edit Data Anggota
 * 
 * Fungsi: Menampilkan form untuk mengubah data anggota yang sudah ada
 * Penjelasan: Mengambil data lama dari database dan menampilkannya
 * di form untuk kemudian diperbarui
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

// Ambil id dari URL dan validasi
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?status=error");
    exit;
}
$id = (int)$_GET['id'];

// Ambil data lama dari database
$data = $controllerAnggota->getAnggotaById($id);

// Validasi apakah data ditemukan
if (!$data) {
    header("Location: index.php?status=error");
    exit;
}

// Proses form submit
if(isset($_POST['submit'])) {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    
    if (empty($nama) || empty($email) || empty($no_hp)) {
        $error = "Nama, Email, dan No HP harus diisi!";
    } else {
        $dataUpdate = [
            'nama' => $nama,
            'email' => $email,
            'no_hp' => $no_hp,
            'alamat' => $alamat
        ];
        
        $hasil = $controllerAnggota->updateAnggota($id, $dataUpdate);
        
        if ($hasil) {
            header("Location: index.php?status=update_sukses");
            exit;
        } else {
            $error = "Gagal mengubah data anggota!";
        }
    }
}
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <h3>✏️ Ubah Data Anggota</h3>
            <hr>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <!-- ID Anggota (Hidden) -->
                <input type="hidden" name="id_anggota" value="<?= htmlspecialchars($data['id_anggota']); ?>">
                
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
                           value="<?= htmlspecialchars($data['nama']); ?>">
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
                           value="<?= htmlspecialchars($data['email']); ?>">
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
                           value="<?= htmlspecialchars($data['no_hp']); ?>">
                </div>
                
                <!-- Alamat -->
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" 
                              id="alamat"
                              name="alamat" 
                              rows="3"><?= htmlspecialchars($data['alamat']); ?></textarea>
                </div>
                
                <!-- Tombol -->
                <div class="mb-3">
                    <button class="btn btn-primary" type="submit" name="submit">
                        💾 Simpan Perubahan
                    </button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>