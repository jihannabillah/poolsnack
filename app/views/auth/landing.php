<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta tags untuk character encoding dan responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pool Snack System - Scan QR</title> <!-- Judul halaman landing page -->
    
    <!-- Load external CSS libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Inline CSS styling untuk halaman landing page -->
    <style>
        /* CSS Variables untuk warna tema */
        :root {
            --primary-color: #667eea;      /* Warna ungu primer */
            --secondary-color: #764ba2;    /* Warna ungu gelap sekunder */
            --accent-color: #ffd700;       /* Warna emas untuk aksen premium */
        }

        /* Base styling untuk body */
        body {
            font-family: 'Poppins', sans-serif; /* Font Poppins untuk typography */
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Hilangkan horizontal scroll */
            background-color: #1a1a2e; /* Warna background fallback */
        }

        /* Hero section utama */
        .landing-hero {
            position: relative;
            min-height: 100vh; /* Tinggi minimal 100% viewport height */
            display: flex;
            align-items: center;
            /* Background image billiard dengan efek gelap */
            background: url('https://images.unsplash.com/photo-1574514807758-a517173e35a1?q=80&w=1920&auto=format&fit=crop') no-repeat center center/cover;
        }

        /* Overlay ungu gelap di atas background image */
        .landing-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Gradient overlay untuk meningkatkan readability teks */
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.85) 0%, rgba(118, 75, 162, 0.95) 100%);
            z-index: 1; /* Di atas background image */
        }

        /* Wrapper untuk konten agar berada di atas overlay */
        .content-wrapper {
            position: relative;
            z-index: 2; /* Di atas overlay */
            width: 100%;
        }

        /* ========== TYPOGRAPHY ========== */
        .hero-title {
            font-weight: 700;
            font-size: 3.5rem;
            text-shadow: 0 4px 10px rgba(0,0,0,0.3); /* Shadow untuk kontras */
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-weight: 300;
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2.5rem;
            max-width: 90%; /* Batas lebar untuk readability */
        }

        /* ========== GLASSMORPHISM CARD ========== */
        /* Kartu dengan efek glassmorphism (kaca buram) */
        .glass-card {
            background: rgba(255, 255, 255, 0.1); /* Background semi-transparan */
            backdrop-filter: blur(15px); /* Efek blur glassmorphism */
            -webkit-backdrop-filter: blur(15px); /* Safari support */
            border: 1px solid rgba(255, 255, 255, 0.2); /* Border tipis transparan */
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); /* Shadow depth */
            color: white; /* Teks putih */
            transition: transform 0.3s ease; /* Animasi hover */
        }

        /* Efek hover pada glass card */
        .glass-card:hover {
            transform: translateY(-5px); /* Naik sedikit saat hover */
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); /* Shadow lebih besar */
        }

        /* Frame untuk QR code */
        .qr-frame {
            background: white; /* Background putih untuk kontras QR */
            padding: 15px;
            border-radius: 15px;
            display: inline-block;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); /* Shadow ringan */
        }

        /* ========== BUTTON STYLES ========== */
        /* Button primary dengan gradient emas */
        .btn-custom-primary {
            background: linear-gradient(45deg, #FFD700, #FFA500); /* Gradient gold */
            border: none;
            color: #333; /* Teks gelap untuk kontras */
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px; /* Fully rounded */
            box-shadow: 0 5px 15px rgba(255, 165, 0, 0.4); /* Shadow orange */
            transition: all 0.3s ease;
        }

        /* Efek hover button primary */
        .btn-custom-primary:hover {
            transform: translateY(-2px); /* Naik sedikit */
            box-shadow: 0 8px 20px rgba(255, 165, 0, 0.6); /* Shadow lebih besar */
            color: #000; /* Teks lebih gelap */
        }

        /* Button outline dengan border transparan */
        .btn-custom-outline {
            background: transparent; /* Background transparan */
            border: 2px solid rgba(255,255,255,0.6); /* Border putih transparan */
            color: white;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 50px; /* Fully rounded */
            transition: all 0.3s ease;
        }

        /* Efek hover button outline */
        .btn-custom-outline:hover {
            background: white; /* Background putih saat hover */
            color: var(--secondary-color); /* Teks warna sekunder */
            border-color: white; /* Border putih solid */
        }
        
        /* ========== FLOATING ANIMATION ========== */
        /* Icons yang mengambang untuk efek visual */
        .floating-icon {
            position: absolute;
            opacity: 0.1; /* Sangat transparan */
            z-index: 1; /* Di bawah konten utama */
            animation: float 6s ease-in-out infinite; /* Animasi mengambang */
        }
        
        /* Keyframes untuk animasi floating */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); } /* Naik */
            100% { transform: translateY(0px); } /* Turun kembali */
        }
    </style>
