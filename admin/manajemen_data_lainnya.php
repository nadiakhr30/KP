<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// DATA Halo PST
$qHaloPST = mysqli_query($koneksi, "SELECT * FROM halo_pst hp");
$dataHaloPSTs = [];
while ($row = mysqli_fetch_assoc($qHaloPST)) {
    $dataHaloPSTs[] = $row;
}
// DATA Jabatan
$qJabatan = mysqli_query($koneksi, "SELECT * FROM jabatan");
$dataJabatans = [];
while ($row = mysqli_fetch_assoc($qJabatan)) {
    $dataJabatans[] = $row;
}
// DATA Jenis
$qJenis = mysqli_query($koneksi, "SELECT * FROM jenis");
$dataJenises = [];
while ($row = mysqli_fetch_assoc($qJenis)) {
    $dataJenises[] = $row;
}
// DATA Sub Jenis
$qSubJenis = mysqli_query($koneksi, "SELECT s.*, j.nama_jenis FROM sub_jenis s LEFT JOIN jenis j ON s.id_jenis = j.id_jenis");
$dataSubJenises = [];
while ($row = mysqli_fetch_assoc($qSubJenis)) {
    $dataSubJenises[] = $row;
}
// DATA Jenis Aset
$qJenisAset = mysqli_query($koneksi, "SELECT * FROM jenis_aset");
$dataJenisAsets = [];
while ($row = mysqli_fetch_assoc($qJenisAset)) {
    $dataJenisAsets[] = $row;
}
// DATA Jenis PIC
$qJenisPIC = mysqli_query($koneksi, "SELECT * FROM jenis_pic");
$dataJenisPICs = [];
while ($row = mysqli_fetch_assoc($qJenisPIC)) {
    $dataJenisPICs[] = $row;
}
// DATA PPID
$qPPID = mysqli_query($koneksi, "SELECT * FROM ppid");
$dataPPIDs = [];
while ($row = mysqli_fetch_assoc($qPPID)) {
    $dataPPIDs[] = $row;
}
// DATA Role
$qRole = mysqli_query($koneksi, "SELECT * FROM role");
$dataRoles = [];
while ($row = mysqli_fetch_assoc($qRole)) {
    $dataRoles[] = $row;
}
// DATA Skill
$qSkill = mysqli_query($koneksi, "SELECT * FROM skill");
$dataSkills = [];
while ($row = mysqli_fetch_assoc($qSkill)) {
    $dataSkills[] = $row;
}
?>
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Manajemen Data Lainnya</h5>
                        <p class="m-b-0">Untuk mengelola data-data mengenai bagian serta jabatan dalam instansi.</p>
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
                            <a href="manajemen_data_lainnya.php">Manajemen Data Lainnya</a>
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
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <h5>Halo PST</h5>
                                <span>Data ini berisi kategori Halo PST untuk mengelompokkan pengguna/aktivitas dalam sistem kehumasan PST.</span>
                            </div>
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
                            <ul class="nav nav-tabs tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#cardhalopst" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tablehalopst" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                                </li>
                            </ul>
                            <div class="tab-content tabs">
                                <div class="tab-pane active" id="cardhalopst" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <a href="export/export_halo_pst.php?format=print" class="btn waves-effect waves-light btn-grd-info">Print</a>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_halo_pst.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row users-card p-3">
                                        <?php if (count($dataHaloPSTs) === 0): ?>
                                            <div class="col-12 text-center">
                                                <p>Tidak ada data Halo PST tersedia.</p>
                                            </div>
                                        <?php else: ?>
                                        <?php foreach ($dataHaloPSTs as $halo) : ?>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="card rounded-card user-card">
                                                <div class="card-block">
                                                    <div class="user-content">
                                                        <h4><?= htmlspecialchars($halo['nama_halo_pst']); ?></h4>
                                                        <a href="edit/edit_halo_pst.php?id=<?= $halo['id_halo_pst']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
                                                          <i class="ti-pencil text-dark"></i>
                                                        </a>
                                                        <a href="hapus/hapus_halo_pst.php?id=<?= $halo['id_halo_pst']; ?>" 
                                                           class="btn waves-effect waves-light btn-danger btn-icon"
                                                           onclick="return confirmDelete('hapus/hapus_halo_pst.php?id=<?= $halo['id_halo_pst']; ?>')"
                                                           title="Hapus">
                                                           <i class="ti-trash text-dark"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tablehalopst" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <div class="dropdown-info dropdown open">
                                                <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                                <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_halo_pst.php?format=print">Print</a>
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_halo_pst.php?format=excel">Excel</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_halo_pst.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="order-table" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nama Halo PST</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($dataHaloPSTs) === 0): ?>
                                                <tr>
                                                  <td colspan="3" class="text-center">Tidak ada data Halo PST tersedia.</td>
                                                </tr>
                                                <?php else: ?>
                                                <?php foreach ($dataHaloPSTs as $pst) : ?>
                                                <tr>
                                                  <td><?= $pst['id_halo_pst']; ?></td>
                                                  <td><?= htmlspecialchars($pst['nama_halo_pst']); ?></td>
                                                  <td>
                                                    <a href="edit/edit_halo_pst.php?id=<?= $pst['id_halo_pst']; ?>" class="btn waves-effect waves-light btn-warning btn-icon">
                                                      <i class="ti-pencil text-dark"></i>
                                                    </a>
                                                    <a href="hapus/hapus_halo_pst.php?id=<?= $pst['id_halo_pst']; ?>"
                                                       class="btn waves-effect waves-light btn-danger btn-icon"
                                                       onclick="return confirmDelete('hapus/hapus_halo_pst.php?id=<?= $pst['id_halo_pst']; ?>')">
                                                       <i class="ti-trash text-dark"></i>
                                                    </a>
                                                  </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nama Halo PST</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <h5>Jabatan</h5>
                                <span>Data jabatan menyimpan informasi mengenai posisi atau kedudukan pengguna dalam instansi.</span>
                            </div>
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
                            <ul class="nav nav-tabs tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#cardjabatan" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tablejabatan" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                                </li>
                            </ul>
                            <div class="tab-content tabs">
                                <div class="tab-pane active" id="cardjabatan" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <a href="export/export_jabatan.php?format=print" class="btn waves-effect waves-light btn-grd-info">Print</a>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_jabatan.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row users-card p-3">
                                        <?php if (count($dataJabatans) === 0): ?>
                                            <div class="col-12 text-center">
                                                <p>Tidak ada data Jabatan tersedia.</p>
                                            </div>
                                        <?php else: ?>
                                        <?php foreach ($dataJabatans as $jabatan) : ?>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="card rounded-card user-card">
                                                <div class="card-block">
                                                    <div class="user-content">
                                                        <h4><?= htmlspecialchars($jabatan['nama_jabatan']); ?></h4>
                                                        <a href="edit/edit_jabatan.php?id=<?= $jabatan['id_jabatan']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
                                                          <i class="ti-pencil text-dark"></i>
                                                        </a>
                                                        <a href="hapus/hapus_jabatan.php?id=<?= $jabatan['id_jabatan']; ?>" 
                                                           class="btn waves-effect waves-light btn-danger btn-icon"
                                                           onclick="return confirmDelete('hapus/hapus_jabatan.php?id=<?= $jabatan['id_jabatan']; ?>')"
                                                           title="Hapus">
                                                           <i class="ti-trash text-dark"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tablejabatan" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <div class="dropdown-info dropdown open">
                                                <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                                <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_jabatan.php?format=print">Print</a>
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_jabatan.php?format=excel">Excel</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_jabatan.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="order-table" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nama Jabatan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($dataJabatans) === 0): ?>
                                                <tr>
                                                  <td colspan="3" class="text-center">Tidak ada data Jabatan tersedia.</td>
                                                </tr>
                                                <?php else: ?>
                                                <?php foreach ($dataJabatans as $jabatan) : ?>
                                                <tr>
                                                  <td><?= $jabatan['id_jabatan']; ?></td>
                                                  <td><?= htmlspecialchars($jabatan['nama_jabatan']); ?></td>
                                                  <td>
                                                    <a href="edit/edit_jabatan.php?id=<?= $jabatan['id_jabatan']; ?>" class="btn waves-effect waves-light btn-warning btn-icon">
                                                      <i class="ti-pencil text-dark"></i>
                                                    </a>
                                                    <a href="hapus/hapus_jabatan.php?id=<?= $jabatan['id_jabatan']; ?>"
                                                       class="btn waves-effect waves-light btn-danger btn-icon"
                                                       onclick="return confirmDelete('hapus/hapus_jabatan.php?id=<?= $jabatan['id_jabatan']; ?>')">
                                                       <i class="ti-trash text-dark"></i>
                                                    </a>
                                                  </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nama Jabatan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <h5>Jenis</h5>
                                <span>Data jenis berisi kategori utama media yang digunakan dalam kegiatan kehumasan.</span>
                            </div>
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
                            <ul class="nav nav-tabs tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#cardjenis" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tablejenis" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                                </li>
                            </ul>
                            <div class="tab-content tabs">
                                <div class="tab-pane active" id="cardjenis" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <a href="export/export_jenis.php?format=print" class="btn waves-effect waves-light btn-grd-info">Print</a>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_jenis.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row users-card p-3">
                                        <?php if (count($dataJenises) === 0): ?>
                                            <div class="col-12 text-center">
                                                <p>Tidak ada data Jenis tersedia.</p>
                                            </div>
                                        <?php else: ?>
                                        <?php foreach ($dataJenises as $jenis) : ?>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="card rounded-card user-card">
                                                <div class="card-block">
                                                    <div class="user-content">
                                                        <h4><?= htmlspecialchars($jenis['nama_jenis']); ?></h4>
                                                        <a href="edit/edit_jenis.php?id=<?= $jenis['id_jenis']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
                                                          <i class="ti-pencil text-dark"></i>
                                                        </a>
                                                        <a href="hapus/hapus_jenis.php?id=<?= $jenis['id_jenis']; ?>" 
                                                           class="btn waves-effect waves-light btn-danger btn-icon"
                                                           onclick="return confirmDelete('hapus/hapus_jenis.php?id=<?= $jenis['id_jenis']; ?>')"
                                                           title="Hapus">
                                                           <i class="ti-trash text-dark"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tablejenis" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <div class="dropdown-info dropdown open">
                                                <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                                <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_jenis.php?format=print">Print</a>
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_jenis.php?format=excel">Excel</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_jenis.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="order-table" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nama Jenis</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($dataJenises) === 0): ?>
                                                <tr>
                                                  <td colspan="3" class="text-center">Tidak ada data Jenis tersedia.</td>
                                                </tr>
                                                <?php else: ?>
                                                <?php foreach ($dataJenises as $jenis) : ?>
                                                <tr>
                                                  <td><?= $jenis['id_jenis']; ?></td>
                                                  <td><?= htmlspecialchars($jenis['nama_jenis']); ?></td>
                                                  <td>
                                                    <a href="edit/edit_jenis.php?id=<?= $jenis['id_jenis']; ?>" class="btn waves-effect waves-light btn-warning btn-icon">
                                                      <i class="ti-pencil text-dark"></i>
                                                    </a>
                                                    <a href="hapus/hapus_jenis.php?id=<?= $jenis['id_jenis']; ?>"
                                                       class="btn waves-effect waves-light btn-danger btn-icon"
                                                       onclick="return confirmDelete('hapus/hapus_jenis.php?id=<?= $jenis['id_jenis']; ?>')">
                                                       <i class="ti-trash text-dark"></i>
                                                    </a>
                                                  </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nama Jenis</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <h5>Sub Jenis</h5>
                                <span>Sub jenis merupakan turunan dari jenis media, berisi detail platform atau bentuk spesifik dari media tersebut.</span>
                            </div>
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
                            <ul class="nav nav-tabs tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#cardsubjenis" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tablesubjenis" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                                </li>
                            </ul>
                            <div class="tab-content tabs">
                                <div class="tab-pane active" id="cardsubjenis" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <a href="export/export_sub_jenis.php?format=print" class="btn waves-effect waves-light btn-grd-info">Print</a>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_sub_jenis.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row users-card p-3">
                                        <?php if (count($dataSubJenises) === 0): ?>
                                            <div class="col-12 text-center">
                                                <p>Tidak ada data Sub Jenis tersedia.</p>
                                            </div>
                                        <?php else: ?>
                                        <?php foreach ($dataSubJenises as $subjenis) : ?>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="card rounded-card user-card">
                                                <div class="card-block">
                                                    <div class="user-content">
                                                        <h4><?= htmlspecialchars($subjenis['nama_sub_jenis']); ?></h4>
                                                        <span><?= htmlspecialchars($subjenis['nama_jenis']); ?></span><br>
                                                        <a href="edit/edit_sub_jenis.php?id=<?= $subjenis['id_sub_jenis']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
                                                          <i class="ti-pencil text-dark"></i>
                                                        </a>
                                                        <a href="hapus/hapus_sub_jenis.php?id=<?= $subjenis['id_sub_jenis']; ?>" 
                                                           class="btn waves-effect waves-light btn-danger btn-icon"
                                                           onclick="return confirmDelete('hapus/hapus_sub_jenis.php?id=<?= $subjenis['id_sub_jenis']; ?>')"
                                                           title="Hapus">
                                                           <i class="ti-trash text-dark"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tablesubjenis" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <div class="dropdown-info dropdown open">
                                                <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                                <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_sub_jenis.php?format=print">Print</a>
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_sub_jenis.php?format=excel">Excel</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_sub_jenis.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="order-table" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Jenis</th>
                                                    <th>Nama Sub Jenis</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($dataSubJenises) === 0): ?>
                                                <tr>
                                                  <td colspan="4" class="text-center">Tidak ada data Sub Jenis tersedia.</td>
                                                </tr>
                                                <?php else: ?>
                                                <?php foreach ($dataSubJenises as $subjenis) : ?>
                                                <tr>
                                                  <td><?= $subjenis['id_sub_jenis']; ?></td>
                                                  <td><?= htmlspecialchars($subjenis['nama_jenis']); ?></td>
                                                  <td><?= htmlspecialchars($subjenis['nama_sub_jenis']); ?></td>
                                                  <td>
                                                    <a href="edit/edit_sub_jenis.php?id=<?= $subjenis['id_sub_jenis']; ?>" class="btn waves-effect waves-light btn-warning btn-icon">
                                                      <i class="ti-pencil text-dark"></i>
                                                    </a>
                                                    <a href="hapus/hapus_sub_jenis.php?id=<?= $subjenis['id_sub_jenis']; ?>"
                                                       class="btn waves-effect waves-light btn-danger btn-icon"
                                                       onclick="return confirmDelete('hapus/hapus_sub_jenis.php?id=<?= $subjenis['id_sub_jenis']; ?>')">
                                                       <i class="ti-trash text-dark"></i>
                                                    </a>
                                                  </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Jenis</th>
                                                    <th>Nama Sub Jenis</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <h5>Jenis Aset</h5>
                                <span>Data jenis aset berisi informasi mengenai tipe aset kehumasan untuk membantu pengelolaan, pencarian, dan pelaporan aset secara sistematis.</span>
                            </div>
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
                            <ul class="nav nav-tabs tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#cardjenisaset" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tablejenisaset" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                                </li>
                            </ul>
                            <div class="tab-content tabs">
                                <div class="tab-pane active" id="cardjenisaset" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <a href="export/export_jenis_aset.php?format=print" class="btn waves-effect waves-light btn-grd-info">Print</a>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_jenis_aset.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row users-card p-3">
                                        <?php if (count($dataJenisAsets) === 0): ?>
                                            <div class="col-12 text-center">
                                                <p>Tidak ada data Jenis Aset tersedia.</p>
                                            </div>
                                        <?php else: ?>
                                        <?php foreach ($dataJenisAsets as $jenisaset) : ?>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="card rounded-card user-card">
                                                <div class="card-block">
                                                    <div class="user-content">
                                                        <h4><?= htmlspecialchars($jenisaset['nama_jenis_aset']); ?></h4>
                                                        <a href="edit/edit_jenis_aset.php?id=<?= $jenisaset['id_jenis_aset']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
                                                          <i class="ti-pencil text-dark"></i>
                                                        </a>
                                                        <a href="hapus/hapus_jenis_aset.php?id=<?= $jenisaset['id_jenis_aset']; ?>" 
                                                           class="btn waves-effect waves-light btn-danger btn-icon"
                                                           onclick="return confirmDelete('hapus/hapus_jenis_aset.php?id=<?= $jenisaset['id_jenis_aset']; ?>')"
                                                           title="Hapus">
                                                           <i class="ti-trash text-dark"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tablejenisaset" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <div class="dropdown-info dropdown open">
                                                <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                                <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_jenis_aset.php?format=print">Print</a>
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_jenis_aset.php?format=excel">Excel</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_jenis_aset.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="order-table" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Jenis Aset</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($dataJenisAsets) === 0): ?>
                                                <tr>
                                                  <td colspan="3" class="text-center">Tidak ada data Jenis Aset tersedia.</td>
                                                </tr>
                                                <?php else: ?>
                                                <?php foreach ($dataJenisAsets as $jenisaset) : ?>
                                                <tr>
                                                  <td><?= $jenisaset['id_jenis_aset']; ?></td>
                                                  <td><?= htmlspecialchars($jenisaset['nama_jenis_aset']); ?></td>
                                                  <td>
                                                    <a href="edit/edit_jenis_aset.php?id=<?= $jenisaset['id_jenis_aset']; ?>" class="btn waves-effect waves-light btn-warning btn-icon">
                                                      <i class="ti-pencil text-dark"></i>
                                                    </a>
                                                    <a href="hapus/hapus_jenis_aset.php?id=<?= $jenisaset['id_jenis_aset']; ?>"
                                                       class="btn waves-effect waves-light btn-danger btn-icon"
                                                       onclick="return confirmDelete('hapus/hapus_jenis_aset.php?id=<?= $jenisaset['id_jenis_aset']; ?>')">
                                                       <i class="ti-trash text-dark"></i>
                                                    </a>
                                                  </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Jenis Aset</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <h5>Jenis PIC</h5>
                                <span>Jenis PIC menyimpan kategori peran penanggung jawab (Person In Charge) dalam suatu kegiatan.</span>
                            </div>
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
                            <ul class="nav nav-tabs tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#cardjenispic" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tablejenispic" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                                </li>
                            </ul>
                            <div class="tab-content tabs">
                                <div class="tab-pane active" id="cardjenispic" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <a href="export/export_jenis_pic.php?format=print" class="btn waves-effect waves-light btn-grd-info">Print</a>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_jenis_pic.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row users-card p-3">
                                        <?php if (count($dataJenisPICs) === 0): ?>
                                            <div class="col-12 text-center">
                                                <p>Tidak ada data Jenis PIC tersedia.</p>
                                            </div>
                                        <?php else: ?>
                                        <?php foreach ($dataJenisPICs as $jenispic) : ?>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="card rounded-card user-card">
                                                <div class="card-block">
                                                    <div class="user-content">
                                                        <h4><?= htmlspecialchars($jenispic['nama_jenis_pic']); ?></h4>
                                                        <a href="edit/edit_jenis_pic.php?id=<?= $jenispic['id_jenis_pic']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
                                                          <i class="ti-pencil text-dark"></i>
                                                        </a>
                                                        <a href="hapus/hapus_jenis_pic.php?id=<?= $jenispic['id_jenis_pic']; ?>" 
                                                           class="btn waves-effect waves-light btn-danger btn-icon"
                                                           onclick="return confirmDelete('hapus/hapus_jenis_pic.php?id=<?= $jenispic['id_jenis_pic']; ?>')"
                                                           title="Hapus">
                                                           <i class="ti-trash text-dark"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tablejenispic" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <div class="dropdown-info dropdown open">
                                                <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                                <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_jenis_pic.php?format=print">Print</a>
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_jenis_pic.php?format=excel">Excel</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_jenis_pic.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="order-table" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Jenis PIC</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($dataJenisPICs) === 0): ?>
                                                <tr>
                                                  <td colspan="3" class="text-center">Tidak ada data Jenis PIC tersedia.</td>
                                                </tr>
                                                <?php else: ?>
                                                <?php foreach ($dataJenisPICs as $jenispic) : ?>
                                                <tr>
                                                  <td><?= $jenispic['id_jenis_pic']; ?></td>
                                                  <td><?= htmlspecialchars($jenispic['nama_jenis_pic']); ?></td>
                                                  <td>
                                                    <a href="edit/edit_jenis_pic.php?id=<?= $jenispic['id_jenis_pic']; ?>" class="btn waves-effect waves-light btn-warning btn-icon">
                                                      <i class="ti-pencil text-dark"></i>
                                                    </a>
                                                    <a href="hapus/hapus_jenis_pic.php?id=<?= $jenispic['id_jenis_pic']; ?>"
                                                       class="btn waves-effect waves-light btn-danger btn-icon"
                                                       onclick="return confirmDelete('hapus/hapus_jenis_pic.php?id=<?= $jenispic['id_jenis_pic']; ?>')">
                                                       <i class="ti-trash text-dark"></i>
                                                    </a>
                                                  </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Jenis Aset</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <h5>PPID</h5>
                                <span>Data PPID (Pejabat Pengelola Informasi dan Dokumentasi) berisi informasi unit atau peran PPID dalam instansi.</span>
                            </div>
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
                            <ul class="nav nav-tabs tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#cardppid" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tableppid" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                                </li>
                            </ul>
                            <div class="tab-content tabs">
                                <div class="tab-pane active" id="cardppid" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <a href="export/export_ppid.php?format=print" class="btn waves-effect waves-light btn-grd-info">Print</a>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_ppid.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row users-card p-3">
                                        <?php if (count($dataPPIDs) === 0): ?>
                                            <div class="col-12 text-center">
                                                <p>Tidak ada data PPID tersedia.</p>
                                            </div>
                                        <?php else: ?>
                                        <?php foreach ($dataPPIDs as $ppid) : ?>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="card rounded-card user-card">
                                                <div class="card-block">
                                                    <div class="user-content">
                                                        <h4><?= htmlspecialchars($ppid['nama_ppid']); ?></h4>
                                                        <a href="edit/edit_ppid.php?id=<?= $ppid['id_ppid']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
                                                          <i class="ti-pencil text-dark"></i>
                                                        </a>
                                                        <a href="hapus/hapus_ppid.php?id=<?= $ppid['id_ppid']; ?>" 
                                                           class="btn waves-effect waves-light btn-danger btn-icon"
                                                           onclick="return confirmDelete('hapus/hapus_ppid.php?id=<?= $ppid['id_ppid']; ?>')"
                                                           title="Hapus">
                                                           <i class="ti-trash text-dark"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tableppid" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <div class="dropdown-info dropdown open">
                                                <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                                <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_ppid.php?format=print">Print</a>
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_ppid.php?format=excel">Excel</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_ppid.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="order-table" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>PPID</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($dataPPIDs) === 0): ?>
                                                <tr>
                                                  <td colspan="3" class="text-center">Tidak ada data PPID tersedia.</td>
                                                </tr>
                                                <?php else: ?>
                                                <?php foreach ($dataPPIDs as $ppid) : ?>
                                                <tr>
                                                  <td><?= $ppid['id_ppid']; ?></td>
                                                  <td><?= htmlspecialchars($ppid['nama_ppid']); ?></td>
                                                  <td>
                                                    <a href="edit/edit_ppid.php?id=<?= $ppid['id_ppid']; ?>" class="btn waves-effect waves-light btn-warning btn-icon">
                                                      <i class="ti-pencil text-dark"></i>
                                                    </a>
                                                    <a href="hapus/hapus_ppid.php?id=<?= $ppid['id_ppid']; ?>"
                                                       class="btn waves-effect waves-light btn-danger btn-icon"
                                                       onclick="return confirmDelete('hapus/hapus_ppid.php?id=<?= $ppid['id_ppid']; ?>')">
                                                       <i class="ti-trash text-dark"></i>
                                                    </a>
                                                  </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>PPID</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <h5>Role</h5>
                                <span>Data role menyimpan hak akses pengguna dalam sistem.</span>
                            </div>
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
                            <ul class="nav nav-tabs tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#cardrole" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tablerole" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                                </li>
                            </ul>
                            <div class="tab-content tabs">
                                <div class="tab-pane active" id="cardrole" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <a href="export/export_role.php?format=print" class="btn waves-effect waves-light btn-grd-info">Print</a>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_role.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row users-card p-3">
                                        <?php if (count($dataRoles) === 0): ?>
                                            <div class="col-12 text-center">
                                                <p>Tidak ada data Role tersedia.</p>
                                            </div>
                                        <?php else: ?>
                                        <?php foreach ($dataRoles as $role) : ?>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="card rounded-card user-card">
                                                <div class="card-block">
                                                    <div class="user-content">
                                                        <h4><?= htmlspecialchars($role['nama_role']); ?></h4>
                                                        <a href="edit/edit_role.php?id=<?= $role['id_role']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
                                                          <i class="ti-pencil text-dark"></i>
                                                        </a>
                                                        <a href="hapus/hapus_role.php?id=<?= $role['id_role']; ?>" 
                                                           class="btn waves-effect waves-light btn-danger btn-icon"
                                                           onclick="return confirmDelete('hapus/hapus_role.php?id=<?= $role['id_role']; ?>')"
                                                           title="Hapus">
                                                           <i class="ti-trash text-dark"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tablerole" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <div class="dropdown-info dropdown open">
                                                <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                                <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_role.php?format=print">Print</a>
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_role.php?format=excel">Excel</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_role.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="order-table" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Role</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($dataRoles) === 0): ?>
                                                <tr>
                                                  <td colspan="3" class="text-center">Tidak ada data Role tersedia.</td>
                                                </tr>
                                                <?php else: ?>
                                                <?php foreach ($dataRoles as $role) : ?>
                                                <tr>
                                                  <td><?= $role['id_role']; ?></td>
                                                  <td><?= htmlspecialchars($role['nama_role']); ?></td>
                                                  <td>
                                                    <a href="edit/edit_role.php?id=<?= $role['id_role']; ?>" class="btn waves-effect waves-light btn-warning btn-icon">
                                                      <i class="ti-pencil text-dark"></i>
                                                    </a>
                                                    <a href="hapus/hapus_role.php?id=<?= $role['id_role']; ?>"
                                                       class="btn waves-effect waves-light btn-danger btn-icon"
                                                       onclick="return confirmDelete('hapus/hapus_role.php?id=<?= $role['id_role']; ?>')">
                                                       <i class="ti-trash text-dark"></i>
                                                    </a>
                                                  </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Role</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <h5>Skill</h5>
                                <span>Data skill berisi kemampuan atau keahlian pengguna.</span>
                            </div>
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
                            <ul class="nav nav-tabs tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#cardskill" role="tab"><i class="ti-layout-grid2"></i> Card</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tableskill" role="tab"><i class="ti-layout-menu-v"></i> Tabel</a>
                                </li>
                            </ul>
                            <div class="tab-content tabs">
                                <div class="tab-pane active" id="cardskill" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <a href="export/export_skill.php?format=print" class="btn waves-effect waves-light btn-grd-info">Print</a>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_skill.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row users-card p-3">
                                        <?php if (count($dataSkills) === 0): ?>
                                            <div class="col-12 text-center">
                                                <p>Tidak ada data Skill tersedia.</p>
                                            </div>
                                        <?php else: ?>
                                        <?php foreach ($dataSkills as $skill) : ?>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="card rounded-card user-card">
                                                <div class="card-block">
                                                    <div class="user-content">
                                                        <h4><?= htmlspecialchars($skill['nama_skill']); ?></h4>
                                                        <a href="edit/edit_skill.php?id=<?= $skill['id_skill']; ?>" class="btn waves-effect waves-light btn-warning btn-icon" title="Edit">
                                                          <i class="ti-pencil text-dark"></i>
                                                        </a>
                                                        <a href="hapus/hapus_skill.php?id=<?= $skill['id_skill']; ?>" 
                                                           class="btn waves-effect waves-light btn-danger btn-icon"
                                                           onclick="return confirmDelete('hapus/hapus_skill.php?id=<?= $skill['id_skill']; ?>')"
                                                           title="Hapus">
                                                           <i class="ti-trash text-dark"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tableskill" role="tabpanel">
                                    <div class="row m-b-10 m-t-10">
                                        <div class="col-6">
                                            <div class="dropdown-info dropdown open">
                                                <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                                <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_skill.php?format=print">Print</a>
                                                    <a class="dropdown-item waves-light waves-effect" href="export/export_skill.php?format=excel">Excel</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="align-items-right" style="float: right;">
                                                <a href="tambah/tambah_skill.php" class="btn waves-effect waves-light btn-grd-success"><i class="ti-plus"></i> Tambah</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="order-table" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Skill</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($dataSkills) === 0): ?>
                                                    <tr>
                                                      <td colspan="3" class="text-center">Tidak ada data Skill tersedia.</td>
                                                    </tr>
                                                <?php else: ?>
                                                <?php foreach ($dataSkills as $skill) : ?>
                                                <tr>
                                                  <td><?= $skill['id_skill']; ?></td>
                                                  <td><?= htmlspecialchars($skill['nama_skill']); ?></td>
                                                  <td>
                                                    <a href="edit/edit_skill.php?id=<?= $skill['id_skill']; ?>" class="btn waves-effect waves-light btn-warning btn-icon">
                                                      <i class="ti-pencil text-dark"></i>
                                                    </a>
                                                    <a href="hapus/hapus_skill.php?id=<?= $skill['id_skill']; ?>"
                                                       class="btn waves-effect waves-light btn-danger btn-icon"
                                                       onclick="return confirmDelete('hapus/hapus_skill.php?id=<?= $skill['id_skill']; ?>')">
                                                       <i class="ti-trash text-dark"></i>
                                                    </a>
                                                  </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Skill</th>
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
</div>

