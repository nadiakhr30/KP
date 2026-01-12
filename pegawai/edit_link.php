<?php
require_once __DIR__ . '/../koneksi.php';

$status  = '';
$message = '';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);

// ambil data lama
$data = mysqli_query($koneksi, "SELECT * FROM link WHERE id_link = $id");
$link = mysqli_fetch_assoc($data);

if (!$link) {
    header('Location: index.php');
    exit;
}

// PROSES UPDATE
if (isset($_POST['simpan'])) {

    $nama_link = mysqli_real_escape_string($koneksi, $_POST['nama_link']);
    $link_web  = mysqli_real_escape_string($koneksi, $_POST['link']);

    $gambar = $link['gambar'];

    // jika upload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $namaFileBaru = uniqid() . '.' . $ext;

        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            'assets/img/steps/' . $namaFileBaru
        );

        $gambar = $namaFileBaru;
    }

    $update = mysqli_query($koneksi, "
        UPDATE link SET
            nama_link = '$nama_link',
            link = '$link_web',
            gambar = '$gambar'
        WHERE id_link = $id
    ");

    if ($update) {
        $status  = 'success';
        $message = 'Link berhasil diperbarui';
    } else {
        $status  = 'error';
        $message = 'Link gagal diperbarui';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Link</title>

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

    .form-label {
      font-weight: 500;
    }

    .form-control {
      border-radius: 12px;
      padding: 12px;
    }

    .btn {
      border-radius: 12px;
      padding: 10px 20px;
      font-weight: 500;
    }

    .preview-img {
      max-height: 120px;
      border-radius: 12px;
      border: 1px solid #ddd;
      padding: 6px;
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
            <i class="fa-solid fa-pen-to-square me-2"></i>
            Edit Link Website
          </h5>
        </div>

        <div class="card-body p-4">
          <form method="POST" enctype="multipart/form-data">

            <!-- Nama -->
            <div class="mb-3">
              <label class="form-label">
                <i class="fa-solid fa-building me-1 text-primary"></i>
                Nama Instansi / Website
              </label>
              <input type="text"
                     name="nama_link"
                     value="<?= $link['nama_link']; ?>"
                     class="form-control"
                     required>
            </div>

            <!-- URL -->
            <div class="mb-3">
              <label class="form-label">
                <i class="fa-solid fa-globe me-1 text-primary"></i>
                Link Website
              </label>
              <input type="url"
                     name="link"
                     value="<?= $link['link']; ?>"
                     class="form-control"
                     required>
            </div>

            <!-- GAMBAR -->
            <div class="mb-3">
              <label class="form-label">
                <i class="fa-solid fa-image me-1 text-primary"></i>
                Gambar (Opsional)
              </label>
              <input type="file"
                     name="gambar"
                     class="form-control"
                     accept="image/*">

              <?php if (!empty($link['gambar'])): ?>
                <div class="mt-3">
                  <small class="text-muted d-block mb-1">Gambar saat ini:</small>
                  <img src="assets/img/steps/<?= $link['gambar']; ?>" class="preview-img">
                </div>
              <?php endif; ?>
            </div>

            <!-- BUTTON -->
            <div class="d-flex justify-content-between mt-4">
              <a href="index.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>
                Kembali
              </a>

              <button type="submit" name="simpan" class="btn btn-success">
                <i class="fa-solid fa-save me-1"></i>
                Update
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
