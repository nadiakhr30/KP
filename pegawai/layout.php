  <style>
        /* Full-width hero/content for mobile */
        @media (max-width: 768px) {
          .hero, .container-xl, .container, .row, .footer-top {
            width: 100vw !important;
            min-width: 100vw !important;
            max-width: 100vw !important;
            margin: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            box-sizing: border-box !important;
          }
          body, html {
            overflow-x: hidden !important;
          }
        }
    #navbarNotif {
      position: relative;
      font-size: 20px;
      color: #ffffff;
      cursor: pointer;
      vertical-align: middle;
    }
    /* removed stray closing brace */
    #navbarNotif .notif-badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #e84118;
      color: #ffc400;
      border-radius: 50%;
      font-size: 12px;
      padding: 2px 6px;
      font-weight: bold;
      z-index: 10001;
    }
    #navbarNotif .dropdown-menu {
      z-index: 10001;
      background-color: #ffc400;
    }
    #navbarNotif .dropdown-header {
      background-color: #ffc400;
      color: #ffffff;
      font-weight: bold;
      font-family: 'Poppins', sans-serif;
    }
    #navbarNotif .dropdown-item {
      color: #ffffff;
      background-color: #ffc400;
      font-family: 'Poppins', sans-serif;
    }
    #navbarNotif .dropdown-item:hover {
      background-color: #e6b300;
      color: #ffffff;
    }

    /* Responsive Navbar & Dropdown */
    @media (max-width: 768px) {
      html, body {
        overflow-x: hidden !important;
      }
      .header {
        padding: 10px 0;
      }
      .navmenu {
        width: 100vw;
        left: 0;
        margin: 0;
        padding: 0;
      }
      .navmenu ul {
        flex-direction: column;
        background: #fff;
        position: absolute;
        top: 60px;
        left: 0;
        width: 100vw;
        min-width: 100vw;
        max-width: 100vw;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        z-index: 9999;
        overflow-x: hidden;
      }
      .navmenu ul li {
        width: 100vw;
        text-align: left;
        margin: 0;
        padding: 0;
      }
      .navmenu ul li a {
        display: block;
        width: 100vw;
        box-sizing: border-box;
        padding: 16px 18px;
        font-size: 17px;
        border-bottom: 1px solid #eee;
        margin: 0;
      }
      #navbarNotif {
        font-size: 26px;
        margin-right: 8px;
      }
      #navbarNotif .notif-badge {
        font-size: 14px;
        top: -10px;
        right: -10px;
      }
      #notifDropdown {
        min-width: 90vw;
        left: 5vw;
        right: auto;
        top: 40px;
        font-size: 15px;
      }
      .dropdown-menu {
        font-size: 15px;
      }
      .avatar-img, .avatar-icon {
        width: 38px;
        height: 38px;
        font-size: 32px;
      }
    }

    @media (max-width: 480px) {
      .header {
        padding: 6px 0;
      }
      .navmenu ul li a {
        padding: 14px 16px;
        font-size: 16px;
      }
      #navbarNotif {
        font-size: 24px;
      }
      #notifDropdown {
        min-width: 98vw;
        left: 1vw;
        top: 38px;
        font-size: 14px;
      }
      .avatar-img, .avatar-icon {
        width: 32px;
        height: 32px;
        font-size: 26px;
      }
    }
  </style>