</head>
<body>
    <!-- Hero section utama -->
    <div class="landing-hero">
        
        <!-- Floating icons untuk efek visual -->
        <i class="fas fa-cookie-bite fa-4x floating-icon text-white" style="top: 10%; left: 10%;"></i>
        <i class="fas fa-glass-martini-alt fa-4x floating-icon text-white" style="bottom: 15%; right: 10%; animation-delay: 1s;"></i>
        
        <!-- Container utama untuk konten -->
        <div class="container content-wrapper">
            <div class="row align-items-center justify-content-center">
                
                <!-- Kolom kiri: Konten teks dan fitur -->
                <div class="col-lg-6 text-white mb-5 mb-lg-0 pe-lg-5">
                    <!-- Badge "Premium Service" -->
                    <span class="badge bg-white text-dark mb-3 px-3 py-2 rounded-pill fw-bold text-uppercase">
                        <i class="fas fa-star text-warning me-1"></i> Premium Service
                    </span>
                    
                    <!-- Judul utama -->
                    <h1 class="hero-title">Pool Snack System</h1>
                    
                    <!-- Subtitle deskriptif -->
                    <p class="hero-subtitle">
                        Nikmati permainan billiard Anda tanpa gangguan. 
                        Pesan snack & minuman dingin langsung dari meja Anda hanya dengan satu sentuhan.
                    </p>
                    
                    <!-- Button aksi untuk login dan daftar -->
                    <div class="d-flex gap-3">
                        <a href="<?= APP_URL ?>/login" class="btn btn-custom-outline btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Login Member
                        </a>
                        <a href="<?= APP_URL ?>/register" class="btn btn-custom-outline btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </a>
                    </div>

                    <!-- Statistik/features dalam bentuk angka -->
                    <div class="mt-5 d-flex gap-4 opacity-75">
                        <div>
                            <h4 class="fw-bold mb-0">15+</h4>
                            <small>Menu Snack</small>
                        </div>
                        <div class="vr bg-white"></div> <!-- Vertical divider -->
                        <div>
                            <h4 class="fw-bold mb-0">24/7</h4>
                            <small>Layanan</small>
                        </div>
                        <div class="vr bg-white"></div>
                        <div>
                            <h4 class="fw-bold mb-0">QRIS</h4>
                            <small>Cashless</small>
                        </div>
                    </div>
                </div>

                <!-- Kolom kanan: QR code dan call-to-action -->
                <div class="col-lg-5 offset-lg-1">
                    <div class="glass-card text-center">
                        <!-- Header QR section -->
                        <div class="mb-4">
                            <i class="fas fa-qrcode fa-3x text-warning mb-2"></i>
                            <h3 class="fw-bold">Pesan Sekarang</h3>
                            <p class="small text-white-50">Scan QR Code di meja Anda</p>
                        </div>

                        <!-- QR code frame -->
                        <div class="qr-frame">
                            <!-- QR code image dengan fallback jika error -->
                            <img src="<?= APP_URL ?>/assets/images/qr/qr-general.png" 
                                 alt="QR Code" class="img-fluid" 
                                 style="max-width: 200px; height: auto;"
                                 onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= APP_URL ?>/login'">
                        </div>

                        <!-- Instruksi penggunaan QR -->
                        <p class="mt-3 mb-4 text-white-50 small">
                            Arahkan kamera HP Anda ke kode di atas untuk melihat menu digital.
                        </p>

                        <!-- Call-to-action button utama -->
                        <a href="<?= APP_URL ?>/login" class="btn btn-custom-primary w-100 py-3 text-uppercase letter-spacing-1">
                            Mulai Pesan <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Load Bootstrap JavaScript untuk komponen interaktif -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>