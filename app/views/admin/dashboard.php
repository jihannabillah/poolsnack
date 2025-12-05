<?php
// app/views/admin/dashboard.php
// File view/template untuk halaman dashboard administrator
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pool Snack</title> <!-- Judul halaman -->
    
    <!-- Load external CSS libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Load custom CSS file -->
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <!-- Inline CSS styling khusus untuk halaman ini -->
    <style>
        /* Base styling */
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f4f6f9; 
        }
        
        /* Warna theme khusus untuk admin */
        .admin-theme .navbar { 
            background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%) !important; 
        }
        
        /* --- STATS CARD PREMIUM (Kartu statistik) --- */
        .stat-card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transition: transform 0.2s ease; /* Animasi hover */
            color: white; /* Warna teks putih kontras */
            padding: 1.5rem;
        }
        .stat-card-custom:hover { 
            transform: translateY(-3px); /* Efek naik saat hover */
            box-shadow: 0 8px 20px rgba(0,0,0,0.25); 
        }

        /* Warna gradient khusus untuk setiap jenis statistik */
        .stat-blue { background: linear-gradient(45deg, #3498DB, #2980B9); } /* Total Menu - Biru */
        .stat-green { background: linear-gradient(45deg, #2ECC71, #27AE60); } /* Tersedia - Hijau */
        .stat-yellow { background: linear-gradient(45deg, #F39C12, #F1C40F); } /* Makanan - Kuning */
        .stat-cyan { background: linear-gradient(45deg, #00BCD4, #00ACC1); } /* Minuman - Cyan */
        
        /* --- QUICK ACTION BUTTONS (Tombol aksi cepat) --- */
        .btn-action-custom {
            border: none;
            border-radius: 10px;
            padding: 1.5rem;
            color: white;
            font-weight: 600;
            transition: transform 0.2s ease;
            text-decoration: none; /* Hilangkan underline link */
            display: block; /* Agar link action jadi kotak penuh */
            text-align: center;
        }
        .btn-action-custom:hover {
            transform: translateY(-2px); /* Efek naik saat hover */
            color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Warna tombol action */
        .action-dark { background: #34495E; } /* Kelola Menu - Dark blue */
        .action-success { background: #2ECC71; } /* View Kasir - Green */
        .action-info { background: #1ABC9C; } /* Kelola User - Teal */
        .action-warning { background: #F39C12; } /* Laporan - Orange */

        /* Styling header card */
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee; /* Garis pemisah */
            font-weight: 600; /* Teks bold */
        }
    </style>
</head>
<body class="admin-theme">
    <!-- Include navigation bar untuk admin -->
    <?php require_once APP_PATH . '/views/layouts/admin-nav.php'; ?>

    <!-- Main content container -->
    <div class="container-fluid mt-4">
        <!-- Header section -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold">
                    <i class="fas fa-tachometer-alt me-2 text-dark"></i>Dashboard Admin
                </h2>
                <p class="text-muted">Overview sistem Pool Snack</p>
            </div>
        </div>

        <!-- Statistics cards section -->
        <div class="row mb-4">
            <!-- Card 1: Total Menu -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-custom stat-blue">
                    <div class="d-flex justify-content-between">
                        <div>
                            <!-- Menampilkan data total menu dari controller -->
                            <h4 class="mb-0 fw-bold"><?= $menuStats['total_menu'] ?? 0 ?></h4>
                            <p class="mb-0">Total Menu</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-utensils fa-2x"></i> <!-- Icon utensils -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card 2: Menu Tersedia -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-custom stat-green">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0 fw-bold"><?= $menuStats['menu_tersedia'] ?? 0 ?></h4>
                            <p class="mb-0">Menu Tersedia</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i> <!-- Icon check circle -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card 3: Menu Makanan -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-custom stat-yellow">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0 fw-bold"><?= $menuStats['total_makanan'] ?? 0 ?></h4>
                            <p class="mb-0">Menu Makanan</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hamburger fa-2x"></i> <!-- Icon hamburger -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card 4: Menu Minuman -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-custom stat-cyan">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0 fw-bold"><?= $menuStats['total_minuman'] ?? 0 ?></h4>
                                <p class="mb-0">Menu Minuman</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-coffee fa-2x"></i> <!-- Icon coffee -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content section dengan 2 kolom -->
        <div class="row">
            <!-- Kolom kiri: Quick Actions -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Action 1: Kelola Menu -->
                            <div class="col-md-6">
                                <a href="<?= APP_URL ?>/admin/menu" class="btn-action-custom action-dark">
                                    <i class="fas fa-utensils fa-2x mb-2"></i><br>Kelola Menu
                                </a>
                            </div>
                            
                            <!-- Action 2: View Kasir -->
                            <div class="col-md-6">
                                <a href="<?= APP_URL ?>/kasir/dashboard" class="btn-action-custom action-success">
                                    <i class="fas fa-cash-register fa-2x mb-2"></i><br>View Kasir
                                </a>
                            </div>
                            
                            <!-- Action 3: Kelola User (Coming Soon) -->
                            <div class="col-md-6">
                                <button class="btn-action-custom action-info" disabled>
                                    <i class="fas fa-users fa-2x mb-2"></i><br>Kelola User (Coming Soon)
                                </button>
                            </div>
                            
                            <!-- Action 4: Laporan -->
                            <div class="col-md-6">
                                <a href="<?= APP_URL ?>/admin/reports" class="btn-action-custom action-warning">
                                    <i class="fas fa-chart-bar fa-2x mb-2"></i><br>Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom kanan: System Info -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>System Info</h5>
                    </div>
                    <div class="card-body">
                        <!-- Informasi sistem -->
                        <div class="mb-3">
                            <strong>Nama System:</strong> Pool Snack Order System<br>
                            <strong>Version:</strong> 1.0.0<br>
                            <strong>Last Update:</strong> <?= date('d/m/Y H:i') ?> <!-- Waktu saat ini -->
                        </div>
                        
                        <!-- Alert status sistem -->
                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-exclamation-circle me-1"></i>
                                System berjalan normal. Semua fitur tersedia.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>