<?php
// app/views/components/order-item.php
// Reusable order item component - Komponen item order yang bisa digunakan berulang

/**
 * Fungsi untuk merender satu item/baris order dalam daftar order
 * Komponen reusable untuk menampilkan order di berbagai halaman
 * 
 * @param array $order Data order dalam bentuk array asosiatif
 * @param bool $showActions Menampilkan tombol aksi (detail, nota) jika true (default: false)
 * @return void Output langsung ke HTML (echo)
 */
function renderOrderItem($order, $showActions = false) {
    // Mapping status order ke badge styling
    $statusBadge = [
        'menunggu_konfirmasi' => [
            'class' => 'bg-warning',      // Kuning - pending
            'text' => 'Menunggu Konfirmasi'
        ],
        'diproses' => [
            'class' => 'bg-info',         // Biru - in progress
            'text' => 'Diproses'
        ],
        'selesai' => [
            'class' => 'bg-success',      // Hijau - completed
            'text' => 'Selesai'
        ],
        'dibatalkan' => [
            'class' => 'bg-danger',       // Merah - cancelled
            'text' => 'Dibatalkan'
        ]
    ];
    
    // Ambil styling untuk status order, default jika status tidak dikenali
    $status = $statusBadge[$order['status']] ?? [
        'class' => 'bg-secondary',        // Abu-abu - unknown status
        'text' => $order['status']        // Tampilkan status asli
    ];
    ?>
    
    <!-- Kartu untuk satu item order -->
    <div class="card order-item mb-3">
        <div class="card-body">
            <!-- Grid layout untuk konten order -->
            <div class="row align-items-center">
                
                <!-- Kolom 1: Nomor Order & Tanggal -->
                <div class="col-md-3">
                    <!-- Nomor order -->
                    <strong>#<?= $order['order_number'] ?></strong>
                    <br>
                    <!-- Tanggal pembuatan order -->
                    <small class="text-muted"><?= $order['created_at'] ?></small>
                </div>
                
                <!-- Kolom 2: Status Order -->
                <div class="col-md-2">
                    <!-- Badge status dengan warna sesuai mapping -->
                    <span class="badge <?= $status['class'] ?>"><?= $status['text'] ?></span>
                </div>
                
                <!-- Kolom 3: Meja -->
                <div class="col-md-2">
                    Meja: <strong>#<?= $order['nomor_meja'] ?></strong>
                </div>
                
                <!-- Kolom 4: Total Harga -->
                <div class="col-md-2">
                    <!-- Format harga ke Rupiah -->
                    <strong>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></strong>
                </div>
                
                <!-- Kolom 5: Actions (conditional) -->
                <div class="col-md-3 text-end">
                    <?php if ($showActions): ?>
                    <!-- Tombol Lihat Detail -->
                    <button class="btn btn-sm btn-outline-primary view-order-details" 
                            data-order-id="<?= $order['id'] ?>">
                        <i class="fas fa-eye me-1"></i>Detail
                    </button>
                    
                    <!-- Tombol Download Nota (hanya untuk order selesai) -->
                    <?php if ($order['status'] == 'selesai'): ?>
                    <button class="btn btn-sm btn-outline-success download-nota" 
                            data-order-id="<?= $order['id'] ?>">
                        <i class="fas fa-receipt me-1"></i>Nota
                    </button>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>
    
    <?php
}
?>