<?php
include("../../koneksi.php");

$error = "";
$success = "";
$previewData = [];
$fileUploaded = false;
$showImportTab = false;

// ==================== IMPORT SECTION ====================
// Handle clear preview (back button in preview section)
if (isset($_POST["clear_preview"])) {
    $previewData = [];
    $fileUploaded = false;
    $showImportTab = true;
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["excelFile"])) {
    $file = $_FILES["excelFile"];
    
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $error = "Gagal upload file!";
    } else if ($file["size"] > 5 * 1024 * 1024) {
        $error = "Ukuran file terlalu besar (max 5MB)!";
    } else {
        $fileExt = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if ($fileExt !== 'xls') {
            $error = "Format file harus .xls! (Gunakan template yang sudah disediakan)";
        } else {
            require '../../vendor/autoload.php';
            $inputFileName = $file["tmp_name"];
            
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                for ($i = 1; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    
                    if (empty($row[0]) && empty($row[1])) {
                        continue;
                    }
                    
                    $previewData[] = [
                        'tim' => trim($row[0] ?? ''),
                        'topik' => trim($row[1] ?? ''),
                        'judul_kegiatan' => trim($row[2] ?? ''),
                        'tanggal_penugasan' => trim($row[3] ?? ''),
                        'tanggal_rilis' => trim($row[4] ?? ''),
                        'keterangan' => trim($row[5] ?? ''),
                        'pic_data' => trim($row[6] ?? ''),
                        'links' => isset($row[7]) && !is_null($row[7]) ? trim((string)$row[7]) : ''
                    ];
                }
                
                if (count($previewData) > 0) {
                    $fileUploaded = true;
                    $showImportTab = true;
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
    $data = json_decode($_POST["preview_data"], true);
    
    if ($data && is_array($data)) {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        
        foreach ($data as $index => $jadwalData) {
            if (empty($jadwalData['tim']) || empty($jadwalData['topik']) || empty($jadwalData['judul_kegiatan'])) {
                $errorCount++;
                $errors[] = "Baris " . ($index + 2) . ": Tim, Topik, dan Judul Kegiatan harus diisi!";
                continue;
            }
            
            $insertJadwal = "INSERT INTO jadwal (tim, topik, judul_kegiatan, tanggal_penugasan, tanggal_rilis, keterangan, status) 
                            VALUES (
                              '" . mysqli_real_escape_string($koneksi, $jadwalData['tim']) . "',
                              '" . mysqli_real_escape_string($koneksi, $jadwalData['topik']) . "',
                              '" . mysqli_real_escape_string($koneksi, $jadwalData['judul_kegiatan']) . "',
                              '" . mysqli_real_escape_string($koneksi, $jadwalData['tanggal_penugasan']) . "',
                              '" . mysqli_real_escape_string($koneksi, $jadwalData['tanggal_rilis']) . "',
                              '" . mysqli_real_escape_string($koneksi, $jadwalData['keterangan']) . "',
                              0
                            )";
            
            if (mysqli_query($koneksi, $insertJadwal)) {
                $id_jadwal = mysqli_insert_id($koneksi);
                $pic_ok = true;
                
                // Insert PIC data (format: nip1|jenis_pic_id1,nip2|jenis_pic_id2)
                if (!empty($jadwalData['pic_data'])) {
                    $pics = explode(',', $jadwalData['pic_data']);
                    foreach ($pics as $pic) {
                        list($nip, $id_jenis_pic) = explode('|', trim($pic));
                        $nip = trim($nip);
                        $id_jenis_pic = (int)trim($id_jenis_pic);
                        
                        if (!empty($nip) && $id_jenis_pic > 0) {
                            $insertPic = "INSERT INTO pic (nip, id_jadwal, id_jenis_pic) VALUES ('" . mysqli_real_escape_string($koneksi, $nip) . "', " . $id_jadwal . ", " . $id_jenis_pic . ")";
                            if (!mysqli_query($koneksi, $insertPic)) {
                                $pic_ok = false;
                                break;
                            }
                        }
                    }
                }
                
                // Insert link data (format: jenis_link_id1,jenis_link_id2)
                if ($pic_ok && !empty($jadwalData['links'])) {
                    // Normalize: convert decimal point to comma (for locale issue)
                    $linksNormalized = str_replace('.', ',', $jadwalData['links']);
                    $linkIds = array_filter(array_map('intval', explode(',', $linksNormalized)));
                    foreach ($linkIds as $id_jenis_link) {
                        $insertLink = "INSERT INTO jadwal_link (id_jadwal, id_jenis_link, link) VALUES (" . $id_jadwal . ", " . $id_jenis_link . ", NULL)";
                        mysqli_query($koneksi, $insertLink);
                    }
                }
                
                $successCount++;
            } else {
                $errorCount++;
                $errors[] = "Baris " . ($index + 2) . ": Gagal menambahkan jadwal!";
            }
        }
        
        if ($successCount > 0) {
            $success = "Berhasil menambahkan $successCount jadwal!";
            $previewData = [];
            $fileUploaded = false;
            $showImportTab = true;
            header("Refresh: 1; url=../jadwal_konten_humas.php");
        }
        
        if ($errorCount > 0) {
            $error = "Gagal menambahkan $errorCount jadwal. " . implode(" ", $errors);
        }
    }
}

// ==================== MANUAL INPUT SECTION ====================
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_FILES["excelFile"]) && !isset($_POST["submit_data"])) {
    // Sanitize and validate input
    $tim = trim($_POST["tim"] ?? "");
    $topik = trim($_POST["topik"] ?? "");
    $judul_kegiatan = trim($_POST["judul_kegiatan"] ?? "");
    $tanggal_penugasan = trim($_POST["tanggal_penugasan"] ?? "");
    $tanggal_rilis = trim($_POST["tanggal_rilis"] ?? "");
    $keterangan = trim($_POST["keterangan"] ?? "");
    $status = 0; // Always 0
    $links_str = trim($_POST["links"] ?? "");
    $links = !empty($links_str) ? explode(',', $links_str) : [];
    
    // Get PIC data
    $pic_data = [];
    $jenis_pic_query = "SELECT id_jenis_pic FROM jenis_pic";
    $jenis_pic_result = mysqli_query($koneksi, $jenis_pic_query);
    while ($jenis = mysqli_fetch_assoc($jenis_pic_result)) {
        $pic_key = "pic_" . $jenis['id_jenis_pic'];
        if (isset($_POST[$pic_key]) && !empty($_POST[$pic_key])) {
            $pic_data[$jenis['id_jenis_pic']] = (int)$_POST[$pic_key];
        }
    }
    
    // Get selected link jenis_link IDs
    $selected_link_jenis = [];
    if (!empty($links_str)) {
        $selected_link_jenis = array_map('intval', explode(',', $links_str));
    }
    
    // Validate required input
    if (empty($tim) || empty($topik) || empty($judul_kegiatan) || empty($tanggal_penugasan) || empty($tanggal_rilis) || empty($keterangan)) {
        $error = "Semua field wajib diisi!";
    } else if (count($pic_data) == 0) {
        $error = "Minimal satu PIC harus dipilih!";
    } else {
        // Insert into jadwal table (without link columns - they're now in jadwal_link table)
        $query = "INSERT INTO jadwal (tim, topik, judul_kegiatan, tanggal_penugasan, tanggal_rilis, keterangan, status) 
                  VALUES (
                    '" . mysqli_real_escape_string($koneksi, $tim) . "',
                    '" . mysqli_real_escape_string($koneksi, $topik) . "',
                    '" . mysqli_real_escape_string($koneksi, $judul_kegiatan) . "',
                    '" . mysqli_real_escape_string($koneksi, $tanggal_penugasan) . "',
                    '" . mysqli_real_escape_string($koneksi, $tanggal_rilis) . "',
                    '" . mysqli_real_escape_string($koneksi, $keterangan) . "',
                    " . $status . "
                  )";
        
        if (mysqli_query($koneksi, $query)) {
            $id_jadwal = mysqli_insert_id($koneksi);
            
            // Insert into pic table
            $pic_insert_success = true;
            foreach ($pic_data as $id_jenis_pic => $nip) {
                $insert_pic_query = "INSERT INTO pic (nip, id_jadwal, id_jenis_pic) VALUES (" . $nip . ", " . $id_jadwal . ", " . $id_jenis_pic . ")";
                
                if (!mysqli_query($koneksi, $insert_pic_query)) {
                    $error = "Gagal menambahkan data PIC: " . mysqli_error($koneksi);
                    $pic_insert_success = false;
                    break;
                }
            }
            
            // Insert into jadwal_link table for selected link types
            $link_insert_success = true;
            if ($pic_insert_success && count($selected_link_jenis) > 0) {
                foreach ($selected_link_jenis as $id_jenis_link) {
                    $insert_link_query = "INSERT INTO jadwal_link (id_jadwal, id_jenis_link, link) VALUES (" . $id_jadwal . ", " . $id_jenis_link . ", NULL)";
                    
                    if (!mysqli_query($koneksi, $insert_link_query)) {
                        $error = "Gagal menambahkan data link: " . mysqli_error($koneksi);
                        $link_insert_success = false;
                        break;
                    }
                }
            }
            
            if ($pic_insert_success && $link_insert_success) {
                $success = "Jadwal berhasil ditambahkan!";
                header("Refresh: 1; url=../jadwal_konten_humas.php");
            }
        } else {
            $error = "Gagal menambahkan jadwal: " . mysqli_error($koneksi);
        }
    }
}

