<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

if (!$qKalender) {
    die("Database error: " . mysqli_error($koneksi));
}

$jadwalkalender = [];
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
    $picNips = [];
    if ($qPic) {
        while ($pic = mysqli_fetch_assoc($qPic)) {
            $picData[$pic['nama_jenis_pic']] = $pic['nama'];
            $picNips[] = $pic['nip'];
        }
    }
    
    // Check if current user is PIC of this jadwal
    $isUserPic = isset($_SESSION['user']['nip']) && in_array($_SESSION['user']['nip'], $picNips);
    
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
                    <div class="form-group mb-4" id="docGroup" style="display: none;">
                        <label for="editDokumentasi" class="form-label"><small class="text-muted fw-600">Dokumentasi</small></label>
                        <input type="url" class="form-control form-control-edit" id="editDokumentasi" name="dokumentasi" placeholder="Paste link dokumentasi...">
                    </div>
                    <div class="form-row">
                        <!-- Instagram -->
                        <div class="col-md-6 form-group mb-4" id="igGroup" style="display: none;">
                            <label for="editInstagram" class="form-label">
                                <small class="text-muted fw-600">Instagram</small>
                           </label>
                            <input 
                                type="url" 
                                class="form-control form-control-edit" 
                                id="editInstagram" 
                                name="link_instagram"
                                placeholder="https://instagram.com/post/..."
                            >
                        </div>
                        <!-- Facebook -->
                        <div class="col-md-6 form-group mb-4" id="fbGroup" style="display: none;">
                            <label for="editFacebook" class="form-label">
                                <small class="text-muted fw-600">Facebook</small>
                            </label>
                            <input 
                                type="url" 
                                class="form-control form-control-edit" 
                                id="editFacebook" 
                            name="link_facebook"
                            placeholder="https://facebook.com/post/..."
                        >
                    </div>
                    <!-- YouTube -->
                    <div class="col-md-6 form-group mb-4" id="ytGroup" style="display: none;">
                        <label for="editYoutube" class="form-label">
                            <small class="text-muted fw-600">YouTube</small>
                        </label>
                        <input 
                            type="url" 
                            class="form-control form-control-edit" 
                            id="editYoutube" 
                            name="link_youtube"
                            placeholder="https://youtube.com/watch?v=..."
                        >
                    </div>
                    <!-- Website -->
                    <div class="col-md-6 form-group mb-0" id="webGroup" style="display: none;">
                        <label for="editWebsite" class="form-label">
                            <small class="text-muted fw-600">Website</small>
                        </label>
                        <input 
                            type="url" 
                            class="form-control form-control-edit" 
                            id="editWebsite" 
                            name="link_website"
                            placeholder="https://website.com/..."
                        >
                    </div>
                    </div>
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
                        <i class="ti-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger waves-effect waves-light btn-icon" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
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

<?php
$content = ob_get_clean();
ob_start();
?>
<script>
// Add custom styles for modals
const style = document.createElement('style');
style.textContent = `
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
    }
    .modal-header .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    .modal-header h5 {
        font-weight: 500;
        font-size: 0.9rem;
    }
    .modal-header h3 {
        font-weight: 600;
        font-size: 1.4rem;
    }
    .modal-body {
        padding: 2rem;
    }
    .modal-body .row {
        margin-bottom: 1.5rem;
    }
    .modal-body p {
        margin: 0;
    }
    .modal-body small {
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .badge {
        padding: 0.4rem 0.8rem;
        font-weight: 500;
        font-size: 0.85rem;
    }
    #modalPIC ul, #modalPIC ul li {
        margin: 0;
    }
    #modalPIC ul li {
        margin-bottom: 0.3rem;
    }
    .form-control-edit {
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 0.95rem;
        border: 1.5px solid #e0e0e0;
    }
    .form-control-edit:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        background-color: #f8f9fa;
    }
`;
document.head.appendChild(style);

