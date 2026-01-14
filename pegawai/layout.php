<?php
function renderLayout($content, $script) {
    
  // Determine avatar HTML: prefer user's uploaded foto_profil, else Font Awesome icon, else default asset
  $avatar_html = '<i class="fa-solid fa-user avatar-icon me-2"></i>';
  if (session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
  }
  if (!empty($_SESSION['user'])) {
    // Attempt to fetch foto_profil from database
    $userId = $_SESSION['user']['id_user'] ?? null;
    $userEmail = $_SESSION['user']['email'] ?? null;
    if ($userId || $userEmail) {
      $koneksiPath = __DIR__ . '/../koneksi.php';
      if (file_exists($koneksiPath)) {
        require_once $koneksiPath;
        $foto = '';
                if ($userId) {
          // Use existing $koneksi if available, otherwise try a temporary connection
          $dbConn = (isset($koneksi) && $koneksi) ? $koneksi : null;
          $tmpConn = null;
          if (!$dbConn) {
            // fallback: try to open a temporary connection (credentials from project koneksi.php)
            $tmpConn = @mysqli_connect('localhost','root','123','sistem_kehumasan');
            if ($tmpConn) $dbConn = $tmpConn;
          }

          if ($dbConn) {
            $id_esc = mysqli_real_escape_string($dbConn, $userId);
            $q = "SELECT foto_profil FROM user WHERE id_user='" . $id_esc . "' LIMIT 1";
            $r = mysqli_query($dbConn, $q);
            if ($r && mysqli_num_rows($r) > 0) {
              $row = mysqli_fetch_assoc($r);
              $foto = $row['foto_profil'] ?? '';
            }
          } else {
            // as last resort, escape with addslashes
            $id_esc = addslashes($userId);
            $q = "SELECT foto_profil FROM user WHERE id_user='" . $id_esc . "' LIMIT 1";
            // cannot run query without DB connection
          }

          if (!empty($tmpConn)) {
            mysqli_close($tmpConn);
          }
        }
        if (empty($foto) && $userEmail) {
          // Use existing $koneksi if available, otherwise try a temporary connection
          $dbConn2 = (isset($koneksi) && $koneksi) ? $koneksi : null;
          $tmpConn2 = null;
          if (!$dbConn2) {
            $tmpConn2 = @mysqli_connect('localhost','root','123','sistem_kehumasan');
            if ($tmpConn2) $dbConn2 = $tmpConn2;
          }

          if ($dbConn2) {
            $email_esc = mysqli_real_escape_string($dbConn2, $userEmail);
            $q = "SELECT foto_profil FROM user WHERE email='" . $email_esc . "' LIMIT 1";
            $r = mysqli_query($dbConn2, $q);
            if ($r && mysqli_num_rows($r) > 0) {
              $row = mysqli_fetch_assoc($r);
              $foto = $row['foto_profil'] ?? '';
            }
          }

          if (!empty($tmpConn2)) {
            mysqli_close($tmpConn2);
          }
        }
        if (!empty($foto)) {
          $uploadsPath = __DIR__ . '/../uploads/' . $foto;
          if (file_exists($uploadsPath)) {
                        $avatar_url = '../uploads/' . rawurlencode($foto);
                        $avatar_html = '<img src="' . htmlspecialchars($avatar_url) . '" alt="User" class="avatar-img me-2">';
          } 
        }
      }
    }
  }

  ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dashboard Pegawai</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- themify icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/themify-icons/0.1.2/css/themify-icons.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>


  <!-- Full Calendar -->
      <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

  <style>
      .hero {
        position: relative;
        overflow: hidden;
      }

      .hero-waves {
        display: block;
        width: 100%;
        height: 80px;
        position: absolute;
        bottom: -1px;
        left: 0;
      }
      .wave1 use {
        animation: move-wave 10s linear infinite;
      }
      .wave2 use {
        animation: move-wave 8s linear infinite;
      }
      .wave3 use {
        animation: move-wave 6s linear infinite;
      }

      @keyframes move-wave {
        from { transform: translateX(0); }
        to { transform: translateX(-160px); }
      }

      .modal-body th {
        color: #6c757d;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
      }
      .modal-body td {
        color: #212529;
        font-family: 'Poppins', sans-serif;
      }
      .btn-close-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .btn-close-circle i {
        font-size: 18px;
        font-weight: bold;
      }
      .icon-link {
        font-size: 1.2rem;
        color: #0d6efd;
        transition: 0.2s;
      }

      .icon-link:hover {
        color: #084298;
      }
      .icon-link {
        font-size: 1.3rem;
        color: #0d6efd;
        cursor: pointer;
      }

      .icon-link:hover {
        color: #084298;
      }

      #modalLinks i {
        font-size: 1.4rem;
      }
      /* CARD */
