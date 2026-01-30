<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// Handle AJAX filter request
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    header('Content-Type: application/json; charset=utf-8');
    
    $filterType = isset($_GET['filterType']) ? $_GET['filterType'] : '';
    $filterId = isset($_GET['filterId']) ? (int)$_GET['filterId'] : 0;
    
    if ($filterType === 'skill') {
        $q = mysqli_query($koneksi, "SELECT DISTINCT p.* FROM pegawai p INNER JOIN user_skill us ON p.id_pegawai = us.id_pegawai WHERE us.id_skill = $filterId");
    } elseif ($filterType === 'ppid') {
        $q = mysqli_query($koneksi, "SELECT DISTINCT p.* FROM pegawai p INNER JOIN user_ppid up ON p.id_pegawai = up.id_pegawai WHERE up.id_ppid = $filterId");
    } elseif ($filterType === 'halo') {
        $q = mysqli_query($koneksi, "SELECT DISTINCT p.* FROM pegawai p INNER JOIN user_halo_pst uh ON p.id_pegawai = uh.id_pegawai WHERE uh.id_halo_pst = $filterId");
    } else {
        echo json_encode(['error' => 'Invalid filter type']);
        exit;
    }
    
    if (!$q) {
        echo json_encode(['error' => mysqli_error($koneksi)]);
        exit;
    }
    
    $users = [];
    while ($row = mysqli_fetch_assoc($q)) {
        $users[] = $row;
    }
    
    echo json_encode($users);
    exit;
}

// Handle AJAX update from edit modal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_canva_link') {
    if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != 'Admin') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    $id_media = isset($_POST['id_media']) ? (int)$_POST['id_media'] : 0;
    $link = isset($_POST['link']) ? trim($_POST['link']) : '';
    $linkEsc = mysqli_real_escape_string($koneksi, $link);
    if ($id_media <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid id']);
        exit;
    }
    $upd = mysqli_query($koneksi, "UPDATE media SET `link` = '$linkEsc' WHERE id_media = $id_media");
    if ($upd) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($koneksi)]);
    }
    exit;
}

// Fetch all skills for display
$qSkills = mysqli_query($koneksi, "SELECT * FROM skill ORDER BY nama_skill");
$allSkills = [];
while ($row = mysqli_fetch_assoc($qSkills)) {
    $allSkills[] = $row;
}

// Fetch all PPID
$qPPIDs = mysqli_query($koneksi, "SELECT * FROM ppid ORDER BY nama_ppid");
$allPPIDs = [];
while ($row = mysqli_fetch_assoc($qPPIDs)) {
    $allPPIDs[] = $row;
}

// Fetch all Halo PST
$qHalos = mysqli_query($koneksi, "SELECT * FROM halo_pst ORDER BY nama_halo_pst");
$allHalos = [];
while ($row = mysqli_fetch_assoc($qHalos)) {
    $allHalos[] = $row;
}

