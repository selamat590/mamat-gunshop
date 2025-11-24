<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - Mamat Gunshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card shadow">
                    <div class="card-body py-5">
                        <i class="bi bi-shield-exclamation display-1 text-warning"></i>
                        <h2 class="mt-3">Akses Ditolak</h2>
                        <p class="text-muted">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                        <div class="mt-4">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <a href="admin/index.php" class="btn btn-primary">Kembali ke Dashboard Admin</a>
                                <?php else: ?>
                                    <a href="user/index.php" class="btn btn-primary">Kembali ke Dashboard User</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-primary">Login</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>