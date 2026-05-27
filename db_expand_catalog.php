<?php
$host = '127.0.0.1';
$db   = 'toko_sepatu';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected to the database successfully.\n";

    // Products to add
    $new_products = [
        [
            'nama_produk' => 'Vans Old Skool',
            'deskripsi' => 'Sneakers skate klasik dengan bahan kanvas dan suede berkualitas tinggi. Dilengkapi dengan garis samping ikonik (jazz stripe) dan sol karet bermotif waffle yang legendaris, kuat, dan anti-slip.',
            'harga' => 900000,
            'id_kategori' => 1,
            'gambar' => 'vans_oldskool.jpg',
            'details' => [
                ['ukuran' => '39', 'warna' => 'Black White', 'stok' => 20],
                ['ukuran' => '40', 'warna' => 'Black White', 'stok' => 25],
                ['ukuran' => '41', 'warna' => 'Black White', 'stok' => 30],
                ['ukuran' => '42', 'warna' => 'Black White', 'stok' => 15],
                ['ukuran' => '40', 'warna' => 'Navy Blue', 'stok' => 12],
                ['ukuran' => '41', 'warna' => 'Navy Blue', 'stok' => 18],
            ]
        ],
        [
            'nama_produk' => 'Converse Chuck 70',
            'deskripsi' => 'Dibuat dengan kanvas katun organik premium 12 oz yang lebih tebal dan kokoh, sol karet mengkilap bergaya vintage, jahitan samping yang klasik, serta insole empuk untuk kenyamanan sepanjang hari.',
            'harga' => 1000000,
            'id_kategori' => 1,
            'gambar' => 'converse_chuck70.jpg',
            'details' => [
                ['ukuran' => '40', 'warna' => 'Parchment', 'stok' => 15],
                ['ukuran' => '41', 'warna' => 'Parchment', 'stok' => 20],
                ['ukuran' => '42', 'warna' => 'Parchment', 'stok' => 15],
                ['ukuran' => '41', 'warna' => 'Black', 'stok' => 18],
                ['ukuran' => '42', 'warna' => 'Black', 'stok' => 22],
                ['ukuran' => '43', 'warna' => 'Black', 'stok' => 10],
            ]
        ],
        [
            'nama_produk' => 'Puma Nitro Velocity',
            'deskripsi' => 'Sepatu lari berperforma tinggi dengan bantalan busa NITRO-infused yang sangat empuk dan responsif namun tetap ultra-ringan. Dilengkapi outsole karet PUMAGRIP untuk traksi luar biasa di segala medan.',
            'harga' => 1800000,
            'id_kategori' => 2,
            'gambar' => 'puma_nitro.jpg',
            'details' => [
                ['ukuran' => '41', 'warna' => 'Fireglow Red', 'stok' => 8],
                ['ukuran' => '42', 'warna' => 'Fireglow Red', 'stok' => 12],
                ['ukuran' => '43', 'warna' => 'Fireglow Red', 'stok' => 10],
                ['ukuran' => '41', 'warna' => 'Electric Blue', 'stok' => 10],
                ['ukuran' => '42', 'warna' => 'Electric Blue', 'stok' => 15],
            ]
        ],
        [
            'nama_produk' => 'Reagan Derby Black',
            'deskripsi' => 'Sepatu formal Derby klasik bernuansa minimalis nan maskulin. Terbuat dari kulit sapi asli premium yang dipoles mengkilap, sol luar karet TPR yang antiselip, serta insole berbantalan lembut.',
            'harga' => 750000,
            'id_kategori' => 3,
            'gambar' => 'reagan_derby.jpg',
            'details' => [
                ['ukuran' => '40', 'warna' => 'Polished Black', 'stok' => 12],
                ['ukuran' => '41', 'warna' => 'Polished Black', 'stok' => 15],
                ['ukuran' => '42', 'warna' => 'Polished Black', 'stok' => 10],
                ['ukuran' => '41', 'warna' => 'Tan Leather', 'stok' => 8],
                ['ukuran' => '42', 'warna' => 'Tan Leather', 'stok' => 12],
            ]
        ],
    ];

    // Prepare statement for products insertion
    $stmt_prod = $pdo->prepare("INSERT INTO produk (nama_produk, deskripsi, harga, id_kategori, gambar) VALUES (?, ?, ?, ?, ?)");
    $stmt_det = $pdo->prepare("INSERT INTO produk_detail (id_produk, ukuran, warna, stok) VALUES (?, ?, ?, ?)");

    foreach ($new_products as $p) {
        // Check if already exists
        $stmt_check = $pdo->prepare("SELECT id_produk FROM produk WHERE nama_produk = ?");
        $stmt_check->execute([$p['nama_produk']]);
        $existing = $stmt_check->fetch();

        if ($existing) {
            echo "Product '{$p['nama_produk']}' already exists. Skipping insertion.\n";
            continue;
        }

        // Insert product
        $stmt_prod->execute([
            $p['nama_produk'],
            $p['deskripsi'],
            $p['harga'],
            $p['id_kategori'],
            $p['gambar']
        ]);
        $id_produk = $pdo->lastInsertId();
        echo "Inserted product '{$p['nama_produk']}' with ID $id_produk.\n";

        // Insert details
        foreach ($p['details'] as $d) {
            $stmt_det->execute([
                $id_produk,
                $d['ukuran'],
                $d['warna'],
                $d['stok']
            ]);
        }
        echo "Seeded variations for '{$p['nama_produk']}' successfully.\n";
    }

    echo "Catalog expansion completed successfully.\n";

} catch (PDOException $e) {
    die("Database update failed: " . $e->getMessage() . "\n");
}
