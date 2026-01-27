<?php
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../../index.php');
    exit();
}

$id_role = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_role <= 0) {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'ID tidak valid';
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$query = "SELECT * FROM role WHERE id_role = $id_role";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'Data tidak ditemukan';
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$deleteQuery = "DELETE FROM role WHERE id_role = $id_role";

if (mysqli_query($koneksi, $deleteQuery)) {
    $_SESSION['delete_status'] = 'success';
    $_SESSION['delete_message'] = 'Data berhasil dihapus!';
} else {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'Gagal menghapus data!';
}
header('Location: ../manajemen_data_lainnya.php');
exit();
