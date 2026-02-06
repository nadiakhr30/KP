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
    <link rel="icon" href="../../images/sikumbang.ico" type="image/x-icon">
    <title>Sikumbang - Sistem Kehumasan BPS Bangkalan</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="author" content="Kamila" />
      <meta name="description" content="Sistem Manajemen Kehumasan BPS Bangkalan">
      <meta name="theme-color" content="#2c52cc">
      <meta name="mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="default">
      <meta name="apple-mobile-web-app-title" content="Sikumbang">
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
  <div class="login-card m-y-5">

    <!-- PWA Install Notification/Toast -->
    <div id="pwa-install-notification" style="display:none; position:fixed; top:20px; left:50%; transform:translateX(-50%); background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; padding:16px 24px; border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,0.2); z-index:100000; max-width:400px; width:90%; animation:slideDownNotif 0.4s ease-out;">
      <div style="display:flex; align-items:center; gap:12px; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:12px; flex:1;">
          <i class="ti-download" style="font-size:24px; flex-shrink:0;"></i>
          <div>
            <p style="margin:0; font-weight:600; font-size:14px;">Install Sikumbang</p>
            <p style="margin:4px 0 0 0; font-size:12px; opacity:0.9;">Akses langsung dari beranda Anda</p>
          </div>
        </div>
        <button id="pwa-install-btn" style="background:white; color:#667eea; border:none; padding:8px 16px; border-radius:6px; font-weight:600; cursor:pointer; font-size:12px; white-space:nowrap; margin-left:12px;">
          Install
        </button>
      </div>
      <button id="pwa-notif-close-btn" style="position:absolute; top:8px; right:8px; background:none; border:none; color:white; font-size:20px; cursor:pointer; opacity:0.7; transition:opacity 0.2s;">
        ✕
      </button>
    </div>

    <!-- NAVBAR TOP (hidden, kept for compatibility) -->
    <div style="position:absolute; top:0; left:0; right:0; padding:12px 20px; background:rgba(255,255,255,0.95); border-bottom:1px solid #eee; display:flex; justify-content:flex-end; align-items:center; gap:10px;">
      <a href="javascript:void(0)" id="pwa-navbar-install-btn" class="pwa-navbar-link" style="display:none; color:#667eea; font-weight:600; text-decoration:none; padding:8px 12px; border-radius:6px; transition:all 0.3s ease; font-size:14px;" title="Install App">
        <i class="ti-download" style="margin-right:10px;"></i>Install
      </a>
    </div>

    <!-- KIRI -->
    <div class="left-panel">
      <div class="brand">
        <img src="images/sikumbang.png" alt="Sikumbang Logo">
      </div>

      <div class="welcome-text">
        <h1>SIKUMBANG</h1>
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
  padding-left: 15px;
  padding-right: 15px;
  display:flex;
  align-items:center;
  justify-content:center;
}

.login-card{
  position:relative;
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
    width:96%;
    min-width:unset;
    margin-top:50px;
    box-shadow: 0 4px 16px rgba(0,0,0,.12);
  }
  .left-panel, .right-panel{
    width:100%;
    padding:28px 12px;
  }
  .left-panel{
    text-align:center;
  }
  .welcome-text h1{
    font-size:28px;
  }
  .welcome-text h6{
    font-size:15px;
  }
  .btn-outline{
    padding:7px 16px;
    font-size:15px;
  }
  .right-panel form{
    max-width:98vw;
    padding:0 2vw;
  }
  .right-panel input, .btn-login{
    font-size:16px;
    padding:14px;
  }
}

@media(max-width:480px){
  .login-card{
    width: 80vw;
    margin-top:50px;
    border-radius: 15px;
    box-shadow:none;
  }
  .left-panel, .right-panel{
    padding:18px 4px;
  }
  .welcome-text h1{
    font-size:22px;
  }
  .welcome-text h6{
    font-size:13px;
  }
  .btn-outline{
    font-size:13px;
    padding:6px 10px;
  }
  .right-panel form{
    max-width:100vw;
    padding:0 1vw;
  }
  .right-panel input, .btn-login{
    font-size:15px;
    padding:12px;
  }
  .toggle-password{
    font-size:22px;
    right:8px;
  }
}

/* PWA Install Notification Animation */
@keyframes slideDownNotif {
  from {
    transform: translateX(-50%) translateY(-100px);
    opacity: 0;
  }
  to {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
  }
}

#pwa-install-notification:hover {
  box-shadow: 0 12px 32px rgba(0,0,0,0.3);
}

#pwa-install-btn:hover {
  transform: scale(1.05);
}

#pwa-notif-close-btn:hover {
  opacity: 1 !important;
}

/* PWA Navbar Link */
.pwa-navbar-link:hover {
  background:rgba(102, 126, 234, 0.1);
  color:#764ba2 !important;
}

@media (max-width: 768px) {
  .pwa-navbar-link {
    font-size: 12px !important;
    padding: 6px 8px !important;
  }
  
  .pwa-navbar-link i {
    margin-right: 4px !important;
  }
  
  #pwa-install-notification {
    top: 10px !important;
    max-width: calc(100% - 20px) !important;
  }
}
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
<!-- Common pages js -->
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

<!-- PWA Install via pwa-install.js -->
<script src="assets/js/pwa-install.js"></script>

</body>

</html>