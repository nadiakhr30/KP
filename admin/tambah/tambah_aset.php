<?php
session_start();
if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header("Location: ../../index.php");
    exit();
}
include("../../koneksi.php");

$error = "";
$success = "";

// Fetch jenis aset and users for selects
$jenisResult = mysqli_query($koneksi, "SELECT id_jenis_aset, nama_jenis_aset FROM jenis_aset ORDER BY nama_jenis_aset ASC");
$usersResult = mysqli_query($koneksi, "SELECT nip, nama FROM pegawai ORDER BY nama ASC");

// Preselect jenis from GET if provided (so add form matches selected jenis on aset.php)
$selectedJenisFromGet = isset($_GET['jenis']) ? (int)$_GET['jenis'] : 0;

// Fetch selected jenis name for display in header
$selectedJenisName = '';
if ($selectedJenisFromGet > 0) {
    $qSelectedJenis = mysqli_query($koneksi, "SELECT nama_jenis_aset FROM jenis_aset WHERE id_jenis_aset = $selectedJenisFromGet");
    if ($qSelectedJenis && mysqli_num_rows($qSelectedJenis) > 0) {
        $rowJenis = mysqli_fetch_assoc($qSelectedJenis);
        $selectedJenisName = $rowJenis['nama_jenis_aset'];
    }
}

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
            // After add, redirect back to aset list for that jenis if possible
            header("Refresh: 1; url=../aset.php?jenis=" . (int)$jenis_s);
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
    <link rel="icon" href="../../images/sikumbang.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="col-md-8 my-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tambah Aset <?= $selectedJenisName ? htmlspecialchars($selectedJenisName) : ""; ?></h5>
            </div>
            <div class="card-body px-5">
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
                            <!-- Hidden field for jenis aset (passed from URL) -->
                            <input type="hidden" name="id_jenis_aset" value="<?= htmlspecialchars($selectedJenisFromGet); ?>">

                            <!-- Row 1: Nama Aset + Link -->
                            <div class="row">
                                <div class="col-md-6">
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
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="link">Link</label>
                                        <input type="url" class="form-control" id="link" name="link" value="<?php echo isset($_POST['link']) ? htmlspecialchars($_POST['link']) : ''; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2: Keterangan (Full Width) -->
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"><?php echo isset($_POST["keterangan"]) ? htmlspecialchars($_POST["keterangan"]) : ''; ?></textarea>
                            </div>

                            <!-- Row 3: Penanggung Jawab (Full Width) -->
                            <div class="form-group">
                                <label for="nip">Penanggung Jawab</label>
                                <select id="nip" name="nip" class="form-control">
                                    <option value="">-- Pilih Penanggung Jawab --</option>
                                    <?php if ($usersResult): while ($u = mysqli_fetch_assoc($usersResult)): ?>
                                        <option value="<?= htmlspecialchars($u['nip']) ?>" <?= (isset($_POST['nip']) && $_POST['nip'] == $u['nip']) ? 'selected' : '' ?>><?= htmlspecialchars($u['nama']) ?> (<?= htmlspecialchars($u['nip']) ?>)</option>
                                    <?php endwhile; endif; ?>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="form-group mt-4 d-flex justify-content-between">
                                <a href="../aset.php?jenis=<?= $selectedJenisFromGet; ?>" class="btn btn-secondary btn-icon-l"><i class="fas fa-arrow-left"></i></a>
                                <button type="submit" class="btn btn-primary btn-icon-l"><i class="fas fa-save"></i></button>
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
