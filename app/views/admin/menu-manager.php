<?php
// app/views/admin/menu-manager.php
// File view/template untuk halaman manajemen menu oleh admin
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Admin Pool Snack</title> <!-- Judul halaman -->
    
    <!-- Load external CSS libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Load custom CSS file -->
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <!-- Inline CSS styling khusus untuk halaman ini -->
    <style>
        /* Base styling */
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f4f6f9; 
        }
        
        /* Warna theme khusus untuk admin navigation */
        .admin-theme .navbar { 
            background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%) !important; 
        }

        /* General Card & Modal Styling */
        .card { 
            border: none; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); 
        }
        
        /* Styling untuk card header */
        .card-header-main { 
            background-color: white; 
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }

        /* Modal Header dengan gradient dark */
        .modal .modal-header {
            background: linear-gradient(45deg, #2C3E50, #34495E) !important;
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            border-bottom: none;
        }

        /* Styling untuk setiap item menu (card) */
        .menu-item .card { 
            transition: transform 0.2s; 
            cursor: pointer; 
        }
        .menu-item .card:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 8px 20px rgba(0,0,0,0.1); 
        }
        
        /* Styling untuk judul menu */
        .card-title { 
            font-weight: 600; 
        }
        
        /* Styling untuk teks harga */
        .text-price-admin { 
            color: #34495E; 
            font-weight: 700; 
            font-size: 1.5rem; 
        }

        /* Button Group Fix untuk tombol kecil */
        .btn-group-sm .btn {
            font-size: 0.8rem;
        }
        
        /* Styling untuk tombol outline primary */
        .btn-outline-primary { 
            color: #34495E; 
            border-color: #34495E; 
        }
        .btn-outline-primary:hover {
            background-color: #34495E;
            color: white;
        }
        
        /* Styling untuk tombol primary */
        .btn-primary { 
            background-color: #34495E; 
            border-color: #34495E; 
        }
        .btn-primary:hover { 
            background-color: #2C3E50; 
            border-color: #2C3E50; 
        }

        /* Image Preview di modal edit */
        #editMenuImagePreview {
            border: 1px solid #eee;
            border-radius: 8px;
        }
    </style>
