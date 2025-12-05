<?php
/**
 * Controller untuk mengelola laporan dan analitik penjualan
 * Hanya dapat diakses oleh kasir dan admin
 */
class ReportController {
    // Properti untuk menyimpan instance ReportModel
    private $reportModel;

    /**
     * Constructor - dijalankan saat class diinstansiasi
     * Melakukan validasi role dan inisialisasi model
     */
    public function __construct() {
        // ✅ LOGIKA VALIDASI DUAL ACCESS (Kasir & Admin):
        
        // 1. CEK LOGIN: Pastikan user sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }

        // 2. CEK ROLE: Hanya Kasir ATAU Admin yang boleh mengakses
        $role = $_SESSION['role'] ?? '';
        if ($role !== 'kasir' && $role !== 'admin') {
            // Jika customer coba akses, redirect ke halaman utama
            header('Location: ' . APP_URL . '/');
            exit;
        }

        // 3. INISIALISASI MODEL: Load ReportModel untuk operasi data laporan
        $this->reportModel = new ReportModel();
    }

    /**
     * Menampilkan halaman utama laporan
     * Menampilkan laporan harian, mingguan, dan menu terlaris
     */
    public function index() {
        // AMBIL FILTER DARI PARAMETER URL:
        $date = $_GET['date'] ?? date('Y-m-d');                  // Tanggal untuk laporan harian
        $week = $_GET['week'] ?? date('Y-m-d', strtotime('-7 days')); // Tanggal awal untuk laporan mingguan
        $startDate = $_GET['start_date'] ?? date('Y-m-01');      // Tanggal mulai untuk analisis
        $endDate = $_GET['end_date'] ?? date('Y-m-d');           // Tanggal akhir untuk analisis
        
        // AMBIL DATA DARI MODEL:
        $dailyReport = $this->reportModel->getDailyReport($date);        // Laporan transaksi harian
        $weeklyReport = $this->reportModel->getWeeklyReport($week);      // Laporan transaksi mingguan
        $bestSelling = $this->reportModel->getBestSellingMenu($startDate, $endDate); // Menu terlaris
        
        // HITUNG TOTAL MANUAL UNTUK DISPLAY DI VIEW:
        $dailyTotal = 0;
        foreach ($dailyReport as $r) $dailyTotal += $r['total_harga']; // Total pendapatan harian

        $weeklyTotal = 0;
        foreach ($weeklyReport as $r) $weeklyTotal += $r['total_pendapatan']; // Total pendapatan mingguan
        
        // MUAT VIEW LAPORAN
        require_once APP_PATH . '/views/kasir/reports.php';
    }

    // ====================================================================
    // FUNGSI EKSPOR LAPORAN
    // ====================================================================
    
    /**
     * ✅ Export Harian: Print View (HTML)
     * Menghasilkan halaman HTML sederhana yang siap dicetak
     * Diakses langsung di browser dengan tampilan print-friendly
     */
    public function exportDaily() {
        // Ambil parameter tanggal (default: hari ini)
        $date = $_GET['date'] ?? date('Y-m-d');
        $dailyReport = $this->reportModel->getDailyReport($date);
        
        // Tampilkan HTML Polos siap cetak
        echo "<html><head><title>Laporan Harian</title></head>";
        // Script window.print() otomatis membuka dialog print saat halaman load
        echo "<body onload='window.print()'>";
        
        // HEADER LAPORAN
        echo "<h2 style='text-align:center'>Laporan Harian ($date)</h2>";
        
        // TABEL DATA
        echo "<table border='1' width='100%' cellpadding='5' cellspacing='0' style='border-collapse:collapse'>";
        echo "<tr style='background:#eee'><th>Order</th><th>Meja</th><th>Total</th><th>Status</th></tr>";
        
        // LOOP DATA DAN TAMPILKAN DI TABEL
        $total = 0;
        foreach ($dailyReport as $row) {
            $total += $row['total_harga']; // Akumulasi total
            echo "<tr>";
            echo "<td>#{$row['order_number']}</td>";                    // Nomor order
            echo "<td>{$row['nomor_meja']}</td>";                       // Nomor meja
            // Format angka ke format Rupiah
            echo "<td style='text-align:right'>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
            echo "<td style='text-align:center'>{$row['status']}</td>"; // Status order
            echo "</tr>";
        }
        
        // BARIS TOTAL
        echo "<tr style='background:#eee; font-weight:bold'>";
        echo "<td colspan='2' style='text-align:right'>GRAND TOTAL</td>";
        echo "<td style='text-align:right'>Rp " . number_format($total, 0, ',', '.') . "</td>";
        echo "<td></td>";
        echo "</tr>";
        echo "</table></body></html>";
        
        // Tidak perlu exit karena ingin menampilkan HTML di browser
    }

    /**
     * ✅ Export Mingguan: CSV Download
     * Mengekspor data laporan mingguan dalam format CSV untuk diunduh
     */
    public function exportWeekly() {
        // Ambil parameter minggu (default: 7 hari terakhir)
        $week = $_GET['week'] ?? date('Y-m-d');
        $data = $this->reportModel->getWeeklyReport($week);
        
        // Nama file untuk download
        $filename = "laporan_mingguan_" . date('Ymd') . ".csv";
        
        // HEADER HTTP untuk download file CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Output ke stream untuk membuat file CSV
        $output = fopen('php://output', 'w');
        
        // Header kolom CSV
        fputcsv($output, ['Tanggal', 'Jumlah Transaksi', 'Pendapatan']);
        
        // Data baris per baris
        foreach ($data as $row) {
            fputcsv($output, [
                $row['tanggal'],           // Kolom tanggal
                $row['total_orders'],      // Kolom jumlah transaksi
                $row['total_pendapatan']   // Kolom pendapatan
            ]);
        }
        
        fclose($output);
        exit; // Hentikan eksekusi setelah file dikirim
    }

    /**
     * ✅ Export Analytics: CSV Download
     * Mengekspor analisis menu terlaris dalam format CSV untuk diunduh
     */
    public function exportAnalytics() {
        // Ambil parameter tanggal (default: bulan ini)
        $start = $_GET['start_date'] ?? date('Y-m-01');
        $end = $_GET['end_date'] ?? date('Y-m-d');
        $data = $this->reportModel->getBestSellingMenu($start, $end);
        
        // Nama file untuk download
        $filename = "analisis_menu_" . date('Ymd') . ".csv";
        
        // HEADER HTTP untuk download file CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Output ke stream untuk membuat file CSV
        $output = fopen('php://output', 'w');
        
        // Header kolom CSV
        fputcsv($output, ['Menu', 'Kategori', 'Terjual', 'Pendapatan']);
        
        // Data baris per baris
        foreach ($data as $row) {
            fputcsv($output, [
                $row['nama'],                 // Nama menu
                $row['kategori'],             // Kategori menu
                $row['total_terjual'],        // Jumlah terjual
                $row['total_pendapatan']      // Total pendapatan dari menu ini
            ]);
        }
        
        fclose($output);
        exit; // Hentikan eksekusi setelah file dikirim
    }
}
?>