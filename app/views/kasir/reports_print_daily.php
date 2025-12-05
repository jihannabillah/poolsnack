<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian - <?= $date ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none; } }
        body { font-family: Arial, sans-serif; }
    </style>
</head>
<body onload="window.print()">
    <div class="container mt-4">
        <div class="no-print mb-4">
            <button onclick="window.history.back()" class="btn btn-secondary">Kembali</button>
            <button onclick="window.print()" class="btn btn-primary">Cetak / Simpan PDF</button>
        </div>

        <div class="text-center mb-4">
            <h3>LAPORAN PENJUALAN HARIAN</h3>
            <p>Tanggal: <?= date('d F Y', strtotime($date)) ?></p>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Order</th>
                    <th>Jam</th>
                    <th>Meja</th>
                    <th>Metode Bayar</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($dailyReport as $row): 
                    $total += $row['total_harga'];
                ?>
                <tr>
                    <td>#<?= $row['order_number'] ?></td>
                    <td><?= date('H:i', strtotime($row['created_at'])) ?></td>
                    <td><?= $row['nomor_meja'] ?></td>
                    <td><?= strtoupper($row['metode_bayar']) ?></td>
                    <td class="text-end">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-dark">
                    <td colspan="4" class="text-end"><strong>GRAND TOTAL</strong></td>
                    <td class="text-end"><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
        </table>
        
        <div class="mt-5 text-end">
            <p>Jambi, <?= date('d F Y') ?></p>
            <br><br>
            <p>( .................................... )<br>Admin / Kasir</p>
        </div>
    </div>
</body>
</html>