// Get jenis_pic data for PIC selects
$jenis_pic_query = "SELECT id_jenis_pic, nama_jenis_pic FROM jenis_pic ORDER BY nama_jenis_pic";
$jenis_pic_result = mysqli_query($koneksi, $jenis_pic_query);
$jenis_pic_data = [];
if ($jenis_pic_result) {
    while ($row = mysqli_fetch_assoc($jenis_pic_result)) {
        $jenis_pic_data[] = $row;
    }
}

// Get user data for PIC dropdowns
$user_query = "SELECT nip, nama FROM user WHERE status = 1 ORDER BY nama";
$user_result = mysqli_query($koneksi, $user_query);
$user_data = [];
if ($user_result) {
    while ($row = mysqli_fetch_assoc($user_result)) {
        $user_data[] = $row;
    }
}

// Get link options from jenis_link table
$link_query = "SELECT id_jenis_link, nama_jenis_link FROM jenis_link ORDER BY nama_jenis_link";
$link_result = mysqli_query($koneksi, $link_query);
$link_options = [];
if ($link_result) {
    while ($row = mysqli_fetch_assoc($link_result)) {
        $link_options[] = $row;
    }
}

// Get jenis_pic and jenis_link data for template info
$jenis_pic_map = [];
foreach ($jenis_pic_data as $jp) {
    $jenis_pic_map[$jp['id_jenis_pic']] = $jp['nama_jenis_pic'];
}

