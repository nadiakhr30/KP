<?php
include("../../koneksi.php");

$error = "";
$success = "";

// Get sub_jenis from URL parameter
$id_sub_jenis = isset($_GET["sub"]) ? (int)$_GET["sub"] : 0;

if ($id_sub_jenis <= 0) {
    $error = "Parameter sub jenis tidak valid!";
}

// determine nama jenis for redirects
$namaJenis = 'media';
if ($id_sub_jenis > 0) {
    $qSub = mysqli_query($koneksi, "SELECT s.nama_sub_jenis, j.nama_jenis FROM sub_jenis s JOIN jenis j ON s.id_jenis = j.id_jenis WHERE s.id_sub_jenis = $id_sub_jenis");
    if ($qSub && mysqli_num_rows($qSub) > 0) {
        $rSub = mysqli_fetch_assoc($qSub);
        $namaJenis = strtolower(str_replace(' ', '_', $rSub['nama_jenis']));
    }
}

// Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $judul = trim($_POST["judul"] ?? "");
    $topik = trim($_POST["topik"] ?? "");
    $deskripsi = trim($_POST["deskripsi"] ?? "");
    $link = trim($_POST["link"] ?? "");
    $id_sub_jenis = isset($_POST["id_sub_jenis"]) ? (int)$_POST["id_sub_jenis"] : 0;
    
    // Validate required input
        if (empty($judul) || empty($topik) || empty($deskripsi) || empty($link) || $id_sub_jenis <= 0) {
            $error = "Semua field harus diisi!";
        } else {
            // Insert into database
            $query = "INSERT INTO media (judul, topik, deskripsi, link, id_sub_jenis) 
                      VALUES (
                        '" . mysqli_real_escape_string($koneksi, $judul) . "',
                        '" . mysqli_real_escape_string($koneksi, $topik) . "',
                        '" . mysqli_real_escape_string($koneksi, $deskripsi) . "',
                        '" . mysqli_real_escape_string($koneksi, $link) . "',
                        " . $id_sub_jenis . "
                      )";

            if (mysqli_query($koneksi, $query)) {
                $success = 'Media berhasil ditambahkan';
                // Redirect after 1 second to current jenis page
                header("Refresh: 1; url=../" . $namaJenis . ".php?sub=" . $id_sub_jenis);
                exit();
            } else {
                $error = "Gagal menambahkan media: " . mysqli_error($koneksi);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Media</title>
    <link rel="icon" href="../assets/images/logo_bps.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <style>
    </style>
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tambah Media</h5>
                </div>
            <div class="card-body px-5">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="judul">Judul <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="judul" 
                                name="judul"
                                required
                                maxlength="255"
                                value="<?php echo isset($_POST["judul"]) ? htmlspecialchars($_POST["judul"]) : ''; ?>"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="topik">Topik <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="topik" 
                                name="topik"
                                required
                                maxlength="255"
                                value="<?php echo isset($_POST["topik"]) ? htmlspecialchars($_POST["topik"]) : ''; ?>"
                            >
                        </div>

                        <div class="form-group col-md-6">
                            <label for="link">Link <span class="text-danger">*</span></label>
                            <input 
                                type="text"
                                class="form-control" 
                                id="link" 
                                name="link"
                                required
                                value="<?php echo isset($_POST["link"]) ? htmlspecialchars($_POST["link"]) : ''; ?>"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                        <textarea 
                            class="form-control" 
                            id="deskripsi" 
                            name="deskripsi"
                            required
                        ><?php echo isset($_POST["deskripsi"]) ? htmlspecialchars($_POST["deskripsi"]) : ''; ?></textarea>
                    </div>

                    <!-- Hidden field for sub_jenis -->
                    <input type="hidden" name="id_sub_jenis" value="<?php echo htmlspecialchars($id_sub_jenis); ?>">

                    <!-- Action Buttons -->
                    <div class="form-group mt-4 d-flex justify-content-between">
                        <a href="../<?php echo htmlspecialchars($namaJenis); ?>.php?sub=<?php echo htmlspecialchars($id_sub_jenis); ?>" class="btn btn-secondary btn-icon-l"><i class="fas fa-arrow-left"></i></a>
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

