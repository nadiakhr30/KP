<?php
include("../../koneksi.php");

$error = "";
$success = "";

// Fetch jenis aset and users for selects
$jenisResult = mysqli_query($koneksi, "SELECT id_jenis_aset, nama_jenis_aset FROM jenis_aset ORDER BY nama_jenis_aset ASC");
$usersResult = mysqli_query($koneksi, "SELECT nip, nama FROM pegawai ORDER BY nama ASC");

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $nama = trim($_POST["nama"] ?? "");
    $keterangan = trim($_POST["keterangan"] ?? "");
    $id_jenis_aset = trim($_POST["id_jenis_aset"] ?? "");
    $link = trim($_POST["link"] ?? "");
    $nip = trim($_POST["nip"] ?? "");

    if (empty($nama)) {
        $error = "Nama aset tidak boleh kosong!";
    } elseif (empty($id_jenis_aset)) {
        $error = "Jenis aset harus dipilih!";
    } else {
        $nama_s = mysqli_real_escape_string($koneksi, $nama);
        $keterangan_s = mysqli_real_escape_string($koneksi, $keterangan);
        $jenis_s = mysqli_real_escape_string($koneksi, $id_jenis_aset);
        $link_s = mysqli_real_escape_string($koneksi, $link);
        $nip_s = mysqli_real_escape_string($koneksi, $nip);

        $query = "INSERT INTO aset (nama, keterangan, id_jenis_aset, link, nip) VALUES ('{$nama_s}', '{$keterangan_s}', '{$jenis_s}', '{$link_s}', " . (empty($nip_s) ? "NULL" : "'{$nip_s}'") . ")";

        if (mysqli_query($koneksi, $query)) {
            $success = "Aset berhasil ditambahkan!";
            header("Refresh: 1; url=../manajemen_data_lainnya.php");
        } else {
            $error = "Gagal menambahkan aset: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Aset</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h4 class="mb-0">Tambah Aset</h4>
                                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($success); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="nama">Nama Aset <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="nama" 
                                    name="nama"
                                    required
                                    maxlength="255"
                                    value="<?php echo isset($_POST["nama"]) ? htmlspecialchars($_POST["nama"]) : ''; ?>"
                                >
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"><?php echo isset($_POST["keterangan"]) ? htmlspecialchars($_POST["keterangan"]) : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="id_jenis_aset">Jenis Aset <span class="text-danger">*</span></label>
                                <select id="id_jenis_aset" name="id_jenis_aset" class="form-control" required>
                                    <option value="">-- Pilih Jenis Aset --</option>
                                    <?php if ($jenisResult): while ($row = mysqli_fetch_assoc($jenisResult)): ?>
                                        <option value="<?= $row['id_jenis_aset'] ?>" <?= (isset($_POST['id_jenis_aset']) && $_POST['id_jenis_aset'] == $row['id_jenis_aset']) ? 'selected' : '' ?>><?= htmlspecialchars($row['nama_jenis_aset']) ?></option>
                                    <?php endwhile; endif; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="link">Link</label>
                                <input type="url" class="form-control" id="link" name="link"  value="<?php echo isset($_POST['link']) ? htmlspecialchars($_POST['link']) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="nip">Penanggung Jawab</label>
                                <select id="nip" name="nip" class="form-control">
                                    <option value="">-- Pilih Penanggung Jawab --</option>
                                    <?php if ($usersResult): while ($u = mysqli_fetch_assoc($usersResult)): ?>
                                        <option value="<?= htmlspecialchars($u['nip']) ?>" <?= (isset($_POST['nip']) && $_POST['nip'] == $u['nip']) ? 'selected' : '' ?>><?= htmlspecialchars($u['nama']) ?> (<?= htmlspecialchars($u['nip']) ?>)</option>
                                    <?php endwhile; endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary mr-2">
                                    Simpan
                                </button>
                                <a href="../manajemen_data_lainnya.php" class="btn btn-secondary">
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
