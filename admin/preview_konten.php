<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// Get sub_jenis from URL parameter
$subId = isset($_GET['sub']) ? (int)$_GET['sub'] : 0;

// Get current sub info and fetch media
$currentSub = null;
$dataMedia = [];
$jenis = null;

if ($subId > 0) {
    $qCurrent = mysqli_query($koneksi, "SELECT s.*, j.nama_jenis FROM sub_jenis s LEFT JOIN jenis j ON s.id_jenis = j.id_jenis WHERE s.id_sub_jenis = $subId");
    if (mysqli_num_rows($qCurrent) > 0) {
        $currentSub = mysqli_fetch_assoc($qCurrent);
        
        // Fetch jenis info
        if ($currentSub['id_jenis']) {
            $qJenis = mysqli_query($koneksi, "SELECT * FROM jenis WHERE id_jenis = " . (int)$currentSub['id_jenis']);
            if (mysqli_num_rows($qJenis) > 0) {
                $jenis = mysqli_fetch_assoc($qJenis);
            }
        }
        
        // Fetch media for this sub_jenis
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
}

// Redirect if invalid sub_jenis
if (!$currentSub) {
    header('Location: index.php');
    exit();
}

// Get first media item if available
$media = count($dataMedia) > 0 ? $dataMedia[0] : null;

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

    // YouTube detection
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
?>
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Manajemen <?= htmlspecialchars($currentSub['nama_sub_jenis']) ?></h5>
                        <p class="m-b-0">Untuk mengelola <?= htmlspecialchars($currentSub['nama_sub_jenis']) ?>.</p>
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
                            <a href=""><?= htmlspecialchars($jenis ? $jenis['nama_jenis'] : $currentSub['nama_sub_jenis']) ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="preview_konten.php?sub=<?= $subId; ?>"><?= htmlspecialchars($currentSub['nama_sub_jenis']) ?></a>
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
                                                        <img src="<?= htmlspecialchars($linkPreview['preview']); ?>" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;" onerror="this.parentElement.innerHTML='<i class=\'ti-image\' style=\'font-size: 96px; color: #999;\'></i>';">
                                                    <?php elseif ($linkPreview['type'] === 'gdrive_file' && $linkPreview['preview']): ?>
                                                        <img src="<?= htmlspecialchars($linkPreview['preview']); ?>" alt="GDrive File" style="max-width: 100%; max-height: 100%; object-fit: contain;" onerror="this.parentElement.innerHTML='<i class=\'ti-link\' style=\'font-size: 96px; color: #999;\'></i>';">
                                                    <?php elseif ($linkPreview['type'] === 'youtube' && !empty($linkPreview['preview'])): ?>
                                                        <a href="#" onclick="openVideoLightbox('<?= $linkPreview['video_id']; ?>'); return false;" style="display:flex; align-items:center; justify-content:center; height:100%; text-decoration:none;">
                                                            <div style="position:relative; width:100%; height:100%;">
                                                                <img src="<?= htmlspecialchars($linkPreview['preview']); ?>" alt="YouTube Preview" style="max-width:100%; max-height:100%; object-fit:contain;" onerror="this.parentElement.innerHTML='<i class=\'ti-youtube\' style=\'font-size:96px;color:#e74c3c;\'></i>';">
                                                                <span style="position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); font-size:72px; color: rgba(255,255,255,0.9);"><i class="fa fa-play-circle"></i></span>
                                                            </div>
                                                        </a>
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

    try {
        if (typeof jQuery !== 'undefined' && jQuery.fn && jQuery.fn.modal) {
            jQuery('#videoLightbox').modal('show');
            return;
        }
    } catch (e) {}

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
        if (e.target === overlay) overlay.remove();
    });
    document.body.appendChild(overlay);
}

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
