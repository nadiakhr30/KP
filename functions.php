<?php
include("koneksi.php");

function checkLogin($data, &$errors)
{
    global $koneksi;

    // Cek apakah session sudah aktif
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $email = htmlspecialchars(trim($data["email"]));
    $password = $data["password"];

    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong.";
    }

    if (empty($password)) {
        $errors[] = "Password tidak boleh kosong.";
    }

    if (count($errors) === 0) {
        // Ambil data user berdasarkan email
        $result = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '$email'");
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verifikasi password
            if ($password == $user["password"]) {
                $_SESSION["user"] = $user;

                // Set role session and redirect to role-specific dashboard
                if ($user["role"] == '1') {
                    $_SESSION["role"] = "Admin";
                    header("Location: admin/index.php");
                    exit;
                } elseif ($user["role"] == '2') {
                    $_SESSION["role"] = "Pegawai";
                    header("Location: pegawai/index.php");
                    exit;
                } else {
                    header("Location: index.php");
                    exit;
                }
            } else {
                $errors[] = "Password salah.";
            }
        } else {
            $errors[] = "Email tidak ditemukan.";
        }
    }
}



