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
    $where = "WHERE a.jenis_aset = '$filterJenis'";

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

    <div class="section-title text-center mb-5">
      <h2 class="fw-bold"><?= $title ?></h2>
      <p class="text-muted"><?= $subtitle ?></p>
    </div>

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
          a.id_aset,
          a.nama_aset,
          a.deskripsi,
          a.jenis_aset,
          a.foto_aset,
          a.link_aset,
          a.tanggal_ditambahkan,
          u.nama AS pemegang
        FROM aset a
        LEFT JOIN user u ON a.nip_pemegang = u.nip
        $where
        ORDER BY a.tanggal_ditambahkan DESC
      ");

      if (mysqli_num_rows($query) == 0) {
        echo '<div class="col-12"><div class="text-center text-muted py-5">Data aset tidak tersedia</div></div>';
      }

      while ($aset = mysqli_fetch_assoc($query)) {

        switch ($aset['jenis_aset']) {
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
          default:
            $jenis = 'Aset Lainnya';
            $badge = 'secondary';
            $icon  = 'bi-file';
        }
      ?>

      <div class="col-lg-4 col-md-6">
        <div class="card asset-card h-100 border-0">

          <!-- FOTO ASET / DEFAULT NO IMAGE -->
          <div class="asset-img">
            <?php if (!empty($aset['foto_aset'])) { ?>
              <img 
                src="../uploads/aset/<?= htmlspecialchars($aset['foto_aset']) ?>" 
                alt="<?= htmlspecialchars($aset['nama_aset']) ?>"
                onerror="this.src='assets/img/noimage.png'">
            <?php } else { ?>
              <img 
                src="assets/img/noimage.png"
                alt="No Image"
                class="img-fluid no

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

.aset-section {
  margin-top: 120px;
}

.asset-card {
  border-radius: 18px;
  box-shadow: 0 10px 30px rgba(0,0,0,.06);
  transition: .3s ease;
}

.asset-img {
  height: 200px;
  overflow: hidden;
  border-radius: 18px 18px 0 0;
}

.asset-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.noimage {
  object-fit: contain;
  padding: 30px;
  opacity: .8;
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
