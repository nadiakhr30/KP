<?php
include("../koneksi.php");

// Get jadwal detail
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_jadwal = (int)$_GET['id'];
    
    $query = "SELECT dokumentasi, link_instagram, link_facebook, link_youtube, link_website, status FROM jadwal WHERE id_jadwal = " . $id_jadwal;
    $result = mysqli_query($koneksi, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'dokumentasi' => $row['dokumentasi'] ?: '',
            'link_instagram' => $row['link_instagram'] ?: '',
            'link_facebook' => $row['link_facebook'] ?: '',
            'link_youtube' => $row['link_youtube'] ?: '',
            'link_website' => $row['link_website'] ?: '',
            'status' => $row['status']
        ]);
    } else {
        echo json_encode(['error' => 'Data tidak ditemukan']);
    }
} else {
    echo json_encode(['error' => 'ID tidak valid']);
}
?>
