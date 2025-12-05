<?php
// app/routes/routes.php
// ===========================================
// ROUTING CONFIGURATION - POOL SNACK SYSTEM
// ===========================================
// File ini mendefinisikan semua endpoint/URL aplikasi
// dan menghubungkannya dengan Controller & Method yang sesuai.
// Sistem menggunakan pattern: URL → Controller → Method
// ===========================================

// ✅ VALIDASI KEAMANAN: Pastikan router instance tersedia
// Mencegah error fatal jika file diinclude tanpa router yang proper
if (!isset($router)) {
    error_log("❌ Router instance not available in routes.php");
    die("Router configuration error - Please check bootstrap initialization");
}

// ===========================================
// SECTION 1: PUBLIC ROUTES (Bisa diakses tanpa login)
// ===========================================
// Routes untuk guest/anonymous users
$router->addRoute('/', 'AuthController', 'landing');      // Halaman landing/utama
$router->addRoute('/login', 'AuthController', 'login');   // Form login
$router->addRoute('/register', 'AuthController', 'register'); // Form registrasi customer
$router->addRoute('/logout', 'AuthController', 'logout'); // Logout semua role

// ===========================================
// SECTION 2: CUSTOMER ROUTES (Role: customer)
// ===========================================
// Alur customer: Pilih Meja → Lihat Menu → Order → Bayar

// --- Subsection 2.1: Flow Pilih Meja ---
// Customer memilih meja sebelum mulai order
$router->addRoute('/customer/select-table', 'CustomerController', 'selectTable');    // Tampilkan form pilih meja
$router->addRoute('/customer/set-table', 'CustomerController', 'processSelectTable'); // Proses pilihan meja (POST)

// --- Subsection 2.2: Flow Belanja ---
$router->addRoute('/customer/dashboard', 'CustomerController', 'dashboard'); // Dashboard customer
$router->addRoute('/customer/menu', 'CustomerController', 'menu');           // Katalog menu snack
$router->addRoute('/customer/history', 'CustomerController', 'history');     // Riwayat transaksi
$router->addRoute('/customer/nota', 'CustomerController', 'nota');           // Cetak nota transaksi

// --- Subsection 2.3: Flow Keranjang & Checkout ---
$router->addRoute('/customer/cart', 'CartController', 'index');      // Lihat keranjang belanja
$router->addRoute('/customer/checkout', 'OrderController', 'checkout'); // Halaman checkout

// ===========================================
// SECTION 3: CART ACTIONS (Form POST requests)
// ===========================================
// Endpoint untuk manipulasi keranjang belanja
// Biasanya dipanggil via AJAX atau form submission
$router->addRoute('/cart/add', 'CartController', 'addToCart');    // Tambah item ke keranjang
$router->addRoute('/cart/update', 'CartController', 'updateCart'); // Update quantity
$router->addRoute('/cart/remove', 'CartController', 'removeItem'); // Hapus item dari keranjang

// ===========================================
// SECTION 4: ORDER ACTIONS
// ===========================================
// Proses order dan manajemen status
$router->addRoute('/order/create', 'OrderController', 'createOrder');    // Buat order baru
$router->addRoute('/order/status', 'OrderController', 'updateStatus');   // Update status order

// ===========================================
// SECTION 5: KASIR ROUTES (Role: kasir)
// ===========================================
// Kasir bertugas: konfirmasi order, proses pembayaran, laporan harian

// --- Subsection 5.1: Halaman Utama Kasir ---
$router->addRoute('/kasir/dashboard', 'KasirController', 'dashboard');    // Dashboard kasir
$router->addRoute('/kasir/orders', 'KasirController', 'orders');          // Daftar order aktif
$router->addRoute('/kasir/manual-order', 'KasirController', 'manualOrder'); // Form order manual (jika customer offline)

// --- Subsection 5.2: Aksi / Logic Kasir ---
// Note: Perhatikan HTTP method yang digunakan
$router->addRoute('/kasir/manual-order/create', 'KasirController', 'processManualOrder'); // POST: Proses order manual
$router->addRoute('/kasir/status', 'KasirController', 'updateStatus');    // GET: Update status order
$router->addRoute('/kasir/verify', 'KasirController', 'verifyPayment');   // GET: Verifikasi pembayaran
$router->addRoute('/kasir/order-detail', 'KasirController', 'orderDetail'); // GET: Detail order

// --- Subsection 5.3: Laporan Kasir ---
$router->addRoute('/kasir/reports', 'ReportController', 'index');          // Dashboard laporan
$router->addRoute('/kasir/reports/export-daily', 'ReportController', 'exportDaily');    // Export harian
$router->addRoute('/kasir/reports/export-weekly', 'ReportController', 'exportWeekly');   // Export mingguan
$router->addRoute('/kasir/reports/export-analytics', 'ReportController', 'exportAnalytics'); // Export analytics

// ===========================================
// SECTION 6: ADMIN ROUTES (Role: admin)
// ===========================================
// Admin: full access, kelola master data, laporan lengkap

$router->addRoute('/admin/dashboard', 'AdminController', 'dashboard');    // Dashboard admin
$router->addRoute('/admin/menu', 'AdminController', 'menuManager');       // Manajemen menu (CRUD interface)

// --- Subsection 6.1: CRUD Menu (Admin only) ---
// Operasi untuk mengelola data master menu snack
$router->addRoute('/admin/menu/create', 'AdminController', 'createMenu'); // POST: Tambah menu baru
$router->addRoute('/admin/menu/update', 'AdminController', 'updateMenu'); // POST: Update menu
$router->addRoute('/admin/menu/delete', 'AdminController', 'deleteMenu'); // GET: Hapus menu

// --- Subsection 6.2: Laporan Admin ---
// ✅ DUPLIKASI ROUTE: Admin perlu akses laporan juga
// Ini memungkinkan link navbar admin bekerja dengan controller yang sama
// tetapi dengan permission check di controller
$router->addRoute('/admin/reports', 'ReportController', 'index');          // Laporan untuk admin
$router->addRoute('/admin/reports/export-daily', 'ReportController', 'exportDaily');
$router->addRoute('/admin/reports/export-weekly', 'ReportController', 'exportWeekly');
$router->addRoute('/admin/reports/export-analytics', 'ReportController', 'exportAnalytics');

// ===========================================
// DEBUG & VERIFIKASI
// ===========================================
// Log untuk memastikan routes berhasil dimuat
error_log("✅ Routes loaded successfully. Total routes: " . count($router->getRoutes()));

// ===========================================
// CATATAN PENTING UNTUK DEVELOPER:
// ===========================================
// 1. URUTAN MATTERS: Router pertama yang match akan dieksekusi
// 2. HTTP METHODS: Perhatikan method (GET/POST) di controller
// 3. PERMISSION: Middleware/authorization harus diimplementasikan di controller
// 4. NAMING CONVENTION:
//    - URL: lowercase dengan hyphen (customer/select-table)
//    - Controller: PascalCase (CustomerController)
//    - Method: camelCase (selectTable)
// 5. RESTFUL CONSIDERATION untuk versi berikutnya:
//    - GET    /menu           → MenuController::index()
//    - GET    /menu/{id}      → MenuController::show()
//    - POST   /menu           → MenuController::store()
//    - PUT    /menu/{id}      → MenuController::update()
//    - DELETE /menu/{id}      → MenuController::destroy()
// ===========================================
?>