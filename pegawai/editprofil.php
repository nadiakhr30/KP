<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$id_user = mysqli_real_escape_string($koneksi, $_SESSION['user']['id_user']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email  = mysqli_real_escape_string($koneksi, $_POST['email']);
    $nip    = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $jabatan= mysqli_real_escape_string($koneksi, $_POST['jabatan']);
    $telp   = mysqli_real_escape_string($koneksi, $_POST['nomor_telepon']);
    $role   = intval($_POST['role']);
    $status = intval($_POST['status']);
    $role_humas = intval($_POST['role_humas']);

    $skills = [
        'skill_data_contributor',
        'skill_content_creator',
        'skill_editor_photo_layout',
        'skill_editor_video',
        'skill_photo_videographer',
        'skill_talent',
        'skill_project_manager',
        'skill_copywriting',
        'skill_protokol',
        'skill_mc',
        'skill_operator'
    ];

    $skill_update = [];
    foreach ($skills as $s) {
        $skill_update[] = "$s=" . (isset($_POST[$s]) ? 1 : 0);
    }

    $foto_sql = "";
    if (!empty($_FILES['foto_profil']['name'])) {
        $ext = pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array(strtolower($ext), $allowed)) {
            $new = time() . '_' . $id_user . '.' . $ext;
            $upload_dir = __DIR__ . '/../uploads/';
            if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $upload_dir . $new)) {
                $foto_sql = ", foto_profil='$new'";
            } else {
                echo "<script>alert('Gagal mengunggah foto. Pastikan folder uploads writable.');</script>";
            }
        } else {
            echo "<script>alert('Format file tidak didukung!');</script>";
        }
    }

    $sql = "UPDATE user SET
        nama='$nama',
        email='$email',
        nip='$nip',
        jabatan='$jabatan',
        nomor_telepon='$telp',
        role='$role',
        status='$status',
        role_humas='$role_humas',
        " . implode(',', $skill_update) . "
        $foto_sql
        WHERE id_user='$id_user'";

    mysqli_query($koneksi, $sql);
    header('Location: profile.php');
    exit;
}

$sql = "SELECT * FROM user WHERE id_user='$id_user' LIMIT 1";
$data = mysqli_fetch_assoc(mysqli_query($koneksi, $sql));

if (!$data) die('Data pengguna tidak ditemukan');
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Profil</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
/* Reset & Body */
body {
    font-family: Poppins, sans-serif;
    background: #f4f7fb;
    margin: 0;
    padding: 40px;
}
.page-wrapper {
    max-width: 900px;
    margin: auto;
}

/* Header */
.page-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 25px;
    color: #1f3c88;
}
.page-header i {
    font-size: 20px;
}

/* Card */
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

/* Profile Image */
.profile-img {
    text-align: center;
    margin-bottom: 25px;
}
.profile-img img {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
}
.visually-hidden {
    position: absolute;
    left: -9999px;
    width: 1px;
    height: 1px;
    overflow: hidden;
}

/* Info Box & Table */
.info-box {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,.08);
    padding: 10px 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
}
tr {
    border-bottom: 1px solid #eee;
}
tr:last-child {
    border-bottom: none;
}
th, td {
    padding: 14px 0;
}
th {
    width: 30%;
    text-align: left;
}
input, select {
    width: 100%;
    padding: 10px 0;
    border-radius: 8px;
    border: 0;
    background: transparent;
    font-size: 15px;
}
.input-box {
    background: #fff;
    border: 1px solid #e6e6e6;
    padding: 8px 12px;
    border-radius: 12px;
}

/* Skills */
.skill-box {
    margin-top: 25px;
}
.skill-title {
    font-weight: 700;
    margin-bottom: 10px;
}
.skill-list {
    display: grid;
    grid-template-columns: repeat(2,1fr);
    gap: 10px;
}
.skill-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 8px;
    border-radius: 8px;
    background: transparent;
    color: #333;
    font-weight: 600;
}
.skill-item input[type=checkbox] {
    width: 16px;
    height: 16px;
}

