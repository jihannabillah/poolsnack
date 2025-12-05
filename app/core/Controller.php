<?php
// app/core/Controller.php

/**
 * Base Controller class yang menjadi parent untuk semua controller lainnya
 * Berisi method-method umum yang sering digunakan oleh controller
 */
class Controller {
    // Properti untuk menyimpan koneksi database
    protected $db;
    
    /**
     * Constructor - dijalankan saat setiap child controller diinstansiasi
     * Menginisialisasi koneksi database yang bisa digunakan oleh semua child controller
     */
    public function __construct() {
        // Membuat instance Database dan mengambil koneksinya
        $this->db = (new Database())->getConnection();
    }
    
    /**
     * Method untuk memuat view/tampilan
     * 
     * @param string $view Nama file view (tanpa ekstensi .php)
     * @param array $data Data yang akan dikirim ke view
     * @return void
     * 
     * Contoh penggunaan di child controller:
     * $this->render('customer/dashboard', ['menus' => $menus, 'user' => $user]);
     */
    protected function render($view, $data = []) {
        // extract() mengubah array menjadi variabel-variabel
        // Contoh: ['name' => 'John'] menjadi $name = 'John'
        extract($data);
        
        // Memuat file view dari folder app/views/
        require_once "../app/views/$view.php";
    }
    
    /**
     * Method untuk mengembalikan response dalam format JSON
     * Berguna untuk API endpoints atau AJAX responses
     * 
     * @param array $data Data yang akan diencode ke JSON
     * @return void
     * 
     * Contoh penggunaan:
     * $this->json(['success' => true, 'message' => 'Data berhasil disimpan']);
     */
    protected function json($data) {
        // Set header Content-Type sebagai application/json
        header('Content-Type: application/json');
        
        // Encode data array ke format JSON
        echo json_encode($data);
        
        // Hentikan eksekusi script setelah mengirim JSON
        exit();
    }
    
    /**
     * Method untuk melakukan redirect ke URL tertentu
     * 
     * @param string $url URL tujuan redirect
     * @return void
     * 
     * Contoh penggunaan:
     * $this->redirect(APP_URL . '/customer/dashboard');
     */
    protected function redirect($url) {
        // Mengirim header Location untuk redirect
        header("Location: $url");
        
        // Hentikan eksekusi script setelah redirect
        exit();
    }
}
?>