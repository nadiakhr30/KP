<?php
include("../../koneksi.php");

$error = "";
$success = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $nama_halo_pst = trim($_POST["nama_halo_pst"] ?? "");
    
    // Validate input
    if (empty($nama_halo_pst)) {
        $error = "Nama halo_pst tidak boleh kosong!";
    } else {
        // Insert into database
        $query = "INSERT INTO halo_pst (nama_halo_pst) VALUES ('" . mysqli_real_escape_string($koneksi, $nama_halo_pst) . "')";
        
        if (mysqli_query($koneksi, $query)) {
            $success = "Halo PST berhasil ditambahkan!";
            // Redirect after 1 second
            header("Refresh: 1; url=../manajemen_data_lainnya.php");
        } else {
            $error = "Gagal menambahkan halo_pst: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Halo PST</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Tambah Halo PST</h4>
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
                                <label for="nama_halo_pst">Nama Halo PST <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="nama_halo_pst" 
                                    name="nama_halo_pst"
                                    placeholder="Masukkan nama Halo PST"
                                    required
                                    maxlength="255"
                                    value="<?php echo isset($_POST["nama_halo_pst"]) ? htmlspecialchars($_POST["nama_halo_pst"]) : ''; ?>"
                                >
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
</body>
</html>