<?php
/**
 * Controller Anggota
 * 
 * Fungsi: Menangani logika bisnis untuk data anggota perpustakaan
 * Penjelasan: Berisi fungsi CRUD untuk mengelola data anggota
 */

require_once '../../database/koneksi.php';

class ControllerAnggota {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Fungsi untuk mendapatkan semua anggota
    public function getAnggota() {
        $query = "SELECT * FROM anggota ORDER BY nama ASC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Fungsi untuk mendapatkan anggota berdasarkan ID
    public function getAnggotaById($id) {
        $query = "SELECT * FROM anggota WHERE id_anggota = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Fungsi untuk menambah anggota baru
    public function tambahAnggota($data) {
        $query = "INSERT INTO anggota (nama, alamat, telepon) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $data['nama'], $data['alamat'], $data['telepon']);
        return $stmt->execute();
    }
    
    // Fungsi untuk mengupdate data anggota
    public function updateAnggota($id, $data) {
        $query = "UPDATE anggota SET nama = ?, alamat = ?, telepon = ? WHERE id_anggota = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $data['nama'], $data['alamat'], $data['telepon'], $id);
        return $stmt->execute();
    }
    
    // Fungsi untuk menghapus data anggota
    public function hapusAnggota($id) {
        $query = "DELETE FROM anggota WHERE id_anggota = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>