<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

ob_start();
?>

<!-- Kalender & Jadwal -->
<section id="kalender-jadwal" class="about section" style="padding-top: 160px;">

  <!-- Section Title + Tombol Import -->
  <div class="container section-title d-flex justify-content-between align-items-center mb-4 flex-wrap" data-aos="fade-up">
    <div>
      <h2>Kalender & Jadwal</h2>
      <p class="text-muted mb-0">Jadwal rilis dan kegiatan kehumasan</p>
    </div>

    <!-- TOMBOL IMPORT JADWAL -->
    <a href="import_jadwal.php" class="btn btn-primary mt-3 mt-md-0">
      <i class="bi bi-plus-circle me-1"></i> Import Data
    </a>
  </div>

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
                <a id="editPicBtn" class="btn btn-sm btn-outline-primary ms-2">
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
                <a id="modalDokumentasi" target="_blank" class="icon-link" style="display:none">
                  <i class="bi bi-eye-fill"></i>
                </a>
                <a id="editDokumentasiBtn" class="btn btn-sm btn-outline-primary" title="Edit Dokumentasi" href="edit_dokumentasi.php">
                  <i class="bi bi-pencil"></i>
                </a>
              </td>
            </tr>
            <tr>
              <th>Link Publikasi</th>
              <td class="d-flex align-items-center gap-2">
                <div id="modalLinks" class="d-flex gap-2"></div>
                <a id="editPublikasiBtn" class="btn btn-sm btn-outline-primary" title="Edit Link Publikasi">
                  <i class="bi bi-pencil"></i>
                </a>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

</section>

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

.page-kalender #header,
.page-kalender #header.header-scrolled {
  background: #3d4d6a !important;
  box-shadow: 0 2px 12px rgba(0,0,0,.08);
}

#calendar { max-width: 100%; }

.section-title .btn {
  border-radius: 30px;
  padding: 8px 18px;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
  document.body.classList.add("page-kalender"); // Header selalu warna default

  var calendarEl = document.getElementById("calendar");
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: "id",
    height: "auto",
    events: "kalender_jadwal.php",
    eventClick: function(info) {
      info.jsEvent.preventDefault();
      const p = info.event.extendedProps;
      const id = info.event.id;

      document.getElementById('modalTopik').innerText = p.topik ?? '-';
      document.getElementById('modalJudul').innerText = info.event.title;
      document.getElementById('modalTanggalPenugasan').innerText = p.tanggal_penugasan ? new Date(p.tanggal_penugasan).toLocaleDateString('id-ID') : '-';
      document.getElementById('modalTargetRilis').innerText = info.event.start.toLocaleDateString('id-ID');
      document.getElementById('modalTim').innerText = p.tim ?? '-';

      let statusText = '-', statusClass = 'secondary';
      switch(String(p.status)) {
        case '0': statusText='Belum Dikerjakan'; statusClass='danger'; break;
        case '1': statusText='Sedang Dikerjakan'; statusClass='warning'; break;
        case '2': statusText='Selesai'; statusClass='success'; break;
      }
      document.getElementById('modalStatus').innerHTML = `<span class="badge bg-${statusClass}">${statusText}</span>`;

      document.getElementById('modalPIC').innerHTML = `
        <b>Desain:</b> ${p.pic_desain ?? '-'}<br>
        <b>Narasi:</b> ${p.pic_narasi ?? '-'}<br>
        <b>Medsos:</b> ${p.pic_medsos ?? '-'}
        
      `;

      document.getElementById('modalKeterangan').innerText = p.keterangan ?? '-';

      const docIcon = document.getElementById('modalDokumentasi');
      if (p.dokumentasi && p.dokumentasi.trim() !== '') {
        docIcon.href = p.dokumentasi;
        docIcon.style.display = 'inline-block';
      } else {
        docIcon.style.display = 'none';
      }

      let linksHTML = '';
      function renderIcon(icon, url, color, title) {
        if (!url || url.trim() === '') return '';
        return `<a href="${url}" target="_blank" title="${title}" class="me-2"><i class="bi ${icon}" style="font-size:1.4rem;color:${color}"></i></a>`;
      }
      linksHTML += renderIcon('bi-instagram', p.link_instagram, '#E1306C','Instagram');
      linksHTML += renderIcon('bi-facebook', p.link_facebook, '#1877F2','Facebook');
      linksHTML += renderIcon('bi-youtube', p.link_youtube, '#FF0000','YouTube');
      linksHTML += renderIcon('bi-globe', p.link_website, '#0d6efd','Website');
      document.getElementById('modalLinks').innerHTML = linksHTML;

      document.getElementById('editDokumentasiBtn').href = `edit_dokumentasi.php?id=${id}&mode=dokumentasi`;
      document.getElementById('editPublikasiBtn').href = `edit_dokumentasi.php?id=${id}&mode=publikasi`;
      document.getElementById('editPicBtn').href = `edit_dokumentasi.php?id=${id}&mode=pic`;

      var jadwalModal = new bootstrap.Modal(document.getElementById('jadwalModal'));
      jadwalModal.show();
    }
  });

  calendar.render();
});
</script>

<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
