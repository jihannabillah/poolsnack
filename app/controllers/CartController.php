<?php
/**
 * Controller untuk mengelola keranjang belanja customer
 * Menangani operasi CRUD pada keranjang seperti menambah, mengupdate, dan menghapus item
 */
class CartController {
    // Properti untuk menyimpan instance model keranjang
    private $cartModel;

    /**
     * Constructor - dijalankan saat class diinstansiasi
     * Memastikan hanya customer yang terautentikasi yang bisa mengakses
     */
    public function __construct() {
        // Memeriksa apakah user sudah login sebagai customer
        Auth::checkAuth('customer');
        // Membuat instance CartModel untuk interaksi dengan database
        $this->cartModel = new CartModel();
    }

    // ====================================================================
    // MENAMPILKAN HALAMAN KERANJANG
    // ====================================================================
    
    /**
     * Menampilkan halaman keranjang belanja
     * Mengambil semua item keranjang dan total harga dari database
     */
    public function index() {
        // Mengambil semua item keranjang berdasarkan ID user yang sedang login
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        // Menghitung total harga semua item di keranjang
        $total = $this->cartModel->getCartTotal($_SESSION['user_id']);
        
        // Memuat file view untuk menampilkan halaman keranjang
        require_once APP_PATH . '/views/customer/cart.php';
    }

    // ====================================================================
    // MENAMBAH ITEM KE KERANJANG
    // ====================================================================
    
    /**
     * Menambahkan item baru ke dalam keranjang belanja
     * Bisa diakses dari halaman dashboard atau menu
     */
    public function addToCart() {
        // Hanya menangani request dengan metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mendapatkan ID menu dari form
            $menuId = $_POST['menu_id'];
            // Mendapatkan jumlah item (default 1 jika tidak ditentukan)
            $quantity = $_POST['quantity'] ?? 1;
            
            // Menyimpan item ke database dan mengatur pesan notifikasi
            if ($this->cartModel->addItem($_SESSION['user_id'], $menuId, $quantity)) {
                // Pesan sukses jika item berhasil ditambahkan
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Berhasil menambahkan item ke keranjang!'
                ];
            } else {
                // Pesan error jika gagal menambahkan item
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Gagal menambahkan item.'
                ];
            }
            
            // Mengecek apakah ada permintaan redirect khusus
            if (isset($_POST['redirect'])) {
                // Redirect ke halaman yang ditentukan (misal: tetap di halaman menu)
                header('Location: ' . APP_URL . $_POST['redirect']);
            } else {
                // Default redirect ke dashboard customer
                header('Location: ' . APP_URL . '/customer/dashboard');
            }
            exit; // Menghentikan eksekusi script setelah redirect
        }
    }

    // ====================================================================
    // MENGUPDATE JUMLAH ITEM DI KERANJANG
    // ====================================================================
    
    /**
     * Mengupdate jumlah item di keranjang (tambah atau kurangi)
     * Dipanggil dari tombol +/- di halaman keranjang
     */
    public function updateCart() {
        // Hanya menangani request dengan metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mendapatkan ID item keranjang dari form
            $cartItemId = $_POST['cart_item_id'];
            // Mendapatkan aksi yang dilakukan: 'increase' (tambah) atau 'decrease' (kurangi)
            $action = $_POST['action'];

            // Mengambil data item saat ini dari database
            $currentItem = $this->cartModel->getItemById($cartItemId);
            
            // Jika item ditemukan di database
            if ($currentItem) {
                // Mengambil jumlah item saat ini
                $newQty = $currentItem['quantity'];

                // Menentukan jumlah baru berdasarkan aksi
                if ($action === 'increase') {
                    $newQty++; // Menambah jumlah
                } elseif ($action === 'decrease') {
                    $newQty--; // Mengurangi jumlah
                }

                // Jika jumlah baru lebih dari 0, update item
                if ($newQty > 0) {
                    $this->cartModel->updateItem($cartItemId, $newQty);
                } else {
                    // Jika jumlah menjadi 0 atau kurang, hapus item dari keranjang
                    $this->cartModel->removeItem($cartItemId);
                }
            }

            // Redirect kembali ke halaman keranjang
            header('Location: ' . APP_URL . '/customer/cart');
            exit; // Menghentikan eksekusi script setelah redirect
        }
    }

    // ====================================================================
    // MENGHAPUS ITEM DARI KERANJANG
    // ====================================================================
    
    /**
     * Menghapus item dari keranjang belanja
     * Dipanggil dari tombol tong sampah di halaman keranjang
     */
    public function removeItem() {
        // Hanya menangani request dengan metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mendapatkan ID item keranjang dari form
            $cartItemId = $_POST['cart_item_id'];
            
            // Menghapus item dari database
            $this->cartModel->removeItem($cartItemId);
            
            // Menambahkan notifikasi bahwa item telah dihapus
            $_SESSION['flash'] = [
                'type' => 'warning',
                'message' => 'Item dihapus dari keranjang.'
            ];
            
            // Redirect kembali ke halaman keranjang
            header('Location: ' . APP_URL . '/customer/cart');
            exit; // Menghentikan eksekusi script setelah redirect
        }
    }
}
?>