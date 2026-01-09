<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

/* =====================
   CEK TOKEN
===================== */
$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';

if ($token === '' || $email === '') {
    die('Token tidak valid');
}

$stmt = $koneksi->prepare("
    SELECT id_user, reset_token 
    FROM user 
    WHERE email=? 
      AND reset_token IS NOT NULL
      AND reset_expired > NOW()
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($token, $user['reset_token'])) {
    die('Token sudah kadaluarsa atau salah');
}

$id_user = $user['id_user'];
$showAlert = '';

/* =====================
   PROSES SIMPAN
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_new     = trim($_POST['password_new']);
    $password_confirm = trim($_POST['password_confirm']);

    if ($password_new === '' || $password_new !== $password_confirm) {
        $showAlert = 'mismatch';
    } else {
        $hash = password_hash($password_new, PASSWORD_DEFAULT);

        // ⛔ TOKEN DIHAPUS SETELAH PASSWORD BERHASIL DIUBAH
        $stmt = $koneksi->prepare("
            UPDATE user SET 
                password=?,
                reset_token=NULL,
                reset_expired=NULL
            WHERE id_user=?
        ");
        $stmt->bind_param("si", $hash, $id_user);

        $showAlert = $stmt->execute() ? 'success' : 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ubah Password</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:40px;
}
.form-box{
    max-width:420px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:14px;
    box-shadow:0 15px 30px rgba(0,0,0,.1);
}
.password-wrap{
    position:relative;
    margin-bottom:16px;
}
.password-wrap input{
    width:100%;
    padding:12px 40px 12px 12px;
    border-radius:8px;
    border:1px solid #ddd;
}
.password-wrap i{
    position:absolute;
    top:50%;
    right:12px;
    transform:translateY(-50%);
    cursor:pointer;
    display:none;
}
button{
    width:100%;
    padding:12px;
    background:#1e6cff;
    color:#fff;
    border:none;
    border-radius:8px;
    font-weight:600;
}
</style>
</head>

<body>

<div class="form-box">
<h2>Ubah Password</h2>

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
    const icon = input.nextElementSibling;
    icon.style.display = input.value ? 'block' : 'none';
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
    text:'Password berhasil diubah'
}).then(() => {
    window.location.href = 'index.php'; // halaman login
});
<?php elseif ($showAlert === 'mismatch'): ?>
Swal.fire({ icon:'error', text:'Password tidak sama' });
<?php elseif ($showAlert === 'error'): ?>
Swal.fire({ icon:'error', text:'Gagal menyimpan password' });
<?php endif; ?>
</script>

</body>
</html>