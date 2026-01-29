<?php
include("../koneksi.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_jadwal = (int)($_POST['id_jadwal'] ?? 0);
    
    if ($id_jadwal <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID jadwal tidak valid']);
        exit;
    }
    
    // Get current data
    $query = "SELECT dokumentasi FROM jadwal WHERE id_jadwal = " . $id_jadwal;
    $result = mysqli_query($koneksi, $query);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        echo json_encode(['success' => false, 'message' => 'Jadwal tidak ditemukan']);
        exit;
    }
    
    $current = mysqli_fetch_assoc($result);
    
    // Prepare dokumentasi update
    $dokumentasi = $_POST['dokumentasi'] ?? '';
    $set_dokumentasi = empty($dokumentasi) ? "dokumentasi = NULL" : "dokumentasi = '" . mysqli_real_escape_string($koneksi, $dokumentasi) . "'";
    
    $updates = [$set_dokumentasi];
    
    // Check if dokumentasi changed
    $has_changes = false;
    if ($dokumentasi !== ($current['dokumentasi'] ?? '')) {
        $has_changes = true;
    }
    
    // Process links from jadwal_link
    // Get available jenis_link
    $queryJenis = "SELECT id_jenis_link, nama_jenis_link FROM jenis_link";
    $resultJenis = mysqli_query($koneksi, $queryJenis);
    
    while ($jenisRow = mysqli_fetch_assoc($resultJenis)) {
        $id_jenis_link = $jenisRow['id_jenis_link'];
        $nama_jenis_link = $jenisRow['nama_jenis_link'];
        $link_value = $_POST['link_' . strtolower(str_replace(' ', '_', $nama_jenis_link))] ?? $_POST['link_' . $id_jenis_link] ?? '';
        
        // Check if this link exists in jadwal_link
        $queryCheck = "SELECT id_jadwal_link, link FROM jadwal_link WHERE id_jadwal = " . $id_jadwal . " AND id_jenis_link = " . $id_jenis_link;
        $resultCheck = mysqli_query($koneksi, $queryCheck);
        
        if (mysqli_num_rows($resultCheck) > 0) {
            // Link exists - update it (set to NULL if empty)
            $linkRow = mysqli_fetch_assoc($resultCheck);
            $currentLink = $linkRow['link'] ?? '';
            
            // Only update if value changed
            if ($link_value !== $currentLink) {
                $linkToSet = empty($link_value) ? "NULL" : "'" . mysqli_real_escape_string($koneksi, $link_value) . "'";
                $queryUpdate = "UPDATE jadwal_link SET link = " . $linkToSet . " WHERE id_jadwal = " . $id_jadwal . " AND id_jenis_link = " . $id_jenis_link;
                mysqli_query($koneksi, $queryUpdate);
                $has_changes = true;
            }
        } else {
            // Link doesn't exist - insert it (with NULL if empty, or with value if not empty)
            if (!empty($link_value)) {
                $queryInsert = "INSERT INTO jadwal_link (id_jadwal, id_jenis_link, link) VALUES (" . $id_jadwal . ", " . $id_jenis_link . ", '" . mysqli_real_escape_string($koneksi, $link_value) . "')";
                mysqli_query($koneksi, $queryInsert);
                $has_changes = true;
            }
        }
    }
    
    if (!$has_changes) {
        echo json_encode(['success' => false, 'message' => 'Tidak ada perubahan']);
        exit;
    }
    
    // Update dokumentasi
    $update_query = "UPDATE jadwal SET " . implode(", ", $updates) . " WHERE id_jadwal = " . $id_jadwal;
    mysqli_query($koneksi, $update_query);
    
    // Calculate new status
    $queryFinal = "SELECT dokumentasi FROM jadwal WHERE id_jadwal = " . $id_jadwal;
    $resultFinal = mysqli_query($koneksi, $queryFinal);
    $finalJadwal = mysqli_fetch_assoc($resultFinal);
    
    // Count total jadwal_link records
    $queryTotalLinks = "SELECT COUNT(*) as total FROM jadwal_link WHERE id_jadwal = " . $id_jadwal;
    $resultTotalLinks = mysqli_query($koneksi, $queryTotalLinks);
    $totalLinks = mysqli_fetch_assoc($resultTotalLinks)['total'];
    
    // Count jadwal_link records with non-empty link
    $queryFilledLinks = "SELECT COUNT(*) as filled FROM jadwal_link WHERE id_jadwal = " . $id_jadwal . " AND link IS NOT NULL AND link != ''";
    $resultFilledLinks = mysqli_query($koneksi, $queryFilledLinks);
    $filledLinks = mysqli_fetch_assoc($resultFilledLinks)['filled'];
    
    $new_status = 0;
    
    $doc_filled = !empty($finalJadwal['dokumentasi']);
    
    // Status 2: dokumentasi filled AND all links filled
    if ($doc_filled && $totalLinks > 0 && $filledLinks === $totalLinks) {
        $new_status = 2;
    } 
    // Status 1: dokumentasi filled OR at least one link filled
    else if ($doc_filled || $filledLinks > 0) {
        $new_status = 1;
    }
    // Status 0: nothing filled (default)
    
    $statusQuery = "UPDATE jadwal SET status = " . $new_status . " WHERE id_jadwal = " . $id_jadwal;
    mysqli_query($koneksi, $statusQuery);
    
    echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan', 'new_status' => $new_status]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
