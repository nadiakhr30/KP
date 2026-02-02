<?php
function renderLayout($content, $script) {

global $user;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="Kamila" />
    <link rel="icon" href="assets/images/logo_bps.ico" type="image/x-icon">
    <!-- Pegawai Theme Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/pages/waves/css/waves.min.css" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/pages/waves/css/waves.min.css" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="assets/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="assets/icon/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <!-- FullCalendar with CDN Fallback & Error Handling -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet" onerror="this.style.display='none'">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
    window.FULLCALENDAR_CHECK = setTimeout(function() {
        if (typeof FullCalendar === 'undefined') {
            console.warn('FullCalendar CDN failed to load - calendar feature disabled');
            window.FULLCALENDAR_DISABLED = true;
            var calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                calendarEl.innerHTML = '<div class="alert alert-warning m-3"><strong>Calendar unavailable</strong> - CDN connection failed. Please refresh the page or check your internet connection.</div>';
            }
        } else {
            clearTimeout(window.FULLCALENDAR_CHECK);
        }
    }, 3000);
    </script>
    <link rel="stylesheet" type="text/css" href="assets/geo/jquery-jvectormap-2.0.2.css">
    <link rel="stylesheet" type="text/css" href="bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="assets/pages/data-table/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css">
  </head>
  <body>
  <div class="theme-loader">
      <div class="loader-track">
          <div class="preloader-wrapper">
              <div class="spinner-layer spinner-blue">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
              <div class="spinner-layer spinner-red">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
            
              <div class="spinner-layer spinner-yellow">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
            
              <div class="spinner-layer spinner-green">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- Pre-loader end -->
  <div id="pcoded" class="pcoded">
      <div class="pcoded-overlay-box"></div>
      <div class="pcoded-container navbar-wrapper">
          <nav class="navbar header-navbar pcoded-header">
              <div class="navbar-wrapper">
                  <div class="navbar-logo">
                      <a class="mobile-menu waves-effect waves-light" id="mobile-collapse">
                          <i class="ti-menu"></i>
                      </a>
                      <a href="index.php" style="display:flex; align-items:center; gap:8px; text-decoration:none; max-width:220px;" class="logo">
                          <img src="../images/bps.png" alt="Logo BPS" style="width:40px; height:auto; flex-shrink:0;" />
                          <span style="color:white;">Humas BPS Bangkalan</span>
                      </a>
                      <a class="mobile-options waves-effect waves-light">
                          <i class="ti-more"></i>
                      </a>
                  </div>
                
                  <div class="navbar-container container-fluid">
                      <ul class="nav-left">
                          <li>
                              <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                          </li>
                          <li>
                              <a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light">
                                  <i class="ti-fullscreen"></i>
                              </a>
                          </li>
                      </ul>
                      <ul class="nav-right">
                          <li class="user-profile header-notification">
                              <a href="#!" class="waves-effect waves-light m-t-10" style="display: flex; align-items: center; gap: 8px;">
                                    <div class="avatar-wrapper2">
                                        <?php if ($_SESSION['pegawai']['foto_profil'] == ""): ?>
                                            <img src="../images/noimages.jpg" class="avatar-img" alt="No Images">
                                        <?php else: ?>
                                            <img src="../uploads/<?= $_SESSION['pegawai']['foto_profil'] ?>" class="avatar-img" alt="<?= $_SESSION['pegawai']['foto_profil'];?>">
                                        <?php endif; ?>
                                    </div>
                                  <i class="ti-angle-down"></i>
                              </a>
                              <ul class="show-notification profile-notification">
                                <div class="card m-0">
                                    <div class="card-header">
                                        <li class="waves-effect waves-light">
                                            <div style="display: flex; flex-direction: column; justify-content: center;">
                                                <h5 style="margin: 0; font-size: 13px;"><?= $_SESSION['pegawai']['nama'];?></h5>
                                                <small style="color: #999;"><?= $_SESSION['role'];?></small>
                                                <small style="color: #999;"><?= $_SESSION['pegawai']['email'];?></small>
                                            </div>
                                        </li>
                                    </div>
                                    <div class="card-block">
                                        <li class="waves-effect waves-light">
                                            <a href="../pegawai/profile.php">
                                                <i class="ti-user" style="color: #191f34;"> <span style="font-family: 'Poppins', sans-serif;">View Profile</span></i>
                                            </a>
                                        </li>
                                        <li class="waves-effect waves-light">
                                            <a href="../logout.php">
                                                <i class="ti-layout-sidebar-left" style="color: #191f34;"> <span style="font-family: 'Poppins', sans-serif;">Logout</span></i>
                                            </a>
                                        </li>
                                    </div>
                                  </div>
                              </ul>
                          </li>
                      </ul>
                  </div>
              </div>
          </nav>
          <div class="pcoded-main-container">
              <div class="pcoded-wrapper">
                  <nav class="pcoded-navbar">
                      <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                      <div class="pcoded-inner-navbar main-menu">
                          <div class="pcoded-navigation-label" data-i18n="nav.category.main">UTAMA</div>
                          <ul class="pcoded-item pcoded-left-item">
                              <li class="active">
                                  <a href="index.php" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li>
                                  <a href="brankas_humas.php" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-harddrives"></i><b>FC</b></span>
                                      <span class="pcoded-mtext" data-i18n="menu.brankas-humas">Brankas Humas</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="menu.manajemen">Manajemen</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <li class=" ">
                                          <a href="manajemen_user.php" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="menu.user">User</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="manajemen_link.php" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="menu.link">Link</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="manajemen_data_lainnya.php" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="menu.data-lainnya">Data Lainnya</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                          </ul>
                          <div class="pcoded-navigation-label" data-i18n="nav.category.workspace">RUANG HUMAS</div>
                          <ul class="pcoded-item pcoded-left-item">
                              <li class=" ">
                                  <a href="ruang_humas.php" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-layers-alt"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="menu.ruang-humas">Struktur Humas</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class=" ">
                                  <a href="jadwal_konten_humas.php" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-calendar"></i><b>FC</b></span>
                                      <span class="pcoded-mtext" data-i18n="menu.jadwal-konten">Jadwal Konten Humas</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-briefcase"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="menu.aset-humas">Aset Humas</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <?php
                                     if (function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                                         $qAsetMenu = @mysqli_query($GLOBALS['koneksi'], "SELECT * FROM jenis_aset ORDER BY nama_jenis_aset");
                                         if ($qAsetMenu) {
                                             while ($rowAset = mysqli_fetch_assoc($qAsetMenu)) {
                                                 echo '<li class=" ">';
                                                 echo '<a href="aset.php?jenis=' . (int)$rowAset['id_jenis_aset'] . '" class="waves-effect waves-dark">';
                                                 echo '<span class="pcoded-micon"><i class="ti-angle-right"></i></span>';
                                                 echo '<span class="pcoded-mtext">Aset ' . htmlspecialchars($rowAset['nama_jenis_aset']) . '</span>';
                                                 echo '<span class="pcoded-mcaret"></span>';
                                                 echo '</a>';
                                                 echo '</li>';
                                             }
                                         } else {
                                             echo '<li><a href="aset.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Aset</span></a></li>';
                                         }
                                     } else {
                                         echo '<li><a href="aset.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Aset</span></a></li>';
                                     }
                                      ?>
                                  </ul>
                              </li>
                          </ul>
        
                          <div class="pcoded-navigation-label" data-i18n="nav.category.resources">SUMBERDAYA HUMAS</div>
                          <ul class="pcoded-item pcoded-left-item">
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                          <span class="pcoded-micon"><i class="ti-instagram"></i></span>
                                          <span class="pcoded-mtext"  data-i18n="menu.template-medsos">Template Medsos</span>
                                          <span class="pcoded-mcaret"></span>
                                      </a>
                                      <ul class="pcoded-submenu">
                                          <?php
                                         if (function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                                             $qSubMenu = @mysqli_query($GLOBALS['koneksi'], "SELECT s.* FROM sub_jenis s JOIN jenis j ON s.id_jenis = j.id_jenis WHERE j.nama_jenis = 'Template Medsos' ORDER BY s.nama_sub_jenis");
                                             if ($qSubMenu) {
                                                 while ($rowSub = mysqli_fetch_assoc($qSubMenu)) {
                                                     echo '<li class=" ">';
                                                     echo '<a href="template_medsos.php?sub=' . (int)$rowSub['id_sub_jenis'] . '" class="waves-effect waves-dark">';
                                                     echo '<span class="pcoded-micon"><i class="ti-angle-right"></i></span>';
                                                     echo '<span class="pcoded-mtext">' . htmlspecialchars($rowSub['nama_sub_jenis']) . '</span>';
                                                     echo '<span class="pcoded-mcaret"></span>';
                                                     echo '</a>';
                                                     echo '</li>';
                                                 }
                                             } else {
                                                 echo '<li><a href="template_medsos.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Template Medsos</span></a></li>';
                                             }
                                         } else {
                                             echo '<li><a href="template_medsos.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Template Medsos</span></a></li>';
                                         }
                                          ?>
                                      </ul>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-folder"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="menu.dokumentasi">Dokumentasi</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <?php
                                     if (function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                                         $qSubMenu = @mysqli_query($GLOBALS['koneksi'], "SELECT s.* FROM sub_jenis s JOIN jenis j ON s.id_jenis = j.id_jenis WHERE j.nama_jenis = 'Dokumentasi' ORDER BY s.nama_sub_jenis");
                                         if ($qSubMenu) {
                                             while ($rowSub = mysqli_fetch_assoc($qSubMenu)) {
                                                 echo '<li class=" ">';
                                                 echo '<a href="dokumentasi.php?sub=' . (int)$rowSub['id_sub_jenis'] . '" class="waves-effect waves-dark">';
                                                 echo '<span class="pcoded-micon"><i class="ti-angle-right"></i></span>';
                                                 echo '<span class="pcoded-mtext">' . htmlspecialchars($rowSub['nama_sub_jenis']) . '</span>';
                                                 echo '<span class="pcoded-mcaret"></span>';
                                                 echo '</a>';
                                                 echo '</li>';
                                             }
                                         } else {
                                             echo '<li><a href="dokumentasi.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Dokumentasi</span></a></li>';
                                         }
                                     } else {
                                         echo '<li><a href="dokumentasi.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Dokumentasi</span></a></li>';
                                     }
                                      ?>
                                  </ul>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-camera"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="menu.galeri-foto">Galeri Foto</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <?php
                                     if (function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                                         $qSubMenu = @mysqli_query($GLOBALS['koneksi'], "SELECT s.* FROM sub_jenis s JOIN jenis j ON s.id_jenis = j.id_jenis WHERE j.nama_jenis = 'Galeri Foto' ORDER BY s.nama_sub_jenis");
                                         if ($qSubMenu) {
                                             while ($rowSub = mysqli_fetch_assoc($qSubMenu)) {
                                                 echo '<li class=" ">';
                                                 echo '<a href="galeri_foto.php?sub=' . (int)$rowSub['id_sub_jenis'] . '" class="waves-effect waves-dark">';
                                                 echo '<span class="pcoded-micon"><i class="ti-angle-right"></i></span>';
                                                 echo '<span class="pcoded-mtext">' . htmlspecialchars($rowSub['nama_sub_jenis']) . '</span>';
                                                 echo '<span class="pcoded-mcaret"></span>';
                                                 echo '</a>';
                                                 echo '</li>';
                                             }
                                         } else {
                                             echo '<li><a href="galeri_foto.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Galeri Foto</span></a></li>';
                                         }
                                     } else {
                                         echo '<li><a href="galeri_foto.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Galeri Foto</span></a></li>';
                                     }
                                      ?>
                                  </ul>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-video-camera"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="???">Galeri Video</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <?php
                                     if (function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                                         $qSubMenu = @mysqli_query($GLOBALS['koneksi'], "SELECT s.* FROM sub_jenis s JOIN jenis j ON s.id_jenis = j.id_jenis WHERE j.nama_jenis = 'Galeri Video' ORDER BY s.nama_sub_jenis");
                                         if ($qSubMenu) {
                                             while ($rowSub = mysqli_fetch_assoc($qSubMenu)) {
                                                 echo '<li class=" ">';
                                                 echo '<a href="galeri_video.php?sub=' . (int)$rowSub['id_sub_jenis'] . '" class="waves-effect waves-dark">';
                                                 echo '<span class="pcoded-micon"><i class="ti-angle-right"></i></span>';
                                                 echo '<span class="pcoded-mtext">' . htmlspecialchars($rowSub['nama_sub_jenis']) . '</span>';
                                                 echo '<span class="pcoded-mcaret"></span>';
                                                 echo '</a>';
                                                 echo '</li>';
                                             }
                                         } else {
                                             echo '<li><a href="galeri_video.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Galeri Video</span></a></li>';
                                         }
                                     } else {
                                         echo '<li><a href="galeri_video.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Galeri Video</span></a></li>';
                                     }
                                      ?>
                                  </ul>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-files"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="???">Laporan</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <?php
                                     if (function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                                         $qSubMenu = @mysqli_query($GLOBALS['koneksi'], "SELECT s.* FROM sub_jenis s JOIN jenis j ON s.id_jenis = j.id_jenis WHERE j.nama_jenis = 'Laporan' ORDER BY s.nama_sub_jenis");
                                         if ($qSubMenu) {
                                             while ($rowSub = mysqli_fetch_assoc($qSubMenu)) {
                                                 echo '<li class=" ">';
                                                 echo '<a href="laporan.php?sub=' . (int)$rowSub['id_sub_jenis'] . '" class="waves-effect waves-dark">';
                                                 echo '<span class="pcoded-micon"><i class="ti-angle-right"></i></span>';
                                                 echo '<span class="pcoded-mtext">' . htmlspecialchars($rowSub['nama_sub_jenis']) . '</span>';
                                                 echo '<span class="pcoded-mcaret"></span>';
                                                 echo '</a>';
                                                 echo '</li>';
                                             }
                                         } else {
                                             echo '<li><a href="laporan.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Laporan</span></a></li>';
                                         }
                                     } else {
                                         echo '<li><a href="laporan.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-angle-right"></i></span><span class="pcoded-mtext">Laporan</span></a></li>';
                                     }
                                      ?>
                                  </ul>
                              </li>
                          </ul>
                          <div class="pcoded-navigation-label" data-i18n="nav.category.broadcast">KEBUTUHAN BROADCAST</div>
                          <ul class="pcoded-item pcoded-left-item">
                              <?php
                             if (function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                                 $qSubMenu = @mysqli_query($GLOBALS['koneksi'], "SELECT s.* FROM sub_jenis s JOIN jenis j ON s.id_jenis = j.id_jenis WHERE j.nama_jenis = 'Kebutuhan Broadcast' ORDER BY s.nama_sub_jenis");
                                 if ($qSubMenu && mysqli_num_rows($qSubMenu) > 0) {
                                     while ($rowSub = mysqli_fetch_assoc($qSubMenu)) {
                                         echo '<li class=" ">';
                                         echo '<a href="kebutuhan_broadcast.php?sub=' . (int)$rowSub['id_sub_jenis'] . '" class="waves-effect waves-dark">';
                                         echo '<span class="pcoded-micon"><i class="ti-video-clapper"></i></span>';
                                         echo '<span class="pcoded-mtext">' . htmlspecialchars($rowSub['nama_sub_jenis']) . '</span>';
                                         echo '<span class="pcoded-mcaret"></span>';
                                         echo '</a>';
                                         echo '</li>';
                                     }
                                 } else {
                                     echo '<li><a href="kebutuhan_broadcast.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-video-clapper"></i></span><span class="pcoded-mtext">Kebutuhan Broadcast</span></a></li>';
                                 }
                             } else {
                                 echo '<li><a href="kebutuhan_broadcast.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-video-clapper"></i></span><span class="pcoded-mtext">Kebutuhan Broadcast</span></a></li>';
                             }
                              ?>
                          </ul>
                          <div class="pcoded-navigation-label" data-i18n="nav.category.capacity">PENINGKATAN KAPASITAS</div>
                          <ul class="pcoded-item pcoded-left-item">
                              <?php
                             if (function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                                 $qSubMenu = @mysqli_query($GLOBALS['koneksi'], "SELECT s.* FROM sub_jenis s JOIN jenis j ON s.id_jenis = j.id_jenis WHERE j.nama_jenis = 'Peningkatan Kapasitas' ORDER BY s.nama_sub_jenis");
                                 if ($qSubMenu && mysqli_num_rows($qSubMenu) > 0) {
                                     while ($rowSub = mysqli_fetch_assoc($qSubMenu)) {
                                         echo '<li><a href="peningkatan_kapasitas.php?sub=' . (int)$rowSub['id_sub_jenis'] . '" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-microphone"></i></span><span class="pcoded-mtext">' . htmlspecialchars($rowSub['nama_sub_jenis']) . '</span></a></li>';
                                     }
                                 } else {
                                     echo '<li><a href="peningkatan_kapasitas.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-microphone"></i></span><span class="pcoded-mtext">Peningkatan Kapasitas</span></a></li>';
                                 }
                             } else {
                                 echo '<li><a href="peningkatan_kapasitas.php" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="ti-microphone"></i></span><span class="pcoded-mtext">Peningkatan Kapasitas</span></a></li>';
                             }
                              ?>
                          </ul>
                      </div>
                  </nav>
                  <?= $content ?>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="assets/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery-ui/jquery-ui.min.js "></script>
    <script type="text/javascript" src="assets/js/popper.js/popper.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap/js/bootstrap.min.js "></script>
    <script type="text/javascript" src="assets/pages/widget/excanvas.js "></script>
    <script src="assets/pages/waves/js/waves.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery-slimscroll/jquery.slimscroll.js "></script>
    <script type="text/javascript" src="assets/js/modernizr/modernizr.js "></script>
    <script type="text/javascript" src="assets/js/SmoothScroll.js"></script>
    <script src="assets/js/jquery.mCustomScrollbar.concat.min.js "></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="assets/pages/widget/amchart/gauge.js"></script>
    <script src="assets/pages/widget/amchart/serial.js"></script>
    <script src="assets/pages/widget/amchart/light.js"></script>
    <script src="assets/pages/widget/amchart/pie.min.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/vertical-layout.min.js "></script>
    <script type="text/javascript" src="assets/pages/dashboard/custom-dashboard.js"></script>
    <script type="text/javascript" src="assets/js/script.js "></script>
    <script type="text/javascript" src="assets/geo/jquery-jvectormap-2.0.2.min.js"></script>
    <script type="text/javascript" src="assets/geo/bangkalan.js"></script>
    <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/pages/data-table/js/jszip.min.js"></script>
    <script src="assets/pages/data-table/js/pdfmake.min.js"></script>
    <script src="bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="bower_components/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="assets/pages/data-table/js/data-table-custom.js"></script>
    <script src="assets/pages/data-table/js/vfs_fonts.js"></script>
    <script src="assets/pages/data-table/extensions/buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/pages/data-table/extensions/buttons/js/buttons.flash.min.js"></script>
    <script src="assets/pages/data-table/extensions/buttons/js/jszip.min.js"></script>
    <script src="assets/pages/data-table/extensions/buttons/js/vfs_fonts.js"></script>
    <script src="assets/pages/data-table/extensions/buttons/js/buttons.colVis.min.js"></script>
    <script src="../files/assets/pages/data-table/extensions/buttons/js/extension-btns-custom.js"></script>
    <?= $script; ?>
    <script>
      // Toggle navbar-container on mobile/tablet
      document.addEventListener('DOMContentLoaded', function() {
        const mobileOptions = document.querySelector('.mobile-options');
        const navbarContainer = document.querySelector('.navbar-container');
        
        if (mobileOptions && navbarContainer) {
          mobileOptions.addEventListener('click', function(e) {
            e.preventDefault();
            navbarContainer.classList.toggle('show');
          });
          
          // Close menu when clicking outside
          document.addEventListener('click', function(e) {
            if (!e.target.closest('.mobile-options') && !e.target.closest('.navbar-container')) {
              navbarContainer.classList.remove('show');
            }
          });
        }
      });
    </script>

    <script>
      // Sync menu active state with current page filename
      document.addEventListener('DOMContentLoaded', function() {
        var path = window.location.pathname.split('/').pop() || 'index.php';
        
        // Remove 'active' class from all menu items
        var allMenuItems = document.querySelectorAll('.pcoded-inner-navbar li');
        allMenuItems.forEach(function(li) {
          li.classList.remove('active');
        });
        
        // Find and mark matching page as active
        var links = document.querySelectorAll('.pcoded-inner-navbar a[href]');
        links.forEach(function(a){
          var href = a.getAttribute('href').split('?')[0];
          if (!href) return;
          var target = href.split('/').pop();
          if (target === path) {
            var li = a.closest('li');
            var parentSub = a.closest('.pcoded-submenu');
            if (parentSub) {
              // Mark only the submenu item as active
              if (li) li.classList.add('active');
              // Open parent submenu
              var parentLi = parentSub.closest('li.pcoded-hasmenu');
              if (parentLi) {
                parentLi.classList.add('pcoded-open');
              }
            } else {
              // For non-submenu items, mark as active normally
              if (li) li.classList.add('active');
            }
          }
        });

        // Handle parent menu header clicks for toggle
        setTimeout(function() {
          var parentMenuLinks = document.querySelectorAll('.pcoded-inner-navbar li.pcoded-hasmenu > a[href="javascript:void(0)"]');
          parentMenuLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
              e.preventDefault();
              
              var parentLi = this.closest('li.pcoded-hasmenu');
              var isOpen = parentLi.classList.contains('pcoded-open');
              
              // Close all other dropdowns
              var allParentMenus = document.querySelectorAll('.pcoded-inner-navbar li.pcoded-hasmenu');
              allParentMenus.forEach(function(menu) {
                menu.classList.remove('pcoded-open');
              });
              
              // Toggle current dropdown
              if (!isOpen) {
                parentLi.classList.add('pcoded-open');
              }
            });
          });
        }, 100);
      });
    </script>

    <style>
      /* Style for active submenu items */
      .pcoded-submenu li.active > a {
        background: linear-gradient(to right, #f5f5f5, #e8e8e8) !important;
        color: #000 !important;
      }
      .pcoded-submenu li.active > a .pcoded-mtext {
        color: #000 !important;
      }
      .pcoded-submenu li.active > a i {
        color: #000 !important;
      }
    </style>
</body>

</html>
    <?php
}
