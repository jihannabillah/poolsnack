<?php
// app/core/Database.php

/**
 * Class Database - Singleton class untuk mengelola koneksi database
 * Menggunakan PDO (PHP Data Objects) untuk koneksi yang aman dan konsisten
 */
class Database {
    // Properti untuk menyimpan koneksi database
    public $conn;

    /**
     * Method untuk mendapatkan/membuat koneksi database
     * Menggunakan pattern singleton (hanya satu koneksi yang aktif)
     * 
     * @return PDO Mengembalikan object PDO connection
     * 
     * Contoh penggunaan:
     * $db = new Database();
     * $connection = $db->getConnection();
     */
    public function getConnection() {
        // Reset koneksi sebelumnya (jika ada)
        $this->conn = null;

        try {
            // ========================================================
            // 1. SETUP DSN (Data Source Name)
            // ========================================================
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            // Penjelasan:
            // - mysql:        : Driver database MySQL
            // - host=DB_HOST  : Host server database (localhost/127.0.0.1)
            // - dbname=DB_NAME: Nama database
            // - charset=utf8mb4: Encoding untuk support emoji dan karakter Unicode lengkap
            
            // ========================================================
            // 2. BUKA KONEKSI KE DATABASE
            // ========================================================
            // Membuat instance PDO dengan parameter DSN, username, password
            $this->conn = new PDO($dsn, DB_USER, DB_PASS);
            
            // ========================================================
            // 3. SETTING ATRIBUT PDO
            // ========================================================
            
            // a. Error Mode: Throw exception jika ada error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Opsi lain:
            // - PDO::ERRMODE_SILENT  : Tidak throw exception (default)
            // - PDO::ERRMODE_WARNING : Throw PHP warning
            // - PDO::ERRMODE_EXCEPTION: Throw PDOException (direkomendasikan)
            
            // b. Default Fetch Mode: Mengembalikan array associative
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // Opsi lain:
            // - PDO::FETCH_NUM      : Array numeric [0, 1, 2]
            // - PDO::FETCH_BOTH     : Array numeric dan associative
            // - PDO::FETCH_OBJ      : Object dengan properti sesuai nama kolom
            // - PDO::FETCH_CLASS    : Instance class tertentu

            // ========================================================
            // 4. ✅ SETTING TIMEZONE KE WIB (+7 JAM)
            // ========================================================
            // Menjalankan query SQL untuk set timezone database ke WIB
            // Penting untuk konsistensi tanggal/waktu di seluruh aplikasi
            $this->conn->exec("SET time_zone = '+07:00';");
            // Penjelasan:
            // - Saat menyimpan timestamp di database, akan menggunakan WIB
            // - Saat membaca timestamp dari database, akan diformat sebagai WIB
            // - Mencegah perbedaan waktu antara server dan aplikasi
            
        } catch(PDOException $exception) {
            // ========================================================
            // 5. ERROR HANDLING
            // ========================================================
            // Menangkap exception jika koneksi gagal
            // Untuk production, sebaiknya log ke file dan tampilkan pesan umum
            die("Koneksi Database Gagal: " . $exception->getMessage());
            
            // Alternatif untuk production:
            // error_log("Database Error: " . $exception->getMessage());
            // header("Location: /error/500");
            // exit;
        }

        // ========================================================
        // 6. RETURN KONEKSI
        // ========================================================
        return $this->conn;
    }
}
?>