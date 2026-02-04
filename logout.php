<?php
$logout_done = false;

// Jalankan ini hanya jika user minta logout
if (isset($_GET['action']) && $_GET['action'] === 'doLogout') {
    session_start();
    session_unset();
    session_destroy();
    $logout_done = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" href="../../images/sikumbang.ico" type="image/x-icon">
  <title>Logout</title>

  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />

  <!-- SweetAlert -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #6689ea 0%, #4b62a2 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .logout-card {
      background: rgba(255,255,255,0.97);
      border-radius: 22px;
      box-shadow: 0 8px 40px rgba(102,126,234,0.18), 0 2px 8px rgba(118,75,162,0.08);
      padding: 48px 36px 36px 36px;
      max-width: 370px;
      width: 100%;
      text-align: center;
      position: relative;
      z-index: 2;
      animation: fadeInCard 0.7s cubic-bezier(0.23,1,0.32,1);
    }

    @keyframes fadeInCard {
      from { opacity: 0; transform: translateY(40px) scale(0.98); }
      to { opacity: 1; transform: none; }
    }

    .logout-icon {
      font-size: 3.2rem;
      color: #764ba2;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 50%;
      width: 70px;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 18px auto;
      box-shadow: 0 4px 18px rgba(102,126,234,0.13);
      animation: popIcon 1.2s cubic-bezier(0.23,1,0.32,1);
    }
    @keyframes popIcon {
      0% { transform: scale(0.7); opacity: 0; }
      60% { transform: scale(1.15); opacity: 1; }
      100% { transform: scale(1); }
    }

    .logout-title {
      font-size: 1.45rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 10px;
      letter-spacing: -0.5px;
    }
    .logout-desc {
      color: #555;
      font-size: 1.05rem;
      margin-bottom: 24px;
    }

    .swal-button--confirm {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
      color: #fff !important;
      font-weight: 600;
      border-radius: 8px;
      padding: 10px 28px;
      box-shadow: 0 2px 8px rgba(102,126,234,0.10);
      border: none;
      font-size: 1rem;
      transition: background 0.2s;
    }
    .swal-button--confirm:hover {
      background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
    }

    .swal-button--cancel {
      background: linear-gradient(135deg, #f5576c 0%, #ff8c42 100%) !important;
      color: #fff !important;
      font-weight: 600;
      border-radius: 8px;
      padding: 10px 28px;
      border: none;
      font-size: 1rem;
      transition: background 0.2s;
    }
    .swal-button--cancel:hover {
      background: linear-gradient(135deg, #ff8c42 0%, #f5576c 100%) !important;
    }

    .swal-footer {
      display: flex !important;
      justify-content: center !important;
      gap: 18px;
    }

    /* Decorative gradient circle */
    .bg-circle {
      position: absolute;
      width: 420px;
      height: 420px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(102,126,234,0.13) 0%, transparent 70%);
      left: 50%;
      top: 50%;
      transform: translate(-50%,-50%);
      z-index: 0;
      pointer-events: none;
      animation: floatCircle 8s ease-in-out infinite;
    }
    @keyframes floatCircle {
      0%,100% { transform: translate(-50%,-50%) scale(1); }
      50% { transform: translate(-50%,-48%) scale(1.08); }
    }
  </style>
</head>

<body>
  <div class="bg-circle"></div>
  <div class="logout-card">
    <div class="logout-icon">
      <i class="bi bi-box-arrow-right"></i>
    </div>
    <div class="logout-title">Keluar dari Sistem</div>
    <div class="logout-desc">Klik tombol di bawah untuk keluar dari akun Anda dengan aman.</div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.js"></script>
  <script>
  $(document).ready(function() {
    <?php if (!$logout_done): ?>
      // Tampilkan konfirmasi logout dulu
      swal({
        title: "Apakah Anda yakin?",
        text: "Ingin keluar dari website?",
        icon: "warning",
        buttons: {
          cancel: {
            visible: true,
            text: "Tidak, batalkan",
            className: "swal-button--cancel"
          },
          confirm: {
            text: "Ya, keluar sekarang",
            className: "swal-button--confirm"
          }
        }
      }).then((willLogout) => {
        if (willLogout) {
          // Kalau user klik ya, redirect ke URL yang memproses session_destroy()
          window.location.href = 'logout.php?action=doLogout';
        } else {
          // Kalau batal, kembali ke homepage atau halaman lain
          window.location.href = 'index.php';
        }
      });
    <?php else: ?>
      // Setelah session dihapus, tampilkan pesan berhasil logout
      swal({
        title: "Berhasil keluar!",
        text: "Anda telah keluar dari website.",
        icon: "success",
        button: {
          text: "OK",
          className: "swal-button--confirm"
        }
      }).then(() => {
        window.location.href = 'index.php';
      });
    <?php endif; ?>
  });
  </script>
</body>
</html>
