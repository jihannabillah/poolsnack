<?php
/**
 * Model untuk mengelola data meja di restoran
 * Menangani operasi terkait meja seperti melihat ketersediaan, mengubah status, dll.
 */
class MejaModel {
    // Properti untuk menyimpan koneksi database
    private $db;
    
    // Nama tabel database yang digunakan untuk meja
    private $table = 'meja';

    /**
     * Constructor - menginisialisasi koneksi database
     * Membuat koneksi ke database saat model diinstansiasi
     */
    public function __construct() {
        // Membuat instance Database dan mendapatkan koneksi
        $this->db = (new Database())->getConnection();
    }

    // ================================================================
    // FUNGSI UTAMA UNTUK MENGELOLA MEJA
    // ================================================================

    /**
     * Mengambil semua meja yang statusnya 'tersedia'
     * Digunakan untuk menampilkan daftar meja kosong yang bisa dipilih
     * 
     * @return array Data semua meja yang tersedia
     */
    public function getAvailableTables() {
        // Query untuk mengambil meja dengan status 'tersedia', diurutkan berdasarkan nomor meja
        $query = "SELECT * FROM " . $this->table . " WHERE status = 'tersedia' ORDER BY nomor_meja";
        
        // Prepare statement untuk keamanan
        $stmt = $this->db->prepare($query);
        
        // Eksekusi query
        $stmt->execute();
        
        // Mengembalikan semua hasil dalam bentuk array asosiatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fungsi alias untuk kompatibilitas dengan controller yang sudah ada
     * Beberapa controller memanggil metode getAllAvailable() 
     * Jadi dibuat alias yang memanggil getAvailableTables()
     * 
     * @return array Hasil dari getAvailableTables()
     */
    public function getAllAvailable() {
        // Memanggil fungsi utama getAvailableTables()
        return $this->getAvailableTables();
    }

    /**
     * Mengubah status meja menjadi 'terpakai'
     * Menyimpan informasi customer yang sedang menggunakan meja
     * 
     * @param int $mejaId ID meja yang akan diubah statusnya
     * @param int $userId ID customer yang menggunakan meja
     * @param string $sessionId Session ID customer
     * @return bool True jika berhasil, false jika gagal
     */
    public function occupyTable($mejaId, $userId, $sessionId) {
        // Query untuk mengupdate status meja menjadi 'terpakai'
        // Juga menyimpan customer_id dan session_id untuk tracking
        $query = "UPDATE " . $this->table . " 
                  SET status = 'terpakai', 
                      customer_id = :customer_id, 
                      session_id = :session_id 
                  WHERE id = :id";
        
        // Prepare statement
        $stmt = $this->db->prepare($query);
        
        // Eksekusi dengan parameter yang aman (mencegah SQL injection)
        return $stmt->execute([
            'customer_id' => $userId,    // ID customer dari session
            'session_id' => $sessionId,  // Session ID untuk tracking
            'id' => $mejaId              // ID meja yang dipilih
        ]);
    }

    /**
     * Mengubah status meja menjadi 'tersedia' kembali
     * Menghapus informasi customer dari meja
     * 
     * @param int $mejaId ID meja yang akan dilepaskan
     * @return bool True jika berhasil, false jika gagal
     */
    public function releaseTable($mejaId) {
        // Query untuk mengembalikan status meja ke 'tersedia'
        // Juga menghapus customer_id dan session_id (mengubah menjadi NULL)
        $query = "UPDATE " . $this->table . " 
                  SET status = 'tersedia', 
                      customer_id = NULL, 
                      session_id = NULL 
                  WHERE id = :id";
        
        // Prepare statement
        $stmt = $this->db->prepare($query);
        
        // Bind parameter ID meja
        $stmt->bindParam(":id", $mejaId);
        
        // Eksekusi query
        return $stmt->execute();
    }

    /**
     * Mengambil data satu meja berdasarkan ID
     * 
     * @param int $id ID meja yang ingin diambil datanya
     * @return array|null Data meja jika ditemukan, null jika tidak
     */
    public function getTableById($id) {
        // Query untuk mengambil data satu meja berdasarkan ID
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        
        // Prepare statement
        $stmt = $this->db->prepare($query);
        
        // Bind parameter ID meja
        $stmt->bindParam(":id", $id);
        
        // Eksekusi query
        $stmt->execute();
        
        // Mengembalikan satu baris hasil dalam bentuk array asosiatif
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>