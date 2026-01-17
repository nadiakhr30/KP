<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

require __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Fungsi konversi tanggal Excel ke YYYY-MM-DD
function excelDateToDate($value) {
    if (is_numeric($value)) {
        // Excel serial date ke UNIX timestamp
        $unixDate = ($value - 25569) * 86400;
        return gmdate("Y-m-d", $unixDate);
    }
    return $value; // sudah string
}

// Validasi file upload
if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] != 0) {
    die('File Excel tidak valid');
}

$ext = pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION);
if (strtolower($ext) !== 'xlsx') {
    die('File harus berformat .xlsx');
}

$fileTmp = $_FILES['file_excel']['tmp_name'];

$spreadsheet = IOFactory::load($fileTmp);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

$berhasil = 0;

// Loop mulai dari baris ke-2 (index 1) → header aman
for ($i = 1; $i < count($rows); $i++) {
    $row = $rows[$i];

    $tim               = trim($row[0] ?? '');
    $topik             = trim($row[1] ?? '');
    $judul_kegiatan    = trim($row[2] ?? '');
    $tanggal_penugasan = excelDateToDate(trim($row[3] ?? ''));
    $target_rilis      = excelDateToDate(trim($row[4] ?? ''));
    $keterangan        = trim($row[5] ?? '');

    // PIC default NULL, nanti diisi admin
    $pic_desain = "NULL";
    $pic_narasi = "NULL";
    $pic_medsos = "NULL";

    // Validasi kolom wajib
    if ($topik === '' || $judul_kegiatan === '' || $target_rilis === '') {
        continue; // lewati baris
    }

    // Validasi tanggal format YYYY-MM-DD
    if ($tanggal_penugasan !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_penugasan)) {
        continue;
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $target_rilis)) {
        continue;
    }

    // Default lain
    $status          = 0;
    $dokumentasi     = '';
    $link_instagram  = '';
    $link_facebook   = '';
    $link_youtube    = '';
    $link_website    = '';

    // Query langsung tanpa bind_param
    $sql = "INSERT INTO jadwal (
        tim, topik, judul_kegiatan,
        tanggal_penugasan, target_rilis, keterangan,
        pic_desain, pic_narasi, pic_medsos,
        status, dokumentasi, link_instagram, link_facebook, link_youtube, link_website
    ) VALUES (
        '$tim',
        '$topik',
        '$judul_kegiatan',
        '$tanggal_penugasan',
        '$target_rilis',
        '$keterangan',
        $pic_desain,
        $pic_narasi,
        $pic_medsos,
        '$status',
        '$dokumentasi',
        '$link_instagram',
        '$link_facebook',
        '$link_youtube',
        '$link_website'
    )";

    if (mysqli_query($koneksi, $sql)) {
        $berhasil++;
    }
}

$_SESSION['success'] = "Berhasil import $berhasil jadwal";
header('Location: viewjadwal.php');
exit;
