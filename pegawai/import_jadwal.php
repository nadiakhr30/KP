<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

// Cek login
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Import Jadwal Kalender</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body {
  font-family: Poppins, sans-serif;
  background: #f4f7fb;
  margin: 0;
  padding: 40px;
}

.page-wrapper {
  max-width: 800px;
  margin: auto;
}

.page-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 25px;
  color: #1f3c88;
}

.page-header i {
  font-size: 20px;
}

.card {
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 12px 30px rgba(0,0,0,.08);
  padding: 30px;
}

.card-title {
  font-size: 22px;
  font-weight: 700;
  color: #1f3c88;
  margin-bottom: 20px;
}

.import-box {
  border: 2px dashed #cfd8ff;
  border-radius: 12px;
  padding: 30px;
  text-align: center;
  background: #f9fbff;
}

.import-box i {
  font-size: 48px;
  color: #1f3c88;
  margin-bottom: 15px;
}

.import-box p {
  margin: 10px 0 20px;
  color: #555;
}

input[type=file] {
  display: block;
  margin: 0 auto 20px;
}

.btn-group {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 25px;
}

.btn {
  padding: 12px 22px;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  color: #fff;
  border: none;
  cursor: pointer;
}

.btn-import { background: #1e6cff; }
.btn-back { background: #6c757d; }

.note {
  margin-top: 20px;
  font-size: 13px;
  color: #666;
}
.note ul {
  text-align: left;
  display: inline-block;
  margin-top: 10px;
}
</style>
</head>
<body>

<div class="page-wrapper">

  <!-- HEADER -->
  <div class="page-header">
    <a href="viewjadwal.php" title="Kalender">
      <i class="fas fa-calendar-alt"></i>
    </a>
    <span>› Import Jadwal</span>
  </div>

  <!-- CARD -->
  <div class="card">
    <div class="card-title">Import Jadwal dari Excel</div>

    <!-- FORM -->
    <form action="proses_import_jadwal.php" method="POST" enctype="multipart/form-data">

      <div class="import-box">
        <i class="fas fa-file-excel"></i>
        <p>Upload file Excel (.xlsx) untuk menambahkan jadwal sekaligus</p>
        <input type="file" name="file_excel" accept=".xlsx" required>
      </div>

      <div class="note">
        <strong>Catatan:</strong>
        <ul>
          <li>Format tanggal: <b>YYYY-MM-DD</b></li>
          <li>Kolom wajib: <b>topik, judul_kegiatan, target_rilis</b></li>
          <li>PIC diisi dengan <b>ID User</b></li>
        </ul>
      </div>

      <div class="btn-group">
        <button type="submit" class="btn btn-import">
          <i class="fas fa-upload"></i> Import Data
        </button>
      </div>

    </form>
    <!-- END FORM -->

  </div>

</div>

</body>
</html>
