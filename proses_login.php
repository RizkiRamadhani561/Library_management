<?php 
// Memulai session untuk menyimpan data pengguna yang login
session_start();

// Daftar user dengan kredensial dan role
$users = [
    'Andi' => ['password' => '12345', 'role' => 'kepala', 'nama' => 'Kepala Perpustakaan'],
    'Budi' => ['password' => '12345', 'role' => 'staff', 'nama' => 'Staff Perpustakaan'],
];

// Mengambil data dari form POST
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validasi: cek apakah username ada dan password cocok
if (isset($users[$username]) && $password === $users[$username]['password']) {
    // Jika valid, simpan data ke session
    $_SESSION['login'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['nama'] = $users[$username]['nama'];
    $_SESSION['role'] = $users[$username]['role'];
    
    // Redirect ke dashboard
    header(header: 'Location: view/dashboard/');
    exit;
}

// Jika login gagal, redirect kembali ke login dengan pesan error
header(header: 'Location: index.php?err=Username atau password salah');
exit;
?>