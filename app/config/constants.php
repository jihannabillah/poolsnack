<?php
// app/config/constants.php
// ===========================================
// FILE KONFIGURASI KONSTANTA SISTEM
// ===========================================
// File ini berisi definisi konstanta yang digunakan secara global
// di seluruh aplikasi Pool Snack System.
// Author: [Nama Anda/Tim]
// Last Modified: [Tanggal]
// ===========================================

// ===========================================
// SECTION 1: KONSTANTA DATABASE
// ===========================================
// Konfigurasi koneksi database MySQL/MariaDB
// Sesuaikan dengan environment (development/production)
define('DB_HOST', 'localhost');      // Host database server
define('DB_NAME', 'pool_snack_system');  // Nama database yang digunakan
define('DB_USER', 'root');           // Username untuk koneksi database
define('DB_PASS', '');               // Password database (kosong di localhost)
// PERHATIAN: Di production, gunakan user dengan privilege terbatas
// PERHATIAN: Simpan password di environment variable, bukan hardcode

// ===========================================
// SECTION 2: KONSTANTA APLIKASI
// ===========================================
define('APP_NAME', 'Pool Snack System');  // Nama aplikasi untuk ditampilkan di UI
define('APP_VERSION', '1.0.0');           // Versi aplikasi (format: major.minor.patch)

// ===========================================
// SECTION 3: KONFIGURASI DYNAMIC APP_URL
// ===========================================
// Penentuan URL aplikasi secara otomatis berdasarkan environment
// Mendukung berbagai skenario development:
// 1. XAMPP/WAMP tradisional dengan folder project
// 2. PHP built-in server (php -S localhost:8000)
// 3. HTTPS di production

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];  // Mendapatkan host dari request saat ini

if ($host === 'localhost:8000') {
    // Mode: PHP built-in server
    // Contoh: php -S localhost:8000 -t public
    define('APP_URL', "$protocol://$host"); 
} else {
    // Mode: XAMPP/WAMP tradisional
    // Aplikasi berada di subdirectory 'pool-snack-system'
    // Contoh: http://localhost/pool-snack-system/public
    define('APP_URL', "$protocol://$host/pool-snack-system/public");
}
// CATATAN: Di production, bisa hardcode APP_URL untuk performa lebih baik
// Contoh: define('APP_URL', 'https://snackpool.com');

// ===========================================
// SECTION 4: KONSTANTA UPLOAD FILE
// ===========================================
// Batasan untuk upload gambar produk/snack
define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024);  // Maksimal 2MB (dalam bytes)
// Tipe MIME yang diizinkan untuk upload gambar
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg']);
// Catatan: 'image/jpg' bukan MIME type standar, biasanya cukup 'image/jpeg'

// ===========================================
// SECTION 5: KONSTANTA STATUS ORDER
// ===========================================
// Finite State Machine untuk alur status pesanan
// Urutan normal: PENDING → PROCESSING → COMPLETED
define('ORDER_STATUS_PENDING', 'menunggu_konfirmasi');    // Pesanan baru, belum dikonfirmasi
define('ORDER_STATUS_PROCESSING', 'diproses');           // Pesanan dikonfirmasi, sedang diproses
define('ORDER_STATUS_COMPLETED', 'selesai');             // Pesanan selesai dan sudah diterima
define('ORDER_STATUS_CANCELLED', 'dibatalkan');          // Pesanan dibatalkan (oleh customer/admin)

// ===========================================
// SECTION 6: KONSTANTA METODE PEMBAYARAN
// ===========================================
// Metode pembayaran yang didukung sistem
define('PAYMENT_METHOD_CASH', 'tunai');    // Pembayaran tunai di tempat
define('PAYMENT_METHOD_QRIS', 'qris');     // Pembayaran digital via QRIS

// ===========================================
// SECTION 7: KONSTANTA ROLE PENGGUNA
// ===========================================
// Sistem role-based access control (RBAC)
// Setiap role memiliki permission yang berbeda
define('ROLE_CUSTOMER', 'customer');  // Pelanggan: bisa order, lihat history
define('ROLE_KASIR', 'kasir');        // Kasir: konfirmasi order, proses pembayaran
define('ROLE_ADMIN', 'admin');        // Admin: full access, kelola master data

// ===========================================
// SECTION 8: KONSTANTA SESSION
// ===========================================
// Konfigurasi keamanan session
define('SESSION_TIMEOUT', 7200);  // Timeout session: 7200 detik = 2 jam
// Setelah waktu ini, user harus login kembali untuk keamanan

// ===========================================
// BEST PRACTICES & CATATAN PENTING:
// ===========================================
// 1. Gunakan konstanta untuk menghindari magic value di kode
// 2. Nama konstanta UPPERCASE dengan underscore sesuai konvensi PHP
// 3. File ini harus diinclude di entry point utama (index.php)
// 4. Di production, simpan sensitive data di .env file
// 5. Update APP_VERSION setiap release
// ===========================================
?>