// Fetch Canva media for Struktur Humas (latest)
$qCanva = mysqli_query($koneksi, "
    SELECT m.*, sj.nama_sub_jenis
    FROM media m
    LEFT JOIN sub_jenis sj ON m.id_sub_jenis = sj.id_sub_jenis
    WHERE sj.nama_sub_jenis = 'Struktur Humas'
    ORDER BY id_media DESC
    LIMIT 1
");
$canvaMedia = mysqli_num_rows($qCanva) ? mysqli_fetch_assoc($qCanva) : null;
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Struktur Organisasi Kehumasan</h5>
                        <p class="m-b-0">Galeri organigram dan filter anggota tim berdasarkan skill, PPID, atau Halo PST.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title align-items-right">
                        <li class="breadcrumb-item">
                            <a href="index.php"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="index.php">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="struktur_humas.php">Struktur Organisasi</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                            <ul class="nav nav-tabs tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#galeri" role="tab"><i class="ti-layout-grid2"></i> Galeri Struktur</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#filter" role="tab"><i class="ti-layout-menu-v"></i> Filter Tim</a>
                                </li>
                            </ul>
                            <div class="tab-content tabs card">
                                <!-- TAB 1: GALERI STRUKTUR -->
                                <div class="tab-pane p-3 active" id="galeri" role="tabpanel">
                                    <!-- Canva Preview Section -->
                                    <?php if ($canvaMedia && !empty($canvaMedia['link'])): ?>
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #fff 0%, #f8fafc 100%); border-bottom: 2px solid #f1f5f9; padding: 20px;">
                                            <h5 class="mb-0" style="color: #2c3e50; font-weight: 600; margin:0;">
                                                <i class="fas fa-diagram-project"></i> Struktur Organisasi Kehumasan
                                            </h5>
                                            <?php if (!empty($canvaMedia['id_media'])): ?>
                                            <div>
                                                <button type="button" id="editCanvaBtn" class="btn btn-sm btn-outline-secondary" data-id="<?php echo (int)$canvaMedia['id_media']; ?>" data-link="<?php echo htmlspecialchars($canvaMedia['link']); ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="card-body p-0" style="background: #f8fafc; min-height: 700px; padding: 20px;">
                                            <?php
                                            $canvaUrl = trim($canvaMedia['link']);

                                            // If stored value contains an iframe, output it directly
                                            if (strpos($canvaUrl, '<iframe') !== false): ?>
                                                <div style="width: 100%; min-height: 700px;">
                                                    <?php echo $canvaUrl; ?>
                                                </div>
                                            <?php else:
                                                // If it's a Canva URL, try convert /view to /embed and embed it
                                                if (filter_var($canvaUrl, FILTER_VALIDATE_URL) !== false && strpos($canvaUrl, 'canva.com') !== false):
                                                    $embedUrl = str_replace('/view', '/embed', $canvaUrl);
                                                    $embedUrl = strtok($embedUrl, '?');
                                            ?>
                                                    <iframe 
                                                        src="<?php echo htmlspecialchars($embedUrl); ?>" 
                                                        style="width: 100%; min-height: 700px; border: none; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                                                        allow="fullscreen"
                                                        title="Canva Design Preview">
                                                    </iframe>
                                            <?php else: ?>
                                                    <div style="text-align: center; padding: 60px 20px;">
                                                        <i class="fas fa-image fa-3x mb-3" style="opacity: 0.3; display: block; margin-bottom: 20px;"></i>
                                                        <p class="text-muted mb-3">Buka organigram di browser untuk melihat preview</p>
                                                        <a href="<?php echo htmlspecialchars($canvaUrl); ?>" target="_blank" class="btn btn-primary btn-lg">
                                                            <i class="fas fa-external-link-alt"></i> Buka Canva Organigram
                                                        </a>
                                                    </div>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-triangle"></i> <strong>Preview Canva tidak tersedia</strong><br>
                                        Silakan tambahkan media dengan sub jenis "Struktur Humas" di menu Manajemen Media.
                                    </div>
                                    <?php endif; ?>

                                    <!-- Edit Modal -->
                                    <div class="modal" id="editCanvaModal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Link / Embed Canva</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="editCanvaForm">
                                                        <input type="hidden" id="edit_id_media" name="id_media" value="" />
                                                        <div class="form-group">
                                                            <label for="edit_link">Link atau Embed Code</label>
                                                            <textarea class="form-control" id="edit_link" name="link" rows="6" placeholder="Paste Canva embed iframe or view URL here..."></textarea>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="button" class="btn btn-primary" id="saveCanvaBtn">Simpan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 2: FILTER TIM -->
                                <div class="tab-pane p-3" id="filter" role="tabpanel">
                                    <div style="margin-bottom: 30px;">
                                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                            <button type="button" class="btn btn-primary waves-effect waves-light filter-category-btn" data-category="skill">Skill</button>
                                            <button type="button" class="btn btn-info waves-effect waves-light filter-category-btn" data-category="ppid">PPID</button>
                                            <button type="button" class="btn btn-success waves-effect waves-light filter-category-btn" data-category="halo">Halo PST</button>
                                        </div>
                                    </div>

                                    <!-- Skill Filter -->
                                    <div id="skillContent" style="display: none; margin-bottom: 30px;">
                                        <h6 style="margin-bottom: 15px; color: #2c3e50; font-weight: 600;">Pilih Skill:</h6>
                                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                            <?php foreach ($allSkills as $skill): ?>
                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light filter-btn" data-filter-type="skill" data-filter-id="<?= $skill['id_skill']; ?>" data-filter-name="<?= htmlspecialchars($skill['nama_skill']); ?>">
                                                <?= htmlspecialchars($skill['nama_skill']); ?>
                                            </button>
                                            <?php endforeach; ?>
                                            <?php if (count($allSkills) === 0): ?>
                                            <p class="text-muted">Tidak ada skill tersedia</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- PPID Filter -->
                                    <div id="ppidContent" style="display: none; margin-bottom: 30px;">
                                        <h6 style="margin-bottom: 15px; color: #2c3e50; font-weight: 600;">Pilih PPID:</h6>
                                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                            <?php foreach ($allPPIDs as $ppid): ?>
                                            <button type="button" class="btn btn-outline-info waves-effect waves-light filter-btn" data-filter-type="ppid" data-filter-id="<?= $ppid['id_ppid']; ?>" data-filter-name="<?= htmlspecialchars($ppid['nama_ppid']); ?>">
                                                <?= htmlspecialchars($ppid['nama_ppid']); ?>
                                            </button>
                                            <?php endforeach; ?>
                                            <?php if (count($allPPIDs) === 0): ?>
                                            <p class="text-muted">Tidak ada PPID tersedia</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Halo PST Filter -->
                                    <div id="haloContent" style="display: none; margin-bottom: 30px;">
                                        <h6 style="margin-bottom: 15px; color: #2c3e50; font-weight: 600;">Pilih Halo PST:</h6>
                                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                            <?php foreach ($allHalos as $halo): ?>
                                            <button type="button" class="btn btn-outline-success waves-effect waves-light filter-btn" data-filter-type="halo" data-filter-id="<?= $halo['id_halo_pst']; ?>" data-filter-name="<?= htmlspecialchars($halo['nama_halo_pst']); ?>">
                                                <?= htmlspecialchars($halo['nama_halo_pst']); ?>
                                            </button>
                                            <?php endforeach; ?>
                                            <?php if (count($allHalos) === 0): ?>
                                            <p class="text-muted">Tidak ada Halo PST tersedia</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Selected Filter Display -->
                                    <div class="row mb-4" id="selectedFilterDiv" style="display: none;">
                                        <div class="col-md-12">
                                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                <span id="selectedFilterText"></span>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- User Cards Display -->
                                    <div class="row users-card" id="usersContainer">
                                        <div class="col-12 text-center text-muted">
                                            <p>Pilih salah satu filter di atas untuk menampilkan anggota tim</p>
                                        </div>
                                    </div>
                                </div>

</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include_once("layout.php");

// Consolidated JavaScript block
$script = <<<'JS'
<script src="assets/pages/data-table/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/pages/data-table/extensions/buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/pages/data-table/extensions/buttons/js/buttons.html5.min.js"></script>
<script src="assets/pages/data-table/extensions/buttons/js/buttons.print.min.js"></script>
<script src="assets/pages/data-table/extensions/buttons/js/buttons.colVis.min.js"></script>
<script src="bower_components/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script>
$(function() {
    // Category button click handler
    $(document).on("click", ".filter-category-btn", function() {
        var category = $(this).data("category");
        var contentId = "#" + category + "Content";
        $("#skillContent, #ppidContent, #haloContent").slideUp(300);
        $(contentId).slideDown(300);
    });

    // Filter button click handler
    $(document).on("click", ".filter-btn", function() {
        var filterType = $(this).data("filter-type");
        var filterId = $(this).data("filter-id");
        var filterName = $(this).data("filter-name");
        $("#usersContainer").html("<div class=\"col-12 text-center\"><p><i class=\"fa fa-spinner fa-spin\"></i> Memuat data...</p></div>");
        $("#selectedFilterDiv").hide();
        
        $.ajax({
            url: "struktur_humas.php",
            method: "GET",
            data: { 
                filterType: filterType, 
                filterId: filterId, 
                ajax: 1 
            },
            dataType: "json",
            timeout: 5000,
            success: function(response) {
                if (response.error) {
                    console.error("Server error:", response.error);
                    $("#usersContainer").html("<div class=\"col-12 text-center\"><p class=\"text-danger\">Error: " + response.error + "</p></div>");
                    return;
                }
                if (!response || response.length === 0) {
                    $("#usersContainer").html("<div class=\"col-12 text-center\"><p class=\"text-muted\">Tidak ada anggota tim dengan filter ini</p></div>");
                } else {
                    var html = "";
                    response.forEach(function(user) {
                        var foto = user.foto ? "../uploads/" + user.foto : "../images/noimages.jpg";
                        var jabatan = user.nama_jabatan || "-";
                        html += "<div class=\"col-lg-3 col-md-4 col-sm-6 mb-3\">";
                        html += "  <div class=\"card rounded-card user-card\">";
                        html += "    <div class=\"card-block\">";
                        html += "      <div class=\"img-hover avatar-wrapper\" style=\"text-align: center;\">";
                        html += "        <img src=\"" + foto + "\" class=\"avatar-img\" alt=\"" + user.nama + "\" style=\"max-height: 200px; width: 100%; object-fit: cover;\">";
                        html += "      </div>";
                        html += "      <h6 class=\"mt-3 mb-1\" style=\"text-align: center;\">" + user.nama + "</h6>";
                        html += "      <p class=\"text-muted text-center\" style=\"font-size: 12px;\">" + jabatan + "</p>";
                        html += "      <p class=\"text-center\" style=\"font-size: 12px;\"><i class=\"fa fa-envelope\"></i> " + user.email + "</p>";
                        html += "      <p class=\"text-center\" style=\"font-size: 12px;\"><i class=\"fa fa-phone\"></i> 0" + user.nomor_telepon + "</p>";
                        html += "    </div>";
                        html += "  </div>";
                        html += "</div>";
                    });
                    $("#usersContainer").html(html);
                    
                    var categoryName = filterType === "skill" ? "Skill" : (filterType === "ppid" ? "PPID" : "Halo PST");
                    $("#selectedFilterText").text("Filter: " + categoryName + " - " + filterName);
                    $("#selectedFilterDiv").show();
                }
            },
            error: function(xhr, status, error) { 
                console.error("AJAX Error - Status:", status, "Error:", error);
                console.error("Response text:", xhr.responseText);
                var msg = error || "Unknown error";
                if (xhr.status === 0) {
                    msg = "Network error or CORS issue";
                }
                $("#usersContainer").html("<div class=\"col-12 text-center\"><p class=\"text-danger\">Error: " + msg + "</p></div>"); 
            }
        });
    });
});

// Edit Canva modal handlers
$(document).on("click", "#editCanvaBtn", function() {
    var id = $(this).data('id');
    var link = $(this).data('link') || '';
    $('#edit_id_media').val(id);
    $('#edit_link').val(link);
    $('#editCanvaModal').modal('show');
});

$(document).on('click', '#saveCanvaBtn', function() {
    var id = $('#edit_id_media').val();
    var link = $('#edit_link').val();
    if (!link) { alert('Link tidak boleh kosong'); return; }
    $.ajax({
        url: 'struktur_humas.php',
        method: 'POST',
        data: { action: 'update_canva_link', id_media: id, link: link },
        dataType: 'json',
        success: function(res) {
            if (res && res.success) {
                $('#editCanvaModal').modal('hide');
                location.reload();
            } else {
                alert(res.message || 'Gagal menyimpan perubahan');
            }
        },
        error: function() { alert('Terjadi kesalahan saat menyimpan'); }
    });
});
</script>
JS;

renderLayout($pageContent, $script);
?>