<?php
function renderLayout($content, $script) {

global $pegawai;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" href="../../images/sikumbang.ico" type="image/x-icon">
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dashboard Pegawai - Sistem Kehumasan</title>
  <meta name="description" content="Sistem Manajemen Kehumasan dan Konten Media Sosial">
  <meta name="theme-color" content="#2196F3">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Kehumasan">

  

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

      /* ===== MANAJEMEN LINK CARDS ===== */
      .steps-item {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        background: #fff;
        border: 1.5px solid rgba(102, 126, 234, 0.1);
        transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        height: 100%;
      }

      .steps-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        z-index: 5;
      }

      .steps-item::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.04) 0%, transparent 100%);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.4s ease;
      }

      .steps-link:hover .steps-item {
        transform: translateY(-12px);
        border-color: rgba(102, 126, 234, 0.2);
        box-shadow: 0 20px 50px rgba(102, 126, 234, 0.15);
      }

      .steps-link:hover .steps-item::after {
        opacity: 1;
      }

      .steps-item .steps-image {
        overflow: hidden;
        border-radius: 18px 18px 0 0;
        height: 200px;
        position: relative;
      }

      .steps-item .steps-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
      }

      .steps-link:hover .steps-item .steps-image img {
        transform: scale(1.08);
      }

      .steps-item .steps-number {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.3rem;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
        z-index: 2;
      }

      .steps-link:hover .steps-item .steps-number {
        transform: scale(1.1) rotate(-5deg);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
      }

      .steps-item .steps-content {
        padding: 35px 24px 28px;
        flex: 1;
        display: flex;
        flex-direction: column;
        position: relative;
        z-index: 2;
      }

      .steps-item h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a2332;
        margin: 8px 0 12px 0;
        letter-spacing: -0.3px;
        transition: all 0.3s ease;
      }

      .steps-link:hover .steps-item h3 {
        color: #667eea;
      }

      .steps-item p {
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        color: rgba(26, 35, 50, 0.7);
        line-height: 1.6;
        margin: 0 0 20px 0;
        transition: all 0.3s ease;
      }

      .steps-link:hover .steps-item p {
        color: rgba(102, 126, 234, 0.8);
      }

      .steps-item .steps-features {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: auto;
      }

      .steps-item .feature-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        color: rgba(26, 35, 50, 0.65);
        transition: all 0.3s ease;
      }

      .steps-item .feature-item i {
        color: #667eea;
        transition: all 0.3s ease;
        flex-shrink: 0;
      }

      .steps-link:hover .steps-item .feature-item {
        color: rgba(102, 126, 234, 0.85);
      }

      .steps-link:hover .steps-item .feature-item i {
        color: #764ba2;
        transform: scale(1.15);
      }

      .steps-link {
        text-decoration: none;
        display: block;
        color: inherit;
      }
      
      .humas-card {
        position: relative;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
        background: #fff;
        border-radius: 20px;
        padding: 48px 30px;
        min-height: 360px;
        height: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 1.5px solid rgba(102, 126, 234, 0.1);
        transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      }

      /* Dokumentasi card tweaks */
      .dokumentasi-card { min-height: 440px; }
      .dokumentasi-card .overlay-list {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 8px;
        max-height: 320px;
        overflow-y: auto;
      }

      .humas-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        z-index: 5;
      }

      .humas-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.04) 0%, transparent 100%);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.4s ease;
      }

      .humas-card:hover {
        transform: translateY(-12px);
        border-color: rgba(102, 126, 234, 0.2);
        box-shadow: 0 20px 50px rgba(102, 126, 234, 0.15);
      }

      .humas-card:hover::after {
        opacity: 1;
      }

      .humas-card .icon {
        position: relative;
        z-index: 2;
        margin-bottom: 25px;
      }

      .humas-card .icon i {
        font-size: 3.2rem;
        color: #667eea;
        transition: all 0.4s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.12) 0%, rgba(102, 126, 234, 0.06) 100%);
        border-radius: 18px;
        border: 1.5px solid rgba(102, 126, 234, 0.15);
        animation: float-icon 3s ease-in-out infinite;
      }

      @keyframes float-icon {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
      }

      .humas-card:hover .icon i {
        color: #764ba2;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(102, 126, 234, 0.1) 100%);
        border-color: rgba(102, 126, 234, 0.3);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
      }

      .humas-card h4 {
        position: relative;
        z-index: 2;
        font-family: 'Poppins', sans-serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a2332;
        margin: 0 0 12px 0;
        letter-spacing: -0.3px;
        transition: all 0.3s ease;
      }

      .humas-card:hover h4 {
        color: #667eea;
      }

      .humas-card p {
        position: relative;
        z-index: 2;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        color: rgba(26, 35, 50, 0.7);
        line-height: 1.6;
        margin: 0;
        transition: all 0.3s ease;
      }

      .humas-card:hover p {
        color: rgba(102, 126, 234, 0.8);
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 16px;
        padding: 30px;
        text-align: center;
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.23, 1, 0.320, 1);
        z-index: 10;
        border-radius: 20px;
      }

      .humas-overlay::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.15) 0%, transparent 60%);
        pointer-events: none;
        border-radius: 20px;
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
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        padding: 12px 20px;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        position: relative;
        z-index: 11;
        font-family: 'Poppins', sans-serif;
      }

      .overlay-item i {
        font-size: 1.1rem;
        transition: all 0.3s ease;
      }

      .overlay-item:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 255, 255, 0.4);
      }

      .overlay-item:hover i {
        transform: scale(1.15);
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
    
    /* GRID CARD */
    .asset-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 24px;
      padding: 20px 0;
    }

    /* CARD */
    /* ===== MODERN ASSET CARD STYLING ===== */
    .asset-card {
      position: relative;
      height: 280px;
      border-radius: 20px;
      background: #ffffff;
      box-shadow: 0 10px 40px rgba(102, 126, 234, 0.08);
      overflow: hidden;
      transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
      align-items: center;
      border: 1.5px solid rgba(102, 126, 234, 0.08);
    }

    .asset-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f5576c 100%);
      z-index: 5;
    }

    .asset-card:hover {
      transform: translateY(-12px);
      box-shadow: 0 20px 60px rgba(102, 126, 234, 0.15);
      border-color: rgba(102, 126, 234, 0.2);
    }

    /* CONTENT */
    .card-content {
      height: 100%;
      padding: 35px 28px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 16px;
      text-align: center;
      position: relative;
      z-index: 2;
    }

    .card-content h4 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1.2rem;
      margin: 0;
      color: #1a2332;
      letter-spacing: -0.3px;
      transition: all 0.3s ease;
    }

    .asset-card:hover .card-content h4 {
      color: #667eea;
    }

    .card-content p {
      margin: 0;
      font-size: 0.95rem;
      color: rgba(26, 35, 50, 0.7);
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s ease;
    }

    .asset-card:hover .card-content p {
      color: rgba(102, 126, 234, 0.8);
    }

    /* ICON */
    .icon-circle {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
      margin: 0;
      transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
      box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    }

    .icon-circle.blue {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #fff;
    }

    .asset-card:hover .icon-circle {
      transform: scale(1.15) rotate(-5deg);
      box-shadow: 0 12px 32px rgba(102, 126, 234, 0.4);
    }

    /* OVERLAY */
    .card-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      flex-direction: column;
      align-items: stretch;
      justify-content: flex-end;
      opacity: 0;
      transform: scale(0.85) rotateY(15deg);
      transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
      z-index: 3;
    }

    .asset-card:hover .card-overlay {
      opacity: 1;
      transform: scale(1) rotateY(0deg);
    }

    /* OVERLAY MENU (STACKED VERTICALLY AT BOTTOM) */
    .overlay-menu {
      display: flex;
      width: 100%;
      flex-direction: column;
      gap: 0;
      overflow: hidden; /* prevent hover translations from causing horizontal overflow */
    }

    .overlay-menu a {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      text-decoration: none;
      color: #fff;
      font-weight: 700;
      font-size: 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
      font-family: 'Poppins', sans-serif;
      position: relative;
      overflow: hidden;
      min-height: 50px;
    }

    .overlay-menu a::after {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(255, 255, 255, 0.15);
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: -1;
    }

    .overlay-menu a:last-child {
      border-bottom: none;
    }

    .overlay-menu a:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: none; /* disable translation to avoid creating horizontal space */
    }

    .overlay-menu a:hover::after {
      opacity: 1;
    }

    /* ICON DI BUTTON - dinonaktifkan agar bisa menggunakan ikon kustom di dalam link */
    .overlay-menu a::before {
      content: none; /* nonaktifkan pseudo-elemen ikon default */
    }

    .overlay-menu a:hover::before {
      transform: none;
    }
      
    }

     

      /* ===== PENGEMBANGAN SECTION ===== */
      .pengembangan-highlight {
        position: relative;
        padding: 80px 0;
      }

      .pengembangan-card {
        position: relative;
        border-radius: 24px;
        background: #ffffff; /* solid white background */
        box-shadow: 0 12px 40px rgba(8,20,60,0.05);
        overflow: hidden;
        transition: all 0.45s cubic-bezier(0.23, 1, 0.320, 1);
        display: flex;
        flex-direction: column;
        align-items: stretch;
        justify-content: space-between;
        padding: 0;
        border: 1px solid rgba(0,0,0,0.03);
        text-align: center;
        gap: 0;
        min-height: 340px; /* keep the original length */
      }

      /* Header (big gradient block) */
      .card-header-main {
        background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
        color: #fff;
        padding: 42px 28px 28px;
        border-top-left-radius: 24px;
        border-top-right-radius: 24px;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
      }

      .icon-wrap{ position: relative; }
      .icon-circle{
        width: 92px;
        height: 92px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        display:flex;align-items:center;justify-content:center;
        box-shadow: 0 18px 40px rgba(79,70,229,0.12);
        font-size: 36px;
        transition: transform 0.36s cubic-bezier(0.2,0.9,0.3,1), box-shadow 0.36s ease, opacity 0.36s ease;
        will-change: transform, box-shadow, opacity;
      }

      .card-header-main h3{ margin:0; font-size:1.35rem; font-weight:800; color:#fff; }
      .card-header-main p{ margin:0; font-size:0.98rem; color: rgba(255,255,255,0.92); max-width:760px }

      /* Small slider dots centered */
      .card-dots{ padding: 18px 0; display:flex; align-items:center; justify-content:center; gap:10px; background: linear-gradient(180deg, rgba(0,0,0,0.02) 0%, rgba(0,0,0,0) 100%);} 
      .card-dots span{ width:10px; height:10px; border-radius:50%; background: rgba(255,255,255,0.7); opacity:0.55; box-shadow: 0 4px 10px rgba(10,20,60,0.04); }
      .card-dots span:nth-child(2){ width:12px; height:12px; background:#4f46e5; box-shadow: 0 6px 16px rgba(79,70,229,0.18); opacity:1 }

      /* Footer CTA */
      .card-footer-cta{ display:flex; align-items:center; justify-content:space-between; padding:18px 22px; background:#fff; border-bottom-left-radius:24px; border-bottom-right-radius:24px; }
      .card-footer-cta .btn-explore{ color:#4f46e5; font-weight:700; text-decoration:none; }
      .cta-circle{ width:44px; height:44px; border-radius:50%; background: linear-gradient(135deg,#4f46e5 0%, #06b6d4 100%); display:flex; align-items:center; justify-content:center; color:#fff; box-shadow: 0 10px 30px rgba(79,70,229,0.12); text-decoration:none; transition: transform 0.32s cubic-bezier(0.2,0.9,0.3,1), box-shadow 0.32s ease; will-change: transform, box-shadow; }

      .pengembangan-card:hover{ transform: translateY(-14px) scale(1.02); box-shadow: 0 40px 140px rgba(79,70,229,0.18), 0 8px 30px rgba(6,182,212,0.06); }

      .pengembangan-card::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 24px;
        pointer-events: none;
        box-shadow: 0 0 0 rgba(79,70,229,0);
        opacity: 0;
        transition: box-shadow 0.45s ease, opacity 0.45s ease, transform 0.45s ease;
        z-index: 2;
      }

      .pengembangan-card:hover::after {
        box-shadow: 0 30px 80px rgba(79,70,229,0.08), 0 0 60px rgba(6,182,212,0.04);
        opacity: 1;
        transform: translateY(-6px);
      }

      /* Icon & CTA hover emphasis */
      .pengembangan-card:hover .icon-circle {
        transform: translateY(-6px) scale(1.12);
        box-shadow: 0 28px 80px rgba(79,70,229,0.2);
      }

      .pengembangan-card:hover .cta-circle {
        transform: translateX(6px) scale(1.06);
        box-shadow: 0 16px 40px rgba(79,70,229,0.16);
      }

      @media (max-width: 768px){
        .card-header-main{ padding:28px 18px 22px }
        .icon-circle{ width:70px; height:70px; font-size:28px }
        .card-header-main h3{ font-size:1.15rem }
        .card-dots{ padding:12px 0 }
        .card-footer-cta{ padding:14px 16px }
      }
      .pengembangan-item::before {
        content: '';
        position: absolute;
        width: 40px;
        height: 40px;
        background: radial-gradient(circle at 30% 30%, rgba(79, 70, 229, 0.18), rgba(79, 70, 229, 0.05));
        border-radius: 50%;
        top: 10%;
        left: 10%;
        animation: bubble-float 4s ease-in-out infinite;
        pointer-events: none;
        z-index: 0;
      }

      .pengembangan-item::after {
        content: '';
        position: absolute;
        width: 25px;
        height: 25px;
        background: radial-gradient(circle at 30% 30%, rgba(6, 182, 212, 0.12), rgba(6, 182, 212, 0.03));
        border-radius: 50%;
        bottom: 15%;
        right: 12%;
        animation: bubble-float 5.5s ease-in-out infinite reverse;
        pointer-events: none;
        z-index: 0;
      }

      @keyframes bubble-float {
        0%, 100% {
          transform: translateY(0px) translateX(0px);
          opacity: 0.6;
        }
        25% {
          transform: translateY(-20px) translateX(10px);
          opacity: 0.4;
        }
        50% {
          transform: translateY(-40px) translateX(0px);
          opacity: 0.7;
        }
        75% {
          transform: translateY(-20px) translateX(-10px);
          opacity: 0.4;
        }
      }

      .pengembangan-card .card-icon {
        width: 132px;
        height: 132px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
        color: #fff;
        margin: 0;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
        box-shadow: 0 32px 80px rgba(79, 70, 229, 0.12), 0 6px 18px rgba(79,70,229,0.06);
        position: relative;
        z-index: 3;
        transform: translateY(-10px);
      }

      .pengembangan-card:hover .card-icon {
        transform: translateY(-12px) scale(1.06) rotate(-6deg);
        box-shadow: 0 38px 120px rgba(79, 70, 229, 0.14);
      }

      .pengembangan-card h3 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 800;
        color: #ffffff; /* white title */
        letter-spacing: -0.3px;
        transition: all 0.3s ease;
        line-height: 1.3;
        position: relative;
        z-index: 3;
        margin-top: 6px;
      }



      .pengembangan-card p {
        margin: 0;
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.95); /* white description */
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
        position: relative;
        z-index: 3;
        line-height: 1.7;
        max-width: 820px;
      }



      /* CTA link centered and subtle like sample */
      .pengembangan-card .btn-explore {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #4f46e5;
        text-decoration: none;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.3s ease;
        margin-top: 18px;
        padding: 0;
      }

      .pengembangan-card .btn-explore i { transition: transform 0.3s ease; }
      .pengembangan-card .btn-explore:hover { transform: translateX(6px); opacity: 0.95; color: inherit; }


      .pengembangan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 24px;
        padding: 20px 0;
      }

      .pengembangan-item {
        width: 100%;
      }

      .btn-view-all {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f5576c 100%);
        color: #fff;
        padding: 16px 40px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
        box-shadow: 0 12px 40px rgba(102, 126, 234, 0.35);
        border: none;
        cursor: pointer;
      }

      .btn-view-all:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.45);
      }

      .btn-view-all i {
        transition: transform 0.3s ease;
      }

      .btn-view-all:hover i {
        transform: translateX(4px);
      }

      /* Remove white space */
      html, body {
        background: transparent !important;
        margin: 0 !important;
        padding: 0 !important;
      }

      #scroll-top {
        display: none !important;
      }

      /* Footer Styling */
      #footer {
        background-color: #37517e;
        color: #ffffff;
      }

      /* Responsive */
      @media (max-width: 768px) {
        #footer {
          padding: 50px 0 20px;
        }

        #footer .footer-top {
          padding-bottom: 30px;
        }

        #footer .row {
          gap: 30px;
        }

        #footer .footer-about {
          padding-right: 0;
          color: #fff;
        }

        #footer .sitename {
          font-size: 1.2rem;
          color: #fff;
        }

        #footer .footer-contact p {
          font-size: 0.9rem;
          color: #fff;
        }

        #footer .footer-links h4 {
          font-size: 1rem;
          margin-bottom: 20px;
          color: #fff;

        }

        #footer .footer-links ul li {
          margin-bottom: 12px;
          color: #fff;
        }

        #footer > .container .col-lg-4:nth-child(4) p {
          font-size: 0.9rem;
          margin-bottom: 20px;
          color: #fff;
        }

        #footer .social-links {
          justify-content: flex-start;
          color: #fff;
        }

        #footer .copyright p {
          font-size: 0.85rem;
          color: #fff;
        }
      }

    
    .avatar-img{width:44px;height:44px;object-fit:cover;border-radius:50%;display:inline-block}
    
    .user-area{margin-left:6rem}
    @media (max-width:1199px){.user-area{margin-left:4rem}}
    @media (max-width:991px){.user-area{margin-left:1rem}}
    
    /* ===== NAVBAR LOGO STYLING ===== */
    .logo {
      transition: all 0.3s ease;
    }

    .logo h5 {
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: 18px;
      letter-spacing: -0.5px;
      color: #ffffff;
      margin: 0;
      transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
    }

    .logo:hover h5 {
      color: #375d84;
      transform: scale(1.05);
    }

    .logo:hover h5 {
      transform: scale(1.05);
      filter: brightness(1.15);
    }

    /* ===== USER SECTION STYLING (MODERN) ===== */
    .ms-3 {
      transition: all 0.3s ease;
    }

    .dropdown-toggle {
      transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1) !important;
      position: relative;
      padding: 8px 12px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
      color: #fff !important;
    }

    .dropdown-toggle::after {
      border: none;
      width: 16px;
      height: 16px;
      background: rgba(255, 255, 255, 0.6);
      -webkit-mask-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="6 9 12 15 18 9"></polyline></svg>');
      mask-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="6 9 12 15 18 9"></polyline></svg>');
      transition: all 0.3s ease;
    }

    .dropdown-toggle:hover {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(245, 87, 108, 0.08) 100%);
      border: 1.5px solid rgba(102, 126, 234, 0.2);
      box-shadow: 0 4px 16px rgba(102, 126, 234, 0.12);
    }

    .dropdown-toggle:hover::after {
      background: rgba(102, 126, 234, 0.8);
      transform: translateY(-2px);
    }

    .dropdown-toggle:active {
      transform: scale(0.95);
    }

    .dropdown-toggle.show {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(245, 87, 108, 0.1) 100%);
      border: 1.5px solid rgba(102, 126, 234, 0.3);
    }

    .dropdown-toggle.show::after {
      background: #667eea;
      transform: translateY(2px) rotate(180deg);
    }

    .avatar-img {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 2px solid rgba(255, 255, 255, 0.3);
      transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
      object-fit: cover;
      background: rgba(102, 126, 234, 0.1);
    }

    .dropdown-toggle:hover .avatar-img {
      border-color: rgba(102, 126, 234, 0.5);
      box-shadow: 0 6px 16px rgba(102, 126, 234, 0.25);
      transform: scale(1.12) rotate(5deg);
    }

    .avatar-icon {
      font-size: 18px;
      color: rgba(255, 255, 255, 0.9);
      transition: all 0.3s ease;
    }

    .dropdown-toggle:hover .avatar-icon {
      color: #667eea;
      transform: scale(1.15);
    }

    /* ===== NAVBAR LINK HOVER EFFECT (FOOTER STYLE) ===== */
    .navmenu {
      padding-right: 3rem;
    }

    .navmenu a {
      position: relative;
      text-decoration: none;
      color: #ffffff;
      font-weight: 600;
      transition: all 0.3s ease;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    }

    .navmenu a::before {
      content: '';
      position: absolute;
      bottom: -3px;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
      transition: width 0.3s ease;
      border-radius: 2px;
    }

    .navmenu a:hover {
      color: #ffffff;
      text-shadow: 0 2px 10px rgba(102, 126, 234, 0.5);
    }

    .navmenu a:hover::before {
      width: 100%;
    }

    .navmenu a.active {
      color: #ffffff;
      text-shadow: 0 2px 10px rgba(102, 126, 234, 0.5);
    }

    .navmenu a.active::before {
      width: 100%;
    }

    /* ===== FOOTER LINKS STYLING (SIMILAR TO NAVBAR) ===== */
    #footer .footer-links ul li a {
      position: relative;
      text-decoration: none;
      color: #ffffff;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    #footer .footer-links ul li a::before {
      content: '';
      position: absolute;
      bottom: -3px;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
      transition: width 0.3s ease;
      border-radius: 2px;
    }

    #footer .footer-links ul li a:hover {
      color: #ffffff;
    }

    #footer .footer-links ul li a:hover::before {
      width: 100%;
    }

    /* Mobile navbar text optimization */
    @media (max-width: 991px) {
      .navmenu a {
        color: #1a2332;
        font-weight: 700;
        text-shadow: none;
        font-size: 14px;
      }

      .navmenu a:hover {
        color: #667eea;
        text-shadow: none;
      }

      .navmenu a.active {
        color: #667eea;
        text-shadow: none;
      }
    }

    @media (max-width: 768px) {
      .navmenu a {
        color: #1a2332;
        font-weight: 700;
        text-shadow: none;
        font-size: 13px;
      }

      .navmenu a:hover {
        color: #667eea;
        text-shadow: none;
      }

      .navmenu a.active {
        color: #667eea;
        text-shadow: none;
      }
    }

    @media (max-width: 480px) {
      .navmenu a {
        color: #1a2332;
        font-weight: 700;
        text-shadow: none;
        font-size: 12px;
      }

      .navmenu a:hover {
        color: #667eea;
        text-shadow: none;
      }

      .navmenu a.active {
        color: #667eea;
        text-shadow: none;
      }
    }

    /* ===== BROADCAST SECTION V3 ===== */
    
    .broadcast section {
      position: relative;
      overflow: hidden;
    }

    /* Animated Background */
    .broadcast-bg-animated {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      z-index: 0;
      pointer-events: none;
    }

    .broadcast-bg-animated::before {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(102, 126, 234, 0.15) 0%, transparent 70%);
      border-radius: 50%;
      top: -100px;
      right: -100px;
      animation: float-bg 8s ease-in-out infinite;
    }

    .broadcast-bg-animated::after {
      content: '';
      position: absolute;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(245, 87, 108, 0.1) 0%, transparent 70%);
      border-radius: 50%;
      bottom: -50px;
      left: -50px;
      animation: float-bg-alt 10s ease-in-out infinite;
    }

    @keyframes float-bg {
      0%, 100% { transform: translate(0, 0); }
      50% { transform: translate(40px, 40px); }
    }

    @keyframes float-bg-alt {
      0%, 100% { transform: translate(0, 0); }
      50% { transform: translate(-30px, -30px); }
    }

    /* Broadcast Link */
    .broadcast-link {
      text-decoration: none;
      display: block;
      position: relative;
      z-index: 1;
    }

    /* Card V3 */
    .broadcast-card-v3 {
      background: white;
      border-radius: 24px;
      overflow: hidden;
      position: relative;
      transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
      height: 100%;
      display: flex;
      flex-direction: column;
      border: 1.5px solid rgba(102, 126, 234, 0.08);
    }

    .broadcast-card-v3.variant-pink {
      border-color: rgba(245, 87, 108, 0.08);
    }

    /* Glow Effect on Hover */
    .card-glow {
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at center, rgba(102, 126, 234, 0.08) 0%, transparent 70%);
      opacity: 0;
      transition: opacity 0.5s ease;
      pointer-events: none;
      border-radius: 24px;
    }

    .broadcast-card-v3.variant-pink .card-glow {
      background: radial-gradient(circle at center, rgba(245, 87, 108, 0.08) 0%, transparent 70%);
    }

    .broadcast-card-v3:hover .card-glow {
      opacity: 1;
    }

    .broadcast-card-v3:hover {
      transform: translateY(-16px);
      border-color: rgba(102, 126, 234, 0.15);
      box-shadow: 
        0 20px 60px rgba(102, 126, 234, 0.12),
        0 0 40px rgba(102, 126, 234, 0.08);
    }

    .broadcast-card-v3.variant-pink:hover {
      border-color: rgba(245, 87, 108, 0.15);
      box-shadow: 
        0 20px 60px rgba(245, 87, 108, 0.12),
        0 0 40px rgba(245, 87, 108, 0.08);
    }

    /* Accent Line */
    .accent-line {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, transparent, #667eea, transparent);
      z-index: 5;
    }

    .broadcast-card-v3.variant-pink .accent-line {
      background: linear-gradient(90deg, transparent, #f5576c, transparent);
    }

    /* Card Header V3 */
    .card-header-v3 {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 60px 40px 50px;
      position: relative;
      overflow: hidden;
      text-align: center;
      z-index: 2;
    }

    .broadcast-card-v3.variant-pink .card-header-v3 {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    /* Header BG Gradient Overlay */
    .header-bg-gradient {
      position: absolute;
      inset: 0;
      background: 
        radial-gradient(circle at top right, rgba(255, 255, 255, 0.15) 0%, transparent 60%),
        radial-gradient(circle at bottom left, rgba(0, 0, 0, 0.1) 0%, transparent 60%);
      pointer-events: none;
    }

    /* Icon Container */
    .icon-container {
      position: relative;
      z-index: 3;
      margin-bottom: 25px;
      display: inline-block;
    }

    .icon-bg {
      position: absolute;
      inset: -20px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      animation: pulse-icon 3s ease-in-out infinite;
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .icon-container i {
      position: relative;
      z-index: 2;
      font-size: 3.5rem;
      color: white;
      display: block;
      width: 80px;
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: bounce-icon 2.5s ease-in-out infinite;
    }

    @keyframes pulse-icon {
      0%, 100% { 
        transform: scale(1);
        opacity: 1;
      }
      50% { 
        transform: scale(1.15);
        opacity: 0.6;
      }
    }

    @keyframes bounce-icon {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
    }

    .broadcast-card-v3:hover .icon-bg {
      animation: pulse-icon-hover 0.6s ease-out;
    }

    @keyframes pulse-icon-hover {
      0% { transform: scale(1); opacity: 1; }
      100% { transform: scale(1.3); opacity: 0; }
    }

    /* Header Text */
    .card-header-v3 h3 {
      font-family: 'Poppins', sans-serif;
      font-size: 1.7rem;
      font-weight: 800;
      color: white;
      margin: 0 0 15px 0;
      letter-spacing: -0.5px;
      position: relative;
      z-index: 3;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .card-header-v3 p {
      font-family: 'Poppins', sans-serif;
      font-size: 0.95rem;
      color: rgba(255, 255, 255, 0.92);
      margin: 0;
      line-height: 1.6;
      position: relative;
      z-index: 3;
      font-weight: 500;
      max-width: 280px;
      margin-left: auto;
      margin-right: auto;
    }

    /* Card Features */
    .card-features {
      padding: 30px 40px;
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.02) 0%, rgba(102, 126, 234, 0) 100%);
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      border-bottom: 1px solid rgba(102, 126, 234, 0.06);
      position: relative;
      z-index: 2;
    }

    .broadcast-card-v3.variant-pink .card-features {
      background: linear-gradient(135deg, rgba(245, 87, 108, 0.02) 0%, rgba(245, 87, 108, 0) 100%);
      border-bottom-color: rgba(245, 87, 108, 0.06);
    }

    .feature-list {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .feature-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #667eea;
      animation: pulse-dot 1.8s ease-in-out infinite;
      box-shadow: 0 0 10px rgba(102, 126, 234, 0.4);
    }

    .broadcast-card-v3.variant-pink .feature-dot {
      background: #f5576c;
      box-shadow: 0 0 10px rgba(245, 87, 108, 0.4);
    }

    .feature-dot:nth-child(1) { animation-delay: 0s; }
    .feature-dot:nth-child(2) { animation-delay: 0.2s; }
    .feature-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes pulse-dot {
      0%, 100% { 
        transform: scale(1);
        opacity: 1;
      }
      50% { 
        transform: scale(1.4);
        opacity: 0.4;
      }
    }

    /* Card Footer CTA */
    .card-footer-cta {
      padding: 28px 40px;
      background: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 16px;
      cursor: pointer;
      position: relative;
      z-index: 2;
      transition: all 0.3s ease;
      border-top: 1px solid rgba(102, 126, 234, 0.05);
    }

    .broadcast-card-v3.variant-pink .card-footer-cta {
      border-top-color: rgba(245, 87, 108, 0.05);
    }

    .broadcast-link:hover .card-footer-cta {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.04) 0%, rgba(102, 126, 234, 0.01) 100%);
    }

    .broadcast-card-v3.variant-pink:hover .card-footer-cta {
      background: linear-gradient(135deg, rgba(245, 87, 108, 0.04) 0%, rgba(245, 87, 108, 0.01) 100%);
    }

    .cta-text {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1rem;
      letter-spacing: 0.3px;
      color: #667eea;
      transition: all 0.3s ease;
    }

    .broadcast-card-v3.variant-pink .cta-text {
      color: #f5576c;
    }

    .broadcast-link:hover .cta-text {
      letter-spacing: 0.8px;
    }

    /* Arrow Icon */
    .arrow-icon {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.2rem;
      transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .broadcast-card-v3.variant-pink .arrow-icon {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
    }

    .broadcast-link:hover .arrow-icon {
      transform: translateX(6px) scale(1.1);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .broadcast-card-v3.variant-pink:hover .arrow-icon {
      box-shadow: 0 8px 25px rgba(245, 87, 108, 0.4);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .card-header-v3 {
        padding: 45px 30px 40px;
      }

      .card-header-v3 h3 {
        font-size: 1.5rem;
      }

      .card-header-v3 p {
        font-size: 0.9rem;
      }

      .icon-container i {
        font-size: 3rem;
        width: 70px;
        height: 70px;
      }

      .icon-bg {
        inset: -15px;
      }

      .card-features {
        padding: 25px 30px;
      }

      .card-footer-cta {
        padding: 25px 30px;
      }

      .cta-text {
        font-size: 0.95rem;
      }

      .arrow-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
      }
    }

    @media (max-width: 480px) {
      .broadcast-card-v3 {
        border-radius: 18px;
      }

      .card-header-v3 {
        padding: 40px 20px 30px;
      }

      .card-header-v3 h3 {
        font-size: 1.3rem;
        margin-bottom: 12px;
      }

      .card-header-v3 p {
        font-size: 0.85rem;
      }

      .icon-container i {
        font-size: 2.5rem;
        width: 60px;
        height: 60px;
      }

      .card-features {
        padding: 20px;
      }

      .card-footer-cta {
        padding: 20px;
      }

      .arrow-icon {
        width: 36px;
        height: 36px;
        font-size: 0.9rem;
      }
    }
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
          <li><a href="#dokumentasi">Dokumentasi</a></li>
          <li><a href="#sumberdaya">Sumber Daya</a></li>
          <li><a href="#broadcast">Broadcast</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

        <!-- Bell Notifikasi Jadwal Deadline 1-2 Hari -->
        <span id="navbarNotif" style="display:none;position:relative;margin-right:18px;">
          <i class="bi bi-bell-fill"></i>
          <span class="notif-badge" id="notifCount">1</span>
          <div id="notifDropdown" class="dropdown-menu dropdown-menu-end" style="min-width:260px;max-width:320px;display:none;position:absolute;top:32px;right:0;z-index:9999;">
            <h6 class="dropdown-header">Jadwal Deadline Mendekat</h6>
            <div id="notifList"></div>
          </div>
        </span>
        <div class="ms-3">
        <div class="dropdown">
          <a class="d-flex align-items-center text-decoration-none dropdown-toggle"
            href="#"
            id="userDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            style="color:#fff">

            <?php
              $foto = $_SESSION['pegawai']['foto_profil'] ?? '';

              if (!empty($foto) && file_exists(__DIR__ . '/../uploads/' . $foto)) {
            ?>
                <img src="../uploads/<?= htmlspecialchars($foto); ?>"
                    class="avatar-img me-2"
                    alt="User">
            <?php } else { ?>
                <i class="bi bi-person-circle avatar-icon me-2"></i>
            <?php } ?>

            <i class="ti-angle-down ms-1"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow mt-2" aria-labelledby="userDropdown">

           <!-- Header Pegawai + Foto -->
            <li>
              <div class="px-3 py-3 border-bottom text-center">

                <h6 class="mb-0 fw-bold"><?= $_SESSION['pegawai']['nama']; ?></h6>
                <small class="text-muted"><?= $_SESSION['role']; ?></small><br>
                <small><?= $_SESSION['pegawai']['email']; ?></small>

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
          <a href="#" class="d-flex align-items-center" style="display: flex; text-decoration: none; color: #ffffff;">
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
            <li><i class="bi bi-chevron-right"></i> <a href="#beranda" style="color: #ffffff; text-decoration: none;">Beranda</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#Humas" style="color: #ffffff; text-decoration: none;">Ruang Humas</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#dokumentasi" style="color: #ffffff; text-decoration: none;">Dokumentasi</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#sumberdaya" style="color: #ffffff; text-decoration: none;">Sumber Daya</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#broadcast" style="color: #ffffff; text-decoration: none;">Broadcast</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Layanan Informasi</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#kalender-jadwal" style="color: #ffffff; text-decoration: none;">Jadwal Konten Humas</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#dokumentasi" style="color: #ffffff; text-decoration: none;">Galeri Foto</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#dokumentasi" style="color: #ffffff; text-decoration: none;">Galeri Video</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#dokumentasi" style="color: #ffffff; text-decoration: none;">Laporan Humas</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#sumberdaya" style="color: #ffffff; text-decoration: none;">Pedoman Visual Medsos</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12" style="text-align: center;">
          <img src="../images/sikumbang.png" alt="Sikumbang" class="img-fluid" style="max-width: 180px; width: 100%;">
          <h4 class="mt-3" style="color: #ffffff;">SIKUMBANG</h4>
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