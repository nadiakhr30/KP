<?php
function renderLayout($content, $script) {

global $user;
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
      
      .humas-card {
        position: relative;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
      }


      .humas-card .icon,
      .humas-card h4,
      .humas-card p {
        position: relative;
        z-index: 1;
      }


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

      
        transform: translateY(100%);
        transition: transform 0.4s ease-in-out;

        z-index: 10;
      }


      .humas-card:hover .humas-overlay {
        transform: translateY(0);
      }


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


      .overlay-item:hover {
        background: rgba(255, 255, 255, 0.2);
      }

   
    
    .fc .fc-toolbar {
      border: none !important;
      border-bottom: none !important;
    }
    
    .fc-toolbar-chunk {
      border: none !important;
    }
    
    
    .fc .fc-daygrid-head-frame {
      border: none !important;
    }
    
    
    .fc .fc-daygrid-day {
      border: 1px solid #e0e0e0 !important;
    }
    
    .fc .fc-col-header-cell {
      border: 1px solid #e0e0e0 !important;
    }
    
    .fc .fc-daygrid-day-frame {
      border: 1px solid #e0e0e0 !important;
    }
    
    .fc-theme-standard {
      border: 1px solid #e0e0e0 !important;
    }
    
    .fc .fc-daygrid-day-number {
      padding: 6px 4px;
    }
/* ================= GRID ================= */
.asset-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:28px;
  margin-top:32px;
}

/* ================= CARD ================= */
.asset-card{
  position:relative;
  background:#ffffff;
  border-radius:22px;
  padding:44px 30px;
  text-align:center;
  box-shadow:
    0 18px 40px rgba(15,23,42,.08),
    inset 0 1px 0 rgba(255,255,255,.7);
  transition:.4s cubic-bezier(.16,1,.3,1);
  overflow:hidden;
  min-height:260px;
}

.asset-card:hover{
  transform:translateY(-8px);
  box-shadow:0 30px 70px rgba(15,23,42,.16);
}

/* ================= CONTENT ================= */
.card-content{
  transition:.4s;
}

.icon-circle{
  width:66px;
  height:66px;
  margin:0 auto 20px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:30px;
  color:#fff;
}

.icon-circle.blue{
  background:linear-gradient(135deg,#38bdf8,#2563eb);
}
.icon-circle.green{
  background:linear-gradient(135deg,#4ade80,#16a34a);
}

/* ================= OVERLAY CARD (KELUAR DARI KIRI) ================= */
.card-overlay{
  position:absolute;
  inset:0;
  background:linear-gradient(135deg,#0d6efd,#084298);
  display:flex;
  justify-content:center;
  align-items:center;
  padding:24px;

  transform:translateX(-100%);
  transition:.45s cubic-bezier(.16,1,.3,1);
  z-index:10;
}

.asset-card:hover .card-overlay{
  transform:translateX(0);
}

.asset-card:hover .card-content{
  filter:blur(3px);
}

/* ================= MENU GRID ================= */
.overlay-menu{
  width:100%;
  display:grid;
  grid-template-columns:repeat(4,minmax(160px,1fr));
  gap:18px;
}

/* ================= BUTTON (TANPA OVERLAY) ================= */
.overlay-menu a{
  display:flex;
  align-items:center;
  justify-content:center;

  height:70px;
  padding:0 22px;

  border-radius:18px;
  background:rgba(255,255,255,0.18);
  backdrop-filter:blur(6px);

  color:#ffffff;
  font-size:15px;
  font-weight:600;
  text-decoration:none;
  text-align:center;

  border:1px solid rgba(255,255,255,0.28);

  box-shadow:
    0 10px 30px rgba(0,0,0,0.18),
    inset 0 1px 0 rgba(255,255,255,0.35);

  transition:
    transform .25s ease,
    box-shadow .25s ease,
    background .25s ease,
    color .25s ease;
}

/* ================= HOVER BUTTON ================= */
.overlay-menu a:hover{
  transform:translateY(-6px) scale(1.03);
  background:rgba(255,255,255,0.35);
  color:#0f172a;
  box-shadow:
    0 20px 45px rgba(0,0,0,0.25),
    inset 0 1px 0 rgba(255,255,255,0.9);
}

/* ================= RESPONSIVE ================= */
@media (max-width:992px){
  .overlay-menu{
    grid-template-columns:repeat(3,minmax(140px,1fr));
  }
}

@media (max-width:768px){
  .overlay-menu{
    grid-template-columns:repeat(2,minmax(140px,1fr));
  }
}

@media (max-width:480px){
  .overlay-menu{
    grid-template-columns:1fr;
  }
  .overlay-menu a{
    height:62px;
    font-size:14px;
  }
}





      #footer {
        background: #3d4d6a;
        color: #fff;
      }

      
      #footer p,
      #footer span,
      #footer h4,
      #footer strong,
      #footer .sitename {
        color: #fff;
      }

      
      #footer a {
        color: #fff;
        text-decoration: none;
      }

      
      #footer a:hover {
        color: #ddd;
      }

      
      #footer .footer-links ul li i {
        color: #fff;
      }

      
      #footer .social-links a {
        color: #fff;
        font-size: 18px;
      }

      #footer .social-links a:hover {
        color: #ddd;
      }

      
      #footer .copyright {
        color: #fff;
      }

    
    .avatar-img{width:44px;height:44px;object-fit:cover;border-radius:50%;display:inline-block}
    
    .user-area{margin-left:6rem}
    @media (max-width:1199px){.user-area{margin-left:4rem}}
    @media (max-width:991px){.user-area{margin-left:1rem}}
    
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
          <li><a href="#sumberdaya">Sumber Daya</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

        <div class="ms-3">
        <div class="dropdown">
          <a class="d-flex align-items-center text-decoration-none dropdown-toggle"
            href="#"
            id="userDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            style="color:#fff">

            <?php
              $foto = $_SESSION['user']['foto_profil'] ?? '';

              if (!empty($foto) && file_exists(__DIR__ . '/../uploads/' . $foto)) {
            ?>
                <img src="../uploads/<?= htmlspecialchars($foto); ?>"
                    class="avatar-img me-2"
                    alt="User">
            <?php } else { ?>
                <i class="fa-solid fa-user avatar-icon me-2"></i>
            <?php } ?>

            <i class="ti-angle-down ms-1"></i>
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
          <h4>Quick Links</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Beranda</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Ruang Humas</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Dokumentasi</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Sumber Daya</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Broadcast</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Layanan Informasi</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Jadwal Konten Humas</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Galeri Foto</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Galeri Video</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Laporan Humas</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Pedoman Visual Medsos</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12">
          <h4>Follow Us</h4>
          <p>Ikuti kami untuk informasi dan publikasi terbaru dari BPS Bangkalan.</p>
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
      <p>© <span>2026 </span> <span>Badan Pusat Statisik Bangkalan</span></p>
      <div class="credits">
        Dikelola oleh <strong class="px-1 sitename">Humas BPS bangkalan</strong>
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