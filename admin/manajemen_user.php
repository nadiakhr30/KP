<?php
ob_start();
session_start();
include_once("../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

// DATA USER with related information
$qUser = mysqli_query($koneksi, "
    SELECT 
        u.nip,
        u.nama,
        u.email,
        u.foto_profil,
        u.status,
        u.nomor_telepon,
        u.id_jabatan,
        u.id_role,
        j.nama_jabatan,
        r.nama_role
    FROM user u
    LEFT JOIN jabatan j ON u.id_jabatan = j.id_jabatan
    LEFT JOIN role r ON u.id_role = r.id_role
    ORDER BY u.nama
");
$dataUsers = [];
while ($row = mysqli_fetch_assoc($qUser)) {
    $dataUsers[] = $row;
}

// Get skills for each user
function getUserSkills($koneksi, $nip) {
    $qSkill = mysqli_query($koneksi, "
        SELECT s.nama_skill
        FROM user_skill us
        JOIN skill s ON us.id_skill = s.id_skill
        WHERE us.nip = " . (int)$nip . "
        ORDER BY s.nama_skill
    ");
    $skills = [];
    while ($row = mysqli_fetch_assoc($qSkill)) {
        $skills[] = $row['nama_skill'];
    }
    return $skills;
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
                                    <div class="col-6">
                                        <div class="dropdown-success dropdown open align-items-right" style="float: right;">
                                            <button class="btn btn-success dropdown-toggle waves-effect waves-light" type="button" id="tambah" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Tambah</button>
                                            <div class="dropdown-menu" aria-labelledby="tambah" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                <a class="dropdown-item waves-light waves-effect" href="tambah/tambah_user_input.php">Input Data</a>
                                                <a class="dropdown-item waves-light waves-effect" href="tambah/tambah_user_import.php">Import Data</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dt-responsive table-responsive">
                                    <table id="order-table" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>NIP</th>
                                                <th>Nama Lengkap</th>
                                                <th>Email</th>
                                                <th>Jabatan</th>
                                                <th>Role</th>
                                                <th>Foto Profil</th>
                                                <th>Status</th>
                                                <th>Nomor Telepon</th>
                                                <th>Skills</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($dataUsers as $user) : ?>
<tr>
  <td><?= $user['nip']; ?></td>
  <td><?= htmlspecialchars($user['nama']); ?></td>
  <td><?= htmlspecialchars($user['email']); ?></td>
  <td><?= htmlspecialchars($user['nama_jabatan'] ?? '-'); ?></td>
  <td><?= badge(htmlspecialchars($user['nama_role'] ?? '-'), 'primary'); ?></td>
  <td>
    <?php if ($user['foto_profil']) : ?>
      <img src="../uploads/<?= htmlspecialchars($user['foto_profil']); ?>" width="40" style="border-radius: 50%;">
    <?php else : ?>
      <span class="badge bg-secondary">-</span>
    <?php endif; ?>
  </td>
  <td>
    <?php echo $user['status'] == 1 ? badge('Aktif', 'success') : badge('Tidak Aktif', 'danger'); ?>
  </td>
  <td><?= $user['nomor_telepon'] ? '0' . $user['nomor_telepon'] : '-'; ?></td>
  <td>
    <?php 
      $skills = getUserSkills($koneksi, $user['nip']);
      if (count($skills) > 0) {
        foreach ($skills as $skill) {
          echo "<span class='badge bg-info mr-2 mb-2'>" . htmlspecialchars($skill) . "</span>";
        }
      } else {
        echo '-';
      }
    ?>
  </td>
  <td>
    <a href="edit_user.php?nip=<?= $user['nip']; ?>" class="btn btn-sm btn-warning" title="Edit">
      <i class="ti-pencil text-dark"></i>
    </a>
    <a href="hapus_user.php?nip=<?= $user['nip']; ?>" 
       class="btn btn-sm btn-danger"
       onclick="return confirm('Yakin hapus user ini?')"
       title="Hapus">
       <i class="ti-trash text-dark"></i>
    </a>
  </td>
</tr>
<?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>NIP</th>
                                                <th>Nama Lengkap</th>
                                                <th>Email</th>
                                                <th>Jabatan</th>
                                                <th>Role</th>
                                                <th>Foto Profil</th>
                                                <th>Status</th>
                                                <th>Nomor Telepon</th>
                                                <th>Skills</th>
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
                                            <a class="dropdown-item waves-light waves-effect" href="#">JSON</a>
                                            <a class="dropdown-item waves-light waves-effect" href="#">CSV</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="dropdown-success dropdown open align-items-right" style="float: right;">
                                        <button class="btn btn-success dropdown-toggle waves-effect waves-light" type="button" id="tambah2" data-toggle="dropdown" aria-haspopup='true' aria-expanded='true'>Tambah</button>
                                        <div class="dropdown-menu" aria-labelledby="tambah2" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                            <a class="dropdown-item waves-light waves-effect" href="tambah/tambah_user_input.php">Input Data</a>
                                            <a class="dropdown-item waves-light waves-effect" href="tambah/tambah_user_import.php">Import Data</a>
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
                                                    <img src="../uploads/<?= htmlspecialchars($pengguna['foto_profil']); ?>" class="avatar-img" alt="<?= htmlspecialchars($pengguna['nama']); ?>">
                                                <?php else : ?>
                                                    <img src="../images/noimages.jpg" class="avatar-img" alt="No Image">
                                                <?php endif; ?>
                                                <div class="img-overlay img-radius">
                                                    <span>
                                                        <a href="edit_user.php?nip=<?= $pengguna['nip']; ?>" class="btn btn-sm btn-primary"><i class="ti-pencil"></i></a>
                                                        <a href="hapus_user.php?nip=<?= $pengguna['nip']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user ini?')"><i class="ti-trash"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="user-content">
                                                <h4><?= htmlspecialchars($pengguna['nama']); ?></h4>
                                                <span style="font-size: 12px; color: #d35858;"><?= $pengguna['nip']; ?></span>
                                                <h5 style="padding: 5px 0px"><?= htmlspecialchars($pengguna['email']); ?></h5>
                                                <p><?= htmlspecialchars($pengguna['nama_jabatan'] ?? '-'); ?></p>
                                                <small class="badge bg-info">
                                                    <?php echo $pengguna['status'] == 1 ? 'Aktif' : 'Tidak Aktif'; ?>
                                                </small>
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
