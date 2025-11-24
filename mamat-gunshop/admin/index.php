<?php
include '../config.php';
checkAuth('admin');
?>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mamat Gunshop</title>
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
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
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
                            <a class="nav-link active" href="index.php">
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
                            <a class="nav-link" href="kelola_barang.php">
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
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard Admin</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <span class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-person-circle"></i>
                                <?= $_SESSION['username'] ?> (Admin)
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM barang");
                                            $total_barang = $stmt->fetch()['total'];
                                            echo $total_barang;
                                            ?>
                                        </h4>
                                        <p class="card-text">Total Barang</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-box-seam fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
                                            $total_user = $stmt->fetch()['total'];
                                            echo $total_user;
                                            ?>
                                        </h4>
                                        <p class="card-text">Total User</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-people fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-warning text-dark">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $stmt = $pdo->query("SELECT SUM(stok) as total FROM barang");
                                            $total_stok = $stmt->fetch()['total'];
                                            echo $total_stok ?: 0;
                                            ?>
                                        </h4>
                                        <p class="card-text">Total Stok</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-archive fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
                                            $total_user_biasa = $stmt->fetch()['total'];
                                            echo $total_user_biasa;
                                            ?>
                                        </h4>
                                        <p class="card-text">User Biasa</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-person fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barang Terbaru -->
                <div class="card shadow">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Barang Terbaru</h5>
                        <a href="kelola_barang.php" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Stok</th>
                                        <th>Harga</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT barang.*, users.username FROM barang 
                                                        JOIN users ON barang.created_by = users.id 
                                                        ORDER BY barang.created_at DESC LIMIT 5");
                                    $barang_terbaru = $stmt->fetchAll();
                                    
                                    if (empty($barang_terbaru)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data barang</td>
                                    </tr>
                                    <?php else: ?>
                                    <?php foreach ($barang_terbaru as $item): ?>
                                    <tr>
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