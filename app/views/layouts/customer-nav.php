<?php
// ================================================
// NAVBAR CUSTOMER - NAVIGASI UNTUK PELANGGAN
// ================================================

// -------------------------------------------------
// 1. LOGIKA HITUNG KERANJANG (PHP)
// -------------------------------------------------
// Inisialisasi variabel untuk jumlah item di keranjang
$navCartCount = 0;

// Cek apakah user sudah login (memiliki session user_id)
if (isset($_SESSION['user_id'])) {
    
    // Cek apakah class CartModel sudah ada/terdefinisi
    // Pencegahan error jika class sudah diload sebelumnya
    if (!class_exists('CartModel')) {
        // Cek apakah konstanta APP_PATH terdefinisi dan file model ada
        if (defined('APP_PATH') && file_exists(APP_PATH . '/models/CartModel.php')) {
            require_once APP_PATH . '/models/CartModel.php'; // Load file model
        }
    }
    
    // Jika class CartModel tersedia, hitung jumlah item di keranjang
    if (class_exists('CartModel')) {
        $navCartModel = new CartModel(); // Instance object model
        $navCartCount = $navCartModel->getCartItemCount($_SESSION['user_id']); // Panggil method
    }
}

// -------------------------------------------------
// 2. LOGIKA ACTIVE STATE (Highlight Menu)
// -------------------------------------------------
// Ambil URI saat ini untuk menentukan menu mana yang aktif
$currentUri = $_SERVER['REQUEST_URI'];

// Cek apakah function isActive sudah didefinisikan (prevent re-declaration)
if (!function_exists('isActive')) {
    /**
     * Fungsi untuk menentukan apakah menu sedang aktif berdasarkan URI
     * 
     * @param string $uri URI lengkap dari halaman saat ini
     * @param string $keyword Kata kunci untuk menu tertentu
     * @return string 'active fw-bold' jika menu aktif, string kosong jika tidak
     */
    function isActive($uri, $keyword) {
        // Cek apakah keyword terdapat dalam URI
        return strpos($uri, $keyword) !== false ? 'active fw-bold' : '';
    }
}
?>

<!-- ================================================
     HTML NAVBAR CUSTOMER
================================================ -->

<!-- 
    Navbar dengan warna biru (bg-primary) dan efek bayangan
    Menggunakan Bootstrap navbar dengan ekspansi di breakpoint lg
-->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    
    <!-- Container untuk mengatur lebar konten -->
    <div class="container">
        
        <!-- 
            BRAND/LOGO
            Mengarah ke dashboard customer dengan ikon utensils
        -->
        <a class="navbar-brand fw-bold" href="<?= APP_URL ?>/customer/dashboard">
            <!-- Ikon utensils (alat makan) sebagai simbol restoran -->
            <i class="fas fa-utensils me-2"></i>
            <!-- Nama aplikasi/brand -->
            Pool Snack
        </a>
        
        <!-- 
            TOGGLE BUTTON UNTUK MOBILE
            Tombol hamburger untuk menampilkan/sembunyikan menu di mobile
        -->
        <button class="navbar-toggler" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navbarNav"
                aria-controls="navbarNav" 
                aria-expanded="false" 
                aria-label="Toggle navigation">
            <!-- Ikon tiga garis (hamburger) -->
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- 
            COLLAPSIBLE NAVBAR CONTENT
            Menu yang akan collapse/expand di mobile
        -->
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <!-- 
                NAVIGASI UTAMA (KIRI)
                Menu-menu utama untuk customer
            -->
            <ul class="navbar-nav me-auto">
                
                <!-- 
                    ITEM 1: DASHBOARD
                    Halaman utama customer
                -->
                <li class="nav-item">
                    <a class="nav-link <?= isActive($currentUri, '/dashboard') ?>" 
                       href="<?= APP_URL ?>/customer/dashboard">
                        <!-- Ikon home untuk dashboard -->
                        <i class="fas fa-home me-1"></i>
                        Dashboard
                    </a>
                </li>
                
                <!-- 
                    ITEM 2: MENU
                    Halaman untuk melihat daftar menu
                -->
                <li class="nav-item">
                    <a class="nav-link <?= isActive($currentUri, '/menu') ?>" 
                       href="<?= APP_URL ?>/customer/menu">
                        <!-- Ikon list untuk daftar menu -->
                        <i class="fas fa-list me-1"></i>
                        Menu
                    </a>
                </li>
                
                <!-- 
                    ITEM 3: KERANJANG
                    Halaman keranjang belanja dengan badge jumlah item
                -->
                <li class="nav-item">
                    <a class="nav-link <?= isActive($currentUri, '/cart') ?>" 
                       href="<?= APP_URL ?>/customer/cart">
                        <!-- Ikon shopping cart -->
                        <i class="fas fa-shopping-cart me-1"></i>
                        Keranjang
                        <!-- 
                            Badge merah untuk menampilkan jumlah item di keranjang
                            Menggunakan nilai dari $navCartCount, default 0
                        -->
                        <span class="badge bg-danger cart-count">
                            <?= $navCartCount ?? 0 ?>
                        </span>
                    </a>
                </li>
                
                <!-- 
                    ITEM 4: RIWAYAT
                    Halaman untuk melihat riwayat pesanan
                -->
                <li class="nav-item">
                    <a class="nav-link <?= isActive($currentUri, '/history') ?>" 
                       href="<?= APP_URL ?>/customer/history">
                        <!-- Ikon history untuk riwayat -->
                        <i class="fas fa-history me-1"></i>
                        Riwayat
                    </a>
                </li>
                
            </ul>
            
            <!-- 
                NAVIGASI USER (KANAN)
                Menu untuk informasi dan aksi user/customer yang login
            -->
            <ul class="navbar-nav">
                
                <!-- 
                    DROPDOWN USER PROFILE
                    Menu dropdown untuk info user dan logout
                -->
                <li class="nav-item dropdown">
                    
                    <!-- 
                        TOGGLE DROPDOWN
                        Tombol dengan nama user dan ikon profile
                    -->
                    <a class="nav-link dropdown-toggle" 
                       href="#" 
                       id="navbarDropdown" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false">
                        <!-- Ikon user profile -->
                        <i class="fas fa-user me-1"></i>
                        <!-- Menampilkan nama user dari session, default 'Guest' -->
                        <?= htmlspecialchars($_SESSION['nama'] ?? 'Guest') ?>
                    </a>
                    
                    <!-- 
                        DROPDOWN MENU
                        Konten dropdown yang muncul
                        Position: dropdown-menu-end untuk rata kanan
                    -->
                    <ul class="dropdown-menu dropdown-menu-end" 
                        aria-labelledby="navbarDropdown">
                        
                        <!-- 
                            INFORMASI MEJA
                            Menampilkan nomor meja yang sedang digunakan
                        -->
                        <li>
                            <span class="dropdown-item-text text-muted small">
                                <!-- Menampilkan nomor meja dari session, default '-' -->
                                Meja: #<?= htmlspecialchars($_SESSION['meja_nama'] ?? '-') ?>
                            </span>
                        </li>
                        
                        <!-- Separator/pembatas -->
                        <li><hr class="dropdown-divider"></li>
                        
                        <!-- 
                            MENU LOGOUT
                            Link untuk logout dari sistem
                        -->
                        <li>
                            <a class="dropdown-item text-danger" 
                               href="<?= APP_URL ?>/logout">
                                <!-- Ikon sign out -->
                                <i class="fas fa-sign-out-alt me-1"></i>
                                Logout
                            </a>
                        </li>
                        
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>