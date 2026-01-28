<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// TOTAL HIRED EMPLOYEE
$qUser = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user WHERE status = 1");
$totalUser = mysqli_fetch_assoc($qUser)['total'];

// TOTAL TIM
$qTim = mysqli_query($koneksi, "SELECT COUNT(DISTINCT tim) AS total FROM jadwal WHERE tim IS NOT NULL");
$totalTim = mysqli_fetch_assoc($qTim)['total'];

// TOTAL ASET
$qAset = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM aset");
$totalAset = mysqli_fetch_assoc($qAset)['total'];

// Total konten bulan ini
$qBulan = mysqli_query($koneksi, "
  SELECT COUNT(*) AS total 
  FROM media 
  WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
    AND YEAR(created_at) = YEAR(CURRENT_DATE())
");
$totalBulan = mysqli_fetch_assoc($qBulan)['total'];

// Total konten tahun ini
$qTahun = mysqli_query($koneksi, "
  SELECT COUNT(*) AS total 
  FROM media 
  WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
");
$totalTahun = mysqli_fetch_assoc($qTahun)['total'];

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
  // Get PIC for this jadwal
  $id_jadwal = $row['id_jadwal'];
  $qPic = mysqli_query($koneksi, "
    SELECT u.nama, jp.nama_jenis_pic
    FROM pic p
    JOIN user u ON p.nip = u.nip
    JOIN jenis_pic jp ON p.id_jenis_pic = jp.id_jenis_pic
    WHERE p.id_jadwal = " . (int)$id_jadwal . "
    ORDER BY jp.nama_jenis_pic
  ");
  
  $picData = [];
  if ($qPic) {
    while ($pic = mysqli_fetch_assoc($qPic)) {
      $picData[$pic['nama_jenis_pic']] = $pic['nama'];
    }
  }
  
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
      'status' => (int)$row['status'],
      'keterangan' => $row['keterangan'],
      'pic_data' => $picData,
      'pic_display' => $picDisplay,
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
  s.nama_skill,
  COUNT(us.id_user_skill) AS total_skill
FROM skill s
LEFT JOIN user_skill us ON s.id_skill = us.id_skill
GROUP BY s.id_skill, s.nama_skill
ORDER BY total_skill DESC
");
$skillLabels = [];
$skillData = [];
while ($row = mysqli_fetch_assoc($qSkill)) {
  $skillLabels[] = $row['nama_skill'];
  $skillData[] = (int)$row['total_skill'];
}

// STATUS JADWAL FOR CHART
$qStatus = mysqli_query($koneksi, "SELECT status, COUNT(*) AS total FROM jadwal GROUP BY status ORDER BY status");
$statusData = [];
while($s = mysqli_fetch_assoc($qStatus)){
  $statusData[] = $s;
}

// TOP 5 PEGAWAI (berdasarkan penugasan di pic)
$qTopPegawai = mysqli_query($koneksi, "
  SELECT u.nama, COUNT(*) AS total
  FROM pic p
  JOIN user u ON p.nip = u.nip
  GROUP BY u.nip, u.nama
  ORDER BY total DESC
  LIMIT 5
");

$pegawai = [];
while ($r = mysqli_fetch_assoc($qTopPegawai)) {
  $pegawai[] = [
    'nama' => $r['nama'],
    'total' => (int)$r['total']
  ];
}
?>
<div class="pcoded-content">
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
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
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
                              <div class="row align-items-center">
                                  <div class="col-2">
                                      <i class="fa fa-file-text-o f-30 text-c-purple"></i>
                                  </div>
                                  <div class="col-10">
                                    <div class="row align-items-center">
                                      <div class="col-6">
                                        <h6 class="text-muted m-b-10">Konten Bulan Ini</h6>
                                        <h2 class="m-b-0"><?= $totalBulan; ?></h2>
                                      </div>
                                      <div class="col-6">
                                        <h6 class="text-muted m-b-10">Konten Tahun Ini</h6>
                                        <h2 class="m-b-0"><?= $totalTahun; ?></h2>
                                      </div>
                                    </div>
                                    <!-- Angka -->
                                    <div class="tab-content tabs card-block p-0">
                                    </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                        </div>
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
                                  <div id="map-wrapper">
                                    <div id="map-bangkalan" style="position:absolute; inset:0; pointer-events:auto;"></div>
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
                                    <td><b>Tanggal Penugasan</b></td>
                                    <td id="modalTanggalPenugasan"></td>
                                  </tr>
                                  <tr>
                                    <td><b>Target Rilis</b></td>
                                    <td id="modalTargetRilis"></td>
                                  </tr>
                                  <tr>
                                    <td><b>Tim</b></td>
                                    <td id="modalTim"></td>
                                  </tr>
                                  <tr>
                                    <td><b>Status</b></td>
                                    <td id="modalStatus"></td>
                                  </tr>
                                  <tr>
                                    <td><b>PIC</b></td>
                                    <td id="modalPIC"></td>
                                  </tr>
                                  <tr>
                                    <td><b>Keterangan</b></td>
                                    <td id="modalKeterangan"></td>
                                  </tr>
                                  <tr id="rowDokumentasi" style="display:none">
                                    <td><b>Dokumentasi</b></td>
                                    <td id="modalDokumentasi"></td>
                                  </tr>
                                  <tr id="rowLink" style="display:none">
                                    <td><b>Link Publikasi</b></td>
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
                                <h5>Top 5 Pegawai Paling Sibuk</h5>
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
                            <div class="card-block text-center pb-0">
                              <h6>Berdasarkan Total Penugasan</h6>
                              <div class="podium-wrapper">
                                <?php foreach ($pegawai as $i => $p): ?>
                                  <div class="podium-step step-<?= $i + 1 ?>" data-name="<?= htmlspecialchars($p['nama']) ?>" data-total="<?= $p['total'] ?>">
                                    <?php if ($i === 0): ?>
                                      <div class="crown">👑</div>
                                    <?php endif; ?>
                                    <div class="name"><?= htmlspecialchars($p['nama']) ?></div>
                                  </div>
                                <?php endforeach; ?>
                              </div>
                            </div>
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
              </div>
          </div>
      </div>
    </div>
</div>
<?php
$content = ob_get_clean();
ob_start();
?>
<script>
  // Chart Skill
new Chart(document.getElementById('skillChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($skillLabels) ?>,
    datasets: [{
      label: 'Total Pengguna',
      data: <?= json_encode($skillData) ?>,
      backgroundColor: '#007bff'
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

//Chart Status Jadwal
new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: {
    labels: ['Belum Dikerjakan', 'Sedang Dikerjakan', 'Selesai'],
    datasets: [{
      data: [
        <?= $statusData[0]['total'] ?? 0 ?>,
        <?= $statusData[1]['total'] ?? 0 ?>,
        <?= $statusData[2]['total'] ?? 0 ?>
      ],
      backgroundColor: ['#e81818', '#fbc531', '#44bd32']
    }]
  }
});

// Kalender - Only initialize if FullCalendar library loaded successfully
document.addEventListener('DOMContentLoaded', function () {
  // Check if FullCalendar is available before initializing
  if (typeof FullCalendar === 'undefined' || window.FULLCALENDAR_DISABLED) {
    console.warn('FullCalendar not available - skipping calendar initialization');
    return;
  }

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
        let picHtml = '';
        const picData = p.pic_data || {};
        if (Object.keys(picData).length > 0) {
          for (const [jenis, nama] of Object.entries(picData)) {
            picHtml += `<b>${jenis}:</b> ${nama}<br>`;
          }
        } else {
          picHtml = '-';
        }
        document.getElementById('modalPIC').innerHTML = picHtml;
        
        document.getElementById('modalKeterangan').innerHTML =
          p.keterangan ?? '-';
        
        // Dokumentasi
        if (p.dokumentasi) {
          document.getElementById('rowDokumentasi').style.display = '';
          document.getElementById('modalDokumentasi').innerHTML = 
            `<a href="${p.dokumentasi}" target="_blank" class="link-primary">Lihat Dokumentasi</a>`;
        } else {
          document.getElementById('rowDokumentasi').style.display = 'none';
        }
        
        // Link publikasi
        let links = [];
        function renderLink(label, url) {
          if (!url) return;
          if (url === '-') {
            links.push(`<span class="text-muted">${label}</span>`);
            return;
          }
          links.push(
            `<a href="${url}" target="_blank" class="link-primary">${label}</a>`
          );
        }
        renderLink('<i class="ti-instagram"></i>', p.link_instagram);
        renderLink('<i class="ti-facebook"></i>', p.link_facebook);
        renderLink('<i class="ti-youtube"></i>', p.link_youtube);
        renderLink('<i class="ti-world"></i>', p.link_website);
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
const warnaKecamatan = {
  '3526010': '#baecb3', // Kamal
  '3526020': '#e1b12c', // Labang
  '3526030': '#c23616', // Kwanyar
  '3526040': '#8c7ae6',
  '3526050': '#00a8ff',
  '3526060': '#9c88ff',
  '3526070': '#fbc531',
  '3526080': '#e84118',
  '3526090': '#7f8fa6',
  '3526100': '#487eb0',
  '3526110': '#192a56', // Bangkalan
  '3526120': '#40739e', // Burneh
  '3526130': '#0097e6', // Arosbaya
  '3526140': '#44bd32',
  '3526150': '#e1b12c',
  '3526160': '#c23616',
  '3526170': '#8c7ae6', // Sepulu
  '3526180': '#273c75'  // Klampis
};
const namaKecamatan = {
  '3526010': 'Kamal',
  '3526020': 'Labang',
  '3526030': 'Kwanyar',
  '3526040': 'Modung',
  '3526050': 'Blega',
  '3526060': 'Konang',
  '3526070': 'Galis',
  '3526080': 'Tanah Merah',
  '3526090': 'Tragah',
  '3526100': 'Socah',
  '3526110': 'Bangkalan',
  '3526120': 'Burneh',
  '3526130': 'Arosbaya',
  '3526140': 'Geger',
  '3526150': 'Kokop',
  '3526160': 'Tanjung Bumi',
  '3526170': 'Sepulu',
  '3526180': 'Klampis'
};
let regionColors = {};
for (let regionCode in jvm.Map.maps['bangkalan'].paths) {
  let kecamatanId = regionCode.substring(0, 7);
  regionColors[regionCode] = warnaKecamatan[kecamatanId] || '#dcdde1';
}
$('#map-bangkalan').vectorMap({
  map: 'bangkalan',
  backgroundColor: 'transparent',
  zoomOnScroll: true,
  panOnDrag: true,

  regionStyle: {
    initial: {
      stroke: '#ffffff',
      'stroke-width': 0.4
    },
    hover: {
      'fill-opacity': 0.1,
      cursor: 'pointer'
    }
  },

  series: {
    regions: [{
      values: regionColors,
      attribute: 'fill'
    }]
  },

  onRegionTipShow: function (e, el, code) {
    let kecId = code.substring(0, 7);
    let kecNama = namaKecamatan[kecId] || kecId;

    el.html(`
      <strong>Desa</strong><br>
      ${el.text()}<br>
      <small>Kec. ${kecNama}</small>
    `);
  }
});
});
</script>
<!-- Bootstrap with CDN Fallback Error Handling -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" onerror="console.warn('Bootstrap CDN failed to load')"></script>
<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
?>
