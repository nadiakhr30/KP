<?php
include("../../koneksi.php");

$error = "";
$success = "";
$previewData = [];
$fileUploaded = false;
$activeTab = 'input'; // Default to input tab

// Determine which tab should be active
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["excelFile"])) {
        $activeTab = 'import';
    } else {
        $activeTab = 'input';
    }
}

// ==================== INPUT MANUAL SECTION ====================
// Process form submission (manual input)
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_FILES["excelFile"]) && !isset($_POST["submit_data"])) {
    // Sanitize and validate input
    $nip = trim($_POST["nip"] ?? "");
    $nama = trim($_POST["nama"] ?? "");
    $password = $_POST["password"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $status = isset($_POST["status"]) ? (int)$_POST["status"] : "";
    $nomor_telepon = trim($_POST["nomor_telepon"] ?? "");
    $id_jabatan = isset($_POST["id_jabatan"]) ? (int)$_POST["id_jabatan"] : "";
    $id_role = isset($_POST["id_role"]) ? (int)$_POST["id_role"] : "";
    $id_ppid = isset($_POST["id_ppid"]) ? (int)$_POST["id_ppid"] : "";
    $id_halo_pst = isset($_POST["id_halo_pst"]) ? (int)$_POST["id_halo_pst"] : "";
    $skills_str = trim($_POST["skills"] ?? "");
    $skills = !empty($skills_str) ? array_map('intval', explode(',', $skills_str)) : [];
    
    // Validate required input
    if (empty($nip) || empty($nama) || empty($password) || empty($email) || empty($status) || empty($id_jabatan) || empty($id_role) || empty($id_ppid) || empty($id_halo_pst)) {
        $error = "Semua field harus diisi!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else if (!empty($nomor_telepon) && !is_numeric($nomor_telepon)) {
        $error = "Nomor telepon harus berupa angka!";
    } else {
        // Check if email already exists
        $check_email_query = "SELECT email FROM user WHERE email = '" . mysqli_real_escape_string($koneksi, $email) . "'";
        $check_email_result = mysqli_query($koneksi, $check_email_query);
        
        if (mysqli_num_rows($check_email_result) > 0) {
            $error = "Email sudah ada.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert into database
            $query = "INSERT INTO user (nip, nama, password, email, status, nomor_telepon, id_jabatan, id_role) 
                      VALUES (
                        '" . mysqli_real_escape_string($koneksi, $nip) . "',
                        '" . mysqli_real_escape_string($koneksi, $nama) . "',
                        '" . mysqli_real_escape_string($koneksi, $hashed_password) . "',
                        '" . mysqli_real_escape_string($koneksi, $email) . "',
                        " . $status . ",
                        " . (!empty($nomor_telepon) ? "'" . mysqli_real_escape_string($koneksi, $nomor_telepon) . "'" : "NULL") . ",
                        " . $id_jabatan . ",
                        " . $id_role . "
                      )";
            
            if (mysqli_query($koneksi, $query)) {
                // Insert into user_ppid
                $insert_ppid_query = "INSERT INTO user_ppid (id_ppid, nip) VALUES (" . $id_ppid . ", '" . mysqli_real_escape_string($koneksi, $nip) . "')";
                
                if (!mysqli_query($koneksi, $insert_ppid_query)) {
                    $error = "Gagal menambahkan data PPID: " . mysqli_error($koneksi);
                } else {
                    // Insert into user_halo_pst
                    $insert_halo_pst_query = "INSERT INTO user_halo_pst (id_halo_pst, nip) VALUES (" . $id_halo_pst . ", '" . mysqli_real_escape_string($koneksi, $nip) . "')";
                    
                    if (!mysqli_query($koneksi, $insert_halo_pst_query)) {
                        $error = "Gagal menambahkan data Halo PST: " . mysqli_error($koneksi);
                    } else {
                        // Insert into user_skill
                        $skills_insert_success = true;
                        foreach ($skills as $id_skill) {
                            $insert_skill_query = "INSERT INTO user_skill (id_skill, nip) VALUES (" . $id_skill . ", '" . mysqli_real_escape_string($koneksi, $nip) . "')";
                            
                            if (!mysqli_query($koneksi, $insert_skill_query)) {
                                $error = "Gagal menambahkan data skill: " . mysqli_error($koneksi);
                                $skills_insert_success = false;
                                break;
                            }
                        }
                        
                        if ($skills_insert_success) {
                            $success = "User berhasil ditambahkan!";
                            // Redirect after 1 second
                            header("Refresh: 1; url=../manajemen_user.php");
                        }
                    }
                }
            } else {
                $error = "Gagal menambahkan user: " . mysqli_error($koneksi);
            }
        }
    }
}

// ==================== IMPORT SECTION ====================
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

// Handle data submission from preview
if (isset($_POST["submit_data"]) && !empty($_POST["preview_data"])) {
    $activeTab = 'import';
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

// ==================== GET REFERENCE DATA ====================
// Get jabatan data
$jabatan_query = "SELECT id_jabatan, nama_jabatan FROM jabatan";
$jabatan_result = mysqli_query($koneksi, $jabatan_query);
$jabatan_data = [];
$jabatan = [];
if ($jabatan_result) {
    while ($row = mysqli_fetch_assoc($jabatan_result)) {
        $jabatan_data[] = $row;
        $jabatan[$row['id_jabatan']] = $row['nama_jabatan'];
    }
}

// Get role data
$role_query = "SELECT id_role, nama_role FROM role";
$role_result = mysqli_query($koneksi, $role_query);
$role_data = [];
$role = [];
if ($role_result) {
    while ($row = mysqli_fetch_assoc($role_result)) {
        $role_data[] = $row;
        $role[$row['id_role']] = $row['nama_role'];
    }
}

// Get ppid data
$ppid_query = "SELECT id_ppid, nama_ppid FROM ppid";
$ppid_result = mysqli_query($koneksi, $ppid_query);
$ppid_data = [];
$ppid = [];
if ($ppid_result) {
    while ($row = mysqli_fetch_assoc($ppid_result)) {
        $ppid_data[] = $row;
        $ppid[$row['id_ppid']] = $row['nama_ppid'];
    }
}

// Get halo_pst data
$halo_pst_query = "SELECT id_halo_pst, nama_halo_pst FROM halo_pst";
$halo_pst_result = mysqli_query($koneksi, $halo_pst_query);
$halo_pst_data = [];
$haloPST = [];
if ($halo_pst_result) {
    while ($row = mysqli_fetch_assoc($halo_pst_result)) {
        $halo_pst_data[] = $row;
        $haloPST[$row['id_halo_pst']] = $row['nama_halo_pst'];
    }
}

// Get skill data
$skill_query = "SELECT id_skill, nama_skill FROM skill";
$skill_result = mysqli_query($koneksi, $skill_query);
$skill_data = [];
$skill = [];
if ($skill_result) {
    while ($row = mysqli_fetch_assoc($skill_result)) {
        $skill_data[] = $row;
        $skill[$row['id_skill']] = $row['nama_skill'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
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
        .skill-badge {
            display: inline-block;
            margin: 5px;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            font-size: 14px;
        }
        .skill-badge .remove-skill {
            cursor: pointer;
            margin-left: 8px;
            font-weight: bold;
        }
        .skill-badge .remove-skill:hover {
            color: #ffcccc;
        }
        #selected-skills {
            min-height: 50px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 4px;
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
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tambah User</h5>
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

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show mt-3">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show mt-3">
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Input Manual Tab -->
                        <div class="tab-pane m-t-10 fade <?php echo $activeTab === 'input' ? 'show active' : ''; ?>" id="input-pane" role="tabpanel">
                            <form method="POST" action="">
                                <input type="hidden" name="tab" value="input">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nip">NIP <span class="text-danger">*</span></label>
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            id="nip" 
                                            name="nip"
                                            placeholder="Masukkan NIP"
                                            required
                                            value="<?php echo isset($_POST["nip"]) ? htmlspecialchars($_POST["nip"]) : ''; ?>"
                                        >
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label for="nama">Nama <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="nama" 
                                            name="nama"
                                            placeholder="Masukkan nama"
                                            required
                                            maxlength="255"
                                            value="<?php echo isset($_POST["nama"]) ? htmlspecialchars($_POST["nama"]) : ''; ?>"
                                        >
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input 
                                            type="email" 
                                            class="form-control" 
                                            id="email" 
                                            name="email"
                                            placeholder="Masukkan email"
                                            required
                                            maxlength="255"
                                            value="<?php echo isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ''; ?>"
                                        >
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input 
                                            type="password" 
                                            class="form-control" 
                                            id="password" 
                                            name="password"
                                            placeholder="Masukkan password"
                                            required
                                            maxlength="255"
                                        >
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nomor_telepon">Nomor Telepon</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="nomor_telepon" 
                                            name="nomor_telepon"
                                            placeholder="Masukkan nomor telepon (opsional)"
                                            value="<?php echo isset($_POST["nomor_telepon"]) ? htmlspecialchars($_POST["nomor_telepon"]) : ''; ?>"
                                        >
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="1" <?php echo isset($_POST["status"]) && $_POST["status"] == '1' ? 'selected' : ''; ?>>Aktif</option>
                                            <option value="0" <?php echo isset($_POST["status"]) && $_POST["status"] == '0' ? 'selected' : ''; ?>>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="id_jabatan">Jabatan <span class="text-danger">*</span></label>
                                        <select class="form-control" id="id_jabatan" name="id_jabatan" required>
                                            <option value="">-- Pilih Jabatan --</option>
                                            <?php foreach ($jabatan_data as $jab): ?>
                                                <option value="<?php echo $jab['id_jabatan']; ?>" <?php echo isset($_POST["id_jabatan"]) && $_POST["id_jabatan"] == $jab['id_jabatan'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($jab['nama_jabatan']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="id_role">Role <span class="text-danger">*</span></label>
                                        <select class="form-control" id="id_role" name="id_role" required>
                                            <option value="">-- Pilih Role --</option>
                                            <?php foreach ($role_data as $r): ?>
                                                <option value="<?php echo $r['id_role']; ?>" <?php echo isset($_POST["id_role"]) && $_POST["id_role"] == $r['id_role'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($r['nama_role']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="id_ppid">PPID Team <span class="text-danger">*</span></label>
                                        <select class="form-control" id="id_ppid" name="id_ppid" required>
                                            <option value="">-- Pilih PPID Team --</option>
                                            <?php foreach ($ppid_data as $p): ?>
                                                <option value="<?php echo $p['id_ppid']; ?>" <?php echo isset($_POST["id_ppid"]) && $_POST["id_ppid"] == $p['id_ppid'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($p['nama_ppid']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="id_halo_pst">Halo PST Team <span class="text-danger">*</span></label>
                                        <select class="form-control" id="id_halo_pst" name="id_halo_pst" required>
                                            <option value="">-- Pilih Halo PST Team --</option>
                                            <?php foreach ($halo_pst_data as $h): ?>
                                                <option value="<?php echo $h['id_halo_pst']; ?>" <?php echo isset($_POST["id_halo_pst"]) && $_POST["id_halo_pst"] == $h['id_halo_pst'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($h['nama_halo_pst']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="skill_select">Skills</label>
                                    <select class="form-control" id="skill_select">
                                        <option value="">-- Pilih Skill --</option>
                                        <?php foreach ($skill_data as $s): ?>
                                            <option value="<?php echo $s['id_skill']; ?>" data-name="<?php echo htmlspecialchars($s['nama_skill']); ?>">
                                                <?php echo htmlspecialchars($s['nama_skill']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <div id="selected-skills"></div>
                                    <input type="hidden" id="skills_input" name="skills" value="">
                                </div>
                                
                                <div class="form-group mt-4 d-flex justify-content-between">
                                    <a href="../manajemen_user.php" class="btn btn-secondary mr-2">
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Import Tab -->
                        <div class="tab-pane fade <?php echo $activeTab === 'import' ? 'show active' : ''; ?>" id="import-pane" role="tabpanel">
                            <!-- Template Information -->
                            <div class="template-info">
                                <h6 class="mb-2"><i class="fas fa-info-circle"></i> Format Excel yang Dibutuhkan</h6>
                                <p class="mb-0">
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
                                        <i class="fas fa-download"></i> Download Template
                                    </a>
                                </p>
                            </div>

                            <?php if (!$fileUploaded): ?>
                            <!-- Upload Section -->
                            <form method="POST" enctype="multipart/form-data" id="uploadForm">
                                <input type="hidden" name="tab" value="import">
                                <div class="form-group">
                                    <label>Pilih File Excel</label>
                                    <div class="dropzone" id="dropzone">
                                        <i class="fas fa-cloud-upload"></i>
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
                                    <i class="fas fa-save"></i> Import Data
                                </button>
                                <a href="tambah_user.php" class="btn btn-secondary">Batal</a>
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

        // Handle skills for input tab
        let selectedSkills = {};

        const skillSelect = document.getElementById('skill_select');
        if (skillSelect) {
            skillSelect.addEventListener('change', function() {
                const skillId = this.value;
                const skillName = this.options[this.selectedIndex].getAttribute('data-name');

                if (skillId && !selectedSkills[skillId]) {
                    selectedSkills[skillId] = skillName;
                    updateSkillDisplay();
                    this.value = '';
                }
            });
        }

        function updateSkillDisplay() {
            const container = document.getElementById('selected-skills');
            const input = document.getElementById('skills_input');

            container.innerHTML = '';
            const skillIds = Object.keys(selectedSkills);

            skillIds.forEach(skillId => {
                const skillName = selectedSkills[skillId];
                const badge = document.createElement('span');
                badge.className = 'skill-badge';
                badge.innerHTML = skillName + ' <span class="remove-skill" onclick="removeSkill(' + skillId + ')">×</span>';
                container.appendChild(badge);
            });

            input.value = skillIds.join(',');
        }

        window.removeSkill = function(skillId) {
            delete selectedSkills[skillId];
            updateSkillDisplay();
        };
    });
</script>

</body>
</html>
