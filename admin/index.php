<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['user']) && $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// TOTAL HIRED EMPLOYEE (user aktif)
$qUser = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user WHERE status = 1");
$totalUser = mysqli_fetch_assoc($qUser)['total'];

// TOTAL TIM
$qTim = mysqli_query($koneksi, "SELECT COUNT(DISTINCT tim) AS total FROM jadwal WHERE tim IS NOT NULL");
$totalTim = mysqli_fetch_assoc($qTim)['total'];

// TOTAL ASET
$qAset = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM aset");
$totalAset = mysqli_fetch_assoc($qAset)['total'];

// TOTAL TENGGAT (jadwal yang belum selesai)
$qDeadline = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal WHERE status != 2");
$totalDeadline = mysqli_fetch_assoc($qDeadline)['total'];

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

// SKILL DATA FOR CHART
$qSkill = mysqli_query($koneksi, "
SELECT
  SUM(skill_data_contributor) AS data_contributor,
  SUM(skill_content_creator) AS content_creator,
  SUM(skill_editor_photo_layout) AS editor_photo_layout,
  SUM(skill_editor_video) AS editor_video,
  SUM(skill_photo_videographer) AS photo_videographer,
  SUM(skill_talent) AS talent,
  SUM(skill_project_manager) AS project_manager,
  SUM(skill_copywriting) AS copywriting,
  SUM(skill_protokol) AS protokol,
  SUM(skill_mc) AS mc,
  SUM(skill_operator) AS operator
FROM user
");
$skill = mysqli_fetch_assoc($qSkill);

// STATUS JADWAL FOR CHART
$qStatus = mysqli_query($koneksi, "SELECT status, COUNT(*) AS total FROM jadwal GROUP BY status");
$statusData = [];
while($s = mysqli_fetch_assoc($qStatus)){
  $statusData[] = $s;
}

// MEDIA CHART
$qMedia = mysqli_query($koneksi, "SELECT j.nama_jenis, COUNT(m.id_media) AS total FROM media m JOIN sub_jenis sj ON m.id_sub_jenis = sj.id_sub_jenis JOIN jenis j ON sj.id_jenis = j.id_jenis GROUP BY j.nama_jenis");
$labels = [];
$data = [];
while($m = mysqli_fetch_assoc($qMedia)){
  $labels[] = $m['nama_jenis'];
  $data[] = $m['total'];
}
?>
                  <div class="pcoded-content">
                      <!-- Page-header start -->
                      <div class="page-header">
                          <div class="page-block">
                              <div class="row align-items-center">
                                  <div class="col-md-8">
                                      <div class="page-header-title">
                                          <h5 class="m-b-10">Dashboard</h5>
                                          <p class="m-b-0">Selamat Datang, <?= $_SESSION['user']['nama'] ?>!</p>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <ul class="breadcrumb-title">
                                          <li class="breadcrumb-item">
                                              <a href="index.php"> <i class="fa fa-home"></i> </a>
                                          </li>
                                          <li class="breadcrumb-item"><a href="index.php">Dashboard</a>
                                          </li>
                                      </ul>
                                  </div>
                              </div>
                          </div>
                      </div>
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            <!-- task, page, download counter  start -->
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card">
                                                    <div class="card-block">
                                                        <div class="row align-items-center m-l-0">
                                                            <div class="col-auto">
                                                                <i class="fa fa-user f-30 text-c-purple"></i>
                                                            </div>
                                                            <div class="col-auto">
                                                                <h6 class="text-muted m-b-10">Total Karyawan</h6>
                                                                <h2 class="m-b-0"><?= $totalUser; ?></h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card">
                                                    <div class="card-block">
                                                        <div class="row align-items-center m-l-0">
                                                            <div class="col-auto">
                                                                <i class="fa fa-group f-30 text-c-purple"></i>
                                                            </div>
                                                            <div class="col-auto">
                                                                <h6 class="text-muted m-b-10">Total Tim</h6>
                                                                <h2 class="m-b-0"><?= $totalTim; ?></h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card">
                                                    <div class="card-block">
                                                        <div class="row align-items-center m-l-0">
                                                            <div class="col-auto">
                                                                <i class="fa fa-archive f-30 text-c-purple"></i>
                                                            </div>
                                                            <div class="col-auto">
                                                                <h6 class="text-muted m-b-10">Total Aset</h6>
                                                                <h2 class="m-b-0"><?= $totalAset; ?></h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card">
                                                    <div class="card-block">
                                                        <div class="row align-items-center m-l-0">
                                                            <div class="col-auto">
                                                                <i class="fa fa-clock-o f-30 text-c-purple"></i>
                                                            </div>
                                                            <div class="col-auto">
                                                                <h6 class="text-muted m-b-10">Total Tenggat</h6>
                                                                <h2 class="m-b-0"><?= $totalDeadline; ?></h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- task, page, download counter  end -->
                                            <div class="col-xl-4 col-md-12">
                                                <div class="card ">
                                                    <div class="card-header">
                                                        <h5>Peta Bangkalan</h5>
                                                        <div class="card-header-right">
                                                            <ul class="list-unstyled card-option">
                                                                <li><i class="fa fa fa-wrench open-card-option"></i></li>
                                                                <li><i class="fa fa-window-maximize full-card"></i></li>
                                                                <li><i class="fa fa-minus minimize-card"></i></li>
                                                                <li><i class="fa fa-refresh reload-card"></i></li>
                                                                <li><i class="fa fa-trash close-card"></i></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="card-block">
                                                        <div id="map-wrapper" style="position: relative; height: 480px;">
                                                          <div id="map-kecamatan" style="position:absolute; inset:0;"></div>
                                                          <div id="map-kelurahan" style="position:absolute; inset:0;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-8 col-md-12">
                                                <div class="card ">
                                                    <div class="card-header">
                                                        <h5>Kalender & Jadwal</h5>
                                                        <div class="card-header-right">
                                                            <ul class="list-unstyled card-option">
                                                                <li><i class="fa fa fa-wrench open-card-option"></i></li>
                                                                <li><i class="fa fa-window-maximize full-card"></i></li>
                                                                <li><i class="fa fa-minus minimize-card"></i></li>
                                                                <li><i class="fa fa-refresh reload-card"></i></li>
                                                                <li><i class="fa fa-trash close-card"></i></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="card-block"><div id="calendar"></div></div>
                                                </div>
                                            </div>
<div class="modal fade" id="jadwalModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h5 class="text-muted" id="modalTopik"></h5>
          <h3 class="modal-title mb-0" id="modalJudul"></h3>
        </div>
        <button class="btn waves-effect waves-dark btn-danger btn-outline-danger btn-icon" aria-label="Close" data-bs-dismiss="modal"><i class="ti-close"></i></button>
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
            <td id="modalPIC"></td>
          </tr>
          <tr>
            <th>Keterangan</th>
            <td id="modalKeterangan"></td>
          </tr>
          <tr id="rowDokumentasi" style="display:none">
            <th>Dokumentasi</th>
            <td>
              <a href="#" target="_blank" id="modalDokumentasi">Lihat dokumentasi</a>
            </td>
          </tr>
          <tr id="rowLink" style="display:none">
            <th>Link Publikasi</th>
            <td id="modalLinks"></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card ">
                                                    <div class="card-header">
                                                        <h5>Status Jadwal Kegiatan</h5>
                                                        <div class="card-header-right">
                                                            <ul class="list-unstyled card-option">
                                                                <li><i class="fa fa fa-wrench open-card-option"></i></li>
                                                                <li><i class="fa fa-window-maximize full-card"></i></li>
                                                                <li><i class="fa fa-minus minimize-card"></i></li>
                                                                <li><i class="fa fa-refresh reload-card"></i></li>
                                                                <li><i class="fa fa-trash close-card"></i></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="card-block"><canvas id="statusChart"></canvas></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card ">
                                                    <div class="card-header">
                                                        <h5>Media Chart</h5>
                                                        <div class="card-header-right">
                                                            <ul class="list-unstyled card-option">
                                                                <li><i class="fa fa fa-wrench open-card-option"></i></li>
                                                                <li><i class="fa fa-window-maximize full-card"></i></li>
                                                                <li><i class="fa fa-minus minimize-card"></i></li>
                                                                <li><i class="fa fa-refresh reload-card"></i></li>
                                                                <li><i class="fa fa-trash close-card"></i></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="card-block"><canvas id="mediaChart"></canvas></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-12">
                                                <div class="card ">
                                                    <div class="card-header">
                                                        <h5>Skill Chart</h5>
                                                        <div class="card-header-right">
                                                            <ul class="list-unstyled card-option">
                                                                <li><i class="fa fa fa-wrench open-card-option"></i></li>
                                                                <li><i class="fa fa-window-maximize full-card"></i></li>
                                                                <li><i class="fa fa-minus minimize-card"></i></li>
                                                                <li><i class="fa fa-refresh reload-card"></i></li>
                                                                <li><i class="fa fa-trash close-card"></i></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="card-block"><canvas id="skillChart"></canvas></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Page-body end -->
                                </div>
                                <div id="styleSelector"> </div>
                            </div>
                        </div>
                      </div>
                        <!-- Page-header end -->
                        <!-- Page-header end -->
                        <!-- Page-header end -->
<?php
$content = ob_get_clean();
ob_start();
?>
<script>
new Chart(document.getElementById('skillChart'), {
  type: 'bar',
  data: {
    labels: ['Data Contributor', 'Content Creator', 'Editor Photo Layout', 'Editor Video', 'Photo Videographer', 'Talent', 'Project Manager', 'Copywriting', 'Protokol', 'MC', 'Operator'],
    datasets: [{
      data: [
        <?= $skill['data_contributor'] ?>,
        <?= $skill['content_creator'] ?>,
        <?= $skill['editor_photo_layout'] ?>,
        <?= $skill['editor_video'] ?>,
        <?= $skill['photo_videographer'] ?>,
        <?= $skill['talent'] ?>,
        <?= $skill['project_manager'] ?>,
        <?= $skill['copywriting'] ?>,
        <?= $skill['protokol'] ?>,
        <?= $skill['mc'] ?>,
        <?= $skill['operator'] ?>
      ]
    }]
  }
});
new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: {
    labels: ['Selesai', 'Belum', 'Proses'],
    datasets: [{
      data: [
        <?= $statusData[2]['total'] ?? 0 ?>,
        <?= $statusData[0]['total'] ?? 0 ?>,
        <?= $statusData[1]['total'] ?? 0 ?>
      ]
    }]
  }
});
new Chart(document.getElementById('mediaChart'), {
  type: 'pie',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [{
      data: <?= json_encode($data) ?>
    }]
  }
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
  if (p.dokumentasi) {
    document.getElementById('rowDokumentasi').style.display = '';
    document.getElementById('modalDokumentasi').href = p.dokumentasi;
  } else {
    document.getElementById('rowDokumentasi').style.display = 'none';
  }
  // Link publikasi
let links = [];
function renderLink(label, url) {
  // NULL / undefined / empty → tidak ditampilkan
  if (!url) return;
  // Isinya "-" → tampil tapi tidak bisa diklik
  if (url === '-') {
    links.push(`<span class="text-muted">${label}</span>`);
    return;
  }
  // Selain "-" → tampil & bisa diklik
  links.push(
    `<a href="${url}" target="_blank" class="link-primary">${label}</a>`
  );
}
renderLink('Instagram', p.link_instagram);
renderLink('Facebook', p.link_facebook);
renderLink('YouTube', p.link_youtube);
renderLink('Website', p.link_website);
if (links.length > 0) {
  document.getElementById('rowLink').style.display = '';
  document.getElementById('modalLinks').innerHTML = links.join(' | ');
} else {
  document.getElementById('rowLink').style.display = 'none';
}
  new bootstrap.Modal(document.getElementById('jadwalModal')).show();
}
    }
  );
  calendar.render();
});

