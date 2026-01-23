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
                            <label>Gambar</label>
                            <div class="text-center" style="margin-bottom: 15px;">
                                <div id="gambarPreview" style="width: 120px; height: 120px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                                    <?php if ($data['gambar']): ?>
                                        <img id="previewImg" src="../../uploads/<?= htmlspecialchars($data['gambar']); ?>" alt="Preview" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; display: block;">
                                    <?php else: ?>
                                        <img id="previewImg" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Crect fill='%23ddd' width='120' height='120'/%3E%3Ctext x='50%' y='50%' text-anchor='middle' dy='.3em' fill='%23999' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E" alt="Preview" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; display: block;">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <input type="file" id="gambarInput" name="gambar" class="form-control" accept="image/*">
                            <small class="text-muted">Biarkan kosong untuk tidak mengubah gambar</small>
                        </div>

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
<script>
    const gambarInput = document.getElementById('gambarInput');
    gambarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('previewImg').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
</body>
</html>
