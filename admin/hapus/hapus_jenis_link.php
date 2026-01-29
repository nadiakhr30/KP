<?php
session_start();
include_once("../../koneksi.php");
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki akses']);
    exit();
}

$id_jenis_link = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_jenis_link <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    exit();
}

$query = "SELECT * FROM jenis_link WHERE id_jenis_link = $id_jenis_link";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    exit();
}

$deleteQuery = "DELETE FROM jenis_link WHERE id_jenis_link = $id_jenis_link";

if (mysqli_query($koneksi, $deleteQuery)) {
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus!']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data: ' . mysqli_error($koneksi)]);
}
exit();
