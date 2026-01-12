<?php
include "../koneksi.php";

$password = password_hash("123", PASSWORD_DEFAULT);

$sql = "UPDATE user SET
    password = '$password'
    WHERE email = 'khoirnadiatul@gmail.com'";

mysqli_query($koneksi, $sql);
