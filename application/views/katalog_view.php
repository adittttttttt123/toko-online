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
                        <a class="nav-link <?= empty($active_filters['category']) ? 'active' : '' ?>" href="<?= base_url() ?>">Semua Koleksi</a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $active_filters['category'] === $cat['slug'] ? 'active' : '' ?>" 
                               href="<?= base_url('?category=' . $cat['slug']) ?>"><?= $cat['nama_kategori'] ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a href="<?= base_url('admin') ?>" class="btn btn-secondary py-2 px-3">
                        <i class="fa-solid fa-gauge me-2"></i>Admin Panel
                    </a>
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

        <!-- Hero Section -->
        <?php if (empty($active_filters['category']) && empty($active_filters['size']) && empty($active_filters['color']) && empty($active_filters['search'])): ?>
            <div class="hero-banner">
                <div class="col-lg-7">
                    <span class="hero-badge">Koleksi Eksklusif 2026</span>
                    <h1>Langkah Terbaik untuk Gaya Maksimal Anda</h1>
                    <p class="lead text-white-50 mb-4">Temukan kurasi sepatu premium dari merk terkemuka global dan lokal. Kenyamanan, gaya, dan presisi berpadu dalam setiap detail rajutan sepatu kami.</p>
                    <a href="#katalog" class="btn btn-gradient btn-lg">Mulai Belanja</a>
                </div>
            </div>
        <?php endif; ?>

        <div class="row" id="katalog">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-4">
                <div class="filter-card">
                    <h4 class="filter-title">Filter Produk</h4>
                    
                    <form action="<?= base_url() ?>" method="GET">
                        <!-- Keep category active filter if set -->
                        <?php if(!empty($active_filters['category'])): ?>
                            <input type="hidden" name="category" value="<?= $active_filters['category'] ?>">
                        <?php endif; ?>

                        <!-- Search -->
                        <div class="filter-group">
                            <h6 class="filter-group-title">Cari Nama/Brand</h6>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control py-2" placeholder="Cari sepatu..." value="<?= $active_filters['search'] ?>">
                                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>

                        <!-- Sizes Filter -->
                        <div class="filter-group">
                            <h6 class="filter-group-title">Ukuran Sepatu (EU)</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <?php foreach ($sizes as $sz): ?>
                                    <a href="<?= base_url(current_url() . '?' . http_build_query(array_merge($_GET, ['size' => $sz]))) ?>" 
                                       class="attribute-pill <?= $active_filters['size'] === $sz ? 'active' : '' ?>">
                                        <?= $sz ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <?php if(!empty($active_filters['size'])): ?>
                                <input type="hidden" name="size" value="<?= $active_filters['size'] ?>">
                            <?php endif; ?>
                        </div>

                        <!-- Colors Filter -->
                        <div class="filter-group">
                            <h6 class="filter-group-title">Warna Varian</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <?php foreach ($colors as $col): ?>
                                    <a href="<?= base_url(current_url() . '?' . http_build_query(array_merge($_GET, ['color' => $col]))) ?>" 
                                       class="attribute-pill <?= $active_filters['color'] === $col ? 'active' : '' ?>">
                                        <?= $col ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <?php if(!empty($active_filters['color'])): ?>
                                <input type="hidden" name="color" value="<?= $active_filters['color'] ?>">
                            <?php endif; ?>
                        </div>

                        <!-- Clear filters button -->
                        <?php if (array_filter($active_filters)): ?>
                            <a href="<?= base_url() ?>" class="btn btn-secondary w-100 mt-2">
                                <i class="fa-solid fa-arrows-rotate me-2"></i>Reset Filter
                            </a>
                        <?php endif; ?>
                        
                        <button type="submit" class="btn btn-primary w-100 mt-3 d-none">Terapkan</button>
                    </form>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="m-0">
                        <?php 
                            if (!empty($active_filters['category'])) {
                                echo 'Koleksi ' . ucfirst($active_filters['category']);
                            } else {
                                echo 'Semua Sepatu';
                            }
                        ?>
                    </h3>
                    <span class="text-muted"><?= count($products) ?> Produk Ditemukan</span>
                </div>

                <?php if (empty($products)): ?>
                    <div class="text-center py-5 bg-white rounded-4 border border-light shadow-sm">
                        <i class="fa-solid fa-shoe-prints text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5>Maaf, produk tidak ditemukan!</h5>
                        <p class="text-muted">Cobalah mereset filter atau cari kata kunci lain.</p>
                        <a href="<?= base_url() ?>" class="btn btn-primary mt-2">Lihat Semua Produk</a>
                    </div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php foreach ($products as $prod): ?>
                            <div class="col">
                                <div class="product-card">
                                    <div class="product-img-wrapper">
                                         <?php 
                                             $img_src = base_url('assets/images/' . $prod['gambar']);
                                             if (empty($prod['gambar']) || !file_exists(FCPATH . 'assets/images/' . $prod['gambar'])) {
                                                 $img_src = base_url('assets/images/default_shoe.svg');
                                             }
                                         ?>
                                         <img src="<?= $img_src ?>" class="product-img" alt="<?= $prod['nama_produk'] ?>">
                                        <span class="product-cat"><?= $prod['nama_kategori'] ?></span>
                                    </div>
                                    <div class="product-content">
                                        <h5 class="product-title"><?= $prod['nama_produk'] ?></h5>
                                        <div class="product-price">Rp <?= number_format($prod['harga'], 0, ',', '.') ?></div>
                                        <p class="text-muted flex-grow-1 text-truncate-2" style="font-size: 0.85rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= $prod['deskripsi'] ?>
                                        </p>
                                        
                                        <!-- Stock Check / Variation Detail Redirection -->
                                        <div class="mt-3">
                                            <a href="<?= base_url('shop/detail/' . $prod['id_produk']) ?>" class="btn btn-primary w-100 py-2">
                                                <i class="fa-solid fa-eye me-2"></i>Pilih Ukuran & Warna
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white-50 py-5 mt-5 border-top border-secondary">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="text-white mb-3 font-heading"><i class="fa-solid fa-shoe-prints me-2 text-primary"></i>SOLESOCIETY</h5>
                    <p>Destinasi belanja sepatu premium terlengkap dengan integrasi pengiriman handal seluruh Indonesia. Kualitas terbaik, kepuasan terjamin.</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="text-white mb-3">Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url() ?>" class="text-white-50 text-decoration-none hover-white">Katalog Sepatu</a></li>
                        <li><a href="<?= base_url('cart') ?>" class="text-white-50 text-decoration-none hover-white">Keranjang Belanja</a></li>
                        <li><a href="<?= base_url('admin') ?>" class="text-white-50 text-decoration-none hover-white">Admin Dashboard</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="text-white mb-3">Kontak & Hubungi</h5>
                    <p class="m-0"><i class="fa-solid fa-envelope me-2"></i>support@solesociety.com</p>
                    <p class="m-0"><i class="fa-solid fa-phone me-2"></i>+62 812-3456-7890</p>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <p class="m-0">&copy; 2026 SOLESOCIETY. All rights reserved.</p>
                <div class="d-flex gap-3">
                    <i class="fa-brands fa-cc-visa fs-4"></i>
                    <i class="fa-brands fa-cc-mastercard fs-4"></i>
                    <i class="fa-solid fa-wallet fs-4"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
