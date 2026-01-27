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

// Get all users data
$qUser = mysqli_query($koneksi, "
    SELECT 
        u.nip,
        u.nama,
        u.email,
        u.status,
        u.nomor_telepon,
        j.nama_jabatan,
        r.nama_role,
        p.nama_ppid
    FROM user u
    LEFT JOIN jabatan j ON u.id_jabatan = j.id_jabatan
    LEFT JOIN role r ON u.id_role = r.id_role
    LEFT JOIN ppid p ON u.id_ppid = p.id_ppid
    ORDER BY u.nama
");
$dataUsers = [];
while ($row = mysqli_fetch_assoc($qUser)) {
    $dataUsers[] = $row;
}

// Get skills for each user
function getUserSkills($koneksi, $nip) {
    $qSkill = mysqli_query($koneksi, "
        SELECT s.nama_skill
        FROM user_skill us
        JOIN skill s ON us.id_skill = s.id_skill
        WHERE us.nip = '" . mysqli_real_escape_string($koneksi, $nip) . "'
        ORDER BY s.nama_skill
    ");
    $skills = [];
    while ($row = mysqli_fetch_assoc($qSkill)) {
        $skills[] = $row['nama_skill'];
    }
    return !empty($skills) ? implode(', ', $skills) : '-';
}

// Get Halo PST for each user
function getUserHaloPST($koneksi, $nip) {
    $qHaloPST = mysqli_query($koneksi, "
        SELECT hp.nama_halo_pst
        FROM user_halo_pst uhp
        JOIN halo_pst hp ON uhp.id_halo_pst = hp.id_halo_pst
        WHERE uhp.nip = '" . mysqli_real_escape_string($koneksi, $nip) . "'
        ORDER BY hp.nama_halo_pst
    ");
    $haloPSTs = [];
    while ($row = mysqli_fetch_assoc($qHaloPST)) {
        $haloPSTs[] = $row['nama_halo_pst'];
    }
    return !empty($haloPSTs) ? implode(', ', $haloPSTs) : '-';
}

// PRINT FORMAT
if ($format == 'print') {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Data User</title>
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
                table { font-size: 11px; }
            }
            body {padding: 40px; }
            h2 { text-align: center; margin-bottom: 20px; margin-top: 20px; }
            .header-info { text-align: center; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
            th { background-color: #007bff; color: white; font-weight: bold; }
            tr:nth-child(even) { background-color: #f9f9f9; }
        </style>
    </head>
    <body>
        <div class="no-print d-flex justify-content-between">
            <button class="btn btn-secondary btn-icon-l" onclick="window.history.back()"><i class="no-print fas fa-arrow-left"></i></button>
            <button class="btn btn-primary btn-icon-l" onclick="window.print()"><i class="no-print fas fa-print"></i></button>
        </div>
        <div class="header-info">
            <h2>Laporan Data User Sistem Kehumasan</h2>
            <h2>Badan Pusat Statistik Bangkalan</h2>
            <p>Tanggal Cetak: <?= date('d-m-Y H:i:s'); ?></p>
            <p>Total User: <?= count($dataUsers); ?></p>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jabatan</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Nomor Telepon</th>
                    <th>PPID</th>
                    <th>Halo PST</th>
                    <th>Skills</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataUsers as $index => $user) : ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= htmlspecialchars($user['nip']); ?></td>
                    <td><?= htmlspecialchars($user['nama']); ?></td>
                    <td><?= htmlspecialchars($user['email']); ?></td>
                    <td><?= htmlspecialchars($user['nama_jabatan'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($user['nama_role'] ?? '-'); ?></td>
                    <td><?= $user['status'] == 1 ? 'Aktif' : 'Tidak Aktif'; ?></td>
                    <td><?= $user['nomor_telepon'] ? '0' . $user['nomor_telepon'] : '-'; ?></td>
                    <td><?= htmlspecialchars($user['nama_ppid']); ?></td>
                    <td><?= htmlspecialchars(getUserHaloPST($koneksi, $user['nip'])); ?></td>
                    <td><?= htmlspecialchars(getUserSkills($koneksi, $user['nip'])); ?></td>
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
// EXCEL FORMAT
else if ($format == 'excel') {
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Data User');
    // Set column widths
    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(25);
    $sheet->getColumnDimension('D')->setWidth(25);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(12);
    $sheet->getColumnDimension('H')->setWidth(15);
    $sheet->getColumnDimension('I')->setWidth(25);
    $sheet->getColumnDimension('J')->setWidth(25);
    $sheet->getColumnDimension('K')->setWidth(30);
    // Add headers
    $headers = ['No', 'NIP', 'Nama', 'Email', 'Jabatan', 'Role', 'Status', 'Nomor Telepon', 'PPID', 'Halo PST', 'Skills'];
    $sheet->fromArray($headers, NULL, 'A1');
    // Style headers
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '007BFF']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
    ];
    $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);
    $sheet->getRowDimension(1)->setRowHeight(25);
    // Add data
    $row = 2;
    foreach ($dataUsers as $index => $user) {
        $sheet->setCellValue("A$row", $index + 1);
        $sheet->setCellValue("B$row", $user['nip']);
        $sheet->setCellValue("C$row", $user['nama']);
        $sheet->setCellValue("D$row", $user['email']);
        $sheet->setCellValue("E$row", $user['nama_jabatan'] ?? '-');
        $sheet->setCellValue("F$row", $user['nama_role'] ?? '-');
        $sheet->setCellValue("G$row", $user['status'] == 1 ? 'Aktif' : 'Tidak Aktif');
        $sheet->setCellValue("H$row", $user['nomor_telepon'] ? '0' . $user['nomor_telepon'] : '-');
        $sheet->setCellValue("I$row", $user['nama_ppid']);
        $sheet->setCellValue("J$row", getUserHaloPST($koneksi, $user['nip']));
        $sheet->setCellValue("K$row", getUserSkills($koneksi, $user['nip']));
        $row++;
    }
    // Add summary
    $row += 2;
    $sheet->setCellValue("A$row", "Total User:");
    $sheet->setCellValue("B$row", count($dataUsers));
    $sheet->getStyle("A$row")->getFont()->setBold(true);
    $sheet->getStyle("B$row")->getFont()->setBold(true);
    $filename = 'Laporan_User_' . date('Y-m-d_H-i-s') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}
// JSON FORMAT
else if ($format == 'json') {
    $jsonData = [];
    
    foreach ($dataUsers as $user) {
        $jsonData[] = [
            'nip' => $user['nip'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'jabatan' => $user['nama_jabatan'] ?? null,
            'role' => $user['nama_role'] ?? null,
            'status' => $user['status'] == 1 ? 'Aktif' : 'Tidak Aktif',
            'nomor_telepon' => $user['nomor_telepon'] ? '0' . $user['nomor_telepon'] : null,
            'ppid' => $user['nama_ppid'],
            'halo_pst' => getUserHaloPST($koneksi, $user['nip']),
            'skills' => getUserSkills($koneksi, $user['nip'])
        ];
    }

    $filename = 'Laporan_User_' . date('Y-m-d_H-i-s') . '.json';
    header('Content-Type: application/json');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    
    echo json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
}

// CSV FORMAT
else if ($format == 'csv') {
    $filename = 'Laporan_User_' . date('Y-m-d_H-i-s') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    // Output BOM for Excel to recognize UTF-8
    echo "\xEF\xBB\xBF";

    // Create file handle
    $output = fopen('php://output', 'w');

    // Add headers
    // Detect system locale delimiter
    $delim = (strpos(setlocale(LC_NUMERIC, ''), 'de_') === 0 || 
              strpos(setlocale(LC_NUMERIC, ''), 'fr_') === 0) ? ';' : ',';
    fputcsv($output, ['No', 'NIP', 'Nama', 'Email', 'Jabatan', 'Role', 'Status', 'Nomor Telepon', 'PPID', 'Halo PST', 'Skills'], $delim);

    // Add data
    foreach ($dataUsers as $index => $user) {
        fputcsv($output, [
            $index + 1,
            $user['nip'],
            $user['nama'],
            $user['email'],
            $user['nama_jabatan'] ?? '-',
            $user['nama_role'] ?? '-',
            $user['status'] == 1 ? 'Aktif' : 'Tidak Aktif',
            $user['nomor_telepon'] ? '0' . $user['nomor_telepon'] : '-',
            $user['nama_ppid'],
            getUserHaloPST($koneksi, $user['nip']),
            getUserSkills($koneksi, $user['nip'])
        ], $delim);
    }

    fclose($output);
    exit();
}
?>
