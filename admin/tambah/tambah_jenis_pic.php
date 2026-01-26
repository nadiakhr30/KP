<?php
include("../../koneksi.php");

$error = "";
$success = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $jenis = trim($_POST["nama_jenis_pic"] ?? "");
    
    // Validate input
    if (empty($jenis)) {
        $error = "Jenis PIC tidak boleh kosong!";
    } else {
        // Insert into database
        $query = "INSERT INTO jenis_pic (nama_jenis_pic) VALUES ('" . mysqli_real_escape_string($koneksi, $jenis) . "')";
        
        if (mysqli_query($koneksi, $query)) {
            $success = "Jenis PIC berhasil ditambahkan!";
            // Redirect after 1 second
            header("Refresh: 1; url=../manajemen_data_lainnya.php");
        } else {
            $error = "Gagal menambahkan Jenis: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jenis PIC</title>
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
                <h4 class="mb-0">Tambah Jenis PIC</h4>
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
                        <label for="jenis">Nama Jenis PIC <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="nama_jenis_pic" 
                            name="nama_jenis_pic"
                            placeholder="Masukkan nama jenis PIC"
                            required
                            maxlength="255"
                            value="<?php echo isset($_POST["nama_jenis_pic"]) ? htmlspecialchars($_POST["nama_jenis_pic"]) : ''; ?>"
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