<?php
include("../koneksi.php");

// Get jadwal detail
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_jadwal = (int)$_GET['id'];
    
    $query = "SELECT dokumentasi, status FROM jadwal WHERE id_jadwal = " . $id_jadwal;
    $result = mysqli_query($koneksi, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Get jadwal links
        $queryLinks = "
            SELECT jl.id_jadwal_link, jl.id_jenis_link, jenis_link.nama_jenis_link, jl.link
            FROM jadwal_link jl
            JOIN jenis_link ON jl.id_jenis_link = jenis_link.id_jenis_link
            WHERE jl.id_jadwal = " . $id_jadwal;
        $resultLinks = mysqli_query($koneksi, $queryLinks);
        
        $links = [];
        while ($linkRow = mysqli_fetch_assoc($resultLinks)) {
            $links[$linkRow['nama_jenis_link']] = [
                'id_jenis_link' => $linkRow['id_jenis_link'],
                'link' => $linkRow['link'] ?: ''
            ];
        }
        
        echo json_encode([
            'dokumentasi' => $row['dokumentasi'] ?: '',
            'links' => $links,
            'status' => $row['status']
        ]);
    } else {
        echo json_encode(['error' => 'Data tidak ditemukan']);
    }
} else {
    echo json_encode(['error' => 'ID tidak valid']);
}
?>

