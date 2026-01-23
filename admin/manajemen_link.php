<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// DATA LINK with related information
$qLink = mysqli_query($koneksi, "
    SELECT 
        l.id_link,
        l.nama_link,
        l.gambar,
        l.link
    FROM link l
    ORDER BY l.nama_link
");
$dataLinks = [];
while ($row = mysqli_fetch_assoc($qLink)) {
    $dataLinks[] = $row;
}
?>
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Manajemen Link</h5>
                        <p class="m-b-0">Untuk mengelola data link dari website sistem BPS lainnya.</p>
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
                            <a href="manajemen_link.php">Manajemen Link</a>
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
                    <ul class="nav nav-tabs tabs card-block" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#table" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#card" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                        </li>
                    </ul>
                    <div class="tab-content tabs card">
                        <div class="tab-pane active" id="table" role="tabpanel">
                            <div class="card-block">
                                <div class="row m-b-10">
                                    <div class="col-6">
                                        <div class="dropdown-info dropdown open">
                                            <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                            <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                <a class="dropdown-item waves-light waves-effect" href="#">Print</a>
                                                <a class="dropdown-item waves-light waves-effect" href="#">Excel</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="align-items-right" style="float: right;">
                                            <a href="tambah/tambah_link.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="dt-responsive table-responsive">
                                    <table id="order-table" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama Link</th>
                                                <th>Gambar</th>
                                                <th>URL</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php if (count($dataLinks) === 0): ?>
<tr>
  <td colspan="5" class="text-center">Tidak ada data link tersedia.</td>
</tr>
<?php else: ?>
<?php foreach ($dataLinks as $link) : ?>
<tr>
  <td><?= $link['id_link']; ?></td>
  <td><?= htmlspecialchars($link['nama_link']); ?></td>
  <td>
    <?php if ($link['gambar']) : ?>
      <a href="../uploads/<?= htmlspecialchars($link['gambar']); ?>" class="glightbox" data-gallery="gallery">
        <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; display: inline-block;">
          <img src="../uploads/<?= htmlspecialchars($link['gambar']); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block; cursor: pointer;">
        </div>
      </a>
    <?php else : ?>
      <span class="badge bg-secondary">-</span>
    <?php endif; ?>
  </td>
  <td><?= htmlspecialchars($link['link']); ?></td>
  <td>
    <a href="edit/edit_link.php?id=<?= $link['id_link']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
      <i class="ti-pencil text-dark"></i>
    </a>
    <button type="button" 
            class="btn waves-effect waves-light btn-danger btn-icon"
            onclick="deleteLink(<?= $link['id_link']; ?>, '<?= htmlspecialchars($link['nama_link']); ?>')"
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
                                                <th>Nama Link</th>
                                                <th>Gambar</th>
                                                <th>Link</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-5" id="card" role="tabpanel">
                            <div class="row m-b-10">
                                <div class="col-6">
                                    <div class="dropdown-info dropdown open">
                                        <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak2" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                        <div class="dropdown-menu" aria-labelledby="cetak2" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                            <a class="dropdown-item waves-light waves-effect" href="#">Print</a>
                                            <a class="dropdown-item waves-light waves-effect" href="#">Excel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="align-items-right" style="float: right;">
                                        <a href="tambah/tambah_link.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row users-card">
                                <?php if (count($dataLinks) === 0): ?>
                                    <div class="col-12 text-center">
                                        <p>Tidak ada data link tersedia.</p>
                                    </div>
                                <?php else: ?>
                                <?php foreach ($dataLinks as $link) : ?>
                                <div class="col-lg-4 col-xl-3 col-md-6">
                                    <div class="card rounded-card user-card">
                                        <div class="card-block">
                                            <div class="img-hover avatar-wrapper">
                                                <?php if ($link['gambar']) : ?>
                                                    <a href="../uploads/<?= htmlspecialchars($link['gambar']); ?>" class="glightbox" data-gallery="links">
                                                        <img src="../uploads/<?= htmlspecialchars($link['gambar']); ?>" class="avatar-img" alt="<?= htmlspecialchars($link['nama_link']); ?>" style="cursor: pointer;">
                                                    </a>
                                                <?php else : ?>
                                                    <img src="../images/no.jpg" class="avatar-img" alt="No Image">
                                                <?php endif; ?>
                                                <div class="img-overlay img-radius">
                                                    <span>
                                                        <a href="edit/edit_link.php?id=<?= $link['id_link']; ?>" class="btn btn-sm btn-primary"><i class="ti-pencil"></i></a>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteLink(<?= $link['id_link']; ?>, '<?= htmlspecialchars($link['nama_link']); ?>')"><i class="ti-trash"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="user-content">
                                                <h4><?= htmlspecialchars($link['nama_link']); ?></h4>
                                                <a href="<?= htmlspecialchars($link['link']); ?>" target="_blank" class="badge bg-primary"><?= htmlspecialchars($link['link']); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
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
<!-- Lightbox Library -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

<!-- Delete Modal with Artistic Design -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border-radius: 15px 15px 0 0;">
                <div style="display: flex; align-items: center; gap: 10px; width: 100%;">
                    <i class="ti-trash" style="font-size: 24px; color: white;"></i>
                    <h5 class="modal-title" id="deleteModalLabel" style="color: white; margin: 0; font-weight: 700;">Konfirmasi Hapus</h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <i class="ti-alert" style="font-size: 48px; color: #e74c3c;"></i>
                </div>
                <p style="font-size: 16px; color: #2c3e50; margin: 15px 0;">Apakah Anda yakin ingin menghapus link</p>
                <p style="font-size: 18px; color: #e74c3c; font-weight: 700; margin: 15px 0;"><strong id="deleteLinkName"></strong>?</p>
                <p style="color: #7f8c8d; font-size: 14px; margin: 15px 0;">
                    <i class="ti-alert-alt" style="margin-right: 8px;"></i>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <input type="hidden" id="deleteLinkId" value="">
            </div>
            <div class="modal-footer border-0" style="padding: 20px 30px; background: rgba(0, 0, 0, 0.02);">
                <button type="button" class="btn" data-dismiss="modal" style="background: #95a5a6; color: white; border-radius: 8px; padding: 8px 20px; font-weight: 500;">
                    <i class="ti-close" style="margin-right: 5px;"></i> Batal
                </button>
                <button type="button" class="btn" id="confirmDelete" style="background: #e74c3c; color: white; border-radius: 8px; padding: 8px 20px; font-weight: 500;">
                    <i class="ti-trash" style="margin-right: 5px;"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteLink(id, namaLink) {
        document.getElementById('deleteLinkId').value = id;
        document.getElementById('deleteLinkName').textContent = namaLink;
        $('#deleteModal').modal('show');
    }
    
    document.getElementById('confirmDelete').addEventListener('click', function() {
        const id = document.getElementById('deleteLinkId').value;
        window.location.href = 'hapus/hapus_link.php?id=' + id;
    });
    
    const lightbox = GLightbox({
        selector: '.glightbox'
    });
</script>
<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
?>
