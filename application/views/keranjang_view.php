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
            <div class="d-flex align-items-center gap-3">
                <a href="<?= base_url() ?>" class="btn btn-secondary py-2 px-3">
                    <i class="fa-solid fa-arrow-left me-2"></i>Lanjut Belanja
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container my-5">
        
        <h1 class="font-heading mb-4"><i class="fa-solid fa-bag-shopping text-primary me-2"></i>Keranjang Belanja</h1>

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

        <?php if (empty($cart_items)): ?>
            <div class="text-center py-5 bg-white rounded-4 border border-light shadow-sm my-5">
                <i class="fa-solid fa-cart-shopping text-muted mb-3" style="font-size: 4rem;"></i>
                <h4 class="fw-bold">Keranjang belanja Anda kosong</h4>
                <p class="text-muted mb-4">Anda belum memasukkan sepasang sepatu pun ke dalam keranjang.</p>
                <a href="<?= base_url() ?>" class="btn btn-primary btn-lg px-4">
                    <i class="fa-solid fa-shoe-prints me-2"></i>Temukan Sepatu Impian Anda
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <!-- Left: Items Table -->
                <div class="col-lg-8 mb-4">
                    <div class="table-responsive">
                        <table class="table table-cart align-middle">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th style="width: 150px;">Jumlah</th>
                                    <th>Subtotal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                 <?php 
                                                     $img_src = $item['options']['Image'];
                                                     if (empty($item['options']['Image'])) {
                                                         $img_src = base_url('assets/images/default_shoe.svg');
                                                     } elseif (!preg_match('/^https?:\/\//', $item['options']['Image'])) {
                                                         $img_src = base_url('assets/images/' . $item['options']['Image']);
                                                         if (!file_exists(FCPATH . 'assets/images/' . $item['options']['Image'])) {
                                                             $img_src = base_url('assets/images/default_shoe.svg');
                                                         }
                                                     }
                                                 ?>
                                                <img src="<?= $img_src ?>" 
                                                     alt="<?= $item['name'] ?>" 
                                                     class="rounded-3 border" 
                                                     style="width: 70px; height: 70px; object-fit: cover;">
                                                <div>
                                                    <h6 class="fw-bold mb-1"><?= $item['name'] ?></h6>
                                                    <div class="d-flex gap-2">
                                                        <span class="badge bg-light text-dark border">Ukuran: <?= $item['options']['Size'] ?></span>
                                                        <span class="badge bg-light text-dark border">Warna: <?= $item['options']['Color'] ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                        <td>
                                            <form action="<?= base_url('cart/update') ?>" method="POST" class="qty-form">
                                                <input type="hidden" name="rowid" value="<?= $item['rowid'] ?>">
                                                <div class="qty-control">
                                                    <button type="button" class="qty-btn btn-minus"><i class="fa-solid fa-minus"></i></button>
                                                    <input type="number" name="qty" class="qty-input" value="<?= $item['qty'] ?>" min="1" readonly>
                                                    <button type="button" class="qty-btn btn-plus"><i class="fa-solid fa-plus"></i></button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="fw-bold text-primary">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('cart/remove/' . $item['rowid']) ?>" class="btn btn-outline-danger btn-sm border-0 py-2 px-3 rounded-3" title="Hapus Item">
                                                <i class="fa-regular fa-trash-can fs-5"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="<?= base_url('cart/clear') ?>" class="btn btn-secondary text-danger border-danger" onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">
                            <i class="fa-solid fa-trash me-2"></i>Kosongkan Keranjang
                        </a>
                        <a href="<?= base_url() ?>" class="btn btn-secondary">
                            <i class="fa-solid fa-shoe-prints me-2"></i>Kembali Berbelanja
                        </a>
                    </div>
                </div>

                <!-- Right: Order Summary Card -->
                <div class="col-lg-4">
                    <div class="filter-card">
                        <h4 class="filter-title">Ringkasan Belanja</h4>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Jumlah Item</span>
                            <span class="fw-bold"><?= $this->cart->total_items() ?> Pasang</span>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Subtotal Produk</span>
                            <span class="fw-bold fs-5 text-primary">Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>

                        <div class="alert alert-success d-flex gap-2" style="font-size: 0.85rem;">
                            <i class="fa-solid fa-truck-fast text-success fs-5"></i>
                            <div>
                                <strong>Integrasi Kurir RajaOngkir</strong>
                                <p class="m-0 text-muted mt-1">Ongkos kirim dan tracking resi kurir akan dihitung secara dinamis pada langkah selanjutnya (Checkout).</p>
                            </div>
                        </div>

                        <hr class="my-4 border-light">

                        <a href="<?= base_url('checkout') ?>" class="btn btn-primary btn-gradient w-100 py-3">
                            <i class="fa-solid fa-credit-card me-2"></i>Lanjut Ke Checkout
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white-50 py-5 mt-5 border-top border-secondary">
        <div class="container text-center">
            <p class="m-0">&copy; 2026 SOLESOCIETY. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Quantity Sync Script -->
    <script>
        document.querySelectorAll('.qty-control').forEach(ctrl => {
            const form = ctrl.closest('form');
            const input = ctrl.querySelector('.qty-input');
            const minusBtn = ctrl.querySelector('.btn-minus');
            const plusBtn = ctrl.querySelector('.btn-plus');

            minusBtn.addEventListener('click', () => {
                let val = parseInt(input.value);
                if (val > 1) {
                    input.value = val - 1;
                    form.submit();
                } else if (val === 1) {
                    if (confirm('Hapus item ini dari keranjang?')) {
                        input.value = 0;
                        form.submit();
                    }
                }
            });

            plusBtn.addEventListener('click', () => {
                let val = parseInt(input.value);
                input.value = val + 1;
                form.submit();
            });
        });
    </script>
</body>
</html>
