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
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
    <link rel="stylesheet" href="assets/pages/waves/css/waves.min.css" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/pages/waves/css/waves.min.css" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="assets/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="assets/icon/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/geo/jquery-jvectormap-2.0.2.css">
    <link rel="stylesheet" type="text/css" href="bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="assets/pages/data-table/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
      <link rel="stylesheet" type="text/css" href="assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css">
    <style>
    /* Kalender */
    .modal-body th {
      color: #6c757d;
      font-weight: 500;
    }
    .modal-body td {
      color: #212529;
    }
    #modalLinks i, #modalDokumentasi i {
      font-size: 20px;        /* atur sesuai selera: 18–22px */
      vertical-align: middle;
      transition: transform 0.15s ease, color 0.15s ease;
    }
    #modalLinks a,
    #modalLinks span, #modalDokumentasi a, #modalDokumentasi span {
      margin-right: 6px;
    }
    /* Default */
#modalLinks a {
  text-decoration: none;
  transition: color 0.2s ease, transform 0.2s ease;
}
/* Instagram */
#modalLinks .ti-instagram {
  color: #E1306C;
}
#modalLinks a:hover .ti-instagram {
  color: #C13584;
}
/* Facebook */
#modalLinks .ti-facebook {
  color: #1877F2;
}
#modalLinks a:hover .ti-facebook {
  color: #145DBF;
}
/* YouTube */
#modalLinks .ti-youtube {
  color: #FF0000;
}
#modalLinks a:hover .ti-youtube {
  color: #CC0000;
}
/* Website */
#modalLinks .ti-world {
  color: #6c757d;
}
#modalLinks a:hover .ti-world {
  color: #343a40;
}
/* Hover efek halus */
#modalLinks a:hover {
  transform: scale(1.1);
}
    
    /* Total konten */
    .nav-pills .nav-link {
      background: #f1f2f6;
      color: #555;
    }
    .nav-pills .nav-link.active {
      background: #6f42c1;
      color: #fff;
    }
    
    /* Top 5 Pegawai */
    .podium-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px 0;
      overflow: visible;
      position: relative;
      z-index:5;
    }
    .podium-step {
      position: relative;
      height: auto;
      padding: 10px;
      color: #fff;
      font-weight: 100;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 25px;
      cursor: default;
      transition: transform .15s ease;
      z-index: 1;
    }
    .podium-step .name {
      max-width: 80%;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      font-size: 14px;
      text-align: center;
    }
    .podium-step:hover {
      transform: translateY(-2px);
      z-index: 10;
    }
    .step-1 { width: 45%; background: #0b3c74; }
    .step-2 { width: 55%; background: #1768af; }
    .step-3 { width: 65%; background: #5d97de; }
    .step-4 { width: 75%; background: #88bcef; }
    .step-5 { width: 85%; background: #9ddaee; }
    .crown {
      position: absolute;
      top: -26px;
      font-size: 22px;
    }
    .podium-step::after {
      content:
        attr(data-name) "\A"
        "Total Penugasan: " attr(data-total);
      position: absolute;
      top: -52px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(30,30,30,.95);
      text-align: center;
      color: #fff;
      padding: 6px 10px;
      font-size: 12px;
      border-radius: 6px;
      white-space: pre;
      opacity: 0;
      pointer-events: none;
      z-index: 9999;
    }
    .podium-step:hover::after {
      opacity: 1;
    }
    
    /* Maps */
    .jvm-tooltip {
      background: rgba(30, 30, 30, 0.9);
      color: #fff;
      border-radius: 8px;
      padding: 8px 12px;
      font-size: 13px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.25);
    }
    #map-wrapper {
      position: relative;
      width: 100%;
      height: 480px;
      overflow: hidden;
    }
    #map-bangkalan svg {
      position: absolute;
      inset: 0;
      pointer-events: auto;
      filter: drop-shadow(0 4px 10px rgba(0,0,0,0.12));
    }
    
    /* ======================
       PC
       ====================== */
    @media (min-width: 992px) {
      .navbar-logo {
        display: flex;
        align-items: center;
      }
      .navbar-logo .logo {
        display: flex;
        align-items: center;
        gap: 2px;
        margin-right: 80px;
        margin-left: 20px;
      }
    }
    
    /* ======================
       MOBILE
       ====================== */
    @media (max-width: 788px) {
      .navbar-logo {
        display: flex;
        justify-content: center;
      }
      .navbar-logo .logo {
        display: flex;
        align-items: center;
        gap: 2px;
        margin: 0 auto;
      }
    }

    /* user card */
.avatar-wrapper {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    position: relative;
}
.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
    </style>
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
                              <a href="#!" class="waves-effect waves-light">
                                <?php if ($_SESSION['user']['foto_profil'] == ""): ?>
                                  <img src="../images/noimages.jpg" class="img-radius" alt="No Images">
                                  <i class="ti-angle-down"></i>
                                <?php else: ?>
                                  <img src="../images/<?= $_SESSION['user']['foto_profil'];?>" class="img-radius" alt="<?= $_SESSION['user']['foto_profil'];?>">;
                                  <i class="ti-angle-down"></i>
                                <?php endif; ?>
                              </a>
                              <ul class="show-notification profile-notification">
                                <div class="card">
                                    <div class="card-header">
                                        <li class="waves-effect waves-light">
                                            <h4><?= $_SESSION['user']['nama'];?></h4>
                                            <span style="font-weight: bold"><?= $_SESSION['role'];?></span>
                                            <br>
                                            <span><?= $_SESSION['user']['email'];?></span>
                                        </li>
                                    </div>
                                    <div class="card-block">
                                        <li class="waves-effect waves-light">
                                            <a href="user-profile.html">
                                                <i class="ti-user"></i> View Profile
                                            </a>
                                        </li>
                                        <li class="waves-effect waves-light">
                                            <a href="../logout.php">
                                                <i class="ti-layout-sidebar-left"></i> Logout
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
                          <div class="pcoded-navigation-label" data-i18n="nav.category.navigation">UTAMA</div>
                          <ul class="pcoded-item pcoded-left-item">
                              <li class="active">
                                  <a href="index.php" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li>
                                  <a href="#" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-harddrives"></i><b>FC</b></span>
                                      <span class="pcoded-mtext" data-i18n="???">Brankas Humas</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="???">Manajemen</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <li class=" ">
                                          <a href="manajemen_user.php" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">User</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Link</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Data Lainnya</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                          </ul>
                          <div class="pcoded-navigation-label" data-i18n="nav.category.forms">RUANG HUMAS</div>
                          <ul class="pcoded-item pcoded-left-item">
                              <li class=" ">
                                  <a href="#" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-layers-alt"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="???">Struktur Humas</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class=" ">
                                  <a href="#" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-calendar"></i><b>FC</b></span>
                                      <span class="pcoded-mtext" data-i18n="???">Jadwal Konten Humas</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-briefcase"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="???">Aset Humas</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Aset Visual</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Aset Barang</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Aset Lisensi</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                          </ul>
        
                          <div class="pcoded-navigation-label" data-i18n="nav.category.forms">SUMBERDAYA HUMAS</div>
                          <ul class="pcoded-item pcoded-left-item">
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-instagram"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="???">Template Medsos</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Potrait (4:5)</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Reels (9:16)</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Landscape (16:9)</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Pedoman Visual Medsos BPS</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-folder"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="???">Dokumentasi</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Kegiatan BPS Bangkalan</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Pendataan Sensus Ekonomi 2026</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-camera"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="???">Galeri Foto</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Pimpinan</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Pegawai</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Sensus Ekonomi 2026</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Gedung Kantor</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Landmark Bangkalan</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-video-camera"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="???">Galeri Video</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Kantor BPS Bangkalan</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Landmark Bangkalan</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Sensus Ekonomi 2026</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                              <li class="pcoded-hasmenu">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-files"></i></span>
                                      <span class="pcoded-mtext"  data-i18n="???">Laporan</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Pemanfaatan Adobe</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Konten SE2026</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Humas Bulanan</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class=" ">
                                          <a href="#" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="???">Humas Tahunan</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                          </ul>
                          <div class="pcoded-navigation-label" data-i18n="nav.category.forms">KEBUTUHAN BROADCAST</div>
                          <ul class="pcoded-item pcoded-left-item">
                              <li class=" ">
                                  <a href="#" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-video-clapper"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="???">Video Operator</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class=" ">
                                  <a href="#" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-blackboard"></i><b>FC</b></span>
                                      <span class="pcoded-mtext" data-i18n="???">Template OBS Rilis</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                          </ul>
                          <div class="pcoded-navigation-label" data-i18n="nav.category.forms">PENINGKATAN KAPASITAS</div>
                          <ul class="pcoded-item pcoded-left-item">
                              <li class=" ">
                                  <a href="#" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-microphone"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="???">Pembinaan Kehumasan</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
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
</body>

</html>
    <?php
}