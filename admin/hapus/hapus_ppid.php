<?php
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../../index.php');
    exit();
}

$id_ppid = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_ppid <= 0) {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'ID tidak valid';
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$query = "SELECT * FROM ppid WHERE id_ppid = $id_ppid";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'Data tidak ditemukan';
    header('Location: ../manajemen_data_lainnya.php');
    exit();
}

$deleteQuery = "DELETE FROM ppid WHERE id_ppid = $id_ppid";

if (mysqli_query($koneksi, $deleteQuery)) {
    $_SESSION['delete_status'] = 'success';
    $_SESSION['delete_message'] = 'Data berhasil dihapus!';
} else {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'Gagal menghapus data!';
}
header('Location: ../manajemen_data_lainnya.php');
exit();
