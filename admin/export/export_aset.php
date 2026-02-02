<?php
ob_start();
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

$format = isset($_GET['format']) ? $_GET['format'] : 'print';
$jenisId = isset($_GET['jenis']) ? (int)$_GET['jenis'] : 0;

// Load PhpSpreadsheet if needed
if ($format == 'excel') {
    require '../../vendor/autoload.php';
}

// Get jenis info
$jeniName = 'Semua Aset';
if ($jenisId > 0) {
    $qJenis = mysqli_query($koneksi, "SELECT nama_jenis_aset FROM jenis_aset WHERE id_jenis_aset = $jenisId");
    if ($qJenis && mysqli_num_rows($qJenis) > 0) {
        $rJenis = mysqli_fetch_assoc($qJenis);
        $jeniName = $rJenis['nama_jenis_aset'];
    }
}

// Get aset data
if ($jenisId > 0) {
    $qAset = mysqli_query($koneksi, "
        SELECT 
            a.id_aset,
            a.nama,
            a.keterangan,
            a.link,
            ja.nama_jenis_aset
        FROM aset a
        INNER JOIN jenis_aset ja ON a.id_jenis_aset = ja.id_jenis_aset
        WHERE a.id_jenis_aset = $jenisId
        ORDER BY a.nama
    ");
} else {
    $qAset = mysqli_query($koneksi, "
        SELECT 
            a.id_aset,
            a.nama,
            a.keterangan,
            a.link,
            ja.nama_jenis_aset
        FROM aset a
        INNER JOIN jenis_aset ja ON a.id_jenis_aset = ja.id_jenis_aset
        ORDER BY a.nama
    ");
}

$dataAset = [];
while ($row = mysqli_fetch_assoc($qAset)) {
    $dataAset[] = $row;
}

// PRINT FORMAT
if ($format == 'print') {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Data Aset</title>
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
            <h2>Laporan Data Aset <?= htmlspecialchars($jeniName); ?> Humas</h2>
            <h2>Badan Pusat Statistik Bangkalan</h2>
            <p>Tanggal Cetak: <?= date('d-m-Y H:i:s'); ?></p>
            <p>Total Aset: <?= count($dataAset); ?></p>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Nama Aset</th>
                    <th style="width: 30%;">Keterangan</th>
                    <th style="width: 30%;">Link</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($dataAset) === 0): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data aset</td>
                </tr>
                <?php else: ?>
                <?php foreach ($dataAset as $index => $aset) : ?>
                <tr>
                    <td style="text-align: center;"><?= $index + 1; ?></td>
                    <td><?= htmlspecialchars($aset['nama']); ?></td>
                    <td><?= htmlspecialchars(substr($aset['keterangan'], 0, 100)); ?><?= strlen($aset['keterangan']) > 100 ? '...' : ''; ?></td>
                    <td><?= htmlspecialchars($aset['link'] ?? '-'); ?></td>
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
    $sheet->setTitle('Data Aset');

    // Set column widths
    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(25);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(35);
    $sheet->getColumnDimension('E')->setWidth(40);

    // Add headers
    $headers = ['No', 'Nama Aset', 'Jenis', 'Keterangan', 'Link'];
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
    foreach ($dataAset as $index => $aset) {
        $sheet->setCellValue("A$row", $index + 1);
        $sheet->setCellValue("B$row", $aset['nama']);
        $sheet->setCellValue("C$row", $aset['nama_jenis_aset']);
        $sheet->setCellValue("D$row", substr($aset['keterangan'], 0, 100));
        $sheet->setCellValue("E$row", $aset['link'] ?? '-');
        $row++;
    }

    // Add summary
    $row += 2;
    $sheet->setCellValue("A$row", "Total Aset:");
    $sheet->setCellValue("B$row", count($dataAset));
    $sheet->getStyle("A$row")->getFont()->setBold(true);
    $sheet->getStyle("B$row")->getFont()->setBold(true);

    $filename = 'Laporan_Aset_' . htmlspecialchars(str_replace(' ', '_', $jeniName)) . '_' . date('Y-m-d_H-i-s') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}
?>