<!-- Delete Modal with Artistic Design -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px; display: flex; align-items: center; justify-content: center; min-height: 100vh;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; background: #fcf2f2; border-left: 5px solid #e74c3c;">
            <div class="modal-body" style="padding: 40px 30px; text-align: center;">
                <div style="margin-bottom: 20px;">
                    <i class="ti-alert" style="font-size: 56px; color: #e74c3c;"></i>
                </div>
                <h5 style="color: #2c3e50; font-weight: 700; font-size: 18px; margin-bottom: 10px;">Konfirmasi Hapus</h5>
                <p style="font-size: 14px; color: #7f8c8d; margin-bottom: 20px;">Apakah Anda yakin ingin menghapus data <strong id="deleteDataName"></strong>?</p>
                <p style="color: #e74c3c; font-size: 12px; margin-top: 20px; margin-bottom: 30px;">
                    <i class="ti-alert-alt" style="margin-right: 6px;"></i>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <input type="hidden" id="deleteDataId" value="">
                <input type="hidden" id="deleteDataType" value="">
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

<?php
$content = ob_get_clean();
ob_start();
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Handle delete notifications from session
window.addEventListener('load', function() {
    <?php
    if (isset($_SESSION['delete_status'])) {
        $status = $_SESSION['delete_status'];
        $message = $_SESSION['delete_message'];
        $icon = ($status === 'success') ? 'success' : 'error';
        $title = ($status === 'success') ? 'Berhasil!' : 'Gagal!';
        ?>
        Swal.fire({
            icon: '<?= $icon ?>',
            title: '<?= $title ?>',
            text: '<?= addslashes($message) ?>',
            confirmButtonColor: '<?= ($status === 'success') ? '#3085d6' : '#d33' ?>',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                container: 'swal-container-custom',
                popup: 'swal-popup-custom',
                confirmButton: 'swal-confirm-button-custom'
            }
        }).then((result) => {
            if (result.isConfirmed && '<?= $status ?>' === 'success') {
                location.reload();
            }
        });
        <?php
        unset($_SESSION['delete_status']);
        unset($_SESSION['delete_message']);
    }
    ?>
});

