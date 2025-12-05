<?php
// app/core/Auth.php

/**
 * Class Auth - Utility class untuk menangani autentikasi dan otorisasi user
 * Menggunakan static methods sehingga bisa dipanggil tanpa instansiasi
 */
class Auth {
    
    /**
     * Memeriksa apakah user sudah login dan memiliki role yang sesuai
     * 
     * @param string|null $role Role yang diizinkan mengakses (opsional)
     * @return bool Mengembalikan true jika validasi berhasil
     * 
     * Contoh penggunaan:
     * 1. Auth::checkAuth()                    // Cek login saja
     * 2. Auth::checkAuth('customer')          // Cek login DAN role customer
     * 3. Auth::checkAuth('admin')             // Cek login DAN role admin
     */
    public static function checkAuth($role = null) {
        // 1. CEK APAKAH USER SUDAH LOGIN (Authentication)
        if (!isset($_SESSION['user_id'])) {
            // Jika belum login, redirect ke halaman login
            // ✅ FIX: Menggunakan APP_URL untuk path yang konsisten
            header('Location: ' . APP_URL . '/login');
            exit(); // Hentikan eksekusi script setelah redirect
        }
        
        // 2. CEK ROLE USER (Authorization - jika parameter $role diberikan)
        if ($role && $_SESSION['role'] !== $role) {
            // Jika role tidak sesuai, redirect ke halaman utama/home
            // ✅ FIX: Menggunakan APP_URL untuk path yang konsisten
            header('Location: ' . APP_URL . '/');
            exit(); // Hentikan eksekusi script setelah redirect
        }
        
        // 3. JIKA SEMUA VALIDASI BERHASIL
        return true;
    }

    /**
     * Mengambil role user yang sedang login dari session
     * 
     * @return string|null Role user atau null jika belum login
     * 
     * Contoh penggunaan:
     * $role = Auth::getUserRole();
     * if ($role === 'admin') { ... }
     */
    public static function getUserRole() {
        // Mengembalikan role dari session atau null jika tidak ada
        return $_SESSION['role'] ?? null;
    }

    /**
     * Redirect user berdasarkan role mereka setelah login
     * Berguna setelah proses login berhasil
     * 
     * @return void Tidak mengembalikan nilai (melakukan redirect)
     * 
     * Contoh penggunaan:
     * Auth::redirectBasedOnRole();
     */
    public static function redirectBasedOnRole() {
        // Ambil role user yang sedang login
        $role = self::getUserRole();
        
        // ✅ FIX: Semua redirect menggunakan APP_URL . '/path' untuk konsistensi
        switch ($role) {
            case 'customer':
                // Customer diarahkan ke dashboard customer
                header('Location: ' . APP_URL . '/customer/dashboard');
                break;
            case 'kasir':
                // Kasir diarahkan ke dashboard kasir
                header('Location: ' . APP_URL . '/kasir/dashboard');
                break;
            case 'admin':
                // Admin diarahkan ke dashboard admin
                header('Location: ' . APP_URL . '/admin/dashboard');
                break;
            default:
                // Jika role tidak dikenali atau tidak ada, redirect ke login
                // Ini bisa terjadi jika session rusak atau belum login
                header('Location: ' . APP_URL . '/login');
        }
        exit(); // Hentikan eksekusi script setelah redirect
    }
}
?>