<?php
/**
 * Class Router - Simple Router untuk aplikasi MVC
 * Menangani routing URL ke controller dan action yang sesuai
 */
class Router {
    // Array untuk menyimpan semua route yang terdaftar
    private $routes = [];

    /**
     * Menambahkan route baru ke dalam sistem routing
     * 
     * @param string $route URL pattern (contoh: '/login', '/customer/dashboard')
     * @param string $controller Nama controller class (contoh: 'LoginController')
     * @param string $action Nama method dalam controller (contoh: 'index')
     * @return void
     */
    public function addRoute($route, $controller, $action) {
        // Simpan route dengan controller dan action yang sesuai
        $this->routes[$route] = [
            'controller' => $controller, 
            'action' => $action
        ];
        
        // Debug: Log route yang ditambahkan
        error_log("Route added: $route -> $controller::$action");
    }

    /**
     * Method utama untuk menangani routing request
     * Mencocokkan URL dengan route yang tersedia dan menjalankan controller
     * 
     * @param string|null $url URL yang akan di-routing (default: ambil dari REQUEST_URI)
     * @return void
     */
    public function dispatch($url = null) {
        // ========================================================
        // 1. AMBIL URL SAAT INI
        // ========================================================
        // Jika tidak ada parameter $url, ambil dari REQUEST_URI server
        $url = $url ?? $_SERVER['REQUEST_URI'];
        
        // Debug: Log URL awal
        error_log("Original URL: " . $url);

        // ========================================================
        // 2. DETEKSI FOLDER PROJECT SECARA OTOMATIS
        // ========================================================
        // Mendapatkan base path dari script yang sedang dijalankan
        // Contoh: /pool-snack-system/public/index.php -> /pool-snack-system/public
        $scriptName = dirname($_SERVER['SCRIPT_NAME']); 
        
        // Normalisasi slash: ubah backslash Windows ('\') menjadi forward slash ('/')
        $scriptName = str_replace('\\', '/', $scriptName);
        
        // Debug: Log script name
        error_log("Script name (base path): " . $scriptName);
        
        // Jika URL mengandung base path, hapus bagian tersebut
        // Contoh: /pool-snack-system/public/login -> /login
        if (strpos($url, $scriptName) === 0) {
            $url = substr($url, strlen($scriptName));
            error_log("URL after removing base path: " . $url);
        }

        // ========================================================
        // 3. BERSIHKAN URL DAN NORMALISASI
        // ========================================================
        // Hapus query string jika ada (misal: ?id=1&status=active)
        $url = strtok($url, '?');
        error_log("URL after removing query string: " . $url);
        
        // Normalisasi URL:
        // 1. Hapus slash di awal dan akhir: '/login/' -> 'login'
        // 2. Tambahkan satu slash di depan: 'login' -> '/login'
        $url = '/' . trim($url, '/');
        
        // Debug: Log URL final yang akan dicocokkan
        error_log("Final URL to match: " . $url);

        // ========================================================
        // 4. CARI ROUTE YANG COCOK DARI YANG TELAH DITAMBAHKAN
        // ========================================================
        foreach ($this->routes as $route => $params) {
            // Cocokkan URL dengan route (exact match)
            if ($route === $url) {
                $controllerName = $params['controller'];
                $actionName = $params['action'];
                
                error_log("Route matched: $controllerName -> $actionName");
                
                // ========================================================
                // 5. LOAD CONTROLLER FILE
                // ========================================================
                // Bangun path lengkap ke file controller
                // APP_PATH harus didefinisikan di index.php (contoh: define('APP_PATH', __DIR__ . '/app'))
                $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';
                
                // Validasi: cek apakah file controller ada
                if (!file_exists($controllerFile)) {
                    error_log("Controller file not found: $controllerFile");
                    $this->show404();
                    return;
                }
                
                // Include file controller
                require_once $controllerFile;
                
                // ========================================================
                // 6. VALIDASI CLASS CONTROLLER
                // ========================================================
                // Cek apakah class controller ada dalam file
                if (!class_exists($controllerName)) {
                    error_log("Controller class not found: $controllerName");
                    $this->show404();
                    return;
                }
                
                // ========================================================
                // 7. INSTANSIASI CONTROLLER DAN JALANKAN ACTION
                // ========================================================
                $controllerInstance = new $controllerName();
                
                // Validasi: cek apakah method action ada
                if (!method_exists($controllerInstance, $actionName)) {
                    error_log("Action method not found: $actionName");
                    $this->show404();
                    return;
                }
                
                // Debug: Log sebelum menjalankan action
                error_log("Executing: $controllerName::$actionName()");
                
                // Jalankan method action controller
                $controllerInstance->$actionName();
                
                // Keluar setelah berhasil menjalankan controller
                return;
            }
        }
        
        // ========================================================
        // 8. JIKA TIDAK ADA ROUTE YANG COCOK
        // ========================================================
        // Ini adalah fallback jika URL tidak cocok dengan route manapun
        error_log("No route found for: $url");
        $this->show404();
    }
    
    /**
     * Menampilkan halaman 404 - Page Not Found
     * @return void
     */
    private function show404() {
        // Set HTTP response code ke 404
        http_response_code(404);
        
        // Tampilkan pesan error 404
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The requested URL was not found on this server.</p>";
        
        // Link kembali ke halaman utama
        // Catatan: Untuk fleksibilitas lebih baik, bisa gunakan APP_URL
        echo "<a href='/pool-snack-system/public/'>Go to Home</a>"; 
        
        // Hentikan eksekusi script
        exit;
    }

    /**
     * Mendapatkan semua route yang telah terdaftar
     * Berguna untuk debugging atau keperluan development
     * 
     * @return array Array semua route yang terdaftar
     */
    public function getRoutes() {
        return $this->routes;
    }
}
?>