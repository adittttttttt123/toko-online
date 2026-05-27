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
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="font-heading fw-bold mb-1">Manajemen Produk</h2>
                    <p class="text-muted mb-0">Kelola katalog sepatu, warna, ukuran, dan stok barang.</p>
                </div>
                <div>
                    <a href="<?= base_url('admin/products/add') ?>" class="btn btn-primary py-2 px-3">
                        <i class="fa-solid fa-plus me-2"></i>Tambah Produk Baru
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i><?= $this->session->flashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i><?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Products Listing Table -->
            <div class="bg-white p-4 rounded-4 border border-light shadow-sm">
                <?php if (empty($products)): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-boxes-stacked text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5>Belum ada produk terdaftar!</h5>
                        <p class="text-muted">Silakan tambahkan produk sepatu baru untuk meluncurkan toko Anda.</p>
                        <a href="<?= base_url('admin/products/add') ?>" class="btn btn-primary mt-2">Tambah Produk</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="table-light">
                                    <th>Sepatu</th>
                                    <th>Kategori</th>
                                    <th>Harga Jual</th>
                                    <th>Detail Atribut Varian (Ukuran | Warna | Stok)</th>
                                    <th class="text-center" style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $prod): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                 <?php 
                                                     $img_src = $prod['gambar'];
                                                     if (empty($prod['gambar'])) {
                                                         $img_src = base_url('assets/images/default_shoe.svg');
                                                     } elseif (!preg_match('/^https?:\/\//', $prod['gambar'])) {
                                                         $img_src = base_url('assets/images/' . $prod['gambar']);
                                                         if (!file_exists(FCPATH . 'assets/images/' . $prod['gambar'])) {
                                                             $img_src = base_url('assets/images/default_shoe.svg');
                                                         }
                                                     }
                                                 ?>
                                                <img src="<?= $img_src ?>" 
                                                     alt="<?= $prod['nama_produk'] ?>" 
                                                     class="rounded border" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="fw-bold mb-1"><?= $prod['nama_produk'] ?></h6>
                                                    <small class="text-muted d-block text-truncate" style="max-width: 250px;"><?= $prod['deskripsi'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1.5"><?= $prod['nama_kategori'] ?></span>
                                        </td>
                                        <td class="fw-bold text-primary">Rp <?= number_format($prod['harga'], 0, ',', '.') ?></td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php 
                                                    // Fetch variations for this product dynamically
                                                    $variations = $this->Produk_model->get_product_variations($prod['id_produk']);
                                                    if (empty($variations)):
                                                ?>
                                                    <span class="text-danger fw-bold" style="font-size: 0.8rem;"><i class="fa-solid fa-triangle-exclamation"></i> Belum ada atribut varian!</span>
                                                <?php else: ?>
                                                    <?php foreach ($variations as $v): ?>
                                                        <span class="badge px-2 py-1.5 <?= $v['stok'] > 0 ? 'bg-light text-dark border' : 'bg-danger-subtle text-danger border border-danger-subtle' ?>" style="font-size: 0.75rem;">
                                                            <?= $v['ukuran'] ?> | <?= $v['warna'] ?> : <strong><?= $v['stok'] ?> Psg</strong>
                                                        </span>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="<?= base_url('admin/products/edit/' . $prod['id_produk']) ?>" class="btn btn-outline-primary btn-sm px-3 py-2 rounded-3" title="Edit Produk">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="<?= base_url('admin/products/delete/' . $prod['id_produk']) ?>" 
                                                   class="btn btn-outline-danger btn-sm px-3 py-2 rounded-3" 
                                                   title="Hapus Produk" 
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini beserta seluruh atribut varian ukurannya?')">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
