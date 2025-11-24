<?php
include '../config.php';
checkAuth(); // Tanpa parameter untuk user biasa

// AMBIL DATA BARANG - INI YANG HILANG
try {
    $stmt = $pdo->query("SELECT barang.*, users.username FROM barang 
                        JOIN users ON barang.created_by = users.id 
                        ORDER BY barang.created_at DESC");
    $barang = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error database: " . $e->getMessage());
}
?>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Mamat Gunshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card {
            transition: transform 0.2s;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .hero-section {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-shield-check"></i> Mamat Gunshop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['username']) ?> (User)
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold">Selamat Datang di Mamat Gunshop</h1>
                    <p class="lead">Toko senjata terpercaya dengan kualitas terbaik sejak 1990</p>
                    <div class="mt-4">
                        <span class="badge bg-light text-dark me-2">
                            <i class="bi bi-check-circle"></i> Terpercaya
                        </span>
                        <span class="badge bg-light text-dark me-2">
                            <i class="bi bi-star-fill"></i> Berkualitas
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-shield-check"></i> Aman
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-shield-check display-1"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Katalog Barang</h2>

                <!-- Debug Info -->
                <div class="alert alert-info mb-4">
                    <strong>Info Database:</strong> 
                    Total barang ditemukan: <?= count($barang) ?>
                    <?php if (!empty($barang)): ?>
                        | Kategori tersedia: 
                        <?php
                        $kategories = array_unique(array_column($barang, 'kategori'));
                        echo implode(', ', $kategories);
                        ?>
                    <?php endif; ?>
                </div>

                <!-- Filter dan Search -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <label for="filterKategori" class="form-label">Filter Kategori</label>
                        <select class="form-select" id="filterKategori">
                            <option value="">Semua Kategori</option>
                            <option value="Pistol">Pistol</option>
                            <option value="Senapan">Senapan</option>
                            <option value="Senapan Angin">Senapan Angin</option>
                            <option value="Aksesoris">Aksesoris</option>
                            <option value="Peluru">Peluru</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="searchBarang" class="form-label">Cari Barang</label>
                        <input type="text" class="form-control" id="searchBarang" placeholder="Ketik nama barang...">
                    </div>
                    <div class="col-md-4 mb-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" onclick="resetFilter()">
                            <i class="bi bi-arrow-clockwise"></i> Reset Filter
                        </button>
                    </div>
                </div>

                <!-- Grid Barang -->
                <div class="row" id="barangContainer">
                    <?php if (empty($barang)): ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            <h4><i class="bi bi-exclamation-triangle"></i> Tidak ada data barang</h4>
                            <p>Database barang kosong. Silahkan hubungi admin untuk menambahkan barang atau 
                               <a href="../create_test_user.php" class="alert-link">klik di sini untuk membuat sample data</a>.</p>
                        </div>
                    </div>
                    <?php else: ?>
                    <?php foreach ($barang as $item): ?>
                    <div class="col-md-4 mb-4 barang-item" 
                         data-kategori="<?= htmlspecialchars($item['kategori']) ?>" 
                         data-nama="<?= htmlspecialchars(strtolower($item['nama_barang'])) ?>">
                        <div class="card h-100 shadow">
                            <!-- Gambar Barang -->
                            <img src="<?= getGambarUrl($item['gambar'], 'thumbnail') ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($item['nama_barang']) ?>"
                                 onerror="this.src='../assets/images/no-image.jpg'">
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($item['nama_barang']) ?></h5>
                                <p class="card-text">
                                    <span class="badge bg-primary"><?= htmlspecialchars($item['kategori']) ?></span>
                                </p>
                                <p class="card-text flex-grow-1">
                                    <?php 
                                    if (!empty($item['deskripsi'])) {
                                        echo htmlspecialchars(substr($item['deskripsi'], 0, 100));
                                        if (strlen($item['deskripsi']) > 100) {
                                            echo '...';
                                        }
                                    } else {
                                        echo '<span class="text-muted">Tidak ada deskripsi</span>';
                                    }
                                    ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div>
                                        <strong class="text-primary">Rp <?= number_format($item['harga'], 0, ',', '.') ?></strong>
                                    </div>
                                    <div>
                                        <span class="badge bg-<?= $item['stok'] > 0 ? 'success' : 'danger' ?>">
                                            <i class="bi bi-<?= $item['stok'] > 0 ? 'check' : 'x' ?>-circle"></i>
                                            Stok: <?= $item['stok'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <small class="text-muted">
                                    <i class="bi bi-person"></i> <?= htmlspecialchars($item['username']) ?> | 
                                    <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($item['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div id="about" class="bg-light mt-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3>Tentang Mamat Gunshop</h3>
                    <p class="lead">Toko senjata terpercaya sejak 1990 dengan komitmen memberikan produk berkualitas terbaik.</p>
                    <p>Kami menyediakan berbagai jenis senjata dan aksesoris dengan standar keamanan yang tinggi.</p>
                </div>
                <div class="col-md-6">
                    <h3>Visi & Misi</h3>
                    <ul>
                        <li>Menyediakan produk berkualitas tinggi</li>
                        <li>Pelayanan terbaik untuk customer</li>
                        <li>Standar keamanan yang ketat</li>
                        <li>Harga kompetitif</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-shield-check"></i> Mamat Gunshop</h5>
                    <p>Toko senjata terpercaya sejak 1990</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; 2025 create by Kaysan.</p>

                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter dan Search
        document.getElementById('filterKategori').addEventListener('change', filterBarang);
        document.getElementById('searchBarang').addEventListener('input', filterBarang);

        function filterBarang() {
            const kategori = document.getElementById('filterKategori').value;
            const search = document.getElementById('searchBarang').value.toLowerCase();
            const items = document.querySelectorAll('.barang-item');
            let visibleCount = 0;
            
            items.forEach(item => {
                const itemKategori = item.getAttribute('data-kategori');
                const itemNama = item.getAttribute('data-nama');
                
                const matchKategori = !kategori || itemKategori === kategori;
                const matchSearch = !search || itemNama.includes(search);
                
                if (matchKategori && matchSearch) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Tampilkan pesan jika tidak ada hasil
            const container = document.getElementById('barangContainer');
            let noResults = container.querySelector('.no-results');
            
            if (visibleCount === 0 && items.length > 0) {
                if (!noResults) {
                    noResults = document.createElement('div');
                    noResults.className = 'col-12 no-results';
                    noResults.innerHTML = `
                        <div class="alert alert-warning text-center">
                            <h4><i class="bi bi-search"></i> Tidak ada barang yang ditemukan</h4>
                            <p>Coba ubah filter atau kata kunci pencarian</p>
                        </div>
                    `;
                    container.appendChild(noResults);
                }
            } else if (noResults) {
                noResults.remove();
            }
        }

        function resetFilter() {
            document.getElementById('filterKategori').value = '';
            document.getElementById('searchBarang').value = '';
            filterBarang();
        }

        // Auto filter saat halaman load
        document.addEventListener('DOMContentLoaded', function() {
            filterBarang();
        });
    </script>
</body>
</html>