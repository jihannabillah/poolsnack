<?php
// app/views/auth/register.php
// File view/template untuk halaman pendaftaran/registrasi user baru

// Ambil error dan success messages dari session jika ada
$error = $_SESSION['register_error'] ?? null;      // Error message dari controller
$success = $_SESSION['register_success'] ?? null;  // Success message dari controller

// Hapus messages dari session setelah ditampilkan (prevent re-display)
unset($_SESSION['register_error']);
unset($_SESSION['register_success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta tags untuk character encoding dan responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Pool Snack System</title> <!-- Judul halaman register -->
    
    <!-- Load external CSS libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Inline CSS styling untuk halaman register -->
    <style>
        /* Base styling untuk body */
        body {
            font-family: 'Poppins', sans-serif; /* Font Poppins */
            /* Background image sama dengan login page untuk konsistensi */
            background: url('https://images.unsplash.com/photo-1574514807758-a517173e35a1?q=80&w=1920&auto=format&fit=crop') no-repeat center center/cover;
            min-height: 100vh; /* Minimal tinggi 100% viewport */
            display: flex;
            align-items: center; /* Vertikal center */
            justify-content: center; /* Horizontal center */
            padding: 2rem 0; /* Padding atas-bawah */
        }

        /* Overlay gradient di atas background image */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            /* Gradient overlay ungu untuk meningkatkan readability */
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.95) 100%);
            z-index: -1; /* Di belakang konten */
            position: fixed; /* Tetap saat scroll */
        }

        /* Kartu registrasi dengan efek glassmorphism */
        .register-card {
            background: rgba(255, 255, 255, 0.1); /* Background semi-transparan */
            backdrop-filter: blur(15px); /* Efek blur glassmorphism */
            -webkit-backdrop-filter: blur(15px); /* Safari support */
            border: 1px solid rgba(255, 255, 255, 0.2); /* Border tipis transparan */
            border-radius: 20px; /* Rounded corners */
            padding: 2.5rem; /* Padding dalam kartu */
            width: 100%;
            max-width: 500px; /* Lebar maksimal lebih besar dari login */
            box-shadow: 0 20px 40px rgba(0,0,0,0.2); /* Shadow depth */
            color: white; /* Teks putih */
        }

        /* Header section dalam kartu registrasi */
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header i {
            color: #FFD700; /* Icon warna emas */
            margin-bottom: 10px;
        }

        /* ========== FORM INPUT STYLING ========== */
        .form-control {
            background: rgba(255, 255, 255, 0.1); /* Background transparan */
            border: 1px solid rgba(255, 255, 255, 0.2); /* Border transparan */
            color: white; /* Teks putih */
            padding: 12px 15px;
            border-radius: 10px;
        }

        /* Placeholder styling */
        .form-control::placeholder { 
            color: rgba(255, 255, 255, 0.6); /* Placeholder semi-transparan */
        }

        /* Focus state untuk input */
        .form-control:focus {
            background: rgba(255, 255, 255, 0.2); /* Background lebih terang saat focus */
            border-color: #FFD700; /* Border emas saat focus */
            box-shadow: none; /* Hilangkan default Bootstrap shadow */
            color: white;
        }

        /* Input group icon */
        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-right: none; /* Hilangkan border kanan untuk menyatu dengan input */
            color: white;
            border-radius: 10px 0 0 10px; /* Border radius kiri saja */
        }
        
        /* Fix border radius untuk input group (kanan saja) */
        .input-group .form-control { 
            border-radius: 0 10px 10px 0; /* Border radius kanan saja */
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
            transition: all 0.3s;
        }

        /* Efek hover tombol primary */
        .btn-custom-primary:hover {
            transform: translateY(-2px); /* Naik sedikit */
            color: #000; /* Teks lebih gelap */
            box-shadow: 0 8px 20px rgba(255, 165, 0, 0.5); /* Shadow lebih besar */
        }

        /* Tombol outline untuk link login */
        .btn-custom-outline {
            border: 1px solid rgba(255, 255, 255, 0.4); /* Border putih transparan */
            color: white;
            border-radius: 50px; /* Fully rounded */
            padding: 8px 20px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .btn-custom-outline:hover {
            background: white; /* Background putih saat hover */
            color: #764ba2; /* Teks warna ungu */
        }

        /* ========== PASSWORD STRENGTH BAR ========== */
        /* Bar untuk indikator kekuatan password */
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 8px;
            transition: all 0.3s ease; /* Animasi smooth */
            background-color: rgba(255,255,255,0.1); /* Default color */
        }
        
        /* ========== LINK STYLING ========== */
        .text-link { 
            color: #FFD700; /* Warna emas untuk link */
            text-decoration: none; 
            font-weight: 600; 
        }
        .text-link:hover { 
            color: #ffeb3b; /* Warna kuning lebih terang saat hover */
        }
        
        /* ========== CHECKBOX STYLING ========== */
        .form-check-input:checked {
            background-color: #FFD700; /* Warna emas saat checked */
            border-color: #FFD700;
        }
    </style>
