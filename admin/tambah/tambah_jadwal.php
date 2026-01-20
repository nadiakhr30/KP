<?php
include("../../koneksi.php");

$error = "";
$success = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    
    // Validate required input
    if (empty($tim) || empty($topik) || empty($judul_kegiatan) || empty($tanggal_penugasan) || empty($tanggal_rilis) || empty($keterangan)) {
        $error = "Semua field wajib diisi!";
    } else if (count($pic_data) == 0) {
        $error = "Minimal satu PIC harus dipilih!";
    } else {
        // Prepare link values (convert selected links to "-")
        $link_instagram = in_array('instagram', $links) ? "-" : NULL;
        $link_facebook = in_array('facebook', $links) ? "-" : NULL;
        $link_youtube = in_array('youtube', $links) ? "-" : NULL;
        $link_website = in_array('website', $links) ? "-" : NULL;
        
        // Insert into jadwal table
        $query = "INSERT INTO jadwal (tim, topik, judul_kegiatan, tanggal_penugasan, tanggal_rilis, keterangan, status, link_instagram, link_facebook, link_youtube, link_website) 
                  VALUES (
                    '" . mysqli_real_escape_string($koneksi, $tim) . "',
                    '" . mysqli_real_escape_string($koneksi, $topik) . "',
                    '" . mysqli_real_escape_string($koneksi, $judul_kegiatan) . "',
                    '" . mysqli_real_escape_string($koneksi, $tanggal_penugasan) . "',
                    '" . mysqli_real_escape_string($koneksi, $tanggal_rilis) . "',
                    '" . mysqli_real_escape_string($koneksi, $keterangan) . "',
                    " . $status . ",
                    " . ($link_instagram ? "'" . mysqli_real_escape_string($koneksi, $link_instagram) . "'" : "NULL") . ",
                    " . ($link_facebook ? "'" . mysqli_real_escape_string($koneksi, $link_facebook) . "'" : "NULL") . ",
                    " . ($link_youtube ? "'" . mysqli_real_escape_string($koneksi, $link_youtube) . "'" : "NULL") . ",
                    " . ($link_website ? "'" . mysqli_real_escape_string($koneksi, $link_website) . "'" : "NULL") . "
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
            
            if ($pic_insert_success) {
                $success = "Jadwal berhasil ditambahkan!";
                header("Refresh: 1; url=../index.php");
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

// Get link options
$link_options = [
    'instagram' => 'Instagram',
    'facebook' => 'Facebook',
    'youtube' => 'YouTube',
    'website' => 'Website'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        .link-badge {
            display: inline-block;
            margin: 5px;
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
        #selected-links {
            min-height: 50px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Tambah Jadwal</h4>
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

                            <!-- PIC Selections -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">PIC (Person In Charge) <span class="text-danger">*</span></h5>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($jenis_pic_data as $jenis): ?>
                                        <div class="form-group">
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
                            </div>

                            <!-- Link Selection -->
                            <div class="form-group">
                                <label for="link_select">Pilih Link untuk Dipublikasikan</label>
                                <select class="form-control" id="link_select">
                                    <option value="">-- Pilih Link --</option>
                                    <?php foreach ($link_options as $link_key => $link_name): ?>
                                        <option value="<?php echo $link_key; ?>">
                                            <?php echo htmlspecialchars($link_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Link yang Dipilih</label>
                                <div id="selected-links"></div>
                                <input type="hidden" id="links_input" name="links" value="">
                            </div>
                            
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary mr-2">
                                    Simpan
                                </button>
                                <a href="../index.php" class="btn btn-secondary">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedLinks = {};

        document.getElementById('link_select').addEventListener('change', function() {
            const linkKey = this.value;
            const linkName = this.options[this.selectedIndex].text;

            if (linkKey && !selectedLinks[linkKey]) {
                selectedLinks[linkKey] = linkName;
                updateLinkDisplay();
                this.value = '';
            }
        });

        function updateLinkDisplay() {
            const container = document.getElementById('selected-links');
            const input = document.getElementById('links_input');

            container.innerHTML = '';
            const linkKeys = Object.keys(selectedLinks);

            linkKeys.forEach(linkKey => {
                const linkName = selectedLinks[linkKey];
                const badge = document.createElement('span');
                badge.className = 'link-badge';
                badge.innerHTML = linkName + ' <span class="remove-link" onclick="removeLink(\'' + linkKey + '\')">×</span>';
                container.appendChild(badge);
            });

            input.value = linkKeys.join(',');
        }

        function removeLink(linkKey) {
            delete selectedLinks[linkKey];
            updateLinkDisplay();
        }
    </script>
</body>
</html>