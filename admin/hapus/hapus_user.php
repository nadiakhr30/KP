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
$qUser = mysqli_query($koneksi, "SELECT * FROM user WHERE nip = '$nip'");

if (mysqli_num_rows($qUser) == 0) {
    $error = "User tidak ditemukan!";
    header("Refresh: 2; url=../manajemen_user.php");
    exit();
}

$user = mysqli_fetch_assoc($qUser);

// Handle deletion
if (isset($_POST['konfirmasi_hapus'])) {
    // Delete user from all related tables
    mysqli_query($koneksi, "DELETE FROM user_skill WHERE nip = '$nip'");
    mysqli_query($koneksi, "DELETE FROM user_ppid WHERE nip = '$nip'");
    mysqli_query($koneksi, "DELETE FROM user_halo_pst WHERE nip = '$nip'");
    
    // Delete user
    $delete = mysqli_query($koneksi, "DELETE FROM user WHERE nip = '$nip'");

    if ($delete) {
        $success = "User berhasil dihapus!";
        header("Refresh: 2; url=../manajemen_user.php");
    } else {
        $error = "Gagal menghapus user! " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>

<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Konfirmasi Hapus User</h5>
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

                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> Peringatan</h6>
                        <p>Anda akan menghapus user berikut. Tindakan ini <strong>TIDAK DAPAT DIBATALKAN</strong>!</p>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <?php if ($user['foto_profil']) : ?>
                                        <img src="../../uploads/<?= htmlspecialchars($user['foto_profil']); ?>" width="80" style="border-radius: 50%; object-fit: cover;">
                                    <?php else : ?>
                                        <img src="../../images/noimages.jpg" width="80" style="border-radius: 50%; object-fit: cover;">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-8">
                                    <p><strong>NIP:</strong> <?= htmlspecialchars($user['nip']); ?></p>
                                    <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama']); ?></p>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                                    <p><strong>Status:</strong> <?= $user['status'] == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="konfirmasi" required>
                                <label class="custom-control-label" for="konfirmasi">
                                    Saya memahami bahwa data user akan dihapus secara permanen dan tidak dapat dikembalikan
                                </label>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-between">
                            <a href="../manajemen_user.php" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>

                            <button type="submit" name="konfirmasi_hapus" class="btn btn-danger" id="deleteBtn" disabled>
                                <i class="fa fa-trash"></i> Ya, Hapus User
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('konfirmasi').addEventListener('change', function() {
        document.getElementById('deleteBtn').disabled = !this.checked;
    });
</script>

</body>
</html>
