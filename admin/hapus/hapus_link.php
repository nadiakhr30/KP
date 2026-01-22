<?php
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../../index.php');
    exit();
}

$id_link = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_link <= 0) {
    header('Location: ../manajemen_link.php');
    exit();
}

// Get data to check if exists and get gambar
$query = "SELECT * FROM link WHERE id_link = $id_link";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: ../manajemen_link.php');
    exit();
}

// Delete gambar jika ada
if ($data['gambar'] && file_exists('../uploads/' . $data['gambar'])) {
    unlink('../uploads/' . $data['gambar']);
}

// Delete from database
$deleteQuery = "DELETE FROM link WHERE id_link = $id_link";

if (mysqli_query($koneksi, $deleteQuery)) {
    header('Location: ../manajemen_link.php?success=deleted');
} else {
    header('Location: ../manajemen_link.php?error=delete_failed');
}
exit();
