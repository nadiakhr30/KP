<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

/* ================= FILTER ================= */
$filterKategori = $_GET['kategori'] ?? 'all';
$where = "";

/* Breadcrumb & Title */
$breadcrumbTitle = "Pembinaan Kehumasan";
$subtitle = "Sumber daya untuk pengembangan dan inovasi internal";

if ($filterKategori != 'all' && !empty($filterKategori)) {
    $countQ = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM media WHERE id_sub_jenis = 5");
    $countRow = mysqli_fetch_assoc($countQ);
    $subtitle = "Total: " . $countRow['total'] . " item pengembangan tersedia";
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
  margin:0 0 8px 0;
  font-size:28px;
  font-weight:700;
  color:#0f172a;
}

.header p{
  margin:0;
  font-size:14px;
  color:#64748b;
}

/* ===== GRID ===== */
.grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
  gap:28px;
}

/* ===== CARD ===== */
.card{
  background:#fff;
  border-radius:18px;
  overflow:hidden;
  box-shadow:0 8px 32px rgba(15,23,42,.08);
  transition:.35s cubic-bezier(0.23, 1, 0.320, 1);
  display:flex;
  flex-direction:column;
  position:relative;
  border: 1px solid #eef2f7;
}

/* top accent removed for solid white cards */
  .card::before { display: none; }

.card:hover{
  transform:translateY(-8px);
  box-shadow:0 18px 54px rgba(15,23,42,.12);
  border-color: #e6eefb;
}

.thumb{
  height:180px;
  background:#ffffff; /* white header */
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:56px;
  color:#2563eb; /* blue icon */
  position: relative;
  overflow: hidden;
  border-bottom: 1px solid rgba(79,70,229,0.04);
}

.thumb::after {
  content: '';
  position: absolute;
  width: 250px;
  height: 250px;
  background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
  border-radius: 50%;
  top: -50px;
  right: -50px;
  animation: float-dev 6s ease-in-out infinite;
}

@keyframes float-dev {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-15px); }
}

.thumb i {
  position: relative;
  z-index: 2;
  transition: transform 0.4s cubic-bezier(0.23, 1, 0.320, 1);
}

.card:hover .thumb i {
  transform: scale(1.2) rotate(-15deg);
}

.body{
  padding:24px;
  display:flex;
  flex-direction:column;
  gap:12px;
  flex:1;
}

.badge{
  width:max-content;
  font-size:11px;
  padding:6px 12px;
  border-radius:999px;
  font-weight:600;
  color:#fff;
  display: flex;
  align-items: center;
  gap: 6px;
}
.primary{background:linear-gradient(135deg, #667eea 0%, #764ba2 100%)}
.success{background:#16a34a}
.warning{background:#f59e0b}
.danger{background:#dc2626}
.secondary{background:#64748b}

.body h4{
  margin:0;
  font-size:18px;
  font-weight:700;
  color:#0f172a;
  line-height: 1.4;
}
.body p{
  font-size:13px;
  color:#718093;
  line-height:1.7;
  margin: 0;
}

.footer{
  margin-top:auto;
  padding-top:16px;
  border-top:1px solid #eef2f7;
  display:flex;
  justify-content:space-between;
  align-items:center;
}
.footer small{
  font-size:11px;
  color:#94a3b8;
  text-transform: uppercase;
  font-weight: 600;
  letter-spacing: 0.5px;
}
.footer strong{
  font-size:14px;
  color: #667eea;
  font-weight: 600;
}

.open{
  font-size:22px;
  color:#667eea;
  text-decoration:none;
  transition: all .35s cubic-bezier(0.23, 1, 0.320, 1);
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: rgba(102, 126, 234, 0.1);
}
.open:hover{
  color:#764ba2;
  transform: translateX(4px);
  background: rgba(102, 126, 234, 0.2);
}

@media(max-width:768px){
  .grid{grid-template-columns:1fr}
  .header{padding:20px 16px}
  .page-wrapper{padding:16px}
}
.breadcrumb-link{color:#0f172a;text-decoration:none}
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
    <a href="index.php#pengembangan-highlight" class="breadcrumb-link">Peningkatan Kapasitas</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-active"><?= $breadcrumbTitle ?></span>
  </div>

  <!-- HEADER -->
  <div class="header">
    <h2><?= $breadcrumbTitle ?></h2>
    <p><?= $subtitle ?></p>
  </div>

  <!-- GRID -->
  <div class="grid">
  <?php
    $q = mysqli_query($koneksi,"
      SELECT m.id_media, m.judul, m.topik, m.deskripsi, m.link, m.id_sub_jenis
      FROM media m
      WHERE m.id_sub_jenis = 5
      ORDER BY m.id_media DESC
    ");

    if (mysqli_num_rows($q) == 0) {
        echo '<div style="color:#64748b">Data pengembangan tidak tersedia</div>';
    }

    while($m = mysqli_fetch_assoc($q)){
  ?>
    <div class="card">
      <div class="thumb">
        <?php
        // Cek apakah link adalah YouTube
        $isYoutube = false;
        $youtubeId = '';
        if (!empty($m['link'])) {
          if (preg_match('~(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|shorts/))([\w-]{11})~i', $m['link'], $ytMatch)) {
            $isYoutube = true;
            $youtubeId = $ytMatch[1];
          }
        }
        ?>
        <?php if($isYoutube): ?>
          <iframe width="100%" height="180" style="border-radius:12px;" src="https://www.youtube.com/embed/<?= htmlspecialchars($youtubeId) ?>" frameborder="0" allowfullscreen></iframe>
        <?php else: ?>
          <i class="bi bi-play-circle"></i>
        <?php endif; ?>
      </div>
      <div class="body">
        <span class="badge primary">
          <i class="bi bi-star"></i> Pengembangan
        </span>
        <h4><?= htmlspecialchars($m['judul']) ?></h4>
        <p><?= htmlspecialchars($m['deskripsi']) ?></p>
        <div class="footer">
          <div>
            <small>Topik</small><br>
            <strong><?= htmlspecialchars($m['topik']) ?></strong>
          </div>
          <?php if(!empty($m['link'])){ ?>
            <a href="<?= htmlspecialchars($m['link']) ?>" target="_blank" class="open" title="Buka Link">
              <i class="bi bi-arrow-right"></i>
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
