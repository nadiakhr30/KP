<?php
session_start();
if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header("Location: ../../index.php");
    exit();
}
include("../../koneksi.php");

$error   = "";
$success = "";

// ambil data kategori untuk dropdown
$dataKategori = mysqli_query(
    $koneksi,
    "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori"
);

// proses simpan
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama_jenis = trim($_POST["nama_jenis"] ?? "");
    $id_kategori       = trim($_POST["id_kategori"] ?? "");

    if ($nama_jenis == "" || $id_kategori == "") {
        $error = "Nama Jenis dan Kategori wajib diisi!";
    } else {

        $nama_jenis = mysqli_real_escape_string($koneksi, $nama_jenis);
        $id_kategori       = mysqli_real_escape_string($koneksi, $id_kategori);

        $query = "
            INSERT INTO jenis (nama_jenis, id_kategori)
            VALUES ('$nama_jenis', '$id_kategori')
        ";

        if (mysqli_query($koneksi, $query)) {
            $success = "Jenis berhasil ditambahkan!";
            header("Refresh: 1; url=../manajemen_data_lainnya.php");
        } else {
            $error = "Gagal menambahkan Jenis: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Jenis</title>
    <link rel="icon" href="../../images/sikumbang.ico" type="image/x-icon">
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
        <div class="col-md-4 my-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tambah Jenis</h5>
                </div>
                <div class="card-body px-5">
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="form-group">
                            <label>Kategori <span class="text-danger">*</span></label>
                            <select name="id_kategori" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php while ($k = mysqli_fetch_assoc($dataKategori)) : ?>
                                    <option value="<?= $k['id_kategori']; ?>"
                                        <?= (isset($_POST['id_kategori']) && $_POST['id_kategori'] == $k['id_kategori']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($k['nama_kategori']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Jenis <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="nama_jenis"
                                class="form-control"
                                placeholder="Masukkan nama jenis"
                                value="<?= isset($_POST['nama_jenis']) ? htmlspecialchars($_POST['nama_jenis']) : '' ?>"
                                required
                            >
                        </div>
                        <div class="form-group mt-4 d-flex justify-content-between">
                            <a href="../manajemen_data_lainnya.php" class="btn btn-secondary btn-icon-l"><i class="fas fa-arrow-left"></i></a>
                            <button type="submit" class="btn btn-primary btn-icon-l"><i class="fas fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>