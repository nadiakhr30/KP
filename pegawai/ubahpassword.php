<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['user']['id_user'];
$showAlert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_new = trim($_POST['password_new'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');

    if ($password_new === '' || $password_confirm === '' || $password_new !== $password_confirm) {
        $showAlert = 'mismatch';
    } else {
        $update = "UPDATE user 
                   SET password='" . mysqli_real_escape_string($koneksi, $password_new) . "' 
                   WHERE id_user='" . mysqli_real_escape_string($koneksi, $id_user) . "'";
        $showAlert = mysqli_query($koneksi, $update) ? 'success' : 'error_db';
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
    margin:0;
    padding:40px;
}

/* CARD */
.form-container{
    background:#fff;
    padding:30px;
    border-radius:14px;
    box-shadow:0 12px 30px rgba(0,0,0,.08);
    max-width:520px;
    margin:auto;
}

h2{
    margin-bottom:25px;
    font-size:22px;
    text-align:center;
    color:#1f3c88;
}

/* FORM */
label{
    display:block;
    margin-bottom:6px;
    font-weight:600;
}

/* PASSWORD FIELD */
.password-wrapper{
    position:relative;
    margin-bottom:18px;
}
.password-wrapper input{
    width:100%;
    padding:12px 42px 12px 12px;
    border-radius:8px;
    border:1px solid #e6e6e6;
    box-sizing:border-box;
}

/* ICON MATA */
.password-wrapper i{
    position:absolute;
    top:50%;
    right:14px;
    transform:translateY(-50%);
    cursor:pointer;
    color:#888;
    display:none; /* 👈 default disembunyikan */
}
.password-wrapper i:hover{
    color:#1e6cff;
}

/* BUTTON */
.button-group{
    display:flex;
    gap:12px;
}
button,
.back-button{
    flex:1;
    padding:12px;
    border:none;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
    text-align:center;
    text-decoration:none;
    color:#fff;
}
button{ background:#1e6cff; }
button:hover{ background:#1557d6; }
.back-button{ background:#9e9e9e; }
.back-button:hover{ background:#7e7e7e; }
</style>
</head>

<body>

<div class="form-container">
    <h2>Ubah Password</h2>

    <form method="POST">

        <label>Password Baru</label>
        <div class="password-wrapper">
            <input type="password" id="password_new" name="password_new" oninput="handleEye(this)" required>
            <i class="fa-solid fa-eye-slash" onclick="togglePassword('password_new', this)"></i>
        </div>

        <label>Konfirmasi Password Baru</label>
        <div class="password-wrapper">
            <input type="password" id="password_confirm" name="password_confirm" oninput="handleEye(this)" required>
            <i class="fa-solid fa-eye-slash" onclick="togglePassword('password_confirm', this)"></i>
        </div>

        <div class="button-group">
            <button type="submit">Ubah</button>
            <a href="profile.php" class="back-button">Kembali</a>
        </div>
    </form>
</div>

<script>
/* MUNCULKAN / SEMBUNYIKAN ICON BERDASARKAN ISI INPUT */
function handleEye(input){
    const icon = input.nextElementSibling;
    if(input.value.length > 0){
        icon.style.display = "block";
    }else{
        icon.style.display = "none";
        input.type = "password";
        icon.className = "fa-solid fa-eye-slash";
    }
}

/* TOGGLE PASSWORD */
function togglePassword(id, icon){
    const input = document.getElementById(id);
    if(input.type === "password"){
        input.type = "text";
        icon.className = "fa-solid fa-eye";
    }else{
        input.type = "password";
        icon.className = "fa-solid fa-eye-slash";
    }
}
</script>

<script>
<?php if ($showAlert === 'success'): ?>
Swal.fire({
    title: "Berhasil!",
    text: "Password berhasil diubah.",
    icon: "success",
    showConfirmButton: false,
    timer: 2000
}).then(() => {
    window.location.href = "profile.php";
});
<?php elseif ($showAlert === 'mismatch'): ?>
Swal.fire({ icon:"error", title:"Gagal", text:"Password baru dan konfirmasi tidak sama!" });
<?php elseif ($showAlert === 'error_db'): ?>
Swal.fire({ icon:"error", title:"Error", text:"Gagal menyimpan password." });
<?php endif; ?>
</script>

</body>
</html>