// MAPS
$(function () {
let mapKecamatan = $('#map-kecamatan').vectorMap({
  map: 'kecamatan',
  backgroundColor: 'transparent',
  zoomOnScroll: true,
  panOnDrag: true,

  regionStyle: {
    initial: {
      stroke: 'none'
    },
    hover: {
      fill: null
    }
  },

  series: {
    regions: [{
      values: {
        '3526010': '#baecb3',
        '3526020': '#e1b12c',
        '3526030': '#c23616',
        '3526040': '#8c7ae6',
        '3526050': '#00a8ff',
        '3526060': '#9c88ff',
        '3526070': '#fbc531',
        '3526080': '#e84118',
        '3526090': '#7f8fa6',
        '3526100': '#487eb0',
        '3526110': '#192a56',
        '3526120': '#40739e',
        '3526130': '#0097e6',
        '3526140': '#44bd32',
        '3526150': '#e1b12c',
        '3526160': '#c23616',
        '3526170': '#8c7ae6',
        '3526180': '#273c75'
      },
      attribute: 'fill'
    }]
  }
}).vectorMap('get', 'mapObject');
let mapKelurahan = $('#map-kelurahan').vectorMap({
  map: 'kelurahan',
  backgroundColor: 'transparent',

  zoomOnScroll: false,
  panOnDrag: false,

  regionStyle: {
    initial: {
      fill: 'rgba(0,0,0,0)',
      stroke: '#636e72',
      'stroke-width': 0.4
    },
    hover: {
      stroke: '#000',
      'stroke-width': 0.8,
      cursor: 'pointer'
    }
  },

  onRegionTipShow: function (e, el) {
    el.html(`
      <strong>Kelurahan</strong><br>
      ${el.text()}
    `);
  }
}).vectorMap('get', 'mapObject');
mapKecamatan.container.on('zoom.jvectormap', function (e, scale) {
  mapKelurahan.setScale(scale);
});

mapKecamatan.container.on('pan.jvectormap', function () {
  mapKelurahan.transX = mapKecamatan.transX;
  mapKelurahan.transY = mapKecamatan.transY;
  mapKelurahan.applyTransform();
});
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
