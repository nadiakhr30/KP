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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
        <div class="col-md-4 my-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Jenis PIC</h5>
                </div>
                <div class="card-body px-5">
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
                        <div class="form-group mt-4 d-flex justify-content-between">
                            <a href="../manajemen_data_lainnya.php" class="btn btn-secondary btn-icon-l"><i class="fas fa-arrow-left"></i></a>
                            <button type="submit" class="btn btn-primary btn-icon-l"><i class="fas fa-save"></i></button>
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
