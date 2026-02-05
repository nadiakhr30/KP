<?php
function renderLayout($content, $script) {

global $user;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard Admin</title>
    <link rel="icon" href="../images/sikumbang.ico" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="Kamila" />
    <meta name="theme-color" content="#343bb9">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Sikumbang">
    <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
    <link rel="manifest" href="../manifest.json">
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
                          <img src="../images/sikumbang.png" alt="Logo BPS" style="width:40px; height:auto; flex-shrink:0;" />
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
                          <!-- PWA Install Button -->
                              <li style="display:none;" id="pwa-install-prompt">
                                  <a href="javascript:void(0)" class="waves-effect waves-light" id="pwa-install-btn" style="padding: 8px 12px; border-radius: 4px; color: white; font-size: 12px; display: flex; align-items: center; gap: 6px;">
                                      <i class="ti-download"></i>
                                      <span>Install App</span>
                                  </a>
                              </li>
                          <li class="header-notification">
                              <?php
                              // Prepare notifications for bell icon: current user as PIC and jadwal.status != 2
                              $nipCurrent = $_SESSION['pegawai']['nip'] ?? null;
                              $notifCount = 0;
                              $notifRows = [];
                              if ($nipCurrent && function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                                  $nipEsc = mysqli_real_escape_string($GLOBALS['koneksi'], $nipCurrent);
                                  $qCount = @mysqli_query($GLOBALS['koneksi'], "SELECT COUNT(DISTINCT j.id_jadwal) AS c FROM jadwal j JOIN pic p ON j.id_jadwal = p.id_jadwal WHERE p.nip = '" . $nipEsc . "' AND (j.status IS NULL OR j.status <> 2)");
                                  if ($qCount) {
                                      $rCount = mysqli_fetch_assoc($qCount);
                                      $notifCount = (int)($rCount['c'] ?? 0);
                                  }
                                  if ($notifCount > 0) {
                                      $qNotifs = @mysqli_query($GLOBALS['koneksi'], "SELECT DISTINCT j.id_jadwal, j.judul_kegiatan, j.tanggal_rilis FROM jadwal j JOIN pic p ON j.id_jadwal = p.id_jadwal WHERE p.nip = '" . $nipEsc . "' AND (j.status IS NULL OR j.status <> 2) ORDER BY j.tanggal_rilis ASC LIMIT 5");
                                      if ($qNotifs) {
                                          while ($r = mysqli_fetch_assoc($qNotifs)) {
                                              $notifRows[] = $r;
                                          }
                                      }
                                  }
                              }
                              ?>
                              <a href="#!" class="waves-effect waves-light">
                                  <i class="ti-bell"></i>
                                  <?php if ($notifCount > 0): ?>
                                      <span class="badge bg-c-red" style="width:8px;height:8px;padding:0;"></span>
                                  <?php else: ?>
                                      <span class="badge bg-c-red" style="display:none;"></span>
                                  <?php endif; ?>
                              </a>
                              <ul class="show-notification">
                                  <div class="card m-0">
                                      <div class="card-header">
                                          <li style="margin:0;">
                                              <h5 style="margin:0; font-size:14px; font-weight:600; display:flex; justify-content:space-between; align-items:center;">
                                                  <span>
                                                      <i class="ti-bell" style="color:#343bb9; margin-right:6px;"></i>Notifikasi Pengingat
                                                  </span>
                                                  <?php if ($notifCount > 0): ?>
                                                      <label class="label label-danger" style="margin:0; font-size:11px; padding:2px 6px;"><?= $notifCount ?> New</label>
                                                  <?php endif; ?>
                                              </h5>
                                          </li>
                                      </div>
                                      <div class="card-block" style="max-height:350px; overflow-y:auto; padding:0;">
                                          <?php if ($notifCount > 0): ?>
                                              <?php foreach ($notifRows as $rowNotif): ?>
                                                  <li class="waves-effect waves-light" style="list-style:none; border-bottom:1px solid #f0f0f0; padding:12px 16px; margin:0; cursor:pointer; transition:all 0.3s ease;">
                                                      <a href="jadwal_konten_humas.php?id=<?= (int)$rowNotif['id_jadwal'] ?>" style="text-decoration:none; color:inherit; display:block;">
                                                          <div style="display:flex; align-items:flex-start; gap:10px;">
                                                              <div style="flex-shrink:0; margin-top:2px;">
                                                                  <i class="ti-calendar" style="color:#c0392b; font-size:16px;"></i>
                                                              </div>
                                                              <div style="flex:1; min-width:0;">
                                                                  <h5 style="margin:0 0 4px 0; font-size:13px; font-weight:600; color:#191f34; word-wrap:break-word;">
                                                                      <?= htmlspecialchars($rowNotif['judul_kegiatan']) ?>
                                                                  </h5>
                                                                  <p style="margin:0; font-size:12px; color:#666; line-height:1.4;">
                                                                      <strong style="color:#c0392b;">Deadline:</strong> <?= htmlspecialchars($rowNotif['tanggal_rilis']) ?>
                                                                  </p>
                                                                  <p style="margin:4px 0 0 0; font-size:11px; color:#999;">
                                                                      <i class="ti-alert" style="color:#c0392b; margin-right:3px;"></i>Segera dikerjakan
                                                                  </p>
                                                              </div>
                                                          </div>
                                                      </a>
                                                  </li>
                                              <?php endforeach; ?>
                                              <?php if ($notifCount > count($notifRows)): ?>
                                                  <li style="list-style:none; padding:10px 16px; text-align:center; border-top:1px solid #f0f0f0;">
                                                      <a href="jadwal_konten_humas.php" style="font-size:12px; color:#343bb9; text-decoration:none; font-weight:600;">
                                                          Lihat semua pengingat (<?= $notifCount - count($notifRows) ?> lainnya)
                                                      </a>
                                                  </li>
                                              <?php endif; ?>
                                          <?php else: ?>
                                              <li style="list-style:none; padding:20px 16px; text-align:center; color:#999;">
                                                  <div style="font-size:14px; margin-bottom:6px;">
                                                      <i class="ti-check-box" style="font-size:20px; color:#27ae60;"></i>
                                                  </div>
                                                  <p style="margin:0; font-size:13px;">Tidak ada pengingat</p>
                                                  <p style="margin:4px 0 0 0; font-size:11px;">Semua jadwal terpenuhi</p>
                                              </li>
                                          <?php endif; ?>
                                      </div>
                                  </div>
                              </ul>
                          </li>
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
                              <?php
                              // Dynamic menu for Utama kategori
                              if (function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                                  $qJenisUtama = @mysqli_query($GLOBALS['koneksi'], "SELECT j.*, k.nama_kategori FROM jenis j JOIN kategori k ON j.id_kategori = k.id_kategori WHERE k.nama_kategori = 'Utama' ORDER BY j.id_jenis");
                                  
                                  if ($qJenisUtama && mysqli_num_rows($qJenisUtama) > 0) {
                                      while ($rowJenisUtama = mysqli_fetch_assoc($qJenisUtama)) {
                                          $namaJenis = $rowJenisUtama['nama_jenis'];
                                          
                                          // Check if there's a sub_jenis with the same name as jenis
                                          $qSameNameSub = @mysqli_query($GLOBALS['koneksi'], "SELECT id_sub_jenis FROM sub_jenis WHERE id_jenis = " . (int)$rowJenisUtama['id_jenis'] . " AND nama_sub_jenis = '" . mysqli_real_escape_string($GLOBALS['koneksi'], $namaJenis) . "' LIMIT 1");
                                          $hasSameNameSub = $qSameNameSub && mysqli_num_rows($qSameNameSub) > 0;
                                          
                                          // Determine the link based on jenis/sub_jenis relationship
                                          if ($hasSameNameSub && strtolower(trim($namaJenis)) !== 'pembinaan kehumasan') {
                                              // If same-named sub exists (and not "Pembinaan Kehumasan"), use preview_konten
                                              $rowSameNameSub = mysqli_fetch_assoc($qSameNameSub);
                                              $jenisLink = 'preview_konten.php?sub=' . (int)$rowSameNameSub['id_sub_jenis'];
                                          } else {
                                              // Otherwise use konten.php with jenis parameter
                                              $jenisLink = 'konten.php?jenis=' . urlencode($namaJenis);
                                          }
                                          
                                          echo '<li>';
                                          echo '<a href="' . htmlspecialchars($jenisLink) . '" class="waves-effect waves-dark">';
                                          echo '<span class="pcoded-micon"><i class="ti-harddrives"></i><b>FC</b></span>';
                                          echo '<span class="pcoded-mtext">' . htmlspecialchars($namaJenis) . '</span>';
                                          echo '<span class="pcoded-mcaret"></span>';
                                          echo '</a>';
                                          echo '</li>';
                                      }
                                  }
                              }
                              ?>
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
                              <?php
                              // Dynamic menu for Ruang Humas kategori
                              if (function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                                  $qJenisRuang = @mysqli_query($GLOBALS['koneksi'], "SELECT j.*, k.nama_kategori FROM jenis j JOIN kategori k ON j.id_kategori = k.id_kategori WHERE k.nama_kategori = 'Ruang Humas' ORDER BY j.id_jenis");
                                  
                                  if ($qJenisRuang && mysqli_num_rows($qJenisRuang) > 0) {
                                      while ($rowJenisRuang = mysqli_fetch_assoc($qJenisRuang)) {
                                          $namaJenis = $rowJenisRuang['nama_jenis'];
                                          
                                          // Skip special items that are handled separately
                                          if ($namaJenis === 'Jadwal Konten Humas' || $namaJenis === 'Aset Humas') {
                                              continue;
                                          }
                                          
                                          // Special handling for Struktur Humas
                                          if ($namaJenis === 'Struktur Humas') {
                                              $jenisLink = 'ruang_humas.php';
                                              $icon = 'ti-layers-alt';
                                          } else {
                                              // For other jenis in Ruang Humas, check if same-named sub exists
                                              $qSameNameSub = @mysqli_query($GLOBALS['koneksi'], "SELECT id_sub_jenis FROM sub_jenis WHERE id_jenis = " . (int)$rowJenisRuang['id_jenis'] . " AND nama_sub_jenis = '" . mysqli_real_escape_string($GLOBALS['koneksi'], $namaJenis) . "' LIMIT 1");
                                              $hasSameNameSub = $qSameNameSub && mysqli_num_rows($qSameNameSub) > 0;
                                              
                                              if ($hasSameNameSub) {
                                                  // If same-named sub exists, use preview_konten
                                                  $rowSameNameSub = mysqli_fetch_assoc($qSameNameSub);
                                                  $jenisLink = 'preview_konten.php?sub=' . (int)$rowSameNameSub['id_sub_jenis'];
                                              } else {
                                                  // Otherwise use konten.php
                                                  $jenisLink = 'konten.php?jenis=' . urlencode($namaJenis);
                                              }
                                              $icon = 'ti-bookmark';
                                          }
                                          
                                          echo '<li>';
                                          echo '<a href="' . htmlspecialchars($jenisLink) . '" class="waves-effect waves-dark">';
                                          echo '<span class="pcoded-micon"><i class="' . htmlspecialchars($icon) . '"></i><b>D</b></span>';
                                          echo '<span class="pcoded-mtext">' . htmlspecialchars($namaJenis) . '</span>';
                                          echo '<span class="pcoded-mcaret"></span>';
                                          echo '</a>';
                                          echo '</li>';
                                      }
                                  }
                              }
                              ?>
                              <li>
                                  <a href="jadwal_konten_humas.php" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-calendar"></i></span>
                                      <span class="pcoded-mtext" data-i18n="menu.jadwal-konten-humas">Jadwal Konten Humas</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-briefcase"></i></span>
                                      <span class="pcoded-mtext" data-i18n="menu.aset-humas">Aset Humas</span>
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
        
                          <?php
                          // Dynamic menu from database (exclude Utama and Ruang Humas as they are already generated above)
                          if (function_exists('mysqli_query') && isset($GLOBALS['koneksi'])) {
                              // Get all kategori except "Utama" and "Ruang Humas" ordered by id
                              $qKategori = @mysqli_query($GLOBALS['koneksi'], "SELECT * FROM kategori WHERE nama_kategori NOT IN ('Utama', 'Ruang Humas') ORDER BY id_kategori");
                              
                              if ($qKategori && mysqli_num_rows($qKategori) > 0) {
                                  while ($rowKategori = mysqli_fetch_assoc($qKategori)) {
                                      echo '<div class="pcoded-navigation-label">' . htmlspecialchars($rowKategori['nama_kategori']) . '</div>';
                                      echo '<ul class="pcoded-item pcoded-left-item">';
                                      
                                      // Get jenis for this kategori
                                      $qJenis = @mysqli_query($GLOBALS['koneksi'], "SELECT * FROM jenis WHERE id_kategori = " . (int)$rowKategori['id_kategori'] . " ORDER BY nama_jenis");
                                      
                                      if ($qJenis && mysqli_num_rows($qJenis) > 0) {
                                          while ($rowJenis = mysqli_fetch_assoc($qJenis)) {
                                              // Get sub_jenis for this jenis
                                              $qSubJenis = @mysqli_query($GLOBALS['koneksi'], "SELECT * FROM sub_jenis WHERE id_jenis = " . (int)$rowJenis['id_jenis'] . " ORDER BY nama_sub_jenis");
                                              $hasSubMenu = false;
                                              $subMenuItems = array();
                                              
                                              if ($qSubJenis && mysqli_num_rows($qSubJenis) > 0) {
                                                  while ($rowSubJenis = mysqli_fetch_assoc($qSubJenis)) {
                                                      // Only add to submenu if sub_jenis name is different from jenis name
                                                      if ($rowSubJenis['nama_sub_jenis'] !== $rowJenis['nama_jenis']) {
                                                          $hasSubMenu = true;
                                                          $subMenuItems[] = $rowSubJenis;
                                                      }
                                                  }
                                              }
                                              
                                              if ($hasSubMenu) {
                                                  // Menu with submenu
                                                  echo '<li class="pcoded-hasmenu">';
                                                  echo '<a href="javascript:void(0)" class="waves-effect waves-dark">';
                                                  echo '<span class="pcoded-micon"><i class="ti-angle-right"></i></span>';
                                                  echo '<span class="pcoded-mtext">' . htmlspecialchars($rowJenis['nama_jenis']) . '</span>';
                                                  echo '<span class="pcoded-mcaret"></span>';
                                                  echo '</a>';
                                                  echo '<ul class="pcoded-submenu">';
                                                  
                                                  foreach ($subMenuItems as $subItem) {
                                                      echo '<li class=" ">';
                                                      echo '<a href="konten.php?jenis=' . urlencode($rowJenis['nama_jenis']) . '&sub=' . (int)$subItem['id_sub_jenis'] . '" class="waves-effect waves-dark">';
                                                      echo '<span class="pcoded-micon"><i class="ti-angle-right"></i></span>';
                                                      echo '<span class="pcoded-mtext">' . htmlspecialchars($subItem['nama_sub_jenis']) . '</span>';
                                                      echo '<span class="pcoded-mcaret"></span>';
                                                      echo '</a>';
                                                      echo '</li>';
                                                  }
                                                  
                                                  echo '</ul>';
                                                  echo '</li>';
                                              } else {
                                                  // Simple menu item without submenu
                                                  // Determine if this should use preview_konten or konten
                                                  $contentFile = 'konten.php'; // Default
                                                  $previewOnlyJenis = array('Kebutuhan Broadcast', 'Brankas Humas'); // Add jenis that should use preview view
                                                  
                                                  if (in_array($rowJenis['nama_jenis'], $previewOnlyJenis)) {
                                                      // Find the sub_jenis that matches jenis name for preview pages
                                                      $qPreviewSub = @mysqli_query($GLOBALS['koneksi'], "SELECT id_sub_jenis FROM sub_jenis WHERE id_jenis = " . (int)$rowJenis['id_jenis'] . " AND nama_sub_jenis = '" . mysqli_real_escape_string($GLOBALS['koneksi'], $rowJenis['nama_jenis']) . "' LIMIT 1");
                                                      if ($qPreviewSub && mysqli_num_rows($qPreviewSub) > 0) {
                                                          $rowPreviewSub = mysqli_fetch_assoc($qPreviewSub);
                                                          $contentFile = 'preview_konten.php?sub=' . (int)$rowPreviewSub['id_sub_jenis'];
                                                      } else {
                                                          $contentFile = 'konten.php?jenis=' . urlencode($rowJenis['nama_jenis']);
                                                      }
                                                  } else {
                                                      $contentFile = 'konten.php?jenis=' . urlencode($rowJenis['nama_jenis']);
                                                  }
                                                  
                                                  // Check if there's a sub_jenis with the same name as jenis (preview-only pattern)
                                                  $qSameNameSub = @mysqli_query($GLOBALS['koneksi'], "SELECT id_sub_jenis FROM sub_jenis WHERE id_jenis = " . (int)$rowJenis['id_jenis'] . " AND nama_sub_jenis = '" . mysqli_real_escape_string($GLOBALS['koneksi'], $rowJenis['nama_jenis']) . "' LIMIT 1");
                                                  $hasSameNameSub = $qSameNameSub && mysqli_num_rows($qSameNameSub) > 0;
                                                  
                                                  // Use preview_konten if same-named sub exists AND jenis is not "pembinaan kehumasan"
                                                  if ($hasSameNameSub && strtolower(trim($rowJenis['nama_jenis'])) !== 'pembinaan kehumasan') {
                                                      $rowSameNameSub = mysqli_fetch_assoc($qSameNameSub);
                                                      $contentFile = 'preview_konten.php?sub=' . (int)$rowSameNameSub['id_sub_jenis'];
                                                  } else if (!$hasSameNameSub) {
                                                      // If no same-named sub, always use conten
                                                      $contentFile = 'konten.php?jenis=' . urlencode($rowJenis['nama_jenis']);
                                                  }
                                                  
                                                  echo '<li class=" ">';
                                                  echo '<a href="' . $contentFile . '" class="waves-effect waves-dark">';
                                                  echo '<span class="pcoded-micon"><i class="ti-angle-right"></i></span>';
                                                  echo '<span class="pcoded-mtext">' . htmlspecialchars($rowJenis['nama_jenis']) . '</span>';
                                                  echo '<span class="pcoded-mcaret"></span>';
                                                  echo '</a>';
                                                  echo '</li>';
                                              }
                                          }
                                      }
                                      
                                      echo '</ul>';
                                  }
                              }
                          }
                          ?>
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
      // Sync menu active state with current page filename and query parameters
      document.addEventListener('DOMContentLoaded', function() {
        var currentPath = window.location.pathname.split('/').pop() || 'index.php';
        var currentSearch = window.location.search; // Get full query string
        
        // Remove 'active' class from all menu items
        var allMenuItems = document.querySelectorAll('.pcoded-inner-navbar li');
        allMenuItems.forEach(function(li) {
          li.classList.remove('active');
        });
        
        // Find and mark matching page as active
        var links = document.querySelectorAll('.pcoded-inner-navbar a[href]');
        links.forEach(function(a){
          var href = a.getAttribute('href');
          if (!href || href === 'javascript:void(0)') return;
          
          // Extract filename and query string from href
          var hrefFile = href.split('?')[0].split('/').pop();
          var hrefSearch = href.indexOf('?') !== -1 ? href.substring(href.indexOf('?')) : '';
          
          // Check if this is the current page
          var isCurrentPage = (hrefFile === currentPath);
          
          // If page matches and has query parameters, compare them too
          if (isCurrentPage && hrefSearch !== '') {
            // Only mark as active if query string matches exactly
            isCurrentPage = (hrefSearch === currentSearch);
          } else if (isCurrentPage && hrefSearch === '' && currentSearch === '') {
            // Both have no query string, so it's a match
            isCurrentPage = true;
          } else if (isCurrentPage && hrefSearch === '' && currentSearch !== '') {
            // href has no query string but current page does, not a match
            isCurrentPage = false;
          }
          
          if (isCurrentPage) {
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

      /* Remove gap between navbar and menu - Mobile only */
      @media (max-width: 991px) {
        .pcoded-inner-navbar {
          padding-top: 0 !important;
          margin-top: 0 !important;
        }

        .pcoded-navbar {
          margin-top: 0 !important;
          padding-top: 0 !important;
        }
      }
    </style>
    
    <!-- PWA Service Worker Registration -->
    <script src="../assets/js/pwa-install.js"></script>
    <script>
      if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
          navigator.serviceWorker.register('../service-worker.js')
            .then(function(registration) {
              console.log('Service Worker registered successfully:', registration);
            })
            .catch(function(error) {
              console.log('Service Worker registration failed:', error);
            });
        });
      }
    </script>
</body>

</html>
    <?php
}
