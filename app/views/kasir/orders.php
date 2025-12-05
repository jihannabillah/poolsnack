<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Orders - Kasir Pool Snack</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        .kasir-theme .navbar { background: linear-gradient(135deg, #27AE60 0%, #2ECC71 100%) !important; }
        
        /* --- CARD & FILTER STYLING --- */
        .filter-card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: none;
            background: white;
            padding: 1.5rem;
        }

        /* --- TABLE HIGH CONTRAST --- */
        .table-kasir th {
            background-color: #34495E; /* Dark Header for professional look */
            color: white;
            border-bottom: none;
            font-size: 0.85rem;
            text-transform: uppercase;
            vertical-align: middle;
        }
        .table-kasir td {
            font-size: 0.9rem;
            vertical-align: middle;
        }
        .table-kasir tbody tr:hover {
            background-color: #f7f7f7;
        }
        .table-kasir {
            border-radius: 10px;
            overflow: hidden;
        }
        
        /* Input & Select Styling */
        .form-control, .form-select {
            border-radius: 8px;
            font-size: 0.9rem;
        }
        .btn-primary {
            background-color: #3498DB;
            border-color: #3498DB;
        }
        .btn-primary:hover {
            background-color: #2980B9;
            border-color: #2980B9;
        }
    </style>
</head>
<body class="kasir-theme">
    <?php require_once APP_PATH . '/views/layouts/kasir-nav.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold"><i class="fas fa-list me-2 text-primary"></i>Semua Orders</h2>
                <p class="text-muted">Kelola semua pesanan dari customer</p>
            </div>
        </div>

        <div class="card mb-4 filter-card">
            <div class="card-body p-0">
                <form action="<?= APP_URL ?>/kasir/orders" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small">Status</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="menunggu_konfirmasi" <?= ($_GET['status'] ?? '') == 'menunggu_konfirmasi' ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
                                <option value="diproses" <?= ($_GET['status'] ?? '') == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option value="selesai" <?= ($_GET['status'] ?? '') == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option value="dibatalkan" <?= ($_GET['status'] ?? '') == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Tanggal</label>
                            <input type="date" class="form-control" name="date" value="<?= $_GET['date'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Metode Bayar</label>
                            <select class="form-select" name="payment">
                                <option value="">Semua Metode</option>
                                <option value="tunai" <?= ($_GET['payment'] ?? '') == 'tunai' ? 'selected' : '' ?>>Tunai</option>
                                <option value="qris" <?= ($_GET['payment'] ?? '') == 'qris' ? 'selected' : '' ?>>QRIS</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-1"></i>Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card filter-card">
            <div class="card-body pt-3 pb-2">
                <div class="table-responsive">
                    <table class="table table-hover table-kasir">
                        <thead>
                            <tr>
                                <th>No Order</th>
                                <th>Tanggal</th>
                                <th>Meja</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Metode Bayar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        Tidak ada data order ditemukan.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong>#<?= $order['order_number'] ?></strong></td>
                                    <td><?= date('d/m H:i', strtotime($order['created_at'])) ?></td>
                                    <td>#<?= $order['nomor_meja'] ?></td>
                                    <td><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></td>
                                    <td>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= strtoupper($order['metode_bayar']) ?></span>
                                    </td>
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
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= APP_URL ?>/customer/nota?id=<?= $order['id'] ?>" target="_blank" class="btn btn-outline-primary" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <?php if ($order['status'] == 'menunggu_konfirmasi'): ?>
                                                <a href="<?= APP_URL ?>/kasir/status?id=<?= $order['id'] ?>&status=diproses" 
                                                   class="btn btn-outline-success" onclick="return confirm('Proses pesanan ini?')">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                            
                                            <?php elseif ($order['status'] == 'diproses'): ?>
                                                <a href="<?= APP_URL ?>/kasir/status?id=<?= $order['id'] ?>&status=selesai" 
                                                   class="btn btn-outline-success" onclick="return confirm('Selesaikan pesanan ini?')">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($order['metode_bayar'] == 'qris' && $order['bukti_bayar']): ?>
                                                <button class="btn btn-outline-info" 
                                                        onclick="viewPaymentProof('<?= $order['bukti_bayar'] ?>', <?= $order['id'] ?>)">
                                                    <i class="fas fa-receipt"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="paymentProofModal" tabindex="-1">
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JS Sederhana untuk Modal
        function viewPaymentProof(imageName, orderId) {
            const imgPath = '<?= APP_URL ?>/uploads/payments/' + imageName;
            document.getElementById('paymentProofImage').src = imgPath;
            document.getElementById('btnAccept').href = '<?= APP_URL ?>/kasir/verify?id=' + orderId + '&action=accept';
            document.getElementById('btnReject').href = '<?= APP_URL ?>/kasir/verify?id=' + orderId + '&action=reject';
            new bootstrap.Modal(document.getElementById('paymentProofModal')).show();
        }
    </script>
</body>
</html>