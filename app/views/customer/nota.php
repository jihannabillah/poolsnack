<?php
// Validasi data order
if (!isset($order) || !$order) {
    echo "Data pesanan tidak ditemukan.";
    exit;
}

// HITUNG MUNDUR (Reverse Calculation) untuk tampilan
// Asumsi: $order['total_harga'] di database adalah Grand Total (Harga Akhir)
$grandTotal = $order['total_harga'];
$subtotalAsli = $grandTotal / 1.1; // Harga sebelum PPN 10%
$ppnHitungan = $grandTotal - $subtotalAsli;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota #<?= $order['order_number'] ?> - Pool Snack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .receipt-container { max-width: 400px; margin: 30px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .receipt-header { text-align: center; border-bottom: 2px dashed #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .receipt-footer { text-align: center; border-top: 2px dashed #eee; padding-top: 20px; margin-top: 20px; font-size: 12px; color: #777; }
        .table-items td { padding: 5px 0; border: none; }
        .total-row td { border-top: 1px solid #eee; padding-top: 10px; font-weight: bold; }
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; }
            .receipt-container { box-shadow: none; border: none; margin: 0; padding: 0; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="text-center mt-3 mb-3 no-print">
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak Nota</button>
        <button onclick="window.location.href='<?= APP_URL ?>/customer/history'" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Kembali
</button>
    </div>

    <div class="receipt-container">
        <div class="receipt-header">
            <h4 class="fw-bold mb-1">POOL SNACK</h4>
            <p class="mb-0 text-muted small">Jl. Raya Billiard No. 88, Jambi</p>
            <p class="mb-0 text-muted small">Telp: 0812-3456-7890</p>
        </div>

        <div class="row mb-3 small">
            <div class="col-6">
                No: <strong>#<?= $order['order_number'] ?></strong><br>
                Tgl: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
            </div>
            <div class="col-6 text-end">
                Meja: <strong>#<?= $order['nomor_meja'] ?? '-' ?></strong><br>
                Kasir: <?= $order['customer_name'] ?? 'Guest' ?>
            </div>
        </div>

        <table class="table table-sm table-items mb-0">
            <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th>Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nama']) ?></td>
                    <td class="text-center"><?= $item['quantity'] ?></td>
                    <td class="text-end">
                        Rp <?= number_format($item['subtotal'] ?? ($item['harga_satuan'] * $item['quantity']), 0, ',', '.') ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end text-muted small">Subtotal:</td>
                    <td class="text-end small">Rp <?= number_format($subtotalAsli, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-end text-muted small">PPN (10%):</td>
                    <td class="text-end small">Rp <?= number_format($ppnHitungan, 0, ',', '.') ?></td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" class="text-end">GRAND TOTAL:</td>
                    <td class="text-end fs-5 text-primary">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="receipt-footer">
            <p class="mb-1">Metode Bayar: <strong><?= strtoupper($order['metode_bayar']) ?></strong></p>
            <p class="mb-1">Status: <span class="badge bg-success"><?= strtoupper(str_replace('_', ' ', $order['status'])) ?></span></p>
            <br>
            <p>*** TERIMA KASIH ATAS KUNJUNGAN ANDA ***</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>