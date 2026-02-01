<?php
session_start();
include_once("../../koneksi.php");
header('Content-Type: application/json');

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != 'Admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki akses']);
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    exit();
}

// Check exists
$q = mysqli_query($koneksi, "SELECT * FROM media WHERE id_media = $id");
$data = mysqli_fetch_assoc($q);
if (!$data) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    exit();
}

// Delete record
$del = mysqli_query($koneksi, "DELETE FROM media WHERE id_media = $id");
if ($del) {
    echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus!']);
    exit();
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data: ' . mysqli_error($koneksi)]);
    exit();
}
