<?php
/**
 * File Auth_Check (Authentication Check)
 * 
 * Fungsi: Memverifikasi apakah user sudah login sebelum mengakses halaman tertentu
 * Penjelasan: File ini digunakan untuk melindungi halaman-halaman yang memerlukan
 * autentikasi. Harus di-include di setiap halaman yang perlu proteksi.
 * 
 * Keamanan:
 * - Cek session login
 * - Regenerasi session ID untuk mencegah session fixation
 * - Validasi role user
 * - Proteksi CSRF dengan token
 * - Log aktivitas login
 */

// Mulai session dengan konfigurasi keamanan
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,     // Prevent JS access to session cookie
        'cookie_secure' => false,      // Set true jika menggunakan HTTPS
        'cookie_samesite' => 'Strict', // Prevent CSRF
        'use_strict_mode' => true,     // Hanya terima session ID dari database
    ]);
}

/**
 * Fungsi: Cek Autentikasi
 * 
 * Penjelasan: Memverifikasi apakah user sudah login
 * Jika belum login, redirect ke halaman login
 */
function checkLogin() {
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
    
    // Regenerasi session ID setiap 5 menit untuk keamanan
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } else if (time() - $_SESSION['last_regeneration'] > 300) { // 5 menit
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

/**
 * Fungsi: Cek Role (Role-Based Access Control)
 * 
 * @param string|array $role Role yang diizinkan
 * Penjelasan: Memverifikasi apakah user memiliki role yang sesuai
 * Jika tidak, tampilkan halaman error atau redirect
 */
function checkRole($role) {
    // Jika parameter $role adalah string, ubah ke array
    if (is_string($role)) {
        $role = [$role];
    }
    
    // Cek apakah role user ada di dalam array role yang diizinkan
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $role)) {
        header('HTTP/1.1 403 Forbidden');
        die("
            <div style='text-align: center; margin-top: 50px;'>
                <h1>❌ Akses Ditolak</h1>
                <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                <p>Role Anda: <strong>" . htmlspecialchars($_SESSION['role'] ?? 'Unknown') . "</strong></p>
                <a href='../../view/dashboard/' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Kembali ke Dashboard</a>
            </div>
        ");
        exit;
    }
}

/**
 * Fungsi: Buat CSRF Token
 * 
 * Penjelasan: Membuat token unik untuk mencegah CSRF attack
 * Token disimpan di session dan harus divalidasi pada form submission
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Fungsi: Validasi CSRF Token
 * 
 * Penjelasan: Memverifikasi apakah CSRF token dari form valid
 * Harus dipanggil sebelum memproses data POST
 */
function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    // Gunakan hash_equals untuk mencegah timing attack
    return hash_equals($_SESSION['csrf_token'], $token ?? '');
}

/**
 * Fungsi: Log Aktivitas
 * 
 * @param string $action Aksi yang dilakukan (LOGIN, LOGOUT, CREATE, UPDATE, DELETE, dll)
 * @param string $description Deskripsi detail aktivitas
 * Penjelasan: Mencatat semua aktivitas user untuk audit trail
 */
function logActivity($action, $description = '') {
    global $conn;
    
    $username = $_SESSION['username'] ?? 'Unknown';
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $timestamp = date('Y-m-d H:i:s');
    
    // Insert log ke database (buat tabel activity_log jika belum ada)
    $query = "
        INSERT INTO activity_log (username, action, description, ip_address, user_agent, timestamp)
        VALUES (?, ?, ?, ?, ?, ?)
    ";
    
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ssssss", $username, $action, $description, $user_ip, $user_agent, $timestamp);
        $stmt->execute();
        $stmt->close();
    }
}

