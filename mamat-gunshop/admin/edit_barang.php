<?php
include '../config.php';

checkAuth('admin');

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch();

if (!$barang) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $nama_barang = sanitize($_POST['nama_barang']);
        $kategori = sanitize($_POST['kategori']);
        $stok = (int)$_POST['stok'];
        $harga = (float)$_POST['harga'];
        $deskripsi = sanitize($_POST['deskripsi']);
        $gambar = $barang['gambar'];
        
        // Handle upload gambar baru
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            // Hapus gambar lama jika ada
            if ($gambar) {
                deleteGambar($gambar);
            }
            $gambar = uploadGambar($_FILES['gambar']);
        }
        
        // Handle hapus gambar
        if (isset($_POST['hapus_gambar']) && $_POST['hapus_gambar'] == '1') {
            if ($gambar) {
                deleteGambar($gambar);
                $gambar = null;
            }
        }
        
        $stmt = $pdo->prepare("UPDATE barang SET nama_barang = ?, kategori = ?, stok = ?, harga = ?, deskripsi = ?, gambar = ? WHERE id = ?");
        
        if ($stmt->execute([$nama_barang, $kategori, $stok, $harga, $deskripsi, $gambar, $id])) {
            header('Location: index.php?success=edit');
            exit;
        } else {
            $error = "Gagal mengupdate barang!";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Mamat Gunshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .preview-image {
            max-width: 200px;
            max-height: 150px;
            margin-top: 10px;
        }
        .current-image {
            max-width: 200px;
            max-height: 150px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Mamat Gunshop</a>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Edit Barang</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_barang" class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <select class="form-select" id="kategori" name="kategori" required>
                                        <option value="Pistol" <?= $barang['kategori'] == 'Pistol' ? 'selected' : '' ?>>Pistol</option>
                                        <option value="Senapan" <?= $barang['kategori'] == 'Senapan' ? 'selected' : '' ?>>Senapan</option>
                                        <option value="Senapan Angin" <?= $barang['kategori'] == 'Senapan Angin' ? 'selected' : '' ?>>Senapan Angin</option>
                                        <option value="Aksesoris" <?= $barang['kategori'] == 'Aksesoris' ? 'selected' : '' ?>>Aksesoris</option>
                                        <option value="Peluru" <?= $barang['kategori'] == 'Peluru' ? 'selected' : '' ?>>Peluru</option>
                                        <option value="Lainnya" <?= $barang['kategori'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" value="<?= $barang['stok'] ?>" min="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="harga" class="form-label">Harga (Rp)</label>
                                    <input type="number" class="form-control" id="harga" name="harga" value="<?= $barang['harga'] ?>" min="0" step="0.01" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="gambar" class="form-label">Gambar Barang</label>
                                
                                <?php if ($barang['gambar']): ?>
                                <div class="mb-2">
                                    <p class="mb-1">Gambar Saat Ini:</p>
                                    <img src="<?= getGambarUrl($barang['gambar'], 'thumbnail') ?>" class="current-image img-thumbnail" alt="<?= htmlspecialchars($barang['nama_barang']) ?>">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="hapus_gambar" value="1" id="hapusGambar">
                                        <label class="form-check-label text-danger" for="hapusGambar">
                                            Hapus gambar ini
                                        </label>
                                    </div>
                                </div>
                                <p class="text-muted">Atau upload gambar baru:</p>
                                <?php endif; ?>
                                
                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                <small class="text-muted">Format: JPEG, PNG, GIF, WebP. Maksimal 2MB</small>
                                <img id="preview" class="preview-image img-thumbnail" alt="Preview" style="display: none;">
                            </div>
                            
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= htmlspecialchars($barang['deskripsi']) ?></textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">Update Barang</button>
                                <a href="index.php" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview gambar baru sebelum upload
        document.getElementById('gambar').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
</body>
</html>