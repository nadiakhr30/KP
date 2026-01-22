<?php
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../../index.php');
    exit();
}

$id_jenis = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_jenis <= 0) {
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$query = "SELECT * FROM jenis WHERE id_jenis = $id_jenis";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$deleteQuery = "DELETE FROM jenis WHERE id_jenis = $id_jenis";

if (mysqli_query($koneksi, $deleteQuery)) {
    header('Location: ../manajemen_data_lainnya.php?success=deleted');
} else {
    header('Location: ../manajemen_data_lainnya.php?error=delete_failed');
}
exit();
