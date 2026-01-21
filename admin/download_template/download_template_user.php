<?php

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Create new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set column widths
$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(10);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(12);
$sheet->getColumnDimension('H')->setWidth(12);
$sheet->getColumnDimension('I')->setWidth(12);
$sheet->getColumnDimension('J')->setWidth(12);
$sheet->getColumnDimension('K')->setWidth(20);

// Set header row
$headers = ['NIP', 'Nama', 'Email', 'Password', 'Status', 'Nomor Telepon', 'ID Jabatan', 'ID Role', 'ID PPID', 'ID Halo PST', 'ID Skill'];
$sheet->fromArray($headers, NULL, 'A1');

// Style header row
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 12
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '007BFF']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ]
    ]
];

for ($col = 'A'; $col <= 'K'; $col++) {
    $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
}

// Add example rows (you can customize these)
$exampleData = [
    ['19850315', 'Budi Santoso', 'budi@bps.go.id', 'password123', 1, '081234567890', 1, 2, 1, 1, '1,2,3'],
    ['19880620', 'Siti Nurhaliza', 'siti@bps.go.id', 'password456', 1, '082345678901', 2, 2, 1, 2, '2,4'],
    ['19920410', 'Ahmad Wijaya', 'ahmad@bps.go.id', 'password789', 1, '083456789012', 3, 2, 2, 1, '3,5,6'],
];

$row = 2;
foreach ($exampleData as $data) {
    $sheet->fromArray($data, NULL, 'A' . $row);
    
    // Style data rows
    $dataStyle = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_CENTER
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ]
        ]
    ];
    
    for ($col = 'A'; $col <= 'K'; $col++) {
        $sheet->getStyle($col . $row)->applyFromArray($dataStyle);
    }
    
    $row++;
}

// Freeze header row
$sheet->freezePane('A2');

// Create writer and output file
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

// Set headers for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Template_Import_User_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>