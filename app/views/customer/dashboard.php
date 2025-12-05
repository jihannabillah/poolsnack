<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta tags untuk karakter dan viewport responsif -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Judul halaman dashboard customer -->
    <title>Dashboard Customer - Pool Snack</title>
    
    <!-- Menyertakan Bootstrap 5 untuk styling dasar -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Menyertakan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Menyertakan font Poppins dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Menyertakan stylesheet kustom utama -->
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <!-- Stylesheet inline untuk styling spesifik dashboard -->
    <style>
        /* --- GLOBAL THEME --- */
        
        /* Styling dasar body dengan background gambar dan overlay ungu */
        body {
            font-family: 'Poppins', sans-serif;
            /* Background gambar kolam renang */
            background: url('https://images.unsplash.com/photo-1574514807758-a517173e35a1?q=80&w=1920&auto=format&fit=crop') no-repeat center center/cover;
            background-attachment: fixed;
            min-height: 100vh;
            color: #fff;
            padding-bottom: 50px;
        }

        /* Overlay ungu transparan untuk memberikan efek elegan pada background */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.95) 100%);
            z-index: -1;
        }

        /* --- GLASSMORPHISM COMPONENTS --- */
        
        /* Kelas untuk card dengan efek glassmorphism (frosted glass) */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            color: white;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        /* Card khusus untuk section welcome/sambutan */
        .welcome-card {
            padding: 2rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        /* Styling untuk teks welcome */
        .welcome-text h4 { font-weight: 700; margin-bottom: 5px; }
        .welcome-text p { font-weight: 300; opacity: 0.9; margin: 0; font-size: 0.95rem; }
        
        /* Tombol dengan efek glass untuk navigasi */
        .btn-glass {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 0.9rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        /* Efek hover pada tombol glass */
        .btn-glass:hover {
            background: white;
            color: #764ba2;
            transform: translateY(-2px);
        }

        /* --- MENU CATEGORY PILLS --- */
        
        /* Styling untuk tab kategori menu */
        .nav-pills .nav-link {
            color: rgba(255,255,255,0.8);
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 50px;
            padding: 10px 25px;
            margin-right: 10px;
            margin-bottom: 10px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        /* Efek hover pada tab kategori */
        .nav-pills .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        /* Styling untuk tab kategori yang aktif */
        .nav-pills .nav-link.active {
            background: #FFD700; /* Warna emas premium */
            color: #333;
            font-weight: 700;
            border-color: #FFD700;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
        }

        /* --- MENU ITEM CARDS --- */
        
        /* Card untuk setiap item menu */
        .menu-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        
        /* Efek hover pada card menu */
        .menu-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        /* Styling untuk gambar menu */
        .card-img-top {
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        /* Body dari card menu */
        .card-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        /* Judul menu */
        .card-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        /* Deskripsi menu */
        .card-text {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.4;
            margin-bottom: 1rem;
            flex-grow: 1;
        }

        /* Tag harga dengan warna emas */
        .price-tag {
            font-size: 1.1rem;
            font-weight: 700;
            color: #FFD700;
        }

        /* Tombol untuk menambahkan item ke keranjang */
        .btn-add {
            background: linear-gradient(45deg, #00b09b, #96c93d); /* Gradien hijau segar */
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            padding: 8px 20px;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(0, 176, 155, 0.3);
            transition: all 0.3s;
        }
        
        /* Efek hover pada tombol tambah */
        .btn-add:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 176, 155, 0.5);
            color: white;
        }

        /* --- NOTIFIKASI MELAYANG --- */
        
        /* Alert yang muncul di bagian atas layar */
        .floating-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 320px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            background: rgba(25, 135, 84, 0.9); /* Hijau transparan */
            color: white;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            animation: slideDown 0.5s ease-out;
        }
        
        /* Animasi untuk notifikasi yang muncul dari atas */
        @keyframes slideDown {
            from { top: -100px; opacity: 0; }
            to { top: 20px; opacity: 1; }
        }
    </style>
</head>
<body class="customer-theme">
    
    <!-- Menyertakan navbar customer -->
    <?php require_once APP_PATH . '/views/layouts/customer-nav.php'; ?>

    <!-- Menampilkan notifikasi flash jika ada -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert floating-alert d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2 fs-5"></i>
            <div><?= $_SESSION['flash']['message'] ?></div>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Container utama untuk konten -->
    <div class="container mt-4">
        
        <!-- Card welcome untuk menyapa customer -->
        <div class="glass-card welcome-card">
            <div class="welcome-text">
                <!-- Menampilkan nama customer -->
                <h4><i class="fas fa-user-circle me-2 text-warning"></i>Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'Customer') ?>!</h4>
                <!-- Menampilkan nomor meja aktif -->
                <p>Meja Aktif: <span class="badge bg-warning text-dark ms-1">#<?= htmlspecialchars($_SESSION['meja_nama'] ?? '-') ?></span></p>
            </div>
            <div class="welcome-actions">
                <!-- Tombol untuk menuju keranjang belanja -->
                <a href="<?= APP_URL ?>/customer/cart" class="btn-glass me-2">
                    <i class="fas fa-shopping-cart"></i> Keranjang
                    <!-- Badge menunjukkan jumlah item di keranjang -->
                    <span class="badge bg-danger rounded-pill ms-1"><?= $navCartCount ?? 0 ?></span>
                </a>
                <!-- Tombol untuk melihat riwayat pesanan -->
                <a href="<?= APP_URL ?>/customer/history" class="btn-glass">
                    <i class="fas fa-history"></i> Riwayat
                </a>
            </div>
        </div>

        <!-- Filter kategori menu -->
        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-pills justify-content-center" id="menuCategories">
                    <!-- Tab untuk semua kategori -->
                    <li class="nav-item">
                        <button class="nav-link active" onclick="filterMenu('all', this)">
                            <i class="fas fa-th-large me-1"></i> Semua
                        </button>
                    </li>
                    <!-- Tab untuk kategori makanan -->
                    <li class="nav-item">
                        <button class="nav-link" onclick="filterMenu('makanan', this)">
                            <i class="fas fa-hamburger me-1"></i> Makanan
                        </button>
                    </li>
                    <!-- Tab untuk kategori minuman -->
                    <li class="nav-item">
                        <button class="nav-link" onclick="filterMenu('minuman', this)">
                            <i class="fas fa-glass-martini-alt me-1"></i> Minuman
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Grid untuk menampilkan item menu -->
        <div class="row g-4" id="menuGrid">
            <?php if (empty($menus)): ?>
                <!-- Pesan jika tidak ada menu tersedia -->
                <div class="col-12 text-center py-5">
                    <div class="glass-card p-5 d-inline-block">
                        <i class="fas fa-utensils fa-3x text-white-50 mb-3"></i>
                        <h4 class="text-white-50">Belum ada menu tersedia.</h4>
                    </div>
                </div>
            <?php else: ?>
                <!-- Loop melalui setiap menu -->
                <?php foreach ($menus as $menu): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 menu-item" data-category="<?= strtolower($menu['kategori']) ?>">
                    <div class="glass-card menu-card">
                        <!-- Gambar menu dengan fallback jika gambar tidak tersedia -->
                        <img src="<?= APP_URL ?>/assets/images/menu/<?= $menu['gambar'] ?>" 
                             class="card-img-top" alt="<?= $menu['nama'] ?>" 
                             onerror="this.src='https://placehold.co/400x300/2a2a35/FFF?text=No+Image'">
                             
                        <div class="card-body">
                            <!-- Nama menu -->
                            <h5 class="card-title"><?= htmlspecialchars($menu['nama']) ?></h5>
                            <!-- Deskripsi menu -->
                            <p class="card-text"><?= htmlspecialchars($menu['deskripsi']) ?></p>
                            
                            <!-- Bagian bawah card dengan harga dan tombol tambah -->
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <!-- Harga menu -->
                                <span class="price-tag">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></span>
                                
                                <!-- Form untuk menambahkan item ke keranjang -->
                                <form action="<?= APP_URL ?>/cart/add" method="POST">
                                    <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    
                                    <!-- Tombol submit untuk menambahkan ke keranjang -->
                                    <button type="submit" class="btn-add">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Menyertakan footer -->
    <?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
    
    <!-- JavaScript untuk fungsi filter dan notifikasi -->
    <script>
    // Fungsi untuk memfilter menu berdasarkan kategori
    function filterMenu(category, btnElement) {
        // Menghapus kelas active dari semua tab
        document.querySelectorAll('#menuCategories .nav-link').forEach(btn => btn.classList.remove('active'));
        // Menambahkan kelas active ke tab yang diklik
        btnElement.classList.add('active');

        // Menampilkan/menyembunyikan item menu berdasarkan kategori
        document.querySelectorAll('.menu-item').forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            if (category === 'all' || itemCategory === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Auto close untuk notifikasi setelah 3 detik
    setTimeout(function() {
        let alertNode = document.querySelector('.floating-alert');
        if (alertNode) {
            let alert = new bootstrap.Alert(alertNode);
            alert.close();
        }
    }, 3000);
    </script>
</body>
</html>