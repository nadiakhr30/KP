<?php
session_start();
include "../koneksi.php";

// cek login
if (!isset($_SESSION['user'])) {
  header("Location: ../login.php");
  exit;
}

$id_user = $_SESSION['user']['id_user'];
$role    = $_SESSION['user']['role']; // 1=admin, 2=pegawai

$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mode = $_GET['mode'] ?? '';

$error = '';
if ($id == 0) {
    $error = "Akses tidak valid: ID kosong";
} elseif (!in_array($mode, ['dokumentasi','publikasi','pic'])) {
    $error = "Akses tidak valid";
} else {
    $data = mysqli_fetch_assoc(
      mysqli_query($koneksi, "SELECT * FROM jadwal WHERE id_jadwal='$id'")
    );
    if (!$data) $error = "Data tidak ditemukan";
}

// jika ada error, tampilkan halaman alert
if ($error) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
      <meta charset="UTF-8">
      <title>Error</title>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: '<?= addslashes($error) ?>',
          confirmButtonText: 'Kembali'
        }).then(() => {
          window.location.href = 'index.php';
        });
      </script>
    </body>
    </html>
    <?php
    exit;
}

// ambil list user untuk dropdown PIC
$users = mysqli_query($koneksi, "SELECT id_user,nama FROM user ORDER BY nama");

// cek hak akses edit
$can_edit = false;
if ($mode == 'dokumentasi') {
    if (in_array($id_user, [$data['pic_desain'], $data['pic_medsos'], $data['pic_narasi']]) || $role == 1) {
        $can_edit = true;
    }
} elseif ($mode == 'publikasi') {
    if ($id_user == $data['pic_medsos'] || $role == 1) {
        $can_edit = true;
    }
} elseif ($mode == 'pic') {
    if ($role == 1) {
        $can_edit = true;
    }
}

if (!$can_edit) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
      <meta charset="UTF-8">
      <title>Akses Ditolak</title>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
      <script>
        Swal.fire({
          icon: 'error',
          title: '<span style="font-family:Poppins,sans-serif;font-weight:600">Akses Ditolak</span>',
          html: '<span style="font-family:Poppins, sans-serif">Anda tidak memiliki hak akses untuk mengedit ini</span>',
          confirmButtonText: '<span style="font-family:Poppins, sans-serif;font-weight:600">Kembali</span>',
        }).then(() => {
          window.location.href = 'index.php';
        });
      </script>
    </body>
    </html>
    <?php
    exit;
}

