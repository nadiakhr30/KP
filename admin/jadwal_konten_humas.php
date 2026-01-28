<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// Fetch jadwal data for calendar
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

$jadwalkalender = [];
while ($row = mysqli_fetch_assoc($qKalender)) {
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
            'pic_display' => $picDisplay,
            'pic_data' => $picData,
            'dokumentasi' => $row['dokumentasi'],
            'link_instagram' => $row['link_instagram'],
            'link_facebook' => $row['link_facebook'],
            'link_youtube' => $row['link_youtube'],
            'link_website' => $row['link_website']
        ]
    ];
}

// Fetch jadwal list for table tab with PIC information
$qJadwalList = mysqli_query($koneksi, "
    SELECT 
        j.id_jadwal,
        j.topik,
        j.judul_kegiatan,
        j.tanggal_penugasan,
        j.tanggal_rilis,
        j.tim,
        j.status,
        GROUP_CONCAT(CONCAT(jp.nama_jenis_pic, ': ', u.nama) SEPARATOR ', ') as pic_info
    FROM jadwal j
    LEFT JOIN pic p ON j.id_jadwal = p.id_jadwal
    LEFT JOIN user u ON p.nip = u.nip
    LEFT JOIN jenis_pic jp ON p.id_jenis_pic = jp.id_jenis_pic
    GROUP BY j.id_jadwal, j.topik, j.judul_kegiatan, j.tanggal_penugasan, j.tanggal_rilis, j.tim, j.status
    ORDER BY j.tanggal_rilis DESC
");
$jadwalList = [];
while ($row = mysqli_fetch_assoc($qJadwalList)) {
    $jadwalList[] = $row;
}

// Render with layout
$content = ob_get_clean();
ob_start();
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Jadwal Konten Humas</h5>
                        <p class="m-b-0">Untuk mengelola jadwal konten dan kegiatan dalam sistem kehumasan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title align-items-right">
                        <li class="breadcrumb-item">
                            <a href="index.php"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="index.php">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="jadwal_konten_humas.php">Jadwal Konten Humas</a>
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
                    <!-- Calendar Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Kalender Jadwal Konten</h5>
                        </div>
                        <div class="card-block">
                            <div id="calendar" style="min-height: 600px;"></div>
                        </div>
                    </div>

                    <!-- Management Tabs -->
                    <ul class="nav nav-tabs tabs card-block" role="tablist" style="margin-top: 30px;">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#card" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#table" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                        </li>
                    </ul>
                    <div class="tab-content tabs card">
                        <!-- Card View Tab -->
                        <div class="tab-pane p-5 active" id="card" role="tabpanel">
                            <div class="row m-b-10">
                                <div class="col-6">
                                    <div class="dropdown-info dropdown open">
                                        <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetakCard" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                        <div class="dropdown-menu" aria-labelledby="cetakCard" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                            <a class="dropdown-item waves-light waves-effect" href="#">Print</a>
                                            <a class="dropdown-item waves-light waves-effect" href="#">Excel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="align-items-right" style="float: right;">
                                        <a href="tambah/tambah_jadwal.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row users-card">
                                <?php if (count($jadwalList) === 0): ?>
                                    <div class="col-12 text-center">
                                        <p>Tidak ada data jadwal tersedia.</p>
                                    </div>
                                <?php else: ?>
                                <?php foreach ($jadwalList as $jadwal): ?>
                                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                    <div class="card rounded-card">
                                        <div class="card-block">
                                            <div style="margin-bottom: 15px;">
                                                <?php
                                                    $statusClass = 'danger';
                                                    $statusText = 'Belum Dikerjakan';
                                                    if ($jadwal['status'] == 1) {
                                                        $statusClass = 'warning';
                                                        $statusText = 'Sedang Dikerjakan';
                                                    } elseif ($jadwal['status'] == 2) {
                                                        $statusClass = 'success';
                                                        $statusText = 'Selesai';
                                                    }
                                                ?>
                                                <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                                            </div>
                                            <h5><?= htmlspecialchars($jadwal['judul_kegiatan']) ?></h5>
                                            <p class="text-muted mb-2"><strong>Topik:</strong> <?= htmlspecialchars($jadwal['topik'] ?? '-') ?></p>
                                            <p class="text-muted mb-2"><strong>Tim:</strong> <?= htmlspecialchars($jadwal['tim'] ?? '-') ?></p>
                                            <p class="text-muted mb-2"><strong>Tanggal Penugasan:</strong> <?= $jadwal['tanggal_penugasan'] ? date('d-m-Y', strtotime($jadwal['tanggal_penugasan'])) : '-' ?></p>
                                            <p class="text-muted mb-2"><strong>Target Rilis:</strong> <?= date('d-m-Y', strtotime($jadwal['tanggal_rilis'])) ?></p>
                                            <p class="text-muted mb-3"><strong>PIC:</strong> <?= htmlspecialchars($jadwal['pic_info'] ?? '-') ?></p>
                                            <div style="display: flex; gap: 8px;">
                                                <a href="edit/edit_jadwal.php?id=<?= $jadwal['id_jadwal'] ?>" class="btn btn-sm btn-warning waves-effect waves-light"><i class="ti-pencil"></i> Edit</a>
                                                <a href="hapus/hapus_jadwal.php?id=<?= $jadwal['id_jadwal'] ?>" class="btn btn-sm btn-danger waves-effect waves-light" onclick="return confirm('Yakin ingin menghapus?');"><i class="ti-trash"></i> Hapus</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Table View Tab -->
                        <div class="tab-pane p-4" id="table" role="tabpanel">
                            <div class="card-block">
                                <div class="row m-b-10">
                                    <div class="col-6">
                                        <div class="dropdown-info dropdown open">
                                            <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetakTable" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                            <div class="dropdown-menu" aria-labelledby="cetakTable" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                <a class="dropdown-item waves-light waves-effect" href="#">Print</a>
                                                <a class="dropdown-item waves-light waves-effect" href="#">Excel</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="align-items-right" style="float: right;">
                                            <a href="#" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="dt-responsive table-responsive">
                                    <table id="order-table" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Topik</th>
                                                <th>Judul Kegiatan</th>
                                                <th>Tim</th>
                                                <th>Tanggal Penugasan</th>
                                                <th>Target Rilis</th>
                                                <th>PIC</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($jadwalList) === 0): ?>
                                            <tr>
                                                <td colspan="9" class="text-center">Tidak ada data jadwal tersedia.</td>
                                            </tr>
                                            <?php else: ?>
                                            <?php foreach ($jadwalList as $jadwal): ?>
                                            <tr>
                                                <td><?= $jadwal['id_jadwal'] ?></td>
                                                <td><?= htmlspecialchars($jadwal['topik'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($jadwal['judul_kegiatan']) ?></td>
                                                <td><?= htmlspecialchars($jadwal['tim'] ?? '-') ?></td>
                                                <td><?= $jadwal['tanggal_penugasan'] ? date('d-m-Y', strtotime($jadwal['tanggal_penugasan'])) : '-' ?></td>
                                                <td><?= date('d-m-Y', strtotime($jadwal['tanggal_rilis'])) ?></td>
                                                <td><?= htmlspecialchars($jadwal['pic_info'] ?? '-') ?></td>
                                                <td>
                                                    <?php
                                                        $statusClass = 'secondary';
                                                        $statusText = 'Belum Dikerjakan';
                                                        if ($jadwal['status'] == 1) {
                                                            $statusClass = 'warning';
                                                            $statusText = 'Sedang Dikerjakan';
                                                        } elseif ($jadwal['status'] == 2) {
                                                            $statusClass = 'success';
                                                            $statusText = 'Selesai';
                                                        }
                                                    ?>
                                                    <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                                                </td>
                                                <td>
                                                    <a href="edit/edit_jadwal.php?id=<?= $jadwal['id_jadwal'] ?>" class="btn btn-sm btn-warning waves-effect waves-light"><i class="ti-pencil"></i></a>
                                                    <a href="hapus/hapus_jadwal.php?id=<?= $jadwal['id_jadwal'] ?>" class="btn btn-sm btn-danger waves-effect waves-light" onclick="return confirm('Yakin ingin menghapus?');"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Topik</th>
                                                <th>Judul Kegiatan</th>
                                                <th>Tim</th>
                                                <th>Tanggal Penugasan</th>
                                                <th>Target Rilis</th>
                                                <th>PIC</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Jadwal Details (Bootstrap Modal) -->
<div class="modal fade" id="jadwalModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalJudulKegiatan">-</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Topik:</strong> <span id="modalTopik">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tim:</strong> <span id="modalTim">-</span></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Tanggal Penugasan:</strong> <span id="modalTanggalPenugasan">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Target Rilis:</strong> <span id="modalTargetRilis">-</span></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <p><strong>Status:</strong> <span id="modalStatus">-</span></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <p><strong>PIC:</strong></p>
                        <div id="modalPIC" style="margin-left: 15px;">-</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <p><strong>Keterangan:</strong></p>
                        <div id="modalKeterangan" style="margin-left: 15px;">-</div>
                    </div>
                </div>
                <div class="row mb-3" id="rowDokumentasi" style="display: none;">
                    <div class="col-12">
                        <p><strong>Dokumentasi:</strong> <span id="modalDokumentasi">-</span></p>
                    </div>
                </div>
                <div class="row mb-3" id="rowLink" style="display: none;">
                    <div class="col-12">
                        <p><strong>Link Publikasi:</strong> <span id="modalLinks">-</span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
ob_start();
?>
<script>
// Modal for event details (Bootstrap Modal version)
function showEventDetail(eventData) {
    let statusText = '-';
    let statusClass = 'danger';
    switch (String(eventData.status)) {
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
        default:
            statusText = 'Tidak Diketahui';
    }
    
    // Set modal header title
    document.getElementById('modalJudulKegiatan').innerText = eventData.title || '-';
    
    // Set topik
    document.getElementById('modalTopik').innerText = eventData.topik || '-';
    
    // Set tanggal penugasan
    document.getElementById('modalTanggalPenugasan').innerText = 
        eventData.tanggal_penugasan 
            ? new Date(eventData.tanggal_penugasan).toLocaleDateString('id-ID') 
            : '-';
    
    // Set target rilis
    document.getElementById('modalTargetRilis').innerText = 
        eventData.start 
            ? new Date(eventData.start).toLocaleDateString('id-ID') 
            : '-';
    
    // Set tim
    document.getElementById('modalTim').innerText = eventData.tim || '-';
    
    // Set status
    document.getElementById('modalStatus').innerHTML = 
        `<span class="badge bg-${statusClass}">${statusText}</span>`;
    
    // Set PIC data
    let picHtml = '-';
    const picData = eventData.pic_data || {};
    if (Object.keys(picData).length > 0) {
        picHtml = '<ul style="margin-bottom: 0; padding-left: 20px;">';
        for (const [jenis, nama] of Object.entries(picData)) {
            picHtml += `<li><b>${jenis}:</b> ${nama}</li>`;
        }
        picHtml += '</ul>';
    }
    document.getElementById('modalPIC').innerHTML = picHtml;
    
    // Set keterangan
    document.getElementById('modalKeterangan').innerHTML = eventData.keterangan || '-';
    
    // Set dokumentasi
    if (eventData.dokumentasi) {
        document.getElementById('rowDokumentasi').style.display = '';
        document.getElementById('modalDokumentasi').innerHTML = 
            `<a href="${eventData.dokumentasi}" target="_blank" class="link-primary">Lihat Dokumentasi</a>`;
    } else {
        document.getElementById('rowDokumentasi').style.display = 'none';
    }
    
    // Set link publikasi
    let links = [];
    function renderLink(label, url) {
        if (!url) return;
        if (url === '-') {
            links.push(`<span class="text-muted">${label}</span>`);
            return;
        }
        links.push(`<a href="${url}" target="_blank" class="link-primary">${label}</a>`);
    }
    renderLink('<i class="ti-instagram"></i>', eventData.link_instagram);
    renderLink('<i class="ti-facebook"></i>', eventData.link_facebook);
    renderLink('<i class="ti-youtube"></i>', eventData.link_youtube);
    renderLink('<i class="ti-world"></i>', eventData.link_website);
    
    if (links.length > 0) {
        document.getElementById('rowLink').style.display = '';
        document.getElementById('modalLinks').innerHTML = links.join(' | ');
    } else {
        document.getElementById('rowLink').style.display = 'none';
    }
    
    new bootstrap.Modal(document.getElementById('jadwalModal')).show();
}

// Calendar - Only initialize if FullCalendar library loaded successfully
var calendarInstance = null;
document.addEventListener('DOMContentLoaded', function () {
    // Check if FullCalendar is available
    if (typeof FullCalendar === 'undefined' || window.FULLCALENDAR_DISABLED) {
        console.warn('FullCalendar not available - skipping calendar initialization');
        return;
    }

    // Only initialize once
    if (calendarInstance) return;

    calendarInstance = new FullCalendar.Calendar(
        document.getElementById('calendar'),
        {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listMonth,timeGridWeek,timeGridDay'
            },
            height: 'auto',
            locale: 'id',
            events: " . json_encode($jadwalkalender) . ",
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                const eventData = {
                    title: info.event.title,
                    start: info.event.start,
                    id: info.event.id,
                    ...info.event.extendedProps
                };
                showEventDetail(eventData);
            }
        }
    );
    calendarInstance.render();
});
</script>
<!-- Bootstrap with CDN Fallback Error Handling -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" onerror="console.warn('Bootstrap CDN failed to load')"></script>
<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
?>
