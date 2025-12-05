<?php
// public/index.php - FINAL FIXED VERSION
// File utama/entry point aplikasi web Pool Snack System

// ==========================================
// 1. SET TIMEZONE KE WIB (WAJIB!)
// ==========================================
// Mengatur zona waktu server ke Waktu Indonesia Barat (WIB)
// Penting untuk konsistensi tanggal dan waktu di seluruh aplikasi
date_default_timezone_set('Asia/Jakarta');

// ==========================================
// 2. FIX KHUSUS PHP BUILT-IN SERVER (php -S)
// ==========================================
// Cek apakah aplikasi dijalankan dengan PHP built-in development server
// php_sapi_name() mengembalikan tipe interface PHP yang sedang berjalan
if (php_sapi_name() === 'cli-server') {
    // Parse URL request
    $url  = parse_url($_SERVER['REQUEST_URI']);
    
    // Buat path file yang diminta
    $file = __DIR__ . $url['path'];
    
    // Cek apakah file yang diminta benar-benar ada di sistem
    if (is_file($file)) {
        // Jika file ada, server PHP akan melayani file statis tersebut
        // Return false untuk memberi tahu PHP built-in server agar melayani file ini
        return false;
    }
    // Jika file tidak ditemukan, lanjutkan ke routing aplikasi
}

// Mulai Session untuk menyimpan data user/login
// session_id() cek apakah session sudah aktif
if(!session_id()) {
    session_start(); // Mulai session jika belum dimulai
}

// Enable error reporting (Penting untuk debugging di InfinityFree)
// Menampilkan semua error PHP untuk memudahkan debugging selama development
// NOTE: Ubah jadi 0 jika web sudah production
error_reporting(E_ALL);          // Report semua tipe error
ini_set('display_errors', 1);    // Tampilkan error di browser

// Define paths konstan yang akan digunakan di seluruh aplikasi
define('BASE_PATH', dirname(__DIR__));      // Path root project
define('APP_PATH', BASE_PATH . '/app');     // Path folder app

// ==========================================
// 3. LOAD CONFIGURATION (Database Credentials)
// ==========================================
// Load file konfigurasi yang berisi kredensial database dan konstanta lain
if (file_exists(APP_PATH . '/config/constants.php')) {
    require_once APP_PATH . '/config/constants.php';
} else {
    // Hentikan aplikasi total jika file konfigurasi tidak ditemukan
    // Karena aplikasi tidak bisa berjalan tanpa koneksi database
    die("❌ CRITICAL ERROR: File 'app/config/constants.php' tidak ditemukan. Pastikan file ini ada!");
}

// Optional: Log ke server untuk tracking startup aplikasi
// Berguna untuk debugging di hosting seperti InfinityFree
error_log("🚀 Starting application Pool Snack System...");

// ==========================================
// 4. MANUAL INCLUDES (CORE SYSTEM)
// ==========================================
// Load file-file core system secara manual untuk memastikan urutan yang benar
// Database.php harus diload SETELAH constants.php karena butuh konstanta DB
require_once APP_PATH . '/core/Router.php';   // Class untuk routing URL
require_once APP_PATH . '/core/Database.php'; // Class untuk koneksi database
require_once APP_PATH . '/core/Auth.php';     // Class untuk autentikasi
require_once APP_PATH . '/core/Session.php';  // Class untuk manajemen session

// ==========================================
// 5. AUTOLOAD (BACKUP)
// ==========================================
// Register fungsi autoload untuk otomatis load class yang dibutuhkan
spl_autoload_register(function ($class) {
    // Daftar folder tempat class-class mungkin berada
    $paths = [
        APP_PATH . '/controllers/',  // Folder controller
        APP_PATH . '/models/',       // Folder model
        APP_PATH . '/core/',         // Folder core
        APP_PATH . '/services/'      // Folder service
    ];
    
    // Cari file class di setiap folder
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true; // Class ditemukan dan diload
        }
    }
    
    return false; // Class tidak ditemukan
});

// ==========================================
// 6. DISPATCH / JALANKAN APP
// ==========================================
try {
    // Buat instance Router yang akan menangani semua routing
    $router = new Router();
    
    // Load file routes.php yang berisi definisi semua route aplikasi
    // Cek di beberapa lokasi kemungkinan untuk fleksibilitas
    if (file_exists(APP_PATH . '/config/routes.php')) {
        require_once APP_PATH . '/config/routes.php';
    } elseif (file_exists(APP_PATH . '/routes.php')) {
        require_once APP_PATH . '/routes.php';
    } else {
        throw new Exception("File routes.php tidak ditemukan di app/ maupun app/config/");
    }
    
    // Jalankan router untuk memproses request URL saat ini
    $router->dispatch();
    
} catch (Exception $e) {
    // Error handling jika terjadi exception selama eksekusi aplikasi
    
    // Log error ke server untuk debugging
    error_log("❌ Application Error: " . $e->getMessage());
    
    // Set HTTP response code ke 500 (Internal Server Error)
    http_response_code(500);
    
    // Tampilkan halaman error yang user-friendly
    echo "<div style='font-family: Arial, sans-serif; background: #fff0f0; border: 1px solid #ffcccc; padding: 20px; margin: 20px; border-radius: 5px;'>";
    echo "<h3 style='color: #d8000c; margin-top: 0;'>❌ Terjadi Kesalahan Aplikasi</h3>";
    echo "<p><strong>Pesan Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<hr style='border: 0; border-top: 1px solid #ffcccc;'>";
    echo "<p style='font-size: 0.9em; color: #555;'><strong>File:</strong> " . $e->getFile() . " <br><strong>Baris:</strong> " . $e->getLine() . "</p>";
    echo "</div>";
}
?>