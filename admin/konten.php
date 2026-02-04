<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// Get jenis from URL parameter
$jenisName = isset($_GET['jenis']) ? trim($_GET['jenis']) : '';
$subId = isset($_GET['sub']) ? (int)$_GET['sub'] : 0;

// Fetch jenis info
$jenis = null;
if (!empty($jenisName)) {
    $qJenis = mysqli_query($koneksi, "SELECT * FROM jenis WHERE nama_jenis = '" . mysqli_real_escape_string($koneksi, $jenisName) . "'");
    if (mysqli_num_rows($qJenis) > 0) {
        $jenis = mysqli_fetch_assoc($qJenis);
    }
}

// If no jenis found, redirect to dashboard
if (!$jenis) {
    header('Location: index.php');
    exit();
}

// Fetch all sub_jenis for this jenis
$qSub = mysqli_query($koneksi, "SELECT s.* FROM sub_jenis s WHERE s.id_jenis = " . (int)$jenis['id_jenis'] . " ORDER BY s.nama_sub_jenis");
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

    $parsedUrl = parse_url($link);
    $host = isset($parsedUrl['host']) ? strtolower($parsedUrl['host']) : '';

    // If it's already a direct driveusercontent image link, use it
    if (strpos($host, 'drive.googleusercontent.com') !== false) {
        $preview['type'] = 'image';
        $preview['preview'] = $link;
        return $preview;
    }

    // Google Drive folder
    if (strpos($link, 'drive.google.com/drive/folders/') !== false) {
        $preview['type'] = 'gdrive_folder';
        $preview['icon'] = 'ti-folder';
        return $preview;
    }

    // Google Drive file: try several common patterns to extract file ID
    if (strpos($host, 'drive.google.com') !== false) {
        $fileId = null;
        // Pattern: /file/d/ID or /d/ID
        if (preg_match('#/(?:file/d|d)/([a-zA-Z0-9_-]+)#', $link, $m)) {
            $fileId = $m[1];
        }
        // Pattern: ?id=ID or &id=ID
        if (!$fileId && preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $link, $m)) {
            $fileId = $m[1];
        }
        // Pattern: /open?id=ID
        if (!$fileId && preg_match('#/open\?id=([a-zA-Z0-9_-]+)#', $link, $m)) {
            $fileId = $m[1];
        }

        if ($fileId) {
            $preview['type'] = 'gdrive_file';
            // Use proven Drive preview URL (no slow HTTP checks)
            $preview['preview'] = 'https://drive.google.com/uc?export=view&id=' . $fileId;
            return $preview;
        }
    }

    // YouTube links: extract video ID and provide thumbnail
    if (strpos($host, 'youtube.com') !== false || strpos($host, 'youtu.be') !== false) {
        $videoId = null;
        if (preg_match('/v=([a-zA-Z0-9_-]{6,})/', $link, $m)) {
            $videoId = $m[1];
        }
        if (!$videoId && preg_match('#youtu\.be/([a-zA-Z0-9_-]+)#', $link, $m)) {
            $videoId = $m[1];
        }
        if (!$videoId && preg_match('#/embed/([a-zA-Z0-9_-]+)#', $link, $m)) {
            $videoId = $m[1];
        }
        if (!$videoId && preg_match('#/shorts/([a-zA-Z0-9_-]+)#', $link, $m)) {
            $videoId = $m[1];
        }

        if ($videoId) {
            $preview['type'] = 'youtube';
            $preview['preview'] = 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg';
            $preview['video_id'] = $videoId;
            return $preview;
        }
    }

    // Detect image URLs by extension (path)
    $imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.bmp', '.svg'];
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
                        <h5 class="m-b-10">Manajemen <?= htmlspecialchars($jenis['nama_jenis']) ?> <?= $currentSub ? htmlspecialchars($currentSub['nama_sub_jenis']) : ''; ?></h5>
                        <p class="m-b-0">Untuk mengelola <?= htmlspecialchars($jenis['nama_jenis']) ?> <?= $currentSub ? htmlspecialchars($currentSub['nama_sub_jenis']) : ''; ?>.</p>
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
                            <a href=""><?= htmlspecialchars($jenis['nama_jenis']) ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="konten.php?jenis=<?= urlencode($jenis['nama_jenis']) ?>&sub=<?= $subId; ?>"><?= $currentSub ? htmlspecialchars($currentSub['nama_sub_jenis']) : htmlspecialchars($jenis['nama_jenis']); ?></a>
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
                                        <p><?= $currentSub ? 'Tidak ada konten untuk ' . htmlspecialchars($currentSub['nama_sub_jenis']) : 'Pilih sub jenis terlebih dahulu'; ?></p>
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
                                                                        <div style="margin-bottom: 10px; min-height: 120px; background: #f5f5f5; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;" class="preview-container">
                                                                            <?php if ($linkPreview['type'] === 'image' && $linkPreview['preview']): ?>
                                                                                <a href="<?= htmlspecialchars($media['link']); ?>" target="_blank" style="display: block; width: 100%; height: 100%; text-decoration: none;">
                                                                                    <img src="<?= htmlspecialchars($linkPreview['preview']); ?>" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.parentElement.innerHTML='<i class=\'ti-image\' style=\'font-size: 48px; color: #999;\'></i>';">
                                                                                </a>
                                                                            <?php elseif ($linkPreview['type'] === 'gdrive_file' && $linkPreview['preview']): ?>
                                                                                <a href="<?= htmlspecialchars($media['link']); ?>" target="_blank" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; text-decoration: none;">
                                                                                    <img src="<?= htmlspecialchars($linkPreview['preview']); ?>" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.parentElement.innerHTML='<i class=\'ti-link\' style=\'font-size: 48px; color: #999;\'></i>';">
                                                                                </a>
                                                                            <?php elseif ($linkPreview['type'] === 'youtube' && !empty($linkPreview['preview'])): ?>
                                                                                <a href="#" onclick="openVideoLightbox('<?= $linkPreview['video_id']; ?>'); return false;" style="display: block; width:100%; height:100%; text-decoration: none;">
                                                                                    <div style="position:relative; width:100%; height:100%;">
                                                                                        <img src="<?= htmlspecialchars($linkPreview['preview']); ?>" alt="YouTube Preview" style="width:100%; height:100%; object-fit:cover;" onerror="this.parentElement.innerHTML='<i class=\'ti-youtube\' style=\'font-size:48px;color:#e74c3c;\'></i>';">
                                                                                        <span style="position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); font-size:48px; color: rgba(255,255,255,0.9);"><i class="fa fa-play-circle"></i></span>
                                                                                    </div>
                                                                                </a>
                                                                            <?php elseif ($linkPreview['type'] === 'gdrive_folder'): ?>
                                                                                <a href="<?= htmlspecialchars($media['link']); ?>" target="_blank" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; text-decoration: none;">
                                                                                    <i class="ti-folder" style="font-size: 48px; color: #FFB84D;"></i>
                                                                                </a>
                                                                            <?php else: ?>
                                                                                <a href="<?= htmlspecialchars($media['link']); ?>" target="_blank" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; text-decoration: none;">
                                                                                    <i class="<?= $linkPreview['icon']; ?>" style="font-size: 48px; color: #999;"></i>
                                                                                </a>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        
                                                                        <div class="user-content">
                                                                            <h4><?= htmlspecialchars($media['judul']); ?></h4>
                                                                            <p style="font-size: 12px; margin-bottom: 10px; min-height: 40px;">
                                                                                <?= htmlspecialchars(substr($media['deskripsi'], 0, 100)); ?><?= strlen($media['deskripsi']) > 100 ? '...' : ''; ?>
                                                                            </p>
                                                                        </div>
                                                                        <div style="margin-top: 10px; display: flex; gap: 8px;">
                                                                            <a href="edit/edit_media.php?id=<?= $media['id_media']; ?>" class="btn btn-icon btn-primary waves-effect waves-light"><i class="ti-pencil"></i></a>
                                                                            <button type="button" class="btn btn-icon btn-danger waves-effect waves-light" onclick="deleteMedia(<?= $media['id_media']; ?>, '<?= htmlspecialchars(str_replace("'", "\\'", $media['judul'])); ?>')"><i class="ti-trash"></i></button>
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
                                        <div style="float: right;">
                                            <a href="tambah/tambah_media.php?sub=<?= $subId; ?>" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                        </div>
                                    </div>
                                </div>
                                <?php if (count($dataMedia) > 0): ?>
                                <div class="table-responsive">
                                    <table id="mediaTable" class="table table-hover table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 5%;">No</th>
                                                <th>Judul</th>
                                                <th style="width: 15%;">Topik</th>
                                                <th style="width: 30%;">Deskripsi</th>
                                                <th style="width: 15%; text-align: center;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dataMedia as $index => $media): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><strong><?= htmlspecialchars($media['judul']) ?></strong></td>
                                                <td><span class="label theme-bg-primary"><?= htmlspecialchars($media['topik']) ?></span></td>
                                                <td><small><?= htmlspecialchars(substr($media['deskripsi'], 0, 50)) ?><?= strlen($media['deskripsi']) > 50 ? '...' : ''; ?></small></td>
                                                <td style="text-align: center;">
                                                    <a href="edit/edit_media.php?id=<?= $media['id_media']; ?>" class="btn btn-icon btn-primary waves-effect waves-light" title="Edit"><i class="ti-pencil"></i></a>
                                                    <button type="button" class="btn btn-icon btn-danger waves-effect waves-light" onclick="deleteMedia(<?= $media['id_media']; ?>, '<?= htmlspecialchars(str_replace("'", "\\'", $media['judul'])); ?>')" title="Hapus"><i class="ti-trash"></i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                    <div class="alert alert-info" role="alert">
                                        <i class="ti-info-alt"></i> Tidak ada konten untuk kategori ini.
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
                <p style="font-size: 14px; color: #7f8c8d; margin-bottom: 20px;">Apakah Anda yakin ingin menghapus <strong id="deleteMediaName"></strong>?</p>
                <p style="color: #e74c3c; font-size: 12px; margin-top: 20px; margin-bottom: 30px;">
                    <i class="ti-alert-alt" style="margin-right: 6px;"></i>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <input type="hidden" id="deleteMediaId" value="">
                <div style="display: flex; justify-content: center; gap: 15px;">
                    <button type="button" class="btn btn-secondary btn-icon waves-effect waves-light" data-dismiss="modal" title="Batal">
                        <i class="ti-close"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger btn-icon waves-effect waves-light" id="confirmDelete" title="Hapus">
                        <i class="ti-trash"></i> Hapus
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
            $('#deleteModal').modal('hide');
            
            fetch('hapus/hapus_media.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#007bff'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan: ' + error.message,
                        confirmButtonColor: '#dc3545'
                    });
                });
        });
    }
    // Initialize DataTable if available to restore search/length/pagination
    try {
        if (typeof $ !== 'undefined' && $.fn && $.fn.DataTable) {
            $('#mediaTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [[10,25,50,-1],[10,25,50,"All"]],
                "columnDefs": [
                    { "orderable": false, "targets": [4] }
                ],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "paginate": { "previous": "Sebelumnya", "next": "Berikutnya" },
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "zeroRecords": "Tidak ada data yang cocok"
                }
            });
        }
    } catch (e) {
        console.warn('DataTable init failed:', e);
    }
});
</script>

