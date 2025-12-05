<?php
/**
 * Class ReportService - Service untuk generate laporan dalam berbagai format
 * Menangani pembuatan laporan PDF, Excel/CSV, dan HTML
 */
class ReportService {
    
    /**
     * Method untuk generate laporan dalam format PDF
     * Saat ini menghasilkan HTML, bisa diintegrasikan dengan library PDF seperti DomPDF
     * 
     * @param array $data Data yang akan dijadikan laporan (biasanya dari ReportModel)
     * @param string $type Jenis laporan: 'daily' (harian) atau 'weekly' (mingguan)
     * @return string HTML content yang bisa dikonversi ke PDF
     */
    public function generatePDF($data, $type = 'daily') {
        // Generate HTML report terlebih dahulu
        $html = $this->generateHTMLReport($data, $type);
        
        // Untuk saat ini, return HTML. 
        // Nanti bisa diintegrasikan dengan library PDF seperti:
        // - DomPDF: library PHP untuk convert HTML ke PDF
        // - TCPDF: library PDF yang lebih advance
        // - mPDF: library dengan support CSS yang baik
        
        return $html;
    }

    /**
     * Method untuk generate laporan dalam format Excel (CSV)
     * CSV adalah format sederhana yang bisa dibuka di Excel, Google Sheets, dll
     * 
     * @param array $data Data yang akan diexport
     * @param string $type Jenis laporan: 'daily' (harian) atau 'weekly' (mingguan)
     * @return string Nama file yang dihasilkan
     */
    public function generateExcel($data, $type = 'daily') {
        // Nama file untuk download
        $filename = $type . '_report_' . date('Y-m-d') . '.csv';
        
        // Buka output stream untuk menulis CSV
        $output = fopen('php://output', 'w');
        
        // Tambahkan header berdasarkan jenis laporan
        if ($type === 'daily') {
            // Header untuk laporan harian
            fputcsv($output, ['No Order', 'Meja', 'Customer', 'Total', 'Metode Bayar', 'Status', 'Waktu']);
            
            // Loop data dan tulis ke CSV
            foreach ($data as $row) {
                fputcsv($output, [
                    $row['order_number'],    // Nomor order
                    $row['nomor_meja'],      // Nomor meja
                    $row['customer_name'],   // Nama customer
                    $row['total_harga'],     // Total harga
                    $row['metode_bayar'],    // Cara bayar (cash/qris)
                    $row['status'],          // Status order
                    $row['created_at']       // Waktu order
                ]);
            }
        }
        // Bisa ditambahkan else if untuk jenis laporan lainnya
        
        // Tutup stream
        fclose($output);
        
        // Return nama file
        return $filename;
    }

    /**
     * Method private untuk generate laporan dalam format HTML
     * Digunakan sebagai template untuk PDF dan juga bisa untuk preview di browser
     * 
     * @param array $data Data yang akan ditampilkan
     * @param string $type Jenis laporan: 'daily' (harian) atau 'weekly' (mingguan)
     * @return string HTML content yang sudah digenerate
     */
    private function generateHTMLReport($data, $type) {
        // Mulai output buffering untuk menangkap output HTML
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Laporan <?= $type === 'daily' ? 'Harian' : 'Mingguan' ?></title>
            <style>
                /* Styling sederhana untuk laporan */
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 20px;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-top: 20px;
                }
                th, td { 
                    border: 1px solid #ddd; 
                    padding: 8px; 
                    text-align: left; 
                }
                th { 
                    background-color: #f2f2f2; 
                }
                .total { 
                    font-weight: bold; 
                }
                h2 {
                    color: #333;
                }
            </style>
        </head>
        <body>
            <!-- Header laporan -->
            <h2>Laporan <?= $type === 'daily' ? 'Harian' : 'Mingguan' ?> Pool Snack</h2>
            <p>Tanggal: <?= date('d/m/Y') ?></p>
            
            <!-- Tabel data -->
            <table>
                <thead>
                    <tr>
                        <th>No Order</th>
                        <th>Meja</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop data dan tampilkan di tabel -->
                    <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= $row['order_number'] ?></td>
                        <td><?= $row['nomor_meja'] ?></td>
                        <td><?= $row['customer_name'] ?></td>
                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td><?= $row['metode_bayar'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </body>
        </html>
        <?php
        // Ambil dan kembalikan content HTML yang sudah digenerate
        return ob_get_clean();
    }
}
?>