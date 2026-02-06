-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 06, 2026 at 08:40 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_kehumasan`
--

-- --------------------------------------------------------

--
-- Table structure for table `aset`
--

CREATE TABLE `aset` (
  `id_aset` int NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_jenis_aset` int NOT NULL,
  `nip` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `halo_pst`
--

CREATE TABLE `halo_pst` (
  `id_halo_pst` int NOT NULL,
  `nama_halo_pst` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `halo_pst`
--

INSERT INTO `halo_pst` (`id_halo_pst`, `nama_halo_pst`) VALUES
(1, 'Kemiskinan'),
(3, 'Ketenagakerjaan'),
(4, 'Pelayanan Umum'),
(5, 'Pertanian'),
(6, 'Statistik Sektoral'),
(7, 'Pojok Statistik'),
(8, 'Desa Cantik'),
(9, 'Pertumbuhan Ekonomi'),
(10, 'Big Data'),
(11, 'Data Science');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int NOT NULL,
  `nama_jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`) VALUES
(1, 'Kepala BPS Kabupaten Bangkalan'),
(2, 'Statistisi Terampil');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int NOT NULL,
  `tim` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `topik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `judul_kegiatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_penugasan` date NOT NULL,
  `tanggal_rilis` date NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL,
  `dokumentasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reminder_sent` tinyint(1) NOT NULL DEFAULT '0',
  `reminder_sent_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_link`
--

CREATE TABLE `jadwal_link` (
  `id_jadwal_link` int NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_jenis_link` int NOT NULL,
  `id_jadwal` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis`
--

CREATE TABLE `jenis` (
  `id_jenis` int NOT NULL,
  `nama_jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_kategori` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis`
--

INSERT INTO `jenis` (`id_jenis`, `nama_jenis`, `id_kategori`) VALUES
(1, 'Template Medsos', 3),
(2, 'Dokumentasi', 3),
(4, 'Pembinaan Kehumasan', 5),
(5, 'Video Operator', 4),
(6, 'Galeri Foto', 3),
(7, 'Galeri Video', 3),
(8, 'Laporan', 3),
(9, 'Struktur Humas', 2),
(10, 'Brankas Humas', 1),
(11, 'Template OBS Rilis', 4);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_aset`
--

CREATE TABLE `jenis_aset` (
  `id_jenis_aset` int NOT NULL,
  `nama_jenis_aset` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_aset`
--

INSERT INTO `jenis_aset` (`id_jenis_aset`, `nama_jenis_aset`) VALUES
(1, 'Visual'),
(2, 'Lisensi'),
(3, 'Barang');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_link`
--

CREATE TABLE `jenis_link` (
  `id_jenis_link` int NOT NULL,
  `nama_jenis_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_link`
--

INSERT INTO `jenis_link` (`id_jenis_link`, `nama_jenis_link`) VALUES
(1, 'Instagram'),
(2, 'Facebook'),
(3, 'YouTube'),
(4, 'Website');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pic`
--