</head>
<body class="admin-theme">
    <!-- Include navigation bar untuk admin -->
    <?php require_once APP_PATH . '/views/layouts/admin-nav.php'; ?>

    <!-- Main content container -->
    <div class="container-fluid mt-4">
        <!-- Header section dengan tombol tambah menu -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">
                            <i class="fas fa-utensils me-2 text-dark"></i>Kelola Menu
                        </h2>
                        <p class="text-muted">Tambah, edit, dan hapus menu makanan & minuman</p>
                    </div>
                    <!-- Tombol untuk membuka modal tambah menu baru -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                        <i class="fas fa-plus me-1"></i>Tambah Menu
                    </button>
                </div>
            </div>
        </div>

        <!-- Alert untuk menampilkan pesan sukses -->
        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" style="border-radius: 10px;">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Berhasil!</strong> Menu berhasil <?= $_GET['success'] == 'deleted' ? 'dihapus' : 'disimpan' ?>.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Grid untuk menampilkan semua menu -->
        <div class="row">
            <!-- Loop melalui setiap menu yang diterima dari controller -->
            <?php foreach ($menus as $menu): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <!-- Gambar menu -->
                    <img src="<?= APP_URL ?>/assets/images/menu/<?= $menu['gambar'] ?>" 
                         class="card-img-top" alt="<?= $menu['nama'] ?>" 
                         style="height: 200px; object-fit: cover;"
                         onerror="this.src='https://placehold.co/200x200/ccc/333?text=No+Image'">
                         
                    <div class="card-body d-flex flex-column">
                        <!-- Nama menu -->
                        <h5 class="card-title"><?= htmlspecialchars($menu['nama']) ?></h5>
                        <!-- Deskripsi menu -->
                        <p class="card-text text-muted small"><?= htmlspecialchars($menu['deskripsi']) ?></p>
                        
                        <!-- Badge untuk kategori dan status -->
                        <div class="mb-2 mt-auto">
                            <span class="badge <?= $menu['kategori'] == 'makanan' ? 'bg-warning' : 'bg-info' ?>">
                                <?= ucfirst($menu['kategori']) ?>
                            </span>
                            <span class="badge <?= $menu['status'] == 'tersedia' ? 'bg-success' : 'bg-danger' ?>">
                                <?= ucfirst($menu['status']) ?>
                            </span>
                        </div>
                        
                        <!-- Harga menu -->
                        <h4 class="text-price-admin mb-3">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></h4>
                        
                        <!-- Tombol edit dan hapus -->
                        <div class="btn-group w-100">
                            <!-- Tombol edit dengan data attributes untuk modal -->
                            <button class="btn btn-outline-primary edit-menu-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editMenuModal"
                                    data-id="<?= $menu['id'] ?>"
                                    data-nama="<?= htmlspecialchars($menu['nama']) ?>"
                                    data-deskripsi="<?= htmlspecialchars($menu['deskripsi']) ?>"
                                    data-harga="<?= $menu['harga'] ?>"
                                    data-kategori="<?= $menu['kategori'] ?>"
                                    data-status="<?= $menu['status'] ?>"
                                    data-gambar="<?= $menu['gambar'] ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            
                            <!-- Tombol hapus dengan konfirmasi JavaScript -->
                            <a href="<?= APP_URL ?>/admin/menu/delete?id=<?= $menu['id'] ?>" 
                               class="btn btn-outline-danger"
                               onclick="return confirm('Yakin hapus menu <?= htmlspecialchars($menu['nama']) ?>? Tindakan ini tidak dapat dibatalkan.')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pesan jika tidak ada menu -->
        <?php if (empty($menus)): ?>
        <div class="text-center py-5">
            <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Belum Ada Menu</h4>
        </div>
        <?php endif; ?>
    </div>

    <!-- Modal untuk menambah menu baru -->
    <div class="modal fade" id="addMenuModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Menu Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- Form untuk menambah menu -->
                <form action="<?= APP_URL ?>/admin/menu/create" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Input nama menu -->
                            <div class="col-md-6">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" class="form-control" name="nama" required>
                            </div>
                            
                            <!-- Select kategori -->
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" name="kategori" required>
                                    <option value="makanan">Makanan</option>
                                    <option value="minuman">Minuman</option>
                                </select>
                            </div>
                            
                            <!-- Textarea deskripsi -->
                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
                            </div>
                            
                            <!-- Input harga -->
                            <div class="col-md-6">
                                <label class="form-label">Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="harga" min="0" required>
                                </div>
                            </div>
                            
                            <!-- Select status -->
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="habis">Habis</option>
                                </select>
                            </div>
                            
                            <!-- Input upload gambar -->
                            <div class="col-12">
                                <label class="form-label">Gambar Menu</label>
                                <input type="file" class="form-control" name="gambar" accept="image/*" required>
                                <div class="form-text">Format: JPG, PNG (Max 2MB)</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk mengedit menu -->
    <div class="modal fade" id="editMenuModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- Form untuk mengedit menu -->
                <form action="<?= APP_URL ?>/admin/menu/update" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!-- Hidden input untuk ID menu -->
                        <input type="hidden" name="id" id="editMenuId">
                        
                        <div class="row g-3">
                            <!-- Input nama menu -->
                            <div class="col-md-6">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" class="form-control" name="nama" id="editMenuName" required>
                            </div>
                            
                            <!-- Select kategori -->
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" name="kategori" id="editMenuCategory" required>
                                    <option value="makanan">Makanan</option>
                                    <option value="minuman">Minuman</option>
                                </select>
                            </div>
                            
                            <!-- Textarea deskripsi -->
                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" id="editMenuDescription" rows="3" required></textarea>
                            </div>
                            
                            <!-- Input harga -->
                            <div class="col-md-6">
                                <label class="form-label">Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="harga" id="editMenuPrice" min="0" required>
                                </div>
                            </div>
                            
                            <!-- Select status -->
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" id="editMenuStatus" required>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="habis">Habis</option>
                                </select>
                            </div>
                            
                            <!-- Input upload gambar (optional) -->
                            <div class="col-12">
                                <label class="form-label">Gambar Menu</label>
                                <input type="file" class="form-control" name="gambar" accept="image/*">
                                <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar</div>
                                <!-- Preview gambar saat ini -->
                                <div class="mt-2">
                                    <img id="editMenuImagePreview" src="" alt="Current Image" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Load Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript untuk halaman ini -->
    <script>
    // ✅ JavaScript untuk Mengisi Modal Edit saat tombol edit diklik
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil semua tombol edit
        const editButtons = document.querySelectorAll('.edit-menu-btn');
        
        // Tambahkan event listener untuk setiap tombol edit
        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Ambil data dari atribut data-* pada tombol
                const data = this.dataset;

                // Isi Form Modal dengan data yang diambil
                document.getElementById('editMenuId').value = data.id;
                document.getElementById('editMenuName').value = data.nama;
                document.getElementById('editMenuDescription').value = data.deskripsi;
                document.getElementById('editMenuPrice').value = data.harga;
                document.getElementById('editMenuCategory').value = data.kategori;
                document.getElementById('editMenuStatus').value = data.status;
                
                // Tampilkan Preview Gambar saat ini
                const imgPath = '<?= APP_URL ?>/assets/images/menu/' + data.gambar;
                document.getElementById('editMenuImagePreview').src = imgPath;
            });
        });
    });

    // ✅ JavaScript untuk konfirmasi delete
    document.querySelectorAll('.delete-menu').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Tampilkan dialog konfirmasi sebelum menghapus
            if (!confirm(`Yakin hapus menu "${this.dataset.nama}"? Tindakan ini tidak dapat dibatalkan.`)) {
                e.preventDefault(); // Batalkan jika user klik cancel
            }
        });
    });
    </script>
</body>
</html>