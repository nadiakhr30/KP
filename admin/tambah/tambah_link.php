<?php
include("../../koneksi.php");

$status  = '';
$message = '';
$previewData = [];
$fileUploaded = false;
$activeTab = 'input'; // Default to input tab

// Determine which tab should be active
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["excelFile"])) {
        $activeTab = 'import';
    } elseif (isset($_POST["submit_data"])) {
        $activeTab = 'import';
    } else {
        $activeTab = 'input';
    }
}

// proses simpan input manual
if (isset($_POST['simpan'])) {

    $nama_link = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['nama_link'])
    );

    $link_web = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['link'])
    );

    // validasi input
    if ($nama_link == '' || $link_web == '') {
        $status  = 'error';
        $message = 'Nama link dan URL wajib diisi!';
    } else {

        $gambar = '';

        // upload gambar (opsional)
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {

            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $namaFileBaru = uniqid() . '.' . $ext;

            move_uploaded_file(
                $_FILES['gambar']['tmp_name'],
                'assets/img/steps/' . $namaFileBaru
            );

            $gambar = $namaFileBaru;
        }

        // simpan ke database
        $simpan = mysqli_query($koneksi, "
            INSERT INTO link (nama_link, gambar, link)
            VALUES ('$nama_link', '$gambar', '$link_web')
        ");

        if ($simpan) {
            $status  = 'success';
            $message = 'Link berhasil ditambahkan!';
            header("Refresh: 1; url=../manajemen_link.php");
        } else {
            $status  = 'error';
            $message = 'Link gagal ditambahkan!';
        }
    }
}

// Handle file upload untuk import
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["excelFile"])) {
    $file = $_FILES["excelFile"];
    
    // Validate file
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $status = "error";
        $message = "Gagal upload file!";
    } else if ($file["size"] > 5 * 1024 * 1024) { // 5MB max
        $status = "error";
        $message = "Ukuran file terlalu besar (max 5MB)!";
    } else {
        $fileExt = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if (!in_array($fileExt, ['xlsx', 'xls'])) {
            $status = "error";
            $message = "Format file harus .xlsx atau .xls!";
        } else {
            // Process the file
            $inputFileName = $file["tmp_name"];
            require '../../vendor/autoload.php';
            
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                // Skip header row and validate data
                for ($i = 1; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    
                    // Check if row is empty
                    if (empty($row[0]) && empty($row[1])) {
                        continue;
                    }
                    
                    $previewData[] = [
                        'nama_link' => trim($row[0] ?? ''),
                        'link' => trim($row[1] ?? '')
                    ];
                }
                
                if (count($previewData) > 0) {
                    $fileUploaded = true;
                } else {
                    $status = "error";
                    $message = "File tidak memiliki data!";
                }
            } catch (Exception $e) {
                $status = "error";
                $message = "Gagal membaca file: " . $e->getMessage();
            }
        }
    }
}

