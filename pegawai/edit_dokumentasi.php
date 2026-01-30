<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

$id_jadwal = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'dokumentasi';

if ($id_jadwal == 0) {
    die("ID Jadwal tidak valid");
}

// Cek apakah pegawai adalah PIC dari jadwal ini
$qCheck = mysqli_query($koneksi, "
    SELECT COUNT(*) as count FROM pic 
    WHERE id_jadwal = $id_jadwal AND nip = '{$_SESSION['pegawai']['nip']}'
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
        body {
            min-height: 100vh;
            padding: 40px 20px;
            background: #f7fbff;
        }
        .page-wrapper {
            max-width: 800px;
            margin: auto;
        }
        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            color: #009cfd;
            font-size: 14px;
        }
        .breadcrumb-link { color: #009cfd; text-decoration: none; }
        .breadcrumb-separator { color: #b0b0b0; }
        .breadcrumb-active { color: #009cfd; font-weight: 600; }

        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 156, 253, 0.12);
            padding: 32px;
            position: relative;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .info-table tr { border-bottom: 1px solid #eee; }
        .info-table th { text-align: left; padding: 10px 0; width: 30%; font-weight: 600; color: #666; }
        .info-table td { padding: 10px 0; color: #333; }

        .form-section { margin-top: 12px; }
        .btn-save { background: #009cfd; border: none; }
        .btn-save:hover { background: #007acc; }

        .btn-cancel { background: #f0f0f0; border: none; color: #333; }

        .doc-preview { margin-top: 10px; }
        .doc-preview img, .doc-preview video { max-width: 100%; border-radius: 8px; display: block; }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="breadcrumb-custom">
            <a href="index.php" class="breadcrumb-link">
                <i class="bi bi-house-fill"></i>
            </a>
            
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-active"><?= $mode == 'dokumentasi' ? 'Edit Dokumentasi' : 'Edit Link Publikasi' ?></span>
        </div>

        <div class="profile-card">
            <div class="card-title">
                <i class="bi bi-camera-reels"></i>
                <?= $mode == 'dokumentasi' ? 'Edit Dokumentasi' : 'Edit Link Publikasi' ?>
            </div>

            <table class="info-table">
                <tr>
                    <th>Judul Kegiatan</th>
                    <td><?= htmlspecialchars($jadwal['judul_kegiatan']) ?></td>
                </tr>
                
            </table>

            <div class="form-section">
                <form method="POST" enctype="multipart/form-data">
                    <?php if ($mode == 'dokumentasi'): ?>
                        <div class="mb-3">
                            <label class="form-label">Upload Dokumentasi (Foto/Video)</label>
                            <input type="file" name="file_dokumentasi" class="form-control" accept="image/*,video/*" required>
                            <small class="text-muted d-block mt-2">Format: JPG, PNG, MP4, dll</small>
                        </div>

                        <?php if (!empty($jadwal['dokumentasi'])): ?>
                            <div class="doc-preview">
                                <?php $doc = $jadwal['dokumentasi']; ?>
                                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $doc)): ?>
                                    <img src="<?= $doc ?>" alt="Dokumentasi">
                                <?php elseif (preg_match('/\.(mp4|webm|ogg)$/i', $doc)): ?>
                                    <video controls src="<?= $doc ?>"></video>
                                <?php else: ?>
                                    <div class="alert alert-info">
                                        <small><i class="bi bi-info-circle"></i> Dokumentasi saat ini: <a href="<?= $doc ?>" target="_blank">Lihat</a></small>
                                    </div>
                                <?php endif; ?>
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
                        <button type="submit" class="btn btn-save btn-primary">
                            <i class="bi bi-check-circle"></i> Simpan
                        </button>
                        <a href="index.php" class="btn btn-cancel btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
