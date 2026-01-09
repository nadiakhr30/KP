<?php
session_start();
require 'koneksi.php';

$email = mysqli_real_escape_string($koneksi, $_POST['email']);

$cek = mysqli_query($koneksi, "SELECT id_user FROM user WHERE email='$email'");
if (mysqli_num_rows($cek) == 0) {
    echo "<script>alert('Email tidak terdaftar');location='forgot_password.php'</script>";
    exit;
}

$token = bin2hex(random_bytes(32));
$expired = date('Y-m-d H:i:s', strtotime('+15 minutes'));

mysqli_query($koneksi, "
    UPDATE user 
    SET reset_token='$token', reset_expired='$expired' 
    WHERE email='$email'
");

$link = "http://localhost/KP/ubahpassword.php?token=$token";

$subject = "Reset Password";
$message = "
<html>
<body>
<h3>Reset Password</h3>
<p>Klik link berikut:</p>
<a href='$link'>Reset Password</a>
<p>Link berlaku 15 menit.</p>
</body>
</html>
";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: KP System <noreply@kp.local>\r\n";

if (mail($email, $subject, $message, $headers)) {
    echo "<script>alert('Link reset dikirim');location='index.php'</script>";
} else {
    echo "<script>alert('Gagal mengirim email');location='forgot_password.php'</script>";
}
