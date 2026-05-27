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

    <div class="admin-layout">
        
        <!-- Admin Sidebar Menu -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-brand">
                <i class="fa-solid fa-shoe-prints me-2 text-primary"></i>SOCIETY ADMIN
            </div>
            <ul class="admin-menu">
                <li class="admin-menu-item">
                    <a href="<?= base_url('admin') ?>" class="admin-menu-link">
                        <i class="fa-solid fa-chart-line"></i>Dashboard
                    </a>
                </li>
                <li class="admin-menu-item">
                    <a href="<?= base_url('admin/products') ?>" class="admin-menu-link active">
                        <i class="fa-solid fa-boxes-stacked"></i>Produk Sepatu
                    </a>
                </li>
                <li class="admin-menu-item">
                    <a href="<?= base_url('admin/orders') ?>" class="admin-menu-link">
                        <i class="fa-solid fa-receipt"></i>Pesanan Masuk
                    </a>
                </li>
                <li class="admin-menu-item mt-5 pt-5 border-top border-secondary">
                    <a href="<?= base_url() ?>" class="admin-menu-link">
                        <i class="fa-solid fa-globe"></i>Lihat Toko
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Admin Content Area -->
        <main class="admin-content">
            
            <!-- Navbar Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h2 class="font-heading fw-bold mb-1"><?= isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' ?></h2>
                    <p class="text-muted mb-0">Lengkapi formulir informasi dasar dan atribut variasi stok sepatu.</p>
                </div>
                <div>
                    <a href="<?= base_url('admin/products') ?>" class="btn btn-secondary py-2 px-3">
                        <i class="fa-solid fa-arrow-left me-2"></i>Batal & Kembali
                    </a>
                </div>
            </div>

            <!-- Validation Errors -->
            <?php if (validation_errors()): ?>
                <div class="alert alert-danger mb-4">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Ada Kesalahan Pengisian:</strong>
                    <ul class="m-0 mt-1">
                        <?= validation_errors('<li>', '</li>') ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Product Form -->
            <form action="<?= isset($product) ? base_url('admin/products/edit/' . $product['id_produk']) : base_url('admin/products/add') ?>" 
                  method="POST" 
                  enctype="multipart/form-data">
                
                <div class="row">
                    <!-- Left: Basic Info -->
                    <div class="col-lg-7 mb-4">
                        <div class="bg-white p-4 rounded-4 border border-light shadow-sm">
                            <h4 class="fw-bold mb-4 border-bottom pb-2"><i class="fa-solid fa-circle-info me-2 text-primary"></i>Informasi Dasar</h4>
                            
                            <!-- Product Name -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Sepatu / Seri</label>
                                <input type="text" name="nama_produk" class="form-control py-2" placeholder="Contoh: Nike Air Jordan High" value="<?= isset($product) ? $product['nama_produk'] : set_value('nama_produk') ?>" required>
                            </div>

                            <div class="row">
                                <!-- Price -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Harga Jual (Rp)</label>
                                    <input type="number" name="harga" class="form-control py-2" placeholder="Contoh: 1500000" value="<?= isset($product) ? (int)$product['harga'] : set_value('harga') ?>" required>
                                </div>
                                <!-- Category -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Kategori</label>
                                    <select name="id_kategori" class="form-select py-2" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id_kategori'] ?>" 
                                                <?= (isset($product) && $product['id_kategori'] == $cat['id_kategori']) ? 'selected' : '' ?>>
                                                <?= $cat['nama_kategori'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Deskripsi Sepatu</label>
                                <textarea name="deskripsi" class="form-control" rows="6" placeholder="Masukkan ulasan bahan material, kecocokan, dan fitur sepatu..." required><?= isset($product) ? $product['deskripsi'] : set_value('deskripsi') ?></textarea>
                            </div>

                            <!-- Product Image -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Foto Sepatu</label>
                                <input type="file" name="gambar" class="form-control py-2">
                                <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengubah foto (Format: JPG/PNG/JPEG, Maksimal 2MB).</small>
                                
                                <?php if (isset($product)): ?>
                                    <div class="mt-3 p-2 border rounded-3 bg-light text-center" style="max-width: 150px;">
                                         <?php 
                                             $img_src = $product['gambar'];
                                             if (empty($product['gambar'])) {
                                                 $img_src = base_url('assets/images/default_shoe.svg');
                                             } elseif (!preg_match('/^https?:\/\//', $product['gambar'])) {
                                                 $img_src = base_url('assets/images/' . $product['gambar']);
                                                 if (!file_exists(FCPATH . 'assets/images/' . $product['gambar'])) {
                                                     $img_src = base_url('assets/images/default_shoe.svg');
                                                 }
                                             }
                                         ?>
                                        <img src="<?= $img_src ?>" alt="" class="img-fluid rounded" style="max-height: 100px;">
                                        <small class="text-muted d-block mt-1">Gambar Saat Ini</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Variations row editor -->
                    <div class="col-lg-5">
                        <div class="bg-white p-4 rounded-4 border border-light shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                <h4 class="fw-bold m-0"><i class="fa-solid fa-cubes me-2 text-primary"></i>Atribut Varian & Stok</h4>
                                <button type="button" class="btn btn-primary btn-sm py-1.5 px-3" id="btn-add-var">
                                    <i class="fa-solid fa-plus me-1"></i>Tambah Baris
                                </button>
                            </div>

                            <p class="text-muted" style="font-size: 0.85rem;">Input variasi kombinasi warna dan ukuran beserta stoknya. Atribut ini digunakan untuk filter sidebar pada katalog depan.</p>
                            
                            <div id="variations-container">
                                <!-- Row inputs populates here -->
                            </div>

                            <button type="submit" class="btn btn-primary btn-gradient w-100 py-3 mt-4">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Produk & Varian
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </main>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Variation Form Row Builder -->
    <script>
        const container = document.getElementById('variations-container');
        const addBtn = document.getElementById('btn-add-var');

        // Load existing variations if editing
        const existingVariations = <?= isset($variations) ? json_encode($variations) : '[]' ?>;

        // Function to create a variation input row
        function createRow(size = '', color = '', stock = 0) {
            const row = document.createElement('div');
            row.className = 'row g-2 mb-3 align-items-center border-bottom pb-3 variation-row';
            
            row.innerHTML = `
                <div class="col-md-3">
                    <label class="form-label fw-bold mb-1" style="font-size: 0.8rem;">Ukuran</label>
                    <input type="text" name="sizes[]" class="form-control py-1.5" placeholder="Contoh: 42" value="${size}" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold mb-1" style="font-size: 0.8rem;">Warna</label>
                    <input type="text" name="colors[]" class="form-control py-1.5" placeholder="Contoh: Triple Black" value="${color}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold mb-1" style="font-size: 0.8rem;">Stok</label>
                    <input type="number" name="stocks[]" class="form-control py-1.5" min="0" placeholder="0" value="${stock}" required>
                </div>
                <div class="col-md-1 align-self-end text-end">
                    <button type="button" class="btn btn-outline-danger btn-sm border-0 py-2 px-2.5 rounded-3 btn-remove-row" title="Hapus Baris">
                        <i class="fa-regular fa-trash-can fs-5"></i>
                    </button>
                </div>
            `;

            // Row remove event
            row.querySelector('.btn-remove-row').addEventListener('click', () => {
                row.remove();
            });

            container.appendChild(row);
        }

        // Initialize rows
        if (existingVariations.length > 0) {
            existingVariations.forEach(v => {
                createRow(v.ukuran, v.warna, v.stok);
            });
        } else {
            // Default 3 standard rows for easy input
            createRow('40', 'Black White', 10);
            createRow('41', 'Black White', 15);
            createRow('42', 'Black White', 12);
        }

        // Add new row event
        addBtn.addEventListener('click', () => {
            createRow('', '', 0);
        });
    </script>
</body>
</html>