CREATE TABLE `jenis_pic` (
  `id_jenis_pic` int NOT NULL,
  `nama_jenis_pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_pic`
--

INSERT INTO `jenis_pic` (`id_jenis_pic`, `nama_jenis_pic`) VALUES
(1, 'Narasi'),
(2, 'Medsos'),
(3, 'Design');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Utama'),
(2, 'Ruang Humas'),
(3, 'Sumberdaya Humas'),
(4, 'Kebutuhan Broadcast'),
(5, 'Peningkatan Kapasitas');

-- --------------------------------------------------------

--
-- Table structure for table `link`
--

CREATE TABLE `link` (
  `id_link` int NOT NULL,
  `nama_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id_media` int NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `topik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `link` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_sub_jenis` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `nip` bigint NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto_profil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` int NOT NULL,
  `nomor_telepon` bigint DEFAULT NULL,
  `id_jabatan` int NOT NULL,
  `id_role` int NOT NULL,
  `id_ppid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`nip`, `nama`, `password`, `email`, `foto_profil`, `status`, `nomor_telepon`, `id_jabatan`, `id_role`, `id_ppid`) VALUES
(230411100156, 'Kamila Mulya Fadila', '$2y$10$XBhAyXq1IWlRJhmE0kMpVuD89YOlkcleljbe.QNsuz.kAz9M4DEsS', 'fadilakamila21@gmail.com', '1769412076_230411100156.jpeg', 1, 87722539067, 2, 1, 3),
(230411100184, 'Nadiatul Khoir', '$2y$10$7WSs6w6hCVCb1dTpl0P8A.Igr58BhuWzB6hGaVdyEtdm2F8OaAf8G', 'khoirnadiatul@gmail.com', NULL, 1, 87722539067, 2, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pic`
--

CREATE TABLE `pic` (
  `nip` bigint NOT NULL,
  `id_jadwal` int NOT NULL,
  `id_pic` int NOT NULL,
  `id_jenis_pic` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ppid`
--

CREATE TABLE `ppid` (
  `id_ppid` int NOT NULL,
  `nama_ppid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ppid`
--

INSERT INTO `ppid` (`id_ppid`, `nama_ppid`) VALUES
(1, 'Umum'),
(2, 'Sosial'),
(3, 'Produksi'),
(4, 'Distribusi'),
(5, 'Neraca'),
(6, 'Statistik Sektoral');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` int NOT NULL,
  `nama_role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id_role`, `nama_role`) VALUES
(1, 'Admin'),
(2, 'Pegawai');

-- --------------------------------------------------------

--
-- Table structure for table `skill`
--

CREATE TABLE `skill` (
  `id_skill` int NOT NULL,
  `nama_skill` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`id_skill`, `nama_skill`) VALUES
(1, 'Data Contributor'),
(2, 'Content Creator'),
(5, 'Editor Photo & Layout'),
(6, 'Editor Video'),
(7, 'Photo & Videographer'),
(8, 'Talent'),
(9, 'Project Manager'),
(10, 'Copywriting '),
(11, 'Protokol'),
(12, 'MC'),
(13, ' Operator');

-- --------------------------------------------------------

--
-- Table structure for table `sub_jenis`
--

CREATE TABLE `sub_jenis` (
  `id_sub_jenis` int NOT NULL,
  `nama_sub_jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_jenis` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_jenis`
--

INSERT INTO `sub_jenis` (`id_sub_jenis`, `nama_sub_jenis`, `id_jenis`) VALUES
(1, 'Potrait (4:5)', 1),
(2, 'Reels (9:16)', 1),
(3, 'Landscape (16:9)', 1),
(4, 'Pedoman Visual Medsos BPS', 1),
(5, 'Pembinaan Kehumasan', 4),
(6, 'Video Operator', 5),
(7, 'Template OBS Rilis', 11),
(8, 'Kegiatan BPS Bangkalan', 2),
(9, 'Pendataan Sensus Ekonomi 2026', 2),
(10, 'Pimpinan', 6),
(11, 'Pegawai', 6),
(12, 'Sensus Ekonomi 2026', 6),
(13, 'Gedung Kantor', 6),
(14, 'Landmark Bangkalan', 6),
(15, 'Kantor BPS Bangkalan', 7),
(16, 'Landmark Bangkalan', 7),
(17, 'Sensus Ekonomi 2026', 7),
(18, 'Pemanfaatan Adobe', 8),
(19, 'Konten SE2026', 8),
(20, 'Humas Bulanan', 8),
(21, 'Humas Tahunan', 8),
(22, 'Struktur Humas', 9),
(23, 'Brankas Humas', 10);

-- --------------------------------------------------------

--
-- Table structure for table `user_halo_pst`
--

CREATE TABLE `user_halo_pst` (
  `nip` bigint NOT NULL,
  `id_halo_pst` int NOT NULL,
  `id_user_halo_pst` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_halo_pst`
--

INSERT INTO `user_halo_pst` (`nip`, `id_halo_pst`, `id_user_halo_pst`) VALUES
(230411100184, 11, 32),
(230411100184, 1, 33),
(230411100184, 3, 34),
(230411100156, 1, 35);

-- --------------------------------------------------------

--
-- Table structure for table `user_skill`
--

CREATE TABLE `user_skill` (
  `nip` bigint NOT NULL,
  `id_skill` int NOT NULL,
  `id_user_skill` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_skill`
--

INSERT INTO `user_skill` (`nip`, `id_skill`, `id_user_skill`) VALUES
(230411100184, 2, 30),
(230411100184, 1, 31),
(230411100156, 1, 32);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aset`
--
ALTER TABLE `aset`
  ADD PRIMARY KEY (`id_aset`),
  ADD KEY `jenis` (`id_jenis_aset`),
  ADD KEY `aset_ibfk_1` (`nip`);

--
-- Indexes for table `halo_pst`
--
ALTER TABLE `halo_pst`
  ADD PRIMARY KEY (`id_halo_pst`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `jadwal_link`
--
ALTER TABLE `jadwal_link`
  ADD PRIMARY KEY (`id_jadwal_link`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `id_jenis_link` (`id_jenis_link`);

--
-- Indexes for table `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`id_jenis`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `jenis_aset`
--
ALTER TABLE `jenis_aset`
  ADD PRIMARY KEY (`id_jenis_aset`);

--
-- Indexes for table `jenis_link`
--
ALTER TABLE `jenis_link`
  ADD PRIMARY KEY (`id_jenis_link`);

--
-- Indexes for table `jenis_pic`
--
ALTER TABLE `jenis_pic`
  ADD PRIMARY KEY (`id_jenis_pic`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `link`
--
ALTER TABLE `link`
  ADD PRIMARY KEY (`id_link`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id_media`),
  ADD KEY `id_sub_jenis` (`id_sub_jenis`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`nip`),
  ADD KEY `menjabat` (`id_jabatan`),
  ADD KEY `sebagai` (`id_role`),
  ADD KEY `bagian` (`id_ppid`);

--
-- Indexes for table `pic`
--
ALTER TABLE `pic`
  ADD PRIMARY KEY (`id_pic`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `nip` (`nip`),
  ADD KEY `id_jenis_pic` (`id_jenis_pic`);

--
-- Indexes for table `ppid`
--
ALTER TABLE `ppid`
  ADD PRIMARY KEY (`id_ppid`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`id_skill`);

--
-- Indexes for table `sub_jenis`
--
ALTER TABLE `sub_jenis`
  ADD PRIMARY KEY (`id_sub_jenis`),
  ADD KEY `id_jenis` (`id_jenis`);

--
-- Indexes for table `user_halo_pst`
--
ALTER TABLE `user_halo_pst`
  ADD PRIMARY KEY (`id_user_halo_pst`),
  ADD KEY `id_halo_pst` (`id_halo_pst`),
  ADD KEY `nip` (`nip`);

--
-- Indexes for table `user_skill`
--
ALTER TABLE `user_skill`
  ADD PRIMARY KEY (`id_user_skill`),
  ADD KEY `nip` (`nip`),
  ADD KEY `id_skill` (`id_skill`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aset`
--
ALTER TABLE `aset`
  MODIFY `id_aset` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `halo_pst`
--
ALTER TABLE `halo_pst`
  MODIFY `id_halo_pst` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `jadwal_link`
--
ALTER TABLE `jadwal_link`
  MODIFY `id_jadwal_link` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `jenis`
--
ALTER TABLE `jenis`
  MODIFY `id_jenis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jenis_aset`
--
ALTER TABLE `jenis_aset`
  MODIFY `id_jenis_aset` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jenis_link`
--
ALTER TABLE `jenis_link`
  MODIFY `id_jenis_link` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jenis_pic`
--
ALTER TABLE `jenis_pic`
  MODIFY `id_jenis_pic` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `link`
--
ALTER TABLE `link`
  MODIFY `id_link` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id_media` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `pic`
--
ALTER TABLE `pic`
  MODIFY `id_pic` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `ppid`
--
ALTER TABLE `ppid`
  MODIFY `id_ppid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skill`
--
ALTER TABLE `skill`
  MODIFY `id_skill` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sub_jenis`
--
ALTER TABLE `sub_jenis`
  MODIFY `id_sub_jenis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_halo_pst`
--
ALTER TABLE `user_halo_pst`
  MODIFY `id_user_halo_pst` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `user_skill`
--
ALTER TABLE `user_skill`
  MODIFY `id_user_skill` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aset`
--
ALTER TABLE `aset`
  ADD CONSTRAINT `aset_ibfk_1` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jenis` FOREIGN KEY (`id_jenis_aset`) REFERENCES `jenis_aset` (`id_jenis_aset`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jadwal_link`
--
ALTER TABLE `jadwal_link`
  ADD CONSTRAINT `jadwal_link_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_link_ibfk_2` FOREIGN KEY (`id_jenis_link`) REFERENCES `jenis_link` (`id_jenis_link`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jenis`
--
ALTER TABLE `jenis`
  ADD CONSTRAINT `jenis_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`id_sub_jenis`) REFERENCES `sub_jenis` (`id_sub_jenis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `bagian` FOREIGN KEY (`id_ppid`) REFERENCES `ppid` (`id_ppid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `menjabat` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sebagai` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pic`
--
ALTER TABLE `pic`
  ADD CONSTRAINT `pic_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pic_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pic_ibfk_3` FOREIGN KEY (`id_jenis_pic`) REFERENCES `jenis_pic` (`id_jenis_pic`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_jenis`
--
ALTER TABLE `sub_jenis`
  ADD CONSTRAINT `sub_jenis_ibfk_1` FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_halo_pst`
--
ALTER TABLE `user_halo_pst`
  ADD CONSTRAINT `user_halo_pst_ibfk_1` FOREIGN KEY (`id_halo_pst`) REFERENCES `halo_pst` (`id_halo_pst`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_halo_pst_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_skill`
--
ALTER TABLE `user_skill`
  ADD CONSTRAINT `user_skill_ibfk_1` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_skill_ibfk_2` FOREIGN KEY (`id_skill`) REFERENCES `skill` (`id_skill`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
