<?php
include("koneksi.php");

function checkLogin($data, &$errors)
{
    global $koneksi;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $email    = trim($data["email"]);
    $password = $data["password"];

    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong.";
    }

    if (empty($password)) {
        $errors[] = "Password tidak boleh kosong.";
    }

    if (count($errors) === 0) {

        // AMBIL PEGAWAI
        $result = mysqli_query(
            $koneksi,
            "SELECT * FROM pegawai WHERE email = '" . mysqli_real_escape_string($koneksi, $email) . "' LIMIT 1"
        );

        if ($result && mysqli_num_rows($result) === 1) {
            $pegawai = mysqli_fetch_assoc($result);

            // VERIFIKASI PASSWORD (INI KUNCI UTAMA)
            if (password_verify($password, $pegawai["password"])) {

                // Login sukses
                $_SESSION["pegawai"] = $pegawai;

                if ($pegawai["id_role"] == '1') {
                    $_SESSION["role"] = "Admin";
                    header("Location: admin/index.php");
                    exit;
                } else {
                    $_SESSION["role"] = "Pegawai";
                    header("Location: pegawai/index.php");
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
