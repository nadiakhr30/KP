<?php
include("../../koneksi.php");

header('Content-Type: application/json');

// Check if ID provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID jadwal tidak ditemukan']);
    exit();
}

$id_jadwal = (int)$_GET['id'];

// Start transaction
mysqli_begin_transaction($koneksi);

try {
    // Delete from jadwal_link table (references)
    $deleteLinks = mysqli_query($koneksi, "DELETE FROM jadwal_link WHERE id_jadwal = " . $id_jadwal);
    if (!$deleteLinks) {
        throw new Exception("Gagal menghapus data link: " . mysqli_error($koneksi));
    }
    
    // Delete from pic table (references)
    $deletePic = mysqli_query($koneksi, "DELETE FROM pic WHERE id_jadwal = " . $id_jadwal);
    if (!$deletePic) {
        throw new Exception("Gagal menghapus data PIC: " . mysqli_error($koneksi));
    }
    
    // Delete from jadwal table (main)
    $deleteJadwal = mysqli_query($koneksi, "DELETE FROM jadwal WHERE id_jadwal = " . $id_jadwal);
    if (!$deleteJadwal) {
        throw new Exception("Gagal menghapus jadwal: " . mysqli_error($koneksi));
    }
    
    // Commit transaction
    mysqli_commit($koneksi);
    
    echo json_encode([
        'success' => true,
        'message' => 'Jadwal berhasil dihapus!'
    ]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($koneksi);
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

