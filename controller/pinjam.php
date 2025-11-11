<?php 
require_once __DIR__ . '/../database/koneksi.php'; // pastikan $conn tersedia

function tampilData() {
    global $conn;
    $sql = "
        SELECT 
            p.id_pinjam,
            a.nama,
            p.tanggal_pinjam,
            p.tanggal_kembali,
            COALESCE(
                (SELECT SUM(pd.jumlah) FROM pinjam_detail pd WHERE pd.id_pinjam = p.id_pinjam),
                0
            ) AS jumlah_buku
        FROM pinjam p
        INNER JOIN anggota a ON a.id_anggota = p.id_anggota
        ORDER BY p.id_pinjam ASC
    ";
    return mysqli_query($conn, $sql);
}

function getDataByID($id) {
    global $koneksi;
    $id = (int)$id;
    $header = mysqli_query($koneksi, "SELECT p.* , a.nama
    FROM pinjam p
    INNER JOIN anggota a ON a.id_anggota = p.id_anggota
    WHERE p.id_pinjam = $id");


    $detail = mysqli_query($koneksi, "SELECT pd.* , b.judul
    FROM pinjam_detail pd
    INNER JOIN buku b ON b.id_buku = pd.id_buku
    WHERE pd.id_pinjam = $id");

    return ['head' => $header, 'detail' => $detail];
}

function kembali($id_pinjam) {
    global $koneksi;
    $id_pinjam = (int)$id_pinjam;

    mysqli_begin_transaction($koneksi);

    // Cek Status Tanggal Kembali
    $cek = mysqli_query($koneksi, "SELECT tanggal_kembali FROM pinjam WHERE id_pinjam = $id_pinjam FOR UPDATE");

    $header = mysqli_fetch_assoc($cek);
    if (!$header) { mysqli_rollback($koneksi); return false;}

    if (!is_null($header['tanggal_kembali'])) { mysqli_commit($koneksi); return true; }

    // Kembalikan Stok
    $detail = mysqli_query($koneksi, "SELECT id_buku, jumlah FROM pinjam_detail WHERE id_pinjam = $id_pinjam");
    while ($data = mysqli_fetch_assoc($detail)) {
        $buku = (int)$data['id_buku'];
        $jumlah = (int)$data['jumlah'];
        $tambahStok = "UPDATE buku SET stok = stok + $jumlah WHERE id_buku = $buku";
        if (!mysqli_query($koneksi, $tambahStok)) {mysqli_rollback($koneksi); return false;}
    }

    // Set Tanggal Kembali
    $updateTanggalKembali = mysqli_query($koneksi, "UPDATE pinjam SET tanggal_kembali = CURDATE() WHERE id_pinjam = $id_pinjam");
    if (!$updateTanggalKembali) {mysqli_rollback($koneksi); return false; }

    mysqli_commit($koneksi);
    return true;
}

function tambahPinjam($data) {
    global $koneksi;

    $id_anggota = (int)$data['id_anggota'];
    $tanggal_pinjam = mysqli_real_escape_string($koneksi, $data['tanggal_pinjam']);
    $id_buku = $data['id_buku'] ?? []; // Data nya itu masih Berbentuk Array jadi contohnya itu [5] roti
    $jumlah = $data['jumlah'] ?? [];

    if (count($id_buku) == 0) return false;

    mysqli_begin_transaction($koneksi);

    // Hitung Total judul Buku yang dipinjam
    $total = 0;
    foreach ($jumlah as $j) $total += (int)$j;

    // Insert ke Tabel Pinjam
    $queryPinjam = "INSERT INTO pinjam (id_anggota, tanggal_pinjam, jumlah_buku)
                    VALUES ($id_anggota, '$tanggal_pinjam', $total)";


    if(!mysqli_query($koneksi, $queryPinjam)) {mysqli_rollback($koneksi); return false;}
        $id_pinjam = mysqli_insert_id($koneksi);


    // Proses Detail Buku
    for ($i = 0; $i < count($id_buku); $i++) {
        $buku = (int)$id_buku[$i]; // Data nya sudah di bagi ke beda beda orang jadi satu orang dapet 1
        $qty = (int)$jumlah[$i];
        if ($qty <= 0) {mysqli_rollback($koneksi); return false;}

    // Ambil Stok Buku
        $stokBuku = mysqli_query($koneksi, "SELECT stok FROM buku WHERE id_buku = $buku FOR UPDATE");
        $row = mysqli_fetch_assoc($stokBuku);
        if (!$row || $row['stok'] < $qty) {mysqli_rollback($koneksi); return false;}

    // Insert Detail Pinjam
    $queryDetail = "INSERT INTO pinjam_detail (id_pinjam, id_buku, jumlah)
                    VALUES ($id_pinjam, $buku, $qty)";
        if (!mysqli_query($koneksi, $queryDetail)) {mysqli_rollback($koneksi); return false;}

    // Kurangi Stok pada Tabel Buku
    $queryUpdateStok = "UPDATE buku SET stok = stok - $qty WHERE id_buku = $buku";
    if (!mysqli_query($koneksi, $queryUpdateStok)) {mysqli_rollback($koneksi); return false;}
    } // Tutup Kurawal Punya For

    mysqli_commit($koneksi);
    return true;
}

function daftarAnggota() {
    global $koneksi;
    return mysqli_query($koneksi, "SELECT id_anggota, nama FROM anggota ORDER BY nama");
}


function daftarBuku() {
    global $koneksi;
    return mysqli_query($koneksi, "SELECT id_buku, judul, stok FROM buku WHERE stok > 0 ORDER BY judul");
}