/**
 * Fungsi: Sanitasi Input
 * 
 * @param mixed $data Data yang akan disanitasi
 * @return mixed Data yang sudah dibersihkan
 * Penjelasan: Membersihkan input user dari karakter berbahaya
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    
    // Hapus whitespace di awal dan akhir
    $data = trim($data);
    
    // Hapus backslashes (jika magic_quotes_gpc aktif)
    $data = stripslashes($data);
    
    // Konversi ke HTML entities untuk keamanan
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    return $data;
}

/**
 * Fungsi: Validasi Email
 * 
 * @param string $email Email yang akan divalidasi
 * @return bool True jika valid, false jika tidak
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Fungsi: Hash Password (bcrypt)
 * 
 * @param string $password Password yang akan di-hash
 * @return string Password yang sudah di-hash
 * Penjelasan: Menggunakan bcrypt untuk hashing password secara aman
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Fungsi: Verifikasi Password
 * 
 * @param string $password Password plain text
 * @param string $hash Password yang sudah di-hash
 * @return bool True jika cocok, false jika tidak
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Fungsi: Rate Limiting (Pencegahan Brute Force)
 * 
 * @param string $identifier Identifier unik (misal: email/IP address)
 * @param int $max_attempts Maksimal percobaan
 * @param int $time_window Jendela waktu dalam detik
 * @return bool True jika masih diizinkan, false jika terlampaui
 */
function checkRateLimit($identifier, $max_attempts = 5, $time_window = 300) {
    $cache_key = "rate_limit_" . md5($identifier);
    
    // Gunakan file cache atau memcached
    // Untuk kesederhanaan, kita gunakan session
    if (!isset($_SESSION[$cache_key])) {
        $_SESSION[$cache_key] = [
            'attempts' => 0,
            'first_attempt' => time()
        ];
    }
    
    $data = $_SESSION[$cache_key];
    $current_time = time();
    
    // Reset jika sudah melewati time_window
    if ($current_time - $data['first_attempt'] > $time_window) {
        $_SESSION[$cache_key] = [
            'attempts' => 1,
            'first_attempt' => $current_time
        ];
        return true;
    }
    
    // Cek apakah sudah melampaui max_attempts
    if ($data['attempts'] >= $max_attempts) {
        return false;
    }
    
    // Increment attempts
    $_SESSION[$cache_key]['attempts']++;
    return true;
}

/**
 * Fungsi: Logout User
 * 
 * Penjelasan: Menghapus session user dan redirect ke login page
 */
function logoutUser() {
    // Log activity
    logActivity('LOGOUT', 'User logout dari sistem');
    
    // Hapus semua session data
    $_SESSION = [];
    
    // Destroy session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    
    session_destroy();
    
    // Redirect ke login
    header('Location: ' . (defined('BASE_URL') ? BASE_URL : '') . 'index.php');
    exit;
}

/**
 * Fungsi: Validasi Session Timeout
 * 
 * @param int $timeout Timeout dalam detik (default: 30 menit)
 * Penjelasan: Cek apakah session sudah expired karena inaktivitas
 */
function checkSessionTimeout($timeout = 1800) {
    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > $timeout) {
            logoutUser();
        }
    }
    $_SESSION['last_activity'] = time();
}

// Panggil fungsi-fungsi proteksi di setiap request
if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['login'])) {
    // Cek session timeout
    checkSessionTimeout();
    
    // Cek session fixation
    if (!isset($_SESSION['user_agent'])) {
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    } else if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        logoutUser();
    }
}

// Definisikan BASE_URL jika belum didefinisikan
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/pertemuan_12/');
}

?>
<?php
// Include auth_check di awal file
require_once __DIR__ . '/../../auth_check.php';

// Cek apakah user sudah login
checkLogin();

// Cek apakah user memiliki role yang sesuai (contoh: hanya 'kepala')
checkRole('kepala');

// Atau untuk multiple roles:
checkRole(['kepala', 'staff']);

// Validasi CSRF Token pada form:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        die("CSRF Token tidak valid!");
    }
    // Proses form
}

// Log aktivitas
logActivity('CREATE', 'Menambah data buku: Judul Buku');

// Sanitasi input
$nama = sanitizeInput($_POST['nama']);

require_once __DIR__ . '/../../layout/header.php';
?>

<!-- Form dengan CSRF Token -->
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
    <input type="text" name="nama" required>
    <button type="submit">Simpan</button>
</form>