<?php
$host = '127.0.0.1:3307';
$username = 'root';
$password = '';
$database = 'sistem_kehumasan';

$koneksi = mysqli_connect($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');
?>