
<?php
session_start();
require '../koneksi.php';
if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}
$breadcrumbTitle = "Video Operator";
$subtitle = "Kumpulan sumber daya Video Operator untuk tim kehumasan";
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
body{margin:0;background:linear-gradient(180deg,#f8fafc,#eef2f7);padding:32px;color:#0f172a}
.page-wrapper{max-width:1200px;margin:auto}
.breadcrumb-custom{display:flex;align-items:center;gap:10px;font-size:14px;margin-bottom:24px}
.breadcrumb-custom i{background:#2563eb;color:#fff;padding:8px;border-radius:10px;font-size:14px}
.breadcrumb-link{color:#0f172a;text-decoration:none}
.breadcrumb-active{font-weight:600;color:#0f172a}
.header{display:flex;flex-direction:column;align-items:flex-start;gap:8px;background:#fff;border-radius:20px;padding:18px 24px;box-shadow:0 10px 30px rgba(15,23,42,.08);margin-bottom:28px}
  .header h2{margin:0;font-size:20px}
  .header p{margin:0;color:#64748b}
  .header-controls{margin-left:auto;display:flex;align-items:center}
  .sub-select-form{display:flex;align-items:center;gap:8px}
  .sub-select{padding:8px 10px;border-radius:8px;border:1px solid #e6eef8;background:#fff;font-weight:600;font-size:14px}
  @media (max-width:600px){ .sub-select{font-size:13px;padding:6px 8px} .header{flex-direction:column;align-items:flex-start;gap:12px} .header-controls{width:100%;justify-content:flex-start} }.grid{display:grid;grid-template-columns:1fr;gap:24px} 
.card{background:transparent;border-radius:0;overflow:visible;box-shadow:none;transition:none;padding:0;position:relative}
.card .body{background:#fff;border-radius:12px;margin-top:-20px;padding:18px;box-shadow:0 8px 30px rgba(15,23,42,.06)}
.card-tag{position:absolute;left:18px;top:12px;z-index:20}
.card-tag .badge{font-size:12px;padding:6px 10px;border-radius:999px;box-shadow:0 6px 18px rgba(15,23,42,.04)}
@media (max-width:900px){ .card-tag{left:12px;top:10px} }
.drive-btn{position:absolute;right:18px;bottom:18px;border-radius:10px;border:1px solid #eef2f7;background:#fff;padding:8px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 6px 18px rgba(15,23,42,.04)}
.drive-btn .bi{font-size:16px;color:#2563eb}
.card .drive-btn{display:none}
.card.card-with-drive .drive-btn{display:inline-flex}
.drive-btn:focus{outline:none;box-shadow:0 8px 20px rgba(37,99,235,.14)}
  .card-folder{height:280px;display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg,#fffaf0,#fff9f2);position:relative}
  .icon-folder{width:140px;height:100px;position:relative}
  .icon-folder .folder-tab{position:absolute;top:-14px;left:16px;width:72px;height:22px;border-radius:6px 6px 0 0;background:linear-gradient(180deg,#fff7ed,#fff4e6);border:1px solid rgba(245,158,11,0.08)}
  .icon-folder .folder-body{width:100%;height:100%;background:linear-gradient(180deg,rgba(245,158,11,0.06),rgba(245,158,11,0.02));border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(245,158,11,0.06)}
  .icon-folder .bi{font-size:42px;color:#f59e0b}
  .card:hover .icon-folder{transform:translateY(-6px);box-shadow:0 12px 30px rgba(245,158,11,0.06);transition:transform .18s ease,box-shadow .18s ease}
  .card.card-with-drive{cursor:pointer}
  .card.card-with-drive .icon-folder{transition:transform .18s}
  .card.card-with-drive:hover .icon-folder{transform:translateY(-8px)}
  .grid{display:grid;grid-template-columns:1fr;gap:24px}

  .modal-custom{position:fixed;inset:0;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center;z-index:9999;padding:16px}
  .modal-custom-inner{width:100%;max-width:1100px;border-radius:10px;overflow:hidden;background:#fff}
  .modal-custom-body iframe{border-radius:8px} 
.body{padding:18px;display:flex;flex-direction:column;gap:10px;background:#fff;border-radius:12px;margin-top:-20px;box-shadow:0 8px 30px rgba(15,23,42,.06)}
.badge{width:max-content;font-size:11px;padding:4px 10px;border-radius:999px;font-weight:600;color:#fff}
.primary{background:#2563eb}.success{background:#16a34a}.warning{background:#f59e0b}.secondary{background:#64748b}
.body h4{margin:0;font-size:16px;font-weight:600}
.body p{font-size:13px;color:#64748b;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.footer{margin-top:auto;padding-top:10px;border-top:1px solid #eef2f7;display:flex;justify-content:space-between;align-items:center}
.footer small{font-size:11px;color:#6b7280}.footer strong{font-size:13px}
.open{font-size:18px;color:#2563eb;text-decoration:none}
.no-data{grid-column:1/-1;color:#64748b;text-align:center;padding:40px;background:#fff;border-radius:14px;box-shadow:0 6px 24px rgba(15,23,42,.06)}
.no-data{grid-column:1/-1;color:#64748b;text-align:center;padding:40px;background:#fff;border-radius:14px;box-shadow:0 6px 24px rgba(15,23,42,.06);margin-bottom:32px}
.card{margin-bottom:100px}
.grid{gap:100px;padding-bottom:100px}
.card{margin-bottom:80px}
.grid{gap:80px;padding-bottom:80px}
.card{margin-bottom:80px}
.grid{gap:40px}
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
    <span class="breadcrumb-active">Video Operator</span>
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
    <?php
      // normalize and detect drive folder or file
      $rawLink = $m['link'] ?? '';
      $driveUrl = '';
      if (!empty($rawLink) && preg_match('/drive\\.google\\.com/', $rawLink)) { $driveUrl = $rawLink; }

      // detect folder id
      $mediaDriveId = null;
      if (preg_match('/drive\\.google\\.com\\/drive\\/folders\\/([a-zA-Z0-9_-]+)/', $rawLink, $mm)) {
        $mediaDriveId = $mm[1];
      }

      $fileId = '';
      if ($driveUrl && preg_match('/id=([a-zA-Z0-9_-]+)/', $driveUrl, $matches)) {
        $fileId = $matches[1];
      } elseif ($driveUrl && preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $driveUrl, $matches)) {
        $fileId = $matches[1];
      }
    ?>
      <?php
        // Show media preview inline and the source link (simplified)
        $rawLink = trim($m['link'] ?? '');
        $href = $rawLink;
        $isExternal = preg_match('/^https?:\/\//i', $rawLink);
        if (!$isExternal && $rawLink !== '') { $href = 'uploads/' . ltrim($rawLink, '/'); }

        $previewHtml = '';
        if ($rawLink && preg_match('/drive\.google\.com\/drive\/folders\/([a-zA-Z0-9_-]+)/', $rawLink, $mm)) {
          $id = $mm[1];
          $previewHtml = '<iframe src="https://drive.google.com/embeddedfolderview?id=' . htmlspecialchars($id) . '#grid" width="100%" height="480" frameborder="0" allowfullscreen></iframe>';
        } elseif ($rawLink && (preg_match('/id=([a-zA-Z0-9_-]+)/', $rawLink, $mm) || preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $rawLink, $mm))) {
          $fid = $mm[1];
          if (preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)$/i', $rawLink)) {
            $previewHtml = '<img src="https://drive.google.com/uc?export=view&id=' . htmlspecialchars($fid) . '" style="width:100%;height:auto;max-height:480px;object-fit:contain" alt="Media">';
          } else {
            $previewHtml = '<iframe src="https://drive.google.com/file/d/' . htmlspecialchars($fid) . '/preview" width="100%" height="480" frameborder="0" allowfullscreen></iframe>';
          }
        } elseif ($href && preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)$/i', $href)) {
          $previewHtml = '<img src="' . htmlspecialchars($href) . '" style="width:100%;height:auto;max-height:480px;object-fit:contain" alt="Media">';
        } elseif ($href && preg_match('/\.(mp4|webm|ogg|mov)$/i', $href)) {
          $previewHtml = '<video controls style="width:100%;max-height:480px;background:#000"><source src="' . htmlspecialchars($href) . '"></video>';
        } elseif ($href) {
          $previewHtml = '<iframe src="' . htmlspecialchars($href) . '" width="100%" height="480" frameborder="0"></iframe>';
        } else {
          $previewHtml = '<div style="padding:36px;text-align:center;color:#64748b"><i class="bi bi-broadcast" style="font-size:36px"></i></div>';
        }
      ?>

      <div class="media-item" style="background:#fff;border-radius:12px;padding:12px;box-shadow:0 8px 30px rgba(15,23,42,.06)">
        <?= $previewHtml ?>
        <?php if (!empty($m['link'])): ?>
          <div style="padding-top:10px;border-top:1px solid #eef2f7;margin-top:10px;">
            <small>Link sumber:</small><br>
            <a href="<?= htmlspecialchars($href) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($href) ?></a>
          </div>
        <?php endif; ?>
      </div>
    <?php endwhile;
    else:
      echo '<div style="color:#64748b">Data broadcast media tidak tersedia</div>';
    endif;
    ?>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  function openDriveModal(driveId, title, originalLink){
    var modal = document.getElementById('driveModal');
    var iframe = document.getElementById('driveIframe');
    var titleEl = document.getElementById('driveModalTitle');
    var linkEl = document.getElementById('driveOriginalLink');
    if (!modal || !iframe) return;
    iframe.src = 'https://drive.google.com/embeddedfolderview?id=' + driveId + '#grid';
    if (titleEl) titleEl.textContent = title ? title : 'Isi Folder Google Drive';
    if (linkEl) linkEl.href = originalLink ? originalLink : '#';
    modal.style.display = 'flex';
  }
  function closeDriveModal(){
    var modal = document.getElementById('driveModal');
    var iframe = document.getElementById('driveIframe');
    if (!modal || !iframe) return;
    iframe.src = '';
    modal.style.display = 'none';
  }
  var closeBtn = document.getElementById('driveModalClose');
  if (closeBtn) closeBtn.addEventListener('click', closeDriveModal);

  // Clicking the folder icon: prefer per-media inline preview if media has its own Drive link
  document.addEventListener('click', function(e){
    var iconEl = e.target.closest && e.target.closest('.icon-folder');
    if (iconEl){
      var card = iconEl.closest('.card');
      if (!card) return;

      var mediaDriveId = card.getAttribute('data-media-drive-id');
      var mediaLink = card.getAttribute('data-media-link') || '';

      var panel = card.querySelector('.drive-panel');
      if (!panel) return;
      var iframe = panel.querySelector('.drive-iframe');

      // If mediaLink present prefer showing it inline
      if (mediaLink) {
        e.preventDefault(); e.stopPropagation();
        // normalize link
        if (/drive\.google\.com\/drive\/folders\//.test(mediaLink)) {
          var m = mediaLink.match(/drive\.google\.com\/drive\/folders\/([a-zA-Z0-9_-]+)/);
          if (m) {
            var id = m[1];
            document.querySelectorAll('.drive-panel').forEach(function(p){ p.style.display='none'; var f = p.querySelector('.drive-iframe'); if (f) f.src=''; });
            panel.style.display = 'block';
            if (iframe) iframe.src = 'https://drive.google.com/embeddedfolderview?id=' + id + '#grid';
            return;
          }
        }
        // check for drive file id
        var fid = null;
        var m1 = mediaLink.match(/id=([a-zA-Z0-9_-]+)/);
        var m2 = mediaLink.match(/\/d\/([a-zA-Z0-9_-]+)/);
        if (m1) fid = m1[1]; else if (m2) fid = m2[1];
        if (fid) {
          document.querySelectorAll('.drive-panel').forEach(function(p){ p.style.display='none'; var f = p.querySelector('.drive-iframe'); if (f) f.src=''; });
          panel.style.display = 'block';
          if (iframe) iframe.src = 'https://drive.google.com/file/d/' + fid + '/preview';
          return;
        }

        // fallback: if relative path (no protocol) assume uploads/
        var href = mediaLink;
        if (!/^https?:\/\//i.test(mediaLink)) href = 'uploads/' + mediaLink;
        document.querySelectorAll('.drive-panel').forEach(function(p){ p.style.display='none'; var f = p.querySelector('.drive-iframe'); if (f) f.src=''; });
        panel.style.display = 'block';
        if (iframe) iframe.src = href;
        return;
      }

      // If no per-media drive, fallback to nothing (no sub drive for broadcast)
    }

    // also support clicking whole card to open sub drive if data-drive-id present
    var el = e.target;
    while (el && el !== document.body){
      if (el.classList && el.classList.contains('card-with-drive')){
        var driveId = el.getAttribute('data-drive-id');
        var mediaLink = el.getAttribute('data-media-link') || '';
        if (mediaLink){
          e.preventDefault(); e.stopPropagation();
          var panel = el.querySelector('.drive-panel');
          if (!panel) return;
          var iframe = panel.querySelector('.drive-iframe');

          // prefer inline mediaLink first
          if (/drive\.google\.com\/drive\/folders\//.test(mediaLink)){
            var m = mediaLink.match(/drive\.google\.com\/drive\/folders\/([a-zA-Z0-9_-]+)/);
            if (m){
              document.querySelectorAll('.drive-panel').forEach(function(p){ p.style.display='none'; var f = p.querySelector('.drive-iframe'); if (f) f.src=''; });
              panel.style.display = 'block';
              if (iframe) iframe.src = 'https://drive.google.com/embeddedfolderview?id=' + m[1] + '#grid';
              return;
            }
          }
          var fid = null;
          var m1 = mediaLink.match(/id=([a-zA-Z0-9_-]+)/);
          var m2 = mediaLink.match(/\/d\/([a-zA-Z0-9_-]+)/);
          if (m1) fid = m1[1]; else if (m2) fid = m2[1];
          if (fid){
            document.querySelectorAll('.drive-panel').forEach(function(p){ p.style.display='none'; var f = p.querySelector('.drive-iframe'); if (f) f.src=''; });
            panel.style.display = 'block';
            if (iframe) iframe.src = 'https://drive.google.com/file/d/' + fid + '/preview';
            return;
          }

          var href = mediaLink;
          if (!/^https?:\/\//i.test(mediaLink)) href = 'uploads/' + mediaLink;
          document.querySelectorAll('.drive-panel').forEach(function(p){ p.style.display='none'; var f = p.querySelector('.drive-iframe'); if (f) f.src=''; });
          panel.style.display = 'block';
          if (iframe) iframe.src = href;
          return;
        }

        if (driveId){
          e.preventDefault();
          openDriveModal(driveId, 'Broadcast Media', '');
          return;
        }
      }
      el = el.parentNode;
    }
  });

  var modalEl = document.getElementById('driveModal');
  if (modalEl) modalEl.addEventListener('click', function(e){ if (e.target === this) closeDriveModal(); });

  // Handle drive button clicks (bottom-right) — open per-media panel using the media.link or drive id
  document.addEventListener('click', function(e){
    var btn = e.target.closest && e.target.closest('.drive-btn');
    if (btn){
      e.preventDefault(); e.stopPropagation();
      var card = btn.closest('.card');
      var panel = card.querySelector('.drive-panel');
      if (!panel) return;
      var mediaDriveId = btn.getAttribute('data-media-drive-id') || btn.getAttribute('data-media-id');
      var mediaLink = btn.getAttribute('data-media-link') || '';

      if (!mediaDriveId && mediaLink){
        var m = mediaLink.match(/drive\.google\.com\/drive\/folders\/([a-zA-Z0-9_-]+)/) || mediaLink.match(/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/) || mediaLink.match(/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/);
        if (m) mediaDriveId = m[1];
      }

      var iframe = panel.querySelector('.drive-iframe');
      if (!iframe) return;

      if (panel.style.display === '' || panel.style.display === 'none'){
        document.querySelectorAll('.drive-panel').forEach(function(p){ p.style.display='none'; var f = p.querySelector('.drive-iframe'); if (f) f.src=''; });
        panel.style.display = 'block';
        if (mediaDriveId) iframe.src = 'https://drive.google.com/embeddedfolderview?id=' + mediaDriveId + '#grid';
      } else {
        panel.style.display = 'none';
        iframe.src = '';
      }
      return;
    }
  });
});
</script>

</body>
</html>
