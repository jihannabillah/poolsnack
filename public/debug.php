<?php
// Menampilkan judul halaman debug
echo "<h1>🔧 DEBUG MODE</h1>";

// ==========================================
// 1. CEK FILE-FILE PENTING APLIKASI
// ==========================================

// Daftar file-file penting yang harus ada agar aplikasi bisa berjalan
$files = [
    '../app/core/Router.php',            // File router untuk menangani routing URL
    '../app/core/Database.php',          // File untuk koneksi database
    '../app/controllers/AuthController.php', // Contoh controller untuk autentikasi
    '../app/config/routes.php'           // File yang berisi definisi semua route aplikasi
];

// Loop melalui setiap file untuk mengecek keberadaannya
foreach ($files as $file) {
    // Cek apakah file ada di sistem
    // Jika ada, tampilkan tanda centang hijau (✅)
    // Jika tidak ada, tampilkan tanda silang merah (❌) dengan pesan "MISSING!"
    echo file_exists($file) ? 
        "✅ $file<br>" : 
        "❌ $file - MISSING!<br>";
}

// ==========================================
// 2. ANALISIS URL YANG DIREQUEST
// ==========================================

// Menampilkan subjudul untuk bagian analisis URL
echo "<h3>URL Analysis:</h3>";

// Menampilkan REQUEST_URI: URL lengkap yang diakses user (termasuk query string)
// Contoh: "/pool-snack-system/public/login?error=1"
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";

// Menampilkan SCRIPT_NAME: Path ke script PHP yang sedang dieksekusi
// Contoh: "/pool-snack-system/public/index.php"
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";

// ==========================================
// 3. TEST ROUTER SEDERHANA
// ==========================================

// Menampilkan subjudul untuk bagian test router
echo "<h3>Simple Route Test:</h3>";

// Cek apakah file Router.php ada sebelum mencoba menggunakannya
if (file_exists('../app/core/Router.php')) {
    // Jika file Router.php ada, load file tersebut
    require_once '../app/core/Router.php';
    
    // Coba instansiasi objek Router
    $router = new Router();
    
    // Jika berhasil, tampilkan pesan sukses
    echo "✅ Router loaded<br>";
} else {
    // Jika file Router.php tidak ditemukan, tampilkan pesan error
    echo "❌ Router failed to load<br>";
}
?>