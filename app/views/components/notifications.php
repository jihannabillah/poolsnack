<?php
// app/views/components/notifications.php
// Reusable notifications component - Komponen notifikasi yang bisa digunakan berulang

/**
 * Fungsi untuk merender multiple notifications/toasts
 * Menggunakan Bootstrap Toast component untuk tampilan notifikasi
 * 
 * @param array $notifications Array notifikasi yang akan ditampilkan
 *   Format: [
 *     ['type' => 'success', 'title' => 'Berhasil', 'message' => '...'],
 *     ['type' => 'error', 'title' => 'Error', 'message' => '...'],
 *     ['type' => 'warning', 'title' => 'Peringatan', 'message' => '...'],
 *     ['type' => 'info', 'title' => 'Info', 'message' => '...']
 *   ]
 * @return void Output langsung ke HTML (echo)
 */
function renderNotifications($notifications = []) {
    ?>
    <!-- Container untuk notifikasi (position fixed di pojok kanan atas) -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <?php foreach ($notifications as $notification): ?>
        <!-- Toast notification individual -->
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <!-- Toast header dengan warna berdasarkan type -->
            <div class="toast-header 
                <?= $notification['type'] == 'success' ? 'bg-success text-white' : 
                   ($notification['type'] == 'error' ? 'bg-danger text-white' : 
                   ($notification['type'] == 'warning' ? 'bg-warning text-dark' : 'bg-info text-white')) ?>">
                
                <!-- Icon berdasarkan type notifikasi -->
                <i class="fas 
                    <?= $notification['type'] == 'success' ? 'fa-check-circle' : 
                       ($notification['type'] == 'error' ? 'fa-exclamation-circle' : 
                       ($notification['type'] == 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle')) ?> 
                    me-2"></i>
                
                <!-- Judul notifikasi -->
                <strong class="me-auto"><?= $notification['title'] ?? 'Notification' ?></strong>
                
                <!-- Tombol close -->
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            
            <!-- Body notifikasi (pesan utama) -->
            <div class="toast-body">
                <?= $notification['message'] ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Fungsi untuk merender notification badge (bubble angka)
 * Biasanya ditempatkan di icon bell atau tombol notifikasi
 * 
 * @param int $count Jumlah notifikasi yang belum dibaca
 * @return string HTML badge atau string kosong jika count = 0
 */
function renderNotificationBadge($count = 0) {
    // Hanya tampilkan badge jika ada notifikasi
    if ($count > 0) {
        // Bootstrap badge dengan posisi absolute (biasanya di atas icon)
        return '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' 
               . $count . 
               '</span>';
    }
    return ''; // Return string kosong jika tidak ada notifikasi
}

/**
 * Fungsi untuk generate notifikasi status order
 * Berdasarkan status order, generate pesan notifikasi yang sesuai
 * 
 * @param array $order Data order (harus memiliki 'status' dan 'order_number')
 * @return array|null Array notifikasi atau null jika status tidak dikenali
 */
function renderOrderStatusNotification($order) {
    // Mapping status order ke pesan notifikasi
    $statusMessages = [
        'menunggu_konfirmasi' => [
            'title' => 'Order Diterima',
            'message' => 'Order #' . $order['order_number'] . ' sedang menunggu konfirmasi',
            'type' => 'info'  // Biru
        ],
        'diproses' => [
            'title' => 'Order Diproses', 
            'message' => 'Order #' . $order['order_number'] . ' sedang diproses dapur',
            'type' => 'warning'  // Kuning
        ],
        'selesai' => [
            'title' => 'Order Selesai',
            'message' => 'Order #' . $order['order_number'] . ' telah selesai dan siap diantar',
            'type' => 'success'  // Hijau
        ]
        // Bisa ditambahkan status lainnya:
        // 'dibatalkan' => [...],
        // 'diantar' => [...],
    ];
    
    // Cek apakah status order ada di mapping
    $message = $statusMessages[$order['status']] ?? null;
    if ($message) {
        return $message;
    }
    return null; // Return null jika status tidak dikenali
}
?>