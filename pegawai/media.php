<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

// Get sub_jenis from URL parameter
$id_sub_jenis = isset($_GET['sub']) ? (int)$_GET['sub'] : 0;

// Validate sub_jenis exists
$subJenisQ = mysqli_query($koneksi, "
    SELECT sj.id_sub_jenis, sj.nama_sub_jenis, j.nama_jenis 
    FROM sub_jenis sj
    JOIN jenis j ON sj.id_jenis = j.id_jenis
    WHERE sj.id_sub_jenis = " . $id_sub_jenis
);

if (!$subJenisQ || mysqli_num_rows($subJenisQ) == 0) {
    header("Location: index.php");
    exit;
}

$subJenisData = mysqli_fetch_assoc($subJenisQ);
$namaSubJenis = $subJenisData['nama_sub_jenis'];
$namaJenis = $subJenisData['nama_jenis'];

// Get media data
$mediaQ = mysqli_query($koneksi, "
    SELECT 
        id_media,
        judul,
        topik,
        deskripsi,
        link,
        created_at
    FROM media
    WHERE id_sub_jenis = " . $id_sub_jenis . "
    ORDER BY created_at DESC
");

$mediaList = [];
if ($mediaQ) {
    while ($row = mysqli_fetch_assoc($mediaQ)) {
        $mediaList[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $namaSubJenis ?></title>
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

/* ===== BREADCRUMB ===== */
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
.breadcrumb-custom a{
  color:#0f172a;
  text-decoration:none;
}
.breadcrumb-custom a:hover{
  text-decoration:none;
}
.breadcrumb-active{
  font-weight:600;
  color:#0f172a;
}

/* ===== HEADER SECTION ===== */
.header{
  background:#fff;
  border-radius:20px;
  padding:28px 32px;
  box-shadow:0 10px 30px rgba(15,23,42,.08);
  margin-bottom:28px;
}
.header h2{
  margin:0;
  font-size:26px;
  font-weight:700;
}
.header p{
  margin-top:8px;
  color:#64748b;
  font-size:14px;
}

/* ===== GRID ===== */
.grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
  gap:24px;
}

/* ===== CARD ===== */
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
}
.primary{background:#2563eb}
.success{background:#16a34a}
.warning{background:#f59e0b}
.secondary{background:#64748b}

.body h4{margin:0;font-size:16px;font-weight:600}
.body p{font-size:13px;color:#64748b;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}

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
.open:hover{color:#1d4ed8}

.no-data{
  grid-column:1/-1;
  color:#64748b;
  text-align:center;
  padding:40px;
  background:#fff;
  border-radius:14px;
  box-shadow:0 6px 24px rgba(15,23,42,.06);
}
</style>
</head>

<body>
<div class="page-wrapper">

  <!-- BREADCRUMB -->
  <div class="breadcrumb-custom">
    <a href="index.php" class="breadcrumb-link">
        <i class="bi bi-house-fill"></i>
    </a>
    <span class="breadcrumb-separator">›</span>
    <a href="index.php#sumberdaya" class="breadcrumb-link">Sumber Daya</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-active"><?= htmlspecialchars($namaSubJenis) ?></span>
  </div>

  <!-- HEADER -->
  <div class="header">
    <h2><?= htmlspecialchars($namaSubJenis) ?></h2>
    <p>Total media: <strong><?= count($mediaList) ?></strong> media tersedia</p>
  </div>

  <!-- GRID -->
  <div class="grid">
  <?php
    if (count($mediaList) == 0) {
        echo '<div class="no-data"><i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.5"></i>Data media tidak tersedia</div>';
    }

    foreach ($mediaList as $m) {
        $id = htmlspecialchars($m['id_media']);
        $judul = htmlspecialchars($m['judul']);
        $topik = htmlspecialchars($m['topik']);
        $deskripsi = htmlspecialchars(substr($m['deskripsi'], 0, 100));
        $link = htmlspecialchars($m['link']);
        $created = date('d M Y', strtotime($m['created_at']));
        
        // Determine badge color based on topik
        $badgeClass = 'primary';
        if (strpos(strtolower($topik), 'reels') !== false) {
            $badgeClass = 'success';
        } elseif (strpos(strtolower($topik), 'landscape') !== false) {
            $badgeClass = 'warning';
        } elseif (strpos(strtolower($topik), 'pedoman') !== false) {
            $badgeClass = 'secondary';
        }
  ?>
    <div class="card">
      <div class="body">
        <span class="badge <?= $badgeClass ?>">
          <?= $topik ?>
        </span>
        <h4><?= $judul ?></h4>
        <p><?= $deskripsi ?>...</p>
        <div class="footer">
          <div>
            <small>Dibuat</small><br>
            <strong><?= $created ?></strong>
          </div>
          <?php if(!empty($link)){ ?>
            <a href="<?= $link ?>" target="_blank" class="open" title="Buka Media">
              <i class="bi bi-box-arrow-up-right"></i>
            </a>
          <?php } ?>
        </div>
      </div>
    </div>
  <?php } ?>
  </div>

</div>
</body>
</html>
