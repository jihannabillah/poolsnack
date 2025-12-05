<!-- 
    NAVBAR ADMIN - NAVIGASI ADMINISTRATOR
    Komponen navigasi khusus untuk panel administrator sistem Pool Snack
-->

<!-- 
    Navbar dengan warna gelap (bg-dark) dan bayangan
    Menggunakan navbar expandable untuk tampilan responsif
-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    
    <!-- Container untuk mengatur lebar konten navbar -->
    <div class="container">
        
        <!-- 
            BRAND/LOGO NAVBAR
            Logo atau nama aplikasi yang mengarah ke dashboard admin
        -->
        <a class="navbar-brand fw-bold" href="<?= APP_URL ?>/admin/dashboard">
            <!-- Ikon gear/settings sebagai simbol admin -->
            <i class="fas fa-cogs me-2"></i>
            <!-- Nama aplikasi dengan identifikasi admin -->
            Admin Pool Snack
        </a>
        
        <!-- 
            TOGGLE BUTTON UNTUK MOBILE
            Tombol hamburger yang muncul di layar kecil
            Mengontrol visibilitas menu navigasi di mobile
        -->
        <button class="navbar-toggler" type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#adminNavbar">
            <!-- Ikon tiga garis (hamburger) -->
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- 
            COLLAPSIBLE NAVBAR CONTENT
            Konten navbar yang akan ditampilkan/sembunyikan di mobile
            ID "adminNavbar" sesuai dengan target tombol toggler
        -->
        <div class="collapse navbar-collapse" id="adminNavbar">
            
            <!-- 
                NAVIGASI UTAMA (KIRI)
                Menu-menu navigasi untuk berbagai fitur admin
            -->
            <ul class="navbar-nav me-auto">
                
                <!-- 
                    ITEM 1: DASHBOARD
                    Halaman utama admin dengan statistik dan overview
                -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_URL ?>/admin/dashboard">
                        <!-- Ikon dashboard/speedometer -->
                        <i class="fas fa-tachometer-alt me-1"></i>
                        Dashboard
                    </a>
                </li>
                
                <!-- 
                    ITEM 2: KELOLA MENU
                    Halaman untuk mengelola menu makanan/minuman
                -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_URL ?>/admin/menu">
                        <!-- Ikon utensils (alat makan) -->
                        <i class="fas fa-utensils me-1"></i>
                        Kelola Menu
                    </a>
                </li>
                
                <!-- 
                    ITEM 3: KELOLA USER (DISABLED)
                    Fitur yang akan datang, saat ini dinonaktifkan
                -->
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" 
                       title="Fitur Segera Hadir">
                        <!-- Ikon users -->
                        <i class="fas fa-users me-1"></i>
                        Kelola User
                    </a>
                </li>
                
                <!-- 
                    ITEM 4: LAPORAN
                    Halaman untuk melihat berbagai laporan dan statistik
                -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_URL ?>/admin/reports">
                        <!-- Ikon chart/grafik -->
                        <i class="fas fa-chart-bar me-1"></i>
                        Laporan
                    </a>
                </li>
                
            </ul>
            
            <!-- 
                NAVIGASI USER (KANAN)
                Menu untuk informasi dan aksi user/admin yang login
            -->
            <ul class="navbar-nav">
                
                <!-- 
                    DROPDOWN USER PROFILE
                    Menu dropdown untuk informasi dan aksi user
                -->
                <li class="nav-item dropdown">
                    
                    <!-- 
                        TOGGLE DROPDOWN
                        Tombol yang menampilkan dropdown saat diklik
                    -->
                    <a class="nav-link dropdown-toggle" href="#" 
                       id="navbarDropdown" 
                       role="button" 
                       data-bs-toggle="dropdown">
                        <!-- Ikon user dengan shield (simbol admin) -->
                        <i class="fas fa-user-shield me-1"></i>
                        <!-- Menampilkan nama admin dari session -->
                        <?= htmlspecialchars($_SESSION['nama'] ?? 'Administrator') ?>
                    </a>
                    
                    <!-- 
                        DROPDOWN MENU
                        Konten dropdown yang muncul saat toggle diklik
                        Position: dropdown-menu-end untuk rata kanan
                    -->
                    <ul class="dropdown-menu dropdown-menu-end">
                        
                        <!-- 
                            INFORMASI ROLE
                            Menampilkan role/posisi user (Admin)
                        -->
                        <li>
                            <span class="dropdown-item-text text-muted small">
                                Role: Admin
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