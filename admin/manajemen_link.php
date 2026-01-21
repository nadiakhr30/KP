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
<?php foreach ($dataLinks as $link) : ?>
<tr>
  <td><?= $link['id_link']; ?></td>
  <td><?= htmlspecialchars($link['nama_link']); ?></td>
  <td>
    <?php if ($link['gambar']) : ?>
      <img src="../uploads/<?= htmlspecialchars($link['gambar']); ?>" width="40" style="border-radius: 50%;">
    <?php else : ?>
      <span class="badge bg-secondary">-</span>
    <?php endif; ?>
  </td>
  <td><?= htmlspecialchars($link['link']); ?></td>
  <td>
    <a href="edit_link.php?id=<?= $link['id_link']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
      <i class="ti-pencil text-dark"></i>
    </a>
    <a href="hapus_link.php?id=<?= $link['id_link']; ?>"
       class="btn waves-effect waves-light btn-danger btn-icon"
       onclick="return confirm('Yakin hapus link ini?')"
       title="Hapus">
       <i class="ti-trash text-dark"></i>
    </a>
  </td>
</tr>
<?php endforeach; ?>
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
                                <?php foreach ($dataLinks as $link) : ?>
                                <div class="col-lg-4 col-xl-3 col-md-6">
                                    <div class="card rounded-card user-card">
                                        <div class="card-block">
                                            <div class="img-hover avatar-wrapper">
                                                <?php if ($link['gambar']) : ?>
                                                    <img src="../uploads/<?= htmlspecialchars($link['gambar']); ?>" class="avatar-img" alt="<?= htmlspecialchars($link['judul']); ?>">
                                                <?php else : ?>
                                                    <img src="../images/noimages.jpg" class="avatar-img" alt="No Image">
                                                <?php endif; ?>
                                                <div class="img-overlay img-radius">
                                                    <span>
                                                        <a href="edit_link.php?id=<?= $link['id_link']; ?>" class="btn btn-sm btn-primary"><i class="ti-pencil"></i></a>
                                                        <a href="hapus_link.php?id=<?= $link['id_link']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus link ini?')"><i class="ti-trash"></i></a>
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
<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
?>
