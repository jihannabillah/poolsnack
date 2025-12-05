<?php
// app/views/customer/select-table.php
if (!isset($availableTables)) {
    header('Location: ' . APP_URL . '/customer/dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Meja - Pool Snack</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            /* Background Ungu Mewah (Konsisten dengan Login/Landing) */
            background: url('https://images.unsplash.com/photo-1574514807758-a517173e35a1?q=80&w=1920&auto=format&fit=crop') no-repeat center center/cover;
            background-attachment: fixed;
            min-height: 100vh;
            color: white;
            padding-bottom: 100px;
        }

        /* Overlay Ungu */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.95) 100%);
            z-index: -1;
        }

        .page-header {
            text-align: center;
            padding: 2rem 0;
        }

        .glass-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        /* CARD MEJA (Glass Button Style) */
        .table-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 15px;
        }

        .table-item {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 1.5rem 0.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .table-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        /* STATE: SELECTED (Emas/Kuning agar kontras dengan Ungu) */
        .table-item.selected {
            background: linear-gradient(45deg, #FFD700, #FFA500);
            border-color: #FFD700;
            color: #333;
            transform: scale(1.05);
            box-shadow: 0 0 25px rgba(255, 215, 0, 0.6);
        }

        .table-item.selected .table-number {
            color: #000;
            font-weight: 800;
        }
        
        .table-item.selected .table-icon {
            color: #333;
        }

        .table-item.selected .table-status {
            background: rgba(0,0,0,0.1);
            color: #000;
        }

        .table-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .table-icon {
            font-size: 1.8rem;
            margin-bottom: 10px;
            opacity: 0.8;
        }

        .table-status {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: rgba(255,255,255,0.2);
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
        }

        /* Floating Bottom Bar (Glass Style) */
        .bottom-bar {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: all 0.4s ease;
            
            /* Hidden by default */
            opacity: 0; 
            visibility: hidden;
            bottom: -50px;
        }

        .bottom-bar.active {
            opacity: 1;
            visibility: visible;
            bottom: 30px;
        }

        .selected-info span {
            display: block;
            font-size: 0.75rem;
            color: #666;
            font-weight: 600;
        }
        .selected-info strong {
            font-size: 1.2rem;
            color: #764ba2;
            font-weight: 800;
        }

        .btn-confirm {
            background: linear-gradient(45deg, #764ba2, #667eea);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
            transition: all 0.3s;
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(118, 75, 162, 0.6);
            color: white;
        }
        
        /* Tombol Logout Pojok Kanan */
        .top-nav {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .btn-logout-glass {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .btn-logout-glass:hover {
            background: rgba(255,255,255,0.4);
            color: white;
        }
    </style>
</head>
<body>

    <div class="top-nav">
        <a href="<?= APP_URL ?>/logout" class="btn-logout-glass">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </div>

    <div class="container">
        <div class="page-header">
            <h2 class="fw-bold mb-1">Pilih Meja Billiard</h2>
            <p class="text-white-50">Silakan pilih meja untuk memulai pesanan</p>
        </div>

        <div class="glass-container">
            <div class="table-grid">
                <?php if (empty($availableTables)): ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-exclamation-circle fa-3x mb-3 text-white-50"></i>
                        <p class="text-white-50">Maaf, semua meja sedang penuh.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($availableTables as $table): ?>
                    <div class="table-item" 
                         onclick="selectTable(<?= $table['id'] ?>, '<?= $table['nomor_meja'] ?>')" 
                         id="table-<?= $table['id'] ?>">
                        
                        <div class="table-icon">
                            <i class="fas fa-circle-notch"></i>
                        </div>
                        <div class="table-number">#<?= $table['nomor_meja'] ?></div>
                        <div class="table-status">Kosong</div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bottom-bar" id="bottomAction">
        <div class="selected-info">
            <span>MEJA TERPILIH</span>
            <strong id="selectedDisplay"># -</strong>
        </div>
        
        <form action="<?= APP_URL ?>/customer/set-table" method="POST" id="tableForm" class="m-0">
            <input type="hidden" name="meja_id" id="hiddenMejaId">
            <input type="hidden" name="meja_nama" id="hiddenMejaNama">
            
            <button type="submit" class="btn-confirm">
                Lanjut Pesan <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </form>
    </div>

    <script>
        function selectTable(id, nomor) {
            // 1. Reset semua style
            document.querySelectorAll('.table-item').forEach(item => {
                item.classList.remove('selected');
                item.querySelector('.table-icon i').className = 'fas fa-circle-notch';
                item.querySelector('.table-status').innerText = 'Kosong';
            });

            // 2. Aktifkan meja yg dipilih
            const element = document.getElementById('table-' + id);
            if(element) {
                element.classList.add('selected');
                // Ubah ikon jadi ceklis
                element.querySelector('.table-icon i').className = 'fas fa-check-circle';
                element.querySelector('.table-status').innerText = 'Dipilih';
            }

            // 3. Update data di floating bar
            document.getElementById('selectedDisplay').innerText = '#' + nomor;
            document.getElementById('hiddenMejaId').value = id;
            document.getElementById('hiddenMejaNama').value = nomor;

            // 4. Tampilkan floating bar
            document.getElementById('bottomAction').classList.add('active');
        }
    </script>

</body>
</html>