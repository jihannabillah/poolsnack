<?php
/**
 * Controller untuk mengelola proses order/pemesanan dari customer
 * Menangani checkout, pembuatan order, dan manajemen order customer
 */
class OrderController {
    // Properti untuk menyimpan instance model yang diperlukan
    private $orderModel;  // Model untuk operasi order/pesanan
    private $cartModel;   // Model untuk operasi keranjang belanja
    private $mejaModel;   // Model untuk data meja (meski tidak digunakan langsung di controller ini)

    /**
     * Constructor - dijalankan saat class diinstansiasi
     * Memastikan hanya customer yang bisa mengakses dan menginisialisasi model
     */
    public function __construct() {
        // Validasi: hanya customer yang login yang boleh akses
        Auth::checkAuth('customer');
        
        // Inisialisasi semua model yang diperlukan
        $this->orderModel = new OrderModel();    // Untuk operasi order
        $this->cartModel = new CartModel();      // Untuk operasi keranjang
        $this->mejaModel = new MejaModel();      // Untuk data meja (tergantung kebutuhan)
    }

    // ====================================================================
    // HALAMAN CHECKOUT
    // ====================================================================
    
    /**
     * 1. Menampilkan Halaman Checkout (GET)
     * Menampilkan halaman konfirmasi order sebelum pembayaran
     */
    public function checkout() {
        // Mengambil semua item dari keranjang user yang sedang login
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        
        // Validasi: jika keranjang kosong, redirect kembali ke halaman keranjang
        if (empty($cartItems)) {
            header('Location: ' . APP_URL . '/customer/cart');
            exit();
        }
        
        // Menghitung total belanjaan dan pajak
        $total = $this->cartModel->getCartTotal($_SESSION['user_id']); // Total sebelum pajak
        $ppn = $total * 0.1;  // PPN 10%
        $grandTotal = $total + $ppn;  // Total akhir setelah pajak
        
        // Memuat view halaman checkout
        require_once APP_PATH . '/views/customer/checkout.php';
    }

    // ====================================================================
    // PROSES PEMBUATAN ORDER
    // ====================================================================
    
    /**
     * 2. Proses Buat Order (POST)
     * Menangani form submission dari halaman checkout
     * Membuat order baru berdasarkan isi keranjang
     */
    public function createOrder() {
        // Hanya proses jika request menggunakan metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // 1. VALIDASI: Ambil ulang data keranjang (server-side validation)
                $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
                
                // Validasi: pastikan keranjang tidak kosong
                if (empty($cartItems)) {
                    $_SESSION['error'] = "Keranjang kosong!";
                    header('Location: ' . APP_URL . '/customer/cart');
                    exit;
                }

                // 2. HITUNG ULANG TOTAL (security measure)
                // Jangan percaya nilai yang dikirim dari form, hitung ulang dari database
                $totalBelanja = $this->cartModel->getCartTotal($_SESSION['user_id']);
                $ppn = $totalBelanja * 0.1;
                $grandTotal = $totalBelanja + $ppn;

                // 3. SIAPKAN DATA ORDER
                $data = [
                    'user_id' => $_SESSION['user_id'],           // ID customer yang membuat order
                    'meja_id' => $_SESSION['meja_id'],           // ID meja yang dipilih customer
                    'total_amount' => $grandTotal,               // Total akhir termasuk pajak
                    'payment_method' => $_POST['payment_method'], // Metode pembayaran (cash/qris)
                    'payment_proof' => null,                     // Default null, akan diisi jika upload bukti
                    'status' => 'menunggu_konfirmasi'            // Status awal order
                ];

                // 4. HANDLE UPLOAD BUKTI PEMBAYARAN (jika metode QRIS)
                if ($_POST['payment_method'] === 'qris' && 
                    isset($_FILES['payment_proof']) && 
                    $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
                    
                    // Konfigurasi direktori upload
                    $uploadDir = BASE_PATH . '/public/uploads/payments/';
                    
                    // Buat folder jika belum ada
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    // Generate nama file unik untuk menghindari overwrite
                    $fileName = time() . '_' . basename($_FILES['payment_proof']['name']);
                    $targetPath = $uploadDir . $fileName;

                    // Pindahkan file dari temporary ke folder upload
                    if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $targetPath)) {
                        $data['payment_proof'] = $fileName; // Simpan nama file ke database
                    }
                }

                // 5. SIMPAN ORDER KE DATABASE
                $orderId = $this->orderModel->createOrder($data);
                
                // 6. PROSES ITEM ORDER JIKA ORDER BERHASIL DIBUAT
                if ($orderId) {
                    // Pindahkan semua item dari keranjang ke detail order
                    foreach ($cartItems as $item) {
                        $this->orderModel->addOrderDetail(
                            $orderId,               // ID order yang baru dibuat
                            $item['menu_id'],       // ID menu
                            $item['quantity'],      // Jumlah pesanan
                            $item['harga']          // Harga per item
                        );
                    }
                    
                    // 7. KOSONGKAN KERANJANG SETELAH ORDER BERHASIL
                    $this->cartModel->clearCart($_SESSION['user_id']);
                    
                    // 8. REDIRECT KE HALAMAN SUKSES
                    header('Location: ' . APP_URL . '/customer/history?status=success');
                    exit;
                } else {
                    // Jika gagal membuat order
                    throw new Exception("Gagal menyimpan data order.");
                }
                
            } catch (Exception $e) {
                // Error handling: tampilkan error atau redirect dengan pesan error
                echo "Error: " . $e->getMessage();
                // Alternatif: redirect kembali ke checkout dengan pesan error
                // $_SESSION['error'] = $e->getMessage();
                // header('Location: ' . APP_URL . '/order/checkout');
                // exit;
            }
        }
    }

    // ====================================================================
    // METHOD LAINNYA (disebutkan dalam komentar asli)
    // ====================================================================
    
    // ... sisa method lain (getOrderDetails, updateStatus) bisa dipertahankan ...
}
?>