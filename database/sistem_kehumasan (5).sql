-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 05, 2026 at 08:54 AM
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

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `tim`, `topik`, `judul_kegiatan`, `tanggal_penugasan`, `tanggal_rilis`, `keterangan`, `status`, `dokumentasi`, `reminder_sent`, `reminder_sent_at`) VALUES
(1, 'PPID', 'Jumat Berkah', 'Acara Jumat Berkah Bulan Agustus', '2026-01-20', '2026-01-23', 'Kasih banyak MBG yah', 0, NULL, 0, NULL),
(2, 'PPID', 'Jumat Berkah', 'Acara Jumat Berkah Bulan Agustus', '2026-01-21', '2026-01-21', 'aaaaaaaaaaaaaaaa', 0, NULL, 0, NULL),
(3, 'Ya', 'Ya', 'Ya', '2026-01-15', '2026-01-31', 'Ya', 0, NULL, 0, NULL),
(4, 'Ya2', 'Ya2', 'Ya2', '2026-01-28', '2026-01-31', 'Ya2', 2, 'https://drive.google.com/file/d/1qn1jZ0NG5m6EarnnC-tdqnu0JVga76tF/view?usp=sharing', 0, NULL),
(17, 'ayayaya', 'sensus', 'Diseminasi dan Pelayanan PEKPPP', '2026-02-04', '2026-02-06', 'aaaa', 2, 'https://docs.google.com/spreadsheets/d/1Ko-VHKp-mwN4w-BqJt0OyjBAcqoQa4imQJ01xTM4now/edit?gid=704059312#gid=704059312', 0, NULL),
(18, 'Humas', 'Hari Berkah', 'Barokah', '2026-02-04', '2026-02-10', 'dddd', 0, NULL, 0, NULL),
(19, 'Humas', 'Hari Berkah', 'Barokah', '2026-02-04', '2026-02-10', 'dddd', 0, NULL, 0, NULL),
(21, 'ayayaya', 'oii', 'Ya2', '2026-02-04', '2026-02-05', 'aaa', 0, NULL, 0, NULL),
(22, 'Pengolahan dan Layanan Statistik', 'sensus', 'adalah pokoknya', '2026-02-04', '2026-02-08', 'aaa', 0, NULL, 0, NULL),
(23, 'vbj', 'sensus', 'asss', '2026-02-04', '2026-02-11', 'aaaa', 0, NULL, 0, NULL),
(24, 'aaaa', 'awww', 'not found', '2026-02-05', '2026-02-06', 'aaaa', 0, NULL, 0, NULL),
(25, 'sss', 'sensus', 'adalah pokoknya', '2026-02-05', '2026-02-06', 'aaa', 0, NULL, 0, NULL);

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

--
-- Dumping data for table `jadwal_link`
--

INSERT INTO `jadwal_link` (`id_jadwal_link`, `link`, `id_jenis_link`, `id_jadwal`) VALUES
(5, 'https://www.instagram.com/', 1, 4),
(6, 'https://www.instagram.com/', 2, 4),
(43, 'https://www.instagram.com/', 1, 17),
(44, 'https://www.instagram.com/', 2, 17),
(45, 'https://www.instagram.com/', 4, 17),
(46, NULL, 3, 18),
(47, NULL, 4, 18),
(48, NULL, 3, 19),
(49, NULL, 4, 19),
(52, NULL, 2, 21),
(53, NULL, 4, 21),
(54, NULL, 1, 22),
(55, NULL, 1, 23),
(56, NULL, 1, 24),
(57, NULL, 4, 24),
(58, NULL, 1, 25),
(59, NULL, 2, 25);

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
(1, 'Visual');

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

--
-- Dumping data for table `link`
--