.humas-card {
  position: relative;
  overflow: hidden;
  font-family: 'Poppins', sans-serif;
}

/* KONTEN AWAL TETAP DI ATAS */
.humas-card .icon,
.humas-card h4,
.humas-card p {
  position: relative;
  z-index: 1;
}

/* OVERLAY */
.humas-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, #0d6efd, #084298);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 14px;
  padding: 20px;
  text-align: center;

  /* animasi */
  transform: translateY(100%);
  transition: transform 0.4s ease-in-out;

  z-index: 10;
}

/* HOVER CARD */
.humas-card:hover .humas-overlay {
  transform: translateY(0);
}

/* ITEM OVERLAY */
.overlay-item {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;

  color: #fff;
  font-size: 15px;
  font-weight: 600;
  text-decoration: none;

  padding: 10px 16px;
  border-radius: 8px;
  transition: background 0.25s ease;
}

.overlay-item i {
  font-size: 18px;
}

/* HOVER ITEM */
.overlay-item:hover {
  background: rgba(255, 255, 255, 0.2);
}


    /* Avatar: fixed size, rounded and crop to fill without distortion */
    .avatar-img{width:44px;height:44px;object-fit:cover;border-radius:50%;display:inline-block}
    /* extra gap between nav and user avatar (increased) */
    .user-area{margin-left:6rem}
    @media (max-width:1199px){.user-area{margin-left:4rem}}
    @media (max-width:991px){.user-area{margin-left:1rem}}
    /* increase right padding for nav to prevent crowding */
    .navmenu{padding-right:3rem}
  </style>

  
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="#" class="logo d-flex align-items-center me-auto">
        <h5 class="sitename">Humas BPS Bangkalan</h5>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#beranda" class="active">Beranda</a></li>
          <li><a href="#Humas">Humas</a></li>
          <li><a href="#services">Manajemen</a></li>
          <li><a href="#portfolio">Dokumentasi</a></li>
          <li><a href="#team">Pengembangan</a></li>
          <li><a href="#pricing">Sumber Daya</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

        <div class="ms-3">
        <div class="dropdown">
          <a class="d-flex align-items-center text-decoration-none dropdown-toggle"
            href=""
            id="userDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            style="color:#fff">
            <?= $avatar_html ?>
          </a>
          
          <ul class="dropdown-menu dropdown-menu-end shadow mt-2" aria-labelledby="userDropdown">

           <!-- Header User + Foto -->
            <li>
              <div class="px-3 py-3 border-bottom text-center">

                <h6 class="mb-0 fw-bold"><?= $_SESSION['user']['nama']; ?></h6>
                <small class="text-muted"><?= $_SESSION['role']; ?></small><br>
                <small><?= $_SESSION['user']['email']; ?></small>

              </div>
            </li>

            <!-- Menu -->
            <li>
              <a class="dropdown-item" href="profile.php">
                <i class="bi bi-person-fill me-2"></i> View Profile
              </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
              <a class="dropdown-item text-danger" href="../logout.php">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
              </a>
            </li>

          </ul>
        </div>
      </div>


    </div>
  </header>



<!-- ======= Isi Content ======= -->
  <?= $content ?>

<!-- ======= Footer ======= -->
  <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="d-flex align-items-center">
            <span class="sitename">Humas BPS Bangkalan</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Jl. Halim Perdana Kusuma No.5, Area Sawah, Mlajah</p>
            <p>Kec. Bangkalan, Kabupaten Bangkalan, Jawa Timur 69116</p>
            <p class="mt-3"><strong>Phone:</strong> <span>0313095622</span></p>
            <p><strong>Email:</strong> <span>bps35260@gmail.com</span></p>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12">
          <h4>Follow Us</h4>
          <p>Cras fermentum odio eu feugiat lide par naso tierra videa magna derita valies</p>
          <div class="social-links d-flex">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <span>Badan Pusat Statisik Bangkalan</span></p>
      <div class="credits">
        Designed by <strong class="px-1 sitename">Humas BPS bangkalan</strong>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>



  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

  <?= $script ?>
  
   
</body>

</html>
    <?php
}