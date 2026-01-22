<?php
session_start();
require "../koneksi.php";

/* ======================
   CEK LOGIN
====================== */
if (!isset($_SESSION['user'])) {
  header("Location: ../login.php");
  exit;
}

$nip_login = $_SESSION['user']['nip'];
$role      = $_SESSION['user']['id_role']; // 1=Admin, 2=Pegawai

$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mode = $_GET['mode'] ?? '';

if ($id <= 0 || !in_array($mode, ['dokumentasi','publikasi','pic'])) {
  errorPage("Akses tidak valid");
}

/* ======================
   DATA JADWAL
====================== */
$q = mysqli_query($koneksi,"SELECT * FROM jadwal WHERE id_jadwal='$id'");
$data = mysqli_fetch_assoc($q);
if (!$data) errorPage("Data jadwal tidak ditemukan");

/* ======================
   CEK PIC
====================== */
$is_pic = mysqli_num_rows(mysqli_query($koneksi,"
  SELECT 1 FROM pic
  WHERE id_jadwal='$id' AND nip='$nip_login'
")) > 0;

$is_medsos = mysqli_num_rows(mysqli_query($koneksi,"
  SELECT 1 FROM pic
  WHERE id_jadwal='$id' AND nip='$nip_login' AND id_jenis_pic=2
")) > 0;

/* ======================
   AKSES
====================== */
$can_edit = false;
if ($role == 1) $can_edit = true;
else {
  if ($mode=='dokumentasi' && $is_pic) $can_edit=true;
  if ($mode=='publikasi' && $is_medsos) $can_edit=true;
}
if (!$can_edit) errorPage("Anda tidak memiliki hak akses");

/* ======================
   SIMPAN
====================== */
if (isset($_POST['simpan'])) {

  if ($mode=='dokumentasi') {
    $dok = mysqli_real_escape_string($koneksi,$_POST['dokumentasi']);
    mysqli_query($koneksi,"UPDATE jadwal SET dokumentasi='$dok' WHERE id_jadwal='$id'");
  }

  if ($mode=='publikasi') {
    mysqli_query($koneksi,"
      UPDATE jadwal SET
        link_instagram='".mysqli_real_escape_string($koneksi,$_POST['link_instagram'])."',
        link_facebook='".mysqli_real_escape_string($koneksi,$_POST['link_facebook'])."',
        link_youtube='".mysqli_real_escape_string($koneksi,$_POST['link_youtube'])."',
        link_website='".mysqli_real_escape_string($koneksi,$_POST['link_website'])."'
      WHERE id_jadwal='$id'
    ");
  }

  if ($mode=='pic' && $role==1) {
    mysqli_query($koneksi,"DELETE FROM pic WHERE id_jadwal='$id'");
    foreach ($_POST['pic'] as $j=>$nip) {
      if ($nip) {
        mysqli_query($koneksi,"
          INSERT INTO pic (id_jadwal,nip,id_jenis_pic)
          VALUES ('$id','$nip','$j')
        ");
      }
    }
  }

  header("Location: index.php");
  exit;
}

/* ======================
   DATA USER
====================== */
$qUsers = mysqli_query($koneksi,"SELECT nip,nama FROM user ORDER BY nama");

function errorPage($msg){
?>
<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
Swal.fire({
  icon:'error',
  title:'Akses Ditolak',
  text:'<?= addslashes($msg) ?>'
}).then(()=>location.href='index.php');
</script>
</body>
</html>
<?php exit; }
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Data</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
  font-family:Poppins,sans-serif;
  background:#f4f7fb;
  margin:0;
  padding:40px;
}
.page-wrapper{
  max-width:900px;
  margin:auto;
}
.page-header{
  display:flex;
  align-items:center;
  gap:10px;
  margin-bottom:25px;
  color:#1f3c88;
}
.card{
  background:#fff;
  border-radius:14px;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
  padding:30px;
  position:relative;
}
.card-title{
  font-size:22px;
  font-weight:700;
  color:#1f3c88;
  margin-bottom:25px;
  text-align:center;
}
.info-box{
  background:#fff;
  border-radius:12px;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
  padding:10px 20px;
}
table{width:100%;border-collapse:collapse}
tr{border-bottom:1px solid #eee}
th{padding:14px 0;width:30%;text-align:left}
td{padding:14px 0}

input,select{
  width:100%;
  padding:8px 10px;
  border-radius:8px;
  border:1px solid #e6e6e6;
}

.btn-save{
  margin-top:25px;
  padding:10px 24px;
  background:#1e6cff;
  color:#fff;
  border:none;
  border-radius:10px;
  font-weight:600;
  cursor:pointer;
}
.btn-save:hover{background:#1a5ed8}
</style>
</head>

<body>

<div class="page-wrapper">

  <div class="page-header">
    <a href="index.php"><i class="fas fa-home"></i></a>
    <span>› Edit <?= ucfirst($mode) ?></span>
  </div>

  <div class="card">
    <div class="card-title">
      Edit <?= strtoupper($mode) ?>
    </div>

    <form method="post">
      <div class="info-box">
        <table>

<?php if($mode=='dokumentasi'): ?>
<tr>
  <th>Link Dokumentasi</th>
  <td><input type="url" name="dokumentasi" value="<?= htmlspecialchars($data['dokumentasi']) ?>"></td>
</tr>
<?php endif; ?>

<?php if($mode=='publikasi'): ?>
<tr><th>Instagram</th><td><input name="link_instagram" value="<?= $data['link_instagram'] ?>"></td></tr>
<tr><th>Facebook</th><td><input name="link_facebook" value="<?= $data['link_facebook'] ?>"></td></tr>
<tr><th>YouTube</th><td><input name="link_youtube" value="<?= $data['link_youtube'] ?>"></td></tr>
<tr><th>Website</th><td><input name="link_website" value="<?= $data['link_website'] ?>"></td></tr>
<?php endif; ?>

<?php if($mode=='pic' && $role==1): ?>
<?php $jenis=[1=>'Desain',2=>'Medsos',3=>'Narasi']; ?>
<?php foreach($jenis as $j=>$label): ?>
<tr>
  <th>PIC <?= $label ?></th>
  <td>
    <select name="pic[<?= $j ?>]">
      <option value="">-- Pilih --</option>
      <?php mysqli_data_seek($qUsers,0); while($u=mysqli_fetch_assoc($qUsers)): ?>
        <option value="<?= $u['nip'] ?>"><?= $u['nama'] ?></option>
      <?php endwhile; ?>
    </select>
  </td>
</tr>
<?php endforeach; ?>
<?php endif; ?>

        </table>
      </div>

      <div style="text-align:center">
        <button class="btn-save" name="simpan">
          <i class="fas fa-save"></i> Simpan
        </button>
      </div>
    </form>

  </div>
</div>

</body>
</html>
