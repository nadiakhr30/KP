<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// Get sub_jenis from URL parameter, default to first one for jenis 'Laporan'
$subId = isset($_GET['sub']) ? (int)$_GET['sub'] : 0;

// Fetch all sub_jenis for jenis 'Laporan'
$qSub = mysqli_query($koneksi, "SELECT s.* FROM sub_jenis s JOIN jenis j ON s.id_jenis = j.id_jenis WHERE j.nama_jenis = 'Laporan' ORDER BY s.nama_sub_jenis");
$allSub = [];
while ($row = mysqli_fetch_assoc($qSub)) {
    $allSub[] = $row;
}

// If no sub selected, use first one
if ($subId === 0 && count($allSub) > 0) {
    $subId = $allSub[0]['id_sub_jenis'];
}

// Get current sub info
$currentSub = null;
if ($subId > 0) {
    $qCurrent = mysqli_query($koneksi, "SELECT * FROM sub_jenis WHERE id_sub_jenis = $subId");
    if (mysqli_num_rows($qCurrent) > 0) {
        $currentSub = mysqli_fetch_assoc($qCurrent);
    }
}

// Fetch media based on sub_jenis
$dataMedia = [];
if ($subId > 0) {
    $qMedia = mysqli_query($koneksi, "
        SELECT m.id_media, m.judul, m.topik, m.deskripsi, m.link, s.nama_sub_jenis
        FROM media m
        INNER JOIN sub_jenis s ON m.id_sub_jenis = s.id_sub_jenis
        WHERE m.id_sub_jenis = $subId
        ORDER BY m.judul
    ");
    while ($row = mysqli_fetch_assoc($qMedia)) {
        $dataMedia[] = $row;
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
    
    // Detect Google Drive file (handle /d/ID and ?id=ID patterns)
    if (strpos($link, 'drive.google.com') !== false || strpos($link, 'drive.googleusercontent.com') !== false) {
        $fileId = null;
        if (preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $link, $m)) {
            $fileId = $m[1];
        } elseif (preg_match('/[?&]id=([a-zA-Z0-9-_]+)/', $link, $m)) {
            $fileId = $m[1];
        }
        if ($fileId) {
            $preview['type'] = 'gdrive_file';
            $preview['preview'] = 'https://drive.google.com/uc?export=view&id=' . $fileId;
            return $preview;
        }
    }
    
    // Detect image URLs by extension (path) or common direct image domains
    $imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.bmp', '.svg'];
    $parsedUrl = parse_url($link);
    $path = isset($parsedUrl['path']) ? strtolower($parsedUrl['path']) : '';

    foreach ($imageExtensions as $ext) {
        if ($ext !== '' && strpos($path, $ext) !== false) {
            $preview['type'] = 'image';
            $preview['preview'] = $link;
            return $preview;
        }
    }

    // Some CDN/image host links may not have extension in path; try common patterns
    $hostsThatMayBeImages = ['imgur.com', 'images.unsplash.com', 'cdn.jsdelivr.net', 'cloudinary.com'];
    $host = isset($parsedUrl['host']) ? strtolower($parsedUrl['host']) : '';
    foreach ($hostsThatMayBeImages as $h) {
        if ($host !== '' && strpos($host, $h) !== false) {
            $preview['type'] = 'image';
            $preview['preview'] = $link;
            return $preview;
        }
    }
    
    return $preview;
}

// Group media by 'topik' for card view
    $groupedMedia = [];
    foreach ($dataMedia as $m) {
        $topicKey = trim($m['topik']) !== '' ? $m['topik'] : 'Tanpa Topik';
        if (!isset($groupedMedia[$topicKey])) $groupedMedia[$topicKey] = [];
        $groupedMedia[$topicKey][] = $m;
    }
?>
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Manajemen Laporan <?= $currentSub ? htmlspecialchars($currentSub['nama_sub_jenis']) : ''; ?></h5>
                        <p class="m-b-0">Untuk mengelola laporan <?= $currentSub ? htmlspecialchars($currentSub['nama_sub_jenis']) : ''; ?>.</p>
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
                            <a href="">Laporan</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="laporan.php?sub=<?= $subId; ?>"><?= $currentSub ? htmlspecialchars($currentSub['nama_sub_jenis']) : 'Laporan'; ?></a>
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
                    <!-- Tabs -->
                    <ul class="nav nav-tabs tabs card-block" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#card" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#table" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                        </li>
                    </ul>
                    <div class="tab-content tabs card">
                        <!-- Card View -->
                        <div class="tab-pane p-5 active" id="card" role="tabpanel">
                            <div class="row m-b-10">
                                <div class="col-6">
                                    <div class="dropdown-info dropdown open">
                                        <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak2" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                        <div class="dropdown-menu" aria-labelledby="cetak2" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                            <a class="dropdown-item waves-light waves-effect" href="export/export_media.php?sub=<?= $subId; ?>&format=print">Print</a>
                                            <a class="dropdown-item waves-light waves-effect" href="export/export_media.php?sub=<?= $subId; ?>&format=excel">Excel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="align-items-right" style="float: right;">
                                        <a href="tambah/tambah_media.php?sub=<?= $subId; ?>" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row users-card">
                                <?php if (count($dataMedia) === 0): ?>
                                    <div class="col-12 text-center">
                                        <p><?= $currentSub ? 'Tidak ada laporan untuk ' . htmlspecialchars($currentSub['nama_sub_jenis']) : 'Pilih sub jenis terlebih dahulu'; ?></p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($groupedMedia as $topic => $items): ?>
                                        <div class="col-12 mb-3">
                                            <div class="card">
                                                <div class="card-header py-2 px-3">
                                                    <h6 class="mb-0" style="font-weight:700;"><?= htmlspecialchars($topic); ?></h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <?php foreach ($items as $media): ?>
                                                            <?php $linkPreview = getLinkPreview($media['link']); ?>
                                                            <div class="col-lg-4 col-xl-3 col-md-6">
                                                                <div class="card rounded-card user-card">
                                                                    <div class="card-block">
                                                                        <!-- Preview Section - Clickable -->
                                                                        <a href="<?= htmlspecialchars($media['link']); ?>" target="_blank" style="display: block; text-decoration: none; cursor: pointer;">
                                                                            <div style="margin-bottom: 15px; min-height: 120px; background: #f5f5f5; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;" class="preview-container">
                                                                                <?php if ($linkPreview['type'] === 'image' && $linkPreview['preview']): ?>
                                                                                    <img src="<?= htmlspecialchars($linkPreview['preview']); ?>" alt="Preview" style="max-width: 100%; max-height: 120px; object-fit: cover;">
                                                                                <?php elseif ($linkPreview['type'] === 'gdrive_file' && $linkPreview['preview']): ?>
                                                                                    <img src="<?= htmlspecialchars($linkPreview['preview']); ?>" alt="GDrive File" style="max-width: 100%; max-height: 120px; object-fit: cover;">
                                                                                <?php elseif ($linkPreview['type'] === 'gdrive_folder'): ?>
                                                                                    <i class="ti-folder" style="font-size: 48px; color: #FFB84D;"></i>
                                                                                <?php else: ?>
                                                                                    <i class="<?= $linkPreview['icon']; ?>" style="font-size: 48px; color: #999;"></i>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </a>
                                                                        
                                                                        <div class="user-content">
                                                                            <h4><?= htmlspecialchars($media['judul']); ?></h4>
                                                                            <p style="font-size: 12px; margin-bottom: 10px; min-height: 40px;">
                                                                                <?= htmlspecialchars(substr($media['deskripsi'], 0, 100)); ?><?= strlen($media['deskripsi']) > 100 ? '...' : ''; ?>
                                                                            </p>
                                                                        </div>
                                                                        <div style="margin-top: 15px; display: flex; gap: 8px;">
                                                                            <a href="edit/edit_media.php?id=<?= $media['id_media']; ?>" class="btn btn-icon btn-primary waves-effect waves-light flex-fill"><i class="ti-pencil"></i></a>
                                                                            <button type="button" class="btn btn-icon btn-danger waves-effect waves-light flex-fill" onclick="deleteMedia(<?= $media['id_media']; ?>, '<?= htmlspecialchars($media['judul']); ?>')"><i class="ti-trash"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Table View -->
                        <div class="tab-pane p-4" id="table" role="tabpanel">
                            <div class="card-block">
                                <div class="row m-b-10">
                                    <div class="col-6">
                                        <div class="dropdown-info dropdown open">
                                            <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                            <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                <a class="dropdown-item waves-light waves-effect" href="export/export_media.php?sub=<?= $subId; ?>&format=print">Print</a>
                                                <a class="dropdown-item waves-light waves-effect" href="export/export_media.php?sub=<?= $subId; ?>&format=excel">Excel</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="align-items-right" style="float: right;">
                                            <a href="tambah/tambah_media.php?sub=<?= $subId; ?>" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="dt-responsive table-responsive">
                                    <table id="order-table" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Judul</th>
                                                <th>Topik</th>
                                                <th>Deskripsi</th>
                                                <th>Link</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($dataMedia) === 0): ?>
                                            <tr>
                                              <td colspan="6" class="text-center"><?= $currentSub ? 'Tidak ada laporan untuk ' . htmlspecialchars($currentSub['nama_sub_jenis']) : 'Pilih sub jenis terlebih dahulu'; ?></td>
                                            </tr>
                                            <?php else: ?>
                                            <?php foreach ($dataMedia as $media) : ?>
                                            <tr>
                                              <td><?= $media['id_media']; ?></td>
                                              <td><?= htmlspecialchars($media['judul']); ?></td>
                                              <td><?= htmlspecialchars($media['topik']); ?></td>
                                              <td><?= htmlspecialchars(substr($media['deskripsi'], 0, 100)); ?><?= strlen($media['deskripsi']) > 100 ? '...' : ''; ?></td>
                                              <td>
                                                <?php if ($media['link']): ?>
                                                  <a href="<?= htmlspecialchars($media['link']); ?>" target="_blank"><?= htmlspecialchars(substr($media['link'], 0, 30)); ?></a>
                                                <?php else: ?>
                                                  <span class="badge bg-secondary">-</span>
                                                <?php endif; ?>
                                              </td>
                                              <td>
                                                <a href="edit/edit_media.php?id=<?= $media['id_media']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
                                                  <i class="ti-pencil text-dark"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn waves-effect waves-light btn-danger btn-icon"
                                                        onclick="deleteMedia(<?= $media['id_media']; ?>, '<?= htmlspecialchars($media['judul']); ?>')"
                                                        title="Hapus">
                                                   <i class="ti-trash text-dark"></i>
                                                </button>
                                              </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Judul</th>
                                                <th>Deskripsi</th>
                                                <th>Link</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; background: #fcf2f2; border-left: 5px solid #e74c3c;">
            <div class="modal-body" style="padding: 40px 30px; text-align: center;">
                <div style="margin-bottom: 20px;">
                    <i class="ti-alert" style="font-size: 56px; color: #e74c3c;"></i>
                </div>
                <h5 style="color: #2c3e50; font-weight: 700; font-size: 18px; margin-bottom: 10px;">Konfirmasi Hapus</h5>
                <p style="font-size: 14px; color: #7f8c8d; margin-bottom: 20px;">Apakah Anda yakin ingin menghapus laporan <strong id="deleteMediaName"></strong>?</p>
                <p style="color: #e74c3c; font-size: 12px; margin-top: 20px; margin-bottom: 30px;">
                    <i class="ti-alert-alt" style="margin-right: 6px;"></i>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <input type="hidden" id="deleteMediaId" value="">
                <div style="display: flex; justify-content: center; gap: 15px;">
                    <button type="button" class="btn btn-secondary btn-icon waves-effect waves-light" data-dismiss="modal" title="Batal">
                        <i class="ti-close"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-icon waves-effect waves-light" id="confirmDelete" title="Hapus">
                        <i class="ti-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteMedia(id, namaMedia) {
    document.getElementById('deleteMediaId').value = id;
    document.getElementById('deleteMediaName').textContent = namaMedia;
    $('#deleteModal').modal('show');
}

document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            const id = document.getElementById('deleteMediaId').value;

            // Close the modal first
            $('#deleteModal').modal('hide');
            
            // Perform deletion
            fetch('hapus/hapus_media.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: data.status === 'success' ? 'success' : 'error',
                        title: data.status === 'success' ? 'Berhasil!' : 'Gagal!',
                        text: data.message,
                        confirmButtonColor: data.status === 'success' ? '#3085d6' : '#d33',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed && data.status === 'success') {
                            location.reload();
                        }
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus data',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
        });
    }
});
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
