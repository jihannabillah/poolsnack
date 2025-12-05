<?php
// app/views/kasir/reports.php
// Asumsi variabel $dailyTotal, $weeklyTotal, $dailyReport, etc. sudah dikirim dari ReportController
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Kasir Pool Snack</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        .kasir-theme .navbar { background: linear-gradient(135deg, #27AE60 0%, #2ECC71 100%) !important; }

        /* --- CARD & TAB STYLING --- */
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .form-control, .form-select { border-radius: 8px; font-size: 0.9rem; }
        
        .nav-tabs { border-bottom: 2px solid #ddd; }
        .nav-tabs .nav-link {
            color: #555;
            font-weight: 600;
            border: none;
            border-bottom: 3px solid transparent;
            border-radius: 0;
            padding: 10px 20px;
            transition: all 0.3s;
        }
        .nav-tabs .nav-link.active {
            color: #2ECC71 !important; /* Kasir Green */
            border-color: #2ECC71 !important;
            background-color: transparent !important;
        }

        /* --- TABLE STYLING (High Contrast) --- */
        .table-report-kasir th {
            background-color: #44607a; /* Dark Blue/Grey Header */
            color: white;
            font-size: 0.85rem;
            text-transform: uppercase;
            padding: 12px;
            vertical-align: middle;
        }
        .table-report-kasir td {
            font-size: 0.9rem;
        }
        .table-report-kasir tbody tr:nth-child(even) {
            background-color: #f9f9f9; /* Subtle striping */
        }
        .table-report-kasir tfoot tr {
            background-color: #e8f5e9; /* Light green footer for totals */
            font-weight: bold;
            color: #27AE60;
        }
    </style>
</head>
<body class="page-theme">
    <?php 
    // Logika ini menentukan Navbar & Base Route (Admin atau Kasir)
    if ($_SESSION['role'] == 'admin') {
        require_once APP_PATH . '/views/layouts/admin-nav.php';
        $baseRoute = '/admin';
    } else {
        require_once APP_PATH . '/views/layouts/kasir-nav.php';
        $baseRoute = '/kasir';
    }
    ?>

    <div class="container-fluid mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold"><i class="fas fa-chart-bar me-2 text-primary"></i>Laporan & Analytics</h2>
                <p class="text-muted">Lihat laporan transaksi dan analisis penjualan</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <ul class="nav nav-tabs" id="reportTabs">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#daily">Laporan Harian</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#weekly">Laporan Mingguan</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#analytics">Analisis Menu</button></li>
                </ul>

                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="daily">
                        <form action="<?= APP_URL . $baseRoute ?>/reports" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small">Pilih Tanggal</label>
                                <input type="date" class="form-control" name="date" value="<?= $_GET['date'] ?? date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">&nbsp;</label>
                                <button type="button" class="btn btn-success w-100" onclick="exportDailyReport()">
                                    <i class="fas fa-print me-1"></i>Cetak / PDF
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="weekly">
                        <form action="<?= APP_URL . $baseRoute ?>/reports" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small">Minggu Mulai</label>
                                <input type="date" class="form-control" name="week" value="<?= $_GET['week'] ?? date('Y-m-d', strtotime('-7 days')) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">&nbsp;</label>
                                <button type="button" class="btn btn-success w-100" onclick="exportWeeklyReport()">
                                    <i class="fas fa-file-csv me-1"></i>Export CSV
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="analytics">
                        <form action="<?= APP_URL . $baseRoute ?>/reports" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small">Dari</label>
                                <input type="date" class="form-control" name="start_date" value="<?= $_GET['start_date'] ?? date('Y-m-01') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Sampai</label>
                                <input type="date" class="form-control" name="end_date" value="<?= $_GET['end_date'] ?? date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">&nbsp;</label>
                                <button type="button" class="btn btn-success w-100" onclick="exportAnalytics()">
                                    <i class="fas fa-file-csv me-1"></i>Export CSV
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="dailyContent">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white">
                        <h5 class="mb-0">Laporan Harian</h5>
                        <span class="badge bg-success fs-6">Total: Rp <?= number_format($dailyTotal, 0, ',', '.') ?></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-report-kasir">
                                <thead>
                                    <tr>
                                        <th>No Order</th>
                                        <th>Waktu</th>
                                        <th>Meja</th>
                                        <th>Customer</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($dailyReport)): ?>
                                        <?php foreach ($dailyReport as $order): ?>
                                        <tr>
                                            <td>#<?= $order['order_number'] ?></td>
                                            <td><?= date('H:i', strtotime($order['created_at'])) ?></td>
                                            <td>#<?= $order['nomor_meja'] ?></td>
                                            <td><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></td>
                                            <td><?= strtoupper($order['metode_bayar']) ?></td>
                                            <td>
                                                <?php 
                                                    $badge = 'bg-secondary';
                                                    if ($order['status'] == 'selesai') $badge = 'bg-success';
                                                    elseif ($order['status'] == 'diproses') $badge = 'bg-info';
                                                    elseif ($order['status'] == 'menunggu_konfirmasi') $badge = 'bg-warning text-dark';
                                                    elseif ($order['status'] == 'dibatalkan') $badge = 'bg-danger';
                                                ?>
                                                <span class="badge <?= $badge ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $order['status'])) ?>
                                                </span>
                                            </td>
                                            <td class="text-end">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada transaksi pada tanggal yang dipilih.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>Total Pendapatan:</strong></td>
                                        <td class="text-end"><strong>Rp <?= number_format($dailyTotal, 0, ',', '.') ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="weeklyContent">
                </div>
             
            <div class="tab-pane fade" id="analyticsContent">
                </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Logika Tab Sync
    document.getElementById('reportTabs').addEventListener('show.bs.tab', function (e) {
        const target = e.target.getAttribute('data-bs-target');
        document.querySelectorAll('.tab-content > .tab-pane').forEach(pane => {
            if(pane.id.includes('Content')) pane.classList.remove('show', 'active');
        });
        const contentId = target.replace('#', '') + 'Content';
        const contentEl = document.getElementById(contentId);
        if(contentEl) contentEl.classList.add('show', 'active');
    });

    // ✅ Export Functions Dinamis (Menggunakan PHP Variable untuk Base Route)
    const baseRoute = '<?= APP_URL . $baseRoute ?>';

    function exportDailyReport() {
        const date = document.querySelector('input[name="date"]').value;
        window.open(baseRoute + '/reports/export-daily?date=' + date, '_blank');
    }

    function exportWeeklyReport() {
        const week = document.querySelector('input[name="week"]').value;
        window.location.href = baseRoute + '/reports/export-weekly?week=' + week;
    }

    function exportAnalytics() {
        const start = document.querySelector('input[name="start_date"]').value;
        const end = document.querySelector('input[name="end_date"]').value;
        window.location.href = baseRoute + '/reports/export-analytics?start_date=' + start + '&end_date=' + end;
    }
    </script>
</body>
</html>