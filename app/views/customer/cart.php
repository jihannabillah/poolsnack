<?php
// app/views/customer/cart.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Pool Snack</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <style>
        /* --- GLOBAL THEME (UNGU MEWAH) --- */
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1574514807758-a517173e35a1?q=80&w=1920&auto=format&fit=crop') no-repeat center center/cover;
            background-attachment: fixed;
            min-height: 100vh;
            color: #fff;
            padding-bottom: 50px;
        }

        /* Overlay Ungu */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.95) 100%);
            z-index: -1;
        }

        /* --- GLASSMORPHISM COMPONENTS --- */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-body {
            padding: 1.5rem;
        }

        /* Tombol Kembali (Glass Style) */
        .btn-glass-back {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 50px;
            padding: 8px 25px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
        }
        .btn-glass-back:hover {
            background: white;
            color: #764ba2;
            transform: translateX(-3px);
        }

        /* Tombol Checkout (Gradient Hijau) */
        .btn-checkout {
            background: linear-gradient(45deg, #00b09b, #96c93d);
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 15px;
            font-weight: 700;
            color: white;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 176, 155, 0.4);
            transition: all 0.3s;
            text-align: center;
            text-decoration: none;
            display: block;
        }
        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 176, 155, 0.6);
            color: white;
        }

        /* --- CART ITEMS LIST STYLE --- */
        .cart-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem 0;
            transition: background 0.3s;
        }
        .cart-item:last-child { border-bottom: none; }
        .cart-item:hover { background: rgba(255,255,255,0.05); }

        .item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .item-title { font-weight: 600; font-size: 1.1rem; margin-bottom: 5px; color: white; }
        .item-price { color: rgba(255,255,255,0.7); font-size: 0.9rem; }
        .item-total { font-weight: 700; color: #FFD700; font-size: 1.1rem; text-shadow: 0 2px 4px rgba(0,0,0,0.2); }

        /* Quantity Buttons (+ / -) */
        .qty-group {
            background: rgba(0,0,0,0.2);
            border-radius: 50px;
            padding: 2px;
            display: inline-flex;
            align-items: center;
        }
        .qty-group .btn {
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: 0.2s;
        }
        .qty-group .btn:hover { background: rgba(255,255,255,0.2); }
        
        .qty-input {
            background: transparent;
            border: none;
            color: white;
            width: 40px;
            text-align: center;
            font-weight: 600;
        }

        /* Delete Button (Tong Sampah) */
        .btn-delete {
            color: #ff6b6b;
            background: rgba(255, 107, 107, 0.15);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .btn-delete:hover {
            background: #ff6b6b;
            color: white;
            transform: scale(1.1);
        }

        /* --- SUMMARY SECTION --- */
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1rem;
            color: rgba(255,255,255,0.9);
        }
        .summary-total {
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 20px;
            margin-top: 20px;
            font-weight: 700;
            font-size: 1.3rem;
            color: white;
        }
        .summary-total span:last-child { color: #FFD700; }
        
        .table-badge {
            background: rgba(255, 215, 0, 0.15);
            color: #FFD700;
            border: 1px solid #FFD700;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="customer-theme">
    <!-- Navbar -->
    <?php require_once APP_PATH . '/views/layouts/customer-nav.php'; ?>

    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-white"><i class="fas fa-shopping-cart me-2 text-warning"></i>Keranjang Belanja</h2>
                <p class="text-white-50 mb-0">Cek kembali pesanan Anda sebelum bayar</p>
            </div>
            <a href="<?= APP_URL ?>/customer/dashboard" class="btn-glass-back">
                <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
            </a>
        </div>

        <?php if (empty($cartItems)): ?>
            <!-- EMPTY STATE (KOSONG) -->
            <div class="glass-card text-center py-5">
                <div class="glass-body">
                    <div class="mb-4 text-white-50">
                        <i class="fas fa-shopping-basket fa-5x" style="opacity: 0.5;"></i>
                    </div>
                    <h3 class="fw-bold">Keranjang Masih Kosong</h3>
                    <p class="text-white-50 mb-4">Yuk, pesan snack dan minuman untuk menemani main billiard!</p>
                    <a href="<?= APP_URL ?>/customer/dashboard" class="btn-checkout" style="max-width: 250px; display: inline-block; background: linear-gradient(45deg, #667eea, #764ba2);">
                        Lihat Menu <i class="fas fa-utensils ms-2"></i>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <!-- LIST ITEM (KIRI) -->
                <div class="col-lg-8">
                    <div class="glass-card">
                        <div class="glass-header">
                            <h5 class="mb-0"><i class="fas fa-list-ul me-2 text-white-50"></i>Daftar Pesanan</h5>
                        </div>
                        <div class="glass-body p-0">
                            <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item px-4">
                                <div class="row align-items-center">
                                    <!-- Gambar -->
                                    <div class="col-3 col-md-2">
                                        <img src="<?= APP_URL ?>/assets/images/menu/<?= $item['gambar'] ?>" 
                                             alt="<?= $item['nama'] ?>" class="item-img"
                                             onerror="this.src='https://placehold.co/80x80/2a2a35/FFF?text=IMG'">
                                    </div>
                                    
                                    <!-- Info Nama & Harga -->
                                    <div class="col-9 col-md-4">
                                        <div class="item-title"><?= htmlspecialchars($item['nama']) ?></div>
                                        <div class="item-price">Rp <?= number_format($item['harga'], 0, ',', '.') ?> / porsi</div>
                                    </div>
                                    
                                    <!-- Quantity Control (+/-) -->
                                    <div class="col-6 col-md-3 mt-3 mt-md-0">
                                        <form action="<?= APP_URL ?>/cart/update" method="POST" class="d-flex align-items-center justify-content-start justify-content-md-center">
                                            <input type="hidden" name="cart_item_id" value="<?= $item['id'] ?>">
                                            
                                            <div class="qty-group">
                                                <button type="submit" name="action" value="decrease" class="btn"><i class="fas fa-minus small"></i></button>
                                                <input type="text" class="qty-input" value="<?= $item['quantity'] ?>" readonly>
                                                <button type="submit" name="action" value="increase" class="btn"><i class="fas fa-plus small"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- Subtotal & Hapus -->
                                    <div class="col-6 col-md-3 mt-3 mt-md-0 text-end d-flex align-items-center justify-content-end gap-3">
                                        <div class="item-total">
                                            Rp <?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?>
                                        </div>
                                        
                                        <form action="<?= APP_URL ?>/cart/remove" method="POST" onsubmit="return confirm('Hapus item ini?');">
                                            <input type="hidden" name="cart_item_id" value="<?= $item['id'] ?>">
                                            <button type="submit" class="btn-delete" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- SUMMARY (KANAN) -->
                <div class="col-lg-4">
                    <div class="glass-card">
                        <div class="glass-header" style="background: rgba(255, 215, 0, 0.1);">
                            <h5 class="mb-0 text-warning"><i class="fas fa-receipt me-2"></i>Ringkasan</h5>
                        </div>
                        <div class="glass-body">
                            <!-- Info Meja -->
                            <div class="text-center">
                                <small class="text-white-50 d-block mb-1">Pesanan untuk Meja</small>
                                <div class="table-badge">
                                    <i class="fas fa-dot-circle me-1"></i> #<?= htmlspecialchars($_SESSION['meja_nama'] ?? '-') ?>
                                </div>
                            </div>
                            
                            <!-- Rincian Harga -->
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                            </div>
                            <!-- Jika ada PPN, tampilkan di sini -->
                            <!-- <div class="summary-row"><span>PPN (10%)</span><span>...</span></div> -->
                            
                            <div class="summary-row summary-total">
                                <span>Total Bayar</span>
                                <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                            </div>

                            <div class="mt-4">
                                <a href="<?= APP_URL ?>/customer/checkout" class="btn-checkout">
                                    <i class="fas fa-credit-card me-2"></i> Checkout Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>