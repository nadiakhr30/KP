<?php
session_start();
require 'koneksi.php';

// Ambil email dari form
$email = mysqli_real_escape_string($koneksi, $_POST['email']);

// Cek email
$cek = mysqli_query($koneksi, "SELECT id_user FROM user WHERE email='$email'");
if (mysqli_num_rows($cek) == 0) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon:'error',
            title:'Gagal',
            text:'Email tidak terdaftar'
        }).then(()=>location='forgot_password.php');
    </script>";
    exit;
}

// Token & expired
$token = bin2hex(random_bytes(32));
$token_hash = password_hash($token, PASSWORD_DEFAULT);
$expired = date("Y-m-d H:i:s", time() + 900);

// Simpan token
mysqli_query($koneksi, "
    UPDATE user 
    SET reset_token='$token_hash', reset_expired='$expired' 
    WHERE email='$email'
");

// Link reset
$link = "http://localhost/KP/ubahpassword.php?token=$token";

// ================= EMAIL TEMPLATE =================
$subject = "Reset Password";

$message = "
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
</head>
<body style='margin:0;padding:0;background:#f4f7fb;font-family:Arial,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td align='center'>

<table width='500' cellpadding='0' cellspacing='0' style='background:#ffffff;margin:30px auto;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,.1);'>
<tr>
<td style='padding:30px;text-align:center;'>

<h2 style='color:#3366ff;margin-bottom:10px;'>
🔐 Reset Password
</h2>

<p style='color:#555;font-size:14px;line-height:1.6;'>
Kami menerima permintaan untuk mengatur ulang password akun Anda.
Silakan klik tombol di bawah ini untuk melanjutkan.
</p>

<a href='$link'
style='display:inline-block;margin:20px 0;padding:12px 25px;
background:#3366ff;color:#fff;text-decoration:none;
border-radius:8px;font-weight:bold;'>
Reset Password
</a>

<p style='font-size:12px;color:#777;'>
Link ini hanya berlaku selama <b>15 menit</b>.
Jika Anda tidak merasa meminta reset password, silakan abaikan email ini.
</p>

<hr style='border:none;border-top:1px solid #eee;margin:25px 0;'>

<p style='font-size:12px;color:#999;'>
KP System<br>
Email otomatis, mohon tidak membalas.
</p>

</td>
</tr>
</table>

</td>
</tr>
</table>
</body>
</html>
";

// Header email
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: Humas BPS System <noreply@BPS.local>\r\n";

// Kirim email
if (mail($email, $subject, $message, $headers)) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon:'success',
            title:'Berhasil',
            text:'Link reset password telah dikirim ke email Anda',
            confirmButtonColor:'#3366ff'
        }).then(()=>location='index.php');
    </script>";
} else {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon:'error',
            title:'Gagal',
            text:'Email gagal dikirim'
        }).then(()=>location='forgot_password.php');
    </script>";
}
?>
