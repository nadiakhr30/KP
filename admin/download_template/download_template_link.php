<?php
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set column widths
$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(50);

// Add header
$sheet->setCellValue('A1', 'Nama Link');
$sheet->setCellValue('B1', 'Link');

// Style header
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '007BFF']],
    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
];

$sheet->getStyle('A1:B1')->applyFromArray($headerStyle);

// Add sample data
$sheet->setCellValue('A2', 'Contoh Nama Link');
$sheet->setCellValue('B2', 'https://example.com');

// Output the file
$filename = 'Template_Link_' . date('Y-m-d_H-i-s') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
