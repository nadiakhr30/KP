<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

$breadcrumbTitle = "Dokumentasi";
$subtitle = "Koleksi dokumentasi BPS Bangkalan (gabungan sub 8 & 9)";

// Ambil daftar sub jenis untuk dokumentasi (khusus sub 8 & 9)
$subList = [];
$subQ = mysqli_query($koneksi, "SELECT id_sub_jenis, nama_sub_jenis FROM sub_jenis WHERE id_sub_jenis IN (8,9) ORDER BY nama_sub_jenis ASC");
if ($subQ) {
    while ($r = mysqli_fetch_assoc($subQ)) { $subList[] = $r; }
}

// Pilih sub dari query string jika ada (opsional)
$selectedSub = isset($_GET['sub']) ? (int)$_GET['sub'] : null;

$validSubIds = array_map(function($s){ return (int)$s['id_sub_jenis']; }, $subList);

if ($selectedSub && in_array($selectedSub, $validSubIds)) {
    // Query hanya untuk sub yang dipilih
    $mediaQ = mysqli_query($koneksi, "SELECT id_media, judul, topik, deskripsi, link, created_at FROM media WHERE id_sub_jenis = " . (int)$selectedSub . " ORDER BY created_at DESC");
} else {
    // Jika tidak memilih sub, tampilkan semua media dari sub 8 dan 9
    $mediaQ = mysqli_query($koneksi, "SELECT id_media, judul, topik, deskripsi, link, created_at FROM media WHERE id_sub_jenis IN (8,9) ORDER BY created_at DESC");
}

// (Optional) Map Google Drive folder per-sub jika perlu
$driveFolderMap = [
  // tambahkan jika ada mapping drive khusus untuk sub 8/9
];

$driveEmbedId = isset($driveFolderMap[$selectedSub]) ? $driveFolderMap[$selectedSub] : null;
$driveOriginalLink = $driveEmbedId ? 'https://drive.google.com/drive/folders/' . $driveEmbedId : null; 

