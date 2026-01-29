<?php
include("../koneksi.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_jadwal = (int)($_POST['id_jadwal'] ?? 0);
    
    if ($id_jadwal <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID jadwal tidak valid']);
        exit;
    }
    
    // Get current data
    $query = "SELECT link_instagram, link_facebook, link_youtube, link_website, dokumentasi FROM jadwal WHERE id_jadwal = " . $id_jadwal;
    $result = mysqli_query($koneksi, $query);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        echo json_encode(['success' => false, 'message' => 'Jadwal tidak ditemukan']);
        exit;
    }
    
    $current = mysqli_fetch_assoc($result);
    
    // Prepare update values - semua field selalu dikirim dari form
    $dokumentasi = $_POST['dokumentasi'] ?? '';
    $link_instagram = $_POST['link_instagram'] ?? '';
    $link_facebook = $_POST['link_facebook'] ?? '';
    $link_youtube = $_POST['link_youtube'] ?? '';
    $link_website = $_POST['link_website'] ?? '';
    
    // Dokumentasi: empty = NULL, filled = URL
    $set_dokumentasi = empty($dokumentasi) ? "dokumentasi = NULL" : "dokumentasi = '" . mysqli_real_escape_string($koneksi, $dokumentasi) . "'";
    
    // Links: empty = "-", filled = URL
    $set_instagram = "link_instagram = " . (empty($link_instagram) ? "'-'" : "'" . mysqli_real_escape_string($koneksi, $link_instagram) . "'");
    $set_facebook = "link_facebook = " . (empty($link_facebook) ? "'-'" : "'" . mysqli_real_escape_string($koneksi, $link_facebook) . "'");
    $set_youtube = "link_youtube = " . (empty($link_youtube) ? "'-'" : "'" . mysqli_real_escape_string($koneksi, $link_youtube) . "'");
    $set_website = "link_website = " . (empty($link_website) ? "'-'" : "'" . mysqli_real_escape_string($koneksi, $link_website) . "'");
    
    $updates = [$set_dokumentasi, $set_instagram, $set_facebook, $set_youtube, $set_website];
    
    // Check if there are any actual changes
    $has_changes = false;
    if ($dokumentasi !== ($current['dokumentasi'] ?? '')) $has_changes = true;
    if ($link_instagram !== ($current['link_instagram'] ?? '')) $has_changes = true;
    if ($link_facebook !== ($current['link_facebook'] ?? '')) $has_changes = true;
    if ($link_youtube !== ($current['link_youtube'] ?? '')) $has_changes = true;
    if ($link_website !== ($current['link_website'] ?? '')) $has_changes = true;
    
    if (!$has_changes) {
        echo json_encode(['success' => false, 'message' => 'Tidak ada perubahan']);
        exit;
    }
    
    // Calculate new status based on final values in database
    $query = "SELECT dokumentasi, link_instagram, link_facebook, link_youtube, link_website FROM jadwal WHERE id_jadwal = " . $id_jadwal;
    $result = mysqli_query($koneksi, $query);
    $final = mysqli_fetch_assoc($result);
    
    $new_status = 0;
    
    // Final values
    $doc = $final['dokumentasi'];
    $ig = $final['link_instagram'];
    $fb = $final['link_facebook'];
    $yt = $final['link_youtube'];
    $web = $final['link_website'];
    
    // Count berapa banyak link yang sudah terisi (bukan NULL dan bukan "-")
    $filled_count = 0;
    if (!empty($doc)) $filled_count++; // dokumentasi
    if (!empty($ig) && $ig !== '-') $filled_count++;
    if (!empty($fb) && $fb !== '-') $filled_count++;
    if (!empty($yt) && $yt !== '-') $filled_count++;
    if (!empty($web) && $web !== '-') $filled_count++;
    
    // Status 2: dokumentasi terisi AND semua link bukan NULL dan bukan "-" (4 links filled)
    if (!empty($doc) && 
        !empty($ig) && $ig !== '-' && 
        !empty($fb) && $fb !== '-' && 
        !empty($yt) && $yt !== '-' && 
        !empty($web) && $web !== '-') {
        $new_status = 2;
    } 
    // Status 1: ada yang terisi (dokumentasi atau minimal 1 link terisi/placeholder "-")
    else if (!empty($doc) || !empty($ig) || !empty($fb) || !empty($yt) || !empty($web)) {
        $new_status = 1;
    }
    // Status 0: semua kosong (hanya NULL)
    else {
        $new_status = 0;
    }
    
    $updates[] = "status = " . $new_status;
    
    $update_query = "UPDATE jadwal SET " . implode(", ", $updates) . " WHERE id_jadwal = " . $id_jadwal;
    
    if (mysqli_query($koneksi, $update_query)) {
        echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan', 'new_status' => $new_status]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data: ' . mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
