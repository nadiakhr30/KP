<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

/* ================= FILTER ================= */
$filterJenis = $_GET['jenis'] ?? 'all';
$where = "";

/* Breadcrumb & Title */
$breadcrumbTitle = "Daftar Aset";
$subtitle = "Aset visual, barang, dan lisensi Humas";

if ($filterJenis == '1') {
    $breadcrumbTitle = "Aset Visual";
    $countQ = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset WHERE id_jenis_aset = '1'");
    $countRow = mysqli_fetch_assoc($countQ);
    $subtitle = "Total: " . $countRow['total'] . " aset visual tersedia";
    $where = "WHERE a.id_jenis_aset = '1'";
} elseif ($filterJenis == '2') {
    $breadcrumbTitle = "Aset Barang";
    $countQ = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset WHERE id_jenis_aset = '2'");
    $countRow = mysqli_fetch_assoc($countQ);
    $subtitle = "Total: " . $countRow['total'] . " aset barang tersedia";
    $where = "WHERE a.id_jenis_aset = '2'";
} elseif ($filterJenis == '3') {
    $breadcrumbTitle = "Aset Lisensi";
    $countQ = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset WHERE id_jenis_aset = '3'");
    $countRow = mysqli_fetch_assoc($countQ);
    $subtitle = "Total: " . $countRow['total'] . " aset lisensi tersedia";
    $where = "WHERE a.id_jenis_aset = '3'";
}
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

/* ===== GRID ===== */
.grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
  gap:24px;
}

/* ===== CARD (TIDAK DIUBAH) ===== */
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

.thumb{height:160px;background:#e5e7eb}
.thumb img{width:100%;height:100%;object-fit:cover}

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

  <!-- BREADCRUMB -->
  <div class="breadcrumb-custom">
    <a href="index.php" class="breadcrumb-link">
        <i class="bi bi-house-fill"></i>
    </a>
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
      SELECT a.*, u.nama AS pemegang
      FROM aset a
      LEFT JOIN pegawai u ON a.nip = u.nip
      $where
      ORDER BY a.id_aset DESC
    ");

    if (mysqli_num_rows($q) == 0) {
        echo '<div style="color:#64748b">Data aset tidak tersedia</div>';
    }

    while($a = mysqli_fetch_assoc($q)){
      switch($a['id_jenis_aset']){
        case '1': $jenis="Visual"; $badge="primary"; $icon="bi-image"; break;
        case '2': $jenis="Barang"; $badge="success"; $icon="bi-box"; break;
        case '3': $jenis="Lisensi"; $badge="warning"; $icon="bi-patch-check"; break;
        default:  $jenis="Lainnya"; $badge="secondary"; $icon="bi-file";
      }
  ?>
    <div class="card">
      <div class="thumb">
        <img src="assets/img/noimage.png">
      </div>
      <div class="body">
        <span class="badge <?= $badge ?>">
          <i class="bi <?= $icon ?>"></i> <?= $jenis ?>
        </span>
        <h4><?= htmlspecialchars($a['nama']) ?></h4>
        <p><?= htmlspecialchars($a['keterangan']) ?></p>
        <div class="footer">
          <div>
            <small>Penanggung Jawab</small><br>
            <strong><?= htmlspecialchars($a['pemegang'] ?? '-') ?></strong>
          </div>
          <?php if(!empty($a['link'])){ ?>
            <a href="<?= htmlspecialchars($a['link']) ?>" target="_blank" class="open">
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
