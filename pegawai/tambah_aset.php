<?php
session_start();
require '../koneksi.php';

// cek login
if (!isset($_SESSION['user']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

$error = '';
$success = '';

if (isset($_POST['simpan'])) {

    $nama        = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $keterangan  = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $jenis       = $_POST['jenis'];
    $pemegang    = $_POST['pemegang'];
    $link        = mysqli_real_escape_string($koneksi, $_POST['link']);

    // ===== upload foto =====
    $fotoName = null;
    if (!empty($_FILES['foto_aset']['name'])) {

        $folder = "../uploads/aset/";
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $ext = pathinfo($_FILES['foto_aset']['name'], PATHINFO_EXTENSION);
        $fotoName = uniqid('aset_') . '.' . $ext;

        move_uploaded_file($_FILES['foto_aset']['tmp_name'], $folder . $fotoName);
    }

    // simpan ke database
    $insert = mysqli_query($koneksi, "
        INSERT INTO aset (
            nama,
            keterangan,
            jenis,
            pemegang,
            link,
            foto_aset
        ) VALUES (
            '$nama',
            '$keterangan',
            '$jenis',
            '$pemegang',
            '$link',
            '$fotoName'
        )
    ");

    if ($insert) {
        $success = "Aset berhasil ditambahkan";
    } else {
        $error = "Gagal menyimpan data";
    }
}

// ambil user untuk dropdown
$users = mysqli_query($koneksi, "SELECT id_user,nama FROM user ORDER BY nama");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Aset</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
  font-family: Poppins, sans-serif;
  background: #f4f7fb;
  padding: 40px;
}
.card {
  border-radius: 18px;
  box-shadow: 0 15px 35px rgba(0,0,0,.08);
}
</style>
</head>

<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-7">

      <div class="card border-0">
        <div class="card-body p-4">

          <h4 class="fw-bold mb-4">Tambah Aset</h4>

          <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
          <?php endif; ?>

          <form method="post" enctype="multipart/form-data">

            <div class="mb-3">
              <label class="form-label">Nama Aset</label>
              <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Keterangan</label>
              <textarea name="keterangan" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Jenis Aset</label>
              <select name="jenis" class="form-select" required>
                <option value="">-- Pilih --</option>
                <option value="1">Aset Visual</option>
                <option value="2">Aset Barang</option>
                <option value="3">Aset Lisensi</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Penanggung Jawab</label>
              <select name="pemegang" class="form-select">
                <option value="">-- Pilih --</option>
                <?php while ($u = mysqli_fetch_assoc($users)) { ?>
                  <option value="<?= $u['id_user'] ?>">
                    <?= htmlspecialchars($u['nama']) ?>
                  </option>
                <?php } ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Link (Opsional)</label>
              <input type="url" name="link" class="form-control">
            </div>

            <div class="mb-4">
              <label class="form-label">Foto Aset</label>
              <input type="file" name="foto_aset" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
              <a href="aset.php" class="btn btn-secondary">Kembali</a>
              <button type="submit" name="simpan" class="btn btn-primary">
                Simpan Aset
              </button>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</div>

</body>
</html>
