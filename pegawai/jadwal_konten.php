<?php
ob_start();
session_start();
require '../koneksi.php';

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
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Jadwal Konten Humas</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- FULLCALENDAR CDN -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

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
.header h2{margin:0 0 8px 0;font-size:24px}
.header p{margin:0;color:#64748b;font-size:14px}

/* ===== CALENDAR CONTAINER ===== */
.calendar-wrapper{
  background:#fff;
  border-radius:20px;
  padding:28px 32px;
  box-shadow:0 10px 30px rgba(15,23,42,.08);
}

.fc {
  font-family: Poppins, sans-serif;
}

.fc .fc-button-primary {
  background-color: #2563eb !important;
  border-color: #2563eb !important;
}

.fc .fc-button-primary:hover {
  background-color: #1d4ed8 !important;
}

.fc .fc-button-primary.fc-button-active {
  background-color: #1d4ed8 !important;
}

.fc .fc-daygrid-day.fc-day-other {
  background-color: #f8fafc;
}

.fc .fc-col-header-cell {
  background-color: #f1f5f9;
  border-color: #e2e8f0;
}

.fc .fc-daygrid-day {
  border-color: #e2e8f0;
}

/* ===== MODAL ===== */
.modal-overlay{
  display:none;
  position:fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background:rgba(15,23,42,0.5);
  z-index:1000;
  align-items:center;
  justify-content:center;
}

.modal-overlay.active{
  display:flex;
}

.modal-content{
  background:#fff;
  border-radius:20px;
  padding:32px;
  max-width:600px;
  width:90%;
  max-height:90vh;
  overflow-y:auto;
  box-shadow:0 20px 60px rgba(15,23,42,0.2);
  position:relative;
}

.modal-header{
  margin-bottom:24px;
}

.modal-header .modal-topik{
  font-size:13px;
  color:#64748b;
  margin:0 0 8px 0;
}

.modal-header .modal-title{
  font-size:22px;
  font-weight:600;
  margin:0;
  color:#0f172a;
}

.close-modal{
  position:absolute;
  top:16px;
  right:16px;
  background:none;
  border:none;
  font-size:24px;
  cursor:pointer;
  color:#64748b;
  padding:0;
  width:32px;
  height:32px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:8px;
  transition:.25s ease;
}

.close-modal:hover{
  background:#eef2f7;
  color:#0f172a;
}

.modal-table{
  width:100%;
  border-collapse:collapse;
  font-size:14px;
}

.modal-table th{
  width:150px;
  text-align:left;
  font-weight:600;
  color:#0f172a;
  padding:12px 0;
  border-bottom:1px solid #e2e8f0;
}

.modal-table td{
  padding:12px 16px 12px 0;
  border-bottom:1px solid #e2e8f0;
  color:#475569;
}

.modal-table tr:last-child td{
  border-bottom:none;
}

.badge-status{
  display:inline-block;
  padding:4px 12px;
  border-radius:999px;
  font-size:12px;
  font-weight:600;
  color:#fff;
}

.badge-danger{background:#e84118}
.badge-warning{background:#fbc531;color:#0f172a}
.badge-success{background:#44bd32}
.badge-secondary{background:#718093}

.modal-links{
  display:flex;
  gap:12px;
  flex-wrap:wrap;
  align-items:center;
}

.modal-links a{
  text-decoration:none;
  font-size:18px;
  transition:.25s ease;
}

.modal-links a:hover{
  transform:scale(1.2);
}

.modal-actions{
  display:flex;
  gap:12px;
  margin-top:24px;
  padding-top:24px;
  border-top:1px solid #e2e8f0;
}

.btn{
  padding:10px 16px;
  border-radius:10px;
  border:1px solid #e2e8f0;
  background:#fff;
  color:#0f172a;
  font-size:13px;
  font-weight:600;
  cursor:pointer;
  transition:.25s ease;
  text-decoration:none;
  display:inline-flex;
  align-items:center;
  gap:6px;
}

.btn:hover{
  background:#eef2f7;
}

.btn-primary{
  background:#2563eb;
  color:#fff;
  border-color:#2563eb;
}

.btn-primary:hover{
  background:#1d4ed8;
}

.text-muted{
  color:#94a3b8;
}

.pic-display{
  line-height:1.6;
}
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
    <span class="breadcrumb-active">Jadwal Konten Humas</span>
  </div>

  <!-- HEADER -->
  <div class="header">
    <h2>Jadwal Konten Humas</h2>
    <p>Jadwal rilis dan kegiatan kehumasan</p>
  </div>

  <!-- CALENDAR -->
  <div class="calendar-wrapper">
    <div id="calendar"></div>
  </div>

</div>

<!-- MODAL JADWAL -->
<div class="modal-overlay" id="jadwalModal">
  <div class="modal-content">
    <button class="close-modal" onclick="closeModal()">
      <i class="bi bi-x-lg"></i>
    </button>
    
    <div class="modal-header">
      <p class="modal-topik" id="modalTopik"></p>
      <h3 class="modal-title" id="modalJudul"></h3>
    </div>

    <table class="modal-table">
      <tr>
        <th>Tanggal Penugasan</th>
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
          <div class="pic-display" id="modalPIC"></div>
        </td>
      </tr>
      <tr>
        <th>Keterangan</th>
        <td id="modalKeterangan"></td>
      </tr>
      <tr id="rowDokumentasi" style="display:none;">
        <th>Dokumentasi</th>
        <td class="d-flex align-items-center gap-2">
          <a id="modalDokumentasi" target="_blank" style="display:none; font-size: 1.2rem;">
            <i class="bi bi-eye-fill" style="color:#2563eb;"></i>
          </a>
          <span id="docPlaceholder" style="display:none; color:#999;">Belum ada dokumentasi</span>
          <a id="editDokumentasiBtn" class="btn" title="Edit Dokumentasi" href="#" style="display:none;">
            <i class="bi bi-pencil"></i> Edit
          </a>
        </td>
      </tr>
      <tr id="rowLink" style="display:none;">
        <th>Link Publikasi</th>
        <td>
          <div class="modal-links" id="modalLinks"></div>
          <a id="editPublikasiBtn" class="btn" title="Edit Link Publikasi" style="display:none; margin-top:12px;">
            <i class="bi bi-pencil"></i> Edit
          </a>
        </td>
      </tr>
    </table>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  var calendar = new FullCalendar.Calendar(
    document.getElementById('calendar'),
    {
      initialView: 'dayGridMonth',
      height: 'auto',
      locale: 'id',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
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
          `<span class="badge-status badge-${statusClass}">${statusText}</span>`;
        
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
          document.getElementById('editDokumentasiBtn').style.display = 'inline-flex';
          document.getElementById('editPublikasiBtn').style.display = 'inline-flex';
        }

        document.getElementById('editDokumentasiBtn').onclick = function(e) {
          e.preventDefault();
          window.location.href = 'edit_dokumentasi.php?id=' + jadwalId + '&mode=dokumentasi';
        };
        document.getElementById('editPublikasiBtn').onclick = function(e) {
          e.preventDefault();
          window.location.href = 'edit_dokumentasi.php?id=' + jadwalId + '&mode=publikasi';
        };

        openModal();
      }
    }
  );
  calendar.render();
});

function openModal() {
  document.getElementById('jadwalModal').classList.add('active');
}

function closeModal() {
  document.getElementById('jadwalModal').classList.remove('active');
}

document.getElementById('jadwalModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeModal();
  }
});
</script>
</body>
</html>
