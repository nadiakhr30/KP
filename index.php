<?php
session_start();
if (isset($_SESSION["pegawai"])) {
    if ($_SESSION["role"] === "Admin") {
        header("Location: admin/index.php");
    } elseif ($_SESSION["role"] === "Pegawai") {
        header("Location: pegawai/index.php");
    }
    exit;
}

include_once("functions.php");

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  checkLogin($_POST, $errors);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Log In - Sistem Kehumasan</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="author" content="Kamila - Nadia" />
      <meta name="description" content="Sistem Manajemen Kehumasan dan Konten Media Sosial">
      <meta name="theme-color" content="#2196F3">
      <meta name="mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="default">
      <meta name="apple-mobile-web-app-title" content="Kehumasan">
      <link rel="icon" href="admin/assets/images/logo_bps.ico" type="image/x-icon">
      <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
      <link rel="manifest" href="manifest.json">
      <!-- Google font-->     
      <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
      <!-- Required Fremwork -->
      <link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap/css/bootstrap.min.css">
      <!-- waves.css -->
      <link rel="stylesheet" href="admin/assets/pages/waves/css/waves.min.css" type="text/css" media="all">
      <!-- themify-icons line icon -->
      <link rel="stylesheet" type="text/css" href="admin/assets/icon/themify-icons/themify-icons.css">
      <!-- ico font -->
      <link rel="stylesheet" type="text/css" href="admin/assets/icon/icofont/css/icofont.css">
      <!-- Font Awesome -->
      <link rel="stylesheet" type="text/css" href="admin/assets/icon/font-awesome/css/font-awesome.min.css">
      <!-- Style.css -->
      <link rel="stylesheet" type="text/css" href="admin/assets/css/style.css">
  </head>

  <body style="
background: linear-gradient(rgba(30, 107, 216, 0.65), rgba(30, 107, 216, 0.65)), url('pegawai/assets/img/bg/BPS_BKL.png') no-repeat center center fixed;
background-size: cover;
">

<section class="login-wrapper">
  <div class="login-card">

    <!-- KIRI -->
    <div class="left-panel">
      <div class="brand">
        <img src="images/bps.png" alt="BPS Logo">
      </div>

      <div class="welcome-text">
        <h1>Selamat<br>Datang!</h1>
        <h6>Sistem Informasi Kehumasan BPS Kabupaten Bangkalan</h6>
      </div>
    </div>

    <!-- KANAN -->
    <div class="right-panel">
      <form method="POST">

        <h3>Login</h3>

        <input type="email" name="email" placeholder="Email address" required>
        
        <div class="password-wrapper">
          <input type="password" id="password" name="password" placeholder="Password" required>
          <i class="fa fa-eye-slash toggle-password" onclick="togglePassword()"></i>
        </div>

        <?php if(!empty($errors)): ?>
        <div class="error-box">
          <?php foreach($errors as $e) echo $e . "<br>"; ?>
        </div>
        <?php endif; ?>

        <button type="submit" class="btn-login">Login</button>

        <p class="signup-text">
          Belum Punya akun? Hubungi Admin.</a>
        </p>

      </form>
    </div>

  </div>
</section>

<style>
*{
  box-sizing:border-box;
  font-family: Poppins, sans-serif;
}

.login-wrapper{
  width:100vw;
  height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
}

.login-card{
  width: 80%;
  max-width: 900px;
  display:flex;
  border-radius: 18px;
  overflow:hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,.2);
  background:white;
}

/* PANEL KIRI */
.left-panel{
  width:50%;
  padding:40px;
  color:white;
  background: linear-gradient(180deg,#1e6bd8,#0f2f6b);
  position:relative;
}

.left-panel img{
  width:90px;
}

.welcome-text{
  margin-top:60px;
}

.welcome-text h1{
  font-size:36px;
  font-weight:700;
}

.btn-outline{
  margin-top:20px;
  padding:8px 20px;
  border:1px solid white;
  background:transparent;
  color:white;
  border-radius:20px;
  cursor:pointer;
}

/* PANEL KANAN */
.right-panel{
  width:50%;
  padding:50px;
  display:flex;
  align-items:center;
  justify-content:center;
}

.right-panel form{
  width:100%;
  max-width:320px;
  text-align:center;
}

.right-panel input{
  width:100%;
  padding:12px;
  margin:10px 0;
  border:1px solid #ddd;
  border-radius:8px;
}

.password-wrapper {
  position: relative;
  margin: 10px 0;
}

.password-wrapper input {
  padding-right: 40px;
  width: 100%;
  padding: 12px 40px 12px 12px;
}

.toggle-password {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #999;
  font-size: 18px;
}

.toggle-password:hover {
  color: #333;
}

.btn-login{
  width:100%;
  padding:12px;
  background:#2aa7ff;
  color:white;
  border:none;
  border-radius:8px;
  margin-top:10px;
  cursor:pointer;
}

.signup-text{
  margin-top:15px;
  font-size:14px;
}

.error-box{
  background:#ffe0e0;
  color:#900;
  padding:8px;
  border-radius:6px;
  font-size:13px;
}

/* RESPONSIVE BIAR DINAMIS */
@media(max-width:768px){
  .login-card{
    flex-direction:column;
    width:90%;
  }

  .left-panel, .right-panel{
    width:100%;
  }

  .left-panel{
    text-align:center;
  }
}
</style>
    <script type="text/javascript" src="admin/assets/js/jquery/jquery.min.js"></script>     <script type="text/javascript" src="admin/assets/js/jquery-ui/jquery-ui.min.js "></script>     <script type="text/javascript" src="admin/assets/js/popper.js/popper.min.js"></script>     <script type="text/javascript" src="admin/assets/js/bootstrap/js/bootstrap.min.js "></script>
<!-- waves js -->
<script src="admin/assets/pages/waves/js/waves.min.js"></script>
<!-- jquery slimscroll js -->
<script type="text/javascript" src="admin/assets/js/jquery-slimscroll/jquery.slimscroll.js "></script>
<!-- modernizr js -->
    <script type="text/javascript" src="admin/assets/js/SmoothScroll.js"></script>     <script src="admin/assets/js/jquery.mCustomScrollbar.concat.min.js "></script>
<!-- i18next.min.js -->
<script type="text/javascript" src="bower_components/i18next/js/i18next.min.js"></script>
<script type="text/javascript" src="bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
<script type="text/javascript" src="bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
<script type="text/javascript" src="bower_components/jquery-i18next/js/jquery-i18next.min.js"></script>
<script type="text/javascript" src="admin/assets/js/common-pages.js"></script>

<script>
function togglePassword() {
  const passwordInput = document.getElementById('password');
  const toggleIcon = document.querySelector('.toggle-password');
  
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    toggleIcon.classList.remove('fa-eye-slash');
    toggleIcon.classList.add('fa-eye');
  } else {
    passwordInput.type = 'password';
    toggleIcon.classList.remove('fa-eye');
    toggleIcon.classList.add('fa-eye-slash');
  }
}
</script>

<!-- PWA Service Worker Registration -->
<script src="assets/js/pwa-install.js"></script>
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
      navigator.serviceWorker.register('service-worker.js')
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