<?php
/**
 * Class UploadService - Service untuk menangani upload file di aplikasi
 * Mengelola upload gambar menu dan bukti pembayaran dengan validasi dan penyimpanan yang aman
 */
class UploadService {
    // Properti untuk menyimpan path/direktori upload
    private $uploadDir;
    
    /**
     * Constructor - Menginisialisasi direktori upload
     * Membuat folder-folder yang diperlukan jika belum ada
     */
    public function __construct() {
        // Set path utama untuk upload (sesuaikan dengan struktur project)
        $this->uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/pool-snack-system/public/uploads/';
        
        // Buat direktori jika belum ada:
        // 1. Folder untuk bukti pembayaran
        if (!is_dir($this->uploadDir . 'bukti-bayar')) {
            // mkdir dengan parameter true untuk membuat parent directory jika belum ada
            // Permission 0777: read, write, execute untuk semua user (bisa disesuaikan untuk production)
            mkdir($this->uploadDir . 'bukti-bayar', 0777, true);
        }
        
        // 2. Folder untuk gambar menu
        if (!is_dir($this->uploadDir . 'menu-images')) {
            mkdir($this->uploadDir . 'menu-images', 0777, true);
        }
    }

    /**
     * Method untuk upload gambar menu
     * Digunakan saat admin menambah/mengedit menu dengan gambar
     * 
     * @param array $file Data file dari $_FILES (misal: $_FILES['gambar'])
     * @return string Nama file yang berhasil diupload (untuk disimpan di database)
     * @throws Exception Jika terjadi error dalam proses upload
     */
    public function uploadMenuImage($file) {
        // 1. DEFINE VALIDASI: Tipe file yang diperbolehkan
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        
        // 2. DEFINE VALIDASI: Ukuran maksimal file (2MB)
        $maxSize = 2 * 1024 * 1024; // 2MB

        // 3. VALIDASI: Cek apakah ada error saat upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            // UPLOAD_ERR_OK = 0 (tidak ada error)
            throw new Exception('Error uploading file');
        }

        // 4. VALIDASI: Cek tipe MIME file
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('File type not allowed. Only JPG, PNG allowed');
        }

        // 5. VALIDASI: Cek ukuran file
        if ($file['size'] > $maxSize) {
            throw new Exception('File too large. Max 2MB allowed');
        }

        // 6. GENERATE NAMA FILE UNIK untuk mencegah overwrite dan keamanan
        // Ambil extension file asli
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        // Buat nama file unik: menu_timestamp_uniqid.extension
        // Contoh: menu_1645456789_abc123def.jpg
        $filename = 'menu_' . time() . '_' . uniqid() . '.' . $extension;
        
        // 7. Tentukan path tujuan penyimpanan
        $destination = $this->uploadDir . 'menu-images/' . $filename;

        // 8. Pindahkan file dari temporary location ke folder tujuan
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Jika berhasil, kembalikan nama file untuk disimpan di database
            return $filename;
        }

        // 9. Jika gagal memindahkan file
        throw new Exception('Failed to save file');
    }

    /**
     * Method untuk upload bukti pembayaran
     * Digunakan saat customer upload bukti bayar untuk metode QRIS/transfer
     * 
     * @param array $file Data file dari $_FILES (misal: $_FILES['payment_proof'])
     * @return string Nama file yang berhasil diupload
     * @throws Exception Jika terjadi error dalam proses upload
     */
    public function uploadPaymentProof($file) {
        // VALIDASI: Sama seperti uploadMenuImage
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        // Cek error upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error uploading file');
        }

        // Cek tipe file
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('File type not allowed. Only JPG, PNG allowed');
        }

        // Cek ukuran file
        if ($file['size'] > $maxSize) {
            throw new Exception('File too large. Max 2MB allowed');
        }

        // GENERATE NAMA FILE UNIK untuk bukti pembayaran
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        // Format: payment_timestamp_uniqid.extension
        $filename = 'payment_' . time() . '_' . uniqid() . '.' . $extension;
        
        // Path tujuan: folder bukti-bayar
        $destination = $this->uploadDir . 'bukti-bayar/' . $filename;

        // Pindahkan file
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }

        throw new Exception('Failed to save file');
    }
}
?>