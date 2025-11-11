<?php 
/**
 * ControllerPenulis
 * Fungsi: CRUD untuk tabel penulis
 */

class ControllerPenulis {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Ambil semua penulis
    public function getPenulis() {
        $sql = "SELECT * FROM penulis ORDER BY nama ASC";
        $res = $this->conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Ambil penulis berdasarkan ID
    public function getPenulisById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM penulis WHERE id_penulis = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Tambah penulis baru
    public function tambahPenulis($data) {
        $stmt = $this->conn->prepare("INSERT INTO penulis (nama, email, negara) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['nama'], $data['email'], $data['negara']);
        return $stmt->execute();
    }

    // Update data penulis
    public function updatePenulis($id, $data) {
        $stmt = $this->conn->prepare("UPDATE penulis SET nama = ?, email = ?, negara = ? WHERE id_penulis = ?");
        $stmt->bind_param("sssi", $data['nama'], $data['email'], $data['negara'], $id);
        return $stmt->execute();
    }

    // Hapus penulis
    public function hapusPenulis($id) {
        $stmt = $this->conn->prepare("DELETE FROM penulis WHERE id_penulis = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>