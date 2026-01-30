<?php
ob_start();
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../../index.php');
    exit();
}

$error = '';
$success = '';
$nip = isset($_GET['nip']) ? mysqli_real_escape_string($koneksi, $_GET['nip']) : '';

// Get user data
$qUser = mysqli_query($koneksi, "
    SELECT u.*, j.nama_jabatan, r.nama_role, p.nama_ppid
    FROM pegawai u
    LEFT JOIN jabatan j ON u.id_jabatan = j.id_jabatan
    LEFT JOIN role r ON u.id_role = r.id_role
    LEFT JOIN ppid p ON u.id_ppid = p.id_ppid
    WHERE u.nip = '$nip'
");

if (mysqli_num_rows($qUser) == 0) {
    $error = "User tidak ditemukan!";
    header("Refresh: 2; url=../manajemen_user.php");
} else {
    $user = mysqli_fetch_assoc($qUser);

    // Get related data
    $qJabatan = mysqli_query($koneksi, "SELECT id_jabatan, nama_jabatan FROM jabatan ORDER BY nama_jabatan");
    $jabatans = [];
    while ($row = mysqli_fetch_assoc($qJabatan)) {
        $jabatans[] = $row;
    }

    $qRole = mysqli_query($koneksi, "SELECT id_role, nama_role FROM role ORDER BY nama_role");
    $roles = [];
    while ($row = mysqli_fetch_assoc($qRole)) {
        $roles[] = $row;
    }

    $qPPID = mysqli_query($koneksi, "SELECT id_ppid, nama_ppid FROM ppid ORDER BY nama_ppid");
    $ppids = [];
    while ($row = mysqli_fetch_assoc($qPPID)) {
        $ppids[] = $row;
    }

    $qHaloPST = mysqli_query($koneksi, "SELECT id_halo_pst, nama_halo_pst FROM halo_pst ORDER BY nama_halo_pst");
    $haloPSTs = [];
    while ($row = mysqli_fetch_assoc($qHaloPST)) {
        $haloPSTs[] = $row;
    }

    $qSkill = mysqli_query($koneksi, "SELECT id_skill, nama_skill FROM skill ORDER BY nama_skill");
    $skills = [];
    while ($row = mysqli_fetch_assoc($qSkill)) {
        $skills[] = $row;
    }

    $qUserHaloPST = mysqli_query($koneksi, "SELECT id_halo_pst FROM user_halo_pst WHERE nip = '" . mysqli_real_escape_string($koneksi, $nip) . "'");
    $userHaloPSTs = [];
    while ($row = mysqli_fetch_assoc($qUserHaloPST)) {
        $userHaloPSTs[] = $row['id_halo_pst'];
    }

    $qUserSkill = mysqli_query($koneksi, "SELECT id_skill FROM user_skill WHERE nip = '$nip'");
    $userSkills = [];
    while ($row = mysqli_fetch_assoc($qUserSkill)) {
        $userSkills[] = $row['id_skill'];
    }

    // Handle form submission
    if (isset($_POST['simpan'])) {
        $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
        $email = mysqli_real_escape_string($koneksi, trim($_POST['email']));
        $nomor_telepon = mysqli_real_escape_string($koneksi, trim($_POST['nomor_telepon'] ?? ''));
        $id_jabatan = (int)$_POST['id_jabatan'];
        $id_role = (int)$_POST['id_role'];
        $id_ppid = (int)$_POST['id_ppid'];
        $status = (int)$_POST['status'];

        // Get password input
        $password = trim($_POST['password'] ?? "");
        
        // Validation
        if ($nama == '' || $email == '') {
            $error = "Nama dan Email wajib diisi!";
        } elseif (empty($_POST['id_halo_pst'])) {
            $error = "Halo PST harus dipilih minimal satu!";
        } else {
            // Build update query
            if (!empty($password)) {
                // If password is provided, hash it and include in update
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $password_sql = ", password = '" . mysqli_real_escape_string($koneksi, $hashed_password) . "'";
            } else {
                // If password is empty, don't update password field
                $password_sql = "";
            }
            
            // Update user data
            $update = mysqli_query($koneksi, "
                UPDATE pegawai 
                SET nama = '$nama', 
                    email = '$email', 
                    nomor_telepon = " . (!empty($nomor_telepon) ? "'$nomor_telepon'" : "NULL") . ",
                    id_jabatan = $id_jabatan,
                    id_role = $id_role,
                    id_ppid = $id_ppid,
                    status = $status
                    $password_sql
                WHERE nip = '$nip'
            ");

            if ($update) {
                // Update Halo PST (required, multiple allowed)
                mysqli_query($koneksi, "DELETE FROM user_halo_pst WHERE nip = '" . mysqli_real_escape_string($koneksi, $nip) . "'");
                if (!empty($_POST['id_halo_pst']) && is_array($_POST['id_halo_pst'])) {
                    foreach ($_POST['id_halo_pst'] as $id_halo_pst) {
                        $id_halo_pst = (int)$id_halo_pst;
                        mysqli_query($koneksi, "INSERT INTO user_halo_pst (nip, id_halo_pst) VALUES ('" . mysqli_real_escape_string($koneksi, $nip) . "', $id_halo_pst)");
                    }
                }

                // Update Skills (optional, multiple allowed)
                mysqli_query($koneksi, "DELETE FROM user_skill WHERE nip = '" . mysqli_real_escape_string($koneksi, $nip) . "'");
                if (!empty($_POST['id_skill']) && is_array($_POST['id_skill'])) {
                    foreach ($_POST['id_skill'] as $id_skill) {
                        $id_skill = (int)$id_skill;
                        mysqli_query($koneksi, "INSERT INTO user_skill (nip, id_skill) VALUES ('" . mysqli_real_escape_string($koneksi, $nip) . "', $id_skill)");
                    }
                }

                $success = "Data user berhasil diperbarui!";
                header("Refresh: 2; url=../manajemen_user.php");
            } else {
                $error = "Gagal memperbarui data user! " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
        <div class="col-md-8 my-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit User</h5>
                </div>
                <div class="card-body px-5">
                    <?php if ($error) : ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <?php if ($success) : ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($user)) : ?>
                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-6 px-5">
                                <label>NIP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user['nip']); ?>" disabled>
                                <small class="text-muted">NIP tidak dapat diubah</small>
                            </div>
                            <div class="form-group col-md-6 px-5">
                                <label>Jabatan <span class="text-danger">*</span></label>
                                <select name="id_jabatan" class="form-control" required>
                                    <option value="">-- Pilih Jabatan --</option>
                                    <?php foreach ($jabatans as $jabatan) : ?>
                                        <option value="<?= $jabatan['id_jabatan']; ?>" <?= $user['id_jabatan'] == $jabatan['id_jabatan'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($jabatan['nama_jabatan']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 px-5">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($user['nama']); ?>">
                            </div>
                            <div class="form-group col-md-6 px-5">
                                <label>PPID</label>
                                <select name="id_ppid" class="form-control">
                                    <option value="">-- Pilih PPID --</option>
                                    <?php foreach ($ppids as $ppid) : ?>
                                        <option value="<?= $ppid['id_ppid']; ?>" <?= ($user['id_ppid'] == $ppid['id_ppid']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($ppid['nama_ppid']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 px-5">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']); ?>">
                            </div>
                            <div class="form-group col-md-6 px-5">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="1" <?= $user['status'] == 1 ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="0" <?= $user['status'] == 0 ? 'selected' : ''; ?>>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 px-5">
                                <label>Nomor Telepon</label>
                                <input type="number" name="nomor_telepon" class="form-control" value="<?= htmlspecialchars($user['nomor_telepon'] ?? ''); ?>">
                                <small class="text-muted">Tanpa 0 di depan, atau kosongkan jika tidak ada</small>
                            </div>
                            <div class="form-group col-md-6 px-5">
                                <label>Role <span class="text-danger">*</span></label>
                                <select name="id_role" class="form-control" required>
                                    <option value="">-- Pilih Role --</option>
                                    <?php foreach ($roles as $role) : ?>
                                        <option value="<?= $role['id_role']; ?>" <?= $user['id_role'] == $role['id_role'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($role['nama_role']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 px-5">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah">
                                <small class="text-muted">Kosongkan untuk mempertahankan password saat ini</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 px-5">
                                <label>Halo PST <span class="text-danger">*</span></label>
                                <div class="checkbox-group">
                                    <?php foreach ($haloPSTs as $haloPST) : ?>
                                        <input type="checkbox" class="hidden-checkbox halo-pst-checkbox" 
                                               id="haloPST<?= $haloPST['id_halo_pst']; ?>" 
                                               name="id_halo_pst[]" value="<?= $haloPST['id_halo_pst']; ?>"
                                               data-button="haloPSTBtn<?= $haloPST['id_halo_pst']; ?>"
                                               <?= in_array($haloPST['id_halo_pst'], $userHaloPSTs) ? 'checked' : ''; ?>>
                                        <button type="button" class="btn-checkbox halo-pst-btn <?= in_array($haloPST['id_halo_pst'], $userHaloPSTs) ? 'active' : ''; ?>" 
                                                id="haloPSTBtn<?= $haloPST['id_halo_pst']; ?>" 
                                                data-checkbox="haloPST<?= $haloPST['id_halo_pst']; ?>">
                                            <?= htmlspecialchars($haloPST['nama_halo_pst']); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="form-group col-md-6 px-5">
                                <label>Skills</label>
                                <div class="checkbox-group">
                                    <?php foreach ($skills as $skill) : ?>
                                        <input type="checkbox" class="hidden-checkbox skill-checkbox" 
                                               id="skill<?= $skill['id_skill']; ?>" 
                                               name="id_skill[]" value="<?= $skill['id_skill']; ?>"
                                               data-button="skillBtn<?= $skill['id_skill']; ?>"
                                               <?= in_array($skill['id_skill'], $userSkills) ? 'checked' : ''; ?>>
                                        <button type="button" class="btn-checkbox skill-btn <?= in_array($skill['id_skill'], $userSkills) ? 'active' : ''; ?>" 
                                                id="skillBtn<?= $skill['id_skill']; ?>" 
                                                data-checkbox="skill<?= $skill['id_skill']; ?>">
                                            <?= htmlspecialchars($skill['nama_skill']); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-between mt-4">
                            <a href="../manajemen_user.php" class="btn btn-secondary btn-icon-l">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                            <button type="submit" name="simpan" class="btn btn-primary btn-icon-l">
                                <i class="fa fa-save"></i>
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Halo PST button checkboxes
        document.querySelectorAll('.halo-pst-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const checkboxId = this.dataset.checkbox;
                const checkbox = document.getElementById(checkboxId);
                checkbox.checked = !checkbox.checked;
                this.classList.toggle('active');
            });
        });

        // Handle Skill button checkboxes
        document.querySelectorAll('.skill-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const checkboxId = this.dataset.checkbox;
                const checkbox = document.getElementById(checkboxId);
                checkbox.checked = !checkbox.checked;
                this.classList.toggle('active');
            });
        });
    });
</script>

</body>
</html>

