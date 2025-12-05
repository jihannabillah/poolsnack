<?php
/**
 * Controller untuk mengelola semua fungsi kasir dan admin
 * Menangani manajemen order, pembayaran, laporan, dan order manual
 */
class KasirController {
    // Properti untuk menyimpan instance model yang diperlukan
    private $orderModel;   // Model untuk mengelola data order/pesanan
    private $reportModel;  // Model untuk mengelola laporan dan data penjualan
    private $menuModel;    // Model untuk mengelola data menu
    private $mejaModel;    // Model untuk mengelola data meja

    /**
     * Constructor - dijalankan saat class diinstansiasi
     * Melakukan validasi role dan inisialisasi semua model
     */
    public function __construct() {
        // 1. Validasi apakah user sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        // 2. Validasi role - hanya kasir dan admin yang boleh mengakses
        if ($_SESSION['role'] !== 'kasir' && $_SESSION['role'] !== 'admin') {
            header('Location: ' . APP_URL . '/'); // Redirect ke halaman utama
            exit;
        }

        // 3. Inisialisasi Model Utama
        $this->orderModel = new OrderModel();  // Untuk operasi order
        $this->reportModel = new ReportModel(); // Untuk laporan penjualan
        
        // 4. Inisialisasi Model Pendukung dengan validasi file
        // Validasi file MenuModel.php sebelum diinstansiasi
        if (file_exists(APP_PATH . '/models/MenuModel.php')) {
            $this->menuModel = new MenuModel(); // Untuk data menu
        } else {
            die("Error: MenuModel not found."); // Hentikan eksekusi jika file tidak ditemukan
        }

        // Validasi file MejaModel.php sebelum diinstansiasi
        if (file_exists(APP_PATH . '/models/MejaModel.php')) {
            $this->mejaModel = new MejaModel(); // Untuk data meja
        } else {
            die("Error: MejaModel not found."); // Hentikan eksekusi jika file tidak ditemukan
        }
    }

    /**
     * Menampilkan dashboard kasir/admin
     * Menampilkan ringkasan order dan penjualan hari ini
     */
    public function dashboard() {
        // Mengambil data untuk dashboard:
        $pendingOrders = $this->orderModel->getOrdersByStatus('menunggu_konfirmasi'); // Order yang perlu dikonfirmasi
        $activeOrders = $this->orderModel->getOrdersByStatus('diproses'); // Order yang sedang diproses
        $todaySales = $this->reportModel->getTodaySales(); // Total penjualan hari ini
        
        // Memuat view dashboard kasir
        require_once APP_PATH . '/views/kasir/dashboard.php';
    }

    /**
     * Menampilkan daftar semua order dengan filter
     * Dapat difilter berdasarkan status, tanggal, atau metode pembayaran
     */
    public function orders() {
        // Membuat array filter dari parameter GET
        $filters = [
            'status'  => $_GET['status'] ?? null,   // Filter berdasarkan status order
            'date'    => $_GET['date'] ?? null,     // Filter berdasarkan tanggal
            'payment' => $_GET['payment'] ?? null   // Filter berdasarkan metode pembayaran
        ];
        
        // Mengecek apakah method filter tersedia di model
        if (method_exists($this->orderModel, 'getFilteredOrders')) {
            // Jika ada method filter, gunakan filter
            $orders = $this->orderModel->getFilteredOrders($filters);
        } else {
            // Fallback - ambil semua order tanpa filter
            $orders = $this->orderModel->getAllOrders();
        }
        
        // Memuat view daftar order
        require_once APP_PATH . '/views/kasir/orders.php';
    }

    /**
     * Menampilkan form untuk membuat order manual
     * Digunakan kasir untuk membuat order untuk customer yang datang langsung
     */
    public function manualOrder() {
        // Mengambil data untuk form:
        $menus = $this->menuModel->getAllAvailable(); // Semua menu yang tersedia
        $tables = $this->mejaModel->getAvailableTables(); // Semua meja yang tersedia
        
        // Memuat view form order manual
        require_once APP_PATH . '/views/kasir/manual-order.php';
    }

