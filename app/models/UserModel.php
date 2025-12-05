<?php
/**
 * Model untuk mengelola data user/pengguna
 * Menangani operasi autentikasi, registrasi, dan manajemen user
 */
class UserModel {
    // Properti untuk koneksi database
    private $db;
    
    // Nama tabel database untuk user
    private $table = 'users';

    /**
     * Constructor - Menginisialisasi koneksi database
     */
    public function __construct() {
        // Membuat koneksi ke database menggunakan class Database
        $this->db = (new Database())->getConnection();
    }

    /**
     * Memverifikasi kredensial login user
     * Mengecek email dan password yang diinput dengan data di database
     * 
     * @param string $email Email yang diinput user
     * @param string $password Password yang diinput user (plain text)
     * @return array|false Data user jika berhasil, false jika gagal
     * 
     * Proses:
     * 1. Cari user berdasarkan email dan status active
     * 2. Jika ditemukan, verifikasi password dengan password_verify()
     * 3. Jika password cocok, return data user
     */
    public function authenticate($email, $password) {
        // Query untuk mencari user berdasarkan email
        // Hanya user dengan status 'active' yang bisa login
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email AND status = 'active'";
        
        $stmt = $this->db->prepare($query);
        
        // Binding parameter email untuk keamanan (mencegah SQL injection)
        $stmt->bindParam(":email", $email);
        
        $stmt->execute();
        
        // Cek apakah ada user dengan email tersebut
        if ($stmt->rowCount() > 0) {
            // Ambil data user dari database
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verifikasi password menggunakan password_verify()
            // Fungsi ini membandingkan password plain text dengan hash di database
            if (password_verify($password, $user['password'])) {
                // Jika password cocok, kembalikan data user
                return $user;
            }
        }
        
        // Jika email tidak ditemukan atau password salah
        return false;
    }

    /**
     * Membuat/mendaftarkan user baru
     * 
     * @param array $data Data user baru dalam bentuk array
     *   Format: ['nama' => '...', 'email' => '...', 'password' => '...', 'role' => '...']
     *   Note: password harus sudah di-hash sebelum dikirim ke method ini
     * @return bool True jika berhasil, false jika gagal
     */
    public function create($data) {
        // Query INSERT untuk membuat user baru
        // Menggunakan standar SQL (VALUES) agar konsisten dengan model lain
        $query = "INSERT INTO " . $this->table . " 
                  (nama, email, password, role, status) 
                  VALUES (:nama, :email, :password, :role, 'active')";
        
        $stmt = $this->db->prepare($query);
        
        // Binding data dengan array parameter (aman dari SQL injection)
        return $stmt->execute([
            'nama' => $data['nama'],        // Nama lengkap user
            'email' => $data['email'],      // Email user (unik)
            'password' => $data['password'], // Password yang sudah di-hash
            'role' => $data['role']         // Role: 'customer', 'kasir', atau 'admin'
        ]);
    }

    /**
     * Mencari user berdasarkan email
     * Digunakan untuk validasi (cek apakah email sudah terdaftar)
     * 
     * @param string $email Email yang ingin dicari
     * @return array|null Data user jika ditemukan, null jika tidak
     */
    public function findByEmail($email) {
        // Query sederhana untuk mencari user berdasarkan email
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        // Mengembalikan satu baris data user atau null jika tidak ditemukan
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>