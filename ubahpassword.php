<?php
session_start();
require_once 'koneksi.php';

$showAlert = '';
$redirectUrl = 'index.php'; // default redirect

/* =====================
   CEK MODE: TOKEN ATAU LOGIN
===================== */
$token = $_GET['token'] ?? '';
if ($token != '') {
    // MODE FORGOT PASSWORD
    $q = mysqli_query($koneksi, "
        SELECT id_user FROM user 
        WHERE reset_token='$token' AND reset_expired > NOW()
    ");
    $user = mysqli_fetch_assoc($q);
    if (!$user) die('Token sudah kadaluarsa atau salah');
    $id_user = $user['id_user'];
    $redirectUrl = 'index.php'; // setelah reset, redirect ke login/index
} elseif (isset($_SESSION['id_user'])) {
    // MODE PROFILE
    $id_user = $_SESSION['id_user'];
    $redirectUrl = 'profile.php'; // setelah ubah, redirect ke profile
} else {
    die('Akses tidak valid');
}

/* =====================
   PROSES SIMPAN
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_new     = trim($_POST['password_new']);
    $password_confirm = trim($_POST['password_confirm']);

    if ($password_new === '' || $password_new !== $password_confirm) {
        $showAlert = 'mismatch';
    } else {
        $password_hash = password_hash($password_new, PASSWORD_DEFAULT);
        $update_query = "UPDATE user SET password='$password_hash'";

        // jika pakai token, hapus token & expired
        if ($token != '') $update_query .= ", reset_token=NULL, reset_expired=NULL";

        $update_query .= " WHERE id_user='$id_user'";
        $update = mysqli_query($koneksi, $update_query);
        $showAlert = $update ? 'success' : 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ubah Password</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background: linear-gradient(135deg,#cce0ff,#6699ff);
    padding:40px 20px;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.form-box{
    width:100%;
    max-width:400px; /* maksimal lebar form */
    background:#fff;
    padding:35px 25px; /* padding horizontal aman */
    border-radius:16px;
    box-shadow:0 20px 40px rgba(0,0,0,.15);
    text-align:center;
    transition: transform .3s ease;
    box-sizing:border-box; /* penting agar padding tidak bikin overflow */
}
.form-box:hover{
    transform: translateY(-5px);
}
.form-box h2{
    margin-bottom:25px;
    color:#3366ff;
    font-weight:600;
}
.password-wrap{
    position:relative;
    margin-bottom:20px;
    text-align:left;
}
.password-wrap input{
    width:100%;
    max-width:100%; /* pastikan tidak melebar */
    box-sizing:border-box; /* padding tidak membuat lebar melebihi container */
    padding:12px 45px 12px 12px;
    border-radius:10px;
    border:1px solid #ccc;
    transition:border .3s;
}
.password-wrap input:focus{
    border-color:#3366ff;
    outline:none;
}
.password-wrap i{
    position:absolute;
    top:50%;
    right:12px;
    transform:translateY(-50%);
    cursor:pointer;
    color:#555;
    display:none;
}
button{
    width:100%;
    padding:12px;
    background:#3366ff;
    color:#fff;
    border:none;
    border-radius:10px;
    font-weight:600;
    cursor:pointer;
    transition: background .3s;
}
button:hover{
    background:#0033cc;
}
label{
    display:block;
    margin-bottom:6px;
    font-weight:500;
    color:#333;
}
@media(max-width:500px){
    .form-box{
        padding:30px 20px;
    }
}
</style>
</head>

<body>

<div class="form-box">
    <h2><i class="fa-solid fa-lock"></i> Ubah Password</h2>

    <form method="POST">
        <div class="password-wrap">
            <label>Password Baru</label>
            <input type="password" name="password_new" oninput="eye(this)" placeholder="Masukkan password baru" required>
            <i class="fa-solid fa-eye-slash" onclick="toggle(this)"></i>
        </div>

        <div class="password-wrap">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirm" oninput="eye(this)" placeholder="Konfirmasi password" required>
            <i class="fa-solid fa-eye-slash" onclick="toggle(this)"></i>
        </div>

        <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan Password</button>
    </form>
</div>

<script>
// Show/hide icon
function eye(input){
    const icon = input.nextElementSibling;
    icon.style.display = input.value ? 'block' : 'none';
}
// Toggle password visibility
function toggle(icon){
    const input = icon.previousElementSibling;
    if(input.type === 'password'){
        input.type = 'text';
        icon.className = 'fa-solid fa-eye';
    } else{
        input.type = 'password';
        icon.className = 'fa-solid fa-eye-slash';
    }
}
</script>

<script>
<?php if ($showAlert === 'success'): ?>
Swal.fire({
    icon:'success',
    title:'Berhasil!',
    text:'Password berhasil diubah',
    confirmButtonColor:'#3366ff'
}).then(()=>window.location.href='<?= $redirectUrl ?>');
<?php elseif ($showAlert === 'mismatch'): ?>
Swal.fire({
    icon:'error',
    text:'Password tidak sama',
    confirmButtonColor:'#3366ff'
});
<?php elseif ($showAlert === 'error'): ?>
Swal.fire({
    icon:'error',
    text:'Gagal menyimpan password',
    confirmButtonColor:'#3366ff'
});
<?php endif; ?>
</script>

</body>
</html>
