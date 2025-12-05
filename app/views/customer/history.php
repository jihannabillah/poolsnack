<?php
// app/views/customer/history.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Pool Snack</title>
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <style>
        /* --- GLOBAL THEME (UNGU) --- */
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1574514807758-a517173e35a1?q=80&w=1920&auto=format&fit=crop') no-repeat center center/cover;
            background-attachment: fixed;
            min-height: 100vh;
            color: #fff;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.95) 100%);
            z-index: -1;
        }

        /* --- GLASS CARD LIST --- */
        .glass-list {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            padding: 0;
        }

        /* Setiap Item Riwayat */
        .order-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.25rem 2rem;
            transition: background 0.3s ease;
            position: relative;
        }
        .order-item:last-child { border-bottom: none; }
        .order-item:hover { background: rgba(255, 255, 255, 0.08); }
        
        .order-number { font-weight: 700; font-size: 1.1rem; color: #FFD700; } /* Emas */
        .order-time { color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; }
        .order-total { font-weight: 700; font-size: 1.1rem; color: white; }

        /* Status Badge (Consistency) */
        .badge-status {
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 600;
        }

        /* Tombol Detail */
        .btn-detail {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 15px;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-detail:hover {
            background: white;
            color: #764ba2;
        }
        
        /* Empty State */
        .empty-state {
            background: rgba(0,0,0,0.2);
            padding: 5rem;
            border-radius: 20px;
            text-align: center;
        }
        .empty-state i { color: rgba(255,255,255,0.3); }
        
        /* Alert Fix */
        .alert-success {
            background-color: rgba(46, 204, 113, 0.9);
            color: white;
            border: none;
        }
        .alert-dismissible .btn-close { filter: invert(1); } /* Tombol close jadi putih */
    </style>
</head>
<body class="customer-theme">
    <?php require_once APP_PATH . '/views/layouts/customer-nav.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0"><i class="fas fa-history me-2 text-warning"></i>Riwayat Pesanan</h2>
                    
                    <!-- Jam Realtime -->
                    <span class="text-white-50" id="liveClock"></span>
                </div>

                <!-- Notifikasi Pesanan Baru -->
                <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert alert-success d-flex align-items-center alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Berhasil!</strong> Pesanan Anda telah dibuat dan sedang diproses.
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (empty($orders)): ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-clipboard-list fa-5x mb-3"></i>
                        <h4 class="text-white-50">Belum Ada Pesanan Hari Ini</h4>
                        <p class="text-white-50 mb-4">Semua riwayat pesanan Anda akan muncul di sini.</p>
                        <a href="<?= APP_URL ?>/customer/dashboard" class="btn btn-primary rounded-pill px-4 py-2" style="background: #FFD700; color: #333; border: none;">
                            <i class="fas fa-utensils me-1"></i>Pesan Sekarang
                        </a>
                    </div>
                <?php else: ?>
                    <!-- List Pesanan -->
                    <div class="glass-list">
                        <?php foreach ($orders as $order): ?>
                        <div class="order-item">
                            <div class="row align-items-center">
                                <!-- Order Number & Time -->
                                <div class="col-md-4">
                                    <div class="order-number">#<?= $order['order_number'] ?></div>
                                    <div class="order-time"><?= date('d M Y', strtotime($order['created_at'])) ?> (<?= date('H:i', strtotime($order['created_at'])) ?>)</div>
                                </div>
                                
                                <!-- Status & Meja -->
                                <div class="col-md-4 text-center">
                                    <?php 
                                        $statusClass = 'bg-secondary';
                                        if ($order['status'] == 'selesai') $statusClass = 'bg-success';
                                        elseif ($order['status'] == 'diproses') $statusClass = 'bg-info';
                                        elseif ($order['status'] == 'menunggu_konfirmasi') $statusClass = 'bg-warning text-dark';
                                        elseif ($order['status'] == 'dibatalkan') $statusClass = 'bg-danger';
                                    ?>
                                    <span class="badge badge-status <?= $statusClass ?>">
                                        <?= ucfirst(str_replace('_', ' ', $order['status'])) ?>
                                    </span>
                                    <div class="small text-white-50 mt-1">Meja: #<?= $order['nomor_meja'] ?? '-' ?></div>
                                </div>
                                
                                <!-- Total & Aksi -->
                                <div class="col-md-4 text-end">
                                    <div class="order-total">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></div>
                                    <a href="<?= APP_URL ?>/customer/nota?id=<?= $order['id'] ?>" class="btn btn-sm btn-detail mt-2">
                                        <i class="fas fa-eye me-1"></i>Detail / Nota
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

    <script>
    // ✅ Script Jam Realtime (WIB)
    function updateClock() {
        const now = new Date();
        const dateOptions = { day: '2-digit', month: 'short', year: 'numeric' };
        const dateStr = now.toLocaleDateString('id-ID', dateOptions);
        
        const timeString = now.toLocaleTimeString('id-ID', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        }).replace(/\./g, ':');

        const el = document.getElementById('liveClock');
        if(el) el.innerText = `Hari ini, ${dateStr} • ${timeString} WIB`;
    }

    setInterval(updateClock, 1000);
    updateClock();

    // Auto refresh halaman jika ada pesanan pending
    setInterval(() => {
        const hasPending = document.querySelector('.bg-warning');
        if (hasPending) {
            location.reload();
        }
    }, 30000); 
    </script>
</body>
</html>