    /**
     * Memproses pembuatan order manual dari kasir
     * Menangani form submission dari halaman manual-order
     */
    public function processManualOrder() {
        // Hanya proses jika request menggunakan metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // 1. Menyiapkan data order utama
                $data = [
                    'user_id' => $_SESSION['user_id'],        // ID kasir yang membuat order
                    'meja_id' => $_POST['meja_id'],           // ID meja yang dipilih
                    'total_amount' => $_POST['total_amount'], // Total harga
                    'payment_method' => $_POST['payment_method'], // Metode pembayaran
                    'payment_proof' => null,                  // Manual order tidak perlu bukti bayar
                    'status' => 'selesai'                     // Status langsung selesai (karena bayar langsung)
                ];

                // 2. Membuat order baru di database
                $orderId = $this->orderModel->createOrder($data);

                // 3. Jika order berhasil dibuat, tambahkan item-itemnya
                if ($orderId) {
                    // Cek apakah ada item yang dikirim
                    if (isset($_POST['items']) && is_array($_POST['items'])) {
                        // Loop setiap item dan tambahkan ke order detail
                        foreach ($_POST['items'] as $item) {
                            $this->orderModel->addOrderDetail(
                                $orderId,              // ID order yang baru dibuat
                                $item['menu_id'],      // ID menu
                                $item['quantity'],     // Jumlah
                                $item['price']         // Harga per item
                            );
                        }
                    }
                    // Redirect ke halaman daftar order setelah berhasil
                    header('Location: ' . APP_URL . '/kasir/orders');
                    exit;
                }
            } catch (Exception $e) {
                // Menangani error jika terjadi masalah
                echo "Gagal membuat order: " . $e->getMessage();
                exit;
            }
        }
    }

    /**
     * Mengupdate status order (untuk kasir mengubah status order)
     * Contoh: dari "diproses" ke "selesai"
     */
    public function updateStatus() {
        // Mendapatkan parameter dari URL
        $orderId = $_GET['id'] ?? null;      // ID order yang akan diupdate
        $status = $_GET['status'] ?? null;   // Status baru yang akan di-set

        // Pastikan kedua parameter ada
        if ($orderId && $status) {
            // Update status order di database
            $this->orderModel->updateStatus($orderId, $status);
        }
        
        // Redirect kembali ke halaman daftar order
        header('Location: ' . APP_URL . '/kasir/orders');
        exit;
    }

    /**
     * Memverifikasi pembayaran customer (terima atau tolak)
     * Untuk order dengan metode pembayaran transfer/online
     */
    public function verifyPayment() {
        // Mendapatkan parameter dari URL
        $orderId = $_GET['id'] ?? null;      // ID order yang akan diverifikasi
        $action = $_GET['action'] ?? null;   // Aksi: 'accept' (terima) atau 'reject' (tolak)

        // Pastikan kedua parameter ada
        if ($orderId && $action) {
            if ($action === 'accept') {
                // Jika diterima, ubah status menjadi 'diproses'
                $this->orderModel->updateStatus($orderId, 'diproses');
            } elseif ($action === 'reject') {
                // Jika ditolak, ubah status menjadi 'dibatalkan'
                $this->orderModel->updateStatus($orderId, 'dibatalkan');
            }
        }
        
        // Redirect kembali ke halaman daftar order
        header('Location: ' . APP_URL . '/kasir/orders');
        exit;
    }
    
    /**
     * Menampilkan detail order dan nota
     * Kasir dapat melihat detail lengkap sebuah order
     */
    public function orderDetail() {
        // Mendapatkan ID order dari parameter URL
        $orderId = $_GET['id'] ?? null;
        
        // Mengambil data order beserta item-itemnya
        $order = $this->orderModel->getOrderWithItems($orderId);
        
        // Validasi: jika order tidak ditemukan
        if (!$order) {
            echo "Order tidak ditemukan.";
            exit;
        }

        // Memuat view nota (sama dengan view yang digunakan customer)
        require_once APP_PATH . '/views/customer/nota.php'; 
    }
}
?>