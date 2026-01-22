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
$id_link = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_link <= 0) {
    header('Location: ../manajemen_link.php');
    exit();
}

// Get current data
$query = "SELECT * FROM link WHERE id_link = $id_link";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: ../manajemen_link.php');
    exit();
}

// Process update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_link = mysqli_real_escape_string($koneksi, trim($_POST['nama_link']));
    $link = mysqli_real_escape_string($koneksi, trim($_POST['link']));

    if (empty($nama_link) || empty($link)) {
        $error = "Nama link dan URL wajib diisi!";
    } else {
        $gambar = $data['gambar'];

        // Upload gambar jika ada
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $namaFileBaru = uniqid() . '.' . $ext;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], '../../uploads/' . $namaFileBaru)) {
                // Hapus gambar lama jika ada
                if ($data['gambar'] && file_exists('../../uploads/' . $data['gambar'])) {
                    unlink('../../uploads/' . $data['gambar']);
                }
                $gambar = $namaFileBaru;
            }
        }

        $updateQuery = "UPDATE link SET 
                        nama_link = '$nama_link',
                        link = '$link',
                        gambar = '$gambar'
                        WHERE id_link = $id_link";

        if (mysqli_query($koneksi, $updateQuery)) {
            $success = "Link berhasil diperbarui!";
            $data = array('id_link' => $id_link, 'nama_link' => $nama_link, 'link' => $link, 'gambar' => $gambar);
            header("Refresh: 1; url=../manajemen_link.php");
        } else {
            $error = "Link gagal diperbarui!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Link</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Link</h5>
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

                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nama Link <span class="text-danger">*</span></label>
                            <input type="text" name="nama_link" class="form-control" required 
                                   value="<?= htmlspecialchars($data['nama_link']) ?>">
                        </div>

                        <div class="form-group">
                            <label>Link Website <span class="text-danger">*</span></label>
                            <input type="url" name="link" class="form-control" required 
                                   value="<?= htmlspecialchars($data['link']) ?>">
                        </div>

                        <div class="form-group">
                            <label>Gambar</label>
                            <?php if ($data['gambar']): ?>
                                <div class="mb-2">
                                    <img src="../../uploads/<?= htmlspecialchars($data['gambar']); ?>" width="100" style="border-radius: 5px;">
                                    <br><small class="text-muted">Gambar saat ini</small>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                            <small class="text-muted">Biarkan kosong untuk tidak mengubah gambar</small>
                        </div>

                        <div class="form-group d-flex justify-content-between mt-4">
                            <a href="../manajemen_link.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
