<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

$id_jadwal = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_jadwal == 0) {
    die("ID Jadwal tidak valid");
}

// Cek apakah pegawai adalah PIC dari jadwal ini
$qCheck = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM pic WHERE id_jadwal = $id_jadwal AND nip = '{$_SESSION['pegawai']['nip']}'");
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
    // Ambil semua jadwal_link untuk id_jadwal ini (join jenis_link untuk nama)
    $jenisLinks = [];
    $qJL = mysqli_query($koneksi, "SELECT jl.id_jenis_link, jl.link, jl.id_jadwal_link, jlk.nama_jenis_link FROM jadwal_link jl JOIN jenis_link jlk ON jl.id_jenis_link = jlk.id_jenis_link WHERE jl.id_jadwal = $id_jadwal");
    while ($row = mysqli_fetch_assoc($qJL)) {
        $jenisLinks[$row['id_jenis_link']] = $row['nama_jenis_link'];
    }
    // Simpan/update hanya untuk jenis_link yang sudah ada di jadwal_link
    foreach ($jenisLinks as $idJenis => $namaJenis) {
        if (empty($namaJenis)) continue;
        $field = strtolower(str_replace(' ', '_', $namaJenis));
        $link = mysqli_real_escape_string($koneksi, $_POST[$field] ?? '');
        // Update jadwal_link
        mysqli_query($koneksi, "UPDATE jadwal_link SET link = '$link' WHERE id_jadwal = $id_jadwal AND id_jenis_link = $idJenis");
    }
    // Cek status selesai: dokumentasi + minimal 1 link publikasi di jadwal_link
    $hasDokumentasi = !empty($jadwal['dokumentasi']);
    $qLinks = mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM jadwal_link WHERE id_jadwal = $id_jadwal AND link IS NOT NULL AND link != ''");
    $cntLinks = mysqli_fetch_assoc($qLinks)['cnt'];
    if ($hasDokumentasi && $cntLinks > 0) {
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
    <title>Edit Link Publikasi</title>
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
                <h5 class="mb-0"><i class="fas fa-link mr-2"></i> Edit Link Publikasi</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <table class="table table-borderless mb-3">
                        <tr>
                            <th>Judul Kegiatan</th>
                            <td><?= htmlspecialchars($jadwal['judul_kegiatan']) ?></td>
                        </tr>
                    </table>
                    <?php
                    $jenisLinks = [];
                    $jadwalLinks = [];
                    $qJL = mysqli_query($koneksi, "SELECT jl.id_jenis_link, jl.link, jl.id_jadwal_link, jlk.nama_jenis_link FROM jadwal_link jl JOIN jenis_link jlk ON jl.id_jenis_link = jlk.id_jenis_link WHERE jl.id_jadwal = $id_jadwal");
                    while ($row = mysqli_fetch_assoc($qJL)) {
                        $jenisLinks[$row['id_jenis_link']] = $row['nama_jenis_link'];
                        $jadwalLinks[$row['id_jenis_link']] = $row['link'];
                    }
                    ?>
                    <?php foreach ($jenisLinks as $idJenis => $namaJenis):
                        if (empty($namaJenis)) continue;
                        $field = strtolower(str_replace(' ', '_', $namaJenis));
                        $icon = 'fa-globe';
                        if (stripos($namaJenis, 'instagram') !== false) $icon = 'fa-instagram';
                        if (stripos($namaJenis, 'facebook') !== false) $icon = 'fa-facebook';
                        if (stripos($namaJenis, 'youtube') !== false) $icon = 'fa-youtube';
                    ?>
                    <div class="form-group mb-4">
                        <label for="<?= $field ?>"><i class="fab <?= $icon ?> mr-1"></i> Link <?= htmlspecialchars($namaJenis) ?></label>
                        <input type="url" name="<?= $field ?>" id="<?= $field ?>" class="form-control" value="<?= htmlspecialchars($jadwalLinks[$idJenis] ?? '') ?>" placeholder="https://...">
                    </div>
                    <?php endforeach; ?>
                    <?php
                    $qPic = mysqli_query($koneksi, "SELECT u.nama, jp.nama_jenis_pic FROM pic p JOIN pegawai u ON p.nip = u.nip JOIN jenis_pic jp ON p.id_jenis_pic = jp.id_jenis_pic WHERE p.id_jadwal = $id_jadwal ORDER BY jp.nama_jenis_pic");
                    $picList = [];
                    while ($row = mysqli_fetch_assoc($qPic)) {
                        $picList[] = "<b>{$row['nama_jenis_pic']}:</b> {$row['nama']}";
                    }
                    ?>
                    <?php if ($picList): ?>
                        <div class="form-group mb-4">
                            <label>PIC Jadwal:</label>
                            <div><?= implode('<br>', $picList) ?></div>
                        </div>
                    <?php endif; ?>
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
