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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
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
            color: #009cfd 0%;
        }
        
        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            color: #009cfd 0%;
            font-size: 14px;
        }
        
        .breadcrumb-custom a {
            color: #009cfd 0%;
            text-decoration: none;
            transition: 0.25s;
        }
        
        .breadcrumb-custom a:hover {
            text-decoration: underline;
        }
        .breadcrumb-link {
            color: #009cfd;
            text-decoration: none;
        }

        .breadcrumb-link i {
            font-size: 16px;
        }

        .breadcrumb-separator {
            color: #b0b0b0;
        }

        .breadcrumb-active {
            color: #009cfd;
            font-weight: 600;
        }
        .profile-card {
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
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .profile-img img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #009cfd 0%;
        }
        
        .profile-img i {
            font-size: 120px;
            color: #ddd;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .info-table tr {
            border-bottom: 1px solid #e0e0e0;
        }
        
        .info-table th {
            text-align: left;
            padding: 15px 0;
            width: 30%;
            font-weight: 600;
            color: #666;
        }
        
        .info-table td {
            padding: 15px 0;
            color: #333;
        }
        
        .skill-section {
            margin-top: 30px;
        }
        
        .skill-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
            font-size: 15px;
        }
        
        .skill-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .skill-badge {
            background: #009cfd 0%;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .skill-empty {
            color: #999;
            font-style: italic;
        }
        
        .btn-edit {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 44px;
            height: 44px;
            border: none;
            background: #009cfd 0%;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: 0.25s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
            background: #0f4382;
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
            <span class="breadcrumb-active">Profil</span>
        </div>

        <div class="profile-card">
            <!-- BUTTON EDIT -->
            <a href="editprofil.php" class="btn-edit" title="Edit Profil">
                <i class="bi bi-pencil-fill"></i>
            </a>

            <div class="card-title">
                <i class="bi bi-person-circle"></i>
                Informasi Pengguna
            </div>

            <div class="profile-img">
                <?php
                $foto = trim($data['foto_profil'] ?? '');
                $path = __DIR__ . '/../uploads/' . $foto;

                if ($foto && file_exists($path)) {
                    echo '<img src="../uploads/'.htmlspecialchars($foto).'" alt="Foto Profil">';
                } else {
                    echo '<i class="bi bi-person-circle"></i>';
                }
                ?>
            </div>

            <table class="info-table">
                <tr>
                    <th>Nama</th>
                    <td><?= htmlspecialchars($data['nama']) ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= htmlspecialchars($data['email']) ?></td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td>
                        <?php
                        $roles = [1 => 'Administrasi', 2 => 'Pegawai', 3 => 'Admin'];
                        echo $roles[$data['id_role']] ?? 'Tidak Diketahui';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <?php if ($data['status'] == 1): ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Non-Aktif</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>NIP</th>
                    <td><?= htmlspecialchars($data['nip']) ?></td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td><?= htmlspecialchars($data['nama_jabatan'] ?? '-') ?></td>
                </tr>
                <tr>
                    <th>Nomor Telepon</th>
                    <td>0<?= htmlspecialchars($data['nomor_telepon'] ?? '-') ?></td>
                </tr>
            </table>

            <div class="skill-section">
                <div class="skill-title">
                    <i class="bi bi-star-fill"></i> Skill
                </div>
                <div class="skill-list">
                    <?php if (!empty($skills)): ?>
                        <?php foreach ($skills as $skill): ?>
                            <span class="skill-badge"><?= htmlspecialchars($skill) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="skill-empty">Tidak ada skill</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="skill-section">
                <div class="skill-title">
                    <i class="bi bi-shield-check"></i> PPID
                </div>
                <div class="skill-list">
                    <?php if (!empty($ppids)): ?>
                        <?php foreach ($ppids as $p): ?>
                            <span class="skill-badge"><?= htmlspecialchars($p) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="skill-empty">Tidak ada PPID</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="skill-section">
                <div class="skill-title">
                    <i class="bi bi-chat-left-text"></i> Halo PST
                </div>
                <div class="skill-list">
                    <?php if (!empty($halos)): ?>
                        <?php foreach ($halos as $h): ?>
                            <span class="skill-badge"><?= htmlspecialchars($h) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="skill-empty">Tidak ada Halo PST</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
