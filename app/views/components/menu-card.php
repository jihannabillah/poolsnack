<?php
// app/views/components/menu-card.php
// Reusable menu card component - Komponen kartu menu yang bisa digunakan berulang

/**
 * Fungsi untuk merender kartu menu individual
 * Komponen reusable yang bisa dipanggil dari berbagai halaman
 * 
 * @param array $menu Data menu dalam bentuk array asosiatif
 * @param bool $showAddButton Menampilkan tombol "Tambah" jika true (default: true)
 * @return void Output langsung ke HTML (echo)
 */
function renderMenuCard($menu, $showAddButton = true) {
    ?>
    <!-- Container untuk setiap item menu -->
    <!-- data-category attribute untuk filtering JavaScript -->
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 menu-item" data-category="<?= $menu['kategori'] ?>">
        
        <!-- Kartu menu -->
        <div class="card h-100 menu-card">
            
            <!-- Gambar menu -->
            <!-- Path gambar: /pool-snack-system/public/assets/images/menu/{nama_file} -->
            <img src="/pool-snack-system/public/assets/images/menu/<?= $menu['gambar'] ?>" 
                 class="card-img-top" alt="<?= $menu['nama'] ?>" 
                 style="height: 200px; object-fit: cover;"> <!-- Fixed height dengan object-fit cover -->
            
            <!-- Body kartu -->
            <div class="card-body d-flex flex-column">
                
                <!-- Nama menu -->
                <h5 class="card-title"><?= htmlspecialchars($menu['nama']) ?></h5>
                
                <!-- Deskripsi menu -->
                <p class="card-text text-muted small flex-grow-1">
                    <?= htmlspecialchars($menu['deskripsi']) ?>
                </p>
                
                <!-- Footer kartu: harga dan tombol -->
                <div class="d-flex justify-content-between align-items-center">
                    
                    <!-- Harga menu dengan format Rupiah -->
                    <span class="h5 text-primary mb-0">
                        Rp <?= number_format($menu['harga'], 0, ',', '.') ?>
                    </span>
                    
                    <!-- Tombol "Tambah" (conditional) -->
                    <?php if ($showAddButton): ?>
                    <button class="btn btn-success btn-sm add-to-cart" 
                            data-menu-id="<?= $menu['id'] ?>"
                            data-menu-name="<?= htmlspecialchars($menu['nama']) ?>"
                            data-menu-price="<?= $menu['harga'] ?>">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>