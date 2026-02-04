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
    $dokumentasi = isset($_POST['dokumentasi']) ? trim($_POST['dokumentasi']) : '';
    $dokumentasi = mysqli_real_escape_string($koneksi, $dokumentasi);
    mysqli_query($koneksi, "UPDATE jadwal SET dokumentasi = '$dokumentasi' WHERE id_jadwal = $id_jadwal");
    // Cek status selesai: dokumentasi
    $qLinks = mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM jadwal_link WHERE id_jadwal = $id_jadwal AND link IS NOT NULL AND link != ''");
    $cntLinks = mysqli_fetch_assoc($qLinks)['cnt'];
    if (!empty($dokumentasi) && $cntLinks > 0) {
        mysqli_query($koneksi, "UPDATE jadwal SET status = 2 WHERE id_jadwal = $id_jadwal");
    }
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../../images/sikumbang.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokumentasi</title>
    <link rel="icon" href="../images/sikumbang.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <style>
        body, .card, .form-control, .btn, .table th, .table td, label, h5 {
            font-family: 'Poppins', sans-serif !important;
        }
        body {
            background: #f7fbff;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
            border: none;
        }
        .card-header {
            background: #009cfd;
            color: #fff;
            border-radius: 16px 16px 0 0;
            padding: 24px 32px 16px 32px;
        }
        .card-body {
            padding: 32px;
        }
        h5.mb-0 {
            font-weight: 600;
            font-size: 1.35rem;
            letter-spacing: 0.5px;
        }
        .form-group label {
            font-weight: 500;
            color: #333;
            margin-bottom: 6px;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #dbeafe;
            font-size: 1rem;
            padding: 10px 14px;
            background: #f8fafc;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #009cfd;
            box-shadow: none;
        }
        .btn-primary.btn-icon-l {
            background: #009cfd;
            border: none;
            font-weight: 500;
            border-radius: 8px;
            padding: 10px 24px;
        }
        .btn-primary.btn-icon-l:hover {
            background: #007acc;
        }
        .btn-secondary.btn-icon-l {
            background: #e5e7eb;
            color: #333;
            border: none;
            font-weight: 500;
            border-radius: 8px;
            padding: 10px 24px;
        }
        .btn-secondary.btn-icon-l:hover {
            background: #cfd8dc;
        }
        .alert-warning {
            background: #fffbe6;
            color: #856404;
            border-radius: 8px;
            border: none;
            font-size: 0.97rem;
            margin-top: 18px;
        }
        .table th {
            color: #009cfd;
            font-weight: 600;
            width: 180px;
            background: none;
            border: none;
        }
        .table td {
            border: none;
        }
    </style>
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="col-md-8 my-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-camera mr-2"></i> Edit Dokumentasi</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <table class="table table-borderless mb-3">
                        <tr>
                            <th>Judul Kegiatan</th>
                            <td><?= htmlspecialchars($jadwal['judul_kegiatan']) ?></td>
                        </tr>
                    </table>
                    <div class="form-group mb-4">
                        <label for="dokumentasi">Link Dokumentasi <span class="text-danger">*</span></label>
                        <input type="url" name="dokumentasi" id="dokumentasi" class="form-control" value="<?= htmlspecialchars($jadwal['dokumentasi'] ?? '') ?>" placeholder="https://..." required>
                        <small class="text-muted d-block mt-2">Masukkan link dokumentasi (foto/video/file) yang dapat diakses publik, misal Google Drive, YouTube, dsb.</small>
                    </div>
                    <div class="alert alert-warning">
                        <small><i class="fas fa-info-circle"></i> Status akan otomatis berubah ke "Selesai" ketika dokumentasi dan minimal 1 link publikasi terisi.</small>
                    </div>
                    <div class="form-group mt-4 d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary btn-icon-l"><i class="fas fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary btn-icon-l"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