// Store current jadwal data
let currentJadwalData = null;

// Open Edit Detail Modal from Jadwal Modal
function openEditDetailModalFromJadwal() {
    if (!currentJadwalData) return;
    
    document.getElementById('editDetailId').value = currentJadwalData.id;
    document.getElementById('editDetailTitle').innerText = currentJadwalData.title;
    
    // Fetch current data
    fetch('get_jadwal_detail.php?id=' + currentJadwalData.id)
        .then(response => response.json())
        .then(data => {
            // Show/hide fields based on whether they have content (bukan NULL, bukan empty string)
            const docGroup = document.getElementById('docGroup');
            const igGroup = document.getElementById('igGroup');
            const fbGroup = document.getElementById('fbGroup');
            const ytGroup = document.getElementById('ytGroup');
            const webGroup = document.getElementById('webGroup');
            
            // Dokumentasi selalu ditampilkan
            docGroup.style.display = 'block';
            document.getElementById('editDokumentasi').value = data.dokumentasi || '';
            
            // Links ditampilkan jika ada value (selain NULL)
            if (data.link_instagram !== null && data.link_instagram !== '') {
                igGroup.style.display = 'block';
                document.getElementById('editInstagram').value = data.link_instagram;
            } else {
                igGroup.style.display = 'none';
                document.getElementById('editInstagram').value = '';
            }
            
            if (data.link_facebook !== null && data.link_facebook !== '') {
                fbGroup.style.display = 'block';
                document.getElementById('editFacebook').value = data.link_facebook;
            } else {
                fbGroup.style.display = 'none';
                document.getElementById('editFacebook').value = '';
            }
            
            if (data.link_youtube !== null && data.link_youtube !== '') {
                ytGroup.style.display = 'block';
                document.getElementById('editYoutube').value = data.link_youtube;
            } else {
                ytGroup.style.display = 'none';
                document.getElementById('editYoutube').value = '';
            }
            
            if (data.link_website !== null && data.link_website !== '') {
                webGroup.style.display = 'block';
                document.getElementById('editWebsite').value = data.link_website;
            } else {
                webGroup.style.display = 'none';
                document.getElementById('editWebsite').value = '';
            }
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
    const formData = new FormData();
    formData.append('id_jadwal', jadwalId);
    // Append semua field, tidak peduli visible atau tidak
    // Sehingga backend bisa update semua field sekaligus
    formData.append('dokumentasi', document.getElementById('editDokumentasi').value);
    formData.append('link_instagram', document.getElementById('editInstagram').value);
    formData.append('link_facebook', document.getElementById('editFacebook').value);
    formData.append('link_youtube', document.getElementById('editYoutube').value);
    formData.append('link_website', document.getElementById('editWebsite').value);
    
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

// Modal for event details (Bootstrap Modal version)
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
    
    // Show/hide edit button based on is_user_pic
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
    function renderLink(label, icon, url) {
        if (!url) return false; // Jika NULL, jangan tampilkan
        if (url === '-') {
            // Jika "-", tampilkan icon tapi tidak aktif
            links.push(
                `<span style="color: #adb5bd; cursor: not-allowed; font-size: 20px;" title="Tidak tersedia">
                  <i class="${icon}"></i>
                </span>`
            );
            return true;
        }
        // Jika ada isi, tampilkan icon aktif
        links.push(
            `<a href="${url}" target="_blank" style="color: #007bff; text-decoration: none; font-size: 20px; cursor: pointer;" onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#007bff'" title="Buka di tab baru">
              <i class="${icon}"></i>
            </a>`
        );
        return true;
    }
    renderLink('Instagram', 'ti-instagram', eventData.link_instagram);
    renderLink('Facebook', 'ti-facebook', eventData.link_facebook);
    renderLink('YouTube', 'ti-youtube', eventData.link_youtube);
    renderLink('Website', 'ti-world', eventData.link_website);
    
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
