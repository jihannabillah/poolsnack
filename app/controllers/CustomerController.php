<?php
/**
 * Controller untuk mengelola semua fungsi yang bisa diakses oleh customer
 * Mengatur tampilan dashboard, menu, riwayat order, pemilihan meja, dan nota
 */
class CustomerController {
    // Properti untuk menyimpan instance model-model yang diperlukan
    private $menuModel;    // Model untuk mengelola data menu
    private $mejaModel;    // Model untuk mengelola data meja
    private $orderModel;   // Model untuk mengelola data order/pesanan

    /**
     * Constructor - dijalankan saat class diinstansiasi
     * Memastikan autentikasi dan inisialisasi semua model yang diperlukan
     */
    public function __construct() {
        // Memeriksa apakah user sudah login sebagai customer
        Auth::checkAuth('customer');
        
        // Inisialisasi semua model yang akan digunakan
        // Hanya dilakukan sekali untuk menghindari inisialisasi berulang
        $this->menuModel = new MenuModel();
        $this->mejaModel = new MejaModel(); // Pastikan file MejaModel.php sudah ada method getAllAvailable()
        $this->orderModel = new OrderModel();
    }

    /**
     * Menampilkan dashboard utama customer
     * Dashboard hanya bisa diakses jika customer sudah memilih meja
     */
    public function dashboard() {
        // Cek apakah customer sudah memilih meja
        // Jika belum, redirect ke halaman pemilihan meja
        if (!isset($_SESSION['meja_id'])) {
            header('Location: ' . APP_URL . '/customer/select-table');
            exit();
        }
        
        // Mengambil semua menu yang tersedia (status = available)
        $menus = $this->menuModel->getAllAvailable();
        
        // Memuat file view dashboard customer
        require_once APP_PATH . '/views/customer/dashboard.php';
    }

    /**
     * Menampilkan halaman menu lengkap
     * Halaman untuk melihat semua menu yang tersedia
     */
    public function menu() {
        // Double check autentikasi (tambahan keamanan)
        Auth::checkAuth('customer');
        
        // Cek apakah customer sudah memilih meja
        // Meski sudah dicek di dashboard, perlu dicek lagi untuk direct access
        if (!isset($_SESSION['meja_id'])) {
            header('Location: ' . APP_URL . '/customer/dashboard');
            exit();
        }
        
        // Mengambil semua menu yang tersedia
        $menus = $this->menuModel->getAllAvailable();
        // Memuat file view menu
        require_once APP_PATH . '/views/customer/menu.php';
    }

    /**
     * Menampilkan riwayat pesanan customer
     * Menampilkan semua order yang pernah dibuat oleh customer
     */
    public function history() {
        // Mengambil semua order/pesanan berdasarkan ID user yang login
        $orders = $this->orderModel->getCustomerOrders($_SESSION['user_id']);
        // Memuat file view riwayat
        require_once APP_PATH . '/views/customer/history.php';
    }
    
    // ==========================================
    // BAGIAN PILIH MEJA (Sudah Digabung & Dirapikan)
    // ==========================================
    
    /**
     * 1. Menampilkan Halaman Pilih Meja (GET)
     * Diakses via: /customer/select-table
     * Menampilkan daftar meja yang tersedia untuk dipilih
     */
    public function selectTable() {
        // Ambil daftar meja yang tersedia (status = available)
        // Menggunakan Model yang sudah diinisialisasi di constructor
        $availableTables = $this->mejaModel->getAllAvailable(); 
        
        // Cek apakah user sedang menempati meja tertentu
        // Berguna untuk fitur ganti meja (jika ingin pindah meja)
        $currentTable = null;
        if (isset($_SESSION['meja_id'])) {
            // Mengambil informasi meja yang sedang ditempati
            // Pastikan MejaModel punya method getTableById()
            $currentTable = $this->mejaModel->getTableById($_SESSION['meja_id']);
        }

        // Memuat file view untuk pemilihan meja
        require_once APP_PATH . '/views/customer/set-table.php';
    }
    
    /**
     * 2. Memproses Pilihan Meja (POST)
     * Diakses via: /customer/set-table (Sesuai action form di view)
     * Menangani submit form pemilihan meja dari customer
     */
    public function processSelectTable() {
        // Hanya menangani request dengan metode POST dan memastikan meja_id dikirim
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meja_id'])) {
            // Simpan ID meja ke session untuk digunakan di seluruh aplikasi
            $_SESSION['meja_id'] = $_POST['meja_id'];
            // Simpan juga nama meja untuk ditampilkan di UI
            $_SESSION['meja_nama'] = $_POST['meja_nama']; 
            
            // Redirect ke dashboard setelah berhasil memilih meja
            header('Location: ' . APP_URL . '/customer/dashboard');
            exit;
        } else {
            // Jika akses langsung tanpa POST (misal: via URL), kembalikan ke halaman pilih meja
            // Ini mencegah akses tidak sah ke method ini
            header('Location: ' . APP_URL . '/customer/select-table');
            exit;
        }
    }

    /**
     * Menampilkan nota/detail invoice untuk sebuah order
     * Customer hanya bisa melihat nota order miliknya sendiri
     */
    public function nota() {
        // Memastikan hanya customer yang login yang bisa mengakses
        Auth::checkAuth('customer');
        
        // Mendapatkan ID order dari parameter URL (GET)
        $orderId = $_GET['id'] ?? 0; // Default 0 jika tidak ada parameter
        
        // Ambil data detail order beserta item-itemnya
        $order = $this->orderModel->getOrderWithItems($orderId);
        
        // Validasi kepemilikan order
        // 1. Cek apakah order ditemukan di database
        // 2. Cek apakah order ini milik customer yang sedang login
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            echo "Order tidak ditemukan atau akses ditolak.";
            exit;
        }
        
        // Jika validasi berhasil, tampilkan nota
        require_once APP_PATH . '/views/customer/nota.php';
    }
}
?>