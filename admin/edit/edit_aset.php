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
    header('Location: ../aset.php');
    exit();
}

// fetch aset
$q = mysqli_query($koneksi, "SELECT * FROM aset WHERE id_aset = $id");
$aset = mysqli_fetch_assoc($q);
if (!$aset) {
    header('Location: ../aset.php');
    exit();
}

// fetch pegawai for select
$qPeg = mysqli_query($koneksi, "SELECT nip, nama FROM pegawai ORDER BY nama ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');
    $nip = trim($_POST['nip'] ?? '');

    if ($nama === '') {
        $error = 'Nama aset tidak boleh kosong';
    } else {
        $nama_s = mysqli_real_escape_string($koneksi, $nama);
        $link_s = mysqli_real_escape_string($koneksi, $link);
        $ket_s = mysqli_real_escape_string($koneksi, $keterangan);
        $nip_s = mysqli_real_escape_string($koneksi, $nip);

        $update = "UPDATE aset SET nama = '$nama_s', link = '$link_s', keterangan = '$ket_s', nip = " . (empty($nip_s) ? "NULL" : "'$nip_s'") . " WHERE id_aset = $id";
        if (mysqli_query($koneksi, $update)) {
            $success = 'Aset berhasil diperbarui';
            $aset['nama'] = $nama;
            $aset['link'] = $link;
            $aset['keterangan'] = $keterangan;
            $aset['nip'] = $nip;
            // redirect back to aset list for the same jenis
            header('Refresh: 1; url=../aset.php?jenis=' . (int)$aset['id_jenis_aset']);
            exit();
        } else {
            $error = 'Gagal memperbarui aset: ' . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Aset</title>
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
<div class="card-header"><h5 class="mb-0">Edit Aset</h5></div>
<div class="card-body px-5">
<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
<div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<form method="post" action="">
    <input type="hidden" name="id_aset" value="<?= $aset['id_aset'] ?>">
    <!-- Row: Name + Link -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Nama Aset <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($aset['nama']) ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Link</label>
                <input type="url" name="link" class="form-control" value="<?= htmlspecialchars($aset['link']) ?>">
            </div>
        </div>
    </div>
    <!-- Keterangan -->
    <div class="form-group">
        <label>Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="4"><?= htmlspecialchars($aset['keterangan']) ?></textarea>
    </div>
    <!-- Penanggung Jawab -->
    <div class="form-group">
        <label>Penanggung Jawab</label>
        <select name="nip" class="form-control">
            <option value="">-- Pilih --</option>
            <?php if ($qPeg): while($p = mysqli_fetch_assoc($qPeg)): ?>
                <option value="<?= htmlspecialchars($p['nip']) ?>" <?= ($aset['nip'] == $p['nip']) ? 'selected' : '' ?>><?= htmlspecialchars($p['nama']) ?> (<?= htmlspecialchars($p['nip']) ?>)</option>
            <?php endwhile; endif; ?>
        </select>
    </div>
    <div class="d-flex justify-content-between mt-4">
        <a href="../aset.php?jenis=<?= (int)$aset['id_jenis_aset'] ?>" class="btn btn-secondary btn-icon-l"><i class="fas fa-arrow-left"></i></a>
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
