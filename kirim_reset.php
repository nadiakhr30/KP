<?php
session_start();
require 'koneksi.php';

$email = mysqli_real_escape_string($koneksi, $_POST['email']);

$cek = mysqli_query($koneksi, "SELECT id_user FROM user WHERE email='$email'");
if (mysqli_num_rows($cek) == 0) {
    echo "<script>alert('Email tidak terdaftar');location='forgot_password.php'</script>";
    exit;
}

$token = bin2hex(random_bytes(32)); // token asli (dikirim ke email)
$token_hash = password_hash($token, PASSWORD_DEFAULT);
$expired = date("Y-m-d H:i:s", time() + 900);

$stmt = $koneksi->prepare("
  UPDATE user 
  SET reset_token=?, reset_expired=?
  WHERE email=?
");
$stmt->bind_param("sss", $token_hash, $expired, $email);
$stmt->execute();

<<<<<<< HEAD
$link = "http://localhost/KP/ubahpassword.php?token=$token";
=======
$link = "http://localhost/KP/pegawai/ubahpassword.php?email=$email&token=$token";
>>>>>>> 32cc8d926e6c372e68328c4a305aee5acbcf2a14

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
