<?php
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../../index.php');
    exit();
}

$id_skill = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_skill <= 0) {
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$query = "SELECT * FROM skill WHERE id_skill = $id_skill";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$deleteQuery = "DELETE FROM skill WHERE id_skill = $id_skill";

if (mysqli_query($koneksi, $deleteQuery)) {
    header('Location: ../manajemen_data_lainnya.php?success=deleted');
} else {
    header('Location: ../manajemen_data_lainnya.php?error=delete_failed');
}
exit();
