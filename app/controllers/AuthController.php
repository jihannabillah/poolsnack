<?php
// app/controllers/AuthController.php
// ===========================================
// AUTHENTICATION CONTROLLER - POOL SNACK SYSTEM
// ===========================================
// Controller ini menangani autentikasi pengguna:
// - Landing page untuk guest
// - Login & Logout
// - Registrasi customer baru
// ===========================================

class AuthController {
    private $userModel;

    // ===========================================
    // CONSTRUCTOR - Initialize Controller
    // ===========================================
    public function __construct() {
        // ✅ Inisialisasi UserModel untuk operasi database terkait user
        // Asumsi: Model sudah di-autoload atau di-include di index.php
        $this->userModel = new UserModel();
        
        // ✅ Pastikan session sudah dimulai untuk semua method
        // Beberapa method membutuhkan $_SESSION
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ===========================================
    // METHOD: landing() - Landing Page
    // ===========================================
    public function landing() {
        // ✅ Cek apakah user sudah login
        // Jika sudah login, redirect ke dashboard sesuai role
        if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
            Auth::redirectBasedOnRole();
            exit;
        }
        
        // ✅ Validasi eksistensi file view
        $viewPath = APP_PATH . '/views/auth/landing.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // ✅ FALLBACK VIEW: Tampilkan halaman sederhana jika file belum dibuat
            // Berguna selama development
            echo "<!DOCTYPE html>
            <html lang='id'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Pool Snack System</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                    h1 { color: #2c3e50; }
                    .buttons { margin-top: 30px; }
                    .btn { display: inline-block; padding: 10px 20px; margin: 0 10px; 
                           background: #3498db; color: white; text-decoration: none; 
                           border-radius: 5px; }
                    .btn:hover { background: #2980b9; }
                </style>
            </head>
            <body>
                <h1>Selamat Datang di Pool Snack System</h1>
                <p>Sistem pemesanan snack untuk kolam renang Anda</p>
                <div class='buttons'>
                    <a href='" . APP_URL . "/login' class='btn'>Login</a>
                    <a href='" . APP_URL . "/register' class='btn'>Daftar</a>
                </div>
            </body>
            </html>";
        }
    }

    // ===========================================
    // METHOD: login() - Login Process
    // ===========================================
    public function login() {
        // ✅ Cek jika user sudah login (prevent re-login)
        if (isset($_SESSION['user_id'])) {
            Auth::redirectBasedOnRole();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ✅ SANITIZE INPUT: Bersihkan data dari form
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            
            // ✅ VALIDASI DASAR
            if (empty($email) || empty($password)) {
                $error = "Email dan password harus diisi!";
                require_once APP_PATH . '/views/auth/login.php';
                return;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format email tidak valid!";
                require_once APP_PATH . '/views/auth/login.php';
                return;
            }
            
            // ✅ AUTHENTICATION: Verifikasi user via model
            $user = $this->userModel->authenticate($email, $password);
            
            if ($user) {
                // ✅ SET SESSION DATA
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['login_time'] = time(); // Untuk session timeout
                
                // ✅ REGENERATE SESSION ID (prevent session fixation)
                session_regenerate_id(true);
                
                // ✅ LOG ACTIVITY (opsional, untuk audit trail)
                error_log("User login: " . $email . " | Role: " . $user['role']);
                
                // ✅ REDIRECT berdasarkan role user
                Auth::redirectBasedOnRole();
                exit;
            } else {
                // ✅ FAILED LOGIN: Tampilkan error
                $error = "Email atau password salah!";
                require_once APP_PATH . '/views/auth/login.php';
            }
        } else {
            // ✅ GET REQUEST: Tampilkan form login
            require_once APP_PATH . '/views/auth/login.php';
        }
    }

    // ===========================================
    // METHOD: register() - Customer Registration
    // ===========================================
    public function register() {
        // ✅ Cek jika user sudah login (prevent re-registration)
        if (isset($_SESSION['user_id'])) {
            Auth::redirectBasedOnRole();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ✅ SANITIZE & VALIDATE INPUT
            $nama = htmlspecialchars(trim($_POST['nama']));
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            // ✅ VALIDATION CHAIN
            $errors = [];
            
            // 1. Required fields
            if (empty($nama)) $errors[] = "Nama lengkap harus diisi!";
            if (empty($email)) $errors[] = "Email harus diisi!";
            if (empty($password)) $errors[] = "Password harus diisi!";
            
            // 2. Email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Format email tidak valid!";
            }
            
            // 3. Password strength
            if (strlen($password) < 6) {
                $errors[] = "Password minimal 6 karakter!";
            }
            
            // 4. Password confirmation
            if ($password !== $confirm_password) {
                $errors[] = "Password dan konfirmasi password tidak cocok!";
            }
            
            // 5. Check email uniqueness (jika tidak ada error sebelumnya)
            if (empty($errors)) {
                $existingUser = $this->userModel->findByEmail($email);
                if ($existingUser) {
                    $errors[] = "Email sudah terdaftar!";
                }
            }
            
            // ✅ PROCESS REGISTRATION jika tidak ada error
            if (empty($errors)) {
                $data = [
                    'nama' => $nama,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => 'customer' // Default role untuk registrasi
                ];
                
                // ✅ CREATE USER di database
                if ($this->userModel->create($data)) {
                    $_SESSION['register_success'] = "Pendaftaran berhasil! Silakan login.";
                    header('Location: ' . APP_URL . '/login');
                    exit;
                } else {
                    $errors[] = "Gagal membuat akun. Silakan coba lagi.";
                }
            }
            
            // ✅ SIMPAN ERROR KE SESSION untuk ditampilkan di form
            if (!empty($errors)) {
                $_SESSION['register_errors'] = $errors;
                header('Location: ' . APP_URL . '/register');
                exit;
            }
            
        } else {
            // ✅ GET REQUEST: Tampilkan form registrasi
            require_once APP_PATH . '/views/auth/register.php';
        }
    }

    // ===========================================
    // METHOD: logout() - Logout Process
    // ===========================================
    public function logout() {
        // ✅ LOG ACTIVITY sebelum menghapus session
        if (isset($_SESSION['email'])) {
            error_log("User logout: " . $_SESSION['email']);
        }
        
        // ✅ CLEAR SESSION DATA
        $_SESSION = [];
        
        // ✅ DELETE SESSION COOKIE dari browser
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }
        
        // ✅ DESTROY SERVER SESSION
        session_destroy();
        
        // ✅ REDIRECT ke login page dengan success message
        // Gunakan query parameter atau session flash untuk message
        header('Location: ' . APP_URL . '/login?logout=success');
        exit;
    }
}
?>