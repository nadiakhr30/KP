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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
        <div class="col-md-8 my-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Link</h5>
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

                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="image-upload-container">
                                <div class="image-preview-circle">
                                    <?php if ($data['gambar']): ?>
                                        <img id="previewImg" src="../../uploads/<?= htmlspecialchars($data['gambar']); ?>" alt="Preview">
                                    <?php else: ?>
                                        <img id="previewImg" src="../../images/no.jpg" alt="Preview">
                                    <?php endif; ?>
                                </div>
                                <div class="custom-file-upload">
                                    <input type="file" id="gambarInput" name="gambar" accept="image/*">
                                    <label for="gambarInput" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-cloud-upload-alt"></i> Tambah Gambar
                                    </label>
                                </div>
                                <div id="uploadedFilename" class="uploaded-filename"><?php echo $data['gambar'] ? $data['gambar'] : ''; ?></div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 px-5">
                                <label>Nama Link <span class="text-danger">*</span></label>
                                <input type="text" name="nama_link" class="form-control" required 
                                       value="<?= htmlspecialchars($data['nama_link']) ?>">
                            </div>
                            <div class="form-group col-md-6 px-5">
                                <label>Link Website <span class="text-danger">*</span></label>
                                <input type="url" name="link" class="form-control" required 
                                       value="<?= htmlspecialchars($data['link']) ?>">
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-between mt-4">
                            <a href="../manajemen_link.php" class="btn btn-secondary btn-icon-l"><i class="fas fa-arrow-left"></i></a>
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
            
            // Show filename
            const filenameDisplay = document.getElementById('uploadedFilename');
            filenameDisplay.textContent = file.name;
            filenameDisplay.classList.add('show');
        } else {
            document.getElementById('previewImg').src = '../assets/images/avatar-blank.jpg';
            const filenameDisplay = document.getElementById('uploadedFilename');
            filenameDisplay.classList.remove('show');
        }
    });
</script>
</body>
</html>