function confirmDelete(url) {
    // Extract URL parameter to store
    document.getElementById('confirmDelete').setAttribute('data-url', url);
    $('#deleteModal').modal('show');
    return false; // Prevent default link behavior
}

document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');

            // Close the modal first
            $('#deleteModal').modal('hide');
            
            // Perform deletion
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Show alert based on response
                    Swal.fire({
                        icon: data.status === 'success' ? 'success' : 'error',
                        title: data.status === 'success' ? 'Berhasil!' : 'Gagal!',
                        text: data.message,
                        confirmButtonColor: data.status === 'success' ? '#3085d6' : '#d33',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        customClass: {
                            container: 'swal-container-custom',
                            popup: 'swal-popup-custom',
                            confirmButton: 'swal-confirm-button-custom'
                        }
                    }).then((result) => {
                        // Reload page only on success
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
                        confirmButtonText: 'OK',
                        customClass: {
                            container: 'swal-container-custom',
                            popup: 'swal-popup-custom',
                            confirmButton: 'swal-confirm-button-custom'
                        }
                    });
                });
        });
    }
});
</script>
<style>
.swal-popup-custom {
    border-radius: 15px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
}

.swal-confirm-button-custom {
    border-radius: 8px !important;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
}

.swal-confirm-button-custom:focus,
.swal-confirm-button-custom:active,
.swal-confirm-button-custom:focus-visible {
    outline: none !important;
    box-shadow: none !important;
}
</style>
<?php
$script = ob_get_clean();
include 'layout.php';
renderLayout($content, $script);
?>
