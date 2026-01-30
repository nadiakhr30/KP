<?php
ob_start();
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

$format = isset($_GET['format']) ? $_GET['format'] : 'print';

// Load PhpSpreadsheet only for excel
if ($format === 'excel') {
    require '../../vendor/autoload.php';
}

// Fetch jadwal data
$q = mysqli_query($koneksi, "SELECT id_jadwal, topik, judul_kegiatan, tim, tanggal_penugasan, tanggal_rilis, dokumentasi, status FROM jadwal ORDER BY tanggal_rilis DESC");
$data = [];
while ($r = mysqli_fetch_assoc($q)) {
    $data[] = $r;
}

// Get PIC types and Link types to create separate columns
$picTypes = [];
$qPicTypes = mysqli_query($koneksi, "SELECT nama_jenis_pic FROM jenis_pic ORDER BY nama_jenis_pic");
if ($qPicTypes) {
    while ($pt = mysqli_fetch_assoc($qPicTypes)) {
        $picTypes[] = $pt['nama_jenis_pic'];
    }
}

$linkTypes = [];
$qLinkTypes = mysqli_query($koneksi, "SELECT nama_jenis_link FROM jenis_link ORDER BY nama_jenis_link");
if ($qLinkTypes) {
    while ($lt = mysqli_fetch_assoc($qLinkTypes)) {
        $linkTypes[] = $lt['nama_jenis_link'];
    }
}

