<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lupa Password</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
*{
    box-sizing:border-box;
}
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}

/* CARD */
.card{
    width:360px;
    background:#fff;
    padding:35px 30px;
    border-radius:16px;
    box-shadow:0 15px 30px rgba(0,0,0,.12);
    text-align:center;
}

/* ICON HEADER */
.card .icon{
    width:70px;
    height:70px;
    background:#eaf0ff;
    color:#246bff;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    margin:0 auto 15px;
    font-size:28px;
}

/* TITLE */
.card h3{
    margin:0;
    font-size:22px;
    color:#1f3c88;
}
.card p{
    font-size:14px;
    color:#666;
    margin:10px 0 25px;
}

/* FORM */
.form-area{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:18px;
}

/* WIDTH GLOBAL */
:root{
    --field-width: 280px;
}

/* INPUT */
.input-group{
    position:relative;
    width:var(--field-width);
}
.input-group i{
    position:absolute;
    top:50%;
    left:14px;
    transform:translateY(-50%);
    color:#999;
    font-size:15px;
}
.input-group input{
    width:100%;
    height:48px;
    padding:0 14px 0 42px;
    border-radius:12px;
    border:1px solid #ddd;
    font-size:15px;
}
.input-group input:focus{
    outline:none;
    border-color:#246bff;
}

/* BUTTON */
button{
    width:var(--field-width);
    height:48px;
    border:none;
    border-radius:12px;
    background:#246bff;
    color:#fff;
    font-weight:600;
    font-size:15px;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
}
button:hover{
    background:#1557d6;
}
</style>
</head>

<body>

<div class="card">

    <div class="icon">
        <i class="fa-solid fa-lock"></i>
    </div>

    <h3>Lupa Password</h3>
    <p>Masukkan email Anda untuk menerima link reset password</p>

    <form method="POST" action="kirim_reset.php" id="resetForm" class="form-area">
        <div class="input-group">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" name="email" placeholder="Email Anda" required>
        </div>

        <button type="button" onclick="konfirmasi()">
            <i class="fa-solid fa-paper-plane"></i>
            Kirim Link Reset
        </button>
    </form>

</div>

<script>
function konfirmasi(){
    Swal.fire({
        title:'Konfirmasi',
        text:'Link reset password akan dikirim ke email Anda',
        icon:'question',
        showCancelButton:true,
        confirmButtonColor:'#246bff',
        cancelButtonColor:'#d33',
        confirmButtonText:'Kirim',
        cancelButtonText:'Batal'
    }).then((result)=>{
        if(result.isConfirmed){
            document.getElementById("resetForm").submit();
        }
    })
}
</script>

</body>
</html>
