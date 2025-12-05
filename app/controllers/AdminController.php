<?php
// app/controllers/AdminController.php
// ===========================================
// ADMIN CONTROLLER - POOL SNACK SYSTEM
// ===========================================
// Controller ini menangani semua logika bisnis untuk role ADMIN.
// Admin memiliki akses penuh untuk mengelola menu, melihat statistik,
// dan mengelola sistem secara keseluruhan.
// ===========================================

class AdminController {
    // Deklarasi property untuk model
    private $menuModel;
    private $orderModel;

    // ===========================================
    // CONSTRUCTOR - Initialize Controller
    // ===========================================
    public function __construct() {
        // ✅ Middleware: Pastikan hanya admin yang bisa mengakses
        // Auth::checkAuth() akan redirect ke login jika tidak authenticated
        // atau menampilkan error jika role tidak sesuai
        Auth::checkAuth('admin');
        
        // Inisialisasi MenuModel untuk operasi CRUD menu
        $this->menuModel = new MenuModel();
        
        // ✅ LOAD OPTIONAL MODEL: OrderModel untuk fitur statistik order
        // Cek eksistensi file sebelum load untuk menghindari error
        // Ini memungkinkan fitur statistik order ditambahkan di masa depan
        // tanpa breaking existing functionality
        if (file_exists(APP_PATH . '/models/OrderModel.php')) {
            $this->orderModel = new OrderModel();
        }
        
        // Log aktivitas admin (opsional, untuk audit trail)
        error_log("AdminController initialized for user: " . $_SESSION['user_id'] ?? 'unknown');
    }

    // ===========================================
    // METHOD: dashboard() - Admin Dashboard
    // ===========================================
    public function dashboard() {
        // ✅ FIX: Konsistensi antara Controller dan View
        // View dashboard.php mengharapkan variabel $menuStats (array)
        // bukan $totalMenu (single value)
        $menuStats = $this->menuModel->getMenuStatistics();
        
        // ✅ FALLBACK HANDLING: Beri nilai default jika database kosong/error
        // Mencegah error di view ketika data tidak tersedia
        if (!$menuStats) {
            $menuStats = [
                'total_menu' => 0,
                'menu_tersedia' => 0,
                'menu_habis' => 0,
                'total_makanan' => 0,
                'total_minuman' => 0
            ];
            error_log("Warning: getMenuStatistics() returned empty, using default values");
        }

        // ✅ LOAD VIEW: Admin dashboard
        // Variabel $menuStats akan tersedia di view
        require_once APP_PATH . '/views/admin/dashboard.php';
    }

    // ===========================================
    // METHOD: menuManager() - Menu Management Page
    // ===========================================
    public function menuManager() {
        // Ambil semua data menu untuk ditampilkan di tabel
        $menus = $this->menuModel->getAll();
        
        // ✅ VALIDASI: Pastikan data berupa array (meski kosong)
        if (!is_array($menus)) {
            $menus = [];
            error_log("Warning: MenuModel::getAll() returned non-array value");
        }
        
        // Load view dengan data menus
        require_once APP_PATH . '/views/admin/menu-manager.php';
    }

    // ===========================================
    // METHOD: createMenu() - Create New Menu (POST)
    // ===========================================
    public function createMenu() {
        // ✅ VALIDASI HTTP METHOD: Hanya terima POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ✅ SANITIZE INPUT: Bersihkan data dari POST
            // PERHATIAN: Consider menggunakan filter_input() untuk keamanan lebih
            $data = [
                'nama' => trim($_POST['nama']),
                'deskripsi' => trim($_POST['deskripsi']),
                'harga' => (int) $_POST['harga'], // Cast ke integer
                'kategori' => $_POST['kategori'],
                'status' => $_POST['status'],
                'gambar' => 'default.jpg' // Default image
            ];
            
            // ✅ VALIDASI DATA (basic validation)
            if (empty($data['nama']) || $data['harga'] <= 0) {
                header('Location: ' . APP_URL . '/admin/menu?error=invalid_data');
                exit;
            }

            // ===========================================
            // FILE UPLOAD HANDLING - Native PHP
            // ===========================================
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
                $uploadDir = BASE_PATH . '/public/assets/images/menu/';
                
                // ✅ Buat directory jika belum ada
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // 0777 untuk development saja
                }

