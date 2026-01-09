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
                                            <div class="col-xl-4 col-md-6">
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
                                                    <div class="card-block"></div>
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
<div class="modal fade" id="jadwalModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalJudul"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <table class="table table-sm">
          <tr>
            <th width="150">Tanggal</th>
            <td id="modalTanggal"></td>
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
            <th>Keterangan</th>
            <td id="modalKeterangan"></td>
          </tr>
        </table>
      </div>

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
document.addEventListener('DOMContentLoaded', function () {

  var calendar = new FullCalendar.Calendar(
    document.getElementById('calendar'),
    {
      initialView: 'dayGridMonth',
      height: 520,
      locale: 'id',
      events: 'kalender_jadwal.php',

      eventClick: function(info) {

        // Judul
        document.getElementById('modalJudul').innerText =
          info.event.title;

        // Tanggal
        document.getElementById('modalTanggal').innerText =
          info.event.start.toLocaleDateString('id-ID');

        // Tim
        document.getElementById('modalTim').innerText =
          info.event.extendedProps.tim ?? '-';

        // Status (ubah ke teks)
        let statusText = '-';
        switch (info.event.extendedProps.status) {
          case 0: statusText = 'Belum Dikerjakan'; break;
          case 1: statusText = 'Proses'; break;
          case 2: statusText = 'Selesai'; break;
        }
        document.getElementById('modalStatus').innerText = statusText;

        // Keterangan
        document.getElementById('modalKeterangan').innerHTML =
          info.event.extendedProps.keterangan ?? '-';

        // PIC
        document.getElementById('modalPIC').innerHTML = `
          <b>Desain:</b> ${info.event.extendedProps.pic_desain ?? '-'}<br>
          <b>Medsos:</b> ${info.event.extendedProps.pic_medsos ?? '-'}<br>
          <b>Narasi:</b> ${info.event.extendedProps.pic_narasi ?? '-'}
        `;

        // Show modal
        new bootstrap.Modal(
          document.getElementById('jadwalModal')
        ).show();
      }
    }
  );

  calendar.render();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
