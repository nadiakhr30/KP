<?php
ob_start();
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../../index.php');
    exit();
}

$error = "";
$success = "";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: ../template_medsos.php');
    exit();
}

// fetch media (include jenis name)
$q = mysqli_query($koneksi, "SELECT m.*, s.id_sub_jenis, j.nama_jenis FROM media m JOIN sub_jenis s ON m.id_sub_jenis = s.id_sub_jenis JOIN jenis j ON s.id_jenis = j.id_jenis WHERE m.id_media = $id");
$media = mysqli_fetch_assoc($q);
if (!$media) {
    header('Location: ../template_medsos.php');
    exit();
}

// derive page name from jenis
$namaJenis = strtolower(str_replace(' ', '_', $media['nama_jenis']));
$backUrl = "../{$namaJenis}.php?sub=" . (int)$media['id_sub_jenis'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $topik = trim($_POST['topik'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $link = trim($_POST['link'] ?? '');

    if ($judul === '') {
        $error = 'Judul media tidak boleh kosong';
    } else {
        $judul_s = mysqli_real_escape_string($koneksi, $judul);
        $topik_s = mysqli_real_escape_string($koneksi, $topik);
        $deskripsi_s = mysqli_real_escape_string($koneksi, $deskripsi);
        $link_s = mysqli_real_escape_string($koneksi, $link);

        $update = "UPDATE media SET judul = '$judul_s', topik = '$topik_s', deskripsi = '$deskripsi_s', link = '$link_s' WHERE id_media = $id";
        if (mysqli_query($koneksi, $update)) {
            $success = 'Media berhasil diperbarui';
            $media['judul'] = $judul;
            $media['topik'] = $topik;
            $media['deskripsi'] = $deskripsi;
            $media['link'] = $link;
            // redirect back to specific jenis page with sub parameter
            header("Refresh: 1; url=$backUrl");
            exit();
        } else {
            $error = 'Gagal memperbarui media: ' . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="../assets/images/logo_bps.ico" type="image/x-icon">
<title>Edit Media</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body style="display:flex; align-items:center; justify-content:center; min-height:100vh;">
<div class="col-md-8 my-5">
<div class="card">
<div class="card-header"><h5 class="mb-0">Edit Media</h5></div>
<div class="card-body px-5">
<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
<div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<form method="post" action="">
    <input type="hidden" name="id_media" value="<?= $media['id_media'] ?>">
    <!-- Row: Judul -->
    <div class="form-group">
        <label>Judul <span class="text-danger">*</span></label>
        <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($media['judul']) ?>">
    </div>
    <!-- Row: Topik + Link -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Topik <span class="text-danger">*</span></label>
                <input type="text" name="topik" class="form-control" required value="<?= htmlspecialchars($media['topik']) ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Link <span class="text-danger">*</span></label>
                <input type="url" name="link" class="form-control" required value="<?= htmlspecialchars($media['link']) ?>">
            </div>
        </div>
    </div>
    <!-- Deskripsi -->
    <div class="form-group">
        <label>Deskripsi <span class="text-danger">*</span></label>
        <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($media['deskripsi']) ?></textarea>
    </div>
    <div class="d-flex justify-content-between mt-4">
        <a href="<?= htmlspecialchars($backUrl) ?>" class="btn btn-secondary btn-icon-l"><i class="fas fa-arrow-left"></i></a>
        <button type="submit" class="btn btn-primary btn-icon-l"><i class="fas fa-save"></i></button>
    </div>
</form>
</div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
