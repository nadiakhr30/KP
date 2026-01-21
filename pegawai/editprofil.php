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
if (!$data) die('Data pengguna tidak ditemukan');

/* =======================
   DATA SKILL
======================= */
$sqlAllSkill = "SELECT id_skill, nama_skill FROM skill ORDER BY nama_skill ASC";
$allSkillQuery = mysqli_query($koneksi, $sqlAllSkill);
$userSkills = [];
$sqlUserSkill = "SELECT id_skill FROM user_skill WHERE nip='$nip'";
$userSkillQuery = mysqli_query($koneksi, $sqlUserSkill);
while ($row = mysqli_fetch_assoc($userSkillQuery)) {
    $userSkills[] = $row['id_skill'];
}

/* =======================
   DATA PPID
======================= */
$sqlPPID = "
SELECT p.id_ppid, p.nama_ppid, up.nip AS selected
FROM ppid p
LEFT JOIN user_ppid up ON p.id_ppid = up.id_ppid AND up.nip='$nip'
ORDER BY p.nama_ppid ASC
";
$ppidQuery = mysqli_query($koneksi, $sqlPPID);
$ppidList = [];
while ($row = mysqli_fetch_assoc($ppidQuery)) {
    $ppidList[] = $row;
}

/* =======================
   DATA HALO PST
======================= */
$sqlHalo = "
SELECT h.id_halo_pst, h.nama_halo_pst, uh.nip AS selected
FROM halo_pst h
LEFT JOIN user_halo_pst uh ON h.id_halo_pst = uh.id_halo_pst AND uh.nip='$nip'
ORDER BY h.nama_halo_pst ASC
";
$haloQuery = mysqli_query($koneksi, $sqlHalo);
$haloList = [];
while ($row = mysqli_fetch_assoc($haloQuery)) {
    $haloList[] = $row;
}

