<?php
include("../../koneksi.php");
$error = "";
$success = "";
$previewData = [];
$fileUploaded = false;
// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["excelFile"])) {
    $file = $_FILES["excelFile"];
    // Validate file
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $error = "Gagal upload file!";
    } else if ($file["size"] > 5 * 1024 * 1024) { // 5MB max
        $error = "Ukuran file terlalu besar (max 5MB)!";
    } else {
        $fileExt = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if (!in_array($fileExt, ['xlsx', 'xls'])) {
            $error = "Format file harus .xlsx atau .xls!";
        } else {
            // Process the file
            require '../../vendor/autoload.php';
            $inputFileName = $file["tmp_name"];
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
                        'nip' => trim($row[0] ?? ''),
                        'nama' => trim($row[1] ?? ''),
                        'email' => trim($row[2] ?? ''),
                        'password' => trim($row[3] ?? ''),
                        'status' => trim($row[4] ?? '1'),
                        'nomor_telepon' => trim($row[5] ?? ''),
                        'id_jabatan' => trim($row[6] ?? ''),
                        'id_role' => trim($row[7] ?? ''),
                        'id_ppid' => trim($row[8] ?? ''),
                        'id_halo_pst' => trim($row[9] ?? ''),
                        'skills' => trim($row[10] ?? '')
                    ];
                }
                if (count($previewData) > 0) {
                    $fileUploaded = true;
                } else {
                    $error = "File tidak memiliki data!";
                }
            } catch (Exception $e) {
                $error = "Gagal membaca file: " . $e->getMessage();
            }
        }
    }
}
// Handle data submission
if (isset($_POST["submit_data"]) && !empty($_POST["preview_data"])) {
    $data = json_decode($_POST["preview_data"], true);
    if ($data && is_array($data)) {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        foreach ($data as $index => $user) {
            // Validate required fields
            if (empty($user['nip']) || empty($user['nama']) || empty($user['email']) || empty($user['password'])) {
                $errorCount++;
                $errors[] = "Baris " . ($index + 2) . ": NIP, Nama, Email, dan Password harus diisi!";
                continue;
            }
            // Check if email already exists
            $checkEmail = mysqli_query($koneksi, "SELECT email FROM user WHERE email = '" . mysqli_real_escape_string($koneksi, $user['email']) . "'");
            if (mysqli_num_rows($checkEmail) > 0) {
                $errorCount++;
                $errors[] = "Baris " . ($index + 2) . ": Email sudah terdaftar!";
                continue;
            }
            // Hash password
            $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
            // Insert into user table
            $insertUser = "INSERT INTO user (nip, nama, email, password, status, nomor_telepon, id_jabatan, id_role) 
                          VALUES (
                            '" . mysqli_real_escape_string($koneksi, $user['nip']) . "',
                            '" . mysqli_real_escape_string($koneksi, $user['nama']) . "',
                            '" . mysqli_real_escape_string($koneksi, $user['email']) . "',
                            '" . mysqli_real_escape_string($koneksi, $hashed_password) . "',
                            " . (int)$user['status'] . ",
                            " . (!empty($user['nomor_telepon']) ? "'" . mysqli_real_escape_string($koneksi, $user['nomor_telepon']) . "'" : "NULL") . ",
                            " . (int)$user['id_jabatan'] . ",
                            " . (int)$user['id_role'] . "
                          )";
            if (mysqli_query($koneksi, $insertUser)) {
                // Insert into user_ppid
                if (!empty($user['id_ppid'])) {
                    $insertPPID = "INSERT INTO user_ppid (id_ppid, nip) VALUES (" . (int)$user['id_ppid'] . ", '" . mysqli_real_escape_string($koneksi, $user['nip']) . "')";
                    mysqli_query($koneksi, $insertPPID);
                }
                // Insert into user_halo_pst
                if (!empty($user['id_halo_pst'])) {
                    $insertHaloPST = "INSERT INTO user_halo_pst (id_halo_pst, nip) VALUES (" . (int)$user['id_halo_pst'] . ", '" . mysqli_real_escape_string($koneksi, $user['nip']) . "')";
                    mysqli_query($koneksi, $insertHaloPST);
                }
                // Insert skills
                if (!empty($user['skills'])) {
                    $skillIds = array_filter(array_map('intval', explode(',', $user['skills'])));
                    foreach ($skillIds as $skillId) {
                        $insertSkill = "INSERT INTO user_skill (id_skill, nip) VALUES (" . $skillId . ", '" . mysqli_real_escape_string($koneksi, $user['nip']) . "')";
                        mysqli_query($koneksi, $insertSkill);
                    }
                }
                $successCount++;
            } else {
                $errorCount++;
                $errors[] = "Baris " . ($index + 2) . ": Gagal menambahkan user!";
            }
        }
        if ($successCount > 0) {
            $success = "Berhasil menambahkan $successCount user!";
            $previewData = [];
            $fileUploaded = false;
        }
        if ($errorCount > 0) {
            $error = "Gagal menambahkan $errorCount user. " . implode(" ", $errors);
        }
    }
}
// Get reference data for preview
$jabatan = [];
$qJabatan = mysqli_query($koneksi, "SELECT id_jabatan, nama_jabatan FROM jabatan");
while ($row = mysqli_fetch_assoc($qJabatan)) {
    $jabatan[$row['id_jabatan']] = $row['nama_jabatan'];
}
$role = [];
$qRole = mysqli_query($koneksi, "SELECT id_role, nama_role FROM role");
while ($row = mysqli_fetch_assoc($qRole)) {
    $role[$row['id_role']] = $row['nama_role'];
}
$ppid = [];
$qPPID = mysqli_query($koneksi, "SELECT id_ppid, nama_ppid FROM ppid");
while ($row = mysqli_fetch_assoc($qPPID)) {
    $ppid[$row['id_ppid']] = $row['nama_ppid'];
}
$haloPST = [];
$qHaloPST = mysqli_query($koneksi, "SELECT id_halo_pst, nama_halo_pst FROM halo_pst");
while ($row = mysqli_fetch_assoc($qHaloPST)) {
    $haloPST[$row['id_halo_pst']] = $row['nama_halo_pst'];
}
$skill = [];
$qSkill = mysqli_query($koneksi, "SELECT id_skill, nama_skill FROM skill");
while ($row = mysqli_fetch_assoc($qSkill)) {
    $skill[$row['id_skill']] = $row['nama_skill'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
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
        .template-info {
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .upload-progress {
            display: none;
        }
        .progress-bar-animated {
            animation: progress-bar-stripes 1s linear infinite;
        }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Import User dari Excel</h4>
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
                        <!-- Template Information -->
                        <div class="template-info">
                            <h6 class="mb-2"><i class="ti-info-alt"></i> Format Excel yang Dibutuhkan</h6>
                            <p>
                                <strong>Keterangan:</strong><br>
                                <div class="row no-border">
                                    <div class="col-12">NIP, Nama, Email, dan Password wajib diisi.</div>
                                </div>
                                <div class="row no-border">
                                    <div class="col-3">Status</div>
                                    <div class="col-9">1 = Aktif, 0 = Tidak Aktif</div>
                                </div>
                                <hr>
                                <div class="row no-border">
                                    <div class="col-3">Jabatan</div>
                                    <div class="col-9">
                                        <?php foreach ($jabatan as $id => $nama): ?>
                                            <div class="row">
                                                <div class="col-12"><?= $id; ?> = <?= htmlspecialchars($nama); ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row no-border">
                                    <div class="col-3">Role</div>
                                    <div class="col-9">
                                        <?php foreach ($role as $id => $nama): ?>
                                            <div class="row">
                                                <div class="col-12"><?= $id; ?> = <?= htmlspecialchars($nama); ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row no-border">
                                    <div class="col-3">PPID</div>
                                    <div class="col-9">
                                        <?php foreach ($ppid as $id => $nama): ?>
                                            <div class="row">
                                                <div class="col-12"><?= $id; ?> = <?= htmlspecialchars($nama); ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row no-border">
                                    <div class="col-3">Halo PST</div>
                                    <div class="col-9">
                                        <?php foreach ($haloPST as $id => $nama): ?>
                                            <div class="row">
                                                <div class="col-12"><?= $id; ?> = <?= htmlspecialchars($nama); ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row no-border">
                                    <div class="col-3">Skill</div>
                                    <div class="col-9">
                                        <?php foreach ($skill as $id => $nama): ?>
                                            <div class="row">
                                                <div class="col-12"><?= $id; ?> = <?= htmlspecialchars($nama); ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="row no-border">
                                    <div class="col-12">Nomor Telepon dan ID Skill bersifat opsional.</div>
                                </div>
                                <div class="row no-border">
                                    <div class="col-12">Untuk kolom ID Skill, masukkan beberapa ID yang dipisahkan dengan koma (misal: 1,3,5).</div>
                                </div>
                            </p>
                            <p style="font-size: 12px; margin-top: 10px;">
                                <a href="../download_template/download_template_user.php" class="btn btn-sm btn-outline-primary" target="_blank">
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
                                    <i class="fa fa-cloud-upload"></i>
                                    <h5>Drag & Drop File di Sini</h5>
                                    <p>atau klik untuk memilih file</p>
                                    <small class="text-muted">Format: .xlsx, .xls (Max: 5MB)</small>
                                    <input type="file" id="fileInput" name="excelFile" accept=".xlsx,.xls" style="display: none;">
                                </div>
                            </div>
                            <a href="../manajemen_user.php" class="btn btn-secondary">Batal</a>
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
                        <h5 class="mt-4 mb-3">Preview Data (<?php echo count($previewData); ?> User)</h5>
                        <div class="preview-table">
                            <table class="table table-sm table-striped table-bordered">
                                <thead class="sticky-top bg-light">
                                    <tr>
                                        <th style="width: 30px;">#</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Nomor Telepon</th>
                                        <th>Jabatan</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>PPID</th>
                                        <th>Halo PST</th>
                                        <th>Skills</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($previewData as $index => $user): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($user['nip']); ?></td>
                                        <td><?php echo htmlspecialchars($user['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><small class="text-muted">●●●●●●●●</small></td>
                                        <td><?php echo htmlspecialchars($user['nomor_telepon'] ?: '-'); ?></td>
                                        <td><?php echo htmlspecialchars($jabatan[$user['id_jabatan']] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($role[$user['id_role']] ?? '-'); ?></td>
                                        <td><?php echo $user['status'] == 1 ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>'; ?></td>
                                        <td><?php echo htmlspecialchars($ppid[$user['id_ppid']] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($haloPST[$user['id_halo_pst']] ?? '-'); ?></td>
                                        <td>
                                            <?php 
                                            if (!empty($user['skills'])) {
                                                $skillIds = array_filter(array_map('intval', explode(',', $user['skills'])));
                                                $skillNames = [];
                                                foreach ($skillIds as $skillId) {
                                                    if (isset($skill[$skillId])) {
                                                        $skillNames[] = htmlspecialchars($skill[$skillId]);
                                                    }
                                                }
                                                echo implode(', ', $skillNames) ?: '-';
                                            } else {
                                                echo '-';
                                            }
                                            ?>
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
                            <a href="tambah_user_import.php" class="btn btn-secondary">Batal</a>
                        </form>
                        <?php endif; ?>
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
            const uploadBtn = document.getElementById('uploadBtn');
            const uploadProgress = document.getElementById('uploadProgress');
            const confirmCheckbox = document.getElementById('confirmCheckbox');
            const submitBtn = document.getElementById('submitBtn');
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
                        if (uploadBtn) uploadBtn.style.display = 'none';
                        if (uploadProgress) uploadProgress.style.display = 'block';
                        if (uploadBtn) uploadBtn.disabled = true;
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