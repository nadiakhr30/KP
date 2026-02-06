<?php
ob_start();
session_start();
require '../koneksi.php';

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

// Kalender - Get PIC data
$jadwalkalender = [];
$qKalender = mysqli_query($koneksi, "
  SELECT 
    j.id_jadwal,
    j.topik,
    j.judul_kegiatan,
    j.tanggal_penugasan,
    j.tanggal_rilis,
    j.tim,
    j.keterangan,
    j.status,
    j.dokumentasi
  FROM jadwal j
  ORDER BY j.tanggal_rilis DESC
");

while ($row = mysqli_fetch_assoc($qKalender)) {
  $id_jadwal = $row['id_jadwal'];
  $qPic = mysqli_query($koneksi, "
    SELECT u.nip, u.nama, jp.nama_jenis_pic
    FROM pic p
    JOIN pegawai u ON p.nip = u.nip
    JOIN jenis_pic jp ON p.id_jenis_pic = jp.id_jenis_pic
    WHERE p.id_jadwal = " . (int)$id_jadwal . "
    ORDER BY jp.nama_jenis_pic
  ");

  $picData = [];
  $nipList = [];
  if ($qPic) {
    while ($pic = mysqli_fetch_assoc($qPic)) {
      $picData[$pic['nama_jenis_pic']] = $pic['nama'];
      $nipList[] = $pic['nip'];
    }
  }

  $isPic = in_array($_SESSION['pegawai']['nip'], $nipList);
  
  if ($row['status'] == 0) $color = '#e84118';
  else if ($row['status'] == 1) $color = '#fbc531';
  else if ($row['status'] == 2) $color = '#44bd32';
  else $color = '#718093';
  
  // Build PIC text dynamically
  $picText = [];
  foreach ($picData as $jenis => $nama) {
    $picText[] = "<b>$jenis:</b> $nama";
  }
  $picDisplay = count($picText) > 0 ? implode("<br>", $picText) : "-";
  
  $jadwalkalender[] = [
    'id'    => $row['id_jadwal'],
    'title' => $row['judul_kegiatan'],
    'start' => $row['tanggal_rilis'],
    'color' => $color,
    'extendedProps' => [
      'topik' => $row['topik'],
      'tanggal_penugasan' => $row['tanggal_penugasan'],
      'tim' => $row['tim'],
      'status' => $row['status'],
      'keterangan' => $row['keterangan'],
      'pic_display' => $picDisplay,
      'dokumentasi' => !empty($row['dokumentasi']) ? $row['dokumentasi'] : '',
      'link_instagram' => !empty($row['link_instagram']) ? $row['link_instagram'] : '',
      'link_facebook' => !empty($row['link_facebook']) ? $row['link_facebook'] : '',
      'link_youtube' => !empty($row['link_youtube']) ? $row['link_youtube'] : '',
      'link_website' => !empty($row['link_website']) ? $row['link_website'] : '',
      'isPic' => $isPic
    ]
  ];
}
?>



  <!-- ICON NOTIFIKASI DI NAVBAR dipindahkan ke layout.php agar tidak bentrok dengan user/profile -->
  <main class="main">
    
    <!-- Beranda -->
    <section id="beranda" class="hero section dark-background position-relative">

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
            <h1>Sistem Kehumasan Badan Pusat Statistik Kabupaten Bangkalan</h1>
            <p>Menyajikan data akurat untuk mendukung perencanaan pembangunan daerah.</p>
            <div class="d-flex">
              <a href="https://youtu.be/0DGiy6TEdS0?si=NJWU-PYx0wBA2xEf"
                class="glightbox btn-watch-video d-flex align-items-center">
                <i class="bi bi-play-circle"></i><span style="text-decoration: none;">Watch Video</span>
              </a>
            </div>
          </div>

          <div class="col-lg-6 order-1 order-lg-2 hero-img"
              data-aos="zoom-out" data-aos-delay="200">
            <img src="assets/img/graph.png" class="img-fluid animated" alt="">
          </div>
        </div>
      </div>

      <!-- SVG WAVES -->
      <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg"
          viewBox="0 24 150 28" preserveAspectRatio="none">
        <defs>
          <path id="wave-path"
            d="M-160 44c30 0 58-18 88-18s58 18 88 18
              58-18 88-18 58 18 88 18v44h-352z" />
        </defs>

        <g class="wave1">
          <use href="#wave-path" x="50" y="3" fill="rgba(255,255,255,.1)" />
        </g>
        <g class="wave2">
          <use href="#wave-path" x="50" y="5" fill="rgba(255,255,255,.2)" />
        </g>
        <g class="wave3">
          <use href="#wave-path" x="50" y="9" fill="#fff" />
        </g>
      </svg>

    </section>
    <!-- /Beranda -->


 <!-- Kalender & Jadwal -->
    <section id="kalender-jadwal" class="about section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Kalender & Jadwal</h2>
        <p class="text-muted mb-0">Jadwal rilis dan kegiatan kehumasan</p>
        <span id="badgeUrgent" style="display:none;background:#e84118;color:#fff;padding:4px 12px;border-radius:999px;font-size:13px;font-weight:600;margin-left:12px;">Tugas Mendesak!</span>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-12">
            <div class="card p-4 shadow-sm border-0">
              <div class="card-block">
                <div id="calendar"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Jadwal -->
      <div class="modal fade" id="jadwalModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <div>
                <h5 class="text-muted" id="modalTopik"></h5>
                <h3 class="modal-title mb-0" id="modalJudul"></h3>
              </div>
              <button type="button" class="btn btn-outline-danger btn-close-circle position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
            <div class="modal-body">
              <table class="table table-sm table-borderless">
                <tr>
                  <th width="180">Tanggal Penugasan</th>
                  <td id="modalTanggalPenugasan"></td>
                </tr>
                <tr>
                  <th>Target Rilis</th>
                  <td id="modalTargetRilis"></td>
                </tr>
                <tr>
                  <th>Tim</th>
                  <td id="modalTim"></td>
                </tr>
                <tr>
                  <th>Status</th>
                  <td id="modalStatus"></td>
                </tr>
                <tr>
                  <th>PIC</th>
                  <td>
                    <span id="modalPIC"></span>
                  </td>
                </tr>
                <tr>
                  <th>Keterangan</th>
                  <td id="modalKeterangan"></td>
                </tr>
                <tr id="rowDokumentasi">
                  <th>Dokumentasi</th>
                  <td class="d-flex align-items-center gap-2">
                    <a id="modalDokumentasi" target="_blank" class="icon-link" style="display:none; font-size: 1.2rem;">
                      <i class="bi bi-eye-fill"></i>
                    </a>
                    <span id="docPlaceholder" style="display:none; color:#999;">Belum ada dokumentasi</span>
                    <a id="editDokumentasiBtn" class="btn btn-sm btn-outline-primary" title="Edit Dokumentasi" href="#" style="display:none;">
                      <i class="bi bi-pencil"></i>
                    </a>
                  </td>
                </tr>
                <tr id="rowLink">
                  <th>Link Publikasi</th>
                  <td class="d-flex align-items-center gap-2 flex-wrap">
                    <div id="modalLinks" class="d-flex gap-2"></div>
                    <a id="editPublikasiBtn" class="btn btn-sm btn-outline-primary" title="Edit Link Publikasi" style="display:none;">
                      <i class="bi bi-pencil"></i>
                    </a>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

    </section><!-- /Kalender & Jadwal -->

     <!-- Brankas Humas -->

    <section id="brankas" class="about section">
    
    

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-12">
            <div class="service-item humas-card position-relative text-center">
          <div class="icon"><i class="bi bi-folder2-open icon"></i></div>
         <h2>Brankas Humas</h2>
        <p class="text-muted mb-0">Penyimpanan dokumen dan arsip publikasi</p>
          

          <div class="humas-overlay">
            <?php
            // Ambil link Google Drive Humas dari tabel media (id_sub_jenis=23)
            $driveLink = 'https://drive.google.com';
            $qDrive = mysqli_query($koneksi, "SELECT link FROM media WHERE id_sub_jenis=23 ORDER BY id_media DESC LIMIT 1");
            if ($qDrive && $rowDrive = mysqli_fetch_assoc($qDrive)) {
                if (!empty($rowDrive['link'])) {
                    $driveLink = $rowDrive['link'];
                }
            }
            ?>
            <a href="<?= htmlspecialchars($driveLink) ?>" target="_blank" class="overlay-item">
              <i class="bi bi-google"></i>
              <span>Google Drive Humas</span>
            </a>
          </div>
            </div>
          </div>
        </div>
      </div>

    </section><!-- /Brankas Humas -->

<section id="humas" class="services section light-background">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2> Ruang Humas</h2>
    <p>Mengelola komunikasi dan publikasi institusi secara terintegrasi dan efisien</p>
  </div>

  <div class="container">
    <div class="row gy-3 justify-content-center">

            <!-- Ruang Humas -->
      <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
        <div class="service-item humas-card position-relative text-center">
          <div class="icon"><i class="bi bi-people icon"></i></div>
          <h4>Ruang Humas</h4>
          <p>Koordinasi dan kolaborasi pegawai kehumasan.</p>

          <!-- Overlay -->
          <div class="humas-overlay">
            <a href="struktur_humas.php" class="overlay-item">
              <i class="bi bi-diagram-3"></i>
              <span>Struktur Humas</span>
            </a>
            <a href="jadwal_konten.php" class="overlay-item">
              <i class="bi bi-calendar-event"></i>
              <span>Jadwal Konten Humas</span>
            </a>
          </div>
        </div>
      </div>

      

      <!-- Aset Humas -->
      <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="300">
        <div class="service-item humas-card position-relative text-center">
          <div class="icon"><i class="bi bi-journal-bookmark icon"></i></div>
          <h4>Aset Humas</h4>
          <p>Manajemen aset dan materi komunikasi.</p>

          <div class="humas-overlay">
            <a href="aset.php?jenis=1" class="overlay-item">
              <i class="bi bi-image"></i>
              <span>Aset Visual</span>
            </a>
            <a href="aset.php?jenis=2" class="overlay-item">
              <i class="bi bi-box-seam"></i>
              <span>Aset Barang</span>
            </a>
            <a href="aset.php?jenis=3" class="overlay-item">
              <i class="bi bi-patch-check"></i>
              <span>Aset Lisensi</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- End Humas Section -->



    <!-- Manajemen Link -->
    <section id="services" class="services section light-background">
      </section><!--End Manajemen Link Section -->

    <!-- Work Process Section -->
    <section id="link" class="work-process section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Manajemen Link</h2>
        <p>Temukan semua tautan resmi BPS dengan mudah dalam satu tempat untuk referensi dan data cepat.</p>
      </div><!-- End Section Title -->
    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="steps-wrapper overflow-hidden">
        <div class="steps-track d-flex" id="stepsTrack">

          <?php
          $query = mysqli_query($koneksi, "SELECT * FROM link ORDER BY id_link ASC");
          while ($row = mysqli_fetch_assoc($query)) {
          ?>
            <!-- Card -->
            <div class="col-lg-4 col-md-6 col-12 px-3 steps-slide">
              <a href="<?= $row['link'] ?>" target="_blank" class="steps-link">

                <div class="steps-item">

                  <!-- IMAGE / ICON -->
                  <div class="steps-image">
                    <?php if (!empty($row['gambar'])) { ?>
                      <img src="assets/img/steps/<?= $row['gambar'] ?>"
                          alt="<?= $row['nama_link'] ?>"
                          class="img-fluid">
                    <?php } else { ?>
                      <div class="icon-placeholder text-center">
                        <img src="assets/img/noimage.png" 
                            alt="No Image"
                            class="img-fluid noimage">
                      </div>
                    <?php } ?>
                  </div>

                  <!-- CONTENT -->
                  <div class="steps-content">
                    <div class="steps-number">
                      <?= str_pad($row['id_link'], 2, '0', STR_PAD_LEFT) ?>
                    </div>

                    <h3><?= $row['nama_link'] ?></h3>

                    <p>
                      Menyajikan data dan informasi resmi yang dapat diakses langsung
                      melalui website terkait.
                    </p>

                    <div class="steps-features">
                      <div class="feature-item">
                        <i class="bi bi-check-circle"></i>
                        <span>Data Resmi & Terverifikasi</span>
                      </div>
                      <div class="feature-item">
                        <i class="bi bi-check-circle"></i>
                        <span>Akses Publik Online</span>
                      </div>
                    </div>
                  </div>

                </div>
              </a>
            </div>
          <?php } ?>

        </div>
      </div>

      <!-- NAVIGATION -->
      <div class="d-flex justify-content-center gap-3 mt-4">
        <button id="prevSlide" class="btn btn-outline-primary">
          <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button id="nextSlide" class="btn btn-outline-primary">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div>

    </div>
    </section><!-- End Work Process Section -->


    <!-- Jargon BPS -->
    <section id="call-to-action" class="call-to-action section dark-background">

      <img src="assets/img/bg/BPS_BKL.png" alt="">

      <div class="container">

        <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
          <div class="col-xl-9 text-center">
            <h3>BADAN PUSAT STATISTIK </h3>
            <p>Melayani Dengan Hati, Bersama Membangun Negri</p>
          </div>
      </div>

    </section><!-- /Jargon BPS  -->

    <!--Sumber Daya -->
    <section id="sumberdaya" class="team section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Sumber Daya</h2>
        <p>
        Sumber daya visual media sosial BPS
        </p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

        <div class="asset-grid">

        <!-- TEMPLATE MEDSOS & DOKUMENTASI, GALERI FOTO, GALERI VIDEO, LAPORAN -->
          <?php
          // Ambil semua jenis (1 untuk Template Medsos, 2,6,7,8 untuk Dokumentasi/Galeri)
          $jenisQ = mysqli_query($koneksi, "SELECT id_jenis, nama_jenis FROM jenis WHERE id_jenis IN (1, 2, 6, 7, 8) ORDER BY id_jenis ASC");
          if ($jenisQ && mysqli_num_rows($jenisQ) > 0) {
              while ($j = mysqli_fetch_assoc($jenisQ)) {
                  $id_jenis = $j['id_jenis'];
                  // Ambil sub jenis untuk setiap jenis
                  $subQ = mysqli_query($koneksi, "SELECT id_sub_jenis, nama_sub_jenis FROM sub_jenis WHERE id_jenis = " . (int)$id_jenis . " ORDER BY nama_sub_jenis ASC");
                  
                  // Icon mapping untuk setiap jenis
                  $iconMap = [
                    1 => 'bi-layout-text-window-reverse', // Template Medsos
                    2 => 'bi-file-earmark-text',      // Dokumentasi
                    6 => 'bi-image',                  // Galeri Foto
                    7 => 'bi-camera-video-fill',      // Galeri Video
                    8 => 'bi-file-earmark-text'       // Laporan
                  ];
                  $icon = isset($iconMap[$id_jenis]) ? $iconMap[$id_jenis] : 'bi-folder2-open';
          ?>
          <div class="asset-card">
            <div class="card-content">
              <div class="icon-circle blue">
                <i class="bi <?= $icon ?>"></i>
              </div>
              <h4><?= htmlspecialchars($j['nama_jenis']) ?></h4>
              <p>Konten visual siap pakai</p>
            </div>

            <div class="card-overlay">
              <div class="overlay-menu">
                <?php if ($subQ && mysqli_num_rows($subQ) > 0): ?>
                  <?php while ($s = mysqli_fetch_assoc($subQ)): ?>
                    <?php
                      // Routing logic untuk setiap jenis
                      if ($id_jenis == 1) {
                        $subHref = "media.php?sub=" . urlencode($s['id_sub_jenis']);
                      } else if ($id_jenis == 6) {
                        $subHref = "galeri_foto.php?sub=" . urlencode($s['id_sub_jenis']);
                      } else if ($id_jenis == 7) {
                        $subHref = "galeri_video.php?sub=" . urlencode($s['id_sub_jenis']);
                      } else if ($id_jenis == 8) {
                        $subHref = "galeri_laporan.php?sub=" . urlencode($s['id_sub_jenis']);
                      } else if ($id_jenis == 2) {
                        $subHref = "dokumentasi.php?sub=" . urlencode($s['id_sub_jenis']);
                      } else {
                        $subHref = "media.php?sub=" . urlencode($s['id_sub_jenis']);
                      }
                    ?>
                    <a href="<?= $subHref ?>"><?= htmlspecialchars($s['nama_sub_jenis']) ?></a>
                  <?php endwhile; ?>
                <?php else: ?>
                  <span style="color:#fff;">Tidak ada sub jenis tersedia</span> 
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php
              }
          } else {
              echo '<div style="color:#fff;">Data tidak tersedia</div>';
          }
        ?>
        </div>
    </section><!-- /Sumber Daya -->

    <!-- Broadcast Section -->
    <section id="broadcast" class="broadcast section light-background position-relative">
      
      <!-- Animated Background -->
      <div class="broadcast-bg-animated"></div>

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Kebutuhan Broadcast</h2>
        <p>Akses semua sumber daya broadcast dalam satu tempat yang terpusat</p>
      </div>

      <div class="container position-relative">
        <div class="row g-4 justify-content-center">

          <!-- Video Operator -->
          <div class="col-lg-5 col-md-10" data-aos="fade-up" data-aos-delay="100">
            <a href="broadcast_media.php" class="broadcast-link">
              <div class="broadcast-card-v3">
                <div class="card-glow"></div>
                <div class="accent-line"></div>
                <div class="card-header-v3">
                  <div class="header-bg-gradient"></div>
                  <div class="icon-container">
                    <div class="icon-bg"></div>
                    <i class="bi bi-camera-video-fill"></i>
                  </div>
                  <h3>Video Operator</h3>
                  <p>Template dan panduan lengkap untuk operator video profesional</p>
                </div>
                <div class="card-features">
                  <div class="feature-list">
                    <div class="feature-dot"></div>
                    <div class="feature-dot"></div>
                    <div class="feature-dot"></div>
                  </div>
                </div>
                <div class="card-footer-cta">
                  <span class="cta-text">Buka Folder</span>
                  <div class="arrow-icon">
                    <i class="bi bi-arrow-right"></i>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <!-- Template OBS -->
          <div class="col-lg-5 col-md-10" data-aos="fade-up" data-aos-delay="200">
            <a href="obs_media.php" class="broadcast-link">
              <div class="broadcast-card-v3 variant-pink">
                <div class="card-glow"></div>
                <div class="accent-line"></div>
                <div class="card-header-v3">
                  <div class="header-bg-gradient"></div>
                  <div class="icon-container">
                    <div class="icon-bg"></div>
                    <i class="bi bi-sliders2"></i>
                  </div>
                  <h3>Template OBS</h3>
                  <p>Konfigurasi dan template siap pakai untuk OBS Studio dengan filter profesional</p>
                </div>
                <div class="card-features">
                  <div class="feature-list">
                    <div class="feature-dot"></div>
                    <div class="feature-dot"></div>
                    <div class="feature-dot"></div>
                  </div>
                </div>
                <div class="card-footer-cta">
                  <span class="cta-text">Buka Folder</span>
                  <div class="arrow-icon">
                    <i class="bi bi-arrow-right"></i>
                  </div>
                </div>
              </div>
            </a>
          </div>

        </div>
      </div>

    </section><!-- /Broadcast Section -->

<!-- Pengembangan Highlight Section -->
    <section id="pengembangan" class="pengembangan-highlight section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Peningkatan Kapasitas</h2>
        <p>Sumber daya inovasi dan pengembangan untuk tim kami</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="pengembangan-grid">

        <?php
          // Ambil jenis dengan id_jenis = 4 (Pengembangan)
          $jenisQ = mysqli_query($koneksi, "SELECT id_jenis, nama_jenis FROM jenis WHERE id_jenis = 4");
          if ($jenisQ && mysqli_num_rows($jenisQ) > 0) {
              $delay = 100;
              while ($j = mysqli_fetch_assoc($jenisQ)) {
                  // Ambil sub jenis dengan id_jenis = 4
                  $subQ = mysqli_query($koneksi, "SELECT id_sub_jenis, nama_sub_jenis FROM sub_jenis WHERE id_jenis = 4 ORDER BY nama_sub_jenis ASC");
                  
                  if ($subQ && mysqli_num_rows($subQ) > 0) {
                      while ($s = mysqli_fetch_assoc($subQ)) {
        ?>
          <div class="pengembangan-item" data-aos="zoom-in" data-aos-delay="<?= $delay ?>">
            <div class="pengembangan-card">

              <div class="card-header-main">
                <div class="icon-wrap">
                  <div class="icon-circle"><i class="bi bi-folder2-open"></i></div>
                </div>
                <h3><?= htmlspecialchars($s['nama_sub_jenis']) ?></h3>
                <p>Meningkatkan kompetensi dan inovasi melalui <?= htmlspecialchars(strtolower($s['nama_sub_jenis'])) ?> di lingkungan kehumasan.</p>
              </div>

              <div class="card-dots"><span></span><span></span><span></span></div>

              <div class="card-footer-cta">
                <a href="pengembangan.php" class="btn-explore">Buka Folder</a>
                <a href="pengembangan.php" class="cta-circle" title="Buka"><i class="bi bi-arrow-right-short"></i></a>
              </div>

            </div>
          </div>
        <?php
                        $delay += 100;
                      }
                  }
              }
          } else {
              echo '<div style="color:#fff;">Data pengembangan tidak tersedia</div>';
          }
        ?>

        </div>

      </div>

    </section><!-- /Pengembangan Highlight Section -->


  </main>
  <?php
  $content = ob_get_clean();
  ob_start();
  ?>

  <!-- FULLCALENDAR CDN -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

  <!-- BOOTSTRAP ICONS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* Sembunyikan ikon overlay sampai kartu di-hover untuk menghindari duplikat di header */
    .humas-overlay .overlay-menu a i { opacity: 0; transform: translateX(-6px); transition: opacity .18s ease, transform .18s ease; display: inline-block; }
    .service-item:hover .humas-overlay .overlay-menu a i { opacity: 1; transform: none; }

    /* Styling lingkaran ikon utama agar lebih konsisten */
    .icon-circle-type { width: 72px; height: 72px; border-radius: 14px; background: linear-gradient(180deg, rgba(86,97,255,0.06), rgba(86,97,255,0.02)); display: inline-flex; align-items: center; justify-content: center; margin: 0 auto 14px; border: 1px solid rgba(86,97,255,0.06); }
    .icon-circle-type .bi { font-size: 28px; color: #5661ff; }

    /* Folder-style icon for Dokumentasi */
    .icon-folder { width: 84px; height: 58px; position: relative; margin: 0 auto 14px; }
    .icon-folder .folder-tab { position: absolute; top: -10px; left: 10px; width: 46px; height: 18px; border-radius: 6px 6px 0 0; background: linear-gradient(180deg, #f3f4ff, #e8eaff); border: 1px solid rgba(86,97,255,0.08); }
    .icon-folder .folder-body { width: 100%; height: 100%; background: linear-gradient(180deg, rgba(86,97,255,0.06), rgba(86,97,255,0.02)); border-radius: 8px; display:flex; align-items:center; justify-content:center; border: 1px solid rgba(86,97,255,0.06); }
    .icon-folder .bi { font-size: 26px; color: #5661ff; }
    .card-main-link-icon:hover .icon-folder { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(86,97,255,0.06); transition: transform .2s ease, box-shadow .2s ease; }

    /* Membuat asset-card lebih panjang/landscape */
    .asset-grid .asset-card {
      min-height: 280px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
   
  </style>

  <script>
  // ===== NOTIFIKASI BELL BERDASARKAN DEADLINE JADWAL =====
  document.addEventListener("DOMContentLoaded", function () {
    var today = new Date();
    var events = <?= json_encode($jadwalkalender) ?>;
    var deadlineTasks = [];
    var oneDayMs = 1000 * 60 * 60 * 24;
    var twoDaysMs = oneDayMs * 2;
    
    events.forEach(function(ev) {
      var status = ev.extendedProps.status;
      if (ev.extendedProps && ev.extendedProps.isPic && (status == 0 || status == 1)) {
        var tglRilis = new Date(ev.start);
        // Bandingkan hanya tanggal (tahun, bulan, hari) tanpa jam
        var tglRilisDate = new Date(tglRilis.getFullYear(), tglRilis.getMonth(), tglRilis.getDate());
        var todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
        var selisihHari = (tglRilisDate - todayDate) / (1000 * 60 * 60 * 24);
        // Hanya tampilkan jika deadline hari ini sampai 2 hari ke depan (jangan jika sudah lewat)
        if (selisihHari >= 0 && selisihHari <= 2) {
          deadlineTasks.push(ev);
        }
      }
    });
    
    // Update notif count berdasarkan jumlah deadline yang sesuai filter
    if (deadlineTasks.length > 0) {
      document.getElementById('navbarNotif').style.display = 'inline-block';
      document.getElementById('notifCount').textContent = deadlineTasks.length;
    } else {
      document.getElementById('navbarNotif').style.display = 'none';
    }
  });
    document.addEventListener("DOMContentLoaded", function () {
      const track = document.getElementById("stepsTrack");
      const slides = document.querySelectorAll(".steps-slide");
      const nextBtn = document.getElementById("nextSlide");
      const prevBtn = document.getElementById("prevSlide");

      let index = 0;

      function slidesPerView() {
        const w = window.innerWidth;
        if (w < 768) return 1;
        if (w < 992) return 2;
        return 3;
      }

      function slideWidth() {
        return slides[0].getBoundingClientRect().width;
      }

      function updateSlide() {
        track.style.transform = `translateX(-${index * slideWidth()}px)`;
      }

      nextBtn.addEventListener("click", () => {
        const maxIndex = slides.length - slidesPerView();
        if (index < maxIndex) {
          index++;
          updateSlide();
        }
      });

      prevBtn.addEventListener("click", () => {
        if (index > 0) {
          index--;
          updateSlide();
        }
      });

      window.addEventListener("resize", updateSlide);

      // Kalender
      var calendar = new FullCalendar.Calendar(
        document.getElementById('calendar'),
        {
          initialView: 'dayGridMonth',
          height: 'auto',
          locale: 'id',
          events: <?= json_encode($jadwalkalender) ?>,
          eventClick: function(info) {
            // Modal hanya muncul di jadwal_konten.php
            info.jsEvent.preventDefault();
          }
        }
      );
      calendar.render();

      // Validasi link dokumentasi (contoh, bisa dipakai di form upload/edit dokumentasi)
      window.validasiLinkDokumentasi = function(input) {
        var url = input.value.trim();
        var pattern = /^(https?:\/\/)[\w\-]+(\.[\w\-]+)+[/#?]?.*$/i;
        if (!pattern.test(url)) {
          input.setCustomValidity('Link tidak valid. Harus diawali http:// atau https://');
        } else {
          input.setCustomValidity('');
        }
      }
    });
  </script>

  <!-- TOASTR NOTIFICATION -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script>
    toastr.options = {
      "positionClass": "toast-bottom-right",
      "closeButton": true,
      "progressBar": true,
      "timeOut": "7000",
      "extendedTimeOut": "2000"
    };
  </script>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    var urgentTasks = [];
    var today = new Date();
    var oneDayMs = 1000 * 60 * 60 * 24;
    var twoDaysMs = oneDayMs * 2;
    var events = <?= json_encode($jadwalkalender) ?>;
    var notifCount = 0;
    var notifItems = [];
    events.forEach(function(ev) {
      var status = ev.extendedProps.status;
      if (ev.extendedProps && ev.extendedProps.isPic && (status == 0 || status == 1)) {
        var tglRilis = new Date(ev.start);
        // Bandingkan hanya tanggal (tahun, bulan, hari) tanpa jam
        var tglRilisDate = new Date(tglRilis.getFullYear(), tglRilis.getMonth(), tglRilis.getDate());
        var todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
        var selisihHari = (tglRilisDate - todayDate) / (1000 * 60 * 60 * 24);
        // Hanya tampilkan jika deadline hari ini sampai 2 hari ke depan (jangan jika sudah lewat)
        if (selisihHari >= 0 && selisihHari <= 2) {
          notifCount++;
          notifItems.push(ev);
        }
      }
    });
    if (notifCount > 0) {
      document.getElementById('navbarNotif').style.display = 'inline-block';
      document.getElementById('notifCount').textContent = notifCount;
      var notifList = document.getElementById('notifList');
      notifList.innerHTML = '';
      notifItems.forEach(function(ev) {
        var tglRilis = new Date(ev.start);
        var tglRilisDate = new Date(tglRilis.getFullYear(), tglRilis.getMonth(), tglRilis.getDate());
        var todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
        var selisihHari = (tglRilisDate - todayDate) / (1000 * 60 * 60 * 24);
        
        // Tentukan label deadline
        var labelDeadline = '';
        if (selisihHari === 0) {
          labelDeadline = '🔴 Hari Ini';
        } else if (selisihHari === 1) {
          labelDeadline = '🟡 Besok';
        } else if (selisihHari === 2) {
          labelDeadline = '🟡 2 Hari Lagi';
        }
        
        // Tentukan status badge
        var statusText = '';
        var statusColor = '';
        switch (String(ev.extendedProps.status)) {
          case '0':
            statusText = 'Belum Dikerjakan';
            statusColor = '#e84118';
            break;
          case '1':
            statusText = 'Sedang Dikerjakan';
            statusColor = '#fbc531';
            break;
          default:
            statusText = 'Selesai';
            statusColor = '#44bd32';
        }
        
        var item = document.createElement('div');
        item.className = 'dropdown-item';
        item.innerHTML = '<div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;font-family:Poppins,sans-serif;"><div style="flex:1;"><b style="display:block;margin-bottom:4px;font-family:Poppins,sans-serif;">' + ev.title + '</b><span style="font-size:11px;color:#666;display:block;margin-bottom:4px;font-family:Poppins,sans-serif;">' + labelDeadline + ' • ' + tglRilis.toLocaleDateString('id-ID') + '</span><span style="font-size:11px;padding:2px 6px;border-radius:4px;background:' + statusColor + '20;color:' + statusColor + ';display:inline-block;font-family:Poppins,sans-serif;">' + statusText + '</span></div></div>';
        notifList.appendChild(item);
      });
    } else {
      document.getElementById('navbarNotif').style.display = 'none';
    }
    // Dropdown toggle
    var notifIcon = document.getElementById('navbarNotif');
    var notifDropdown = document.getElementById('notifDropdown');
    notifIcon.onclick = function(e) {
      e.stopPropagation();
      if (notifDropdown.style.display === 'none' || notifDropdown.style.display === '') {
        notifDropdown.style.display = 'block';
      } else {
        notifDropdown.style.display = 'none';
      }
    };
    document.addEventListener('click', function(e) {
      if (notifDropdown.style.display === 'block') {
        notifDropdown.style.display = 'none';
      }
    });
  });
  </script>

  <?php
  $script = ob_get_clean();
  include 'layout.php';
  renderLayout($content, $script);
