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
// Get pegawai data
$qPegawai = mysqli_query($koneksi, "SELECT * FROM pegawai WHERE nip = '$nip'");
if (mysqli_num_rows($qPegawai) == 0) {
    $error = "Pegawai tidak ditemukan!";
    header("Refresh: 2; url=../manajemen_user.php");
    exit();
}
$pegawai = mysqli_fetch_assoc($qPegawai);
// Handle deletion
$deleteSuccess = false;
$deleteError = '';
if (isset($_POST['konfirmasi_hapus'])) {
    // Delete pegawai from all related tables
    mysqli_query($koneksi, "DELETE FROM user_skill WHERE nip = '$nip'");
    mysqli_query($koneksi, "DELETE FROM user_halo_pst WHERE nip = '$nip'");
    // Delete user
    $delete = mysqli_query($koneksi, "DELETE FROM pegawai WHERE nip = '$nip'");
    if ($delete) {
        $deleteSuccess = true;
    } else {
        $deleteError = "Gagal menghapus user! " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus User</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;>
        <div class="col-md-8 my-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Konfirmasi Hapus User</h5>
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
                    <div class="alert alert-danger">
                        <h6 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> Peringatan</h6>
                        <p>Anda akan menghapus pegawai berikut. Tindakan ini <strong>TIDAK DAPAT DIBATALKAN</strong>!</p>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <?php if ($pegawai['foto_profil']) : ?>
                                        <img src="../../uploads/<?= htmlspecialchars($pegawai['foto_profil']); ?>" width="80" style="border-radius: 50%; object-fit: cover;">
                                    <?php else : ?>
                                        <img src="../../images/noimages.jpg" width="80" style="border-radius: 50%; object-fit: cover;">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-8">
                                    <p><strong>NIP:</strong> <?= htmlspecialchars($pegawai['nip']); ?></p>
                                    <p><strong>Nama:</strong> <?= htmlspecialchars($pegawai['nama']); ?></p>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($pegawai['email']); ?></p>
                                    <p><strong>Status:</strong> <?= $pegawai['status'] == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="POST">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="konfirmasi" required>
                                <label class="custom-control-label" for="konfirmasi">
                                    Saya memahami bahwa data pegawai akan dihapus secara permanen dan tidak dapat dikembalikan
                                </label>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-between">
                            <a href="../manajemen_user.php" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('konfirmasi').addEventListener('change', function() {
        document.getElementById('deleteBtn').disabled = !this.checked;
    });

    <?php if ($deleteSuccess): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Pegawai berhasil dihapus!',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    }).then((result) => {
        window.location.href = '../manajemen_user.php';
    });
    <?php elseif ($deleteError): ?>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '<?= addslashes($deleteError) ?>',
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    });
    <?php endif; ?>
</script>

</body>
</html>

