<?php
include '../config.php';
checkAuth(); // Tanpa parameter untuk user biasa

?>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pendataan Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://source.unsplash.com/random/1920x1080/?warehouse') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            min-height: 100vh;
        }
        .dashboard-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            color: #333;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.7) !important;
        }
        .table-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .stats-card {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            color: white;
            margin-bottom: 15px;
        }
        .stats-card.total {
            background-color: #3498db;
        }
        .stats-card.available {
            background-color: #2ecc71;
        }
        .stats-card.sold {
            background-color: #e74c3c;
        }
        .btn-custom {
            background-color: #2980b9;
            color: white;
            border: none;
        }
        .btn-custom:hover {
            background-color: #1c6ea4;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard Barang</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tambah Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Laporan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="text-center">Dashboard Pendataan Barang</h1>
                <p class="text-center">Sistem manajemen inventori barang sederhana</p>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card total">
                    <h3><?php echo getTotalItems(); ?></h3>
                    <p>Total Barang</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card available">
                    <h3><?php echo getAvailableItems(); ?></h3>
                    <p>Barang Tersedia</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card sold">
                    <h3><?php echo getSoldItems(); ?></h3>
                    <p>Barang Terjual</p>
                </div>
            </div>
        </div>

        <!-- Form Tambah Barang -->
        <div class="row">
            <div class="col-md-4">
                <div class="dashboard-card">
                    <h4>Tambah Barang Baru</h4>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Pakaian">Pakaian</option>
                                <option value="Makanan">Makanan</option>
                                <option value="Alat Tulis">Alat Tulis</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" required>
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" required>
                        </div>
                        <button type="submit" class="btn btn-custom w-100" name="tambah">Tambah Barang</button>
                    </form>
                </div>
            </div>

            <!-- Daftar Barang -->
            <div class="col-md-8">
                <div class="table-container">
                    <h4>Daftar Barang</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php displayItems(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Fungsi untuk koneksi database (simulasi)
function connectDB() {
    // Dalam implementasi nyata, ini akan berisi koneksi ke database
    return true;
}

// Fungsi untuk mendapatkan total barang
function getTotalItems() {
    // Dalam implementasi nyata, ini akan mengambil data dari database
    return 42;
}

// Fungsi untuk mendapatkan barang tersedia
function getAvailableItems() {
    // Dalam implementasi nyata, ini akan mengambil data dari database
    return 28;
}

// Fungsi untuk mendapatkan barang terjual
function getSoldItems() {
    // Dalam implementasi nyata, ini akan mengambil data dari database
    return 14;
}

// Fungsi untuk menampilkan daftar barang
function displayItems() {
    // Data contoh (dalam implementasi nyata, ini akan mengambil data dari database)
    $items = array(
        array("id" => 1, "nama" => "Laptop", "kategori" => "Elektronik", "harga" => 8000000, "stok" => 5),
        array("id" => 2, "nama" => "Mouse", "kategori" => "Elektronik", "harga" => 150000, "stok" => 20),
        array("id" => 3, "nama" => "Kaos", "kategori" => "Pakaian", "harga" => 75000, "stok" => 15),
        array("id" => 4, "nama" => "Buku Tulis", "kategori" => "Alat Tulis", "harga" => 5000, "stok" => 50),
        array("id" => 5, "nama" => "Snack", "kategori" => "Makanan", "harga" => 10000, "stok" => 30)
    );
    
    $no = 1;
    foreach($items as $item) {
        echo "<tr>";
        echo "<td>" . $no . "</td>";
        echo "<td>" . $item['nama'] . "</td>";
        echo "<td>" . $item['kategori'] . "</td>";
        echo "<td>Rp " . number_format($item['harga'], 0, ',', '.') . "</td>";
        echo "<td>" . $item['stok'] . "</td>";
        echo "<td>
                <button class='btn btn-sm btn-primary'>Edit</button>
                <button class='btn btn-sm btn-danger'>Hapus</button>
              </td>";
        echo "</tr>";
        $no++;
    }
}

// Proses form tambah barang
if(isset($_POST['tambah'])) {
    // Dalam implementasi nyata, ini akan menyimpan data ke database
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    
    // Simulasi penyimpanan data
    echo "<script>alert('Barang $nama berhasil ditambahkan!');</script>";
}
?>