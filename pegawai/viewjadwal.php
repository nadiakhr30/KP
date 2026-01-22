<?php
ob_start();
session_start();
require '../koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

// Cek apakah user adalah admin atau PIC
$isAdmin = $_SESSION['role'] == 'Admin';
$userNip = $_SESSION['user']['nip'];

// Get all PIC untuk validasi
$qAllPic = mysqli_query($koneksi, "SELECT DISTINCT nip FROM pic");
$picNipList = [];
while ($picRow = mysqli_fetch_assoc($qAllPic)) {
    $picNipList[] = $picRow['nip'];
}
$canImport = $isAdmin || in_array($userNip, $picNipList);

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

<!-- Kalender & Jadwal -->
<section id="kalender-jadwal" class="about section" style="padding-top: 160px;">

  <!-- Section Title + Tombol Import -->
  <div class="container section-title d-flex justify-content-between align-items-center mb-4 flex-wrap" data-aos="fade-up">
    <div>
      <h2>Kalender & Jadwal</h2>
      <p class="text-muted mb-0">Jadwal rilis dan kegiatan kehumasan</p>
    </div>

    
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
document.addEventListener('DOMContentLoaded', function () {
  document.body.classList.add("page-kalender");
  
  var calendar = new FullCalendar.Calendar(
    document.getElementById('calendar'),
    {
      initialView: 'dayGridMonth',
      height: 520,
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
        
        // Display PIC data
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
            // Icon tidak berwarna jika belum ada link
            links.push(`<span class="text-muted" title="${label} (belum diisi)"><i class="bi ${icon}" style="font-size: 1.2rem; opacity: 0.3;"></i></span>`);
          } else {
            // Icon berwarna jika sudah ada link
            links.push(`<a href="${url}" target="_blank" title="${label}" style="color: ${color};"><i class="bi ${icon}" style="font-size: 1.2rem;"></i></a>`);
          }
        }
        
        renderLink('bi-instagram', 'Instagram', p.link_instagram, '#E1306C');
        renderLink('bi-facebook', 'Facebook', p.link_facebook, '#1877F2');
        renderLink('bi-youtube', 'YouTube', p.link_youtube, '#FF0000');
        renderLink('bi-globe', 'Website', p.link_website, '#0d6efd');
        
        document.getElementById('rowLink').style.display = 'table-row';
        document.getElementById('modalLinks').innerHTML = links.length > 0 ? links.join(' ') : '-';

        // Sembunyikan semua tombol edit terlebih dahulu
        document.getElementById('editDokumentasiBtn').style.display = 'none';
        document.getElementById('editPublikasiBtn').style.display = 'none';

        // Tampilkan tombol edit hanya jika user adalah PIC dari jadwal ini
        if (p.isPic) {
          document.getElementById('editDokumentasiBtn').style.display = 'inline-block';
          document.getElementById('editPublikasiBtn').style.display = 'inline-block';
        }

        // Add click handlers for edit buttons
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
?>
