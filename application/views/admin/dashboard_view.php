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
                    <a href="<?= base_url('admin') ?>" class="admin-menu-link active">
                        <i class="fa-solid fa-chart-line"></i>Dashboard
                    </a>
                </li>
                <li class="admin-menu-item">
                    <a href="<?= base_url('admin/products') ?>" class="admin-menu-link">
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
                    <h2 class="font-heading fw-bold mb-1">Dashboard Ringkasan</h2>
                    <p class="text-muted mb-0">Selamat datang kembali, Administrator.</p>
                </div>
                <div class="text-muted">
                    <i class="fa-solid fa-calendar me-2"></i> Hari ini: <?= date('d M Y') ?>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i><?= $this->session->flashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Dashboard Stats Summary Cards -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
                <!-- Total Revenue -->
                <div class="col">
                    <div class="admin-card admin-card-success h-100">
                        <div class="admin-card-title">Total Omset Penjualan</div>
                        <div class="admin-card-value">Rp <?= number_format($stats['total_revenue'], 0, ',', '.') ?></div>
                        <i class="fa-solid fa-money-bill-wave admin-icon"></i>
                    </div>
                </div>
                <!-- Total Orders -->
                <div class="col">
                    <div class="admin-card admin-card-primary h-100">
                        <div class="admin-card-title">Total Transaksi</div>
                        <div class="admin-card-value"><?= $stats['total_orders'] ?> Order</div>
                        <i class="fa-solid fa-cart-shopping admin-icon"></i>
                    </div>
                </div>
                <!-- Pending Orders -->
                <div class="col">
                    <div class="admin-card admin-card-warning h-100">
                        <div class="admin-card-title">Menunggu Konfirmasi</div>
                        <div class="admin-card-value"><?= $stats['pending_orders'] ?> Order</div>
                        <i class="fa-solid fa-clock admin-icon"></i>
                    </div>
                </div>
                <!-- Total Products -->
                <div class="col">
                    <div class="admin-card admin-card-danger h-100">
                        <div class="admin-card-title">Katalog Produk</div>
                        <div class="admin-card-value"><?= $stats['total_products'] ?> Sepatu</div>
                        <i class="fa-solid fa-box admin-icon"></i>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <!-- Top Selling Products -->
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="bg-white p-4 rounded-4 border border-light shadow-sm h-100">
                        <h4 class="fw-bold mb-4 border-bottom pb-2"><i class="fa-solid fa-star text-warning me-2"></i>Produk Paling Laris</h4>
                        <?php if (empty($stats['top_selling'])): ?>
                            <p class="text-muted py-3">Belum ada transaksi pembayaran dikonfirmasi.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table align-middle m-0">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Sepatu</th>
                                            <th class="text-center">Terjual</th>
                                            <th class="text-end">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stats['top_selling'] as $top): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <img src="<?= base_url('assets/images/' . $top['gambar']) ?>" alt="" class="rounded border" style="width: 40px; height: 40px; object-fit: cover;">
                                                        <span class="fw-bold"><?= $top['nama_produk'] ?></span>
                                                    </div>
                                                </td>
                                                <td class="text-center fw-bold text-success"><?= $top['total_sold'] ?> Pasang</td>
                                                <td class="text-end">Rp <?= number_format($top['harga'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sales breakdown by Category -->
                <div class="col-lg-5">
                    <div class="bg-white p-4 rounded-4 border border-light shadow-sm h-100">
                        <h4 class="fw-bold mb-4 border-bottom pb-2"><i class="fa-solid fa-pie-chart text-primary me-2"></i>Kategori Populer</h4>
                        <?php if (empty($stats['category_sales'])): ?>
                            <p class="text-muted py-3">Belum ada data kategori.</p>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-3">
                                <?php foreach ($stats['category_sales'] as $cat_sale): ?>
                                    <div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-bold"><?= $cat_sale['nama_kategori'] ?></span>
                                            <span class="text-muted"><?= $cat_sale['total_qty'] ?> Pasang (<?= $cat_sale['transaction_count'] ?> Transaksi)</span>
                                        </div>
                                        <div class="progress" style="height: 10px; border-radius: 10px;">
                                            <?php 
                                                // Dynamic percentage color calculation
                                                $pct = min(100, $cat_sale['total_qty'] * 10); 
                                            ?>
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $pct ?>%; border-radius: 10px;"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="bg-white p-4 rounded-4 border border-light shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h4 class="fw-bold m-0"><i class="fa-solid fa-receipt text-primary me-2"></i>Aktivitas Transaksi Terbaru</h4>
                    <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline-primary btn-sm py-2 px-3">Semua Pesanan</a>
                </div>

                <?php if (empty($stats['recent_orders'])): ?>
                    <p class="text-muted text-center py-4">Belum ada pesanan masuk.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="table-light">
                                    <th>No. Invoice</th>
                                    <th>Pembeli</th>
                                    <th>Tanggal</th>
                                    <th>Ongkir</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['recent_orders'] as $ord): ?>
                                    <tr>
                                        <td class="fw-bold text-primary"><?= $ord['invoice_no'] ?></td>
                                        <td>
                                            <div class="fw-bold"><?= $ord['nama_penerima'] ?></div>
                                            <small class="text-muted"><?= $ord['kota'] ?></small>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($ord['tgl_pesan'])) ?></td>
                                        <td>Rp <?= number_format($ord['ongkir'], 0, ',', '.') ?></td>
                                        <td class="fw-bold">Rp <?= number_format($ord['total_bayar'], 0, ',', '.') ?></td>
                                        <td>
                                            <?php if ($ord['status'] === 'Paid'): ?>
                                                <span class="badge bg-success py-2 px-3 rounded-pill text-white"><i class="fa-solid fa-circle-check me-1"></i>Lunas</span>
                                            <?php elseif ($ord['status'] === 'Cancelled'): ?>
                                                <span class="badge bg-secondary py-2 px-3 rounded-pill text-white"><i class="fa-solid fa-circle-xmark me-1"></i>Batal</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning py-2 px-3 rounded-pill text-white"><i class="fa-solid fa-clock me-1"></i>Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($ord['status'] === 'Pending'): ?>
                                                <a href="<?= base_url('admin/orders/confirm/' . $ord['id_pesanan']) ?>" 
                                                   class="btn btn-success btn-sm py-2 px-3 rounded-3" 
                                                   onclick="return confirm('Konfirmasi pembayaran untuk invoice <?= $ord['invoice_no'] ?>? Tindakan ini akan memotong stok secara otomatis.')">
                                                    <i class="fa-solid fa-check me-1"></i>Konfirmasi Lunas
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
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
