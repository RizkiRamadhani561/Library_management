-- =====================================================
-- DATABASE PERPUSTAKAAN
-- =====================================================
-- Fungsi: Menyimpan semua data perpustakaan
-- Penjelasan: Database ini berisi tabel-tabel untuk
-- mengelola buku, anggota, penulis, dan peminjaman

-- Hapus database jika sudah ada
DROP DATABASE IF EXISTS perpustakaan;

-- Buat database baru
CREATE DATABASE perpustakaan;
USE perpustakaan;

-- =====================================================
-- TABEL: PENULIS
-- =====================================================
-- Fungsi: Menyimpan data penulis buku
-- Field: id_penulis (PK), nama, email, negara
CREATE TABLE penulis (
    id_penulis INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    negara VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: BUKU
-- =====================================================
-- Fungsi: Menyimpan data buku di perpustakaan
-- Field: id_buku (PK), judul, id_penulis (FK), tahun, stok
CREATE TABLE buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    id_penulis INT NOT NULL,
    tahun INT,
    stok INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_penulis) REFERENCES penulis(id_penulis) ON DELETE CASCADE,
    INDEX idx_penulis (id_penulis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: ANGGOTA
-- =====================================================
-- Fungsi: Menyimpan data anggota perpustakaan
-- Field: id_anggota (PK), nama, email, no_hp, alamat
CREATE TABLE anggota (
    id_anggota INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    no_hp VARCHAR(15) UNIQUE,
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: PINJAM (Peminjaman)
-- =====================================================
-- Fungsi: Menyimpan data peminjaman buku
-- Field: id_pinjam (PK), id_anggota (FK), id_buku (FK), 
--        tanggal_pinjam, tanggal_kembali, status
CREATE TABLE pinjam (
    id_pinjam INT AUTO_INCREMENT PRIMARY KEY,
    id_anggota INT NOT NULL,
    id_buku INT NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE,
    status ENUM('aktif', 'dikembalikan', 'hilang') DEFAULT 'aktif',
    denda INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE,
    INDEX idx_anggota (id_anggota),
    INDEX idx_buku (id_buku),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: PINJAM_DETAIL (Detail Peminjaman)
-- =====================================================
-- Fungsi: Menyimpan rincian buku per peminjaman
-- Field: id_pinjam_detail (PK), id_pinjam (FK), id_buku (FK), jumlah
CREATE TABLE pinjam_detail (
    id_pinjam_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_pinjam INT NOT NULL,
    id_buku INT NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pinjam) REFERENCES pinjam(id_pinjam) ON DELETE CASCADE,
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE RESTRICT,
    INDEX idx_pinjam (id_pinjam),
    INDEX idx_buku (id_buku)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: ACTIVITY_LOG (Audit Trail)
-- =====================================================
-- Fungsi: Mencatat semua aktivitas user untuk audit
-- Field: id_log (PK), username, action, description, ip_address, user_agent, timestamp
CREATE TABLE activity_log (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    action VARCHAR(50) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_action (action),
    INDEX idx_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DATA DUMMY / SAMPLE DATA
-- =====================================================

-- Insert data PENULIS
INSERT INTO penulis (nama, email, negara) VALUES
('J.K. Rowling', 'jk.rowling@example.com', 'Inggris'),
('George R.R. Martin', 'george@example.com', 'Amerika'),
('J.R.R. Tolkien', 'tolkien@example.com', 'Inggris'),
('Stephenie Meyer', 'stephenie@example.com', 'Amerika'),
('Suzanne Collins', 'suzanne@example.com', 'Amerika'),
('Agatha Christie', 'agatha@example.com', 'Inggris'),
('Stephen King', 'stephen@example.com', 'Amerika'),
('J.R.R. Tolkien', 'tolkien2@example.com', 'Inggris'),
('Paulo Coelho', 'paulo@example.com', 'Brasil'),
('Haruki Murakami', 'haruki@example.com', 'Jepang');

-- Insert data BUKU
INSERT INTO buku (judul, id_penulis, tahun, stok) VALUES
('Harry Potter and the Philosopher\'s Stone', 1, 1997, 5),
('Harry Potter and the Chamber of Secrets', 1, 1998, 4),
('Harry Potter and the Prisoner of Azkaban', 1, 1999, 3),
('A Game of Thrones', 2, 1996, 3),
('A Clash of Kings', 2, 1998, 2),
('A Storm of Swords', 2, 2000, 2),
('The Hobbit', 3, 1937, 6),
('The Lord of the Rings: Fellowship', 3, 1954, 4),
('The Lord of the Rings: Two Towers', 3, 1954, 4),
('Twilight', 4, 2005, 3),
('New Moon', 4, 2006, 3),
('Eclipse', 4, 2007, 2),
('The Hunger Games', 5, 2008, 5),
('Catching Fire', 5, 2009, 3),
('Mockingjay', 5, 2010, 2),
('Murder on the Orient Express', 6, 1934, 4),
('The Shining', 7, 1977, 3),
('It', 7, 1986, 2),
('The Alchemist', 9, 1988, 4),
('Norwegian Wood', 10, 1987, 3);

-- Insert data ANGGOTA
INSERT INTO anggota (nama, email, no_hp, alamat) VALUES
('Ahmad Faqih', 'ahmad@example.com', '081234567890', 'Jl. Merdeka No. 1, Jakarta'),
('Siti Nurhaliza', 'siti@example.com', '081234567891', 'Jl. Sudirman No. 2, Bandung'),
('Budi Santoso', 'budi@example.com', '081234567892', 'Jl. Gatot Subroto No. 3, Surabaya'),
('Dewi Lestari', 'dewi@example.com', '081234567893', 'Jl. Ahmad Yani No. 4, Medan'),
('Rinto Harahap', 'rinto@example.com', '081234567894', 'Jl. Diponegoro No. 5, Yogyakarta'),
('Eka Putri', 'eka@example.com', '081234567895', 'Jl. Merdeka No. 10, Jakarta'),
('Fajar Rahman', 'fajar@example.com', '081234567896', 'Jl. Imam Bonjol No. 5, Semarang'),
('Gina Wijaya', 'gina@example.com', '081234567897', 'Jl. Ahmad Dahlan No. 8, Makassar'),
('Hendra Kusuma', 'hendra@example.com', '081234567898', 'Jl. Sudirman No. 15, Palembang'),
('Indra Putra', 'indra@example.com', '081234567899', 'Jl. Gatot Subroto No. 20, Bandung');

-- Insert data PINJAM
INSERT INTO pinjam (id_anggota, id_buku, tanggal_pinjam, tanggal_kembali, status) VALUES
(1, 1, '2025-11-01', '2025-11-08', 'dikembalikan'),
(2, 3, '2025-11-02', NULL, 'aktif'),
(3, 5, '2025-11-03', '2025-11-10', 'dikembalikan'),
(4, 7, '2025-11-04', NULL, 'aktif'),
(5, 8, '2025-11-05', NULL, 'aktif'),
(1, 2, '2025-11-06', NULL, 'aktif'),
(2, 4, '2025-11-07', '2025-11-14', 'dikembalikan'),
(3, 6, '2025-11-08', NULL, 'aktif'),
(4, 9, '2025-11-09', '2025-11-16', 'dikembalikan'),
(5, 10, '2025-10-20', NULL, 'aktif');

-- Insert data PINJAM_DETAIL
INSERT INTO pinjam_detail (id_pinjam, id_buku, jumlah) VALUES
(1, 1, 1),
(2, 3, 1),
(3, 5, 1),
(4, 7, 1),
(5, 8, 1),
(6, 2, 1),
(7, 4, 1),
(8, 6, 1),
(9, 9, 1),
(10, 10, 2);

-- =====================================================
-- VERIFIKASI DATA
-- =====================================================
SELECT 'Total Penulis' as Info, COUNT(*) as Jumlah FROM penulis
UNION ALL
SELECT 'Total Buku', COUNT(*) FROM buku
UNION ALL
SELECT 'Total Anggota', COUNT(*) FROM anggota
UNION ALL
SELECT 'Total Peminjaman', COUNT(*) FROM pinjam
UNION ALL
SELECT 'Total Detail Peminjaman', COUNT(*) FROM pinjam_detail;