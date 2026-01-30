<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

if (!isset($_SESSION['pegawai']['nip'])) {
    header('Location: ../index.php');
    exit;
}

$nip = mysqli_real_escape_string($koneksi, $_SESSION['pegawai']['nip']);

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
    u.id_ppid,
    r.nama_role,
    j.nama_jabatan,
    p.nama_ppid
FROM pegawai u
LEFT JOIN jabatan j ON u.id_jabatan = j.id_jabatan
LEFT JOIN ppid p ON u.id_ppid = p.id_ppid
LEFT JOIN role r ON u.id_role = r.id_role
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
$sqlAllPPID = "SELECT * FROM ppid ORDER BY nama_ppid ASC";
$allPPIDQuery = mysqli_query($koneksi, $sqlAllPPID);
$ppidList = [];
while ($row = mysqli_fetch_assoc($allPPIDQuery)) {
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
    // Validate Halo PST (required)
    if (empty($_POST['halo_pst'])) {
        die('Halo PST harus dipilih minimal satu!');
    }

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $telp = mysqli_real_escape_string($koneksi, $_POST['nomor_telepon']);
    $id_ppid = isset($_POST['id_ppid']) ? (int)$_POST['id_ppid'] : 0;

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
                mysqli_query($koneksi, "UPDATE pegawai SET foto_profil='$newFile' WHERE nip='$nip'");
                $_SESSION['pegawai']['foto_profil'] = $newFile;
            }
        }
    }

    /* =======================
       UPDATE USER DATA
    ======================= */
    mysqli_query($koneksi, "
        UPDATE pegawai SET
            nama='$nama',
            email='$email',
            nomor_telepon='$telp',
            id_ppid=" . ($id_ppid > 0 ? $id_ppid : "NULL") . "
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
        }
        
        body {
           
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .page-wrapper {
            max-width: 800px;
            margin: auto;
        }
        
        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            color: white;
            font-size: 14px;
        }
        
        .breadcrumb-custom a {
            color: #009cfd 0%;
            text-decoration: none;
            transition: 0.25s;
        }
        .breadcrumb-link {
            color: #009cfd;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-link:hover {
            text-decoration: underline;
        }

        .breadcrumb-separator {
            color: #b0b0b0;
        }

        .breadcrumb-active {
            color: #009cfd;
            font-weight: 600;
        }
        .breadcrumb-custom a:hover {
            text-decoration: underline;
        }
        
        .edit-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 156, 253, 0.3);
            padding: 40px;
            position: relative;
        }
        
        .card-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .profile-img {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .profile-img img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
           
        }
        
        .profile-img i {
            font-size: 100px;
            color: #ddd;
        }
        
        .upload-label {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background: #009cfd 0%;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: 0.25s;
        }
        
        .upload-label:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            background: #0f4382;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            font-size: 14px;
            transition: 0.25s;
        }
        
        .form-control:focus {
            border-color: #009cfd 0%;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        
        .form-control:disabled {
            background-color: #f5f5f5;
            color: #999;
        }
        
        .section-title {
            font-weight: 600;
            color: #009cfd 0%;
            font-size: 15px;
            margin-top: 25px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .skill-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }
        
        .skill-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: #f0f4ff;
            border: 1.5px solid #e0e6ff;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.25s;
        }
        
        .skill-item:hover {
            background: #e5ebff;
            border-color: #667eea;
        }
        
        .skill-item input[type="checkbox"] {
            cursor: pointer;
            width: 16px;
            height: 16px;
        }
        
        .skill-item label {
            margin: 0;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: #333;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn {
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            flex: 1;
            border: none;
            transition: 0.25s;
        }
        
        .btn-primary {
            background: #009cfd 0%;
            color: white;
        }
        
        .btn-primary:hover {
            background: #009cfd 0%;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
            transform: translateY(-2px);
            color: #333;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="breadcrumb-custom">
            <a href="index.php" class="breadcrumb-link">
                <i class="bi bi-house-fill"></i>
            </a>
            <span class="breadcrumb-separator">›</span>
            <a href="profile.php" class="breadcrumb-link">Profil</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-active">Edit</span>
        </div>

        <div class="edit-card">
            <div class="card-title">
                <i class="bi bi-pencil-square"></i>
                Edit Profil
            </div>

            <form method="POST" enctype="multipart/form-data">
                <!-- FOTO PROFIL -->
                <div class="profile-img">
                    <?php if ($data['foto_profil']): ?>
                        <img src="../uploads/<?= htmlspecialchars($data['foto_profil']) ?>" alt="Foto Profil">
                    <?php else: ?>
                        <i class="bi bi-person-circle"></i>
                    <?php endif; ?>
                    
                    <input type="file" name="foto_profil" id="foto_profil" class="d-none" accept="image/*">
                    <label for="foto_profil" class="upload-label">
                         Ganti Foto
                    </label>
                </div>

                <!-- INFORMASI DASAR -->
                <div class="section-title">
                    <i class="bi bi-person-fill"></i>
                    Informasi Dasar
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($data['nip']) ?>" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="nomor_telepon" class="form-control" value="<?= htmlspecialchars($data['nomor_telepon']) ?>">
                </div>

                <!-- SKILL -->
                <div class="section-title">
                    <i class="bi bi-star-fill"></i>
                    Skill
                </div>

                <div class="skill-group">
                    <?php
                    // Reset pointer ke awal
                    mysqli_data_seek($allSkillQuery, 0);
                    while ($row = mysqli_fetch_assoc($allSkillQuery)):
                        $checked = in_array($row['id_skill'], $userSkills) ? 'checked' : '';
                    ?>
                        <div class="skill-item">
                            <input type="checkbox" name="skill[]" id="skill_<?= $row['id_skill'] ?>" value="<?= $row['id_skill'] ?>" <?= $checked ?>>
                            <label for="skill_<?= $row['id_skill'] ?>">
                                <?= htmlspecialchars($row['nama_skill']) ?>
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- PPID -->
                <div class="mb-3">
                    <label class="form-label">PPID</label>
                    <select name="id_ppid" class="form-control">
                        <option value="">-- Pilih PPID --</option>
                        <?php foreach ($ppidList as $ppid): ?>
                            <option value="<?= $ppid['id_ppid'] ?>" <?= ($data['id_ppid'] == $ppid['id_ppid']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ppid['nama_ppid']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- HALO PST -->
                <div class="section-title">
                    <i class="bi bi-chat-left-text"></i>
                    Halo PST
                </div>

                <div class="skill-group">
                    <?php foreach ($haloList as $h): ?>
                        <div class="skill-item">
                            <input type="checkbox" name="halo_pst[]" id="halo_<?= $h['id_halo_pst'] ?>" value="<?= $h['id_halo_pst'] ?>" <?= $h['selected'] ? 'checked' : '' ?>>
                            <label for="halo_<?= $h['id_halo_pst'] ?>">
                                <?= htmlspecialchars($h['nama_halo_pst']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- BUTTONS -->
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Simpan Perubahan
                    </button>
                    <a href="profile.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
