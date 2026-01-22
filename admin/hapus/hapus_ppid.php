<?php
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../../index.php');
    exit();
}

$id_ppid = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_ppid <= 0) {
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$query = "SELECT * FROM ppid WHERE id_ppid = $id_ppid";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$deleteQuery = "DELETE FROM ppid WHERE id_ppid = $id_ppid";

if (mysqli_query($koneksi, $deleteQuery)) {
    header('Location: ../manajemen_data_lainnya.php?success=deleted');
} else {
    header('Location: ../manajemen_data_lainnya.php?error=delete_failed');
}
exit();
