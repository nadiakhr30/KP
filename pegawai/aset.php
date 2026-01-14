<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

/* ================= FILTER JENIS ================= */
$filterJenis = $_GET['jenis'] ?? 'all';

$where = "";
$title = "Daftar Aset";
$subtitle = "Aset visual, barang, dan lisensi Humas";

if (in_array($filterJenis, ['1','2','3'])) {
    $where = "WHERE a.jenis = '$filterJenis'";

    if ($filterJenis == '1') {
        $title = "Aset Visual";
        $subtitle = "Kumpulan aset visual Humas";
    } elseif ($filterJenis == '2') {
        $title = "Aset Barang";
        $subtitle = "Kumpulan aset barang Humas";
    } elseif ($filterJenis == '3') {
        $title = "Aset Lisensi";
        $subtitle = "Kumpulan aset lisensi Humas";
    }
}

ob_start();
?>

<section class="section aset-section">
  <div class="container" data-aos="fade-up">

    <!-- JUDUL DINAMIS -->
    <div class="section-title text-center mb-5">
      <h2 class="fw-bold"><?= $title ?></h2>
      <p class="text-muted"><?= $subtitle ?></p>
    </div>

    <!-- TAB FILTER (OPSIONAL TAPI KEREN) -->
    <div class="text-center mb-4">
      <a href="aset.php" class="btn btn-outline-secondary btn-sm">Semua</a>
      <a href="aset.php?jenis=1" class="btn btn-outline-primary btn-sm">Visual</a>
      <a href="aset.php?jenis=2" class="btn btn-outline-success btn-sm">Barang</a>
      <a href="aset.php?jenis=3" class="btn btn-outline-warning btn-sm">Lisensi</a>
    </div>

    <div class="row g-4">

      <?php
      $query = mysqli_query($koneksi, "
        SELECT 
          a.nama,
          a.link,
          a.keterangan,
          a.jenis,
          u.nama AS pemegang
        FROM aset a
        LEFT JOIN user u ON a.pemegang = u.id_user
        $where
        ORDER BY a.id_aset DESC
      ");

      if (mysqli_num_rows($query) == 0) {
        echo '<div class="text-center text-muted">Data aset tidak tersedia</div>';
      }

      while ($aset = mysqli_fetch_assoc($query)) {

        switch ($aset['jenis']) {
          case '1':
            $jenis = 'Aset Visual';
            $badge = 'primary';
            $icon  = 'bi-image';
            break;
          case '2':
            $jenis = 'Aset Barang';
            $badge = 'success';
            $icon  = 'bi-box-seam';
            break;
          case '3':
            $jenis = 'Aset Lisensi';
            $badge = 'warning';
            $icon  = 'bi-patch-check';
            break;
        }
      ?>

      <div class="col-lg-4 col-md-6">
        <div class="card asset-card h-100 border-0">

          <div class="card-body">
            <span class="badge bg-<?= $badge ?> mb-3"><?= $jenis ?></span>

            <h5 class="fw-bold"><?= htmlspecialchars($aset['nama']) ?></h5>

            <p class="text-muted small">
              <?= nl2br(htmlspecialchars($aset['keterangan'])) ?>
            </p>
          </div>

          <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
            <div class="small">
              <div class="text-muted fw-semibold">Penanggung Jawab</div>
              <hr>
              <div class="fw-bold">
                <i class="bi bi-person-check"></i>
                <?= htmlspecialchars($aset['pemegang'] ?? '-') ?>
              </div>
            </div>

            <?php if ($aset['link']) { ?>
              <a href="<?= htmlspecialchars($aset['link']) ?>" 
                 target="_blank"
                 class="asset-icon text-<?= $badge ?>">
                <i class="bi <?= $icon ?>"></i>
              </a>
            <?php } ?>
          </div>

        </div>
      </div>

      <?php } ?>

    </div>
  </div>
</section>

<?php
$content = ob_get_clean();
ob_start();
?>

<style>
  .page-aset #header,
.page-aset #header.header-scrolled {
  background: #3d4d6a !important;
  box-shadow: 0 2px 12px rgba(0,0,0,.08);
}

.page-aset .navbar a {
  color: #222 !important;
}

.page-aset .navbar a:hover,
.page-aset .navbar .active {
  color: #0d6efd !important;
}
.aset-section {
  margin-top: 120px;
}

.asset-card {
  border-radius: 18px;
  box-shadow: 0 10px 30px rgba(0,0,0,.06);
  transition: .3s ease;
}

.asset-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 25px 45px rgba(0,0,0,.12);
}

.asset-icon {
  font-size: 1.8rem;
  transition: .3s ease;
}

.asset-icon:hover {
  transform: scale(1.25);
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
  document.body.classList.add("page-aset");
});
</script>

<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
