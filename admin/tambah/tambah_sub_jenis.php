<?php
include("../../koneksi.php");

$error   = "";
$success = "";

// ambil data jenis untuk dropdown
$dataJenis = mysqli_query(
    $koneksi,
    "SELECT id_jenis, nama_jenis FROM jenis ORDER BY nama_jenis"
);

// proses simpan
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama_sub_jenis = trim($_POST["nama_sub_jenis"] ?? "");
    $id_jenis       = trim($_POST["id_jenis"] ?? "");

    if ($nama_sub_jenis == "" || $id_jenis == "") {
        $error = "Nama Sub Jenis dan Jenis wajib diisi!";
    } else {

        $nama_sub_jenis = mysqli_real_escape_string($koneksi, $nama_sub_jenis);
        $id_jenis       = mysqli_real_escape_string($koneksi, $id_jenis);

        $query = "
            INSERT INTO sub_jenis (nama_sub_jenis, id_jenis)
            VALUES ('$nama_sub_jenis', '$id_jenis')
        ";

        if (mysqli_query($koneksi, $query)) {
            $success = "Sub Jenis berhasil ditambahkan!";
            header("Refresh: 1; url=../index.php");
        } else {
            $error = "Gagal menambahkan Sub Jenis: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Sub Jenis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>

<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tambah Sub Jenis</h4>
                </div>

                <div class="card-body">

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
                            <label>Jenis <span class="text-danger">*</span></label>
                            <select name="id_jenis" class="form-control" required>
                                <option value="">-- Pilih Jenis --</option>
                                <?php while ($j = mysqli_fetch_assoc($dataJenis)) : ?>
                                    <option value="<?= $j['id_jenis']; ?>"
                                        <?= (isset($_POST['id_jenis']) && $_POST['id_jenis'] == $j['id_jenis']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($j['nama_jenis']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nama Sub Jenis <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="nama_sub_jenis"
                                class="form-control"
                                placeholder="Masukkan nama sub jenis"
                                required
                                maxlength="255"
                                value="<?= isset($_POST['nama_sub_jenis']) ? htmlspecialchars($_POST['nama_sub_jenis']) : '' ?>"
                            >
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary mr-2">
                                Simpan
                            </button>
                            <a href="../index.php" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
