<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['user']) && $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}
// DATA USER
$qUser = mysqli_query($koneksi, "SELECT * FROM user");
$dataUsers = [];
while ($row = mysqli_fetch_assoc($qUser)) {
    $dataUsers[] = $row;
}

function badge($text, $color) {
  return "<span class='badge bg-$color'>$text</span>";
}
?>
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Manajemen User</h5>
                        <p class="m-b-0">Untuk mengelola data user sistem kehumasan.</p>
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
                          <a href="manajemen_user.php">Manajemen User</a>
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
                        <li class="nav-item">
                            <a class="nav-link text-success" data-toggle="tab" href="#tambah" role="tab"><i class="ti-plus"></i> Tambah User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-success" data-toggle="tab" href="#import" role="tab"><i class="ti-import"></i> Import Data</a>
                        </li>
                    </ul>
                    <div class="tab-content tabs card">
                        <div class="tab-pane active" id="table" role="tabpanel">
                            <div class="card-block">
                                <div class="row m-b-5">
                                    <div class="col-6">
                                        <div class="dropdown-info dropdown open">
                                            <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                            <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                <a class="dropdown-item waves-light waves-effect" href="#">Print</a>
                                                <a class="dropdown-item waves-light waves-effect" href="#">Excel</a>
                                                <a class="dropdown-item waves-light waves-effect" href="#">JSON</a>
                                                <a class="dropdown-item waves-light waves-effect" href="#">CSV</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 ">
                                        <div class="dropdown-success dropdown open align-items-right" style="float: right;">
                                            <button class="btn btn-success dropdown-toggle waves-effect waves-light" type="button" id="tambah" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Tambah</button>
                                            <div class="dropdown-menu" aria-labelledby="tambah" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                <a class="dropdown-item waves-light waves-effect" href="#">Input Data</a>
                                                <a class="dropdown-item waves-light waves-effect" href="#">Import Data</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dt-responsive table-responsive">
                                    <table id="order-table" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2">Nama Lengkap</th>
                                                <th rowspan="2">Role</th>
                                                <th rowspan="2">Email</th>
                                                <th rowspan="2">Foto Profil</th>
                                                <th rowspan="2">Status</th>
                                                <th rowspan="2">NIP</th>
                                                <th rowspan="2">Role Humas</th>
                                                <th rowspan="2">Jabatan</th>
                                                <th rowspan="2">Nomor Telepon</th>
                                                <th colspan="11">Skill</th>
                                                <th rowspan="2">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th>Data Contributor</th>
                                                <th>Content Creator</th>
                                                <th>Editor Foto & Layout</th>
                                                <th>Editor Video</th>
                                                <th>Photo & Videographer</th>
                                                <th>Talent</th>
                                                <th>Project Manager</th>
                                                <th>Copywriting</th>
                                                <th>Protokol</th>
                                                <th>MC</th>
                                                <th>Operator</th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($dataUsers as $user) : ?>
<tr>
  <td><?= $user['id_user']; ?></td>
  <td><?= htmlspecialchars($user['nama']); ?></td>
  <td><?= $user['role'] == 1 ? badge('Admin', 'primary') : badge('Pegawai', 'secondary'); ?></td>
  <td><?= htmlspecialchars($user['email']); ?></td>
  <td>
    <?php if ($user['foto_profil']) : ?>
      <img src="../uploads/<?= $user['foto_profil']; ?>" width="40">
    <?php else : ?>
      -
    <?php endif; ?>
  </td>
<td><?= $user['status'] == 1 ? badge('Aktif', 'success') : badge('Tidak Aktif', 'danger'); ?></td>
</td>
  <td><?= $user['nip'] ?: '-'; ?></td>
  <td><?= $user['role_humas'] ?: '-'; ?></td>
  <td><?= $user['jabatan'] ?: '-'; ?></td>
  <td>0<?= $user['nomor_telepon'] ?: '-'; ?></td>
