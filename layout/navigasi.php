<?php
/**
 * File Navigasi
 * 
 * Fungsi: Menampilkan menu navigasi utama aplikasi
 * Penjelasan: Berisi navbar dengan link ke semua halaman utama
 * Tampilannya berbeda tergantung role user (kepala/staff)
 */
?>
<!-- Navbar Bootstrap -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <!-- Brand/Logo -->
        <a class="navbar-brand" href="../../view/dashboard/">
            📚 Sistem Perpustakaan
        </a>
        
        <!-- Toggle button untuk mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Menu items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../../view/dashboard/">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../view/anggota/">Anggota</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../view/buku/">Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../view/penulis/">Penulis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../view/pinjam/">Peminjaman</a>
                </li>
                
                <!-- Hanya untuk role kepala -->
                <?php if ($_SESSION['role'] === 'kepala') : ?>
                <li class="nav-item">
                    <a class="nav-link" href="../../view/laporan/">Laporan</a>
                </li>
                <?php endif; ?>
                
                <!-- Logout -->
                <li class="nav-item">
                    <a class="nav-link text-danger" href="../../logout.php">Logout (<?= $_SESSION['username']; ?>)</a>
                </li>
            </ul>
        </div>
    </div>
</nav>