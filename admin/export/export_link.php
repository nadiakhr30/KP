<?php
ob_start();
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

$format = isset($_GET['format']) ? $_GET['format'] : 'print';

// Load PhpSpreadsheet if needed
if ($format == 'excel') {
    require '../../vendor/autoload.php';
}

// Get all links data
$qLink = mysqli_query($koneksi, "
    SELECT 
        l.id_link,
        l.nama_link,
        l.gambar,
        l.link
    FROM link l
    ORDER BY l.nama_link
");
$dataLinks = [];
while ($row = mysqli_fetch_assoc($qLink)) {
    $dataLinks[] = $row;
}

// PRINT FORMAT
if ($format == 'print') {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Data Link</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Jost&display=swap">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/custom.css">
        <style>
            @media print {
                body { margin: 0; padding: 20px; }
                .no-print { display: none; }
                .link-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
                img { max-width: 100%; height: auto; }
            }
            body { padding: 40px; }
            h2 { text-align: center; margin-bottom: 20px; margin-top: 20px; }
            .header-info { text-align: center; margin-bottom: 30px; }
            .link-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 20px;
                margin-bottom: 20px;
            }
            .card {
                border-radius: 12px;
                border: 1px solid #ddd;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                page-break-inside: avoid;
            }
            .card-block {
                padding: 0;
            }
            .card-image-wrapper {
                width: 100%;
                height: 200px;
                overflow: hidden;
                background-color: #f5f5f5;
            }
            .card-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .card-content {
                padding: 15px;
            }
            .card-content h4 {
                margin: 0 0 10px 0;
                color: #333;
                font-weight: 600;
                font-size: 15px;
                word-break: break-word;
            }
            .card-content p {
                margin: 8px 0;
                color: #666;
                font-size: 13px;
                word-break: break-all;
            }
        </style>
    </head>
    <body>
        <div class="no-print d-flex justify-content-between mb-4">
            <button class="btn btn-secondary btn-icon-l" onclick="window.history.back()"><i class="no-print fas fa-arrow-left"></i></button>
            <button class="btn btn-primary btn-icon-l" onclick="window.print()"><i class="no-print fas fa-print"></i></button>
        </div>
        <div class="header-info">
            <h2>Laporan Data Link Sistem Kehumasan</h2>
            <h2>Badan Pusat Statistik Bangkalan</h2>
            <p>Tanggal Cetak: <?= date('d-m-Y H:i:s'); ?></p>
            <p>Total Link: <?= count($dataLinks); ?></p>
        </div>
        <div class="link-grid">
            <?php foreach ($dataLinks as $index => $link) : ?>
            <div class="card">
                <div class="card-block">
                    <div class="card-image-wrapper">
                        <?php if ($link['gambar'] && file_exists('../../uploads/' . $link['gambar'])) : ?>
                            <img src="../../uploads/<?= htmlspecialchars($link['gambar']); ?>" alt="<?= htmlspecialchars($link['nama_link']); ?>" class="card-image">
                        <?php else : ?>
                            <div class="no-image-placeholder">
                                Tidak ada gambar
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h4><?= htmlspecialchars($link['nama_link']); ?></h4>
                        <p><strong>URL:</strong><br><?= htmlspecialchars($link['link']); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-5" style="text-align: center; font-size: 12px;">
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
    $sheet->setTitle('Data Link');
    // Set column widths
    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(30);
    $sheet->getColumnDimension('D')->setWidth(40);

    // Add headers
    $headers = ['No', 'ID', 'Nama Link', 'URL'];
    $sheet->fromArray($headers, NULL, 'A1');

    // Style headers
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '007BFF']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
    ];
    $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
    $sheet->getRowDimension(1)->setRowHeight(25);

    // Add data
    $row = 2;
    foreach ($dataLinks as $index => $link) {
        $sheet->setCellValue("A$row", $index + 1);
        $sheet->setCellValue("B$row", $link['id_link']);
        $sheet->setCellValue("C$row", $link['nama_link']);
        $sheet->setCellValue("D$row", $link['link']);
        $row++;
    }

    // Add summary
    $row += 2;
    $sheet->setCellValue("A$row", "Total Link:");
    $sheet->setCellValue("B$row", count($dataLinks));
    $sheet->getStyle("A$row")->getFont()->setBold(true);
    $sheet->getStyle("B$row")->getFont()->setBold(true);

    $filename = 'Laporan_Link_' . date('Y-m-d_H-i-s') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}
?>
