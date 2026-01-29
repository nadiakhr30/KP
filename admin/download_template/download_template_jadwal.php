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
$sheet->getColumnDimension('D')->setWidth(18);
$sheet->getColumnDimension('E')->setWidth(18);
$sheet->getColumnDimension('F')->setWidth(30);
$sheet->getColumnDimension('G')->setWidth(35);
$sheet->getColumnDimension('H')->setWidth(20);

// Set header row
$headers = ['Tim', 'Topik', 'Judul Kegiatan', 'Tanggal Penugasan', 'Tanggal Rilis', 'Keterangan', 'PIC Data', 'Links'];
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

for ($col = 'A'; $col <= 'H'; $col++) {
    $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
}

// Add example rows (you can customize these)
$exampleData = [
    ['Tim A', 'Sosialisasi', 'Workshop Pelatihan', '2026-02-01', '2026-02-15', 'Kegiatan sosialisasi untuk komunitas', '123456|1,654321|2', '1,2,3'],
    ['Tim B', 'Edukasi', 'Seminar Webinar', '2026-02-05', '2026-02-20', 'Webinar edukasi digital', '111111|1', '1,3'],
    ['Tim C', 'Promosi', 'Kampanye Media Sosial', '2026-02-10', '2026-03-01', 'Kampanye promosi melalui media sosial', '222222|2,333333|3', '2,3'],
];

$row = 2;
foreach ($exampleData as $data) {
    $sheet->fromArray($data, NULL, 'A' . $row);
    // Style data rows
    $dataStyle = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ]
        ]
    ];
    for ($col = 'A'; $col <= 'H'; $col++) {
        $sheet->getStyle($col . $row)->applyFromArray($dataStyle);
    }
    $row++;
}

// Freeze header row
$sheet->freezePane('A2');

// Create writer and output file
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);

// Set headers for download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Template_Import_Jadwal_' . date('Y-m-d') . '.xls"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