$mediaList = [];
if ($mediaQ) {
    while ($row = mysqli_fetch_assoc($mediaQ)) { $mediaList[] = $row; }
}
?>
<?php
$selectedSubName = null;
if ($selectedSub) {
    foreach ($subList as $s) {
        if ((int)$s['id_sub_jenis'] === (int)$selectedSub) {
            $selectedSubName = $s['nama_sub_jenis'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<link rel="icon" href="../../images/sikumbang.ico" type="image/x-icon">
<title><?= htmlspecialchars($breadcrumbTitle) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
/* Menggunakan style sama seperti galeri_foto.php untuk konsistensi */
*{font-family:Poppins,sans-serif}
body{margin:0;background:linear-gradient(180deg,#f8fafc,#eef2f7);padding:32px;color:#0f172a}
.page-wrapper{max-width:1200px;margin:auto}
.breadcrumb-custom{display:flex;align-items:center;gap:10px;font-size:14px;margin-bottom:24px}
.breadcrumb-custom i{background:#2563eb;color:#fff;padding:8px;border-radius:10px;font-size:14px}
.breadcrumb-link{color:#0f172a;text-decoration:none}
.breadcrumb-active{font-weight:600;color:#0f172a}
.header{display:flex;align-items:center;gap:20px;background:#fff;border-radius:20px;padding:18px 24px;box-shadow:0 10px 30px rgba(15,23,42,.08);margin-bottom:28px}  .header-controls{margin-left:auto;display:flex;align-items:center}
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

  .modal-custom{position:fixed;inset:0;background:rgba(2,6,23,0.6);display:flex;align-items:center;justify-content:center;z-index:9999;padding:24px;backdrop-filter:blur(6px);transition:opacity .18s ease}
  .modal-custom[aria-hidden='true']{opacity:0;pointer-events:none}
  .modal-card{width:100%;max-width:720px;border-radius:12px;overflow:hidden;background:linear-gradient(180deg,#fff,#fcfcff);box-shadow:0 18px 40px rgba(2,6,23,0.18);display:flex;flex-direction:column}
  .modal-card-header{display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid #eef2f7}
  .modal-folder-icon{width:52px;height:52px;border-radius:10px;background:linear-gradient(180deg,#fff5eb,#fff1e6);display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(245,158,11,0.06)}
  .modal-folder-icon svg{width:28px;height:28px;color:#f59e0b}
  .modal-card-title{font-size:15px;font-weight:700;color:#0f172a}
  .modal-card-controls{margin-left:auto;display:flex;align-items:center;gap:10px}
  .modal-close-btn{border:none;background:transparent;font-size:20px;line-height:1;cursor:pointer;color:#475569}
  .modal-card-body{background:#fff;padding:10px}
  .modal-iframe{width:100%;height:480px;border-radius:10px;border:0;background:#f8fafc;min-height:260px}
  .modal-placeholder{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:26px;color:#64748b}

  .modal-error{padding:18px;text-align:center;color:#ef4444} 
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
    <a href="index.php" class="breadcrumb-link"><i class="bi bi-house-fill"></i></a>
    <span class="breadcrumb-separator">›</span>
    <a href="index.php#sumberdaya" class="breadcrumb-link">Sumber Daya</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-active"><?= htmlspecialchars($selectedSubName ? $selectedSubName : 'Semua Dokumentasi') ?></span>
    
  </div>

  <div class="header">
    <div class="header-left" style="display:flex;align-items:center;gap:16px;">
      <div class="header-text">
        <h2 style="margin:0;">
          <?= htmlspecialchars($breadcrumbTitle) ?>
          <?php if ($selectedSubName): ?>
            <span style="font-weight:600;color:#0f172a;font-size:18px;"> — <?= htmlspecialchars($selectedSubName) ?></span>
          <?php endif; ?>
        </h2>
        <p style="margin:0;font-size:14px;color:#475569;">
          <?php if ($selectedSubName): ?>
            Menampilkan koleksi untuk: <strong><?= htmlspecialchars($selectedSubName) ?></strong>
          <?php else: ?>
            Menampilkan semua koleksi dokumentasi (sub 8 & 9).
          <?php endif; ?>
          • Total: <strong><?= count($mediaList) ?></strong>
        </p>
      </div>
    </div>
  </div>

  
  <div class="grid"> 
    <?php if (count($mediaList) == 0): ?>
      <div class="no-data"><i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.5"></i>Tidak ada media ditemukan</div>
    <?php endif; ?>
    
    <?php foreach ($mediaList as $m):
      $id = htmlspecialchars($m['id_media']);
      $judul = htmlspecialchars($m['judul']);
      $topik = htmlspecialchars($m['topik']);
      $deskripsi = htmlspecialchars(substr($m['deskripsi'], 0, 150));
      $rawLink = $m['link'];
      $link = htmlspecialchars($rawLink);
      $created = date('d M Y', strtotime($m['created_at']));
      $badgeClass = 'primary';

      $mediaDriveId = null;
      if (!empty($rawLink)) {
        if (preg_match('/drive\.google\.com\/drive\/folders\/([a-zA-Z0-9_-]+)/', $rawLink, $matches) || preg_match('/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/', $rawLink, $matches) || preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $rawLink, $matches)) {
          $mediaDriveId = $matches[1];
        }
      }

      $cardDataAttr = '';
      // expose the original link and title so JS can show modal
      if (!empty($rawLink)) {
        $cardDataAttr .= ' data-media-link="' . htmlspecialchars($rawLink) . '"';
      }
      $cardDataAttr .= ' data-media-title="' . htmlspecialchars($judul) . '"';
      if ($mediaDriveId) {
        $cardDataAttr .= ' data-media-drive-id="' . htmlspecialchars($mediaDriveId) . '"';
      } elseif ($driveEmbedId) {
        $cardDataAttr .= ' data-drive-id="' . htmlspecialchars($driveEmbedId) . '"';
      }
    ?>
    <div class="card<?= $mediaDriveId || $driveEmbedId ? ' card-with-drive' : '' ?>" <?= $cardDataAttr ?> >
      <div class="card-folder"> 
        <div class="icon-folder">
          <div class="folder-tab"></div>
          <div class="folder-body">
            <i class="bi bi-folder2-open" aria-hidden="true"></i>
          </div>
        </div>
      </div> 

      <div class="card-tag">
        <span class="badge <?= $badgeClass ?>"><?= $topik ?></span>
      </div>

      <div class="body">
        <h4><?= $judul ?></h4>
        <p><?= $deskripsi ?>...</p>
        <div class="footer">
          <div>
            <small>Dibuat</small><br>
            <strong><?= $created ?></strong>
          </div>

          <?php if (!empty($link)): ?>
            <?php
              // hanya preview untuk Google Drive
              $isDrive = stripos($rawLink, 'drive.google.com') !== false;
            ?>
            <?php if ($isDrive): ?>
              <!-- preview modal untuk Drive (gh: gunakan href asli sebagai fallback) -->
              <a href="<?= htmlspecialchars($rawLink) ?>" target="_blank" class="open open-in-modal" data-media-link="<?= htmlspecialchars($rawLink) ?>" data-media-title="<?= htmlspecialchars($judul) ?>" title="Buka tautan (tab baru)"><i class="bi bi-box-arrow-up-right"></i></a>
            <?php else: ?>
              <!-- langsung buka tautan (termasuk YouTube) -->
              <a href="<?= htmlspecialchars($rawLink) ?>" target="_blank" class="open" title="Buka tautan"><i class="bi bi-box-arrow-up-right"></i></a>
            <?php endif; ?>
          <?php endif; ?>

        </div>

        
      <?php if ($mediaDriveId): ?>
        <div class="drive-panel" style="display:none;margin-top:12px;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.06);">
          <iframe class="drive-iframe" src="" width="100%" height="420" frameborder="0"></iframe>
        </div>
      <?php endif; ?>

    </div>
    <br><br>
    <?php endforeach; ?>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function(){
  function openDriveModal(driveId, title, originalLink){
    // reuse generic modal but use the embedded folder view for Drive folders
    openGenericModal('https://drive.google.com/embeddedfolderview?id=' + driveId + '#grid', title ? title : 'Isi Folder Google Drive', originalLink ? originalLink : ('https://drive.google.com/drive/folders/' + driveId));
  }

  // try to convert a Drive file link to the embeddable preview url
  function extractDrivePreviewUrl(url){
    var m = url.match(/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/) || url.match(/open\?id=([a-zA-Z0-9_-]+)/);
    if (m) return 'https://drive.google.com/file/d/' + m[1] + '/preview';
    return url;
  }


  // generic modal open: shows spinner, loads iframe, shows fallback on error
  function openGenericModal(url, title, originalLink){
    var modal = document.getElementById('driveModal');
    var titleEl = document.getElementById('driveModalTitle');
    var iframe = document.getElementById('driveIframe');
    var placeholder = document.getElementById('modalPlaceholder');
    var error = document.getElementById('modalError');
    var openOrigBtn = document.getElementById('modalOpenOriginal');

    titleEl.textContent = title || 'Pratinjau';

    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden','false');

    error.style.display = 'none';
    iframe.style.display = 'none';
    placeholder.style.display = 'flex';
    if (openOrigBtn) openOrigBtn.style.display = 'none';

    if (openOrigBtn) openOrigBtn.href = originalLink || url;
    if (openOrigBtn) openOrigBtn.onclick = function(){ setTimeout(closeDriveModal, 150); };

    var loaded = false;
    var loadTimer = setTimeout(function(){
      if (!loaded){
        placeholder.style.display = 'none';
        error.style.display = 'block';
        if (openOrigBtn) openOrigBtn.style.display = 'inline-block';
      }
    }, 1800);

    iframe.onload = function(){
      loaded = true;
      clearTimeout(loadTimer);
      placeholder.style.display = 'none';
      error.style.display = 'none';
      if (openOrigBtn) openOrigBtn.style.display = 'none';
      iframe.style.display = 'block';
    };

    iframe.src = url;
  }

  function closeDriveModal(){
    var modal = document.getElementById('driveModal');
    var iframe = document.getElementById('driveIframe');
    iframe.src = '';
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
  }
  var closeBtn = document.getElementById('driveModalClose');
  if (closeBtn) closeBtn.addEventListener('click', closeDriveModal);

  // Clicking the folder icon: prefer per-media inline preview if media has its own Drive link
  document.addEventListener('click', function(e){
    // If the click is inside an icon-folder, handle inline preview
    var iconEl = e.target.closest && e.target.closest('.icon-folder');
    if (iconEl){
      var card = iconEl.closest('.card');
      if (!card) return;

      var mediaDriveId = card.getAttribute('data-media-drive-id');
      if (mediaDriveId){
        e.preventDefault(); e.stopPropagation();
        // open centralized modal (embedded folder view)
        var title = card.getAttribute('data-media-title') || null;
        var original = card.getAttribute('data-media-link') || ('https://drive.google.com/drive/folders/' + mediaDriveId);
        openGenericModal('https://drive.google.com/embeddedfolderview?id=' + mediaDriveId + '#grid', title ? title : 'Isi Folder Google Drive', original);
        return;
      }

      // If no per-media drive, but this media has a direct link: only preview Drive files here; non-Drive do nothing (use box-arrow)
      var mediaLink = card.getAttribute('data-media-link');
      if (mediaLink){
        if (mediaLink.indexOf('drive.google.com') !== -1){
          e.preventDefault(); e.stopPropagation();
          var title = card.getAttribute('data-media-title') || null;
          var preview = extractDrivePreviewUrl(mediaLink);
          openGenericModal(preview, title, mediaLink);
          return;
        }
        // otherwise: do nothing — user should click the box-arrow (footer) to open the link
      }

      // If no per-media drive, fall back to sub-level drive modal if present
      var subDriveId = card.getAttribute('data-drive-id');
      if (subDriveId){
        e.preventDefault(); e.stopPropagation();
        var subName = <?= json_encode($selectedSubName ? $selectedSubName : 'Dokumentasi') ?>;
        var original = '<?= htmlspecialchars($driveOriginalLink ? $driveOriginalLink : '') ?>';
        openDriveModal(subDriveId, subName, original);
        return;
      }
    }

    // existing modal background click close
    var el = e.target;
    while (el && el !== document.body){
      if (el.classList && el.classList.contains('card-with-drive')){
        var driveId = el.getAttribute('data-drive-id');
        if (driveId){
          e.preventDefault();
          var subName = <?= json_encode($selectedSubName ? $selectedSubName : 'Dokumentasi') ?>;
          var original = '<?= htmlspecialchars($driveOriginalLink ? $driveOriginalLink : '') ?>';
          openDriveModal(driveId, subName, original);
          return;
        }
      }
      el = el.parentNode;
    }
  });

  // Footer icon (box-arrow) always opens the original link in a new tab; modal is only for the folder icon
  document.addEventListener('click', function(e){
    var a = e.target.closest && e.target.closest('a.open-in-modal');
    if (a){
      e.preventDefault(); e.stopPropagation();
      var link = a.getAttribute('data-media-link') || a.href;
      if (!link) return;
      window.open(link, '_blank');
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
        else {
          // no drive id; open the link in modal (preview if Drive file) or open in new tab for non-Drive
          var title = card.getAttribute('data-media-title') || null;
          if (mediaLink.indexOf('drive.google.com') !== -1){
            var preview = extractDrivePreviewUrl(mediaLink);
            openGenericModal(preview, title, mediaLink);
          } else {
            window.open(mediaLink, '_blank');
          }
          return;
        }
      }

      // If we have a drive id prefer opening it in the modal
      if (mediaDriveId){
        var title = card.getAttribute('data-media-title') || null;
        var original = mediaLink || ('https://drive.google.com/drive/folders/' + mediaDriveId);
        openGenericModal('https://drive.google.com/embeddedfolderview?id=' + mediaDriveId + '#grid', title ? title : 'Isi Folder Google Drive', original);
        return;
      }

      // If there's no drive id but a mediaLink, preview if Drive file, otherwise open in new tab
      if (mediaLink){
        var title2 = card.getAttribute('data-media-title') || null;
        if (mediaLink.indexOf('drive.google.com') !== -1){
          var preview = extractDrivePreviewUrl(mediaLink);
          openGenericModal(preview, title2, mediaLink);
        } else {
          window.open(mediaLink, '_blank');
        }
        return;
      }
    }
  });
});
</script>

<!-- Modal for drive / file preview -->
<div id="driveModal" class="modal-custom" aria-hidden="true" style="display:none;">
  <div class="modal-card" role="dialog" aria-modal="true" aria-label="Preview">
    <div class="modal-card-header">
      <div class="modal-folder-icon"><i class="bi bi-folder2-open" aria-hidden="true"></i></div>
      <div id="driveModalTitle" class="modal-card-title">Pratinjau</div>
      <div class="modal-card-controls">
        <button id="driveModalClose" class="modal-close-btn" aria-label="Tutup">&times;</button>
      </div>
    </div>
    <div class="modal-card-body">
      <div id="modalPlaceholder" class="modal-placeholder">
        <div style="margin-top:14px;color:#64748b">Memuat pratinjau…</div>
      </div>

      <iframe id="driveIframe" class="modal-iframe" src="" frameborder="0" style="display:none;"></iframe>

      <div id="modalError" class="modal-error" style="display:none">
        <p><strong>Pratinjau tidak tersedia untuk alamat ini.</strong></p>
        <p>Silakan buka tautan asli dari kartu atau footer untuk melihat sumber.</p>
        <p><a id="modalOpenOriginal" href="#" target="_blank" class="btn modal-open-original">Buka tautan asli</a></p>
      </div>
    </div>
  </div>
</div>

</body>
</html>
