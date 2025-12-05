<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Customer - Pool Snack</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <style>
        /* --- GLOBAL THEME --- */
        body {
            font-family: 'Poppins', sans-serif;
            /* Background Gambar Billiard Gelap */
            background: url('https://images.unsplash.com/photo-1574514807758-a517173e35a1?q=80&w=1920&auto=format&fit=crop') no-repeat center center/cover;
            background-attachment: fixed;
            min-height: 100vh;
            color: #fff; /* Teks default putih */
            padding-bottom: 50px;
        }

        /* Overlay Ungu Gradient */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.95) 100%);
            z-index: -1;
        }

        /* --- GLASSMORPHISM CARD --- */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            color: white;
            overflow: hidden;
        }

        /* --- WELCOME HEADER --- */
        .welcome-section {
            padding: 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .user-info h4 { font-weight: 700; margin: 0; }
        .user-info p { margin: 0; opacity: 0.8; font-size: 0.9rem; }
        
        /* Tombol Header (Glass Style) */
        .btn-glass-nav {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 50px;
            padding: 8px 20px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-glass-nav:hover {
            background: white;
            color: #764ba2;
            transform: translateY(-2px);
        }

        /* --- CATEGORY TABS --- */
        .nav-pills { gap: 10px; justify-content: center; }
        .nav-pills .nav-link {
            color: rgba(255,255,255,0.7);
            background: rgba(0,0,0,0.2);
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 500;
            border: 1px solid transparent;
            transition: all 0.3s;
        }
        .nav-pills .nav-link:hover { color: white; background: rgba(0,0,0,0.4); }
        .nav-pills .nav-link.active {
            background: #FFD700; /* Emas */
            color: #333;
            font-weight: 700;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.4);
            border-color: #FFD700;
        }

        /* --- MENU CARDS --- */
        .menu-item .glass-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .menu-item:hover .glass-card {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .card-img-top {
            height: 180px;
            object-fit: cover;
            width: 100%;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .menu-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .menu-title { font-weight: 600; font-size: 1.1rem; margin-bottom: 0.5rem; }
        .menu-desc { font-size: 0.85rem; opacity: 0.7; line-height: 1.4; margin-bottom: 1rem; flex-grow: 1; }
        
        .price-tag {
            font-size: 1.2rem;
            font-weight: 700;
            color: #FFD700; /* Harga Emas */
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        /* Tombol Tambah */
        .btn-add-cart {
            background: linear-gradient(45deg, #00b09b, #96c93d); /* Gradasi Hijau Segar */
            border: none;
            border-radius: 50px;
            padding: 8px 20px;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,176,155,0.4);
            transition: all 0.3s;
        }
        .btn-add-cart:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0,176,155,0.6);
            color: white;
        }

        /* Notifikasi */
        .floating-alert {
            position: fixed;
            top: 20px; left: 50%; transform: translateX(-50%);
            z-index: 9999;
            min-width: 320px;
            backdrop-filter: blur(10px);
            background: rgba(46, 204, 113, 0.9);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            animation: slideDown 0.5s ease-out;
        }
        @keyframes slideDown { from { top: -100px; opacity: 0; } to { top: 20px; opacity: 1; } }
    </style>
</head>
<body class="customer-theme">
    
    <?php require_once APP_PATH . '/views/layouts/customer-nav.php'; ?>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert floating-alert d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2 fs-5"></i>
            <div><?= $_SESSION['flash']['message'] ?></div>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="container mt-4">
        
        <div class="glass-card welcome-section">
            <div class="user-info">
                <h4><i class="fas fa-user-circle me-2 text-warning"></i>Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'Customer') ?>!</h4>
                <p>Meja Aktif: <span class="badge bg-warning text-dark px-2">#<?= htmlspecialchars($_SESSION['meja_nama'] ?? '-') ?></span></p>
            </div>
            <div class="action-buttons">
                <a href="<?= APP_URL ?>/customer/cart" class="btn-glass-nav me-2">
                    <i class="fas fa-shopping-cart"></i> Keranjang
                    <?php if(($navCartCount ?? 0) > 0): ?>
                        <span class="badge bg-danger rounded-pill"><?= $navCartCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= APP_URL ?>/customer/history" class="btn-glass-nav">
                    <i class="fas fa-history"></i> Riwayat
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-pills" id="menuCategories">
                    <li class="nav-item">
                        <button class="nav-link active" onclick="filterMenu('all', this)">
                            <i class="fas fa-th-large me-1"></i> Semua
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" onclick="filterMenu('makanan', this)">
                            <i class="fas fa-hamburger me-1"></i> Makanan
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" onclick="filterMenu('minuman', this)">
                            <i class="fas fa-glass-martini-alt me-1"></i> Minuman
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row g-4" id="menuGrid">
            <?php foreach ($menus as $menu): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 menu-item" data-category="<?= strtolower($menu['kategori']) ?>">
                <div class="glass-card menu-card">
                    <img src="<?= APP_URL ?>/assets/images/menu/<?= $menu['gambar'] ?>" 
                         class="card-img-top" alt="<?= $menu['nama'] ?>" 
                         onerror="this.src='https://placehold.co/400x300/2a2a35/FFF?text=No+Image'">
                         
                    <div class="menu-body">
                        <h5 class="menu-title"><?= htmlspecialchars($menu['nama']) ?></h5>
                        <p class="menu-desc"><?= htmlspecialchars($menu['deskripsi']) ?></p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="price-tag">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></span>
                            
                            <form action="<?= APP_URL ?>/cart/add" method="POST">
                                <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                
                                <button type="submit" class="btn-add-cart">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
    
    <script>
    function filterMenu(category, btnElement) {
        // Update tombol aktif
        document.querySelectorAll('#menuCategories .nav-link').forEach(btn => btn.classList.remove('active'));
        btnElement.classList.add('active');

        // Filter item
        document.querySelectorAll('.menu-item').forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            if (category === 'all' || itemCategory === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Auto Close Notifikasi
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