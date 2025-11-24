<?php
include '../config.php';
checkAuth('admin');

// Ambil data barang
$stmt = $pdo->query("SELECT barang.*, users.username FROM barang 
                    JOIN users ON barang.created_by = users.id 
                    ORDER BY barang.created_at DESC");
$barang = $stmt->fetchAll();
?>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Barang - Mamat Gunshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse bg-dark">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">Mamat Gunshop</h4>
                        <small class="text-muted">Admin Panel</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="tambah_barang.php">
                                <i class="bi bi-plus-circle"></i>
                                Tambah Barang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="kelola_barang.php">
                                <i class="bi bi-box-seam"></i>
                                Kelola Barang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="kelola_user.php">
                                <i class="bi bi-people"></i>
                                Kelola User
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="text-white">
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Kelola Barang</h1>
                    <a href="tambah_barang.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Barang
                    </a>
                </div>

                <!-- Alert -->
                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_GET['success'] == 'tambah' ? 'Barang berhasil ditambahkan!' : 
                         ($_GET['success'] == 'edit' ? 'Barang berhasil diupdate!' : 
                         ($_GET['success'] == 'hapus' ? 'Barang berhasil dihapus!' : '')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Tabel Barang -->
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Gambar</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Stok</th>
                                        <th>Harga</th>
                                        <th>Ditambahkan Oleh</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($barang)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data barang</td>
                                    </tr>
                                    <?php else: ?>
                                    <?php foreach ($barang as $index => $item): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <img src="<?= getGambarUrl($item['gambar'], 'thumbnail') ?>" 
                                                 alt="<?= htmlspecialchars($item['nama_barang']) ?>" 
                                                 class="img-thumbnail" 
                                                 style="width: 60px; height: 45px; object-fit: cover;"
                                                 onerror="this.src='../assets/images/no-image.jpg'">
                                        </td>
                                        <td><?= htmlspecialchars($item['nama_barang']) ?></td>
                                        <td><?= htmlspecialchars($item['kategori']) ?></td>
                                        <td><?= $item['stok'] ?></td>
                                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($item['username']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($item['created_at'])) ?></td>
                                        <td>
                                            <a href="edit_barang.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="hapus_barang.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>