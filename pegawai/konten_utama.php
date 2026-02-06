<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

// Get sub_jenis from URL parameter
$id_sub_jenis = isset($_GET['sub']) ? (int)$_GET['sub'] : 0;

// Validate sub_jenis exists dan belong to kategori 'Utama'
$subJenisQ = mysqli_query($koneksi, "
    SELECT sj.id_sub_jenis, sj.nama_sub_jenis, j.id_jenis, j.nama_jenis, k.nama_kategori
    FROM sub_jenis sj
    JOIN jenis j ON sj.id_jenis = j.id_jenis
    JOIN kategori k ON j.id_kategori = k.id_kategori
    WHERE sj.id_sub_jenis = " . $id_sub_jenis . " AND k.nama_kategori = 'Utama'
");

if (!$subJenisQ || mysqli_num_rows($subJenisQ) == 0) {
    header("Location: index.php");
    exit;
}

$subJenisData = mysqli_fetch_assoc($subJenisQ);
$namaSubJenis = $subJenisData['nama_sub_jenis'];
$namaJenis = $subJenisData['nama_jenis'];
$id_jenis = $subJenisData['id_jenis'];

// Get media data untuk sub_jenis ini
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
<link rel="icon" href="../../images/sikumbang.ico" type="image/x-icon">
<title><?= htmlspecialchars($namaSubJenis) ?> - Konten Utama</title>
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
  padding:32px;
  margin-bottom:32px;
  box-shadow:0 4px 6px rgba(0,0,0,0.07);
}
.header h1{
  margin:0 0 8px 0;
  font-size:32px;
  font-weight:700;
}
.header p{
  margin:0;
  color:#64748b;
  font-size:15px;
}

/* ===== CONTENT GRID ===== */
.content-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
  gap:20px;
  margin-top:20px;
}

.content-card{
  background:#fff;
  border-radius:16px;
  overflow:hidden;
  box-shadow:0 2px 4px rgba(0,0,0,0.05);
  transition:all 0.3s ease;
}

.content-card:hover{
  box-shadow:0 8px 16px rgba(0,0,0,0.1);
  transform:translateY(-4px);
}

.card-header{
  background:linear-gradient(135deg,#2563eb,#1e40af);
  padding:20px;
  color:#fff;
}

.card-header-title{
  font-size:16px;
  font-weight:600;
  margin:0 0 4px 0;
}

.card-header-meta{
  font-size:13px;
  opacity:0.8;
  margin:0;
}

.card-body{
  padding:20px;
}

.card-body-title{
  font-size:15px;
  font-weight:600;
  margin:0 0 8px 0;
  color:#0f172a;
}

.card-body-desc{
  font-size:14px;
  color:#64748b;
  margin:0 0 12px 0;
  line-height:1.5;
}

.card-body-date{
  font-size:12px;
  color:#94a3b8;
  margin:0;
}

.card-footer{
  padding:0 20px 20px 20px;
}

.btn-link{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:10px 16px;
  background:#2563eb;
  color:#fff;
  text-decoration:none;
  border-radius:8px;
  font-size:14px;
  font-weight:500;
  transition:all 0.2s ease;
}

.btn-link:hover{
  background:#1e40af;
  transform:translateX(2px);
}

/* ===== EMPTY STATE ===== */
.empty-state{
  text-align:center;
  padding:60px 20px;
  background:#fff;
  border-radius:16px;
}

.empty-state-icon{
  font-size:64px;
  color:#cbd5e1;
  margin-bottom:16px;
}

.empty-state-title{
  font-size:18px;
  font-weight:600;
  color:#0f172a;
  margin-bottom:8px;
}

.empty-state-desc{
  color:#64748b;
  font-size:14px;
}

/* ===== RESPONSIVE ===== */
@media(max-width:768px){
  body{padding:16px}
  .header{padding:20px}
  .header h1{font-size:24px}
  .content-grid{grid-template-columns:1fr}
}
</style>
</head>
<body>

<div class="page-wrapper">

  <!-- BREADCRUMB -->
  <div class="breadcrumb-custom">
    <i class="bi bi-house-fill"></i>
    <a href="index.php">Beranda</a>
    <span>/</span>
    <a href="index.php#konten-utama">Konten Utama</a>
    <span>/</span>
    <span class="breadcrumb-active"><?= htmlspecialchars($namaSubJenis) ?></span>
  </div>

  <!-- HEADER -->
  <div class="header">
    <h1><?= htmlspecialchars($namaSubJenis) ?></h1>
    <p>Kategori: <strong><?= htmlspecialchars($namaJenis) ?></strong></p>
  </div>

  <!-- CONTENT GRID -->
  <?php if (count($mediaList) > 0): ?>
    <div class="content-grid">
      <?php foreach ($mediaList as $idx => $media): ?>
        <div class="content-card">
          <div class="card-header">
            <h3 class="card-header-title"><?= htmlspecialchars($media['judul']) ?></h3>
            <p class="card-header-meta">
              <?= !empty($media['topik']) ? htmlspecialchars($media['topik']) : 'Tidak ada topik' ?>
            </p>
          </div>
          
          <div class="card-body">
            <?php if (!empty($media['deskripsi'])): ?>
              <p class="card-body-desc"><?= htmlspecialchars(substr($media['deskripsi'], 0, 100)) ?>...</p>
            <?php endif; ?>
            <p class="card-body-date">
              Ditambahkan: <?= date('d M Y', strtotime($media['created_at'])) ?>
            </p>
          </div>

          <?php if (!empty($media['link'])): ?>
            <div class="card-footer">
              <a href="<?= htmlspecialchars($media['link']) ?>" target="_blank" class="btn-link">
                <i class="bi bi-box-arrow-up-right"></i>
                Buka
              </a>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

  <?php else: ?>
    <!-- EMPTY STATE -->
    <div class="empty-state">
      <div class="empty-state-icon">
        <i class="bi bi-inbox"></i>
      </div>
      <h3 class="empty-state-title">Tidak ada konten</h3>
      <p class="empty-state-desc">Belum ada konten tersedia untuk kategori ini. Silakan coba lagi nanti.</p>
    </div>
  <?php endif; ?>

</div>

</body>
</html>
