<?php
/**
 * Class Session - Utility class untuk mengelola session dengan aman
 * Menggunakan static methods untuk kemudahan akses dari mana saja
 */
class Session {
    
    /**
     * Memulai session jika belum dimulai
     * 
     * @return void
     */
    public static function start() {
        // Cek apakah session belum dimulai
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Mulai session
        }
    }

    /**
     * Menyimpan data ke session
     * 
     * @param string $key Kunci untuk mengakses data
     * @param mixed $value Nilai yang akan disimpan
     * @return void
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Mengambil data dari session
     * 
     * @param string $key Kunci data yang ingin diambil
     * @param mixed $default Nilai default jika kunci tidak ditemukan
     * @return mixed Nilai session atau default
     */
    public static function get($key, $default = null) {
        // Null coalescing operator: return $_SESSION[$key] jika ada, else $default
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Menghapus data tertentu dari session
     * 
     * @param string $key Kunci data yang akan dihapus
     * @return void
     */
    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]); // Hapus data dari session
        }
    }

    /**
     * Menghancurkan seluruh session
     * Berguna untuk logout
     * 
     * @return void
     */
    public static function destroy() {
        session_destroy();
    }

    /**
     * Meregenerasi session ID untuk mencegah session fixation attack
     * 
     * @return void
     */
    public static function regenerate() {
        // Parameter true: hapus session lama dari server
        session_regenerate_id(true);
    }

    // ====================================================================
    // SPECIAL SESSION MANAGEMENT UNTUK CART ISOLATION
    // ====================================================================
    
    /**
     * Menginisialisasi session user setelah login berhasil
     * Dengan security measures untuk mencegah session hijacking
     * 
     * @param array $userData Data user dari database
     * @return void
     */
    public static function initializeUserSession($userData) {
        // 1. Regenerasi session ID untuk mencegah fixation attack
        self::regenerate();
        
        // 2. Simpan data user ke session
        $_SESSION['user_id'] = $userData['id'];      // ID user dari database
        $_SESSION['role'] = $userData['role'];       // Role: customer/kasir/admin
        $_SESSION['nama'] = $userData['nama'];       // Nama user untuk tampilan
        $_SESSION['session_start'] = time();         // Waktu mulai session
        
        // 3. Clear any existing cart from previous session
        // Mencegah cart dari session sebelumnya tercampur
        self::remove('cart_items');   // Hapus cart items lama
        self::remove('meja_id');      // Hapus meja pilihan lama
        self::remove('meja_nomor');   // Hapus nomor meja lama
    }

    /**
     * Memvalidasi session user (security check)
     * Mengecek apakah session masih valid dan belum expired
     * 
     * @return bool True jika session valid, false jika tidak
     */
    public static function validateSession() {
        // 1. Cek apakah session user_id dan session_start ada
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['session_start'])) {
            return false;
        }

        // 2. Cek session timeout (2 jam = 7200 detik)
        if (time() - $_SESSION['session_start'] > 7200) {
            // Session expired, hancurkan session
            self::destroy();
            return false;
        }

        // 3. Session valid
        return true;
    }

    /**
     * Membersihkan session user (untuk logout atau switch user)
     * Menghapus data user tapi mempertahankan beberapa info session
     * 
     * @return void
     */
    public static function cleanupUserSession() {
        // Data yang ingin dipertahankan (jika ada)
        $preserve = ['session_start']; // Contoh: pertahankan session_start
        
        // 1. Simpan data yang ingin dipertahankan
        $userData = [];
        foreach ($preserve as $key) {
            if (isset($_SESSION[$key])) {
                $userData[$key] = $_SESSION[$key];
            }
        }
        
        // 2. Hancurkan session yang ada
        session_destroy();
        
        // 3. Mulai session baru
        session_start();
        
        // 4. Restore data yang ingin dipertahankan
        foreach ($userData as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
}
?>