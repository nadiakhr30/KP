<?php
ob_start();
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

$format = isset($_GET['format']) ? $_GET['format'] : 'print';
$subId = isset($_GET['sub']) ? (int)$_GET['sub'] : 0;

// Load PhpSpreadsheet if needed
if ($format == 'excel') {
    require '../../vendor/autoload.php';
}

// Get sub_jenis info
$subName = 'Semua Media';
$jenisName = 'Media';
if ($subId > 0) {
    $qSub = mysqli_query($koneksi, "SELECT s.nama_sub_jenis, j.nama_jenis FROM sub_jenis s JOIN jenis j ON s.id_jenis = j.id_jenis WHERE s.id_sub_jenis = $subId");
    if ($qSub && mysqli_num_rows($qSub) > 0) {
        $rSub = mysqli_fetch_assoc($qSub);
        $subName = $rSub['nama_sub_jenis'];
        $jenisName = $rSub['nama_jenis'];
    }
}

// Get media data
if ($subId > 0) {
    $qMedia = mysqli_query($koneksi, "
        SELECT 
            m.id_media,
            m.judul,
            m.topik,
            m.deskripsi,
            m.link,
            s.nama_sub_jenis
        FROM media m
        INNER JOIN sub_jenis s ON m.id_sub_jenis = s.id_sub_jenis
        WHERE m.id_sub_jenis = $subId
        ORDER BY m.judul
    ");
} else {
    $qMedia = mysqli_query($koneksi, "
        SELECT 
            m.id_media,
            m.judul,
            m.topik,
            m.deskripsi,
            m.link,
            s.nama_sub_jenis
        FROM media m
        INNER JOIN sub_jenis s ON m.id_sub_jenis = s.id_sub_jenis
        ORDER BY m.judul
    ");
}

$dataMedia = [];
while ($row = mysqli_fetch_assoc($qMedia)) {
    $dataMedia[] = $row;
}

// PRINT FORMAT
if ($format == 'print') {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Data <?= htmlspecialchars($jenisName) ?></title>
        <link rel="icon" href="../assets/images/logo_bps.ico" type="image/x-icon">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/custom.css">
        <style>
            @media print {
                .no-print {
                    display: none;
                }
            }
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f5f5f5;
                padding: 80px;
            }
            .header-info {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 3px solid #000000;
                padding-bottom: 15px;
            }
            .header-info h2 {
                margin: 5px 0;
                font-weight: 700;
                color: #2c3e50;
            }
            .header-info p {
                margin: 3px 0;
                color: #7f8c8d;
                font-size: 14px;
            }
            table {
                background: white;
                border-collapse: collapse;
            }
            th {
                background-color: #007bff;
                color: white;
                font-weight: 600;
                padding: 12px;
                text-align: left;
            }
            td {
                padding: 10px 12px;
                border-bottom: 1px solid #ddd;
                color: #666;
                font-size: 13px;
            }
            tr:hover {
                background-color: #f9f9f9;
            }
        </style>
    </head>
    <body>
        <div class="no-print d-flex justify-content-between mb-4">
            <button class="btn btn-secondary btn-icon-l" onclick="window.history.back()"><i class="fas fa-arrow-left no-print"></i></button>
            <button class="btn btn-primary btn-icon-l" onclick="window.print()"><i class="fas fa-print no-print"></i></button>
        </div>

        <div class="header-info">
            <h2>Laporan Data <?= htmlspecialchars($jenisName); ?> <?= htmlspecialchars($subName); ?></h2>
            <h2>Badan Pusat Statistik Bangkalan</h2>
            <p>Tanggal Cetak: <?= date('d-m-Y H:i:s'); ?></p>
            <p>Total <?= htmlspecialchars($jenisName); ?>: <?= count($dataMedia); ?></p>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Judul</th>
                    <th style="width: 15%;">Topik</th>
                    <th style="width: 30%;">Deskripsi</th>
                    <th style="width: 30%;">Link</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($dataMedia) === 0): ?>
                <tr>
                    <td colspan="5" style="text-align: center;"><?php echo 'Tidak ada data ' . htmlspecialchars(strtolower($jenisName)); ?></td>
                </tr>
                <?php else: ?>
                <?php foreach ($dataMedia as $index => $media) : ?>
                <tr>
                    <td style="text-align: center;"><?= $index + 1; ?></td>
                    <td><?= htmlspecialchars($media['judul']); ?></td>
                    <td><?= htmlspecialchars($media['topik']); ?></td>
                    <td><?= htmlspecialchars(substr($media['deskripsi'], 0, 100)); ?><?= strlen($media['deskripsi']) > 100 ? '...' : ''; ?></td>
                    <td><?= htmlspecialchars($media['link'] ?? '-'); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-5" style="text-align: center; font-size: 12px; color: #7f8c8d;">
            <p>Laporan ini digenerate otomatis pada <?= date('d-m-Y H:i:s'); ?></p>
        </div>
    </body>
    </html>
    <?php
    exit();
}
// EXCEL FORMAT
else if ($format == 'excel') {
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Data ' . $jenisName);

    // Set column widths
    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(25);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(30);
    $sheet->getColumnDimension('E')->setWidth(40);

    // Add headers
    $headers = ['No', 'Judul', 'Topik', 'Deskripsi', 'Link'];
    $sheet->insertNewRowBefore(1, 1);
    $sheet->fromArray($headers, NULL, 'A1');

    // Style headers
    $headerFill = new \PhpOffice\PhpSpreadsheet\Style\Fill();
    $headerFill->setFillType('solid');
    $headerFill->setStartColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF007BFF'));
    
    for ($col = 1; $col <= 5; $col++) {
        $cell = $sheet->getCellByColumnAndRow($col, 1);
        $cell->getStyle()->getFont()->setBold(true);
        $cell->getStyle()->getFont()->getColor()->setRGB('FFFFFF');
        $cell->getStyle()->getFont()->setSize(12);
        $cell->getStyle()->getFill()->setFillType('solid');
        $cell->getStyle()->getFill()->getStartColor()->setRGB('007BFF');
        $cell->getStyle()->getAlignment()->setHorizontal('center');
        $cell->getStyle()->getAlignment()->setVertical('center');
        $cell->getStyle()->getAlignment()->setWrapText(true);
    }
    $sheet->getRowDimension(1)->setRowHeight(25);

    // Add data
    $row = 2;
    foreach ($dataMedia as $index => $media) {
        $sheet->setCellValue("A$row", $index + 1);
        $sheet->setCellValue("B$row", $media['judul']);
        $sheet->setCellValue("C$row", $media['topik']);
        $sheet->setCellValue("D$row", substr($media['deskripsi'], 0, 100));
        $sheet->setCellValue("E$row", $media['link'] ?? '-');
        $row++;
    }

    // Add summary
    $row += 2;
    $sheet->setCellValue("A$row", "Total " . $jenisName . ":");
    $sheet->setCellValue("B$row", count($dataMedia));
    $sheet->getStyle("A$row")->getFont()->setBold(true);
    $sheet->getStyle("B$row")->getFont()->setBold(true);

    $filename = 'Laporan_' . htmlspecialchars(str_replace(' ', '_', $jenisName)) . '_' . htmlspecialchars(str_replace(' ', '_', $subName)) . '_' . date('Y-m-d_H-i-s') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}
?>
