<?php
ob_start();
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../../index.php');
    exit();
}

$error = "";
$success = "";
$id_sub_jenis = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_sub_jenis <= 0) {
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$query = "SELECT s.*, j.nama_jenis FROM sub_jenis s LEFT JOIN jenis j ON s.id_jenis = j.id_jenis WHERE s.id_sub_jenis = $id_sub_jenis";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

// Get jenis options
$qJenis = mysqli_query($koneksi, "SELECT * FROM jenis");
$jenisOptions = [];
while ($row = mysqli_fetch_assoc($qJenis)) {
    $jenisOptions[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_sub_jenis = mysqli_real_escape_string($koneksi, trim($_POST['nama_sub_jenis']));
    $id_jenis = isset($_POST['id_jenis']) ? (int)$_POST['id_jenis'] : 0;

    if (empty($nama_sub_jenis) || $id_jenis <= 0) {
        $error = "Nama Sub Jenis dan Jenis wajib diisi!";
    } else {
        $updateQuery = "UPDATE sub_jenis SET nama_sub_jenis = '$nama_sub_jenis', id_jenis = $id_jenis WHERE id_sub_jenis = $id_sub_jenis";

        if (mysqli_query($koneksi, $updateQuery)) {
            $success = "Sub Jenis berhasil diperbarui!";
            $data['nama_sub_jenis'] = $nama_sub_jenis;
            $data['id_jenis'] = $id_jenis;
            header("Refresh: 1; url=../manajemen_data_lainnya.php");
        } else {
            $error = "Sub Jenis gagal diperbarui!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Sub Jenis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Sub Jenis</h5>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="form-group">
                            <label>Nama Sub Jenis <span class="text-danger">*</span></label>
                            <input type="text" name="nama_sub_jenis" class="form-control" required 
                                   value="<?= htmlspecialchars($data['nama_sub_jenis']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Jenis <span class="text-danger">*</span></label>
                            <select name="id_jenis" class="form-control" required>
                                <option value="">-- Pilih Jenis --</option>
                                <?php foreach ($jenisOptions as $jenis): ?>
                                    <option value="<?= $jenis['id_jenis']; ?>" <?= $data['id_jenis'] == $jenis['id_jenis'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($jenis['nama_jenis']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group d-flex justify-content-between mt-4">
                            <a href="../manajemen_data_lainnya.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
