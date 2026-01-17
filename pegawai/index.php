<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

// Kalender
$jadwalkalender = [];
$qKalender = mysqli_query($koneksi, "
  SELECT 
    j.id_jadwal,
    j.topik,
    j.judul_kegiatan,
    j.tanggal_penugasan,
    j.target_rilis,
    j.tim,
    j.keterangan,
    j.status,
    j.dokumentasi,
    j.link_instagram,
    j.link_facebook,
    j.link_youtube,
    j.link_website,
    u1.nama AS pic_desain_nama,
    u2.nama AS pic_narasi_nama,
    u3.nama AS pic_medsos_nama
  FROM jadwal j
  LEFT JOIN user u1 ON j.pic_desain = u1.id_user
  LEFT JOIN user u2 ON j.pic_narasi = u2.id_user
  LEFT JOIN user u3 ON j.pic_medsos = u3.id_user
  WHERE j.target_rilis IS NOT NULL
");
while ($row = mysqli_fetch_assoc($qKalender)) {
  if ($row['status'] == 0) $color = '#e84118';
  else if ($row['status'] == 1) $color = '#fbc531';
  else if ($row['status'] == 2) $color = '#44bd32';
  else $color = '#718093';
  $jadwalkalender[] = [
    'id'    => $row['id_jadwal'],
    'title' => $row['judul_kegiatan'],
    'start' => $row['target_rilis'],
    'color' => $color,
    'extendedProps' => [
      'topik' => $row['topik'],
      'tanggal_penugasan' => $row['tanggal_penugasan'],
      'tim' => $row['tim'],
      'status' => (int)$row['status'],
      'keterangan' => $row['keterangan'],
      'pic_desain' => $row['pic_desain_nama'] ?? '-',
      'pic_narasi' => $row['pic_narasi_nama'] ?? '-',
      'pic_medsos' => $row['pic_medsos_nama'] ?? '-',
      'dokumentasi' => $row['dokumentasi'],
      'link_instagram' => $row['link_instagram'],
      'link_facebook' => $row['link_facebook'],
      'link_youtube' => $row['link_youtube'],
      'link_website' => $row['link_website']
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
      </div><!-- End Section Title -->


      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-12">

            <div id="calendar"></div>
            <div class="col-xl-8 col-md-12">
          <div class="card ">
              <div class="card-block"><div id="calendar"></div></div>
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
          <button
            type="button"
            class="btn btn-outline-danger btn-close-circle position-absolute top-0 end-0 m-3"
            data-bs-dismiss="modal"
            aria-label="Close">
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
            <td >
               <span id="modalPIC"></span>

                <!-- tombol edit PIC -->
                <a id="editPicBtn" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil"></i>
                </a>
            </td>
          </tr>
          <tr>
            <th>Keterangan</th>
            <td id="modalKeterangan"></td>
          </tr>
          <tr>
            <th>Dokumentasi</th>
            <td class="d-flex align-items-center gap-2">

              <a
                id="modalDokumentasi"
                target="_blank"
                class="icon-link"
                style="display:none"
              >
                <i class="bi bi-eye-fill"></i>
              </a>

              <a
                id="editDokumentasiBtn"
                class="btn btn-sm btn-outline-primary"
                title="Edit Dokumentasi"
                href="edit_dokumentasi.php"
              >
                <i class="bi bi-pencil"></i>
              </a>

            </td>
          </tr>


          <tr>
            <th>Link Publikasi</th>
            <td class="d-flex align-items-center gap-2">

              <div id="modalLinks" class="d-flex gap-2"></div>

              <a
                id="editPublikasiBtn"
                class="btn btn-sm btn-outline-primary"
                title="Edit Link Publikasi"
              >
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
            <a href="#" class="overlay-item">
              <i class="bi bi-diagram-3"></i>
              <span>Struktur Humas</span>
            </a>
            <a href="viewjadwal.php" class="overlay-item">
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
      </section><!-- End Manajemen Link Section -->

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

  </main>
  <?php
  $content = ob_get_clean();
  ob_start();
  ?>
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
      });

      // Kalender
      document.addEventListener('DOMContentLoaded', function () {
        var calendar = new FullCalendar.Calendar(
          document.getElementById('calendar'),
          {
            initialView: 'dayGridMonth',
            height: 520,
            locale: 'id',
            events: 'kalender_jadwal.php',
            eventClick: function(info) {
        info.jsEvent.preventDefault();
        const p = info.event.extendedProps;
        const id = info.event.id; 
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
        document.getElementById('modalPIC').innerHTML = `
          <b>Desain:</b> ${p.pic_desain}<br>
          <b>Narasi:</b> ${p.pic_narasi}<br>
          <b>Medsos:</b> ${p.pic_medsos}
        `;
        document.getElementById('modalKeterangan').innerHTML =
          p.keterangan ?? '-';
        // Dokumentasi
          const docIcon = document.getElementById('modalDokumentasi');
          if (p.dokumentasi && p.dokumentasi.trim() !== '') {
            docIcon.href = p.dokumentasi;
            docIcon.style.display = 'inline-block';
          } else {
            docIcon.style.display = 'none';
          }

          // ===============================
          // Link Publikasi
          // ===============================
          let linksHTML = '';

          function renderIcon(icon, url, color, title) {
            if (!url || url.trim() === '') return '';
            return `
              <a href="${url}" target="_blank" title="${title}" class="me-2">
                <i class="bi ${icon}" style="font-size:1.4rem;color:${color}"></i>
              </a>
            `;
          }

          linksHTML += renderIcon('bi-instagram', p.link_instagram, '#E1306C', 'Instagram');
          linksHTML += renderIcon('bi-facebook', p.link_facebook, '#1877F2', 'Facebook');
          linksHTML += renderIcon('bi-youtube', p.link_youtube, '#FF0000', 'YouTube');
          linksHTML += renderIcon('bi-globe', p.link_website, '#0d6efd', 'Website');

          document.getElementById('modalLinks').innerHTML = linksHTML;

          
          document.getElementById('editDokumentasiBtn').href =
            `edit_dokumentasi.php?id=${id}&mode=dokumentasi`;

          document.getElementById('editPublikasiBtn').href =
            `edit_dokumentasi.php?id=${id}&mode=publikasi`;

          document.getElementById('editPicBtn').href =
            `edit_dokumentasi.php?id=${id}&mode=pic`;

          // Show modal
          var jadwalModal = new bootstrap.Modal(
            document.getElementById('jadwalModal')
          );
          jadwalModal.show();
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