$link_map = [];
foreach ($link_options as $lo) {
    $link_map[$lo['id_jenis_link']] = $lo['nama_jenis_link'];
}

// Create user map for easy lookup
$user_map = [];
foreach ($user_data as $user) {
    $user_map[$user['nip']] = $user['nama'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <style>
        .link-badge {
            display: inline-block;
            margin: 5px 5px 5px 0;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            font-size: 14px;
        }
        .link-badge .remove-link {
            cursor: pointer;
            margin-left: 8px;
            font-weight: bold;
        }
        .link-badge .remove-link:hover {
            color: #ffcccc;
        }
        .pic-form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .pic-form-row .form-group {
            padding-right: 15px;
            padding-left: 15px;
        }
        @media (max-width: 767.98px) {
            .pic-form-row .form-group {
                flex: 0 0 100%;
                max-width: 100%;
                padding-right: 0;
                padding-left: 0;
            }
        }
        @media (min-width: 768px) {
            .pic-form-row .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
        .link-buttons-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .link-btn {
            border-width: 2px;
            transition: all 0.3s ease;
        }
        .link-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.25);
        }
        .link-btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .link-btn.active:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
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
    </style>
</head>
<body>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="col-md-8 my-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tambah Jadwal</h5>
            </div>
            <div class="card-body px-5">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs md-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?php echo !$showImportTab ? 'active' : ''; ?>" id="input-tab" data-toggle="tab" href="#input-pane" role="tab">
                            <i class="fas fa-keyboard"></i> Input Manual
                        </a>
                        <div class="slide"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $showImportTab ? 'active' : ''; ?>" id="import-tab" data-toggle="tab" href="#import-pane" role="tab">
                            <i class="fas fa-file-upload"></i> Import dari Excel
                        </a>
                        <div class="slide"></div>
                    </li>
                </ul>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show mt-3">
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3">
                        <?php echo htmlspecialchars($success); ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Input Manual Tab -->
                    <div class="tab-pane m-t-10 fade <?php echo !$showImportTab ? 'show active' : ''; ?>" id="input-pane" role="tabpanel">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="tim">Tim <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="tim" 
                                        name="tim"
                                        placeholder="Masukkan nama tim"
                                        required
                                        maxlength="255"
                                        value="<?php echo isset($_POST["tim"]) ? htmlspecialchars($_POST["tim"]) : ''; ?>"
                                    >
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="topik">Topik <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="topik" 
                                        name="topik"
                                        placeholder="Masukkan topik"
                                        required
                                        maxlength="255"
                                        value="<?php echo isset($_POST["topik"]) ? htmlspecialchars($_POST["topik"]) : ''; ?>"
                                    >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="judul_kegiatan">Judul Kegiatan <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="judul_kegiatan" 
                                    name="judul_kegiatan"
                                    placeholder="Masukkan judul kegiatan"
                                    required
                                    maxlength="255"
                                    value="<?php echo isset($_POST["judul_kegiatan"]) ? htmlspecialchars($_POST["judul_kegiatan"]) : ''; ?>"
                                >
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="tanggal_penugasan">Tanggal Penugasan <span class="text-danger">*</span></label>
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        id="tanggal_penugasan" 
                                        name="tanggal_penugasan"
                                        required
                                        value="<?php echo isset($_POST["tanggal_penugasan"]) ? htmlspecialchars($_POST["tanggal_penugasan"]) : ''; ?>"
                                    >
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="tanggal_rilis">Tanggal Rilis <span class="text-danger">*</span></label>
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        id="tanggal_rilis" 
                                        name="tanggal_rilis"
                                        required
                                        value="<?php echo isset($_POST["tanggal_rilis"]) ? htmlspecialchars($_POST["tanggal_rilis"]) : ''; ?>"
                                    >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                                <textarea 
                                    class="form-control" 
                                    id="keterangan" 
                                    name="keterangan"
                                    placeholder="Masukkan keterangan"
                                    required
                                    rows="4"
                                ><?php echo isset($_POST["keterangan"]) ? htmlspecialchars($_POST["keterangan"]) : ''; ?></textarea>
                            </div>
                            <div class="pic-form-row">
                                    <?php foreach ($jenis_pic_data as $jenis): ?>
                                        <div class="form-group col-md-6">
                                            <label for="pic_<?php echo $jenis['id_jenis_pic']; ?>">
                                                PIC <?php echo htmlspecialchars($jenis['nama_jenis_pic']); ?> <span class="text-danger">*</span>
                                            </label>
                                            <select 
                                                class="form-control" 
                                                id="pic_<?php echo $jenis['id_jenis_pic']; ?>" 
                                                name="pic_<?php echo $jenis['id_jenis_pic']; ?>"
                                                required
                                            >
                                                <option value="">-- Pilih <?php echo htmlspecialchars($jenis['nama_jenis_pic']); ?> --</option>
                                                <?php foreach ($user_data as $user): ?>
                                                    <option value="<?php echo $user['nip']; ?>" 
                                                        <?php echo isset($_POST["pic_" . $jenis['id_jenis_pic']]) && $_POST["pic_" . $jenis['id_jenis_pic']] == $user['nip'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($user['nama']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <?php endforeach; ?>
                            </div>
                            <!-- Link Selection -->
                            <div class="form-group">
                                <label>Pilih Link untuk Dipublikasikan</label>
                                <div class="link-buttons-container mb-3">
                                    <?php foreach ($link_options as $link): ?>
                                        <button 
                                            type="button" 
                                            class="btn btn-outline-primary btn-sm link-btn me-2" 
                                            data-link-id="<?php echo $link['id_jenis_link']; ?>"
                                            data-link-name="<?php echo htmlspecialchars($link['nama_jenis_link']); ?>"
                                            style="margin-bottom: 8px;"
                                        >
                                            <?php echo htmlspecialchars($link['nama_jenis_link']); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <input type="hidden" id="links_input" name="links" value="">
                            <div class="form-group mt-4 d-flex justify-content-between">
                                <a href="../jadwal_konten_humas.php" class="btn btn-secondary btn-icon-l"><i class="fas fa-arrow-left"></i></a>
                                <button type="submit" class="btn btn-primary btn-icon-l"><i class="fas fa-save"></i></button>
                            </div>
                        </form>
                    </div>

                    <!-- Import Tab -->
                    <div class="tab-pane px-5 fade <?php echo $showImportTab ? 'show active' : ''; ?>" id="import-pane" role="tabpanel">
                        <!-- Template Information -->
                        <div class="template-info mt-4">
                            <h6 class="mb-2"><i class="fas fa-info-circle"></i> Format Excel yang Dibutuhkan</h6>
                            <p class="mb-0">
                                <strong>Kolom Excel (8 Kolom):</strong><br>
                                <div class="row no-border">
                                    <div class="col-4"><strong>1. Tim</strong></div>
                                    <div class="col-8">Nama tim (Required)</div>
                                </div>
                                <div class="row no-border">
                                    <div class="col-4"><strong>2. Topik</strong></div>
                                    <div class="col-8">Topik jadwal (Required)</div>
                                </div>
                                <div class="row no-border">
                                    <div class="col-4"><strong>3. Judul Kegiatan</strong></div>
                                    <div class="col-8">Judul kegiatan (Required)</div>
                                </div>
                                <div class="row no-border">
                                    <div class="col-4"><strong>4. Tanggal Penugasan</strong></div>
                                    <div class="col-8">Format: YYYY-MM-DD (Required)</div>
                                </div>
                                <div class="row no-border">
                                    <div class="col-4"><strong>5. Tanggal Rilis</strong></div>
                                    <div class="col-8">Format: YYYY-MM-DD (Required)</div>
                                </div>
                                <div class="row no-border">
                                    <div class="col-4"><strong>6. Keterangan</strong></div>
                                    <div class="col-8">Keterangan jadwal (Required)</div>
                                </div>
                                <hr>
                                <div class="row no-border">
                                    <div class="col-12"><strong>7. PIC Data Format:</strong> <code>nip|jenis_pic_id,nip|jenis_pic_id</code><br>
                                    Contoh: <code>123456|1,654321|2</code><br>
                                    <strong>Jenis PIC:</strong>
                                    <?php foreach ($jenis_pic_map as $id => $nama): ?>
                                        <div style="margin-left: 20px;"><?= htmlspecialchars($id); ?> = <?= htmlspecialchars($nama); ?></div>
                                    <?php endforeach; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row no-border">
                                    <div class="col-12"><strong>8. Links Format:</strong> ID Jenis Link dipisahkan koma: <code>1,2,3</code><br>
                                    <strong>Jenis Link:</strong>
                                    <?php foreach ($link_map as $id => $nama): ?>
                                        <div style="margin-left: 20px;"><?= htmlspecialchars($id); ?> = <?= htmlspecialchars($nama); ?></div>
                                    <?php endforeach; ?>
                                    </div>
                                </div>
                            </p>
                            <p style="font-size: 12px; margin-top: 10px;">
                                <a href="../download_template/download_template_jadwal.php" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-download"></i> Download Template
                                </a>
                            </p>
                        </div>

                        <?php if (!$fileUploaded): ?>
                        <!-- Upload Section -->
                        <form method="POST" enctype="multipart/form-data" id="uploadForm" class="mt-4">
                            <div class="form-group">
                                <label for="fileInput">Upload File Excel (.xls)</label>
                                <div class="dropzone" id="dropzone">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p><strong>Drag & Drop file di sini</strong></p>
                                    <p class="text-muted">atau klik untuk memilih file</p>
                                </div>
                                <input type="file" id="fileInput" name="excelFile" accept=".xls" style="display: none;">
                            </div>
                            <a href="../jadwal_konten_humas.php" class="btn btn-secondary btn-icon-l"><i class="fas fa-arrow-left"></i></a>
                        </form>
                        <?php else: ?>
                        <!-- Preview Section -->
                        <h5 class="mt-4 mb-3">Preview Data (<?php echo count($previewData); ?> Jadwal)</h5>
                        <div class="preview-table">
                            <table class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tim</th>
                                        <th>Topik</th>
                                        <th>Judul Kegiatan</th>
                                        <th>Tgl Penugasan</th>
                                        <th>Tgl Rilis</th>
                                        <th>Keterangan</th>
                                        <th>PIC Data</th>
                                        <th>Link</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($previewData as $idx => $data): ?>
                                    <tr>
                                        <td><?php echo $idx + 1; ?></td>
                                        <td><?php echo htmlspecialchars($data['tim']); ?></td>
                                        <td><?php echo htmlspecialchars($data['topik']); ?></td>
                                        <td><?php echo htmlspecialchars($data['judul_kegiatan']); ?></td>
                                        <td><?php echo htmlspecialchars($data['tanggal_penugasan']); ?></td>
                                        <td><?php echo htmlspecialchars($data['tanggal_rilis']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($data['keterangan'], 0, 30)) . (strlen($data['keterangan']) > 30 ? '...' : ''); ?></td>
                                        <td>
                                            <?php 
                                            if (!empty($data['pic_data'])) {
                                                $pics = explode(',', $data['pic_data']);
                                                foreach ($pics as $pic) {
                                                    $parts = explode('|', trim($pic));
                                                    if (count($parts) == 2) {
                                                        $nip = trim($parts[0]);
                                                        $id_jenis_pic = trim($parts[1]);
                                                        $nama_pic = isset($jenis_pic_map[$id_jenis_pic]) ? htmlspecialchars($jenis_pic_map[$id_jenis_pic]) : $id_jenis_pic;
                                                        $nama_user = isset($user_map[$nip]) ? htmlspecialchars($user_map[$nip]) : htmlspecialchars($nip);
                                                        echo '<small class="badge badge-info" style="display: block; margin: 2px 0;">' . $nama_pic . ': ' . $nama_user . '</small>';
                                                    }
                                                }
                                            } else {
                                                echo '<small class="text-muted">-</small>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if (!empty($data['links'])) {
                                                // Normalize: convert decimal point to comma (for locale issue)
                                                $rawLinks = str_replace('.', ',', $data['links']);
                                                $linkIds = array_filter(array_map('intval', array_map('trim', explode(',', $rawLinks))));

                                                
                                                if (!empty($linkIds)) {
                                                    foreach ($linkIds as $linkId) {
                                                        if (isset($link_map[$linkId])) {
                                                            echo '<small class="badge badge-success" style="display: block; margin: 2px 0;">' . htmlspecialchars($link_map[$linkId]) . '</small>';
                                                        }
                                                    }
                                                } else {
                                                    echo '<small class="text-muted">-</small>';
                                                }
                                            } else {
                                                echo '<small class="text-muted">-</small>';
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
                                        Saya yakin data sudah benar, lanjutkan import
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mt-4 d-flex justify-content-between">
                                <form method="POST" style="display: inline;">
                                    <button type="submit" name="clear_preview" value="1" class="btn btn-secondary btn-icon-l"><i class="fas fa-arrow-left"></i></button>
                                </form>
                                <button type="submit" id="submitBtn" name="submit_data" class="btn btn-primary btn-icon-l" disabled><i class="fas fa-check"></i></button>
                            </div>
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
        let selectedLinks = {};

        // Handle link button clicks
        document.querySelectorAll('.link-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const linkId = this.dataset.linkId;
                const linkName = this.dataset.linkName;

                if (selectedLinks[linkId]) {
                    // Remove if already selected
                    delete selectedLinks[linkId];
                    this.classList.remove('active');
                } else {
                    // Add if not selected
                    selectedLinks[linkId] = linkName;
                    this.classList.add('active');
                }

                updateLinkDisplay();
            });
        });

        function updateLinkDisplay() {
            const input = document.getElementById('links_input');
            const linkIds = Object.keys(selectedLinks);
            
            if (input) {
                input.value = linkIds.join(',');
            }
        }

        function removeLink(linkId) {
            delete selectedLinks[linkId];
            
            // Update button state
            const button = document.querySelector(`[data-link-id="${linkId}"]`);
            if (button) {
                button.classList.remove('active');
            }
            
            updateLinkDisplay();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateLinkDisplay();

            // Handle upload section
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('fileInput');
            const uploadForm = document.getElementById('uploadForm');
            const confirmCheckbox = document.getElementById('confirmCheckbox');
            const submitBtn = document.getElementById('submitBtn');

            if (dropzone && fileInput && uploadForm) {
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
                        dropzone.style.display = 'none';
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