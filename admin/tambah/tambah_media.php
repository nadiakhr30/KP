<?php
include("../../koneksi.php");

$error = "";
$success = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $judul = trim($_POST["judul"] ?? "");
    $topik = trim($_POST["topik"] ?? "");
    $deskripsi = trim($_POST["deskripsi"] ?? "");
    $link = trim($_POST["link"] ?? "");
    $id_sub_jenis = isset($_POST["id_sub_jenis"]) ? (int)$_POST["id_sub_jenis"] : "";
    
    // Validate required input
    if (empty($judul) || empty($topik) || empty($deskripsi) || empty($link) || empty($id_sub_jenis)) {
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
            $success = "Media berhasil ditambahkan!";
            // Redirect after 1 second
            header("Refresh: 1; url=../index.php");
        } else {
            $error = "Gagal menambahkan media: " . mysqli_error($koneksi);
        }
    }
}

// Get sub_jenis data
$sub_jenis_query = "SELECT sub_jenis.id_sub_jenis, sub_jenis.nama_sub_jenis, jenis.nama_jenis 
                    FROM sub_jenis 
                    JOIN jenis ON sub_jenis.id_jenis = jenis.id_jenis 
                    ORDER BY jenis.nama_jenis, sub_jenis.nama_sub_jenis";
$sub_jenis_result = mysqli_query($koneksi, $sub_jenis_query);
$sub_jenis_data = [];

if ($sub_jenis_result) {
    while ($row = mysqli_fetch_assoc($sub_jenis_result)) {
        $sub_jenis_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Media</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px 12px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .text-danger {
            color: #dc3545;
        }
        .alert {
            border-radius: 4px;
            padding: 12px 20px;
        }
        .btn {
            border-radius: 4px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            color: white;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: space-between;
            margin-top: 30px;
        }
    </style>
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tambah Media</h5>
                </div>
            <div class="card-body">
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
                            <label for="id_sub_jenis">Sub Jenis <span class="text-danger">*</span></label>
                            <select class="form-control" id="id_sub_jenis" name="id_sub_jenis" required>
                                <option value="">-- Pilih Sub Jenis --</option>
                                <?php 
                                $current_jenis = '';
                                foreach ($sub_jenis_data as $sj): 
                                    if ($current_jenis != $sj['nama_jenis']) {
                                        if ($current_jenis != '') echo '</optgroup>';
                                        echo '<optgroup label="' . htmlspecialchars($sj['nama_jenis']) . '">';
                                        $current_jenis = $sj['nama_jenis'];
                                    }
                                ?>
                                    <option value="<?php echo $sj['id_sub_jenis']; ?>" <?php echo isset($_POST["id_sub_jenis"]) && $_POST["id_sub_jenis"] == $sj['id_sub_jenis'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($sj['nama_sub_jenis']); ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php if ($current_jenis != '') echo '</optgroup>'; ?>
                            </select>
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

                    <div class="form-group">
                        <label for="link">Link <span class="text-danger">*</span></label>
                        <input 
                            type="url" 
                            class="form-control" 
                            id="link" 
                            name="link"
                            required
                            maxlength="255"
                            value="<?php echo isset($_POST["link"]) ? htmlspecialchars($_POST["link"]) : ''; ?>"
                        >
                    </div>

                    <div class="button-group">
                        <a href="../index.php" class="btn btn-secondary">
                            <i class="fas fa-times-circle"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Media
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
