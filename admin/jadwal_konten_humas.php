<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
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
        j.dokumentasi
    FROM jadwal j
    ORDER BY j.tanggal_penugasan DESC
");

if (!$qKalender) {
    die("Database error: " . mysqli_error($koneksi));
}

$jadwalkalender = [];
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
    
    // Get links from jadwal_link
    $qLinks = mysqli_query($koneksi, "
        SELECT jl.id_jadwal_link, jl.id_jenis_link, jenis_link.nama_jenis_link, jl.link
        FROM jadwal_link jl
        JOIN jenis_link ON jl.id_jenis_link = jenis_link.id_jenis_link
        WHERE jl.id_jadwal = " . (int)$id_jadwal
    );
    
    $linksData = [];
    if ($qLinks) {
        while ($linkRow = mysqli_fetch_assoc($qLinks)) {
            $linksData[$linkRow['nama_jenis_link']] = [
                'id_jenis_link' => $linkRow['id_jenis_link'],
                'link' => $linkRow['link'] ?: ''
            ];
        }
    }
    
    $picData = [];
    $picNips = [];
    if ($qPic) {
        while ($pic = mysqli_fetch_assoc($qPic)) {
            $picData[$pic['nama_jenis_pic']] = $pic['nama'];
            $picNips[] = $pic['nip'];
        }
    }
    
    // Check if current user is PIC of this jadwal
    $isUserPic = isset($_SESSION['pegawai']['nip']) && in_array($_SESSION['pegawai']['nip'], $picNips);
    
    // Set color based on status
    $color = match ($row['status']) {
        0 => '#e84118',
        1 => '#fbc531',
        2 => '#44bd32',
        default => '#718093',
    };
    
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
            'is_user_pic' => $isUserPic,
            'pic_display' => $picDisplay,
            'pic_data' => $picData,
            'dokumentasi' => $row['dokumentasi'],
            'links_data' => $linksData
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
    // Fetch detailed PIC data for this jadwal
    $qDetailPic = mysqli_query($koneksi, "
        SELECT jp.nama_jenis_pic, u.nama
        FROM pic p
        JOIN user u ON p.nip = u.nip
        JOIN jenis_pic jp ON p.id_jenis_pic = jp.id_jenis_pic
        WHERE p.id_jadwal = " . (int)$row['id_jadwal'] . "
        ORDER BY jp.nama_jenis_pic
    ");
    
    $picDataDetail = [];
    while ($picDetail = mysqli_fetch_assoc($qDetailPic)) {
        $picDataDetail[$picDetail['nama_jenis_pic']] = $picDetail['nama'];
    }
    $row['pic_data_detail'] = $picDataDetail;
    
    // Fetch links for this jadwal
    $qLinksDetail = mysqli_query($koneksi, "
        SELECT jl.id_jenis_link, jenis_link.nama_jenis_link, jl.link
        FROM jadwal_link jl
        JOIN jenis_link ON jl.id_jenis_link = jenis_link.id_jenis_link
        WHERE jl.id_jadwal = " . (int)$row['id_jadwal'] . "
        ORDER BY jenis_link.nama_jenis_link
    ");
    
    $linksDataDetail = [];
    while ($linkDetail = mysqli_fetch_assoc($qLinksDetail)) {
        $linksDataDetail[$linkDetail['nama_jenis_link']] = [
            'id_jenis_link' => $linkDetail['id_jenis_link'],
            'link' => $linkDetail['link'] ?: ''
        ];
    }
    $row['links_data'] = $linksDataDetail;
    
    $jadwalList[] = $row;
}

// Get all distinct PIC types for table columns
$qPicTypes = mysqli_query($koneksi, "
    SELECT DISTINCT nama_jenis_pic 
    FROM jenis_pic 
    ORDER BY nama_jenis_pic
");
$picTypes = [];
while ($picType = mysqli_fetch_assoc($qPicTypes)) {
    $picTypes[] = $picType['nama_jenis_pic'];
}

// Get all distinct link types for table columns
$qLinkTypes = mysqli_query($koneksi, "
    SELECT DISTINCT nama_jenis_link 
    FROM jenis_link 
    ORDER BY nama_jenis_link
");
$linkTypes = [];
while ($linkType = mysqli_fetch_assoc($qLinkTypes)) {
    $linkTypes[] = $linkType['nama_jenis_link'];
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
                                            <a class="dropdown-item waves-light waves-effect" href="export/export_jadwal.php?format=print">Print</a>
                                            <a class="dropdown-item waves-light waves-effect" href="export/export_jadwal.php?format=excel">Excel</a>
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
                                            <h5 class="text-center m-b-10"><?= htmlspecialchars($jadwal['judul_kegiatan']) ?></h5>
                                            <p class="text-muted mb-2"><strong>Topik:</strong> <?= htmlspecialchars($jadwal['topik'] ?? '-') ?></p>
                                            <p class="text-muted mb-2"><strong>Tim:</strong> <?= htmlspecialchars($jadwal['tim'] ?? '-') ?></p>
                                            <p class="text-muted mb-2"><strong>Tanggal Penugasan:</strong> <?= $jadwal['tanggal_penugasan'] ? date('d-m-Y', strtotime($jadwal['tanggal_penugasan'])) : '-' ?></p>
                                            <p class="text-muted mb-2"><strong>Target Rilis:</strong> <?= date('d-m-Y', strtotime($jadwal['tanggal_rilis'])) ?></p>
                                            <p class="text-muted mb-3"><strong>PIC:</strong>
<?php if (!empty($jadwal['pic_data_detail'])): ?>
    <span class="badge bg-info ms-2"
          style="cursor: help;"
          data-bs-toggle="tooltip"
          data-bs-html="true"
          title="<?php
              $picTooltip = [];
              foreach ($jadwal['pic_data_detail'] as $jenis => $nama) {
                  $picTooltip[] = htmlspecialchars($jenis . ': ' . $nama);
              }
              echo $picTooltip ? implode('<br>', $picTooltip) : '-';
          ?>">
        <i class="ti-user"></i> <?= count($jadwal['pic_data_detail']) ?> PIC
    </span>
<?php else: ?>
    <span class="text-muted">-</span>
<?php endif; ?>
                                            </p>
                                            <p class="text-muted mb-3"><strong>Link Publikasi:</strong>
<?php if (!empty($jadwal['links_data'])): ?>
    <span class="badge bg-primary ms-2"
          style="cursor: help;"
          data-bs-toggle="tooltip"
          data-bs-html="true"
          title="<?php
              $linkTooltip = [];
              foreach ($jadwal['links_data'] as $jenis => $linkData) {
                  $status = !empty($linkData['link']) ? 'Tersedia' : 'Tidak tersedia';
                  $linkTooltip[] = htmlspecialchars($jenis . ': ' . $status);
              }
              echo implode('<br>', $linkTooltip);
          ?>">
        <i class="ti-world"></i> <?= count($jadwal['links_data']) ?> Link
    </span>
<?php else: ?>
    <span class="text-muted">-</span>
<?php endif; ?>
                                            </p>
                                            <p class="text-muted mb-3"><strong>Dokumentasi:</strong>
<?php if (!empty($jadwal['dokumentasi'])): ?>
    <span class="badge bg-success ms-2" style="cursor: help;" data-bs-toggle="tooltip" title="Dokumentasi tersedia">
        <i class="ti-eye"></i> Ada
    </span>
<?php else: ?>
    <span class="badge bg-secondary ms-2" style="cursor: help;" data-bs-toggle="tooltip" title="Dokumentasi tidak tersedia">
        <i class="ti-eye"></i> Tidak ada
    </span>
<?php endif; ?>
                                            </p>
                                            <div style="display: flex; gap: 8px; justify-content: center;">
                                                <a href="edit/edit_jadwal.php?id=<?= $jadwal['id_jadwal'] ?>" class="btn btn-icon btn-warning waves-effect waves-light"><i class="ti-pencil text-dark"></i></a>
                                                <button type="button" class="btn btn-icon btn-danger waves-effect waves-light" onclick="confirmDeleteJadwal(<?= $jadwal['id_jadwal'] ?>, '<?= htmlspecialchars($jadwal['judul_kegiatan'], ENT_QUOTES) ?>')"><i class="ti-trash text-dark"></i></button>
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
                                                <a class="dropdown-item waves-light waves-effect" href="export/export_jadwal.php?format=print">Print</a>
                                                <a class="dropdown-item waves-light waves-effect" href="export/export_jadwal.php?format=excel">Excel</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="align-items-right" style="float: right;">
                                            <a href="tambah/tambah_jadwal.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
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
                                                <?php foreach ($picTypes as $picType): ?>
                                                    <th>PIC <?= htmlspecialchars($picType) ?></th>
                                                <?php endforeach; ?>
                                                <?php foreach ($linkTypes as $linkType): ?>
                                                    <th><?= htmlspecialchars($linkType) ?></th>
                                                <?php endforeach; ?>
                                                <th>Dokumentasi</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($jadwalList) === 0): ?>
                                            <tr>
                                                <td colspan="<?= 8 + count($picTypes) + count($linkTypes) ?>" class="text-center">Tidak ada data jadwal tersedia.</td>
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
                                                <?php foreach ($picTypes as $picType): ?>
                                                    <td>
                                                        <?= htmlspecialchars($jadwal['pic_data_detail'][$picType] ?? '-') ?>
                                                    </td>
                                                <?php endforeach; ?>
                                                <?php foreach ($linkTypes as $linkType): ?>
                                                    <td>
                                                        <?php 
                                                            $linkData = $jadwal['links_data'][$linkType] ?? null;
                                                            if ($linkData && !empty($linkData['link'])):
                                                        ?>
                                                            <a href="<?= htmlspecialchars($linkData['link']) ?>" target="_blank" class="badge bg-primary text-decoration-none">
                                                                <i class="ti-world"></i>
                                                            </a>
                                                        <?php elseif ($linkData): ?>
                                                            <span class="badge bg-secondary">
                                                                <i class="ti-world"></i>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                                <td>
                                                    <?php if (!empty($jadwal['dokumentasi'])): ?>
                                                        <span class="badge bg-success">
                                                            <i class="ti-eye"></i>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">
                                                            <i class="ti-eye"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
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
                                                    <a href="edit/edit_jadwal.php?id=<?= $jadwal['id_jadwal'] ?>" class="btn btn-icon btn-warning waves-effect waves-light"><i class="ti-pencil text-dark"></i></a>
                                                    <button type="button" class="btn btn-icon btn-danger waves-effect waves-light" onclick="confirmDeleteJadwal(<?= $jadwal['id_jadwal'] ?>, '<?= htmlspecialchars($jadwal['judul_kegiatan'], ENT_QUOTES) ?>')"><i class="ti-trash text-dark"></i></button>
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
                                                <?php foreach ($picTypes as $picType): ?>
                                                    <th>PIC <?= htmlspecialchars($picType) ?></th>
                                                <?php endforeach; ?>
                                                <?php foreach ($linkTypes as $linkType): ?>
                                                    <th><?= htmlspecialchars($linkType) ?></th>
                                                <?php endforeach; ?>
                                                <th>Dokumentasi</th>
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

<!-- Modal for Edit Detail (Dokumentasi & Link Media Sosial) -->
<div class="modal fade" id="editDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <div>
                    <h5 class="text-muted m-b-5">Edit Detail Link</h5>
                    <h3 class="modal-title mb-0" id="editDetailTitle">-</h3>
                </div>
                <button type="button" class="btn btn-sm btn-danger btn-icon waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="editDetailForm">
                    <input type="hidden" id="editDetailId" name="id_jadwal" value="">
                    <!-- Dokumentasi -->
                    <div class="form-group mb-4">
                        <label for="editDokumentasi" class="form-label"><small class="text-muted fw-600">Dokumentasi</small></label>
                        <input type="url" class="form-control form-control-edit" id="editDokumentasi" name="dokumentasi" placeholder="Paste link dokumentasi...">
                    </div>
                    <div id="linksContainer"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light btn-icon" data-bs-dismiss="modal"><i class="fas fa-arrow-left"></i></button>
                <button type="button" class="btn btn-sm btn-primary btn-icon waves-effect waves-light" onclick="saveEditDetail()"><i class="fas fa-save"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Jadwal Details (Bootstrap Modal) -->
<div class="modal fade" id="jadwalModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <div>
                    <h5 class="text-muted m-b-5" id="modalTopik">-</h5>
                    <h3 class="modal-title mb-0" id="modalJudulKegiatan">-</h3>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <button type="button" class="btn btn-sm btn-warning waves-effect waves-light btn-icon" id="editDetailBtn" style="display: none;" onclick="openEditDetailModalFromJadwal()">
                        <i class="ti-pencil text-dark"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger waves-effect waves-light btn-icon" data-bs-dismiss="modal"><i class="fas fa-times text-dark"></i></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="row mb-md-3">
                    <div class="col-md-6">
                        <p class="m-b-5"><small class="text-muted">Tanggal Penugasan</small></p>
                        <p class="m-b-0" id="modalTanggalPenugasan">-</p>
                    </div>
                    <div class="col-md-6">
                        <p class="m-b-5"><small class="text-muted">Target Rilis</small></p>
                        <p class="m-b-0" id="modalTargetRilis">-</p>
                    </div>
                </div>
                <div class="row mb-md-3">
                    <div class="col-md-6">
                        <p class="m-b-5"><small class="text-muted">Tim</small></p>
                        <p class="m-b-0" id="modalTim">-</p>
                    </div>
                    <div class="col-md-6">
                        <p class="m-b-5"><small class="text-muted">Status</small></p>
                        <p class="m-b-0" id="modalStatus">-</p>
                    </div>
                </div>
                <div class="row mb-md-3">
                    <div class="col-md-6">
                        <p class="m-b-5"><small class="text-muted">PIC (Person In Charge)</small></p>
                        <div id="modalPIC" class="ps-3" style="margin-left: 10px;">-</div>
                    </div>
                    <div class="col-md-6">
                        <p class="m-b-5"><small class="text-muted">Keterangan</small></p>
                        <div id="modalKeterangan" class="ps-3 text-secondary">-</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6" id="rowDokumentasi" style="display:none">
                        <p class="m-b-5"><small class="text-muted">Dokumentasi</small></p>
                        <div id="modalDokumentasi"></div>
                    </div>
                    <div class="col-md-6" id="rowLink" style="display:none">
                        <p class="m-b-5"><small class="text-muted">Link Publikasi</small></p>
                        <div id="modalLinks"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Jadwal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px; display: flex; align-items: center; justify-content: center; min-height: 100vh;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; background: #fcf2f2; border-left: 5px solid #e74c3c;">
            <div class="modal-body" style="padding: 40px 30px; text-align: center;">
                <div style="margin-bottom: 20px;">
                    <i class="ti-alert" style="font-size: 56px; color: #e74c3c;"></i>
                </div>
                <h5 style="color: #2c3e50; font-weight: 700; font-size: 18px; margin-bottom: 10px;">Konfirmasi Hapus</h5>
                <p style="font-size: 14px; color: #7f8c8d; margin-bottom: 20px;">Apakah Anda yakin ingin menghapus jadwal <strong id="deleteConfirmTitle"></strong>?</p>
                <p style="color: #e74c3c; font-size: 12px; margin-top: 20px; margin-bottom: 30px;">
                    <i class="ti-alert-alt" style="margin-right: 6px;"></i>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <input type="hidden" id="jadwalToDeleteId" value="">
                <div style="display: flex; justify-content: center; gap: 15px;">
                    <button type="button" class="btn btn-secondary btn-icon waves-effect waves-light" data-bs-dismiss="modal" title="Batal">
                        <i class="ti-close"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-icon waves-effect waves-light" id="confirmDeleteBtn" title="Hapus" onclick="performDeleteJadwal()">
                        <i class="ti-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
ob_start();
?>
<!-- Sweetalert2 untuk Notifikasi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Delete Jadwal
let jadwalToDelete = null;

function confirmDeleteJadwal(id, title) {
    jadwalToDelete = id;
    document.getElementById('deleteConfirmTitle').innerText = title;
    new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
}

function performDeleteJadwal() {
    if (!jadwalToDelete) return;
    
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>';
    
    fetch('hapus/hapus_jadwal.php?id=' + jadwalToDelete)
        .then(response => response.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
            
            // Show sweetalert notification
            const icon = data.success ? 'success' : 'error';
            const title = data.success ? 'Berhasil!' : 'Gagal!';
            
            Swal.fire({
                icon: icon,
                title: title,
                text: data.message,
                confirmButtonColor: data.success ? '#3085d6' : '#d33',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed && data.success) {
                    location.reload();
                } else {
                    // Reset button jika gagal
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = '<i class="ti-trash"></i>';
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menghapus data',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
            
            // Reset button
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = '<i class="ti-trash"></i>';
        });
}

// Notification function (tidak lagi dipakai, diganti sweetalert)
function showNotification(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 4000);
}

// Open Edit Detail Modal from Jadwal Modal
function openEditDetailModalFromJadwal() {
    if (!currentJadwalData) return;
    
    document.getElementById('editDetailId').value = currentJadwalData.id;
    document.getElementById('editDetailTitle').innerText = currentJadwalData.title;
    
    // Fetch current data
    fetch('get_jadwal_detail.php?id=' + currentJadwalData.id)
        .then(response => response.json())
        .then(data => {
            // Set dokumentasi
            document.getElementById('editDokumentasi').value = data.dokumentasi || '';
            
            // Build links container dynamically
            let linksHtml = '';
            if (Object.keys(data.links).length > 0) {
                linksHtml += '<div class="form-row">';
                for (const [jenis, linkData] of Object.entries(data.links)) {
                    const fieldName = 'link_' + jenis.toLowerCase().replace(' ', '_');
                    linksHtml += `
                        <div class="col-md-6 form-group mb-4">
                            <label for="edit${jenis}" class="form-label">
                                <small class="text-muted fw-600">${jenis}</small>
                            </label>
                            <input 
                                type="url" 
                                class="form-control form-control-edit" 
                                id="edit${jenis}" 
                                name="${fieldName}"
                                data-jenis-link-id="${linkData.id_jenis_link}"
                                placeholder="https://example.com/${jenis.toLowerCase()}..."
                                value="${linkData.link || ''}"
                            >
                        </div>
                    `;
                }
                linksHtml += '</div>';
            }
            document.getElementById('linksContainer').innerHTML = linksHtml;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            alert('Gagal memuat data');
        });
    
    // Hide jadwal modal and show edit modal
    bootstrap.Modal.getInstance(document.getElementById('jadwalModal')).hide();
    new bootstrap.Modal(document.getElementById('editDetailModal')).show();
}

// Save Edit Detail
function saveEditDetail() {
    const jadwalId = document.getElementById('editDetailId').value;
    const form = document.getElementById('editDetailForm');
    const formData = new FormData(form);
    formData.append('id_jadwal', jadwalId);
    
    fetch('update_jadwal_detail.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('editDetailModal')).hide();
            // Refresh halaman
            location.reload();
        } else {
            alert('Gagal menyimpan data: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
}

function showEventDetail(eventData) {
    // Store current data untuk edit button
    currentJadwalData = eventData;
    
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
    
    const editBtn = document.getElementById('editDetailBtn');
    if (eventData.is_user_pic) {
        editBtn.style.display = 'block';
    } else {
        editBtn.style.display = 'none';
    }
    
    // Set modal header title
    document.getElementById('modalJudulKegiatan').innerText = eventData.title || '-';
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
    let picHtml = '<span class="text-muted">-</span>';
    const picData = eventData.pic_data || {};
    if (Object.keys(picData).length > 0) {
        picHtml = '<ul style="margin: 0; padding-left: 20px;">';
        for (const [jenis, nama] of Object.entries(picData)) {
            picHtml += `<li><b>${jenis}:</b> ${nama}</li>`;

        }
        picHtml += '</ul>';
    }
    document.getElementById('modalPIC').innerHTML = picHtml;
    
    // Set keterangan
    document.getElementById('modalKeterangan').innerHTML = eventData.keterangan || '<span class="text-muted">-</span>';
    
    // Set dokumentasi - always show the row
    document.getElementById('rowDokumentasi').style.display = '';
    if (eventData.dokumentasi) {
        // Ada isi - bisa di-klik
        document.getElementById('modalDokumentasi').innerHTML = 
            `<a href="${eventData.dokumentasi}" target="_blank" style="color: #007bff; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
              <i class="ti-eye" style="font-size: 18px;"></i> Lihat Dokumentasi
            </a>`;
    } else {
        // Kosong - icon abu-abu, tidak aktif
        document.getElementById('modalDokumentasi').innerHTML = 
            `<span style="color: #adb5bd; cursor: not-allowed; display: inline-flex; align-items: center; gap: 8px;">
              <i class="ti-eye" style="font-size: 18px;"></i> Tidak ada dokumentasi
            </span>`;
    }
    
    // Set link publikasi
    let links = [];
    const linksData = eventData.links_data || {};
    
    // Map icon untuk setiap jenis link
    const linkIcons = {
        'Instagram': 'ti-instagram',
        'Facebook': 'ti-facebook',
        'YouTube': 'ti-youtube',
        'Website': 'ti-world'
    };
    
    for (const [jenis, linkData] of Object.entries(linksData)) {
        const url = linkData.link || '';
        const icon = linkIcons[jenis] || 'ti-link';
        
        if (url) {
            // Ada isi - icon aktif, bisa diklik
            links.push(
                `<a href="${url}" target="_blank" style="color: #007bff; text-decoration: none; font-size: 20px; cursor: pointer;" onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#007bff'" title="Buka ${jenis} di tab baru">
                  <i class="${icon}"></i>
                </a>`
            );
        } else {
            // Kosong - icon abu-abu, tidak aktif
            links.push(
                `<span style="color: #adb5bd; cursor: not-allowed; font-size: 20px;" title="${jenis} - Belum ada link">
                  <i class="${icon}"></i>
                </span>`
            );
        }
    }
    
    if (links.length > 0) {
        document.getElementById('rowLink').style.display = '';
        document.getElementById('modalLinks').innerHTML = '<div style="display: flex; gap: 15px;">' + links.join('') + '</div>';
    } else {
        document.getElementById('rowLink').style.display = 'none';
    }
    
    new bootstrap.Modal(document.getElementById('jadwalModal')).show();
}

// Calendar - Only initialize if FullCalendar library loaded successfully
var calendarInstance = null;
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOMContentLoaded event fired');
    
    var calendarEl = document.getElementById('calendar');
    console.log('Calendar element found:', calendarEl);
    
    // Check if FullCalendar is available
    if (typeof FullCalendar === 'undefined' || window.FULLCALENDAR_DISABLED) {
        console.warn('FullCalendar not available - skipping calendar initialization');
        if (calendarEl) {
            calendarEl.innerHTML = '<div class="alert alert-warning">FullCalendar library not loaded. Please check console for errors.</div>';
        }
        return;
    }

    // Only initialize once
    if (calendarInstance) {
        console.warn('Calendar already initialized');
        return;
    }

    // Calendar events data from database
    var calendarEvents = <?php echo json_encode($jadwalkalender); ?>;
    console.log('Calendar Events from database:', calendarEvents);
    console.log('Total events:', calendarEvents.length);

    if (!calendarEl) {
        console.error('Calendar element with id "calendar" not found!');
        return;
    }

    try {
        calendarInstance = new FullCalendar.Calendar(
            calendarEl,
            {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                height: 'auto',
                locale: 'id',
                events: calendarEvents,
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
        console.log('Calendar instance created successfully');
        calendarInstance.render();
        console.log('Calendar rendered successfully');
    } catch (error) {
        console.error('Error initializing calendar:', error);
        if (calendarEl) {
            calendarEl.innerHTML = '<div class="alert alert-danger">Error initializing calendar: ' + error.message + '</div>';
        }
    }
});
</script>
<!-- Bootstrap with CDN Fallback Error Handling -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" onerror="console.warn('Bootstrap CDN failed to load')"></script>
<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
?>

