<?php
/**
 * Controller Buku
 * 
 * Fungsi: Menangani logika bisnis untuk data buku
 * Penjelasan: Berisi fungsi CRUD (Create, Read, Update, Delete)
 * untuk mengelola data buku di database
 */

require_once '../../database/koneksi.php';

class ControllerBuku {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Fungsi untuk mengambil semua data buku
     * @return array - Array berisi data semua buku
     */
    public function getBuku() {
        $query = "SELECT * FROM buku";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Fungsi untuk mengambil data buku berdasarkan ID
     * @param int $id - ID buku yang dicari
     * @return array - Data buku dengan ID tertentu
     */
    public function getBukuById($id) {
        $query = "SELECT * FROM buku WHERE id_buku = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Fungsi untuk menambah data buku baru
     * @param array $data - Data buku yang akan ditambahkan
     * @return bool - true jika berhasil, false jika gagal
     */
    public function tambahBuku($data) {
        $query = "INSERT INTO buku (judul, id_penulis, tahun) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sii", $data['judul'], $data['id_penulis'], $data['tahun']);
        return $stmt->execute();
    }
    
    /**
     * Fungsi untuk mengupdate data buku
     * @param int $id - ID buku yang akan diupdate
     * @param array $data - Data buku yang baru
     * @return bool - true jika berhasil, false jika gagal
     */
    public function updateBuku($id, $data) {
        $query = "UPDATE buku SET judul = ?, id_penulis = ?, tahun = ? WHERE id_buku = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("siii", $data['judul'], $data['id_penulis'], $data['tahun'], $id);
        return $stmt->execute();
    }
    
    /**
     * Fungsi untuk menghapus data buku
     * @param int $id - ID buku yang akan dihapus
     * @return bool - true jika berhasil, false jika gagal
     */
    public function hapusBuku($id) {
        $query = "DELETE FROM buku WHERE id_buku = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>