</head>
<body>

    <!-- Kartu registrasi utama -->
    <div class="register-card">
        <!-- Header section -->
        <div class="register-header">
            <!-- Icon user-plus dengan warna emas -->
            <i class="fas fa-user-plus fa-3x"></i>
            <h3>Daftar Akun</h3> <!-- Judul -->
            <p class="text-white-50">Bergabung untuk kemudahan pemesanan</p> <!-- Subtitle -->
        </div>

        <!-- Alert error jika registrasi gagal -->
        <?php if ($error): ?>
        <div class="alert alert-danger d-flex align-items-center mb-4" style="background: rgba(220, 53, 69, 0.9); border: none; color: white;">
            <i class="fas fa-exclamation-circle me-2"></i> <!-- Icon error -->
            <div><?= $error ?></div> <!-- Pesan error dari session -->
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Form registrasi -->
        <form action="<?= APP_URL ?>/register" method="POST" id="registerForm">
            
            <!-- Input nama lengkap -->
            <div class="mb-3">
                <label class="form-label small ps-1">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span> <!-- Icon user -->
                    <input type="text" class="form-control" name="nama" placeholder="Masukkan nama lengkap" required 
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"> <!-- Preserve value jika error -->
                </div>
            </div>

            <!-- Input email -->
            <div class="mb-3">
                <label class="form-label small ps-1">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span> <!-- Icon envelope -->
                    <input type="email" class="form-control" name="email" placeholder="contoh@email.com" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"> <!-- Preserve value jika error -->
                </div>
            </div>

            <!-- Input password dengan strength indicator -->
            <div class="mb-3">
                <label class="form-label small ps-1">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span> <!-- Icon lock -->
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Minimal 6 karakter" required 
                           oninput="checkPasswordStrength(this.value)"> <!-- Trigger strength check -->
                    <!-- Tombol toggle show/hide password -->
                    <button type="button" class="btn btn-outline-light toggle-password" 
                            style="border-radius: 0 10px 10px 0; border-color: rgba(255,255,255,0.2);">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <!-- Bar indikator kekuatan password -->
                <div class="password-strength" id="passwordStrength"></div>
                <!-- Feedback text untuk kekuatan password -->
                <div class="form-text text-white-50 small mt-1" id="passwordFeedback"></div>
            </div>

            <!-- Input konfirmasi password -->
            <div class="mb-4">
                <label class="form-label small ps-1">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span> <!-- Icon lock -->
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                           placeholder="Ketik ulang password" required>
                </div>
                <!-- Feedback untuk konfirmasi password -->
                <div class="form-text text-white-50 small mt-1" id="confirmFeedback"></div>
            </div>

            <!-- Checkbox terms and conditions -->
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="agree_terms" required>
                <label class="form-check-label small" for="agree_terms">
                    Saya menyetujui <a href="#" class="text-link">Syarat & Ketentuan</a>
                </label>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-custom-primary" id="submitBtn">
                Daftar Sekarang <i class="fas fa-arrow-right ms-1"></i> <!-- Icon arrow -->
            </button>

            <!-- Link ke halaman login -->
            <div class="text-center mt-4">
                <p class="small mb-2">Sudah punya akun?</p>
                <a href="<?= APP_URL ?>/login" class="btn btn-custom-outline btn-sm">
                    Login di sini
                </a>
            </div>
        </form>
    </div>

    <!-- Load Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript untuk halaman register -->
    <script>
        // ========== TOGGLE PASSWORD VISIBILITY ==========
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text'; // Show password
                    icon.classList.replace('fa-eye', 'fa-eye-slash'); // Change icon
                } else {
                    input.type = 'password'; // Hide password
                    icon.classList.replace('fa-eye-slash', 'fa-eye'); // Change icon back
                }
            });
        });

        // ========== PASSWORD STRENGTH CHECKER ==========
        function checkPasswordStrength(password) {
            const bar = document.getElementById('passwordStrength');
            const feed = document.getElementById('passwordFeedback');
            let strength = 0;
            
            // Kriteria kekuatan password:
            if (password.length >= 6) strength++; // Panjang minimal 6
            if (password.length >= 8) strength++; // Panjang minimal 8 (lebih kuat)
            if (/[A-Z]/.test(password)) strength++; // Mengandung huruf besar
            if (/[0-9]/.test(password)) strength++; // Mengandung angka
            
            // Hitung width bar (0-100%)
            let width = (strength * 25) + '%';
            
            // Tentukan warna dan teks berdasarkan strength
            let color = 'red';     // Default: lemah
            let text = 'Lemah';

            if(strength > 2) { 
                color = 'orange';  // Cukup
                text = 'Cukup'; 
            }
            if(strength > 3) { 
                color = '#00ff88'; // Kuat (hijau)
                text = 'Kuat'; 
            }

            // Update visual
            bar.style.width = width;
            bar.style.backgroundColor = color;
            feed.innerText = text;
            feed.style.color = color;
        }

        // ========== CONFIRM PASSWORD MATCH ==========
        document.getElementById('confirm_password').addEventListener('input', function() {
            const pass = document.getElementById('password').value;
            const feed = document.getElementById('confirmFeedback');
            
            // Cek apakah password cocok
            if(this.value === pass && pass !== '') {
                feed.innerText = '✓ Password Cocok';
                feed.style.color = '#00ff88'; // Hijau
            } else {
                feed.innerText = '✗ Belum Cocok';
                feed.style.color = 'red'; // Merah
            }
        });

        // ========== FORM SUBMIT VALIDATION ==========
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const pass = document.getElementById('password').value;
            const conf = document.getElementById('confirm_password').value;
            
            // Validasi: password harus cocok
            if(pass !== conf) {
                e.preventDefault(); // Hentikan submit
                alert('Password tidak cocok!'); // Tampilkan alert
            } else {
                // Jika valid, tampilkan loading state
                const btn = document.getElementById('submitBtn');
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mendaftarkan...';
                btn.disabled = true; // Disable button selama proses
            }
        });
    </script>
</body>
</html>