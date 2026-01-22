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
$id_jenis_pic = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_jenis_pic <= 0) {
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$query = "SELECT * FROM jenis_pic WHERE id_jenis_pic = $id_jenis_pic";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_jenis_pic = mysqli_real_escape_string($koneksi, trim($_POST['nama_jenis_pic']));

    if (empty($nama_jenis_pic)) {
        $error = "Nama Jenis PIC wajib diisi!";
    } else {
        $updateQuery = "UPDATE jenis_pic SET nama_jenis_pic = '$nama_jenis_pic' WHERE id_jenis_pic = $id_jenis_pic";

        if (mysqli_query($koneksi, $updateQuery)) {
            $success = "Jenis PIC berhasil diperbarui!";
            $data['nama_jenis_pic'] = $nama_jenis_pic;
            header("Refresh: 1; url=../manajemen_data_lainnya.php");
        } else {
            $error = "Jenis PIC gagal diperbarui!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Jenis PIC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Jenis PIC</h5>
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
                            <label>Nama Jenis PIC <span class="text-danger">*</span></label>
                            <input type="text" name="nama_jenis_pic" class="form-control" required 
                                   value="<?= htmlspecialchars($data['nama_jenis_pic']) ?>">
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
