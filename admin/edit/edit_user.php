<?php
ob_start();
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../../index.php');
    exit();
}

$error = '';
$success = '';
$nip = isset($_GET['nip']) ? mysqli_real_escape_string($koneksi, $_GET['nip']) : '';

// Get user data
$qUser = mysqli_query($koneksi, "
    SELECT u.*, j.nama_jabatan, r.nama_role, p.nama_ppid
    FROM user u
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

        // Validation
        if ($nama == '' || $email == '') {
            $error = "Nama dan Email wajib diisi!";
        } elseif (empty($_POST['id_halo_pst'])) {
            $error = "Halo PST harus dipilih minimal satu!";
        } else {
            // Update user data
            $update = mysqli_query($koneksi, "
                UPDATE user 
                SET nama = '$nama', 
                    email = '$email', 
                    nomor_telepon = " . (!empty($nomor_telepon) ? "'$nomor_telepon'" : "NULL") . ",
                    id_jabatan = $id_jabatan,
                    id_role = $id_role,
                    id_ppid = $id_ppid,
                    status = $status
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>

<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit User</h5>
                </div>

                <div class="card-body">

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

                        <div class="form-group">
                            <label>NIP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['nip']); ?>" disabled>
                            <small class="text-muted">NIP tidak dapat diubah</small>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($user['nama']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" class="form-control" value="<?= htmlspecialchars($user['nomor_telepon'] ?? ''); ?>">
                            <small class="text-muted">Tanpa 0 di depan, atau kosongkan jika tidak ada</small>
                        </div>
                        <div class="form-group">
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
                        <div class="form-group">
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
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="1" <?= $user['status'] == 1 ? 'selected' : ''; ?>>Aktif</option>
                                <option value="0" <?= $user['status'] == 0 ? 'selected' : ''; ?>>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="form-group">
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
                        <div class="form-group">
                            <label>Halo PST</label>
                            <div class="border p-3" style="max-height: 200px; overflow-y: auto;">
                                <?php foreach ($haloPSTs as $haloPST) : ?>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="haloPST<?= $haloPST['id_halo_pst']; ?>" 
                                               name="id_halo_pst[]" value="<?= $haloPST['id_halo_pst']; ?>"
                                               <?= in_array($haloPST['id_halo_pst'], $userHaloPSTs) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="haloPST<?= $haloPST['id_halo_pst']; ?>">
                                            <?= htmlspecialchars($haloPST['nama_halo_pst']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Skills</label>
                            <div class="border p-3" style="max-height: 200px; overflow-y: auto;">
                                <?php foreach ($skills as $skill) : ?>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="skill<?= $skill['id_skill']; ?>" 
                                               name="id_skill[]" value="<?= $skill['id_skill']; ?>"
                                               <?= in_array($skill['id_skill'], $userSkills) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="skill<?= $skill['id_skill']; ?>">
                                            <?= htmlspecialchars($skill['nama_skill']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-between mt-4">
                            <a href="../manajemen_user.php" class="btn btn-secondary">
                                <i class="fa fa-times"></i> Batal
                            </a>

                            <button type="submit" name="simpan" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan Perubahan
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

</body>
</html>
