<?php
/**
 * Model untuk mengelola laporan dan analitik penjualan
 * Menyediakan data untuk report dashboard, statistik, dan analisis penjualan
 */
class ReportModel {
    // Properti untuk koneksi database
    private $db;

    /**
     * Constructor - Menginisialisasi koneksi database
     */
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    /**
     * Mendapatkan total penjualan hari ini
     * Menghitung jumlah uang dari order yang selesai/diproses pada tanggal hari ini
     * 
     * @return float Total penjualan hari ini (default: 0 jika tidak ada penjualan)
     */
    public function getTodaySales() {
        // 1. Ambil tanggal hari ini dari PHP (Sesuai zona waktu Indonesia/Server App)
        // date('Y-m-d') akan menghasilkan tanggal sesuai dengan timezone server
        $today = date('Y-m-d');

        // 2. Query untuk menjumlahkan total_harga dari order hari ini
        // Hanya order dengan status 'diproses' atau 'selesai' yang dihitung
        // Menggunakan parameter :today, JANGAN pakai CURDATE() untuk konsistensi timezone
        $query = "SELECT SUM(total_harga) as total 
                  FROM orders 
                  WHERE DATE(created_at) = :today 
                  AND status IN ('diproses', 'selesai')";
        
        $stmt = $this->db->prepare($query);
        
        // 3. Binding parameter untuk keamanan dan konsistensi timezone
        $stmt->bindValue(':today', $today);
        $stmt->execute();
        
        // Ambil hasil query
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Kembalikan total penjualan atau 0 jika tidak ada data
        return $result['total'] ?? 0;
    }

    /**
     * Mendapatkan laporan harian detail
     * Menampilkan semua order pada tanggal tertentu beserta informasi terkait
     * 
     * @param string|null $date Tanggal dalam format YYYY-MM-DD (default: hari ini)
     * @return array Daftar order pada tanggal tersebut dengan informasi lengkap
     */
    public function getDailyReport($date = null) {
        // Jika tanggal tidak diberikan, gunakan tanggal hari ini
        $date = $date ?: date('Y-m-d');
        
        // Query untuk mengambil semua order pada tanggal tertentu
        // JOIN dengan tabel meja (untuk nomor meja) dan users (untuk nama customer)
        $query = "SELECT o.*, m.nomor_meja, u.nama as customer_name 
                  FROM orders o 
                  JOIN meja m ON o.meja_id = m.id 
                  JOIN users u ON o.user_id = u.id 
                  WHERE DATE(o.created_at) = :date 
                  AND o.status IN ('diproses', 'selesai')  // Hanya order yang aktif/selesai
                  ORDER BY o.created_at DESC";  // Order terbaru di atas
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":date", $date);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mendapatkan laporan mingguan (7 hari terakhir)
     * Menghitung jumlah order dan pendapatan per hari dalam rentang waktu
     * 
     * @param string|null $startDate Tanggal mulai (default: 7 hari yang lalu)
     * @return array Statistik harian dalam rentang waktu
     *   Format: [['tanggal' => '2024-01-15', 'total_orders' => 5, 'total_pendapatan' => 250000], ...]
     */
    public function getWeeklyReport($startDate = null) {
        // Jika startDate tidak diberikan, default ke 7 hari yang lalu
        $startDate = $startDate ?: date('Y-m-d', strtotime('-7 days'));
        
        // End date selalu hari ini
        $endDate = date('Y-m-d');
        
        // Query untuk menghitung statistik per hari
        // GROUP BY tanggal untuk mendapatkan data per hari
        $query = "SELECT 
                    DATE(created_at) as tanggal,
                    COUNT(*) as total_orders,
                    SUM(total_harga) as total_pendapatan
                  FROM orders 
                  WHERE DATE(created_at) BETWEEN :start_date AND :end_date
                  AND status IN ('diproses', 'selesai')
                  GROUP BY DATE(created_at) 
                  ORDER BY tanggal DESC";  // Urutkan dari tanggal terbaru
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":start_date", $startDate);
        $stmt->bindParam(":end_date", $endDate);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mendapatkan data menu terlaris dalam periode tertentu
     * Analisis penjualan per menu (berapa banyak terjual, berapa pendapatan)
     * 
     * @param string|null $startDate Tanggal mulai (default: awal bulan ini)
     * @param string|null $endDate Tanggal akhir (default: hari ini)
     * @return array 10 menu terlaris dengan statistik penjualan
     *   Format: [['nama' => 'Nasi Goreng', 'kategori' => 'makanan', 'total_terjual' => 50, 'total_pendapatan' => 1250000], ...]
     */
    public function getBestSellingMenu($startDate = null, $endDate = null) {
        // Jika tanggal tidak diberikan, default ke bulan berjalan
        $startDate = $startDate ?: date('Y-m-01');  // Tanggal 1 bulan ini
        $endDate = $endDate ?: date('Y-m-d');       // Hari ini
        
        // Query kompleks untuk analisis penjualan menu
        // JOIN 3 tabel: order_items → orders → menu
        $query = "SELECT 
                    m.nama,
                    m.kategori,
                    SUM(oi.quantity) as total_terjual,
                    SUM(oi.subtotal) as total_pendapatan
                  FROM order_items oi
                  JOIN orders o ON oi.order_id = o.id
                  JOIN menu m ON oi.menu_id = m.id
                  WHERE DATE(o.created_at) BETWEEN :start_date AND :end_date
                  AND o.status IN ('diproses', 'selesai')  // Hanya order yang valid
                  GROUP BY m.id  // Kelompokkan berdasarkan menu
                  ORDER BY total_terjual DESC  // Urutkan dari yang paling banyak terjual
                  LIMIT 10";  // Ambil 10 teratas saja
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":start_date", $startDate);
        $stmt->bindParam(":end_date", $endDate);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>