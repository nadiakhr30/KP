<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// Get sub_jenis for "Brankas Humas"
$currentSub = null;
$subId = 0;

// First try to find existing sub_jenis
$qSub = mysqli_query($koneksi, "SELECT s.* FROM sub_jenis s JOIN jenis j ON s.id_jenis = j.id_jenis WHERE j.nama_jenis = 'Brankas Humas' LIMIT 1");

if ($qSub && mysqli_num_rows($qSub) > 0) {
    $currentSub = mysqli_fetch_assoc($qSub);
    $subId = $currentSub['id_sub_jenis'];
} else {
    // If jenis doesn't exist, create it first
    $qJenis = mysqli_query($koneksi, "SELECT id_jenis FROM jenis WHERE nama_jenis = 'Brankas Humas'");
    $idJenis = 0;
    
    if (!$qJenis || mysqli_num_rows($qJenis) === 0) {
        // Create jenis
        $insertJenis = mysqli_query($koneksi, "INSERT INTO jenis (nama_jenis) VALUES ('Brankas Humas')");
        if ($insertJenis) {
            $idJenis = mysqli_insert_id($koneksi);
        }
    } else {
        $rJenis = mysqli_fetch_assoc($qJenis);
        $idJenis = $rJenis['id_jenis'];
    }
    
    // Now create sub_jenis
    if ($idJenis > 0) {
        $insertSub = mysqli_query($koneksi, "INSERT INTO sub_jenis (id_jenis, nama_sub_jenis) VALUES ($idJenis, 'Brankas Humas')");
        if ($insertSub) {
            $subId = mysqli_insert_id($koneksi);
            $qSub = mysqli_query($koneksi, "SELECT s.* FROM sub_jenis s WHERE s.id_sub_jenis = $subId");
            if ($qSub && mysqli_num_rows($qSub) > 0) {
                $currentSub = mysqli_fetch_assoc($qSub);
            }
        }
    }
}

// Fetch media for this sub_jenis (should be only one)
$media = null;
if ($subId > 0) {
    $qMedia = mysqli_query($koneksi, "
        SELECT m.id_media, m.judul, m.topik, m.deskripsi, m.link, s.nama_sub_jenis
        FROM media m
        INNER JOIN sub_jenis s ON m.id_sub_jenis = s.id_sub_jenis
        WHERE m.id_sub_jenis = $subId
        LIMIT 1
    ");
    if (mysqli_num_rows($qMedia) > 0) {
        $media = mysqli_fetch_assoc($qMedia);
    }
}

// Helper function to detect link type and return preview info
function getLinkPreview($link) {
    $preview = [
        'type' => 'link',
        'icon' => 'ti-link',
        'preview' => null
    ];
    
    if (empty($link)) return $preview;
    
    // Detect Google Drive folder
    if (strpos($link, 'drive.google.com/drive/folders/') !== false) {
        $preview['type'] = 'gdrive_folder';
        $preview['icon'] = 'ti-folder';
        return $preview;
    }
    
    // Detect Google Drive file
    if (strpos($link, 'drive.google.com') !== false) {
        // Extract file ID
        if (preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $link, $matches)) {
            $fileId = $matches[1];
            $preview['type'] = 'gdrive_file';
            $preview['preview'] = 'https://drive.google.com/thumbnail?id=' . $fileId . '&sz=w200';
            return $preview;
        }
    }
    
    // Detect image URLs
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $parsedUrl = parse_url($link);
    $path = isset($parsedUrl['path']) ? strtolower($parsedUrl['path']) : '';
    
    foreach ($imageExtensions as $ext) {
        if (strpos($path, '.' . $ext) !== false) {
            $preview['type'] = 'image';
            $preview['preview'] = $link;
            return $preview;
        }
    }
    
    return $preview;
}
?>
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Manajemen Brankas Humas</h5>
                        <p class="m-b-0">Untuk mengelola brankas humas.</p>
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
                            <a href="">Brankas Humas</a>
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
                    <!-- Preview Only View -->
                    <div class="p-5">
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <div class="card position-relative" style="min-height: 400px; display: flex; align-items: center; justify-content: center;">
                                    <!-- Action Buttons in Top Right Corner -->
                                    <div style="position: absolute; top: 15px; right: 15px; z-index: 10; display: flex; gap: 8px;">
                                        <?php if (!$media || empty($media['link'])): ?>
                                            <a href="tambah/tambah_media.php?sub=<?= $subId; ?>" class="btn btn-success btn-icon waves-effect waves-light" title="Tambah Link">
                                                <i class="ti-plus"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="edit/edit_media.php?id=<?= $media['id_media']; ?>" class="btn btn-warning btn-icon waves-effect waves-light" title="Update">
                                                <i class="ti-pencil"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                    <div class="card-block" style="width: 100%; text-align: center;">
                                        <?php if ($media && !empty($media['link'])): ?>
                                            <?php $linkPreview = getLinkPreview($media['link']); ?>
                                            <a href="<?= htmlspecialchars($media['link']); ?>" target="_blank" style="display: flex; align-items: center; justify-content: center; height: 350px; text-decoration: none; cursor: pointer;" class="preview-container">
                                                <?php if ($linkPreview['type'] === 'image' && $linkPreview['preview']): ?>
                                                    <img src="<?= htmlspecialchars($linkPreview['preview']); ?>" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                <?php elseif ($linkPreview['type'] === 'gdrive_file' && $linkPreview['preview']): ?>
                                                    <img src="<?= htmlspecialchars($linkPreview['preview']); ?>" alt="GDrive File" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                <?php elseif ($linkPreview['type'] === 'gdrive_folder'): ?>
                                                    <i class="ti-folder" style="font-size: 96px; color: #FFB84D;"></i>
                                                <?php else: ?>
                                                    <i class="<?= $linkPreview['icon']; ?>" style="font-size: 96px; color: #999;"></i>
                                                <?php endif; ?>
                                            </a>
                                        <?php else: ?>
                                            <div style="display: flex; align-items: center; justify-content: center; height: 350px;">
                                                <div style="text-align: center;">
                                                    <i class="ti-link" style="font-size: 96px; color: #ddd; margin-bottom: 20px;"></i>
                                                    <p style="color: #999; font-size: 16px;">Belum ada konten</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
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
$content = ob_get_clean();
ob_start();
?>
<script>
// Minimal script - no delete functionality needed
</script>
<style>
.preview-container:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
?>
