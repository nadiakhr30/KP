
<?php
session_start();
require '../koneksi.php';
if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}
$breadcrumbTitle = "Broadcast Media";
$subtitle = "Kumpulan sumber daya broadcast untuk tim kehumasan";
$mediaQ = mysqli_query($koneksi, "SELECT * FROM media WHERE id_sub_jenis = 6 ORDER BY id_media DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
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
.header{
  background:#fff;
  border-radius:20px;
  padding:28px 32px;
  box-shadow:0 10px 30px rgba(15,23,42,.08);
  margin-bottom:28px;
}
.grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(340px,1fr));
  gap:28px;
}
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
.thumb{height:200px;background:#e5e7eb;display:flex;align-items:center;justify-content:center;}
.thumb i{font-size:64px;color:#2563eb;}
.body{padding:16px;display:flex;flex-direction:column;gap:10px}
.badge{
  width:max-content;
  font-size:11px;
  padding:4px 10px;
  border-radius:999px;
  font-weight:600;
  color:#fff;
  background:#2563eb;
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
.open{font-size:18px;color:#2563eb;text-decoration:none}
</style>
</head>
<body>
<div class="page-wrapper">
  <div class="breadcrumb-custom">
    <a href="index.php" class="breadcrumb-link">
        <i class="bi bi-house-fill"></i>
    </a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-active">Broadcast Media</span>
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
      <div class="thumb">
        <?php
        $driveUrl = '';
        if (!empty($m['link']) && preg_match('/drive\\.google\\.com/', $m['link'])) {
          $driveUrl = $m['link'];
        }
        if ($driveUrl) {
          $fileId = '';
          if (preg_match('/id=([a-zA-Z0-9_-]+)/', $driveUrl, $matches)) {
            $fileId = $matches[1];
          } elseif (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $driveUrl, $matches)) {
            $fileId = $matches[1];
          }
          if ($fileId) {
            $isImage = false;
            $isVideo = false;
            if (preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)/i', $driveUrl)) {
              $isImage = true;
            } elseif (preg_match('/\.(mp4|webm|ogg|mov|avi|mkv)/i', $driveUrl)) {
              $isVideo = true;
            }
            if ($isImage) {
              echo '<img src="https://drive.google.com/uc?export=view&id=' . htmlspecialchars($fileId) . '" style="max-width:100%;max-height:160px;object-fit:contain;" alt="Gambar">';
            } else {
              echo '<iframe src="https://drive.google.com/file/d/' . htmlspecialchars($fileId) . '/preview" width="100%" height="160" allow="autoplay" frameborder="0" allowfullscreen></iframe>';
            }
          } else {
            echo '<i class="bi bi-broadcast"></i>';
          }
        } else {
          echo '<i class="bi bi-broadcast"></i>';
        }
        ?>
      </div>
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
        <span class="badge">Broadcast</span>
        <h4><?= htmlspecialchars($displayTitle) ?></h4>
        <p><?= htmlspecialchars($m['deskripsi'] ?? '-') ?></p>
        <div class="footer">
          <div>
            <small>Link</small><br>
          </div>
          <?php if (!empty($m['link'])): ?>
            <?php
              $isExternal = preg_match('/^https?:\\/\\//', $m['link']);
              $href = $isExternal ? $m['link'] : 'uploads/' . $m['link'];
            ?>
            <a href="<?= htmlspecialchars($href) ?>" target="_blank" class="open" title="Buka link">
              <i class="bi bi-link-45deg"></i>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endwhile;
    else:
      echo '<div style="color:#64748b">Data broadcast media tidak tersedia</div>';
    endif;
    ?>
  </div>
</div>
</body>
</html>
