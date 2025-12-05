<?php
// app/views/customer/checkout.php
if (!isset($cartItems)) {
    header('Location: ' . APP_URL . '/customer/cart');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Pool Snack</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <style>
        /* --- GLOBAL THEME (UNGU) --- */
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1574514807758-a517173e35a1?q=80&w=1920&auto=format&fit=crop') no-repeat center center/cover;
            background-attachment: fixed;
            min-height: 100vh;
            color: #fff;
            padding-bottom: 80px;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.95) 100%);
            z-index: -1;
        }

        /* --- GLASS CARD --- */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-body { padding: 2rem; }

        /* --- TABLE STYLE --- */
        .table-custom { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .table-custom th { color: rgba(255,255,255,0.6); font-weight: 500; text-transform: uppercase; font-size: 0.85rem; padding-bottom: 10px; }
        .table-custom td { background: rgba(255,255,255,0.05); padding: 15px; first-child: border-radius: 10px 0 0 10px; last-child: border-radius: 0 10px 10px 0; }
        .table-custom tr td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
        .table-custom tr td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }
        
        .item-name { font-weight: 600; color: white; }
        .item-meta { font-size: 0.85rem; color: rgba(255,255,255,0.6); }
        .item-total { font-weight: 700; color: #FFD700; }

        /* --- SUMMARY BOX --- */
        .summary-box {
            background: rgba(0,0,0,0.2);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
        }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; color: rgba(255,255,255,0.8); }
        .summary-total { border-top: 1px solid rgba(255,255,255,0.2); padding-top: 15px; margin-top: 15px; font-size: 1.2rem; font-weight: 700; color: white; }
        .summary-total span:last-child { color: #FFD700; }

        /* --- PAYMENT RADIO (Custom Style) --- */
        .payment-option {
            display: block;
            position: relative;
            cursor: pointer;
            margin-bottom: 15px;
        }
        .payment-option input { position: absolute; opacity: 0; cursor: pointer; }
        
        .payment-card {
            background: rgba(255,255,255,0.05);
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        
        .payment-card:hover { background: rgba(255,255,255,0.1); }
        
        /* Selected State */
        .payment-option input:checked ~ .payment-card {
            border-color: #FFD700;
            background: rgba(255, 215, 0, 0.1);
        }
        .payment-option input:checked ~ .payment-card .icon-box {
            background: #FFD700;
            color: #333;
        }

        .icon-box {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
            transition: all 0.3s;
        }

        /* --- BUTTONS --- */
        .btn-order {
            background: linear-gradient(45deg, #00b09b, #96c93d);
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 50px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 176, 155, 0.4);
            transition: all 0.3s;
        }
        .btn-order:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0, 176, 155, 0.6); color: white; }
        
        .btn-back-glass {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .btn-back-glass:hover { color: white; }

        /* Upload Input */
        .form-control {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 10px;
        }
        .form-control:focus { background: rgba(255,255,255,0.15); color: white; border-color: #FFD700; box-shadow: none; }
        
        /* QR Frame */
        .qr-frame {
            background: white;
            padding: 10px;
            border-radius: 15px;
            display: inline-block;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.3); /* Gold Glow */
        }
    </style>
</head>
<body class="customer-theme">
    <?php require_once APP_PATH . '/views/layouts/customer-nav.php'; ?>

    <div class="container mt-4">
        <form action="<?= APP_URL ?>/order/create" method="POST" enctype="multipart/form-data" id="checkoutForm">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold m-0"><i class="fas fa-wallet me-2 text-warning"></i>Checkout</h3>
                        <a href="<?= APP_URL ?>/customer/cart" class="btn-back-glass">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Keranjang
                        </a>
                    </div>

                    <div class="glass-card mb-4">
                        <div class="glass-body">
                            <h5 class="mb-4 fw-bold">Rincian Pesanan</h5>
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="item-name"><?= htmlspecialchars($item['nama']) ?></div>
                                            <div class="item-meta"><?= $item['quantity'] ?> x Rp <?= number_format($item['harga'], 0, ',', '.') ?></div>
                                        </td>
                                        <td class="text-end">
                                            <div class="item-total">Rp <?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <div class="summary-box">
                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>PPN (10%)</span>
                                    <span>Rp <?= number_format($ppn, 0, ',', '.') ?></span>
                                </div>
                                <div class="summary-total d-flex justify-content-between">
                                    <span>Total Bayar</span>
                                    <span>Rp <?= number_format($grandTotal, 0, ',', '.') ?></span>
                                </div>
                                <input type="hidden" name="grand_total" value="<?= $grandTotal ?>">
                            </div>
                        </div>
                    </div>

                    <div class="glass-card mb-4">
                        <div class="glass-body">
                            <h5 class="mb-4 fw-bold">Metode Pembayaran</h5>
                            
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="tunai" checked>
                                <div class="payment-card">
                                    <div class="icon-box"><i class="fas fa-money-bill-wave"></i></div>
                                    <div>
                                        <div class="fw-bold">Tunai (Cash)</div>
                                        <div class="small text-white-50">Bayar langsung di kasir</div>
                                    </div>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="qris">
                                <div class="payment-card">
                                    <div class="icon-box"><i class="fas fa-qrcode"></i></div>
                                    <div>
                                        <div class="fw-bold">QRIS / Transfer</div>
                                        <div class="small text-white-50">Scan kode QR & upload bukti</div>
                                    </div>
                                </div>
                            </label>

                            <div id="qrisSection" class="mt-4 ps-2" style="display: none;">
                                <div class="text-center mb-4">
                                    <div class="qr-frame">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=http://poolsnackbilliard.wuaze.com" 
                                             alt="QR Code Pool Snack" 
                                             class="img-fluid rounded"
                                             style="width: 200px; height: 200px;">
                                    </div>
                                    <p class="mt-3 text-white-50 small">
                                        Scan untuk info pembayaran atau transfer ke nomor admin.
                                    </p>
                                </div>

                                <div class="alert alert-info bg-opacity-25 border-0 text-white">
                                    <i class="fas fa-info-circle me-2"></i> Total Transfer: <strong>Rp <?= number_format($grandTotal, 0, ',', '.') ?></strong>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label small text-white-50">Upload Bukti Pembayaran</label>
                                    <input type="file" class="form-control" name="payment_proof" id="payment_proof" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <div class="d-inline-block px-4 py-2 rounded-pill" style="background: rgba(255,215,0,0.15); border: 1px solid #FFD700;">
                            <span class="text-warning"><i class="fas fa-map-marker-alt me-2"></i>Pesanan untuk <strong>Meja #<?= htmlspecialchars($_SESSION['meja_nama'] ?? '-') ?></strong></span>
                        </div>
                    </div>

                    <button type="submit" class="btn-order" id="placeOrderBtn">
                        Buat Pesanan <i class="fas fa-check-circle ms-2"></i>
                    </button>

                </div>
            </div>
        </form>
    </div>

    <?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
    
    <script>
    // Toggle QRIS Form
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const qrisSection = document.getElementById('qrisSection');
            const proofInput = document.getElementById('payment_proof');
            
            if (this.value === 'qris') {
                qrisSection.style.display = 'block';
                proofInput.required = true;
            } else {
                qrisSection.style.display = 'none';
                proofInput.required = false;
                proofInput.value = '';
            }
        });
    });

    // Loading State
    document.getElementById('checkoutForm').addEventListener('submit', function() {
        const btn = document.getElementById('placeOrderBtn');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        btn.disabled = true;
    });
    </script>
</body>
</html>