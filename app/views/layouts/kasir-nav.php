<!-- 
    NAVBAR KASIR - NAVIGASI KHUSUS UNTUK USER KASIR
    Menggunakan tema hijau (bg-success) untuk membedakan role
-->

<!-- 
    Navbar dengan warna hijau (bg-success) dan efek bayangan
    Expand di breakpoint lg, tema gelap untuk kontras teks putih
-->
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
    
    <!-- Container untuk mengatur lebar konten -->
    <div class="container">
        
        <!-- 
            BRAND/LOGO KASIR
            Brand khusus untuk panel kasir dengan ikon cash register
        -->
        <a class="navbar-brand fw-bold" href="<?= APP_URL ?>/kasir/dashboard">
            <!-- Ikon cash register (mesin kasir) sebagai simbol role kasir -->
            <i class="fas fa-cash-register me-2"></i>
            <!-- Label dengan identifikasi role -->
            Kasir Pool Snack
        </a>
        
        <!-- 
            TOGGLE BUTTON UNTUK MOBILE
            Tombol hamburger untuk menampilkan/sembunyikan menu di mobile
        -->
        <button class="navbar-toggler" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#kasirNavbar"
                aria-controls="kasirNavbar" 
                aria-expanded="false" 
                aria-label="Toggle navigation">
            <!-- Ikon tiga garis (hamburger) -->
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- 
            COLLAPSIBLE NAVBAR CONTENT
            Menu yang akan collapse/expand di mobile
            ID "kasirNavbar" sesuai dengan target data-bs-target
        -->
        <div class="collapse navbar-collapse" id="kasirNavbar">
            
            <!-- 
                NAVIGASI UTAMA (KIRI)
                Menu-menu utama untuk operasional kasir
            -->
            <ul class="navbar-nav me-auto">
                
                <!-- 
                    ITEM 1: DASHBOARD
                    Halaman utama kasir dengan statistik real-time
                -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_URL ?>/kasir/dashboard">
                        <!-- Ikon tachometer/dashboard -->
                        <i class="fas fa-tachometer-alt me-1"></i>
                        Dashboard
                    </a>
                </li>
                
                <!-- 
                    ITEM 2: SEMUA ORDER
                    Halaman untuk melihat dan mengelola semua pesanan
                -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_URL ?>/kasir/orders">
                        <!-- Ikon list untuk daftar lengkap -->
                        <i class="fas fa-list me-1"></i>
                        Semua Order
                    </a>
                </li>
                
                <!-- 
                    ITEM 3: INPUT MANUAL
                    Fitur untuk input pesanan secara manual (walk-in customer)
                -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_URL ?>/kasir/manual-order">
                        <!-- Ikon edit/pencil untuk input data -->
                        <i class="fas fa-edit me-1"></i>
                        Input Manual
                    </a>
                </li>
                
                <!-- 
                    ITEM 4: LAPORAN
                    Halaman untuk melihat laporan penjualan dan transaksi
                -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_URL ?>/kasir/reports">
                        <!-- Ikon chart/grafik untuk visualisasi data -->
                        <i class="fas fa-chart-bar me-1"></i>
                        Laporan
                    </a>
                </li>
                
            </ul>
            
            <!-- 
                NAVIGASI USER (KANAN)
                Menu untuk informasi dan aksi user kasir
            -->
            <ul class="navbar-nav">
                
                <!-- 
                    DROPDOWN USER PROFILE
                    Menu dropdown untuk info user kasir dan logout
                -->
                <li class="nav-item dropdown">
                    
                    <!-- 
                        TOGGLE DROPDOWN
                        Tombol dengan nama kasir dan ikon user shield
                    -->
                    <a class="nav-link dropdown-toggle" 
                       href="#" 
                       id="navbarDropdown" 
                       role="button" 
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <!-- Ikon user dengan shield (simbol otorisasi) -->
                        <i class="fas fa-user-shield me-1"></i>
                        <!-- Menampilkan nama kasir dari session, default 'Kasir' -->
                        <?= htmlspecialchars($_SESSION['nama'] ?? 'Kasir') ?>
                    </a>
                    
                    <!-- 
                        DROPDOWN MENU
                        Konten dropdown yang muncul
                        Position: dropdown-menu-end untuk rata kanan
                    -->
                    <ul class="dropdown-menu dropdown-menu-end" 
                        aria-labelledby="navbarDropdown">
                        
                        <!-- 
                            INFORMASI ROLE
                            Menampilkan role/posisi user (Kasir)
                        -->
                        <li>
                            <span class="dropdown-item-text text-muted small">
                                Role: Kasir
                            </span>
                        </li>
                        
                        <!-- Separator/pembatas -->
                        <li><hr class="dropdown-divider"></li>
                        
                        <!-- 
                            MENU LOGOUT
                            Link untuk logout dari sistem
                            Warna merah (danger) untuk aksi penting
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