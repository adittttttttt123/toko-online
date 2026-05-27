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
                <a href="<?= base_url('cart') ?>" class="btn btn-secondary py-2 px-3">
                    <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Keranjang
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container my-5">
        
        <h1 class="font-heading mb-4"><i class="fa-solid fa-credit-card text-primary me-2"></i>Pengiriman & Pembayaran</h1>

        <!-- Validation Errors / Flash Messages -->
        <?php if (validation_errors()): ?>
            <div class="alert alert-danger mb-4">
                <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Kesalahan Pengisian:</strong>
                <ul class="m-0 mt-1">
                    <?= validation_errors('<li>', '</li>') ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger mb-4">
                <i class="fa-solid fa-triangle-exclamation me-2"></i><?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('checkout/process') ?>" method="POST" id="checkout-form">
            <div class="row">
                <!-- Left Column: Shipping Address & RajaOngkir simulation -->
                <div class="col-lg-7 mb-4">
                    <div class="bg-white p-4 rounded-4 border border-light shadow-sm">
                        <h4 class="fw-bold mb-4 border-bottom pb-2"><i class="fa-solid fa-map-location-dot me-2 text-primary"></i>Alamat Pengiriman</h4>
                        
                        <!-- Recipient Name -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Nama Lengkap Penerima</label>
                                <input type="text" name="nama_penerima" class="form-control py-2" placeholder="Contoh: Budi Santoso" value="<?= set_value('nama_penerima') ?>" required>
                            </div>
                        </div>

                        <!-- Email & Phone -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Alamat Email</label>
                                <input type="email" name="email" class="form-control py-2" placeholder="budi@example.com" value="<?= set_value('email') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor Telepon</label>
                                <input type="tel" name="telepon" class="form-control py-2" placeholder="0812XXXXXXXX" value="<?= set_value('telepon') ?>" required>
                            </div>
                        </div>

                        <!-- Complete Address -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Rumah Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan nama jalan, nomor rumah, RT/RW, dan detail patokan..." required><?= set_value('alamat') ?></textarea>
                        </div>

                        <!-- Province Dropdown (RajaOngkir Simulation) -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Provinsi</label>
                                <select name="provinsi" id="provinsi" class="form-select py-2" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                    <?php foreach ($provinces as $p): ?>
                                        <option value="<?= $p ?>" <?= set_value('provinsi') === $p ? 'selected' : '' ?>><?= $p ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- City Dropdown (Populates via AJAX) -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kabupaten / Kota</label>
                                <select name="kota" id="kota" class="form-select py-2" required disabled>
                                    <option value="">-- Pilih Provinsi Dulu --</option>
                                </select>
                            </div>
                        </div>

                        <!-- Courier Dropdown (RajaOngkir Simulation) -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Kurir Pengiriman</label>
                                <select name="kurir" id="kurir" class="form-select py-2" required disabled>
                                    <option value="">-- Pilih Kota Dulu --</option>
                                    <option value="JNE">JNE Express</option>
                                    <option value="J&T">J&T Express</option>
                                    <option value="POS">POS Indonesia</option>
                                </select>
                            </div>
                            <!-- Shipping Service Options (Loads via AJAX) -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Layanan Kurir & Ongkir</label>
                                <select name="layanan" id="layanan" class="form-select py-2" required disabled>
                                    <option value="">-- Pilih Kurir Dulu --</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Column: Order Summary & Pricing -->
                <div class="col-lg-5">
                    <div class="filter-card">
                        <h4 class="filter-title">Tinjauan Pesanan</h4>

                        <!-- Items List -->
                        <div class="mb-4" style="max-height: 250px; overflow-y: auto;">
                            <?php foreach ($cart_items as $item): ?>
                                <div class="d-flex align-items-center gap-3 border-bottom pb-3 mb-3">
                                    <?php 
                                        $img_src = base_url('assets/images/' . $item['options']['Image']);
                                        if (empty($item['options']['Image']) || !file_exists(FCPATH . 'assets/images/' . $item['options']['Image'])) {
                                            $img_src = base_url('assets/images/default_shoe.svg');
                                        }
                                    ?>
                                    <img src="<?= $img_src ?>" 
                                         alt="<?= $item['name'] ?>" 
                                         class="rounded-3 border" 
                                         style="width: 55px; height: 55px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0" style="font-size: 0.9rem;"><?= $item['name'] ?></h6>
                                        <small class="text-muted">Ukuran: <?= $item['options']['Size'] ?> | Warna: <?= $item['options']['Color'] ?></small>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <span class="text-muted" style="font-size: 0.8rem;"><?= $item['qty'] ?> Pasang x Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                                            <span class="fw-bold text-dark" style="font-size: 0.85rem;">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Subtotal -->
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                            <span class="text-muted">Subtotal Belanja</span>
                            <span class="fw-bold" id="cart-subtotal" data-value="<?= $total ?>">Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>

                        <!-- Shipping Cost Display -->
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span class="fw-bold text-primary" id="shipping-cost-display">Pilih Layanan</span>
                        </div>

                        <!-- Grand Total -->
                        <div class="d-flex justify-content-between mb-4 border-bottom pb-3">
                            <span class="fw-bold fs-5">Total Pembayaran</span>
                            <span class="fw-extrabold fs-4 text-primary" id="grand-total-display">Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>

                        <!-- Payment Instructions Box -->
                        <div class="alert alert-light border d-flex gap-2 mb-4" style="font-size: 0.85rem;">
                            <i class="fa-solid fa-circle-info text-primary fs-5"></i>
                            <div>
                                <strong>Metode Pembayaran Transfer Bank</strong>
                                <p class="m-0 text-muted mt-1">Selesaikan pesanan untuk mendapatkan instruksi lengkap transfer bank. Pesanan Anda akan kami proses dan stok akan dikurangi secara real-time setelah admin mengonfirmasi pembayaran Anda.</p>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-gradient w-100 py-3 btn-lg" id="btn-submit-order" disabled>
                            <i class="fa-solid fa-lock me-2"></i>Buat Pesanan & Bayar
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white-50 py-5 mt-5 border-top border-secondary">
        <div class="container text-center">
            <p class="m-0">&copy; 2026 SOLESOCIETY. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AJAX RajaOngkir-like Simulation Script -->
    <script>
        const provSelect = document.getElementById('provinsi');
        const citySelect = document.getElementById('kota');
        const courierSelect = document.getElementById('kurir');
        const serviceSelect = document.getElementById('layanan');
        
        const subtotal = parseFloat(document.getElementById('cart-subtotal').getAttribute('data-value'));
        const shippingDisplay = document.getElementById('shipping-cost-display');
        const grandDisplay = document.getElementById('grand-total-display');
        const submitBtn = document.getElementById('btn-submit-order');

        // Helper to format currency
        function formatRupiah(num) {
            return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // 1. Province Selected -> Fetch Cities
        provSelect.addEventListener('change', function() {
            const val = this.value;
            citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
            courierSelect.innerHTML = '<option value="">-- Pilih Kota Dulu --</option>';
            serviceSelect.innerHTML = '<option value="">-- Pilih Kurir Dulu --</option>';
            
            citySelect.disabled = true;
            courierSelect.disabled = true;
            serviceSelect.disabled = true;
            submitBtn.disabled = true;
            shippingDisplay.innerText = 'Pilih Layanan';
            grandDisplay.innerText = formatRupiah(subtotal);

            if (!val) return;

            // Fetch cities using mock AJAX
            fetch('<?= base_url('shop/get_cities_ajax') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'province=' + encodeURIComponent(val)
            })
            .then(res => res.json())
            .then(data => {
                data.forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city;
                    opt.innerText = city;
                    citySelect.appendChild(opt);
                });
                citySelect.disabled = false;
                citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>' + citySelect.innerHTML;
            });
        });

        // 2. City Selected -> Enable Courier
        citySelect.addEventListener('change', function() {
            const val = this.value;
            serviceSelect.innerHTML = '<option value="">-- Pilih Kurir Dulu --</option>';
            serviceSelect.disabled = true;
            submitBtn.disabled = true;
            shippingDisplay.innerText = 'Pilih Layanan';
            grandDisplay.innerText = formatRupiah(subtotal);

            if (val) {
                courierSelect.innerHTML = `
                    <option value="">-- Pilih Kurir --</option>
                    <option value="JNE">JNE Express</option>
                    <option value="J&T">J&T Express</option>
                    <option value="POS">POS Indonesia</option>
                `;
                courierSelect.disabled = false;
            } else {
                courierSelect.innerHTML = '<option value="">-- Pilih Kota Dulu --</option>';
                courierSelect.disabled = true;
            }
        });

        // 3. Courier Selected -> Fetch Costs
        courierSelect.addEventListener('change', function() {
            const val = this.value;
            const city = citySelect.value;
            
            serviceSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
            serviceSelect.disabled = true;
            submitBtn.disabled = true;
            shippingDisplay.innerText = 'Pilih Layanan';
            grandDisplay.innerText = formatRupiah(subtotal);

            if (!val) return;

            // Fetch simulated shipping costs using AJAX
            fetch('<?= base_url('shop/get_shipping_costs_ajax') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `city=${encodeURIComponent(city)}&courier=${encodeURIComponent(val)}`
            })
            .then(res => res.json())
            .then(data => {
                data.forEach(srv => {
                    const opt = document.createElement('option');
                    // We store service name and cost combined with a pipe char e.g. "REG|15000"
                    opt.value = `${srv.service}|${srv.cost}`;
                    opt.innerText = `${srv.service} - ${formatRupiah(srv.cost)} (${srv.etd})`;
                    serviceSelect.appendChild(opt);
                });
                serviceSelect.disabled = false;
                serviceSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>' + serviceSelect.innerHTML;
            });
        });

        // 4. Service Selected -> Calculate Totals
        serviceSelect.addEventListener('change', function() {
            const val = this.value;
            if (!val) {
                shippingDisplay.innerText = 'Pilih Layanan';
                grandDisplay.innerText = formatRupiah(subtotal);
                submitBtn.disabled = true;
                return;
            }

            const parts = val.split('|');
            const cost = parseFloat(parts[1]) || 0;

            shippingDisplay.innerText = formatRupiah(cost);
            grandDisplay.innerText = formatRupiah(subtotal + cost);
            submitBtn.disabled = false;
        });
    </script>
</body>
</html>
