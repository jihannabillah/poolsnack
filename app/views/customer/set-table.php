<?php
// app/views/customer/set-table.php

// Pastikan model di-load jika belum ada (Fallback)
if (!isset($mejaModel)) {
    $mejaModel = new MejaModel();
}
if (!isset($availableTables)) {
    $availableTables = $mejaModel->getAvailableTables();
}

// Cek meja saat ini dari session
$currentTable = null;
if (isset($_SESSION['meja_id'])) {
    $currentTable = $mejaModel->getTableById($_SESSION['meja_id']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Meja Saya - Pool Snack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <style>
        .table-management {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 0;
        }
        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .current-table {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .table-item {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            background: white;
            cursor: pointer;
            margin-bottom: 1rem;
        }
        .table-item:hover {
            transform: translateY(-5px);
            border-color: #007bff;
            box-shadow: 0 5px 15px rgba(0,123,255,0.1);
        }
        .table-item.selected {
            border-color: #28a745;
            background: #f8fff9;
            transform: scale(1.05);
        }
        .table-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2C3E50;
        }
        .status-available {
            background: #d4edda;
            color: #155724;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body class="customer-theme">
    <?php require_once APP_PATH . '/views/layouts/customer-nav.php'; ?>

    <div class="table-management">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="table-card">
                        <div class="p-4 text-center">
                            <i class="fas fa-table fa-3x text-primary mb-3"></i>
                            <h2>Atur Meja Billiard Saya</h2>
                            <p class="text-muted">Pilih atau ubah meja untuk pemesanan Anda</p>
                        </div>

                        <?php if ($currentTable): ?>
                        <div class="current-table">
                            <div class="row align-items-center">
                                <div class="col-md-8 text-start">
                                    <h4><i class="fas fa-check-circle me-2"></i>Meja Saat Ini</h4>
                                    <p class="mb-0">Anda sedang menggunakan meja berikut</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="h1 mb-0">#<?= $currentTable['nomor_meja'] ?></div>
                                    <span class="badge bg-light text-success">Aktif</span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="p-4">
                            <h5 class="mb-3">
                                <i class="fas fa-list me-2"></i>
                                <?= $currentTable ? 'Ganti Meja' : 'Pilih Meja' ?>
                            </h5>
                            
                            <div class="row" id="tablesGrid">
                                <?php if (empty($availableTables)): ?>
                                    <div class="col-12">
                                        <div class="alert alert-warning text-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Semua meja sedang terpakai.
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($availableTables as $table): ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="table-item" 
                                             id="table-<?= $table['id'] ?>"
                                             onclick="selectTable(<?= $table['id'] ?>, '<?= $table['nomor_meja'] ?>')"
                                             <?= $currentTable && $currentTable['id'] == $table['id'] ? 'style="display:none"' : '' ?>>
                                            <div class="table-number">#<?= $table['nomor_meja'] ?></div>
                                            <div class="status-available">Tersedia</div>
                                            <div class="mt-2 text-muted small">Klik Pilih</div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <form action="<?= APP_URL ?>/customer/set-table" method="POST" id="tableForm">
                            <input type="hidden" name="meja_id" id="hiddenMejaId">
                            <input type="hidden" name="meja_nama" id="hiddenMejaNama">

                            <div class="p-4 border-top bg-light">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <a href="<?= APP_URL ?>/customer/dashboard" class="btn btn-secondary w-100">
                                            <i class="fas fa-arrow-left me-1"></i> Kembali
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-success w-100" id="confirmTableBtn" disabled>
                                            <i class="fas fa-check me-1"></i> Konfirmasi Pilihan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Meja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menggunakan meja <strong id="confirmTableNumber"></strong>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="document.getElementById('tableForm').submit()">Ya, Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ✅ FIX 4: JavaScript Murni (Tanpa Fetch API)
        // Fungsi ini hanya mengubah UI dan mengisi input hidden
        function selectTable(id, nomor) {
            // Reset style
            document.querySelectorAll('.table-item').forEach(t => t.classList.remove('selected'));
            
            // Highlight pilihan
            const selectedEl = document.getElementById('table-' + id);
            if(selectedEl) selectedEl.classList.add('selected');
            
            // Isi Form Hidden
            document.getElementById('hiddenMejaId').value = id;
            document.getElementById('hiddenMejaNama').value = nomor;
            
            // Update teks modal
            document.getElementById('confirmTableNumber').textContent = '#' + nomor;
            
            // Enable tombol
            document.getElementById('confirmTableBtn').disabled = false;
        }

        // Handle klik tombol konfirmasi untuk memunculkan modal
        document.getElementById('confirmTableBtn').addEventListener('click', function(e) {
            e.preventDefault(); // Jangan submit dulu
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();
        });
    </script>
</body>
</html>