<?php
/**
 * Model untuk mengelola data menu makanan/minuman
 * Berinteraksi dengan tabel 'menu' di database
 */
class MenuModel {
    // Properti untuk koneksi database
    private $db;
    
    // Nama tabel di database
    private $table = 'menu';

    /**
     * Constructor - Menginisialisasi koneksi database
     */
    public function __construct() {
        // Membuat koneksi ke database menggunakan class Database
        $this->db = (new Database())->getConnection();
    }

    /**
     * Mengambil SEMUA data menu tanpa filter
     * 
     * @return array Semua data menu diurutkan dari yang terbaru
     */
    public function getAll() {
        // Query: ambil semua kolom, urutkan berdasarkan tanggal dibuat (terbaru duluan)
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        
        // Prepare statement untuk keamanan
        $stmt = $this->db->prepare($query);
        
        // Eksekusi query
        $stmt->execute();
        
        // Kembalikan semua hasil dalam bentuk array asosiatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil menu yang statusnya 'tersedia' (bisa dipesan)
     * 
     * @return array Menu yang tersedia, diurutkan berdasarkan kategori lalu nama
     */
    public function getAllAvailable() {
        // Query: hanya ambil menu dengan status 'tersedia'
        // Diurutkan: kelompokkan berdasarkan kategori, lalu alfabet berdasarkan nama
        $query = "SELECT * FROM " . $this->table . " WHERE status = 'tersedia' ORDER BY kategori, nama";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil data SATU menu berdasarkan ID
     * 
     * @param int $id ID menu yang ingin diambil
     * @return array|null Data menu atau null jika tidak ditemukan
     */
    public function getById($id) {
        // Query: ambil data menu dengan ID tertentu
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameter ID untuk keamanan (mencegah SQL injection)
        $stmt->bindParam(":id", $id);
        
        $stmt->execute();
        
        // Kembalikan satu baris saja (karena berdasarkan ID pasti unik)
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Membuat/menambahkan menu baru ke database
     * 
     * @param array $data Data menu dalam bentuk array asosiatif
     * @return bool True jika berhasil, false jika gagal
     */
    public function create($data) {
        // Query INSERT: menambahkan data baru ke tabel menu
        $query = "INSERT INTO " . $this->table . " 
                  (nama, deskripsi, harga, kategori, gambar, status) 
                  VALUES (:nama, :deskripsi, :harga, :kategori, :gambar, :status)";
        
        $stmt = $this->db->prepare($query);
        
        // Eksekusi dengan data yang sudah di-binding
        return $stmt->execute($data);
    }

    /**
     * ✅ FIX: Query Update Dinamis (Cek Gambar)
     * Mengupdate data menu dengan logika khusus untuk kolom gambar
     * 
     * @param int $id ID menu yang akan diupdate
     * @param array $data Data baru untuk diupdate
     * @return bool True jika berhasil, false jika gagal
     */
    public function update($id, $data) {
        // CEK: Apakah ada data gambar baru yang diupload?
        if (isset($data['gambar'])) {
            // JIKA ADA GAMBAR BARU: update SEMUA kolom termasuk gambar
            $query = "UPDATE " . $this->table . " 
                      SET nama = :nama, deskripsi = :deskripsi, harga = :harga, 
                          kategori = :kategori, gambar = :gambar, status = :status 
                      WHERE id = :id";
        } else {
            // JIKA TIDAK ADA GAMBAR BARU: jangan update kolom gambar
            // Hanya update kolom lainnya, gambar tetap pakai yang lama
            $query = "UPDATE " . $this->table . " 
                      SET nama = :nama, deskripsi = :deskripsi, harga = :harga, 
                          kategori = :kategori, status = :status 
                      WHERE id = :id";
        }
        
        // Tambahkan ID ke dalam array data untuk binding parameter :id
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    /**
     * Menghapus menu dari database
     * 
     * @param int $id ID menu yang akan dihapus
     * @return bool True jika berhasil, false jika gagal
     */
    public function delete($id) {
        // Query DELETE: hapus data berdasarkan ID
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    /**
     * Mendapatkan statistik menu (jumlah total, kategori, status)
     * Berguna untuk dashboard admin
     * 
     * @return array Statistik dalam bentuk array asosiatif
     */
    public function getMenuStatistics() {
        // Query kompleks untuk menghitung berbagai statistik sekaligus
        $query = "SELECT 
                    COUNT(*) as total_menu,                     // Total semua menu
                    SUM(CASE WHEN status = 'tersedia' THEN 1 ELSE 0 END) as menu_tersedia,  // Menu tersedia
                    SUM(CASE WHEN status = 'habis' THEN 1 ELSE 0 END) as menu_habis,        // Menu habis
                    SUM(CASE WHEN kategori = 'makanan' THEN 1 ELSE 0 END) as total_makanan, // Menu makanan
                    SUM(CASE WHEN kategori = 'minuman' THEN 1 ELSE 0 END) as total_minuman  // Menu minuman
                  FROM " . $this->table;  // Dari tabel menu
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        // Kembalikan satu baris hasil (semua statistik dalam satu array)
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>