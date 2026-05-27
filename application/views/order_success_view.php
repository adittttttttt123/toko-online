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
        <div class="container justify-content-center">
            <a class="navbar-brand m-0" href="<?= base_url() ?>">
                <i class="fa-solid fa-shoe-prints me-2"></i>SOLESOCIETY
            </a>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container my-5" style="max-width: 800px;">
        
        <!-- Success Alert Header -->
        <div class="text-center mb-5">
            <div class="display-1 text-success mb-3">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h1 class="font-heading fw-bold">Pesanan Berhasil Dibuat!</h1>
            <p class="lead text-muted">Terima kasih atas pesanan Anda. Silakan selesaikan pembayaran agar pesanan segera diproses.</p>
        </div>

        <!-- Receipt / Invoice Card -->
        <div class="bg-white p-5 rounded-4 border border-light shadow-sm mb-4">
            
            <!-- Invoice Header -->
            <div class="d-flex justify-content-between align-items-center border-bottom pb-4 mb-4 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Invoice: <span class="text-primary"><?= $order['invoice_no'] ?></span></h5>
                    <small class="text-muted">Tanggal Pesan: <?= date('d M Y, H:i', strtotime($order['tgl_pesan'])) ?></small>
                </div>
                <div class="text-end">
                    <span class="badge bg-warning px-3 py-2 rounded-pill text-white fw-bold">MENUNGGU PEMBAYARAN</span>
                </div>
            </div>

            <!-- Recipient & Shipping Details -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size: 0.8rem;">Penerima & Alamat</h6>
                    <p class="mb-1"><strong><?= $order['nama_penerima'] ?></strong></p>
                    <p class="mb-1 text-muted" style="font-size: 0.9rem;"><i class="fa-solid fa-phone me-1"></i><?= $order['telepon'] ?></p>
                    <p class="mb-1 text-muted" style="font-size: 0.9rem;"><i class="fa-solid fa-envelope me-1"></i><?= $order['email'] ?></p>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;"><i class="fa-solid fa-location-dot me-1"></i><?= $order['alamat'] ?>, <?= $order['kota'] ?>, <?= $order['provinsi'] ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size: 0.8rem;">Pengiriman (Simulasi RajaOngkir)</h6>
                    <p class="mb-1">Kurir: <strong><?= $order['kurir'] ?> Express</strong></p>
                    <p class="mb-1 text-muted" style="font-size: 0.9rem;">Layanan: <?= $order['layanan'] ?></p>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;"><i class="fa-solid fa-truck-fast me-1"></i>Status: Menunggu Pembayaran</p>
                </div>
            </div>

            <!-- Items Purchased Table -->
            <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.8rem;">Detail Item Belanja</h6>
            <div class="table-responsive mb-4">
                <table class="table align-middle">
                    <thead>
                        <tr class="table-light">
                            <th>Item Sepatu</th>
                            <th class="text-center">Ukuran</th>
                            <th class="text-center">Warna</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php 
                                            $img_src = base_url('assets/images/' . $item['gambar']);
                                            if (empty($item['gambar']) || !file_exists(FCPATH . 'assets/images/' . $item['gambar'])) {
                                                $img_src = base_url('assets/images/default_shoe.svg');
                                            }
                                        ?>
                                        <img src="<?= $img_src ?>" alt="" class="rounded border" style="width: 40px; height: 40px; object-fit: cover;">
                                        <span class="fw-semibold" style="font-size: 0.9rem;"><?= $item['nama_produk'] ?></span>
                                    </div>
                                </td>
                                <td class="text-center"><?= $item['ukuran'] ?></td>
                                <td class="text-center"><?= $item['warna'] ?></td>
                                <td class="text-center"><?= $item['qty'] ?></td>
                                <td class="text-end" style="font-size: 0.9rem;">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td class="text-end fw-bold text-dark" style="font-size: 0.9rem;">Rp <?= number_format($item['qty'] * $item['harga'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pricing Details -->
            <div class="border-top pt-4 col-md-6 offset-md-6">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal Produk:</span>
                    <span class="fw-semibold">Rp <?= number_format($order['total_bayar'] - $order['ongkir'], 0, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ongkos Kirim (<?= $order['kurir'] ?>):</span>
                    <span class="fw-semibold">Rp <?= number_format($order['ongkir'], 0, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between border-top pt-2">
                    <span class="fw-bold fs-5">Total Bayar:</span>
                    <span class="fw-bold fs-4 text-primary">Rp <?= number_format($order['total_bayar'], 0, ',', '.') ?></span>
                </div>
            </div>

        </div>

        <!-- Payment Instructions Bank Accounts -->
        <div class="bg-white p-5 rounded-4 border border-light shadow-sm mb-4">
            <h4 class="fw-bold mb-4 font-heading text-center"><i class="fa-solid fa-university me-2 text-primary"></i>Instruksi Pembayaran</h4>
            
            <p class="text-center text-muted mb-4">Silakan transfer nominal total tagihan di atas ke salah satu rekening bank resmi kami di bawah ini:</p>
            
            <div class="row row-cols-1 row-cols-md-2 g-3">
                <div class="col">
                    <div class="p-3 border rounded-3 bg-light text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" style="height: 30px;" class="mb-2">
                        <h6 class="fw-bold mb-1">Bank BCA</h6>
                        <h5 class="fw-extrabold text-primary mb-1">872-049-1234</h5>
                        <small class="text-muted">a/n PT SOLESOCIETY INDONESIA</small>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 border rounded-3 bg-light text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" alt="Mandiri" style="height: 30px;" class="mb-2">
                        <h6 class="fw-bold mb-1">Bank Mandiri</h6>
                        <h5 class="fw-extrabold text-primary mb-1">137-00-1234-5678</h5>
                        <small class="text-muted">a/n PT SOLESOCIETY INDONESIA</small>
                    </div>
                </div>
            </div>

            <!-- Important Info -->
            <div class="alert alert-warning mt-4 d-flex gap-2" style="font-size: 0.85rem;">
                <i class="fa-solid fa-triangle-exclamation text-warning fs-4"></i>
                <div>
                    <strong>PENTING:</strong>
                    <p class="m-0 text-muted mt-1">Stok sepatu di sistem gudang belum dikurangi. Stok akan dikurangi secara otomatis begitu admin mengonfirmasi pembayaran Anda. Lakukan pembayaran segera sebelum kehabisan stok variasi ukuran/warna pilihan Anda!</p>
                </div>
            </div>
        </div>

        <!-- Call to Action Buttons -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="<?= base_url() ?>" class="btn btn-secondary px-4 py-2">
                <i class="fa-solid fa-shoe-prints me-2"></i>Kembali Berbelanja
            </a>
            <a href="<?= base_url('admin/orders') ?>" class="btn btn-primary px-4 py-2">
                <i class="fa-solid fa-gauge me-2"></i>Buka Admin Panel (Konfirmasi Pembayaran)
            </a>
        </div>

    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