if ($format === 'print') {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Jadwal Konten</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/custom.css">
        <style>
            @media print { .no-print { display: none; } }
            body { font-family: 'Poppins', sans-serif; background-color: #f5f5f5; padding: 80px; }
            .header-info { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #000000; padding-bottom: 15px; }
            .header-info h2 { margin: 5px 0; font-weight: 700; color: #2c3e50; }
            .header-info p { margin: 3px 0; color: #7f8c8d; font-size: 14px; }
            .table-report { background: #fff; padding: 10px; border-radius: 8px; }
            .table-report .table { width: 100%; table-layout: fixed; }
            .table-report .table th, .table-report .table td { word-wrap: break-word; white-space: normal; }
            @media (max-width: 1200px) {
                .table-report { padding: 6px; }
                body { padding: 20px; }
            }
        </style>
    </head>
    <body>
        <div class="no-print d-flex justify-content-between mb-4">
            <button class="no-print btn btn-secondary btn-icon-l" onclick="window.history.back()"><i class="no-print fas fa-arrow-left"></i></button>
            <button class="no-print btn btn-primary btn-icon-l" onclick="window.print()"><i class="no-print fas fa-print"></i></button>
        </div>
        <div class="header-info">
            <h2>Laporan Jadwal Konten</h2>
            <h2>Badan Pusat Statistik Bangkalan</h2>
            <p>Tanggal Cetak: <?= date('d-m-Y H:i:s'); ?></p>
            <p>Total Jadwal: <?= count($data); ?></p>
        </div>

        <div class="table-report">
            <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:40px;">No</th>
                        <th style="width:60px;">ID</th>
                        <th>Topik</th>
                        <th>Judul Kegiatan</th>
                        <th>Tim</th>
                        <th style="width:110px;">Tgl Penugasan</th>
                        <th style="width:110px;">Target Rilis</th>
                        <?php foreach ($picTypes as $ptype): ?>
                            <th><?= htmlspecialchars($ptype) ?></th>
                        <?php endforeach; ?>
                        <?php foreach ($linkTypes as $ltype): ?>
                            <th><?= htmlspecialchars($ltype) ?></th>
                        <?php endforeach; ?>
                        <th style="width:140px;">Dokumentasi</th>
                        <th style="width:120px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $i => $row):
                    $id = (int)$row['id_jadwal'];
                    // PIC map per type
                    $picMap = [];
                    $qPic = mysqli_query($koneksi, "SELECT jp.nama_jenis_pic, u.nama FROM pic p JOIN pegawai u ON p.nip = u.nip JOIN jenis_pic jp ON p.id_jenis_pic = jp.id_jenis_pic WHERE p.id_jadwal = " . $id . " ORDER BY jp.nama_jenis_pic");
                    if ($qPic) { while ($p = mysqli_fetch_assoc($qPic)) { $picMap[$p['nama_jenis_pic']] = $p['nama']; } }

                    // Link map per type
                    $linkMap = [];
                    $qLink = mysqli_query($koneksi, "SELECT jenis_link.nama_jenis_link, jl.link FROM jadwal_link jl JOIN jenis_link ON jl.id_jenis_link = jenis_link.id_jenis_link WHERE jl.id_jadwal = " . $id . " ORDER BY jenis_link.nama_jenis_link");
                    if ($qLink) { while ($l = mysqli_fetch_assoc($qLink)) { $linkMap[$l['nama_jenis_link']] = $l['link']; } }

                    switch ((int)$row['status']) { case 1: $statusText = 'Sedang Dikerjakan'; break; case 2: $statusText = 'Selesai'; break; default: $statusText = 'Belum Dikerjakan'; }
                ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $id ?></td>
                        <td><?= htmlspecialchars($row['topik']) ?></td>
                        <td><?= htmlspecialchars($row['judul_kegiatan']) ?></td>
                        <td><?= htmlspecialchars($row['tim']) ?></td>
                        <td><?= $row['tanggal_penugasan'] ?></td>
                        <td><?= $row['tanggal_rilis'] ?></td>
                        <?php foreach ($picTypes as $ptype): ?>
                            <td><?= htmlspecialchars($picMap[$ptype] ?? '-') ?></td>
                        <?php endforeach; ?>
                        <?php foreach ($linkTypes as $ltype): ?>
                            <td>
                                <?php $url = $linkMap[$ltype] ?? ''; ?>
                                <?php if (!empty($url)): ?>
                                    <?= htmlspecialchars($url) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                        <td><?= htmlspecialchars($row['dokumentasi']) ?></td>
                        <td><?= $statusText ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>

        <div class="mt-3 text-center" style="font-size:12px;">Laporan ini digenerate otomatis pada <?= date('d-m-Y H:i:s'); ?></div>
    </body>
    </html>
    <?php
    exit();
}

// Helper function to convert column number to letter
function getColumnLetter($col) {
    $letter = '';
    while ($col > 0) {
        $col--;
        $letter = chr(65 + ($col % 26)) . $letter;
        $col = intdiv($col, 26);
    }
    return $letter;
}

// Excel export via PhpSpreadsheet
if ($format === 'excel') {
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Jadwal Konten');

    // Build headers dynamically: base + pic types + link types + footer
    $headers = array_merge(
        ['No','ID','Topik','Judul Kegiatan','Tim','Tanggal Penugasan','Target Rilis'],
        $picTypes,
        $linkTypes,
        ['Dokumentasi','Status']
    );
    $sheet->fromArray($headers, NULL, 'A1');

    // Style header
    $sheet->getStyle('A1:ZZ1')->getFont()->setBold(true);
    $sheet->getDefaultColumnDimension()->setWidth(20);

    $rowNum = 2;
    foreach ($data as $index => $row) {
        $id = (int)$row['id_jadwal'];

        // PIC map per type
        $picMap = [];
        $qPic = mysqli_query($koneksi, "SELECT jp.nama_jenis_pic, u.nama FROM pic p JOIN pegawai u ON p.nip = u.nip JOIN jenis_pic jp ON p.id_jenis_pic = jp.id_jenis_pic WHERE p.id_jadwal = " . $id . " ORDER BY jp.nama_jenis_pic");
        if ($qPic) { while ($p = mysqli_fetch_assoc($qPic)) { $picMap[$p['nama_jenis_pic']] = $p['nama']; } }

        // Link map per type
        $linkMap = [];
        $qLink = mysqli_query($koneksi, "SELECT jenis_link.nama_jenis_link, jl.link FROM jadwal_link jl JOIN jenis_link ON jl.id_jenis_link = jenis_link.id_jenis_link WHERE jl.id_jadwal = " . $id . " ORDER BY jenis_link.nama_jenis_link");
        if ($qLink) { while ($l = mysqli_fetch_assoc($qLink)) { $linkMap[$l['nama_jenis_link']] = $l['link']; } }

        // Start writing columns by index
        $col = 1;
        $colLetter = getColumnLetter($col++);
        $sheet->setCellValue($colLetter . $rowNum, $index + 1); // No
        
        $colLetter = getColumnLetter($col++);
        $sheet->setCellValue($colLetter . $rowNum, $id); // ID
        
        $colLetter = getColumnLetter($col++);
        $sheet->setCellValue($colLetter . $rowNum, $row['topik']);
        
        $colLetter = getColumnLetter($col++);
        $sheet->setCellValue($colLetter . $rowNum, $row['judul_kegiatan']);
        
        $colLetter = getColumnLetter($col++);
        $sheet->setCellValue($colLetter . $rowNum, $row['tim']);
        
        $colLetter = getColumnLetter($col++);
        $sheet->setCellValue($colLetter . $rowNum, $row['tanggal_penugasan']);
        
        $colLetter = getColumnLetter($col++);
        $sheet->setCellValue($colLetter . $rowNum, $row['tanggal_rilis']);

        // PIC columns
        foreach ($picTypes as $ptype) {
            $colLetter = getColumnLetter($col++);
            $sheet->setCellValue($colLetter . $rowNum, $picMap[$ptype] ?? '-');
        }

        // Link columns
        foreach ($linkTypes as $ltype) {
            $colLetter = getColumnLetter($col++);
            $sheet->setCellValue($colLetter . $rowNum, $linkMap[$ltype] ?? '');
        }

        // Dokumentasi & Status
        switch ((int)$row['status']) { case 1: $statusText = 'Sedang Dikerjakan'; break; case 2: $statusText = 'Selesai'; break; default: $statusText = 'Belum Dikerjakan'; }
        $colLetter = getColumnLetter($col++);
        $sheet->setCellValue($colLetter . $rowNum, $row['dokumentasi']);
        
        $colLetter = getColumnLetter($col++);
        $sheet->setCellValue($colLetter . $rowNum, $statusText);

        $rowNum++;
    }

    // Summary
    $sheet->setCellValue('A' . ($rowNum + 1), 'Total Jadwal:');
    $sheet->setCellValue('B' . ($rowNum + 1), count($data));

    $filename = 'Laporan_Jadwal_' . date('Y-m-d_H-i-s') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

// Fallback: if unknown format, redirect back
header('Location: ../jadwal_konten_humas.php');
exit();