// proses simpan data
if (isset($_POST['simpan'])) {

  if ($mode == 'dokumentasi') {
    $dok = mysqli_real_escape_string($koneksi, $_POST['dokumentasi']);
    mysqli_query($koneksi,
      "UPDATE jadwal SET dokumentasi='$dok' WHERE id_jadwal='$id'"
    );
  }

  if ($mode == 'publikasi') {
    $ig  = mysqli_real_escape_string($koneksi, $_POST['link_instagram']);
    $fb  = mysqli_real_escape_string($koneksi, $_POST['link_facebook']);
    $yt  = mysqli_real_escape_string($koneksi, $_POST['link_youtube']);
    $web = mysqli_real_escape_string($koneksi, $_POST['link_website']);

    mysqli_query($koneksi,"
      UPDATE jadwal SET
        link_instagram='$ig',
        link_facebook='$fb',
        link_youtube='$yt',
        link_website='$web'
      WHERE id_jadwal='$id'
    ");
  }

  if ($mode == 'pic') {
    $desain = $_POST['pic_desain'];
    $medsos = $_POST['pic_medsos'];
    $narasi = $_POST['pic_narasi'];

    mysqli_query($koneksi,"
      UPDATE jadwal SET
        pic_desain='$desain',
        pic_medsos='$medsos',
        pic_narasi='$narasi'
      WHERE id_jadwal='$id'
    ");
  }

  header("Location: edit_dokumentasi.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">


<style>
body {
  font-family: Poppins, sans-serif;
  background: #f4f7fb;
  padding: 40px;
}

.page-wrapper {
  max-width: 900px;
  margin: auto;
}

.page-header {
  display: flex;
  gap: 10px;
  margin-bottom: 25px;
  color: #1f3c88;
}

.card {
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 12px 30px rgba(0,0,0,.08);
  padding: 30px;
}

.card-title {
  font-size: 22px;
  font-weight: 700;
  color: #1f3c88;
  margin-bottom: 25px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

tr {
  border-bottom: 1px solid #eee;
}

th,
td {
  padding: 14px 0;
}

th {
  width: 30%;
  text-align: left;
}

.input-box {
  border: 1px solid #e6e6e6;
  border-radius: 12px;
  padding: 8px 12px;
}

input,
select {
  width: 100%;
  border: 0;
  background: transparent;
  font-size: 15px;
}

.btn-group {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 30px;
}

.btn {
  padding: 12px 22px;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  color: #fff;
  border: 0;
  cursor: pointer;
}

.btn-back {
  background: #1e6cff;
}

.btn-save {
  background: #ffb347;
  color: #000;
}
</style>

</head>

<body>
<div class="page-wrapper">

  <div class="page-header">
    <a href="index.php"><i class="fas fa-home"></i></a>
    <span>› Edit</span>
  </div>

  <div class="card">
    <div class="card-title">
      <?= $mode=='dokumentasi'?'Edit Dokumentasi':($mode=='publikasi'?'Edit Link Publikasi':'Edit PIC') ?>
    </div>

    <form method="post">
      <table>

        <?php if ($mode=='dokumentasi'): ?>
        <tr>
          <th>Link Dokumentasi</th>
          <td>
            <div class="input-box">
              <input type="url" name="dokumentasi"
                value="<?= htmlspecialchars($data['dokumentasi']) ?>">
            </div>
          </td>
        </tr>
        <?php endif; ?>

        <?php if ($mode=='publikasi'): ?>
        <tr><th>Instagram</th><td><div class="input-box">
          <input type="url" name="link_instagram" value="<?= htmlspecialchars($data['link_instagram']) ?>">
        </div></td></tr>

        <tr><th>Facebook</th><td><div class="input-box">
          <input type="url" name="link_facebook" value="<?= htmlspecialchars($data['link_facebook']) ?>">
        </div></td></tr>

        <tr><th>YouTube</th><td><div class="input-box">
          <input type="url" name="link_youtube" value="<?= htmlspecialchars($data['link_youtube']) ?>">
        </div></td></tr>

        <tr><th>Website</th><td><div class="input-box">
          <input type="url" name="link_website" value="<?= htmlspecialchars($data['link_website']) ?>">
        </div></td></tr>
        <?php endif; ?>

        <?php if ($mode=='pic'): ?>
        <?php
        function opt($users,$selected){
          mysqli_data_seek($users,0);
          while($u=mysqli_fetch_assoc($users)){
            $s=$u['id_user']==$selected?'selected':'';
            echo "<option value='{$u['id_user']}' $s>".htmlspecialchars($u['nama'])."</option>";
          }
        }
        ?>
        <tr><th>PIC Desain</th><td><div class="input-box">
          <select name="pic_desain"><option value="">-- Pilih --</option><?php opt($users,$data['pic_desain']); ?></select>
        </div></td></tr>

        <tr><th>PIC Medsos</th><td><div class="input-box">
          <select name="pic_medsos"><option value="">-- Pilih --</option><?php opt($users,$data['pic_medsos']); ?></select>
        </div></td></tr>

        <tr><th>PIC Narasi</th><td><div class="input-box">
          <select name="pic_narasi"><option value="">-- Pilih --</option><?php opt($users,$data['pic_narasi']); ?></select>
        </div></td></tr>
        <?php endif; ?>

      </table>

      <div class="btn-group">
        <button type="submit" name="simpan" class="btn btn-save">
          <i class="fas fa-save"></i> Simpan
        </button>
      </div>

    </form>
  </div>
</div>
</body>
</html>
