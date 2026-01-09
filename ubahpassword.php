<?php
session_start();
require 'koneksi.php';

$showAlert  = '';
$redirectUrl = '';
$id_user    = null;
$token      = $_GET['token'] ?? '';

/* =====================
   CEK AKSES
===================== */

// === MODE PROFILE (SUDAH LOGIN) ===
if (isset($_SESSION['user']['id_user'])) {
    $id_user = $_SESSION['user']['id_user'];
    $redirectUrl = 'pegawai/profile.php';
}

// === MODE FORGOT PASSWORD ===
elseif ($token != '') {

    $q = mysqli_query($koneksi, "
        SELECT id_user, reset_token 
        FROM user 
        WHERE reset_expired > NOW()
    ");

    $validUser = null;
    while ($row = mysqli_fetch_assoc($q)) {
        if (password_verify($token, $row['reset_token'])) {
            $validUser = $row;
            break;
        }
    }

    if (!$validUser) {
        die('Token tidak valid atau sudah kadaluarsa');
    }

    $id_user = $validUser['id_user'];
    $redirectUrl = 'index.php';
}

// === AKSES ILEGAL ===
else {
    die('Akses tidak valid');
}

/* =====================
   PROSES SIMPAN PASSWORD
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password_new     = trim($_POST['password_new']);
    $password_confirm = trim($_POST['password_confirm']);

    if ($password_new === '' || $password_new !== $password_confirm) {
        $showAlert = 'mismatch';
    } else {

        $password_hash = password_hash($password_new, PASSWORD_DEFAULT);

        $sql = "UPDATE user SET password='$password_hash'";

        // kalau dari forgot password → hapus token
        if ($token != '') {
            $sql .= ", reset_token=NULL, reset_expired=NULL";
        }

        $sql .= " WHERE id_user='$id_user'";

        $update = mysqli_query($koneksi, $sql);
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#cce0ff,#6699ff);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}
.form-box{
    width:100%;
    max-width:400px;
    background:#fff;
    padding:35px 25px;
    border-radius:16px;
    box-shadow:0 20px 40px rgba(0,0,0,.15);
}
.form-box h2{
    text-align:center;
    color:#3366ff;
    margin-bottom:25px;
}
.password-wrap{
    position:relative;
    margin-bottom:20px;
}
.password-wrap input{
    width:100%;
    padding:12px 45px 12px 12px;
    border-radius:10px;
    border:1px solid #ccc;
    box-sizing:border-box;
}
.password-wrap i{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
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
}
button:hover{
    background:#0033cc;
}
label{
    font-weight:500;
    display:block;
    margin-bottom:6px;
}
</style>
</head>

<body>

<div class="form-box">
    <h2><i class="fa-solid fa-lock"></i> Ubah Password</h2>

    <form method="POST">
        <label>Password Baru</label>
        <div class="password-wrap">
            <input type="password" name="password_new" oninput="eye(this)" required>
            <i class="fa-solid fa-eye-slash" onclick="toggle(this)"></i>
        </div>

        <label>Konfirmasi Password</label>
        <div class="password-wrap">
            <input type="password" name="password_confirm" oninput="eye(this)" required>
            <i class="fa-solid fa-eye-slash" onclick="toggle(this)"></i>
        </div>

        <button type="submit">Simpan Password</button>
    </form>
</div>

<script>
function eye(input){
    input.nextElementSibling.style.display = input.value ? 'block' : 'none';
}
function toggle(icon){
    const input = icon.previousElementSibling;
    if(input.type === 'password'){
        input.type = 'text';
        icon.className = 'fa-solid fa-eye';
    }else{
        input.type = 'password';
        icon.className = 'fa-solid fa-eye-slash';
    }
}
</script>

<script>
<?php if ($showAlert === 'success'): ?>
Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:'Password berhasil diubah',
    confirmButtonColor:'#3366ff'
}).then(()=>window.location.href='<?= $redirectUrl ?>');
<?php elseif ($showAlert === 'mismatch'): ?>
Swal.fire({icon:'error',text:'Password tidak sama'});
<?php elseif ($showAlert === 'error'): ?>
Swal.fire({icon:'error',text:'Gagal menyimpan password'});
<?php endif; ?>
</script>

</body>
</html>