// Handle data submission from preview
if (isset($_POST["submit_data"]) && !empty($_POST["preview_data"])) {
    $data = json_decode($_POST["preview_data"], true);
    
    if ($data && is_array($data)) {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        
        foreach ($data as $index => $link) {
            // Validate required fields
            if (empty($link['nama_link']) || empty($link['link'])) {
                $errorCount++;
                $errors[] = "Baris " . ($index + 2) . ": Nama Link dan URL wajib diisi!";
                continue;
            }
            
            // Insert into link table
            $insertLink = "INSERT INTO link (nama_link, link) 
                          VALUES (
                            '" . mysqli_real_escape_string($koneksi, $link['nama_link']) . "',
                            '" . mysqli_real_escape_string($koneksi, $link['link']) . "'
                          )";
            
            if (mysqli_query($koneksi, $insertLink)) {
                $successCount++;
            } else {
                $errorCount++;
                $errors[] = "Baris " . ($index + 2) . ": Gagal menambahkan link!";
            }
        }
        
        if ($successCount > 0) {
            $status = "success";
            $message = "Berhasil menambahkan $successCount link!";
            $previewData = [];
            $fileUploaded = false;
        }
        
        if ($errorCount > 0) {
            $status = "error";
            $message = "Gagal menambahkan $errorCount link. " . implode(" ", $errors);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Link</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .nav-tabs .nav-link {
            color: #495057;
            border: none;
            border-bottom: 3px solid transparent;
        }
        .nav-tabs .nav-link.active {
            color: #007bff;
            background-color: transparent;
            border-bottom: 3px solid #007bff;
        }
        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #007bff;
        }
        .dropzone {
            border: 2px dashed #007bff;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .dropzone:hover {
            background-color: #e9ecef;
            border-color: #0056b3;
        }
        .dropzone.dragover {
            background-color: #d1ecf1;
            border-color: #0c5460;
        }
        .dropzone i {
            font-size: 48px;
            color: #007bff;
            margin-bottom: 15px;
        }
        .preview-table {
            max-height: 400px;
            overflow-y: auto;
        }
        .upload-progress {
            display: none;
        }
        .progress-bar-animated {
            animation: progress-bar-stripes 1s linear infinite;
        }
        .template-info {
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>

<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Link Website</h5>
                </div>
                <div class="card-body">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs md-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $activeTab === 'input' ? 'active' : ''; ?>" id="input-tab" data-toggle="tab" href="#input-pane" role="tab">
                                <i class="fas fa-keyboard"></i> Input Manual
                            </a>
                            <div class="slide"></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $activeTab === 'import' ? 'active' : ''; ?>" id="import-tab" data-toggle="tab" href="#import-pane" role="tab">
                                <i class="fas fa-file-upload"></i> Import dari Excel
                            </a>
                            <div class="slide"></div>
                        </li>
                    </ul>
                    <?php if ($status == 'error'): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <?php if ($status == 'success'): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Input Manual Tab -->
                        <div class="tab-pane fade <?php echo $activeTab === 'input' ? 'show active' : ''; ?>" id="input-pane" role="tabpanel">
                            <form method="POST" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label>Upload Gambar (Opsional)</label>
                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <div id="gambarPreview" style="display: none; text-align: center; width: 120px; height: 120px; margin: 0 auto 15px;">
                                            <img id="previewImg" src="" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; display: block;">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input
                                            type="file"
                                            name="gambar"
                                            class="form-control"
                                            id="gambarInput"
                                            accept="image/*"
                                        >
                                        <small class="text-muted">
                                            Jika tidak upload, gambar akan dikosongkan
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Nama Instansi / Website <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="nama_link"
                                        class="form-control"
                                        required
                                        value="<?= isset($_POST['nama_link']) ? htmlspecialchars($_POST['nama_link']) : '' ?>"
                                    >
                                </div>

                                <div class="form-group">
                                    <label>Link Website <span class="text-danger">*</span></label>
                                    <input
                                        type="url"
                                        name="link"
                                        class="form-control"
                                        required
                                        value="<?= isset($_POST['link']) ? htmlspecialchars($_POST['link']) : '' ?>"
                                    >
                                </div>

                                <div class="form-group d-flex justify-content-between mt-4">
                                    <a href="../manajemen_link.php" class="btn btn-secondary">Batal</a>

                                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                                </div>

                            </form>
                        </div>

                        <!-- Import Tab -->
                        <div class="tab-pane fade <?php echo $activeTab === 'import' ? 'show active' : ''; ?>" id="import-pane" role="tabpanel">
                            <!-- Template Information -->
                            <div class="template-info">
                                <h6 class="mb-2"><i class="fas fa-info-circle"></i> Format Excel yang Dibutuhkan</h6>
                                <p class="mb-0">
                                    <strong>Kolom yang diperlukan:</strong><br>
                                    1. Nama Link (nama instansi/website)<br>
                                    2. Link (URL website)<br>
                                    <br>
                                    <strong>Catatan:</strong> Kedua kolom wajib diisi. Gambar dapat ditambahkan kemudian secara manual.
                                </p>
                                <p style="font-size: 12px; margin-top: 10px; margin-bottom: 0;">
                                    <a href="../download_template/download_template_link.php" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fa fa-download"></i> Download Template
                                    </a>
                                </p>
                            </div>

                            <?php if (!$fileUploaded): ?>
                            <!-- Upload Section -->
                            <form method="POST" enctype="multipart/form-data" id="uploadForm">
                                <div class="form-group">
                                    <label>Pilih File Excel</label>
                                    <div class="dropzone" id="dropzone">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <h5>Drag & Drop File di Sini</h5>
                                        <p>atau klik untuk memilih file</p>
                                        <small class="text-muted">Format: .xlsx, .xls (Max: 5MB)</small>
                                        <input type="file" id="fileInput" name="excelFile" accept=".xlsx,.xls" style="display: none;">
                                    </div>
                                </div>
                                <a href="../manajemen_link.php" class="btn btn-secondary">Batal</a>
                            </form>

                            <!-- Upload Progress Section -->
                            <div class="upload-progress" id="uploadProgress">
                                <h5 class="mb-3">Mengupload File...</h5>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" 
                                         role="progressbar" 
                                         aria-valuenow="100" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100" 
                                         style="width: 100%">
                                        Memproses...
                                    </div>
                                </div>
                            </div>

                            <?php else: ?>
                            <!-- Preview Section -->
                            <h5 class="mt-4 mb-3">Preview Data (<?php echo count($previewData); ?> Link)</h5>
                            <div class="preview-table">
                                <table class="table table-sm table-striped table-bordered">
                                    <thead class="sticky-top bg-light">
                                        <tr>
                                            <th style="width: 30px;">#</th>
                                            <th>Nama Link</th>
                                            <th>URL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($previewData as $index => $link): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($link['nama_link']); ?></td>
                                            <td>
                                                <a href="<?php echo htmlspecialchars($link['link']); ?>" target="_blank" class="text-truncate d-block" style="max-width: 200px;" title="<?php echo htmlspecialchars($link['link']); ?>">
                                                    <?php echo htmlspecialchars($link['link']); ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Submit Form -->
                            <form method="POST" class="mt-4">
                                <input type="hidden" name="preview_data" value="<?php echo htmlspecialchars(json_encode($previewData)); ?>">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="confirmCheckbox">
                                        <label class="custom-control-label" for="confirmCheckbox">
                                            Saya sudah memverifikasi data di atas dan siap untuk mengimport
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" name="submit_data" class="btn btn-success" id="submitBtn" disabled>
                                    <i class="fa fa-save"></i> Import Data
                                </button>
                                <a href="tambah_link.php" class="btn btn-secondary">Batal</a>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const uploadForm = document.getElementById('uploadForm');
        const uploadProgress = document.getElementById('uploadProgress');
        const confirmCheckbox = document.getElementById('confirmCheckbox');
        const submitBtn = document.getElementById('submitBtn');
        const gambarInput = document.getElementById('gambarInput');

        // Image preview for input tab
        if (gambarInput) {
            gambarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        document.getElementById('previewImg').src = event.target.result;
                        document.getElementById('gambarPreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    document.getElementById('gambarPreview').style.display = 'none';
                }
            });
        }

        // Handle upload section
        if (dropzone && fileInput && uploadForm) {
            // Drag and drop handlers
            dropzone.addEventListener('click', () => fileInput.click());

            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('dragover');
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('dragover');
            });

            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
                fileInput.files = e.dataTransfer.files;
                handleFileSelect();
            });

            fileInput.addEventListener('change', handleFileSelect);

            function handleFileSelect() {
                if (fileInput.files.length > 0) {
                    // Show loading progress
                    dropzone.style.display = 'none';
                    if (uploadProgress) uploadProgress.style.display = 'block';

                    // Submit form after short delay to ensure UI updates
                    setTimeout(() => {
                        uploadForm.submit();
                    }, 300);
                }
            }
        }

        // Handle preview section checkbox
        if (confirmCheckbox && submitBtn) {
            confirmCheckbox.addEventListener('change', function() {
                submitBtn.disabled = !this.checked;
            });
        }
    });
</script>

</body>
</html>
