<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

$id_jadwal = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'dokumentasi';

if ($id_jadwal == 0) {
    die("ID Jadwal tidak valid");
}

// Cek apakah user adalah PIC dari jadwal ini
$qCheck = mysqli_query($koneksi, "
    SELECT COUNT(*) as count FROM pic 
    WHERE id_jadwal = $id_jadwal AND nip = '{$_SESSION['user']['nip']}'
");
$check = mysqli_fetch_assoc($qCheck);
if ($check['count'] == 0) {
    die("Anda tidak memiliki akses untuk mengedit jadwal ini");
}

// Get jadwal data
$qJadwal = mysqli_query($koneksi, "SELECT * FROM jadwal WHERE id_jadwal = $id_jadwal");
$jadwal = mysqli_fetch_assoc($qJadwal);

if (!$jadwal) {
    die("Jadwal tidak ditemukan");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'dokumentasi') {
        $dokumentasi = $jadwal['dokumentasi']; // Default ke dokumentasi lama
        
        // Handle file upload
        if (isset($_FILES['file_dokumentasi']) && $_FILES['file_dokumentasi']['size'] > 0) {
            $file = $_FILES['file_dokumentasi'];
            $upload_dir = '../uploads/dokumentasi/';
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_name = time() . '_' . basename($file['name']);
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $dokumentasi = '../uploads/dokumentasi/' . $file_name;
            }
        }
        
        $dokumentasi = mysqli_real_escape_string($koneksi, $dokumentasi);
        mysqli_query($koneksi, "UPDATE jadwal SET dokumentasi = '$dokumentasi' WHERE id_jadwal = $id_jadwal");
        
    } elseif ($mode == 'publikasi') {
        $link_instagram = mysqli_real_escape_string($koneksi, $_POST['link_instagram'] ?? '');
        $link_facebook = mysqli_real_escape_string($koneksi, $_POST['link_facebook'] ?? '');
        $link_youtube = mysqli_real_escape_string($koneksi, $_POST['link_youtube'] ?? '');
        $link_website = mysqli_real_escape_string($koneksi, $_POST['link_website'] ?? '');
        
        mysqli_query($koneksi, "UPDATE jadwal SET 
            link_instagram = '$link_instagram',
            link_facebook = '$link_facebook',
            link_youtube = '$link_youtube',
            link_website = '$link_website'
            WHERE id_jadwal = $id_jadwal
        ");
    }
    
    // Auto-update status ke "Selesai" (2) jika dokumentasi + minimal 1 link publikasi ada
    $qCheck = mysqli_query($koneksi, "
        SELECT dokumentasi, link_instagram, link_facebook, link_youtube, link_website 
        FROM jadwal WHERE id_jadwal = $id_jadwal
    ");
    $data = mysqli_fetch_assoc($qCheck);
    
    $hasDokumentasi = !empty($data['dokumentasi']);
    $hasLink = !empty($data['link_instagram']) || !empty($data['link_facebook']) || 
               !empty($data['link_youtube']) || !empty($data['link_website']);
    
    if ($hasDokumentasi && $hasLink) {
        mysqli_query($koneksi, "UPDATE jadwal SET status = 2 WHERE id_jadwal = $id_jadwal");
    }
    
    header("Location: viewjadwal.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokumentasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <?= $mode == 'dokumentasi' ? 'Edit Dokumentasi' : 'Edit Link Publikasi' ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3 text-muted"><?= $jadwal['judul_kegiatan'] ?></h6>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <?php if ($mode == 'dokumentasi'): ?>
                                <div class="mb-3">
                                    <label class="form-label">Upload Dokumentasi (Foto/Video)</label>
                                    <input type="file" name="file_dokumentasi" class="form-control" accept="image/*,video/*" required>
                                    <small class="text-muted d-block mt-2">Format: JPG, PNG, MP4, dll</small>
                                </div>
                                <?php if (!empty($jadwal['dokumentasi'])): ?>
                                    <div class="alert alert-info">
                                        <small><i class="bi bi-info-circle"></i> Dokumentasi saat ini: 
                                            <a href="<?= $jadwal['dokumentasi'] ?>" target="_blank">Lihat</a>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            <?php elseif ($mode == 'publikasi'): ?>
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-instagram"></i> Link Instagram</label>
                                    <input type="url" name="link_instagram" class="form-control" value="<?= htmlspecialchars($jadwal['link_instagram'] ?? '') ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-facebook"></i> Link Facebook</label>
                                    <input type="url" name="link_facebook" class="form-control" value="<?= htmlspecialchars($jadwal['link_facebook'] ?? '') ?>" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-youtube"></i> Link YouTube</label>
                                    <input type="url" name="link_youtube" class="form-control" value="<?= htmlspecialchars($jadwal['link_youtube'] ?? '') ?>" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-globe"></i> Link Website</label>
                                    <input type="url" name="link_website" class="form-control" value="<?= htmlspecialchars($jadwal['link_website'] ?? '') ?>" >
                                </div>
                                <div class="alert alert-warning">
                                    <small><i class="bi bi-info-circle"></i> Status akan otomatis berubah ke "Selesai" ketika dokumentasi dan minimal 1 link publikasi terisi.</small>
                                </div>
                            <?php endif; ?>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Simpan
                                </button>
                                <a href="viewjadwal.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
