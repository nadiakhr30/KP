<?php
include("../../koneksi.php");

$error = "";
$success = "";
$jadwal = null;
$pic_data = [];
$selected_links = [];

// Check if ID provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../jadwal_konten_humas.php");
    exit();
}

$id_jadwal = (int)$_GET['id'];

// Fetch jadwal data
$query = "SELECT * FROM jadwal WHERE id_jadwal = " . $id_jadwal;
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: ../jadwal_konten_humas.php");
    exit();
}

$jadwal = mysqli_fetch_assoc($result);

// Fetch current PIC data
$qPic = mysqli_query($koneksi, "
    SELECT id_jenis_pic, nip
    FROM pic
    WHERE id_jadwal = " . $id_jadwal
);

if ($qPic) {
    while ($pic = mysqli_fetch_assoc($qPic)) {
        $pic_data[$pic['id_jenis_pic']] = $pic['nip'];
    }
}

// Fetch current link selections
$qLinks = mysqli_query($koneksi, "
    SELECT id_jenis_link
    FROM jadwal_link
    WHERE id_jadwal = " . $id_jadwal
);

if ($qLinks) {
    while ($link = mysqli_fetch_assoc($qLinks)) {
        $selected_links[] = $link['id_jenis_link'];
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tim = trim($_POST["tim"] ?? "");
    $topik = trim($_POST["topik"] ?? "");
    $judul_kegiatan = trim($_POST["judul_kegiatan"] ?? "");
    $tanggal_penugasan = trim($_POST["tanggal_penugasan"] ?? "");
    $tanggal_rilis = trim($_POST["tanggal_rilis"] ?? "");
    $keterangan = trim($_POST["keterangan"] ?? "");
    $status = (int)($_POST["status"] ?? 0);
    $links_str = trim($_POST["links"] ?? "");
    $links = !empty($links_str) ? explode(',', $links_str) : [];
    
    // Get PIC data
    $new_pic_data = [];
    $jenis_pic_query = "SELECT id_jenis_pic FROM jenis_pic";
    $jenis_pic_result = mysqli_query($koneksi, $jenis_pic_query);
    while ($jenis = mysqli_fetch_assoc($jenis_pic_result)) {
        $pic_key = "pic_" . $jenis['id_jenis_pic'];
        if (isset($_POST[$pic_key]) && !empty($_POST[$pic_key])) {
            $new_pic_data[$jenis['id_jenis_pic']] = (int)$_POST[$pic_key];
        }
    }
    
    // Validate required input
    if (empty($tim) || empty($topik) || empty($judul_kegiatan) || empty($tanggal_penugasan) || empty($tanggal_rilis) || empty($keterangan)) {
        $error = "Semua field wajib diisi!";
    } else if (count($new_pic_data) == 0) {
        $error = "Minimal satu PIC harus dipilih!";
    } else {
        // Update jadwal table
        $update_query = "UPDATE jadwal SET
            tim = '" . mysqli_real_escape_string($koneksi, $tim) . "',
            topik = '" . mysqli_real_escape_string($koneksi, $topik) . "',
            judul_kegiatan = '" . mysqli_real_escape_string($koneksi, $judul_kegiatan) . "',
            tanggal_penugasan = '" . mysqli_real_escape_string($koneksi, $tanggal_penugasan) . "',
            tanggal_rilis = '" . mysqli_real_escape_string($koneksi, $tanggal_rilis) . "',
            keterangan = '" . mysqli_real_escape_string($koneksi, $keterangan) . "',
            status = " . $status . "
            WHERE id_jadwal = " . $id_jadwal;
        
        if (mysqli_query($koneksi, $update_query)) {
            $pic_update_success = true;
            
            // Delete old PIC data
            mysqli_query($koneksi, "DELETE FROM pic WHERE id_jadwal = " . $id_jadwal);
            
            // Insert new PIC data
            foreach ($new_pic_data as $id_jenis_pic => $nip) {
                $insert_pic_query = "INSERT INTO pic (nip, id_jadwal, id_jenis_pic) VALUES ('" . mysqli_real_escape_string($koneksi, $nip) . "', " . $id_jadwal . ", " . $id_jenis_pic . ")";
                
                if (!mysqli_query($koneksi, $insert_pic_query)) {
                    $error = "Gagal memperbarui data PIC: " . mysqli_error($koneksi);
                    $pic_update_success = false;
                    break;
                }
            }
            
            // Update link data
            $link_update_success = true;
            if ($pic_update_success) {
                // Delete old link data
                mysqli_query($koneksi, "DELETE FROM jadwal_link WHERE id_jadwal = " . $id_jadwal);
                
                // Insert new link data
                $selected_link_jenis = array_map('intval', $links);
                foreach ($selected_link_jenis as $id_jenis_link) {
                    $insert_link_query = "INSERT INTO jadwal_link (id_jadwal, id_jenis_link, link) VALUES (" . $id_jadwal . ", " . $id_jenis_link . ", NULL)";
                    
                    if (!mysqli_query($koneksi, $insert_link_query)) {
                        $error = "Gagal memperbarui data link: " . mysqli_error($koneksi);
                        $link_update_success = false;
                        break;
                    }
                }
            }
            
            if ($pic_update_success && $link_update_success) {
                $success = "Jadwal berhasil diperbarui!";
                header("Refresh: 1; url=../jadwal_konten_humas.php");
            }
        } else {
            $error = "Gagal memperbarui jadwal: " . mysqli_error($koneksi);
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
$user_query = "SELECT nip, nama FROM pegawai WHERE status = 1 ORDER BY nama";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <style>
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
    </style>
</head>
<body>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="col-md-8 my-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Jadwal</h5>
            </div>
            <div class="card-body px-5">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo htmlspecialchars($success); ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <form method="POST">
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
                                value="<?php echo htmlspecialchars($jadwal['tim']); ?>"
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
                                value="<?php echo htmlspecialchars($jadwal['topik']); ?>"
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
                            value="<?php echo htmlspecialchars($jadwal['judul_kegiatan']); ?>"
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
                                value="<?php echo $jadwal['tanggal_penugasan']; ?>"
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
                                value="<?php echo $jadwal['tanggal_rilis']; ?>"
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
                        ><?php echo htmlspecialchars($jadwal['keterangan']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="0" <?php echo $jadwal['status'] == 0 ? 'selected' : ''; ?>>Belum Dikerjakan</option>
                            <option value="1" <?php echo $jadwal['status'] == 1 ? 'selected' : ''; ?>>Sedang Dikerjakan</option>
                            <option value="2" <?php echo $jadwal['status'] == 2 ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                    </div>

                    <!-- PIC Section -->
                    <div class="form-group">
                        <label><strong>PIC (Person In Charge) <span class="text-danger">*</span></strong></label>
                    </div>
                    <div class="pic-form-row">
                        <?php foreach ($jenis_pic_data as $jenis): ?>
                            <div class="form-group col-md-6">
                                <label for="pic_<?php echo $jenis['id_jenis_pic']; ?>">
                                    <?php echo htmlspecialchars($jenis['nama_jenis_pic']); ?>
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
                                            <?php echo isset($pic_data[$jenis['id_jenis_pic']]) && $pic_data[$jenis['id_jenis_pic']] == $user['nip'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($user['nama']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Link Selection -->
                    <div class="form-group mt-4">
                        <label><strong>Pilih Link untuk Dipublikasikan</strong></label>
                        <div class="link-buttons-container mb-3">
                            <?php foreach ($link_options as $link): ?>
                                <button 
                                    type="button" 
                                    class="btn btn-outline-primary btn-sm link-btn me-2" 
                                    data-link-id="<?php echo $link['id_jenis_link']; ?>"
                                    data-link-name="<?php echo htmlspecialchars($link['nama_jenis_link']); ?>"
                                    style="margin-bottom: 8px;"
                                    <?php echo in_array($link['id_jenis_link'], $selected_links) ? 'data-selected="true"' : ''; ?>
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
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedLinks = {};

        // Initialize selected links from data attributes
        document.querySelectorAll('.link-btn[data-selected="true"]').forEach(button => {
            const linkId = button.dataset.linkId;
            const linkName = button.dataset.linkName;
            selectedLinks[linkId] = linkName;
            button.classList.add('active');
        });

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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateLinkDisplay();
        });
    </script>
</body>
</html>

