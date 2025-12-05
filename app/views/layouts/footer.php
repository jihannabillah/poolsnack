<!-- 
    FOOTER DAN SCRIPT BAGIAN AKHIR HTML
    Bagian penutup dari struktur halaman web
-->

<!-- ================================================
     SECTION: EXTERNAL JAVASCRIPT LIBRARIES
================================================ -->

<!-- 
    1. jQuery 3.6.0 (Minified)
    Library JavaScript untuk DOM manipulation dan AJAX
    Digunakan untuk interaksi client-side yang lebih mudah
-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- 
    2. Bootstrap 5.3.0 JavaScript Bundle (with Popper)
    Bundle yang berisi semua komponen JavaScript Bootstrap
    Termasuk Popper.js untuk positioning tooltip/dropdown
-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- ================================================
     SECTION: CUSTOM JAVASCRIPT FILES
================================================ -->

<!-- 
    3. Main Custom JavaScript File
    File JavaScript kustom untuk aplikasi
    Path absolut: /pool-snack-system/public/assets/js/main.js
-->
<script src="/pool-snack-system/public/assets/js/main.js"></script>

<!-- ================================================
     SECTION: DYNAMIC PAGE-SPECIFIC JAVASCRIPT
================================================ -->

<!-- 
    4. Conditional Inline JavaScript per Page
    Blok script yang dapat ditambahkan secara dinamis per halaman
    Berguna untuk kode JavaScript yang spesifik untuk halaman tertentu
-->
<?php if (isset($additional_js)): ?>
    <script>
        <?= $additional_js ?>
    </script>
<?php endif; ?>

<!-- ================================================
     SECTION: TOAST NOTIFICATION CONTAINER
================================================ -->

<!-- 
    5. Toast Notification Container
    Container untuk menampilkan notifikasi pop-up (toast)
    Posisi tetap (fixed) di pojok kanan atas layar
-->
<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3">
    <!-- 
        Toast notifications akan dimasukkan secara dinamis melalui JavaScript
        Contoh: notifikasi sukses, error, atau informasi
    -->
</div>

<!-- ================================================
     SECTION: FOOTER CONTENT
================================================ -->

<!-- 
    6. Main Footer
    Bagian footer website dengan informasi copyright dan branding
-->
<footer class="mt-5 py-4 bg-light border-top">
    
    <!-- Container untuk mengatur lebar konten footer -->
    <div class="container">
        
        <!-- Baris untuk layout footer -->
        <div class="row">
            
            <!-- Kolom kiri: Branding dan deskripsi -->
            <div class="col-md-6">
                <!-- Judul aplikasi dengan ikon -->
                <h5>
                    <i class="fas fa-utensils me-2"></i>
                    Pool Snack System
                </h5>
                
                <!-- Deskripsi singkat aplikasi -->
                <p class="text-muted mb-0">
                    Sistem pemesanan snack & minuman berbasis web untuk arena billiard
                </p>
            </div>
            
            <!-- Kolom kanan: Copyright dan informasi developer -->
            <div class="col-md-6 text-end">
                <!-- Informasi copyright dengan tahun dinamis -->
                <p class="text-muted mb-0">
                    &copy; <?= date('Y') ?> Pool Snack Billiard. All rights reserved.
                    <br>
                    <!-- Credit developer dengan ikon hati -->
                    <small>
                        Developed with <i class="fas fa-heart text-danger"></i> for Final Project
                    </small>
                </p>
            </div>
            
        </div> <!-- Akhir row -->
        
    </div> <!-- Akhir container -->
    
</footer>

<!-- ================================================
     PENUTUP TAGS HTML
================================================ -->

<!-- 
    Tag penutup untuk body dan html
    Menandai akhir dari dokumen HTML
-->
</body>
</html>