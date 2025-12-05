<!-- 
    HEAD SECTION - TEMPLATE AWAL HTML
    Bagian kepala (head) dari dokumen HTML yang berisi metadata dan resource
-->

<!-- 
    DOCTYPE Declaration
    Menentukan versi HTML yang digunakan (HTML5)
-->
<!DOCTYPE html>

<!-- 
    Root HTML Element dengan atribut lang
    Menentukan bahasa konten (Indonesian)
-->
<html lang="id">

<!-- 
    HEAD Section
    Berisi metadata, title, dan link ke external resources
    Tidak menampilkan konten langsung ke user
-->
<head>
    
    <!-- 
        Meta Charset
        Menentukan karakter encoding untuk halaman (UTF-8)
        Penting untuk menampilkan karakter khusus dengan benar
    -->
    <meta charset="UTF-8">
    
    <!-- 
        Viewport Meta Tag
        Essential untuk responsive design di mobile devices
        width=device-width: Lebar viewport mengikuti device
        initial-scale=1.0: Zoom level awal 100%
    -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- 
        Dynamic Page Title
        Menggunakan PHP untuk menentukan judul halaman secara dinamis
        Fallback ke 'Pool Snack System' jika $title tidak tersedia
    -->
    <title><?= $title ?? 'Pool Snack System' ?></title>
    
    <!-- ================================================
         SECTION: EXTERNAL CSS LIBRARIES
    ================================================ -->
    
    <!-- 
        1. Bootstrap 5.3.0 CSS
        Framework CSS untuk styling, grid system, dan komponen UI
        Load dari CDN untuk performa optimal
    -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- 
        2. Font Awesome 6.0.0
        Library ikon vektor untuk ikon-ikon UI
        Versi 6 (terbaru) dengan ikon-ikon modern
    -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- ================================================
         SECTION: CUSTOM CSS FILES
    ================================================ -->
    
    <!-- 
        3. Main Custom CSS File
        Stylesheet kustom utama untuk aplikasi
        Path absolut: /pool-snack-system/public/assets/css/main.css
        Berisi styling khusus yang tidak tersedia di Bootstrap
    -->
    <link href="/pool-snack-system/public/assets/css/main.css" rel="stylesheet">
    
    <!-- ================================================
         SECTION: DYNAMIC PAGE-SPECIFIC CSS
    ================================================ -->
    
    <!-- 
        4. Conditional Inline CSS per Page
        Blok style yang dapat ditambahkan secara dinamis per halaman
        Berguna untuk styling yang spesifik untuk halaman tertentu
    -->
    <?php if (isset($additional_css)): ?>
        <style>
            <?= $additional_css ?>
        </style>
    <?php endif; ?>
    
</head> <!-- Akhir dari head section -->

<!-- 
    BODY Section
    Bagian yang berisi konten aktual yang ditampilkan ke user
-->
<body class="<?= $body_class ?? '' ?>">
    <!-- 
        Body Class Dinamis
        Menggunakan PHP untuk menambahkan class CSS ke body secara dinamis
        Berguna untuk styling spesifik per halaman atau tema
    -->