/* =======================
   HANDLE FORM SUBMIT
======================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $telp = mysqli_real_escape_string($koneksi, $_POST['nomor_telepon']);

    /* =======================
       UPLOAD FOTO PROFIL
    ======================= */
    if (!empty($_FILES['foto_profil']['name'])) {
        $ext = pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array(strtolower($ext), $allowed)) {
            $newFile = time() . '_' . $nip . '.' . $ext;
            $uploadDir = __DIR__ . '/../uploads/';
            if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $uploadDir . $newFile)) {
                mysqli_query($koneksi, "UPDATE user SET foto_profil='$newFile' WHERE nip='$nip'");
                $_SESSION['user']['foto_profil'] = $newFile;
            }
        }
    }

    /* =======================
       UPDATE USER DATA
    ======================= */
    mysqli_query($koneksi, "
        UPDATE user SET
            nama='$nama',
            email='$email',
            nomor_telepon='$telp'
        WHERE nip='$nip'
    ");

    /* =======================
       UPDATE SKILL
    ======================= */
    mysqli_query($koneksi, "DELETE FROM user_skill WHERE nip='$nip'");
    if (!empty($_POST['skill'])) {
        foreach ($_POST['skill'] as $id_skill) {
            $id_skill = (int)$id_skill;
            mysqli_query($koneksi, "INSERT INTO user_skill (nip,id_skill) VALUES ('$nip',$id_skill)");
        }
    }

    /* =======================
       UPDATE PPID
    ======================= */
    mysqli_query($koneksi, "DELETE FROM user_ppid WHERE nip='$nip'");
    if (!empty($_POST['ppid'])) {
        foreach ($_POST['ppid'] as $id_ppid) {
            $id_ppid = (int)$id_ppid;
            mysqli_query($koneksi, "INSERT INTO user_ppid (nip,id_ppid) VALUES ('$nip',$id_ppid)");
        }
    }

    /* =======================
       UPDATE HALO PST
    ======================= */
    mysqli_query($koneksi, "DELETE FROM user_halo_pst WHERE nip='$nip'");
    if (!empty($_POST['halo_pst'])) {
        foreach ($_POST['halo_pst'] as $id_halo) {
            $id_halo = (int)$id_halo;
            mysqli_query($koneksi, "INSERT INTO user_halo_pst (nip,id_halo_pst) VALUES ('$nip',$id_halo)");
        }
    }

    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Profil</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

body { 
font-family: Poppins,sans-serif; 
background:#f4f7fb; 
margin:0; 
padding:40px; 
}
.page-wrapper { 
    max-width:900px; 
    margin:auto; 
}

.page-header { 
    display:flex; 
    align-items:center; 
    gap:10px; 
    margin-bottom:25px; 
    color:#1f3c88; 
}
.page-header a { 
    text-decoration:none; 
    color:inherit; 
}
.page-header a:hover { 
    text-decoration:none; 
    color:inherit; 
    cursor:pointer; 
}

.card { 
    background:#fff; 
    border-radius:14px; 
    box-shadow:0 12px 30px rgba(0,0,0,.08); 
    padding:30px; 
    position:relative; }
.card-title { 
    font-size:22px; 
    font-weight:700; 
    color:#1f3c88; 
    margin-bottom:25px; 
}

.profile-img {
    display: flex;
    flex-direction: column; 
    align-items: center;    
    margin-bottom: 25px;
}

.profile-img img {
    width: 110px;
    height: 110px;
    max-width: 100%;
    border-radius: 50%;
    object-fit: cover;
    display: block; 
}

.upload-btn {
  display: inline-block;
  margin-top: 10px; 
  padding: 6px 10px;
  font-size: 14px;
  border-radius: 6px;
  background: #f0f0f0;
  color: #333;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  border: 1px solid #000;
  outline: none;
  box-shadow: none;
}

.upload-btn:hover {
  background: #e0e0e0;
}

.info-box { 
    background:#fff; 
    border-radius:12px; 
    box-shadow:0 10px 25px rgba(0,0,0,.08); 
    padding:10px 20px; 
}
table { 
    width:100%; 
    border-collapse:collapse; 
}
tr { 
    border-bottom:1px s
    olid #eee; 
}
th { 
    text-align:left; 
    padding:14px 0; 
    width:30%; 
}
td { 
    padding:14px 0; 
}
input[type=text], 
input[type=email] { 
    width:100%; 
    padding:8px 10px; 
    border-radius:8px; 
    border:1px solid #e6e6e6; 
    font-size:14px; 
}


.skill-box { 
    margin-top:25px; 
}
.skill-title { 
    font-weight:700; 
    margin-bottom:10px; 
}
.skill-list { 
    display:flex; 
    flex-wrap:wrap; 
    gap:8px; 
}
.skill { 
    background:#e9f2ff; 
    color:#1f3c88; 
    padding:6px 14px; 
    border-radius:20px; 
    font-size:13px; 
    font-weight:600; 
    display:flex; 
    align-items:center; 
    gap:6px; 
}
.skill input[type=checkbox] { 
    width:16px; 
    height:16px; 
    cursor:pointer; 
}

.btn-save-float { 
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
    cursor:pointer; 
    transition:.25s; 
}
.btn-save-float:hover { 
    background:#1e6cff; 
    color:#fff; border-color:#1a5ed8; 
}


</style>
</head>
<body>
<div class="page-wrapper">
  <div class="page-header">
    <a href="index.php"><i class="fas fa-home"></i></a>
    <a href="profile.php"> › Profil</a>
    <span> › Edit Profil</span>
  </div>

  <div class="card">
    <!-- BUTTON SIMPAN FLOAT -->
    <button type="submit" form="editForm" class="btn-save-float" title="Simpan Perubahan">
      <i class="fas fa-save"></i>
    </button>

    <div class="card-title">Edit Profil Pengguna</div>

    <form id="editForm" method="post" enctype="multipart/form-data">

      <!-- FOTO PROFIL -->
      <div class="profile-img">
        <?php if($data['foto_profil']): ?>
            <img src="../uploads/<?= htmlspecialchars($data['foto_profil']) ?>">
        <?php else: ?>
            <i class="fas fa-user-circle fa-6x"></i>
        <?php endif; ?>

        <input type="file" name="foto_profil" id="foto_profil" class="visually-hidden">
        
       </div>


      <!-- INFO USER -->
      <div class="info-box">
        <table>
          <tr><th>Nama</th><td><input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>"></td></tr>
          <tr><th>Email</th><td><input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>"></td></tr>
          <tr><th>NIP</th><td><input type="text" name="nip" value="<?= htmlspecialchars($data['nip']) ?>" readonly></td></tr>
          <tr><th>Nomor Telepon</th><td><input type="text" name="nomor_telepon" value="<?= 0 . htmlspecialchars($data['nomor_telepon']) ?>"></td></tr>
        </table>
      </div>

      <!-- SKILL -->
      <div class="skill-box">
        <div class="skill-title">Skill</div>
        <div class="skill-list">
          <?php foreach ($allSkillQuery as $row): ?>
            <?php $checked = in_array($row['id_skill'], $userSkills) ? 'checked' : ''; ?>
            <label class="skill">
              <input type="checkbox" name="skill[]" value="<?= $row['id_skill'] ?>" <?= $checked ?>>
              <?= htmlspecialchars($row['nama_skill']) ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- PPID -->
      <div class="skill-box">
        <div class="skill-title">PPID</div>
        <div class="skill-list">
          <?php foreach ($ppidList as $p): ?>
            <?php $checked = $p['selected'] ? 'checked' : ''; ?>
            <label class="skill">
              <input type="checkbox" name="ppid[]" value="<?= $p['id_ppid'] ?>" <?= $checked ?>>
              <?= htmlspecialchars($p['nama_ppid']) ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- HALO PST -->
      <div class="skill-box">
        <div class="skill-title">Halo PST</div>
        <div class="skill-list">
          <?php foreach ($haloList as $h): ?>
            <?php $checked = $h['selected'] ? 'checked' : ''; ?>
            <label class="skill">
              <input type="checkbox" name="halo_pst[]" value="<?= $h['id_halo_pst'] ?>" <?= $checked ?>>
              <?= htmlspecialchars($h['nama_halo_pst']) ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

    </form>
  </div>
</div>
</body>
</html>
