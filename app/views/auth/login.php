<?php
// app/views/auth/login.php
// File view/template untuk halaman login

// Ambil error message dari session jika ada
$error = $_SESSION['login_error'] ?? null;
// Hapus error dari session setelah ditampilkan (agar tidak muncul lagi)
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta tags untuk character encoding dan responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pool Snack System</title> <!-- Judul halaman login -->
    
    <!-- Load external CSS libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Inline CSS styling untuk halaman login -->
    <style>
        /* Base styling untuk body */
        body {
            font-family: 'Poppins', sans-serif; /* Font Poppins */
            margin: 0;
            padding: 0;
            min-height: 100vh; /* Minimal tinggi 100% viewport */
            display: flex;
            align-items: center; /* Vertikal center */
            justify-content: center; /* Horizontal center */
            /* Background Image dengan Overlay Ungu (konsisten dengan landing page) */
            background: url('https://images.unsplash.com/photo-1574514807758-a517173e35a1?q=80&w=1920&auto=format&fit=crop') no-repeat center center/cover;
        }

        /* Overlay Gradient Ungu di atas background image */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            /* Gradient overlay untuk meningkatkan readability */
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.85) 0%, rgba(118, 75, 162, 0.95) 100%);
            z-index: -1; /* Di belakang konten */
        }

        /* Kartu login dengan efek glassmorphism */
        .login-card {
            background: rgba(255, 255, 255, 0.1); /* Background semi-transparan */
            backdrop-filter: blur(15px); /* Efek blur glassmorphism */
            -webkit-backdrop-filter: blur(15px); /* Safari support */
            border: 1px solid rgba(255, 255, 255, 0.2); /* Border tipis transparan */
            border-radius: 24px; /* Rounded corners */
            padding: 3rem 2.5rem; /* Padding dalam kartu */
            width: 100%;
            max-width: 450px; /* Lebar maksimal kartu */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); /* Shadow depth */
            color: white; /* Teks putih */
            position: relative;
        }

        /* Header section dalam kartu login */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h3 {
            font-weight: 700;
            letter-spacing: 1px; /* Spasi huruf untuk estetika */
            margin-bottom: 0.5rem;
        }

        .login-header p {
            font-weight: 300;
            opacity: 0.8; /* Sedikit transparan */
            font-size: 0.9rem;
        }

        /* ========== FORM INPUT STYLING ========== */
        .form-control {
            background: rgba(255, 255, 255, 0.1); /* Background transparan */
            border: 1px solid rgba(255, 255, 255, 0.2); /* Border transparan */
            color: white; /* Teks putih */
            padding: 12px 15px;
            border-radius: 12px;
            font-size: 0.95rem;
        }

        /* Placeholder styling */
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6); /* Placeholder semi-transparan */
        }

        /* Focus state untuk input */
        .form-control:focus {
            background: rgba(255, 255, 255, 0.2); /* Background lebih terang saat focus */
            border-color: rgba(255, 255, 255, 0.5); /* Border lebih terang */
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1); /* Glow effect */
            color: white;
        }

        /* Input group icon */
        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-right: none; /* Hilangkan border kanan untuk menyatu dengan input */
            color: white;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        /* ========== BUTTON STYLING ========== */
        /* Tombol primary dengan gradient emas */
        .btn-custom-primary {
            background: linear-gradient(45deg, #FFD700, #FFA500); /* Gradient emas */
            border: none;
            color: #333; /* Teks gelap untuk kontras */
            font-weight: 600;
            padding: 12px;
            border-radius: 50px; /* Fully rounded */
            width: 100%; /* Lebar penuh */
            margin-top: 1rem;
            box-shadow: 0 5px 15px rgba(255, 165, 0, 0.3); /* Shadow orange */
            transition: all 0.3s ease;
        }

        /* Efek hover tombol primary */
        .btn-custom-primary:hover {
            transform: translateY(-2px); /* Naik sedikit */
            box-shadow: 0 8px 20px rgba(255, 165, 0, 0.5); /* Shadow lebih besar */
            color: #000; /* Teks lebih gelap */
        }

        /* ========== DEMO BUTTONS ========== */
        /* Tombol untuk demo login cepat */
        .role-btn {
            background: rgba(255, 255, 255, 0.1); /* Background transparan */
            border: 1px solid rgba(255, 255, 255, 0.2); /* Border transparan */
            color: white;
            border-radius: 12px;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        /* Efek hover tombol demo */
        .role-btn:hover {
            background: rgba(255, 255, 255, 0.25); /* Background lebih terang */
            color: white;
        }

        /* ========== LINK STYLING ========== */
        .text-link {
            color: #FFD700; /* Warna emas untuk link */
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        .text-link:hover { 
            color: #FFA500; /* Warna orange saat hover */
        }
        
        /* ========== CHECKBOX STYLING ========== */
        .form-check-input:checked {
            background-color: #FFD700; /* Warna emas saat checked */
            border-color: #FFD700;
        }
    </style>
</head>
<body>

    <!-- Kartu login utama -->
    <div class="login-card">
        <!-- Header section -->
        <div class="login-header">
            <!-- Icon utensils dengan warna emas -->
            <i class="fas fa-utensils fa-3x mb-3" style="color: #FFD700;"></i>
            <h3>Pool Snack System</h3> <!-- Judul aplikasi -->
            <p>Silakan login untuk mulai memesan</p> <!-- Subtitle -->
        </div>

        <!-- Alert error jika login gagal -->
        <?php if ($error): ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert" style="background: rgba(220, 53, 69, 0.9); border: none; color: white;">
            <i class="fas fa-exclamation-circle me-2"></i> <!-- Icon error -->
            <div><?= $error ?></div> <!-- Pesan error dari session -->
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Form login -->
        <form action="<?= APP_URL ?>/login" method="POST">
            <!-- Input email -->
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span> <!-- Icon envelope -->
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                </div>
            </div>

            <!-- Input password -->
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span> <!-- Icon lock -->
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
            </div>

            <!-- Remember me checkbox -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label text-white-50" for="remember" style="font-size: 0.9rem;">
                        Ingat Saya
                    </label>
                </div>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-custom-primary">
                LOGIN <i class="fas fa-sign-in-alt ms-2"></i> <!-- Icon sign-in -->
            </button>

            <!-- Link ke halaman register -->
            <div class="text-center mt-4">
                <p class="small text-white-50 mb-0">Belum punya akun?</p>
                <a href="<?= APP_URL ?>/register" class="text-link small">Daftar Member Baru</a>
            </div>
        </form>

        <!-- Section untuk demo login cepat -->
        <div class="mt-4 pt-3 border-top border-light border-opacity-25">
            <p class="text-center small text-white-50 mb-2">Login Cepat (Demo)</p>
            <div class="row g-2">
                <!-- Demo button untuk admin -->
                <div class="col-6">
                    <button class="btn role-btn w-100" onclick="fillDemo('admin@poolsnack.com', 'password')">
                        <i class="fas fa-user-shield me-1"></i> <!-- Icon shield -->
                        Admin
                    </button>
                </div>
                <!-- Demo button untuk kasir -->
                <div class="col-6">
                    <button class="btn role-btn w-100" onclick="fillDemo('kasir@poolsnack.com', 'password')">
                        <i class="fas fa-cash-register me-1"></i> <!-- Icon cash register -->
                        Kasir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Load Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript untuk fungsi demo login -->
    <script>
        // Fungsi untuk mengisi form dengan data demo dan auto-submit
        function fillDemo(email, password) {
            // Isi field email dan password
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            
            // Auto-submit form setelah 300ms (memberi sedikit delay untuk UX)
            setTimeout(() => document.querySelector('form').submit(), 300);
        }
    </script>
</body>
</html>