<style>
.preview-container:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.user-card {
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.user-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #007bff;
}

.user-content h4 {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    line-height: 1.4;
}

.user-content p {
    color: #666;
    line-height: 1.5;
}

.rounded-card {
    border-radius: 8px;
}

.theme-bg-primary {
    background-color: #007bff;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}

.m-b-10 {
    margin-bottom: 10px !important;
}

.p-5 {
    padding: 3rem !important;
}

.p-4 {
    padding: 1.5rem !important;
}

.py-2 {
    padding-top: 0.5rem !important;
    padding-bottom: 0.5rem !important;
}

.px-3 {
    padding-left: 1rem !important;
    padding-right: 1rem !important;
}

.mb-0 {
    margin-bottom: 0 !important;
}

.flex-fill {
    flex: 1 !important;
}
</style>

<?php
$modalHtml = <<<HTML
<!-- Video Lightbox Modal -->
<div class="modal fade" id="videoLightbox" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0" style="position:relative; padding-top:56.25%;">
                <iframe id="videoLightboxIframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute; top:0; left:0; width:100%; height:100%; border-radius:8px;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
function openVideoLightbox(videoId) {
    if (!videoId) return;
    var src = 'https://www.youtube.com/embed/' + videoId + '?rel=0&autoplay=1';
    var iframe = document.getElementById('videoLightboxIframe');
    if (iframe) iframe.src = src;

    // Prefer Bootstrap modal if available
    try {
        if (typeof jQuery !== 'undefined' && jQuery.fn && jQuery.fn.modal) {
            jQuery('#videoLightbox').modal('show');
            return;
        }
    } catch (e) {
        // fallthrough to fallback
    }

    // Fallback: create a simple overlay lightbox
    var existing = document.getElementById('simpleVideoOverlay');
    if (existing) existing.remove();
    var overlay = document.createElement('div');
    overlay.id = 'simpleVideoOverlay';
    overlay.style.position = 'fixed';
    overlay.style.left = 0;
    overlay.style.top = 0;
    overlay.style.right = 0;
    overlay.style.bottom = 0;
    overlay.style.background = 'rgba(0,0,0,0.85)';
    overlay.style.display = 'flex';
    overlay.style.alignItems = 'center';
    overlay.style.justifyContent = 'center';
    overlay.style.zIndex = 99999;

    var frame = document.createElement('iframe');
    frame.src = src;
    frame.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
    frame.style.width = '80%';
    frame.style.height = '60%';
    frame.style.border = 'none';
    frame.id = 'simpleVideoOverlayIframe';
    overlay.appendChild(frame);

    overlay.addEventListener('click', function(e){
        if (e.target === overlay) {
            overlay.remove();
        }
    });
    document.body.appendChild(overlay);
}

// Clear iframe when modal closed (Bootstrap)
if (typeof jQuery !== 'undefined' && jQuery.fn && jQuery.fn.modal) {
    jQuery(document).on('hidden.bs.modal', '#videoLightbox', function () {
        var iframe = document.getElementById('videoLightboxIframe');
        if (iframe) iframe.src = '';
    });
}
</script>
HTML;
$script = ob_get_clean() . $modalHtml;
include 'layout.php';
renderLayout($content, $script);
?>
