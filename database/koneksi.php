<?php
/**
 * File Koneksi Database
 * 
 * Fungsi: Menghubungkan aplikasi ke database MySQL
 * Penjelasan: File ini berisi konfigurasi dan koneksi ke database
 * yang akan digunakan oleh seluruh aplikasi
 */

// Konfigurasi database
$servername = "localhost";    // Server MySQL
$username = "root";           // Username MySQL
$password = "";               // Password MySQL
$dbname = "perpustakaan";     // Nama database

// Membuat koneksi ke database
$conn = new mysqli(hostname: $servername, username: $username, password: $password, database: $dbname);

// Mengecek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset UTF-8 untuk mendukung karakter Indonesia
$conn->set_charset("utf8");

?>

