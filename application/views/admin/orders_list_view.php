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
                    <a href="<?= base_url('admin/products') ?>" class="admin-menu-link">
                        <i class="fa-solid fa-boxes-stacked"></i>Produk Sepatu
                    </a>
                </li>
                <li class="admin-menu-item">
                    <a href="<?= base_url('admin/orders') ?>" class="admin-menu-link active">
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
            <div class="mb-4">
                <h2 class="font-heading fw-bold mb-1">Manajemen Pesanan</h2>
                <p class="text-muted mb-0">Kelola konfirmasi pembayaran transaksi masuk dan kurir pengiriman barang.</p>
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

            <!-- Orders Table List -->
            <div class="bg-white p-4 rounded-4 border border-light shadow-sm">
                <?php if (empty($orders)): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-receipt text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5>Belum ada pesanan masuk!</h5>
                        <p class="text-muted">Semua pesanan checkout dari pelanggan akan masuk ke halaman log ini.</p>
                        <a href="<?= base_url() ?>" class="btn btn-primary mt-2">Lihat Toko</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="table-light">
                                    <th>No. Invoice & Tanggal</th>
                                    <th>Informasi Pembeli & Penerima</th>
                                    <th>Detail Belanja</th>
                                    <th>Pengiriman (RajaOngkir)</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 200px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $ord): ?>
                                    <tr>
                                        <!-- Invoice & Date -->
                                        <td>
                                            <div class="fw-bold text-primary mb-1"><?= $ord['invoice_no'] ?></div>
                                            <small class="text-muted d-block"><i class="fa-solid fa-calendar me-1"></i><?= date('d M Y', strtotime($ord['tgl_pesan'])) ?></small>
                                            <small class="text-muted d-block"><i class="fa-solid fa-clock me-1"></i><?= date('H:i', strtotime($ord['tgl_pesan'])) ?></small>
                                        </td>
                                        <!-- Buyer Info -->
                                        <td>
                                            <div class="fw-bold"><?= $ord['nama_penerima'] ?></div>
                                            <small class="text-muted d-block"><i class="fa-solid fa-phone me-1"></i><?= $ord['telepon'] ?></small>
                                            <small class="text-muted d-block"><i class="fa-solid fa-envelope me-1"></i><?= $ord['email'] ?></small>
                                            <small class="text-muted d-block text-truncate" style="max-width: 200px;"><i class="fa-solid fa-location-dot me-1"></i><?= $ord['alamat'] ?>, <?= $ord['kota'] ?></small>
                                        </td>
                                        <!-- Purchased Items -->
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <?php foreach ($ord['items'] as $item): ?>
                                                    <div class="border-bottom pb-1 mb-1" style="font-size: 0.85rem;">
                                                        <strong><?= $item['nama_produk'] ?></strong>
                                                        <div class="text-muted" style="font-size: 0.75rem;">
                                                            Ukuran: <?= $item['ukuran'] ?> | Warna: <?= $item['warna'] ?> | Qty: <?= $item['qty'] ?> Psg
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <!-- Shipping Courier Details -->
                                        <td>
                                            <div class="fw-bold mb-1"><?= $ord['kurir'] ?> Express</div>
                                            <small class="text-muted d-block">Layanan: <?= $ord['layanan'] ?></small>
                                            <small class="text-primary d-block">Ongkir: Rp <?= number_format($ord['ongkir'], 0, ',', '.') ?></small>
                                        </td>
                                        <!-- Grand Total -->
                                        <td class="fw-bold fs-5 text-dark">
                                            Rp <?= number_format($ord['total_bayar'], 0, ',', '.') ?>
                                        </td>
                                        <!-- Status Badge -->
                                        <td>
                                            <?php if ($ord['status'] === 'Paid'): ?>
                                                <span class="badge bg-success py-2 px-3 rounded-pill text-white"><i class="fa-solid fa-circle-check me-1"></i>Lunas</span>
                                                <small class="text-muted d-block mt-1 text-center" style="font-size: 0.7rem;"><?= date('d/m/y H:i', strtotime($ord['tgl_bayar'])) ?></small>
                                            <?php elseif ($ord['status'] === 'Cancelled'): ?>
                                                <span class="badge bg-secondary py-2 px-3 rounded-pill text-white"><i class="fa-solid fa-circle-xmark me-1"></i>Batal</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning py-2 px-3 rounded-pill text-white"><i class="fa-solid fa-clock me-1"></i>Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <!-- Actions -->
                                        <td class="text-center">
                                            <?php if ($ord['status'] === 'Pending'): ?>
                                                <div class="d-flex flex-column gap-2">
                                                    <a href="<?= base_url('admin/orders/confirm/' . $ord['id_pesanan']) ?>" 
                                                       class="btn btn-success btn-sm py-2 rounded-3 fw-semibold" 
                                                       onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pembayaran lunas untuk invoice <?= $ord['invoice_no'] ?>? Langkah ini secara otomatis akan mengurangi stok sepatu di database.')">
                                                        <i class="fa-solid fa-check-double me-1"></i>Konfirmasi Lunas
                                                    </a>
                                                    <a href="<?= base_url('admin/orders/cancel/' . $ord['id_pesanan']) ?>" 
                                                       class="btn btn-outline-danger btn-sm py-1.5 rounded-3" 
                                                       onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi <?= $ord['invoice_no'] ?>?')">
                                                        <i class="fa-solid fa-xmark me-1"></i>Batalkan Pesanan
                                                    </a>
                                                </div>
                                            <?php elseif ($ord['status'] === 'Paid'): ?>
                                                <a href="<?= base_url('admin/orders/cancel/' . $ord['id_pesanan']) ?>" 
                                                   class="btn btn-outline-secondary btn-sm py-1.5 w-100 rounded-3" 
                                                   onclick="return confirm('Batalkan transaksi yang telah lunas? Tindakan ini akan mengembalikan stok sepatu di database secara otomatis.')">
                                                    <i class="fa-solid fa-rotate-left me-1"></i>Batalkan & Kembalikan Stok
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
