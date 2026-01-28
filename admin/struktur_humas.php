<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// Fetch all skills
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

$filterType = isset($_GET['filterType']) ? $_GET['filterType'] : '';
$filterId = isset($_GET['filterId']) ? (int)$_GET['filterId'] : 0;

$filteredUsers = [];
if ($filterType && $filterId > 0) {
    if ($filterType === 'skill') {
        $qUsers = mysqli_query($koneksi, "
            SELECT DISTINCT u.nip, u.nama, u.email, u.nomor_telepon, u.foto_profil as foto, j.nama_jabatan
            FROM user u
            LEFT JOIN jabatan j ON u.id_jabatan = j.id_jabatan
            JOIN user_skill us ON u.nip = us.nip
            WHERE us.id_skill = $filterId AND u.status = 1
            ORDER BY u.nama
        ");
    } elseif ($filterType === 'ppid') {
        $qUsers = mysqli_query($koneksi, "
            SELECT DISTINCT u.nip, u.nama, u.email, u.nomor_telepon, u.foto_profil as foto, j.nama_jabatan
            FROM user u
            LEFT JOIN jabatan j ON u.id_jabatan = j.id_jabatan
            WHERE u.id_ppid = $filterId AND u.status = 1
            ORDER BY u.nama
        ");
    } elseif ($filterType === 'halo') {
        $qUsers = mysqli_query($koneksi, "
            SELECT DISTINCT u.nip, u.nama, u.email, u.nomor_telepon, u.foto_profil as foto, j.nama_jabatan
            FROM user u
            LEFT JOIN jabatan j ON u.id_jabatan = j.id_jabatan
            JOIN user_halo_pst hu ON u.nip = hu.nip
            WHERE hu.id_halo_pst = $filterId AND u.status = 1
            ORDER BY u.nama
        ");
    }
    
    if ($qUsers) {
        while ($row = mysqli_fetch_assoc($qUsers)) {
            $filteredUsers[] = $row;
        }
    }
}

// If JSON request (AJAX), return users
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    header('Content-Type: application/json');
    echo json_encode($filteredUsers);
    exit;
}

// Render with layout
$content = ob_get_clean();
ob_start();
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Struktur Organisasi Humas</h5>
                        <p class="m-b-0">Untuk menampilkan struktur organisasi dan tim dalam sistem kehumasan.</p>
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
                    <ul class="nav nav-tabs tabs card-block" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#galeri" role="tab"><i class="ti-image"></i> Galeri Struktur</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#filter" role="tab"><i class="ti-filter"></i> Filter Tim</a>
                        </li>
                    </ul>
                    <div class="tab-content tabs card">
                        <!-- TAB 1: GALERI STRUKTUR -->
                        <div class="tab-pane p-5 active" id="galeri" role="tabpanel">
                            <div style="padding: 30px;">
                                <div class="img-container" style="text-align: center;">
                                    <?php 
                                    // Check if organigram image exists
                                    $organigram_path = '../uploads/organigram.jpg';
                                    if (file_exists($organigram_path)) {
                                        echo '<a href="' . htmlspecialchars($organigram_path) . '" class="glightbox" data-gallery="organigram">';
                                        echo '<img src="' . htmlspecialchars($organigram_path) . '" alt="Organigram" class="img-fluid" style="max-height: 600px; cursor: pointer;">';
                                        echo '</a>';
                                    } else {
                                        echo '<div class="alert alert-info" role="alert">';
                                        echo '<i class="fa fa-image"></i> File organigram belum tersedia. Silakan upload file gambar organigram dengan nama "organigram.jpg" ke folder uploads.';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: FILTER TIM -->
                        <div class="tab-pane p-5" id="filter" role="tabpanel">
                            <div style="margin-bottom: 30px;">
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <button type="button" class="btn btn-primary waves-effect waves-light filter-category-btn" data-category="skill">
                                        Skill
                                    </button>
                                    <button type="button" class="btn btn-info waves-effect waves-light filter-category-btn" data-category="ppid">
                                        PPID
                                    </button>
                                    <button type="button" class="btn btn-success waves-effect waves-light filter-category-btn" data-category="halo">
                                        Halo PST
                                    </button>
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

// Include layout function
include_once("layout.php");

// JavaScript for filter functionality
$script = '
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
        
        // Hide all filter content
        $("#skillContent, #ppidContent, #haloContent").slideUp(300);
        
        // Show selected filter content
        $(contentId).slideDown(300);
    });
    
    // Filter button click handler
    $(document).on("click", ".filter-btn", function() {
        var filterType = $(this).data("filter-type");
        var filterId = $(this).data("filter-id");
        var filterName = $(this).data("filter-name");
        
        // Show loading state
        $("#usersContainer").html("<div class=\"col-12 text-center\"><p><i class=\"fa fa-spinner fa-spin\"></i> Memuat data...</p></div>");
        
        // Fetch users via AJAX
        $.ajax({
            url: "struktur_humas.php",
            method: "GET",
            data: {
                filterType: filterType,
                filterId: filterId,
                ajax: 1
            },
            dataType: "json",
            success: function(users) {
                if (users.length === 0) {
                    $("#usersContainer").html("<div class=\"col-12 text-center\"><p class=\"text-muted\">Tidak ada anggota tim dengan filter ini</p></div>");
                } else {
                    var html = "";
                    users.forEach(function(user) {
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
                        html += "      <p class=\"text-center\" style=\"font-size: 12px;\"><i class=\"fa fa-phone\"></i> " + 0 + user.nomor_telepon + "</p>";
                        html += "    </div>";
                        html += "  </div>";
                        html += "</div>";
                    });
                    $("#usersContainer").html(html);
                }
                
                // Show filter info
                var categoryName = filterType === "skill" ? "Skill" : (filterType === "ppid" ? "PPID" : "Halo PST");
                $("#selectedFilterText").text("Filter: " + categoryName + " - " + filterName);
                $("#selectedFilterDiv").show();
            },
            error: function() {
                $("#usersContainer").html("<div class=\"col-12 text-center\"><p class=\"text-danger\">Terjadi kesalahan saat memuat data</p></div>");
            }
        });
    });
});
</script>
';

renderLayout($pageContent, $script);
?>
