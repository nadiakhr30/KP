<?php
include("../../koneksi.php");

$error = "";
$success = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $nama_jabatan = trim($_POST["nama_jabatan"] ?? "");
    
    // Validate input
    if (empty($nama_jabatan)) {
        $error = "Nama jabatan tidak boleh kosong!";
    } else {
        // Insert into database
        $query = "INSERT INTO jabatan (nama_jabatan) VALUES ('" . mysqli_real_escape_string($koneksi, $nama_jabatan) . "')";
        
        if (mysqli_query($koneksi, $query)) {
            $success = "Jabatan berhasil ditambahkan!";
            // Redirect after 1 second
            header("Refresh: 1; url=../manajemen_data_lainnya.php");
        } else {
            $error = "Gagal menambahkan jabatan: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jabatan</title>
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
                        <h5 class="mb-0">Tambah Jabatan</h5>
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
                            <div class="form-group">
                                <label for="nama_jabatan">Nama Jabatan <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="nama_jabatan" 
                                    name="nama_jabatan"
                                    placeholder="Masukkan nama jabatan"
                                    required
                                    maxlength="255"
                                    value="<?php echo isset($_POST["nama_jabatan"]) ? htmlspecialchars($_POST["nama_jabatan"]) : ''; ?>"
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
