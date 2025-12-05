<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta tags untuk karakter dan viewport responsif -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Judul halaman dashboard kasir -->
    <title>Dashboard Kasir - Pool Snack</title>
    
    <!-- Menyertakan Bootstrap 5 untuk styling dasar -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Menyertakan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Menyertakan font Poppins dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Menyertakan stylesheet kustom utama -->
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <!-- Stylesheet inline untuk styling spesifik dashboard kasir -->
    <style>
        /* Styling dasar body dengan background abu-abu terang */
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        
        /* Navbar dengan gradient hijau untuk tema kasir */
        .kasir-theme .navbar { background: linear-gradient(135deg, #27AE60 0%, #2ECC71 100%) !important; }
        
        /* --- STATS CARD PREMIUM --- */
        
        /* Card statistik dengan styling premium */
        .stat-card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
            color: white;
            padding: 1.5rem;
        }
        
        /* Efek hover pada card statistik */
        .stat-card-custom:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 8px 20px rgba(0,0,0,0.2); 
        }

        /* Gradient untuk card statistik pesanan menunggu (kuning emas) */
        .stat-pending { 
            background: linear-gradient(45deg, #FFC107, #FFD700); 
        }
        
        /* Gradient untuk card statistik pesanan diproses (biru) */
        .stat-active { 
            background: linear-gradient(45deg, #3498DB, #5DADE2); 
        }
        
        /* Gradient untuk card statistik pendapatan (hijau) */
        .stat-sales { 
            background: linear-gradient(45deg, #27AE60, #2ECC71); 
        }
        
        /* Gradient untuk card statistik waktu (abu-abu gelap) */
        .stat-time { 
            background: linear-gradient(45deg, #34495E, #5D6D7E); 
        }

        /* --- ORDER LIST CARD (High Contrast) --- */
        
        /* Container card untuk daftar pesanan */
        .order-container-card {
            border-radius: 12px;
            border: none;
        }

        /* Card untuk setiap pesanan */
        .order-card {
            border: 1px solid #eee;
            border-left: 5px solid #FFC107; /* Warna kuning untuk status menunggu */
            transition: background 0.2s ease, transform 0.2s ease;
            cursor: pointer; /* Menunjukkan bahwa card dapat diklik */
        }
        
        /* Variasi card untuk pesanan aktif/diproses */
        .order-card-active {
            border-left: 5px solid #3498DB; /* Warna biru untuk status diproses */
        }
        
        /* Efek hover pada card pesanan */
        .order-card:hover {
            background-color: #fefefe;
            transform: scale(1.01);
        }

        /* Styling untuk teks detail yang lebih kecil */
        .text-detail { 
            font-size: 0.8rem; 
            color: #777; 
        }
        
        /* Styling untuk teks total harga dengan penekanan */
        .text-total { 
            font-weight: 700; 
            color: #333; 
        }
    </style>
</head>
<body class="kasir-theme">
    
    <!-- Menyertakan navbar kasir -->
    <?php require_once APP_PATH . '/views/layouts/kasir-nav.php'; ?>

    <!-- Container utama untuk konten dashboard -->
    <div class="container-fluid mt-4">
        
        <!-- Baris pertama: Statistik Dashboard -->
        <div class="row mb-4">
            
            <!-- Card 1: Order Menunggu -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-custom stat-pending">
                    <div class="d-flex justify-content-between">
                        <div>
                            <!-- Menampilkan jumlah order yang menunggu -->
                            <h4 class="mb-0 fw-bold"><?= count($pendingOrders) ?></h4>
                            <p class="mb-0">Order Menunggu</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Order Sedang Diproses -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-custom stat-active">
                    <div class="d-flex justify-content-between">
                        <div>
                            <!-- Menampilkan jumlah order yang sedang diproses -->
                            <h4 class="mb-0 fw-bold"><?= count($activeOrders) ?></h4>
                            <p class="mb-0">Sedang Diproses</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-utensils fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Pendapatan Hari Ini -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-custom stat-sales">
                    <div class="d-flex justify-content-between">
                        <div>
                            <!-- Menampilkan total pendapatan hari ini -->
                            <h4 class="mb-0 fw-bold">Rp <?= number_format($todaySales, 0, ',', '.') ?></h4>
                            <p class="mb-0">Pendapatan Hari Ini</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Waktu Sekarang -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-custom stat-time">
                    <div class="d-flex justify-content-between">
                        <div>
                            <!-- Menampilkan waktu realtime -->
                            <h4 class="mb-0 fw-bold" id="realtimeClock"><?= date('H:i:s') ?></h4>
                            <p class="mb-0">Waktu Sekarang (WIB)</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris kedua: Daftar Pesanan -->
        <div class="row">
            
            <!-- Kolom kiri: Order Menunggu Konfirmasi -->
            <div class="col-lg-6">
                <div class="card order-container-card">
                    <!-- Header card dengan warna kuning -->
                    <div class="card-header bg-warning text-white order-container-card">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Order Menunggu Konfirmasi
                        </h5>
                    </div>
                    
                    <!-- Body card untuk daftar order menunggu -->
                    <div class="card-body pt-3">
                        <?php if (empty($pendingOrders)): ?>
                            <!-- Pesan jika tidak ada order menunggu -->
                            <p class="text-muted text-center py-3">Tidak ada order menunggu</p>
                        <?php else: ?>
                            <!-- Loop melalui setiap order yang menunggu -->
                            <?php foreach ($pendingOrders as $order): ?>
                            <div class="card order-card mb-3" onclick="location.href='<?= APP_URL ?>/kasir/orders'" style="border-left-color: #FFC107;">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <!-- Nomor order dan meja -->
                                            <h6 class="mb-1 fw-bold">#<?= $order['order_number'] ?></h6>
                                            <p class="mb-0 text-detail">
                                                Meja: <?= $order['nomor_meja'] ?> | 
                                                <?= date('H:i', strtotime($order['created_at'])) ?>
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <!-- Total harga order -->
                                            <p class="mb-0 text-total">
                                                Rp <?= number_format($order['total_harga'], 0, ',', '.') ?>
                                            </p>
                                            <div class="mt-1">
                                                <!-- Badge status menunggu -->
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                                
                                                <?php if ($order['metode_bayar'] === 'qris' && $order['bukti_bayar']): ?>
                                                    <!-- Tombol untuk melihat bukti pembayaran QRIS -->
                                                    <button class="btn btn-sm btn-outline-primary ms-2" 
                                                            onclick="event.stopPropagation(); viewPaymentProof('<?= $order['bukti_bayar'] ?>', <?= $order['id'] ?>)">
                                                        <i class="fas fa-receipt"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <!-- Tombol untuk memproses order (non-QRIS) -->
                                                    <a href="<?= APP_URL ?>/kasir/status?id=<?= $order['id'] ?>&status=diproses" 
                                                       class="btn btn-sm btn-success ms-2"
                                                       onclick="event.stopPropagation(); return confirm('Proses pesanan ini?')">
                                                        <i class="fas fa-check"></i> Proses
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Kolom kanan: Order Sedang Diproses -->
            <div class="col-lg-6">
                <div class="card order-container-card">
                    <!-- Header card dengan gradient biru -->
                    <div class="card-header bg-info text-white order-container-card" style="background: linear-gradient(45deg, #3498DB, #5DADE2) !important;">
                        <h5 class="mb-0">
                            <i class="fas fa-utensils me-2"></i>
                            Order Sedang Diproses
                        </h5>
                    </div>
                    
                    <!-- Body card untuk daftar order diproses -->
                    <div class="card-body pt-3">
                        <?php if (empty($activeOrders)): ?>
                            <!-- Pesan jika tidak ada order diproses -->
                            <p class="text-muted text-center py-3">Tidak ada order diproses</p>
                        <?php else: ?>
                            <!-- Loop melalui setiap order yang diproses -->
                            <?php foreach ($activeOrders as $order): ?>
                            <div class="card order-card order-card-active mb-3" style="border-left-color: #3498DB;">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <!-- Nomor order dan informasi meja -->
                                            <h6 class="mb-1 fw-bold">Order #<?= $order['order_number'] ?></h6>
                                            <p class="mb-0 text-detail">
                                                Meja: <?= $order['nomor_meja'] ?> | 
                                                Total Item: (X) <!-- TODO: Tambahkan logika jumlah item -->
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <!-- Total harga order -->
                                            <p class="mb-0 text-total">
                                                Rp <?= number_format($order['total_harga'], 0, ',', '.') ?>
                                            </p>
                                            <div class="mt-1">
                                                <!-- Tombol untuk menyelesaikan order -->
                                                <a href="<?= APP_URL ?>/kasir/status?id=<?= $order['id'] ?>&status=selesai" 
                                                   class="btn btn-sm btn-success"
                                                   onclick="return confirm('Selesaikan pesanan ini?')">
                                                    <i class="fas fa-check me-1"></i> Selesai
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk menampilkan bukti pembayaran -->
    <div class="modal fade" id="paymentProofModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Header modal -->
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <!-- Body modal: Menampilkan gambar bukti pembayaran -->
                <div class="modal-body text-center">
                    <img id="paymentProofImage" src="" alt="Bukti Pembayaran" class="img-fluid rounded">
                </div>
                
                <!-- Footer modal: Tombol aksi -->
                <div class="modal-footer">
                    <a href="#" id="btnReject" class="btn btn-danger">Tolak</a>
                    <a href="#" id="btnAccept" class="btn btn-success">Verifikasi & Proses</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Menyertakan Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript kustom -->
    <script>
    // Fungsi untuk update jam realtime (WIB)
    function updateClock() {
        const now = new Date();
        // Format waktu menjadi HH:mm:ss dengan timezone Jakarta
        const timeString = now.toLocaleTimeString('id-ID', {
            timeZone: 'Asia/Jakarta', 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit', 
            hour12: false
        }).replace(/\./g, ':');
        
        // Update elemen jam di halaman
        const el = document.getElementById('realtimeClock');
        if(el) el.innerText = timeString;
    }
    
    // Update jam setiap detik
    setInterval(updateClock, 1000);
    // Jalankan pertama kali saat halaman dimuat
    updateClock();

    // Fungsi untuk menampilkan modal bukti pembayaran
    function viewPaymentProof(imageName, orderId) {
        // Membuat path lengkap ke gambar
        const imgPath = '<?= APP_URL ?>/uploads/payments/' + imageName;
        
        // Mengatur sumber gambar di modal
        document.getElementById('paymentProofImage').src = imgPath;
        
        // Mengatur URL untuk tombol aksi di modal
        document.getElementById('btnAccept').href = '<?= APP_URL ?>/kasir/verify?id=' + orderId + '&action=accept';
        document.getElementById('btnReject').href = '<?= APP_URL ?>/kasir/verify?id=' + orderId + '&action=reject';
        
        // Menampilkan modal
        new bootstrap.Modal(document.getElementById('paymentProofModal')).show();
    }

    // Auto refresh halaman setiap 30 detik untuk update data realtime
    setInterval(() => {
        location.reload();
    }, 30000);
    </script>
</body>
</html>