<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// Get jenis_aset from URL parameter, default to first one
$jenisAsetId = isset($_GET['jenis']) ? (int)$_GET['jenis'] : 0;

// Fetch all jenis_aset
$qJenis = mysqli_query($koneksi, "SELECT * FROM jenis_aset ORDER BY nama_jenis_aset");
$allJenis = [];
while ($row = mysqli_fetch_assoc($qJenis)) {
    $allJenis[] = $row;
}

// If no jenis_aset selected, use the first one
if ($jenisAsetId === 0 && count($allJenis) > 0) {
    $jenisAsetId = $allJenis[0]['id_jenis_aset'];
}

// Get current jenis_aset info
$currentJenis = null;
if ($jenisAsetId > 0) {
    $qCurrentJenis = mysqli_query($koneksi, "SELECT * FROM jenis_aset WHERE id_jenis_aset = $jenisAsetId");
    if (mysqli_num_rows($qCurrentJenis) > 0) {
        $currentJenis = mysqli_fetch_assoc($qCurrentJenis);
    }
}

// Fetch aset based on jenis_aset
$dataAset = [];
if ($jenisAsetId > 0) {
    $qAset = mysqli_query($koneksi, "
        SELECT 
            a.id_aset,
            a.nama,
            a.link,
            a.keterangan,
            ja.nama_jenis_aset
        FROM aset a
        INNER JOIN jenis_aset ja ON a.id_jenis_aset = ja.id_jenis_aset
        WHERE a.id_jenis_aset = $jenisAsetId
        ORDER BY a.nama
    ");
    while ($row = mysqli_fetch_assoc($qAset)) {
        $dataAset[] = $row;
    }
}
?>
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Manajemen Aset <?= $currentJenis ? htmlspecialchars($currentJenis['nama_jenis_aset']) : ''; ?></h5>
                        <p class="m-b-0">Untuk mengelola aset <?= $currentJenis ? htmlspecialchars($currentJenis['nama_jenis_aset']) : ''; ?> humas.</p>
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
                            <a href="">Aset</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="aset.php?jenis=<?= $jenisAsetId; ?>">Aset <?= $currentJenis ? htmlspecialchars($currentJenis['nama_jenis_aset']) : ''; ?></a>
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
                    <!-- Tabs -->
                    <ul class="nav nav-tabs tabs card-block" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#card" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#table" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                        </li>
                    </ul>
                    <div class="tab-content tabs card">
                        <!-- Card View -->
                        <div class="tab-pane p-5 active" id="card" role="tabpanel">
                            <div class="row m-b-10">
                                <div class="col-6">
                                    <div class="dropdown-info dropdown open">
                                        <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak2" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                        <div class="dropdown-menu" aria-labelledby="cetak2" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                            <a class="dropdown-item waves-light waves-effect" href="export/export_aset.php?jenis=<?= $jenisAsetId; ?>&format=print">Print</a>
                                            <a class="dropdown-item waves-light waves-effect" href="export/export_aset.php?jenis=<?= $jenisAsetId; ?>&format=excel">Excel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="align-items-right" style="float: right;">
                                        <a href="tambah/tambah_aset.php?jenis=<?= $jenisAsetId; ?>" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row users-card">
                                <?php if (count($dataAset) === 0): ?>
                                    <div class="col-12 text-center">
                                        <p><?= $currentJenis ? 'Tidak ada aset untuk ' . htmlspecialchars($currentJenis['nama_jenis_aset']) : 'Pilih jenis aset terlebih dahulu'; ?></p>
                                    </div>
                                <?php else: ?>
                                <?php foreach ($dataAset as $aset) : ?>
                                <div class="col-lg-4 col-xl-3 col-md-6">
                                    <div class="card rounded-card user-card">
                                        <div class="card-block">
                                            <div class="user-content">
                                                <h4><?= htmlspecialchars($aset['nama']); ?></h4>
                                                <p style="font-size: 12px; margin-bottom: 10px; min-height: 40px;">
                                                    <?= htmlspecialchars(substr($aset['keterangan'], 0, 100)); ?><?= strlen($aset['keterangan']) > 100 ? '...' : ''; ?>
                                                </p>
                                                <?php if ($aset['link']): ?>
                                                <a href="<?= htmlspecialchars($aset['link']); ?>" target="_blank" class="badge bg-primary link-badge" title="<?= htmlspecialchars($aset['link']); ?>">
                                                    <?= htmlspecialchars($aset['link']); ?>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                            <div style="margin-top: 15px; display: flex; gap: 8px;">
                                                <a href="edit/edit_aset.php?id=<?= $aset['id_aset']; ?>" class="btn btn-icon btn-primary waves-effect waves-light flex-fill"><i class="ti-pencil"></i></a>
                                                <button type="button" class="btn btn-icon btn-danger waves-effect waves-light flex-fill" onclick="deleteAset(<?= $aset['id_aset']; ?>, '<?= htmlspecialchars($aset['nama']); ?>')"><i class="ti-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Table View -->
                        <div class="tab-pane p-4" id="table" role="tabpanel">
                            <div class="card-block">
                                <div class="row m-b-10">
                                    <div class="col-6">
                                        <div class="dropdown-info dropdown open">
                                            <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                            <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                <a class="dropdown-item waves-light waves-effect" href="export/export_aset.php?jenis=<?= $jenisAsetId; ?>&format=print">Print</a>
                                                <a class="dropdown-item waves-light waves-effect" href="export/export_aset.php?jenis=<?= $jenisAsetId; ?>&format=excel">Excel</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="align-items-right" style="float: right;">
                                            <a href="tambah/tambah_aset.php?jenis=<?= $jenisAsetId; ?>" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="dt-responsive table-responsive">
                                    <table id="order-table" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama Aset</th>
                                                <th>Keterangan</th>
                                                <th>Link</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($dataAset) === 0): ?>
                                            <tr>
                                              <td colspan="6" class="text-center"><?= $currentJenis ? 'Tidak ada aset untuk ' . htmlspecialchars($currentJenis['nama_jenis_aset']) : 'Pilih jenis aset terlebih dahulu'; ?></td>
                                            </tr>
                                            <?php else: ?>
                                            <?php foreach ($dataAset as $aset) : ?>
                                            <tr>
                                              <td><?= $aset['id_aset']; ?></td>
                                              <td><?= htmlspecialchars($aset['nama']); ?></td>
                                              <td><?= htmlspecialchars(substr($aset['keterangan'], 0, 100)); ?><?= strlen($aset['keterangan']) > 100 ? '...' : ''; ?></td>
                                              <td>
                                                <?php if ($aset['link']): ?>
                                                  <a href="<?= htmlspecialchars($aset['link']); ?>" target="_blank"><?= htmlspecialchars(substr($aset['link'], 0, 30)); ?></a>
                                                <?php else: ?>
                                                  <span class="badge bg-secondary">-</span>
                                                <?php endif; ?>
                                              </td>
                                              <td>
                                                <a href="edit/edit_aset.php?id=<?= $aset['id_aset']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
                                                  <i class="ti-pencil text-dark"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn waves-effect waves-light btn-danger btn-icon"
                                                        onclick="deleteAset(<?= $aset['id_aset']; ?>, '<?= htmlspecialchars($aset['nama']); ?>')"
                                                        title="Hapus">
                                                   <i class="ti-trash text-dark"></i>
                                                </button>
                                              </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama Aset</th>
                                                <th>Keterangan</th>
                                                <th>Link</th>
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
<?php
$content = ob_get_clean();
ob_start();
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; background: #fcf2f2; border-left: 5px solid #e74c3c;">
            <div class="modal-body" style="padding: 40px 30px; text-align: center;">
                <div style="margin-bottom: 20px;">
                    <i class="ti-alert" style="font-size: 56px; color: #e74c3c;"></i>
                </div>
                <h5 style="color: #2c3e50; font-weight: 700; font-size: 18px; margin-bottom: 10px;">Konfirmasi Hapus</h5>
                <p style="font-size: 14px; color: #7f8c8d; margin-bottom: 20px;">Apakah Anda yakin ingin menghapus aset <strong id="deleteAsetName"></strong>?</p>
                <p style="color: #e74c3c; font-size: 12px; margin-top: 20px; margin-bottom: 30px;">
                    <i class="ti-alert-alt" style="margin-right: 6px;"></i>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <input type="hidden" id="deleteAsetId" value="">
                <div style="display: flex; justify-content: center; gap: 15px;">
                    <button type="button" class="btn btn-secondary btn-icon waves-effect waves-light" data-dismiss="modal" title="Batal">
                        <i class="ti-close"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-icon waves-effect waves-light" id="confirmDelete" title="Hapus">
                        <i class="ti-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteAset(id, namaAset) {
    document.getElementById('deleteAsetId').value = id;
    document.getElementById('deleteAsetName').textContent = namaAset;
    $('#deleteModal').modal('show');
}

document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            const id = document.getElementById('deleteAsetId').value;

            // Close the modal first
            $('#deleteModal').modal('hide');
            
            // Perform deletion
            fetch('hapus/hapus_aset.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: data.status === 'success' ? 'success' : 'error',
                        title: data.status === 'success' ? 'Berhasil!' : 'Gagal!',
                        text: data.message,
                        confirmButtonColor: data.status === 'success' ? '#3085d6' : '#d33',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed && data.status === 'success') {
                            location.reload();
                        }
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus data',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
        });
    }
});
</script>
<style>
.link-badge {
    display: block;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
?>
