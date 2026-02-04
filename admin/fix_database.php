<?php
include '../koneksi.php';

echo "=== FIXING DATABASE STRUCTURE ===\n\n";

// Disable foreign key checks
mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS=0");

// Drop tables in correct order (child first, then parent)
mysqli_query($koneksi, "DROP TABLE IF EXISTS `sub_jenis`");
mysqli_query($koneksi, "DROP TABLE IF EXISTS `jenis`");
mysqli_query($koneksi, "DROP TABLE IF EXISTS `kategori`");

// Create kategori table (parent)
$sql1 = "CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if (mysqli_query($koneksi, $sql1)) {
    echo "✓ Tabel 'kategori' berhasil dibuat\n";
} else {
    echo "✗ Error kategori: " . mysqli_error($koneksi) . "\n";
}

// Create jenis table (child of kategori)
$sql2 = "CREATE TABLE `jenis` (
  `id_jenis` int NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_kategori` int NOT NULL,
  PRIMARY KEY (`id_jenis`),
  KEY `id_kategori` (`id_kategori`),
  CONSTRAINT `jenis_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if (mysqli_query($koneksi, $sql2)) {
    echo "✓ Tabel 'jenis' berhasil dibuat dengan id_kategori FK\n";
} else {
    echo "✗ Error jenis: " . mysqli_error($koneksi) . "\n";
}

// Create sub_jenis table (child of jenis)
$sql3 = "CREATE TABLE `sub_jenis` (
  `id_sub_jenis` int NOT NULL AUTO_INCREMENT,
  `nama_sub_jenis` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_jenis` int NOT NULL,
  PRIMARY KEY (`id_sub_jenis`),
  KEY `id_jenis` (`id_jenis`),
  CONSTRAINT `sub_jenis_ibfk_1` FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if (mysqli_query($koneksi, $sql3)) {
    echo "✓ Tabel 'sub_jenis' berhasil dibuat\n";
} else {
    echo "✗ Error sub_jenis: " . mysqli_error($koneksi) . "\n";
}

// Re-enable foreign key checks
mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS=1");

// Verify structure
echo "\n=== VERIFYING TABLE STRUCTURES ===\n\n";

echo "Struktur tabel kategori:\n";
$result = mysqli_query($koneksi, 'DESCRIBE kategori');
while($row = mysqli_fetch_assoc($result)) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\nStruktur tabel jenis:\n";
$result = mysqli_query($koneksi, 'DESCRIBE jenis');
while($row = mysqli_fetch_assoc($result)) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\nStruktur tabel sub_jenis:\n";
$result = mysqli_query($koneksi, 'DESCRIBE sub_jenis');
while($row = mysqli_fetch_assoc($result)) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\n✅ Database structure fixed!";
?>
