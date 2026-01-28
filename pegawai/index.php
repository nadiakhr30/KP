<?php
ob_start();
session_start();
require '../koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Pegawai") {
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
    j.dokumentasi,
    j.link_instagram,
    j.link_facebook,
    j.link_youtube,
    j.link_website
  FROM jadwal j
  ORDER BY j.tanggal_rilis DESC
");

while ($row = mysqli_fetch_assoc($qKalender)) {
  $id_jadwal = $row['id_jadwal'];
  $qPic = mysqli_query($koneksi, "
    SELECT u.nip, u.nama, jp.nama_jenis_pic
    FROM pic p
    JOIN user u ON p.nip = u.nip
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

  $isPic = in_array($_SESSION['user']['nip'], $nipList);
  
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
                <i class="bi bi-play-circle"></i><span>Watch Video</span>
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


 <!-- Humas Section -->
<section id="Humas" class="services section light-background">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Humas</h2>
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

      <!-- Brankas Humas -->
      <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
        <div class="service-item humas-card position-relative text-center">
          <div class="icon"><i class="bi bi-folder2-open icon"></i></div>
          <h4>Brankas Humas</h4>
          <p>Penyimpanan dokumen dan arsip publikasi.</p>

          <div class="humas-overlay">
            <a href="https://drive.google.com" target="_blank" class="overlay-item">
              <i class="bi bi-google"></i>
              <span>Google Drive Humas</span>
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
    <section id="work-process" class="work-process section">

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

        <!-- TEMPLATE MEDSOS -->
          <?php
          // Ambil jenis dengan id_jenis = 1
          $jenisQ = mysqli_query($koneksi, "SELECT id_jenis, nama_jenis FROM jenis WHERE id_jenis = 1");
          if ($jenisQ && mysqli_num_rows($jenisQ) > 0) {
              while ($j = mysqli_fetch_assoc($jenisQ)) {
                  // Ambil sub jenis untuk id_jenis = 1
                  $subQ = mysqli_query($koneksi, "SELECT id_sub_jenis, nama_sub_jenis FROM sub_jenis WHERE id_jenis = " . (int)$j['id_jenis'] . " ORDER BY nama_sub_jenis ASC");
          ?>
          <div class="asset-card">
            <div class="card-content">
              <div class="icon-circle blue">
                <i class="bi bi-layout-text-window-reverse"></i>
              </div>
              <h4><?= htmlspecialchars($j['nama_jenis']) ?></h4>
              <p>Konten visual siap pakai</p>
            </div>

            <div class="card-overlay">
              <div class="overlay-menu">
                <?php if ($subQ && mysqli_num_rows($subQ) > 0): ?>
                  <?php while ($s = mysqli_fetch_assoc($subQ)): ?>
                    <a href="media.php?sub=<?= urlencode($s['id_sub_jenis']) ?>"><?= htmlspecialchars($s['nama_sub_jenis']) ?></a>
                  <?php endwhile; ?>
                <?php else: ?>
                  <span style="color:#fff;">Tidak ada sub jenis tersedia</span> 
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php
              }
          }
          ?>
      </div>

    </section><!-- /Sumber Daya -->

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

  

  <script>
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
            info.jsEvent.preventDefault();
            const p = info.event.extendedProps;
            const jadwalId = info.event.id;

            document.getElementById('modalTopik').innerText = p.topik ?? '-';
            document.getElementById('modalJudul').innerText = info.event.title;
            document.getElementById('modalTanggalPenugasan').innerText =
              p.tanggal_penugasan
                ? new Date(p.tanggal_penugasan).toLocaleDateString('id-ID')
                : '-';
            document.getElementById('modalTargetRilis').innerText =
              info.event.start.toLocaleDateString('id-ID');
            document.getElementById('modalTim').innerText = p.tim ?? '-';
            
            let statusText = '-';
            let statusClass = 'secondary';
            switch (String(p.status)) {
              case '0':
                statusText = 'Belum Dikerjakan';
                statusClass = 'danger';
                break;
              case '1':
                statusText = 'Sedang Dikerjakan';
                statusClass = 'warning';
                break;
              case '2':
                statusText = 'Selesai';
                statusClass = 'success';
                break;
            }
            document.getElementById('modalStatus').innerHTML =
              `<span class="badge bg-${statusClass}">${statusText}</span>`;
            
            document.getElementById('modalPIC').innerHTML = p.pic_display || '-';
            document.getElementById('modalKeterangan').innerHTML = p.keterangan ?? '-';
            
            // Dokumentasi
            if (p.dokumentasi && p.dokumentasi.trim() !== '') {
              document.getElementById('rowDokumentasi').style.display = 'table-row';
              document.getElementById('modalDokumentasi').href = '../uploads/dokumentasi/' + p.dokumentasi;
              document.getElementById('modalDokumentasi').style.display = 'inline-flex';
              document.getElementById('docPlaceholder').style.display = 'none';
            } else {
              document.getElementById('rowDokumentasi').style.display = 'table-row';
              document.getElementById('modalDokumentasi').style.display = 'none';
              document.getElementById('docPlaceholder').style.display = p.isPic ? 'none' : 'inline';
            }

            // Link publikasi
            let links = [];
            
            function renderLink(icon, label, url, color) {
              if (!url || url === '-' || url.trim() === '') {
                links.push(`<span class="text-muted" title="${label} (belum diisi)"><i class="bi ${icon}" style="font-size: 1.2rem; opacity: 0.3;"></i></span>`);
              } else {
                links.push(`<a href="${url}" target="_blank" title="${label}" style="color: ${color};"><i class="bi ${icon}" style="font-size: 1.2rem;"></i></a>`);
              }
            }
            
            renderLink('bi-instagram', 'Instagram', p.link_instagram, '#E1306C');
            renderLink('bi-facebook', 'Facebook', p.link_facebook, '#1877F2');
            renderLink('bi-youtube', 'YouTube', p.link_youtube, '#FF0000');
            renderLink('bi-globe', 'Website', p.link_website, '#0d6efd');
            
            document.getElementById('rowLink').style.display = 'table-row';
            document.getElementById('modalLinks').innerHTML = links.length > 0 ? links.join(' ') : '-';

            document.getElementById('editDokumentasiBtn').style.display = 'none';
            document.getElementById('editPublikasiBtn').style.display = 'none';
            
            if (p.isPic) {
              document.getElementById('editDokumentasiBtn').style.display = 'inline-block';
              document.getElementById('editPublikasiBtn').style.display = 'inline-block';
            }

            document.getElementById('editDokumentasiBtn').onclick = function() {
              window.location.href = 'edit_dokumentasi.php?id=' + jadwalId + '&mode=dokumentasi';
            };
            document.getElementById('editPublikasiBtn').onclick = function() {
              window.location.href = 'edit_dokumentasi.php?id=' + jadwalId + '&mode=publikasi';
            };

            new bootstrap.Modal(document.getElementById('jadwalModal')).show();
          }
        }
      );
      calendar.render();
    });
  </script>

  <?php
  $script = ob_get_clean();
  include 'layout.php';
  renderLayout($content, $script);
