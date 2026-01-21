<?php
include("../../koneksi.php");

$status  = '';
$message = '';

// proses simpan
if (isset($_POST['simpan'])) {

    $nama_link = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['nama_link'])
    );

    $link_web = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['link'])
    );

    // validasi input
    if ($nama_link == '' || $link_web == '') {
        $status  = 'error';
        $message = 'Nama link dan URL wajib diisi!';
    } else {

        $gambar = '';

        // upload gambar (opsional)
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {

            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $namaFileBaru = uniqid() . '.' . $ext;

            move_uploaded_file(
                $_FILES['gambar']['tmp_name'],
                'assets/img/steps/' . $namaFileBaru
            );

            $gambar = $namaFileBaru;
        }

        // simpan ke database
        $simpan = mysqli_query($koneksi, "
            INSERT INTO link (nama_link, gambar, link)
            VALUES ('$nama_link', '$gambar', '$link_web')
        ");

        if ($simpan) {
            $status  = 'success';
            $message = 'Link berhasil ditambahkan!';
            header("Refresh: 1; url=../manajemen_link.php");
        } else {
            $status  = 'error';
            $message = 'Link gagal ditambahkan!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Link</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>

<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Link Website</h5>
                </div>

                <div class="card-body">

                    <?php if ($status == 'error'): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <?php if ($status == 'success'): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">

                        <div class="form-group">
                            <label>Nama Instansi / Website <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="nama_link"
                                class="form-control"
                                required
                                value="<?= isset($_POST['nama_link']) ? htmlspecialchars($_POST['nama_link']) : '' ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label>Link Website <span class="text-danger">*</span></label>
                            <input
                                type="url"
                                name="link"
                                class="form-control"
                                required
                                value="<?= isset($_POST['link']) ? htmlspecialchars($_POST['link']) : '' ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label>Upload Gambar (Opsional)</label>
                            <input
                                type="file"
                                name="gambar"
                                class="form-control"
                                accept="image/*"
                            >
                            <small class="text-muted">
                                Jika tidak upload, gambar akan dikosongkan
                            </small>
                        </div>

                        <div class="form-group d-flex justify-content-between mt-4">
                            <a href="../manajemen_link.php" class="btn btn-secondary">
                                Batal
                            </a>

                            <button type="submit" name="simpan" class="btn btn-primary">
                                Simpan
                            </button>
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
