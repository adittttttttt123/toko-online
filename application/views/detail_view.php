<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fa-solid fa-shoe-prints me-2"></i>SOLESOCIETY
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>">Semua Koleksi</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a href="<?= base_url('cart') ?>" class="btn btn-primary d-flex align-items-center py-2 px-3 position-relative">
                        <i class="fa-solid fa-bag-shopping me-2"></i>Keranjang
                        <?php if ($this->cart->total_items() > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $this->cart->total_items() ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container my-5">
        
        <!-- Back Button -->
        <a href="<?= base_url() ?>" class="btn btn-secondary mb-4">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Katalog
        </a>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i><?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Product Detail Row -->
        <div class="row">
            <!-- Left: Product Image -->
            <div class="col-md-6 mb-4">
                <div class="bg-white p-3 rounded-4 border border-light shadow-sm text-center">
                    <?php 
                        $img_src = base_url('assets/images/' . $product['gambar']);
                        if (empty($product['gambar']) || !file_exists(FCPATH . 'assets/images/' . $product['gambar'])) {
                            $img_src = base_url('assets/images/default_shoe.svg');
                        }
                    ?>
                    <img src="<?= $img_src ?>" 
                         class="img-fluid rounded-3" 
                         alt="<?= $product['nama_produk'] ?>"
                         style="max-height: 480px; object-fit: cover;">
                </div>
            </div>

            <!-- Right: Details and Cart Form -->
            <div class="col-md-6">
                <div class="p-2">
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-3"><?= $product['nama_kategori'] ?></span>
                    <h1 class="font-heading mb-2"><?= $product['nama_produk'] ?></h1>
                    
                    <!-- Rating Star Simulation -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="text-warning">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half-stroke"></i>
                        </div>
                        <span class="text-muted">(4.8 / 5.0) | 120 ulasan</span>
                    </div>

                    <h2 class="text-primary font-heading fw-800 mb-4">Rp <?= number_format($product['harga'], 0, ',', '.') ?></h2>
                    
                    <h5 class="fw-bold mb-2">Deskripsi Produk</h5>
                    <p class="text-muted mb-4"><?= $product['deskripsi'] ?></p>

                    <hr class="my-4 border-light">

                    <!-- Cart Options Form -->
                    <form action="<?= base_url('cart/add') ?>" method="POST" id="add-to-cart-form">
                        <input type="hidden" name="id_produk" value="<?= $product['id_produk'] ?>">

                        <!-- Attributes Select: Color -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Varian Warna</label>
                            <div class="d-flex flex-wrap gap-2" id="color-selectors">
                                <!-- Loaded dynamically via JS based on unique colors -->
                            </div>
                            <input type="hidden" name="warna" id="selected-color" required>
                        </div>

                        <!-- Attributes Select: Size -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Ukuran (EU)</label>
                            <div class="d-flex flex-wrap gap-2" id="size-selectors">
                                <!-- Loaded dynamically via JS based on color selected -->
                            </div>
                            <input type="hidden" name="ukuran" id="selected-size" required>
                        </div>

                        <!-- Dynamic Stock Notice -->
                        <div class="shipping-result-box mb-4 d-none" id="stock-notice-box">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-cubes-stacked text-primary"></i>
                                <span class="fw-bold" id="stock-value">Stok Tersedia: -</span>
                            </div>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div>
                                <label class="form-label fw-bold mb-1">Jumlah</label>
                                <div class="qty-control">
                                    <button type="button" class="qty-btn" id="btn-minus"><i class="fa-solid fa-minus"></i></button>
                                    <input type="number" name="qty" id="qty-input" class="qty-input" value="1" min="1" readonly>
                                    <button type="button" class="qty-btn" id="btn-plus"><i class="fa-solid fa-plus"></i></button>
                                </div>
                            </div>

                            <div class="flex-grow-1 align-self-end">
                                <button type="submit" class="btn btn-primary btn-gradient w-100 py-3" id="btn-submit" disabled>
                                    <i class="fa-solid fa-cart-plus me-2"></i>Pilih Ukuran & Warna
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white-50 py-5 mt-5 border-top border-secondary">
        <div class="container text-center">
            <p class="m-0">&copy; 2026 SOLESOCIETY. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Variation Engine Script -->
    <script>
        // Pass PHP variations array as JSON object
        const variations = <?= json_encode($variations) ?>;
        
        // Extract unique colors available
        const colors = [...new Set(variations.map(v => v.warna))];

        const colorContainer = document.getElementById('color-selectors');
        const sizeContainer = document.getElementById('size-selectors');
        const colorInput = document.getElementById('selected-color');
        const sizeInput = document.getElementById('selected-size');
        const stockBox = document.getElementById('stock-notice-box');
        const stockVal = document.getElementById('stock-value');
        const submitBtn = document.getElementById('btn-submit');
        const qtyInput = document.getElementById('qty-input');

        // Populate Colors
        colors.forEach(col => {
            const pill = document.createElement('div');
            pill.className = 'attribute-pill';
            pill.innerText = col;
            pill.addEventListener('click', () => {
                // Clear active color state
                colorContainer.querySelectorAll('.attribute-pill').forEach(el => el.classList.remove('active'));
                pill.classList.add('active');
                
                colorInput.value = col;
                sizeInput.value = ''; // Reset selected size
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-cart-plus me-2"></i>Pilih Ukuran';
                
                // Populate sizes based on selected color
                populateSizes(col);
                stockBox.classList.add('d-none');
            });
            colorContainer.appendChild(pill);
        });

        // Function to populate sizes based on selected color
        function populateSizes(selectedColor) {
            sizeContainer.innerHTML = '';
            
            // Filter variations matching color
            const matchingSizes = variations.filter(v => v.warna === selectedColor);
            
            matchingSizes.forEach(v => {
                const pill = document.createElement('div');
                // Check if sold out to style differently
                const isSoldOut = parseInt(v.stok) <= 0;
                pill.className = 'attribute-pill' + (isSoldOut ? ' text-decoration-line-through opacity-50 bg-light' : '');
                pill.innerText = v.ukuran;

                if (!isSoldOut) {
                    pill.addEventListener('click', () => {
                        sizeContainer.querySelectorAll('.attribute-pill').forEach(el => el.classList.remove('active'));
                        pill.classList.add('active');
                        
                        sizeInput.value = v.ukuran;
                        updateStockUI(v.stok);
                    });
                }
                sizeContainer.appendChild(pill);
            });
        }

        // Function to update stock displays
        function updateStockUI(stok) {
            stockBox.classList.remove('d-none');
            const stockCount = parseInt(stok);
            stockVal.innerText = `Stok Tersedia: ${stockCount} Pasang`;

            qtyInput.max = stockCount;
            if (parseInt(qtyInput.value) > stockCount) {
                qtyInput.value = stockCount;
            }

            if (stockCount > 0) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-cart-plus me-2"></i>Tambah Ke Keranjang';
            } else {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-2"></i>Stok Habis';
            }
        }

        // Quantity controls
        document.getElementById('btn-minus').addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val > 1) qtyInput.value = val - 1;
        });

        document.getElementById('btn-plus').addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            let max = parseInt(qtyInput.max) || 100;
            if (val < max) qtyInput.value = val + 1;
        });
    </script>
</body>
</html>
