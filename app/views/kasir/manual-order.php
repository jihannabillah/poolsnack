<?php
// app/views/kasir/manual-order.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Order Manual - Kasir Pool Snack</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        .kasir-theme .navbar { background: linear-gradient(135deg, #27AE60 0%, #2ECC71 100%) !important; }
        
        /* Custom styles for professional Kasir look */
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .card-header { 
            background: linear-gradient(45deg, #27AE60, #2ECC71) !important;
            color: white !important;
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
            border-bottom: none;
            padding: 1rem 1.5rem;
        }
        .form-control, .form-select { border-radius: 8px; font-size: 0.9rem; }
        
        /* Menu Cards */
        .menu-item .card { transition: transform 0.2s; cursor: pointer; }
        .menu-item .card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .menu-title { font-weight: 600; font-size: 1rem; }
        .menu-price { color: #27AE60; font-weight: 700; }

        /* Order Summary List Styling */
        #orderItemsList {
            background-color: #fcfcfc;
            border: 1px solid #ddd !important;
            border-radius: 8px !important;
            min-height: 100px; /* Agar terlihat rapi saat kosong */
        }
        .order-item-list { 
            border-bottom: 1px dashed #eee;
            padding: 8px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .order-item-list:last-child { border-bottom: none; }

        .btn-remove-item { color: #e74c3c; padding: 0; font-size: 0.8rem; background: none; border: none; }
        .btn-remove-item:hover { color: #c0392b; text-decoration: underline; }

        /* Total Display */
        #displayTotal { color: #2ECC71; font-size: 1.6rem !important; }
        #submitBtn { padding: 12px; font-size: 1rem; border-radius: 50px; }
    </style>
</head>
<body class="kasir-theme">
    <?php require_once APP_PATH . '/views/layouts/kasir-nav.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="fw-bold"><i class="fas fa-edit me-2 text-success"></i>Input Order Manual</h2>
                <p class="text-muted">Input pesanan langsung dari kasir (tanpa scan QR)</p>
            </div>
        </div>

        <form action="<?= APP_URL ?>/kasir/manual-order/create" method="POST" id="manualOrderForm">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-utensils me-2"></i>Pilih Menu</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="searchMenu" placeholder="Cari menu...">
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" id="categoryFilter">
                                        <option value="">Semua Kategori</option>
                                        <option value="makanan">Makanan</option>
                                        <option value="minuman">Minuman</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row" id="menuGrid">
                                <?php foreach ($menus as $menu): ?>
                                <div class="col-lg-4 col-md-6 mb-3 menu-item" data-category="<?= strtolower($menu['kategori']) ?>" data-price="<?= $menu['harga'] ?>" data-name="<?= strtolower($menu['nama']) ?>">
                                    <div class="card h-100">
                                        <img src="<?= APP_URL ?>/assets/images/menu/<?= $menu['gambar'] ?>" 
                                             class="card-img-top" alt="<?= $menu['nama'] ?>" 
                                             style="height: 120px; object-fit: cover;"
                                             onerror="this.src='https://placehold.co/120x120/ccc/333?text=IMG'">
                                        <div class="card-body">
                                            <h6 class="card-title"><?= htmlspecialchars($menu['nama']) ?></h6>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <span class="text-success fw-bold menu-price">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></span>
                                                <button type="button" class="btn btn-sm btn-success add-to-list" 
                                                        data-id="<?= $menu['id'] ?>" data-name="<?= htmlspecialchars($menu['nama']) ?>" data-price="<?= $menu['harga'] ?>">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Ringkasan Order</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small">Nomor Meja</label>
                                <select class="form-select" name="meja_id" required>
                                    <option value="">Pilih Meja</option>
                                    <?php foreach ($tables as $table): ?>
                                    <option value="<?= $table['id'] ?>">#<?= $table['nomor_meja'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small">Nama Customer</label>
                                <input type="text" class="form-control" name="customer_name" placeholder="Nama customer..." required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small">Items Pesanan</label>
                                <div id="orderItemsList" class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                    <p class="text-muted text-center mb-0 py-3 empty-msg">Belum ada item</p>
                                </div>
                                <div id="hiddenInputs"></div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2">
                                    <span>Total:</span>
                                    <span id="displayTotal">Rp 0</span>
                                    <input type="hidden" name="total_amount" id="inputTotal" value="0">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small">Metode Pembayaran</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_method" value="tunai" checked>
                                        <label class="form-check-label small">Tunai</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_method" value="qris">
                                        <label class="form-check-label small">QRIS</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100" id="submitBtn" disabled>
                                <i class="fas fa-check me-1"></i>Buat Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let cart = [];

    // 1. Tambah Item ke List
    document.querySelectorAll('.add-to-list').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = parseFloat(this.dataset.price);

            const existing = cart.find(item => item.id == id);
            if (existing) {
                existing.qty++;
            } else {
                cart.push({ id, name, price, qty: 1 });
            }
            renderCart();
        });
    });

    // 2. Render Tampilan Keranjang & Input Hidden
    function renderCart() {
        const listDiv = document.getElementById('orderItemsList');
        const hiddenDiv = document.getElementById('hiddenInputs');
        let total = 0;
        
        listDiv.innerHTML = '';
        hiddenDiv.innerHTML = '';

        if (cart.length === 0) {
            listDiv.innerHTML = '<p class="text-muted text-center mb-0 py-3 empty-msg">Belum ada item</p>';
            document.getElementById('submitBtn').disabled = true;
        } else {
            document.getElementById('submitBtn').disabled = false;
            
            cart.forEach((item, index) => {
                const subtotal = item.price * item.qty;
                total += subtotal;

                // Tampilan HTML List Item
                const itemHtml = `
                    <div class="order-item-list">
                        <div>
                            <strong>${item.name}</strong><br>
                            <small class="text-muted">${item.qty} x Rp ${item.price.toLocaleString('id-ID')}</small>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold">Rp ${subtotal.toLocaleString('id-ID')}</span><br>
                            <button type="button" class="btn-remove-item" onclick="removeItem('${item.id}')">
                                Hapus
                            </button>
                        </div>
                    </div>
                `;
                listDiv.innerHTML += itemHtml;

                // Input Hidden untuk Form Submission
                hiddenDiv.innerHTML += `
                    <input type="hidden" name="items[${index}][menu_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.qty}">
                    <input type="hidden" name="items[${index}][price]" value="${item.price}">
                `;
            });
        }

        // Update Total
        document.getElementById('displayTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('inputTotal').value = total;
    }

    // 3. Hapus Item
    window.removeItem = function(id) {
        cart = cart.filter(item => item.id != id);
        renderCart();
    };

    // 4. Filter Menu (JS Sederhana)
    document.getElementById('searchMenu').addEventListener('input', filterMenu);
    document.getElementById('categoryFilter').addEventListener('change', filterMenu);

    function filterMenu() {
        const search = document.getElementById('searchMenu').value.toLowerCase();
        const cat = document.getElementById('categoryFilter').value.toLowerCase();

        document.querySelectorAll('.menu-item').forEach(item => {
            const itemName = item.dataset.name;
            const itemCat = item.dataset.category;
            
            const matchSearch = itemName.includes(search);
            const matchCat = cat === '' || itemCat === cat;

            item.style.display = (matchSearch && matchCat) ? 'block' : 'none';
        });
    }

    // 5. Validasi Form (Agar tidak bisa submit jika meja/customer kosong)
    document.getElementById('tableSelect').addEventListener('change', updateSubmitButton);
    document.getElementById('customerName').addEventListener('input', updateSubmitButton);
    
    function updateSubmitButton() {
        const tableSelected = document.querySelector('select[name="meja_id"]').value;
        const customerName = document.querySelector('input[name="customer_name"]').value.trim();
        const itemsCount = cart.length;
        
        const isReady = tableSelected && customerName && itemsCount > 0;
        document.getElementById('submitBtn').disabled = !isReady;
    }
    
    // Initial call to set button state
    document.addEventListener('DOMContentLoaded', updateSubmitButton);
    </script>
</body>
</html>