INSERT INTO `link` (`id_link`, `nama_link`, `gambar`, `link`) VALUES
(8, 'BPS Sampang', '', 'https://bangkalankab.bps.go.id/id');

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

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id_media`, `judul`, `topik`, `deskripsi`, `link`, `created_at`, `id_sub_jenis`) VALUES
(1, 'ayyaya', 'oii', 'wihhh', 'https://bangkalankab.bps.go.id/id', '2026-01-29 07:19:34', 5),
(2, 'ayoyo', 'Hari Berkah', 'intinya itu lah', 'https://youtu.be/0ULADG6Iq-s?si=wXrTld-ykTSCeoGA', '2026-01-30 06:57:38', 5),
(4, 'ahh malas', 'malass', 'oiii', 'https://drive.google.com/drive/u/0/folders/1IqVL27bOUIIqJqOGc-nAMSy3cjQwNJIk', '2026-01-30 07:26:43', 3),
(12, 'sensus 2026', 'sensus', 'aaa', 'https://drive.google.com/drive/folders/1zePa7oyrxVY4cSsKPIfI4gfR1TX7NdHf?usp=sharing', '2026-02-03 03:15:43', 8),
(13, 'pendataan sensus', '2026', 'www', 'https://drive.google.com/drive/folders/1iForWwZ54uUgvTPPCGZHznTSmqwONbm-?usp=sharing', '2026-02-01 03:35:56', 9),
(15, 'landmark bangkalan', 'landmark bangkalan', 'aaaa', 'https://drive.google.com/drive/folders/1P3FWTYgH54fqwgOdaOtoTXB1okSkR6RA?usp=sharing', '2026-02-01 06:46:32', 14),
(16, 'Gedung kantor', 'Gedung', 'aaa', 'https://drive.google.com/drive/folders/1EJAun7zsJhfjXi7Iue9bcwFMWNjHRdOg?usp=sharing', '2026-02-01 07:17:20', 13),
(17, 'Sensus Ekonomi 2026', 'sensus', 'aaa', 'https://drive.google.com/drive/folders/1DMC9g_cYH9kQAPFMuEPscrl1sooykICT?usp=sharing', '2026-02-01 07:18:07', 12),
(18, 'Pegawai', 'Pegawai', 'aaa', 'https://drive.google.com/drive/folders/10kDigwyu6FhbkT0EDhK06OOfIyuWS2fm?usp=sharing', '2026-02-01 07:18:51', 11),
(19, 'Pimpinan', 'Pak Insaf', 'aaa', 'https://drive.google.com/drive/folders/17qHv-slqvUHwaxqCWHqXU3eNISoWDljU?usp=sharing', '2026-02-01 07:19:54', 12),
(20, 'capeknya', 'capek', 'aaa', 'https://drive.google.com/drive/folders/1DMC9g_cYH9kQAPFMuEPscrl1sooykICT?usp=sharing', '2026-02-01 13:00:35', 12),
(21, 'ihhh', 'capek', 'aaa', 'https://drive.google.com/drive/folders/1DMC9g_cYH9kQAPFMuEPscrl1sooykICT?usp=sharing', '2026-02-01 13:08:13', 12),
(22, 'capeknya', 'sensus', 'qqq', 'https://drive.google.com/drive/folders/1DPS7SXg0RKjgmcJ2orELz3-eEdW-TcUW?usp=sharing', '2026-02-01 14:39:46', 17),
(23, 'uiiii', 'sensus', 'qqq', 'https://drive.google.com/drive/folders/1DPS7SXg0RKjgmcJ2orELz3-eEdW-TcUW?usp=sharing', '2026-02-01 14:54:10', 17),
(24, 'asikkk', 'capek', 'aaaa', 'https://drive.google.com/drive/folders/1RcCoLxXfsNqGXJc2dEiyCbK_pn7oQYNr?usp=sharing', '2026-02-01 17:09:06', 21),
(25, 'aaa', 'adlah pokoknya', 'aaaa', 'https://drive.google.com/drive/folders/1k5WKGHiKni2VXQPgVoOUCiG2zSuKHQ63?usp=sharing', '2026-02-02 03:57:17', 6),
(26, 'aaaa', 'ayyy', 'awwww', 'https://drive.google.com/drive/folders/1YfxK8VSypHrnnFWGrAvVNtio8JlbiJxB?usp=sharing', '2026-02-02 04:41:44', 7),
(27, 'Organigram Humas', 'Organigram', 'BPS Bangkalan', '<div style=\"position: relative; width: 100%; height: 0; padding-top: 100.0000%;  padding-bottom: 0; box-shadow: 0 2px 8px 0 rgba(63,69,81,0.16); margin-top: 1.6em; margin-bottom: 0.9em; overflow: hidden;  border-radius: 8px; will-change: transform;\">   <iframe loading=\"lazy\" style=\"position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none; padding: 0;margin: 0;\"     src=\"https://www.canva.com/design/DAG_ftpWi2k/KOCL7HumwJahW8SKt23LTw/view?embed\" allowfullscreen=\"allowfullscreen\" allow=\"fullscreen\">   </iframe> </div> <a href=\"https:&#x2F;&#x2F;www.canva.com&#x2F;design&#x2F;DAG_ftpWi2k&#x2F;KOCL7HumwJahW8SKt23LTw&#x2F;view?utm_content=DAG_ftpWi2k&amp;utm_campaign=designshare&amp;utm_medium=embeds&amp;utm_source=link\" target=\"_blank\" rel=\"noopener\">struktur humas</a> oleh Nadiatul Khoir', '2026-02-02 07:01:41', 22),
(28, 'nadia', 'udah capek', 'aaaa', 'https://drive.google.com/drive/folders/1WWx2mtsOU6_PitIFzZd8oqTqv2EqI-qZ', '2026-02-02 07:50:49', 23),
(29, 'aaa', 'aaa', 'aaa', 'https://drive.google.com/drive/folders/17ZJUa8nX_-G5HLYaLU-hrzxt1BjW3ebE?usp=sharing', '2026-02-03 04:13:29', 15),
(30, 'aaa', 'aaa', 'aaa', 'https://drive.google.com/drive/folders/1XH_L1GUhQXs313dd6a9Z2qpJfjzdaz8k?usp=sharing', '2026-02-03 04:16:10', 20),
(31, 'aaa', 'sensus', 'aaa', 'https://drive.google.com/drive/folders/1XH_L1GUhQXs313dd6a9Z2qpJfjzdaz8k?usp=sharing', '2026-02-04 06:46:44', 20);

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
(19920410, 'Ahmad Wijaya', '$2y$10$rRoUya61NE5wJ1AiOK7gH.edIno7X7LS/sAjCHYn85wLdTc8HnEnK', 'ahmad@bps.go.id', NULL, 1, 83456789012, 2, 2, 2),
(230411100156, 'Kamila Mulya Fadila', '$2y$10$XBhAyXq1IWlRJhmE0kMpVuD89YOlkcleljbe.QNsuz.kAz9M4DEsS', 'fadilakamila21@gmail.com', '1769412076_230411100156.jpeg', 1, 87722539067, 2, 1, 3),
(230411100157, 'Aliya Zulfa Syafitri', '$2y$10$8t0wzZE1uV3DMs6RV.AiOOlhY5IQ5ccnPTdTA9oKwHWNR99UKabne', 'aliyazulfa123@gmail.com', NULL, 1, NULL, 2, 2, 1),
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

--
-- Dumping data for table `pic`
--

INSERT INTO `pic` (`nip`, `id_jadwal`, `id_pic`, `id_jenis_pic`) VALUES
(19920410, 3, 8, 1),
(19920410, 3, 9, 2),
(19920410, 3, 10, 3),
(230411100184, 4, 24, 1),
(230411100184, 4, 25, 2),
(230411100156, 4, 26, 3),
(230411100184, 17, 64, 1),
(230411100184, 17, 65, 2),
(230411100184, 17, 66, 3),
(230411100184, 18, 67, 1),
(19920410, 18, 68, 2),
(230411100184, 18, 69, 3),
(230411100184, 19, 70, 1),
(19920410, 19, 71, 2),
(230411100184, 19, 72, 3),
(230411100184, 21, 76, 1),
(230411100184, 21, 77, 2),
(230411100184, 21, 78, 3),
(230411100184, 22, 79, 1),
(230411100184, 22, 80, 2),
(230411100184, 22, 81, 3),
(230411100184, 23, 82, 1),
(230411100184, 23, 83, 2),
(230411100184, 23, 84, 3),
(230411100156, 24, 85, 1),
(230411100184, 24, 86, 2),
(230411100184, 24, 87, 3),
(230411100184, 25, 88, 1),
(230411100184, 25, 89, 2),
(230411100184, 25, 90, 3);

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
(2, 'Pegawai'),
(3, 'Developer');

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
(230411100157, 3, 23),
(19920410, 1, 29),
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
(19920410, 2, 27),
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
  MODIFY `id_aset` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_jenis_aset` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id_media` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
  MODIFY `id_user_halo_pst` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user_skill`
--
ALTER TABLE `user_skill`
  MODIFY `id_user_skill` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
