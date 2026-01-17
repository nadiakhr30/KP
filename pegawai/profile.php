<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

$id_user = mysqli_real_escape_string($koneksi, $_SESSION['user']['id_user']);

$sql = "SELECT 
  nama, email, role, status, foto_profil, nip, role_humas, jabatan, nomor_telepon,
    skill_data_contributor,
    skill_content_creator,
    skill_editor_photo_layout,
    skill_editor_video,
    skill_photo_videographer,
    skill_talent,
    skill_project_manager,
    skill_copywriting,
    skill_protokol,
    skill_mc,
    skill_operator
FROM user
WHERE id_user='$id_user'
LIMIT 1";

$result = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die('Data pengguna tidak ditemukan');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Pengguna</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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

.page-header i{
  font-size:20px;
}

.card{
  background:#fff;
  border-radius:14px;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
  padding:30px;
}

.card-title{
  font-size:22px;
  font-weight:700;
  color:#1f3c88;
  margin-bottom:25px;
}

.profile-img{
  display:flex;
  justify-content:center;
  margin-bottom:25px;
}
.profile-img img{
  width:110px;
  height:110px;
  border-radius:50%;
  object-fit:cover;
}
.profile-img i{
  color:#222;
}

.info-box{
  background:#fff;
  border-radius:12px;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
  padding:10px 20px;
}

table{
  width:100%;
  border-collapse:collapse;
}
tr{
  border-bottom:1px solid #eee;
}
tr:last-child{
  border-bottom:none;
}
th{
  text-align:left;
  padding:14px 0;
  color:#000;
  width:30%;
}
td{
  padding:14px 0;
  font-weight:500;
  color:#333;
}

.skill-box{
  margin-top:25px;
}
.skill-title{
  font-weight:700;
  margin-bottom:10px;
}
.skill-list{
  display:flex;
  flex-wrap:wrap;
  gap:8px;
}
.skill{
  background:#e9f2ff;
  color:#1f3c88;
  padding:6px 14px;
  border-radius:20px;
  font-size:13px;
  font-weight:600;
}

.btn-group{
  display:flex;
  justify-content:center;
  gap:15px;
  margin-top:30px;
}
.btn{
  padding:12px 22px;
  border-radius:8px;
  font-weight:600;
  text-decoration:none;
  color:#fff;
}
.btn-back{background:#1e6cff}
.btn-edit{background:#ffb347;color:#000}
.btn-password{background:#6a5acd}
</style>
</head>

<body>

<div class="page-wrapper">

  <div class="page-header">
    <a href="index.php" title="Beranda"><i class="fas fa-home"></i></a>
    <span>› Profil</span>
  </div>

  <div class="card">
    <div class="card-title">Informasi Pengguna</div>

    <div class="profile-img">
      <?php
        $foto = !empty($data['foto_profil']) ? trim($data['foto_profil']) : '';
        $foto_path = $foto ? __DIR__ . '/../uploads/' . $foto : '';
        if ($foto && file_exists($foto_path)) {
          echo '<img src="../uploads/'.htmlspecialchars($foto).'">';
        } else {
          echo '<i class="fas fa-user-circle fa-6x"></i>';
        }
      ?>
    </div>

    <div class="info-box">
      <table>
        <tr><th>Nama</th><td><?= htmlspecialchars($data['nama'] ?? '') ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($data['email'] ?? '') ?></td></tr>
        <tr>
          <th>Role</th>
          <td><?= $data['role']==1?'Administrasi':($data['role']==2?'Pegawai':'Admin') ?></td>
        </tr>
        <tr>
          <th>Status</th>
          <td><?= $data['status']==1?'Aktif':'Non-Aktif' ?></td>
        </tr>
        <tr><th>NIP</th><td><?= htmlspecialchars($data['nip'] ?? '') ?></td></tr>
        <tr><th>Role Humas</th><td><?= htmlspecialchars($data['role_humas'] ?? '') ?></td></tr>
        <tr><th>Jabatan</th><td><?= htmlspecialchars($data['jabatan'] ?? '') ?></td></tr>
        <tr><th>Nomor Telepon</th><td><?= htmlspecialchars($data['nomor_telepon'] ?? '') ?></td></tr>
      </table>
    </div>

    <div class="skill-box">
      <div class="skill-title">Skill</div>
      <div class="skill-list">
        <?php
        $skills = [
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
        foreach($skills as $f=>$l){
          if($data[$f]==1) echo "<span class='skill'>$l</span>";
        }
        ?>
      </div>
    </div>

    <div class="btn-group">

    <!-- Tombol Edit Profil -->
    <a href="editprofil.php" class="btn btn-edit">
        <i class="fas fa-user-edit"></i> Edit Profil
    </a>
    </div>

  </div>
</div>

</body>
</html>
