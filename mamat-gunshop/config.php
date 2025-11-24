<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

// Konfigurasi database
$host = 'localhost';
$dbname = 'mamat_gunshop';
$username = 'root';
$password = '';

// Konfigurasi path
define('BASE_URL', 'http://localhost/mamat-gunshop');
define('UPLOAD_PATH', __DIR__ . '/uploads/barang/');
define('THUMBNAIL_PATH', __DIR__ . '/uploads/thumbnails/');

// Buat folder uploads jika belum ada
if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0777, true);
}
if (!is_dir(THUMBNAIL_PATH)) {
    mkdir(THUMBNAIL_PATH, 0777, true);
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Fungsi helper
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirectBasedOnRole() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] == 'admin') {
            header('Location: admin/index.php');
        } else {
            header('Location: user/index.php');
        }
        exit;
    }
}

function checkAuth($required_role = null) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }
    
    if ($required_role && $_SESSION['role'] != $required_role) {
        header('Location: ../unauthorized.php');
        exit;
    }
    
    return true;
}

function uploadGambar($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error dalam upload file');
    }
    
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Hanya file JPEG, PNG, GIF, dan WebP yang diizinkan');
    }
    
    if ($file['size'] > $max_size) {
        throw new Exception('Ukuran file maksimal 2MB');
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $destination = UPLOAD_PATH . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception('Gagal menyimpan file');
    }
    
    // Buat thumbnail
    createThumbnail($destination, $filename);
    
    return $filename;
}

function createThumbnail($source_path, $filename) {
    $thumb_width = 200;
    $thumb_height = 150;
    
    $source_info = getimagesize($source_path);
    if (!$source_info) {
        return false;
    }
    
    $source_type = $source_info[2];
    
    switch ($source_type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($source_path);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($source_path);
            break;
        case IMAGETYPE_WEBP:
            $source = imagecreatefromwebp($source_path);
            break;
        default:
            return false;
    }
    
    if (!$source) {
        return false;
    }
    
    $source_width = imagesx($source);
    $source_height = imagesy($source);
    
    // Hitung rasio thumbnail
    $source_ratio = $source_width / $source_height;
    $thumb_ratio = $thumb_width / $thumb_height;
    
    if ($source_ratio > $thumb_ratio) {
        $new_height = $thumb_height;
        $new_width = (int) ($thumb_height * $source_ratio);
    } else {
        $new_width = $thumb_width;
        $new_height = (int) ($thumb_width / $source_ratio);
    }
    
    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
    $white = imagecolorallocate($thumb, 255, 255, 255);
    imagefill($thumb, 0, 0, $white);
    
    // Resize dan posisikan di tengah
    $x_offset = (int) (($thumb_width - $new_width) / 2);
    $y_offset = (int) (($thumb_height - $new_height) / 2);
    
    imagecopyresampled($thumb, $source, $x_offset, $y_offset, 0, 0, 
                      $new_width, $new_height, $source_width, $source_height);
    
    $thumb_path = THUMBNAIL_PATH . $filename;
    
    switch ($source_type) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumb, $thumb_path, 85);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumb, $thumb_path, 8);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumb, $thumb_path);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($thumb, $thumb_path, 85);
            break;
    }
    
    imagedestroy($source);
    imagedestroy($thumb);
    
    return true;
}

function deleteGambar($filename) {
    if ($filename) {
        $file_path = UPLOAD_PATH . $filename;
        $thumb_path = THUMBNAIL_PATH . $filename;
        
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        if (file_exists($thumb_path)) {
            unlink($thumb_path);
        }
    }
}

function getGambarUrl($filename, $type = 'original') {
    if (!$filename) {
        return '../assets/images/no-image.jpg';
    }
    
    if ($type === 'thumbnail') {
        return '../uploads/thumbnails/' . $filename;
    }
    
    return '../uploads/barang/' . $filename;
}
?>