/* Buttons */
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
    border: none;
    cursor: pointer;
}
.btn-back { background: #1e6cff; }
.btn-save { background: #ffb347; color: #000; }

/* Upload Button */
.upload-btn {
    display: inline-block;
    margin: 8px auto 0;
    padding: 6px 10px;
    font-size: 14px;
    border-radius: 6px;
    background: #f0f0f0 !important;
    color: #333 !important;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    border: 1px solid #000 !important;
    outline: none !important;
    box-shadow: none !important;
}
.upload-btn:focus,
.upload-btn:active {
    outline: none !important;
    box-shadow: none !important;
    border-color: #000 !important;
}
</style>
</head>
<body>
<div class="page-wrapper">

  <div class="page-header">
    <a href="index.php"><i class="fas fa-home"></i></a>
    <span>› Profil</span>
    <span>› Edit Profil</span>
  </div>

  <div class="card">
    <div class="card-title">Edit Profil Pengguna</div>

    <form method="post" enctype="multipart/form-data">
      <div class="profile-img">
        <?php if($data['foto_profil']): ?>
          <img src="../uploads/<?= htmlspecialchars($data['foto_profil']) ?>">
        <?php else: ?>
          <i class="fas fa-user-circle fa-6x"></i>
        <?php endif; ?>
        <br>
        <input type="file" name="foto_profil" id="foto_profil" class="visually-hidden">
        <label for="foto_profil" class="upload-btn">
            <i class="fas fa-upload"></i> Pilih Foto
        </label>
      </div>

      <div class="info-box">
        <table>
          <tr><th>Nama</th><td><div class="input-box"><input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>"></div></td></tr>
          <tr><th>Email</th><td><div class="input-box"><input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>"></div></td></tr>
          <tr><th>Role</th><td><div class="input-box"><select name="role">
            <option value="1" <?= $data['role']==1?'selected':'' ?>>Administrasi</option>
            <option value="2" <?= $data['role']==2?'selected':'' ?>>Pegawai</option>
          </select></div></td></tr>
          <tr><th>Status</th><td><div class="input-box"><select name="status">
            <option value="1" <?= $data['status']==1?'selected':'' ?>>Aktif</option>
            <option value="0" <?= $data['status']==0?'selected':'' ?>>Non-Aktif</option>
          </select></div></td></tr>
          <tr><th>NIP</th><td><div class="input-box"><input type="text" name="nip" value="<?= htmlspecialchars($data['nip']) ?>"></div></td></tr>
          <tr><th>Role Humas</th><td><div class="input-box"><select name="role_humas">
            <option value="1" <?= $data['role_humas']==1?'selected':'' ?>>Staf Humas</option>
            <option value="2" <?= $data['role_humas']==2?'selected':'' ?>>Koordinator Humas</option>
            <option value="3" <?= $data['role_humas']==3?'selected':'' ?>>Kepala BPS Kabupaten</option>
          </select></div></td></tr>
          <tr><th>Jabatan</th><td><div class="input-box"><input type="text" name="jabatan" value="<?= htmlspecialchars($data['jabatan']) ?>"></div></td></tr>
          <tr><th>Nomor Telepon</th><td><div class="input-box"><input type="text" name="nomor_telepon" value="<?= htmlspecialchars($data['nomor_telepon']) ?>"></div></td></tr>
        </table>
      </div>

      <div class="skill-box">
        <div class="skill-title">Skill</div>
        <div class="skill-list">
          <?php
          $labels = [
            'skill_data_contributor'=>'Data Contributor',
            'skill_content_creator'=>'Content Creator',
            'skill_editor_photo_layout'=>'Editor Foto/Layout',
            'skill_editor_video'=>'Editor Video',
            'skill_photo_videographer'=>'Photo/Videographer',
            'skill_talent'=>'Talent',
            'skill_project_manager'=>'Project Manager',
            'skill_copywriting'=>'Copywriting',
            'skill_protokol'=>'Protokol',
            'skill_mc'=>'MC',
            'skill_operator'=>'Operator'
          ];
          foreach($labels as $f=>$l){
            $checked = !empty($data[$f]) && $data[$f]==1 ? 'checked' : '';
            echo "<label class='skill-item'><input type='checkbox' name='$f' $checked> ".htmlspecialchars($l)."</label>";
          }
          ?>
        </div>
      </div>

      <div class="btn-group">
        <a href="profile.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Simpan Perubahan</button>
      </div>

    </form>
  </div>
</div>
</body>
</html>
