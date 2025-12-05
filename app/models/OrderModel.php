<?php
/**
 * Model untuk mengelola data order/pesanan
 * Menangani operasi dari customer (order, history) dan kasir (manage orders)
 */
class OrderModel {
    // Properti untuk koneksi database
    private $db;
    
    // Nama tabel utama untuk order
    private $table = 'orders';

    /**
     * Constructor - Menginisialisasi koneksi database
     */
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    // =========================================================
    // 1. BAGIAN CUSTOMER (Checkout, Nota, History)
    // =========================================================

    /**
     * Membuat order baru di database
     * 
     * @param array $data Data order dalam bentuk array asosiatif
     * @return int|false ID order baru jika berhasil, false jika gagal
     */
    public function createOrder($data) {
        // Generate nomor order unik: ORD + tanggal + waktu + random number
        $orderNumber = 'ORD' . date('YmdHis') . rand(100, 999);
        
        // Query INSERT untuk membuat order baru
        $query = "INSERT INTO " . $this->table . " 
                  (order_number, user_id, meja_id, total_harga, metode_bayar, bukti_bayar, status, created_at) 
                  VALUES (:ord_num, :uid, :mid, :total, :method, :proof, :status, NOW())";
        
        $stmt = $this->db->prepare($query);
        
        // Binding parameter satu per satu untuk kejelasan
        $stmt->bindValue(':ord_num', $orderNumber);      // Nomor order unik
        $stmt->bindValue(':uid', $data['user_id']);      // ID customer
        $stmt->bindValue(':mid', $data['meja_id']);      // ID meja
        $stmt->bindValue(':total', $data['total_amount']); // Total harga
        $stmt->bindValue(':method', $data['payment_method']); // Cara bayar
        $stmt->bindValue(':proof', $data['payment_proof']);   // Bukti bayar (jika ada)
        $stmt->bindValue(':status', $data['status']);    // Status awal
        
        // Eksekusi query
        if ($stmt->execute()) {
            // Jika berhasil, kembalikan ID order yang baru dibuat
            return $this->db->lastInsertId();
        }
        return false; // Gagal
    }

    /**
     * Menambahkan item/detail ke dalam order yang sudah dibuat
     * 
     * @param int $orderId ID order
     * @param int $menuId ID menu yang dipesan
     * @param int $qty Jumlah pesanan
     * @param float $price Harga satuan
     * @return bool True jika berhasil, false jika gagal
     */
    public function addOrderDetail($orderId, $menuId, $qty, $price) {
        // Hitung subtotal: jumlah × harga
        $subtotal = $qty * $price;
        
        // Query INSERT ke tabel order_items
        $query = "INSERT INTO order_items (order_id, menu_id, quantity, harga_satuan, subtotal) 
                  VALUES (:oid, :mid, :qty, :price, :subtotal)";
        
        $stmt = $this->db->prepare($query);
        
        // Eksekusi dengan array parameter
        return $stmt->execute([
            'oid' => $orderId,
            'mid' => $menuId,
            'qty' => $qty,
            'price' => $price,
            'subtotal' => $subtotal
        ]);
    }
    
