<?php
session_start();
require '../koneksi.php';
if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}
$breadcrumbTitle = "OBS Media";
$subtitle = "Kumpulan sumber daya OBS untuk tim kehumasan";
$mediaQ = mysqli_query($koneksi, "SELECT * FROM media WHERE id_sub_jenis = 7 ORDER BY id_media DESC");

// Helper: render a preview HTML for a media link (Drive folder/file, image, video, iframe fallback)
function render_media_preview($rawLink, $height = 360) {
  $rawLink = trim($rawLink ?? '');
  $href = $rawLink;
  $isExternal = preg_match('/^https?:\/\//i', $rawLink);
  if (!$isExternal && $rawLink !== '') { $href = 'uploads/' . ltrim($rawLink, '/'); }

  $previewHtml = '';
  if ($rawLink && preg_match('/drive\.google\.com\/drive\/folders\/([a-zA-Z0-9_-]+)/', $rawLink, $mm)) {
    $id = $mm[1];
    $previewHtml = '<iframe src="https://drive.google.com/embeddedfolderview?id=' . htmlspecialchars($id) . '#grid" width="100%" height="' . intval($height) . '" frameborder="0" allowfullscreen></iframe>';
  } elseif ($rawLink && (preg_match('/id=([a-zA-Z0-9_-]+)/', $rawLink, $mm) || preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $rawLink, $mm))) {
    $fid = $mm[1];
    if (preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)$/i', $rawLink)) {
      $previewHtml = '<img src="https://drive.google.com/uc?export=view&id=' . htmlspecialchars($fid) . '" style="width:100%;height:auto;max-height:' . intval($height) . 'px;object-fit:contain" alt="Media">';
    } else {
      $previewHtml = '<iframe src="https://drive.google.com/file/d/' . htmlspecialchars($fid) . '/preview" width="100%" height="' . intval($height) . '" frameborder="0" allowfullscreen></iframe>';
    }
  } elseif ($href && preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)$/i', $href)) {
    $previewHtml = '<img src="' . htmlspecialchars($href) . '" style="width:100%;height:auto;max-height:' . intval($height) . 'px;object-fit:contain" alt="Media">';
  } elseif ($href && preg_match('/\.(mp4|webm|ogg|mov)$/i', $href)) {
    $previewHtml = '<video controls style="width:100%;max-height:' . intval($height) . 'px;background:#000"><source src="' . htmlspecialchars($href) . '"></video>';
  } elseif ($href) {
    $previewHtml = '<iframe src="' . htmlspecialchars($href) . '" width="100%" height="' . intval($height) . '" frameborder="0"></iframe>';
  }
    return $previewHtml;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<link rel="icon" href="../../images/sikumbang.ico" type="image/x-icon">
<title><?= $breadcrumbTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
*{font-family:Poppins,sans-serif}
body{
  margin:0;
  background:linear-gradient(180deg,#f8fafc,#eef2f7);
  padding:32px;
  color:#0f172a
}
.page-wrapper{max-width:1200px;margin:auto}
.breadcrumb-custom{
  display:flex;
  align-items:center;
  gap:10px;
  font-size:14px;
  margin-bottom:24px;
}
.breadcrumb-custom i{
  background:#2563eb;
  color:#fff;
  padding:8px;
  border-radius:10px;
  font-size:14px;
}
.breadcrumb-active{
  font-weight:600;
  color:#0f172a;
}
.breadcrumb-link{color:#0f172a;text-decoration:none}

.header{display:flex;flex-direction:column;align-items:flex-start;gap:8px;background:#fff;border-radius:20px;padding:18px 24px;box-shadow:0 10px 30px rgba(15,23,42,.08);margin-bottom:28px}
  .header h2{margin:0;font-size:20px}
  .header p{margin:0;color:#64748b}
.grid{display:grid;grid-template-columns:1fr;gap:40px;padding-bottom:40px}
.media-item{background:#fff;border-radius:12px;padding:12px;box-shadow:0 8px 30px rgba(15,23,42,.06)}
.card{
  background:#fff;
  border-radius:14px;
  overflow:hidden;
  box-shadow:0 6px 24px rgba(15,23,42,.06);
  transition:.25s ease;
}
.card:hover{
  transform:translateY(-4px);
  box-shadow:0 14px 40px rgba(15,23,42,.12);
}
.body{padding:16px;display:flex;flex-direction:column;gap:10px}
.badge{
  width:max-content;
  font-size:11px;
  padding:4px 10px;
  border-radius:999px;
  font-weight:600;
  color:#fff;
  background:#e83e8c;
}
.body h4{margin:0;font-size:16px;font-weight:600}
.body p{font-size:13px;color:#64748b;line-height:1.5}
.footer{
  margin-top:auto;
  padding-top:10px;
  border-top:1px solid #eef2f7;
  display:flex;
  justify-content:space-between;
  align-items:center;
}
.footer small{font-size:11px;color:#6b7280}
.footer strong{font-size:13px}
.open{font-size:18px;color:#e83e8c;text-decoration:none}
</style>
</head>
<body>
<div class="page-wrapper">
  <div class="breadcrumb-custom">
    <a href="index.php" class="breadcrumb-link">
        <i class="bi bi-house-fill"></i>
    </a>
    <span class="breadcrumb-separator">›</span>
    <a href="index.php#broadcast" class="breadcrumb-link">Broadcast</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-active">OBS Media</span>
  </div>
  <div class="header">
    <h2><?= $breadcrumbTitle ?></h2>
    <p><?= $subtitle ?></p>
  </div>
  <div class="grid">
    <?php
    if ($mediaQ && mysqli_num_rows($mediaQ) > 0):
      while ($m = mysqli_fetch_assoc($mediaQ)):
    ?>
    <div class="card">
      <?php
        $displayTitle = $m['judul'] ?? '-';
        if (!empty($driveUrl)) {
          $driveTitle = '';
          if (preg_match('/[\?&]filename=([^&]+)/', $driveUrl, $match)) {
            $driveTitle = urldecode($match[1]);
          } elseif (preg_match('/\/([^\/\?]+\.(jpg|jpeg|png|gif|bmp|webp|mp4|webm|ogg|mov|avi|mkv))(?:\?|$)/i', $driveUrl, $match)) {
            $driveTitle = $match[1];
          }
          // Try Drive API if available (returns real file name for public files)
          if (empty($driveTitle) && !empty($fileId) && function_exists('fetch_drive_filename')) {
            $apiName = fetch_drive_filename($fileId);
            if (!empty($apiName)) $driveTitle = $apiName;
          }
          if ($driveTitle) $displayTitle = $driveTitle;
        }
      ?>
      <div class="body">
        <?php
          // Render full-size preview (same style as Broadcast)
          echo render_media_preview($m['link'], 360);
        ?>
        <?php if (!empty($m['link'])): ?>
          <?php $rawLink = trim($m['link'] ?? ''); $isExternal = preg_match('/^https?:\/\//i', $rawLink); $href = $isExternal ? $rawLink : 'uploads/' . ltrim($rawLink, '/'); ?>
          <div style="padding-top:10px;border-top:1px solid #eef2f7;margin-top:10px;">
            <small>Link sumber:</small><br>
            <a href="<?= htmlspecialchars($href) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($href) ?></a>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endwhile;
    else:
      echo '<div style="color:#64748b">Data OBS media tidak tersedia</div>';
    endif;
    ?>
  </div>
</div>
</body>
</html>
