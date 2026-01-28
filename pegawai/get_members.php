<?php
session_start();
require '../koneksi.php';

header('Content-Type: application/json');

$category = $_GET['category'] ?? null;
$id = $_GET['id'] ?? null;

$members = [];

if (!$category || !$id) {
    echo json_encode(['members' => []]);
    exit;
}

// Fetch members berdasarkan kategori
if ($category === 'skill') {
    $query = "
        SELECT DISTINCT u.nip, u.nama, j.nama_jabatan as role, u.email
        FROM user u
        JOIN jabatan j ON u.id_jabatan = j.id_jabatan
        JOIN user_skill us ON u.nip = us.nip
        WHERE us.id_skill = ? AND u.id_role = 2
        ORDER BY u.nama ASC
    ";
    
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $members[] = $row;
    }
    mysqli_stmt_close($stmt);
    
} else if ($category === 'ppid') {
    $query = "
        SELECT DISTINCT u.nip, u.nama, j.nama_jabatan as role, u.email
        FROM user u
        JOIN jabatan j ON u.id_jabatan = j.id_jabatan
        WHERE u.id_ppid = ? AND u.id_role = 2
        ORDER BY u.nama ASC
    ";
    
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $members[] = $row;
    }
    mysqli_stmt_close($stmt);
    
} else if ($category === 'halopst') {
    $query = "
        SELECT DISTINCT u.nip, u.nama, j.nama_jabatan as role, u.email
        FROM user u
        JOIN jabatan j ON u.id_jabatan = j.id_jabatan
        JOIN user_halo_pst uhp ON u.nip = uhp.nip
        WHERE uhp.id_halo_pst = ? AND u.id_role = 2
        ORDER BY u.nama ASC
    ";
    
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $members[] = $row;
    }
    mysqli_stmt_close($stmt);
}

echo json_encode(['members' => $members]);
mysqli_close($koneksi);
?>
