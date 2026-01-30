<?php
ob_start();
session_start();
include_once("../../koneksi.php");

if (!isset(<?php
ob_start();
session_start();
include_once("../../koneksi.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

$format = isset($_GET['format']) ? $_GET['format'] : 'print';

if ($format == 'excel') {
    require '../../vendor/autoload.php';
}

$qData = mysqli_query($koneksi, "SELECT * FROM skill ORDER BY nama_skill");
$dataList = [];
while ($row = mysqli_fetch_assoc($qData)) {
    $dataList[] = $row;
}

if ($format == 'print') {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Data Skill</title>
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
            <button class="btn btn-secondary btn-icon-l" onclick="window.history.back()"><i class="no-print fas fa-arrow-left"></i></button>
            <button class="btn btn-primary btn-icon-l" onclick="window.print()"><i class="no-print fas fa-print"></i></button>
        </div>
        <div class="header-info">
            <h2>Laporan Data Skill Sistem Kehumasan</h2>
            <h2>Badan Pusat Statistik Bangkalan</h2>
            <p>Tanggal Cetak: <?= date('d-m-Y H:i:s'); ?></p>
            <p>Total Data: <?= count($dataList); ?></p>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 10%;">ID</th>
                    <th style="width: 85%;">Nama Skill</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataList as $index => $item) : ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $item['id_skill'] ?></td>
                    <td><?= htmlspecialchars($item['nama_skill']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-5" style="text-align: center; font-size: 12px;">
            <p>Laporan ini digenerate otomatis pada <?= date('d-m-Y H:i:s'); ?></p>
        </div>
    </body>
    </html>
    <?php
    exit();
}
else if ($format == 'excel') {
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Data Skill');
    
    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(10);
    $sheet->getColumnDimension('C')->setWidth(30);

    $headers = ['No', 'ID', 'Nama Skill'];
    $sheet->fromArray($headers, NULL, 'A1');

    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '007BFF']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
    ];
    $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);
    $sheet->getRowDimension(1)->setRowHeight(25);

    $row = 2;
    foreach ($dataList as $index => $item) {
        $sheet->setCellValue("A$row", $index + 1);
        $sheet->setCellValue("B$row", $item['id_skill']);
        $sheet->setCellValue("C$row", $item['nama_skill']);
        $row++;
    }

    $row += 2;
    $sheet->setCellValue("A$row", "Total:");
    $sheet->setCellValue("B$row", count($dataList));
    $sheet->getStyle("A$row")->getFont()->setBold(true);
    $sheet->getStyle("B$row")->getFont()->setBold(true);

    $filename = 'Laporan_Skill_' . date('Y-m-d_H-i-s') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}
?>
SESSION['pegawai']) || $_SESSION['role'] != "Admin") {
    header('Location: ../index.php');
    exit();
}

$format = isset($_GET['format']) ? $_GET['format'] : 'print';

if ($format == 'excel') {
    require '../../vendor/autoload.php';
}

$qData = mysqli_query($koneksi, "SELECT * FROM skill ORDER BY nama_skill");
$dataList = [];
while ($row = mysqli_fetch_assoc($qData)) {
    $dataList[] = $row;
}

if ($format == 'print') {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Data Skill</title>
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
            <button class="btn btn-secondary btn-icon-l" onclick="window.history.back()"><i class="no-print fas fa-arrow-left"></i></button>
            <button class="btn btn-primary btn-icon-l" onclick="window.print()"><i class="no-print fas fa-print"></i></button>
        </div>
        <div class="header-info">
            <h2>Laporan Data Skill Sistem Kehumasan</h2>
            <h2>Badan Pusat Statistik Bangkalan</h2>
            <p>Tanggal Cetak: <?= date('d-m-Y H:i:s'); ?></p>
            <p>Total Data: <?= count($dataList); ?></p>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 10%;">ID</th>
                    <th style="width: 85%;">Nama Skill</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataList as $index => $item) : ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $item['id_skill'] ?></td>
                    <td><?= htmlspecialchars($item['nama_skill']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-5" style="text-align: center; font-size: 12px;">
            <p>Laporan ini digenerate otomatis pada <?= date('d-m-Y H:i:s'); ?></p>
        </div>
    </body>
    </html>
    <?php
    exit();
}
else if ($format == 'excel') {
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Data Skill');
    
    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(10);
    $sheet->getColumnDimension('C')->setWidth(30);

    $headers = ['No', 'ID', 'Nama Skill'];
    $sheet->fromArray($headers, NULL, 'A1');

    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '007BFF']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
    ];
    $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);
    $sheet->getRowDimension(1)->setRowHeight(25);

    $row = 2;
    foreach ($dataList as $index => $item) {
        $sheet->setCellValue("A$row", $index + 1);
        $sheet->setCellValue("B$row", $item['id_skill']);
        $sheet->setCellValue("C$row", $item['nama_skill']);
        $row++;
    }

    $row += 2;
    $sheet->setCellValue("A$row", "Total:");
    $sheet->setCellValue("B$row", count($dataList));
    $sheet->getStyle("A$row")->getFont()->setBold(true);
    $sheet->getStyle("B$row")->getFont()->setBold(true);

    $filename = 'Laporan_Skill_' . date('Y-m-d_H-i-s') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}
?>

