<?php
/**
 * Class CartModel - Model untuk mengelola data keranjang belanja
 * Berinteraksi dengan database untuk operasi CRUD pada cart
 */
class CartModel {
    // Properti untuk koneksi database dan nama tabel
    private $db;
    private $table = 'cart'; // Pastikan nama tabel di database kamu 'cart' atau 'keranjang'

    /**
     * Constructor - Menginisialisasi koneksi database
     */
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    // ====================================================================
    // OPERASI DASAR CART
    // ====================================================================
    
    /**
     * 1. Tambah Item ke Keranjang (Cek duplikat dulu)
     * 
     * @param int $userId ID user pemilik keranjang
     * @param int $menuId ID menu yang akan ditambahkan
     * @param int $quantity Jumlah item (default: 1)
     * @return bool True jika berhasil, false jika gagal
     * 
     * Logika: Jika item sudah ada, tambah quantity. Jika belum, insert baru.
     */
    public function addItem($userId, $menuId, $quantity = 1) {
        // CEK APAKAH ITEM SUDAH ADA DI CART USER INI
        $checkQuery = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id AND menu_id = :menu_id";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->execute(['user_id' => $userId, 'menu_id' => $menuId]);
        
        if ($checkStmt->rowCount() > 0) {
            // JIKA ADA: update quantity-nya saja (tambah quantity)
            $updateQuery = "UPDATE " . $this->table . " SET quantity = quantity + :quantity WHERE user_id = :user_id AND menu_id = :menu_id";
            $updateStmt = $this->db->prepare($updateQuery);
            return $updateStmt->execute([
                'quantity' => $quantity,
                'user_id' => $userId,
                'menu_id' => $menuId
            ]);
        } else {
            // JIKA BELUM ADA: insert baru
            $insertQuery = "INSERT INTO " . $this->table . " (user_id, menu_id, quantity) VALUES (:user_id, :menu_id, :quantity)";
            $insertStmt = $this->db->prepare($insertQuery);
            return $insertStmt->execute([
                'user_id' => $userId,
                'menu_id' => $menuId,
                'quantity' => $quantity
            ]);
        }
    }

    /**
     * 2. Ambil Semua Item dari Keranjang (Untuk Halaman Keranjang)
     * 
     * @param int $userId ID user pemilik keranjang
     * @return array Array berisi semua item keranjang beserta detail menu
     * 
     * Menggunakan JOIN untuk mengambil data dari tabel cart dan menu
     */
    public function getCartItems($userId) {
        // Join ke tabel menu untuk ambil nama, harga, gambar
        $query = "SELECT c.*, m.nama, m.harga, m.gambar, m.deskripsi 
                  FROM " . $this->table . " c 
                  JOIN menu m ON c.menu_id = m.id 
                  WHERE c.user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3. Hitung Total Harga Keranjang
     * 
     * @param int $userId ID user pemilik keranjang
     * @return float Total harga semua item dalam keranjang
     * 
     * Menghitung: SUM(quantity * harga) untuk semua item di keranjang
     */
    public function getCartTotal($userId) {
        $query = "SELECT SUM(c.quantity * m.harga) as total 
                  FROM " . $this->table . " c 
                  JOIN menu m ON c.menu_id = m.id 
                  WHERE c.user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0; // Default 0 jika keranjang kosong
    }

    /**
     * 4. Hitung Jumlah Total Item (Untuk Badge Notifikasi)
     * 
     * @param int $userId ID user pemilik keranjang
     * @return int Total jumlah item (quantity dari semua item)
     * 
     * Contoh: 2 burger + 3 soda = 5 item total
     */
    public function getCartItemCount($userId) {
        $query = "SELECT SUM(quantity) as count FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0; // Default 0 jika keranjang kosong
    }

    /**
     * 5. Ambil Satu Item Spesifik berdasarkan ID Cart Item
     * WAJIB ADA: Dipakai Controller saat update (+ / -)
     * 
     * @param int $cartItemId ID item di tabel cart (bukan menu_id)
     * @return array|null Data item cart atau null jika tidak ditemukan
     */
    public function getItemById($cartItemId) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $cartItemId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 6. Update Quantity Item
     * 
     * @param int $cartItemId ID item di tabel cart
     * @param int $quantity Jumlah baru
     * @return bool True jika berhasil, false jika gagal
     */
    public function updateItem($cartItemId, $quantity) {
        $query = "UPDATE " . $this->table . " SET quantity = :quantity WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'quantity' => $quantity,
            'id' => $cartItemId
        ]);
    }

    /**
     * 7. Hapus Item dari Keranjang
     * 
     * @param int $cartItemId ID item di tabel cart
     * @return bool True jika berhasil, false jika gagal
     */
    public function removeItem($cartItemId) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $cartItemId]);
    }

    /**
     * 8. Kosongkan Keranjang (Setelah Checkout)
     * 
     * @param int $userId ID user pemilik keranjang
     * @return bool True jika berhasil, false jika gagal
     */
    public function clearCart($userId) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['user_id' => $userId]);
    }
}
?>