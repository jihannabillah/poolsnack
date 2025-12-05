<?php
/**
 * Model untuk mengelola data pembayaran
 * Menangani operasi terkait pembayaran seperti membuat, verifikasi, dan melihat data pembayaran
 */
class PaymentModel {
    // Properti untuk koneksi database
    private $db;
    
    // Nama tabel database untuk pembayaran
    private $table = 'payments';

    /**
     * Constructor - Menginisialisasi koneksi database
     */
    public function __construct() {
        // Membuat koneksi ke database menggunakan class Database
        $this->db = (new Database())->getConnection();
    }

    /**
     * Membuat/mencatat pembayaran baru ke database
     * 
     * @param int $orderId ID order yang dibayar
     * @param float $amount Jumlah uang yang dibayarkan
     * @param string $method Metode pembayaran (cash, qris, transfer, dll)
     * @param string|null $proof Nama file bukti pembayaran (untuk transfer/qris), null untuk cash
     * @return bool True jika berhasil, false jika gagal
     */
    public function createPayment($orderId, $amount, $method, $proof = null) {
        // Query untuk menambahkan data pembayaran baru
        // Status default: 'pending' (menunggu verifikasi)
        $query = "INSERT INTO payments (order_id, amount, method, proof_image, status) 
                  VALUES (:order_id, :amount, :method, :proof_image, 'pending')";
        
        // Prepare statement untuk keamanan
        $stmt = $this->db->prepare($query);
        
        // Eksekusi dengan parameter yang aman
        return $stmt->execute([
            'order_id' => $orderId,    // ID order yang dibayar
            'amount' => $amount,       // Jumlah pembayaran
            'method' => $method,       // Metode: 'cash', 'qris', 'transfer'
            'proof_image' => $proof    // File bukti: bisa null untuk cash
        ]);
    }

    /**
     * Memverifikasi pembayaran (mengubah status dari 'pending' ke 'verified')
     * 
     * @param int $paymentId ID pembayaran yang akan diverifikasi
     * @param int $verifiedBy ID user/kasir yang melakukan verifikasi
     * @return bool True jika berhasil, false jika gagal
     */
    public function verifyPayment($paymentId, $verifiedBy) {
        // Query untuk update status pembayaran
        // Juga mencatat siapa dan kapan verifikasi dilakukan
        $query = "UPDATE payments 
                  SET status = 'verified', 
                      verified_by = :verified_by, 
                      verified_at = NOW() 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            'verified_by' => $verifiedBy,  // ID kasir/admin yang verifikasi
            'id' => $paymentId            // ID pembayaran yang diverifikasi
        ]);
    }

    /**
     * Mengambil data pembayaran berdasarkan ID order
     * 
     * @param int $orderId ID order yang ingin dilihat pembayarannya
     * @return array|null Data pembayaran atau null jika tidak ditemukan
     */
    public function getPaymentByOrder($orderId) {
        // Query untuk mengambil data pembayaran berdasarkan order_id
        $query = "SELECT * FROM payments WHERE order_id = :order_id";
        
        $stmt = $this->db->prepare($query);
        
        // Binding parameter dengan bindParam (alternatif cara binding)
        $stmt->bindParam(":order_id", $orderId);
        
        $stmt->execute();
        
        // Mengembalikan satu baris (karena satu order biasanya satu pembayaran)
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>