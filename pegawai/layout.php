<?php
function renderLayout($content, $script) {

global $pegawai;
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
        padding: 40px 30px;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 1.5px solid rgba(102, 126, 234, 0.1);
        transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
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
      align-items: stretch;
      justify-content: stretch;
      opacity: 0;
      transform: scale(0.85) rotateY(15deg);
      transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
      z-index: 3;
    }

    .asset-card:hover .card-overlay {
      opacity: 1;
      transform: scale(1) rotateY(0deg);
    }

    /* OVERLAY MENU (FULL HEIGHT BUTTON) */
    .overlay-menu {
      display: flex;
      width: 100%;
      height: 100%;
      flex-direction: row;
      gap: 0;
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
      border-right: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
      font-family: 'Poppins', sans-serif;
      position: relative;
      overflow: hidden;
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
      border-right: none;
    }

    .overlay-menu a:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateX(8px);
    }

    .overlay-menu a:hover::after {
      opacity: 1;
    }

    /* ICON DI BUTTON */
    .overlay-menu a::before {
      content: "\F392"; /* bi-folder-fill */
      font-family: "Bootstrap-icons";
      font-size: 1.3rem;
      transition: all 0.3s ease;
    }

    .overlay-menu a:hover::before {
      transform: scale(1.2);
    }
      
    }

      /* ===== MODERN FOOTER STYLE ===== */
      #footer {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #172554 100%) !important;
        background-color: #0f172a !important;
        color: #fff !important;
        position: relative;
        overflow: hidden;
        padding: 60px 0 0;
      }

      .footer {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #172554 100%) !important;
        background-color: #0f172a !important;
      }

      #footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        pointer-events: none;
      }

      #footer::after {
        content: '';
        position: absolute;
        bottom: -10%;
        left: -5%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(102, 126, 234, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        animation: float-footer 10s ease-in-out infinite;
        pointer-events: none;
      }

      @keyframes float-footer {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(40px, 40px); }
      }

      #footer .container {
        position: relative;
        z-index: 2;
      }

      #footer .footer-top {
        padding-bottom: 40px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      }

      #footer .row {
        gap: 30px;
        display: flex;
        flex-wrap: wrap;
      }

      @media (min-width: 992px) {
        #footer .row {
          flex-wrap: nowrap;
        }
      }

      /* Footer About */
      #footer .footer-about {
        padding-right: 30px;
      }

      #footer .sitename {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.3rem;
        letter-spacing: -0.5px;
        color: #fff;
      }

      #footer .footer-contact {
        font-family: 'Poppins', sans-serif;
      }

      #footer .footer-contact p {
        margin-bottom: 12px;
        font-size: 0.95rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.8);
      }

      #footer .footer-contact strong {
        color: #667eea;
        font-weight: 700;
      }

      #footer .footer-contact span {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
      }

      /* Footer Links */
      #footer .footer-links h4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 25px;
        letter-spacing: -0.3px;
        position: relative;
        padding-bottom: 12px;
        color: #fff;
      }

      #footer .footer-links h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
        transition: width 0.3s ease;
      }

      #footer .footer-links h4:hover::after {
        width: 60px;
      }

      #footer .footer-links ul {
        list-style: none;
        padding: 0;
        margin: 0;
      }

      #footer .footer-links ul li {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
      }

      #footer .footer-links ul li:hover {
        transform: translateX(6px);
      }

      #footer .footer-links ul li i {
        color: #667eea;
        font-size: 1rem;
        transition: all 0.3s ease;
        flex-shrink: 0;
      }

      #footer .footer-links ul li:hover i {
        color: #764ba2;
        transform: scaleX(1.2);
      }

      #footer .footer-links ul li a {
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
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
      }

      #footer .footer-links ul li a:hover {
        color: #fff;
      }

      #footer .footer-links ul li a:hover::before {
        width: 100%;
      }

      /* Follow Us Section */
      #footer > .container .col-lg-4:nth-child(4) h4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 18px;
        letter-spacing: -0.3px;
        color: #fff;
      }

      #footer > .container .col-lg-4:nth-child(4) p {
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 20px;
      }

      /* Social Links */
      #footer .social-links {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
      }

      #footer .social-links a {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(102, 126, 234, 0.12);
        color: #667eea;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
        border: 1.5px solid rgba(102, 126, 234, 0.25);
        cursor: pointer;
        text-decoration: none;
      }

      #footer .social-links a:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        transform: translateY(-8px) scale(1.1);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.3);
        border-color: transparent;
      }

      /* Copyright */
      #footer .copyright {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #172554 100%) !important;
        text-align: center;
        padding-top: 30px !important;
        padding-bottom: 40px !important;
        position: relative;
        z-index: 2;
      }

      #footer .copyright p {
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7);
        margin: 0;
        font-weight: 500;
      }

      #footer .copyright span {
        color: rgba(255, 255, 255, 0.85);
      }

      #footer .credits {
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 10px;
        font-weight: 500;
      }

      #footer .credits strong {
        color: #667eea;
        transition: all 0.3s ease;
      }

      #footer .credits:hover strong {
        color: #764ba2;
      }

      /* ===== PENGEMBANGAN SECTION ===== */
      .pengembangan-highlight {
        position: relative;
        padding: 80px 0;
      }

      .pengembangan-card {
        position: relative;
        height: 340px;
        border-radius: 24px;
        background: linear-gradient(135deg, #ffffff 0%, #f5f7ff 100%);
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.15);
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px 36px;
        border: 1.5px solid rgba(102, 126, 234, 0.12);
        text-align: center;
        gap: 16px;
      }

      .pengembangan-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f5576c 100%);
        z-index: 5;
      }

      .pengembangan-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.25);
        border-color: rgba(102, 126, 234, 0.2);
      }

      .pengembangan-item::before {
        content: '';
        position: absolute;
        width: 40px;
        height: 40px;
        background: radial-gradient(circle at 30% 30%, rgba(102, 126, 234, 0.2), rgba(102, 126, 234, 0.05));
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
        background: radial-gradient(circle at 30% 30%, rgba(245, 87, 108, 0.15), rgba(245, 87, 108, 0.03));
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
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f5576c 100%);
        color: #fff;
        margin: 0;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
        box-shadow: 0 12px 40px rgba(102, 126, 234, 0.35);
        position: relative;
        z-index: 3;
      }

      .pengembangan-card:hover .card-icon {
        transform: scale(1.15) rotate(-8deg);
        box-shadow: 0 16px 48px rgba(102, 126, 234, 0.45);
      }

      .pengembangan-card h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a2332;
        letter-spacing: -0.3px;
        transition: all 0.3s ease;
        line-height: 1.4;
        position: relative;
        z-index: 3;
      }

      .pengembangan-card:hover h3 {
        color: #667eea;
      }

      .pengembangan-card p {
        margin: 0;
        font-size: 0.95rem;
        color: rgba(26, 35, 50, 0.65);
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
        position: relative;
        z-index: 3;
        line-height: 1.6;
      }

      .pengembangan-card:hover p {
        color: rgba(102, 126, 234, 0.85);
      }

      .pengembangan-card .btn-explore {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
        margin-top: auto;
        padding: 10px 20px;
      }

      .pengembangan-card .btn-explore:hover {
        color: #764ba2;
        gap: 12px;
      }

      .pengembangan-card .btn-explore i {
        transition: transform 0.3s ease;
      }

      .pengembangan-card .btn-explore:hover i {
        transform: translateX(4px);
      }

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
        }

        #footer .sitename {
          font-size: 1.2rem;
        }

        #footer .footer-contact p {
          font-size: 0.9rem;
        }

        #footer .footer-links h4 {
          font-size: 1rem;
          margin-bottom: 20px;
        }

        #footer .footer-links ul li {
          margin-bottom: 12px;
        }

        #footer > .container .col-lg-4:nth-child(4) p {
          font-size: 0.9rem;
          margin-bottom: 20px;
        }

        #footer .social-links {
          justify-content: flex-start;
        }

        #footer .copyright p {
          font-size: 0.85rem;
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
      align-items: center;
      justify-content: space-between;
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
          <li><a href="#portfolio">Dokumentasi</a></li>
          <li><a href="#team">Pengembangan</a></li>
          <li><a href="#sumberdaya">Sumber Daya</a></li>
          <li><a href="#broadcast">Broadcast</a></li>
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
          <p>Ikuti kami untuk informasi dan publikasi terbaru dari<br><span>BPS Bangkalan.</span></p>
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