                // ✅ Generate unique filename untuk prevent overwrite
                $fileName = time() . '_' . basename($_FILES['gambar']['name']);
                $targetPath = $uploadDir . $fileName;
                
                // ✅ VALIDASI FILE: Tambahkan validasi type dan size
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                $maxSize = 2 * 1024 * 1024; // 2MB
                
                if (in_array($_FILES['gambar']['type'], $allowedTypes) && 
                    $_FILES['gambar']['size'] <= $maxSize) {
                    
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
                        $data['gambar'] = $fileName;
                        error_log("Image uploaded successfully: " . $fileName);
                    } else {
                        error_log("Failed to move uploaded file");
                    }
                } else {
                    error_log("Invalid file type or size too large");
                }
            }

            // ✅ CREATE OPERATION: Panggil model untuk insert ke database
            if ($this->menuModel->create($data)) {
                // ✅ REDIRECT dengan success message
                header('Location: ' . APP_URL . '/admin/menu?success=created');
            } else {
                // ✅ REDIRECT dengan error message
                header('Location: ' . APP_URL . '/admin/menu?error=failed');
            }
            exit;
        } else {
            // ❌ Jika bukan POST, redirect ke menu manager
            header('Location: ' . APP_URL . '/admin/menu');
            exit;
        }
    }

    // ===========================================
    // METHOD: updateMenu() - Update Existing Menu (POST)
    // ===========================================
    public function updateMenu() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ✅ VALIDASI: Pastikan ID tersedia
            $id = $_POST['id'] ?? null;
            if (!$id) {
                header('Location: ' . APP_URL . '/admin/menu?error=no_id');
                exit;
            }

            $data = [
                'nama' => trim($_POST['nama']),
                'deskripsi' => trim($_POST['deskripsi']),
                'harga' => (int) $_POST['harga'],
                'kategori' => $_POST['kategori'],
                'status' => $_POST['status']
            ];

            // ===========================================
            // FILE UPLOAD UPDATE (optional)
            // ===========================================
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
                $uploadDir = BASE_PATH . '/public/assets/images/menu/';
                $fileName = time() . '_' . basename($_FILES['gambar']['name']);
                $targetPath = $uploadDir . $fileName;
                
                // ✅ HAPUS GAMBAR LAMA (opsional, tergantung requirement)
                // $oldImage = $this->menuModel->getImageById($id);
                // if ($oldImage && $oldImage !== 'default.jpg') {
                //     unlink($uploadDir . $oldImage);
                // }
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
                    $data['gambar'] = $fileName;
                }
            }

            // ✅ UPDATE OPERATION
            if ($this->menuModel->update($id, $data)) {
                header('Location: ' . APP_URL . '/admin/menu?success=updated');
            } else {
                header('Location: ' . APP_URL . '/admin/menu?error=failed');
            }
            exit;
        }
    }

    // ===========================================
    // METHOD: deleteMenu() - Delete Menu (GET)
    // ===========================================
    public function deleteMenu() {
        // ✅ AMBIL ID dari query parameter
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            // ✅ DELETE OPERATION
            $this->menuModel->delete($id);
            
            // ❌ PERHATIAN KEAMANAN:
            // Method DELETE seharusnya menggunakan POST request
            // untuk mencegah CSRF attack (Delete via GET link berbahaya!)
            // Implementasi saat ini rentan terhadap: <img src="/admin/menu/delete?id=123">
            // SOLUSI: Gunakan POST dengan CSRF token
        } else {
            error_log("Warning: deleteMenu called without ID parameter");
        }
        
        // ✅ REDIRECT kembali ke menu manager
        header('Location: ' . APP_URL . '/admin/menu?success=deleted');
        exit;
    }
    
    // ===========================================
    // FUTURE ENHANCEMENTS (TODO)
    // ===========================================
    // 1. public function usersManager() {}      // Manage users (kasir, admin)
    // 2. public function salesReport() {}       // Detailed sales analytics
    // 3. public function systemSettings() {}    // App configuration
    // 4. public function backupDatabase() {}    // Database backup utility
    // ===========================================
}
?>