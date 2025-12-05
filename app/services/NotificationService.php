<?php
/**
 * Service untuk mengelola notifikasi di aplikasi
 * Bertugas mengirimkan notifikasi untuk berbagai event di sistem
 * Saat ini menggunakan error_log sebagai placeholder, bisa dikembangkan lebih lanjut
 */
class NotificationService {
    
    /**
     * Mengirim notifikasi ketika order baru dibuat
     * Dipanggil saat customer berhasil checkout
     * 
     * @param int $orderId ID order yang baru dibuat
     * @return void
     */
    public static function notifyOrderCreated($orderId) {
        // Simulasi notifikasi real-time (di aplikasi nyata, gunakan WebSocket/Pusher)
        // Log ke error_log untuk debugging/demo
        error_log("ORDER CREATED: Order #" . $orderId . " at " . date('Y-m-d H:i:s'));
        
        // Di aplikasi nyata, bisa diintegrasikan dengan:
        // - WebSocket (Ratchet/Socket.io) untuk real-time notification
        // - Pusher.com (third-party service untuk real-time)
        // - Firebase Cloud Messaging (untuk mobile app)
        // - Browser Push API (untuk notifikasi browser)
        // - Email/SMS untuk notifikasi ke customer/kasir
    }

    /**
     * Mengirim notifikasi ketika status order berubah
     * Contoh: dari 'menunggu_konfirmasi' ke 'diproses'
     * 
     * @param int $orderId ID order yang statusnya berubah
     * @param string $newStatus Status baru order
     * @return void
     */
    public static function notifyOrderStatusChanged($orderId, $newStatus) {
        // Log ke error_log
        error_log("ORDER STATUS CHANGED: Order #" . $orderId . " to " . $newStatus);
        
        // Notify customer tentang perubahan status
        // Di aplikasi nyata, ini bisa berupa:
        // 1. Notifikasi di aplikasi web (real-time)
        // 2. Email ke customer
        // 3. SMS/WhatsApp notification
        self::notifyCustomer($orderId, "Status pesanan berubah menjadi: " . $newStatus);
    }

    /**
     * Mengirim notifikasi ketika customer upload bukti pembayaran
     * Dipanggil saat customer upload bukti bayar untuk metode qris/transfer
     * 
     * @param int $orderId ID order yang diupload bukti bayarnya
     * @return void
     */
    public static function notifyPaymentUploaded($orderId) {
        error_log("PAYMENT UPLOADED: Order #" . $orderId);
        
        // Notify kasir tentang bukti pembayaran baru
        // Kasir perlu tahu bahwa ada pembayaran yang perlu diverifikasi
        self::notifyKasir("Bukti pembayaran baru diupload untuk Order #" . $orderId);
    }

    /**
     * Method private untuk mengirim notifikasi ke customer
     * Ini adalah template method yang bisa di-extend dengan berbagai channel
     * 
     * @param int $orderId ID order yang terkait
     * @param string $message Pesan notifikasi
     * @return void
     */
    private static function notifyCustomer($orderId, $message) {
        // Implementasi untuk notifikasi ke customer
        // Bisa berupa:
        // - WebSocket (untuk real-time update di dashboard customer)
        // - Email (contoh: "Pesanan Anda sedang diproses")
        // - SMS (contoh: untuk notifikasi penting)
        // - Push notification (jika ada mobile app)
        
        // Contoh implementasi email:
        // Mailer::send($customerEmail, "Update Pesanan #" . $orderId, $message);
        
        // Contoh implementasi WebSocket:
        // WebSocketServer::sendToUser($customerId, ['type' => 'order_update', 'data' => $message]);
    }

    /**
     * Method private untuk mengirim notifikasi ke kasir
     * Kasir perlu mendapat alert untuk event-event penting
     * 
     * @param string $message Pesan notifikasi untuk kasir
     * @return void
     */
    private static function notifyKasir($message) {
        // Implementasi untuk notifikasi ke kasir
        // Bisa berupa:
        // - Sound alert di dashboard kasir
        // - Browser notification (jika browser mendukung)
        // - Toast notification di aplikasi
        // - Email (jika kasir tidak sedang online)
        
        // Contoh implementasi WebSocket untuk kasir:
        // WebSocketServer::sendToRole('kasir', ['type' => 'alert', 'message' => $message]);
        
        // Contoh implementasi browser notification:
        // echo "<script>new Notification('Notifikasi Kasir', { body: '$message' });</script>";
    }

    /**
     * Mendapatkan daftar notifikasi untuk dashboard kasir
     * Bisa berupa notifikasi sistem, order baru, dll
     * 
     * @return array Daftar notifikasi dalam format array
     */
    public static function getNotificationsForKasir() {
        // Mendapatkan notifikasi terbaru untuk ditampilkan di dashboard kasir
        // Di aplikasi nyata, data ini bisa diambil dari database atau cache
        
        return [
            [
                'type' => 'info',           // Jenis: info, warning, success, danger
                'message' => 'Sistem berjalan normal',  // Pesan notifikasi
                'time' => '5 menit lalu'    // Waktu notifikasi
            ]
            // Bisa ditambahkan lebih banyak notifikasi
            // [
            //     'type' => 'warning',
            //     'message' => 'Stok menu X hampir habis',
            //     'time' => '10 menit lalu'
            // ]
        ];
    }
}
?>