<td class="text-center"><?= $user['skill_data_contributor'] == 1 ? '<i class="ti-check text-success"></i>' : '<i class="ti-close text-danger"></i>'; ?></td>
<td class="text-center"><?= $user['skill_content_creator'] == 1 ? '<i class="ti-check text-success"></i>' : '<i class="ti-close text-danger"></i>'; ?></td>
<td class="text-center"><?= $user['skill_editor_photo_layout'] == 1 ? '<i class="ti-check text-success"></i>' : '<i class="ti-close text-danger"></i>'; ?></td>
<td class="text-center"><?= $user['skill_editor_video'] == 1 ? '<i class="ti-check text-success"></i>' : '<i class="ti-close text-danger"></i>'; ?></td>
<td class="text-center"><?= $user['skill_photo_videographer'] == 1 ? '<i class="ti-check text-success"></i>' : '<i class="ti-close text-danger"></i>'; ?></td>
<td class="text-center"><?= $user['skill_talent'] == 1 ? '<i class="ti-check text-success"></i>' : '<i class="ti-close text-danger"></i>'; ?></td>
<td class="text-center"><?= $user['skill_project_manager'] == 1 ? '<i class="ti-check text-success"></i>' : '<i class="ti-close text-danger"></i>'; ?></td>
<td class="text-center"><?= $user['skill_copywriting'] == 1 ? '<i class="ti-check text-success"></i>' : '<i class="ti-close text-danger"></i>'; ?></td>
<td class="text-center"><?= $user['skill_protokol'] == 1 ? '<i class="ti-check text-success"></i>' : '<i class="ti-close text-danger"></i>'; ?></td>
<td class="text-center"><?= $user['skill_mc'] == 1 ? '<i class="ti-check text-success"></i>' : '<i class="ti-close text-danger"></i>'; ?></td>
<td class="text-center"><?= $user['skill_operator'] == 1 ? '<i class="ti-check text-success"></i>' : '<i class="ti-close text-danger"></i>'; ?></td>
  <td>
    <a href="edit_user.php?id=<?= $user['id_user']; ?>" class="btn btn-sm btn-warning"><i class="ti-pencil text-dark"></i></a>
    <a href="hapus_user.php?id=<?= $user['id_user']; ?>" 
       class="btn btn-sm btn-danger"
       onclick="return confirm('Yakin hapus user ini?')">
       <i class="ti-trash text-dark"></i>
    </a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
                                        <tfoot>
                                            <tr>
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2">Nama Lengkap</th>
                                                <th rowspan="2">Role</th>
                                                <th rowspan="2">Email</th>
                                                <th rowspan="2">Foto Profil</th>
                                                <th rowspan="2">Status</th>
                                                <th rowspan="2">NIP</th>
                                                <th rowspan="2">Role Humas</th>
                                                <th rowspan="2">Jabatan</th>
                                                <th rowspan="2">Nomor Telepon</th>
                                                <th colspan="11">Skill</th>
                                                <th rowspan="2">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th>Data Contributor</th>
                                                <th>Content Creator</th>
                                                <th>Editor Foto & Layout</th>
                                                <th>Editor Video</th>
                                                <th>Photo & Videographer</th>
                                                <th>Talent</th>
                                                <th>Project Manager</th>
                                                <th>Copywriting</th>
                                                <th>Protokol</th>
                                                <th>MC</th>
                                                <th>Operator</th>
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
                                        <button class="btn btn-info dropdown-toggle waves-effect waves-light" type="button" id="cetak" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Cetak</button>
                                        <div class="dropdown-menu" aria-labelledby="cetak" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                            <a class="dropdown-item waves-light waves-effect" href="#">Print</a>
                                            <a class="dropdown-item waves-light waves-effect" href="#">Excel</a>
                                            <a class="dropdown-item waves-light waves-effect" href="#">JSON</a>
                                            <a class="dropdown-item waves-light waves-effect" href="#">CSV</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 ">
                                    <div class="dropdown-success dropdown open align-items-right" style="float: right;">
                                        <button class="btn btn-success dropdown-toggle waves-effect waves-light" type="button" id="tambah" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Tambah</button>
                                        <div class="dropdown-menu" aria-labelledby="tambah" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                            <a class="dropdown-item waves-light waves-effect" href="#">Input Data</a>
                                            <a class="dropdown-item waves-light waves-effect" href="#">Import Data</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row users-card">
                                <?php foreach ($dataUsers as $pengguna) : ?>
                                <div class="col-lg-4 col-xl-3 col-md-6">
                                    <div class="card rounded-card user-card">
                                        <div class="card-block">
                                            <div class="img-hover avatar-wrapper">
                                                <?php if ($pengguna['foto_profil']) : ?>
                                                  <img src="../uploads/<?= $pengguna['foto_profil']; ?>" class="avatar-img" alt="<?= $pengguna['foto_profil']; ?>">
                                                <?php else : ?>
                                                  <img src="../images/noimages.jpg" class="avatar-img" alt="<?= $pengguna['foto_profil']; ?>">
                                                <?php endif; ?>
                                                <div class="img-overlay img-radius">
                                                    <span>
                                                        <a href="#" class="btn btn-sm btn-primary" data-popup="lightbox"><i class="ti-pencil"></i></a>
                                                        <a href="#" class="btn btn-sm btn-primary"><i class="ti-trash"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="user-content">
                                                <h4><?= $pengguna['nama']; ?></h4>
                                                <span style="font-size: 12px; color: #d35858;"><?= $pengguna['nip']; ?></span>
                                                <h5 style="padding: 5px 0px"><?= $pengguna['email']; ?></h5>
                                                <p><?= $pengguna['jabatan']; ?></p>
                                                <button type="button" class="btn btn-primary waves-effect waves-light">Detail</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="tambah" role="tabpanel">
                            <div class="card-block">
                                <form action="" class="form-material">
                                    <div class="form-group form-default">
                                        <input type="number" name="footer-email" class="form-control" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">NIP</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" name="footer-email" class="form-control" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nama</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="password" name="footer-email" class="form-control" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Password</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <select name="select" class="form-control" required>
                                            <option value="">Pilih Role User</option>
                                            <option value="1">Admin</option>
                                            <option value="2">Pegawai</option>
                                        </select>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" name="footer-email" class="form-control" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Email (contoh.bps3526@gmail.com)</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <select name="select" class="form-control" required>
                                            <option value="">Status Kepegawaian</option>
                                            <option value="0">Tidak Aktif</option>
                                            <option value="1">Aktif</option>
                                        </select>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" name="footer-email" class="form-control" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Jabatan</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="number" name="footer-email" class="form-control" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nomor Telepon</label>
                                    </div>
                                    <button class="btn waves-effect waves-light btn-primary btn-outline-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane" id="import" role="tabpanel">
                            <div class="card-block">

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
