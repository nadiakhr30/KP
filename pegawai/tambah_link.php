<?php
require_once __DIR__ . '/../koneksi.php';

$status  = '';
$message = '';

if (isset($_POST['simpan'])) {

    $nama_link = mysqli_real_escape_string($koneksi, $_POST['nama_link']);
    $link_web  = mysqli_real_escape_string($koneksi, $_POST['link']);

    $gambar = '';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $namaFileBaru = uniqid() . '.' . $ext;

        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            'assets/img/steps/' . $namaFileBaru
        );

        $gambar = $namaFileBaru;
    }

    $simpan = mysqli_query($koneksi, "
        INSERT INTO link (nama_link, gambar, link)
        VALUES ('$nama_link', '$gambar', '$link_web')
    ");

    if ($simpan) {
        $status  = 'success';
        $message = 'Link berhasil ditambahkan';
    } else {
        $status  = 'error';
        $message = 'Link gagal ditambahkan';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Link</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f4f6f9;
    }

    .card {
      border-radius: 18px;
    }

    .card-header {
      background: linear-gradient(135deg, #0d6efd, #0a58ca);
      border-radius: 18px 18px 0 0;
      padding: 18px 24px;
    }

    .card-header h5 {
      font-weight: 600;
      letter-spacing: 0.3px;
    }

    .form-label {
      font-weight: 500;
      color: #444;
    }

    .form-control {
      border-radius: 12px;
      padding: 12px 14px;
      font-size: 14px;
    }

    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.15rem rgba(13,110,253,.2);
    }

    .btn {
      border-radius: 12px;
      padding: 10px 20px;
      font-weight: 500;
    }

    .btn-success {
      background: linear-gradient(135deg, #198754, #157347);
      border: none;
    }

    .btn-secondary {
      border: none;
    }

    small {
      font-size: 12px;
    }
  </style>
</head>

<body>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">

      <div class="card shadow border-0">
        <div class="card-header text-white">
          <h5 class="mb-0">
            <i class="fa-solid fa-link me-2"></i>
            Tambah Link Website
          </h5>
        </div>

        <div class="card-body p-4">
          <form method="POST" enctype="multipart/form-data">

            <!-- Nama Link -->
            <div class="mb-3">
              <label class="form-label">
                <i class="fa-solid fa-building me-1 text-primary"></i>
                Nama Instansi / Website
              </label>
              <input type="text" name="nama_link" class="form-control" required>
            </div>

            <!-- URL -->
            <div class="mb-3">
              <label class="form-label">
                <i class="fa-solid fa-globe me-1 text-primary"></i>
                Link Website
              </label>
              <input type="url" name="link" class="form-control" required>
            </div>

            <!-- Upload -->
            <div class="mb-3">
              <label class="form-label">
                <i class="fa-solid fa-image me-1 text-primary"></i>
                Upload Gambar (Opsional)
              </label>
              <input type="file" name="gambar" class="form-control" accept="image/*">
              <small class="text-muted">
                Jika tidak upload gambar, akan menggunakan gambar default
              </small>
            </div>

            <!-- BUTTON -->
            <div class="d-flex justify-content-between mt-4">
              <a href="index.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>
                Kembali
              </a>

              <button type="submit" name="simpan" class="btn btn-success">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Simpan
              </button>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<?php if ($status): ?>
<script>
Swal.fire({
  icon: '<?= $status ?>',
  title: '<?= $status === "success" ? "Berhasil!" : "Gagal!" ?>',
  text: '<?= $message ?>',
  confirmButtonColor: '#0d6efd'
}).then(() => {
  <?php if ($status === 'success'): ?>
    window.location = 'index.php';
  <?php endif; ?>
});
</script>
<?php endif; ?>

</body>
</html>
