<?php
ob_start();
session_start();
include_once("../koneksi.php");

// Fetch jadwal data for calendar
$qKalender = mysqli_query($koneksi, "
    SELECT 
        j.id_jadwal,
        j.topik,
        j.judul_kegiatan,
        j.tanggal_penugasan,
        j.tanggal_rilis,
        j.tim,
        j.keterangan,
        j.status,
        j.dokumentasi
    FROM jadwal j
    ORDER BY j.tanggal_rilis DESC
");

if (!$qKalender) {
    die("Database error: " . mysqli_error($koneksi));
}

echo "Number of rows: " . mysqli_num_rows($qKalender) . "\n\n";

$jadwalkalender = [];
while ($row = mysqli_fetch_assoc($qKalender)) {
    echo "Processing jadwal ID: " . $row['id_jadwal'] . "\n";
    
    $id_jadwal = $row['id_jadwal'];
    $qPic = mysqli_query($koneksi, "
        SELECT u.nama, jp.nama_jenis_pic
        FROM pic p
        JOIN user u ON p.nip = u.nip
        JOIN jenis_pic jp ON p.id_jenis_pic = jp.id_jenis_pic
        WHERE p.id_jadwal = " . (int)$id_jadwal . "
        ORDER BY jp.nama_jenis_pic
    ");
    
    $picData = [];
    if ($qPic) {
        while ($pic = mysqli_fetch_assoc($qPic)) {
            $picData[$pic['nama_jenis_pic']] = $pic['nama'];
        }
    }
    
    // Get links for this jadwal from the new schema
    $qLinks = mysqli_query($koneksi, "
        SELECT jl.id_jenis_link, jjl.nama_jenis_link, jl.link
        FROM jadwal_link jl
        JOIN jenis_link jjl ON jl.id_jenis_link = jjl.id_jenis_link
        WHERE jl.id_jadwal = " . (int)$id_jadwal . "
        ORDER BY jjl.nama_jenis_link
    ");
    
    $linksData = [];
    if ($qLinks) {
        while ($link = mysqli_fetch_assoc($qLinks)) {
            $linksData[$link['nama_jenis_link']] = [
                'id_jenis_link' => $link['id_jenis_link'],
                'link' => $link['link']
            ];
        }
    }
    
    // Set color based on status
    $color = match ($row['status']) {
        0 => '#e84118',
        1 => '#fbc531',
        2 => '#44bd32',
        default => '#718093',
    };
    
    $picText = [];
    foreach ($picData as $jenis => $nama) {
        $picText[] = "<b>$jenis:</b> $nama";
    }
    $picDisplay = count($picText) > 0 ? implode("<br>", $picText) : "-";
    
    $jadwalkalender[] = [
        'id'    => $row['id_jadwal'],
        'title' => $row['judul_kegiatan'],
        'start' => $row['tanggal_rilis'],
        'color' => $color,
        'extendedProps' => [
            'topik' => $row['topik'],
            'tanggal_penugasan' => $row['tanggal_penugasan'],
            'tim' => $row['tim'],
            'status' => (int)$row['status'],
            'keterangan' => $row['keterangan'],
            'pic_display' => $picDisplay,
            'pic_data' => $picData,
            'dokumentasi' => $row['dokumentasi'],
            'links_data' => $linksData
        ]
    ];
}

echo "\nFinal JSON output:\n";
echo json_encode($jadwalkalender, JSON_PRETTY_PRINT);
?>