    /**
     * Mengambil semua order milik customer tertentu
     * 
     * @param int $userId ID customer
     * @return array Daftar order customer (untuk halaman riwayat)
     */
    public function getCustomerOrders($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :uid ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':uid', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil detail lengkap satu order termasuk item-itemnya
     * 
     * @param int $orderId ID order yang ingin dilihat
     * @return array|null Data order lengkap atau null jika tidak ditemukan
     */
    public function getOrderWithItems($orderId) {
        // Query utama: ambil data order + info meja + nama customer
        $query = "SELECT o.*, m.nomor_meja, u.nama as customer_name 
                  FROM " . $this->table . " o 
                  JOIN meja m ON o.meja_id = m.id 
                  JOIN users u ON o.user_id = u.id 
                  WHERE o.id = :order_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_id', $orderId);
        $stmt->execute();
        
        // Ambil data order utama
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika order ditemukan, ambil juga item-itemnya
        if ($order) {
            // Query kedua: ambil semua item dalam order ini
            $queryItems = "SELECT oi.*, menu.nama, menu.gambar 
                           FROM order_items oi 
                           JOIN menu ON oi.menu_id = menu.id 
                           WHERE oi.order_id = :order_id";
            
            $stmtItems = $this->db->prepare($queryItems);
            $stmtItems->bindValue(':order_id', $orderId);
            $stmtItems->execute();
            
            // Tambahkan array items ke data order
            $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        }
        return $order;
    }

    // =========================================================
    // 2. BAGIAN KASIR (Dashboard, List, Update)
    // =========================================================

    /**
     * Mengambil order berdasarkan status tertentu
     * Digunakan untuk dashboard kasir (lihat order menunggu, diproses, dll)
     * 
     * @param string $status Status order yang dicari
     * @return array Daftar order dengan status tersebut
     */
    public function getOrdersByStatus($status) {
        $query = "SELECT o.*, m.nomor_meja, u.nama as customer_name 
                  FROM " . $this->table . " o 
                  JOIN meja m ON o.meja_id = m.id 
                  JOIN users u ON o.user_id = u.id 
                  WHERE o.status = :status 
                  ORDER BY o.created_at ASC"; // ASC: order tertua pertama
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil SEMUA order tanpa filter
     * Digunakan sebagai fallback jika method filter tidak tersedia
     * 
     * @return array Semua order dalam sistem
     */
    public function getAllOrders() {
        $query = "SELECT o.*, m.nomor_meja, u.nama as customer_name 
                  FROM " . $this->table . " o 
                  JOIN meja m ON o.meja_id = m.id 
                  JOIN users u ON o.user_id = u.id 
                  ORDER BY o.created_at DESC"; // DESC: order terbaru pertama
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengupdate status order
     * 
     * @param int $orderId ID order yang akan diupdate
     * @param string $status Status baru
     * @return bool True jika berhasil, false jika gagal
     */
    public function updateStatus($orderId, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $orderId);
        return $stmt->execute();
    }

    // =========================================================
    // 3. BAGIAN FILTER (INI YANG SEBELUMNYA HILANG)
    // =========================================================
    
    /**
     * ✅ Method Filter Canggih (Status + Tanggal + Metode)
     * Mengambil order dengan filter dinamis berdasarkan parameter
     * 
     * @param array $filters Array filter dengan key 'status', 'date', 'payment'
     * @return array Daftar order yang sesuai dengan filter
     */
    public function getFilteredOrders($filters = []) {
        // Query dasar dengan JOIN untuk data lengkap
        $query = "SELECT o.*, m.nomor_meja, u.nama as customer_name 
                  FROM " . $this->table . " o 
                  JOIN meja m ON o.meja_id = m.id 
                  JOIN users u ON o.user_id = u.id 
                  WHERE 1=1"; // Trik SQL: WHERE 1=1 agar bisa tambah AND dinamis
        
        $params = []; // Untuk menyimpan parameter yang akan di-binding

        // 1. Filter Status (Jika ada di URL atau input)
        if (!empty($filters['status'])) {
            $query .= " AND o.status = :status";
            $params[':status'] = $filters['status'];
        }

        // 2. Filter Tanggal (Format: YYYY-MM-DD)
        if (!empty($filters['date'])) {
            // Hanya ambil tanggal (DATE()) dari created_at
            $query .= " AND DATE(o.created_at) = :date";
            $params[':date'] = $filters['date'];
        }

        // 3. Filter Metode Bayar (cash, qris, dll)
        if (!empty($filters['payment'])) {
            $query .= " AND o.metode_bayar = :payment";
            $params[':payment'] = $filters['payment'];
        }

        // Urutkan dari yang terbaru
        $query .= " ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($query);
        
        // Binding semua parameter secara otomatis
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>