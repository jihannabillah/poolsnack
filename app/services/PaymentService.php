<?php
/**
 * Class PaymentService - Service untuk menangani logika bisnis terkait pembayaran
 * Service class ini berisi method static yang bisa dipanggil dari mana saja
 */
class PaymentService {
    
    /**
     * Method untuk memvalidasi file bukti pembayaran yang diupload oleh customer
     * Melakukan pengecekan tipe file dan ukuran file
     * 
     * @param array $file Data file dari $_FILES (contoh: $_FILES['payment_proof'])
     * @return bool Mengembalikan true jika file valid
     * @throws Exception Melempar exception dengan pesan error jika validasi gagal
     */
    public static function validatePaymentProof($file) {
        // Daftar tipe file yang diizinkan untuk upload bukti pembayaran
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        
        // Ukuran maksimal file: 2MB (dalam bytes)
        $maxSize = 2 * 1024 * 1024; // 2MB

        // Validasi 1: Cek apakah tipe file termasuk dalam daftar yang diizinkan
        if (!in_array($file['type'], $allowedTypes)) {
            // Jika tipe file tidak diizinkan, lempar exception dengan pesan error
            throw new Exception('Format file tidak didukung. Hanya JPG dan PNG yang diperbolehkan.');
        }

        // Validasi 2: Cek apakah ukuran file melebihi batas maksimal
        if ($file['size'] > $maxSize) {
            // Jika file terlalu besar, lempar exception
            throw new Exception('Ukuran file terlalu besar. Maksimal 2MB.');
        }

        // Catatan: Validasi tambahan yang bisa ditambahkan di masa depan:
        // - Pengecekan dimensi gambar (lebar dan tinggi minimum)
        // - Validasi apakah gambar mengandung QR code (untuk bukti QRIS)
        // - Deteksi watermark atau tanda keaslian pada gambar
        // - Pemeriksaan metadata gambar

        // Jika semua validasi berhasil, kembalikan true
        return true;
    }

    /**
     * Method untuk generate data QRIS (QR Code Indonesian Standard)
     * Membuat struktur data yang akan digunakan untuk membuat QR code pembayaran
     * 
     * @param float $amount Jumlah nominal pembayaran
     * @param int $orderId ID order yang terkait dengan pembayaran
     * @return array Struktur data QRIS dalam bentuk array asosiatif
     */
    public static function generateQRCodeData($amount, $orderId) {
        // Nama merchant/toko yang akan ditampilkan di QR code
        $merchantName = "POOL SNACK BILLIARD";
        
        // Format amount: hilangkan pemisah ribuan dan desimal
        // Contoh: 25.000 → 25000
        $transactionAmount = number_format($amount, 0, '', '');
        
        // Membuat struktur data QRIS (versi sederhana untuk demo)
        // Di implementasi nyata, ini akan menghasilkan payload QRIS yang sesuai standar
        $qrisData = [
            'merchant_name' => $merchantName,      // Nama merchant/toko
            'amount' => $transactionAmount,        // Nominal transaksi (tanpa format)
            'order_id' => $orderId,                // ID order untuk referensi
            'timestamp' => date('YmdHis')          // Timestamp transaksi (format: YYYYMMDDHHMMSS)
        ];

        // Di aplikasi produksi, data ini akan diolah lebih lanjut:
        // - Diencode menjadi string payload QRIS
        // - Digenerate menjadi gambar QR code
        // - Dikirim ke frontend untuk ditampilkan

        return $qrisData;
    }

    /**
     * Method untuk memverifikasi pembayaran QRIS
     * Simulasi proses verifikasi pembayaran dengan delay meniru API call
     * 
     * @param array $paymentData Data pembayaran yang perlu diverifikasi
     * @return array Hasil verifikasi dalam bentuk array asosiatif
     */
    public static function verifyQRISPayment($paymentData) {
        // Simulasi delay: meniru waktu yang dibutuhkan untuk call API ke payment gateway
        // Di aplikasi nyata, ini akan memanggil API bank atau payment gateway
        sleep(2); // Simulate API call delay (tunggu 2 detik)
        
        // Di implementasi nyata, method ini akan mengintegrasikan dengan:
        // - API QRIS dari bank (BCA, Mandiri, BRI, dll)
        // - Payment gateway pihak ketiga (Midtrans, Xendit, Doku, dll)
        // - Atau verifikasi manual oleh kasir untuk kasus tertentu
        
        // Untuk keperluan demo: asumsikan pembayaran selalu berhasil
        return [
            'success' => true,                      // Status keberhasilan verifikasi
            'transaction_id' => 'TRX' . time(),     // ID transaksi unik (dibuat dari timestamp)
            'amount' => $paymentData['amount'],     // Jumlah yang diverifikasi
            'timestamp' => date('Y-m-d H:i:s')      // Waktu verifikasi
        ];
    }

    /**
     * Method untuk memproses pembayaran tunai (cash)
     * Logika sederhana untuk pembayaran langsung di kasir
     * 
     * @param float $amount Jumlah yang harus dibayar
     * @return array Hasil proses pembayaran tunai
     */
    public static function processCashPayment($amount) {
        // Proses pembayaran tunai yang disederhanakan
        // Di aplikasi nyata, mungkin perlu menghitung kembalian, dll
        return [
            'success' => true,                     // Status selalu berhasil untuk cash
            'payment_method' => 'cash',            // Metode pembayaran
            'amount_received' => $amount,          // Jumlah yang diterima (diasumsikan pas)
            'change' => 0                          // Kembalian (0 karena diasumsikan bayar pas)
            
            // Di aplikasi nyata, perlu menangani:
            // - Input jumlah uang yang diterima dari customer
            // - Perhitungan kembalian jika bayar lebih
            // - Validasi bahwa uang yang diterima cukup
        ];
    }
}
?>