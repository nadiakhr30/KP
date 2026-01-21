<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

if (!isset($_SESSION['user']['nip'])) {
    header('Location: ../index.php');
    exit;
}

$nip = mysqli_real_escape_string($koneksi, $_SESSION['user']['nip']);

/* =======================
   DATA USER
======================= */
$sqlUser = "
SELECT 
    u.nip,
    u.nama,
    u.email,
    u.foto_profil,
    u.status,
    u.nomor_telepon,
    u.id_role,
    j.nama_jabatan
FROM user u
LEFT JOIN jabatan j ON u.id_jabatan = j.id_jabatan
WHERE u.nip = '$nip'
LIMIT 1
";
$userQuery = mysqli_query($koneksi, $sqlUser);
$data = mysqli_fetch_assoc($userQuery);

if (!$data) {
    die('Data pengguna tidak ditemukan');
}

/* =======================
   DATA SKILL
======================= */
$sqlSkill = "
SELECT s.nama_skill
FROM user_skill us
JOIN skill s ON us.id_skill = s.id_skill
WHERE us.nip = '$nip'
ORDER BY s.nama_skill ASC
";
$skillQuery = mysqli_query($koneksi, $sqlSkill);
$skills = [];
while ($row = mysqli_fetch_assoc($skillQuery)) {
    $skills[] = $row['nama_skill'];
}

/* =======================
   DATA PPID
======================= */
$sqlPPID = "
SELECT p.nama_ppid
FROM user_ppid up
JOIN ppid p ON up.id_ppid = p.id_ppid
WHERE up.nip = '$nip'
ORDER BY p.nama_ppid ASC
";
$ppidQuery = mysqli_query($koneksi, $sqlPPID);
$ppids = [];
while ($row = mysqli_fetch_assoc($ppidQuery)) {
    $ppids[] = $row['nama_ppid'];
}

/* =======================
   DATA HALO PST
======================= */
$sqlHalo = "
SELECT h.nama_halo_pst
FROM user_halo_pst uh
JOIN halo_pst h ON uh.id_halo_pst = h.id_halo_pst
WHERE uh.nip = '$nip'
ORDER BY h.nama_halo_pst ASC
";
$haloQuery = mysqli_query($koneksi, $sqlHalo);
$halos = [];
while ($row = mysqli_fetch_assoc($haloQuery)) {
    $halos[] = $row['nama_halo_pst'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Pengguna</title>

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
th{
  text-align:left;
  padding:14px 0;
  width:30%;
}
td{
  padding:14px 0;
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

/* BUTTON EDIT */
.btn-edit{
  position:absolute;
  top:20px;
  right:20px;
  width:38px;
  height:38px;
  border:1.5px solid #e0e0e0;
  background:#fff;
  border-radius:10px;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#555;
  text-decoration:none;
  transition:.25s;
}
.btn-edit:hover{
  background:#fff3c4;
  color:#d99a00;
  border-color:#ffd54f;
}
</style>
</head>

<body>

<div class="page-wrapper">

  <div class="page-header">
    <a href="index.php"><i class="fas fa-home"></i></a>
    <span>› Profil</span>
  </div>

  <div class="card">

    <!-- BUTTON EDIT -->
    <a href="editprofil.php" class="btn-edit" title="Edit Profil">
      <i class="fas fa-pen"></i>
    </a>

    <div class="card-title">Informasi Pengguna</div>

    <div class="profile-img">
      <?php
      $foto = trim($data['foto_profil'] ?? '');
      $path = __DIR__ . '/../uploads/' . $foto;

      if ($foto && file_exists($path)) {
          echo '<img src="../uploads/'.htmlspecialchars($foto).'">';
      } else {
          echo '<i class="fas fa-user-circle fa-6x"></i>';
      }
      ?>
    </div>

    <div class="info-box">
      <table>
        <tr><th>Nama</th><td><?= htmlspecialchars($data['nama']) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($data['email']) ?></td></tr>
        <tr><th>Role</th><td><?= $data['id_role']==1?'Administrasi':($data['id_role']==2?'Pegawai':'Admin') ?></td></tr>
        <tr><th>Status</th><td><?= $data['status']==1?'Aktif':'Non-Aktif' ?></td></tr>
        <tr><th>NIP</th><td><?= htmlspecialchars($data['nip']) ?></td></tr>
        <tr><th>Jabatan</th><td><?= htmlspecialchars($data['nama_jabatan'] ?? '-') ?></td></tr>
        <tr><th>Nomor Telepon</th><td>0<?= htmlspecialchars($data['nomor_telepon'] ?? '-') ?></td></tr>
      </table>
    </div>

    <div class="skill-box">
      <div class="skill-title">Skill</div>
      <div class="skill-list">
        <?php if (!empty($skills)): ?>
          <?php foreach ($skills as $skill): ?>
            <span class="skill"><?= htmlspecialchars($skill) ?></span>
          <?php endforeach; ?>
        <?php else: ?>
          <span>- Tidak ada skill -</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="skill-box">
      <div class="skill-title">PPID</div>
      <div class="skill-list">
        <?php if (!empty($ppids)): ?>
          <?php foreach ($ppids as $p): ?>
            <span class="skill"><?= htmlspecialchars($p) ?></span>
          <?php endforeach; ?>
        <?php else: ?>
          <span>- Tidak ada PPID -</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="skill-box">
      <div class="skill-title">Halo PST</div>
      <div class="skill-list">
        <?php if (!empty($halos)): ?>
          <?php foreach ($halos as $h): ?>
            <span class="skill"><?= htmlspecialchars($h) ?></span>
          <?php endforeach; ?>
        <?php else: ?>
          <span>- Tidak ada Halo PST -</span>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

</body>
</html>
